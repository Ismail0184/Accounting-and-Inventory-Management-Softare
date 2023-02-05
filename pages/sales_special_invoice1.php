<?php require_once 'support_file.php'; ?>
<? //=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$page = 'sales_special_invoice1.php';
$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$unique_detail='id';


if(isset($_POST['select_dealer_do'])) {
    $_SESSION[select_dealer_do_SP]=$_POST[dealer_code];
}
if(isset($_POST['dealer_cancel'])) {
	unset($_SESSION[select_dealer_do_SP]);
}
if(prevent_multi_submit()){
    if(isset($_POST['new']))
    {
        $crud   = new crud($table_master);
        $_SESSION['depot_do_<?=$unique_master?>']=$_POST['depot_id'];
        $_SESSION['dlrid']=$_POST['dealer_code'];
        $_SESSION['DEPID']=$_POST['depot_id'];
		$_POST['do_section']="Special_invoice";
        $_POST['entry_at']=date('Y-m-d H:s:i');
        $_POST['entry_by']=$_SESSION['userid'];
        if($_POST['flag']<1){
            $_POST['do_no'] = find_a_field($table_master,'max(do_no)','1')+1;
            $crud->insert();
            $_SESSION['unique_master_for_SP']=$_POST[$unique_master];
            unset($$unique);
            $type=1;
            $msg='Work Order Initialized. (Demand Order No-'.$_SESSION['unique_master_for_SP'].')';
        }else {
            $crud->update($unique_master);
            $type=1;
            $msg='Successfully Updated.';
        } }

if(isset($_POST['add'])&&($_POST[$unique_master]>0)){
  if($_POST[dist_unit]>$_POST[inStock_pcs]){
echo "<script>alert ('oops!! exceed stock limit!! Thanks')</script>";
unset($_POST);
  } else {
$_POST['depot_id']=$_SESSION['depot_do_<?=$unique_master?>'];
$table		=$table_detail;
$crud      	=new crud($table);
$_POST['total_unit'] = ($_POST['pkt_unit'] * $_POST['pkt_size']) + $_POST['dist_unit'];
$_POST['total_amt'] = ($_POST['total_unit'] * $_POST['unit_price']);
$_POST['do_section']="Special_invoice";
$_SESSION[select_dealer_do_SP]=$_POST[dealer_code];
$crud->insert();

}}}else{
    $type=0;
    $msg='Data Re-Submit Error!';}
if($del>0){
    $main_del = find_a_field($table_detail,'gift_on_order','id = '.$del);
    $crud   = new crud($table_detail);
    if($del>0)
    {   $condition=$unique_detail."=".$del;
        $crud->delete_all($condition);
        $condition="gift_on_order=".$del;
        $crud->delete_all($condition);
        if($main_del>0){
            $condition=$unique_detail."=".$main_del;
            $crud->delete_all($condition);
            $condition="gift_on_order=".$main_del;
            $crud->delete_all($condition);}}
    $type=1;
    header("Location: sales_special_invoice.php?do_no=$_GET[do_no]&dealer_code=$_GET[dealer_code]");}

if (isset($_GET[id])) {
$edit_value=find_all_field(''.$table_detail.'','','id='.$_GET[id].'');
}

if(isset($_POST['cancel']))
{
    $crud   = new crud($table_master);
    $condition=$unique_master."=".$_SESSION['unique_master_for_SP'];
    $crud->delete($condition);
    $crud   = new crud($table_detail);
    $crud->delete_all($condition);
    $crud   = new crud($table_chalan);
    $crud->delete_all($condition);
    unset($$unique_master);
    unset($_POST[$unique_master]);
    unset($_SESSION['select_dealer_do_SP']);
    unset($_SESSION['unique_master_for_SP']);
    $type=1;
    $msg='Successfully Deleted.';
}

if(isset($_POST['confirm'])){	  unset($_POST);
    $_POST[$unique_master]=$_SESSION['unique_master_for_SP'];
    $_POST['entry_at']=date('Y-m-d H:i:s');
    $_POST['status']='UNCHECKED';
    $crud   = new crud($table_master);
    $crud->update($unique_master);
    $crud   = new crud($table_detail);
    $crud->update($unique_master);
    unset($$unique_master);
    unset($_POST[$unique_master]);
    unset($_SESSION['select_dealer_do_SP']);
    unset($_SESSION['unique_master_for_SP']);
    $type=1;
    $msg='Successfully Instructed to Depot.';}



// fatch unique master data
if($_SESSION['unique_master_for_SP']>0)
{   $condition=$unique_master."=".$_SESSION['unique_master_for_SP'];
    $data=db_fetch_object($table_master,$condition);
    while (list($key, $value)=@each($data))
    { $$key=$value;}}

