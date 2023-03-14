<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$page = 'sales_regular_invoice.php';
$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$unique_detail='id';
$title='Sales Invoice';

if(isset($_POST['select_dealer_do'])) {
    $_SESSION['select_dealer_do_regular']=$_POST['dealer_code'];
}
$select_dealer_do_regular = @$_SESSION['select_dealer_do_regular'];
if(isset($_POST['dealer_cancel'])) {
	unset($_SESSION['select_dealer_do_regular']);
	unset($select_dealer_do_regular);
}
if(prevent_multi_submit()){
    if(isset($_POST['new']))
    {
        $crud   = new crud($table_master);
        $_SESSION['depot_do_<?=$unique_master?>']=$_POST['depot_id'];
        $_SESSION['dlrid']=$_POST['dealer_code'];
        $_SESSION['DEPID']=$_POST['depot_id'];
		$_POST['do_section']="regular_invoice";
        $_POST['entry_at']=date('Y-m-d H:s:i');
        $_POST['entry_by']=$_SESSION['userid'];
        if($_POST['flag']<1){
            $_POST['do_no'] = find_a_field($table_master,'max(do_no)','1')+1;
            $crud->insert();
            $_SESSION['unique_master_for_regular']=$_POST[$unique_master];

            $type=1;
            $msg='Work Order Initialized. (Demand Order No-'.$_SESSION['unique_master_for_regular'].')';
        }else {
            $crud->update($unique_master);
            $type=1;
            $msg='Successfully Updated.';
        } }

        if(isset($_POST['add'])&&($_POST[$unique_master]>0)){
            if($_POST['dist_unit']>$_POST['inStock_pcs']){
                echo "<script>alert ('oops!! exceed stock limit!! Thanks')</script>";
                unset($_POST);
                  } else {
            $table		=$table_detail;
            $crud      	=new crud($table);
            $_POST['do_section']="regular_invoice";
            $_POST['total_unit'] = ($_POST['pkt_unit'] * $_POST['pkt_size']) + $_POST['dist_unit'];
            $_POST['total_amt'] = ($_POST['total_unit'] * $_POST['unit_price']);
            $_POST['revenue_amount'] = ((($_POST['total_unit'] * $_POST['unit_price'])/100)*$_POST['revenue_persentage']);
            $_POST['t_price'] = find_a_field('item_info','t_price','item_id ='.$_POST['item_id']);
            $_POST['entry_by']=$_SESSION['userid'];
            $_POST['gift_on_orders'] = $crud->insert();
            $sql_gift_on_order=mysqli_query($conn, 'SELECT id from sale_do_details WHERE do_no='.$_SESSION['unique_master_for_regular'].' and item_id='.$_POST['item_id'].' and dealer_code='.$_POST['dealer_code'].' and total_unit='.$_POST['total_unit'].' and total_amt='. $_POST['total_amt'].' and entry_by='.$_POST['entry_by'].' order by id desc limit 1');
            $gor=mysqli_fetch_object($sql_gift_on_order);

            $_POST['gift_on_order'] = $gor->id;
            $do_date = date('Y-m-d');
            $_POST['gift_on_item'] = $_POST['item_id'];
            $dealer = find_all_field('dealer_info','','dealer_code='.$_POST['dealer_code']);
            if($_POST['group_for']!='M'){
                $sss = "select * from sale_gift_offer where item_id='".$_POST['item_id']."' and start_date<='".$do_date."' and end_date>='".$do_date."' and dealer_type='".$dealer->dealer_type."'";
            }else
                $sss = "select * from sale_gift_offer where item_id='".$_POST['item_id']."' and start_date<='".$do_date."' and end_date>='".$do_date."' and (group_for like '%A%' or group_for like '%B%' or group_for like '%C%' or group_for like '%D%') and dealer_type='".$dealer->dealer_type."'";
            $qqq = mysqli_query($conn, $sss);
            $total_unit = $_POST['total_unit'];

            while($gift=mysqli_fetch_object($qqq)){
                if($gift->item_qty>0){
                    $_POST['gift_id'] = $gift->id;
                    $gift_item = find_all_field('item_info','','item_id="'.$gift->gift_id.'"');
                    $_POST['item_id'] = $gift->gift_id;
                    if($gift->gift_id== 1096000100010312){
                        $_POST['unit_price'] = (-1)*($gift->gift_qty);
                        $_POST['total_amt']  = (((int)($total_unit/$gift->item_qty))*($_POST['unit_price']));
                        $_POST['total_unit'] = (((int)($total_unit/$gift->item_qty)));
                        $_POST['dist_unit'] = $_POST['total_unit'];
                        $_POST['pkt_unit']  = '0.00';
                        $_POST['d_price']  = '0.00';
                        $_POST['pkt_size']  = '1.00';
                        $_POST['t_price']   = '-1.00';
                        $_POST['gift_type']=$gift->gift_type;
                        $crud->insert();
                        unset($_POST);
                    }else{
                        $in_stock_pcs = find_a_field('journal_item','sum(item_in)-sum(item_ex)','item_id="'.$gift_item->item_id.'" and warehouse_id="'.$_POST['depot_id'].'" ');
                        $_POST['pkt_size'] = $gift_item->pack_size;
                        $_POST['unit_price'] = '0.00';
                        $_POST['total_amt'] = '0.00';
                        $_POST['gift_type']=$gift->gift_type;
                        $_POST['total_unit'] = (((int)($total_unit/$gift->item_qty))*($gift->gift_qty));
                        if($gift_item->pack_size!=1){
                            $_POST['dist_unit'] = ($_POST['total_unit']%$gift_item->pack_size);
                        }else{
                            $_POST['dist_unit'] = $_POST['total_unit'];
                        }
                        if($gift_item->pack_size!=1){
                            $_POST['pkt_unit'] = (int)($_POST['total_unit']/$gift_item->pack_size);
                        }else{
                            $_POST['pkt_unit'] = 0;
                        }
                        $_POST['t_price'] = '0.00';
                        $inStockCtn = ($in_stock_pcs-$ordered_qty)/$gift_item->pack_size; $inStockCtn=(int)$inStockCtn;
                        $_POST['inStock_ctn']=$inStockCtn;
                        $_POST['inStock_pcs']=($in_stock_pcs-$ordered_qty)-($inStockCtn*$gift_item->pack_size);
                        $_POST['inStock_Totalpcs']=$in_stock_pcs-$ordered_qty;
                        if($_POST['unit_price']==0&&$_POST['total_unit']==0){
                            echo '';
                        }else
                            $crud->insert();
                        unset($_POST);
                    } // gift id

                        }
            }
 } }

}

