<?php
require_once 'support_file.php';
$title='Demand Order Create';
do_calander('#est_date');
$page = 'do2.php';
if($_POST['dealer']>0) 
$dealer_code = $_POST['dealer'];
$dealer = find_all_field('dealer_info','','dealer_code='.$dealer_code);
$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$unique_detail='id';


if($_REQUEST['old_do_no']>0)
$$unique_master=$_REQUEST['old_do_no'];
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
		{



			$condition=$unique_detail."=".$del;	
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
{		$condition=$unique_master."=".$$unique_master;
		$data=db_fetch_object($table_master,$condition);
		while (list($key, $value)=@each($data))

		{ $$key=$value;}}


		$dealer = find_all_field('dealer_info','','dealer_code='.$dealer_code);
		if($dealer->product_group!='M') $dgp = $dealer->product_group;
auto_complete_from_db('item_info','concat(finish_goods_code,"#>",item_name)','finish_goods_code','product_nature="Salable"','item');
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

//var do_total = ((document.getElementById('do_total').value)*1);
//var do_ordering	= total_amt+do_total;
//document.getElementById('do_ordering').value =do_ordering.toFixed(2);
}

else{

document.getElementById('dist_unit').focus();
document.getElementById('pkt_unit').setAttribute("readonly", "readonly");
document.getElementById('pkt_unit').value=0;}


}

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
  document.getElementById(id).focus();
}
window.onload = function() {
var received_amt=(document.getElementById('received_amt').value*1);
document.getElementById('received_amt2').value=received_amt.toFixed(2);
var do_ordering=(document.getElementById('do_ordering').value*1);
//document.getElementById('received_amt2').value=(received_amt-do_ordering).toFixed(2);
if(document.getElementById("flag").value=='0')
  document.getElementById("rcv_amt").focus();
  else
  document.getElementById("item").focus();
}
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
	$item_i++;}
?>


var item_check=id;
var f=myCars.indexOf(item_check);
getData2('do_ajax.php', 'do',document.getElementById("item").value,'<?=$_SESSION['depot_do_<?=$unique_master?>'];?>');
}}
</script>


<style type="text/css">
<!--

.style1 {
	color: #FFFFFF;
	font-weight: bold;}
-->
</style>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>

    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">

    
</head>



