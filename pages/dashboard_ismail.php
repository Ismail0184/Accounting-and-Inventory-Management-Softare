<?php require_once 'support_file.php';?>
<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>

            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_title">
                    <h2>Choose your Module & Menu</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?=$crud->dashboard_modules($module_get,$url_current,$link);?> 
                  </div>
                </div>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_content">
                  <?=$crud->dashboard_quick_access_menu($main_manu_get,$url_current,$link);?> 
                  </div>
                </div>
              </div>

<?=$html->footer_content();?>
