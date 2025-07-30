@extends('layouts/default')

@section('title0')

    @if (Request::get('company_id') && $company)
        {{ $company->name }}
    @endif



    @if (Request::get('status'))
        @if (Request::get('status') == 'Pending')
            {{ trans('general.pending') }}
        @elseif (Request::get('status') == 'RTD')
            {{ trans('general.ready_to_deploy') }}
        @elseif (Request::get('status') == 'Deployed')
            {{ trans('general.deployed') }}
        @elseif (Request::get('status') == 'Undeployable')
            {{ trans('general.undeployable') }}
        @elseif (Request::get('status') == 'Deployable')
            {{ trans('general.deployed') }}
        @elseif (Request::get('status') == 'Requestable')
            {{ trans('admin/hardware/general.requestable') }}
        @elseif (Request::get('status') == 'Archived')
            {{ trans('general.archived') }}
        @elseif (Request::get('status') == 'Deleted')
            {{ trans('general.deleted') }}
        @elseif (Request::get('status') == 'byod')
            {{ trans('general.byod') }}
        @endif
    @else
        {{ trans('general.all') }}
    @endif
    {{ trans('general.assets') }}

    @if (Request::has('order_number'))
        : Order #{{ strval(Request::get('order_number')) }}
    @endif
@stop

{{-- Page title --}}
@section('title')
    @yield('title0') @parent
@stop

@section('header_right')
    <a href="{{ route('reports/custom') }}" style="margin-right: 5px;" class="btn btn-default">
        {{ trans('admin/hardware/general.custom_export') }}</a>
    @can('create', \App\Models\Asset::class)
        <a href="{{ route('hardware.create') }}" {{ $snipeSettings->shortcuts_enabled == 1 ? 'n' : '' }}
            class="btn btn-primary pull-right"></i> {{ trans('general.create') }}</a>
    @endcan

@stop

{{-- Page content --}}
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12">

                            @include('partials.asset-bulk-actions', ['status' => Request::get('status')])
                            @include('partials.advanced-search')

                            <table data-columns="{{ \App\Presenters\AssetPresenter::dataTableLayout() }}"
                                data-cookie-id-table="{{ request()->has('status') ? e(request()->input('status')) : '' }}assetsListingTable"
                                data-id-table="{{ request()->has('status') ? e(request()->input('status')) : '' }}assetsListingTable"
                                data-search-text="{{ e(Session::get('search')) }}" data-side-pagination="server"
                                data-show-footer="true" data-sort-order="asc" data-sort-name="name"
                                data-toolbar="#assetsBulkEditToolbar" data-bulk-button-id="#bulkAssetEditButton"
                                data-bulk-form-id="#assetsBulkForm"
                                id="{{ request()->has('status') ? e(request()->input('status')) : '' }}assetsListingTable"
                                class="table table-striped snipe-table"
                                data-url="{{ route('api.assets.index', [
                                    'status' => e(Request::get('status')),
                                    'order_number' => e(strval(Request::get('order_number'))),
                                    'company_id' => e(Request::get('company_id')),
                                    'status_id' => e(Request::get('status_id')),
                                ]) }}"
                                data-export-options='{
                "fileName": "export{{ Request::has('status') ? '-' . str_slug(Request::get('status')) : '' }}-assets-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'>
                            </table>
                            </span>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                </div><!-- ./box-body -->
            </div><!-- /.box -->
        </div>
    </div>
@stop

@section('moar_scripts')
    @include('partials.bootstrap-table')

@stop

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Insert the table ID using php
        const tableId =
            "{{ request()->has('status') ? e(request()->input('status')) : '' }}assetsListingTable";
        const $table = $('#' + tableId);

        function collectAdvancedSearchFilters() {
            // Collect all advanced search fields (selects, inputs, etc.)
            const filters = {};
            document.querySelectorAll('[id^="advancedSearchSelect_"]').forEach(function(el) {
                // Use the field name from the id, you may need to adjust this if it changes
                const field = el.id.replace('advancedSearchSelect_', '');
                if (el.value && el.value.trim() !== '') {
                    filters[field] = el.value.trim();
                }
            });
            return filters;
        }

        function refreshTableWithAdvancedFilters() {
            const filters = collectAdvancedSearchFilters();
            $table.bootstrapTable('refresh', {
                query: {
                    filter: JSON.stringify(filters)
                }
            });
        }

        // Trigger search on change
        document.querySelectorAll('[id^="advancedSearchSelect_"]').forEach(function(el) {
            el.addEventListener('change', function() {
                refreshTableWithAdvancedFilters();
            });
        });
    });
</script>

<style>
    <style>.container {
        width: 100%;
        margin: 0 auto;
        padding: 10px;
    }

    .panel-heading {
        cursor: pointer;
    }

    #advancedSearchFilters {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px 20px;
        max-width: 1050px;
        /* 3 columns of ~300px */
        margin: 0 auto;
        justify-content: center;
    }

    .advancedSearchItemContainer {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .advancedSearchItemContainer b {
        margin-bottom: 5px;
    }

    .advancedSearchItemContainer select.form-control {
        width: 100%;
        box-sizing: border-box;
    }
</style>
