<?php require_once 'support_file.php';

$jv_no =  @$_GET['v_no'];
$v_type 		= find_a_field('journal','distinct tr_from','jv_no='.$jv_no);
$v_type = strtolower($v_type);
if($v_type=='receipt'){$voucher_name='RECEIPT VOUCHER';$vtype='receipt';$tr_from='receipt';$dtype='receiptdate';$olddtype='receipt_date';}
elseif($v_type=='payment'){$voucher_name='PAYMENT VOUCHER';$vtype='payment';$tr_from='payment';$dtype='paymentdate';$olddtype='payment_date';}
elseif($v_type=='Purchase'){$voucher_name='Purchase VOUCHER';$vtype='secondary_journal';$tr_from='Purchase';$dtype='jvdate';$olddtype='jv_date';}
elseif($v_type=='Opening'){$voucher_name='Opening Balance VOUCHER';$vtype='Opening';$tr_from='Opening';$dtype='j_date';$olddtype='opening_info_date';}
elseif($v_type=='journal_info'){$voucher_name='JOURNAL VOUCHER';$vtype='journal_info';$tr_from='journal_info';$dtype='j_date';$olddtype='journal_info_date';}
elseif($v_type=='Contra'){$voucher_name='CONTRA VOUCHER';$vtype='coutra';$tr_from='Contra';$dtype='coutradate';$olddtype='coutra_date';}
else{$v_type=='Contra';$voucher_name='CONTRA VOUCHER';$vtype='coutra';$tr_from='Contra';$dtype='coutradate';$olddtype='coutra_date';}




