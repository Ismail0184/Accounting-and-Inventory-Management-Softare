<?php
require_once 'support_file.php';
$title="Travel Expenses Claim";

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$now=time();

$table="travel_application_claim_master";
$unique = 'trvClaim_id';   // Primary Key of this Database table
$table_details = 'travel_application_claim_details';
$details_unique = 'trvClaim_id';
$page="emp_access_requisition_travel_exp_claim.php";
$crud      =new crud($table);
$taken=getSVALUE("".$table_details."", "SUM(qty)", " where oi_date between '$dfrom' and '$dto' and  issued_to='".$_SESSION[PBI_ID]."' and item_id=".$_GET[item_code_GET]."");
$unit=getSVALUE("item_info", "unit_name", " where item_id=".$_GET[item_code_GET]."");
$department=getSVALUE("personnel_basic_info", "PBI_DEPARTMENT", " where PBI_ID=".$_SESSION[PBI_ID]."");
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
   
    if(isset($_POST['initiate']))
    {		
		$_POST['section_id'] = $_SESSION['sectionid'];
		$_POST['company_id'] = $_SESSION['companyid'];
        $_POST['hrm_viewed']='NO';
		$_POST['entry_by'] = $_SESSION['userid'];
        $_POST['application_date'] = date('Y-m-d');		
	    $_POST['status'] = 'MANUAL';
		$_POST['PBI_ID'] = $_SESSION[PBI_ID];
		$_SESSION['initiate_travel_exp_claim_requisition']=$_POST[$unique];		
        $crud->insert();
        unset($_POST);
        unset($$unique);
    }
	
	
	if(isset($_POST['add']))
    {			
		
		$_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
		$sd=$_POST[travel_date]; 
		$_POST[travel_date]=date('Y-m-d' , strtotime($sd));		
	    $_POST['issue_type'] = 'Office Issue';	
	    $_POST['status'] = 'MANUAL';
		$_POST['requisition_from'] = $_SESSION["department"];
	    $_POST['warehouse_id'] = '11';
		$_POST['recommend_qty'] = $_POST['qty'];
		$_POST['request_qty'] = $_POST['qty'];
		$_POST['issued_to'] = $_SESSION[PBI_ID];
		$_POST[oi_no]=$_SESSION['initiate_travel_exp_claim_requisition'];	
        $crud      =new crud($table_details);
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';		

        unset($_POST);
        unset($$unique);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{	$_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
	$sd=$_POST[oi_date]; 
    $_POST[oi_date]=date('Y-m-d' , strtotime($sd));
    $crud->update($unique);
    $type=1;
}

$query="Select * from ".$table_details."  where trvClaim_id='".$_SESSION['initiate_travel_exp_claim_requisition']."'";
$result=mysqli_query($conn, $query);
while($data=mysqli_fetch_object($result)){
    $id=$data->id;
    if(isset($_POST['deletedata'.$data->id]))
    {  mysqli_query($conn, ("DELETE FROM ".$table_details." WHERE id=".$id));
        $_SESSION['initiate_travel_exp_claim_requisition']=$_SESSION['initiate_travel_exp_claim_requisition'];
        unset($_POST);
    }
    if(isset($_POST['editdata'.$id]))
    {  mysqli_query($conn, ("UPDATE ".$table_details." SET travel_date='".$_POST[travel_date]."', travel_from='".$_POST[travel_from]."',travel_to='".$_POST[travel_to]."',mode_of_transport='".$_POST[mode_of_transport]."',transport_fair_rqst='".$_POST[transport_fair_rqst]."',
        lodging_expense='".$_POST[lodging_expense]."',lodging_fair_rqst='".$_POST[lodging_fair_rqst]."',breakfast_rqst='".$_POST[breakfast_rqst]."',lunch_rqst='".$_POST[lunch_rqst]."',dinner_rqst='".$_POST[dinner_rqst]."',total_amount='".$_POST[total_amount]."' WHERE id=".$id));
        unset($_POST);}
}


}

//for cancel or exit..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table);
    $condition=$unique."=".$_SESSION['initiate_travel_exp_claim_requisition'];
    $crud->delete($condition);
    $crud = new crud($table_details);
    $condition = $unique . "=" . $_SESSION['initiate_travel_exp_claim_requisition'];
    $crud->delete_all($condition);
    unset($_SESSION['initiate_travel_exp_claim_requisition']);
}

if (isset($_GET[id])) {
    $edit_value=find_all_field(''.$table_details.'','','id='.$_GET[id].'');
}


if (isset($_POST['confirm'])){

mysqli_query($conn, "Update ".$table." set status='UNCHECKED' where ".$unique."=".$_SESSION['initiate_travel_exp_claim_requisition']."");

$approved_by= find_a_field('travel_application_claim_master','checked_by','trvClaim_id='.$_SESSION['initiate_travel_exp_claim_requisition']);
$authorised_person=find_a_field('travel_application_claim_master','authorised_person','trvClaim_id='.$_SESSION['initiate_travel_exp_claim_requisition']);
$myid=$_SESSION[PBI_ID];
$name=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$myid);
$emailId=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$approved_by);
$emailIds=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$authorised_personss);
				$to = $emailId;
				$subject = "Requisition for Travel Exp. Claim" ;
				$txt1 = "<p>Dear Sir,</p>				
				<p>A Travel Exp. Claim requisition is pending for your Recommendation/Authorization. Please enter Employee Access module to approve the requisition. </p>				<p><strong>Requisition By-</strong> ".$name."</p>				
				<p><b><i>This EMAIL is automatically generated by ERP Software.</i></b></p>";
				$txt=$txt1.$txt2.$tr;
				$from = 'erp@icpbd.com';
				$headers = "";
$headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
$headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
mail($to,$subject,$txt,$headers);
unset($_SESSION['initiate_travel_exp_claim_requisition']);
unset($_POST);

}

// data query..................................
if(isset($_SESSION['initiate_travel_exp_claim_requisition']))
{   $condition=$unique."=".$_SESSION['initiate_travel_exp_claim_requisition'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

    $sql_approved_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM
    personnel_basic_info p,
    department d,
    essential_info e
     where
     p.PBI_JOB_STATUS in ('In Service') and
     p.PBI_DEPARTMENT=d.DEPT_ID	and
     p.PBI_ID=e.PBI_ID and
     e.ESS_JOB_LOCATION=1 group by p.PBI_ID
      order by p.PBI_NAME";   
 $COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$_SESSION['initiate_travel_exp_claim_requisition'].'');
 $res="Select d.id,d.travel_date as date,d.travel_from,d.travel_to,d.mode_of_transport,d.transport_fair_rqst as Transport_cost,d.lodging_expense,d.lodging_fair_rqst as lodging_cost,d.breakfast_rqst as breakfast,d.lunch_rqst as lunch,d.dinner_rqst as dinner,d.total_amount from ".$table_details." d where d.trvClaim_id='".$_SESSION['initiate_travel_exp_claim_requisition']."'";
 $total_amount=find_a_field(''.$table_details.'','SUM(total_amount)','trvClaim_id='.$_SESSION[initiate_travel_exp_claim_requisition]);    
?>

<?php require_once 'header_content.php'; ?>

<SCRIPT language=JavaScript>
function reload(form){
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='<?=$page;?>?item_code_GET=' + val ;}
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
   
<script>
    $('#lodging_fair_rqst').keyup(function(){
        var transport_fair_rqst;
        var lodging_fair_rqst;
        transport_fair_rqst = parseFloat($('#transport_fair_rqst').val());
        lodging_fair_rqst = parseFloat($('#lodging_fair_rqst').val());
        var total_amount = transport_fair_rqst + lodging_fair_rqst;
        $('#total_amount').val(total_amount.toFixed(2));


    });
</script>   

<?php require_once 'body_content.php'; ?>




                    <div class="col-md-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo $title; ?></h2>
                                <a style="float: right" target="_new" class="btn btn-sm btn-default"  href="emp_access_report_requisition_travel_exp_claim.php">
                                <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">View Applications</span>
                                </a>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content"> 
                            <form action="<?=$page?>" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px">
                            <table style="width:100%"  cellpadding="0" cellspacing="0">
                                <tr>
                                    <th style="width:10%;">Req. ID</th><th style="width: 2%;">:</th>
                                    <td style="width:15%"><input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<? if($_SESSION['initiate_travel_exp_claim_requisition']>0) { echo  $_SESSION['initiate_travel_exp_claim_requisition']; 
											} else  { echo find_a_field($table,'max('.$unique.')','1')+1;											
											if($$unique<1) $$unique = 1;}?>" class="form-control col-md-7 col-xs-12"  readonly style="width:90%; font-size:11px"></td>
                                    <th style="width:10%;">Date from</th><th style="width: 2%">:</th>
                                    <td><input type="date" id="trvDate_from"  required="required" name="trvDate_from" value="<?=($trvDate_from!='')? $trvDate_from : date('Y-m-d') ?>"  class="form-control col-md-7 col-xs-12" style="width:90%; font-size:11px"></td>
                                    <th style="width:10%;">Date to</th><th style="width: 2%">:</th>
                                    <td><input type="date" required="required" name="trvDate_to" value="<?=($trvDate_to!='')? $trvDate_to : date('Y-m-d') ?>"  class="form-control col-md-7 col-xs-12" style="width:90%; font-size:11px"></td>
                                </tr>
                               
                                
                                <tr><td style="height:5px"></td></tr>
                                <tr>
                                <th>Advance Amount</th><th style="width: 2%">:</th>
                                <td><input type="number" required="required" name="advance_amount" value="<?=($advance_amount!='')? $advance_amount : '';?>"  class="form-control col-md-7 col-xs-12" style="width:90%; font-size:11px"></td>
                                    <th>Departure Date</th><th style="width: 2%;">:</th>
                                    <td><input type="date" id="departure_date" value="<?=($departure_date!='')? $departure_date : date('Y-m-d') ?>" required="required" name="departure_date"  class="form-control col-md-7 col-xs-12" style="width:90%;font-size:11px"></td>
                                    <th>Return Date</th><th style="width: 2%">:</th>
                                    <td><input type="date" required="required" name="return_date" value="<?=($return_date!='')? $return_date : date('Y-m-d') ?>"  class="form-control col-md-7 col-xs-12" style="width:90%;font-size:11px;"></td>
                                    </tr>
                                
                                <tr><td style="height:5px"></td></tr>

                                <tr>
                                <th style="">Travel Purpose</th><th style="width: 2%">:</th>
                                    <td><input type="text" required="required" name="travel_purpose" value="<?=($travel_purpose!='')? $travel_purpose : '';?>"  class="form-control col-md-7 col-xs-12" style="width:90%; font-size:11px"></td>
                                    
                                    <th>Check By</th><th style="width: 2%">:</th>
                                    <td><select class="select2_single form-control" style="width: 90%; " tabindex="-1" required="required" name="checked_by" id="checked_by">
                                                <option></option>
                                                <?=advance_foreign_relation($sql_approved_by,$checked_by);?>
                                            </select></td>
                                    <th>Approved By</th><th style="width: 2%">:</th>
                                    <td><select class="select2_single form-control" style="width: 90%; " tabindex="-1" required="required" name="approved_by" id="approved_by">
                                                <option></option>
                                                <?=advance_foreign_relation($sql_approved_by,$approved_by);?>
                                            </select></td>
                                </tr></table>
                                <div class="form-group" style="margin-left:40%; margin-top: 20px">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_SESSION[initiate_travel_exp_claim_requisition]){  ?>
                                               <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size:12px">Update <?=$title;?></button>
                                               <?php   } else {?>
                                                <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size:12px">Initiate <?=$title;?></button>
                                            <?php } ?>
                                        </div></div>
                                </form></div></div></div>











