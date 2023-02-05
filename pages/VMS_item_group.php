<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
ob_start();
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));

$title='VAT Item Group';
$unique='group_id';
$unique_field='group_name';
$table='VAT_item_group';
$page="VMS_item_group.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


if(isset($_POST[$unique_field]))
{ $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        unset($_POST);
        unset($$unique);}



//for Modify..................................
    if(isset($_POST['modify']))
    {   $_POST['item_name'] = str_replace('"',"``",$_POST['item_name']);
        $_POST['item_name'] = str_replace("'","`",$_POST['item_name']);
        $_POST['item_description'] = str_replace(Array("\r\n","\n","\r"), " ", $_POST['item_description']);
        $_POST['item_description'] = str_replace('"',"``",$_POST['item_description']);
        $_POST['item_description'] = str_replace("'","`",$_POST['item_description']);
		$_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
		echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";}



//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
}}



if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}



$res='Select group_id, group_id, group_name,IF(status>0, "Active", "Inactive") as status from VAT_item_group order by group_id';

								 $sql = "SELECT sg.sub_group_id,concat(sg.sub_group_id,' : ',sg.sub_group_name,' : ',g.group_name) FROM
                        item_sub_group sg,
                        item_group g
                        where
                        sg.group_id=g.group_id
                        order by sg.sub_group_id";
$sql_unit="select unit_name, unit_name from unit_management";
$sql_item_type="Select item_type,item_type from item_type";
$sql_brand="Select id,brand_name from item_brand";
$sql_brand_category="Select category_name,category_name from brand_category"
?>
<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php if(isset($_GET[$unique])):
 require_once 'body_content_without_menu.php'; else :
 require_once 'body_content.php'; endif;  ?>








<?php if(isset($_GET[$unique])): ?>
<div class="col-md-5 col-sm-12 col-xs-12">
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
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                                            <input type="hidden" name="<?=$unique?>" />


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Group Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" id="<?=$unique_field;?>" style="width:100%; font-size: 12px"  required   name="<?=$unique_field;?>" value="<?=$$unique_field;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                   
<hr/>


<?php if($_GET[$unique]):  ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="modify" id="modify" class="btn btn-success">Modify Item</button>
                                        </div></div>
                                    <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-success">Add New Group</button></div></div> <?php endif; ?>


                        </form>
                    </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
<?php if(!isset($_GET[$unique])):?>
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>
