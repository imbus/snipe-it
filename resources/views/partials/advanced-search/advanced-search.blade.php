<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading" data-toggle="collapse" data-target="#collapsePanel" aria-expanded="false"
            aria-controls="collapsePanel">
            <span class="panel-title" style="margin: 0;">
                <i class="fas fa-search"></i>
                Advanced search
            </span>
        </div>
        <div id="collapsePanel" class="panel-collapse collapse">
            <div class="panel-body">
                <span id="advancedSearchFilters">
                    @php
                        $layoutJson = \App\Presenters\AssetPresenter::dataTableLayout();
                        $layout = json_decode($layoutJson); // decode to object by default
                        dump($layout);
                    @endphp

                    @foreach ($layout as $tableField)
                        @if (!empty($tableField->searchable) && $tableField->searchable === true)
                            <div id="advancedSearch_{{ $tableField->field }}" class="advancedSearchItemContainer">
                                <label for="advancedSearchSelect_{{ $tableField->field }}">
                                    <b>{{ $tableField->title }}</b>
                                </label>
                                @if (!isset($tableField->formatter))
                                    {{-- Default select if formatter is not set --}}
                                    <select class="form-control select2" data-endpoint="{{ $tableField->field }}"
                                        name="{{ $tableField->title }}" style="width: 100%"
                                        id="advancedSearchSelect_{{ $tableField->field }}"></select>
                                @else
                                    @switch($tableField->formatter)
                                        @case('dateDisplayFormatter')
                                            <input type="date" id="advancedSearchSelect_{{ $tableField->field }}"
                                                name="{{ $tableField->title }}">
                                        @break

                                        @case('companiesLinkObjFormatter')
                                            <p>companiesLinkObjFormatter</p>
                                        @break

                                        @case('trueFalseFormatter')
                                            <p>True/false</p>
                                        @break

                                        @case('categoriesLinkObjFormatter')
                                            @include ('partials.select.dropdowns.category-select', [
                                                'translated_name' => trans('admin/hardware/category.model'),
                                                'fieldname' => 'category_id',
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('companiesLinkObjFormatter')
                                            <p>companiesLinkObjFormatter</p>
                                        @break

                                        @case('deployedLocationFormatter')
                                            @include ('partials.select.dropdowns.location-select', [
                                                'translated_name' => trans('admin/hardware/location.model'),
                                                'fieldname' => 'location_id',
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('employeeNumFormatter')
                                            <p>employeeNumFormatter</p>
                                        @break

                                        @case('hardwareLinkFormatter')
                                            <p>hardwareLinkFormatter</p>
                                        @break

                                        @case('imageFormatter')
                                            <p>imageFormatter</p>
                                        @break

                                        @case('jobtitleFormatter')
                                            <p>jobtitleFormatter</p>
                                        @break

                                        @case('manufacturersLinkObjFormatter')
                                            @include ('partials.select.dropdowns.manufacturer-select', [
                                                'translated_name' => trans('admin/hardware/manufacturer.model'),
                                                'fieldname' => 'manufacturer_id',
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('modelsLinkObjFormatter')
                                            @include ('partials.select.dropdowns.model-select', [
                                                'translated_name' => trans('admin/hardware/form.model'),
                                                'fieldname' => 'model_id',
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('orderNumberObjFilterFormatter')
                                            <p>orderNumberObjFilterFormatter</p>
                                        @break

                                        @case('polymorphicItemFormatter')
                                            <p>polymorphicItemFormatter</p>
                                        @break

                                        @case('statuslabelsLinkObjFormatter')
                                            @include ('partials.select.dropdowns.status-select', [
                                                'translated_name' => trans('admin/hardware/status.model'),
                                                'fieldname' => 'status_id',
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('suppliersLinkObjFormatter')
                                            @include ('partials.select.dropdowns.supplier-select', [
                                                'translated_name' => trans('admin/hardware/supplier.model'),
                                                'fieldname' => 'supplier_id',
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('trueFalseFormatter')
                                            <p>suppliersLinkObjFormatter</p>
                                        @break

                                        @case('usersLinkObjFormatter')
                                            @include ('partials.select.dropdowns.user-select', [
                                                'translated_name' => trans('admin/hardware/user.model'),
                                                'fieldname' => 'user_id',
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @default
                                            <select class="form-control select2" data-endpoint="{{ $tableField->field }}"
                                                name="{{ $tableField->title }}" style="width: 100%"
                                                id="advancedSearchSelect_{{ $tableField->field }}"></select>
                                    @endswitch
                                @endif
                            </div>
                        @endif
                    @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Insert the table ID using php
        const tableId =
            "{{ request()->has('status') ? e(request()->input('status')) : '' }}assetsListingTable";
        const $table = $('#' + tableId);

        function collectAdvancedSearchFilters() {
            // Collect all advanced search fields (selects, inputs, etc.)
            const filters = {};
            document.querySelectorAll('[id^="advancedSearchSelect_"]').forEach(function(el) {
                // Use the field name from the id, you may need to adjust this if it changes
                const field = el.id.replace('advancedSearchSelect_', '');
                if (el.value && el.value.trim() !== '') {
                    filters[field] = el.value.trim();
                }
            });
            return filters;
        }

        function refreshTableWithAdvancedFilters() {
            const filters = collectAdvancedSearchFilters();
            $table.bootstrapTable('refresh', {
                query: {
                    filter: JSON.stringify(filters)
                }
            });
        }

        // Trigger search on change
        document.querySelectorAll('[id^="advancedSearchSelect_"]').forEach(function(el) {
            el.addEventListener('change', function() {
                refreshTableWithAdvancedFilters();
            });
        });
    });
</script>

<style>
    <style>.container {
        width: 100%;
        margin: 0 auto;
        padding: 10px;
    }

    .panel-heading {
        cursor: pointer;
    }

    #advancedSearchFilters {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px 20px;
        max-width: 1050px;
        /* 3 columns of ~300px */
        margin: 0 auto;
        justify-content: center;
    }

    .advancedSearchItemContainer {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .advancedSearchItemContainer b {
        margin-bottom: 5px;
    }

    .advancedSearchItemContainer select.form-control {
        width: 100%;
        box-sizing: border-box;
    }
</style>
