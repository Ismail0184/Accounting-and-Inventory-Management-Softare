<?php

require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='do_no';
$table="sale_do_master";
$table_details="sale_do_details";
$table_chalan="sale_do_chalan";
$journal_item="journal_item";
$journal_accounts="journal";
$page='accounts_sales_return_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);

$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$chalan_date=date('Y-m-d');
$config_group_class=find_all_field("config_group_class","","1");
$chalan_date=date('Y-m-d');



if(isset($_POST['select_dealer_do'])) {
    $select_dealer_do=$_POST[dealer_code];
}
$$unique = $_SESSION[wpc_DO];
$do_master=find_all_field(''.$table.'','',''.$unique.'='.$_SESSION[wpc_DO].'');
$dealer_master=find_all_field('dealer_info','','dealer_code='.$do_master->dealer_code.'');
$CONN_warehouse_master=find_all_field('warehouse','','warehouse_id='.$do_master->depot_id.'');


if(isset($_POST['cancel']))
{   
    unset($_POST);
    unset($$unique);
}


if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['returned_by']=$_SESSION[userid];
        $_POST['returned_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        //echo "<script>self.opener.location = 'QC_sales_return_view.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }


    /// challan checked & data insert to challan table ...........................
    if(isset($_POST['checked']))
    {
        $d =$_POST[chalan_date];
        $chalan_date=date('Y-m-d' , strtotime($d));
        $_POST[chalan_date]=$chalan_date;
        $_POST[chalan_no] =  $_SESSION[challan_auto_number];
        $rs=mysqli_query($conn, "Select d.*,i.*
from 
".$table_details." d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$_SESSION[wpc_DO]."
 order by d.id");
        while($row=mysqli_fetch_object($rs)){
            $id=$row->id;
            $qty=$_POST['received_qty'.$id];
            $_POST['item_id'] = $row->item_id;
            $_POST[total_unit]=$qty;
            $_POST[order_no]=$row->id;
            $_POST['unit_price'] = $row->unit_price;
            $_POST['pkt_size'] = $row->pkt_size;
            $_POST['cogs_price'] = $row->cogs_price;
            $_POST['d_price'] = $row->d_price;
            $_POST['t_price'] = $row->t_price;
            $_POST['m_price'] = $row->m_price;
            $_POST[brand_id]=$row->brand_id;
            $_POST[pc_code]=$row->pc_code;
            $_POST['depot_id'] = $row->depot_id;
            $_POST['total_amt'] = $_POST['received_qty'.$id]*$row->unit_price;
            $_POST['dist_unit'] = $_POST['received_qty'.$id];
            $_POST['dealer_code'] = $row->dealer_code;
            $_POST['dealer_type'] = $row->dealer_type;
            $_POST['town'] = $row->town;
            $_POST['area_code'] = $row->area_code;
            $_POST['territory'] = $row->territory;
            $_POST['region'] = $row->region;
            $_POST['chalan_type'] = "Delivery";
            $_POST['challan_type'] = $row->do_type;
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row->id;
            $_POST[ip]=$ip;
            if($qty>0) {
                $crud = new crud($table_chalan);
                $crud->insert();
            }
        }
        $up_master=mysqli_query($conn,"UPDATE ".$table." SET challan_date='$_POST[challan_date]',delivery_man='$_POST[delivery_man]',driver_name_real='$_POST[driver_name_real]',vehicle_no='$_POST[vehicle_no]',transporter_name='$_POST[transporter_name]' where ".$unique."=".$_SESSION[wpc_DO]."");
        $type=1;
        unset($_POST);
    }

///// journal Creation
    if(isset($_POST['confirmed']))
    {
        $rs=mysqli_query($conn, "Select d.*,i.*
from 
".$table_chalan." d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$_SESSION[wpc_DO]."
 order by d.id");
        while($row=mysqli_fetch_object($rs)){
            $toal_amount=$toal_amount+$row->total_amt; // invoice value
            $per_unit_COGS_value=$row->total_unit*$row->cogs_price; // COGS Value Calculation
            $toal_COGS_amount=$toal_COGS_amount+$per_unit_COGS_value; // invoice total COGS
            $free=find_a_field(''.$table_chalan.'','SUM(total_unit)','item_id='.$row->item_id.' and do_no='.$_SESSION[wpc_DO].' and total_amt=0');
            $free_amt=$free*$row->cogs_price;
            $total_free_amt=$total_free_amt+$free_amt;

        }
        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
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
        $transitLedger=$config_group_class->finished_goods_in_transit;
        $re=mysqli_query($conn, "Select distinct chalan_no from sale_do_chalan where do_no='".$_SESSION[wpc_DO]."' order by chalan_no desc limit 1");
        $ch_row=mysqli_fetch_array($re);
        $narration = 'CH#'.$ch_row[chalan_no].'/'.$ch_id.'(DO#'.$_SESSION[wpc_DO].'), '.$_POST[remarks].'';
        $comission_narration=$do_master->commission.' % Super DB Commission, '.$narration;
        $free_narration='Free Products, '.$narration;
        if($toal_amount>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[dealer_ledger], $narration, $toal_amount, 0, Sales, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,$_POST[pc_code],$_SESSION[wpc_DO]);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[sales_ledger], $narration, 0, $toal_amount, Sales, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,$_POST[pc_code],$_SESSION[wpc_DO]);
        } // sales transtion start form here
        if($do_master->commission_amount>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[sales_special_discount], $comission_narration, $do_master->commission_amount, 0, Sales, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,$_POST[pc_code],$_SESSION[wpc_DO]);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[dealer_ledger], $comission_narration, 0, $do_master->commission_amount, Sales, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,$_POST[pc_code],$_SESSION[wpc_DO]);
        } // commission amount
        if($toal_COGS_amount>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $config_group_class->cogs_sales, $narration, $toal_COGS_amount, 0, Sales, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
        }
        if($total_free_amt>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $config_group_class->free_own_product, $free_narration, $total_free_amt, 0, Sales, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,$_POST[pc_code],$_SESSION[wpc_DO]);
        }
        $inventory_amount=$toal_COGS_amount+$total_free_amt;
        if($inventory_amount>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $CONN_warehouse_master->ledger_id_FG, $narration, 0, $inventory_amount, Sales, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
        }
        $up_master=mysqli_query($conn,"UPDATE ".$table_details." SET status='COMPLETED' where ".$unique."=".$_SESSION[wpc_DO]."");
        $up_master=mysqli_query($conn,"UPDATE ".$table_chalan." SET  status='COMPLETED' where ".$unique."=".$_SESSION[wpc_DO]."");
        $up_master=mysqli_query($conn,"UPDATE ".$table." SET challan_date='$_POST[challan_date]',driver_name='$_POST[delivery_man]',driver_name_real='$_POST[driver_name_real]',vehicle_no='$_POST[vehicle_no]',delivery_man='$_POST[delivery_man]',status='COMPLETED' where ".$unique."=".$_SESSION[wpc_DO]."");
        $type=1;
        unset($_POST);
        unset($_SESSION[wpc_DO]);

    }
}



// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$_SESSION[wpc_DO];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$status=find_a_field('sale_do_master','status','do_no='.$_SESSION[wpc_DO].'');
?>

<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>

    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 60%;; font-size: 11px">
            <tr><td>Dealer / Customer / Outlet</td>
                <td style="width:10px; text-align:center;vertical-align: middle"> -</td>
                <td style="vertical-align: middle"><select class="select2_single form-control" style="width:300px; font-size: 11px" tabindex="-1" required="required"  name="dealer_code" id="dealer_code">
                        <option></option>
                        <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $select_dealer_do, 'canceled="YES"'); ?>
                    </select></td>
                <td style="padding:10px;vertical-align: middle">
                    <?php if(isset($select_dealer_do)>0){ ?>
                        <button type="submit" style="font-size: 11px; height: 30px" name="cancel" id="cancel"  class="btn btn-danger">Cancel the Invoice</button>
                        <?php if($status!=='COMPLETED'){ ?>
                        <a align="center" href="do_challan_view.php?v_no=<?=$select_dealer_do;?>" target="_new"><img src="../../warehouse_mod/images/print.png" width="25" height="25" /></a>
                            <?php } else { ?>
                            <a target="_blank" href="chalan_view.php?v_no=<?=$select_dealer_do;?>"><img src="../../warehouse_mod/images/print.png" width="25" height="25" /></a>
                        <?php } ?>
                    <?php } else { ?>
                        <button type="submit" style="font-size: 11px;" name="select_dealer_do"  class="btn btn-primary">Create New Demand Order</button>
                    <?php } ?>
                </td>
            </tr></table>
    </form>