<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="<?php echo $webiste; ?>" class="site_title"><i class="fa fa-paw"></i> <span>ICPBD</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <?php include ("pro.php");  ?> <br />
                <!-- /menu profile quick info -->


                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <?php include("sidebar_menus.php"); ?>
                </div>
                <!-- /sidebar menu -->



                <!-- /menu footer buttons -->
                <?php include("menu_footer.php"); ?>
                <!-- /menu footer buttons -->

            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <?php include("top.php"); ?>
        </div>

        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="row">



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                                        </a-->
                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <form  name="codz2" id="codz2" action="<?=$page?>" class="form-horizontal form-label-left" method="post">
                                    <?require_once 'support_html.php';?>

  <table width="99%" border="0" cellspacing="0" cellpadding="0" align="center" style="font-size: 12px; font-weight: normal">
    <tr>
      <td>


          <fieldset style="width:200px;">

              <div class="form-group" style="width: 100%">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">DO No</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input style="width:155px;"  name="do_no" type="text" id="do_no" value="<? if($$unique_master>0) echo $$unique_master; else echo (find_a_field($table_master,'max('.$unique_master.')','1')+1);?>" class="form-control col-md-7 col-xs-12" readonly/>
              </div></div>


              <div class="form-group" style="width: 100%">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Dealer</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="select2_single form-control" style="width:157px; height:25px" id="dealer_code" name="dealer_code" readonly="readonly">
                          <option value="<?=$dealer->dealer_code;?>">
                              <?=$dealer->dealer_code.'-'.$dealer->dealer_name_e;?>
                          </option>
                      </select>
                  </div></div>


              <div class="form-group" style="width: 100%">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea name="delivery_address" id="delivery_address" style="width:155px;"  class="form-control col-md-7 col-xs-12" ><? if($delivery_address!='') echo $delivery_address; else echo $dealer->address_e?></textarea>
                  </div></div>
          </fieldset></td>




        <td><fieldset style="width:200px;">


                <div class="form-group" style="width: 100%">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Town</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input style="width:155px;"  name="area_code" type="text" id="area_code" class="form-control col-md-7 col-xs-12" value="<?=$town=find_a_field('town',' 	town_name','town_code='.$dealer->town_code);?>" readonly/>
                    <input style="width:155px;"  name="town" type="hidden" id="town" value="<?=$dealer->town_code?>" readonly/>
                    <input style="width:155px;"  name="dealer_type" type="hidden" id="dealer_type" value="<?=$dealer->customer_type?>" readonly/>
                </div></div>

            <div class="form-group" style="width: 100%; display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Area</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input style="width:155px;"  name="area_code" type="text" id="area_code" class="form-control col-md-7 col-xs-12" value="<?=$area=find_a_field('area','AREA_NAME','AREA_CODE='.$dealer->area_code);?>" readonly/>
                    <input style="width:155px;"  name="area_code" type="hidden" id="area_code" value="<?=$dealer->area_code?>" readonly/>
                </div></div>

            <div class="form-group" style="width: 100%">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Territory</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input style="width:155px;"  name="territory1" class="form-control col-md-7 col-xs-12" type="text" id="territory1" value="<?=$zon=find_a_field('zon','ZONE_NAME','ZONE_CODE='.$dealer->territory);?>"  readonly/>
                    <input style="width:155px;"  name="territory" type="hidden" id="territory" value="<?=$dealer->territory?>" readonly/>
                </div></div>

            <div class="form-group" style="width: 100%">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Region</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input style="width:155px;"  name="region2" class="form-control col-md-7 col-xs-12" type="text" id="region2" value="<?=$region=find_a_field('branch','BRANCH_NAME','BRANCH_ID='.$dealer->region); ?>" readonly/>
                    <input style="width:155px;"  name="region" type="hidden" id="region" value="<?=$dealer->region?>" readonly/>
                </div></div>








        </fieldset></td>






        <td><fieldset style="width:200px;">



                <div class="form-group" style="width: 100%">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">DO Date</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="width:155px;"  name="do_date" class="form-control col-md-7 col-xs-12" type="text" id="do_date" value="<?=date('Y-m-d')?>" readonly/>
                    </div></div>


                <div class="form-group" style="width: 100%">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Available Amt</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="width:155px;"  name="received_amt2" type="hidden" id="received_amt2" value="" readonly/>
                        <input style="width:155px;"  name="received_amt" class="form-control col-md-7 col-xs-12" type="text" id="received_amt" value="<? echo $av_amt=(find_a_field_sql('select sum(cr_amt-dr_amt) from journal where ledger_id='.$dealer->account_code))?>" readonly/>

                    </div></div>


                <div class="form-group" style="width: 100%">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Credit Limit</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="width:155px;"  name="wo_subject" class="form-control col-md-7 col-xs-12" type="text" id="wo_subject" value="<?=$dealer->credit_limit;?>" readonly/>

                    </div></div>

                </td>





      <td><fieldset style="width:200px;">




              <div class="form-group" style="width: 100%">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Commission</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input style="width:155px;"  name="commission" class="form-control col-md-7 col-xs-12" type="text" id="commission" value="<?=$region=find_a_field('dealer_info','commission','dealer_code='.$dealer->dealer_code); ?>%" readonly/>

              </div></div>




              <div class="form-group" style="width: 100%; display: none">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Note</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input style="width:155px;"  name="remarks" class="form-control col-md-7 col-xs-12" type="text" id="remarks" value="<?=$remarks?>" readonly/>

                  </div></div>


              <div class="form-group" style="width: 100%">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Do Type</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="select2_single form-control" style="width:157px; height:25px" name="do_type" id="do_type"  required>
                          <?php
                          $dotype=find_a_field('sale_do_master','do_type','do_no='.$$unique_master);
                          if($dotype==''){
                              ?>
                              <option value=""></option>
                              <option value="sales">Sales</option>
                              <option value="sample">Sample</option>
                              <option value="display">Product Display</option>
                              <option value="gift">Gift</option>
                              <option value="free">Free</option>
                          <?php } else { ?>

                              <option selected="selected" value="<?=$dotype?>"><?=$dotype?></option>
                              <option value="sales">Sales</option>
                              <option value="sample">Sample</option>
                              <option value="display">Product Display</option>
                              <option value="gift">Gift</option>
                              <option value="free">Free</option>

                          <?php } ?>
                      </select>
              </div></div>


              <div class="form-group" style="width: 100%">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Warehouse</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="select2_single form-control" style="width:157px; height:25px" id="depot_id" name="depot_id" required>
                          <?php  if($depot_id>0){ ?>
                              <option value="<?=$depot_id?>"><?= $warehouse=find_a_field('warehouse','warehouse_name','warehouse_id='.$depot_id); ?></option>
                          <?php } else {  ?>
                              <option value=""></option>
                              <? foreign_relation('warehouse','warehouse_id','warehouse_name',$depot_id,' 1')?>
                          <?php } ?>
                      </select>
                  </div></div>
          </fieldset></td>

    </tr>
    
    <tr>
      <td align="center" colspan="4"><div class="buttonrow">
          <? 
		  
		  if($$unique_master>0) {?>


              <button type="submit" name="new" class="btn btn-success">Update Demand Order</button>
          <input name="flag" id="flag" type="hidden" value="1" />
          <? }else{?>
           <button type="submit" name="new" id="new" class="btn btn-success">Initiate Demand Order</button>

              <input name="flag" id="flag" type="hidden" value="0" />
          <? }
		  
		  ?>
        </div>
        <!---a target="_blank" href="../report/do_view.php?v_no=<?=$$unique_master?>"><img src="../../images/print.png" alt="" width="26" height="26" /></a---></td>
    </tr>
  </table>
