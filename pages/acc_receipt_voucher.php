<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
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
$page="acc_receipt_voucher.php";
$crud      =new crud($table_journal_master);
$create_date=date('Y-m-d');
$jv=next_journal_voucher_id();
if(prevent_multi_submit()) {
    if(isset($_POST[$unique]))
    {
        if (isset($_POST['initiate'])) {
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['ip'] = $ip;
            $d = $_POST['voucher_date'];
            $_POST['voucher_date'] = date('Y-m-d', strtotime($d));
            if($_POST['Cheque_Date']>0){
                $ckd = $_POST['Cheque_Date'];
                $_POST['Cheque_Date'] = date('Y-m-d', strtotime($ckd));
            } else {
                $_POST['Cheque_Date']='';
            }
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:s:i');
            $_SESSION['initiate_credit_note'] = $_POST[$unique];
            $_POST['journal_type'] = 'Receipt';
            $_POST['status'] = 'MANUAL';
            $crud->insert();
            unset($_POST);
        }

//for modify PS information ...........................
        if (isset($_POST['modify'])) {
            $d = $_POST['voucher_date'];
            $_POST['voucher_date'] = date('Y-m-d', strtotime($d));
            if($_POST['Cheque_Date']>0){
                $ckd = $_POST['Cheque_Date'];
                $_POST['Cheque_Date'] = date('Y-m-d', strtotime($ckd));
            } else {
                $_POST['Cheque_Date']='';
            }
            $_POST['edit_at'] = time();
            $_POST['edit_by'] = $_SESSION['userid'];
            $crud->update($unique);
            $type = 1;
            unset($_POST);
        }


//for single FG Add...........................
        if (isset($_POST['add'])) {
            $tdates = date("Y-m-d");
            $day = date('l', strtotime($idatess));
            $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            $timess = $dateTime->format("d-m-y  h:i A");
            $date=date('Y-m-d');
            $POST_dr_amt = @$_POST['dr_amt'];
            $POST_cr_amt = @$_POST['cr_amt'];
            $c_date = 0;
            $cur_bal = 0;
            $manual_payment_no = 0;
            $cc_code = @$_POST['cc_code'];
            $subledger_id = @$_POST['subledger_id'];
            if (($POST_dr_amt && $POST_cr_amt) > 0) {
                echo "<script>alert('Yor are trying to input an invalid transaction!!')</script>";
            } else {

                if ((($POST_dr_amt || $POST_cr_amt) > 0) && ($_SESSION['initiate_credit_note']>0)) {
                    add_to_receipt($_SESSION['initiate_credit_note'], $date, $proj_id, $_POST['narration'], $_POST['ledger_id'], $_POST['dr_amt'],
                        $POST_cr_amt,'Debit', $cur_bal, $_POST['paid_to'], $_POST['Cheque_No'], $c_date, $_POST['Cheque_of_bank'], $manual_payment_no,$cc_code,$subledger_id,'MANUAL', $ip, $_POST['receipt_date'], $_SESSION['sectionid'], $_SESSION['companyid'], $_SESSION['userid'], $create_date, $now, $day
                        , $thisday, $thismonth, $thisyear, $_POST['party_ledger']);
                    if ($_POST['rcved_remining']==$_POST['dr_amt']) {
                        add_to_receipt($_SESSION['initiate_credit_note'], $date, $proj_id, $_POST['narration'], $_POST['party_ledger'], 0,
                            $_POST['amount'],'Credit', $cur_bal, $_POST['paid_to'], $_POST['Cheque_No'], $c_date, $_POST['Cheque_of_bank'], $manual_payment_no,$cc_code,$subledger_id,'MANUAL', $ip, $_POST['receipt_date'], $_SESSION['sectionid'], $_SESSION['companyid'], $_SESSION['userid'], $create_date, $now, $day
                            ,$thisday, $thismonth, $thisyear, $_POST['ledger_id']);
                    }
                    $_SESSION['credit_note_last_narration']=$_POST['narration'];
                }
                if ($_FILES["attachment"]["tmp_name"] != '') {
                    $path = '../page/receipt_attch/' . $_SESSION['initiate_credit_note'] . '.jpg';
                    move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
                }
            }}
    } // end post unique
} // end prevent_multi_submit
$initiate_credit_note = @$_SESSION['initiate_credit_note'];
if($initiate_credit_note>0){
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
j.day_name,
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
    $ids=$uncheckrow['jid'];
    if (isset($_POST['confirmsave']) && ($uncheckrow['receipt_no']>0)) {
        add_to_journal_new($uncheckrow['receiptdate'], $proj_id, $jv, $uncheckrow['receipt_date'], $uncheckrow['ledger_id'], $uncheckrow['narration'], $uncheckrow['dr_amt'], $uncheckrow['cr_amt'],'Receipt', $uncheckrow['receipt_no'], $uncheckrow['jid'], $uncheckrow['cc_code'], $uncheckrow['sub_ledger_id'], $_SESSION['usergroup'], $uncheckrow['cheq_no'], $uncheckrow['cheq_date'], $create_date, $ip, $now, $uncheckrow['day_name'], $thisday, $thismonth, $thisyear,'','','');}
    if(isset($_POST['deletedata'.$ids]))
    {  mysqli_query($conn, ("DELETE FROM ".$table_receipt." WHERE id=".$ids));
        $_SESSION['initiate_credit_note']=$_SESSION['initiate_credit_note'];
        unset($_POST);
    }
    if(isset($_POST['editdata'.$ids]))
    {  mysqli_query($conn, ("UPDATE ".$table_receipt." SET ledger_id='".$_POST['ledger_id']."', pc_code='".$_POST['pc_code']."',narration='".$_POST['narration']."',dr_amt='".$_POST['dr_amt']."',cr_amt='".$_POST['cr_amt']."' WHERE id=".$ids));
        unset($_POST);
    }
}
if (isset($_REQUEST['id'])) {
    $edit_value=find_all_field(''.$table_receipt.'','','id='.$_REQUEST['id'].'');
}
    $edit_value_ledger_id = @$edit_value->ledger_id;
    $edit_value_pc_code = @$edit_value->pc_code;
    $edit_value_narration = @$edit_value->narration;