$dealer = find_all_field('dealer_info','','dealer_code='.$_SESSION[select_dealer_do_SP]);
$item_all= find_all_field('item_info','','item_id="'.$_GET[item_id].'"');
if($_GET[id]>0){
  $in_stock_pcs = find_a_field('journal_item','sum(item_in)-sum(item_ex)','item_id="'.$edit_value->item_id.'" and warehouse_id="'.$depot_id.'" ');					$ordered_qty = find_a_field('sale_do_details','sum(total_unit)','item_id="'.$_GET[item_id].'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","PROCESSING","MANUAL")');
} else {
  $inventory_stock = find_a_field('journal_item','sum(item_in)-sum(item_ex)','item_id="'.$_GET[item_id].'" and warehouse_id="'.$depot_id.'" ');
  $ordered_qty = find_a_field('sale_do_details','sum(total_unit)','item_id="'.$_GET[item_id].'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","PROCESSING","MANUAL")');
  $in_stock_pcs= $inventory_stock-$ordered_qty;
}
$del_qty = find_a_field('sale_do_chalan','sum(total_unit)','item_id="'.$_GET[item_id].'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","CHECKED","PROCESSING")');


$res='select a.id,b.finish_goods_code as code,b.item_name,b.unit_name as unit,b.pack_size,round(a.d_price, 2) as d_price,round(a.unit_price, 2) as unit_price,a.total_unit, a.total_amt
from
sale_do_details a,item_info b where b.item_id=a.item_id and a.do_no='.$_SESSION['unique_master_for_SP'].' order by a.id';
$query=mysqli_query($conn, $res);
while($data=mysqli_fetch_object($query)){
  if(isset($_POST['deletedata'.$data->id]))
  {  mysqli_query($conn, ("DELETE FROM ".$table_detail." WHERE id=".$data->id));
      $_SESSION['initiate_credit_note']=$_SESSION['initiate_credit_note'];
      unset($_POST);}
  if(isset($_POST['editdata'.$data->id]))
  {  mysqli_query($conn, ("UPDATE ".$table_detail." SET item_id='".$_POST[item_id]."', unit_price='".$_POST[unit_price]."',dist_unit='".$_POST[dist_unit]."',total_amt='".$_POST[total_amt]."' WHERE id=".$data->id));
      unset($_POST);
    }}



$COUNT_details_data=find_a_field(''.$table_detail.'','Count(id)',''.$unique_master.'='.$_SESSION['unique_master_for_SP'].'');?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
function DoNavPOPUP(lk){myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
function reload(form){
var val=form.item_id.options[form.item_id.options.selectedIndex].value;
self.location='<?=$page;?>?<?php if($_GET[id]>0){?>id=<?=$_GET[id]?>&<?php } ?>item_id=' + val ;}</script>
    <style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content_entry_mod.php'; ?>

    <form action="<?=$page?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 60%;; font-size: 11px">
            <tr><td>Dealer / Customer / Outlet</td>
                <td style="width:10px; text-align:center;vertical-align: middle"> -</td>
                <td style="vertical-align: middle"><select class="select2_single form-control" style="width:300px; font-size: 11px" tabindex="-1" required="required"  name="dealer_code" id="dealer_code">
                        <option></option>
                        <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $_SESSION[select_dealer_do_SP], 'canceled="YES"'); ?>
                    </select></td>
                <td style="padding:10px;vertical-align: middle">
                    <?php if(isset($_SESSION[select_dealer_do_SP])>0){ ?>
                        <button type="submit" style="font-size: 11px; height: 30px" name="dealer_cancel" id="dealer_cancel"  class="btn btn-danger">Cancel the dealer</button>
                        <?php if($status!=='COMPLETED'){ ?>
                        <a align="center" href="do_challan_view.php?v_no=<?=$_SESSION[select_dealer_do_SP];?>" target="_new"><img src="../../warehouse_mod/images/print.png" width="25" height="25" /></a>
                            <?php } else { ?>
                            <a target="_blank" href="chalan_view.php?v_no=<?=$_SESSION[select_dealer_do_SP];?>"><img src="../../warehouse_mod/images/print.png" width="25" height="25" /></a>
                        <?php } ?>
                    <?php } else { ?>
                        <button type="submit" style="font-size: 11px;" name="select_dealer_do"  class="btn btn-primary">Create Special Invoice</button>
                    <?php } ?>
                </td>
            </tr></table>
    </form>

