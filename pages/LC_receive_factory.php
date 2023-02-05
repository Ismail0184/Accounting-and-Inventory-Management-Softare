<?php
require_once 'support_file.php';



function LCuniqueid(){
    $bdtime=date_default_timezone_set('Asia/Dhaka');
    $idatess=date('Y-m-d');
    $sql="Select distinct lot_number from  lc_lc_receive where rcv_Date='$idatess'  ORDER BY lot_number DESC LIMIT 1";
    $result=mysql_query($sql);
    if (mysql_num_rows($result) == 0){
        $idates=date('Y-m-d');
        list( $year1, $month, $day) = split('[/.-]', $idatess);
        $tdatevalye=substr($year1,2,3).$month.$day;
        $vnos="".$tdatevalye."0001";
        $_SESSION['LCunique_id']= $vnos; //echo $_SESSION['vno']  .  $idates;
    } else {
        while($row = mysql_fetch_array($result)) {
            $sl= substr($row['lot_number'],-3);
            $sl=$sl+1;
            if (strlen($sl)==1) {
                $sl="000".$sl;
            } else if (strlen($sl)==2){
                $sl="0".$sl;
            }
            $idatess=date('Y-m-d');
            list( $year1, $month, $day) = split('[/.-]', $idatess);
            $tdatevalye=substr($year1,2,3).$month.$day;
            $_SESSION['LCunique_id']= "".$tdatevalye.$sl;
        }}}
LCuniqueid();




$entry_at = date('Y-m-d H:s:i');
$title='LC Received';
$table_master='lc_lc_master';
$table_details='lc_lc_details';
$table_received='lc_lc_received';
$journal_item="journal_item";
$journal_accounts="journal";
$page="LC_receive_factory.php";
$unique='id';
$crud   = new crud($table_master);
$lc_no=$_GET[$unique];
$$unique = $_GET[$unique];


$LC_data = find_all_field(''.$table_master.'','',''.$unique.' ='.$lc_no);

if(prevent_multi_submit()){
    if(isset($_POST['confirmsave']))
    {
        $rs=mysqli_query($conn, "Select d.*,i.*, (select item_name from item_info where item_id=d.fg_id) as fg_name
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
            $_POST['tr_from'] = 'LC Purchase';
            $_POST['custom_no'] = $row->custom_pr_no;
            $_POST['tr_no'] = $_POST[lcr_no];
            $_POST['entry_at'] = $entry_at;
            $_POST['sr_no'] = $id;
            $_POST['section_id'] = $row->section_id;
            $_POST['company_id'] = $row->company_id;
            $_POST[ip]=$ip;
            if($lcqty>0) {
            $crud      =new crud($table_received);
            $crud->insert();
            $crud      =new crud($journal_item);
            $crud->insert();
            }
        }


        $jv=next_journal_voucher_id();
        $receipt_no = $_SESSION['debitvoucherNOW'];
        $dotoday=date('Y-m-d');
        $transaction_con_date=date('Y-m-d');
        $tfrom='LC Purchase';
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
        $Inventory_ledger=find_a_field('warehouse','ledger_id_RM','warehouse_id='.$_POST[warehouse_id].'');
        $party_ledger=find_a_field('vendor','ledger_id','vendor_id='.$LC_data->party_id.'');
        $LC_ledger='1002000400010000';
            add_to_journal_new($transaction_con_date,$proj_id, $jv, $_SESSION[postdate], $Inventory_ledger,$narration, $_POST[vendor_payable], 0,$tfrom, $receipt_no,$_GET[$unique],$_POST[dr_cc_code],$jvrow[sub_ledger_id],$_SESSION[usergroup],$jvrow[cheq_no],$jvrow[cheq_date],$create_date,$ip,$now,date('D'),$thisday,$thismonth,$thisyear,$_GET[id]);
            add_to_journal_new($transaction_con_date,$proj_id, $jv, $_SESSION[postdate], $LC_ledger,$narration, 0,$_POST[vendor_payable],$tfrom, $receipt_no,$_GET[$unique],$_POST[cr_cc_code],$jvrow[sub_ledger_id],$_SESSION[usergroup],$jvrow[cheq_no],$jvrow[cheq_date],$create_date,$ip,$now,date('D'),$thisday,$thismonth,$thisyear,$_GET[id]);





        unset($_POST);
    }

    //for modify PS information ...........................


}


