<!-- begin redirect submit options -->
@props([
    'object',
    'object_type' => '',
])

<!-- begin non-ajaxed file listing table -->
<div class="table-responsive">
    <table
            data-columns="{{ \App\Presenters\UploadedFilesPresenter::dataTableLayout() }}"
            data-cookie-id-table="{{ str_slug($object->name ?? $object->id) }}-UploadsTable"
            data-id-table="{{ str_slug($object->name ?? $object->id) }}-UploadsTable"
            id="{{ str_slug($object->name ?? $object->id) }}-UploadsTable"
            data-side-pagination="server"
            data-toolbar="#upload-toolbar"
            data-sort-order="asc"
            data-sort-name="created_at"
            data-show-custom-view="true"
            data-custom-view="customViewFormatter"
            data-show-custom-view-button="true"
            data-url="{{ route('api.files.index', ['object_type' => $object_type, 'id' => $object->id]) }}"
            class="table table-striped snipe-table"
            data-export-options='{
                    "fileName": "export-uploads-{{ str_slug($object->name) }}-{{ date('Y-m-d') }}",
                    "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","delete","download","icon"]
                    }'>
    </table>

    <x-gallery-card />

</div>



<!-- end non-ajaxed file listing table -->