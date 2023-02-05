<?php
require_once 'support_file.php';
$title='Expenses Voucher Entry';


//Image Attachment Function
function image_upload_on_id2($path,$file,$id)
{   $root=$path.'/'.$id.'.jpg';
    move_uploaded_file($file['tmp_name'],$root);
    return $root;
}
function image_upload_on_id($path,$file,$id='')
{    if($file['name']!=''){
    $path_file = $path.basename($file['name']);
    $imageFileType = pathinfo($path_file,PATHINFO_EXTENSION);
    $root=$path.'/'.$id.'.'.$imageFileType;
    if($imageFileType != "jpg" && $imageFileType != "pdf" )
    {}
    else
        move_uploaded_file($file['tmp_name'],$root);
    return $root;
}

}
//Image Attachment Function

$unique='voucherno';
$unique_field='voucher_date';
$table_journal_master="journal_voucher_master";
$table_payment="payment";
$payment_unique='payment_no';
$page="acc_payment_voucher.php";
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
            $_SESSION[initiate_debit_note] = $_POST[$unique];
            $_POST['journal_type'] = 'Payment';
            $_POST['MANUAL'] = 'MANUAL';
            $crud->insert();
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
            $crud->update($unique);
            $type = 1;
            unset($_POST);
        }


//for single FG Add...........................
        if (isset($_POST['add'])) {
            if ($_POST[dr_amt] > 0) {
                $type = 'Debit';
            } elseif ($_POST[cr_amt] > 0) {
                $type = 'Credit';
            }
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
                echo "<script>alert('Yor are trying to input an invalid transaction!!'); window.location.href='".$page."';</script>";

            } else {
                if ((($_POST[dr_amt] || $_POST[cr_amt]) > 0) && ($_SESSION[initiate_debit_note]>0)) {
                    add_to_payment($_SESSION[initiate_debit_note],$date, $proj_id, $_POST[narration], $_POST[ledger_id], $_POST[dr_amt],
                        $_POST[cr_amt], $type,$cur_bal,$_POST[paid_to],$_POST[Cheque_No],$c_date,$_POST[Cheque_of_bank],$manual_payment_no,$_POST[cc_code],$_POST[subledger_id],MANUAL,$ip,$_POST[receipt_date],$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
                        ,$thisday,$thismonth,$thisyear,$receive_ledger);
                    $_SESSION[debit_note_last_narration]=$_POST[narration];
                }
                if ($_FILES["attachment"]["tmp_name"] != '') {
                    $path = '../page/payment_attch/' . $_SESSION[initiate_debit_note] . '.jpg';
                    move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
                }
            }}

    } // end post unique
} // end prevent_multi_submit



if($_SESSION['initiate_debit_note']>0){
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
    $ids=$uncheckrow[jid];
    if (isset($_POST['confirmsave']) && ($uncheckrow[payment_no]>0)) {
        add_to_journal_new($uncheckrow[paymentdate], $proj_id, $jv, $uncheckrow[payment_date], $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt], Payment, $uncheckrow[payment_no], $uncheckrow[jid], $uncheckrow[cc_code], $uncheckrow[sub_ledger_id], $_SESSION[usergroup], $uncheckrow[cheq_no], $uncheckrow[cheq_date], $create_date, $ip, $now, $uncheckrow[day_name], $thisday, $thismonth, $thisyear);} // end of confirm


    if(isset($_POST['deletedata'.$ids]))
    {  $res=mysqli_query($conn, ("DELETE FROM ".$table_payment." WHERE id=".$ids));
        unset($_POST);
    } // end of deletedata
    if(isset($_POST['editdata'.$ids]))
    {  mysqli_query($conn, ("UPDATE ".$table_payment." SET ledger_id='".$_POST[ledger_id]."', cc_code='".$_POST[cc_code]."',narration='".$_POST[narration]."',dr_amt='".$_POST[dr_amt]."',cr_amt='".$_POST[cr_amt]."' WHERE id=".$ids));
        unset($_POST);
    } // end of editdata
}
if (isset($_GET[id])) {
    $edit_value=find_all_field(''.$table_payment.'','','id='.$_GET[id].'');
}
if (isset($_POST['confirmsave'])) {
    $up_payment="UPDATE ".$table_payment." SET entry_status='UNCHECKED' where ".$payment_unique."=".$_SESSION['initiate_debit_note']."";
    $up_query=mysqli_query($conn, $up_payment);
    $up_master=mysqli_query($conn, "UPDATE journal SET status='UNCHECKED' where jv_no=".$jv);
    $up_master=mysqli_query($conn, "UPDATE ".$table_journal_master." SET entry_status='UNCHECKED' where ".$unique."=".$_SESSION['initiate_debit_note']."");

    unset($_SESSION['initiate_debit_note']);
    unset($_SESSION['debit_note_last_narration']);
    unset($_POST);
    unset($$unique);

} // if insert confirm


