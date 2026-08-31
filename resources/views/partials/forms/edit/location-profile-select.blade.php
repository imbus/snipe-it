<!-- Location -->
<div id="location_id" class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}"{!! isset($style) ? ' style="' . e($style) . '"' : '' !!}>

    <label for="location_id" class="col-md-3 control-label">{{ $translated_name }}</label>
    <div class="col-md-8">
        @include('partials.select/dropdowns/location-profile-select')
    </div>

    <div class="col-md-8 col-md-offset-3"><x-form.error name="location_id" /></div>

    @if ($snipeSettings->full_multiple_companies_support == '1' && $snipeSettings->scope_locations_fmcs == '1')
        @cannot('superadmin')
            <div class="col-md-8 col-md-offset-3">
                <p class="help-block"><x-icon type="tip" /> {{ trans('general.fmcs_location_select_note') }}</p>
            </div>
        @endcannot
    @endif

</div>
