<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Users";
$now=time();
$unique='user_id';
$unique_field='fname';
$table="users";
$page="acc_admin_users.php";
$crud      =new crud($table);
$unique_GET = @$_GET[$unique];
if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))

//for insert..................................
    {    $unique_GET = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $_POST['picture_url']=$link.$_POST[PBI_ID].'.jpeg';
            $_POST['group_for']=$_SESSION['usergroup'];
            if($_POST['gander']=='Female') {
                $_POST['gander'] = '0';
            } else {
                $_POST['gander']='1';
            }
            $_POST['status'] = 1;
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['entry_date'] = date('Y-m-d');
            $crud->insert();
            $type=1;
            $msg='New Entry Successfully Inserted.';
            unset($_POST);
            unset($unique_GET);
        }

        //for modify..................................
        if(isset($_POST['modify']))
        {
            $_POST['edit_at']=time();
            $_POST['edit_by']=$_SESSION['userid'];
            $crud->update($unique);
            echo "<script>self.opener.location = '$page'; self.blur(); </script>";
            echo "<script>window.close(); </script>";
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
        }

//for Delete..................................
        if(isset($_POST['delete']))
        {   $condition=$unique."=".$unique_GET;
            $crud->delete($condition);
            unset($unique_GET);
            $type=1;
            $msg='Successfully Deleted.';
            echo "<script>self.opener.location = '$page'; self.blur(); </script>";
            echo "<script>window.close(); </script>";
        }}}

// data query..................................
if(isset($unique_GET))
{   $condition=$unique."=".$unique_GET;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$user_id = @$user_id;
$username = @$username;
$password = @$password;
$fname = @$fname;
$email = @$email;
$mobile = @$mobile;
$account_status = @$account_status;
$level = @$level;
$department = @$department;
$res='select '.$unique.','.$unique.' as User_id,username as user_name,'.$unique_field.' as display_name,email,level,entry_date,expire_date,account_status as status from '.$table.' where department in ("Accounts") order by '.$unique.' desc';
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
    <SCRIPT language=JavaScript>
        function reload(form)
        {
            var val=form.PBI_ID.options[form.PBI_ID.options.selectedIndex].value;
            self.location='<?=$page;?>?PBI_ID=' + val ;
        }
    </script>
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
                                <?php endif;?>
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">User ID <span class="required text-danger">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" value="<?=$user_id?>" <?=($unique_GET>0)? 'readonly' : '';?> required name="user_id" style="font-size: 11px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Username <span class="required text-danger">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" value="<?=$username?>" <?=($unique_GET>0)? 'readonly' : '';?> required name="username" style="font-size: 11px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Password <span class="required text-danger">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="password" value="<?=$password?>" <?=($unique_GET>0)? 'readonly' : '';?> class="form-control" name="password" style="font-size: 11px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Full Name <span class="required text-danger">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" value="<?=$fname?>" name="fname" style="font-size: 11px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Email <span class="required text-danger">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="email" class="form-control" value="<?=$email?>" name="email" style="font-size: 11px">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:30%">Mobile <span class="required text-danger">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" value="<?=$mobile?>" name="mobile" style="font-size: 11px">
                                        </div>
                                    </div>
                                    <?php if(isset($unique_GET)): ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select required name="account_status" id="account_status" style="width: 100%; font-size: 11px" class="form-control">
                                                <option></option>
                                                <option value="active" <?=($account_status=='active')? 'selected' : '';?>>Active</option>
                                                <option value="inactive" <?=($account_status=='inactive')? 'selected' : '';?>>Inactive</option>
                                                <option value="banned" <?=($account_status=='banned')? 'selected' : '';?>>Banned</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">User Level<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select required name="level" id="level" style="width: 100%; font-size: 11px" class="form-control">
                                                <option></option>
                                                <option value="5" <?=($level==5)? 'selected' : '';?>>User</option>
                                                <option value="4" <?=($level==4)? 'selected' : '';?>>Editor</option>
                                                <option value="3" <?=($level==3)? 'selected' : '';?>>Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php if($unique_GET): if($department!=='Accounts'): echo "<h6 style='color: red; text-align: center'>Access Denied!! You don't have permission to edit the user. <br>A notification has been sent to the administrator that you attempted to view an unauthorized page.<br>If you try to view 2 more times your account will be banned. </h6>"; else: ?>
                                        <div class="form-group" style="margin-left:30%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="cancel" id="cancel" style="font-size:12px"  class="btn btn-danger">Cancel</button>
                                                <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                                            </div>
                                        </div>
                                    <?php endif; else : ?>
                                        <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="cancel" id="cancel" style="font-size:12px" data-dismiss="modal"  class="btn btn-danger">Cancel</button>
                                                <button type="submit" name="record" id="record" style="font-size:12px" class="btn btn-primary">Record</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php if(!isset($_GET[$unique])): ?></div><?php endif;?>
<?php if(!isset($_GET[$unique])){ ?>
    <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1,$page);?>
<?php } ?>
<?=$html->footer_content();?>