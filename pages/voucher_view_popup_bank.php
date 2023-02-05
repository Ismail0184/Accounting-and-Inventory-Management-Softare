<?php
require_once 'support_file.php';
$proj_id='icpbd';
$unique='voucherno';
$unique_field='voucher_date';
$table_journal_master="journal_voucher_master";
$secondary_journal_bank="secondary_journal_bank";
$payment_unique='payment_no';
$page="bank_voucher_checked.php";
$crud      =new crud($table_journal_master);
$$unique = $_POST[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');
$jv=next_journal_voucher_id();

if(prevent_multi_submit()) {

    if (isset($_POST['confirmsave'])) {

        $dd = $_POST[confirm_date];
        $confirm_date= date('Y-m-d', strtotime($dd));
        $date = date('d-m-Y', strtotime($dd));
        $j = 0;
        for ($i = 0; $i < strlen($date); $i++) {
            if (is_numeric($date[$i])) {
                $time[$j] = $time[$j] . $date[$i];
            } else {
                $j++;
            }
        }
        $date = mktime(0, 0, 0, $time[1], $time[0], $time[2]);



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
a.*,c.center_name as cname 
from 
secondary_payment j,
accounts_ledger a,
cost_center c
where 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 j.payment_no='".$_GET[v_no]."'";
        $re_query = mysqli_query($conn, $rs);
        while ($uncheckrow = mysqli_fetch_array($re_query)) {
            add_to_journal_new($confirm_date, $proj_id, $jv, $uncheckrow[payment_date], $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt], Payment, $uncheckrow[payment_no], $uncheckrow[jid], $uncheckrow[cc_code], $uncheckrow[sub_ledger_id], $_SESSION[usergroup], $uncheckrow[cheq_no], $uncheckrow[cheq_date], $create_date, $ip, $now, $uncheckrow[day_name], $thisday, $thismonth, $thisyear);
        }

        $up_payment=mysqli_query($conn, "UPDATE ".$secondary_journal_bank." SET status='COMPLETE' where tr_no=".$_GET[v_no]."");
        $up_payment2=mysqli_query($conn, "UPDATE secondary_payment SET entry_status='SETTLED' where payment_no=".$_GET[v_no]."");
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";

    }// if insert confirm



if (isset($_POST['bounced'])) {
$deleted=mysqli_query($conn, 'Update secondary_payment SET entry_status="BOUNCED" WHERE payment_no='.$_GET[v_no].'');
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}
}

if(isset($_REQUEST['v_no']))
{
$sql1="select narration,cheq_no,cheq_date,' ',jv_date,cc_code,sub_ledger_id,jvdate,tr_no,jvdate from secondary_journal_bank where tr_no='$_GET[v_no]'  limit 1";
$data1=mysqli_fetch_row(mysqli_query($conn, $sql1));
$sql1."<br>";
$cheque_id=find_a_field('secondary_payment','distinct cheque_id','payment_no='.$_GET[v_no]);
?>


    <?php require_once 'header_content.php'; ?>
    <?php if(isset($_GET[v_no])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_content">
            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                <? require_once 'support_html.php';?>
                <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                    <tr>
                        <td width="15%" align="right"><strong>Voucher  No:</strong></td>
                        <td  align="left"><?= $data1[8];?>&nbsp;</td>
                        <td align="right"><strong>Cheque No:</strong> </td>
                        <td align="left"><input name="cheq_no" id="cheq_no" type="text" readonly value="<?=find_a_field('Cheque_Book','Cheque_number','id='.$cheque_id);?>" style="width:100px" /></td>
                        <td align="right" valign="top"><strong>Purpose</strong>:</td>
                        <td align="left" valign="top" colspan="2"><?php echo $data1[0];?>&nbsp;</td>
                    </tr>
                    <tr style="height:30px">
                        <td align="right"><strong>Voucher Date:</strong></td>
                        <td align="left"><input style="width: 150px" name="vdate" type="date" readonly value="<?=$data1[9];?>" /></td>
                        <td  align="right" valign="middle"><strong>Issued Date: </strong></td>
                        <td width="" align="left" valign="middle"><input name="cheq_date" readonly  type="date" value="<?=$data1[9]?>" style="width:150px" /></td>
                        <td height="20" align="right"><strong>Confirm Date:</strong></td>
                        <td align="left"><input required name="confirm_date" id="confirm_date" type="date" /></td>
                    </tr>
                    </table>





            <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                <tr align="center" style="background-color: bisque">
                    <th>S/L</th>
                    <th>A/C Ledger</th>
                    <th>Narration</th>
                    <th style="width: 10%">Debit</th>
                    <th style="width: 10%">Credit</th>
                </tr>
                <?php
                $pi=0;
                $d_total=0;
                $c_total=0;
                $sql2="select a.dr_amt,a.cr_amt,b.ledger_name,b.ledger_id,a.narration,a.id,a.cc_code from accounts_ledger b, secondary_journal_bank a where a.ledger_id=b.ledger_id  and a.tr_no='$_GET[v_no]' and a.ledger_id>0";
                $data2=mysqli_query($conn, $sql2);
                while($info=mysqli_fetch_row($data2)){ $pi++;
                $entry[$pi] = $info[5];
                    if($info[0]==0) $type='Credit';
                    else $type='Debit';
                    $d_total=$d_total+$info[0];
                    $c_total=$c_total+$info[1];
                    ?>
                    <tr align="center">
                        <td><?=$pi;?></td>
                        <td style="width: 15%">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_<?=$info[5]?>" id="ledger_<?=$info[5]?>">
                                <option value="<?=$info[3];?>" selected><?=$info[3].' : '.$info[2];?></option>
                                <option></option>
                                <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id,"-", ledger_name)', $ledger_id, ' ledger_group_id not in  ("1006")'); ?>
                            </select>
                        <td><textarea  id="narration" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="narration_<?=$info[5];?>" id="narration_<?=$info[5];?>" class="form-control col-md-7 col-xs-12" autocomplete="off" ><?=$info[4];?></textarea>
                            <input type="hidden" name="l_<?=$pi;?>" id="l_<?=$pi;?>" value="<?=$info[3];?>" />
                        </td>
                        <td style="vertical-align: middle"><input name="dr_amt_<?=$info[5];?>" class="form-control col-md-7 col-xs-12" type="text" id="dr_amt_<?=$info[5];?>" value="<?=$info[0]?>" style="width:98%; height:37px; font-size: 11px; text-align:right" readonly /></td>
                        <td style="vertical-align: middle"><input name="cr_amt_<?=$info[5];?>" class="form-control col-md-7 col-xs-12" type="text" id="cr_amt_<?=$info[5];?>" value="<?=$info[1]?>" style="width:98%; height:37px; font-size: 11px; text-align:right" readonly /></td>
                    </tr>
                <?php }  ?>
            </table>
    <?php
    $GET_status=find_a_field('secondary_payment','entry_status','payment_no='.$_GET[v_no]);
    if($GET_status=='PREMATURE' || $GET_status=='MANUAL'){  ?>
<button style="float: left; margin-left:1%; font-size: 11px" type="submit" name="bounced" id="bounced" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Completed?");'>Cheque Bounced</button></td>
<button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="confirmsave" id="confirmsave" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Confirm the cheque settelement</button></td>
    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Cheque has been '.$GET_status.' !!</i></h6>'; } ?>

        </form>
        </div></div></div>
    <?php  }  ?>
<?=$html->footer_content();mysqli_close($conn);?>