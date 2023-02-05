<?php
ob_start();
session_start();
require_once 'base.php';
$today=date('Y-m-d');
$create_date=date('Y-m-d');
$day = date('l', strtotime($today));
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$now=$dateTime->format("d/m/Y  h:i A");
list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $today);
$thisday=date('d');
$thisyear=$year1;
$thismonth=$month;

function check_permission($page){ 
global $conn;
$page_id_GET=find_a_field('zone_sub','zonecodesub','`url` LIKE "'.$page.'"');
$sql="select 
p.zonecode as powerpage,
p.zonecodemain,
p.user_id,
p.powerby,
z.zonecodesub as page_id,
z.zonename,
z.subzonedetails,
z.url as pageurl,
z.zonetype 
from 
user_permissions2 p,
zone_sub z 
where 
p.zonecode=z.zonecodesub and 
z.url like '".$page."' and 
z.zonecodesub='".$page_id_GET."' and 
p.user_id='".$_SESSION[userid]."'";
 $res=@mysqli_query($conn, $sql);
 $count=@mysqli_num_rows($res);
 if($count>0)
 {
  $data=@mysqli_fetch_row($res);
  return $data[0];  
 }
 else
 unauthorised_page_tried_to_view($page_id_GET,$page);
  return NULL;
  
}








function do_calander($field)
{
 echo '<script type="text/javascript">
$(document).ready(function(){
	
	$(function() {
		$("'.$field.'").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		});

	});

});</script>';
}



function auto_complete_from_db($table,$show,$id,$con,$text_field_id)
{ global $conn;
 if($con!='') $condition = " where ".$con;
 $query="Select ".$id.", ".$show." from ".$table.$condition;

 $led=mysqli_query($conn, $query);
 if(mysqli_num_rows($led) > 0)
 {
  $ledger = '[';
  while($ledg = mysqli_fetch_row($led)){
   $ledger .= '{ name: "'.$ledg[1].'", id: "'.$ledg[0].'" },';
  }
  $ledger = substr($ledger, 0, -1);
  $ledger .= ']';
 }
 else
 {
  $ledger = '[{ name: "empty", id: "" }]';
 }

 echo '<script type="text/javascript">
$(document).ready(function(){
    var data = '.$ledger.';
    $("#'.$text_field_id.'").autocomplete(data, {
		matchContains: true,
		minChars: 0,
		scroll: true,
		scrollHeight: 300,
        formatItem: function(row, i, max, term) {
            return row.name + " [" + row.id + "]";
		},
		formatResult: function(row) {
			return row.id;
		}
	});
  });
</script>';
}
function auto_complete_from_db_sql($query,$text_field_id)
{ global $conn;
 $led=mysqli_query($conn, $query);
 if(mysqli_num_rows($led) > 0)
 {
  $ledger = '[';
  while($ledg = mysqli_fetch_row($led)){
   $ledger .= '{ name: "'.$ledg[1].'", id: "'.$ledg[0].'" },';
  }
  $ledger = substr($ledger, 0, -1);
  $ledger .= ']';
 }
 else
 {
  $ledger = '[{ name: "empty", id: "" }]';
 }

 echo '<script type="text/javascript">
$(document).ready(function(){
    var data = '.$ledger.';
    $("#'.$text_field_id.'").autocomplete(data, {
		matchContains: true,
		minChars: 0,
		scroll: true,
		scrollHeight: 300,
        formatItem: function(row, i, max, term) {
            return row.name + " [" + row.id + "]";
		},
		formatResult: function(row) {
			return row.id;
		}
	});
  });
</script>';
}



function find_a_field($table,$field,$condition)
{ global $conn;
 $sql="select $field from $table where $condition limit 1";
 $res=@mysqli_query($conn,$sql);
 $count=@mysqli_num_rows($res);
 if($count>0)
 {
  $data=@mysqli_fetch_row($res);
  return $data[0];
 }
 else
  return NULL;
}




function find_a_field_sql($sql)
{
 global $conn;
 $res=@mysqli_query($conn, $sql);
 $count=@mysqli_num_rows($res);
 if($count>0)
 {
  $data=@mysqli_fetch_row($res);
  return $data[0];
 }
 else
  return NULL;
}



function find_all_field_sql($sql)
{global $conn;
 $res=@mysqli_query($conn, $sql);
 $count=@mysqli_num_rows($res);

 if($count>0)
 {
  $data=@mysqli_fetch_object($res);
  return $data;
 }
 else
  return NULL;
}


function find_all_field($table,$field,$condition)
{global $conn;
 $sql="select * from $table where $condition limit 1";
 $res=@mysqli_query($conn, $sql);
 $count=@mysqli_num_rows($res);

 if($count>0)
 {
  $data=@mysqli_fetch_object($res);
  return $data;
 }
 else
  return NULL;
}


function foreign_relation($table,$id,$show,$value,$condition=''){
	global $conn;
 if($condition=='')
  $sql="select $id,$show from $table";
 else
  $sql="select $id,$show from $table where $condition";
 $res=mysqli_query($conn, $sql);
 while($data=mysqli_fetch_row($res))
 {
  if($value==$data[0])
   echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
  else
   echo '<option value="'.$data[0].'">'.$data[1].'</option>';
 }
}



