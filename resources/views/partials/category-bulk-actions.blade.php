<div id="{{ (isset($id_divname)) ? $id_divname : 'categoriesBulkEditToolbar' }}" style="min-width:400px">
    <form
            method="POST"
            action="{{ route('categories.bulk.delete') }}"
            accept-charset="UTF-8"
            class="form-inline"
            id="{{ (isset($id_formname)) ? $id_formname : 'categoriesBulkForm' }}"
    >
        @csrf

        {{-- The sort and order will only be used if the cookie is actually empty (like on first-use) --}}
        <input name="sort" type="hidden" value="assets.id">
        <input name="order" type="hidden" value="asc">
        <label for="bulk_actions">
        <span class="sr-only">
            {{ trans('button.bulk_actions') }}
        </span>
        </label>
        <select name="bulk_actions" class="form-control select2" aria-label="bulk_actions" style="min-width: 350px;">
            @can('delete', \App\Models\Category::class)
                <option value="delete">{{ trans('button.delete') }}</option>
            @endcan
        </select>

        <button class="btn btn-primary" id="{{ (isset($id_button)) ? $id_button : 'bulkCategoryEditButton' }}"
                disabled>{{ trans('button.go') }}</button>
    </form>
</div>
