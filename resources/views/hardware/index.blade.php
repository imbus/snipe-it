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

                            <div class="container">
                                <div class="panel panel-default">
                                    <div class="panel-heading" data-toggle="collapse" data-target="#collapsePanel"
                                        aria-expanded="false" aria-controls="collapsePanel">
                                        <span class="panel-title" style="margin: 0;">
                                            <i class="fas fa-search"></i>
                                            Advanced search
                                        </span>
                                    </div>
                                    <div id="collapsePanel" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <span id="advancedSearchFilters">
                                                @php
                                                    $layoutJson = \App\Presenters\AssetPresenter::dataTableLayout();
                                                    $layout = json_decode($layoutJson); // decode to object by default
                                                    dump($layout);
                                                @endphp

                                                @foreach ($layout as $tableField)
                                                    @if (!empty($tableField->searchable) && $tableField->searchable === true)
                                                        <div id="advancedSearch_{{ $tableField->field }}"
                                                            class="advancedSearchItemContainer">
                                                            <b>{{ $tableField->title }}</b>

                                                            @isset($tableField->formatter)
                                                                @switch($tableField->formatter)
                                                                    @case('categoriesLinkObjFormatter')
                                                                    @case('companiesLinkObjFormatter')
                                                                    @case('dateDisplayFormatter')
                                                                    
                                                                    @break
                                                                    @case('deployedLocationFormatter')
                                                                    @case('employeeNumFormatter')
                                                                    @case('hardwareLinkFormatter')
                                                                    @case('imageFormatter')
                                                                    @case('jobtitleFormatter')
                                                                    @case('manufacturersLinkObjFormatter')
                                                                    @case('modelsLinkObjFormatter')
                                                                    @case('orderNumberObjFilterFormatter')
                                                                    @case('polymorphicItemFormatter')
                                                                    @case('statuslabelsLinkObjFormatter')
                                                                    @case('suppliersLinkObjFormatter')
                                                                    @case('trueFalseFormatter')
                                                                    @case('usersLinkObjFormatter')
                                                                    @break

                                                                    @default
                                                                        <select class="form-control select2"
                                                                            data-endpoint="{{ $tableField->field }}"
                                                                            name="{{ $tableField->title }}" style="width: 100%"
                                                                            id="advancedSearchSelect_{{ $tableField->field }}"></select>
                                                                @endswitch
                                                            @endisset
                                                        </div>
                                                    @endif
                                                @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--<input type="text" id="externalSearchInput" placeholder="Search assets..."
                                                    class="form-control" style="width: 300px; display: inline-block;">
                                                <button id="searchButton" class="btn btn-primary">Search</button>-->


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
        const tableId =
            "{{ request()->has('status') ? e(request()->input('status')) : '' }}assetsListingTable";
        const table = document.getElementById(tableId);
        const searchInput = document.getElementById('externalSearchInput');
        const searchButton = document.getElementById('searchButton');

        // Helper to refresh the table with a new search term
        function refreshTableWithSearch(term) {
            $('#{{ request()->has('status') ? e(request()->input('status')) : '' }}assetsListingTable')
                .bootstrapTable('refresh', {
                    query: {
                        search: term
                    }
                });
        }

        // Button click event
        searchButton.addEventListener('click', function() {
            const term = searchInput.value.trim();
            refreshTableWithSearch(term);
        });

        // Trigger on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const term = searchInput.value.trim();
                refreshTableWithSearch(term);
            }
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

</style>