$rs=mysqli_query($conn, "Select d.*,i.*, (select item_name from item_info where item_id=d.fg_id) as fg_name
from 
".$table_details." d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.lc_id=".$_GET[$unique]."
 order by d.id");
?>


<?php require_once 'header_content.php'; ?>
<script src="js/vendor/modernizr-2.8.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content.php'; ?>


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
                        <th style="width: 10%">PI Date</th><th style="width: 1%"> : </th>
                        <td style="width:20%;"><input type="text" style="width: 90%;height: 25px; font-size: 11px" readonly value="<?=find_a_field('lc_pi_master','pi_issue_date','id='.$LC_data->pi_id.'');?>" ></td>
                        <th style="width:15%;">Remarks</th><th style="width: 1%"> : </th>
                        <td style="width:20%;"><input name="remarks" readonly id="remarks" value="<?=$LC_data->remarks;?>" type="text" style="width: 90%; height: 25px; font-size: 11px" ></td>
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
                </table>
            </div></div></div>



    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table align="center"  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <thead>
                    <tr style="background-color: blanchedalmond">
                        <th>SL</th>
                        <th>FG Name</th>
                        <th>Material Description</th>
                        <th style="text-align:center; width: 5%">Unit</th>
                        <th style="text-align:center; width: 10%">LC Qty</th>
                        <th style="text-align:center; width: 10%">Received Qty</th>
                        <th style="text-align:center; width: 15%">Rate</th>
                        <th style="text-align:center; width: 15%">GEN Qty</th>
                        <th style="text-align:center; width: 15%">Amount</th>

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

                        <tr>
                            <td style="width:3%; vertical-align:middle"><?=$i=$i+1;?></td>
                            <td><?=$row->fg_name;?></td>
                            <td style="text-align:left"><?=$row->item_name;?></td>
                            <td style="text-align:center"><?=$row->unit_name;?></td>

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
                            <td align="center" style="text-align:center">
                                <input type="hidden" style="width:70px" name="lot_number_<?=$id;?>" id="lot_number_<?=$id;?>" value="<?=$_SESSION['LCunique_id']++;?>" readonly />
                                <input type="text" name="lc_qtyS_<?=$id;?>" disabled   value="<?=$row->qty;?>" readonly style="width: 70px; text-align: center" >
                            </td>

                            <td style="text-align: center"><input type="text" disabled value="<?=number_format($revdqty,0);?>"  style="width: 70px; text-align: center" ></td>
                            <td style="text-align: center"><input type="text" name="lc_rate_<?=$id;?>" autocomplete="off" value="<?=$row->rate;?>" id="lc_rate_<?=$id;?>"  style="width: 50px; text-align: center" class='lc_rate_<?=$ids;?>'>
                            </td>
                            <td align="center" style="text-align:center">
                                <?php
                                if($unrec_qty>0){$cow++;?>
                                    <input type="text" name="lc_qty_<?=$id;?>"  id="lc_qty_<?=$id;?>" onkeyup="doAlert<?=$id;?>(this.form);"   style="width: 60px; text-align: center" class='lc_qty_<?=$ids;?>' autocomplete="off">
                                <?php } else { echo '<font style="font-weight: bold">Done</font>';} ?>
                            </td>


                            <td align="center" style="text-align:center">
                                <?php
                                if($unrec_qty>0){$cow++;?>
                                    <input style="width: 80px; text-align: right"  autocomplete="off" readonly type='text' id='lc_amount_<?=$id;?>' name='lc_amount_<?=$id;?>' value="" class='sum'  />
                                <?php } else { echo '<font style="font-weight: bold">Done</font>';} ?>
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
                        // we used jQuery 'keyup' to trigger the computation as the user type
                        $('.sum').keyup(function () {
                            // initialize the sum (total price) to zero
                            var sum = 0;
                            // we use jQuery each() to loop through all the textbox with 'price' class
                            // and compute the sum for each loop
                            $('.sum').each(function() {
                                sum += Number($(this).val());
                            });
                            // set the computed value to 'totalPrice' textbox
                            $('#totalPrice').val((sum).toFixed(2));
                        });
                    </script>
                    </tbody>
                    <tr><td colspan="7" style="text-align: right;font-weight: bold; vertical-align: middle; font-size: 11px">Total LC value in Currency of (<strong style="color: red"><?=$currency=find_a_field('currency','code','id='.$LC_data->currency.'');;?></strong>) = </td>
                        <td colspan="2" align="center" style="text-align:center"><input style="width: 98%; font-weight: bold; font-size: 11px; text-align: right" type='text' name="totalPrice" id='totalPrice' class='totalPrice' readonly /></td></tr>

                    <tr><td colspan="7" style="text-align: right;font-weight: bold; vertical-align: middle; font-size: 11px">Total LC value in BAT = </td>
                        <td colspan="2" align="center" style="text-align:center">
                            <input style="width: 30%; font-size: 11px; text-align: right" type='text' name="conversion_rate" id='conversion_rate' class='conversion_rate' placeholder="Ex. Rate" autocomplete="off"  required />
                            <input style="width: 68%; font-size: 11px; text-align: right;" type='text' name="total_lc_amount_after_conversion" id='total_lc_amount_after_conversion' class='total_lc_amount_after_conversion'  readonly /></td></tr>

                    <tr><td colspan="7" style="text-align: right;font-weight: bold; vertical-align: middle; font-size: 11px">Miscellaneous Cost in BDT = </td>
                        <td colspan="2" align="center" style="text-align:center">
                            <input style="width: 98%;font-size: 11px; text-align: center" type='text' name="miscellaneous_cost" id='miscellaneous_cost' class='miscellaneous_cost' placeholder="Cost in <?=$currency;?>"  /><br>
                            <input style="width: 30%; font-size: 11px; text-align: right; margin-top: 5px" type='text' name="conversion_rate2" id='conversion_rate2' class='conversion_rate2' placeholder="Ex. Rate" autocomplete="off" />
                            <input style="width: 68%; font-size: 11px; text-align: right;margin-top: 5px" type='text' name="total_miscellaneous_cost" id='total_miscellaneous_cost' class='total_miscellaneous_cost' autocomplete="off"  readonly />
                        </td></tr>

                    <tr><td colspan="7" style="text-align: right;font-weight: bold; vertical-align: middle; font-size: 11px">Total Vendor Payable Against LC in BAT = </td>
                        <td colspan="2" align="center" style="text-align:center"><input style="width: 98%; font-weight: bold; font-size: 11px; height: 25px; text-align: right; background: #dddddd;
    color: #333;
    border: 1px solid #CCCC " type='text' name="vendor_payable" id='vendor_payable' class='vendor_payable' readonly /></td></tr>



                </table>

                <script>
                    $(function(){
                        $('#conversion_rate, #totalPrice').keyup(function(){
                            var conversion_rate = parseFloat($('#conversion_rate').val()) || 0;
                            var totalPrice = parseFloat($('#totalPrice').val()) || 0;
                            $('#total_lc_amount_after_conversion').val((conversion_rate * totalPrice).toFixed(2));
                        });
                    });
                </script>

                <script>
                    $(function(){
                        $('#miscellaneous_cost, #conversion_rate2').keyup(function(){
                            var miscellaneous_cost = parseFloat($('#miscellaneous_cost').val()) || 0;
                            var conversion_rate2 = parseFloat($('#conversion_rate2').val()) || 0;
                            $('#total_miscellaneous_cost').val((miscellaneous_cost * conversion_rate2).toFixed(2));
                        });
                    });
                </script>

                <script>
                    $(function(){
                        $('#total_lc_amount_after_conversion, #total_miscellaneous_cost').keyup(function(){
                            var total_lc_amount_after_conversion = parseFloat($('#total_lc_amount_after_conversion').val()) || 0;
                            var total_miscellaneous_cost = parseFloat($('#total_miscellaneous_cost').val()) || 0;
                            $('#vendor_payable').val((total_lc_amount_after_conversion + total_miscellaneous_cost).toFixed(2));
                        });
                    });
                </script>
                <?php
                if($cow<1){
                    $vars['status']='COMPLETED';
                    $table_master='lc_lc_master';
                    $id=$_GET[id];
                    //db_update($table_master, $id, $vars, 'id');
                    ?>
                    <h6 style="text-align: center; color: red; font-weight: bold"><i>THIS LC HAS BEEN COMPLETED !!</i></h6>
                <?php } else { ?>
                    <button  type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="confirmsave" class="btn btn-primary" style="float: right; font-size: 11px">Add LC Received</button>
                <?php } ?>
</form>
</div></div></div>



<?php require_once 'footer_content.php' ?>