function foreign_relation_sql($sql){
global $conn;
 $res=mysqli_query($conn, $sql);
 while($data=mysqli_fetch_row($res))
 {
  if($value==$data[0])
   echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
  else
   echo '<option value="'.$data[0].'">'.$data[1].'</option>';
 }
}

function advance_foreign_relation($sql,$value=''){
	global $conn;
 $res=mysqli_query($conn, $sql);
 while($data=mysqli_fetch_row($res))
 {
  if($value==$data[0])
   echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
  else
   echo '<option value="'.$data[0].'">'.$data[1].'</option>';
 }
}

function join_relation($sql,$value=''){
	global $conn;
 $res=mysqli_query($conn, $sql);
 echo '<option></option>';
 while($data=mysqli_fetch_row($res))
 {
  if($value==$data[0])
   echo '<option value="'.$data[0].'" selected>'.$data[1].'</option>';
  else
   echo '<option value="'.$data[0].'">'.$data[1].'</option>';
 }
}



function next_value($field,$table,$diff=1,$initiate=100001,$btw1='',$btw2='')
{ global $conn;
 if($btw1>0)
  $sql="select max(".$field.") from ".$table." where ".$field." between '".$btw1."' and '".$btw2."'";
 else
  $sql="select max(".$field.") from ".$table;

 //echo $sql;
 $query=mysqli_fetch_row(mysqli_query($conn, $sql));
 $value=$query[0]+$diff;
 if($query[0] == 0)
 {
  $value=$initiate;
 }
 return $value;
}


function prevent_multi_submit($type = "post", $excl = "validator") {
 $string = "";
 foreach ($_POST as $key => $val) {
  if ($key != $excl) {
   $string .= $val;
  }
 }
 if (isset($_SESSION['last'])) {
  if ($_SESSION['last'] === md5($string)) {
   return false;
  } else {
   $_SESSION['last'] = md5($string);
   return true;
  }
 } else {
  $_SESSION['last'] = md5($string);
  return true;
 }
}



function prevent_multi_submit_one($type = "post", $excl = "validator") {
 $string = "";
 foreach ($_POST as $key => $val) {
  if ($key != $excl) {
   $string .= $val;
  }
 }
 if (isset($_SESSION['last'])) {
  if ($_SESSION['last'] === md5($string)) {
   return false;
  } else {
   $_SESSION['last'] = md5($string);
   return true;
  }
 } else {
  $_SESSION['last'] = md5($string);
  return true;
 }
}








///////////////// function for add to journal master
function add_to_journal_master($invoice,$transaction_date, $rfrom, $cno, $c_date, $bank, $status, $remarks, $voucyertype,$PBI_ID,$ip)
{ global $conn;
 $journal_master = "INSERT INTO `journal_voucher_master` (`voucherno`,
    `voucher_date`,
    `paid_to`,
    `Cheque_No`,
    `Cheque_Date`,
    `Cheque_of_bank`,
    `entry_status`,
    `remarks`,
    `journal_type`,
    `entry_by`,
    `entry_at`,
    `ip`,
    `section_id`,
    `company_id`,
    `PBI_ID`
	) VALUES ('$invoice','$transaction_date', '$rfrom', '$cno', '$c_date', '$bank', '$status', '$remarks', '$voucyertype','".$_SESSION['userid']."', '".date('Y-m-d H:s:i')."','$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$PBI_ID')";
 $query_journal = mysqli_query($conn, $journal_master);
}


function journal_master_update($transaction_date, $rfrom, $cno, $c_date, $bank, $remarks,$PBI_ID,$SESSIONVoucherno)
{ global $conn;
 $journal_master_update="UPDATE `journal_voucher_master` SET  

voucher_date='$transaction_date',
paid_to='$rfrom',
Cheque_No='$cno',
Cheque_Date='$c_date',
Cheque_of_bank='$bank',
remarks='$remarks',
PBI_ID='$PBI_ID' where voucherno='".$SESSIONVoucherno."' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'";
 $query_journal = mysqli_query($conn, $journal_master_update);

}


function add_to_receipt ($initiate_credit_note,$date,$proj_id,$narration,$ledger_id,$dr_amt,$cr_amt,$type,$cur_bal,$paid_to,
                         $Cheque_No,$c_date,$Cheque_of_bank,$manual_payment_no,$cc_code,$subledger_id,$status,$ip,$voucher_date,$sectionid,$companyid,$userid,$create_date,$now,$day
    ,$thisday,$thismonth,$thisyear,$receive_ledger)
{ global $conn;

 $recept="INSERT INTO `receipt` (
							receipt_no ,
							receipt_date ,
							proj_id ,
							narration ,
							ledger_id ,
							dr_amt ,
							cr_amt ,
							type ,
							cur_bal ,
							received_from,
							cheq_no,
							cheq_date,
							bank,
							manual_receipt_no,
							cc_code,
							sub_ledger_id,
							entry_status,
							`ip`,
							receiptdate,
							section_id,
							company_id,
							entry_by,
							do_no,
							create_date,
							`time`,
							`day_name`,
							`day`,
							`month`,
							`year` ) 
							 
							VALUES 
							
							('$initiate_credit_note',
							'$date',
							'$proj_id', 
							'$narration', 
							'$ledger_id', 
							'$dr_amt',
							'$cr_amt', 
							'$type', 
							'$cur_bal',
							'$paid_to',
							'$Cheque_No',
							'$c_date',
							'$Cheque_of_bank',
							'$manual_payment_no'
							,'$cc_code',
							'$subledger_id',
							'$status',
							'$ip',
							'$voucher_date',
							'$sectionid',
							'$companyid',
							'$userid','','$create_date','$now','$day','$thisday','$thismonth','$thisyear')";
 $query_receipt = mysqli_query($conn, $recept);
}
//// /receipt add data update




