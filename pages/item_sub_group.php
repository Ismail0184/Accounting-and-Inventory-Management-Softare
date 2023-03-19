<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Add Item Sub Group';
$unique='sub_group_id';
$unique_field='sub_group_name';
$table='item_sub_group';
$page="item_sub_group.php";
$crud      =new crud($table);
$$unique = @$_GET[$unique];
$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and a.company_id='".$_SESSION['companyid']."' and a.section_id in ('400000','".$_SESSION['sectionid']."')";
}

if(isset($_POST[$unique_field]))
{

    $$unique = $_POST[$unique];
//for Record..................................
    if(isset($_POST['record']))

    {
        $_POST['entry_by']=$_SESSION['userid'];
        $min=number_format($_POST['group_id']+10000, 0, '.', '');
        $max=number_format($_POST['group_id']+100000000, 0, '.', '');
        $_POST[$unique]=number_format(next_value('sub_group_id','item_sub_group','10000',$min,$min,$max), 0, '.', '');
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);

    }
//for Modify..................................
    if(isset($_POST['modify']))
    {
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        $msg='Successfully Updated.'; }
        echo $targeturl;
//for Delete..................................
    if(isset($_POST['delete']))
    {   $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';

    }}
if(isset($$unique)) {
    $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$sql="select group_id,concat(group_id,' : ',group_name) from item_group order by group_id";
$res="select a.".$unique.",a.".$unique." as Sub_group_code,a.".$unique_field.",(SELECT COUNT(item_id) from item_info where sub_group_id=a.sub_group_id) as has_child,s.section_name as branch
,IF(a.status=1, 'Active',IF(a.status='SUSPENDED','SUSPENDED','Inactive')) as status from ".$table." a, company s
 where 
 a.section_id=s.section_id".$sec_com_connection."
 order by a.".$unique;
$query=mysqli_query($conn, $res);
while($data=mysqli_fetch_object($query)){
    if(isset($_POST['deletedata'.$data->$unique]))
    { if($data->has_child == 0){
        mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$data->$unique.""));
    } else { echo "It has child (".$data->has_child."). Hence you cannot delete the Sub-Group ID (".$data->sub_group_id.")";}
        unset($_POST);
    }}
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
        
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size:11px" method="post">
                                    <div class="form-group">
                                        <? require_once 'support_html.php';?>
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Group Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%" name="group_id" id="group_id">
                                                <option></option>
                                                 <?=advance_foreign_relation($sql,$group_id);?>
                                               </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?require_once 'support_html.php';?>
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sub Group Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="sub_group_name" style="width:100%; font-size: 12px"  required   name="sub_group_name" value="<?=$sub_group_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                     <?php if($_GET[$unique]):  ?>
                                         <div class="form-group" style="width: 100%">
                                             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status</label>
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                                 <select class="select2_single form-control" style="width:100%; font-size:11px" name="status" id="status">
                                                     <option value="1"<?=($status=='1')? ' Selected' : '' ?>>Active</option>
                                                     <option value="0"<?=($status=='0')? ' Selected' : '' ?>>Inactive</option>
                                                     <option value="SUSPENDED"<?=($status=='SUSPENDED')? ' Selected' : '' ?>>SUSPENDED</option>
                                                 </select>
                                             </div>
                                         </div>

                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify</button>
                                        </div></div>
                                    <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New Group</button></div></div> <?php endif; ?> </form> </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>

<?php if(!isset($_GET[$unique])):?> 
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>                  
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>