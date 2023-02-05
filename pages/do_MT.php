<?php
session_start();
ob_start();

require_once 'base.php';
require_once 'create_id.php';
require_once 'module.php';
require_once 'function_mod.php';
require_once 'curd_function.php';
require_once 'report.class.php';
require_once 'db.php';
require_once 'function_module_create.php';
$title='Demand Order (MT)';
$page = 'do_MT.php';

if($_POST['dealer']>0)
$dealer_code = $_POST['dealer'];
else
$dealer_code = $_SESSION[dealer_code_GET];
$dealer = find_all_field('dealer_info','','dealer_code='.$dealer_code);





/////////// table name find

$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$unique_detail='id';




if($_SESSION['old_do_find']>0)
$$unique_master=$_SESSION['old_do_find'];
elseif(isset($_GET['del']))
{$$unique_master=find_a_field('sale_do_details','do_no','id='.$_GET['del']); $del = $_GET['del'];}

else

$$unique_master=$_REQUEST[$unique_master];







if(prevent_multi_submit()){

    if(isset($_POST['new']))

    {

        $crud   = new crud($table_master);

        $_SESSION['depot_do_<?=$unique_master?>']=$_POST['depot_id'];

        $_SESSION['dlrid']=$_POST['dealer_code'];

        $_SESSION['DEPID']=$_POST['depot_id'];

        $_POST['entry_at']=date('Y-m-d H:s:i');

        $_POST['entry_by']=$_SESSION['userid'];

        if($_POST['flag']<1){

            $_POST['do_no'] = find_a_field($table_master,'max(do_no)','1')+1;

            $crud->insert();

            $$unique_master=$_POST[$unique_master];

            unset($$unique);

            $type=1;

            $msg='Work Order Initialized. (Demand Order No-'.$$unique_master.')';

        }else {

            $crud->update($unique_master);

            $type=1;

            $msg='Successfully Updated.';

        }

    }









    if(isset($_POST['add'])&&($_POST[$unique_master]>0)){

        $_POST['depot_id']=$_SESSION['depot_do_<?=$unique_master?>'];

        $table		=$table_detail;

        $crud      	=new crud($table);

        $_POST['total_unit'] = ($_POST['pkt_unit'] * $_POST['pkt_size']) + $_POST['dist_unit'];



        if($_POST['unit_price2']==0) $_POST['unit_price'] = 0;

        $_POST['total_amt'] = ($_POST['total_unit'] * $_POST['unit_price']);



        $_POST['t_price'] = find_a_field('item_info','t_price','item_id ='.$_POST['item_id']);

        $_POST['gift_on_order'] = $crud->insert();



        $do_date = date('Y-m-d');

        $_POST['gift_on_item'] = $_POST['item_id'];



        $dealer = find_all_field('dealer_info','','dealer_code='.$_POST['dealer_code']);



        if($_POST['group_for']!='M'){

            $sss = "select * from sale_gift_offer where item_id='".$_POST['item_id']."' and start_date<='".$do_date."' and end_date>='".$do_date."' and dealer_type='".$dealer->dealer_type."'";

        }else

            $sss = "select * from sale_gift_offer where item_id='".$_POST['item_id']."' and start_date<='".$do_date."' and end_date>='".$do_date."' and (group_for like '%A%' or group_for like '%B%' or group_for like '%C%' or group_for like '%D%') and dealer_type='".$dealer->dealer_type."'";

        $qqq = mysql_query($sss);

        $total_unit = $_POST['total_unit'];

        while($gift=mysql_fetch_object($qqq)){

            if($gift->item_qty>0){

                $_POST['gift_id'] = $gift->id;

                $gift_item = find_all_field('item_info','','item_id="'.$gift->gift_id.'"');

                $_POST['item_id'] = $gift->gift_id;

                if($gift->gift_id== 1096000100010239){

                    $_POST['unit_price'] = (-1)*($gift->gift_qty);

                    $_POST['total_amt']  = (((int)($total_unit/$gift->item_qty))*($_POST['unit_price']));

                    $_POST['total_unit'] = (((int)($total_unit/$gift->item_qty)));

                    $_POST['dist_unit'] = $_POST['total_unit'];

                    $_POST['pkt_unit']  = '0.00';

                    $_POST['pkt_size']  = '1.00';

                    $_POST['t_price']   = '-1.00';

                    $crud->insert();

                }

                elseif($gift->gift_id== 1096000100010312){

                    $_POST['unit_price'] = (-1)*($gift->gift_qty);

                    $_POST['total_amt']  = (((int)($total_unit/$gift->item_qty))*($_POST['unit_price']));

                    $_POST['total_unit'] = (((int)($total_unit/$gift->item_qty)));

                    $_POST['dist_unit'] = $_POST['total_unit'];

                    $_POST['pkt_unit']  = '0.00';

                    $_POST['pkt_size']  = '1.00';

                    $_POST['t_price']   = '-1.00';

                    $crud->insert();

                }else{

                    $in_stock_pcs = find_a_field('journal_item','sum(item_in)-sum(item_ex)','item_id="'.$gift_item->item_id.'" and warehouse_id="'.$_POST['depot_id'].'" ');

                    //echo 'item_id="'.$gift_item->item_id.'" and warehouse_id="'.$_POST['depot_id'].'" ';

                    //$ordered_qty = find_a_field('sale_do_details','sum(total_unit)','item_id="'.$gift_item->item_id.'" and depot_id="'.$_POST['depot_id'].'" and status in ("UNCHECKED","CHECKED","PROCESSING","MANUAL") group by item_id');

                    $_POST['pkt_size'] = $gift_item->pack_size;

                    $_POST['unit_price'] = '0.00';

                    $_POST['total_amt'] = '0.00';

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

                }

//		unset($_POST['gift_id']);

//		unset($_POST['gift_on_order']);

//		unset($_POST['gift_on_item']);

            }

        }

    }

}else{

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

    $msg='Successfully Deleted.';}



