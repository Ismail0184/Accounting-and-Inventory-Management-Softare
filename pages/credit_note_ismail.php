<?php
require_once 'support_file.php';
$title='Receipt Voucher';


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
$table_receipt="receipt";
$recpt_unique='receipt_no';
$page="credit_note.php";
$crud      =new crud($table_journal_master);
$$unique = $_POST[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');
$jv=next_journal_voucher_id();

if(prevent_multi_submit()) {
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

            if (($_POST[dr_amt] || $_POST[cr_amt]) > 0) {


                add_to_receipt($_SESSION[initiate_credit_note], $date, $proj_id, $_POST[narration], $_POST[ledger_id], $_POST[dr_amt],
                    $_POST[cr_amt], Debit, $cur_bal, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                    , $thisday, $thismonth, $thisyear, $_POST[party_ledger]);


                if ($_POST[rcved_remining]==$_POST[dr_amt]) {
                    add_to_receipt($_SESSION[initiate_credit_note], $date, $proj_id, $_POST[narration], $_POST[party_ledger], 0,
                        $_POST[amount], Credit, $cur_bal, $_POST[paid_to], $_POST[Cheque_No], $c_date, $_POST[Cheque_of_bank], $manual_payment_no, $_POST[cc_code], $_POST[subledger_id], MANUAL, $ip, $_POST[receipt_date], $_SESSION[sectionid], $_SESSION[companyid], $_SESSION[userid], $create_date, $now, $day
                        , $thisday, $thismonth, $thisyear, $_POST[ledger_id]);
                }


                $_SESSION[credit_note_last_narration]=$_POST[narration];
            }
            if ($_FILES["attachment"]["tmp_name"] != '') {
                $path = '../page/receipt_attch/' . $_SESSION[initiate_credit_note] . '.jpg';
                move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
            }
        }}


    if (isset($_POST['confirmsave'])) {
        $rs = "Select 
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
j.day_name,
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
 entry_status='MANUAL' and 
 j.receipt_no='" . $_SESSION['initiate_credit_note'] . "'
 ";
        $re_query = mysqli_query($conn, $rs);
        while ($uncheckrow = mysqli_fetch_array($re_query)) {
            add_to_journal_new($uncheckrow[receiptdate], $proj_id, $jv, $uncheckrow[receipt_date], $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt], Receipt, $uncheckrow[receipt_no], $uncheckrow[jid], $uncheckrow[cc_code], $uncheckrow[sub_ledger_id], $_SESSION[usergroup], $uncheckrow[cheq_no], $uncheckrow[cheq_date], $create_date, $ip, $now, $uncheckrow[day_name], $thisday, $thismonth, $thisyear);

        }
        $up_master="UPDATE ".$table_receipt." SET entry_status='UNCHECKED' where ".$recpt_unique."=".$_SESSION['initiate_credit_note']."";
        $up_query=mysqli_query($conn, $up_master);
        unset($_SESSION['initiate_credit_note']);
        unset($_SESSION['credit_note_last_narration']);
        unset($_POST);
        unset($$unique);

    }// if insert confirm
}

//for single FG Delete..................................
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
 entry_status='MANUAL' and 
 j.receipt_no='".$_SESSION['initiate_credit_note']."'";
$re_query=mysqli_query($conn, $rs);
while($uncheckrow=mysqli_fetch_array($re_query)){
    $ids=$uncheckrow[jid];
    if(isset($_POST['deletedata'.$ids]))
    {  mysqli_query($conn, ("DELETE FROM ".$table_receipt." WHERE id='$ids'"));
        unset($_POST);
    }}


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

$COUNT_details_data=find_a_field(''.$table_receipt.'','Count(id)',''.$recpt_unique.'='.$_SESSION['initiate_credit_note'].'');

