<!-- HTML for the file gallery template -->
<template id="fileGalleryTemplate">
    <div class="col-md-4 col-lg-3 col-xl-2" style="height: 400px;">
            <div class="box box-%PANEL_CLASS%">
                <div class="box-header with-border">
                    <h5>
                        <i class="%ICON%" /></i>
                        %FILENAME%
                    </h5>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12" style="height: 200px; overflow: scroll !important;">
                            %FILE_EMBED%
                            <br><br>
                            <p>
                            %NOTE%
                            <br>
                            %CREATED_AT% - %CREATED_BY%
                            </p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="text-left col-md-6">
                        %DELETE_BUTTON%
                    </div>
                    <div class="text-right col-md-6" style="white-space: nowrap">
                        %DOWNLOAD_BUTTON% %NEW_WINDOW_BUTTON%
                    </div>
                </div>
        </div><!-- /.box -->
    </div><!-- /.col-md-4 col-lg-3 col-xl-1 -->
</template>
<!-- ./ HTML for the file gallery template -->