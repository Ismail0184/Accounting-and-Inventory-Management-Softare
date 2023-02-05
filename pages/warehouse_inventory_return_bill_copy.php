<?php

session_start();

require_once 'support_file.php';
require_once 'class.numbertoword.php';

$chalan_no 		= $_REQUEST['v_no'];
$masterdata=find_all_field('purchase_return_master','s','id='.$chalan_no);

$ssql = 'select a.* from vendor a, purchase_return_master b where a.vendor_id=b.vendor_id and b.id='.$chalan_no;
$dealer = find_all_field_sql($ssql);



?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Inventory Return Bill :.</title>
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
                <td width="17%"><p><strong><img src="http://icpbd-erp.com/51816/logo/title.png" width="99%" /></strong></p></td>
<td width="83%" align="left" valign="top"><h2 style="margin-left:33%;margin-top:2%;text-transform:uppercase"><u><strong>Invoice</strong></u></h2></td>
                </tr>
                <tr>
                <td colspan="4">
                <table width="100%" style="border-bottom:1px dotted black; margin-bottom:2%; font-size: 13px;">
                <tr>
                  <td width="74%">Vendor Name: <?php echo $dealer->vendor_name.'-'.$dealer->vendor_id.'';?></td>
                  <td width="26%">Invoice No : <strong><?php echo $chalan_no;?></strong></td>
                </tr>
  <tr>
    <td>Address: <?php echo $dealer->address?></td>
    <td>Invoice Date: 
      <?=$masterdata->return_date;?></td>
  </tr>
  <tr>
    <td>Contact Person: <?php echo $dealer->contact_person_name;?></td>
    <td>Ref. No: <?=$masterdata->ref_no;?></td>
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
          <td width="20"   align="center"><strong>SL No.</strong></td>
          <td   align="center"><strong>Item description</strong></td>
          
          <td  width="57" align="center" bgcolor="#FFFFFF"><strong>Pcs/Ctn</strong></td>
          <td  width="78"   align="center"><strong>Trade Price / Pcs</strong></td>
          <td  align="center"><strong>Rate</strong></td>
          <td  align="center"><strong>Qty</strong></td>
          <td  width="60" align="center" bgcolor="#FFFFFF"><strong>Invoice Value</strong></td>
        </tr>


          <?php
          $res=mysql_query("SELECT d.*,i.* from 

purchase_return_details d,
item_info i 

where 
d.item_id=i.item_id and 
d.m_id=".$chalan_no."
");
          while($row=mysql_fetch_array($res)){ ?>

<tr>
<td><?=$i=$i+1;?></td>
<td><?=$row[item_name];?></td>
<td style="text-align: center"><?=$row[unit_name];?></td>
<td style="text-align: center"><?=$row[pack_size];?></td>
    <td style="text-align: center"><?=$row[rate];?></td>
    <td style="text-align: center"><?=$row[qty];?></td>
    <td style="text-align: center"><?=$row[amount];?></td>
</tr>
          <?php

              $tot=$tot+$row[amount];
          } ?>
        
        
        

        <tr style="border-bottom:#FFFFFF">
          <td colspan="6" align="left" valign="middle"><strong>Total</strong>&nbsp;</td>
          <td align="right" valign="middle"><strong>
            <?=number_format($tot,2)?>
            </strong></td>
        </tr>
       
       
        <tr>
          <td colspan="6" align="left" valign="middle"><strong>Cash Discount :&nbsp;</strong></td>
          <td align="right" valign="middle"><strong><? echo  number_format($tot_sales_cash_discount,2);?></strong></td>
        </tr>

        
        <tr>
          <td colspan="6" align="left" valign="middle"><strong> Net Payable Amount :&nbsp;</strong></td>
          <td align="right" valign="middle"><strong>
            <?=number_format(($tot-$tot_sales_cash_discount-$commission_amountGET),2)?>
          </strong></td>
        </tr>
        <tr>
          <td colspan="7" align="left" valign="middle">In Word:<strong> Taka

          </strong></td>
          </tr>
    </table>
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
          <td colspan="2" style="font-size:12px"><em>3. Bank Details: <strong>NRB Bank Ltd.</strong> A/C Name- <strong>INTERNATIONAL CONSUMER PRODUCTS BANGLADESH LIMITED,</strong> A/C No.- <strong>1012 0100 83018</strong>, Branch- <strong>Principal Branch</strong>, Routing No.- <strong>290260218</strong></em> </td>
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
