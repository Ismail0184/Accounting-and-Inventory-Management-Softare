<?php
 require_once 'support_file.php';
 $title='Change Default Warehouse';
 $table='users';
 $unique='user_id';

 $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
 user_plant_permission upp,
 warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
 upp.user_id=".$_SESSION[userid]." and upp.status>0
 order by w.warehouse_id";

if(isset($_POST[update])){
mysqli_query($conn, "UPDATE  ".$table." SET warehouse_id='".$_POST[warehouse_id]."' where user_id='".$_SESSION["userid"]."' ");
$_SESSION[warehouse]=$_POST[warehouse_id];}
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
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

<form  name="addem" id="addem" style="font-size:11px" class="form-horizontal form-label-left" method="post">
  <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Existing Warehouse :</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
          <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" >
              <option></option>
              <?=advance_foreign_relation($sql_plant,$_SESSION[warehouse]);?>
              <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
          </select>
      </div>
  </div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Change to Warehouse :</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="warehouse_id" >
            <option></option>
            <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),'');?>
        </select>
    </div>
</div>

<div class="form-group" style="margin-left:40%">
               <div class="col-md-6 col-sm-6 col-xs-12">
               <button type="submit" name="update" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Make as default warehouse</button>
               </div></div>
             </form>
         </div>
     </div>
 </div>
<?=$html->footer_content();?>
