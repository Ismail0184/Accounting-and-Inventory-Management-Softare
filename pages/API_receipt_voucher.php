<?php
include 'base.php';

$table_receipt="receipt";
$recpt_unique='receipt_no';

function automatic_voucher_number_generate($table,$parameter,$condition,$voucher_type){
    global $conn;
$date=date('Y-m-d');
$keyword=$_GET['entry_by'].$voucher_type;
list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $date);
$sql="Select ".$parameter." from ".$table." where create_date='".$date."' and entry_by=".$_GET[entry_by]." and section_id=".$_GET[sectionid]." and company_id=".$_GET[companyid]." and ".$condition." and ".$parameter." like '".$keyword."%' ORDER BY ".$parameter." DESC LIMIT 1";
$result=mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0){
    $output=$keyword.substr($year1,2,3).$month.$day."001";
} else {
    while($row = mysqli_fetch_array($result)) {
        $sl= substr($row[$parameter],-3);
        $sl=$sl+1;
        if (strlen($sl)==1) {
            $sl="00".$sl;
        } else if (strlen($sl)==2){
            $sl="0".$sl;
        }
        $output=$keyword.substr($year1,2,3).$month.$day.$sl;
    }}return $output;}
$receiv_no=automatic_voucher_number_generate($table_receipt,$recpt_unique,1,1);
$create_date=date('Y-m-d');
if($_GET['create_order']==1) {
if (($_GET[ledger_id_dr]> 0) && (($_GET[dr_amt] && $_GET[ledger_id_cr]) > 0) && ($_GET[cr_amt]>0)) {
    $Sql_Query_Create_Order_dr = mysqli_query($conn, "insert into receipt (receipt_no,receiptdate,narration,ledger_id,dr_amt,cr_amt,entry_by,create_date,ip,section_id,company_id,received_from,entry_status) values 
('$receiv_no','$_GET[receiptdate]','$_GET[narration]','$_GET[ledger_id_dr]','$_GET[dr_amt]','0','$_GET[entry_by]','$create_date','$ip','$_GET[sectionid]','$_GET[companyid]','External','UNCHECKED')");
    $Sql_Query_Create_Order_cr = mysqli_query($conn, "insert into receipt (receipt_no,receiptdate,narration,ledger_id,dr_amt,cr_amt,entry_by,create_date,ip,section_id,company_id,received_from,entry_status) values 
('$receiv_no','$_GET[receiptdate]','$_GET[narration]','$_GET[ledger_id_cr]','0','$_GET[cr_amt]','$_GET[entry_by]','$create_date','$ip','$_GET[sectionid]','$_GET[companyid]','External','UNCHECKED')");
}


if (($_GET[ledger_id_dr_2]> 0) && (($_GET[dr_amt_2] && $_GET[ledger_id_cr_2]) > 0) && ($_GET[cr_amt_2]>0)) {

    $Sql_Query_Create_Order_dr_2 = mysqli_query($conn, "insert into receipt (receipt_no,receiptdate,narration,ledger_id,dr_amt,cr_amt,entry_by,create_date,ip,section_id,company_id,received_from,entry_status) values 
('$receiv_no','$_GET[receiptdate]','$_GET[narration]','$_GET[ledger_id_dr_2]','$_GET[dr_amt_2]','0','$_GET[entry_by]','$create_date','$ip','$_GET[sectionid]','$_GET[companyid]','External','UNCHECKED')");

    $Sql_Query_Create_Order_cr_2 = mysqli_query($conn, "insert into receipt (receipt_no,receiptdate,narration,ledger_id,dr_amt,cr_amt,entry_by,create_date,ip,section_id,company_id,received_from,entry_status) values 
('$receiv_no','$_GET[receiptdate]','$_GET[narration]','$_GET[ledger_id_cr_2]','','$_GET[cr_amt_2]','$_GET[entry_by]','$create_date','$ip','$_GET[sectionid]','$_GET[companyid]','External','UNCHECKED')");
}
        $return_URL=$_GET[return_back_URL].'?delete_commend=1';
        header("Location: ".$return_URL."");

} else { echo 'Invalid Request';}
mysqli_close($conn);
?>

