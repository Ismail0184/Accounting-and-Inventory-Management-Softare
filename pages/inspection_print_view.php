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
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><?php echo $userRow[proj_name]; ?></td>
                            </tr>
                            <tr>
                              <td style="text-align:center; font-size:15px; font-weight:bold;">CMU: <?php $cid=getSVALUE("QC_Inspection_Work_Sheet_master", "distinct warehouse_id", "where MAN_ID='$_GET[custom_pr_no]'");?>
                              <?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id=".$cid);?>
                              </td>
                            </tr>
                            
                            <tr>
                              <td style="text-align:center; font-size:15px; font-weight:bold;"><span class="style1">Certificate of Anylasis		
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
                        <th  align="left" valign="middle" style="width:25%">Product Name</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td ><?=getSVALUE("item_info", "finish_goods_code", "where item_id='$_GET[fgid]'");?>#<?=getSVALUE("item_info", "item_name", "where item_id='$_GET[fgid]'");?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">Inspection Lot	</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("QC_Inspection_Work_Sheet_master", "inspection_lot_no", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="top">Mfg Date	</td>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("QC_Inspection_Work_Sheet_master", "mfg", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");?></td>
                      </tr>
                      
                      <tr>
                        <th align="left" valign="top">Exp Date</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("QC_Inspection_Work_Sheet_master", "Exp_Date", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");?></td>
                      </tr>
                      
                      
                       <tr>
                        <th align="left" valign="top">Receipt Date</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=getSVALUE("QC_Inspection_Work_Sheet_master", "Receipt_Date", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");?></td>
                      </tr>
                      
                      
                       <tr>
                        <th align="left" valign="top">Analyst Name</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?php $analyst=getSVALUE("QC_Inspection_Work_Sheet_master", "analyst", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");
				
						 echo $analystname=getSVALUE("users", "fname", "where  user_id='".$analyst."'");
						?></td>
                      </tr>
                      
                    </table></td>
                  <td width="40%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <th width="48%" align="left" valign="middle">Batch No</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td width="52%"><?=getSVALUE("QC_Inspection_Work_Sheet_master", "batch", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">Lot No.</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?
						$packsize=getSVALUE("item_info", "pack_size", "where  item_id='$_GET[fgid]'");
						$unit=getSVALUE("item_info", "unit_name", "where  item_id='$_GET[fgid]'"); ?>
						 <?=getSVALUE("production_floor_receive_detail", "total_unit", "where  pr_no='$_GET[prno]'")/$packsize?></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle">Quantity</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=$aqty=getSVALUE("QC_Inspection_Work_Sheet_master", "accepted_qty", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'")/$packsize;?> (<?=$unit?>)</td>
                      </tr>
                      
                      
                      <tr>
                        <th align="left" valign="middle">Samp. Size</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=$aqty=getSVALUE("QC_Inspection_Work_Sheet_master", "Sample_Size", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");?></td>
                      </tr>
                      
                      <tr>
                        <th align="left" valign="middle">Release Dt</th>
                        <td  align="left" valign="middle" style="width:2%">: </td>
                        <td><?=$aqty=getSVALUE("QC_Inspection_Work_Sheet_master", "Release_Date", "where item_id='$_GET[fgid]' and MAN_ID='$_GET[custom_pr_no]'");?></td>
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
          <td align="center" bgcolor="#FFFFFF"><strong>S. No</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>TEST PARAMETERS</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>RESULT</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>SPECIFICATION</strong></td>
          
        </tr>
        <?php
		
		$speresult=mysql_query("Select * from item_SPECIFICATION where  item_id='$_GET[fgid]'");
		while($sprow=mysql_fetch_array($speresult)){
		 ?>
        <tr>
          <td align="center" valign="top"><?=$i?></td>
          <td align="left" valign="top"><?=$sprow[TEST_PARAMETERS];?></td>
          <td align="left" valign="top"><?=$sprow[RESULT];?></td>
          <td align="center" valign="top"><?=$sprow[SPECIFICATION];?></td>
          
        </tr>
        <?php } ?>
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
                <td align="center" style="text-decoration:overline"><strong>Analysis by</strong></td>
                <td align="center" style="text-decoration:overline"><strong>Checked By </strong></td>
                
                <td align="center" style="text-decoration:overline"><strong>Approved By </strong></td>
                
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