</form>
                            </div></div></div>
                    <!-- input section-->





                                <!-- input section-->
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="x_panel">

                                        <div class="x_content">
                                            <br />
                                            <form action="<?=$page?>?depot=<?=$_POST['depot_id']?>" method="post" name="codz2" id="codz2">
                                                <? if($$unique_master>0){?>
                                                <table  width="100%" border="1" align="left"  style="border-collapse:collapse; border:1px solid #caf5a5;" cellpadding="0" cellspacing="2">
                                                    <tr>
                                                        <td colspan="3" align="right" bgcolor="#009966" style="text-align:right"><strong>Total Ordering: </strong>
                                                            <?



                                                            $total_do = find_a_field($table_detail,'sum(total_amt)',$unique_master.'='.$$unique_master);



                                                            ?>
                                                            <input type="text" name="do_ordering" id="do_ordering" value="<?=$total_do?>" style="float:right; width:100px;" disabled="disabled" readonly />
                                                            <input type="hidden" name="do_total" id="do_total" value="<?=$total_do?>" />
                                                            &nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" bgcolor="#0099FF"><strong>Item Code</strong></td>
                                                        <td align="center" bgcolor="#0099FF"><table width="100%" border="1" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td width="42%" rowspan="2" align="center" bgcolor="#0099FF"><strong>Item Name</strong></td>
                                                                    <td colspan="2" align="center" bgcolor="#0099FF"><strong>In Stk</strong></td>
                                                                    <td width="9%" rowspan="2" align="center" bgcolor="#0099FF"><strong>Price</strong></td>
                                                                    <td width="9%" rowspan="2" align="center" bgcolor="#0099FF"><strong>Ctn Qty</strong></td>
                                                                    <td width="9%" rowspan="2" align="center" bgcolor="#0099FF"><strong>Pcs</strong></td>
                                                                    <td width="12%" rowspan="2" align="center" bgcolor="#0099FF"><strong>Total</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center" bgcolor="#0099FF">Ctn</td>
                                                                    <td align="center" bgcolor="#0099FF">Pcs</td>
                                                                </tr>
                                                            </table></td>
                                                        <td  rowspan="2" align="center" bgcolor="#FF0000"><div class="button">
                                                                <input name="add" type="submit" id="add" value="ADD" onclick="count()" class="update" tabindex="5"/>
                                                            </div></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" bgcolor="#CCCCCC"><span id="inst_no">
        <input name="item" type="text" class="input3" id="item"  style="width:80px;" required onblur="grp_check(this.value)" tabindex="1"/>
        <input name="do_no" type="hidden" id="do_no" value="<?=$do_no;?>" readonly/>
        <input name="group_for" type="hidden" id="group_for" value="<?=$dealer->product_group;?>" readonly/>
        <input name="dealer_code" type="hidden" id="dealer_code" value="<?=$dealer->dealer_code;?>"/>
        <input name="depot_id" type="hidden" id="depot_id" value="<?=$dealer->depot;?>"/>
        <input name="flag" id="flag" type="hidden" value="1" />
        </span>
                                                            <input style="width:10px;"  name="group_for" type="hidden" id="group_for" value="<?=$dealer->product_group;?>" readonly/></td>
                                                        <td bgcolor="#CCCCCC"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#CCCCCC"><span id="do">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input name="item2" type="text" class="input3" id="item2"  style="width:260px;" required="required" tabindex="3" value="<?=$item_all->item_name?>"/></td>
                  <td><input name="in_stock" type="text" class="input3" id="in_stock"  style="width:55px;" value="<?=$in_stock?>" readonly />
                    <input name="item_id" type="hidden" class="input3" id="item_id"  style="width:55px;"  value="<?=$item_all->item_id?>" readonly/></td>
                  <td><input name="undel" type="text" class="input3" id="undel"  style="width:55px;" readonly  value="<?=($ordered_qty+$del_qty)?>"/></td>
                  <td><input name="unit_price" type="text" class="input3" id="unit_price"  style="width:55px;" value="<?=$item_all->d_price?>" readonly/>
                    <input name="pkt_size" type="hidden" class="input3" id="pkt_size"  style="width:55px;"  value="<?=$item_all->pack_size?>" readonly/></td>
                </tr>
              </table>
              </span></td>
                                                                    <td bgcolor="#CCCCCC"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td><input placeholder="Crt"  name="pkt_unit" type="text" class="input3" id="pkt_unit" style="width:55px;" onkeyup="avail_amount(),count()" required="required"  tabindex="4"/></td>
                                                                                <td><input placeholder="Pcs" name="dist_unit" type="text" class="input3" id="dist_unit" style="width:55px;" onkeyup="avail_amount(),count()"/></td>
                                                                                <td><input name="total_unit" type="hidden" class="input3" id="total_unit"  style="width:55px;" readonly/>
                                                                                    <input placeholder="Total" name="total_amt" type="text" class="input3" id="total_amt" style="width:70px;" readonly/></td>
                                                                            </tr>
                                                                        </table></td>
                                                                </tr>
                                                            </table></td>
                                                    </tr>
                                                </table>
                                                <br />
                                                <br />
                                                <br />
                                                <br />

                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                    <tr>
                                                        <td><div class="tabledesign2">
                                                                <table id="grp" cellspacing="0" cellpadding="0" width="100%">
                                                                    <tbody>
                                                                    <tr>
                                                                        <th rowspan="2">S/L</th>
                                                                        <th rowspan="2">Code</th>
                                                                        <th rowspan="2">Item Name</th>
                                                                        <th rowspan="2">DPrice</th>
                                                                        <th colspan="2">In Stock </th>
                                                                        <th colspan="2" style="text-align:center; background-color:#0FF">Order </th>

                                                                        <th rowspan="2">Total Amt</th>
                                                                        <th rowspan="2">X</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Ctn</th>
                                                                        <th>Pcs</th>
                                                                        <th style="text-align:center; background-color:#0FF">Ctn</th>
                                                                        <th style="text-align:center; background-color:#0FF">Pcs</th>
                                                                    </tr>
                                                                    <?
                                                                    $res='select a.id,b.finish_goods_code as code,b.item_name, round(a.unit_price, 2) as dPrice,a.pkt_unit as crt_qty,a.dist_unit as pcs, a.inStock_Totalpcs, a.total_unit, a.total_amt, a.inStock_ctn, a.inStock_pcs, "X" from sale_do_details a,item_info b where b.item_id=a.item_id and a.do_no='.$$unique_master.' order by a.id';
                                                                    $query=mysql_query($res);
                                                                    while($data=mysql_fetch_object($query)){
                                                                        ?>

                                                                        <?php if ($data->inStock_Totalpcs<$data->total_unit && $data->dPrice==0){ ?>
                                                                            <tr style="background-color:red">

                                                                        <?php } elseif( $data->total_amt==0 || $data->total_amt<0){ ?>
                                                                            <tr style="background-color:#FFE4C4">
                                                                        <?php } ?>
                                                                        <td><?=++$z;?></td>
                                                                        <td><?=$data->code?></td>
                                                                        <td><?=$data->item_name?></td>
                                                                        <td><?=$data->dPrice?></td>
                                                                        <td align="center"><?=$data->inStock_ctn?></td>
                                                                        <td align="center"><?=$data->inStock_pcs?></td>
                                                                        <td align="center"><?=$data->crt_qty?></td>
                                                                        <td align="center"><?=$data->pcs?></td>
                                                                        <td align="right"><?=$data->total_amt; $gTotla+=$data->total_amt;?></td>
                                                                        <td><a href="?del=<?=$data->id?>">&nbsp;X&nbsp;</a></td>
                                                                        </tr>
                                                                    <? }?>
                                                                    <tr style="font-weight:bold">
                                                                        <td colspan="8" style="text-align:right;">Total:</td>
                                                                        <td colspan="1" align="right"><?=number_format($gTotla,2)?></td>
                                                                    </tr>

                                                                    <?php
                                                                    if($dealer->commission>0){

                                                                        ?>
                                                                        <tr style="font-weight:bold">
                                                                            <td colspan="8" style="text-align:right;">Super DB Commission (<?=$dealer->commission?> %):</td>
                                                                            <td colspan="1" align="right"><?=number_format($comissionGET=($gTotla/100)*$dealer->commission,2);
                                                                                $_SESSION['COMWR']=$comissionGET;

                                                                                ?></td>
                                                                        </tr>


                                                                        <tr style="font-weight:bold">
                                                                            <td colspan="8" style="text-align:right;">Net Payable:</td>
                                                                            <td colspan="1" align="right"><?=number_format(($gTotla-$comissionGET),2)?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </form>

 </div></div></div>
                <!-- input section-->





                <!-------------------list view ------------------------->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>

                                    <tr>
                                        <th rowspan="2">S/L</th>
                                        <th rowspan="2">Code</th>
                                        <th rowspan="2">Item Name</th>
                                        <th rowspan="2">DPrice</th>
                                        <th colspan="2">In Stock </th>
                                        <th colspan="2" style="text-align:center; background-color:#0FF">Order </th>

                                        <th rowspan="2">Total Amt</th>
                                        <th rowspan="2">X</th>
                                    </tr>
                                    <tr>
                                        <th>Ctn</th>
                                        <th>Pcs</th>
                                        <th style="text-align:center; background-color:#0FF">Ctn</th>
                                        <th style="text-align:center; background-color:#0FF">Pcs</th>
                                    </tr>
                                    <?
                                    $res='select a.id,b.finish_goods_code as code,b.item_name, round(a.unit_price, 2) as dPrice,a.pkt_unit as crt_qty,a.dist_unit as pcs, a.inStock_Totalpcs, a.total_unit, a.total_amt, a.inStock_ctn, a.inStock_pcs, "X" from sale_do_details a,item_info b where b.item_id=a.item_id and a.do_no='.$$unique_master.' order by a.id';
                                    $query=mysql_query($res);
                                    while($data=mysql_fetch_object($query)){
                                        ?>

                                        <?php if ($data->inStock_Totalpcs<$data->total_unit && $data->dPrice==0){ ?>
                                            <tr style="background-color:red">

                                        <?php } elseif( $data->total_amt==0 || $data->total_amt<0){ ?>
                                            <tr style="background-color:#FFE4C4">
                                        <?php } ?>
                                        <td><?=++$z;?></td>
                                        <td><?=$data->code?></td>
                                        <td><?=$data->item_name?></td>
                                        <td><?=$data->dPrice?></td>
                                        <td align="center"><?=$data->inStock_ctn?></td>
                                        <td align="center"><?=$data->inStock_pcs?></td>
                                        <td align="center"><?=$data->crt_qty?></td>
                                        <td align="center"><?=$data->pcs?></td>
                                        <td align="right"><?=$data->total_amt; $gTotla+=$data->total_amt;?></td>
                                        <td><a href="?del=<?=$data->id?>">&nbsp;X&nbsp;</a></td>
                                        </tr>
                                    <? }?>
                                    <tr style="font-weight:bold">
                                        <td colspan="8" style="text-align:right;">Total:</td>
                                        <td colspan="1" align="right"><?=number_format($gTotla,2)?></td>
                                    </tr>

                                    <?php
                                    if($dealer->commission>0){

                                        ?>
                                        <tr style="font-weight:bold">
                                            <td colspan="8" style="text-align:right;">Super DB Commission (<?=$dealer->commission?> %):</td>
                                            <td colspan="1" align="right"><?=number_format($comissionGET=($gTotla/100)*$dealer->commission,2);
                                                $_SESSION['COMWR']=$comissionGET;

                                                ?></td>
                                        </tr>


                                        <tr style="font-weight:bold">
                                            <td colspan="8" style="text-align:right;">Net Payable:</td>
                                            <td colspan="1" align="right"><?=number_format(($gTotla-$comissionGET),2)?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->











<?php
$freeStock = find_a_field('sale_do_details','id','inStock_Totalpcs<total_unit and unit_price=0 and do_no='.$$unique_master);
?>
<form action="select_dealer_do.php" method="post" name="cz" id="cz" onSubmit="if(!confirm('Are You Sure Execute this?')){return false;}">
  <table width="100%" border="0">
    <tr>
      <td align="center"><input name="delete"  type="submit" class="btn1" value="DELETE DO" style="width:100px; font-weight:bold; font-size:12px;color:#F00; height:30px" />
        <input  name="do_no" type="hidden" id="do_no" value="<?=$$unique_master?>"/></td>
      <td align="right" style="text-align:right">
      <?php if ($freeStock>0){ echo "<h2 align='center'>Invalid DO</h2>"; ?>
      
      <?php } else  { ?>
      
      <input name="confirm" type="submit" class="btn1" value="PRIMARILY SAVE THIS DO" style="width:270px; font-weight:bold; font-size:12px; height:30px; color:#090; float:right" <?=($freeStock>0)? 'disabled' : ''?> />
      
       <?php }  ?>
      </td>
    </tr>
  </table>
  <? }?>
</form>
</div>


                        </div></div></div>
                <!-- input section-->


                <!-------------------End of  List View --------------------->
            </div>
        </div>
    </div>
    <!---page content----->







    <?php require_once 'footer_content.php' ?>