// data query..................................
if(isset($_SESSION['initiate_credit_note']))
{   $condition=$unique."=".$_SESSION['initiate_credit_note'];
    $data=db_fetch_object($table_journal_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$sql2="select a.tr_no, a.jvdate as Date,a.jv_no as Voucher_No,SUM(a.dr_amt) as amount
from  journal a where a.tr_from='Receipt' and a.user_id='$_SESSION[userid]' and a.section_id='$_SESSION[sectionid]' and a.company_id='$_SESSION[companyid]'  group by a.tr_no  order by a.id desc limit 10";
$data2=mysqli_query($conn, $sql2);


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
 entry_status='MANUAL' and 
 j.receipt_no='".$_SESSION['initiate_credit_note']."'
 ";
$re_query=mysqli_query($conn, $rs);
$inputted_amount=find_a_field('receipt','SUM(dr_amt)','receipt_no="'.$_SESSION['initiate_credit_note'].'"');
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
<?php require_once 'body_content.php'; ?>

    <div class="col-md-7 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                    <table align="center" style="width:100%">
                        <tr>
                            <th style="width:15%;">Voucher Date</th><th style="width: 2%;">:</th>
                            <td><input type="date" id="voucher_date"  required="required" name="voucher_date" value="<?=$voucher_date;?>" max="<?=$_SESSION[create_date];?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" ></td>

                            <th style="width:15%;">Voucher No</th><th style="width: 2%">:</th>
                            <td><input type="text" required="required" name="<?=$unique?>" id="<?=$unique?>"  value="<?php if($_SESSION['initiate_credit_note']>0){ echo $_SESSION['initiate_credit_note'];} else { echo
                                $_SESSION['creditvoucherNOW']; } ?>" class="form-control col-md-7 col-xs-12"  readonly style="width: 90%; font-size: 11px;"></td>
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
                            <th style="">Received From</th><th>:</th>
                            <td colspan="3" style="padding-top: 5px;"><select class="select2_single form-control" style="width:98%; font-size: 11px" tabindex="-1" required="required"  name="party_ledger" id="party_ledger">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $party_ledger, 'ledger_group_id in ("1006")'); ?>
                                </select></td>
                            <td ><input type="text" id="amount"   value="<?=$amount;?>" name="amount"  class="form-control col-md-7 col-xs-12" placeholder="Rcvd. Amt" required="required" style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle">
                            </td>
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


    <div class="col-md-5 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Recent Voucher</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" style="overflow:scroll; height:210px;">
            <?=$crud->recent_voucher_view($sql2,'voucher_view_popup_ismail.php','receipt');?>
            </div></div></div>