$unique_master_for_regular = @$_SESSION['unique_master_for_regular'];

if (isset($_REQUEST['id'])) {
$edit_value=find_all_field(''.$table_detail.'','','id='.$_REQUEST['id'].'');
}
$edit_value_item_id = @$edit_value->item_id;
if(isset($_POST['cancel']))
{
    $crud   = new crud($table_master);
    $condition=$unique_master."=".$unique_master_for_regular;
    $crud->delete($condition);
    $crud   = new crud($table_detail);
    $crud->delete_all($condition);

    unset($$unique_master);
    unset($_POST[$unique_master]);
    unset($unique_master_for_regular);
    unset($select_dealer_do_regular);
    unset($_SESSION['select_dealer_do_regular']);
    unset($_SESSION['unique_master_for_regular']);
    $type=1;
    $msg='Successfully Deleted.';
}

if(isset($_POST['confirm'])){
    $commission_amount=$_POST['commission_amount'];
    unset($_POST);
    $_POST['commission_amount']=$commission_amount;
    $_POST[$unique_master]=$unique_master_for_regular;
    $_POST['entry_at']=date('Y-m-d H:i:s');
    $_POST['status']='PROCESSING';

    $crud   = new crud($table_master);
    $crud->update($unique_master);
    $crud   = new crud($table_detail);
    $crud->update($unique_master);
    unset($$unique_master);
    unset($_POST[$unique_master]);
    unset($select_dealer_do_regular);
    unset($_SESSION['select_dealer_do_regular']);
    unset($_SESSION['unique_master_for_regular']);
    $type=1;
    $msg='Successfully Instructed to Depot.';}

// fatch unique master data
if($unique_master_for_regular>0)
{   $condition=$unique_master."=".$unique_master_for_regular;
    $data=db_fetch_object($table_master,$condition);
    while (list($key, $value)=@each($data))
    { $$key=$value;}}

$depot_id = @$depot_id;

