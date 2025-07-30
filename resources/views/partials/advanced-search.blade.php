<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading" data-toggle="collapse" data-target="#collapsePanel"
            aria-expanded="false" aria-controls="collapsePanel">
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
                            <div id="advancedSearch_{{ $tableField->field }}"
                                class="advancedSearchItemContainer">
                                <label for="advancedSearchSelect_{{ $tableField->field }}">
                                    <b>{{ $tableField->title }}</b>
                                </label>
                                @if (!isset($tableField->formatter))
                                    {{-- Default select if formatter is not set --}}
                                    <select class="form-control select2"
                                        data-endpoint="{{ $tableField->field }}"
                                        name="{{ $tableField->title }}" style="width: 100%"
                                        id="advancedSearchSelect_{{ $tableField->field }}"></select>
                                @else
                                    @switch($tableField->formatter)
                                        @case('dateDisplayFormatter')
                                            <input type="date"
                                                id="advancedSearchSelect_{{ $tableField->field }}"
                                                name="{{ $tableField->title }}">
                                            @break
                                        @default
                                            <select class="form-control select2"
                                                data-endpoint="{{ $tableField->field }}"
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