if (isset($_POST['confirmsave'])) {
    $up_master=mysqli_query($conn, "UPDATE ".$table_receipt." SET entry_status='UNCHECKED' where ".$recpt_unique."=".$_SESSION['initiate_credit_note']."");
    $up_master=mysqli_query($conn, "UPDATE journal SET status='UNCHECKED' where jv_no=".$jv);
    $up_master=mysqli_query($conn, "UPDATE ".$table_journal_master." SET entry_status='UNCHECKED' where ".$unique."=".$_SESSION['initiate_credit_note']."");
    $up_query=mysqli_query($conn, $up_master);
    unset($_SESSION['initiate_credit_note']);
    unset($_SESSION['credit_note_last_narration']);
    unset($initiate_credit_note);
    unset($_POST);
    unset($$unique);
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
    unset($initiate_credit_note);
    unset($_POST);
    unset($$unique);
}
    $initiate_credit_note = @$_SESSION['initiate_credit_note'];
$COUNT_details_data=find_a_field(''.$table_receipt.'','Count(id)',''.$recpt_unique.'='.$initiate_credit_note.'');

// data query..................................
$condition=$unique."=".$initiate_credit_note;
    $data=db_fetch_object($table_journal_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}
	$inputted_amount=find_a_field('receipt','SUM(dr_amt)','receipt_no="'.$initiate_credit_note.'"');
	}
$voucher_date = @$voucher_date;
$date = date('Y-m-d');
$paid_to = @$paid_to;
$Cheque_of_bank = @$Cheque_of_bank;
$Cheque_No = @$Cheque_No;
$Cheque_Date = @$Cheque_Date;
$amount = @$amount;
$party_ledger = @$party_ledger;
$credit_note_last_narration = @$_SESSION['credit_note_last_narration'];