if($$unique_master>0)

{

    $condition=$unique_master."=".$$unique_master;

    $data=db_fetch_object($table_master,$condition);

    while (list($key, $value)=@each($data))

    { $$key=$value;}}

$dealer = find_all_field('dealer_info','','dealer_code='.$dealer_code);

if($dealer->product_group!='M') $dgp = $dealer->product_group;

auto_complete_from_db('item_info','concat(finish_goods_code,"#>",item_name)','finish_goods_code','product_nature="Salable" and exim_status not in ("Export")','item');

?>





<script language="javascript">

    function count()

    {

        var quantity_type = document.getElementById('quantity_type').value;

        if(quantity_type=="Ctn"){

//document.getElementById('pkt_unit').focus();

            var pkt_unit = ((document.getElementById('pkt_unit').value)*1);
            var dist_unit = ((document.getElementById('dist_unit').value)*1);
            var pkt_size = ((document.getElementById('pkt_size').value)*1);
            var unit_price2 = ((document.getElementById('unit_price2').value)*1);
            var total_unit = (pkt_unit*pkt_size)+dist_unit;
            if(unit_price2==0)
                var unit_price =0;
            else
                var unit_price = ((document.getElementById('unit_price2').value)*1);
            var total_amt  = (total_unit*unit_price);
            document.getElementById('total_unit').value=total_unit;
            document.getElementById('total_amt').value	= total_amt.toFixed(2);
        } else {

            document.getElementById('dist_unit').focus();
            document.getElementById('pkt_unit').setAttribute("readonly", "readonly");
            document.getElementById('pkt_unit').value=0;}    }
    function comm_cal() {
        var total_amt=(document.getElementById('total_amt').value*1);
        var comm_amount=(document.getElementById('commission2').value*1);
        var ctn=(document.getElementById('pkt_unit').value*1);
        document.getElementById('commission').value=(ctn*comm_amount);
        var tot_comm=(document.getElementById('commission').value*1);
        document.getElementById('net_amount').value=(total_amt-tot_comm);

    }

</script>







