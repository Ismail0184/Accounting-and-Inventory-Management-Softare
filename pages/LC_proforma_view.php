<?php

session_start();

require_once 'support_file.php';
require_once 'class.numbertoword.php';
?>


<head>
   <style>table {
           border: 0px solid #666666;
           font: normal 11px Tahoma;
       }
       th {
           border-bottom: 1px solid #000000;
           border-left: 1px solid #000000;
           border-right: 1px solid #000000;
           border-top: 1px solid #000000;
           font: 12px;
           padding: 2px 5px;
           text-align:left;
           font-weight:bold;
       }
       td {
           border-bottom: 1px solid #000000;
           border-right: 1px solid #000000;
           border-left: 1px solid #000000;
           font: 11px;
           padding: 2px 5px;
       }

       .footer {
           color: #000000;
           font-size: 12px;
           font-weight:bold;
       }

       .footer td {
           border:0px;
           font-size: 12px;
           font-weight:bold;
       }
       /*---------------------Header-----------------------*/
       .header{
           width:100%;
           text-align:center;
           float:left;
       }
       .logo{
           width:20%;
           text-align:center;
           float:left;
           position:absolute;
           left: 0px;
           top: 3px;
       }
       h1{
           padding:0px;
           font: normal 22px Tahoma;
           line-height:0px;}
       h2{
           padding: 0px;
           font: normal 16px Tahoma;
       }
       h3{
           padding: 0px;
           font: normal 14px Tahoma;
       }
       p{
           text-align:left;
           font-weight:normal;
           font-size:14px;
           font-family:Tahoma;
           line-height:10px;}
       /*-------------------End of Header-------------------------*/
       /*---------------------Project Details-----------------------*/
       .left{
           width:50%;
           text-align:lleft;
           float:left;
       }
       .right{
           width:50%;
           text-align:left;
           float:right;
       }
       /*---------------------End of Project Details-----------------------*/
       .main{
           width:100%;
           float:left;
       }
       /*---------------------Date-----------------------*/
       .date{
           width:100%;
           text-align:right;
           font-size:12px;
           font-family:Tahoma;
           line-height:20px;
           float:left;
       }
       /*---------------------end-----------------------*/
   </style>
</head>


<?php
$pi_id = $_REQUEST['pi_id'];
if (isset($_POST['change'])) {
    $sql = 'UPDATE  `sale_chalan_bill` SET  `vat` =  ' . $_POST['vat'] . ',`vat_note` =  "' . $_POST['vat_note'] . '",
`discount` =  ' . $_POST['discount'] . ' WHERE  `id` =' . $v_no . '';
    mysql_query($sql);}

if (isset($_POST['confirm'])) {

    $sql = "UPDATE lc_lc_master SET  status='DOC_CONFIRM' WHERE id =$lc_id";
    mysql_query($sql);
    echo "<center> LC Document Complete </center>";

}

$pi_master = find_all_field('lc_pi_master', 's', 'id=' . $pi_id);
$pi_details = find_all_field('lc_pi_details', 's', 'pi_id=' . $pi_master->id);
$from_d = find_all_field('user_activity_management', 'fname', 'user_id=' . $lc_master->prepared_by);
$sql1 = "select * from lc_pi_details where pi_id = $pi_master->id";
$data1 = mysql_query($sql1);
$pi = 0;
$total = 0;
//echo $sql2;