$select_dealer_do_regular = @$_SESSION['select_dealer_do_regular'];
$dealer = find_all_field('dealer_info','','dealer_code='.$select_dealer_do_regular);
$_GET_item_id = @$_GET['item_id'];
$item_all= find_all_field('item_info','','item_id="'.$_GET_item_id.'"');
$GET_id = @$_REQUEST['id'];
if($GET_id>0){
  $present_stock_sql=mysqli_query($conn, "Select i.item_id,i.finish_goods_code,i.item_name,i.unit_name,i.pack_size,
  REPLACE(FORMAT(SUM(j.item_in-j.item_ex), 0), ',', '') as Available_stock_balance
  from
  item_info i,
  journal_item j,
  lc_lc_received_batch_split bsp
  where
  j.item_id=i.item_id and
  j.warehouse_id='".$depot_id."' and
  bsp.batch=j.batch and 
  bsp.status='PROCESSING' and 
  j.item_id='".$edit_value_item_id."'
  group by j.item_id order by i.item_id");
  $ps_data=mysqli_fetch_object($present_stock_sql);
  $in_stock_pcs = $ps_data->Available_stock_balance;
  $ordered_qty = find_a_field('sale_do_details','sum(total_unit)','item_id="'.$_REQUEST['item_id'].'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","PROCESSING","MANUAL")');
} else {
    $present_stock_sql=mysqli_query($conn, "Select i.item_id,i.finish_goods_code,i.item_name,i.unit_name,i.pack_size,
    REPLACE(FORMAT(SUM(j.item_in-j.item_ex), 0), ',', '') as Available_stock_balance
    from
    item_info i,
    journal_item j,
    lc_lc_received_batch_split bsp
    
    where
    
    j.item_id=i.item_id and
    j.warehouse_id='".$depot_id."' and
    bsp.batch=j.batch and 
    bsp.status='PROCESSING' and 
    j.item_id='".$_GET_item_id."'
    group by j.item_id order by i.item_id");
    $ps_data=mysqli_fetch_object($present_stock_sql);
  $inventory_stock = @$ps_data->Available_stock_balance;
  $ordered_qty = find_a_field('sale_do_details','sum(total_unit)','item_id="'.$_GET_item_id.'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","PROCESSING","MANUAL")');
  $in_stock_pcs= $inventory_stock-$ordered_qty;
}
$del_qty = find_a_field('sale_do_chalan','sum(total_unit)','item_id="'.$_GET_item_id.'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","CHECKED","PROCESSING")');
$res='select a.id,a.gift_on_order as gift_id,concat(b.item_id," # ",b.finish_goods_code," # ",b.item_name) as item_description,b.unit_name as unit,b.pack_size,round(a.d_price, 2) as d_price,round(a.unit_price, 2) as unit_price,a.total_unit, a.total_amt
from
sale_do_details a,item_info b where b.item_id=a.item_id and a.do_no='.$unique_master_for_regular.' order by a.id';
$query=mysqli_query($conn, $res);
while($data=@mysqli_fetch_object($query)){
  if(isset($_POST['deletedata'.$data->id]))
  {   $resd1 = mysqli_query($conn, ("DELETE FROM ".$table_detail." WHERE id=".$data->id));
      $resd2 = mysqli_query($conn, ("DELETE FROM ".$table_detail." WHERE gift_on_order=".$data->id));
      unset($_POST);}
  if(isset($_POST['editdata'.$data->id]))
  {   mysqli_query($conn, ("UPDATE ".$table_detail." SET item_id='".$_POST['item_id']."', unit_price='".$_POST['unit_price']."',dist_unit='".$_POST['dist_unit']."',total_amt='".$_POST['total_amt']."' WHERE id=".$data->id));
      unset($_POST);
    }}

$COUNT_details_data=find_a_field(''.$table_detail.'','Count(id)',''.$unique_master.'='.$unique_master_for_regular.'');?>
<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
function DoNavPOPUP(lk){myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
function reload(form){
var val=form.item_id.options[form.item_id.options.selectedIndex].value;
self.location='<?=$page;?>?<?php if($GET_id>0){?>id=<?=$GET_id?>&<?php } ?>item_id=' + val ;}</script>
<style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #f5f5f5;}
        td{}
    </style>
    <style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content_nva_sm.php'; ?>
    <form action="<?=$page?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 60%;; font-size: 11px">
            <tr><td>Dealer / Customer / Outlet</td>
                <td style="width:10px; text-align:center;vertical-align: middle"> -</td>
                <td style="vertical-align: middle"><select class="select2_single form-control" style="width:300px; font-size: 11px" tabindex="-1" required="required"  name="dealer_code" id="dealer_code">
                        <option></option>
                        <?php if(isset($select_dealer_do_regular)>0): ?>
                        <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $select_dealer_do_regular, 'dealer_code='.$_SESSION['select_dealer_do_regular']); ?>
                        <?php else: ?>
                        <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $select_dealer_do_regular, 'canceled="YES"'); ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td style="padding:10px;vertical-align: middle">
                    <?php if(isset($select_dealer_do_regular)>0){
                        $status = find_a_field(''.$table_master.'','status','do_no='.$select_dealer_do_regular)
                        ?>
                        <button type="submit" style="font-size: 11px; height: 30px" name="dealer_cancel" id="dealer_cancel"  class="btn btn-danger">Cancel the dealer</button>
                        <?php if($status!=='COMPLETED'){ ?>
                        <a align="center" href="do_challan_view.php?v_no=<?=$select_dealer_do_regular;?>" target="_new"><img src="../../assets/images/print.png" width="25" height="25" /></a>
                            <?php } else { ?>
                            <a target="_blank" href="chalan_view.php?v_no=<?=$select_dealer_do_regular;?>"><img src="../../assets/images/print.png" width="25" height="25" /></a>
                        <?php } ?>
                    <?php } else { ?>
                        <button type="submit" style="font-size: 11px;" name="select_dealer_do"  class="btn btn-primary">Select and Proceed to Next</button>
                    <?php } ?>
                </td>
            </tr></table>
    </form>

<?php if(isset($select_dealer_do_regular)>0):
    $date = date('Y-m-d');
    $do_date = @$do_date;
    $do_type = @$do_type;
    $remarks = @$remarks;
?>
 <div class="col-md-12 col-xs-12">
  <div class="x_panel">
   <div class="x_content">
    <form action="<?=$page?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <? require_once 'support_html.php';?>
        <input type="hidden" name="dealer_code" id="dealer_code" value="<?=$select_dealer_do_regular;?>">
        <input type="hidden" name="dealer_type" id="dealer_type" value="<?=$dealer->dealer_type;?>">
        <input type="hidden" name="town" id="town" value="<?=$dealer->town_code;?>">
        <input type="hidden" name="area_code" id="area_code" value="<?=$dealer->area_code;?>">
        <input type="hidden" name="territory" id="territory" value="<?=$dealer->territory;?>">
        <input type="hidden" name="region" id="region" value="<?=$dealer->region;?>">
                    <table style="width:100%; font-size: 11px">
                        <tr>
                            <th style="width: 10%">DO No</th><th style="text-align: center; width: 2%">:</th>
                            <td style="width: 21.5%"><input type="text" style="width: 90%;" name="do_no" readonly value="<? if($unique_master_for_regular>0) echo $unique_master_for_regular; else echo (find_a_field($table_master,'max('.$unique_master.')','1')+1);?>" class="form-control col-md-7 col-xs-12"></td>

                            <th style="width: 10%">DO Date</th><th style="text-align: center; width: 2%">:</th>
                            <td style="width: 21.5%"><input type="date" style="width: 90%; font-size: 11px" name="do_date" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>"  max="<?=date('Y-m-d');?>" value="<?=($do_date!='')? $do_date : date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"></td>

                            <th style="width: 10%">Do Type</th><th style="text-align: center; width: 2%">:</th>
                            <td style="width: 21%">
                                <select style="width:90%; font-size: 11px" name="do_type" id="do_type"  required class="form-control col-md-7 col-xs-12">
                                    <option value="sales">Sales</option>
                                    <?php if(isset($unique_master_for_regular)>0): ?>
                                        <?=foreign_relation('sales_type', 'do_type', 'type_name', $do_type, 'do_type="'.$do_type.'"'); ?>
                                    <?php else: ?>
                                        <?php foreign_relation('sales_type', 'do_type', 'do_type',$do_type, '1'); ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>
                        <tr><td style="height: 5px"></td></tr>
                        <tr><input type="hidden" style="width: 80%; background-color:  #EBEBE4 !important;  border: 1px solid darkgray" name="exim_status"  value="<?=$dealer->dealer_category;?>">
                            <th>Available Amt</th><th style="text-align: center; width: 2%">:</th>
                            <td>
                                <input type="text" style="width: 90%;" name="received_amt" readonly value="<?=$av_amt=(find_a_field_sql('select sum(cr_amt-dr_amt) from journal where ledger_id='.$dealer->account_code));?>" class="form-control col-md-7 col-xs-12"></td>
                            <th>Credit Limit</th><th style="text-align: center; width: 2%">:</th>
                            <td>
                                <input type="text" style="width: 90%;" readonly value="<?=$dealer->credit_limit;?>" class="form-control col-md-7 col-xs-12">
                            </td>
                            <th>Commission </th><th style="text-align: center; width: 2%">:</th>
                            <td>
                                <input style="width: 90%;" name="commission" readonly type="text" value="<?=$dealer->commission;?>" class="form-control col-md-7 col-xs-12">
                            </td>
                        </tr>
                        <tr><td style="height: 5px"></td></tr>
                        <tr>
                            <th style="">Delivery Address</th><th style="text-align: center; width: 2%">:</th>
                            <td>
                                <input type="text" name="delivery_address" style="width: 90%; text-align:left;" readonly value="<?=$dealer->address_e;?>" class="form-control col-md-7 col-xs-12" /></td>
                            <th>Remarks</th><th style="text-align: center; width: 2%">:</th>
                            <td>
                                <input type="text" name="remarks" style="width: 90%" value="<?=$remarks;?>" class="form-control col-md-7 col-xs-12" /></td>
                            <th>Warehouse</th><th style="text-align: center; width: 2%">:</th>
                            <td>
                                <select style="width:90%;  font-size: 11px" tabindex="-1" required="required"  name="depot_id" id="depot_id" class="form-control col-md-7 col-xs-12">
                                    <option></option>
                                    <?php if(isset($_SESSION['unique_master_for_regular'])>0): ?>
                                        <option value="<?=$depot_id?>" selected><?=find_a_field('warehouse','warehouse_name','warehouse_id='.$depot_id)?></option>
                                    <?php else: ?>
                                        <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$depot_id);?>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>
                        </table>
                    <p align="center" style="margin-top:10px">
                    <?
                    if($unique_master_for_regular>0) {?>
                        <button type="submit" name="new" class="btn btn-primary" style="font-size: 12px">Modify Invoice Info</button>
                        <input name="flag" id="flag" type="hidden" value="1" />
                    <? }else{?>
                        <button type="submit" name="new" class="btn btn-primary" style="font-size: 12px">Initiate Invoice</button>
                        <input name="flag" id="flag" type="hidden" value="0" />
                    <? } ?></p></form>

                </div></div></div>

