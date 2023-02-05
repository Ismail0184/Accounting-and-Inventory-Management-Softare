<?php

require_once 'base.php';
function sum_com($conn, $com,$fdate,$tdate,$sec_com_connection)
{   global $conn;
    $sql = mysqli_query($conn,'select sum(j.dr_amt-j.cr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.group_for='.$_SESSION['usergroup'].' and j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and g.com_id in ('.$com.')'.$sec_com_connection);
	$a = mysqli_fetch_array($sql,  MYSQLI_ASSOC);
	$amount = $a[amt];
	return $amount;
}

function sum_cc_code($conn,$cc_code,$fdate,$tdate,$sec_com_connection)
{   
global $conn;
$sql = mysqli_query($conn,'select sum(j.dr_amt-j.cr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and j.cc_code in ('.$cc_code.')'.$sec_com_connection);
	$a = mysqli_fetch_array($sql,  MYSQLI_ASSOC);
	$amount = $a[amt];
	return $amount;
}

function sum_com_sub($conn, $com,$fdate,$tdate,$subgroup,$last_subgroup,$sec_com_connection)
{ global $conn;
	if($subgroup>0){
		$sub_group_conn=' and l.ledger_id between "'.$subgroup.'" and "'.$last_subgroup.'"';
	} else {
		$sub_group_conn='';
	}
	$sql = mysqli_query($conn, 'select sum(j.dr_amt-j.cr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.group_for='.$_SESSION['usergroup'].' and j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and g.com_id in ('.$com.')'.$sec_com_connection.$sub_group_conn);
	$a = mysqli_fetch_array($sql,  MYSQLI_ASSOC);
	$amount = $a[amt];
	return $amount;
}


function sum_com_liabilities($conn,$com,$fdate,$tdate,$sec_com_connection)
{   global $conn;
$sql = mysqli_query($conn, 'select sum(j.cr_amt-j.dr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.group_for='.$_SESSION['usergroup'].' and j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and g.com_id in ("'.$com.'")'.$sec_com_connection);
	$a = mysqli_fetch_array($sql,  MYSQLI_ASSOC);
	$amount = $a[amt];
	return $amount;
}

function sum_com_P_L($conn,$fdate,$tdate,$sec_com_connection)
{   global $conn;
$sql_income = mysqli_query($conn, 'select sum(j.cr_amt-j.dr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.group_for='.$_SESSION['usergroup'].' and j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and g.group_class in ("3000")'.$sec_com_connection);
	$a_income = mysqli_fetch_array($sql_income,  MYSQLI_ASSOC);
	$amount_income = $a_income[amt];

	$sql_expenses = mysqli_query($conn, 'select sum(j.dr_amt-j.cr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.group_for='.$_SESSION['usergroup'].' and j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and g.group_class in ("4000")'.$sec_com_connection);
	$a_expenses = mysqli_fetch_array($sql_expenses,  MYSQLI_ASSOC);
	$amount_expenses = $a_expenses[amt];
	return $amount_income-$amount_expenses;
}

function sum_com_sub_PL_cr($conn, $com,$fdate,$tdate,$sec_com_connection)
{   global $conn;
    $sectionid=$_SESSION['sectionid'];
	$companyid=$_SESSION['companyid'];
	$sql = mysqli_query($conn, 'select sum(j.cr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.group_for='.$_SESSION['usergroup'].' and j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and g.com_id in ('.$com.')'.$sec_com_connection);
	$a = mysqli_fetch_array($sql,  MYSQLI_ASSOC);
	$amount = $a[amt];
	return $amount;
}
function sum_com_sub_PL_dr($conn, $com,$fdate,$tdate,$sec_com_connection)
{   global $conn;
    $sectionid=$_SESSION['sectionid'];
	$companyid=$_SESSION['companyid'];
	$sql = mysqli_query($conn, 'select sum(j.dr_amt) as amt from journal j,accounts_ledger l, ledger_group g where j.group_for='.$_SESSION['usergroup'].' and j.jvdate between "'.$fdate.'" and "'.$tdate.'" and j.ledger_id=l.ledger_id and l.ledger_group_id=g.group_id and g.com_id in ('.$com.')'.$sec_com_connection);
	$a = mysqli_fetch_array($sql,  MYSQLI_ASSOC);
	$amount = $a[amt];
	return $amount;
}




function next_invoice($field,$table)
{   global $conn;
	$start=date('y').sprintf("%02d",$_SESSION['usergroup']).'0'.'00001';
	$end=date('y').sprintf("%02d",($_SESSION['usergroup'])).'1'.'00000';
	$sql="select max(".$field.") from ".$table." where ".$field." between '$start' and '$end'";
	$query=mysqli_fetch_row(mysqli_query($conn, $sql));
	$value=$query[0]+1;
	if($query[0] == 0)
		$value=$start;
	return $value;
}


function next_journal_voucher_id()
{   global $conn;
	$jv_no=mysqli_fetch_row(mysqli_query($conn, "select MAX(jv_no) from journal"));
	$p_id= date("Ymd")."0000";

	if($jv_no[0]>$p_id)
		$jv=$jv_no[0]+1;
	else
		$jv=$p_id+1;
	return $jv;
}


function next_journal_bank_voucher_id()
{   global $conn;
	$jv_no=mysqli_fetch_row(mysqli_query($conn, "select MAX(jv_no) from secondary_journal_bank"));
	$p_id= date("Ymd")."0000";
	if($jv_no[0]>$p_id)
		$jvbank=$jv_no[0]+1;
	else
		$jvbank=$p_id+1;
	return $jvbank;
}



function getSVALUE($table_name, $fld_name, $con){
	global $conn;
		$result=mysqli_query($conn, "select ".$fld_name." from ".$table_name.' '.$con);
		$row=mysqli_fetch_array($result);
		$getVALUEs=$row[0]; //mysql_result($result,0,0);
		return $getVALUEs;
}?>
    
   