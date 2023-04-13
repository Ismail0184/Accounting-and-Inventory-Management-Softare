<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Cheque Payment';
$sectionid = @$_SESSION['sectionid'];
$sectionid_substr = @(substr($_SESSION['sectionid'],4));
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
    $sec_com_connection_wa=' and 1';
} else {
    $sec_com_connection=" and j.company_id='".$_SESSION['companyid']."' and j.section_id in ('400000','".$_SESSION['sectionid']."')";
    $sec_com_connection_wa=" and company_id='".$_SESSION['companyid']."' and section_id in ('400000','".$_SESSION['sectionid']."')";
}

//Image Attachment Function
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
$table_payment="secondary_payment";
$payment_unique='payment_no';
$page="acc_cheque_payment_voucher.php";
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
        if(isset($_POST['Cheque_Date'])>0){
            $ckd = $_POST['Cheque_Date'];
            $_POST['Cheque_Date'] = date('Y-m-d', strtotime($ckd));
        } else {
            $_POST['Cheque_Date']='';
        }
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION['initiate_bank_debit_note'] = @$_POST[$unique];
        $_POST['journal_type'] = 'Bank_Payment';
        $_POST['MANUAL'] = 'MANUAL';
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
        if ($_POST['dr_amt'] > 0) {
            $type = 'Debit';
        } elseif ($_POST['cr_amt'] > 0) {
            $type = 'Credit';
        }
        $date = $_POST['receipt_date'];
        if(isset($_POST['Cheque_Date'])) {
            $c_date = $_POST['Cheque_Date'];
        } else {
            $c_date='';
        }

        $tdates = date("Y-m-d");
        $day = date('l', strtotime($tdates));
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
        $timess = $dateTime->format("d-m-y  h:i A");
        $POST_dr_amt = @$_POST['dr_amt'];
        $POST_cr_amt = @$_POST['cr_amt'];
        $c_date = 0;
        $cur_bal = 0;
        $manual_payment_no = 0;
        $cc_code = @$_POST['cc_code'];
        $subledger_id = @$_POST['subledger_id'];
        $Cheque_of_bank = @$_POST['Cheque_of_bank'];
        $receive_ledger = 0;


        if (($_POST['dr_amt'] && $POST_cr_amt) > 0) {
            echo "<script>alert('Yor are trying to input an invalid transaction!!'); window.location.href='".$page."';</script>";
             
        } else {
        if ((($_POST['dr_amt'] || $POST_cr_amt) > 0) && ($_SESSION['initiate_bank_debit_note']>0)) {
            add_to_bank_payment($_SESSION['initiate_bank_debit_note'],$date, $proj_id, $_POST['narration'], $_POST['ledger_id'], $_POST['dr_amt'],
                0, $type,$cur_bal,$_POST['paid_to'],$_POST['cheque_id'],$_POST['maturity_date'],$Cheque_of_bank,$manual_payment_no,$_POST['cc_code'],$_POST['cash_bank_ledger'],'MANUAL',$ip,$_POST['receipt_date'],$_SESSION['sectionid'],$_SESSION['companyid'],$_SESSION['userid'],$create_date,$now,$day,$thisday,$thismonth,$thisyear,$receive_ledger);

            if ($_POST['rcved_remining']==$_POST['dr_amt']) {
                add_to_bank_payment($_SESSION['initiate_bank_debit_note'],$date, $proj_id, $_POST['narration'], $_POST['cash_bank_ledger'], 0,
                    $_POST['amount'], $type,$cur_bal,$_POST['paid_to'],$_POST['cheque_id'],$_POST['maturity_date'],$Cheque_of_bank,$manual_payment_no,$_POST['cc_code'],$subledger_id,'MANUAL',$ip,$_POST['receipt_date'],$_SESSION['sectionid'],$_SESSION['companyid'],$_SESSION['userid'],$create_date,$now,$day,$thisday,$thismonth,$thisyear,$receive_ledger);
                $_SESSION['cheque_payment_last_narration']=$_POST['narration'];
            }
        }
            if ($_FILES["attachment"]["tmp_name"] != '') {
                $path = '../assets/images/attachment/vouchers/bank_payment/' . $_SESSION['initiate_bank_debit_note'] . '.jpg';
                move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
            }
    }} 

    } // end post unique   
    } // end prevent_multi_submit

$initiate_bank_debit_note = @$_SESSION['initiate_bank_debit_note'];
$cheque_payment_last_narration = @$_SESSION['cheque_payment_last_narration'];

