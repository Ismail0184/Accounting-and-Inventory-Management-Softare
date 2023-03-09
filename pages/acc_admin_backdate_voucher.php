<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Backdated voucher entry limit";

$now=time();
$unique='id';
$unique_field='back_date_limit';
$table="acc_voucher_config";
$table_log='acc_voucher_config_log';
$page="acc_admin_backdate_voucher.php";
$crud      =new crud($table);
$unique_GET = @$_GET[$unique];
 $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
 $todayss=$dateTime->format("d/m/Y  h:i A");


if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $unique_GET = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($unique_GET);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['entry_at']=$todayss;
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $up_previous=mysqli_query($conn, "Update ".$table_log." set status='Inactive' where status in ('Active')");
    $_POST['user_id']=$_SESSION['userid'];
    $_POST['entry_at']=$todayss;
    $_POST['status']='Active';
    $crud      =new crud($table_log);
    $crud->insert();
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
	
$table='acc_voucher_config_log';
$res='select l.id,u.fname as Changed_by,l.entry_at as changed_at,concat(l.limit_old," Days") as Previous_limit,concat(l.back_date_limit," Days") as New_limit,l.status  from '.$table.' l,users u where l.user_id=u.user_id order by '.$unique;
?>



<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>


<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Record</h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Days<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="hidden" id="limit_old" style="width:100%; font-size: 11px"   name="limit_old" value="<?=find_a_field('acc_voucher_config','back_date_limit','section_id='.$_SESSION['sectionid'].' and company_id='.$_SESSION['companyid'].'');?>" class="form-control col-md-7 col-xs-12" >
                                            <input type="text" id="<?=$unique_field?>" style="width:100%; font-size: 11px"  required   name="<?=$unique_field?>" value="<?=find_a_field('acc_voucher_config','back_date_limit','section_id='.$_SESSION['sectionid'].' and company_id='.$_SESSION['companyid'].'');?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Change At</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text"  style="width:100%; font-size: 11px"  required   name="entry_at" readonly value="<?=$todayss;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to change?");' style="font-size: 12px">Modify</button>
                                            </div>
                                            </div>



                                </form>
                                </div>
                                </div>
                                </div></div>

               
              
<?=$crud->report_templates_with_add_new($res,'Changed Logs',12,$action=0,$create=1,$page);?>
<?=$html->footer_content();mysqli_close($conn);?>