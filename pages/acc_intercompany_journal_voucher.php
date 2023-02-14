<?php
require_once 'support_file.php';
$title='Intercompany Receipt Voucher';


if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";
$url.= $_SERVER['HTTP_HOST'];
$url.= $_SERVER['REQUEST_URI'];

$unique='voucherno';
$unique_field='voucher_date';
$table_journal_master="journal_voucher_master";
$table_receipt="receipt";
$recpt_unique='receipt_no';
$page="acc_intercompany_journal_voucher.php";
$crud      =new crud($table_journal_master);
$$unique = $_POST[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');
$jv=next_journal_voucher_id();
if(prevent_multi_submit()) {
    if(isset($_POST[$unique]))
    {

        if (isset($_POST['initiate'])) {
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['ip'] = $ip;
            $d = $_POST[voucher_date];
            $_POST[voucher_date] = date('Y-m-d', strtotime($d));
            if($_POST[Cheque_Date]>0){
                $ckd = $_POST[Cheque_Date];
                $_POST[Cheque_Date] = date('Y-m-d', strtotime($ckd));
            } else {
                $_POST[Cheque_Date]='';
            }
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:s:i');
            $_SESSION[initiate_credit_note] = $_POST[$unique];
            $_POST['journal_type'] = 'Receipt';
            $_POST['status'] = 'MANUAL';
            $_POST['party_ledger'] = $_POST[ledger_id_1];
            $crud->insert();
            $_SESSION[API_client_id]=find_a_field('acc_intercompany','client_id','ledger_id='.$_POST[ledger_id_1]);
            unset($_POST);
        }

//for modify PS information ...........................
        if (isset($_POST['modify'])) {
            $d = $_POST[voucher_date];
            $_POST[voucher_date] = date('Y-m-d', strtotime($d));
            if($_POST[Cheque_Date]>0){
                $ckd = $_POST[Cheque_Date];
                $_POST[Cheque_Date] = date('Y-m-d', strtotime($ckd));
            } else {
                $_POST[Cheque_Date]='';
            }
            $_POST['edit_at'] = time();
            $_POST['edit_by'] = $_SESSION['userid'];
            $_POST['party_ledger'] = $_POST[ledger_id_1];
            $crud->update($unique);
            $type = 1;
            $_SESSION[API_client_id]=find_a_field('acc_intercompany','client_id','ledger_id='.$_POST[ledger_id_1]);
            unset($_POST);
        }


//for single FG Add...........................
        if (isset($_POST['add'])) {
            $dd = $_POST[receipt_date];
            $date = date('d-m-y', strtotime($dd));
            $j = 0;
            for ($i = 0; $i < strlen($date); $i++) {
                if (is_numeric($date[$i])) {
                    $time[$j] = $time[$j] . $date[$i];
                } else {
                    $j++;
                }
            }
            $date = mktime(0, 0, 0, $time[1], $time[0], $time[2]);

            if($_POST[Cheque_Date]) {
                $c_dd = $_POST[Cheque_Date];
                $c_date = date('d-m-y', strtotime($c_dd));
                $j = 0;
                for ($i = 0; $i < strlen($c_date); $i++) {
                    if (is_numeric($c_date[$i])) {
                        $ptime[$j] = $ptime[$j] . $c_date[$i];
                    } else {
                        $j++;
                    }
                }
                $c_date = mktime(0, 0, 0, $ptime[1], $ptime[0], $ptime[2]);
            } else {
                $c_date='';
            }

            $tdates = date("Y-m-d");
            $day = date('l', strtotime($idatess));
            $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            $timess = $dateTime->format("d-m-y  h:i A");


            if (($_POST[dr_amt] && $_POST[cr_amt]) > 0) {
                echo "<script>alert('Yor are trying to input an invalid transaction!!')</script>";
                echo $targeturl;
            } else {

                if (($_POST[amount_1]> 0) && (($_POST[ledger_id_1] && $_POST[ledger_id_2]) > 0) && ($_SESSION[initiate_credit_note]>0)) {
                    add_to_receipt($_SESSION[initiate_credit_note], $date, $proj_id, $_POST[narration], $_POST[ledger_id_1], $_POST[amount_1],
                        $_POST[cr_amt], Debit, $cur_bal, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                        , $thisday, $thismonth, $thisyear, $_POST[ledger_id_2]);
                    add_to_receipt($_SESSION[initiate_credit_note], $date, $proj_id, $_POST[narration], $_POST[ledger_id_2], 0,
                            $_POST[amount_1], Credit, $cur_bal, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                            , $thisday, $thismonth, $thisyear, $_POST[ledger_id_1]);
                }
                if (($_POST[amount_1]> 0) && (($_POST[Ex_debit_ledger] && $_POST[Ex_credit_ledger]) > 0) && ($_SESSION[initiate_credit_note]>0)) {
                    add_to_receipt($_SESSION[initiate_credit_note], 1845854380, $proj_id, $_POST[narration], $_POST[Ex_debit_ledger], $_POST[amount_1],
                        0, Debit, 1, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                        , $thisday, $thismonth, $thisyear, $_POST[Ex_credit_ledger]);
                        add_to_receipt($_SESSION[initiate_credit_note], 1845854380, $proj_id, $_POST[narration], $_POST[Ex_credit_ledger], 0,
                            $_POST[amount_1], Credit, 2, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                            , $thisday, $thismonth, $thisyear, $_POST[ledger_id_1]);
                }
                if (($_POST[amount_3]> 0) && (($_POST[Ex_debit_ledger] && $_POST[Ex2_credit_ledger]) > 0) && ($_SESSION[initiate_credit_note]>0)) {
                    add_to_receipt($_SESSION[initiate_credit_note], 1845854380, $proj_id, $_POST[narration], $_POST[Ex_debit_ledger], $_POST[amount_3],
                        0, Debit, 3, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                        , $thisday, $thismonth, $thisyear, $_POST[Ex2_credit_ledger]);
                    add_to_receipt($_SESSION[initiate_credit_note], 1845854380, $proj_id, $_POST[narration], $_POST[Ex2_credit_ledger], 0,
                        $_POST[amount_3], Credit, 4, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                        , $thisday, $thismonth, $thisyear, $_POST[Ex_debit_ledger]);
                }


            }}
    } // end post unique
} // end prevent_multi_submit


if($_SESSION['initiate_credit_note']>0){
    $rs="Select 
j.id as jid,
j.receipt_no,
j.receipt_date,
j.receiptdate,
j.narration,
j.ledger_id,
j.dr_amt,
j.cr_amt,
j.type,
j.cheq_no,
j.cheq_date,
j.bank,
j.cc_code,
j.sub_ledger_id,
a.*,c.center_name as cname 
from 
receipt j,
accounts_ledger a,
cost_center c
  where 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 j.receipt_date not in ('1845854380') and 
 entry_status='MANUAL' and 
 j.receipt_no='".$_SESSION['initiate_credit_note']."'";
    $re_query=mysqli_query($conn, $rs);
    while($uncheckrow=mysqli_fetch_array($re_query)){
        $ids=$uncheckrow[jid];
        if (isset($_POST['confirmsave']) && ($uncheckrow[receipt_no]>0)) {
            add_to_journal_new($uncheckrow[receiptdate], $proj_id, $jv, $uncheckrow[receipt_date], $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt], Receipt, $uncheckrow[receipt_no], $uncheckrow[jid], $uncheckrow[cc_code], $uncheckrow[sub_ledger_id], $_SESSION[usergroup], $uncheckrow[cheq_no], $uncheckrow[cheq_date], $create_date, $ip, $now, $uncheckrow[day_name], $thisday, $thismonth, $thisyear);
            }

        if(isset($_POST['deletedata'.$ids]))
        {  mysqli_query($conn, ("DELETE FROM ".$table_receipt." WHERE id=".$ids));
            $_SESSION['initiate_credit_note']=$_SESSION['initiate_credit_note'];
            unset($_POST);
        }
        if(isset($_POST['editdata'.$ids]))
        {  mysqli_query($conn, ("UPDATE ".$table_receipt." SET ledger_id='".$_POST[ledger_id]."', pc_code='".$_POST[pc_code]."',narration='".$_POST[narration]."',dr_amt='".$_POST[dr_amt]."',cr_amt='".$_POST[cr_amt]."' WHERE id=".$ids));
            unset($_POST);
        }
    }
    if (isset($_GET[id])) {
        $edit_value=find_all_field(''.$table_receipt.'','','id='.$_GET[id].'');
    }

    if (isset($_POST['confirmsave'])) {
        $up_master=mysqli_query($conn, "UPDATE ".$table_receipt." SET entry_status='UNCHECKED' where ".$recpt_unique."=".$_SESSION['initiate_credit_note']."");
        $up_master=mysqli_query($conn, "UPDATE journal SET status='UNCHECKED' where jv_no=".$jv);
        $up_master=mysqli_query($conn, "UPDATE ".$table_journal_master." SET entry_status='UNCHECKED' where ".$unique."=".$_SESSION['initiate_credit_note']."");
        $up_query=mysqli_query($conn, $up_master);
        $external_dr_voucher_data=find_all_field(''.$table_receipt.'','','receipt_date in ("1845854380") and cur_bal=1 and receipt_no='.$_SESSION['initiate_credit_note']);
        $external_dr_voucher_data_2=find_all_field(''.$table_receipt.'','','receipt_date in ("1845854380") and cur_bal=3 and receipt_no='.$_SESSION['initiate_credit_note']);
        $external_cr_voucher_data=find_all_field(''.$table_receipt.'','','receipt_date in ("1845854380") and cur_bal=2 and receipt_no='.$_SESSION['initiate_credit_note']);
        $external_cr_voucher_data_2=find_all_field(''.$table_receipt.'','','receipt_date in ("1845854380") and cur_bal=4 and receipt_no='.$_SESSION['initiate_credit_note']);
        $targeturl='http://icpbd-erp.com/51816/cmu_mod/page/API_receipt_voucher.php?create_order=1&ledger_id_dr_2='.$external_dr_voucher_data_2->ledger_id.'&ledger_id_cr_2='.$external_cr_voucher_data_2->ledger_id.'&dr_amt_2='.$external_dr_voucher_data_2->dr_amt.'&cr_amt_2='.$external_cr_voucher_data_2->cr_amt.'&dr_amt='.$external_dr_voucher_data->dr_amt.'&cr_amt='.$external_cr_voucher_data->cr_amt.'&receiptdate='.$external_dr_voucher_data->receiptdate.'&narration='.$external_dr_voucher_data->narration.'&ledger_id_dr='.$external_dr_voucher_data->ledger_id.'&ledger_id_cr='.$external_cr_voucher_data->ledger_id.'&entry_by='.$_SESSION[userid].'&sectionid='.$_SESSION[sectionid].'&companyid='.$_SESSION[companyid].'&return_back_URL='.$url.'';
        unset($_SESSION['initiate_credit_note']);
        unset($_SESSION['credit_note_last_narration']);
        unset($_POST);
        unset($$unique);
        header("Location: ".$targeturl."");
    }




//for Delete..................................
    if (isset($_POST['cancel'])) {
        $crud = new crud($table_receipt);
        $condition =$recpt_unique."=".$_SESSION['initiate_credit_note'];
        $crud->delete_all($condition);
        $crud = new crud($table_journal_master);
        $condition=$unique."=".$_SESSION['initiate_credit_note'];
        $crud->delete($condition);
        unset($_SESSION['initiate_credit_note']);
        unset($_SESSION['credit_note_last_narration']);
        unset($_POST);
        unset($$unique);
    }

    $COUNT_details_data=find_a_field(''.$table_receipt.'','Count(id)',''.$recpt_unique.'='.$_SESSION['initiate_credit_note'].' and receipt_date not in ("1845854380")');

// data query..................................
    $condition=$unique."=".$_SESSION['initiate_credit_note'];
    $data=db_fetch_object($table_journal_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}
    $inputted_amount=find_a_field('receipt','SUM(dr_amt)','receipt_no="'.$_SESSION['initiate_credit_note'].'"');
}
$sql2="select a.tr_no, a.jvdate as Date,a.jv_no as Voucher_No,SUM(a.dr_amt) as amount
from  journal a where a.tr_from='Receipt' and a.user_id='$_SESSION[userid]' and a.section_id='$_SESSION[sectionid]' and a.company_id='$_SESSION[companyid]'  group by a.tr_no  order by a.id desc limit 10";
$rs = "Select 
j.id as jid,
concat(a.ledger_id, ' : ' ,a.ledger_name) as Account_Head,c.center_name as 'Profit_Center',j.narration,j.dr_amt,j.cr_amt 
from 
receipt j,
accounts_ledger a,
cost_center c
  where 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 j.entry_status='MANUAL' and 
 j.receipt_date not in ('1845854380') and 
 j.receipt_no='" . $_SESSION['initiate_credit_note'] . "'";