<script language="javascript">

    function focuson(id) {

        if(document.getElementById('item').value=='')

            document.getElementById('item').focus();

        else

            document.getElementById(id).focus();  }

    window.onload = function() {

        var received_amt=(document.getElementById('received_amt').value*1);

        document.getElementById('received_amt2').value=received_amt.toFixed(2);

        var do_ordering=(document.getElementById('do_ordering').value*1);

//document.getElementById('received_amt2').value=(received_amt-do_ordering).toFixed(2);





        if(document.getElementById("flag").value=='0')

            document.getElementById("rcv_amt").focus();

        else

            document.getElementById("item").focus();    }



    function avail_amount(){

        var inStock_ctn=(document.getElementById('inStock_ctn').value*1);

        var pkt_size=(document.getElementById('pkt_size').value*1);

        var inStock_pcs=(document.getElementById('inStock_pcs').value*1);

        var pkt_unit=(document.getElementById('pkt_unit').value*1);

        var dist_unit=(document.getElementById('dist_unit').value*1);

        var totalStockPcs = (inStock_ctn*pkt_size)+inStock_pcs;

        var totalOrderPcs = (pkt_unit*pkt_size)+dist_unit;



        if(totalOrderPcs>totalStockPcs){

            alert('You can\'t Make Order More Than Stock');

            document.getElementById('pkt_unit').value=0;

            document.getElementById('dist_unit').value=0;

        }

    }



</script>

<script language="javascript">



    function grp_check(id){

        if(document.getElementById("item").value!=''){

            var myCars=new Array();

            myCars[0]="01815224424";



            <?

            $item_i = 1;

            $sql_i='select finish_goods_code from item_info where sales_item_type like "%'.$dgp.'%" and product_nature="Salable"';

            $query_i=mysql_query($sql_i);

            while($is=mysql_fetch_object($query_i))

            {

                echo 'myCars['.$item_i.']="'.$is->finish_goods_code.'";';

                $item_i++;

            }  ?>

            var item_check=id;

            var f=myCars.indexOf(item_check);

            getData2('do_ajax_MT.php', 'do',document.getElementById("item").value,'<?=$_SESSION['depot_do_<?=$unique_master?>'];?>');

        }}

</script>





<style type="text/css">
    .style1 {
        color: #FFFFFF;
        font-weight: bold;}
    input[type=text]{
        font-size: 11px;
        height: 25px;
    }
    select {
        font-size: 11px;
        height: 25px;
    }
</style>







<div class="form-container_large">

    <form action="<?=$page?>" method="post" class="form-horizontal form-label-left" name="codz2" id="codz2" style="font-size: 11px">
        <? require_once 'support_html.php';?>
        <table width="100%" border="0" style="padding:5px; border-spacing:5px;" cellspacing="0" cellpadding="0" align="center" >
            <tr>
                <th style="padding: 2px">DO No</th><th style="width: 1%;padding: 2px">:</th>
                <td style="padding: 2px; width: 25%">
                    <input style="width:80%; height: 30px"  name="do_no" type="text" id="do_no" value="<? if($$unique_master>0) echo $$unique_master; else echo (find_a_field($table_master,'max('.$unique_master.')','1')+1);?>"  readonly/>
                    <input style="width:80%; height: 30px"  name="exim_status" type="hidden" id="exim_status" value="Local" class="form-control col-md-7 col-xs-12" readonly/>
                </td>

                <th style="padding: 2px">DO Date</th><th style="width: 1%; padding: 2px">:</th>
                <td style="padding: 2px; width: 25%">
                    <input style="width:80%; font-size: 11px; height: 30px"  name="do_date" type="date" max="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>" id="do_date" value="<?=date('Y-m-d')?>">
                </td>

                <th style="padding: 2px">Do Type</th><th style="width: 1%; padding: 2px">:</th>
                <td style="padding: 2px; width: 25%">
                    <select class="select2_single form-control" style="width:80%; font-size: 11px; height: 30px" tabindex="-1"  name="do_type" id="do_type">
                        <option></option>
                        <?php foreign_relation('sale_do_master', 'distinct do_type', 'do_type', $do_type, '1'); ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th style="padding: 2px">Outlet</th><th style="width: 1%; padding: 2px">:</th>
                <td style="padding: 2px; width: 25%">
                    <input type="hidden" id="dealer_code" name="dealer_code" value=" <?=$dealer->dealer_code.'-'.$dealer->dealer_name_e;?>" readonly="readonly">
                    <select class="select2_single form-control" style="width:80%; font-size: 11px; height: 30px" tabindex="-1"  name="sub_dealer_code" id="sub_dealer_code">
                        <option></option>
                        <?php foreign_relation('dealer_sub_info', 'sub_dealer_code', 'CONCAT(sub_dealer_code," : ", sub_dealer_name)', $sub_dealer_code, 'dealer_code='.$dealer->dealer_code.''); ?>
                    </select>                </td>

                <th style="padding: 2px">Balance</th><th style="width: 1%; padding: 2px">:</th>
                <td style="padding: 2px; width: 25%">
                    <input style="width:155px;"  name="received_amt2" type="hidden" id="received_amt2" value="" readonly/>
                    <input style="width:80%; height: 30px"  name="received_amt"  type="text" id="received_amt" value="<? echo $av_amt=(find_a_field_sql('select sum(cr_amt-dr_amt) from journal where ledger_id='.$dealer->account_code))?>" readonly/>              </div></div>
