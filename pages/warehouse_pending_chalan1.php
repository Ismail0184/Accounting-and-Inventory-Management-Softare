<?php require_once 'support_file.php'; ?>
<?
$title='Delivery Challan | DO No: '.$_GET[do_no];
$now=time();
$unique='do_no';
$table="sale_do_master";
$table_details="sale_do_details";
$table_chalan="sale_do_chalan";
$journal_item="journal_item";
$journal_accounts="journal";
$page='warehouse_pending_chalan1.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$config_group_class=find_all_field("config_group_class","","1");
if(isset($_GET[do_no])) {
    unset($_SESSION['wpc_DO']);
    $_SESSION[wpc_DO]=$_GET[do_no];
} else {
    unset($_SESSION['wpc_DO']);
}
$$unique = $_SESSION[wpc_DO];
$do_master=find_all_field(''.$table.'','',''.$unique.'='.$_SESSION[wpc_DO].'');
$dealer_master=find_all_field('dealer_info','','dealer_code='.$do_master->dealer_code.'');
$CONN_warehouse_master=find_all_field('warehouse','','warehouse_id='.$do_master->depot_id.'');
$find_chalan_no=next_chalan_no($_SESSION['warehouse'],date('Y-m-d'));

//for Delete..................................
if(isset($_POST['cancel']))
{   unset($_SESSION['wpc_DO']);
    unset($_POST);
    unset($$unique);
    echo "<script>window.close(); </script>";
}
if(prevent_multi_submit()){
    if(isset($_POST['checked']))
    {
        $chalan_date=$_POST[chalan_date];
        $_POST[chalan_no] = $_POST[chalan_no];
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
            $_POST['cogs_price'] = find_a_field('journal_item','item_price','do_no='.$_GET[do_no].' and item_id='.$row->item_id.'');
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
            $_POST['gift_type'] = $row->gift_type;
            $_POST[ip]=$ip;
            if($qty>0) {
                $crud = new crud($table_chalan);
                $crud->insert();
            }
        }
        $jv=next_journal_voucher_id();

        if ($_POST[do_type]=='sales') {
            if (($_POST[ledger_1] > 0) && (($_POST[ledger_2] && $_POST[dr_amount_1]) > 0) && ($_POST[cr_amount_2] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_1], 0, $_POST[cr_amount_2], Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // sales start form here
            if (($_POST[ledger_3] > 0) && (($_POST[ledger_4] && $_POST[dr_amount_3]) > 0) && ($_POST[cr_amount_4] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_3], $_POST[narration_3], $_POST[dr_amount_3], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_4], $_POST[narration_3], 0, $_POST[cr_amount_4], Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // COGS start form here
            if (($_POST[ledger_5] > 0) && (($_POST[ledger_6] && $_POST[dr_amount_5]) > 0) && ($_POST[cr_amount_6] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_5], $_POST[narration_5], $_POST[dr_amount_5], 0, Sales, $_POST[chalan_no], $$unique, $_POST[sales_cost_center], 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_6], $_POST[narration_5], 0, $_POST[cr_amount_6], Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Comission form here
            if (($_POST[ledger_7] > 0) && ($_POST[dr_amount_7] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_7], $_POST[narration_7], $_POST[dr_amount_7], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Free own products
            if (($_POST[ledger_8] > 0) && ($_POST[dr_amount_8] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_8], $_POST[narration_8], $_POST[dr_amount_8], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Free other SKU
            if (($_POST[ledger_9] > 0) && ($_POST[dr_amount_9] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_9], $_POST[narration_9], $_POST[dr_amount_9], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Free other SKU
            if (($_POST[ledger_10] > 0) && ($_POST[cr_amount_10] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_10], $_POST[narration_10], 0, $_POST[cr_amount_10], Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Free other SKU
            if (($_POST[ledger_11] > 0) && ($_POST[dr_amount_11] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_11], $_POST[narration_11], $_POST[dr_amount_11], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Cash discounted on style forever products
            if (($_POST[ledger_12] > 0) && ($_POST[dr_amount_12] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_12], $_POST[narration_12], $_POST[dr_amount_12], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Cash discount on Remond
            if (($_POST[ledger_13] > 0) && ($_POST[cr_amount_13] > 0)) {
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_13], $_POST[narration_13], 0, $_POST[cr_amount_13], Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            } // Total Cash Discount

        } // end of sales invoice
        if($_POST[do_type]=='sample' || $_POST[do_type]=='display' || $_POST[do_type]=='gift' || $_POST[do_type]=='free'){
            if (($_POST[ledger_1] > 0) && (($_POST[ledger_4] && $_POST[dr_amount_1]) > 0)) {
                $sample_amount=find_a_field('journal_item','SUM(total_amt)',''.$unique.'='.$_SESSION[wpc_DO]);
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[other_sales_invoice_ledger], $_POST[narration_1], $sample_amount, 0, $_POST[do_type].' issue', $_POST[chalan_no], $$unique, $_POST[cc_code], 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
                add_to_journal_new($_POST[do_date], $proj_id, $jv, $date, $_POST[ledger_4], $_POST[narration_3], 0, $sample_amount, $_POST[do_type].' issue', $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
            }} // end of other invoice

        $up_master=mysqli_query($conn,"UPDATE ".$table_details." SET status='COMPLETED' where ".$unique."=".$_SESSION[wpc_DO]."");
        $up_master=mysqli_query($conn,"UPDATE ".$table_chalan." SET  status='COMPLETED' where ".$unique."=".$_SESSION[wpc_DO]."");
        $up_journal=mysqli_query($conn,"UPDATE ".$journal_item." SET  sr_no='".$_POST[chalan_no]."' where ".$unique."=".$_SESSION[wpc_DO]."");
        $up_master=mysqli_query($conn,"UPDATE ".$table." SET challan_date='$_POST[challan_date]',driver_name='$_POST[delivery_man]',driver_name_real='$_POST[driver_name_real]',vehicle_no='$_POST[vehicle_no]',delivery_man='$_POST[delivery_man]',status='COMPLETED' where ".$unique."=".$_SESSION[wpc_DO]."");
        $type=1;
        unset($_POST);
        unset($_SESSION[wpc_DO]);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$_SESSION[wpc_DO];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$status=find_a_field('sale_do_master','status','do_no='.$_SESSION[wpc_DO].'');
if($_SESSION["userlevel"]=='5') {
    $warehouse_conn = '';
} else {
    $warehouse_conn=' and m.depot_id='.$_SESSION[warehouse].'';
}
$config_group_class=find_all_field("config_group_class","","1");
$inventory_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$_SESSION[warehouse]);
$pending_do_list="";
if($do_master->do_type=='sales'){
    $do_type_get='Sales';
} else {
    $do_type_get=$do_master->do_type.' issued';
}
$cash_discount=find_a_field('sale_do_details','SUM(total_amt)','item_id="1096000100010312" and '.$unique.'='.$$unique);
$cash_discount_on_SF=mysqli_query($conn, "select SUM(sdd.total_amt) as total_amt from sale_do_details sdd,item_info i where sdd.gift_on_item=i.item_id and i.brand_id='1' and sdd.item_id='1096000100010312' and sdd.do_no=".$$unique." group by sdd.do_no");
$cd_data=mysqli_fetch_object($cash_discount_on_SF);
echo $cd_data->total_amt;

$cash_discount_on_Raymond=mysqli_query($conn, "select SUM(sdd.total_amt) as total_amt from sale_do_details sdd,item_info i where sdd.gift_on_item=i.item_id and i.brand_id='2' and sdd.item_id='1096000100010312' and sdd.do_no=".$$unique." group by sdd.do_no");
$cd_data_RMND=mysqli_fetch_object($cash_discount_on_Raymond);

echo '<br>';
echo $cd_data_RMND->total_amt;

$narration=$do_type_get." to ".$dealer_master->dealer_name_e.', Do No # '.$_SESSION[wpc_DO].', Challan No # '.$find_chalan_no;
if (isset($_POST[viewreport])) {
    $res = "SELECT  m.do_no,m.do_no,m.do_date,m.do_type,d.dealer_name_e as customer_name,m.remarks,m.delivery_address,uam.fname as entry_by,m.entry_at,m.sent_to_warehuse_at as sent_at,m.status FROM
							 sale_do_master m,
							dealer_info d,
							user_activity_management uam
							 where
							 m.dealer_code=d.dealer_code and
							 m.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and
							 m.depot_id=".$_POST[depot_id]." and
							 m.status not in ('MANUAL','PROCESSING') and
							 m.entry_by=uam.user_id
							  order by m.do_no"; } else {
    $res = "SELECT  m.do_no,m.do_no,m.do_date,m.do_type,d.dealer_name_e as customer_name,m.remarks,m.delivery_address,uam.fname as entry_by,m.entry_at,m.sent_to_warehuse_at as sent_at,m.status FROM
							 sale_do_master m,
							dealer_info d,
							user_activity_management uam
							 where
							 m.dealer_code=d.dealer_code and
							 m.depot_id=".$_SESSION[warehouse]." and
							 m.status in ('CHECKED') and
							 m.entry_by=uam.user_id
							  order by m.do_no";
} ?>
<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=0, directories=no, status=0, menubar=0, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=700,left = 230,top = -1");}
    </script>
    <style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #white;}
        #customers tr:hover {background-color: #F0F0F0;}
        td{}
    </style>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>

<?php if(isset($_SESSION['wpc_DO'])>0){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <?require_once 'support_html.php';?>
        <input type="hidden" name="other_sales_invoice_ledger" value="<?=$config_group_class->free_sample_issue?>">
        <input type="hidden" name="cc_code" value="<?=$config_group_class->marketing_cost_center?>">
        <input type="hidden" name="sales_cost_center" value="<?=$config_group_class->sales_cost_center?>">
        <input type="hidden" name="do_type" value="<?=$do_master->do_type?>">
        <input type="hidden" name="challan_date" value="<?=date('Y-m-d H:s:i');?>">
        <input type="hidden" name="chalan_date" value="<?=date('Y-m-d');?>">
        <input type="hidden" name="do_date" value="<?=$do_master->do_date;?>">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table style="width:100%; font-size: 11px">
                        <tr>
                            <th style="">DO No</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" name="do_no" readonly value="<?=$_SESSION[wpc_DO];?>"></td>
                            <th style="width: 15%">DO Date</th><th style="text-align: center; width: 2%">:</th><td><input type="date" style="width: 80%" name="do_date" readonly value="<?=$do_master->do_date;?>"></td>
                            <th style="">D. Challan No</th><th style="text-align: center; width: 2%">:</th><td><input style="width: 80%" name="chalan_no" id="chalan_no" readonly type="text" value="<?=$find_chalan_no?>"></td>
                        </tr>
                        <tr><td style="height: 5px"></td></tr>
                        <tr>
                            <th style="">Dealer Name</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=$dealer_master->dealer_name_e;?>"></td>
                            <th style="width: 15%">Delivery Address</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=$dealer_master->address_e;?>"></td>
                            <th style="">Remarks</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%" readonly value="<?=$do_master->remarks;?>"></td>
                        </tr>
                    </table>
                </div></div></div>
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tr style="background-color: bisque">
                <th style="vertical-align: middle">Truck No</th><td style="vertical-align: middle"><input type="text" required name="vehicle_no" value="<?=$vehicle_no;?>" style="width:"></td>
                <th style="vertical-align: middle">Delivery Man</th><td style="vertical-align: middle"><input type="text" required name="delivery_man" value="<?=$delivery_man;?>" style="width:"></td>
                <th style="vertical-align: middle">Driver Name</th><td style="vertical-align: middle"><input type="text" required name="driver_name_real" value="<?=$driver_name_real;?>" style="width:"></td>
                <th style="vertical-align: middle">Transporter Name</th><td style="vertical-align: middle"><input type="text" required name="transporter_name" value="<?=$transporter_name;?>" style="width:"></td>
            </tr>
        </table>
        <table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <thead>
            <tr style="background-color: bisque">
                <th style="vertical-align: middle">SL</th>
                <th style="vertical-align: middle">Item Code</th>
                <th style="vertical-align: middle">Item Description</th>
                <th style="text-align:center; vertical-align: middle">Unit</th>
                <th style="text-align:center; vertical-align: middle">Pack Size</th>
                <th style="text-align:center; vertical-align: middle">Ordered Qty</th>
                <th style="text-align:center; vertical-align: middle">Delivered Qty</th>
                <th style="text-align:center; vertical-align: middle">Un.del Qty</th>
                <th style="text-align:center; vertical-align: middle">Chalan Qty</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $rs=mysqli_query($conn, "Select d.*,i.* from ".$table_details." d, item_info i where i.item_id=d.item_id and d.".$unique."=".$_SESSION[wpc_DO]." order by d.id");
            while($row=mysqli_fetch_object($rs)){
                $id=$row->id;
                $del_qty = find_a_field('sale_do_chalan','sum(total_unit)','do_no="'.$_SESSION[wpc_DO].'" and order_no="'.$row->id.'" and item_id="'.$row->item_id.'"');
                $undel_qty=$row->total_unit-$del_qty;?>
                <tr>
                    <td style="width:3%; vertical-align:middle"><?=$i=$i+1;?></td>
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
                                alert('oops!! Exceed Qty Limit!! Thanks');
                                form.received_qty<?=$id;?>.value='';
                            }
                            form.received_qty<?=$id;?>.focus();
                        }</script>
                    <input type="hidden" name="Un_del_<?=$id;?>" id="Un_del_<?=$id;?>" style="text-align: center; width: 80px; vertical-align: middle" value="<?=$undel_qty;?>" >
                    <td style="width:10%; text-align:center; vertical-align: middle">
                        <?php if($undel_qty>0){$cow++; ?>
                            <input type="text" name="received_qty<?=$id;?>" id="received_qty<?=$id;?>" onkeyup="doAlert<?=$id;?>(this.form);" style="text-align: center; width: 80px; vertical-align: middle" value="<?=$undel_qty;?>" >
                        <?php } else { echo '<font style="font-weight: bold">Done</font>';} ?>
                    </td>
                </tr>
                <?php  
                       $total_sales_amount=$total_sales_amount+$row->total_amt;
                       $amountqty=$amountqty+$ttotal;
                       $COGS_amount=find_a_field('journal_item','SUM(total_amt)','Remarks in ("buy") and do_no='.$_GET[do_no].' and gift_type in ("none")');
                       $free_own_product=find_a_field('journal_item','SUM(total_amt)','Remarks in ("get") and do_no='.$_GET[do_no].' and gift_type in ("free_own_products")');
                       $free_other_SKU=find_a_field('journal_item','SUM(total_amt)','Remarks in ("get") and do_no='.$_GET[do_no].' and gift_type in ("free_other_SKU")');;
                       $free_other_product=find_a_field('journal_item','SUM(total_amt)','Remarks in ("get") and do_no='.$_GET[do_no].' and gift_type in ("free_other_products")');;
            }
            //$total_sales_amount=$total_sales_amounts+find_a_field('sale_do_details','SUM(total_amt)','do_no='.$_SESSION[wpc_DO].' and gift_type in ("Cash")');
            $cash_discounts=substr($cash_discount,1);
            ?>
            </tbody></table>
        <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px; display:">
            <thead>
            <tr style="background-color: bisque">
                <th>#</th>
                <th style="width: 8%; vertical-align: middle; text-align: center">Journal</th>
                <th style="width: 10%; vertical-align: middle; text-align: center">For</th>
                <th style="vertical-align: middle">Accounts Description</th>
                <th style="text-align:center; width: 25%; vertical-align: middle">Narration</th>
                <th style="text-align:center; width: 12%; vertical-align: middle">Debit</th>
                <th style="text-align:center; width: 12%; vertical-align: middle">Credit</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle">1</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle">Sales Journal</th>
                <th style="text-align: center; vertical-align: middle">Customer Ledger</th>
                <td style="vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_1"  name="ledger_1">
                        <option  value="<?=$dealer_master->account_code;?>"><?=$dealer_master->account_code; ?>-<?=$customer_name=find_a_field('accounts_ledger','ledger_name','ledger_id='.$dealer_master->account_code.''); ?></option>
                    </select>
                </td>
                <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_1" id="narration_1" class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$narration?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?></textarea></td>
                <td align="center" style="vertical-align: middle"><input type="text" name="dr_amount_1" readonly value="<?=$total_sales_amount+$cash_discounts;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                <td align="center" style="vertical-align: middle"><input type="text" name="cr_amount_1" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
            </tr>
            <tr>
                <th style="text-align: center; vertical-align: middle">Sales Ledger</th>
                <td style="vertical-align: middle"><?$sales_ledger=$config_group_class->sales_ledger;?>
                    <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_2" id="ledger_2">
                        <?=foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_ledger, 'ledger_id='.$sales_ledger); ?>
                    </select></td>
                <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_2" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_2" readonly value="<?=$total_sales_amount+$cash_discounts;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
            </tr>
            <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle">2</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle">COGS Journal</th>
                <th style="text-align: center; vertical-align: middle">COGS Ledger</th>
                <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_3" id="ledger_3">
                        <?$COGS_sales=$config_group_class->cogs_sales;?>
                        <option  value="<?=$COGS_sales;?>"><?=$COGS_sales; ?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$COGS_sales.''); ?></option>
                    </select></td>
                <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_3" id="narration_3" class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$narration;?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?></textarea></td>
                <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_3" readonly value="<?=$COGS_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_3" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
            </tr>
            <tr>
                <th style="text-align: center; vertical-align: middle">Warehouse / Inventory</th>
                <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_4" id="ledger_4">
                        <option  value="<?=$inventory_ledger;?>"><?=$inventory_ledger?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$inventory_ledger.''); ?></option>
                    </select></td>
                <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_4"  readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_4" readonly value="<?=$COGS_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
            </tr>
            <?php if($do_master->commission>0):?>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle">3</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle">SO Comission</th>
                    <th style="text-align: center; vertical-align: middle">Comission Ledger</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_5" id="ledger_5">
                            <option  value="4002000500000000">4002000500000000 : <?=find_a_field('accounts_ledger','ledger_name','ledger_id="4002000500000000"'); ?></option>
                        </select></td>
                    <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_5" id="narration_5"  class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$do_master->commission.' % Super DB Commission, '.$narration.'';?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?></textarea></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_5" readonly value="<?=$do_master->commission_amount?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_5" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
                <tr>
                    <th style="text-align: center; vertical-align: middle">Customer Ledger</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_6" id="ledger_6">
                            <option  value="<?=$dealer_master->account_code;?>"><?=$dealer_master->account_code; ?>-<?=$customer_name?></option>
                        </select></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text"  name="dr_amount_6" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text"  name="cr_amount_6" readonly value="<?=$do_master->commission_amount?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
            <?php endif; ?>
            <?php if($free_own_product>0):?>
                <tr>
                    <th rowspan="4" style="text-align: center; vertical-align: middle">4</th>
                    <th rowspan="4" style="text-align: center; vertical-align: middle">Free Inventory Journal</th>
                    <th style="text-align: center; vertical-align: middle">Own Products</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_7" id="ledger_7">
                            <option  value="<?=$config_group_class->free_own_product?>"><?=$config_group_class->free_own_product?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$config_group_class->free_own_product); ?></option>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_7" value="<?='Free Own Products, '.$narration.'';?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_7" readonly value="<?=$free_own_product?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_7" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
            <?php endif; ?>
            <?php if($free_other_SKU>0):?>
                <tr>
                    <th style="text-align: center; vertical-align: middle">Free Other Product</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_8" id="ledger_8">
                            <option  value="<?=$config_group_class->free_other_SKU?>"><?=$config_group_class->free_other_SKU?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$config_group_class->free_other_SKU); ?></option>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_8" value="<?='Free Other SKU, '.$narration.''?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_8" readonly value="<?=$free_other_SKU?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_8" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
            <?php endif; ?>
            <?php if($free_other_product>0):?>
                <tr>
                    <th style="text-align: center; vertical-align: middle">Free Other Product</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_9" id="ledger_9">
                            <option  value="<?=$config_group_class->free_other_product?>"><?=$config_group_class->free_other_product?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$config_group_class->free_other_product); ?></option>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_9"  value="<?='Free Other Products, '.$narration.'';?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_9" readonly value="<?=$free_other_product?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_9" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
            <?php endif; $total_free_amt_last=$free_own_product+$free_other_SKU+$free_other_product; if($total_free_amt_last>0): ?>
                <tr>
                    <th style="text-align: center; vertical-align: middle">Warehouse / Inventory Ledger</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_10" id="ledger_10">
                            <option  value="<?=$inventory_ledger;?>"><?=$inventory_ledger?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$inventory_ledger.''); ?></option>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_10"  value="<?='Free Products, '.$narration.'';?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text"  name="dr_amount_10" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text"  name="cr_amount_10" readonly value="<?=$total_free_amt_last?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
            <?php endif; ?>

                <tr>
                    <th rowspan="3" style="text-align: center; vertical-align: middle">5</th>
                    <th rowspan="3" style="text-align: center; vertical-align: middle">Cash Discount</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle">Discount Ledger</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_11" id="ledger_11">
                            <option  value="4013000500010000">4013000500010000: <?=find_a_field('accounts_ledger','ledger_name','ledger_id="4013000500010000"'); ?></option>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_11"  value="<?='Cash discount offer on Style Forever products, '.$narration.'';?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_11" readonly value="<?=substr($cd_data->total_amt,1)?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_12" id="ledger_12">
                            <option  value="4013000500020000">4013000500020000 : <?=find_a_field('accounts_ledger','ledger_name','ledger_id="4013000500020000"'); ?></option>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_12"  value="<?='Cash discount offer on Raymond products, '.$narration.'';?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text"  name="dr_amount_12" readonly value="<?=substr($cd_data_RMND->total_amt,1)?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"></td>
                </tr>
                
                <tr>
                    <th style="text-align: center; vertical-align: middle">Customer Ledger</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_13" id="ledger_13">
                        <option  value="<?=$dealer_master->account_code;?>"><?=$dealer_master->account_code; ?>-<?=$customer_name=find_a_field('accounts_ledger','ledger_name','ledger_id='.$dealer_master->account_code.''); ?></option>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_13"  value="<?='Received cash discount, '.$narration.'';?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                    <td style="text-align: right; vertical-align: middle"></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text"  name="cr_amount_13" readonly value="<?=substr($cash_discount,1)?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>

            </tbody>
        </table>


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
                    <button style="float: left; font-size: 12px; margin-left: 1%" type="submit" name="cancel" id="cancel" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Cancel</button>
                    <button style="float: right; font-size: 12px; margin-right: 1%" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Delivery Confirm</button>
                </p>
            <? }} else echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>The order has been delivered!!</i></h6>'; ;?>
    </form>
<?php } ?>


<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 30px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 30px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select  class="form-control" style="width: 200px;font-size:11px; height: 30px" required="required"  name="depot_id" id="depot_id">
                        <option selected></option>
                        <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
							 upp.user_id=".$_SESSION[userid]." and upp.status>0
							  order by w.warehouse_id";
                        advance_foreign_relation($sql_plant,$_POST[depot_id]);?>
                    </select></td>
                <td style="padding: 10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Delivered Challan</button></td>
            </tr></table>
    </form>

<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