function add_to_payment ($initiate_credit_note,$date,$proj_id,$narration,$ledger_id,$dr_amt,$cr_amt,$type,$cur_bal,$paid_to,
                         $Cheque_No,$c_date,$Cheque_of_bank,$manual_payment_no,$cc_code,$subledger_id,$status,$ip,$voucher_date,$sectionid,$companyid,$userid,$create_date,$now,$day
    ,$thisday,$thismonth,$thisyear,$receive_ledger)
{
	global $conn;
 $recept="INSERT INTO `payment` (
							payment_no ,
							payment_date ,
							proj_id ,
							narration ,
							ledger_id ,
							dr_amt ,
							cr_amt ,
							type ,
							cur_bal ,
							received_from,
							cheq_no,
							cheq_date,
							bank,
							manual_payment_no,
							cc_code,
							sub_ledger_id,
							entry_status,
							`ip`,
							paymentdate,
							section_id,
							company_id,
							entry_by,
							do_no,
							create_date,
							`time`,
							`day_name`,
							`day`,
							`month`,
							`year` ) 
							 
							VALUES 
							
							('$initiate_credit_note',
							'$date',
							'$proj_id', 
							'$narration', 
							'$ledger_id', 
							'$dr_amt',
							'$cr_amt', 
							'$type', 
							'$cur_bal',
							'$paid_to',
							'$Cheque_No',
							'$c_date',
							'$Cheque_of_bank',
							'$manual_payment_no'
							,'$cc_code',
							'$subledger_id',
							'$status',
							'$ip',
							'$voucher_date',
							'$sectionid',
							'$companyid',
							'$userid','','$create_date','$now','$day','$thisday','$thismonth','$thisyear')";
 $query_receipt = mysqli_query($conn, $recept);
}
//// /receipt add data update


function add_to_bank_payment ($initiate_credit_note,$date,$proj_id,$narration,$ledger_id,$dr_amt,$cr_amt,$type,$cur_bal,$paid_to,
                         $Cheque_No,$c_date,$Cheque_of_bank,$manual_payment_no,$cc_code,$subledger_id,$status,$ip,$voucher_date,$sectionid,$companyid,$userid,$create_date,$now,$day
    ,$thisday,$thismonth,$thisyear,$receive_ledger)
{ global $conn;
 $recept="INSERT INTO `secondary_payment` (
							payment_no ,
							payment_date ,
							proj_id ,
							narration ,
							ledger_id ,
							dr_amt ,
							cr_amt ,
							type ,
							cur_bal ,
							received_from,
							cheq_no,
							cheq_date,
							bank,
							manual_payment_no,
							cc_code,
							sub_ledger_id,
							entry_status,
							`ip`,
							paymentdate,
							section_id,
							company_id,
							entry_by,
							do_no,
							create_date,
							`time`,
							`day_name`,
							`day`,
							`month`,
							`year` ) 
							 
							VALUES 
							
							('$initiate_credit_note',
							'$date',
							'$proj_id', 
							'$narration', 
							'$ledger_id', 
							'$dr_amt',
							'$cr_amt', 
							'$type', 
							'$cur_bal',
							'$paid_to',
							'$Cheque_No',
							'$c_date',
							'$Cheque_of_bank',
							'$manual_payment_no'
							,'$cc_code',
							'$subledger_id',
							'$status',
							'$ip',
							'$voucher_date',
							'$sectionid',
							'$companyid',
							'$userid','','$create_date','$now','$day','$thisday','$thismonth','$thisyear')";
 $query_receipt = mysqli_query($conn, $recept);
}