</td>

<th style="padding: 2px">Credit Limit</th><th style="width: 1%; padding: 2px">:</th>
<td style="padding: 2px; width: 25%"><input style="width:80%; height: 30px"  name="credit_limit"  type="text" id="wo_subject" value="<?=$dealer->credit_limit;?>" readonly/></td>
</tr>



<tr>
    <th style="padding: 2px">Region</th><th style="width: 1%; padding: 2px">:</th>
    <td style="padding: 2px; width: 25%">
        <input type="hidden" id="dealer_code" name="dealer_code" value=" <?=$dealer->dealer_code.'-'.$dealer->dealer_name_e;?>" readonly="readonly">
        <input style="width: 80%"  name="region2" class="form-control col-md-7 col-xs-12" type="text" id="region2" value="<?=$region=find_a_field('branch','BRANCH_NAME','BRANCH_ID='.$dealer->region); ?>" readonly/>
        <input style="width: 80%"  name="region" type="hidden" id="region" value="<?=$dealer->region?>" readonly/>
    </td>

    <th style="padding: 2px">Territory</th><th style="width: 1%; padding: 2px">:</th>
    <td style="padding: 2px; width: 25%">
        <input style="width: 80%"  name="area_code" type="hidden" id="area_code" class="form-control col-md-7 col-xs-12" value="<?=$area=find_a_field('area','AREA_NAME','AREA_CODE='.$dealer->area_code);?>" readonly/>
        <input style="width: 80%;"  name="area_code" type="hidden" id="area_code" value="<?=$dealer->area_code?>" readonly/>
        <input style="width: 80%;"  name="territory1" class="form-control col-md-7 col-xs-12" type="text" id="territory1" value="<?=$zon=find_a_field('zon','ZONE_NAME','ZONE_CODE='.$dealer->territory);?>"  readonly/>
        <input style="width: 80%;"  name="territory" type="hidden" id="territory" value="<?=$dealer->territory?>" readonly/></td>

    <th style="padding: 2px">Town</th><th style="width: 1%; padding: 2px">:</th>
    <td style="padding: 2px; width: 25%">
        <input style="width: 80%"  name="area_code" type="text" id="area_code" class="form-control col-md-7 col-xs-12" value="<?=$town=find_a_field('town',' 	town_name','town_code='.$dealer->town_code);?>" readonly/>
        <input style="width: 80%"  name="town" type="hidden" id="town" value="<?=$dealer->town_code?>" readonly/>
    </td>
</tr>

