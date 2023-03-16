<?php
 require_once 'support_file.php'; 
 $title='Production Report';
$MAN_QUERY=find_all_field('MAN_master','','id="'.$_GET[id].'"');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Print Preview :.</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

</script>
<style type="text/css">

<!--

.style1 {color: #000000}

-->

</style>
</head>
<body style="font-family:Tahoma, Geneva, sans-serif">
<br />
<br />
<br />
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div class="header">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><?=$userRow['company_name']?></td>
                            </tr>
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;">CMU / Warehouse: <?=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='$MAN_QUERY->warehouse_id'");?></td>
                            </tr>
                            
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><span class="style1">MAN Details
</span></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr><td style="height:30px"></td></tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">

                      <tr>
                        <th align="left" valign="top">Date</td>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=$MAN_QUERY->man_date;?></td>
                      </tr>
                      
                      <tr>
                        <th align="left" valign="top"> MAN No</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=$MAN_QUERY->MAN_ID;?></td>
                      </tr>

                          <tr>
                              <th align="left" valign="top"> Vandor</th>
                              <td  align="left" valign="middle" style="width:2%">: </td>
                              <td><?=getSVALUE("vendor", "vendor_name", "where  vendor_id='".$MAN_QUERY->vendor_code."'");?></td>
                          </tr>


                          <tr>
                              <th align="left" valign="top"> Entry By</th>
                              <td  align="left" valign="middle" style="width:2%">: </td>
                              <td><?=find_a_field('users','fname','user_id="'.$MAN_QUERY->entry_by.'"')?></td>
                          </tr>
                      
                    </table></td>
                  <td width="40%" valign="top">
                      <table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <th width="48%" align="left" valign="middle">Delivary Challan No</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td width="52%"><?=$MAN_QUERY->delivary_challan;?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">D. Challan Date</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=$MAN_QUERY->delivary_challan_Date;?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">VAT No</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=$MAN_QUERY->VAT_challan;?></td>
                      </tr>

                          <tr>
                              <th align="left" valign="middle">VAT Date</th>
                              <td  align="left" valign="middle" style="width:2%">: </td>
                              <td><?=$MAN_QUERY->VAT_challan_Date;?></td>
                          </tr>
                      
                    </table></td>
                </tr>
              </table></td>
          </tr>
        </table>
      </div></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td><div id="pr">
        <div align="left">
          <form id="form1" name="form1" method="post" action="">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
      <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="2" style="font-size:11px; border-collapse:collapse">
        <tr>
          <td align="center" bgcolor="#FFFFFF"><strong>SL</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Mat. Code</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Material Description</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>UOM</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Qty</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>PO NO</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Remarks</strong></td>
        </tr>
        <?




$res = mysqli_query($conn, "Select d.*,i.* 
 from  
  MAN_details d,
  item_info i
   where 
   i.item_id=d.item_id and    
   d.m_id=".$_GET[id]);

while($data=mysqli_fetch_object($res)){
	$i=$i+1;

?>
        <tr>
          <td align="center" valign="top"><?=$i?></td>
          <td align="left" valign="top"><?=$data->finish_goods_code;?></td>
          <td align="left" valign="top"><?=$data->item_name;?></td>
          <td align="center" valign="top"><?=$data->unit_name;?></td>
          <td align="center" valign="top"><?=$data->qty?></td>
          <td align="center" valign="top"><?=$data->po_no?></td>
          <td align="center" valign="top"><?=$MAN_QUERY->remarks?></td>
        </tr>
        <? }?>
      </table></td>
  </tr>
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        
        
        <tr>
          <td colspan="2" align="center">
           
           
          
             <table style="font-size:11px; margin-top:50px" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" style="">
				<?=find_a_field('users','fname','user_id='.$MAN_QUERY->entry_by);?><br />(<?=$MAN_QUERY->entry_at?>)</em><br />
</td>
                <td align="center" style="">
				<?=find_a_field('users','fname','user_id='.$MAN_QUERY->cehck_by);?><br />(<?=$MAN_QUERY->cehck_at?>)</em><br />
</td>
				<td align="center" style="">
				<?=find_a_field('users','fname','user_id='.$MAN_QUERY->VERIFIED_BY);?><br />(<?=$MAN_QUERY->VERIFIED_at?>)</em><br />
</td>
               
              </tr>
              
              <tr>
                <td align="center" style="text-decoration:overline"><strong>Prepared By</strong></td>
                <td align="center" style="text-decoration:overline"><strong>Checked By </strong></td>
                <td align="center" style="text-decoration:overline"><strong>Authorised By </strong></td>
                
              </tr>
            </table>
            <strong><br />
            </strong></td>
        </tr>
        
      </table>
    <div class="footer1"> </div></td>
  </tr>
</table>
</body>
</html>
