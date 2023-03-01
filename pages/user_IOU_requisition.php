<?php
require_once 'support_file.php';
$title="IOU Requisition";

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$now=time();

$table="user_IOU";
$unique = 'id';   // Primary Key of this Database table
$page="user_IOU_requisition.php";
$crud      =new crud($table);
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$$unique=$_GET[$unique];
if(prevent_multi_submit()){

    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['PBI_ID'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $sd=$_POST['req_date'];
        $_POST['req_date']=date('Y-m-d' , strtotime($sd));
        $_POST['ip'] = $ip;
        $_POST['status'] = 'UNCHECKED';
        $_POST['create_date'] = $_SESSION['create_date'];
        $_POST['PBI_ID'] = $_SESSION['PBI_ID'];
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }


//for modify..................................
    if(isset($_POST['modify']))
    {
        $sd=$_POST['or_date'];
        $_POST['or_date']=date('Y-m-d' , strtotime($sd));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
    }


//for Delete..................................
    if(isset($_POST['delete']))
    {
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$sql_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM							 
							personnel_basic_info p,
							department d,
							essential_info e
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and
							 p.PBI_ID=e.PBI_ID and 
							 e.ESS_JOB_LOCATION=1 group by p.PBI_ID							 
							  order by p.PBI_NAME";
$res="select a.id,a.req_date as date,a.purpose,a.amount,a.status from ".$table." a where a.PBI_ID=".$_SESSION['PBI_ID']." order by a.".$unique." desc limit 7";

?>

<?php require_once 'header_content.php'; ?>
    <style>
    input[type=text]{
        font-size: 11px;
    }
    input[type=date]{
        font-size: 11px;
    }
    </style>
<?php require_once 'body_content.php'; ?>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right">
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="" enctype="multipart/form-data" method="post" style="font-size: 11px" name="addem" id="addem" >
                <table style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:50%;">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">ID No<span class="required text-danger">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<?
                                    $uids=find_a_field($table,'max('.$unique.')+1','1');
                                    if($$unique>0){
                                        $uid=$$unique; } else {
                                     $uid=$uids;
                                        if($uids<1) $uid = 1;} echo $uid;?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                    <tr>
                        <td style="width:50%">
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">When is Needed <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" id="req_date"  required="required" name="req_date" value="<?=$req_date?>" class="form-control col-md-7 col-xs-12" style="width:100%" >
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                    <tr>
                        <td><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Amount in BDT <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="amount" id="amount" value="<?=$amount;?>" class="form-control col-md-7 col-xs-12" style="width: 100%;">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">IOU Purpose <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="purpose" id="purpose" value="<?=$purpose;?>" class="form-control col-md-7 col-xs-12" style="width: 100%;">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Recommended By <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select style="width: 100%; " class="select2_single form-control" name="recommended_by" id="recommended_by">
                                        <option></option>
                                        <?=advance_foreign_relation($sql_by,$recommended_by);?>
                                    </select>
                                </div></div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Authorized By <span class="required text-danger">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select style="width: 100%;" class="select2_single form-control" name="authorized_by" id="authorized_by">
                                        <option></option>
                                        <?=advance_foreign_relation($sql_by,$authorized_by);?>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="form-group" style="margin-left:25%;">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($$unique>0){  ?>
                            <button type="submit" style="font-size: 11px" name="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm to delete?");'>Delete</button>
                            <button type="submit" style="font-size: 11px" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");'>Update IOU Info</button>
                        <?php   } else {?>
                            <button type="submit" style="font-size: 11px" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Initiate <?=$title;?></button>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if(!isset($_GET[$unique])):?>
<?=recentdataview($res,'voucher_view_popup_ismail.php','hrm_leave_info','318px','List of IOU Slip','hrm_requisition_leave_report.php','6');?>
<?php endif; ?>
<?=$html->footer_content();?>