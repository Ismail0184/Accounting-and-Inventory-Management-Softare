<?php

require_once 'support_file.php';

$po_no 		= $_REQUEST['po_no'];
$datas=find_all_field('purchase_receive','s','po_no='.$po_no);
$sql1="select b.* from purchase_receive b where b.po_no = '".$po_no."'";
$data1=mysql_query($sql1);
$pi=0;
$total=0;
while($info=mysql_fetch_object($data1)){ 
$pi++;
$rec_date=$info->rec_date;
$rec_no=$info->rec_no;
$remarks=$info->Remarks;
$po_no=$info->po_no;
$order_no[]=$info->order_no;
$ch_no=$info->ch_no;
$VATch_no=$info->VAT_challan;
$qc_by=$info->qc_by;

if($info->rcv_Date=='0000-00-00'){
$entry_at=$info->entry_at;} else {
$entry_at=$info->rcv_Date;
}

$entry_by=$info->entry_by;
$item_id[] = $info->item_id;
$rate[] = $info->rate;
$order_no[] = $info->order_no;
$amount[] = $info->amount;
$unit_qty[] = $info->qty;
$unit_name[] = $info->unit_name;
$labor_bill= $info->labor_bill;
$transport_bill = $info->transport_bill;
$others_bill=$info->others_bill;
$tax = $info->tax;

}

$ssql = 'select a.* from vendor a, purchase_master b where a.vendor_id=b.vendor_id and b.po_no='.$po_no;
$dealer = find_all_field_sql($ssql);
$asf = find_a_field('purchase_master','asf','po_no='.$po_no);
$cash_discount = find_a_field('purchase_master','cash_discount','po_no='.$po_no);


if(isset($_GET[delete_id])){
    mysql_query("Delete from grn_report_view where grn_no='".$_GET[delete_id]."'"); ?>
    <meta http-equiv="refresh" content="0;grn_list_view.php">
<?php } ?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Bill Wise GRN SUMMERY :.</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">
function hide()
{    document.getElementById("pr").style.display="none";
}
</script>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
<style type="text/css">

<!--


.style11 {
	font-size: 16px;
	font-weight: bold;
}


.style14 {font-weight: bold}
.style12 {
	font-size: 16px;
	font-weight: normal;}


