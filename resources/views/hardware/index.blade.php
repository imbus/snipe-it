@extends('layouts/default')

@section('title0')

  @php
      $requestStatusType = request()->input('status_type', request()->input('status'));
      $requestOrderNumber = request()->input('order_number');
      $requestCompanyId = request()->input('company_id');
      $requestStatusTypeId = request()->input('status_id');
  @endphp

  @if (is_scalar($requestCompanyId) && ($company instanceof \App\Models\Company))
    {{ $company->name }}
  @endif



  @if ($requestStatusType)
      @if ($requestStatusType=='Pending')
    {{ trans('general.pending') }}
      @elseif ($requestStatusType=='RTD')
    {{ trans('general.ready_to_deploy') }}
      @elseif ($requestStatusType=='Deployed')
    {{ trans('general.deployed') }}
      @elseif ($requestStatusType=='Undeployable')
    {{ trans('general.undeployable') }}
      @elseif ($requestStatusType=='Deployable')
    {{ trans('general.deployed') }}
      @elseif ($requestStatusType=='Requestable')
    {{ trans('admin/hardware/general.requestable') }}
      @elseif ($requestStatusType=='Archived')
    {{ trans('general.archived') }}
      @elseif ($requestStatusType=='Deleted')
    {{ ucfirst(trans('general.deleted')) }}
      @elseif ($requestStatusType=='byod')
    {{ strtoupper(trans('general.byod')) }}
  @endif
@else
{{ trans('general.all') }}
@endif
{{ trans('general.assets') }}

  @if (Request::has('order_number') && is_scalar($requestOrderNumber))
    : Order #{{ strval($requestOrderNumber) }}
  @endif
@stop

{{-- Page title --}}
@section('title')
@yield('title0')  @parent
@stop


{{-- Page content --}}
@section('content')
<div class="responsive-layout">
  <!-- Filter Section -->
  <div class="filter-section hide" id="filterSection">
    @include('partials.advanced-search.advanced-search', [
      'predefined_filter_id' => $predefined_filter_id,
    ])
  </div>

    <!-- Table Section -->
    <div class="table-section">
        <div class="box">
            <div class="box-body">
                @include('partials.asset-bulk-actions', [
                    'status' => is_scalar($requestStatusType) ? $requestStatusType : null,
                    'showFiltersTogglebutton' => $advanced_search_permission,
                ])

                <table
                data-columns="{{ \App\Presenters\AssetPresenter::dataTableLayout() }}"
                data-cookie-id-table="{{ is_scalar($requestStatusType) ? e($requestStatusType) : '' }}assetsListingTable"
                data-id-table="{{ is_scalar($requestStatusType) ? e($requestStatusType) : '' }}assetsListingTable"
                data-side-pagination="server"
                data-show-footer="true"
                data-sort-order="asc"
                data-sort-name="name"
                data-search-text="{{ session()->get('search') }}"
                data-show-columns-search="true"
                data-toolbar="#assetsBulkEditToolbar"
                data-bulk-button-id="#bulkAssetEditButton"
                data-bulk-form-id="#assetsBulkForm"
                data-buttons="assetButtons"
                id="{{ is_scalar($requestStatusType) ? e($requestStatusType) : '' }}assetsListingTable"
                class="table table-striped snipe-table"
                data-url="{{ route('api.assets.index',
                    array(
                        'status_type' => is_scalar($requestStatusType) ? $requestStatusType : null,
                        'order_number' => is_scalar($requestOrderNumber) ? strval($requestOrderNumber) : null,
                        'company_id' => is_scalar($requestCompanyId) ? $requestCompanyId : null,
                        'status_id' => is_scalar($requestStatusTypeId) ? $requestStatusTypeId : null,
                    )) }}"
                data-export-options='{
                "fileName": "export{{ $requestStatusType ? '-'.str_slug($requestStatusType) : '' }}-assets-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'>
              </table>
            </div>

        </div>
    </div>

    @if($advanced_search_permission)
        <livewire:partials.advancedSearch.modal />
    @endif

</div>

<link rel="stylesheet" href="{{ mix('css/dist/advanced-search-index.min.css') }}">

@if($advanced_search_permission)
    <script type="module" src="{{ mix('js/dist/advanced-search-index.min.js') }}"></script>
@endif
@stop

@section('moar_scripts')
@include('partials.bootstrap-table')
@stop