<?php if($_SESSION[initiate_credit_note]){  ?>

    <form action="" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="receipt_no" id="receipt_no" value="<?=$_SESSION[initiate_credit_note];?>">
        <input type="hidden" name="receipt_date" id="receipt_date" value="<?=$voucher_date;?>">
        <input type="hidden" name="amount" id="amount" value="<?=$amount;?>">
        <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
        <input type="hidden" name="paid_to" id="paid_to" value="<?=$paid_to;?>">
        <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
        <input type="hidden" name="party_ledger" id="party_ledger" value="<?=$party_ledger;?>">
        <?php if($Cheque_Date>0){ ?>
            <input type="hidden" name="Cheque_Date" id="Cheque_Date" value="<?=$Cheque_Date;?>">
        <?php } ?>
        <input type="hidden" name="Cheque_of_bank" id="Cheque_of_bank" value="<?=$Cheque_of_bank;?>">
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tbody>
            <tr style="background-color: bisque">
                <th style="text-align: center">Cash , Bank & Others</th>
                <th style="text-align: center">Profit Center</th>
                <th style="text-align: center">Narration</th>
                <th style="text-align: center">Attachment</th>
                <th style="width:5%; text-align:center">Amount</th>
                <th style="text-align:center">#</th>
            </tr>
            <tbody>
            <tr>
                <td style="width: 25%; vertical-align: middle" align="center">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id">
                        <option></option>
                        <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, 'ledger_group_id in ("1002","4007")'); ?>
                    </select></td>
                <td align="center" style="width: 10%;vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="profit_center">
                        <option></option>
                        <?php foreign_relation('profit_center', 'id', 'CONCAT(id," : ", center_name)', $profit_center, '1'); ?>
                    </select></td>
                <td style="width:15%;vertical-align: middle" align="center">
                    <textarea  id="narration" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="narration" value="<?=$_POST[narration];?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" ><?=$_SESSION['credit_note_last_narration'];?></textarea></td>
                <td style="width:10%;vertical-align: middle" align="center">
                    <input type="file" id="attachment" style="width:100%; height:37px; font-size: 11px; text-align:center"    name="attachment" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                <td align="center" style="width:10%; vertical-align: middle">
                    <input type="hidden" id="rcved_remining" style="width:100%; height:37px; font-size: 11px; text-align:center"  value="<?=$rcved_remining=$amount-$inputted_amount;?>"  name="rcved_remining" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                    <input type="text" id="dr_amt" onkeyup="doAlert(this.form);" style="width:100%; height:37px; font-size: 11px; text-align:center"  value="<?=$rcved_remining;?>"  name="dr_amt" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                <td align="center" style="width:5%; vertical-align: middle "><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button></td></tr>
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
        <input name="count" id="count" type="hidden" value="" />
    </form>


    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

    <?php
    if($_GET[type]=='delete'){
        if($_GET[productdeletecode]){
            $results=mysqli_query($conn, ("Delete from receipt where id='$_GET[productdeletecode]'")); ?>
            <meta http-equiv="refresh" content="0;credit_note.php">
        <?php  } } ?>


    <form id="ismail" name="ismail"  method="post" style="font-size: 11px"  class="form-horizontal form-label-left">
        <?php if($COUNT_details_data>0) { ?>
            <table align="center" class="table table-striped table-bordered" style="width:98%">
                <thead>
                <tr style="background-color: bisque">
                    <th>SL</th>
                    <th>Account Head</th>
                    <th style="text-align:center">Profit Center</th>
                    <th style="text-align:center">Narration</th>
                    <th style="text-align:center">Attachment</th>
                    <th style="width:10%; text-align:center">Debit</th>
                    <th style="width:10%; text-align:center">Credit</th>
                    <th style="width:15%; text-align:center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php while($uncheckrow=mysqli_fetch_array($re_query)){ $ids=$uncheckrow[jid]; ?>
                    <tr>
                        <td style="width:3%; vertical-align:middle"><?=$js=$js+1; ?></td>
                        <td style="vertical-align:middle"><?=$uncheckrow[ledger_id];?>-<?=$uncheckrow[ledger_name];?></td>
                        <td style="vertical-align:middle"><?=$uncheckrow[id];?>-<?=$uncheckrow[profit_name]; ?></td>
                        <td style="vertical-align:middle; width: 20%"><?=$uncheckrow[narration] ;?></td>
                        <td style="vertical-align:middle; text-align: center; width: 10%"><a href="#" target="_blank" style="color: blue; text-decoration: underline"><?=$uncheckrow[receipt_no] ;?></a></td>
                        <td align="center" style="width:6%; text-align:center; vertical-align:middle"><?=$uncheckrow[dr_amt] ;?></td>
                        <td align="center" style="width:6%; text-align:center; vertical-align:middle"><?=$uncheckrow[cr_amt] ;?></td>
                        <td align="center" style="width:10%;vertical-align:middle">
                            <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete Credit Voucher?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                        </td>
                    </tr>
                    <?php
                    $totaldr=$totaldr+$uncheckrow[dr_amt];
                    $totalcr=$totalcr+$uncheckrow[cr_amt];
                } ?>
                </tbody>
                <tr>
                    <td colspan="5" style="font-weight:bold;" align="right">Total</td>
                    <td align="center" ><strong><?=number_format($totaldr,2); ?></strong></td>
                    <td align="center" ><strong><?=number_format($totalcr,2); ?></strong></td>
                    <td></td>
                </tr></table>
        <?php }  ?>

        <button style="float: left; font-size: 11px; margin-left: 1%" type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Delete the Voucher </button>
        <?php if($COUNT_details_data>0) { ?>
            <?php if($totaldr==$totalcr){ ?>
                <button style="float: right; font-size: 11px; margin-right: 1%" type="submit" name="confirmsave" class="btn btn-success">Confirm and Finish Voucher </button>
            <?php } else { ?>
                <font  style="font-size: 13px; color: red; font-weight: bold; float: right; margin-right: 1%">Invalid Credit Voucher. Debit and Credit Amount Are Not Equal !!</font>
            <?php }} ?>

    </form><br><br></div></div></div>
<?php } mysqli_close($conn); ?>
<?php require_once 'footer_content.php' ?>