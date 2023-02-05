<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='LC Expenses';
$unique='voucherno';
$unique_field='voucher_date';
$table_journal_master="journal_voucher_master";
$table_payment="payment";
$payment_unique='payment_no';
$LC_table_master='lc_lc_master';
$page="accounts_LC_expenses.php";
$crud      =new crud($table_journal_master);
$$unique = $_POST[$unique];
$create_date=date('Y-m-d');
$jv=next_journal_voucher_id();

if(prevent_multi_submit()) {
if (isset($_POST['initiate'])) {
    $_POST['section_id'] = $_SESSION['sectionid'];
    $_POST['company_id'] = $_SESSION['companyid'];
    $_POST['ip'] = $ip;
    $_POST['entry_by'] = $_SESSION['userid'];
    $_POST['entry_at'] = date('Y-m-d H:s:i');
    $_SESSION[initiate_LC_expenses] = $_POST[$unique];
    $_SESSION[initiate_LC_ID]=$_POST[lc_id];
    $_POST['journal_type'] = 'Payment';
    $_POST['entry_by'] = 'MANUAL';
    $crud->insert();
    unset($_POST);
}

//for modify PS information ...........................
if (isset($_POST['modify'])) {
    $d = $_POST[nam_date];
    $_POST[voucher_date] = date('Y-m-d', strtotime($d));
    $ckd = $_POST[nam_date];
    $_POST[Cheque_Date] = date('Y-m-d', strtotime($ckd));
    $_POST['edit_at'] = time();
    $_POST['edit_by'] = $_SESSION['userid'];
    $crud->update($unique);
    $type = 1;
    unset($_POST);
}


if (isset($_POST['add'])) {
    $tdates=date("Y-m-d");
    $day = date('l', strtotime($idatess));
    $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
    $timess=$dateTime->format("d-m-y  h:i A");

    $add=$_POST[add];
    if (isset($_POST['add'])){
        $valid = true;
        if($_POST[dr_amt]>0) {
            $type='Debit';        }
        elseif ($_POST[cr_amt]>0) {
            $type='Credit';
        }

        $_POST_dr_ledger_id=find_a_field('sub_sub_ledger','sub_ledger_id','sub_sub_ledger_id='.$_POST[subledger_id].'');
        $cost_head_name=find_a_field('LC_expenses_head','LC_expenses_head','LC_exp_ledger='.$_POST[subledger_id].'');

            if($_POST[dr_amt_1]>0){

                if($_POST[VAT_ledger_id]=='4015000100000000'){
                    $dr_total_amt=$_POST[dr_amt_1]+$_POST[dr_amt_2];
                } else {
                    $dr_total_amt=$_POST[dr_amt_1];
                }
                $_POST[narration]=$cost_head_name.' , '.$_POST[narration].', PI#'.$_POST[pi_no].', LCID#'. $_POST[lc_id].', LC NO#'.$_POST[lc_no];
                add_to_payment($_SESSION[initiate_LC_expenses],0, $proj_id, $_POST[narration], $_POST_dr_ledger_id, $_POST[dr_amt_1],
                    '', $type,$cur_bal,$inirow[paid_to],$inirow[Cheque_No],$c_date,$inirow[Cheque_of_bank],$dr_total_amt,0,$_POST[subledger_id],UNCHECKED,$ip,$_POST[voucher_date],$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
                    ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[initiate_LC_ID]);}

                if($_POST[dr_amt_2]>0){
                    $_POST[narration2]='VAT Current Account , '.$_POST[narration].', PI#'.$_POST[pi_no].', LCID#'. $_POST[lc_id].', LC NO#'.$_POST[lc_no];

                    add_to_payment($_SESSION[initiate_LC_expenses],0, $proj_id, $_POST[narration2], $_POST[VAT_ledger_id], $_POST[dr_amt_2],
                        '', $type,$cur_bal,0,0,$c_date,0,0,0,0,UNCHECKED,$ip,$_POST[voucher_date],$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
                        ,$thisday,$thismonth,$thisyear,$receive_ledger,0);
                }if($_POST[dr_amt_3]>0){
            $_POST[narration3]='VAT Expenses , '.$_POST[narration].', PI#'.$_POST[pi_no].', LCID#'. $_POST[lc_id].', LC NO#'.$_POST[lc_no];
            add_to_payment($_SESSION[initiate_LC_expenses],0, $proj_id, $_POST[narration3], $_POST_dr_ledger_id, $_POST[dr_amt_3],
                        '', $type,$cur_bal,$inirow[paid_to],$inirow[Cheque_No],$c_date,$inirow[Cheque_of_bank],0,0,$_POST[VAT_ledger_id_exp],UNCHECKED,$ip,$_POST[voucher_date],$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
                        ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[initiate_LC_ID]);
            $_SESSION[dr_amt_3]=$_POST[dr_amt_3];
                }
                if($_POST[cr_amt]>0){
                    add_to_payment($_SESSION[initiate_LC_expenses],0, $proj_id, $_POST[narration], $_POST[cr_ledger_id], '',
                    $_POST[cr_amt], $type,$cur_bal,$inirow[paid_to],$inirow[Cheque_No],$c_date,$inirow[Cheque_of_bank],0,0,$_POST[subledger_id],UNCHECKED,$ip,$_POST[voucher_date],$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
                    ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[initiate_LC_ID]);
                $_SESSION['LC_HEAD']=find_a_field('LC_expenses_head','db_column_name','LC_exp_ledger='.$_POST[subledger_id].'');
            }}}




} // prevent multi submit