function add_to_journal_info ($initiate_credit_note,$date,$proj_id,$narration,$ledger_id,$dr_amt,$cr_amt,$type,$cur_bal,$paid_to,
                         $Cheque_No,$c_date,$Cheque_of_bank,$manual_payment_no,$cc_code,$subledger_id,$status,$ip,$voucher_date,$sectionid,$companyid,$userid,$create_date,$now,$day
    ,$thisday,$thismonth,$thisyear,$receive_ledger)
{ global $conn;
 $recept="INSERT INTO `journal_info` (
							journal_info_no ,
							journal_info_date ,
							proj_id ,
							narration ,
							ledger_id ,
							dr_amt ,
							cr_amt ,
							type ,
							cur_bal ,
							received_from,
							cheq_no,
							cheq_date,
							bank,
							manual_journal_info_no,
							cc_code,
							sub_ledger_id,
							entry_status,
							`ip`,
							j_date,
							section_id,
							company_id,
							entry_by,
							do_no,
							create_date,
							`time`,
							`day_name`,
							`day`,
							`month`,
							`year` ) 
							 
							VALUES 
							
							('$initiate_credit_note',
							'$date',
							'$proj_id', 
							'$narration', 
							'$ledger_id', 
							'$dr_amt',
							'$cr_amt', 
							'$type', 
							'$cur_bal',
							'$paid_to',
							'$Cheque_No',
							'$c_date',
							'$Cheque_of_bank',
							'$manual_payment_no'
							,'$cc_code',
							'$subledger_id',
							'$status',
							'$ip',
							'$voucher_date',
							'$sectionid',
							'$companyid',
							'$userid','','$create_date','$now','$day','$thisday','$thismonth','$thisyear')";
 $query_receipt = mysqli_query($conn, $recept);
}
//// /receipt add data update



function add_to_coutra ($initiate_credit_note,$date,$proj_id,$narration,$ledger_id,$dr_amt,$cr_amt,$type,$cur_bal,$paid_to,
                         $Cheque_No,$c_date,$Cheque_of_bank,$manual_payment_no,$cc_code,$subledger_id,$status,$ip,$voucher_date,$sectionid,$companyid,$userid,$create_date,$now,$day
    ,$thisday,$thismonth,$thisyear,$receive_ledger)
{
global $conn;
 $coutra="INSERT INTO `coutra` (
							coutra_no ,
							coutra_date ,
							proj_id ,
							narration ,
							ledger_id ,
							dr_amt ,
							cr_amt ,
							type ,
							cur_bal ,
							received_from,
							cheq_no,
							cheq_date,
							bank,
							manual_coutra_no,
							cc_code,
							sub_ledger_id,
							entry_status,
							`ip`,
							coutradate,
							section_id,
							company_id,
							entry_by,
							create_date,
							`time`,
							`day_name`,
							`day`,
							`month`,
							`year` ) 
							 
							VALUES 
							
							('$initiate_credit_note',
							'$date',
							'$proj_id', 
							'$narration', 
							'$ledger_id', 
							'$dr_amt',
							'$cr_amt', 
							'$type', 
							'$cur_bal',
							'$paid_to',
							'$Cheque_No',
							'$c_date',
							'$Cheque_of_bank',
							'$manual_payment_no'
							,'$cc_code',
							'$subledger_id',
							'$status',
							'$ip',
							'$voucher_date',
							'$sectionid',
							'$companyid',
							'$userid','$create_date','$now','$day','$thisday','$thismonth','$thisyear')";
 $query_receipt = mysqli_query($conn, $coutra);
}
//// /receipt add data update



function update_receipt_add_data($ledger_code_update,$cc_code_update, $updr_amt, $upcr_amt, $upnarration, $ids)
{ global $conn;
 $Receiptupdate = "UPDATE `receipt` SET
    
ledger_id='$ledger_code_update',
cc_code='$cc_code_update',
dr_amt='$updr_amt',
cr_amt='$upcr_amt',
narration='$upnarration'  where id='".$ids."' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'";
 $query_journal = mysqli_query($conn, $Receiptupdate);
}



function update_payment_add_data($ledger_code_update,$cc_code_update, $updr_amt, $upcr_amt, $upnarration, $ids)
{global $conn;
 $paymentupdate = "UPDATE `payment` SET
    
ledger_id='$ledger_code_update',
cc_code='$cc_code_update',
dr_amt='$updr_amt',
cr_amt='$upcr_amt',
narration='$upnarration'  where id='".$ids."' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'";
 $query_journal = mysqli_query($conn, $paymentupdate);
}



function update_journal_info_add_data($ledger_code_update,$cc_code_update, $updr_amt, $upcr_amt, $upnarration, $ids)
{ global $conn;
 $Receiptupdate = "UPDATE `journal_info` SET
    
ledger_id='$ledger_code_update',
cc_code='$cc_code_update',
dr_amt='$updr_amt',
cr_amt='$upcr_amt',
narration='$upnarration'  where id='".$ids."' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'";
 $query_journal = mysqli_query($conn, $Receiptupdate);
}


function update_contra_add_data($ledger_code_update,$cc_code_update, $updr_amt, $upcr_amt, $upnarration, $ids)
{ global $conn;
 $coutraupdate = "UPDATE `coutra` SET
    
ledger_id='$ledger_code_update',
cc_code='$cc_code_update',
dr_amt='$updr_amt',
cr_amt='$upcr_amt',
narration='$upnarration'  where id='".$ids."' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'";
 $query_journal = mysqli_query($conn, $coutraupdate);
}