<tr>
    <th style="padding: 2px">Warehouse</th><th style="width: 1%; padding: 2px">:</th>
    <td style="padding: 2px; width: 25%"><select class="form-control" style="width:80%; font-size: 11px; height: 30px" tabindex="-1" required="required" id="depot_id" name="depot_id">
            <option></option>
            <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $depot_id, 'use_type in (\'PL\',\'WH\')','order by warehouse_id'); ?>
        </select>
    </td>

    <th style="padding: 2px">Commission</th><th style="width: 1%; padding: 2px">:</th>
    <td style="padding: 2px; width: 25%">
        <input style="width:80%; height: 30px"  name="commission" class="form-control col-md-7 col-xs-12" type="text" id="commission" value="<?=$region=find_a_field('dealer_info','commission','dealer_code='.$dealer->dealer_code); ?>%" readonly/>
    </td>

    <th style="padding: 2px">Remarks</th><th style="width: 1%; padding: 2px">:</th>
    <td style="padding: 2px; width: 25%"><input style="width:80%; height: 30px"  name="remarks"  type="text" id="remarks" value="<?=$remarks?>" /></td>
</tr>

            <tr>
                <td align="center" style="padding-top: 10px" colspan="9">
                        <?
                        if($$unique_master>0) {?>
                            <button type="submit" name="new" class="btn btn-primary" style="font-size: 12px">Update Demand Order</button>
                             <input name="flag" id="flag" type="hidden" value="1" />
                        <? }else{?>
                            <button type="submit" name="new" class="btn btn-primary" style="font-size: 12px">Initiate Demand Order</button>
                             <input name="flag" id="flag" type="hidden" value="0" />
                        <? } ?>



                    <!--a target="_blank" href="../report/do_view.php?v_no=<?=$$unique_master?>"><img src="../../images/print.png" alt="" width="26" height="26" /></a--></td>

            </tr>

        </table>
    </form>
</div>

<script>$("#cz").validate();$("#cloud").validate();</script>

<?
$main_content=ob_get_contents();
ob_end_clean();
?>







<?php require_once 'header_content.php'; ?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



    <title>Sales ERP <?=PROJECT?></title>

    <!--link href="http://icpbd-erp.com/51816/sales_mod/css/menu.css" type="text/css" rel="stylesheet"/>

    <link href="http://icpbd-erp.com/51816/sales_mod/css/table.css" type="text/css" rel="stylesheet"/>

    <link href="http://icpbd-erp.com/51816/sales_mod/css/input.css" type="text/css" rel="stylesheet"/>

    <link href="http://icpbd-erp.com/51816/sales_mod/css/form.css" type="text/css" rel="stylesheet"/-->





    <!--link href="http://icpbd-erp.com/51816/sales_mod/css/pagination.css" rel="stylesheet" type="text/css" /-->

    <link href="http://icpbd-erp.com/51816/sales_mod/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />

    <link href="http://icpbd-erp.com/51816/sales_mod/css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />



    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/jquery-1.4.2.min.js"></script>

    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/jquery-ui-1.8.2.custom.min.js"></script>

    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/jquery.ui.datepicker.js"></script>

    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/jquery.autocomplete.js"></script>

    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/jquery.validate.js"></script>

    <script type="text/javascript" src="http://icpbd-erp.com/51816/sales_mod/js/paging.js"></script>

    <script type="text/javascript" src=http://icpbd-erp.com/51816/sales_mod/js/ddaccordion.js"></script>

    <script type="text/javascript" src=http://icpbd-erp.com/51816/sales_mod/js/js.js"></script>

    <style type="text/css">

        <!--

        .style1 {font-size: 20px}

        -->

    </style>

</head>

<?php require_once 'body_content.php'; ?>

<div class="col-md-12 col-sm-12 col-xs-12">

    <div class="x_panel">

        <div class="x_title">

            <h2><?=$title;?></h2>

            <div class="clearfix"></div>

        </div>



        <div class="x_content">





<?=$main_content?>

        </div></div></div>