while ($info = mysql_fetch_object($data1)) {

    $pi++;

//var_dump($info);

    $sl[] = $pi;

    $qty[] = $info->qty;

    $item_id[] = $info->item_id;

	$style_no[] = $info->style_no;

	$specification[] = $info->specification;

	$meassurment[] = $info->meassurment;

    $rate[] = $info->rate;

    $total_price[] = $info->amount;

    //$order=find_all_field('lc_workorder_details','rate','id='.$info->specification_id);

    $item = find_all_field('item_info', 'item_name', 'item_id=' . $info->item_id);
    $item_name[] = $item->item_name;
    if ($item->pack_unit == 'Dz')
        $amount[] = ($info->rate / 12) * $info->qty;
    else
        $amount[] = ($info->rate) * $info->qty;
}
$buyer_name = find_a_field('lc_buyer', 'buyer_name', 'party_id=' . $pi_master->party_id);
$buyer = $info->buyer_id;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

        <title>.: Proforma Invoice :.</title>

        <link href="../../css/report.css" type="text/css" rel="stylesheet"/>

        <script type="text/javascript">

            function hide()

            {

                document.getElementById("pr").style.display = "none";

            }

        </script>

        <style type="text/css">

            <!--

            .style7 {font-size: 12px}

            .style8 {

                font-size: 14px;

                font-weight: bold;

            }

            td {

                padding: 0px 0px;

            }

            -->

        </style>

    </head>

    <body style="font-family:Tahoma, Geneva, sans-serif">



        <br /><br />

        <form action="" method="post">

            <table width="700" border="1" cellspacing="0" cellpadding="0" align="center">

                <tr>

                    <td>

                        <div class="header">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                    <td width="20%" rowspan="2" valign="top"><img src="http://myone-erp.com/51816/logo/title.png" height="70" /></td>

                                    <td colspan="2" align="center" valign="middle">

                                        <table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">

                                            <tr>

                                                <td bgcolor="#666666" style="text-align:center; color:#FFF; font-size:18px; font-weight:bold;">PROFORMA INVOICE</td>

                                            </tr>

                                  </table>  </td>

                                </tr>

                                <tr>

                                  <td width="33%" align="center" valign="middle">&nbsp;</td>

                                  <td width="30%"><table width="100%" border="0" cellspacing="0">

                                    <tr>

                                      <td width="40%" align="right" valign="middle"></td>

                                      <td></td>

                                    </tr>

                                    <tr>

                                      <td align="right" valign="middle">PI NO : </td>

                                      <td><?php echo $pi_master->pi_no; ?></td>

                                    </tr>

                                    <tr>

                                      <td width="40%" align="right" valign="middle">Date : </td>

                                      <td><?php echo $pi_master->pi_issue_date; ?></td>

                                    </tr>

                                  </table></td>

                                </tr>

                            </table>

                        </div>

                    </td>

                </tr>

                <tr>  

                    <td>	</td>

                </tr> 

                <tr>

                    <td style="border:0px;">

                        <div id="pr">

                            <div align="left">

                                <input name="button" type="button" onclick="hide();

        window.print();" value="Print" />

                                <a style="padding:4px 5px 4px 5px; background:#ddd;color:green;text-decoration:none;font-size:11px;border:1px solid #000;" href="../proforma/createPi.php?pi_id=<?= $pi_id ?>"> Change</a>

                                <!-- <input type="submit" name="change" value="CHANGE" />  -->

                                <!-- <input type="submit" name="confirm" value="Confirm LC Document" /> -->

                                <input name="lc_id" type="hidden" value="<?= $pi_id ?>" />

                                <span style="margin-left:20px;color:green;font-size:15px;background:#ded;"> 



                                </span>

                            </div>

                        </div>

                        <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#333333" bgcolor="#FFFFFF">

                            <tr>

                                <td bordercolor="#333333" bgcolor="#CCCCCC"><div align="center"><span class="style7">SHIPPER/EXPORTER</span></div></td>

                                <td bordercolor="#333333" bgcolor="#CCCCCC"><div align="center"><span class="style7">RECEIVER/BILL TO </span></div></td>

                            </tr>

                            <tr>

                                <td width="50%" valign="top" bordercolor="#333333">

                                    <span class="style8"><?= $_SESSION['company_name'] ?></span><br /> 

                                    <span class="style7">Office: <?= $_SESSION['company_address'] ?></span>

                                </td>

                                <td width="50%" valign="top" bordercolor="#333333">

                                    <span class="style7">

                                        <? $par=find_all_field('lc_buyer','buyer_name','id='.$pi_master->party_id);

										

                                        echo '<span class="style8">'.$par->buyer_name.' </span><BR><span class="style7">Address:'.$par->address.' Contact:'.$par->contact_person_name.' Cell:'.$par->contact_person_cell.'</span>';

										

                                        ?>

                                    </span>

                                </td>

                            </tr>

                        </table>

                        <table width="100%" border="0">

                            <tr>

                                <td width="50%"><span class="style7">Ref No# <?= $ww->manual_no ?> </span></td>

                                <td><span class="style7">Buyer Name: <strong><? echo $buyer_name;?></strong></span></td>

                            </tr>

                        </table>

                        <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0">

                            <tr>

                                <td align="center" bgcolor="#CCCCCC"><strong>SL #</strong></td>

                                <td align="center" bgcolor="#CCCCCC"><div align="center"><strong>Item Description</strong></div></td>

                                <td align="center" bgcolor="#CCCCCC">Style/PO</td>

                                <td align="center" bgcolor="#CCCCCC">Meassurment</td>

                                <td align="center" bgcolor="#CCCCCC">Specification</td>

                                <td align="center" bgcolor="#CCCCCC"><strong>Quantity</strong></td>

                                <td align="center" bgcolor="#CCCCCC"><strong>Unit Price</strong></td>

                                <td align="center" bgcolor="#CCCCCC"><strong>Total </strong></td>

                            </tr> 

                            <?

                            for($i=0;$i<$pi;$i++){ ?>

                            <tr>

                                <td align="center" valign="top"><?= $sl[$i] ?></td>

                                <td align="left" valign="top">

                                    <div align="left">&nbsp;&nbsp;

<?= $item_name[$i]; ?>

                                    </div>                                </td>

                                <td align="center" valign="top">&nbsp;<?= $style_no[$i]; ?></td>

								<td align="center" valign="top">&nbsp;<?= $meassurment[$i]; ?></td>

                                <td align="center" valign="top">&nbsp;<?= $specification[$i]; ?></td>



                                <td align="right" valign="top">

                                    <div align="center">

<?= $qty[$i] ?>

                                    </div>                                </td>

                                <td align="right" valign="top">

                                    <div align="center">

<?= $rate[$i] ?>

                                    </div>                                </td>

                                <td align="right" valign="top">

                                  <div align="right">

                                    <?=number_format($total_price[$i],2)?>

                                  </div></td></tr>

                            <? $total = $total + $total_price[$i]; $totalt = $totalt+$qty[$i];}?>

                            <tr>

                              <td colspan="7" align="right"><strong>Total  :</strong></td>

                              <td align="right"><strong><?php echo number_format($total,2); ?></strong></td>

                            </tr>

                            <tr>

                              <td colspan="8" align="left"><span style="font-size:12px;border:0px;">Inwords: </span>&nbsp;<?php echo convertNumberMhafuz($total);  ?></td>

                            </tr>

                      </table>

                    </td>

                </tr>

                <tr>

                    <td style="border:0px;" align="center">

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                            <tr style="border:#666666">

                                <td  colspan="3" style="font-size:12px;border:0px;"></td>

                            </tr>

                            <tr>

                                <td  colspan="3" style="font-size:12px;border:0px;">

								<p><strong>All Order is accepted to the following terms and condition: </strong></p></td>

                            </tr>

                            <tr>

                                <td  colspan="3" style="font-size:12px;border:0px;">

                                    <ul>