$intercompany_ledger="Select a.ledger_id,concat(i.client_id, ' : ' ,a.ledger_name) as ledger_name from accounts_ledger a,acc_intercompany i where a.ledger_id=i.ledger_id";
$dealer_info="Select a.ledger_id,a.ledger_name from accounts_ledger a,dealer_info d where a.ledger_id=d.account_code and d.canceled in ('Yes')";

if($_GET['delete_commend']==1) {
    $delete_external_receipt=mysqli_query($conn, "delete from ".$table_receipt." where receipt_date='1845854380'");
    header("Location: ".$page."");}
$find_API_bank_ledger=find_all_field('dev_API_received','','API_name="API_bank_ledger" and status=1 and client_id='.$_SESSION[API_client_id]);
$find_API_intercompany_ledger=find_all_field('dev_API_received','','API_name="API_intercompany_ledger" and status=1 and client_id='.$_SESSION[API_client_id]);
$find_API_customer_list=find_all_field('dev_API_received','','API_name="API_customer_list" and status=1 and client_id='.$_SESSION[API_client_id]);
?>

<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]:focus {
        background-color: lightblue;
    }
</style>
<script type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }
</script>
<?php require_once 'body_content_entry_mod.php'; ?>

<div class="col-md-8 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <a style="float: right" class="btn btn-sm btn-default"  href="acc_receipt_voucher.php">
                <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Single Entry</span>
            </a>
            <a style="float: right" class="btn btn-sm btn-default"  href="acc_receipt_voucher_multiple.php">
                <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Multiple Entry</span>
            </a>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page;?>" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                <table align="center" style="width:100%">
                    <tr>
                        <th style="width:15%;">Transaction Date</th><th style="width: 2%;">:</th>
                        <td><input type="date" id="voucher_date"  required="required" name="voucher_date" value="<?=($voucher_date!='')? $voucher_date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" ></td>
                        <th style="width:15%;">Transaction No</th><th style="width: 2%">:</th>
                        <td><input type="text" required="required" name="<?=$unique?>" id="<?=$unique?>"  value="<?php if($_SESSION['initiate_credit_note']>0){ echo $_SESSION['initiate_credit_note'];} else { echo
                            automatic_voucher_number_generate($table_receipt,$recpt_unique,1,1); } ?>" class="form-control col-md-7 col-xs-12" readonly style="width: 90%; font-size: 11px;"></td>
                    </tr>
                    <tr>
                        <th style="">Person From</th><th>:</th>
                        <td><input type="text" id="paid_to"  value="<?=$paid_to;?>" name="paid_to" class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px; font-size: 11px;" ></td>
                        <th>Of Bank</th><th>:</th>
                        <td><input type="text" name="Cheque_of_bank" id="Cheque_of_bank" value="<?=$Cheque_of_bank;?>" class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px; font-size: 11px;"></td>
                    </tr>
                    <tr>
                        <th style="">Cheque No</th><th>:</th>
                        <td><input type="text" id="Cheque_No"  value="<?=$Cheque_No;?>" name="Cheque_No"  class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px; font-size: 11px;" ></td>
                        <th>Cheque Date</th><th>:</th>
                        <td><input type="date" id="Cheque_Date"   value="<?=$Cheque_Date;?>" name="Cheque_Date"  class="form-control col-md-7 col-xs-12"  style="width: 90%; margin-top: 5px; font-size: 11px; vertical-align: middle"></td>
                    </tr>

                    <tr>
                        <th style="">Received Company Account</th><th>:</th>
                        <td colspan="4" style="padding-top: 5px;"><select class="select2_single form-control" style="width:96%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id_1" id="ledger_id_1">
                                <option></option>
                                <?=advance_foreign_relation($intercompany_ledger,$party_ledger);?>
                            </select></td>
                    </tr>
                </table>

                <?php if($_SESSION[initiate_credit_note]){
                    if($COUNT_details_data>0) {
                        $ml='40';
                        $display='style="margin-left:40%; margin-top: 22px;"';

                    } else {
                        $ml='40';
                        $display='style="margin-left:40%; margin-top: 15px; display: none"';
                    }
                    ?>
                    <div class="form-group" style="margin-left:<?=$ml;?>%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 11px">Update Receipt Voucher</button>
                        </div></div>

                    <div class="form-group" <?=$display;?>>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <a  href="voucher_print_preview.php?v_type=Receipt&vo_no=<?=$_SESSION[initiate_credit_note];?>&v_date=<?=$voucher_date;?>" target="_blank" style="color: blue; text-decoration: underline; font-size: 11px; font-weight: bold; vertical-align: middle">View Receipt Voucher</a>
                        </div></div>
                <?php   } else {?>
                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="initiate" class="btn btn-primary" style="font-size: 11px">Initiate Receipt Voucher</button>
                        </div></div>
                <?php } ?>

            </form></div></div></div>


