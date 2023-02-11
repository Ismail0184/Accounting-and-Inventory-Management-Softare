<?php require_once 'support_file.php'; ?>
<?=$html->header_content('Dashboard');?>
<?php require_once 'body_content.php'; ?>


<div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_title">
                    <h2><?php if($_SESSION['language']=='Bangla') : ?>আপনার মডিউল এবং মেনু পছন্দ করুন <?php else: ?>   Choose your Module & Menu <?php endif;?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?=$crud->dashboard_modules($module_get,$url_current,$link);?> 
                  </div>
                </div>
              </div>
<?php if($_SESSION['module_id']>0){?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_content">
                  <?=$crud->dashboard_quick_access_menu($main_manu_get,$url_current,$link);?> 
                  </div>
                </div>
              </div>
              <?php } else {} ?>


<?php if($_SESSION['module_id']>0):
require_once("toptitle_".$_SESSION[module_name].".php"); else :
endif; ?>
<?=$html->footer_content();?>