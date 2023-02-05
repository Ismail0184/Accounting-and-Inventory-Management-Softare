<?php
require_once 'support_file.php';
$entry_at = date('Y-m-d H:s:i');
$title='LC Received';

$table_master='lc_lc_master';
$table_details='lc_lc_details';
$table_received='lc_lc_received';
$lc_received_master="lc_received_master";
$journal_accounts="journal";
$unique='id';
$crud   = new crud($table_master);
$lc_no=$_GET[$unique];
$$unique = $_GET[$unique];
$LC_data = find_all_field(''.$table_master.'','',''.$unique.' ='.$lc_no);
$LC_cost_amount=find_a_field('payment','sum(dr_amt)','lc_id='.$LC_data->id);

if(prevent_multi_submit()){
    if(isset($_POST['confirmsave']))
    {
        $rs=mysqli_query($conn, "Select d.*,i.*
from 
".$table_details." d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.lc_id=".$$unique."
 order by d.id");
        while($row=mysqli_fetch_object($rs)){
            $id=$row->id;
            $lcqty=$_POST['lc_qty_'.$id];
            $lcrate=$_POST['lc_rate_'.$id];
            $lcamt=$lcqty*$lcrate;
            $lot_number=$_POST['lot_number_'.$id];
            $_POST['rcv_Date'] = date('Y-m-d');
            $_POST['lcr_no'] = $_POST[lcr_no];
            $_POST['lc_id'] = $_GET[id];
            $_POST['rec_date'] = $rcv_Date;
            $_POST['rate'] = $row->rate;
            $_POST['qty'] = $lcqty;
            $_POST['amount'] = $lcamt;
            $_POST['lot_number'] = $lot_number;
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $row->item_id;
            $_POST['warehouse_id'] = $_POST[warehouse_id];
            $_POST['item_in'] = $lcqty;
            $_POST['item_price'] = $row->rate;
            $_POST['total_amt'] = $lcamt;
            $_POST['lot_number'] = $row->lot;
            $_POST['po_no'] = $_GET[id];
            $_POST['rate_in_local_currency'] = $_POST['rate_in_local_currency'.$id];
            $_POST['amount_in_local_currency'] = $_POST['amount_in_local_currency'.$id];
            $_POST['rate_in_USD_currency'] = $_POST['rate_in_USD_currency'.$id];
            $_POST['rate_in_NEG_currency'] = $_POST['lc_rate_'.$id];
            $_POST['tr_from'] = 'Imported';
            $_POST['custom_no'] = $row->custom_pr_no;
            $_POST['tr_no'] = $_POST[lcr_no];
            $_POST['entry_at'] = $entry_at;
            $_POST['sr_no'] = $id;
            $_POST['entry_by'] = $_SESSION[userid];
            $_POST['section_id'] = $row->section_id;
            $_POST['company_id'] = $row->company_id;
            $_POST[ip]=$ip;
            if($_POST['lc_qty_'.$id]>0) {
                $_POST[status]='UNCHECKED';
            $crud      =new crud($table_received);
            $crud->insert();
            }
            $party_payment=$party_payment+$_POST['amount_in_local_currency'.$id];
        }

        $crud      =new crud($lc_received_master);
        $crud->insert();

        $jv=next_journal_voucher_id();
        $receipt_no = $_SESSION['debitvoucherNOW'];
        $dotoday=date('Y-m-d');
        $transaction_con_date=date('Y-m-d');
        $tfrom='Imported';
        $pinoGEt=find_a_field('lc_pi_master','pi_no','id='.$LC_data->pi_id.'');
        $narration='Inventory Received Against PI NO#'.$pinoGEt.', LC NO#'.$LC_data->lc_no;
        $date = date('d-m-y');
        $j = 0;
        for ($i = 0; $i < strlen($date); $i++) {
            if (is_numeric($date[$i])) {
                $time[$j] = $time[$j] . $date[$i];
            } else {
                $j++;
            }
        }
        $date = mktime(0, 0, 0, $time[1], $time[0], $time[2]);
        $_SESSION[postdate] = $date;
        $Inventory_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$_POST[warehouse_id].'');
        $party_ledger=find_a_field('lc_buyer','ledger_id','party_id='.$LC_data->party_id.'');
        $LC_ledger='1003001200010000';
        $inventory_amount=$LC_cost_amount+$party_payment;
            add_to_journal_new($transaction_con_date,$proj_id, $jv, $_SESSION[postdate], $Inventory_ledger,$narration, $party_payment+$LC_cost_amount, 0,$tfrom, $receipt_no,$_GET[$unique],$_POST[dr_cc_code],$jvrow[sub_ledger_id],$_SESSION[usergroup],$jvrow[cheq_no],$jvrow[cheq_date],$create_date,$ip,$now,date('D'),$thisday,$thismonth,$thisyear,$_GET[id]);
            add_to_journal_new($transaction_con_date,$proj_id, $jv, $_SESSION[postdate], $party_ledger,$narration, 0,$party_payment,$tfrom, $receipt_no,$_GET[$unique],$_POST[cr_cc_code],$jvrow[sub_ledger_id],$_SESSION[usergroup],$jvrow[cheq_no],$jvrow[cheq_date],$create_date,$ip,$now,date('D'),$thisday,$thismonth,$thisyear,$_GET[id]);
            add_to_journal_new($transaction_con_date,$proj_id, $jv, $_SESSION[postdate], $LC_ledger,$narration, 0,$LC_cost_amount,$tfrom, $receipt_no,$_GET[$unique],$_POST[cr_cc_code],$jvrow[sub_ledger_id],$_SESSION[usergroup],$jvrow[cheq_no],$jvrow[cheq_date],$create_date,$ip,$now,date('D'),$thisday,$thismonth,$thisyear,$_GET[id]);
        unset($_POST);
    }}


