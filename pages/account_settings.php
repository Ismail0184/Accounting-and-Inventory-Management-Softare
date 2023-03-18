<?php

 require_once 'support_file.php';
 $title='Change Password';
 $table='users';
 $enat=date('Y-m-d h:s:i');
 if(isset($_POST['changePASS'])){
  $valid = true;
 	if ($_SESSION["PASSCODE"]!==$_POST['old_password'])
  {echo "<script> alert('Invalid Old Password!!') </script>";
         $valid = false;}
if ($valid){
 unset($_SESSION['PASSCODE']);
 $insert=mysqli_query($conn, "UPDATE  users SET password='".$_POST['new_password']."' where user_id='".$_SESSION['userid']."' ");
  $_SESSION["PASSCODE"]	=$_POST['new_password'];
}}?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php';?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?=$title;?></h2>
                    <a style="float: right" class="btn btn-sm btn-default"  href="account_settings_warehouse.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Change Default Warehoues</span>
                    </a>
                      <a style="float: right" class="btn btn-sm btn-default"  href="account_settings_change_default_branch.php">
                          <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Change Default Branch</span>
                      </a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<form  name="addem" id="addem" style="font-size:11px" class="form-horizontal form-label-left" method="post">
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Old Password<span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
	<input type="text" id="old_password" style="width:400px"  required  name="old_password"  class="form-control col-md-7 col-xs-12" >
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">New Password<span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
	<input type="text" id="new_password" style="width:400px"  required  name="new_password"  class="form-control col-md-7 col-xs-12" >
  </div>
</div>
<div class="form-group" style="margin-left:40%">
               <div class="col-md-6 col-sm-6 col-xs-12">
               <button type="submit" name="changePASS" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Change Password</button>
               </div></div>
             </form>
         </div>
     </div>
 </div>
<?=$html->footer_content();?>
