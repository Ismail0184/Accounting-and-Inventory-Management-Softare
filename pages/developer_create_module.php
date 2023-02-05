<?php require_once 'support_file.php';?>
<? //(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Create Module';
$proj_id=$_SESSION['proj_id'];
$now=time();
$page="developer_create_module.php";
$table="dev_modules";
$unique="id";
$unique_field="modulename";
$crud      =new crud($table);


if(prevent_multi_submit()){
if(isset($_POST['record'])){
    $_POST[status]=1;
    $crud->insert();
    unset($_POST);
    unset($$unique);
    
}
if(isset($_POST['modify'])){
    $crud->update($unique);
    unset($_POST);
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}


if(isset($_POST['cancel'])){echo "<script>window.close(); </script>";}
if(isset($_GET[$unique]))
{   $condition=$unique."=".$_GET[$unique];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$res='select '.$unique.','.$unique.' as module_id,sl as serial,'.$unique_field.' as module_name,module_short_name,fa_icon,fa_icon_color,IF(status=1, "Active", "Inactive") as status from '.$table.'  where 
1 order by '.$unique;
$query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
if(isset($_POST['deletedata'.$row->$unique]))
    {  mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$row->$unique.""));
       unset($_POST);
    }} 
?>


<?php require_once 'header_content.php'; ?>
<?php if(isset($_GET[$unique])): 
 require_once 'body_content_without_menu.php'; else :  
 require_once 'body_content.php'; endif;  ?>
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
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post"  style="font-size: 11px">
                        <?php require_once 'support_html.php';?>
                        <?php if($_GET[$unique]):  ?>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Serial <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="number" required="required" name="sl" value="<?=$sl;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Module  Name <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" required="required" name="modulename" value="<?=$modulename;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div>
                        </div>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Module Short Name <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" required="required" name="module_short_name" value="<?=$module_short_name;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div>
                        </div>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">fa Icon <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" required="required" name="fa_icon" value="<?=$fa_icon;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div>
                        </div>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">fa Icon Color <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" required="required" name="fa_icon_color" value="<?=$fa_icon_color;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div>
                        </div>
                        <?php if($_GET[$unique]):  ?>
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" style="width:100%; font-size:11px" name="status" id="status">
                                            <option value="1"<?=($status=='1')? ' Selected' : '' ?>>Active</option>
                                            <option value="0"<?=($status=='0')? ' Selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                            </div>
                        <?php endif ?>
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
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>  
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>