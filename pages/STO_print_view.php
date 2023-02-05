<?php
 require_once 'support_file.php'; 
 $title='Production Report';
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
                              <td style="text-align:center; font-size:18px; font-weight:bold;">
							  <?php $wid=getSVALUE("production_issue_master", "warehouse_from", "where  pi_no='$_GET[pino]'");
							  echo $from=getSVALUE("warehouse", "warehouse_name", "where  warehouse_id='$wid'");?>
                              <p style="font-size:12px; font-weight:normal"><?=getSVALUE("warehouse", "address", "where warehouse_id='$wid'");?></p></td>
                            </tr>
                            <tr>
                              
							  
							  
							  
							  
                            </tr>
                            
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><span class="style1">Delivery Challan</span></td>
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
                        <th  align="left" valign="middle" style="width:25%">To</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td ><?php 
						$widto=getSVALUE("production_issue_master", "warehouse_to", "where  pi_no='$_GET[pino]'");?>
						<?=$from=getSVALUE("warehouse", "warehouse_name", "where  warehouse_id='$widto'");?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">Address</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("warehouse", "address", "where warehouse_id='$widto'");?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="top">VAT Reg. No</td>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("warehouse", "VAT", "where warehouse_id='$widto'");?></td>
                      </tr></table></td>
                      
                  <td width="40%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <th width="48%" align="left" valign="middle">STO No</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td width="52%"><?=getSVALUE("production_issue_master", "custom_pi_no", "where  pi_no='$_GET[pino]'");?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">STO Date</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("production_issue_master", "pi_date", "where  pi_no='$_GET[pino]'");?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">Challan & Date</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("production_issue_master", "VATChallanno", "where  pi_no='$_GET[pino]'");?></td>
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
          <td align="center" bgcolor="#FFFFFF"><strong>FG. Code</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>FG Description</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>UOM</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Batch</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Qty in Case</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Qty in Pcs</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Exp. Date</strong></td>
        </tr>
        <? 
$g_s_date=date('Y-01-01');

$g_e_date=date('Y-12-31');



$res = mysql_query("Select * from production_issue_detail where pi_no='$_GET[pino]'");

while($data=mysql_fetch_array($res)){
	$pks=getSVALUE("item_info", "pack_size", "where  item_id='$data[item_id]'");
	$i=$i+1;

?>
        <tr>
          <td align="center" valign="top"><?=$i?></td>
          <td align="left" valign="top"><?=$finish_goods_code=getSVALUE("item_info", "finish_goods_code", "where  item_id='$data[item_id]'");?></td>
          <td align="left" valign="top"><?=$item_name=getSVALUE("item_info", "item_name", "where  item_id='$data[item_id]'");?></td>
          <td align="center" valign="top"><?=$unit_name=getSVALUE("item_info", "unit_name", "where  item_id='$data[item_id]'");?></td>
          <td align="center" valign="top"><?=$data[batch]?></td>
          <td align="center" valign="top"><?=$data[total_unit]/$pks;?></td>
          <td align="center" valign="top"><?=$data[total_unit]?></td>
          <td align="center" valign="top"><?=$data[i]?></td>
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
                <td>&nbsp;</td>
                <td>&nbsp;</td>
               
              </tr>
              
              <tr>
                <td align="center" style="text-decoration:overline"><strong>Authorized Signatory</strong></td>
               
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
