<?php
require_once 'support_file.php';
$title='Create Optgroup Label';
$now=time();
$unique='optgroup_label_id';
$unique_field='optgroup_label_name';
$table='module_reportview_optgroup_label';
$page="developer_optgroup_label.php";
$crud      =new crud($table);


if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))
{
$$unique = $_POST[$unique];
if(isset($_POST['record']))
{   $crud->insert();
    $type=1;
    $msg='New Entry Successfully Inserted.';
    unset($_POST);
    unset($$unique);
}}}
$query="Select ol.optgroup_label_id,ol.optgroup_label_id,ol.optgroup_label_name,m.module_short_name as module from $table ol,module_department m where ol.module_id=m.module_id order by ol.$unique ";
?>
<?php require_once 'header_content.php'; ?>
    <style>
        input[type=text]{
            font-size: 11px;    }
    </style>
<?php require_once 'body_content.php'; ?>

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


                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <input type="hidden" name="optgroup_label_id" value="">
                                    <? require_once 'support_html.php';?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Parents<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" id="module_id"  name="module_id">
                                                    <option></option>
                                                    <?php foreign_relation('module_department', 'id', 'CONCAT(id," : ", module_short_name)', $module_id, 'status in (\'1\')'); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Serial<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="sl" style="width:100%"  required  name="sl" value="<?=$sl;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Status<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" id="status" name="status">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select></div></div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Optgroup Label Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="optgroup_label_name" style="width:100%"  required  name="optgroup_label_name" value="<?=$optgroup_label_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_GET[mood]){  ?>
                                                <button type="submit" name="updatePS" class="btn btn-success">Update</button>
                                            <?php   } else {?>
                                                <button type="submit" name="record"  class="btn btn-primary">Create</button>
                                            <?php } ?>
                                        </div></div>

                                </form>
    </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
<?php if(!isset($_GET[$unique])):?>
    <?=$crud->report_templates_with_add_new($query,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>