<?

$sql = 'select * from lc_pi_rules order by serial';

$query = mysql_query($sql);

while($rule=mysql_fetch_object($query))

{

?>

<li><?=$rule->rule;?></li>

<?

}

?>

                                        



                                    </ul>                                </td>

                            </tr>

                            <tr>

                                <td style="border:0px;" width="50%">&nbsp;</td>

                                <td style="border:0px;">&nbsp;</td>

                                <td style="border:0px;">&nbsp;</td>

                            </tr>

                            <tr>

                              <td style="border:0px;">&nbsp;</td>

                              <td style="border:0px;">&nbsp;</td>

                              <td style="border:0px;">&nbsp;</td>

                            </tr>

                            <tr>

                                <td style="border:0px;">&nbsp;</td>

                                <td style="border:0px;">&nbsp;</td>

                                <td style="border:0px;">&nbsp;</td>

                            </tr>

                            <tr>

                                <td colspan="3" style="border:0px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                            <tr>

                              <td align="center" valign="top" style="border:0px;">&nbsp;</td>

                              <td align="center" valign="bottom" style="border:0px;"><div align="left">For and on behalf of </div></td>

                              <td align="center" valign="bottom" style="border:0px;">&nbsp;</td>

                              <td align="center" valign="bottom" style="border:0px;"><div align="left">Accepted By<br />

                                <?=$par->buyer_name?>

                              </div></td>

                            </tr>

                            <tr>

                                <td width="4%" align="center" valign="top" style="border:0px;">&nbsp;</td>

                                <td width="30%" align="center" valign="top" style="border:0px;"><div align="left"><img src="../../../logo/md.png" height="70px;" /></div></td>

                                <td width="44%" align="center" valign="top" style="border:0px;">&nbsp;</td>

                                <td width="22%" align="center" style="border:0px;"><div align="left"></div></td>

                            </tr>

                            <tr>

                              <td align="center" valign="top" style="border:0px;">&nbsp;</td>

                              <td align="center" valign="top" style="border:0px;"><div align="left">Authorized Signature, SPIL </div></td>

                              <td align="center" valign="top" style="border:0px;">&nbsp;</td>

                              <td align="center" style="border:0px;"><div align="left">Authorized Signature</div></td>

                            </tr>

                                </table></td>

                            </tr>

                        </table>

                    </td>

                </tr>

            </table>

        </form>

    </body>

</html>

