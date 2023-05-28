<?php
require_once 'support_file.php';
require_once 'class.numbertoword.php';

$chalan_no 		= find_a_field('sale_do_chalan','chalan_no','do_no='.$_GET['do_no']);

$challan= find_all_field('sale_do_chalan','','chalan_no='.$chalan_no);
$dealer_category = find_a_field('dealer_info','dealer_category','dealer_code='.$challan->dealer_code);

if($dealer_category!=='3'){ echo '<h5 style="text-align: center">opps!! You do not have permission to view this page. The administrator has been notified that you attempted to view an unauthorized page. </h5>';} else {



$sql1=mysqli_query($conn, "select d.*,b.*, b.sales_commission, sum(b.total_unit) as total_unit, d.total_unit as ord_unit, sum(b.total_amt) as total_amt, j.item_ex as item_ex, m.depot_id as depot, m.remarks
from sale_do_chalan b,sale_do_details d, journal_item j, sale_do_master m
where d.id=b.order_no and j.sr_no='".$chalan_no."' and  j.item_id=d.item_id and m.do_no=d.do_no and b.chalan_no = '".$chalan_no."' and (b.item_id!=1096000100010239 and b.item_id!=1096000100010312) group by b.order_no order by d.id");
$pi=0;
$total=0;
while($info=mysqli_fetch_object($sql1)){

$pi++;
$depot_id=$info->depot;
$remarks=$info->remarks;
$entry_time=$info->entry_time;
$chalan_date=$info->chalan_date;
$do_no=$info->do_no;
$commissionGET = find_a_field('sale_do_master','commission','do_no='.$_GET['do_no']);
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
$to_ctn = find_a_field('sale_do_chalan','sum(pkt_unit)','chalan_no='.$chalan_no);
$to_pcs = find_a_field('sale_do_chalan','sum(dist_unit)','chalan_no='.$chalan_no);
}
$entry_sql = 'select u.fname from users u, sale_do_master b where u.user_id=b.entry_by and b.do_no='.$_GET[do_no];
$entry_by = find_all_field_sql($entry_sql);
$ssql = 'select a.* from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$_GET[do_no];
$dealer = find_all_field_sql($ssql);
$ssql = 'select b.* from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$_GET[do_no];
$do = find_all_field_sql($ssql);
$ssqld = 'select a.* from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$_GET[do_no];
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
                <td width="17%"><p><strong><img src="../assets/images/icon/title.png" width="99%" /></strong></p></td>
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
                  <td width="20"  align="center"><strong>SL</strong></td>
                  <td width="91"  align="center"><strong>FG description</strong></td>
                  <td width="57" align="center" bgcolor="#FFFFFF"><strong>UoM</strong></td>
                  <td width="78"  align="center"><strong>Trade Price</strong></td>
                  <td align="center"><strong>Invoice Rate</strong></td>
                  <td align="center"><strong>Invoice Qty</strong></td>
                  <td width="92" align="center" bgcolor="#FFFFFF"><strong>Total Cash Discount</strong> </td>
                  <td width="60" align="center" bgcolor="#FFFFFF"><strong>Invoice Value</strong></td>
              </tr>




              <?php



              $go=mysqli_query($conn, "Select do_no from sale_do_chalan where chalan_no='$_GET[do_no]'");
              $donos=mysqli_fetch_array($go);
              $result=mysqli_query($conn, "Select sdd.*,i.* from sale_do_details sdd, item_info i where sdd.do_no='".$_GET[do_no]."' and sdd.item_id=i.item_id and  sdd.item_id not in ('1096000100010312' ,'1096000100010313') order by sdd.id");
              while($row=mysqli_fetch_array($result)){
                  ?>
                  <tr>
                      <td style="width:2%;" align="center" valign="middle"><?=$i=$i+1?></td>
                      <td style="width:auto" align="left" valign="middle"><? echo $row[item_name]; if($row[unit_price]==0)  echo ' <b>[FREE]</b>'; else '';?></td>
                      <td width="5%" align="center" valign="middle"><?=$row[unit_name];?></td>
                      <td width="5%" align="center" valign="middle"><?=($row[t_price]>0)? $row[t_price] : '-';?></td>
                      <td width="8%" align="center" valign="middle"><?=($row[unit_price]>0)? number_format($row[unit_price],2) : '-';?></td>
                      <td width="8%" align="center" valign="middle"><?=$row[dist_unit];?></td>
                      <td width="8%" align="right" valign="middle">
                          <?  if($row[unit_price]>0){ echo $sales_cash_discount = find_a_field('sale_do_details','total_amt*-1','do_no='.$do_no.' and item_id=1096000100010312 and gift_on_item='.$row[item_id]); $tot_sales_cash_discount+=$sales_cash_discount;} ?>
                      </td>
                      <td width="8%" align="right" valign="middle"><?=($row[total_amt]>0)? number_format($row[total_amt],2) : '-';?></td>

                  </tr>
                  <?
                  $tot=$tot+$row[total_amt];
                  $CMstatus = find_a_field('item_info','commission_status','item_id='.$row[item_id]);

                  if($CMstatus=='1'){
                      $dealeromission=$commissionGET;
                  } else {
                      $dealeromission=0;
                  }
                  $comcal=($row[total_amt]/100)*$dealeromission;
                  $comissionGETS=$comissionGETS+$comcal;
									$total_qty=$total_qty+$row[dist_unit];
              }?>
              <tr style="border-bottom:#FFFFFF">
                  <td colspan="5" align="left" valign="middle"><strong>Total</strong>&nbsp;</td>
                  <td align="center" valign="middle"><strong><?=$total_qty?></strong></td>
                  <td align="center" valign="middle"><strong><? echo  number_format($tot_sales_cash_discount,2);?></strong></td>
                  <td align="right" valign="middle"><strong><?=number_format($tot,2)?></strong></td>
              </tr>


              <tr>
                  <td colspan="7" align="left" valign="middle"><strong>Cash Discount :&nbsp;</strong></td>
                  <td align="right" valign="middle"><strong><? echo  number_format($tot_sales_cash_discount,2);?></strong></td>
              </tr>
              <?php
              $commissionGET = find_a_field('sale_do_master','commission','do_no='.$do_no);
              $commission_amountGET = find_a_field('sale_do_master','commission_amount','do_no='.$do_no);
              if($commissionGET>0.00){
                  ?>

                  <tr>
                      <td colspan="7" align="left" valign="middle"><strong>Commission :&nbsp;</strong></td>
                      <td align="right" valign="middle"><strong><?=number_format($commission_amountGET,2);  ?></strong></td>
                  </tr>
              <?php } ?>

              <tr>
                  <td colspan="7" align="left" valign="middle"><strong> Net Payable Amount :&nbsp;</strong></td>
                  <td align="right" valign="middle"><strong>
                          <?=number_format(($tot-$tot_sales_cash_discount-$commission_amountGET),2)?>
                      </strong></td>
              </tr>
              <tr>
                  <td colspan="11" align="left" valign="middle">In Word:<strong> Taka
                          <?
                          $gnet_tot=($tot-$tot_sales_cash_discount-$commission_amountGET);
                          $credit_amt = explode('.',$gnet_tot);
                          if($credit_amt[0]>0){
                              echo convertNumberToWordsForIndia($credit_amt[0]);}
                          if($credit_amt[1]>0){
                              echo  ' & Paisa '.convertNumberToWordsForIndia($credit_amt[1]);}
                          echo ' Only';
                          ?>
                      </strong></td>
              </tr>
          </table>
    </div>
    </td>
  </tr>
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr width="100%" border="0" cellspacing="0" cellpadding="0">
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
                <td colspan="2" style="font-size:12px"><em>3. Bank Details: <strong>A/C Name-</strong> <?=find_a_field('bank_account_name','account_name','id='.$dealer->bank_account)?></em>
                    <?php
                    $res=mysql_query("select ba.*,b.* from bank_account ba,bank b where b.BANK_CODE=ba.BANK_CODE and ba.account_code=".$dealer->bank_account." group by ba.id");
                    while($data=mysql_fetch_object($res)){ ?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="font-size:12px">
                                    <em><strong>Bank-</strong> <?=$data->BANK_NAME?>,<strong> Account No.- </strong><?=$data->account_number?>, <strong>Branch- </strong><?=$data->branch?>, <strong>Routing No.- </strong><?=$data->routing_no?></strong>
                                    </em></td>
                            </tr>
                        </table>
                    <?php } ?>
                </td>
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

          <?=$_SESSION['company_address']?>

      <br />
      Tel: +88029860176 | 9860178, <span class="style3">VAT Reg. No. <?php if($widdd=='5'){ echo '000702484'; }
	  if($widdd=='12'){ echo '000851876'; }
	   ?></span><br />
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
<?php } ?>
