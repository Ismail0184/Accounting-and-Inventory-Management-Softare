<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Cost Center";

$now=time();
$unique='id';
$unique_field='center_name';
$table="cost_center";
$page="acc_cost_center.php";
$crud      =new crud($table);
$$unique = @$_GET[$unique];
$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and a.company_id='".$_SESSION['companyid']."' and a.section_id in ('400000','".$_SESSION['sectionid']."')";
}

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
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
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	
$sql="select a.".$unique.",a.".$unique." as Code,a.".$unique_field.",c.category_name,s.section_name as branch,
IF(a.status=1, 'Active',IF(a.status='SUSPENDED', 'SUSPENDED','Inactive')) as status  from ".$table." a, company s, cost_category c where 
 a.category_id=c.id and
 a.section_id=s.section_id".$sec_com_connection."
 order by ".$unique."";
?>



<?php require_once 'header_content.php'; ?>
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
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size:11px">
                                    <?php require_once 'support_html.php';?>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Category<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%" name="category_id" id="category_id">
                                                <option></option>
                                                <?=foreign_relation('cost_category', 'id', 'CONCAT(id," : ", category_name)', $category_id, '1','1'); ?>
                                            </select>
                                        </div></div>

                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Cost Center<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="center_name" style="width:100%; font-size: 11px"  required   name="center_name" value="<?=$center_name;?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>

                                    <?php if($_GET[$unique]):  ?>

                                        <div class="form-group" style="width: 100%">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%; font-size:11px" name="status" id="status">
                                                    <option value="1"<?=($status=='1')? ' Selected' : '' ?>>Active</option>
                                                    <option value="0"<?=($status=='0')? ' Selected' : '' ?>>Inactive</option>
                                                    <option value="SUSPENDED"<?=($status=='SUSPENDED')? ' Selected' : '' ?>>SUSPENDED</option>
                                                </select>
                                            </div></div>

                                        <div class="form-group" style="margin-left:30%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="cancel" id="cancel" style="font-size:12px" class="btn btn-danger">Cancel</button>
                                                <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                                            </div></div>
                                    <?php else : ?>
                                        <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button></div></div> <?php endif; ?>
                                </form></div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>

<?php if(!isset($_GET[$unique])):?>
    <?=$crud->report_templates_with_add_new($sql,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();?>