.style4 {	font-size: 18px;
	color: #000000;}
.style6 {font-size: 10px}
.style15 {
	color: #FF0000;
	font-weight: bold;}
.style16 {color: #336600}
-->


</style>


</head>


<body style="font-family:Tahoma, Geneva, sans-serif">


<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">


    <tr>


        <td><div class="header">


                <table width="100%" border="0" cellspacing="0" cellpadding="0">


                    <tr>


                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">


                                <tr>


                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">


                                            <tr>


                                                <td>
                                                    <table  width="80%" border="0" align="center" cellpadding="3" cellspacing="0">


                                                        <tr>


                                                            <td bgcolor="#FFFFCC" style="text-align:center; color:#000000; font-size:14px; font-weight:bold;"><p class="style4">INTERNATIONAL <br />
                                                                    <span class="style12">Consumer Products Bangladesh Ltd.</span></p>
                                                                <p class="style6">389/B, West Rampura, MG Tower (11th Floor), Dhaka-1219, Bangladesh.</p></td>


                                                        </tr>

                                                        <tr width="80%">
                                                            <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">BILL WISE GRN SUMMERY </td>
                                                        </tr>



                                                    </table>
                                                    <table  width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
                                                    </table>
                                                </td>
                                            </tr>




  


  <tr>


    <td>


      <div id="pr">


  <div align="left">

      <form action="grn_list_view.php" method="post" >

          <?php
          if(isset($_POST[grnadd])){
              if($_POST[grnno]>0){
                  mysql_query("INSERT INTO grn_report_view (grn_no,status,sl) VALUES ('$_POST[grnno]','MANUAL','$_POST[sl]')");
              }}

          if(isset($_POST[deleteall])){
              mysql_query("DELETE from grn_report_view");
          }

          if(isset($_POST[submitall])){
              mysql_query("Update grn_report_view set status='CONFIRM'");
          }



          ?>

      </form>
<input name="button" type="button" onClick="hide();window.print();" value="Print" />


  </div>


</div>

        <br><br>


        <table width="100%" class="tabledesign" border="1" bordercolor="#000000" style="font-size: 12px" cellspacing="0" cellpadding="5">
        <tr>
        <td align="center" bgcolor="#CCCCCC"><strong>SL</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Item Code</strong></td>
        <td align="center" bgcolor="#CCCCCC"><div align="center"><strong>Item Description</strong></div></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Unit</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>GRN</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>PO</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Challan</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>VAT Challan</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Rate</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>GRN Qty</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Amount</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>VAT %</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>VAT Amount</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Others</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Discount</strong></td>
        <td align="center" bgcolor="#CCCCCC"><strong>Amount Total</strong></td>
        </tr>


       


<? $res=mysql_query("Select * from grn_report_view order by sl,grn_no");
while($grnrow=mysql_fetch_array($res)){

    $grnamount=find_a_field('purchase_receive','SUM(amount)','pr_no="'.$grnrow[grn_no].'"');
    $transport=find_a_field('purchase_receive','distinct transport_bill','pr_no="'.$grnrow[grn_no].'"');
    $labor_bill=find_a_field('purchase_receive','distinct labor_bill','pr_no="'.$grnrow[grn_no].'"');
	
    $othersamount=$transport+$labor_bill;
?>

    <? $ress=mysql_query("Select r.*,i.* from purchase_receive r, item_info i where r.pr_no=".$grnrow[grn_no]." and 
     r.item_id=i.item_id
     order by id");
    while($grnresult=mysql_fetch_array($ress)){
		$cash_discount = find_a_field('purchase_master','cash_discount','po_no='.$grnresult[po_no]);
        ?>

        <tr>
            <td align="center" valign="top"><?=$i=$i+1?></td>
            <td align="center" valign="top"><?=$grnresult[finish_goods_code]?></td>
            <td align="left" valign="top"><?=$grnresult[item_name]?></td>
            <td align="center" valign="top"><?=$grnresult[unit_name]?></td>
            <td align="center" valign="top"><?=$grnrow[grn_no]?></td>
            <td align="center" valign="top"><?=$grnresult[po_no]?></td>
            <td align="center" valign="top"><?=$grnresult[ch_no]?></td>
            <td align="center" valign="top"><?=$grnresult[VAT_challan]?></td>
            <td align="right" valign="top"><?=number_format($grnresult[rate],2)?></td>
            <td align="right" valign="top"><?=number_format($grnresult[qty],2)?></td>
            <td align="right" valign="top"><?=number_format($grnresult[amount],2);?></td>
            <td align="right" valign="top"><?=number_format($grnresult[tax],2)?></td>
            <td align="right" valign="top"><?=number_format($vats=$grnresult[amount]*$grnresult[tax]/100,2)?></td>
            <td align="right" valign="top"><?=number_format(($otherscal=$othersamount/$grnamount)*$grnresult[amount],2);?></td>
            <td align="right" valign="top"><?=number_format($cash_discount,2)?></td>
            <td align="right" valign="top"><?=number_format($amounttotal=$grnresult[amount],2)?></td>

        </tr>


<?
    $total_qty=$total_qty+$grnresult[qty];
    $vatamount=$vatamount+$vats;
    $totalamount=$totalamount+$amounttotal;
    }
    $otherscals=$otherscals+$othersamount;
	$tdiscount=$tdiscount+$cash_discount;
}?>


  <tr>
    <td colspan="9" align="right" valign="top"><strong>Total Qty:</strong></td>
      <td  align="right" valign="top"><strong><?=$total_qty;?></strong></td>
      <td colspan="5" align="right" valign="top"><strong>Total Amount:</strong></td>
    <td align="right" valign="top"><span class="style1">
      <?=number_format($totalamount,2);?>
    </span></td></tr>
 
  
  
  <tr>
    <td colspan="15" align="right" valign="top"><strong>Total VAT Amount</strong></td>
    <td align="right" valign="top"><strong>
      <?php  echo number_format($vatamount,2)?>
    </strong></td></tr>
    
    <tr>
    <td colspan="15" align="right" valign="top"><strong>Toal Payable Amount</strong></td>
    <td align="right" valign="top"><strong>
      <?php  echo number_format($totalamount+$vatamount,2)?>
    </strong></td></tr>



  
  <tr>
    <td colspan="15" align="right" valign="top"><strong>Others (Transport+Labour)</strong></td>
    <td align="right" valign="top"><strong><?=number_format($otherscals,2);?></strong></td>
  </tr>

<tr>
    <td colspan="15" align="right" valign="top"><strong>Discount</strong></td>
    <td align="right" valign="top"><strong>
      <?php  echo number_format($tdiscount,2)?>
    </strong></td></tr>
  
   <tr>
    <td colspan="15" align="right" valign="top"><strong>Net Payable Amount</strong></td>
    <td align="right" valign="top"><strong>
    <?php $tpa=$totalamount+$vatamount+$otherscals-$tdiscount; echo number_format($tpa,2);?>
    </strong></td></tr>


  
  



    </table></td>


  </tr>





        <tr>


            <td align="center">


                <table width="100%" border="0" style="font-size: 12px" cellspacing="0" cellpadding="0">
                    <tr><td colspan="2" style="font-size:12px"><em>All goods are received in a good condition as per Terms</em></td></tr>
                    <tr><td colspan="2" style="font-size:12px; color:red; font-weight: bold"><em>Office Copy & Confidential Office Use Only</em></td> </tr>
                    <tr><td width="50%">&nbsp;</td><td>&nbsp;</td></tr>


                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>


                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>


                    <tr>
                        <td colspan="2" align="center">
                            <table width="100%" border="0" style="font-size: 12px" cellspacing="0" cellpadding="0">
                            
                            <tr>
                                
                                    <td width="25%"><div align="center" style=""></div></td>
                                    <td width="25%"><div align="center" style="">Sadek Ali</div></td>
                                    <td width="25%"><div align="center" style="">Saiful Islam </div></td>
                                    <td width="25%"></td>
                                </tr>
                                
                                <tr>
                                
                                    <td width="25%"><div align="center" style="text-decoration: overline">Prepared By </div></td>
                                    <td width="25%"><div align="center" style="text-decoration: overline">Checked By </div></td>
                                    <td width="25%"><div align="center" style="text-decoration: overline">Quality Controller </div></td>
                                    <td width="25%"><div align="center" style="text-decoration: overline">Authorized By </div></td>
                                </tr>
                            </table></td>
                    </tr>


                    <tr>


                        <td>&nbsp;</td>


                        <td>&nbsp;</td>


                    </tr>


                </table>


                <div class="footer1"> </div>


            </td>


        </tr>


    </table>


</body>


</html>
