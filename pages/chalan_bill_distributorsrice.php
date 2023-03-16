<?php


require_once 'base.php';
require $_SERVER['DOCUMENT_ROOT']."/51816/engine/tools/my.php";
require $_SERVER['DOCUMENT_ROOT']."/51816/engine/tools/report.class.php";
include $_SERVER['DOCUMENT_ROOT']."/51816/engine/tools/class.numbertoword.php";




$chalan_no 		= $_REQUEST['v_no'];
$datas=find_all_field('lc_workorder_chalan','s','chalan_no='.$v_no);
$sql1="select d.*,b.*, b.sales_commission, sum(b.total_unit) as total_unit, d.total_unit as ord_unit, sum(b.total_amt) as total_amt, j.item_ex as item_ex, m.depot_id as depot, m.remarks
from sale_do_chalan b,sale_do_details d, journal_item j, sale_do_master m 
where d.id=b.order_no and j.do_no='".$chalan_no."' and  j.item_id=d.item_id and m.do_no=d.do_no and d.do_no = '".$chalan_no."' and (b.item_id!=1096000100010239 and b.item_id!=1096000100010312) group by b.order_no order by d.id";

//echo $sql1;

$data1=mysql_query($sql1);











$pi=0;
$total=0;
while($info=mysql_fetch_object($data1)){ 
$pi++;
$depot_id=$info->depot;
$remarks=$info->remarks;
$entry_time=$info->entry_time;
$chalan_date=$info->chalan_date;
$do_no=$info->do_no;
$commissionGET = find_a_field('sale_do_master','commission','do_no='.$do_no); 
$item_ex[]=$info->item_ex;
$order_no[]=$info->order_no;
$store_sl=$info->driver_name; 
$driver_name=$info->driver_name_real;
$vehicle_no=$info->vehicle_no;
$delivery_man=$info->delivery_man;
$cash_discount=$info->cash_discount;
$del_ord[]=$info->ord_unit;
$undel_ord[]=$info->ord_unit - find_a_field('sale_do_chalan','sum(total_unit)','order_no='.$info->order_no);
$item_id[] = $info->item_id;
$t_price[] = $info->t_price;
$unit_price[] = $info->unit_price;
$pkt_size[] = $info->pkt_size;
$sps = find_a_field('item_info','sub_pack_size','item_id='.$info->item_id);
$sub_pkt_size[] = (($sps>1)?$sps:1);
$total_unit[] = $info->total_unit;
$pkt_unit[] = (int)($info->pkt_unit);
$dist_unit[] = (int)($info->dist_unit);
$ord_unit[] = (int)($info->ord_unit);
$total_amt[] = $info->total_amt;
$sales_commission[] = $info->sales_commission;
$tax_percentage = $info->tax_percentage;
$transporter_name = $info->transporter_name;
$to_ctn = find_a_field('sale_do_chalan','sum(pkt_unit)','do_no='.$chalan_no);
$to_pcs = find_a_field('sale_do_chalan','sum(dist_unit)','do_no='.$chalan_no);
}
$entry_sql = 'select u.fname from users u, sale_do_master b where u.user_id=b.entry_by and b.do_no='.$do_no;
$entry_by = find_all_field_sql($entry_sql);
$ssql = 'select a.* from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$do_no;
$dealer = find_all_field_sql($ssql);
$ssql = 'select b.* from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$do_no;
$do = find_all_field_sql($ssql);
$ssqld = 'select a.* from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$do_no;
$dd = find_all_field_sql($ssqld);
$dept = 'select warehouse_name from warehouse where warehouse_id='.$depot_id;
$deptt = find_all_field_sql($dept);
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Delivery Chalan Bill Report :.</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">
function hide()
{document.getElementById("pr").style.display="none";}
</script>
<style type="text/css">
<!--
.style3 {
font-size: 14px
}-->.hidecl{
	display:none;
	}

</style>
</head>
<body style="font-family:Tahoma, Geneva, sans-serif; font-size: 13px;">

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div class="header">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            </td>
          </tr>
          <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              
                <tr>
                  <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                <td width="17%"><p><strong><img src="http://patanjaliwellness.com/51816/logo/title.png" width="99%" /></strong></p></td>
