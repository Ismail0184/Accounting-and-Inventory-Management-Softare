 <?php
require_once 'support_file.php';
$now=time();
$unique='pr_no';
$unique_field='po_no';
$table="purchase_receive";
$page="grn_srn_view.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";	 
if(!isset($_GET[$unique])){ 
$title="GRN / SRN View";

if(isset($_POST[viewreport])){	
$res='select r.'.$unique.',r.'.$unique.' as "GRN/SRN No",r.'.$unique_field.',r.rcv_Date as Rec_Date,v.vendor_name,SUM(r.amount) as Amount,
u.fname as Received_by,r.entry_at,j.checked as status
from 
'.$table.' r,
vendor v,
users u,
secondary_journal j
where 
j.tr_no = r.pr_no AND
v.vendor_id=r.vendor_id and 
r.entry_by=u.user_id and r.rcv_Date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"
group by r.'.$unique.'
 order by r.'.$unique.' desc';
}?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>


<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View GRN / SRN</button></td>
            </tr></table>
<?=$crud->report_templates_with_status($res,$title='GRN/SRN List');?>   
</form>         
<?=$html->footer_content();?> 

<?php } else {  
session_start();
//====================== EOF ===================
//var_dump($_SESSION);

$pr_no 		= $_REQUEST['pr_no'];
$datas=find_all_field('purchase_receive','s','pr_no='.$pr_no);
$sql1="select b.* from purchase_receive b where b.pr_no = '".$pr_no."'";
$data1=mysqli_query($conn, $sql1);
$pi=0;
$total=0;
while($info=mysqli_fetch_object($data1)){
    $pi++;
    $rec_date=$info->rec_date;
    $rec_no=$info->rec_no;
    $mid = $info->m_id;
    $remarks=$info->Remarks;
    $po_no=$info->po_no;
    $order_no[]=$info->order_no;
    $ch_no=$info->ch_no;
    $VATch_no=$info->VAT_challan;
    $qc_by=$info->qc_by;
	$qc_at=$info->QC_at;

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

$MAN_by=find_all_field('MAN_details','','po_no='.$po_no.'','group by po_no');
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>.: GRN View :.</title>
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


                                                            <td bgcolor="#FFFFCC" style="text-align:center; color:#000000; font-size:14px; font-weight:bold;"><p class="style4"><?=$_SESSION[company_name]?> <br />
                                                                    <span class="style12"></span></p>
                                                                <p class="style6"><?=$_SESSION['company_address']?></p></td>


                                                        </tr>





                                                    </table>
                                                    <table  width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
                                                    </table>
                                                </td>


                                            </tr>





                                            <tr>


                                                <td height="19">&nbsp;</td>


                                            </tr>


                                        </table></td>


                                </tr>





                            </table></td>


                    </tr>





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
                                                    </table>
                                                    <?
                                                    if($datas->duplicate>0){?>
                                                        <table width="40%" border="0" align="center" cellpadding="5" cellspacing="0">
                                                            <tr>
                                                                <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">DUPLICATE COPY </td>
                                                            </tr>
                                                        </table>
                                                    <? }else{
                                                        db_execute('update purchase_receive set duplicate=1 where pr_no='.$pr_no);
                                                        ?>
                                                        <table width="40%" border="0" align="center" cellpadding="5" cellspacing="0">
                                                            <tr>
                                                                <td bgcolor="#999999" style="text-align:center; color:#99FF66; font-size:18px; font-weight:bold;">ORIGINAL COPY </td>
                                                            </tr>


                                                        </table>


                                                    <? }?>


                                                </td>


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


                                                <td width="30%" align="right" valign="middle">Vendor Company: </td>


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


                                                <td align="right" valign="middle">GRN Posting Time  :</td>


                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">


                                                        <tr>


                                                            <td>By: <?php echo find_a_field('users','fname','user_id='.$entry_by);?>/ At: <?php echo $entry_at;?></td>


                                                        </tr>


                                                    </table></td>


                                            </tr>


                                            <tr>
                                                <td align="right" valign="middle">GRN Rec No :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?php echo $mid;?> : <?php echo $rec_no;?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>



                                            <tr>
                                                <td align="right" valign="middle">Remarks</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?php echo $remarks;?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>





                                        </table>		      </td>


                                    <td width="40%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">


                                            <tr>


                                                <td align="right" valign="middle">GRN No:</td>


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
                                                <td align="right" valign="middle">QC / Rcv. By :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=find_a_field('purchase_receive','distinct qc_by','pr_no='. $_GET[v_no]);?>&nbsp;</td>
                                                        </tr>
                                                    </table></td>
                                            </tr>



                                            <tr>
                                                <td align="right" valign="middle">VAT Chalan No  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><strong><?php echo $VATch_no;?></strong></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>


                                            <tr>
                                                <td align="right" valign="middle">Delivary Chalan No  :</td>
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


                    <input name="button" type="button" onClick="hide();window.print();" value="Print" />


                </div>


            </div>


            <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="5">


                <tr>


                    <td align="center" bgcolor="#CCCCCC"><strong>SL</strong></td>


                    <td align="center" bgcolor="#CCCCCC"><strong>Code</strong></td>


                    <td align="center" bgcolor="#CCCCCC"><div align="center"><strong>Product Name</strong></div></td>





                    <td align="center" bgcolor="#CCCCCC"><strong>Unit</strong></td>


                    <td align="center" bgcolor="#CCCCCC"><strong>Rate</strong></td>


                    <td align="center" bgcolor="#CCCCCC"><strong>Rec Qty</strong></td>


                    <td align="center" bgcolor="#CCCCCC"><strong>Amount</strong></td>


                </tr>





                <? for($i=0;$i<$pi;$i++){?>





                    <tr>


                        <td align="center" valign="top"><?=$i+1?></td>


                        <td align="left" valign="top"><?=find_a_field('item_info','finish_goods_code','item_id='.$item_id[$i]);?></td>


                        <td align="left" valign="top"><?=find_a_field('item_info','item_name','item_id='.$item_id[$i]);?># <?=find_a_field('purchase_invoice','item_details','po_no="'.$po_no.'" and id='.$order_no[$i]);?></td>


                        <td align="right" valign="top"><?=$unit_name[$i]?></td>


                        <td align="right" valign="top"><?=$rate[$i]?></td>


                        <td align="right" valign="top"><?=$unit_qty[$i]?></td>


                        <td align="right" valign="top"><?=number_format($amount[$i],2); $t_amount = $t_amount + $amount[$i];?></td>


                    </tr>

                <? }?>


                <tr>
                    <td colspan="6" align="right" valign="top"><strong>Total Amount:</strong></td>
                    <td align="right" valign="top"><span class="style1">
      <?=number_format($t_amount,2);?>
    </span></td></tr>




                <? if ($asf >0){?>

                    <tr>
                        <td colspan="6" align="right" valign="top"><strong>ASF(
                                <?php echo $asf ;?>
                                %)</strong></td>
                        <td align="right" valign="top"><strong>
                                <?php $with_asf=(($t_amount)*($asf))/(100); echo number_format($with_asf,2)?>
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


                <tr>
                    <td colspan="6" align="right" valign="top"><strong>Total Payable Amount</strong></td>
                    <td align="right" valign="top"><strong>
                            <?php $totalpayable=$t_amount+$with_tax+$labor_bill+$others_bill+$transport_bill+$with_asf+$with_taxait;?>
                            <? //number_format(($t_amount+$with_tax+$labor_bill+$others_bill+$transport_bill),2)

                            print number_format($totalpayable,2);
                            ?>
                        </strong></td></tr>




                <? if ($cash_discount >0){?>

                    <tr>
                        <td colspan="6" align="right" valign="top"><strong>Less: Cash Discount</strong></td>
                        <td align="right" valign="top"><strong>
                                <?=number_format($cash_discount,2)?>
                            </strong></td></tr>
                <?php } ?>





                <tr>
                    <td colspan="6" align="right" valign="top"><strong>Net Payable Amount</strong></td>
                    <td align="right" valign="top"><strong>
                            <?=number_format(($totalpayable-$cash_discount),2)?>
                            <? //number_format(($t_amount+$with_tax+$labor_bill+$others_bill+$transport_bill),2)?>
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
						<tr>
          <td colspan="2" align="center">    
             <table style="font-size:11px; margin-top:50px" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" style="width:25%">
				<?=find_a_field('users','fname','user_id='.$MAN_by->entry_by);?><br />(<?=$MAN_by->entry_at?>)</em><br />
</td>
                <td align="center" style="width:25%">
				<?=find_a_field('users','fname','user_id='.$entry_by);?><br />(<?=$entry_at;?>)</em><br />
</td>
				<td align="center" style="width:25%">
				<?=find_a_field('users','fname','user_id='.$qc_by);?><br />(<?=$qc_at?>)</em><br />
</td><td align="center" style="width:25%"></td></tr>             
              <tr>
                <td align="center" style="text-decoration:overline"><strong>MAN By</strong></td>
                <td align="center" style="text-decoration:overline"><strong>GRN By </strong></td>
				<td align="center" style="text-decoration:overline"><strong>QC Checked By </strong></td> 
                <td align="center" style="text-decoration:overline"><strong>Approved By </strong></td> 				
              </tr>
            </table>
			</td>


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
<?php } ?>
