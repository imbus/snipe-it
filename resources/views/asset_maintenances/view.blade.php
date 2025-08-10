<?php
use Carbon\Carbon;
?>
@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/asset_maintenances/general.view') }} {{ $assetMaintenance->title }}
@parent
@stop

{{-- Page content --}}
@section('content')
  <div class="row">
    <div class="col-md-9">

      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs hidden-print">

          <li class="active">
            <a href="#info" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                            <x-icon type="info-circle" class="fa-2x" />
                            </span>
              <span class="hidden-xs hidden-sm">{{ trans('admin/users/general.info') }}</span>
            </a>
          </li>

          <li>
            <a href="#files" data-toggle="tab">
                                <span class="hidden-lg hidden-md">
                                <x-icon type="files" class="fa-2x" />
                                </span>
              <span class="hidden-xs hidden-sm">{{ trans('general.file_uploads') }}
                {!! ($assetMaintenance->uploads->count() > 0 ) ? '<span class="badge badge-secondary">'.number_format($assetMaintenance->uploads->count()).'</span>' : '' !!}
                                </span>
            </a>
          </li>

          @can('update', $assetMaintenance)
            <li class="pull-right">
              <a href="#" data-toggle="modal" data-target="#uploadFileModal">
                                <span class="hidden-lg hidden-xl hidden-md">
                                    <x-icon type="paperclip" class="fa-2x" />
                                </span>
                <span class="hidden-xs hidden-sm">
                                    <x-icon type="paperclip" />
                                    {{ trans('button.upload') }}
                                </span>
              </a>
            </li>
          @endcan
        </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="info">
            <div class="row-new-striped">
              <div class="row">

                  <div class="col-md-3">
                    {{ trans('admin/asset_maintenances/form.asset_maintenance_type') }}
                  </div>
                  <div class="col-md-9">
                    {{ $assetMaintenance->asset_maintenance_type }}
                  </div>

              </div> <!-- /row -->

              <div class="row">
                <div class="col-md-3">
                  {{ trans('general.asset') }}
                </div>
                <div class="col-md-9">
                  <a href="{{ route('hardware.show', $assetMaintenance->asset_id) }}">
                    {{ $assetMaintenance->asset->present()->fullName }}
                  </a>
                </div>
              </div> <!-- /row -->

              @if ($assetMaintenance->asset->model)
                <div class="row">
                  <div class="col-md-3">
                    {{ trans('general.asset_model') }}
                  </div>
                  <div class="col-md-9">
                    <a href="{{ route('models.show', $assetMaintenance->asset->model_id) }}">
                      {{ $assetMaintenance->asset->model->name }}
                    </a>
                  </div>
                </div> <!-- /row -->
              @endif

              @if ($assetMaintenance->asset->company)
                <div class="row">
                  <div class="col-md-3">
                    {{ trans('general.company') }}
                  </div>
                  <div class="col-md-9">
                    <a href="{{ route('companies.show', $assetMaintenance->asset->company_id) }}">
                      {{ $assetMaintenance->asset->company->name }}
                    </a>
                  </div>
                </div> <!-- /row -->
              @endif


              @if ($assetMaintenance->supplier)
              <div class="row">
                <div class="col-md-3">
                  {{ trans('general.supplier') }}
                </div>
                <div class="col-md-9">
                  <a href="{{ route('suppliers.show', $assetMaintenance->supplier_id) }}">
                    {{ $assetMaintenance->supplier->name }}
                  </a>
                </div>
              </div> <!-- /row -->
              @endif

              <div class="row">
                <div class="col-md-3">
                  {{ trans('admin/asset_maintenances/form.start_date') }}
                </div>
                <div class="col-md-9">
                  {{ Helper::getFormattedDateObject($assetMaintenance->start_date, 'date', false) }}
                </div>
              </div> <!-- /row -->

              <div class="row">
                <div class="col-md-3">
                  {{ trans('admin/asset_maintenances/form.completion_date') }}
                </div>
                <div class="col-md-9">
                  @if ($assetMaintenance->completion_date)
                    {{ Helper::getFormattedDateObject($assetMaintenance->completion_date, 'date', false) }}
                  @else
                    {{ trans('admin/asset_maintenances/message.asset_maintenance_incomplete') }}
                  @endif
                </div>
              </div> <!-- /row -->

              <div class="row">
                <div class="col-md-3">
                  {{ trans('admin/asset_maintenances/form.asset_maintenance_time') }}
                </div>
                <div class="col-md-9">
                  {{ $assetMaintenance->asset_maintenance_time }}
                </div>
              </div> <!-- /row -->

              @if ($assetMaintenance->cost > 0)
              <div class="row">
                <div class="col-md-3">
                  {{ trans('admin/asset_maintenances/form.cost') }}
                </div>
                <div class="col-md-9">
                  {{ \App\Models\Setting::getSettings()->default_currency .' '. Helper::formatCurrencyOutput($assetMaintenance->cost) }}
                </div>
              </div> <!-- /row -->
              @endif

              <div class="row">
                <div class="col-md-3">
                  {{ trans('admin/asset_maintenances/form.is_warranty') }}
                </div>
                <div class="col-md-9">
                  {{ $assetMaintenance->is_warranty ? trans('admin/asset_maintenances/message.warranty') : trans('admin/asset_maintenances/message.not_warranty') }}
                </div>
              </div> <!-- /row -->

              @if ($assetMaintenance->notes)
              <div class="row">
                <div class="col-md-3">
                  {{ trans('admin/asset_maintenances/form.notes') }}
                </div>
                <div class="col-md-9">
                  {!! nl2br(Helper::parseEscapedMarkedownInline($assetMaintenance->notes)) !!}
                </div>
              </div> <!-- /row -->
              @endif


            </div>
            </div><!-- /row-new-striped -->
            <div class="tab-pane" id="files">
              <div class="row">
                <div class="col-md-12">
                  <x-filestable object_type="accessories" :object="$assetMaintenance" />
                </div>
              </div>
            </div>
        </div><!-- /box-body -->
      </div><!-- /box -->

      </div> <!-- col-md-9  end -->
      <div class="col-md-3">

        @if ($assetMaintenance->image!='')
          <div class="col-md-12 text-center" style="padding-bottom: 17px;">
            <img src="{{ Storage::disk('public')->url(app('asset_maintenances_path').e($assetMaintenance->image)) }}" class="img-responsive img-thumbnail" style="width:100%" alt="{{ $assetMaintenance->name }}">
          </div>
        @endif

        <div class="col-md-12">

          <ul class="list-unstyled" style="line-height: 22px; padding-bottom: 20px;">

            @if ($assetMaintenance->notes)
              <li>
                <strong>{{ trans('general.notes') }}</strong>:
                {!! nl2br(Helper::parseEscapedMarkedownInline($assetMaintenance->notes)) !!}
              </li>
            @endif


          </ul>
      </div>

      @can('update', $assetMaintenance)
        <div class="col-md-12">
          <a href="{{ route('maintenances.edit', [$assetMaintenance->id]) }}" style="width: 100%;" class="btn btn-sm btn-warning btn-social">
            <x-icon type="edit" />
            {{ trans('general.update') }}
          </a>
        </div>
      @endcan
    </div>

    </div> <!-- row  end -->

  @can('assets.files', Asset::class)
    @include ('modals.upload-file', ['item_type' => 'maintenance', 'item_id' => $assetMaintenance->id])
  @endcan
@stop

@section('moar_scripts')
  @include ('partials.bootstrap-table')
@stop