if($initiate_bank_debit_note>0){
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
cb.id as cq_id,
j.maturity_date,
j.day_name,
a.*,c.center_name as cname 
from 
".$table_payment." j,
accounts_ledger a,
cost_center c,
Cheque_Book cb
where 
 cb.id=j.cheque_id and 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 j.entry_status='MANUAL' and 
 j.payment_no='".$_SESSION['initiate_bank_debit_note']."'".$sec_com_connection."";
        $re_query = mysqli_query($conn, $rs);
        while ($uncheckrow = mysqli_fetch_array($re_query)) {
			$ids=$uncheckrow[jid];
			if (isset($_POST['confirmsave']) && ($uncheckrow['payment_no']>0)) {
                add_to_journal_bank($uncheckrow['paymentdate'], $proj_id, $jv, $uncheckrow['payment_date'], $uncheckrow['ledger_id'], $uncheckrow['narration'], $uncheckrow['dr_amt'], $uncheckrow['cr_amt'],'Payment', $uncheckrow['payment_no'], $uncheckrow['jid'], $uncheckrow['cc_code'], $uncheckrow['sub_ledger_id'], $_SESSION['usergroup'], $uncheckrow['cheq_no'], $uncheckrow['cheq_date'], $create_date, $ip, $now, $uncheckrow['day_name'], $thisday, $thismonth, $thisyear);
                $up_cq=mysqli_query($conn, "Update Cheque_Book SET status='USED',cheque_issued_date='".$uncheckrow['paymentdate']."',maturity_date='".$uncheckrow['maturity_date']."' where id=".$uncheckrow['cq_id']."".$sec_com_connection_wa."");
			} // end of confirm
                   
	
	if(isset($_POST['deletedata'.$ids]))
    {  $res=mysqli_query($conn, ("DELETE FROM ".$table_payment." WHERE id=".$ids));
       unset($_POST);
    } // end of deletedata
	if(isset($_POST['editdata'.$ids]))
    {  mysqli_query($conn, ("UPDATE ".$table_payment." SET ledger_id='".$_POST['ledger_id']."', cc_code='".$_POST['cc_code']."',narration='".$_POST['narration']."',dr_amt='".$_POST['dr_amt']."',cr_amt='".$_POST['cr_amt']."' WHERE id=".$ids));
       unset($_POST);
    } // end of editdata
	}
    if (isset($_GET['id'])) {
$edit_value=find_all_field(''.$table_payment.'','','id='.$_GET['id'].'');
}
    $edit_value_ledger_id = @$edit_value->ledger_id;
    $edit_value_cc_code = @$edit_value->cc_code;
    $edit_value_narration = @$edit_value->narration;
    $initiate_bank_debit_note = @$_SESSION['initiate_bank_debit_note'];


    if (isset($_POST['confirmsave'])) {
       $up_payment="UPDATE ".$table_payment." SET entry_status='PREMATURE' where ".$payment_unique."=".$initiate_bank_debit_note."".$sec_com_connection_wa."";
        $up_query=mysqli_query($conn, $up_payment);
        unset($initiate_bank_debit_note);
        unset($_SESSION['initiate_bank_debit_note']);
		unset($_SESSION['debit_note_last_narration']);
        unset($_POST);
    } // if insert confirm


//for Delete..................................
if (isset($_POST['cancel'])) {
    $crud = new crud($table_payment);
    $condition =$payment_unique."=".$initiate_bank_debit_note;
    $crud->delete_all($condition);
    $crud = new crud($table_journal_master);
    $condition=$unique."=".$initiate_bank_debit_note;
    $crud->delete($condition);
    unset($initiate_bank_debit_note);
    unset($_SESSION['initiate_bank_debit_note']);
	unset($_SESSION['debit_note_last_narration']);
    unset($_POST);
}
    $initiate_bank_debit_note = @$_SESSION['initiate_bank_debit_note'];
$COUNT_details_data=find_a_field("".$table_payment."","Count(id)","".$payment_unique."=".$initiate_bank_debit_note."".$sec_com_connection_wa."");

// data query..................................
 $condition=$unique."=".$initiate_bank_debit_note;
    $data=db_fetch_object($table_journal_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}
	$inputted_amount=find_a_field("".$table_payment."","SUM(dr_amt)","".$payment_unique."='".$initiate_bank_debit_note."'".$sec_com_connection_wa."");
	}
$voucher_date = @$voucher_date;
$date = date('Y-m-d');
$paid_to = @$paid_to;
$Cheque_of_bank = @$Cheque_of_bank;
$Cheque_No = @$Cheque_No;
$Cheque_Date = @$Cheque_Date;
$maturity_date = @$maturity_date;
$edit_value_cheque_id = @$edit_value_cheque_id;
$cash_bank_ledger = @$cash_bank_ledger;
$amount = @$amount;

