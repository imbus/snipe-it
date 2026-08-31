<!-- Asset Model -->
<div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">

    @php
        $hideFieldNameFlag = isset($hideFieldName) && filter_var($hideFieldName, FILTER_VALIDATE_BOOLEAN);
        $hideNewButtonFlag = isset($hideNewButton) && filter_var($hideNewButton, FILTER_VALIDATE_BOOLEAN);
    @endphp

    @unless($hideFieldNameFlag)
        <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>
    @endunless

    <div class="col-md-7">
        @include('partials.select/dropdowns/model-select')
    </div>

    @unless($hideNewButtonFlag)
        <div class="col-md-1 col-sm-1 text-left">
            @can('create', \App\Models\AssetModel::class)
                @if ((!isset($hide_new)) || ($hide_new!='true'))
                    <a href='{{ route('modal.show', 'model') }}' data-toggle="modal" data-target="#createModal" data-select='model_select_id' class="btn btn-sm btn-theme">{{ trans('button.new') }}</a>
                    <span class="mac_spinner" style="padding-left: 10px; color: green; display:none; width: 30px;">
                        <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
                    </span>
                @endif
            @endcan
        </div>
    @endunless

    <div class="col-md-8 col-md-offset-3"><x-form.error :name="$fieldname" /></div>
</div>
