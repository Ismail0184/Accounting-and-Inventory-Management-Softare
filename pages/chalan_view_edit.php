<?php



//====================== EOF ===================

//var_dump($_SESSION);

require_once 'support_file.php';

$jv_no=$_REQUEST['jv_no'];

if($_REQUEST['req_no']>0)

$pr_no 		= $_REQUEST['req_no'];



elseif($_REQUEST['v_no']>0)

$pr_no 		= $_REQUEST['v_no'];









$dealer = find_all_field('dealer_info','s','dealer_code='.$vendor_id);

$sql1="select b.* from purchase_receive b where b.pr_no = '".$pr_no."'";

$data1=mysql_query($sql1);



$pi=0;

$total=0;

while($info=mysql_fetch_object($data1)){ 

$pi++;

$ch_no=$info->ch_no;

$po_no = $info->po_no;

$id[]=$info->id;

$qc_by=$info->qc_by;

$receive_type[]=$info->receive_type;

$item_id[] = $info->item_id;

$rate[] = $info->rate;

$amount[] = $info->amount;

$rec_date=$info->rec_date;

$unit_qty[] = $info->qty;

$unit_name[] = $info->unit_name;

$entry_at=$info->entry_at;

}

$ssql = 'select a.* from vendor a, purchase_master b where a.vendor_id=b.vendor_id and b.po_no='.$po_no;

$dealer = find_all_field_sql($ssql);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>.: Sales Return  Report :.</title>

<link href="../../damage_mod/pages/css/invoice.css" type="text/css" rel="stylesheet"/>

<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

function reloadPage() {

location.reload();

}



</script>

<script type="text/javascript" src="../js/paging.js"></script>

<style type="text/css">

<!--

.style1 {font-weight: bold}

-->

</style>

</head>

<body style="font-family:Tahoma, Geneva, sans-serif"><form action="" method="post">

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

                        <td><?php echo $dealer->address;?>&nbsp;</td>

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

		        </table>		      </td>

			<td width="30%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">

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

       <tr style="font-size:13px;">

        <td align="center" bgcolor="#CCCCCC"><strong>SL</strong></td>

        <td align="center" bgcolor="#CCCCCC"><strong>Pay</strong></td>

        <td align="center" bgcolor="#CCCCCC"><strong>Damage</strong></td>

        <td align="center" bgcolor="#CCCCCC"><div align="center"><strong>Product Name</strong></div></td>



        <td align="center" bgcolor="#CCCCCC"><strong>Unit</strong></td>

        <td align="center" bgcolor="#CCCCCC"><strong>Rate</strong></td>

        <td align="center" bgcolor="#CCCCCC"><strong> Qty</strong></td>

        <td align="center" bgcolor="#CCCCCC"><strong>Payable Amt</strong></td>

        <td align="center" bgcolor="#CCCCCC">&nbsp;</td>

        </tr>

       

<? for($i=0;$i<$pi;$i++){

$item_info = find_all_field('item_info','item_name','item_id='.$item_id[$i]);

$damage_cause = find_all_field('damage_cause','damage_cause','id='.$receive_type[$i]);

?>

      

      <tr style="font-size:12px; height:40px;" <?=($i%2)?'bgcolor="#F7F7F7"':'';?>>

        <td align="center" valign="top"><?=$i+1?></td>

        <td align="left" valign="top"><?=$damage_cause->payable;?></td>

        <td align="left" valign="top"><?=$damage_cause->damage_cause;?></td>

        <td align="left" valign="top"><?=$item_info->item_name.'('.$item_info->finish_goods_code.')';?></td>

        <td align="right" valign="top"><?=$unit_name[$i]?></td>

        <td align="right" valign="top">

          <input type="hidden" name="id_<?=$id[$i]?>" id="id_<?=$id[$i]?>" style="width:70px; text-align:right; color:#FF3300" value="<?=$rate[$i]?>" />

		  <input type="text" name="rate_<?=$id[$i]?>" id="rate_<?=$id[$i]?>" style="width:70px; text-align:right; color:#FF3300" value="<?=$rate[$i]?>" readonly="readonly" />

            </td>

        <td align="right" valign="top"><?=$unit_qty[$i]?></td>

        <td align="right" valign="top"><strong><?=number_format($amount[$i],2);$t_amount = $t_amount + $amount[$i];?>

        </strong></td>

        <td align="right" valign="top">

		<span id="po<?=$id[$i]?>"><input type="button" name="Submit" value="RESET" style="font-size:11px; color:#FF0000;" 

		onclick="getData2('chalan_view_print_ajax.php', 'po<?=$id[$i]?>','<?=$id[$i]?>',document.getElementById('rate_<?=$id[$i]?>').value);" /></span></td>

        </tr>

		

<? }?>

  <tr style="font-size:14px;"><td colspan="7" align="center" valign="top"><div align="right"><strong>Total Amount: </strong></div></td>

        <td align="right" valign="top"><span class="style1">

          <?=number_format($t_amount,2)?>

        </span></td>

        <td align="right" valign="top">&nbsp;</td></tr>

    </table></td>

  </tr>

  <tr>

    <td align="center">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td colspan="2" style="font-size:12px"><em>All goods are checked and confirmed as per Terms.</em></td>

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

</table></form>

</body>

</html>