<?php if($_SESSION[initiate_travel_exp_claim_requisition]):  ?>
<form action="<?=$page?>" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post"><? require_once 'support_html.php';?>
<table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tbody>
            <tr style="background-color: bisque">
                <th rowspan="2" style="text-align: center; vertical-align:middle">Date</th>
                <th colspan="2" style="text-align: center">Place/location</th>
                <th colspan="2" style="text-align: center">Mode of Transport</th>
                <th colspan="2" style="text-align: center">Lodging Expense</th>
                <th colspan="3" style="text-align:center">Food Expenses</th>
                <th rowspan="2" style="text-align:center;vertical-align:middle">Total</th>
                <th rowspan="2" style="text-align:center;vertical-align:middle">Action</th>
            </tr>
            <tr>
            <th style="text-align: center">From</th>
            <th style="text-align: center">To</th>
            <th style="text-align: center">Details</th>
            <th style="text-align: center">Cost</th>
            <th style="text-align: center">Details</th>
            <th style="text-align: center">Cost</th>
            <th style="text-align: center">Breakfast</th>
            <th style="text-align: center">Lunch</th>
            <th style="text-align: center">Dinner</th>

           </tr><tbody>
            <tr>
<td style="vertical-align: middle" align="center">
<input type="hidden" id="trvClaim_id" style="width:100%; font-size:11px"   name="trvClaim_id" value="<?=$trvClaim_id?>"  class="form-control col-md-7 col-xs-12" >
<input type="date" id="travel_date" style="width:100%; font-size:11px" min="<?=$trvDate_from?>" max="<?=$trvDate_to?>" value="<?=$edit_value->travel_date;?>"  required="required" name="travel_date"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="text" id="travel_from" style="width:100%; font-size:11px" required="required" name="travel_from" value="<?=$edit_value->travel_from;?>" class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="text" id="travel_to" Style="width:100%; font-size:11px" required="required" name="travel_to" value="<?=$edit_value->travel_to;?>"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="text" id="mode_of_transport" style="width:100%; font-size:11px"  value="<?=$edit_value->mode_of_transport;?>" required="required" name="mode_of_transport"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="number" id="transport_fair_rqst" style="width:100%; font-size:11px" class="form-control col-md-7 col-xs-12"  value="<?=$edit_value->transport_fair_rqst;?>"  required="required" name="transport_fair_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="text" id="lodging_expense" style="width:100%; font-size:11px" value="<?=$edit_value->lodging_expense;?>" placeholder="details" name="lodging_expense"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="number" id="lodging_fair_rqst" style="width:100%; font-size:11px"  value="<?=$edit_value->lodging_fair_rqst;?>" name="lodging_fair_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="number" id="breakfast_rqst" style="width:100%; font-size:11px"  value="<?=$edit_value->breakfast_rqst;?>"  name="breakfast_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="number" id="lunch_rqst" style="width:100%; font-size:11px"  value="<?=$edit_value->lunch_rqst;?>"  name="lunch_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="vertical-align: middle" align="center">
<input type="number" id="dinner_rqst" style="width:100%; font-size:11px"  value="<?=$edit_value->dinner_rqst;?>"  name="dinner_rqst"  class="form-control col-md-7 col-xs-12" >
</td>