//for Delete..................................
if (isset($_POST['cancel'])) {
    $crud = new crud($table_payment);
    $condition =$payment_unique."=".$_SESSION['initiate_debit_note'];
    $crud->delete_all($condition);
    $crud = new crud($table_journal_master);
    $condition=$unique."=".$_SESSION['initiate_debit_note'];
    $crud->delete($condition);
    unset($_SESSION['initiate_debit_note']);
    unset($_SESSION['debit_note_last_narration']);
    unset($_POST);
    unset($$unique);
}
$COUNT_details_data=find_a_field(''.$table_payment.'','Count(id)',''.$payment_unique.'='.$_SESSION['initiate_debit_note'].'');

// data query..................................
$condition=$unique."=".$_SESSION['initiate_debit_note'];
    $data=db_fetch_object($table_journal_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$sql2="select a.tr_no,a.jvdate as Date,a.jv_no as Voucher_No,SUM(a.dr_amt) as amount
from  journal a where a.tr_from='Payment' and a.user_id='$_SESSION[userid]' and a.section_id='$_SESSION[sectionid]' and a.company_id='$_SESSION[companyid]'  group by a.tr_no  order by a.id desc limit 10";
$rs="Select 
j.id as jid,
concat(a.ledger_id, ' : ' ,a.ledger_name) as Account_Head,
c.center_name as cost_center,j.narration,j.dr_amt,j.cr_amt  
from 
payment j,
accounts_ledger a,
cost_center c
where 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 j.entry_status='MANUAL' and 
 j.payment_no='".$_SESSION['initiate_debit_note']."'";

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
            <a  style="float: right" class="btn btn-sm btn-default"  href="warehouse_payment_voucher_single.php">
                <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Single Entry</span></a>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                <table align="center" style="width:100%">
                    <tr>
                        <th style="width:15%;">Transaction Date<span class="required">*</span></th><th style="width: 2%;">:</th>
                        <td><input type="date" id="voucher_date"  required="required" name="voucher_date" value="<?=($voucher_date!='')? $voucher_date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" ></td>
                        <th style="width:15%;">Transaction No<span class="required">*</span></th><th style="width: 2%">:</th>
                        <td><input type="text" required="required" name="<?=$unique?>" id="<?=$unique?>"  value="<?=($_SESSION['initiate_debit_note']!='')? $_SESSION['initiate_debit_note'] : automatic_voucher_number_generate($table_payment,$payment_unique,1,2); ?>" class="form-control col-md-7 col-xs-12" readonly style="width: 90%; font-size: 11px;"></td>
                    </tr>

                    <tr>
                        <th style="">Person to</th><th>:</th>
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
                </table>



                <?php if($_SESSION[initiate_debit_note]){
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
                            <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 11px">Update Payment Voucher</button>
                        </div></div>

                    <div class="form-group" <?=$display;?>>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <a  href="voucher_print_preview.php?v_type=payment&vo_no=<?=$_SESSION[initiate_debit_note];?>&v_date=<?=$voucher_date;?>" target="_blank" style="color: blue; text-decoration: underline; font-size: 11px; font-weight: bold; vertical-align: middle">View Payment Voucher</a>
                        </div></div>
                <?php   } else {?>
                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="initiate" class="btn btn-primary" style="font-size: 11px">Initiate Payment Voucher</button>
                        </div></div>
                <?php } ?>

            </form></div></div></div>


<?=recentvoucherview($sql2,'voucher_view_popup_ismail.php','payment','171px');?>
<?php if($_SESSION[initiate_debit_note]):  ?>
    <form action="<?=$page;?>" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="<?=$unique?>" id="<?=$unique?>" value="<?=$_SESSION[initiate_debit_note];?>">
        <input type="hidden" name="payment_no" id="payment_no" value="<?=$_SESSION[initiate_debit_note];?>">
        <input type="hidden" name="receipt_date" id="receipt_date" value="<?=$voucher_date;?>">
        <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
        <input type="hidden" name="paid_to" id="paid_to" value="<?=$paid_to;?>">
        <?php if($Cheque_Date>0){ ?>
            <input type="hidden" name="Cheque_Date" id="Cheque_Date" value="<?=$Cheque_Date;?>">
        <?php } ?>
        <input type="hidden" name="Cheque_of_bank" id="Cheque_of_bank" value="<?=$Cheque_of_bank;?>">
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tbody>
            <tr style="background-color: bisque">
                <th style="text-align: center">Cash , Bank & Expenses Head</th>
                <th style="text-align: center">Cost Center</th>
                <th style="text-align: center">Narration</th>
                <th style="text-align: center">Attachment</th>
                <th style="width:5%; text-align:center">Amount</th>
                <th style="text-align:center">Action</th>
            </tr>
            <tbody>
            <tr>
                <td style="width: 25%; vertical-align: middle" align="center">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id">
                        <option></option>
                        <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $edit_value->ledger_id, 'status=1'); ?>
                    </select></td>
                <td align="center" style="width: 10%;vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="cc_code" id="cc_code">
                        <option></option>
                        <?php foreign_relation('cost_center', 'id', 'CONCAT(id,"-", center_name)', $edit_value->cc_code, 'status=1'); ?>
                    </select></td>
                <td style="width:15%;vertical-align: middle" align="center"><textarea  id="narration" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="narration" value="<?=$_POST[narration];?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" ><?=($edit_value->narration!='')? $edit_value->narration : $_SESSION['debit_note_last_narration'];?></textarea>

                </td>
                <td style="width:10%;vertical-align: middle" align="center">
                    <input type="file" id="attachment" style="width:100%; height:37px; font-size: 11px; text-align:center"    name="attachment" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                <td align="center" style="width:10%">
                    <?php if (isset($_GET[id])) { ?>
                        <input type="number" id="dr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center"  value="<?=$edit_value->dr_amt;?>" <?php if($edit_value->dr_amt>0)  echo ''; else echo 'readonly'; ?>  name="dr_amt" placeholder="Debit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                        <input type="number" id="cr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center; margin-top: 5px"  value="<?=$edit_value->cr_amt;?>" <?php if($edit_value->cr_amt>0)  echo ''; else echo 'readonly'; ?>  name="cr_amt" placeholder="Credit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                    <?php } else { ?>
                        <input type="number" id="dr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center"    name="dr_amt" placeholder="Debit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                        <input type="number" id="cr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center; margin-top: 5px" name="cr_amt" placeholder="Credit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                    <?php } ?>
                </td>
                <td align="center" style="width:5%; vertical-align: middle "><?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                    <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
            </tbody>
        </table>
        <input name="count" id="count" type="hidden" value="" />
    </form>


    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

<?=voucher_delete_edit($rs,$unique,$_SESSION['initiate_debit_note'],$COUNT_details_data);?><br><br>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>