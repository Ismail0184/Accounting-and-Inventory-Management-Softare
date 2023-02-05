<?php
require_once 'support_file.php';
$table_master='purchase_master';
$table_purchase_invoice='purchase_invoice';
$table_purchase_receive='purchase_receive';
$pr_no 		= $_REQUEST['v_no'];
$datas=find_all_field('purchase_receive','s','pr_no='.$pr_no);

$sql1="select b.*,i.item_name,i.unit_name from purchase_receive b,item_info i where b.item_id=i.item_id and b.pr_no = '".$pr_no."'";
$data1=mysqli_query($conn, $sql1);
$pi=0;
$total=0;
while($info=mysqli_fetch_object($data1)){
$pi++;
$rec_date=$info->rec_date;
$rec_no=$info->rec_no;
$po_no=$info->po_no;
$order_no[]=$info->order_no;
$ch_no=$info->ch_no;
$qc_by=$info->qc_by;
$entry_at=$info->entry_at;
$item_id[] = $info->item_id;
$order_no[]=$info->order_no;
$rate[] = $info->rate;
$amount[] = $info->amount;
$unit_qty[] = $info->qty;
$unit_name[] = $info->unit_name;
$labor_bill= $info->labor_bill;
$transport_bill = $info->transport_bill;
$others_bill=$info->others_bill;
$tax = $info->tax;
$taxait = $info->tax_ait;

}

$ssql = 'select a.*,b.commission as vendor_commission from vendor a, purchase_master b where a.vendor_id=b.vendor_id and b.po_no='.$po_no;
$dealer = find_all_field_sql($ssql);
$asf = find_a_field('purchase_master','asf','po_no='.$po_no);
$commission_amount=find_a_field(''.$table_purchase_invoice.'','SUM(commission_amount)','po_no='.$po_no);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Cash Memo :.</title>
<script type="text/javascript">
function hide()
{
    document.getElementById("pr").style.display="none";
}
</script>