//for Delete..................................
if (isset($_POST['cancel'])) {
    $crud = new crud($table_payment);
    $condition =$payment_unique."=".$_SESSION['initiate_LC_expenses'];
    $crud->delete_all($condition);
    $crud = new crud($table_journal_master);
    $condition=$unique."=".$_SESSION['initiate_LC_expenses'];
    $crud->delete($condition);
    unset($_SESSION['initiate_LC_expenses']);
    unset($_SESSION['initiate_LC_ID']);
    unset($_SESSION['LC_HEAD']);
    unset($_POST);
    unset($$unique);
}


// data query..................................
if(isset($_SESSION['initiate_LC_expenses']))
{   $unique='id';
	$condition=$unique."=".$_SESSION['initiate_LC_ID'];
    $data=db_fetch_object($LC_table_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$rs_details="Select 
j.id as jid,
a.ledger_id,
j.paymentdate,
j.payment_no,
j.sub_ledger_id,
(select ledger_name from accounts_ledger where ledger_id=j.sub_ledger_id) as 'LC Expensed Head',
j.narration,
j.dr_amt,
j.cr_amt,
j.manual_payment_no
from 
payment j,
 accounts_ledger a
  where 
 j.ledger_id=a.ledger_id and
 entry_status='UNCHECKED' and 
 j.payment_no='".$_SESSION['initiate_LC_expenses']."' group by j.id";
$rs = mysqli_query($conn, $rs_details);
while($uncheckrow=mysqli_fetch_array($rs)){
    $ids=$uncheckrow[jid];
    if(isset($_POST['deletedata'.$ids]))
    {  $res=mysqli_query($conn, ("DELETE FROM ".$table_payment." WHERE id=".$ids));
        unset($_POST); 
    } // end of deletedata
    if(isset($_POST['editdata'.$ids]))
    {  mysqli_query($conn, ("UPDATE ".$table_payment." SET ledger_id='".$_POST[ledger_id]."', cc_code='".$_POST[cc_code]."',narration='".$_POST[narration]."',dr_amt='".$_POST[dr_amt]."',cr_amt='".$_POST[cr_amt]."' WHERE id=".$ids));
        unset($_POST);
    } // end of editdata
if (isset($_GET[id])) {
    $edit_value=find_all_field(''.$table_payment.'','','id='.$_GET[id].'');
}
    if (isset($_POST['confirmsave'])){
add_to_journal_new($uncheckrow[paymentdate],$proj_id, $jv, 0, $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt],Payment, $uncheckrow[payment_no],$uncheckrow[jid],$uncheckrow[cc_code],$uncheckrow[sub_ledger_id],$_SESSION[usergroup],$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear);

$updated_expenses=find_a_field('lc_lc_master','SUM('.$_SESSION[LC_HEAD].')',''.$_SESSION[LC_HEAD].'='.$_SESSION[LC_HEAD].' and id='.$_SESSION[initiate_LC_ID])+$uncheckrow[manual_payment_no]+$_SESSION[dr_amt_3];
mysqli_query($conn, "UPDATE lc_lc_master SET ".$_SESSION['LC_HEAD']."='$updated_expenses' WHERE id='".$_SESSION['initiate_LC_ID']."'");
unset($_SESSION['initiate_LC_expenses']);
unset($_SESSION['initiate_LC_ID']);
unset($_SESSION['LC_HEAD']);
unset($_SESSION['dr_amt_3']);
header("Location: ".$pages."");
	}}

$COUNT_details_data=find_a_field(''.$table_payment.'','Count(id)',''.$payment_unique.'='.$_SESSION['initiate_LC_expenses'].'');

$rs_details="Select 
j.id as jid,
CONCAT(a.ledger_id,' : ',a.ledger_name) as 'Account Head',
(select CONCAT(ledger_id,' : ',ledger_name) from accounts_ledger where ledger_id=j.sub_ledger_id) as 'LC Expensed Head',
j.narration,
j.dr_amt,
j.cr_amt
from 
payment j,
 accounts_ledger a
  where 
 j.ledger_id=a.ledger_id and
 entry_status='UNCHECKED' and 
 j.payment_no='".$_SESSION['initiate_LC_expenses']."' group by j.id";
?>

<?php require_once 'header_content.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content_entry_mod.php'; ?>


<form action="<?=$page;?>" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px">
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_content">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <div class="clearfix"></div>
            </div>
            <? require_once 'support_html.php';?>
            <table align="center" style="width:100%">
                <tr style="display:">
                    <td style="width:30%;">
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Date<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="date" id="voucher_date"  required="required" name="voucher_date" value="<?=($voucher_date!='')? $voucher_date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" class="form-control col-md-7 col-xs-12" style="width: 130px; font-size: 11px" ><br>
                            </div>
                        </div></td>


                    <td style="width:30%"><div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Transaction No<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="voucherno"   required="required" name="voucherno" value="<?=($_SESSION['initiate_LC_expenses']!='')? $_SESSION['initiate_LC_expenses'] : automatic_voucher_number_generate($table_payment,$payment_unique,1,2);?>" class="form-control col-md-7 col-xs-12"  readonly style="width: 130px; font-size: 11px">
                            </div>
                        </div>
                    </td>
                
                    <td style="width:30%"><div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Available LC<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="lc_id" id="lc_id">
                            <option></option>
                            <?php foreign_relation('lc_lc_master', 'id', 'CONCAT(id," : ", lc_no)', $_SESSION[initiate_LC_ID], 'status in ("CHECKED")'); ?>
                            </select>
                            </div>
                        </div>
                    </td>
                </tr>
            </table><br>
            <table align="center" style="width:60%">
                <tbody>
                <tr>
                    <td align="center" style="width:10%">
                        <?php if($_SESSION[initiate_LC_expenses]){  ?>
                            <button type="submit" name="initiate" class="btn btn-primary" style="font-size: 12px">Update Info</button>
                        <?php   } else {?>
                            <button type="submit" class="btn btn-primary" name="initiate" id="initiate" style="font-size: 12px">Initiate and Proceed to Next</button>
                        <?php } ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div></div></div>
    <?php  if($_SESSION[initiate_LC_ID]){ ?>
            <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                <thead>
                <tr style="background-color: bisque">
                    <?php
                    $lctablew=mysqli_query($conn, "Select * from LC_expenses_head where status in ('1')");
                    while($lcrow=mysqli_fetch_array($lctablew)){
                    ?><th style="text-align:center; vertical-align: middle"><?=$lcrow[LC_expenses_head];?></th>
                    <?php } ?>
                    <th style="text-align:center">Total LC Cost</th>
                </tr>
                </thead>
                <?php
                $js=$js+1;
                $ids=$uncheckrow[jid];
                $ledger_code_update=$_POST['ledger_code_update'.$ids];
                $cc_code_update=$_POST['cc_code_update'.$ids];
                $updr_amt=$_POST['updr_amt'.$ids];
                $upcr_amt=$_POST['upcr_amt'.$ids];
                $upnarration=$_POST['upnarration'.$ids];
                if(isset($_POST['deletedata'.$ids]))
                {
                    mysqli_query($conn, "DELETE FROM payment WHERE id='$ids'"); ?>
                    <meta http-equiv="refresh" content="0;debit_note.php">
                    <?php
                }

                if(isset($_POST['editdata'.$ids]))
                {
                    update_payment_add_data($ledger_code_update,$cc_code_update,$updr_amt,$upcr_amt,$upnarration,$ids); ?>
                    <meta http-equiv="refresh" content="0;accounts_LC_expenses.php">
                <?php }?>


                <tr>
                    <?php
                    $lctablew=mysqli_query($conn,"Select lh.* from LC_expenses_head lh where lh.status in ('1')");
                    while($lcrow=mysqli_fetch_array($lctablew)){
                        ?><td style="text-align:center; vertical-align: middle"><?=$COST=find_a_field('lc_lc_master',''.$lcrow[db_column_name].'',''.$lcrow[db_column_name].'='.$lcrow[db_column_name].' and id='.$_SESSION[initiate_LC_ID].'');?></td>
                    <?php $total_LC_COST=$total_LC_COST+$COST;  } ?>
                    <td style="vertical-align:middle; text-align: right"><?=number_format($total_LC_COST,2);?></td>
                </tr>

            </table></form>

<?php } ?>



<?php if($_SESSION[initiate_LC_ID]){?>
    <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <input type="hidden" id="pi_no" name="pi_no" value="<?=$pi_id?>">
        <input type="hidden" id="lc_id" name="lc_id" value="<?=$id?>">
        <input type="hidden" id="lc_no" name="lc_no" value="<?=$lc_no?>">
        <input type="hidden" id="voucher_date" name="voucher_date" value="<?=date('Y-m-d');?>">
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque; font-size: 11px; height: 25px;">
                            <th style="text-align:center; vertical-align: middle">LC Expenses Head</th>
                            <th style="text-align:center; vertical-align: middle">VAT Ledger <br>Current / Expenses</th>
                            <th style="text-align:center; vertical-align: middle">Expenses From Cash or Bank</th>
                            <th style="text-align:center; vertical-align: middle">Narration</th>
                            <th style="text-align:center; vertical-align: middle">Amount</th>
                            <th style="text-align:center; vertical-align: middle">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td align="left" style="width:15%; vertical-align: middle">
                                 <select class="select2_single form-control" style="width:100%; font-size: 11px" required="required"  name="subledger_id">
                                                    <option></option>
                                                <?=foreign_relation('LC_expenses_head', 'LC_exp_ledger', 'LC_expenses_head', $subledger_id, 'status in (\'1\')'); ?>
                                                </select></td>
                            <td align="left" style="width:20%; vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" name="VAT_ledger_id">
                                    <option></option>
                                    <?=foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $VAT_ledger_id, 'ledger_id in  ("1005000400000000")'); ?>
                                </select><br><br>
							<select class="select2_single form-control" style="width:100%; font-size: 11px" name="VAT_ledger_id_exp">
                                    <option></option>
                                    <?=foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $VAT_ledger_id_exp, 'ledger_id in  ("4015000100000000")'); ?>
                                </select>
							</td>
                            <td align="left" style="width:25%; vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="cr_ledger_id">
                                                    <option></option>
                                                <?=foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $cr_ledger_id, '1'); ?>
                                                </select>
                                </td>


                            <td align="center" style="width:10%; vertical-align: middle" >
                                <textarea id="narration" style="width:100%; height:37px; font-size: 11px; text-align:center"    name="narration" class="form-control col-md-7 col-xs-12" autocomplete="off" ></textarea></td>
                            <td align="center" style="width:10%; vertical-align: middle">
                                <input type="number" id="dr_amt_1" style="width:100%; height:25px; font-size: 11px; text-align:center;" step="any" placeholder="Expenses" required   name="dr_amt_1" class="form-control col-md-7 col-xs-12" autocomplete="off" class="dr_amt_1" ><br>
                                <input type="number" id="dr_amt_2" style="width:100%; height:25px; font-size: 11px; margin-top: 3px; text-align:center;" step="any" placeholder="VAT Current"  name="dr_amt_2" class="form-control col-md-7 col-xs-12" autocomplete="off" class="dr_amt_2"><br>
								<input type="number" id="dr_amt_3" style="width:100%; height:25px; font-size: 11px; margin-top: 3px; text-align:center;" step="any" placeholder="VAT Expenses"  name="dr_amt_3" class="form-control col-md-7 col-xs-12" autocomplete="off" class="dr_amt_3"><br>
                                <input type="number" id="cr_amt" style="width:100%; height:25px; font-size: 11px; text-align:center;margin-top:3px" step="any" placeholder="Cr amt" readonly required name="cr_amt" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                            <td align="center" style="width:5%; vertical-align: middle"><?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                                <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
                        </tbody>
                    </table>
                </form>
    <script>
        $(function(){
            $('#dr_amt_1, #dr_amt_2, #dr_amt_3').keyup(function(){
                var dr_amt_1 = parseFloat($('#dr_amt_1').val()) || 0;
                var dr_amt_2 = parseFloat($('#dr_amt_2').val()) || 0;
                var dr_amt_3 = parseFloat($('#dr_amt_3').val()) || 0;
                $('#cr_amt').val((dr_amt_1 + dr_amt_2 + dr_amt_3).toFixed(2));
            });
        });
    </script>


<?=voucher_delete_edit($rs_details,$unique,$_SESSION['initiate_LC_expenses'],$COUNT_details_data);?><br><br>
<?php } mysqli_close($conn); ?>
<?=$html->footer_content();?>