<? if($$unique_master>0){?>
            <form action="<?=$page?>?depot=<?=$_POST['depot_id']?>" method="post" name="codz2" id="codz2" style="font-size: 11px">
                                <table align="center"  class="table table-striped table-bordered" style="width:98%; font-size: 11px; ">
                                    <thead>
                                <tr style="background-color: bisque">
                                    <td width="10%"   align="center"><strong>Item Code</strong></td>
                                    <td width="42%"   align="center"><strong>Item Name, Stock & Price</strong></td>
                                    <td  colspan="2"  align="center"><strong>Order</strong></td>
                                    <td width="12%"   align="center"><strong>Total</strong></td>
                                    <td></td>
                                </tr>
                                </thead>

                                <tbody>
                    <tr>
                        <td align="center" style="vertical-align: middle" bgcolor="#CCCCCC"><span id="inst_no">
        <input name="item" type="text" class="input3" id="item"  style="width:180px;" required onblur="grp_check(this.value)" placeholder="select your choose item" tabindex="1"/>
        <input name="do_no" type="hidden" id="do_no" value="<?=$do_no;?>" readonly/>
        <input name="do_type" type="hidden" id="do_type" value="<?=$do_type;?>" readonly/>
        <input name="section_id" type="hidden" id="section_id" value="<?=$_SESSION[sectionid]?>" readonly/>
        <input name="company_id" type="hidden" id="company_id" value="<?=$_SESSION[companyid]?>" readonly/>
        <input name="group_for" type="hidden" id="group_for" value="<?=$dealer->product_group;?>" readonly/>
        <input name="dealer_code" type="hidden" id="dealer_code" value="<?=$dealer->dealer_code;?>"/>
        <input name="depot_id" type="hidden" id="depot_id" value="<?=$dealer->depot;?>"/>
        <input style="width:155px;"  name="exim_status" type="hidden" id="exim_status" value="Local" class="form-control col-md-7 col-xs-12" readonly/>
        <input name="flag" id="flag" type="hidden" value="1" />
        </span>         <input style="width:10px;"  name="group_for" type="hidden" id="group_for" value="<?=$dealer->product_group;?>" readonly/></td>
                        <td bgcolor="#CCCCCC" style="vertical-align: middle">
                                        <span id="do">
                                            <input name="item2" type="text" class="input3" id="item2"  style="width:240px;" autocomplete="off" readonly required="required" tabindex="3" value="<?=$item_all->item_name?>" placeholder="item details" />

                  <input name="in_stock" type="text" class="input3" id="in_stock"  style="width:55px;" value="<?=$in_stock?>" placeholder="crt" readonly />

                  <input name="in_stock_pcs" type="text" class="input3" id="in_stock_pcs"  style="width:55px;" value="<?=$in_stock?>" placeholder="pcs" readonly />

                    <input name="item_id" type="hidden" class="input3" id="item_id"  style="width:55px;"  value="<?=$item_all->item_id?>" readonly/>

                  <input name="undel" type="hidden" class="input3" id="undel"  style="width:55px;" readonly placeholder="stock in pcs" value="<?=($ordered_qty+$del_qty)?>"/>

                  <input name="unit_price" type="text" class="input3" id="unit_price"  style="width:55px;" value="<?=$item_all->m_price?>" placeholder="price" readonly/>

                    <input name="pkt_size" type="hidden" class="input3" id="pkt_size"  style="width:55px;"  value="<?=$item_all->pack_size?>" readonly/>



              </span></td>

                        <td align="center" style="vertical-align: middle">

                        <input placeholder="Crt"  name="pkt_unit" type="text" class="input3" id="pkt_unit" style="width:55px;height: 30px;text-align: center" onkeyup="avail_amount(),count()" required="required"  tabindex="4" autocomplete="off" /></td>

                        <td align="center" style="vertical-align: middle">

                        <input placeholder="Pcs" name="dist_unit" type="text" class="input3" id="dist_unit" style="width:55px; height: 30px;text-align: center" onkeyup="avail_amount(),count()" autocomplete="off" /></td>

                        <input name="total_unit" type="hidden" class="input3" id="total_unit"  style="width:55px;height: 30px" class="form-control col-md-7 col-xs-12" readonly/>

         <td align="center" style="vertical-align: middle"><input placeholder="Total" name="total_amt" type="text" class="input3" id="total_amt" style="width:100px;height: 30px; text-align: center" readonly/></td>



                        <td style="vertical-align: middle">

                            <button  type="submit" class="btn btn-primary" name="add" onclick="count()" tabindex="5" id="add" style="font-size: 12px">Add</button></td>

                                            </tr>

                                        </table>











                <table align="center"  class="table table-striped table-bordered" style="width:98%; font-size: 11px">

                    <thead>

                                    <tr style="background-color: bisque">
                                        <th rowspan="2" style="vertical-align: middle">S/L</th>
                                        <th rowspan="2" style="vertical-align: middle">Code</th>
                                        <th rowspan="2" style="vertical-align: middle">Item Name</th>
                                        <th rowspan="2" style="vertical-align: middle;text-align: center">Unit Price</th>
                                        <th colspan="2" style="vertical-align: middle; text-align: center">In Stock </th>
                                        <th colspan="2" style="text-align:center;vertical-align: middle">Order </th>
                                        <th rowspan="2" style="vertical-align: middle; text-align: center">Total Amt</th>
                                        <th rowspan="2" style="vertical-align: middle; text-align: center">X</th>
                                    </tr>
                                    <tr >
                                        <th>Ctn</th>
                                        <th>Pcs</th>
                                        <th>Ctn</th>
                                        <th>Pcs</th>
                                    </tr>
                                   </thead>



                                    <tbody>

                                    <? $res='select a.id,b.finish_goods_code as code,b.item_name, round(a.unit_price, 2) as dPrice,a.pkt_unit as crt_qty,a.dist_unit as pcs, a.inStock_Totalpcs, a.total_unit, a.total_amt, a.inStock_ctn, a.inStock_pcs, "X" from sale_do_details a,item_info b where b.item_id=a.item_id and a.do_no='.$$unique_master.' order by a.id';
                                    $query=mysqli_query($conn, $res);
                                    while($data=mysqli_fetch_object($query)){ ?>

                                        <?php if ($data->inStock_Totalpcs<$data->total_unit && $data->dPrice==0){ ?>
                                            <tr style="background-color:red">
                                        <?php } elseif( $data->total_amt==0 || $data->total_amt<0){ ?>
                                            <tr style="background-color:#FFE4C4">
                                        <?php } ?>
                                        <td><?=++$z;?></td>
                                        <td><?=$data->code?></td>
                                        <td><?=$data->item_name?> <?php if($data->dPrice>0) echo ''; else echo '<font style="margin-left: 15px; color: red">[Free]</font>'; ?></td>
                                        <td><?=$data->dPrice?></td>
                                        <td align="center"><?=$data->inStock_ctn?></td>
                                        <td align="center"><?=$data->inStock_pcs?></td>
                                        <td align="center"><?=$data->crt_qty?></td>
                                        <td align="center"><?=$data->pcs?></td>
                                        <td align="right"><?=$data->total_amt; $gTotla+=$data->total_amt;?></td>
                                        <td style="text-align: center"><a href="?del=<?=$data->id?>"><img src="delete.png" style="width:15px;  height:15px"></a></td>
                                        </tr>

                                    <? }?></tbody>

                                    <tr style="font-weight:bold">
                                        <td colspan="8" style="text-align:right;">Total:</td>
                                        <td colspan="1" align="right"><?=number_format($gTotla,2)?></td>
                                        <td></td>
                                    </tr>



                                    <?php if($dealer->commission>0){ ?>

                                        <tr style="font-weight:bold">

                                            <td colspan="8" style="text-align:right;">Super DB Commission (<?=$dealer->commission?> %):</td>

                                            <td colspan="1" align="right"><?=number_format($comissionGET=($gTotla/100)*$dealer->commission,2);  $_SESSION['COMWR']=$comissionGET; ?></td>

                                        </tr>



                                        <tr style="font-weight:bold">
                                            <td colspan="8" style="text-align:right;">Net Payable:</td>
                                            <td colspan="1" align="right"><?=number_format(($gTotla-$comissionGET),2)?></td>
                                        </tr>
                                    <?php } ?>
                                </table>

            </form>

            <?php
            $freeStock = find_a_field('sale_do_details','id','inStock_Totalpcs<total_unit and unit_price=0 and do_no='.$$unique_master);
            ?>

            <form action="select_dealer_do_MT.php" method="post" name="cz" id="cz" onSubmit="if(!confirm('Are You Sure Execute this?')){return false;}">

                            <button type="submit" name="delete"  class="btn btn-danger" style="font-size: 12px; float: left; margin-left: 1%">Delete & Cancel this DO</button>
                            <input  name="do_no" type="hidden" id="do_no" value="<?=$$unique_master?>"/></td>
                            <?php if ($freeStock>0){ echo "<h2 align='center'>Invalid DO</h2>"; ?>
                            <?php } else  { ?>
                                <button type="submit" name="confirm"  class="btn btn-success" style="width:270px;  float:right; font-size: 12px; margin-right: 1%" <?=($freeStock>0)? 'disabled' : ''?>>PRIMARILY SAVE THIS DO</button>
                            <?php }  ?>

            </form>
<? }?>
<br><br><br>  <br><br><br><br><br><br>  <br><br><br><br><br><br>  <br><br><br><br><br><br>  <br><br><br><br><br><br>  <br><br><br><br><br><br>  <br><br><br>
</div>
</div>
</div>