if(prevent_multi_submit()) {


    if (isset($_POST['modify'])) {

    }


}
if(isset($_REQUEST['v_no']))
{

?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content_without_menu.php'; ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="form2" id="form2" class="form-horizontal form-label-left" method="post" onsubmit="return validate_total()" style="font-size: 11px">
                    <? require_once 'support_html.php';?>
                    <table class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <tr>
                            <th style="text-align: right">Date</th>
                            <td style="text-align: left">
                                <input style="width: 150px" name="vdate" type="date" value="<?=$data1[7];?>" max="<?=date('Y-m-d');?>"> </td>
                            <th height="20" style="text-align: right">Cq No:</th>
                            <td height="20" style="text-align: left">
                                <input name="cheq_no" id="cheq_no" type="text" value="<?php echo $data1[1];?>" style="width:100px" /></td>
                            <th style="text-align: right; width: 20%; vertical-align: middle">Cq Date</th>
                            <td style="text-align: left; width: 25%">
                                <input name="cheq_date" id="cheq_date" type="date" value="<?=$data1[2];?>" style="" />
                            </td>
                        </tr>
                    </table>


                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <tr style="background-color: bisque">
                            <th style="width: 2%">S/L</th>
                            <th>A/C Ledger</th>
                            <th style="width: 25%">Narration</th>
                            <th style="width: 20%">Cost Center</th>
                            <th style="width: 12%">Debit</th>
                            <th style="width: 12%">Credit</th>
                        </tr>
    <?php
    $i=0;
    $d_total=0;
    $c_total=0;
    $sql="select a.dr_amt,a.cr_amt,b.ledger_name,b.ledger_id,a.narration,a.id,a.cc_code from accounts_ledger b, journal a where a.ledger_id=b.ledger_id and a.tr_from = '$tr_from' and a.jv_no='$jv_no' and a.ledger_id>0";
    $result=mysqli_query($conn, $sql);
    while($data=mysqli_fetch_object($result)){
        $id = $data->id; ?>

                        <tr>
                            <td style="text-align: center; vertical-align: middle"><?=$i=$i+1;?></td>
                            <td style="text-align: left; vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_<?=$id?>" id="ledger_<?=$id?>">
                                    <?=foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_name)',$data->ledger_id, 'status=1'); ?>
                                </select>
                            </td>
                            <td style="text-align: left; vertical-align: middle">
                                <textarea  name="narration_<?=$id;?>" id="narration_<?=$id;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:37px; font-size: 11px; text-align:center"><?=$data->narration;?></textarea>
                            </td>
                            <td style="text-align: left; vertical-align: middle">
                                <select class="select2_single form-control" style="width:99%" tabindex="-1" name="cc_code<?=$id;?>" id="cc_code<?=$id;?>">
                                    <?php foreign_relation('cost_center', 'id', 'CONCAT(id,":", center_name)', $data->cc_code, 'status=1'); ?>
                                </select>
                            </td>
                            <td style="text-align: center; vertical-align: middle"><input name="dr_amt<?=$id;?>" type="text" id="dr_amt<?=$id;?>" value="<?=$data->dr_amt?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                            <td style="text-align: center; vertical-align: middle"><input name="cr_amt<?=$id;?>" type="text" id="cr_amt<?=$id;?>" value="<?=$data->cr_amt?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td style="text-align: center; vertical-align: middle"><?=++$i;?></td>
                            <td style="text-align: left; vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_new1" id="ledger_new1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',1, 'status=1'); ?>
                                </select>
                            </td>
                            <td style="text-align: center; vertical-align: middle"><textarea name="narration_new1" id="narration_new1" class="form-control col-md-7 col-xs-12" style="width:100%; height:37px; font-size: 11px; text-align:center" value=""></textarea></td>
                            <td style="text-align: left; vertical-align: middle">
                                <select class="select2_single form-control" style="width:99%" tabindex="-1"  name="cc_new1" id="cc_new1">
                                    <?=foreign_relation('cost_center', 'id', 'CONCAT(id,":", center_name)', $info[6], 'status=1'); ?>
                                </select>
                            </td>
                            <td style="text-align: right; vertical-align: middle"><input name="dr_amt_new1" type="text" id="dr_amt_new1" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                            <td style="text-align: right; vertical-align: middle"><input name="cr_amt_new1" type="text" id="cr_amt_new1" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                        </tr>

                        <tr>
                            <td style="text-align: center; vertical-align: middle"><?=++$i;?></td>
                            <td style="text-align: left; vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_new2" id="ledger_new2">
                                    <option></option>
                                    <?=foreign_relation('accounts_ledger','ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',1, 'status=1'); ?>
                                </select>
                            </td>
                            <td style="text-align: center; vertical-align: middle"><textarea name="narration_new2" id="narration_new2" class="form-control col-md-7 col-xs-12" style="width:100%; height:37px; font-size: 11px; text-align:center" value="" ></textarea></td>
                            <td style="text-align: left; vertical-align: middle">
                                <select class="select2_single form-control" style="width:99%" tabindex="-1"  name="cc_new2" id="cc_new2">
                                <?=foreign_relation('cost_center', 'id', 'CONCAT(id,":", center_name)', $info[6], 'status=1'); ?>
                                </select>
                            </td>
                            <td style="text-align: right; vertical-align: middle"><input name="dr_amt_new2" type="text" id="dr_amt_new2" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                            <td style="text-align: right; vertical-align: middle"><input name="cr_amt_new2" type="text" id="cr_amt_new2"  class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                        </tr>
                        <tr align="center">
                            <td colspan="4" style="vertical-align:middle; font-weight: bold" align="right">Total Amount :</td>
                            <td style="vertical-align:middle; font-weight: bold"><input name="dr_amt" type="text" id="dr_amt" value="<?=$d_total?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" readonly="readonly"/></td>
                            <td style="vertical-align:middle; font-weight: bold"><input name="cr_amt" type="text" id="cr_amt" value="<?=$c_total?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" readonly="readonly" /></td>
                        </tr>

    <?php
    if($vtype=='receipt'||$vtype=='Receipt') $page="credit_note.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    if($vtype=='payment'||$vtype=='Payment') $page="debit_note.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    if($vtype=='coutra'||$vtype=='Coutra') $page="coutra_note_new.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    if($vtype=='journal_info'||$vtype=='Journal_info') $page="journal_note_new.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    ?>



        <?php
        $GET_status=find_a_field('journal','distinct status','jv_no='.$_GET['v_no']);
        if($GET_status=='MANUAL' || $GET_status=='UNCHECKED'){
            $access_days=30;
            $datetime1 = date_create($data1[7]);
            $datetime2 = date_create(date('Y-m-d'));
            $interval = date_diff($datetime1, $datetime2);
            $v_d=$interval->format('%a');
            if($_SESSION['userlevel']=='2'){
                if($v_d>$access_days){ echo '<h6 style="text-align: center; color:red">Access Restricted.</h6>';} else {?>
                    <tr><td colspan="6"><textarea style="float: left; margin-left:1%; font-size: 11px; width: 250px" name="note" id="note" placeholder="Type the reason for the update or deletion" ></textarea></td></tr>
                    <tr><td colspan="2"><button style="float: left; margin-left:1%; font-size: 11px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Completed?");'>Delete Voucher</button></td>
                    <td colspan="2"><button style="float: left; margin-left:30%; font-size: 11px" type="submit" name="update" id="update" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Update Voucher</button></td>
                    <td colspan="2"><button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="check" id="check" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Check & Proceed to next</button></td>
                <? }}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This voucher has been '.$GET_status.' !!</i></h6>'; } ?>
    <?php } if($_SESSION['userlevel']=='1'){ ?>
    <tr><td colspan="6"><textarea style="float: left; margin-left:1%; font-size: 11px; width: 250px" name="note" id="note" placeholder="Type the reason for the update or deletion" ></textarea></td></tr>
<tr><td colspan="2"><button style="float: left; margin-left:1%; font-size: 11px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Completed?");'>Delete Voucher</button></td>
    <td colspan="2"><button style="float: left; margin-left:30%; font-size: 11px" type="submit" name="update" id="update" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Update Voucher</button></td>
    <td colspan="2"><button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="check" id="check" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Check & Proceed to next</button></td>
    <?php } ?>

    <script type="application/javascript">
        function add_sum()
        {
            var dr_amt_new1 = ((document.getElementById('dr_amt_new1').value)*1)+0;
            var dr_amt_new2 = ((document.getElementById('dr_amt_new2').value)*1)+0;
            var cr_amt_new1 = ((document.getElementById('cr_amt_new1').value)*1)+0;
            var cr_amt_new2 = ((document.getElementById('cr_amt_new2').value)*1)+0;
            var dr_total = dr_amt_new1;
            var cr_total = cr_amt_new1;
            if(cr_amt_new2>0){
                cr_total = cr_total + cr_amt_new2;}
            if(dr_amt_new2>0){
                dr_total = dr_total + dr_amt_new2;}
            <?
            for($i=1;$i<=$pi;$i++){
                if($entry[$i]>0){
                    echo "cr_total = cr_total+((document.getElementById('cr_amt_".$entry[$i]."').value)*1);";
                    echo "dr_total = dr_total+((document.getElementById('dr_amt_".$entry[$i]."').value)*1);";
                }} ?>

            document.getElementById('cr_amt').value = cr_total.toFixed(2);
            document.getElementById('dr_amt').value = dr_total.toFixed(2);
        }
        function validate_total() {
            var dr_amt = ((document.getElementById('dr_amt').value)*1);
            var cr_amt = ((document.getElementById('cr_amt').value)*1);
            if(dr_amt==cr_amt)
                return true;
            else
            {
                alert('Debit and Credit have to be equal.Please Re-Check This voucher.');
                return false;
            }
        }
        function loadinparent(url)
        {
            self.opener.location = url;
            self.blur();
        }
    </script>

    <input name="count" id="count" type="hidden" value="<?=$pi;?>" />
    </table>
    </form>
    </div>
    </div>
    </div>
<?=$html->footer_content();
mysqli_close($conn);?>