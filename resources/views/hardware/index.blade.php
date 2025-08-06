@extends('layouts/default')

@section('title0')

  @if ((Request::get('company_id')) && ($company))
    {{ $company->name }}
  @endif



@if (Request::get('status'))
  @if (Request::get('status')=='Pending')
    {{ trans('general.pending') }}
  @elseif (Request::get('status')=='RTD')
    {{ trans('general.ready_to_deploy') }}
  @elseif (Request::get('status')=='Deployed')
    {{ trans('general.deployed') }}
  @elseif (Request::get('status')=='Undeployable')
    {{ trans('general.undeployable') }}
  @elseif (Request::get('status')=='Deployable')
    {{ trans('general.deployed') }}
  @elseif (Request::get('status')=='Requestable')
    {{ trans('admin/hardware/general.requestable') }}
  @elseif (Request::get('status')=='Archived')
    {{ trans('general.archived') }}
  @elseif (Request::get('status')=='Deleted')
    {{ ucfirst(trans('general.deleted')) }}
  @elseif (Request::get('status')=='byod')
    {{ strtoupper(trans('general.byod')) }}
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
@yield('title0')  @parent
@stop


{{-- Page content --}}
@section('content')

<div class="form-group">
<label for="templates">{{ trans('admin/reports/general.open_saved_template') }}</label>
<select
    id="templates"
    class="form-control select"
    data-placeholder="{{ trans('admin/reports/general.select_a_template') }}"
>
    <option></option>
    @foreach($report_templates as $template)
        <option
            value="{{ $template->id }}"
            @selected($template->is(request()->route()->parameter('reportTemplate')))
        >
            {{ $template->name }}
        </option>
    @endforeach
</select>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-body">

          <div class="row">
            <div class="col-md-12">

                @include('partials.asset-bulk-actions', ['status' => Request::get('status')])

              <table
                data-columns="{{ \App\Presenters\AssetPresenter::dataTableLayout() }}"
                data-cookie-id-table="{{ request()->has('status') ? e(request()->input('status')) : ''  }}assetsListingTable"
                data-id-table="{{ request()->has('status') ? e(request()->input('status')) : ''  }}assetsListingTable"
                data-search-text="{{ e(Session::get('search')) }}"
                data-side-pagination="server"
                data-show-footer="true"
                data-sort-order="asc"
                data-sort-name="name"
                data-toolbar="#assetsBulkEditToolbar"
                data-bulk-button-id="#bulkAssetEditButton"
                data-bulk-form-id="#assetsBulkForm"
                data-buttons="assetButtons"
                id="{{ request()->has('status') ? e(request()->input('status')) : ''  }}assetsListingTable"
                class="table table-striped snipe-table"
                data-url="{{ route('api.assets.index',
                    array('status' => e(Request::get('status')),
                    'order_number'=>e(strval(Request::get('order_number'))),
                    'company_id'=>e(Request::get('company_id')),
                    'status_id'=>e(Request::get('status_id')))) }}"
                data-export-options='{
                "fileName": "export{{ (Request::has('status')) ? '-'.str_slug(Request::get('status')) : '' }}-assets-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'>
              </table>

            </div><!-- /.col -->
          </div><!-- /.row -->

      </div><!-- ./box-body -->
    </div><!-- /.box -->
  </div>
</div>
@stop

@section('moar_scripts')
@include('partials.bootstrap-table')

<script>
  $('#templates').on('change', function () {
    const selectedId = $(this).val();
    const $table = $('#assetsListingTable');

    let baseUrl = $table.data('url');

    const newParams = new URLSearchParams({
        reportTemplate: selectedId || ''
    });

    const status = '{{ Request::get('status') }}';
    if (status) newParams.append('status', status);

    const companyId = '{{ Request::get('company_id') }}';
    if (companyId) newParams.append('company_id', companyId);

    const orderNumber = '{{ Request::get('order_number') }}';
    if (orderNumber) newParams.append('order_number', orderNumber);

    const statusId = '{{ Request::get('status_id') }}';
    if (statusId) newParams.append('status_id', statusId);

    const connector = baseUrl.includes('?') ? '&' : '?';
    const finalUrl = baseUrl + connector + newParams.toString();

    $table.bootstrapTable('refreshOptions', {
        url: finalUrl,
        pageNumber: 1
    });
  });
</script>

@stop