<td width="83%" align="left" valign="top"><h2 style="margin-left:33%;margin-top:2%;text-transform:uppercase"><u><strong>Invoice</strong></u></h2></td>
                </tr>
                <tr>
                <td colspan="4">
                <table width="100%" style="border-bottom:1px dotted black; margin-bottom:2%; font-size: 13px;">
                <tr>
                  <td width="74%">Customer Name: <?php echo $dealer->dealer_name_e.'-'.$dealer->dealer_code.'('.$dealer->product_group.')';?></td>
                  <td width="26%">Invoice No : <strong><?php echo $chalan_no;?></strong></td>
                </tr>
  <tr>
    <td>Address: <?php echo $dealer->address_e?></td>
    <td>Invoice Date: 
      <?=$chalan_date?></td>
  </tr>
  <tr>
    <td>Contact Person: <?php echo $dealer->propritor_name_e;?></td>
    <td>Do No: <?php echo $do_no;?></td>
  </tr>
  </table>
                </td>
               </table>
               
               </td>
                </tr>
                
              </table>
            </td>
          </tr>
        </table>
      </div></td>
  </tr>
  <tr>
    <td><div id="pr" style="margin-top:3%;">
        <div align="left">
          <form id="form1" name="form1" method="post" action="">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
             
              <tr>
              
                <td><input name="button" type="button" id="print" onclick="hide();window.print();" value="Print" /></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
      <div style="min-height:600px;">
      <table width="100%" class="tabledesign" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="1" style="font-size:12px; margin:0; padding:0">
        
       
         <tr>
          <td width="20"  align="center"><strong>SL No.</strong></td>
          <td width="91"  align="center"><strong>FG description</strong></td>
          
          <td  width="57" align="center" bgcolor="#FFFFFF"><strong>UOM</strong></td>
          <td width="78"  align="center"><strong>Trade Price / Per Sack</strong></td>
          <td  align="center"><strong>Invoice Rate<br />(Per Sack)</strong></td>
          <td  align="center"><strong>Invoice Qty</strong></td>          
          <td  width="92" align="center" bgcolor="#FFFFFF"><strong>Commission</strong> </td>
          <td width="60" align="center" bgcolor="#FFFFFF"><strong>Invoice Value</strong></td>
        </tr>
        
        
        
        
       <?php 
	   
	   
	   
	   $go=mysql_query("Select do_no from sale_do_chalan where do_no='$_GET[v_no]'");
	   $donos=mysql_fetch_array($go);
	   
	   
	  
	   
	   
	   
	   $result=mysql_query("Select d.*,i.* from sale_do_details d, item_info i where 
	   d.item_id=i.item_id and 
	   d.do_no='".$donos[do_no]."' and d.item_id not in ('1096000100010312' ,'1096000100010313')order by d.id");
	   while($row=mysql_fetch_array($result)){
		   
		   
		   $items=mysql_query("Select * from item_info where item_id='$row[item_id]'");
	       $itemrow=mysql_fetch_array($items);
		   $i=$i+1;
	   ?>
        <tr>
          <td style="width:2%;" align="center" valign="middle"><? echo $i ;?></td>
          <td style="width:auto" align="left" valign="middle"><? echo $row[item_name]; if($row[unit_price]==0)  echo ' <b>[FREE]</b>'; else '';?></td>
          <td width="5%" align="center" valign="middle"><?=$uom = find_a_field('item_info','unit_name','item_id='.$row[item_id]);;?></td>
          <td width="10%" align="center" valign="middle"><? echo number_format($row[unit_price],2);?></td>        
          
          <td width="10%" align="center" valign="middle"><? echo number_format($row[unit_price],2);?></td>
          <td width="8%" align="center" valign="middle"><? echo $row[dist_unit];?></td>
          
          <td width="8%" align="right" valign="middle">
          <?  if($row[commission_status]>0){ 
		  $commissionGET = find_a_field('sale_do_master','commission','do_no='.$do_no); 
		  $comcal=(($row[total_amt]*100/104)/100)*$commissionGET; echo number_format($comcal,2);
		  $comtotalcal=$comtotalcal+$comcal;
		  } ?>
          </td>
          <td width="8%" align="right" valign="middle"><? echo number_format($row[total_amt],2);?></td>
         
        </tr>
        <? 
		$tot=$tot+$row[total_amt];		
		$CMstatus = find_a_field('item_info','commission_status','item_id='.$row[item_id]);
		
		if($CMstatus=='1'){
					$dealeromission=$commissionGET;				
				} else { 
				    $dealeromission=0;	
				}
				$comcal=(($row[total_amt]*100/104)/100)*$dealeromission;
				$comissionGETS=$comissionGETS+$comcal;
		}?>
        <tr style="border-bottom:#FFFFFF">
          <td colspan="4" align="left" valign="middle"><strong>Total</strong>&nbsp;</td>
          <td align="center" valign="middle"><strong>
            <?=$to_ctn?>
          </strong></td>
          <td align="center" valign="middle"><strong>
            <?=$to_pcs?>
          </strong></td>
          
          <td align="right" valign="middle"><strong><? echo  number_format($comtotalcal,2);?></strong></td>
          <td align="right" valign="middle"><strong>
            <?=number_format($tot,2)?>
            </strong></td>
        </tr>
       
       
        <tr>
          <td colspan="7" align="left" valign="middle"><strong>Cash Discount :&nbsp;</strong></td>
          <td align="right" valign="middle"><strong><? echo  number_format($tot_sales_cash_discount,2);?></strong></td>
        </tr>
        <?php
		$commissionGET = find_a_field('sale_do_master','commission','do_no='.$do_no); 
		if($commissionGET>0.00){
		?>
        
        <tr>
          <td colspan="7" align="left" valign="middle"><strong>Commission :&nbsp;</strong></td>
          <td align="right" valign="middle"><strong><?=number_format($comissionGETS,2);  ?></strong></td>
        </tr>
        <?php } ?>
        
        
        
        <?php
		$transport_cost = find_a_field('sale_do_master','transport_cost','do_no='.$do_no); 
		if($transport_cost>0.00){
			
			//echo transport cost start from here;
		?>
        
        <tr>
          <td colspan="7" align="left" valign="middle"><strong>Transport Cost (Paid by Distributor) :&nbsp;</strong></td>
          <td align="right" valign="middle"><strong><?=number_format($transport_cost,2);  ?></strong></td>
        </tr>
        <?php } ?>
        
        
        
        
        
        
        <tr>
          <td colspan="7" align="left" valign="middle"><strong> Net Payable Amount :&nbsp;</strong></td>
          <td align="right" valign="middle"><strong>
            <?=$ttal=number_format(($tot-$tot_sales_cash_discount-$comissionGETS-$transport_cost),2)?>
          </strong></td>
        </tr>
        <tr>
          <td colspan="10" align="left" valign="middle">In Word:<strong> Taka 
            <?		
				$gnet_tot=($tot-$tot_sales_cash_discount-$comissionGETS-$transport_cost);				
				$credit_amt = explode('.',$gnet_tot);				
				
				
				if($credit_amt[0]>0){				
				echo convertNumberToWordsForIndia($credit_amt[0]);}				
				
				if($credit_amt[1]>0){					
				//echo  ' & '.convertNumberToWordsForIndia($credit_amt[1]);
				}				
				echo ' Only'.'<br>';
		?>
        
        
          </strong></td>
          </tr>
    </table>
    <div style="border:1px solid #CCC; width:40%; margin-top:20px">
      <p style="font-size:12px"><u><strong>For Delivary Please Contact</strong></u></p>
      <p style="font-size:12px"><strong>Name:  <?=$dealer->contact_person;?></strong></p>
      <p style="font-size:12px"><strong>Mobile No:  <?=$dealer->contact_number;?></strong></p>
      </div>
      </div>
    </div>
    
    
    </td>
  </tr>
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" style="font-size:12px"><em>Terms and Conditions: </em></td>
        </tr>
        <tr>
          <td colspan="2" style="font-size:12px"><em>1. Payment should be made in advance. </em></td>
        </tr>
        <tr>
          <td colspan="2" style="font-size:12px"><em>2. Payment Mode: DD, TT, Pay Order, RTGS, BEFTN </em></td>
        </tr>
        <tr>
          <td colspan="2" style="font-size:12px"><em>3. Bank Details: <strong>Dutch Bangla Bank Ltd.</strong> A/C Name- <strong>INTERNATIONAL CONSUMER PRODUCTS BANGLADESH LIMITED (RICE),</strong> A/C No.- <strong>11611025866</strong>, Branch- <strong>Gulshan Branch, Dhaka</strong>, Routing No.- <strong>090261725</strong></em> </td>
        </tr>
        <tr>
          <td colspan="2" style="font-size:12px">&nbsp;</td>
        </tr>
        
        <tr>
          <td width="50%"><?php if($remarks!=""){echo "<span style='font-size:10px'>NOTE: " .$remarks."</span>";}?></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center"><div class="footer_left">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30%"><div align="center">
                  <p><br />
                    <?php echo $entry_by->fname; ?><br />
                    Prepared By</p>
                </div></td>
                <td width="36%" align="center"><p>Received By<br />
                  (Carrier)<br />
                  (Signature)</p></td>
                <td width="34%" align="center"><p>Received &amp; Confirmed By<br />
                  (Distributor)<br />
                  (Seal &amp; Signature)</p></td>
              </tr>
            </table>
          </div>            </td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" style="border:1px solid #CCC; color: #666; font-size: 12px;">
    <tr>
    <td align="center" >
      
      <p>
<?=$_SESSION['company_name']?>
        <br />
        
        <?php
		$widdd = $_SESSION['user']['depot'];

		if($widdd=='5'){
			echo '118/1, Rakhaliachala, Mowchak, Kaliakair, Gazipur.';
		}
		
		
		
		if($widdd=='12'){
			echo '387, East Padardia, Satarkul Road, Uttar Badda.';
		}
		
		 ?>
		
      <br />
      Tel: +88029860176 | 9860178, <span class="style3"></span><br />
      </p></td>
    </tr>
    </table></td>
        </tr>
      </table>
      <div class="footer1"> </div></td>
  </tr>
</table>

</body>
</html>