<style type="text/css">
<!--
.style11 {
	font-size: 16px;
	font-weight: bold;
}
.style14 {font-weight: bold}
.style12 {
	font-size: 16px;
	font-weight: normal;
}
.style4 {	font-size: 18px;
	color: #000000;
}
.style6 {font-size: 10px}
.style15 {
	color: #FF0000;
	font-weight: bold;
}
.style16 {color: #336600}
-->
</style>

</head>

<body style="font-family:Tahoma, Geneva, sans-serif">
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
		  <tr>
	    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table  width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
                  <tr>
                    <td bgcolor="#FFFFCC" style="text-align:center; color:#000000; font-size:14px; font-weight:bold;"><span class="style4"><?=$_SESSION['company_name']?><br />
                          <span class="style6"><?=$_SESSION['company_address']?></span></span></td>
                  </tr></td>
              </tr>

              <tr>
                <td height="19">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
	    </tr>

  <tr>
    <td><div class="header">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
				<table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
      <tr>
        <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">GOODS RECEIVE NOTE </td>
      </tr>
    </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
	    </tr>
	  <tr>
	    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td valign="top">
		      <table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
		        <tr>
		          <td width="40%" align="right" valign="middle">Vendor Company: </td>
		          <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
		            <tr>
		              <td><?php echo $dealer->vendor_name;?>&nbsp;</td>
		              </tr>
		            </table></td>
		          </tr>
		        <tr>
		          <td align="right" valign="top"> Address:</td>
		          <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
		            <tr>
		              <td height="60" valign="top"><?php echo $dealer->address;?>&nbsp;</td>
		              </tr>
		            </table></td>
		          </tr>

		        <tr>
		          <td align="right" valign="middle">GR Posting Time  :</td>
		          <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
		            <tr>
		              <td><?php echo $entry_at;?></td>
		              </tr>
		            </table></td>
		          </tr>

				  <tr>
		          <td align="right" valign="middle">GR Rec No :</td>
		          <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
		            <tr>
		              <td><?php echo $rec_no;?></td>
		              </tr>
		            </table></td>
		          </tr>
		        </table>		      </td>
			<td width="30%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
			  <tr>
                <td align="right" valign="middle">GR No:</td>
			    <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                    <tr>
                      <td><strong><?php echo $pr_no;?></strong>&nbsp;</td>
                    </tr>
                </table></td>
			    </tr>

                <tr>
                <td align="right" valign="middle"> REC Date</td>
			    <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                    <tr>
                      <td><?=$rec_date?>
                        &nbsp;</td>
                    </tr>
                </table></td>
			    </tr>
			  <tr>
			    <td align="right" valign="middle">PO No: </td>
			    <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
			      <tr>
			        <td><?php echo $po_no;?></td>
			        </tr>
			      </table></td>
			    </tr>
			  <tr>
                <td align="right" valign="middle">QC By :</td>
			    <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                    <tr>
                      <td><?php echo $qc_by;?>&nbsp;</td>
                    </tr>
                </table></td>
			    </tr>

			  <tr>
                <td align="right" valign="middle">Chalan No  :</td>
			    <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                    <tr>
                      <td><strong><?php echo $ch_no;?></strong></td>
                    </tr>
                </table></td>
			    </tr>
			  </table></td>
		  </tr>
		</table>		</td>
	  </tr>
    </table>
    </div></td>
  </tr>

  <tr>
	<td>	</td>
  </tr>

  <tr>
    <td>
      <div id="pr">
  <div align="left">
<input name="button" type="button" onclick="hide();window.print();" value="Print" />
  </div>
</div>
<table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="5">
       <tr style="background-color:#CCCCCC">
        <th>SL</th>
        <th>Code</th>
        <th>Product Name</th>
        <th>Unit</th>
        <th>Rate</th>
        <th>Rec Qty</th>
        <th>Amount</th>
        </tr>

<? for($i=0;$i<$pi;$i++){?>
      <tr>
        <td align="center" valign="top"><?=$i+1?></td>
        <td align="left" valign="top"><?=$item_id[$i]?></td>
        <td align="left" valign="top"><strong><?=find_a_field('item_info','item_name','item_id='.$item_id[$i]);?> # </strong><?=find_a_field('purchase_invoice','item_details','po_no="'.$po_no.'" and id='.$order_no[$i]);?></td>
        <td align="right" valign="top"><?=$unit_name[$i]?></td>
        <td align="right" valign="top"><?=$rate[$i]?></td>
        <td align="right" valign="top"><?=$unit_qty[$i]?></td>
        <td align="right" valign="top"><?=number_format($amount[$i],2); $t_amount = $t_amount + $amount[$i];?></td>
        </tr>

<? }?>
<tr>
  <td colspan="6" align="center" valign="top"><div align="right"><strong>Total Amount: </strong></div></td>
        <td align="right" valign="top"><span class="style1">
          <?=number_format($t_amount,2);?>
        </span></td></tr>
         <?
   if ($labor_bill >0){?>
   <tr>
    <td colspan="6" align="right" valign="top"><strong>labour Bill</strong></td>
    <td align="right" valign="top"><strong>
      <?=$labor_bill?>
    </strong></td></tr>
    </tr>
    <?php } ?>
    
    
    <? if ($transport_bill >0){?> 
  <tr>
    <td colspan="6" align="right" valign="top"><strong>Transport Bill</strong></td>
    <td align="right" valign="top"><strong>
      <?=$transport_bill?>
    </strong></td></tr>
    <?php } ?>
    
    
	
	<? if ($others_bill >0){?> 
  <tr>
    <td colspan="6" align="right" valign="top"><strong>Others Bill</strong></td>
    <td align="right" valign="top"><strong>
      <?=$others_bill?>
    </strong></td></tr>
  <?php } ?>
  
  
  <? if ($asf >0){?>
  <tr>
    <td colspan="6" align="right" valign="top"><strong>ASF(
        <?php echo $asf ;?>
%)</strong></td>
    <td align="right" valign="top"><strong>
      <?php $with_asf=(($t_amount)*($asf))/(100); echo number_format($with_asf,2)?>
    </strong></td></tr>
 <?php } ?>
 
 
 <? 
  $subtotalafterasf=$t_amount+$with_asf;
  if ($asf >0){?> 
  
  <tr>
    <td colspan="6" align="right" valign="top"><strong>SUB - TOTAL</strong></td>
    <td align="right" valign="top"><strong>
      <?php echo number_format($subtotalafterasf,2)?>
    </strong></td></tr>
 <?php } ?>
 
  
  
  <? if ($tax >0){?> 
  
  <tr>
    <td colspan="6" align="right" valign="top"><strong>VAT(
        <?php echo $tax ;?>
%)</strong></td>
    <td align="right" valign="top"><strong>
      <?php $with_tax=(($subtotalafterasf)*($tax))/(100); echo number_format($with_tax,2)?>
    </strong></td></tr>
 <?php } ?>
 
 
 
 
 <? if ($taxait >0){?> 
  
  <tr>
    <td colspan="6" align="right" valign="top"><strong>Tax(
        <?php echo $tax ;?>
%)</strong></td>
    <td align="right" valign="top"><strong>
      <?php $with_taxait=(($t_amount)*($taxait))/(100); echo number_format($with_taxait,2)?>
    </strong></td></tr>
 <?php } ?>




    <?php if ($dealer->vendor_commission>0){?>
    <tr>
        <td colspan="6" align="right" valign="top"><strong>Commission:</strong></td>
        <td align="right" valign="top"><?=$commission_amount?></td></tr>
    </tr>
    <?php } ?>
 
 
  
  <tr>
    <td colspan="6" align="right" valign="top"><strong>Net Payable Amount:</strong></td>
    <td align="right" valign="top"><strong>
      <?=number_format(($t_amount+$with_tax+$labor_bill+$others_bill+$transport_bill+$with_asf+$with_taxait-$commission_amount),2)?>
    </strong></td></tr>


    </table></td>

  </tr>

  <tr>
    <td align="center">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" style="font-size:12px"><em>All goods are received in a good condition as per Terms</em></td>
    </tr>
  <tr>
    <td width="50%">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>


  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>


  <tr>
    <td colspan="2" align="center"><strong><br />
      </strong>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div align="center">Received By </div></td>
          <td><div align="center">Quality Controller </div></td>
          <td><div align="center">Store Incharge </div></td>
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

