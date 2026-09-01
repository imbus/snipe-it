<div id="assigned_user" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">

    <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>

    <div class="col-md-6">
        @include('partials.select/dropdowns/department-select')
    </div>


    <div class="col-md-8 col-md-offset-3"><x-form.error :name="$fieldname" /></div>

</div>