$rs=mysqli_query($conn, "Select d.*,i.*
from 
".$table_details." d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.lc_id=".$_GET[$unique]."
 order by d.id");
$currency=find_a_field('currency','code','id='.$LC_data->currency);
?>


<?php require_once 'header_content.php'; ?>
<script src="js/vendor/modernizr-2.8.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>


<?php  if(isset($_GET[$unique])){
 require_once 'body_content_without_menu.php'; } else {
 require_once 'body_content.php'; } ?>
<form action="" method="post" id="ismail" name="ismail">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table style="width:100%; font-size: 11px"  cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="width:10%;">PI No</th><th style="width: 1%"> : </th>
                        <td style="width:20%;">
                            <input type="hidden" style="width: 120px;height: 25px; font-size: 11px" name="pi_id" id="pi_id" readonly value="<?=$LC_data->pi_id;?>" >
                            <input type="hidden" style="width: 120px;height: 25px; font-size: 11px" name="vendor_id" id="vendor_id" readonly value="<?=$LC_data->party_id;?>" >
                            <input type="hidden" style="width: 120px;height: 25px; font-size: 11px" name="lc_id" id="lc_id" readonly value="<?=$LC_data->id;?>" >
                            <input type="text" style="width: 90%; height: 25px; font-size: 11px" readonly value="<?=find_a_field('lc_pi_master','pi_no','id='.$LC_data->pi_id.'');?>" ></td>
                        <th style="width: 15%">PI Date</th><th style="width: 1%"> : </th>
                        <td style="width:20%;"><input type="text" style="width: 90%;height: 25px; font-size: 11px" readonly value="<?=find_a_field('lc_pi_master','pi_issue_date','id='.$LC_data->pi_id.'');?>" ></td>
                        <th style="width:15%;">R. Date</th><th style="width: 1%"> : </th>
                        <td style="width:20%;"><input name="rcv_Date" min="<?=date('Y-m-d');?>" id="rcv_Date" value="<?=date('Y-m-d');?>" type="date" style="width: 90%; height: 25px; font-size: 11px" ></td>
                    </tr>
                    <tr><td style="height: 5px"></td></tr>
                    <tr>
                        <th style="">LC No</th><th style="width: 1%"> : </th>
                        <td>
                            <input type="hidden" style="width: 120px; height: 25px;font-size: 11px" name="lcr_no" id="lcr_no" readonly value="<?=find_a_field('lc_lc_received','MAX(lcr_no)','1')+1;?>" >
                            <input type="text" style="width: 90%;height: 25px; font-size: 11px" readonly value="<?=$LC_data->lc_no;?>" ></td>
                        <th style="">LC Date</th><th style="width: 1%"> : </th>
                        <td><input type="text" style="width: 90%; height: 25px; font-size: 11px" readonly value="<?=$LC_data->lc_issue_date;?>" ></td>
                        <th>Received Destination</th><th style="width: 1%"> : </th>
                        <td>
                            <select required style="width: 90%;height: 25px" name="warehouse_id" id="warehouse_id">
                                <option></option>
                                <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, 'use_type in (\'PL\',\'WH\')','order by warehouse_id'); ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td style="height: 5px"></td></tr>

                    <tr>
                        <th style="">Remarks</th><th style="width: 1%"> : </th>
                        <td><input name="remarks" id="remarks" value="<?=$LC_data->remarks;?>" type="text" style="width: 90%; height: 25px; font-size: 11px" ></td>
                        <th style="">C. R (USD to BDT)</th><th style="width: 1%"> : </th>
                        <td><input type="number" step="any" name="currency_conversion_rate_USD" id="currency_conversion_rate_USD" style="width: 90%;height: 25px; font-size: 11px"></td>
                        <th>C. Rate(<?=$currency?> to BDT)</th><th style="width: 1%"> : </th>
                        <td><input type="number" step="any" required name="currency_conversion_rate" id="currency_conversion_rate" style="width: 90%;height: 25px; font-size: 11px"></td>
                    </tr>
                </table>
            </div></div></div>

    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table align="center"  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <thead>
                    <tr style="background-color: blanchedalmond">
                        <th style="text-align:center; width: 1%; vertical-align: middle">SL</th>
                        <th style="vertical-align: middle">Material Description</th>
                        <th style="text-align:center; width: 5%; vertical-align: middle">Unit</th>
                        <th style="text-align:center; width: 10%; vertical-align: middle">LC Qty</th>
                        <th style="text-align:center; width: 10%; vertical-align: middle">Received Qty</th>
                        <th style="text-align:center; width: 10%; vertical-align: middle">NEG <br>Rate, <?=$currency_aa=find_a_field('currency','code','id='.$LC_data->currency);?></th>
                        <th style="text-align:center; width: 10%; vertical-align: middle">LC <br>Rate, USD</th>
                        <th style="text-align:center; width: 10%; vertical-align: middle">Rate, BDT<br>(USD+<?=$currency_aa;?>)</th>
                        <th style="text-align:center; width: 10%; vertical-align: middle">Input Qty</th>
                        <th style="text-align:center; width: 10%; vertical-align: middle">Amount, BDT</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while($row=mysqli_fetch_object($rs)){
                        $id=$row->id;
                        $lcqty=$_POST['lc_qty_'.$id];
                        $lcrate=$_POST['lc_rate_'.$id];
                        $lcamt=$lcqty*$lcrate;
                        $revdqty=find_a_field('lc_lc_received','SUM(qty)','item_id='.$row->item_id.' and lc_id='.$_GET[$unique].'');
                        $unrec_qty=$row->qty-$revdqty;
                        ?>
                        <SCRIPT language=JavaScript>
                            function doAlert<?=$id;?>(form)
                            {
                                var val=form.lc_qty_<?=$id;?>.value;
                                var val2=form.lc_qtyS_<?=$id;?>.value;
                                if (Number(val)>Number(val2)){
                                    alert('Oops!! Exceed Qty Limit!! Thanks');
                                    form.lc_qty_<?=$id;?>.value='';
                                }
                                form.lc_qty_<?=$id;?>.focus();
                            }</script>
                        <tr>
                            <td style="vertical-align:middle"><?=$i=$i+1;?></td>
                            <td style="text-align:left; vertical-align: middle"><?=$row->item_name;?></td>
                            <td style="text-align:center; vertical-align: middle"><?=$row->unit_name;?></td>
                            <td align="center" style="text-align:center">
                                <input type="hidden" style="width:70px" name="lot_number_<?=$id;?>" id="lot_number_<?=$id;?>" value="<?=$_SESSION['LCunique_id']++;?>" tabindex="-1" readonly />
                                <input type="text" name="lc_qtyS_<?=$id;?>" class="form-control col-md-7 col-xs-12"   value="<?=$row->qty;?>" readonly style="width:100%; text-align: center; height: 25px; font-size: 11px" tabindex="-1">
                            </td>
                            <td style="text-align: center"><input type="text" readonly class="form-control col-md-7 col-xs-12" value="<?=number_format($revdqty,0);?>"  style="width:100%; text-align: center; height: 25px; font-size: 11px" tabindex="-1"></td>
                            <td style="text-align: center"><input type="text" class="form-control col-md-7 col-xs-12" readonly name="lc_rate_<?=$id;?>" autocomplete="off" value="<?=$row->rate_in_NEG_currency;?>" id="lc_rate_<?=$id;?>"  style="width:100%; text-align: center; height: 25px; font-size: 11px" class='lc_rate_<?=$ids;?>' tabindex="-1"></td>
                            <td style="text-align: center"><input type="text" class="form-control col-md-7 col-xs-12" readonly name="rate_in_USD_currency<?=$id;?>" autocomplete="off" value="<?=$row->rate_in_USD_currency;?>" id="rate_in_USD_currency<?=$id;?>"  style="width:100%; text-align: center; height: 25px; font-size: 11px" class='lc_rate_<?=$ids;?>' tabindex="-1"></td>
                            <td align="center" style="text-align:center">
                                    <input type="text" class="form-control col-md-7 col-xs-12" name="rate_in_local_currency<?=$id;?>" readonly value=""  id="rate_in_local_currency<?=$id;?>"  style="width:100%; text-align: center; height: 25px; font-size: 11px" tabindex="1">
                            </td>
							<td align="center" style="text-align:center">
                                <?php if($unrec_qty>0){$cow++;?>
                                    <input type="text" name="lc_qty_<?=$id;?>" value="<?=$row->qty;?>"  id="lc_qty_<?=$id;?>" onkeyup="doAlert<?=$id;?>(this.form);"  style="width:100%; text-align: center; height: 25px; font-size: 11px" autocomplete="off" tabindex="1">
                                <?php } else { echo '<font style="font-weight: bold">Done</font>';} ?></td>
                            <td align="center" style="text-align:center">
                                <input type="text" name="amount_in_local_currency<?=$id;?>" id="amount_in_local_currency<?=$id;?>" style="width:100%; text-align: center; height: 25px; font-size: 11px" class='sum' tabindex="1">
                            </td>
                            <script>
                                $(function(){
                                    $('#lc_rate_<?=$id;?>, #lc_qty_<?=$id;?>').keyup(function(){
                                        var lc_rate_<?=$id;?> = parseFloat($('#lc_rate_<?=$id;?>').val()) || 0;
                                        var lc_qty_<?=$id;?> = parseFloat($('#lc_qty_<?=$id;?>').val()) || 0;
                                        $('#lc_amount_<?=$id;?>').val((lc_rate_<?=$id;?> * lc_qty_<?=$id;?>).toFixed(2));
                                    });
                                });
                            </script>
                            <script>
                                $(function(){
                                    $('#lc_qty_<?=$id;?>').keyup(function(){
                                        var lc_qty_<?=$id;?> = parseFloat($('#lc_qty_<?=$id;?>').val()) || 0;
                                        var rate_in_local_currency<?=$id;?> = parseFloat($('#rate_in_local_currency<?=$id;?>').val()) || 0;
                                        $('#amount_in_local_currency<?=$id;?>').val((lc_qty_<?=$id;?> * rate_in_local_currency<?=$id;?>).toFixed(14));
                                    });
                                });
                            </script>
                            <script>
                                $(function(){
                                    $('#currency_conversion_rate,#currency_conversion_rate_USD').keyup(function(){
                                        var lc_rate_<?=$id;?> = parseFloat($('#lc_rate_<?=$id;?>').val()) || 0;
                                        var rate_in_USD_currency<?=$id;?> = parseFloat($('#rate_in_USD_currency<?=$id;?>').val()) || 0;
                                        var currency_conversion_rate = parseFloat($('#currency_conversion_rate').val()) || 0;
                                        var currency_conversion_rate_USD = parseFloat($('#currency_conversion_rate_USD').val()) || 0;
                                        $('#rate_in_local_currency<?=$id;?>').val((lc_rate_<?=$id;?> * currency_conversion_rate+rate_in_USD_currency<?=$id;?> * currency_conversion_rate_USD).toFixed(14));
                                    });
                                });
                            </script>
                        </tr>
                        <?php
                        $totalamount=$totalamount+$lcamt;
                    }
                    $lctablew=mysqli_query($conn, "Select lh.* from LC_expenses_head lh where lh.status in ('1')");
                    while($lcrow=mysqli_fetch_array($lctablew)){
                        ?><?php $COST=find_a_field('lc_lc_master',''.$lcrow[db_column_name].'',''.$lcrow[db_column_name].'='.$lcrow[db_column_name].' and id='.$_GET[id].'');?>
                        <?php
                        $total_LC_COST=$total_LC_COST+$COST;
                    } ?>

                    <script>
                        $('.sum').blur(function () {
                            var sum = 0;
                            $('.sum').each(function() {
                                sum += Number($(this).val());
                            });
                            $('#vendor_payable').val((sum).toFixed(2));
                        });
                    </script>
                    </tbody>
                    <tr><th colspan="8" style="text-align: right; vertical-align: middle">Total Vendor Payable</th><td colspan="2"><input class="form-control col-md-7 col-xs-12" required type="text" step="any" min="1" name="vendor_payable" id="vendor_payable"  readonly style="width:100%; text-align: right; height: 25px; font-size: 11px" class="vendor_payable" tabindex="1"></td></tr>
                    <tr><th colspan="8" style="text-align: right; vertical-align: middle">Total LC Expenses</th><td colspan="2"><input class="form-control col-md-7 col-xs-12" required type="number" value="<?=$LC_cost_amount;?>" name="grand_total_lc_expenses" id="grand_total_lc_expenses" readonly style="width:100%; text-align: right; height: 25px; font-size: 11px" class="grand_total_lc_expenses" tabindex="1"></td></tr>
                    <tr><th colspan="8" style="text-align: right; vertical-align: middle">Total Total LC Amount</th><td colspan="2"><input class="form-control col-md-7 col-xs-12" required type="number" readonly name="grand_total_lc_amount" id="grand_total_lc_amount" style="width:100%; text-align: right; height: 25px; font-size: 11px" tabindex="1"></td></tr>
                    <script>
                        $(function(){
                            $('#vendor_payable').keyup(function(){
                                var vendor_payable = parseFloat($('#vendor_payable').val()) || 0;
                                var grand_total_lc_expenses = parseFloat($('#grand_total_lc_expenses').val()) || 0;
                                $('#grand_total_lc_amount').val((vendor_payable + grand_total_lc_expenses).toFixed(2));
                            });
                        });
                    </script>
                </table>
                <?php
                if($cow<1){

                    $vars['status']='COMPLETED';
                    $table_master='lc_lc_master';
                    $id=$_GET[id];
                    db_update($table_master, $id, $vars, 'id'); ?>
                    <h6 style="text-align: center; color: red; font-weight: bold"><i>THIS LC HAS BEEN COMPLETED !!</i></h6>
                <?php } else { ?>
                    <button  type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="confirmsave" class="btn btn-primary" style="float: right; font-size: 11px" tabindex="1">Add LC Received</button>
                <?php } ?>
</form>
</div></div></div>
<?php require_once 'footer_content.php' ?>
