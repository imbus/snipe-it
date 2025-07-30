<!-- HTML for the file gallery template -->
<template id="fileGalleryTemplate">

    <div class="col-md-4 col-lg-3 col-xl-2">

        <div class="panel panel-%PANEL_CLASS%">
            <div class="panel-heading">
                <h3 class="panel-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <i class="%ICON%" /></i>
                    %ID% - %FILENAME%
                </h3>
            </div>
            <div class="panel-body" style="height: 300px; overflow: scroll !important;">
                <div class="col-md-12">
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
                    <div class="text-left col-md-1 col-sm-2">
                        <!-- %DELETE_BUTTON% should go here -->
                    </div>
                    <div class="text-right col-md-11 col-sm-10" style="white-space: nowrap">
                        %DOWNLOAD_BUTTON% %NEW_WINDOW_BUTTON%
                    </div>
                </div>
            </div><!-- /.panel-footer -->
        </div> <!-- /.panel panel-%PANEL_CLASS% -->
    </div><!-- /.col-md-4 col-lg-3 col-xl-1 -->
</template>
<!-- ./ HTML for the file gallery template -->