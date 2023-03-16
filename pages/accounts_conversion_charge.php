<?php
require_once 'support_file.php';
$title="Pending Conversion Charge";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");
$now=time();
$unique='pi_no';
$unique_field='pi_date';
$table="production_issue_master";
$table_details="production_issue_detail";
$required_status="processing";
$page="accounts_conversion_charge.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$pi_master=find_all_field('production_issue_master','','pi_no='.$_GET[pi_no].'');


if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['verifi_status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        //echo "<script>window.close(); </script>";
    }

    if(isset($_POST['checked']))
    {
        $sql='Select td.*,i.* from '.$table_details.' td,item_info i
				  where td.item_id=i.item_id and 				  
				  td.pi_no='.$_GET[$unique].' group by td.id';
        $res_result= mysqli_query($conn, $sql);
        while($req_data=mysqli_fetch_object($res_result)) {
            $ids=$req_data->id;
            $already=find_a_field(''.$table_details.'','SUM(total_unit_received_by_accounts)','id='.$ids);;
            $qty_by_acc=$already+($_POST['total_unit_received_by_accounts_'.$ids]*$req_data->pack_size);
            $update_sql=mysqli_query($conn, "UPDATE ".$table_details." SET total_unit_received_by_accounts='".$qty_by_acc."' WHERE id=".$ids."");
        }

        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['verifi_status']="COMPLETED";
        $jv=next_journal_voucher_id();
        $transaction_date=$_POST[transaction_date];
        $enat=date('Y-m-d h:s:i');
        $cd =$_POST[c_date];
        $c_date=date('Y-m-d' , strtotime($cd));
        $invoice=$_POST[invoice];
        $date=date('d-m-y' , strtotime($transaction_date));
        $j=0;
        for($i=0;$i<strlen($date);$i++)
        {
            if(is_numeric($date[$i]))
            { $time[$j]=$time[$j].$date[$i];
            } else {
                $j++; } }
        $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
        if($_POST[ledger_1]>0 && $_POST[dr_amount_1]>0 && $_POST[cr_amount_3]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], ProductionCost, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_2], $_POST[dr_amount_2], $_POST[cr_amount_2], ProductionCost, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_3], $_POST[narration_3], $_POST[dr_amount_3], $_POST[cr_amount_3], ProductionCost, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

if ($_POST['warehouse_from'] != '') $warehouse_con = ' and pim.warehouse_from="' . $_POST['warehouse_from'] . '" ';
if(isset($_POST[viewreport])) {
    $sql = "Select pim.pi_no,pim.pi_no as STO_No,pim.pi_date as STO_date,w.warehouse_name as CMU_from,w2.warehouse_name as warehouse_to,pim.remarks,u.fname as entry_by,(select distinct jv_no from journal where tr_from in ('ProductionCost') and tr_id=pim.pi_no) as ac_journal
from 
production_issue_master pim,
warehouse w,
warehouse w2,
users u
where 
pim.entry_by=u.user_id and 
pim.warehouse_from=w.warehouse_id and 
pim.warehouse_to=w2.warehouse_id and 
pim.pi_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'".$warehouse_con." and 
w.use_type  in ('PL')  order by pim.pi_no desc";
} else {
    $sql = "Select pim.pi_no,pim.pi_no as STO_No,pim.pi_date as STO_date,w.warehouse_name as CMU_from,w2.warehouse_name as warehouse_to,pim.remarks,u.fname as entry_by,pim.verifi_status as status from 
production_issue_master pim,
warehouse w,
warehouse w2,
users u
where 
pim.entry_by=u.user_id and 
pim.warehouse_from=w.warehouse_id and 
pim.verifi_status='processing' and 
pim.warehouse_to=w2.warehouse_id and 
w.use_type  in ('PL')  order by pim.pi_no desc";
}
?>



<?php require_once 'header_content.php'; ?>
<script src="js/vendor/modernizr-2.8.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>
<?php if(!isset($_GET[$unique])){ ?>
    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select class="form-control" style="width:200px; font-size: 11px ;height: 25px" tabindex="-1"  name="warehouse_from" id="warehouse_from">
                        <option selected></option>
                        <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name) FROM  
							warehouse w  WHERE  1					 
							  order by w.warehouse_id";
                        advance_foreign_relation($sql_plant,$_POST[warehouse_from]);?>
                    </select></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View STO</button></td>
            </tr></table>
        <?=$crud->report_templates_with_status($sql);?>
    </form>
<?php } ?>