<!-- footer content -->
<footer>
    <div class="pull-right">
        Power By: ICP MIS Department
    </div>
    <div class="clearfix">Â© <?=date('Y')?> <strong>RARESOFT</strong> All Rights Reserved</div>
</footer>
<!-- /footer content -->
</div>
</div>


<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../vendors/fastclick/lib/fastclick.js"></script>
<script src="../vendors/nprogress/nprogress.js"></script>
<!-- iCheck -->
<script src="../vendors/iCheck/icheck.min.js"></script>
<!-- Datatables -->
<script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="../vendors/jszip/dist/jszip.min.js"></script>
<script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- Select2 -->
<script src="../vendors/select2/dist/js/select2.full.min.js"></script>
<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../vendors/moment/min/moment.min.js"></script>
<script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- jQuery custom content scroller -->
<script src="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- Datatables -->
<script>

    $(document).ready(function() {

        $(".select2_single").select2({

            placeholder: "Select a Choose",

            allowClear: true

        });

        $(".select2_group").select2({});

        $(".select2_multiple").select2({

            maximumSelectionLength: 4,

            placeholder: "With Max Selection limit 4",

            allowClear: true

        });

    });

</script>

<!-- /Select2 -->



<!-- /Datatables -->

<script>

    $(document).ready(function() {

        $('#f_date').daterangepicker({



            singleDatePicker: true,

            calender_style: "picker_4",



        }, function(start, end, label) {

            console.log(start.toISOString(), end.toISOString(), label);

        });

    });

</script>





<script>

    $(document).ready(function() {

        $('#t_date').daterangepicker({



            singleDatePicker: true,

            calender_style: "picker_4",



        }, function(start, end, label) {

            console.log(start.toISOString(), end.toISOString(), label);

        });

    });

</script>





<script>

    $(document).ready(function() {

        $('#po_date').daterangepicker({



            singleDatePicker: true,

            calender_style: "picker_4",



        }, function(start, end, label) {

            console.log(start.toISOString(), end.toISOString(), label);

        });

    });

</script>





<script>

    $(document).ready(function() {

        $('#quotation_date').daterangepicker({



            singleDatePicker: true,

            calender_style: "picker_4",



        }, function(start, end, label) {

            console.log(start.toISOString(), end.toISOString(), label);

        });

    });

</script>





<script>

    $(document).ready(function() {

        $('#delivery_within').daterangepicker({



            singleDatePicker: true,

            calender_style: "picker_4",



        }, function(start, end, label) {

            console.log(start.toISOString(), end.toISOString(), label);

        });

    });

</script>

</body>



