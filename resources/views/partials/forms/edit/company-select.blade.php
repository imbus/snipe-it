<!-- When full company support is enabled and user is NOT superadmin -->
@if (($snipeSettings->full_multiple_companies_support=='1') && (!Auth::user()->isSuperUser()))
    <div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">
        <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>
        <div class="col-md-6">
            @include('partials.select/dropdowns/company-select', [
                'fieldname' => $fieldname,
                'translated_name' => $translated_name,
                'item' => $item ?? null,
                'multiple' => $multiple ?? 'false',
                'selected' => $selected ?? null,
                'only_top_level' => $only_top_level ?? null,
                'exclude_id' => $exclude_id ?? null,
                'disabled' => true
            ])
        </div>
        <div class="col-md-6 col-md-offset-3">
            <p class="help-block"><x-icon type="tip" /> {{ trans('general.fmcs_company_select_note') }}</p>
        </div>
        <div class="col-md-8 col-md-offset-3"><x-form.error :name="$fieldname" /></div>
    </div>
@else
    <div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">
        <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>
        <div class="col-md-6">
            @include('partials.select/dropdowns/company-select', [
                'fieldname' => $fieldname,
                'translated_name' => $translated_name,
                'item' => $item ?? null,
                'multiple' => $multiple ?? 'false',
                'selected' => $selected ?? null,
                'only_top_level' => $only_top_level ?? null,
                'exclude_id' => $exclude_id ?? null,
                'disabled' => false
            ])
        </div>

        @if ($snipeSettings->full_multiple_companies_support == '1')
            @can('superadmin')
                <div class="col-md-6 col-md-offset-3">
                    <p class="help-block"><x-icon type="tip" /> {{ trans('general.fmcs_company_select_superadmin_note') }}</p>
                </div>
            @endcan
        @endif

        <div class="col-md-8 col-md-offset-3"><x-form.error :name="$fieldname" /></div>
    </div>
@endif
