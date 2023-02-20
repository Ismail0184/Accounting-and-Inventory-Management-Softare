<?php
require_once 'support_file.php';
$title="Add New User";

$now=time();
$unique='user_id';
$unique_field='fname';
$table="users";
$table2="user_activity_management";
$page="MIS_user_management_ERP_user.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$link="http://". $_SERVER['SERVER_NAME']."".'/51816/hrm_mod/pic/staff/';



if(prevent_multi_submit()){
        if(isset($_POST['record']))
        {
            $_POST['entry_date'] = date("Y-m-d"); 
            $_POST['status'] = '1';            
            $crud->insert();
            $crud      =new crud($table2);
            $crud->insert();
            unset($_POST);
            unset($$unique);
        }


//for modify..................................
        if(isset($_POST['modify']))
        {
            $_POST['edit_at']=time();
            $_POST['edit_by']=$_SESSION['userid'];
            $crud->update($unique);
            $type=1;
            //echo $targeturl;
            echo "<script>self.opener.location = '$page'; self.blur(); </script>";
            echo "<script>window.close(); </script>";
        }}

// data query..................................
if(isset($_GET[PBI_ID]))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$resss='select '.$unique.','.$unique.' as ID,username as user_name,'.$unique_field.' as display_name,email,level,IF(status=1, "Active", "Inactive") as status from '.$table.' where dep_power_level not in ("APP") order by '.$unique;
?>



<?php require_once 'header_content.php'; ?>
    <style>
        input[type=text]{
            font-size: 11px;
        }
        input[type=email]{
            font-size: 11px;
        }
        input[type=password]{
            font-size: 11px;
        }
        input[type=tel]{
            font-size: 11px;
        }
    </style>

<?php require_once 'body_content.php'; ?>
<?php if(isset($_GET[$unique])): ?>
<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right"></div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
							<?php else: ?>                            
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Record
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
          </h5>
        </div>
        <div class="modal-body">
        <?php endif; ?>

                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                    <? require_once 'support_html.php';?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">User ID <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" required name="user_id" style="font-size: 11px">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Username <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" required name="username" style="font-size: 11px">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Password <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" name="password" style="font-size: 11px">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Full Name <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" name="fname" style="font-size: 11px">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Email <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="email" class="form-control" name="email" style="font-size: 11px">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Mobile <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" name="mobile" style="font-size: 11px">
                        </div>
                    </div>
                            

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select required name="account_status" id="account_status" style="width: 100%; font-size: 11px" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                <option value="2">Banned</option>
                            </select>
                        </div></div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">User Level<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select required name="level" id="level" style="width: 100%; font-size: 11px" class="form-control">
                                <option></option>
                                
                                <option value="5">User</option>
                                <option value="4">Editor</option>
                                <option value="3">Admin</option>
                                <option value="2">Super Admin</option>
                                <option value="1">Developer</option>
                            </select>
                        </div></div>

                                    <hr> 

                                    <?php if($_GET[$unique]):  ?>
                                    <div class="form-group" style="margin-left:30%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <button type="submit" name="cancel" id="cancel" style="font-size:12px"  class="btn btn-danger">Cancel</button>
                                        <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                                        </div></div>
                                    <?php else : ?>
                                        <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="cancel" id="cancel" style="font-size:12px" data-dismiss="modal"  class="btn btn-danger">Cancel</button>
                                                <button type="submit" name="record" id="record" style="font-size:12px" class="btn btn-primary">Record</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>     
                                    </form></div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
                                        

<?php if(!isset($_GET[$unique])):?> 
<?=$crud->report_templates_with_add_new($resss,$title,12,$action=$_SESSION["userlevel"],$create=1);?>  
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>