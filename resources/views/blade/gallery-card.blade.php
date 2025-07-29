<!-- HTML for the file gallery template -->
<template id="fileGalleryTemplate">
    <div class="col-md-3 col-lg-2 col-xl-1" style="height: 300px;">
            <div class="box box-%PANEL_CLASS%">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="%ICON%" /></i>
                        %FILENAME% (%ID%)
                    </h3>
                </div>

                <div class="box-body">
                    <div class="col-md-12">
                        %INLINE_IMAGE%
                        <br>
                        %NOTE%
                    </div>
                </div>
                <div class="box-footer">
                    <div class="text-left col-md-6">
                        %DELETE_BUTTON%
                    </div>
                    <div class="text-right col-md-6">
                        %DOWNLOAD_BUTTON% %NEW_WINDOW_BUTTON%
                    </div>
                </div>
        </div><!-- /.box -->
    </div><!-- /.col-md-4 col-lg-3 col-xl-1 -->
</template>
<!-- ./ HTML for the file gallery template -->