function add_to_journal($jvdate,$proj_id, $jv_no, $jv_date, $ledger_id, $narration, $dr_amt, $cr_amt, $tr_from, $jv,$sub_ledger='',$tr_id='',$user_id='',$cc_code,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear)
{global $conn;
 $journal="INSERT INTO `journal` (
    `jvdate`,
	`proj_id` ,
	`jv_no` ,
	`jv_date` ,	
	`ledger_id` ,
	`narration` ,
	`dr_amt` ,
	`cr_amt` ,
	`tr_from` ,
	`tr_no` ,
	`cc_code`,
	`sub_ledger`,
	user_id,
	entry_at,
	group_for,
	`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`
	)VALUES ('$jvdate','$proj_id', '$jv_no', '$jv_date', '$ledger_id', '$narration', '$dr_amt', '$cr_amt', '$tr_from', '$jv','$cc_code','$sub_ledger','".$_SESSION['userid']."','".date('Y-m-d H:s:i')."','".$_SESSION['usergroup']."'
	,'$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear')";
 $query_journal=mysqli_query($conn, $journal);
}


function add_to_journal_new($jvdate,$proj_id, $jv, $date, $ledger_id, $narration, $dr_amt, $cr_amt, $tr_from, $tr_no,$tr_id,$cc_code,$sub_ledger_id,$usergroup,$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear,$pc_code,$do_no,$po_no)
{global $conn;
 $journal="INSERT INTO `journal` (
    `jvdate`,
	`proj_id` ,
	`jv_no` ,
	`jv_date` ,	
	`ledger_id` ,
	`narration` ,
	`dr_amt` ,
	`cr_amt` ,
	`tr_from` ,
	`tr_no` ,
	`tr_id`,
	`cc_code`,
	`sub_ledger_id`,
	`relavent_cash_head`,
	user_id,
	entry_at,
	group_for,
	`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,
	`cheq_date`,`cheq_no`,`pc_code`,`do_no`,`po_no`
	)VALUES ('$jvdate','$proj_id', '$jv', '$date', '$ledger_id', '$narration', '$dr_amt', '$cr_amt', '$tr_from', '$tr_no', '$tr_id','$cc_code','$sub_ledger_id','$relavent_cash_head','".$_SESSION['userid']."','".date('Y-m-d H:s:i')."','".$_SESSION['usergroup']."'
	,'$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear','$c_date','$c_no','$pc_code','$do_no','$po_no')";
 $query_journal=mysqli_query($conn, $journal);
}



function insert_into_secondary_journal($jvdate,$proj_id, $jv, $date, $ledger_id, $narration, $dr_amt, $cr_amt, $tr_from, $tr_no,$tr_id,$cc_code,$sub_ledger_id,$usergroup,$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear,$grn_inventory_type)
{
 global $conn;
 $journal="INSERT INTO `secondary_journal` (
    `jvdate`,
	`proj_id` ,
	`jv_no` ,
	`jv_date` ,	
	`ledger_id` ,
	`narration` ,
	`dr_amt` ,
	`cr_amt` ,
	`tr_from` ,
	`tr_no` ,
	`tr_id`,
	`cc_code`,
	user_id,
	entry_at,
	group_for,
	`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,
	`cheq_date`,`cheq_no`,`grn_inventory_type`
	)VALUES ('$jvdate','$proj_id', '$jv', '$date', '$ledger_id', '$narration', '$dr_amt', '$cr_amt', '$tr_from', '$tr_no', '$tr_id','$cc_code','".$_SESSION['userid']."','".date('Y-m-d H:s:i')."','".$_SESSION['usergroup']."'
	,'$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear','$c_date','$c_no','$grn_inventory_type')";
 $query_journal=mysqli_query($conn, $journal);
}




