 <?php
require_once 'support_file.php';
$now=time();
$unique='id';
$unique_field='m_id';
$table="MAN_master";
$table_details="MAN_details";
$page="GRN_MAN_print_view.php";
$$unique=$_GET[$unique];

if(isset($$unique))
{
$condition=$unique."=".$$unique;
$data=db_fetch_object($table,$condition);
while (list($key, $value)=each($data))
{ $$key=$value;}
}

$vendor=find_all_field('vendor','','vendor_id='.$vendor_code.'');
$query=mysqli_query($conn, 'SELECT d.qty,d.rate,d.amount,i.item_id,i.item_name,i.unit_name from MAN_details d,item_info i where m_id='.$_GET[$unique].' and i.item_id=d.item_id');

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>.: MATERIAL ARRIVAL NOTE :.</title>
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


                                                            <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">MATERIAL ARRIVAL NOTE </td>


                                                        </tr>


                                                    </table>


                                                   


                                                        <table width="40%" border="0" align="center" cellpadding="5" cellspacing="0">


                                                            <tr>


                                                                <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">DUPLICATE COPY </td>


                                                            </tr>


                                                        </table>                                                   


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


                                                            <td><?=$vendor->vendor_name;?>&nbsp;</td>


                                                        </tr>


                                                    </table></td>


                                            </tr>


                                            <tr>


                                                <td align="right" valign="top"> Address:</td>


                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">


                                                        <tr>


                                                            <td height="60" valign="top"><?=$vendor->address;?>&nbsp;</td>


                                                        </tr>


                                                    </table></td>


                                            </tr>


                                            <tr>
                                                <td align="right" valign="middle">Remarks</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><?=$remarks;?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>





                                        </table>		      </td>


                                    <td width="40%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">


                                            <tr>


                                                <td align="right" valign="middle">MAN No:</td>


                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">


                                                        <tr>


                                                            <td><strong><?=$MAN_ID;?></strong>&nbsp;</td>


                                                        </tr>


                                                    </table></td>


                                            </tr>


                                            <tr>


                                                <td align="right" valign="middle"> MAN Date</td>


                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">


                                                        <tr>


                                                            <td><?=$man_date?>


                                                                &nbsp;</td>


                                                        </tr>


                                                    </table></td>


                                            </tr>


                                            <tr>


                                                <td align="right" valign="middle">PO No: </td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td>
															<?php $res=mysqli_query($conn, 'select distinct po_no from MAN_details where m_id='.$_GET[$unique].'');
															while($po=mysqli_fetch_object($res)){ echo $po->po_no.' ';};?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>







                                            <tr>
                                                <td align="right" valign="middle">VAT Chalan No  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><strong><?=$VAT_challan;?></strong></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>


                                            <tr>
                                                <td align="right" valign="middle">Delivary Chalan No  :</td>
                                                <td><table width="100%" border="1" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                            <td><strong><?=$delivary_challan;?></strong></td>
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
                <? while($data=mysqli_fetch_object($query)){?>
                    <tr>
                        <td align="center" valign="top"><?=$i+1?></td>
                        <td align="left" valign="top"><?=$data->item_id;?></td>
                        <td align="left" valign="top"><?=$data->item_name;?></td>
                        <td align="right" valign="top"><?=$data->unit_name;?></td>
                        <td align="right" valign="top"><?=$data->rate;?></td>
                        <td align="right" valign="top"><?=$data->qty;?></td>
                        <td align="right" valign="top"><?=$data->amount;?></td>
                    </tr>
                <? }?>
                <tr>
                    <td colspan="6" align="right" valign="top"><strong>Total Amount:</strong></td>
                    <td align="right" valign="top"><span class="style1">
      <?=number_format($t_amount,2);?>
    </span></td></tr>
    </table></td></tr>


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
                    <td colspan="2" align="center">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
              
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('users','fname','user_id='.$entry_by)?>
                </span><br /><font style="font-size:11px; padding-bottom::-50px">(<?=$entry_at;?>)</font></td>
                
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('users','fname','user_id='.$cehck_by)?>
                </span><br /> <font style="font-size:11px; padding-bottom::-50px">(<?=$cehck_at;?>)</font></td>
                
                
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorised_person)?>
                </span><br /> <font style="font-size:11px; padding-bottom::-50px">(<?=$datas->authorised_date;?>)</font></td>
              </tr>
                            <tr>
                                <td width="25%" align="center" style="text-decoration:overline"><div align="center">Received By </div></td>
                                <td width="25%" align="center" style="text-decoration:overline"><div align="center">Checked By </div></td>
                                <td width="25%" align="center" style="text-decoration:overline"><div align="center">Authorized By </div></td>
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

