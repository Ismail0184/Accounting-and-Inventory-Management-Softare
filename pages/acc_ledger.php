<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Accounts Ledger';
$proj_id=@$_SESSION['proj_id'];
$now=time();
$page="acc_ledger.php";
$table="accounts_ledger";
$unique="ledger_id";
$unique_field="ledger_name";
$crud      =new crud($table);
$separator	= @$_SESSION['separator'];
$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and al.company_id='".$_SESSION['companyid']."' and al.section_id in ('400000','".$_SESSION['sectionid']."')";
}
if(isset($_REQUEST['ledger_name'])||isset($_REQUEST['ledger_id']))
{	$ledger_id			= @mysqli_real_escape_string($conn, $_REQUEST['ledger_id']);
	$ledger_name 		= @mysqli_real_escape_string($conn, $_REQUEST['ledger_name']);
	$ledger_name		= str_replace("'","",$ledger_name);
	$ledger_name		= str_replace("&","",$ledger_name);
	$ledger_name		= str_replace('"','',$ledger_name);
	$ledger_group_id	= @mysqli_real_escape_string($conn, $_REQUEST['ledger_group_id']);
	$opening_balance	= @mysqli_real_escape_string($conn, $_REQUEST['balance']);
	$balance_type		= @mysqli_real_escape_string($conn, $_REQUEST['b_type']);
	$depreciation_rate	= @mysqli_real_escape_string($conn, $_REQUEST['d_rate']);
	$credit_limit		= @mysqli_real_escape_string($conn, $_REQUEST['cr_limit']);
	$date				= @mysqli_real_escape_string($conn, $_REQUEST['open_date']);
	$now				= date_value($date);
	$budget_enable		= @mysqli_real_escape_string($conn, $_REQUEST['budget_enable']);
	if(isset($_POST['record']))
	{if(!ledger_excess($ledger_name))
	{$type=0;
	$msg='Given Name('.$ledger_name.') is already exists.';
	} else {
	$ledger_id=approximate_ledger_id($ledger_group_id);
    $type= 'ledger';
	 if(ledger_generate($ledger_id,$ledger_name,$ledger_group_id,$opening_balance,$balance_type,$depreciation_rate,$credit_limit, $now,$proj_id,$budget_enable,$type))
		{ $type=1;
		  $msg='New Entry Successfully Inserted.';}}}

//for Modify..................................

if(isset($_POST['mledger']))

{

if(ledger_excess($ledger_name,$ledger_id))

{

$sql="UPDATE `accounts_ledger` SET 

		`ledger_name` 		= '$ledger_name',
		`opening_balance` 	= '$opening_balance',
		`ledger_group_id`	= '$ledger_group_id',
		`balance_type` 		= '$balance_type',
		`depreciation_rate` = '$depreciation_rate',
		`credit_limit` 		= '$credit_limit',
		`budget_enable`		= '$budget_enable',
		`opening_balance_on`= '$now'
	WHERE `ledger_id` 		= '$ledger_id' LIMIT 1";

		if(mysqli_query($conn, $sql))

		{
		$type=1;
		$msg='Successfully Updated.';

		} } else {
    $type=0;
	$msg='Given Name('.$ledger_name.') is already exists.';

	}

}

if(isset($_POST['modify']))
    {   $_POST['ledger_name']=$ledger_name;
		$_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
		}


}
if(isset($_POST['cancel'])){echo "<script>window.close(); </script>";}
if(isset($_GET[$unique]))
{   $condition=$unique."=".$_GET[$unique];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$ledger_group_id = @$ledger_group_id;
$ledger_name = @$ledger_name;
$depreciation_rate = @$depreciation_rate;
$budget_enable = @$budget_enable;
$status = @$status;
$unique_GET = @$_GET[$unique];
$post_ledger_group_id = @$_POST['ledger_group_id'];
$res="select al.".$unique.",al.".$unique." as Code,al.".$unique_field.",lg.group_name,(select COUNT(ledger_id) from journal where ledger_id=al.ledger_id) as has_transaction,s.section_name as branch,
IF(al.status=1, 'Active',IF(al.status='SUSPENDED', 'SUSPENDED','Inactive')) as status from ".$table." al,ledger_group lg,company s where 
al.ledger_group_id=lg.group_id and 
al.section_id=s.section_id".$sec_com_connection." order by al.ledger_group_id,al.".$unique;
$query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
if(isset($_POST['deletedata'.$row->$unique]))
    { if($row->has_transaction == 0){
        mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$row->$unique.""));
    } else { echo "It has transactions (".$row->has_transaction."). Hence you cannot delete the Ledger ID (".$row->ledger_id.")";}
       unset($_POST);
    }} // end of deletedata
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
                            <?php if(!isset($unique_GET)): ?>
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Ledger Group<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control" style="width:100%" name="ledger_group_id" id="ledger_group_id">
                                    <option></option>
                                    <?=foreign_relation('ledger_group', 'group_id', 'CONCAT(group_id," : ", group_name)', ($unique_GET!='')? $ledger_group_id : $post_ledger_group_id, '1','order by group_id'); ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Ledger  Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="ledger_name"  required="required" name="ledger_name" value="<?=$ledger_name;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div>
                            </div>
                            <div class="form-group" style="width: 100%;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Depreciation Rate</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="number" id="depreciation_rate"   name="depreciation_rate" value="<?=$depreciation_rate;?>" class="form-control col-md-7 col-xs-12" step="any" style="width: 100%; font-size:11px" >
                            </div>
                            </div>
                            

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Budget Enable</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" style="width:100%; font-size:11px" name="budget_enable" id="budget_enable">
                                    <option value="NO"<?php if($budget_enable=='NO') echo " Selected "?>>NO</option>
                                    <option value="YES"<?php if($budget_enable=='YES') echo " Selected "?>>YES</option>
                                </select>
                            </div></div>
                            <?php if($unique_GET):  ?>
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
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1,$page);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush(); ?>