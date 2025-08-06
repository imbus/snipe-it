<div class="container">
    <div class="panel panel-default">
        <!-- The button for controlling the collapse -->
        <div class="panel-heading" data-toggle="collapse" data-target="#collapsePanel" aria-expanded="false"
            aria-controls="collapsePanel">
            <span class="panel-title" style="margin: 0;">
                <i class="fas fa-search"></i>
                Advanced search
            </span>
        </div>

        <!-- The collapsible content section -->
        <div id="collapsePanel" class="panel-collapse collapse" role="region" aria-labelledby="panelHeading">
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
                                <label for="advancedSearch_{{ $tableField->field }}">
                                    <b>{{ $tableField->title }}</b>
                                </label>
                                @if (!isset($tableField->formatter))
                                    {{-- Default select if formatter is not set --}}
                                    <select class="form-control select2" data-endpoint="{{ $tableField->field }}"
                                        name="{{ $tableField->title }}" style="width: 100%"
                                        id="advancedSearch_{{ $tableField->field }}"></select>
                                @else
                                    @switch($tableField->formatter)
                                        @case('dateDisplayFormatter')
                                            <input type="date" id="advancedSearch_{{ $tableField->field }}"
                                                name="{{ $tableField->title }}">
                                        @break

                                        @case('companiesLinkObjFormatter')
                                            @include ('partials.select.dropdowns.company-select', [
                                                'translated_name' => trans('admin/hardware/company.model'),
                                                'fieldname' => $tableField->field,
                                                'select_id' => "advancedSearch_$tableField->field",
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('trueFalseFormatter')
                                            <p>True/false</p>
                                        @break

                                        @case('categoriesLinkObjFormatter')
                                            @include ('partials.select.dropdowns.category-select', [
                                                'translated_name' => trans('admin/hardware/category.model'),
                                                'fieldname' => $tableField->field,
                                                'category_type' => 'asset',
                                                'select_id' => "advancedSearch_$tableField->field",
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
                                                'category_type' => 'asset',
                                                'select_id' => "advancedSearch_$tableField->field",
                                                'fieldname' => $tableField->field,
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
                                                'select_id' => "advancedSearch_$tableField->field",
                                                'fieldname' => $tableField->field,
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('modelsLinkObjFormatter')
                                            @include ('partials.select.dropdowns.model-select', [
                                                'translated_name' => trans('admin/hardware/form.model'),
                                                'select_id' => "advancedSearch_$tableField->field",
                                                'fieldname' => $tableField->field,
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
                                                'select_id' => "advancedSearch_$tableField->field",
                                                'fieldname' => $tableField->field,
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('suppliersLinkObjFormatter')
                                            @include ('partials.select.dropdowns.supplier-select', [
                                                'translated_name' => trans('admin/hardware/supplier.model'),
                                                'select_id' => "advancedSearch_$tableField->field",
                                                'fieldname' => $tableField->field,
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @case('trueFalseFormatter')
                                            <p>trueFalseFormatter</p>
                                        @break

                                        @case('customFieldsFormatter')
                                            <input type="text" autocomplete="on">
                                        @break

                                        @case('usersLinkObjFormatter')
                                            @include ('partials.select.dropdowns.user-select', [
                                                'translated_name' => trans('admin/hardware/user.model'),
                                                'select_id' => "advancedSearch_$tableField->field",
                                                'fieldname' => $tableField->field,
                                                'required' => 'false',
                                                'multiple' => 'true',
                                            ])
                                        @break

                                        @default
                                            <select class="form-control select2" data-endpoint="{{ $tableField->field }}"
                                                name="{{ $tableField->title }}" style="width: 100%"
                                                id="advancedSearch_{{ $tableField->field }}"></select>
                                    @endswitch
                                @endif
                            </div>
                        @endif
                    @endforeach
                    <button class="button" id="filterButton">Search</button>
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

            // Handle all selects
            document.querySelectorAll('select[id^="advancedSearch_"]').forEach(function(el) {
                // Use the field name from the id
                const id = "#" + el.id;
                const field = id.replace("#advancedSearch_", "");

                const selections = $(id).select2('data');
                let selectedOptionValue = [];

                console.log(selections);
                selections.forEach(item => {
                        if (item.itemKey) {
                            selectedOptionValue.push(item.itemKey);
                        } else {
                            selectedOptionValue.push(item.text);
                        }
                        console.log(selectedOptionValue);
                });


                if (selectedOptionValue.length > 0) {
                    filters[field] = selectedOptionValue;
                }

            });

            return filters;
        }

        function refreshTableWithAdvancedFilters() {
            console.log("refreshTableWithAdvancedFilters");

            const filters = collectAdvancedSearchFilters();
            console.log(filters);
            $table.bootstrapTable('refresh', {
                query: {
                    filter: JSON.stringify(filters)
                }
            });
        }

        // Trigger search on change
        document.querySelectorAll('[id^="advancedSearch_"]').forEach(function(el) {
            el.addEventListener('change', function() {
                refreshTableWithAdvancedFilters();
            });
        });

        const btn = document.getElementById("filterButton");
        console.log(btn);
        btn.addEventListener("click", (event) => {
            console.log(event);
            refreshTableWithAdvancedFilters();
        })
    });
</script>

<style>
    /* Ensure the container is properly constrained */
    .container {
        width: 100%;
        margin: 0 auto;
        padding: 10px;
        box-sizing: border-box;
    }

    /* Make the panel-heading fit within the container */
    .panel-heading {
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
        padding: 10px 15px;
        margin: 0;
    }

    /* Ensure the grid stays centered */
    #advancedSearchFilters {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px 20px;
        max-width: 1050px;
        margin: 0 auto;
        justify-content: center;
    }

    /* Flexbox styling remains the same */
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