<td style="vertical-align: middle" align="center">
<input type="number" id="total_amount" style="width:100%; font-size:11px" value="<?=$edit_value->total_amount;?>" name="total_amount" class="form-control col-md-7 col-xs-12"  class='total_amount' readonly="readonly">
</td>
<script>
    $(function(){
    $('#transport_fair_rqst, #lodging_fair_rqst,#breakfast_rqst,#lunch_rqst,#dinner_rqst').keyup(function(){
    var transport_fair_rqst = parseFloat($('#transport_fair_rqst').val()) || 0;
    var lodging_fair_rqst = parseFloat($('#lodging_fair_rqst').val()) || 0;
	var breakfast_rqst = parseFloat($('#breakfast_rqst').val()) || 0;
	var lunch_rqst = parseFloat($('#lunch_rqst').val()) || 0;
	var dinner_rqst = parseFloat($('#dinner_rqst').val()) || 0;
    $('#total_amount').val((transport_fair_rqst + lodging_fair_rqst + breakfast_rqst+lunch_rqst+dinner_rqst).toFixed(2));
     });});</script> 

<td align="center" style="width:5%"><?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
<?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
        </tbody>
            </table>
                </form>

<?=added_data_delete_edit($res,$unique,$_SESSION['initiate_travel_exp_claim_requisition'],$COUNT_details_data,$page,$total_amount,11);?><br><br>
<?php endif;?>                                
<?=$html->footer_content();mysqli_close($conn);?>