<?php if(isset($_SESSION[select_dealer_do_SP])>0): ?>
 <div class="col-md-12 col-xs-12">
  <div class="x_panel">
   <div class="x_content">
    <form action="<?=$page?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <? require_once 'support_html.php';?>
        <input type="hidden" name="dealer_code" id="dealer_code" value="<?=$_SESSION[select_dealer_do_SP];?>">
        <input type="hidden" name="dealer_type" id="dealer_type" value="<?=$dealer->dealer_type;?>">
        <input type="hidden" name="town" id="town" value="<?=$dealer->town_code;?>">
        <input type="hidden" name="area_code" id="area_code" value="<?=$dealer->area_code;?>">
        <input type="hidden" name="territory" id="territory" value="<?=$dealer->territory;?>">
        <input type="hidden" name="region" id="region" value="<?=$dealer->region;?>">
                    <table style="width:100%; font-size: 11px">
                        <tr>
                            <th style="">DO No</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%; background-color:  #EBEBE4 !important;  border: 1px solid darkgray" name="do_no" readonly value="<? if($_SESSION['unique_master_for_SP']>0) echo $_SESSION['unique_master_for_SP']; else echo (find_a_field($table_master,'max('.$unique_master.')','1')+1);?>"></td>
                            <th style="width: 15%">DO Date</th><th style="text-align: center; width: 2%">:</th><td><input type="date" style="width: 80%" name="do_date" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>"  max="<?=date('Y-m-d');?>" value="<?=($do_date!='')? $do_date : date('Y-m-d');?>"></td>
                            <th style="width: 15%">Do Type</th><th style="text-align: center; width: 2%">:</th>
                            <td><select style="width:80%; height:25px; font-size: 11px" name="do_type" id="do_type"  required>
                                        <option value=""></option>
                                        <option value="sales" <?php if($do_type=='sales') { echo 'selected';} else {};?> selected>Sales</option>
                                        <option value="sample" <?php if($do_type=='sample') { echo 'selected';} else {};?>>Sample</option>
                                        <option value="display" <?php if($do_type=='display') { echo 'selected';} else {};?>>Product Display</option>
                                        <option value="gift" <?php if($do_type=='gift') { echo 'selected';} else {};?>>Gift</option>
                                        <option value="free" <?php if($do_type=='free') { echo 'selected';} else {};?>>Free</option>
                                </select>
                                </td>
                        </tr>
                        <tr><td style="height: 5px"></td></tr>
                        <tr><input type="hidden" style="width: 80%; background-color:  #EBEBE4 !important;  border: 1px solid darkgray" name="exim_status"  value="<?=$dealer->dealer_category;?>">
                            <th style="">Available Amt</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%;background-color:  #EBEBE4 !important;  border: 1px solid darkgray" name="received_amt" readonly value="<?=$av_amt=(find_a_field_sql('select sum(cr_amt-dr_amt) from journal where ledger_id='.$dealer->account_code));?>"></td>
                            <th style="width: 15%">Credit Limit</th><th style="text-align: center; width: 2%">:</th><td><input type="text" style="width: 80%; background-color:  #EBEBE4 !important;  border: 1px solid darkgray" readonly value="<?=$dealer->credit_limit;?>"></td>
                            <th style="width: 15%">Commission </th><th style="text-align: center; width: 2%">:</th><td><input style="width: 80%; background-color:  #EBEBE4 !important;  border: 1px solid darkgray" readonly type="text" value="<?=$dealer->commission;?>"></td>
                        </tr>

                        <tr><td style="height: 5px"></td></tr>
                        <tr>
                            <th style="">Delivery Address</th><th style="text-align: center; width: 2%">:</th><td><input type="text" name="delivery_address" style="width: 80%; text-align:left; background-color:  #EBEBE4 !important;  border: 1px solid darkgray" value="<?=$dealer->address_e;?>" /></td>
                            <th style="width: 15%">Remarks</th><th style="text-align: center; width: 2%">:</th><td><input type="text" name="remarks" style="width: 80%" value="<?=$remarks;?>"></td>
                            <th style="width: 15%">Warehouse</th><th style="text-align: center; width: 2%">:</th><td><select style="width:80%; height:25px; font-size: 11px" tabindex="-1" required="required"  name="depot_id" id="depot_id">
                        <option></option>
                        <?php if(isset($_SESSION['unique_master_for_SP'])>0): ?>
                          <option value="<?=$depot_id?>" selected><?=find_a_field('warehouse','warehouse_name','warehouse_id='.$depot_id)?></option>
                        <?php else: ?>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$depot_id);?>
                      <?php endif; ?>
                    </select></td>
                        </tr>
                        </table>
                    <p align="center" style="margin-top:10px">
                    <?
                    if($_SESSION['unique_master_for_SP']>0) {?>
                        <button type="submit" name="new" class="btn btn-primary" style="font-size: 11px">Modify Invoice Info</button>
                        <input name="flag" id="flag" type="hidden" value="1" />
                    <? }else{?>
                        <button type="submit" name="new" class="btn btn-primary" style="font-size: 11px">Initiate Invoice</button>
                        <input name="flag" id="flag" type="hidden" value="0" />
                    <? } ?></p></form>

                </div></div></div>