<?php if(isset($_SESSION['unique_master_for_regular'])>0):

    ?>
<form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
 <? require_once 'support_html.php';?>
     <input type="hidden" name="do_no" id="do_no" value="<?=$_SESSION['unique_master_for_regular'];?>">
      <input type="hidden" name="do_date" id="do_date" value="<?=$do_date;?>">
     <input type="hidden" name="dealer_code" id="dealer_code" value="<?=$select_dealer_do_regular;?>">
     <input type="hidden" name="dealer_type" id="dealer_type" value="<?=$dealer->dealer_type;?>">
     <input type="hidden" name="<?=$unique_master;?>" id="<?=$unique_master;?>" value="<?=$_SESSION['unique_master_for_regular'];?>">
     <input type="hidden" name="town" id="town" value="<?=$dealer->town_code;?>">
     <input type="hidden" name="area_code" id="area_code" value="<?=$dealer->area_code;?>">
     <input type="hidden" name="territory" id="territory" value="<?=$dealer->territory;?>">
     <input type="hidden" name="region" id="region" value="<?=$dealer->region;?>">
     <input  name="t_price" type="hidden" id="t_price" value="<?=$item_all->t_price?>" readonly="readonly"/>
      <input  name="cogs_price" type="hidden" id="cogs_price" value="<?=$item_all->production_cost?>" readonly="readonly"/>
      <input  name="d_price" type="hidden" id="d_price" value="<?=$item_all->d_price?>" readonly="readonly"/>
      <input  name="m_price" type="hidden" id="m_price" value="<?=$item_all->m_price?>" readonly="readonly"/>
      <input style="width:155px;"  name="do_type" type="hidden" id="do_type" value="<?=$do_type;?>" readonly/>
      <input  name="section_id" type="hidden" id="section_id" value="<?=$_SESSION['sectionid']?>">
      <input style="width:155px;"  name="company_id" type="hidden" id="company_id" value="<?=$_SESSION['companyid']?>"/>
      <input style="width:155px;"  name="depot_id" type="hidden" id="depot_id" value="<?=$depot_id?>"/>
      <input style="width:155px;"  name="revenue_persentage" type="hidden" id="revenue_persentage" value="<?=$item_all->revenue_persentage?>"/>
 <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
        <thead>
        <tr style="background-color: bisque">
            <th style="text-align: center">Finish Goods Code</th>
            <th style="text-align: center">In Stock</th>
            <th style="text-align: center">D Price</th>
            <th style="text-align: center">Unit Price</th>
            <th style="text-align: center">Invoice Qty</th>
            <th style="text-align: center">Unit Amount</th>
            <th style="text-align: center">Action</th>
        </tr>
        </thead>
        <tbody>
                    <tr>
                    <td style="vertical-align: middle">
                    <select class="select2_single form-control"  tabindex="-1" required="required" name="item_id" id="item_id" onchange="javascript:reload(this.form)">
                    <option></option>
                    <? advance_foreign_relation(find_all_item($product_nature="'Salable','Both'"),($_GET['item_id']>0)? $_GET['item_id'] : $edit_value->item_id);?>
                    </select>
                    </td>
                     <td style="width:10%; vertical-align: middle" align="center">
                     <input type="number" id="inStock_pcs" style="width:99%; height:37px; font-size:11px;text-align:center"  required="required" value="<?=$in_stock_pcs?>" name="inStock_pcs"  class="form-control col-md-7 col-xs-12" readonly class="total_amt" ></td>
                     <td style="vertical-align: middle"><input class="form-control col-md-7 col-xs-12" name="d_price" type="number" style="width:99%; height:37px; font-size:11px;text-align:center" readonly id="d_price" value="<?=$item_all->d_price?>" readonly="readonly"/></td>
                     <td style="width:10%; vertical-align: middle" align="center">
                     <input type="number" id="unit_price" style="width:99%; height:37px; font-size:11px;text-align:center" min="1" step="any" required="required" value="<?=($_REQUEST['item_id']>0)? $item_all->d_price : $edit_value->unit_price?>" readonly name="unit_price"  class="form-control col-md-7 col-xs-12" autocomplete="off" class="unit_price" ></td>
                     <td style="width:10%; vertical-align: middle" align="center">
                        <input placeholder="Crt"  name="pkt_unit" type="hidden" id="pkt_unit" style="width:45%; height:37px" onkeyup="avail_amount(),count()" required="required" class="form-control col-md-7 col-xs-12"  tabindex="4"/ -->
                     <input  class="form-control col-md-7 col-xs-12" name="dist_unit" type="number" onkeyup="doAlert(this.form);" min="1" id="dist_unit" style="width:99%; height:37px; text-align:center; font-size:11px" value="<?=$edit_value->dist_unit?>" required="required" class="dist_unit" />
                     <input name="pkt_size" type="hidden" class="input3" id="pkt_size"  style="width:55px;"  value="<?=$item_all->pack_size?>" readonly/></td>
                     <td align="center" style="width:10%; vertical-align: middle">
                     <input type="number" id="total_amt" style="width:99%; height:37px; font-size:11px;text-align:center" required="required" min="1" step="any"  name="total_amt" value="<?=$edit_value->total_amt?>" class="form-control col-md-7 col-xs-12" readonly autocomplete="off" class="total_amt" ></td>
                     <td align="center" style="width:5%; vertical-align: middle">
                       <?php if (isset($_REQUEST['id'])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_REQUEST['id'];?>" id="editdata<?=$_REQUEST['id'];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                       <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>
                     </tr>
                     </tbody>
                     <script>
                         $(function(){
                             $('#unit_price,#dist_unit').keyup(function(){
                                 var unit_price = parseFloat($('#unit_price').val()) || 0;
                                 var dist_unit = parseFloat($('#dist_unit').val()) || 0;
                                 $('#total_amt').val((unit_price * dist_unit));
                             });
                         });
                     </script>
                     <SCRIPT language=JavaScript>
                         function doAlert(form)
                         {   var val=form.dist_unit.value;
                             var val2=form.inStock_pcs.value;
                             if (Number(val)>Number(val2)){
                                 alert('oops!! exceed stock limit!! Thanks');
                                 form.dist_unit.value='';}
                             form.dist_unit.focus();
                         }</script>
                     </table></form>
<?=added_data_delete_edit_invoice($res,$unique,$unique_GET,$COUNT_details_data,$page,8,8,$commission);
endif;endif;?>
<?=$html->footer_content();mysqli_close($conn);?>