<?php if(isset($select_dealer_do)>0){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <?require_once 'support_html.php';?>
        <input type="hidden" name="dealer_ledger" value="<?=$dealer_master->account_code;?>">
        <input type="hidden" name="sales_ledger" value="<?=$config_group_class->sales_ledger;?>">
        <input type="hidden" name="pc_code" value="<?=$do_master->pc_code;?>">
        <input type="hidden" name="sales_special_discount" value="<?=$config_group_class->sales_special_discount;?>">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table style="width:100%; font-size: 11px">
                        <tr>
                            <th style="">DO No</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" name="do_no" readonly value="<?=$_SESSION[wpc_DO];?>"></td>
                            <th style="width: 15%">DO Date</th><th style="text-align: center; width: 2%">:</th><td><input type="date" style="width: 80%" name="do_date" readonly value="<?=$do_master->do_date;?>"></td>
                            <th style="width: 15%">Chalan No</th><th style="text-align: center; width: 2%">:</th><td>
                                <input readonly style="width: 80%" name="chalan_no" id="chalan_no" type="text" value="<?=$_SESSION[challan_auto_number];?>">
                                <input readonly style="width: 80%" name="chalan_date" type="hidden" value="<?=date('Y-m-d')?>"></td>
                        </tr>
                        <tr><td style="height: 5px"></td></tr>
                        <tr>
                            <th style="">Dealer Name</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=$dealer_master->dealer_name_e;?>"></td>
                            <th style="width: 15%">Delivery Address</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=$dealer_master->address;?>"></td>
                            <th style="width: 15%">Profit Center</th><th style="text-align: center; width: 2%">:</th><td><input style="width: 80%" readonly type="text" value="<?=find_a_field('profit_center','profit_center_name','id='.$do_master->pc_code.'');?>"></td>
                        </tr>
                        <tr><td style="height: 5px"></td></tr>
                        <tr>
                            <th style="">Region</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=$region=find_a_field('branch','BRANCH_NAME','BRANCH_ID='.$dealer_master->region);?>"></td>
                            <th style="width: 15%">Area</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=find_a_field('zon','ZONE_NAME','ZONE_CODE='.$dealer_master->area_code);?>"></td>
                            <th style="width: 15%">Territory</th><th style="text-align: center; width: 2%">:</th><td><input style="width: 80%" readonly type="text" value="<?=find_a_field('area','AREA_NAME','AREA_CODE='.$dealer_master->area_code.'');?>"></td>
                        </tr>
                        <tr><td style="height: 5px"></td></tr>
                        <tr>
                            <th style="">Remarks</th><th style="text-align: center; width: 2%">:</th><td><input type="text" name="remarks" style="width: 80%" readonly value="<?=$remarks;?>"></td>
                            <th style="width: 15%">Entry By</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=find_a_field('user_activity_management','fname','user_id='.$do_master->entry_by);?>"></td>
                            <th style="width: 15%">Entry At</th><th style="text-align: center; width: 2%">:</th><td><input style="width: 80%" readonly type="text" value="<?=$do_master->entry_at;?>"></td>
                        </tr>
                    </table>

                </div></div></div>
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tr style="background-color: bisque">
                <th style="vertical-align: middle">Truck No</th><td><input type="text" required name="vehicle_no" value="<?=$vehicle_no;?>" style="width:"></td>
                <th style="vertical-align: middle">Delivery Man</th><td><input type="text" required name="delivery_man" value="<?=$delivery_man;?>" style="width:"></td>
                <th style="vertical-align: middle">Driver Name</th><td><input type="text" required name="driver_name_real" value="<?=$driver_name_real;?>" style="width:"></td>
                <th style="vertical-align: middle">Transporter Name</th><td><input type="text" required name="transporter_name" value="<?=$transporter_name;?>" style="width:"></td>
            </tr>
        </table>


        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <thead>
            <tr style="background-color: blanchedalmond">
                <th style="vertical-align: middle">SL</th>
                <th style="vertical-align: middle">Code / Barcode</th>
                <th style="vertical-align: middle">Item Description</th>
                <th style="text-align:center; vertical-align: middle">Unit</th>
                <th style="text-align:center; vertical-align: middle">Pack Size</th>
                <th style="text-align:center; vertical-align: middle">Ordered Qty</th>
                <th style="text-align:center; vertical-align: middle">Delivered Qty</th>
                <th style="text-align:center; vertical-align: middle">UnDel. Qty</th>
                <th style="text-align:center; vertical-align: middle">Chalan Qty</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $rs=mysqli_query($conn, "Select d.*,i.*from 
".$table_details." d,
item_info i
 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$_SESSION[wpc_DO]."
 order by d.id");
            while($row=mysqli_fetch_object($rs)){
                $id=$row->id;
                $del_qty = find_a_field('sale_do_chalan','sum(total_unit)','do_no="'.$_SESSION[wpc_DO].'" and item_id="'.$row->item_id.'"');
                $undel_qty=$row->total_unit-$del_qty;
                ?>

                <tr>
                    <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                    <td style="text-align:left;vertical-align: middle"><?=$row->finish_goods_code;?></td>
                    <td style="text-align:left;vertical-align: middle"><?=$row->item_name;?></td>
                    <td style="text-align:center; vertical-align: middle"><?=$row->unit_name;?></td>
                    <td style="text-align:center; vertical-align: middle"><?=$row->pack_size;?></td>
                    <td style="text-align:center; vertical-align: middle"><?=$row->total_unit;?></td>
                    <td style="text-align:center; vertical-align: middle"><?=$del_qty;?></td>
                    <td align="center" style="width:15%; text-align:center;vertical-align: middle"><?=$undel_qty;?></td>
                    <SCRIPT language=JavaScript>
                        function doAlert<?=$id;?>(form)
                        {
                            var val=form.received_qty<?=$id;?>.value;
                            var val2=form.Un_del_<?=$id;?>.value;
                            if (Number(val)>Number(val2)){
                                alert('oops!! Exceed Received Limit!! Thanks');
                                form.received_qty<?=$id;?>.value='';
                            }

                            form.received_qty<?=$id;?>.focus();
                        }</script>
                    <input type="hidden" name="Un_del_<?=$id;?>" id="Un_del_<?=$id;?>" style="text-align: center; width: 80px; vertical-align: middle" value="<?=$undel_qty;?>" >
                    <td style="width:10%; text-align:center; vertical-align: middle">
                        <?php if($undel_qty>0){$cow++; ?>
                            <input type="text" name="received_qty<?=$id;?>" id="received_qty<?=$id;?>" onkeyup="doAlert<?=$id;?>(this.form);" style="text-align: center; width: 80px; vertical-align: middle" value="<?=$unrec_qty;?>" >
                        <?php } else { echo '<font style="font-weight: bold">Done</font>';} ?>
                    </td>
                </tr>
                <?php  $amountqty=$amountqty+$ttotal;  } ?>
            </tbody></table>




        <?php
        if($status!=='COMPLETED'){
            if($cow<1){
                $vars['verifi_status']='COMPLETED';
                $table_master='production_issue_master';
                $id=$$unique;
                db_update($table_master, $id, $vars, 'pi_no'); ?>
                <button style="float: right; font-size: 12px; margin-right: 1%" type="submit" name="confirmed" id="confirmed" class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>Confirmed & Finished the DO</button>
            <?php } else {?>
                <p>
                    <button style="float: left; font-size: 12px; margin-left: 1%" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned</button>
                    <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                    <button style="float: right; font-size: 12px; margin-right: 1%" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Received</button>
                </p>
            <? }} else echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>THIS DELIVERY ORDER IS COMPLETE !!</i></h6>'; ;?>
    </form>
<?php } ?>
<?php require_once 'footer_content.php' ?>