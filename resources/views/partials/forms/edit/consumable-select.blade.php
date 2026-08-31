<!-- Consumable -->
<div id="assigned_consumable" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!! isset($style) ? ' style="' . e($style) . '"' : '' !!}>
    <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>
    <div class="col-md-7{{ isset($required) && $required == 'true' ? ' required' : '' }}">
        @include('partials.select/dropdowns/consumable-select')
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
