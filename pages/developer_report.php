<?php
require_once 'support_file.php';
$title='Create Report';
$now=time();
$unique='report_id';
$unique_field='report_name';
$table='module_reportview_report';
$page="developer_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

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
}}

//for Modify..................................
    if(isset($_POST['modify']))
    {
        $crud->update($unique);
        $type=1;
        echo "<script>window.close(); </script>";}

}

if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}
$query="Select r.report_id,r.report_id,r.report_name,ol.optgroup_label_name,m.module_short_name as module,IF(r.status=1, 'Active', 'Inactive') as status from $table r,module_reportview_optgroup_label ol,module_department m where  r.optgroup_label_id=ol.optgroup_label_id and r.module_id=m.module_id order by r.$unique ";
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
                                    <? require_once 'support_html.php';?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Modules<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" id="module_id"  name="module_id">
                                                    <option></option>
                                                    <?php foreign_relation('module_department', 'id', 'CONCAT(id," : ", module_short_name)', $module_id, 'status in (\'1\')'); ?>
                                                </select>
                                            </div>
                                        </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Optgroup Label<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" id="optgroup_label_id"  name="optgroup_label_id">
                                                <option></option>
                                                <?php foreign_relation('module_reportview_optgroup_label', 'optgroup_label_id', 'CONCAT(optgroup_label_id," : ", optgroup_label_name)', $optgroup_label_id, 'status in (\'1\')'); ?>
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
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Report ID<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text"  style="width:100%"  required  name="report_id" value="<?=$report_id;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Report Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="report_name" style="width:100%"  required  name="report_name" value="<?=$report_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_GET[$unique]){  ?>
                                                <button type="submit" name="modify" class="btn btn-success">Update</button>
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