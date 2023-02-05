<?php



session_start();
require_once 'base.php';
require_once 'create_id.php';
require_once 'module.php';
require_once 'function_mod.php';
require_once 'curd_function.php';
require_once 'director.class.php';
require_once 'db.php';
require_once 'function_module_create.php';

@ini_set('error_reporting', E_ALL);
@ini_set('display_errors', 'Off');


$str = $_POST['data'];
$data=explode('##',$str);
$item=$data[0];
$finish_goods_code = $item;
$depot_id = $data[1];

$item_all= find_all_field('item_info','','finish_goods_code="'.$finish_goods_code.'"');
$dealerall= find_all_field('dealer_info','','dealer_code="'.$_SESSION['dlrid'].'"');


$in_stock_pcs = find_a_field('journal_item','sum(item_in)-sum(item_ex)','item_id="'.$item_all->item_id.'" and warehouse_id="'.$depot_id.'" ');
$da=date('Y-m-d');
//$ordered_qty = find_a_field('sale_do_details','sum(total_unit)','item_id="'.$item_all->item_id.'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","CHECKED","PROCESSING","MANUAL")');
$del_qty = find_a_field('sale_do_chalan','sum(total_unit)','item_id="'.$item_all->item_id.'" and depot_id="'.$depot_id.'" and status in ("UNCHECKED","CHECKED","PROCESSING")');







$in_stock = (int)($in_stock_pcs / $item_all->pack_size);

//echo 'OK'.$item_all->quantity_type;

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input name="item2" type="text" class="input3" id="item2"  style="width:240px;" required="required" tabindex="3" value="<?=$item_all->item_name?>" onfocus="focuson('pkt_unit')"/></td>
    <td><input name="inStock_ctn" type="text" class="input3" id="inStock_ctn"  style="width:55px" value="<? $inStockCtn = ($in_stock_pcs-$ordered_qty)/$item_all->pack_size; $inStockCtn=(int)$inStockCtn; echo $inStockCtn;?>" readonly="readonly"/>

	<input name="inStock_pcs" type="text" class="input3" id="inStock_pcs"  style="width:55px;" value="<?=($in_stock_pcs-$ordered_qty)-($inStockCtn*$item_all->pack_size)?>" readonly="readonly"/>


        <?php
            $fdcom = find_a_field('sales_setup_MT_price','comission_margin','dealer_code="'.$_SESSION['dlrid'].'" and item_id='.$item_all->item_id.'');
            $acmarp=(($item_all->m_price)*($fdcom/100)) ;
            $acmarps=$item_all->m_price-$acmarp;

        ?>


      <input name="item_id" type="hidden" class="input3" id="item_id"  style="width:55px;"  value="<?=$item_all->item_id?>" readonly="readonly"/></td>
    <td><input name="unit_price" type="text" class="input3" id="unit_price"  style="width:55px;" value="<?=$acmarps;?>"/>
      <input name="unit_price2" type="hidden" class="input3" id="unit_price2"  style="width:55px;" value="<?=($acmarps);?>"/>
      <input name="pkt_size" type="hidden" class="input3" id="pkt_size"  style="width:55px;"  value="<?=$item_all->pack_size?>" readonly="readonly"/>
	  <input name="quantity_type" type="hidden" class="input3" id="quantity_type"  style="width:55px;"  value="<?=$item_all->quantity_type?>" readonly="readonly"/>
      <input  name="t_price" type="hidden" id="t_price" value="<?=$item_all->t_price?>" readonly="readonly"/>
      <input  name="cogs_price" type="hidden" id="cogs_price" value="<?=$item_all->production_cost?>" readonly="readonly"/>
      <input  name="d_price" type="hidden" id="d_price" value="<?=$item_all->d_price?>" readonly="readonly"/>
      <input  name="m_price" type="hidden" id="m_price" value="<?=$item_all->m_price?>" readonly="readonly"/>
      <input style="width:155px;"  name="dealer_type" type="hidden" id="dealer_type" value="<?=$dealerall->customer_type?>" readonly/>
      <input style="width:155px;"  name="town" type="hidden" id="town" value="<?=$dealerall->town_code?>" readonly/>
      <input style="width:155px;"  name="area_code" type="hidden" id="area_code" value="<?=$dealerall->area_code?>" readonly/>
      <input style="width:155px;"  name="territory" type="hidden" id="territory" value="<?=$dealerall->territory?>" readonly/>
      <input style="width:155px;"  name="region" type="hidden" id="region" value="<?=$dealerall->region?>" readonly/>
	  </td>
  </tr>
</table>
