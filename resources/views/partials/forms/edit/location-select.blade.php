<!-- Location -->
<div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!! isset($style) ? ' style="' . e($style) . '"' : '' !!}>

    <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>
    <div class="col-md-7">
        @include('partials.select/dropdowns/location-select')
    </div>

    <div class="col-md-1 col-sm-1 text-left">
        @can('create', \App\Models\Location::class)
            @if ((!isset($hide_new)) || ($hide_new!='true'))
            <a href='{{ route('modal.show', 'location') }}' data-toggle="modal"  data-target="#createModal" data-select='{{ $fieldname }}_location_select' class="btn btn-sm btn-theme">{{ trans('button.new') }}</a>
            @endif
        @endcan
    </div>

    <div class="col-md-8 col-md-offset-3"><x-form.error :name="$fieldname" /></div>

    @if ($snipeSettings->full_multiple_companies_support == '1' && $snipeSettings->scope_locations_fmcs == '1')
        @cannot('superadmin')
            <div class="col-md-7 col-md-offset-3">
                <p class="help-block"><x-icon type="tip" /> {{ trans('general.fmcs_location_select_note') }}</p>
            </div>
        @endcannot
    @endif

    @if (isset($help_text))
        <div class="col-md-7 col-sm-11 col-md-offset-3">
            <p class="help-block">{{ $help_text }}</p>
        </div>
    @endif

    @if (isset($hide_location_radio))
        <!-- Update actual location  -->
        <div class="form-group">
            <div class="col-md-9 col-md-offset-3">
                <label class="form-control">
                    <input name="update_default_location" type="radio" value="1" checked="checked"
                        aria-label="update_default_location" />
                    {{ trans('admin/hardware/form.asset_location') }}
                </label>
                <label class="form-control">
                    <input name="update_default_location" type="radio" value="0"
                        aria-label="update_default_location" />
                    {{ trans('admin/hardware/form.asset_location_update_default_current') }}
                </label>
            </div>
        </div> <!--/form-group-->
    @endif

</div>
