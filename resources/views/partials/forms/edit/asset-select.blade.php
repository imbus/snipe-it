<!-- Asset -->
<div id="{{ $asset_selector_div_id ?? "assigned_asset" }}"
     class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!!  (isset($style)) ? ' style="'.e($style).'"' : ''  !!}>
    <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>
    <div class="col-md-7">
        @include('partials.select/dropdowns/asset-select')
    </div>
    @if ($snipeSettings->full_multiple_companies_support == '1')
        @cannot('superadmin')
            <div class="col-md-7 col-md-offset-3">
                <p class="help-block"><x-icon type="tip" /> {{ trans('general.fmcs_select_note') }}</p>
            </div>
        @endcannot
    @endif

    <div class="col-md-8 col-md-offset-3"><x-form.error :name="$fieldname" /></div>

</div>