function add_to_journal_bank($jvdate,$proj_id, $jv, $date, $ledger_id, $narration, $dr_amt, $cr_amt, $tr_from, $tr_no,$tr_id,$cc_code,$sub_ledger_id,$usergroup,$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear)
{ global $conn;
 $journal="INSERT INTO `secondary_journal_bank` (
    `jvdate`,
	`proj_id` ,
	`jv_no` ,
	`jv_date` ,	
	`ledger_id` ,
	`narration` ,
	`dr_amt` ,
	`cr_amt` ,
	`tr_from` ,
	`tr_no` ,
	`tr_id`,
	`cc_code`,
	`sub_ledger_id`,
	`relavent_cash_head`,
	user_id,
	entry_at,
	group_for,
	`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,
	`cheq_date`,`cheq_no`
	)VALUES ('$jvdate','$proj_id', '$jv', '$date', '$ledger_id', '$narration', '$dr_amt', '$cr_amt', '$tr_from', '$tr_no', '$tr_id','$cc_code','$sub_ledger_id','$relavent_cash_head','".$_SESSION['userid']."','".date('Y-m-d H:s:i')."','".$_SESSION['usergroup']."'
	,'$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear','$c_date','$c_no')";
 $query_journal=mysqli_query($conn, $journal);
}

function sec_journal_journal($sec_jv_no,$jv_no,$tr_froms,$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear,$jvdate)
{global $conn;
 $sql = 'select * from secondary_journal where jv_no = "'.$sec_jv_no.'" and tr_from = "'.$tr_froms.'"';
 $query = mysqli_query($conn, $sql);
 while($data = mysqli_fetch_object($query))
 {
  $journal="INSERT INTO `journal` (
	`proj_id` ,
	`jv_no` ,
	`jv_date` ,
	`ledger_id` ,
	`narration` ,
	`dr_amt` ,
	`cr_amt` ,
	`tr_from` ,
	`tr_no` ,
	`tr_id` ,
	`sub_ledger`,
	user_id,
	entry_at,
	group_for,
	cc_code,
	
	`ip`,
	section_id,
	company_id,
	create_date,
	`time`,
	`day_name`,
	`day`,
	`month`,
	`year`,
	`cheq_date`,`cheq_no`,jvdate	
	)VALUES 
('$data->proj_id', '$data->jv_no', '$data->jv_date', '$data->ledger_id', '$data->narration', '$data->dr_amt', '$data->cr_amt', '$data->tr_from', '$data->tr_no', '$data->tr_id','$data->sub_ledger','".$_SESSION['userid']."','".date('Y-m-d H:i:s')."', '$data->group_for', ".$data->cc_code.",'$ip','$_SESSION[sectionid]','$_SESSION[companyid]','$create_date','$now','$day','$thisday','$thismonth','$thisyear','$c_date','$c_no','$jvdate')";
  $query_journal=mysqli_query($conn, $journal);
 }
}




function ledger_sep($ledger,$separator)
{		$j=0;
 $separate_at=3;
 for($i=0;$i<strlen($ledger);$i++)
 {
  if((($i%$separate_at)==0))
  {
   $ledger_sep[$j]=$ledger_sep[$j].$separator;
   $j++;
  }
  $ledger_sep[$j]=$ledger_sep[$j].$ledger[$i];
  $j++;
 }
 //echo $separator;
 return implode('',$ledger_sep);
}
function ledger_sepe($ledger,$separator)
{		$j=0;

 for($i=0;$i<strlen($ledger);$i++)
 {
  if(($i==3)||($i==6))
   echo $separator.$ledger[$i];
  else
   echo $ledger[$i];
 }
}
function add_separator($ledger,$separator)
{		$j=0;
 $str='';
 for($i=0;$i<strlen($ledger);$i++)
 {
  if(($i==3)||($i==6))
   $str .= $separator.$ledger[$i];
  else
   $str .= $ledger[$i];
 }
 return $str;
}

function js_ledger_subledger_autocomplete($ledger_type,$proj_id)
{ global $conn;
 if(	$ledger_type	==	'receipt'	) $balance_type = "balance_type IN ('Credit','Both') AND";
 if(	$ledger_type	==	'payment'	) $balance_type = "balance_type IN ('Debit','Both') AND";
 if(	$ledger_type	==	'journal'	) $balance_type = "";
 if(	$ledger_type	==	'contra'	) $balance_type = "";
 echo '<script type="text/javascript">';
 $separator=find_a_field('project_info','ledger_id_separator',"1");
 echo ' var sub_ledgers = [';
 $a2="select 
			ledger_id, 
			ledger_name 
		from 
			accounts_ledger 
		where 
			".$balance_type." 1
			
		order by 
			ledger_name";
 $a1	=	mysqli_query($conn, $a2);
 $i = 0;
 while(	$a = mysqli_fetch_row($a1) )
 {
  if( $i == 0 ) 	echo   "'".$a[1]."'";
  else 			echo ", '".$a[1]."'";

  $b2="select 
						sub_ledger_id,
						sub_ledger 
					from 
						sub_ledger 
					where 
						ledger_id='$a[0]'";
  $b1 = mysqli_query($conn, $b2);
  $c  = mysqli_num_rows($b1);

  if($c>0)
  {}
  $i++;
 }
 echo ' ]; </script>';
}

function father_ledger_name($ledger_id)
{
 if (strpos($ledger_id,'00000000') == false)
 {
  if (strpos(substr($ledger_id, -4),'0000') >0)
   $f = substr($ledger_id, 0,12).'0000'; 		// S S L
  else
   $f = substr($ledger_id, 0,8).'00000000'; 	//   S L
  $father_ledger_name=find_a_field('accounts_ledger','ledger_name','ledger_id='.$f);
  return ' >>> '.$father_ledger_name;
 }
}

function js_ledger_subledger_autocomplete_new($ledger_type,$proj_id,$type='',$group_for)
{ global $conn;
 if(	$ledger_type	==	'receipt'	) $balance_type = " and balance_type IN ('Credit','Both') ";
 if(	$ledger_type	==	'payment'	) $balance_type = " and balance_type IN ('Debit','Both') ";
 if(	$ledger_type	==	'contra'	) $balance_type = " and ledger_group_id in (select group_id from ledger_group where group_sub_class='1020')";
 if(	$ledger_type	==	'journal'	) $balance_type = " and ledger_group_id not in (select group_id from ledger_group where group_sub_class='1020')";
 if(	$group_for	>	1	) $groupfor = " and group_for =".$group_for;

 $under_ledger = '[';
 $a2="select 
			ledger_id, 
			ledger_name 
		from 
			accounts_ledger 
		where 
		parent=0 
			".$balance_type.$groupfor." 
		order by ledger_id";
 $a1	=	mysqli_query($conn, $a2);

 while($a = mysqli_fetch_row($a1)){
  $father_ledger_name=father_ledger_name($a[0]);
  $l_name = $a[1].$father_ledger_name;
  $l_name = str_replace('#','@',$l_name);
  $under_ledger .= '{ name: "'.$l_name.'", id: "'.$a[0].'" },';}
 $under_ledger = substr($under_ledger, 0, -1);

 echo '<script type="text/javascript">';
 $under_ledger .= ']';
 echo '
$().ready(function() {
var data = '.$under_ledger.';
	$("#ledger_id").autocomplete(data, {
		matchContains: true,
		minChars: 0,
		scroll: true,
		scrollHeight: 300,
        formatItem: function(row, i, max, term) {
		//return row.name.replace(new RegExp("(" + term + ")", "gi"), "<strong>$1</strong>") + "<br><span style="font-size: 80%;">ID: " + row.id + "</span>"; 
			';
 if($type==1)
  echo 'return row.name + " [" + row.id + "]";';
 else
  echo 'return row.id + " [" + row.name + "]";';
 echo '},
		formatResult: function(row) {
			return  row.id + "::" + row.name;
		}
	});
	
	$(function() {
		$("#date").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd-mm-y"
		});

		$("#c_date").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd-mm-y"
		});
	});

});
</script>';
}


function access_control($user_group)
{
 if($user_group!='admin' && $user_group!='accountant') {
  echo '<script>alert("You Are Not Authorized to Access This Page! For More Information Contact Administrator."); self.location="home.php"</script>';
  exit;
 }
}
function group_class($rp){
 if($rp==1000) 		$cls='Asset';
 elseif($rp==2000) 	$cls='Liabilities';
 elseif($rp==3000) 	$cls='Income';
 elseif($rp==4000) 	$cls='Expense';
 else $cls=NULL;
 return $cls;
}
function next_group_id($cls)
{ global $conn;
 $max=(ceil(($cls+1)/1000))*1000;
 $min=$cls;
 $s='select max(group_id) from ledger_group where group_id>'.$min.' and group_id<'.$max;
 $sql=mysqli_query($conn, $s);
 if(mysqli_num_rows($sql)>0)
  $data=mysqli_fetch_row($sql);
 else
  $acc_no=$min+1;
 if(!isset($acc_no)&&(is_null($data[0])))
  $acc_no=$min+1;
 else
  $acc_no=$data[0]+1;
 return $acc_no;
}

function next_ledger_id($group_id)
{ global $conn;
 $max=($group_id*1000000000000)+1000000000000;
 $min=($group_id*1000000000000);
 $s='select max(ledger_id) from accounts_ledger where ledger_id like "%00000000" and ledger_id>'.$min.' and ledger_id<'.$max;
 $sql=mysqli_query($conn, $s);
 if(mysqli_num_rows($sql)>0)
  $data=mysqli_fetch_row($sql);
 else
  $acc_no=$min+100000000;
 if(!isset($acc_no)&&(is_null($data[0])))
  $acc_no=$min+100000000;
 else
  $acc_no=$data[0]+100000000;
 return $acc_no;
}


function under_ledger_id($id)
{ global $conn;
//***-***-***
 if(($id%100000000)==0)// group level
 {
  $max=($id)+100000000;
  $min=($id)-1;
  $add=10000;		// make ledger
  $s='select ledger_id from accounts_ledger where ledger_id>'.$min.' and ledger_id<'.$max.' order by ledger_id desc limit 1';
 }
 elseif(($ledger_id%10000)==0)// ledger level
 {
  $max=($id)+10000;
  $min=($id)-1;
  $add=1;		// make ledger
  $s='select sub_ledger_id from sub_ledger where sub_ledger_id>'.$min.' and sub_ledger_id<'.$max.' order by sub_ledger_id desc limit 1';
 }

 $sql=mysqli_query($conn, $s);
 if(mysqli_num_rows($sql)>0)
  $data=mysqli_fetch_row($sql);
 else
  $acc_no=($id)+$add;
 if(!isset($acc_no))
  $acc_no=$data[0]+$add;
 return $acc_no;
}


function next_sub_ledger_id($ledger_id)
{ global $conn;
 $max=$ledger_id+100000000;
 $min=$ledger_id;
 $s='select max(ledger_id) from accounts_ledger where ledger_id like "%0000" and ledger_id>'.$min.' and ledger_id<'.$max;
 $sql=mysqli_query($conn, $s);
 if(mysqli_num_rows($sql)>0)
  $data=mysqli_fetch_row($sql);
 else
  $acc_no=$min+10000;
 if(!isset($acc_no)&&(is_null($data[0])))
  $acc_no=$min+10000;
 else
  $acc_no=$data[0]+10000;
 return $acc_no;
}

function next_sub_sub_ledger_id($ledger_id)
{ global $conn;
 $max=number_format(($ledger_id+10000),0,'','');
 $min=number_format($ledger_id,0,'','');
 $s='select max(ledger_id) from accounts_ledger where ledger_id>'.$min.' and ledger_id<'.$max;
 $sql=mysqli_query($conn, $s);
 $c=mysqli_num_rows($sql);
 if($c>0)
  $data=mysqli_fetch_row($sql);
 else
  $acc_no=number_format(($min+1),0,'','');
 if(!isset($acc_no)&&(is_null($data[0])))
  $acc_no=number_format(($min+1),0,'','');
 else
  $acc_no=number_format(($data[0]+1),0,'','');
 return $acc_no;
}



function group_ledger_id($group_id)
{ global $conn;
 return $group_id*100000000;
}
function sub_ledger_create($sub_ledger_id,$name, $under, $balance, $now, $proj_id)
{ global $conn;
 $under=substr($sub_ledger_id,0,8).'00000000';
 $sql="INSERT INTO `sub_ledger` (
			`sub_ledger_id` ,
			`sub_ledger` ,
			`ledger_id` ,
			`opening_balance` ,
			`created_on` ,
			`proj_id`,
			 group_for,section_id,company_id,create_date,entry_by
			)
			VALUES ('$sub_ledger_id','$name', '$under', '$balance', '$now', '$proj_id','".$_SESSION['usergroup']."','".$_SESSION['sectionid']."','".$_SESSION['companyid']."','".$_SESSION['create_date']."','".$_SESSION['userid']."')";

 $query=mysqli_query($conn, $sql);
}
function sub_sub_ledger_create($sub_ledger_id,$name, $under, $balance, $now, $proj_id)
{ global $conn;
 $under=substr($sub_ledger_id,0,12).'0000';
 $sql="INSERT INTO `sub_sub_ledger` (
			`sub_sub_ledger_id` ,
			`sub_sub_ledger` ,
			`sub_ledger_id` ,
			`opening_balance` ,
			`created_on` ,
			`proj_id`,
			group_for,section_id,company_id,create_date,entry_by
			)
			VALUES ('$sub_ledger_id','$name', '$under', '$balance', '$now', '$proj_id','".$_SESSION['usergroup']."','".$_SESSION['sectionid']."','".$_SESSION['companyid']."','".$_SESSION['create_date']."','".$_SESSION['userid']."')";

 $query=mysqli_query($conn, $sql);
}


function ledger_create($ledger_id,$ledger_name,$ledger_group_id,$opening_balance,$balance_type,$depreciation_rate,$credit_limit, $opening_balance_on,$proj_id,$budget_enable='NO',$parent)
{
 global $conn;
 $ledger_group=substr($ledger_group_id,0,4);
 $sql="INSERT INTO `accounts_ledger` 
		(`ledger_id`,
		`ledger_name` ,
		`ledger_group_id` ,
		`opening_balance` ,
		`balance_type` ,
		`depreciation_rate` ,
		`credit_limit` ,
		`opening_balance_on` ,
		`proj_id`,
		`budget_enable`,
		group_for,section_id,company_id,create_date,entry_by,parent)
		VALUES ('$ledger_id','$ledger_name', '$ledger_group', '$opening_balance', '$balance_type', '$depreciation_rate', '$credit_limit', '$opening_balance_on','$proj_id','$budget_enable','".$_SESSION['usergroup']."','".$_SESSION['sectionid']."','".$_SESSION['companyid']."','".$_SESSION['create_date']."','".$_SESSION['userid']."','$parent')";
 if(mysqli_query($conn, $sql))
  return TRUE;
 else
  return FALSE;
}


function ledger_redundancy($ledger_name,$ledger_id='')
{ global $conn;
 if($ledger_id!='')
  $advance_check=" and ledger_id!='$ledger_id'";
 $check="select ledger_id from accounts_ledger where ledger_name='$ledger_name'".$advance_check;
 if(mysqli_num_rows(mysqli_query($conn, $check))>0)
  return FALSE;
 else
  return TRUE;
}


function group_redundancy($group_name,$manual_group_code='',$group_id='')
{ global $conn;
 if($manual_group_code!='')
  $add_check=" or manual_group_code='$manual_group_code'";
 if($group_id!='')
  $advance_check=" and group_id!='$group_id'";
 $check="select group_id from ledger_group where group_name='$group_name'".$add_check.$advance_check;
 if(mysqli_num_rows(mysqli_query($conn, $check))>0)
  return FALSE;
 else
  return TRUE;
}


function date_value($date)
{
 $j=0;
 for($i=0;$i<strlen($date);$i++)
 {
  if(is_numeric($date[$i]))
   $time[$j]=$time[$j].$date[$i];
  else $j++;
 }
 $time=mktime(0,0,0,$time[1],$time[0],$time[2]);
 return $time;
}






function unauthorised_page_tried_to_view($page_id,$url)
{
	    global $conn;
		global $ip;
        $tdates = date("Y-m-d");
        $day = date('l', strtotime($idatess));
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
        $timess = $dateTime->format("d-m-y  h:i A");
 $sql="INSERT INTO `unauthorised_page_tried_to_view` ( `page_id`, `url`, `user_id`, `date`, `time`, `ip`, `section_id`,`company_id`) VALUES ('$page_id', '$url', '$_SESSION[userid]', '$tdates','$timess','$ip','$_SESSION[sectionid]','$_SESSION[companyid]')";
 mysqli_query($conn, $sql);
 return 1;
}
?>



<?php ob_end_flush(); ?>