<?php if(isset($_GET[$unique])){ ?>
    <form action="" enctype="multipart/form-data" method="post" style="font-size: 11px" name="addem" id="addem" >
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                    <? require_once 'support_html.php';?>
                <table align="center" class="table table-striped table-bordered" style="width:100%;font-size:11px">
                <thead>
                        <tr style="background-color: bisque">
                            <th style="width: 2%; vertical-align: middle">#</th>
                            <!--th style="vertical-align: middle">Code</th-->
                            <th style="vertical-align: middle">Item Description</th>
                            <th style="vertical-align: middle">Unit</th>
                            <th style="vertical-align: middle; width: 10%">Rate</th>
                            <th style="width: 10%; vertical-align: middle">STO Qty</th>
                            <th style="width: 10%; vertical-align: middle">Qty By <br>Warehouse</th>
                            <th style="width: 10%; vertical-align: middle">Qty Rcvd.<br>By Accounts</th>
                            <th style="width: 10%; vertical-align: middle">Qty By <br>Accounts</th>
                            <th style="text-align: center; vertical-align: middle; width: 15%">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                        $sql='Select td.*,i.* from '.$table_details.' td,item_info i
				  where td.item_id=i.item_id and 				  
				  td.pi_no='.$_GET[$unique];
                        $res_result= mysqli_query($conn, $sql);
                        while($req_data=mysqli_fetch_object($res_result)){
                            $ids=$req_data->id;
                            $qty_by_acc=$_POST['total_unit_received_by_accounts_'.$ids];
                            if($req_data->total_unit_received<0){
                            $received_qty=$req_data->total_unit_received; } else {
                                $received_qty=$req_data->total_unit;}?>
                            <SCRIPT language=JavaScript>
                                function doAlert<?=$ids;?>(form)
                                {   var val=form.total_unit_received_by_accounts_<?=$ids;?>.value;
                                    var val2=form.receivable_qty_by_accounts_<?=$ids;?>.value;
                                    if (Number(val)>Number(val2)){
                                        alert('Oops!! Exceed Qty Limit!! Thanks');
                                        form.total_unit_received_by_accounts_<?=$ids;?>.value='';
                                    }
                                    form.total_unit_received_by_accounts_<?=$ids;?>.focus();
                                }</script>
                            <tr>
                                <td style="vertical-align: middle"><?=$ids;?></td>
                                <!--td style="vertical-align: middle"><?=$req_data->item_id;?></td-->
                                <td style="vertical-align: middle"><?=$req_data->item_name;?></td>
                                <td style="vertical-align: middle"><?=$req_data->unit_name;?> (<?=$req_data->pack_size?>)</td>
                                <td style="text-align:right">
                                    <input type="hidden" id="unit_price<?=$ids?>" value="<?=$req_data->conversion_cost;?>" class="unit_price<?=$ids?>">
                                    <input type="text" readonly class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" value="<?=$req_data->conversion_cost;?>"></td>
                                <td style="text-align: right; vertical-align: middle"><input type="text" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" readonly value="<?=$req_data->total_unit/$req_data->pack_size;?>"></td>
                                <td style="text-align: right; vertical-align: middle"><input type="text" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" readonly value="<?=$rcvd_by_warehouse=$received_qty/$req_data->pack_size;?>"></td>
                                <td style="text-align: right; vertical-align: middle"><input type="text" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" readonly value="<?=$rcvd_by_accounts=find_a_field(''.$table_details.'','total_unit_received_by_accounts','id='.$ids)/$req_data->pack_size;?>"></td>
                                <td style="text-align: center; vertical-align: middle">
                                    <?php  $unrec_qty=$rcvd_by_warehouse-$rcvd_by_accounts;
                                    if($unrec_qty>0){$cow++; ?>
                                    <input type="text" class="form-control col-md-7 col-xs-12" onkeyup="doAlert<?=$ids;?>(this.form);" name="total_unit_received_by_accounts_<?=$ids;?>" id="total_unit_received_by_accounts_<?=$ids;?>" style="width:100%; font-size: 11px" class="total_unit_received_by_accounts_<?=$ids?>">
                                    <?php } else { echo '<p style="font-weight: bold">Done</p>';} ?></td>
                                <input type="hidden" id="receivable_qty_by_accounts_<?=$ids;?>" style="width: 80px; text-align: center" value="<?=$rcvd_by_warehouse-$rcvd_by_accounts; ?>" readonly >
                                <td style="text-align: center; vertical-align: middle">
                                    <?php
                                    if($unrec_qty>0){$cow++; ?>
                                    <input type="text" readonly name="total_amt<?=$ids?>" id="total_amt<?=$ids?>" style="height: 34px;width: 100%;font-size: 11px; text-align: right" class="sum">
                                    <?php } else { echo '<p style="font-weight: bold">Done</p>';} ?></td>

                                </td>
                            </tr>
                            <script>
                                $(function(){
                                    $('#total_unit_received_by_accounts_<?=$ids?>, #unit_price<?=$ids?>').keyup(function(){
                                        var total_unit_received_by_accounts_<?=$ids?> = parseFloat($('#total_unit_received_by_accounts_<?=$ids?>').val()) || 0;
                                        var unit_price<?=$ids?> = parseFloat($('#unit_price<?=$ids?>').val()) || 0;
                                        $('#total_amt<?=$ids?>').val((total_unit_received_by_accounts_<?=$ids?> * unit_price<?=$ids?>).toFixed(2));
                                    });
                                });
                            </script>
                        <?php
                            $total_amt=$total_amt+$tamount;
                            $unique_STO=$req_data->custom_pi_no;
                        } ?>
                        <script>
                            $('.sum').focus(function () {
                                var sum = 0;
                                $('.sum').each(function() {
                                    sum += Number($(this).val());
                                });
                                $('#totalPrice').val((sum).toFixed(2));
                            });
                        </script>
                        <script>
                            $('.sum').focus(function () {
                                var sum = 0;
                                $('.sum').each(function() {
                                    sum += Number($(this).val());
                                });
                                $('#dr_amount_1').val((sum).toFixed(2));
                            });
                        </script>
                        <tr style="font-weight: bold"><td colspan="8" style="text-align: right; vertical-align: middle">Total = </td><td style="text-align: right; vertical-align: middle"><input type="text" class="form-control col-md-7 col-xs-12" readonly name="totalPrice" id="totalPrice" style="width:100%; font-size: 11px; text-align: right"></td></tr>
                        </tbody>
                    </table>
            </div></div></div>


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                    <input type="hidden" name="transaction_date" id="transaction_date" value="<?=$pi_master->pi_date?>">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:100%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="text-align: center; width: 10%">For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php  $tax=($total_amt*15/100); ?>

                        <tr>
                            <td style="text-align: center; width: 1%;vertical-align: middle">1</td>
                            <td style="text-align: center; vertical-align: middle">CMU Ledger</td>
                            <td>
                            <?php
                            $CMU_Ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$pi_master->warehouse_from.'');
                            $Payable_Ledger=find_a_field('warehouse','ledger_id','warehouse_id='.$pi_master->warehouse_from.'');
                            $VAT_current_account='1005000400000000';
                            ?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" required="ledger_1" tabindex="-1" name="ledger_1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $CMU_Ledger, 'ledger_group_id in (\'1007\')'); ?>
                                </select>
                            </td>
                            <td style="text-align: center; width: 20%"><input type="text" name="narration_1" tabindex="-1" id="narration_1" value="FG Transfer, ID#<?=$unique_STO;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text"  name="dr_amount_1" readonly id="dr_amount_1" required  value="<?=$total_amt;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" class="dr_amount_1"></td>
                            <td style="text-align: right"><input type="text"  name="cr_amount_1" readonly tabindex="-1" value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center; vertical-align: middle">2</td>
                            <td style="text-align: center;vertical-align: middle">VAT Current Account</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_2"  name="ledger_2">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $VAT_current_account, 'ledger_group_id in ("1005","4015")'); ?>
                                </select></td>
                            <td style="text-align: center"><input type="text" name="narration_2" id="narration_2" tabindex="-1" value="TAX against STO, ID#<?=$unique_STO;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text"  name="dr_amount_2" id="dr_amount_2"   value="<?=$tax;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" class="dr_amount_2"></td>
                            <td style="text-align: right"><input type="text"  name="cr_amount_2" readonly value="0.00" tabindex="-1" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center; vertical-align: middle">3</td>
                            <td style="text-align: center;vertical-align: middle">Payable Ledger</td>
                            <td><select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_3"  name="ledger_3">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $Payable_Ledger, 'ledger_group_id in (\'2002\')'); ?>
                                </select></td>
                            <td style="text-align: center"><input type="text" name="narration_3" id="narration_3" tabindex="-1" value="Conversion Charge, ID#<?=$unique_STO;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text"  name="dr_amount_3" readonly value="0.00" tabindex="-1" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text"  name="cr_amount_3" id="cr_amount_3" readonly required  value="<?=$total_amt+$tax;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" class="cr_amount_3"></td>
                        </tr>
                        </tbody>
                    </table>



                    <?php if($cow<1){ echo '<h6 style="text-align:center; color:red; font-weight:"><i>Journal has been created !!</i></h6>';} else { ?>
                        <p>
                            <button style="float: left" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right;" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Create Journal </button>
                        </p>
                    <?php } ?>


            </div></div></div></form>
<?php } ?>
<?=$html->footer_content();?>

<script>
    $(function(){
        $('#dr_amount_1').keyup(function(){
            var dr_amount_1 = parseFloat($('#dr_amount_1').val()) || 0;
            $('#dr_amount_2').val((((dr_amount_1 / 1.15) * 15) / 100).toFixed(2));
        });
    });
</script>
<script>
    $(function(){
        $('#dr_amount_1, #dr_amount_2').keyup(function(){
            var dr_amount_1 = parseFloat($('#dr_amount_1').val()) || 0;
            var dr_amount_2 = parseFloat($('#dr_amount_2').val()) || 0;
            $('#cr_amount_3').val((dr_amount_1 + dr_amount_2).toFixed(2));
        });
    });
</script>