<?php if(isset($_SESSION['unique_master_for_SP'])>0): ?>
<form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
 <? require_once 'support_html.php';?>
     <input type="hidden" name="do_no" id="do_no" value="<?=$_SESSION['unique_master_for_SP'];?>">
      <input type="hidden" name="do_date" id="do_date" value="<?=$do_date;?>">
     <input type="hidden" name="dealer_code" id="dealer_code" value="<?=$_SESSION[select_dealer_do_SP];?>">
        <input type="hidden" name="dealer_type" id="dealer_type" value="<?=$dealer->dealer_type;?>">
        <input type="hidden" name="<?=$unique_master;?>" id="<?=$unique_master;?>" value="<?=$_SESSION['unique_master_for_SP'];?>">
        <input type="hidden" name="town" id="town" value="<?=$dealer->town_code;?>">
        <input type="hidden" name="area_code" id="area_code" value="<?=$dealer->area_code;?>">
        <input type="hidden" name="territory" id="territory" value="<?=$dealer->territory;?>">
        <input type="hidden" name="region" id="region" value="<?=$dealer->region;?>">
        <input  name="t_price" type="hidden" id="t_price" value="<?=$item_all->t_price?>" readonly="readonly"/>
      <input  name="cogs_price" type="hidden" id="cogs_price" value="<?=$item_all->production_cost?>" readonly="readonly"/>
      <input  name="d_price" type="hidden" id="d_price" value="<?=$item_all->d_price?>" readonly="readonly"/>
      <input  name="m_price" type="hidden" id="m_price" value="<?=$item_all->m_price?>" readonly="readonly"/>
      <input style="width:155px;"  name="do_type" type="hidden" id="do_type" value="<?=$do_type;?>" readonly/>
      <input  name="section_id" type="hidden" id="section_id" value="<?=$_SESSION[sectionid]?>">
      <input style="width:155px;"  name="company_id" type="hidden" id="company_id" value="<?=$_SESSION[companyid]?>"/>
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
                    <? advance_foreign_relation(find_all_item($product_nature="'Salable','Both'"),($_GET[item_id]>0)? $_GET[item_id] : $edit_value->item_id);?>
                    </select>
                    </td>


                     <td style="width:10%; vertical-align: middle" align="center">
                     <input type="number" id="inStock_pcs" style="width:99%; height:37px; font-size:11px;text-align:center"  required="required" value="<?=$in_stock_pcs?>" name="inStock_pcs"  class="form-control col-md-7 col-xs-12" readonly class="total_amt" ></td>
                     <td style="vertical-align: middle"><input class="form-control col-md-7 col-xs-12" name="d_price" type="number" style="width:99%; height:37px; font-size:11px;text-align:center" readonly id="d_price" value="<?=$item_all->d_price?>" readonly="readonly"/></td>

                     <td style="width:10%; vertical-align: middle" align="center">
                     <input type="number" id="unit_price" style="width:99%; height:37px; font-size:11px;text-align:center" min=".001" required="required" value="<?=$edit_value->unit_price?>" name="unit_price"  class="form-control col-md-7 col-xs-12" autocomplete="off" class="unit_price" ></td>

                     <td style="width:10%; vertical-align: middle" align="center">
                        <!--input placeholder="Crt"  name="pkt_unit" type="text" id="pkt_unit" style="width:45%; height:37px" onkeyup="avail_amount(),count()" required="required" class="form-control col-md-7 col-xs-12"  tabindex="4"/ -->
                     <input  class="form-control col-md-7 col-xs-12" name="dist_unit" type="number" onkeyup="doAlert(this.form);" min="1" id="dist_unit" style="width:99%; height:37px; text-align:center; font-size:11px" value="<?=$edit_value->dist_unit?>" required="required" class="dist_unit" />
                     <input name="pkt_size" type="hidden" class="input3" id="pkt_size"  style="width:55px;"  value="<?=$item_all->pack_size?>" readonly/></td>

                     <td align="center" style="width:10%; vertical-align: middle">
                     <input type="number" id="total_amt" style="width:99%; height:37px; font-size:11px;text-align:center" required="required" min=".001" name="total_amt" value="<?=$edit_value->total_amt?>" class="form-control col-md-7 col-xs-12" readonly autocomplete="off" class="total_amt" ></td>

                     <td align="center" style="width:5%; vertical-align: middle">
                       <?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
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



<?=added_data_delete_edit_invoice($res,$unique,$unique_GET,$COUNT_details_data,$page,8,8);
endif;endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
