<?php
require_once 'support_file.php';
require_once 'class.numbertoword.php';
$chalan_no 		= $_REQUEST['v_no'];
$challan= find_all_field('sale_do_chalan','','chalan_no='.$chalan_no);
foreach($challan as $key=>$value){
$$key=$value;
}
$ssql = 'select a.*,b.do_date from dealer_info a, sale_do_master b where a.dealer_code=b.dealer_code and b.do_no='.$do_no;
$dealer = find_all_field_sql($ssql);
$entry_time=$dealer->do_date;
$dept = 'select warehouse_name from warehouse where warehouse_id='.$dept;
$deptt = find_all_field_sql($dept);
$to_ctn = find_a_field('sale_do_chalan','sum(pkt_unit)','chalan_no='.$chalan_no);
$to_pcs = find_a_field('sale_do_chalan','sum(dist_unit)','chalan_no='.$chalan_no);
$ordered_total_ctn = find_a_field('sale_do_details','sum(pkt_unit)','dist_unit = 0 and do_no='.$do_no);
$ordered_total_pcs = find_a_field('sale_do_details','sum(dist_unit)','do_no='.$do_no);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Challan Copy :.</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">
function hide()
{    document.getElementById("pr").style.display="none";
}
</script>
<style type="text/css">
<!--
.style4 {
	font-size: 18px;
	color: #000000;
}
.style8 {font-size: 16px; font-weight:bold}
.style9 {font-size: 12px}
.style10 {font-size: 16px}
.style11 {font-size: 14px}
.header table tr td table tr td table tr td table tr td {
	color: #000;
}
-->
</style>
</head>


<body style="font-family:Tahoma, Geneva, sans-serif; font-size: 10px;">
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div class="header">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="17%">
                        <img src="../../assets/images/icon/title.png" width="98%" />
                        <td width="58%"><table  width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
                            <tr>
                              <td style="text-align:center; color:#000; font-size:14px; font-weight:bold;"><? if($_SESSION['warehouse']>0) echo $deptt->warehouse_name;?>
                                <br />
                              <strong>Delivery Challan</strong></td>
                            </tr>
                            <tr>
                              <td><div align="center"></div></td>
                            </tr>
                          </table>
                        <td width="25%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px; color: #F00;">
                            <tr>
                              <td align="right" valign="middle"> Delivery Chalan Date</td>
                              <td><table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">
                                  <tr>
                                    <td><?php echo date('d-m-Y',strtotime($chalan_date));?></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle">Delivery Chalan  No: </td>
                              <td><table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">
                                  <tr>
                                    <td><strong><?php echo $chalan_no;?></strong></td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <td width="25%" align="left" valign="middle">Customer Name: </td>
                        <td width="75%"><?php echo $dealer->dealer_name_e.'- '.$dealer->dealer_code.' ';?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="top"> Address:</td>
                        <td><?php echo $dealer->address_e.'&nbsp;'.' Mobile: '.$dealer->mobile_no;?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle">Propritor's Name:</td>
                        <td><?php echo $dealer->propritor_name_e;?></td>
                      </tr>
                      <tr>
                      <? if ($transporter_name != ""){ ?>
                        <td width="25%" align="left" valign="middle">Transporter Name : </td>
                        <td><?php echo $transporter_name;?></td>
                      <? }?>
                      </tr>
                    </table></td>
                  <td width="30%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <td align="right" valign="middle">DO No:</td>
                        <td><table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">
                            <tr>
                              <td><?php echo $do_no;?>&nbsp;</td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle">DO Date:</td>
                        <td><table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">
                            <tr>
                              <td><?php echo date('d-m-Y',strtotime($entry_time));?></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                     <? if($vehicle_no != "") {?>
                        <td align="right" valign="middle">Vehicle No:</td>
                        <td><table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">
                            <tr>
                              <td><?=$vehicle_no?>
                                &nbsp;</td>
                            </tr>
                          </table></td>
                          <?  } ?>
                      </tr>
                      <tr>
                       <? if ($driver_name_real != ""){ ?>
                        <td align="right" valign="middle">Driver Name: </td>
                        <td><table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">
                            <tr>
                              <td><?php echo $driver_name_real;?>&nbsp;</td>
                             
                            </tr>
                          </table></td>
                           <?  } ?>
                      </tr>
                      <tr>
                       <? if($delivery_man != "") {?>
                        <td align="right" valign="middle">Delivery Man:</td>
                        <td><table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">
                            <tr>
                              <td><?php echo $delivery_man;?>&nbsp;</td>
                            </tr>
                          </table></td>
                        <?  } ?>
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
          <p>
            <input name="button" type="button" onclick="hide();window.print();" value="Print" />
          </p>
          <nobr>
          <!--<a href="chalan_bill_view.php?v_no=<?=$_REQUEST['v_no']?>">Bill</a>&nbsp;&nbsp;-->
          <? if($dealer->team_name=='Corporate'){?>
          <a href="chalan_bill_corporate.php?v_no=<?=$_REQUEST['v_no']?>" target="_blank">New Bill</a>&nbsp;&nbsp;
          <? }

