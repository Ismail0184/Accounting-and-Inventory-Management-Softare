<?php
require_once 'support_file.php';
$title='Issued Cheque';


$unique='v_no';
$unique_field='voucher_date';
$table_journal_master="secondary_payment";
$table_payment="payment";
$payment_unique='payment_no';
$page="voucher_view_popup_bank.php";
$crud      =new crud($table_journal_master);
$$unique = $_POST[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');
$jv=next_journal_voucher_id();

if(prevent_multi_submit()) {

    if (isset($_POST['confirmsave'])) {
        $rs = "Select 
j.id as jid,
j.payment_no,
j.payment_date,
j.paymentdate,
j.narration,
j.ledger_id,
j.dr_amt,
j.cr_amt,
j.type,
j.cheq_no,
j.cheq_date,
j.bank,
j.cc_code,
j.day_name,
a.*,c.center_name as cname 
from 
payment j,
accounts_ledger a,
cost_center c
where 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 j.entry_status='MANUAL' and 
 j.payment_no='".$_SESSION['initiate_debit_note']."'
 ";
        $re_query = mysqli_query($conn, $rs);
        while ($uncheckrow = mysqli_fetch_array($re_query)) {
            add_to_journal_new($uncheckrow[paymentdate], $proj_id, $jv, $uncheckrow[payment_date], $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt], Payment, $uncheckrow[payment_no], $uncheckrow[jid], $uncheckrow[cc_code], $uncheckrow[sub_ledger_id], $_SESSION[usergroup], $uncheckrow[cheq_no], $uncheckrow[cheq_date], $create_date, $ip, $now, $uncheckrow[day_name], $thisday, $thismonth, $thisyear);
        }
        $up_payment="UPDATE ".$table_payment." SET entry_status='UNCHECKED' where ".$payment_unique."=".$_SESSION['initiate_debit_note']."";
        $up_query=mysqli_query($conn, $up_payment);
        unset($_SESSION['initiate_debit_note']);
        unset($_POST);
        unset($$unique);

    }// if insert confirm
}

$bankquery="SELECT j.*,a.*,u.*,cb.* FROM 
".$table_journal_master." j, 
accounts_ledger a, 
user_activity_management u,
Cheque_Book cb
WHERE j.ledger_id=a.ledger_id and j.status in ('') and cb.id=j.cheque_id  and j.dr_amt>0 and j.user_id=u.user_id";
?>


    <?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 230,top = -1");}
</script>
    <?php require_once 'body_content.php'; ?>


<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[f_date]) echo $_POST[f_date]; else echo date('Y-m-01');?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if($_POST[t_date]) echo $_POST[t_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Issued Cheque</button></td>
            </tr></table>
    </form>
    <?php
    if(isset($_POST[viewreport])){
        $res="SELECT sp.payment_no,cb.Cheque_number,(select ledger_name from accounts_ledger where ledger_id=sub_ledger_id) as Bank_name,a.ledger_name as 'Vendor / Party Name',FORMAT(SUM(sp.cr_amt),2) as amount,cb.cheque_issued_date as issued_date,cb.maturity_date,CONCAT(DATEDIFF(cb.maturity_date,now()), ', Days') as remaining_days,entry_status as status from 
secondary_payment sp,accounts_ledger a,Cheque_Book cb 
where 
  sp.ledger_id=a.ledger_id and cb.cheque_issued_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and cb.id=sp.cheque_id group by sp.payment_no";
    } else {
$res="SELECT sp.payment_no,cb.Cheque_number,(select ledger_name from accounts_ledger where ledger_id=sub_ledger_id) as Bank_name,a.ledger_name as 'Vendor / Party Name',FORMAT(SUM(sp.cr_amt),2) as amount,cb.cheque_issued_date as issued_date,cb.maturity_date,CONCAT(DATEDIFF(cb.maturity_date,now()), ', Days') as remaining_days,entry_status as status from
secondary_payment sp,accounts_ledger a,Cheque_Book cb 
where 
  sp.ledger_id=a.ledger_id and sp.entry_status in ('PREMATURE') and cb.id=sp.cheque_id group by sp.payment_no";}
    echo $crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
