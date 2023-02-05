<?php
 require_once 'base.php';
function next_chalan_no($depot_id,$chalan_date){
	global $conn;
    list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $chalan_date);
    $sdate=substr($year1,2,3).$month.$day;
    $e_ch = $sdate.sprintf('%02s', $depot_id).'999';
    $s_ch = $sdate.sprintf('%02s', $depot_id).'000';
    $unit = 1;
    $sql = 'select max(chalan_no) from sale_do_chalan where chalan_no between "'.$s_ch.'" and "'.$e_ch.'" ';
    $query = mysqli_query($conn, $sql);
    $data=mysqli_fetch_row($query);
    if($data[0]<$s_ch)
        $ch_no = $s_ch+$unit;
    else
        $ch_no = $data[0]+$unit;
    return $ch_no;
    }

function automatic_number_generate($keyword,$table,$parameter,$condition,$digit){
        if (!empty($digit)) {
            $digit=$digit;
        } else {
            $digit='00';
        }
	global $conn;
	if($table==NULL) return NULL;
    $bdtime=date_default_timezone_set('Asia/Dhaka');
    $date=date('Y-m-d');
	list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $date);
    $sql="Select ".$parameter." from ".$table." where ".$condition." ORDER BY ".$parameter." DESC LIMIT 1";
    $result=mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0){
        $output=$keyword.substr($year1,2,3).$month.$day.$digit."1";
    } else {
    while($row = mysqli_fetch_object($result)) {
            $sl= substr($row->$parameter,-3);
            $sl=$sl+1;
            if (strlen($sl)==1) {
                $sl=$digit.$sl;
            } if (strlen($sl)==2){
                $sl=substr($digit,1).$sl;
            }
            else if (strlen($sl)==3){
                $sl=substr($digit,2).$sl;
            }
            $output=$keyword.substr($year1,2,3).$month.$day.$sl;
        }}
		return $output;
		mysqli_close($conn);
		} 


function automatic_voucher_number_generate($table,$parameter,$condition,$voucher_type){
	global $conn;	
    $date=date('Y-m-d');
	$keyword=$_SESSION['userid'].$voucher_type;
	list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $date);
	$sql="Select ".$parameter." from ".$table." where create_date='".date('Y-m-d')."' and entry_by=".$_SESSION[userid]." and section_id=".$_SESSION[sectionid]." and company_id=".$_SESSION[companyid]." and ".$condition." and ".$parameter." like '".$keyword."%' ORDER BY ".$parameter." DESC LIMIT 1";
    $result=mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0){
		$output=$keyword.substr($year1,2,3).$month.$day."001";
    } else {
        while($row = mysqli_fetch_object($result)) {
            $sl= substr($row->$parameter,-3);
            $sl=$sl+1;
            if (strlen($sl)==1) {
                $sl="00".$sl;
            } else if (strlen($sl)==2){
                $sl="0".$sl;
            }
			$output=$keyword.substr($year1,2,3).$month.$day.$sl;
        }}return $output;
		mysqli_close($conn);} ?>
	
			
		
	


<?php

function ps_no(){
    global $conn;
    $bdtime=date_default_timezone_set('Asia/Dhaka');
    $sekeyword='PS'.$_SESSION['userid'];
    $idatess=date('Y-m-d');
    $sql="Select custom_pr_no from production_floor_receive_master where create_date='".$idatess."' and  custom_pr_no like '$sekeyword%'  ORDER BY custom_pr_no DESC LIMIT 1";
    $result=mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0){
        $idates=date('Y-m-d');
        list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $idatess);
        $tdatevalye=substr($year1,2,3).$month.$day;
        $ps_no=$sekeyword.$tdatevalye."001";
    } else {
        while($row = mysqli_fetch_object($result)) {
            $sl= substr($row->custom_pr_no,-3);
            $sl=$sl+1;
            if (strlen($sl)==1) {
                $sl="00".$sl;
            } else if (strlen($sl)==2){
                $sl="0".$sl;
            }$idatess=date('Y-m-d');
            list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $idatess);
            $tdatevalye=substr($year1,2,3).$month.$day;
            $ps_no= $sekeyword.$tdatevalye.$sl;
        }}
    return $ps_no;
    mysqli_close($conn);
} ?>