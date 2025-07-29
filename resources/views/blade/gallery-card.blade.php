<!-- HTML for the file gallery template -->
<template id="fileGalleryTemplate">

    <div class="col-md-4 col-lg-3 col-xl-2" style="height: 400px;">

        <div class="panel panel-%PANEL_CLASS%">
            <div class="panel-heading">
                <h3 class="panel-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <i class="%ICON%" /></i>
                    %ID% - %FILENAME%
                </h3>
            </div>
            <div class="panel-body">
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
            <div class="panel-footer">
                <div class="row">
                    <div class="text-left col-md-1">
                        %DELETE_BUTTON%
                    </div>
                    <div class="text-right col-md-11" style="white-space: nowrap">
                        %DOWNLOAD_BUTTON% %NEW_WINDOW_BUTTON%
                    </div>
                </div>
            </div><!-- /.panel-footer -->
        </div> <!-- /.panel panel-%PANEL_CLASS% -->
    </div><!-- /.col-md-4 col-lg-3 col-xl-1 -->
</template>
<!-- ./ HTML for the file gallery template -->