$sql2="select j.payment_no,cb.Cheque_number,j.payment_no,SUM(j.dr_amt) as amount
from  ".$table_payment." j,Cheque_Book cb where j.entry_by='".$_SESSION['userid']."' and cb.id=j.cheque_id and j.entry_status not in ('MANUAL')".$sec_com_connection."  group by j.payment_no  order by j.payment_no desc limit 10";

$rs="Select 
j.id as jid,
concat(a.ledger_id, ' : ' ,a.ledger_name) as Account_Head,cb.Cheque_number,j.narration,j.dr_amt,j.cr_amt  
from 
".$table_payment." j,
accounts_ledger a,
cost_center c,
Cheque_Book cb
where 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 j.entry_status='MANUAL' and 
 j.cheque_id=cb.id and 
 j.payment_no='".$initiate_bank_debit_note."'".$sec_com_connection."";

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
                                <h2><?=$title;?> <small class="text-danger">field marked with * are mandatory</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                            <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                            <table align="center" style="width:100%">
                        <tr>
                            <th style="width:15%;">Transaction Date<span class="required">*</span></th><th style="width: 2%;">:</th>
                            <td><input type="date" id="voucher_date"  required="required" name="voucher_date" value="<?=($voucher_date!='')? $voucher_date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" ></td>
                            <th style="width:15%;">Transaction No<span class="required">*</span></th><th style="width: 2%">:</th>
                            <td><input type="text" required="required" name="<?=$unique?>" id="<?=$unique?>"  value="<?=($initiate_bank_debit_note!='')? $initiate_bank_debit_note : automatic_voucher_number_generate($table_payment,$payment_unique,1,'5'.$sectionid_substr);?>" class="form-control col-md-7 col-xs-12" readonly style="width: 90%; font-size: 11px;"></td>
                        </tr>

                        <tr>
                            <th style="">Received By</th><th>:</th>
                            <td><input type="text" id="paid_to"  value="<?=$paid_to;?>" name="paid_to" class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px; font-size: 11px;" ></td>

                            <th>Maturity date</th><th>:</th>
                            <td><input type="date" id="Cheque_Date" required value="<?=$maturity_date;?>" min="<?=date('Y-m-d')?>" name="maturity_date"  class="form-control col-md-7 col-xs-12"  style="width: 90%; margin-top: 5px; font-size: 11px; vertical-align: middle"></td>
                        </tr>
                        
                         <tr>
                            <th style="">Bank Name</th><th>:</th>
                            <td colspan="3" style="padding-top: 5px;"><select class="select2_single form-control" style="width:98%; font-size: 11px" tabindex="-1" required="required"  name="cash_bank_ledger" id="cash_bank_ledger">
                                    <option></option>
                                    <?php foreign_relation("accounts_ledger", "ledger_id", "CONCAT(ledger_id,' : ', ledger_name)", $cash_bank_ledger, "ledger_group_id in ('1002') and show_in_transaction=1 and status=1".$sec_com_connection_wa.""); ?>
                                </select></td>
                            <td ><input type="number" id="amount"   value="<?=$amount;?>" name="amount"  class="form-control col-md-7 col-xs-12" placeholder="Amount" required="required" style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" step="any" min="1" />
                            </td>
                        </tr>                    
                    </table>
                    
                                    

                               <?php if($initiate_bank_debit_note){
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
                                                <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 11px">Update Voucher</button>
                                        </div></div>

                                   <div class="form-group" <?=$display;?>>
                                       <div class="col-md-6 col-sm-6 col-xs-12">
                                           <a  href="voucher_print_preview.php?v_type=payment&vo_no=<?=$initiate_bank_debit_note;?>&v_date=<?=$voucher_date;?>" target="_blank" style="color: blue; text-decoration: underline; font-size: 11px; font-weight: bold; vertical-align: middle">View Cheque Payment Voucher</a>
                                       </div></div>
                                            <?php   } else {?>
                                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                   <button type="submit" name="initiate" class="btn btn-primary" style="font-size: 11px">Initiate Voucher</button>
                                        </div></div>
                                            <?php } ?>

                                </form>
                            </div>
                        </div>
                    </div>
