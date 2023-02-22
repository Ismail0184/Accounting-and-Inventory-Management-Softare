<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='QC Testing Parameter';
$unique='id';
$unique_field='PARAMETERS_Name';
$table='PARAMETERS';
$page="QC_setup_parameters.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


if(prevent_multi_submit()) {
if(isset($_POST['record'])) {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['status'] = 1;
        $crud->insert();
        unset($_POST);
        unset($$unique);
}

if(isset($_POST['modify'])) {
        $crud->update($unique);
        unset($_POST);
		echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
}}

if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}

$res="Select p.id,p.PARAMETERS_CODE as code,p.PARAMETERS_Name as name,
       IF(p.status=1, 'Active','Inactive') as status
       from PARAMETERS p order by p.id DESC ";
$query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
    if(isset($_POST['deletedata'.$row->$unique]))
    //{ if($row->has_entry == 0){
        mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$row->$unique.""));
    //} else { echo "It has entry (".$row->has_transaction."). Hence you cannot delete the Item Id (".$row->item_id.")";}
        unset($_POST);
    } //}
?>


<?php require_once 'header_content.php'; ?>
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

                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Parameter Code<span class="required text-danger">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="<?=$unique?>" value="<?=$$unique?>">
                                <input type="text" id="PARAMETERS_CODE" style="font-size: 11px"  required  name="PARAMETERS_CODE" value="<?=$PARAMETERS_CODE;?>" class="form-control col-md-7 col-xs-12" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Parameter Name<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PARAMETERS_Name" style="font-size: 11px"  required  name="PARAMETERS_Name" value="<?=$PARAMETERS_Name;?>" class="form-control col-md-7 col-xs-12" >
                            </div>
                        </div>
                        <hr/>
                        <?php if($_GET[$unique]):  ?>
                            <div class="form-group" style="margin-left:40%">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify Item</button>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="form-group" style="margin-left:40%">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                  </div>
                </div>
              </div>
<?php if(!isset($_GET[$unique])): ?>
    </div>
<?php endif; ?>



<?php if(!isset($_GET[$unique])):?>
    <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>