<?=recentvoucherview($sql2,'voucher_view_popup_ismail.php','receipt','213px');?>
<?php if($_SESSION[initiate_credit_note]):  ?>
    <form action="<?=$page;?>" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="receipt_no" id="receipt_no" value="<?=$_SESSION[initiate_credit_note];?>">
        <input type="hidden" name="<?=$unique?>" id="<?=$unique?>"  value="<?=$_SESSION['initiate_credit_note'];?>">
        <input type="hidden" name="receipt_date" id="receipt_date" value="<?=$voucher_date;?>">
        <input type="hidden" name="amount" id="amount" value="<?=$amount;?>">
        <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
        <input type="hidden" name="paid_to" id="paid_to" value="<?=$paid_to;?>">
        <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
        <?php if($Cheque_Date>0){ ?>
            <input type="hidden" name="Cheque_Date" id="Cheque_Date" value="<?=$Cheque_Date;?>">
        <?php } ?>
        <input type="hidden" name="Cheque_of_bank" id="Cheque_of_bank" value="<?=$Cheque_of_bank;?>">
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tbody>
            <tr style="background-color: bisque">
                <th style="text-align: center; width: 10%">For</th>
                <th style="text-align: center">Bank / Debit Ledger</th>
                <th style="text-align: center">Customer / Credit Ledger</th>
                <th style="text-align: center">Narration</th>
                <th style="width:12%; text-align:center;">Amount</th>
                <th style="text-align:center;">Action</th>
            </tr>
            <tbody>
            <tr>
                <th style="vertical-align: middle; text-align: center">Internal Journal</th>
                <td style="vertical-align: middle; width: 38%">
                    <select class="select2_single form-control" style="width:99%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id_1">
                        <option></option>
                        <?=advance_foreign_relation($intercompany_ledger,$party_ledger);?>
                    </select></td>
                <td tyle="vertical-align: middle; width: 38%">
                    <select class="select2_single form-control" style="width:99%; font-size: 11px"    name="ledger_id_2">
                        <option></option>
                        <?=advance_foreign_relation($dealer_info,$ledger_id_2);?>
                    </select></td>
                <td rowspan="3" style="width:15%;vertical-align: middle" align="center">
                    <textarea  id="narration" style="width:100%; height:149px; font-size: 11px; text-align:center"  name="narration"  class="form-control col-md-7 col-xs-12" autocomplete="off" ><?=($edit_value->narration!='')? $edit_value->narration : $_SESSION['credit_note_last_narration']. 'Ref: '.$_SESSION['initiate_credit_note'].',';?></textarea></td>
                <td align="center" rowspan="2" style="width:10%; vertical-align: middle;">
                    <input type="number" id="amount_1" style="width:99%; font-size: 11px; text-align:center"  value="<?=$edit_value->dr_amt;?>"  name="amount_1" class="form-control col-md-7 col-xs-12" required placeholder="amt 1 & 2" autocomplete="off" step="any" min="1" />
                </td>
                <td rowspan="3" align="center" style="width:5%; vertical-align: middle ">
                    <?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                    <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>
            </tr>
            <tr>
                <th rowspan="2" style="vertical-align: middle; text-align: center">External Journal<br>vai API</th>
                <td rowspan="2" style="vertical-align: middle;">
                    <select class="select2_single form-control" style="width:99%" tabindex="-1" required name="Ex_debit_ledger">
                        <option></option>
                        <?php
                        $characters = json_decode(file_get_contents($find_API_bank_ledger->API_endpoint)); // decode the JSON feed
                        foreach ($characters as $character) :;?>
                            <option value="<?=$character->ledger_id;?>"><?=$character->ledger_name;?></option> <?php endforeach;  ?>
                    </select></td>
                <td tyle="vertical-align: middle">
                    <select class="select2_single form-control" style="width:99%" tabindex="-1" required  name="Ex_credit_ledger">
                        <?php
                        $characters = json_decode(file_get_contents($find_API_intercompany_ledger->API_endpoint)); // decode the JSON feed
                        foreach ($characters as $character) :;?>
                            <option value="<?=$character->ledger_id;?>" <?php if($find_API_intercompany_ledger=$character->client_id) echo 'selected';?>><?=$character->ledger_name;?></option> <?php endforeach;  ?>
                    </select></td></tr>

            <tr>
                <td tyle="vertical-align: middle">
                    <select class="select2_single form-control" style="width:99%" tabindex="-1"  name="Ex2_credit_ledger">
                        <option></option>
                        <?php
                        $characters = json_decode(file_get_contents($find_API_customer_list->API_endpoint)); // decode the JSON feed
                        foreach ($characters as $character) :;?>
                            <option value="<?=$character->account_code;?>"><?=$character->dealer_name_e;?></option> <?php endforeach;  ?>
                    </select></td>
                <td align="center" style="width:10%; vertical-align: middle;">
                    <input type="number" id="amount_3" style="width:99%; font-size: 11px; text-align:center"  value="<?=$edit_value->dr_amt;?>"  name="amount_3" class="form-control col-md-7 col-xs-12" autocomplete="off" placeholder="amt 3" step="any" min="1" />
                </td>
            </tr>
            </tbody>
        </table>
        <SCRIPT language=JavaScript>
            function doAlert(form)
            {
                var val=form.dr_amt.value;
                var val2=form.rcved_remining.value;
                if (Number(val)>Number(val2)){
                    alert('oops!! Exceed Received Limit!! Thanks');
                    form.dr_amt.value='';
                }
                form.dr_amt.focus();
            }</script>
    </form>



<?=voucher_delete_edit($rs,$unique,$_SESSION['initiate_credit_note'],$COUNT_details_data);?><br><br>
<?php endif; mysqli_close($conn); ?>
<?=$html->footer_content();?> 