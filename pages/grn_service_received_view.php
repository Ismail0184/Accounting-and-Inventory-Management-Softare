 <?php
require_once 'support_file.php';
$now=time();
$unique='custom_grn_no';
$unique_field='po_no';
$table="grn_service_receive";
$page="grn_service_received_view.php";
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
user_activity_management u,
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

$pr_no 		= $_REQUEST['custom_grn_no'];
$datas=find_all_field(''.$table.'','s',''.$unique.'='.$pr_no);
$sql1="select b.*,concat(m.monthfullName,', ',b.year) as monthfullName from ".$table." b,monthname m where b.".$unique." = '".$pr_no."' and m.id=b.month";
$data1=mysqli_query($conn, $sql1);



$ssql = 'select a.*,b.* from vendor a, purchase_receive_master b where a.vendor_id=b.vendor_id and b.'.$unique.'='.$pr_no;
$dealer = find_all_field_sql($ssql);
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>.: Cash Memo :.</title>
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
                                                            <td bgcolor="#FFFFCC" style="text-align:center; color:#000000; font-size:14px; font-weight:bold;"><p class="style4"><?=$_SESSION[company_name]?> <br />                                                                    <span class="style12"></span></p>
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
                                                            <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">SERVICE RECEIVE NOTE </td>                                                        </tr>
                                                    </table>
                                                    <? if($datas->duplicate>0){?>
                                                        <table width="40%" border="0" align="center" cellpadding="5" cellspacing="0">
                                                            <tr>
                                                                <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">DUPLICATE COPY </td>


                                                            </tr>
                                                             </table>
                                                              <? }else{
															if($datas->status=="CHECKED"){
																  db_execute('update purchase_receive_master set duplicate=1 where custom_grn_no='.$_GET[custom_grn_no]); ?>


                                                        <table width="40%" border="0" align="center" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                        <td bgcolor="#999999" style="text-align:center; color:#99FF66; font-size:18px; font-weight:bold;">ORIGINAL COPY </td> </tr>


                                                        </table><? } else { ?> 
														<table width="40%" border="0" align="center" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                        <td bgcolor="#999999" style="text-align:center; color:red; font-size:18px; font-weight:bold;">UNAUTHORIZED COPY</td> </tr>


                                                        </table>
														<?php }}?></td></tr></table></td></tr></table></td></tr>


                    <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td valign="top">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                                            <tr>
                                                <td width="30%" align="right" valign="middle">Vendor Company: </td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$dealer->vendor_name;?>&nbsp;</td>
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
                                                            <td>By: <?php echo find_a_field('user_activity_management','fname','user_id='.$dealer->entry_by);?>/ At: <?php echo $dealer->entry_at;?></td>


                                                        </tr>


                                                    </table></td>


                                            </tr>


                                            <tr>
                                                <td align="right" valign="middle">Challan / Invoice No :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$dealer->ch_no;?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>



                                            <tr>
                                                <td align="right" valign="middle">Remarks</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$dealer->Remarks;?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>





                                        </table>		      </td>


                                    <td width="40%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                                            <tr>
                                                <td align="right" valign="middle">SRN No  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><strong><?=$pr_no;?></strong>&nbsp;</td>
                                                        </tr>
                                                    </table></td>


                                            </tr>


                                            <tr>


                                                <td align="right" valign="middle">SRN Date  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$dealer->rcv_Date;?>
                                                                &nbsp;</td>
                                                        </tr>
                                                    </table></td>
                                            </tr>


                                            <tr>


                                                <td align="right" valign="middle">Service For  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$dealer->advertisers;?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>




                                            <tr>
                                                <td align="right" valign="middle">Payment Trems  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$dealer->payments_terms;?>&nbsp;</td>
                                                        </tr>
                                                    </table></td>
                                            </tr>



                                            <tr>
                                                <td align="right" valign="middle">VAT Chalan No  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><strong><?=$dealer->VAT_challan;?></strong></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>


                                            <tr>
                                                <td align="right" valign="middle">VAT Chalan Date  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$dealer->VAT_challan_Date;?></td>
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
                    <td align="center" bgcolor="#CCCCCC"><div align="center"><strong>Service Details</strong></div></td>
                    <td align="center" bgcolor="#CCCCCC"><strong>Month</strong></td>
                    <td align="center" bgcolor="#CCCCCC"><strong>Monthly Charge</strong></td>
                    <td align="center" bgcolor="#CCCCCC"><strong>No. of months</strong></td>
                    <td align="center" bgcolor="#CCCCCC"><strong>Total Amount</strong></td>


                </tr>





                <?php while($data=mysqli_fetch_object($data1)):?>
                <tr>
                        <td align="center" valign="top"><?=$i=$i+1;?></td>
                        <td align="left" valign="top"><?=$data->service_details;?></td>
                        <td align="center" valign="top"><?=$data->monthfullName;?></td>
                        <td align="right" valign="top"><?=$data->rate;?></td>
                        <td align="center" valign="top"><?=number_format($data->qty);?></td>
                        <td align="right" valign="top"><?=number_format($data->amount,2);?></td>
                    </tr>
                    <?php 
					$total_amount=$total_amount+$data->amount;
					 ?>
                <? endwhile;?>


                <tr>
                    <td colspan="5" align="right" valign="top"><strong>Total Amount:</strong></td>
                    <td align="right" valign="top"><span class="style1">
      <?=number_format($total_amount,2);?>
    </span></td></tr>



             


                <? if ($dealer->tax >0){?>
                    <tr>
                        <td colspan="5" align="right" valign="top"><strong>VAT(
                                <?=$dealer->tax ;?> %)</strong></td>
                        <td align="right" valign="top"><strong>
                                <?php $with_tax=(($total_amount)*($dealer->tax))/(100); echo number_format($with_tax,2)?>
                            </strong></td></tr>
                <?php } ?>




                <? if ($dealer->tax_ait >0){?>

                    <tr>
                        <td colspan="5" align="right" valign="top"><strong>Tax(
                                <?=$dealer->tax_ait ;?> %)</strong></td>
                        <td align="right" valign="top"><strong>
                                <?php $with_taxait=(($total_amount)*($dealer->tax_ait))/(100); echo number_format($with_taxait,2)?>

                            </strong></td></tr>
                <?php } ?>





                <tr>
                    <td colspan="5" align="right" valign="top"><strong>Total Payable Amount</strong></td>
                    <td align="right" valign="top"><strong>
                            <?php $totalpayable=$total_amount+$with_tax+$labor_bill+$others_bill+$transport_bill+$with_asf+$with_taxait;?>
                            <? //number_format(($t_amount+$with_tax+$labor_bill+$others_bill+$transport_bill),2)

                            print number_format($totalpayable,2);
                            ?>
                        </strong></td></tr>




                <? if ($cash_discount >0):?>
                <tr>
                        <td colspan="5" align="right" valign="top"><strong>Less: Cash Discount</strong></td>
                        <td align="right" valign="top"><strong>
                                <?=number_format($cash_discount,2)?>
                            </strong></td></tr>
                <?php endif; ?>





                <!--tr>
                    <td colspan="5" align="right" valign="top"><strong>Net Payable Amount</strong></td>
                    <td align="right" valign="top"><strong>
                            <?=number_format(($totalpayable-$cash_discount),2)?>
                            <? //number_format(($t_amount+$with_tax+$labor_bill+$others_bill+$transport_bill),2)?>
                        </strong></td></tr-->


            </table></td>


    </tr>
    <tr>
        <td align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" style="font-size:12px"><em>All services are received in a good condition as per Terms</em></td>
                </tr>
                <tr>
                    <td width="50%">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr>
                <td colspan="2" style="font-size:12px;"><b><u>Attached Documents:</u></b> <br /><br /><?php $res=mysqli_query($conn, "select  documents_list from ".$table." where ".$unique."=".$_GET['custom_grn_no']."");
				while($dl=mysqli_fetch_object($res)){ ?><span style="margin-left:20px;"> <?php echo $is=$is+1; echo '. '; echo $dl->documents_list;} ?></span></td>
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
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><strong></strong>
                    
                    <?php if($datas->status=="CHECKED"){ ?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                                <!--td width="33%"><div align="center"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$po_master->entry_by);?><br /><span style="font-size:9px">(<?=$po_master->entry_at;?>)</span></div></td-->
                                <td width="50%"><div align="center"><?=find_a_field('user_activity_management','fname','user_id='.$datas->entry_by);?><br /><span style="font-size:9px">(<?=$datas->entry_at;?>)</span></div></td>
                                <td width="50%"><div align="center"><?=find_a_field('user_activity_management','fname','user_id='.$datas->qc_by);?><br /><span style="font-size:9px">(<?=$datas->QC_at;?>)</span></div></td>
                            </tr>
                            <tr>
                            <!--td width="33%" style="border-top:solid thin 2px"><div align="center" style="text-decoration:overline; font-weight:bold">Prepared By</div></td-->
                                <td width="50%" style="border-top:solid thin 2px"><div align="center" style="text-decoration:overline; font-weight:bold">Service Received By </div></td>
                                <td width="50%"><div align="center" style="text-decoration:overline; font-weight:bold">Checked By</div></td>
                               
                            </tr>
                        </table>
                        <?php } else {echo "<h3 style='color:red'>You are trying to print an unauthorized SRN. Please wait until approval!!
</h3>";} ?> 
                        </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>


</body>


</html>
<?php } ?>
