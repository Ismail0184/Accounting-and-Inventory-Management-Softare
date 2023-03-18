<?php
 require_once 'support_file.php';
 $title='Change Default Branch';
 $table='users';
 $unique='user_id';

if(isset($_POST['update'])){
$_SESSION['warehouse']=@$_POST['section_id'];}
?>
<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php';?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?=$title;?></h2>
                      <a style="float: right" class="btn btn-sm btn-default"  href="account_settings.php">
                          <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Change Password</span>
                      </a>
                      <a style="float: right" class="btn btn-sm btn-default"  href="account_settings_warehouse.php">
                          <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Change Default Warehous3</span>
                      </a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<form  name="addem" id="addem" style="font-size:11px" class="form-horizontal form-label-left" method="post">
  <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Existing Branch :</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
          <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" >
              <?=foreign_relation('company', 'section_id', 'CONCAT(section_id," : ", com_short_name)','', 'section_id="'.$_SESSION['sectionid'].'"','order by section_id'); ?>
          </select>
      </div>
  </div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Change to Branch :</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="section_id" >
            <option></option>
            <?=foreign_relation('company', 'section_id', 'CONCAT(section_id," : ", com_short_name)','', 'status=1','order by id'); ?>
        </select>
    </div>
</div>
<div class="form-group" style="margin-left:40%">
               <div class="col-md-6 col-sm-6 col-xs-12">
               <button type="submit" name="update" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Make as default Branch</button>
               </div></div>
             </form>
         </div>
     </div>
 </div>
<?=$html->footer_content();?>