$sql2="select a.tr_no, a.jvdate as Date,a.jv_no as Voucher_No,SUM(a.dr_amt) as amount
from  journal a where a.tr_from='Receipt' and a.user_id='".$_SESSION['userid']."' and a.section_id='".$_SESSION['sectionid']."' and a.company_id='".$_SESSION['companyid']."'  group by a.tr_no  order by a.id desc limit 10";
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
 entry_status='MANUAL' and 
 j.receipt_no='".$initiate_credit_note."'";


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
<?php require_once 'body_content_nva_sm.php'; ?>

    <div class="col-md-8 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title;?> <small>Single Entry</small></h2>
                <a style="float: right" class="btn btn-sm btn-default"  href="acc_receipt_voucher_multiple.php">
                    <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Multiple Entry</span>
                </a>
                <a style="float: right" class="btn btn-sm btn-default"  href="acc_intercompany_journal_voucher.php">
                    <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Intercompany Entry</span>
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
                            <td><input type="text" required="required" name="<?=$unique?>" id="<?=$unique?>"  value="<?php if($initiate_credit_note>0){ echo $initiate_credit_note;} else { echo
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
                            <th style="">Received From</th><th>:</th>
                            <td colspan="3" style="padding-top: 5px;"><select class="select2_single form-control" style="width:98%; font-size: 11px" tabindex="-1" required="required"  name="party_ledger" id="party_ledger">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $party_ledger, 'ledger_group_id in ("1006") and status=1'); ?>
                                </select></td>
                            <td ><input type="number" id="amount"   value="<?=$amount;?>" name="amount"  class="form-control col-md-7 col-xs-12" placeholder="Rcvd. Amt" required="required" style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" step="any" min="1" />
                            </td>
                        </tr>
                    </table>

                    <?php if($initiate_credit_note){
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
                                <a  href="voucher_print_preview.php?v_type=Receipt&vo_no=<?=$initiate_credit_note;?>&v_date=<?=$voucher_date;?>" target="_blank" style="color: blue; text-decoration: underline; font-size: 11px; font-weight: bold; vertical-align: middle">View Receipt Voucher</a>
                            </div></div>
                    <?php   } else {?>
                        <div class="form-group" style="margin-left:40%; margin-top: 15px">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="initiate" class="btn btn-primary" style="font-size: 11px">Initiate Receipt Voucher</button>
                            </div></div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
<?=recentvoucherview($sql2,'voucher_view_popup_ismail.php','receipt','213px');?>
<?php if($initiate_credit_note):  ?>
    <form action="<?=$page;?>" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="receipt_no" id="receipt_no" value="<?=$initiate_credit_note;?>">
        <input type="hidden" name="<?=$unique?>" id="<?=$unique?>"  value="<?=$initiate_credit_note;?>">
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
            <tr style="background-color: #3caae4; color:white">
                <th style="text-align: center">Cash , Bank & Others</th>
                <th style="text-align: center">Profit Center</th>
                <th style="text-align: center">Narration</th>
                <th style="text-align: center">Attachment</th>
                <th style="width:5%; text-align:center">Amount</th>
                <th style="text-align:center;">Action</th>
            </tr>
            <tbody>
            <tr>
                <td style="width: 25%; vertical-align: middle" align="center">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id">
                        <option></option>
                        <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $edit_value_ledger_id, 'ledger_group_id in ("1002","4007") and status=1'); ?>
                    </select>
                </td>
                <td align="center" style="width: 10%;vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="pc_code">
                        <option></option>
                        <?php foreign_relation('profit_center', 'id', 'CONCAT(id," : ", center_name)', $edit_value_pc_code, '1'); ?>
                    </select>
                </td>
                <td style="width:15%;vertical-align: middle" align="center">
                    <textarea  id="narration" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="narration"  class="form-control col-md-7 col-xs-12" autocomplete="off" ><?=($edit_value_narration!='')? $edit_value_narration : $credit_note_last_narration;?></textarea></td>
                <td style="width:10%;vertical-align: middle" align="center">
                    <input type="file" id="attachment" style="width:100%; height:37px; font-size: 11px; text-align:center"    name="attachment" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                <td align="center" style="width:10%; vertical-align: middle">
                    <?php if (isset($_REQUEST['id'])) { ?>
                        <input type="text" id="dr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center"  value="<?=$edit_value->dr_amt;?>" <?php if($edit_value->dr_amt>0)  echo ''; else echo 'readonly'; ?>   name="dr_amt" placeholder="Debit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                        <input type="text" id="cr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center; margin-top: 5px"    value="<?=$edit_value->cr_amt;?>" <?php if($edit_value->cr_amt>0)  echo ''; else echo 'readonly'; ?> name="cr_amt" placeholder="Credit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                    <?php } else {  ?>
                        <input type="hidden" id="rcved_remining" style="width:100%; height:37px; font-size: 11px; text-align:center"  value="<?=$rcved_remining=$amount-$inputted_amount;?>"  name="rcved_remining" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                        <input type="number" id="dr_amt" onkeyup="doAlert(this.form);" style="width:100%; height:37px; font-size: 11px; text-align:center"  value="<?=$rcved_remining;?>"  name="dr_amt" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                    <?php } ?>
                </td>
                <td align="center" style="width:5%; vertical-align: middle ">
                    <?php if (isset($_REQUEST['id'])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_REQUEST['id'];?>" id="editdata<?=$_REQUEST['id'];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                    <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
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
    <?=voucher_delete_edit($rs,$unique,$initiate_credit_note,$COUNT_details_data,$page);?><br><br>
<?php endif; mysqli_close($conn); ?>
<?=$html->footer_content();?> 