<?=recentvoucherview($sql2,'voucher_view_popup_ismail.php','payment_bank','171px');?>
<?php if($initiate_bank_debit_note):  ?>
                                <form action="<?=$page;?>" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
                                  <input type="hidden" name="<?=$unique?>" id="<?=$unique?>" value="<?=$initiate_bank_debit_note;?>">
                                  <input type="hidden" name="payment_no" id="payment_no" value="<?=$initiate_bank_debit_note;?>">
                                    <input type="hidden" name="receipt_date" id="receipt_date" value="<?=$voucher_date;?>">
                                    <input type="hidden" name="amount" id="amount" value="<?=$amount;?>">
                                    <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
                                    <input type="hidden" name="paid_to" id="paid_to" value="<?=$paid_to;?>">
                                    <input type="hidden" name="dr_amt" id="dr_amt" value="<?=$amount;?>">
                                    <input type="hidden" name="cash_bank_ledger" id="cash_bank_ledger" value="<?=$cash_bank_ledger;?>">
                                    <input type="hidden" name="maturity_date" value="<?=$maturity_date;?>">
                                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                                        <tbody>
                                        <tr style="background-color: #3caae4; color:white">
                                            <th style="text-align: center">Vendor Name</th>
                                            <th style="text-align: center">Cost Center</th>
                                            <th style="text-align: center">Narration</th>
                                            <th style="text-align: center">Cheque Number</th>
                                            <th style="width:5%; text-align:center">Amount</th>
                                            <th style="text-align:center">Action</th>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td style="width: 25%; vertical-align: middle" align="center">
                                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id">
                                                    <option></option>
                                                <?php foreign_relation("accounts_ledger","ledger_id","CONCAT(ledger_id,' : ', ledger_name)", $edit_value_ledger_id, "ledger_group_id in ('2002') and show_in_transaction=1 and status=1".$sec_com_connection_wa.""); ?>
                                                </select></td>
                                            <td align="center" style="width: 10%;vertical-align: middle">
                                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="cc_code" id="cc_code">
                                                    <option></option>
                                                    <?php foreign_relation("cost_center", "id","CONCAT(id,'-', center_name)", $edit_value_cc_code, "status=1".$sec_com_connection_wa.""); ?>
                                                </select>
                                            </td>
                                            <td style="width:15%;vertical-align: middle" align="center">
                                                <textarea  id="narration" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="narration"   class="form-control col-md-7 col-xs-12" autocomplete="off" ><?=($edit_value_narration!='')? $edit_value_narration : $cheque_payment_last_narration;?></textarea>
                                            </td>
                                            <td align="center" style="width:10%; vertical-align: middle">
                                                 <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="cheque_id" id="cheque_id">
                                                     <option></option>
                                                     <?php foreign_relation("Cheque_Book","id","CONCAT(id,'-', Cheque_number)", $edit_value_cheque_id,"status='UNUSED' and bank_id=".$cash_bank_ledger."".$sec_com_connection_wa.""); ?>
                                                 </select>
                                            </td>
                                            <td align="center" style="width:10%; vertical-align: middle">
                                                <?php if (isset($_GET['id'])) { ?>
                                                    <input type="text" id="dr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center"  value="<?=$edit_value->dr_amt;?>" <?php if($edit_value->dr_amt>0)  echo ''; else echo 'readonly'; ?>  name="dr_amt" placeholder="Debit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                                                    <input type="text" id="cr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center; margin-top: 5px"  value="<?=$edit_value->cr_amt;?>" <?php if($edit_value->cr_amt>0)  echo ''; else echo 'readonly'; ?>  name="cr_amt" placeholder="Credit" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                                                <?php } else { ?>
                                                    <input type="hidden" id="rcved_remining" style="width:100%; height:37px; font-size: 11px; text-align:center"  value="<?=$rcved_remining=$amount-$inputted_amount;?>"  name="rcved_remining" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" /><input type="number" id="dr_amt" onkeyup="doAlert(this.form);" style="width:100%; height:37px; font-size: 11px; text-align:center"  value="<?=$rcved_remining;?>"  name="dr_amt" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" />
                                                <?php } ?>
                                            </td>
                                            <td align="center" style="width:5%; vertical-align: middle "><?php if (isset($_GET['id'])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET['id'];?>" id="editdata<?=$_GET['id'];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
				<?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
                                        </tbody>
                                    </table>
                                    <SCRIPT language=JavaScript>
            function doAlert(form)
            {
                var val=form.dr_amt.value;
                var val2=form.rcved_remining.value;
                if (Number(val)>Number(val2)){
                    alert('oops!! Exceed Amount Limit!! Thanks');
                    form.dr_amt.value='';
                }
                form.dr_amt.focus();
            }</script>
                                </form>


                <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

<?=voucher_delete_edit($rs,$unique,$initiate_bank_debit_note,$COUNT_details_data,$page);?><br><br>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>