else{?>
          <a href="chalan_bill_distributors.php?v_no=<?=$_REQUEST['v_no']?>" target="_blank">Invoice</a>&nbsp;&nbsp;
          <!---a href="chalan_bill_distributorsrice.php?v_no=<?=$_REQUEST['v_no']?>" target="_blank">(Rice)</a>&nbsp;&nbsp;
          
           <a href="chalan_bill_distributors_rice.php?v_no=<?=$_REQUEST['v_no']?>" target="_blank">Rice without Commission</a-->&nbsp;&nbsp;
          <? }?>
          <!--<a href="chalan_view_mis.php?v_no=<?=$_REQUEST['v_no']?>">MIS Copy</a>-->
          </nobr>
		  <nobr>
          
          <!--<a href="chalan_bill_distributor_vat_copy.php?v_no=<?=$_REQUEST['v_no']?>" target="_blank">Vat Copy</a>-->
          </nobr>
	    </div>
      </div>
      <div style="min-height:600px;">

          <table width="100%" class="tabledesign" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="2" style="font-size:11px;">
              <tr>
                  <th  align="center">SL</th>
                  <th  align="center">Product Name</th>
                  <th  align="center">UoM</th>
                  <th  align="center">Order Qty</th>
                  <th  align="center">Undel. Qty</th>
                  <th  align="center">Delivery Qty</th>
                  <th  align="center">Batch</th>
                  <th  align="center">Expiry Date</th>
              </tr>

              <? $sqlc = 'select c.*,SUM(c.dist_unit) as deliverd_qty,(select SUM(dist_unit) from sale_do_details where do_no=c.do_no and item_id=c.item_id ) as order_qty, i.item_name, i.finish_goods_code, i.pack_size,i.unit_name from sale_do_chalan c, item_info i where 
        i.item_id=c.item_id and i.finish_goods_code != 2001 and c.chalan_no='.$chalan_no.' group by c.item_id order by c.id asc';
              $queryc=mysqli_query($conn, $sqlc);
              while($datac = mysqli_fetch_object($queryc)){?>
                  <tr style="font-size:10px;">
                      <td align="center" valign="top" style="vertical-align: middle"><?=++$kk;?></td>
                      <td align="left" valign="top" style="vertical-align: middle"><?=$datac->item_name;?></td>
                      <td align="center" valign="top" style="vertical-align: middle"><?=$datac->unit_name;?></td>
                      <td align="center" valign="top" style="vertical-align: middle"><?=$datac->order_qty;?></td>
                      <td align="center" valign="top" style="vertical-align: middle"><?=$datac->order_qty-$datac->deliverd_qty;?></td>

                      <td align="center" valign="top" style="vertical-align: middle"><table border="1" style="border-collapse: collapse; width: 100%" >
                              <?php $res=mysqli_query($conn, "SELECT SUM(j.item_ex) as item_ex from journal_item j where j.item_ex>0 and j.item_id=".$datac->item_id." and  j.sr_no=".$chalan_no." group by j.batch,j.expiry_date");
                              While($data_inventory=mysqli_fetch_object($res)){ ?>
                                  <tr><td style="border: 1px solid #ccc; text-align: center"><?=number_format($data_inventory->item_ex);?> </td></tr>
                              <?php } ?></table></td>
                      <td align="center" valign="top" style="vertical-align: middle"><table border="1" style="border-collapse: collapse; width: 100%" >
                              <?php $res=mysqli_query($conn, "SELECT b.batch_no as batch from journal_item j,lc_lc_received_batch_split b where b.batch=j.batch and j.item_ex>0 and j.item_id=".$datac->item_id." and  j.sr_no=".$chalan_no." group by j.batch,j.expiry_date");
                              While($data_inventory=mysqli_fetch_object($res)){ ?>
                                  <tr><td style="border: 1px solid #ccc; text-align: center"><?=$data_inventory->batch;?> </td></tr>
                              <?php } ?></table></td>


                      <td align="center" valign="top" style="vertical-align: middle"><table border="1" style="border-collapse: collapse; width: 100%" >
                              <?php $res=mysqli_query($conn, "SELECT expiry_date from journal_item where item_ex>0 and item_id=".$datac->item_id." and  sr_no=".$chalan_no." group by batch,expiry_date");
                              While($data_inventory=mysqli_fetch_object($res)){ ?>
                                  <tr><td style="border: 1px solid #ccc; text-align: center"><?=$data_inventory->expiry_date;?> </td></tr>
                              <?php } ?></table></td>
                  </tr>

              <? }

              ?>
              <tr>
                  <td colspan="3" align="right" valign="middle">Total</td>

                  <td align="center" valign="middle"><?=$g_tot_ctn_order?></td>
                  <td align="center" valign="middle" ><?=$g_tot_pcs_order?></td>
                  <td align="center" valign="middle"><?=$g_tot_undel_ctn?></td>
                  <td align="center" valign="middle"><?=$g_tot_undel_pcs?></td>
                  <td align="center" valign="middle"><?=$g_tot_ctn_delv?></td>
              </tr>

          </table>
      <div style="border:1px solid #CCC; width:40%; margin-top:20px">
      <p style="font-size:12px"><u><strong>For Delivary Please Contact</strong></u></p>
      <p style="font-size:12px"><strong>Name:  <?=$dealer->contact_person;?></strong></p>
      <p style="font-size:12px"><strong>Mobile No:  <?=$dealer->contact_number;?></strong></p>
      </div>
      </div>
      </td>
  </tr>
  
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" style="font-size:10px">All goods are received in a good condition as per Terms</td>
        </tr>
        <tr>
          <td colspan="2">Prepared By:
            <?=find_a_field('users','fname','user_id='.$entry_by);?></td>
        </tr>
        <tr>
          <td width="50%">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center"><strong><br />
            </strong>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="25%"><div align="center">
                  <p>Received By <br />(Driver/Carrier)</p>
                </div></td>
                
                <td width="25%"><div align="center">Executive Inventory</div></td>
                <td width="25%"><div align="center">Depot Incharge </div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan="2" style="border:1px solid #CCC; color: #666;" align="center" ><p>
<?=$_SESSION['company_name']?>
            <br />
            <?=$_SESSION['company_address']?>
            <br />
            Tel: +88029860176 | 9860178, VAT Reg. No. <?php
			$widdd = $_SESSION['userdepot'];
			 if($widdd=='5'){ echo '000702484'; } 
	  if($widdd=='12'){ echo '000851876'; }
	   ?></p>
            </td>
        </tr>
      </table>
    <div class="footer1"> </div></td>
  </tr>
</table>
</body>
</html>
