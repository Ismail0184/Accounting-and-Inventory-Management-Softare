<?php
require_once 'support_file.php';
$title="Add New User";

$now=time();
$unique='user_id';
$unique_field='fname';
$table="user_activity_management";
$page="MIS_add_app_new_user.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$link="http://". $_SERVER['SERVER_NAME']."".'/51816/hrm_mod/pic/staff/';



if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))

//for insert..................................
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $_POST[picture_url]=$link.$_POST[PBI_ID].'.jpeg';
            $_POST[group_for]=$_SESSION[usergroup];
            $_POST[dep_power_level]='APP';
            if($_POST[gander]=='Female') {
                $_POST[gander] = '0';
            } else {
                $_POST[gander]='1';
            }
            $crud->insert();
            $type=1;
            $msg='New Entry Successfully Inserted.';
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
        }

//for Delete..................................
        if(isset($_POST['delete']))
        {   $condition=$unique."=".$$unique;
            $crud->delete($condition);
            unset($$unique);
            $type=1;
            $msg='Successfully Deleted.';
            echo "<script>self.opener.location = '$page'; self.blur(); </script>";
            echo "<script>window.close(); </script>";
        }}}

// data query..................................
if(isset($_GET[$unique]))
{   $condition=$unique."=".$_GET[$unique];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$resss='select '.$unique.','.$unique.' as ID,username as user_name,'.$unique_field.' as display_name,email,level,status from '.$table.' where dep_power_level in ("APP") order by '.$unique;
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

<?php if(!isset($_GET[$unique])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>User List</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php echo $crud->link_report_popup($resss,$link);?>
            </div>

        </div></div>
    <!-------------------End of  List View --------------------->
<?php } ?>
<!---page content----->

<!-- input section-->
<div class="col-md-5 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right">
                    <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                    </a-->
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <? require_once 'support_html.php';?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Product Group<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="product_group" id="product_group">
                                    <option></option>
                                    <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ",vendor_name)', $product_group, 'status="ACTIVE"'); ?>
                                </select></div></div>


                <div class="form-group" style="display: none">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?=$unique?><span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="<?=$unique?>" style="width:100%"   name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                    </div></div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Username<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="username" style="width:100%"  required   name="username" value="<?=$username;?>" placeholder="email, mobile or username"  class="form-control col-md-7 col-xs-12" >
                    </div></div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Display Name<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="fname" style="width:100%"  required   name="fname" value="<?=$fname;?>"  class="form-control col-md-7 col-xs-12" >
                    </div></div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Password<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="password" style="width:100%"  required   name="password" value="<?=$password;?>"   class="form-control col-md-7 col-xs-12" >
                    </div></div>


                    <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Email<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email" style="width:100%"  required   name="email"  value="<?=$email;?>"  class="form-control col-md-7 col-xs-12" >
                    </div></div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Gander<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="gander" style="width:100%"     name="gander"  value="<?=$gander;?>"  class="form-control col-md-7 col-xs-12" >
                            </div></div>



                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Mobile<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="tel" id="mobile" style="width:100%"    name="mobile"  value="<?=$mobile;?>"  class="form-control col-md-7 col-xs-12" >
                    </div></div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select required name="account_status" id="account_status" style="width: 100%; font-size: 11px" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="banned">Banned</option>
                                </select>
                            </div></div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">User Level<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select required name="level" id="level" style="width: 100%; font-size: 11px" class="form-control">
                                    <option></option>
                                    <option value="5">Administrator</option>
                                    <option value="4">User</option>
                                    <option value="3">Editor</option>
                                    <option value="1">Developer</option>
                                </select>
                            </div></div>



                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Image<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" name="picture_url" style="width: 100%" class="form-control col-md-7 col-xs-12">
                            </div></div>
                <br>
                <?php if($_GET[$unique]){  ?>
                    <div class="form-group" style="margin-left: 25%; width: 100%">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="cancel" id="cancel"  class="btn btn-danger">Cancel</button>
                            <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify User info</button>
                        </div></div>
                    <? if($_SESSION['userid']=="10019"){?>
                        <div class="form-group" style="margin-left:40%;">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                            </div></div>
                    <? }?>
                <?php } else {?>
                    <div  class="form-group" style="margin-left:30%;width: 100%">

                            <button type="submit" name="cancel" id="cancel" style="font-size: 12px;"  class="btn btn-danger">Cancel</button>
                            <button type="submit" name="record" id="record" style="font-size: 12px; "  class="btn btn-primary">Add New User</button>
                        </div>
                <?php } ?>


            </form>
        </div>
    </div>
</div>






<?php require_once 'footer_content.php' ?>