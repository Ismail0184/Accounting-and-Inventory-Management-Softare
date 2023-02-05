<?php

require_once 'support_file.php';
require_once ('../page/common/class.numbertoword.php');
$jv_no=$_GET['id'];
$bill_no=$_REQUEST['bill_no'];
$bill_date=$_REQUEST['bill_date'];
$tdates = date("Y-m-d");
$day = date('l', strtotime($idatess));
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$timess = $dateTime->format("d-m-y  h:i A");
$address=find_a_field('project_info','proj_address',"1");
$jv = find_all_field('secondary_journal','','tr_from = "Purchase" and tr_no='.$jv_no);
$pr = find_all_field('purchase_receive','pr_no','pr_no='.$jv->tr_no);
?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>.: Voucher :.</title>

<link href="../page/css/voucher_print.css" type="text/css" rel="stylesheet"/>



<link href="../page/css/pagination.css" rel="stylesheet" type="text/css" />

<link href="../page/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />

<link href="../page/css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />



<script type="text/javascript" src="../page/js/jquery-1.4.2.min.js"></script>

<script type="text/javascript" src="../page/js/jquery-ui-1.8.2.custom.min.js"></script>



<script type="text/javascript" src="../page/js/jquery.autocomplete.js"></script>

<script type="text/javascript" src="../page/js/jquery.validate.js"></script>

<script type="text/javascript" src="../page/js/paging.js"></script>

<script type="text/javascript" src="../page/js/ddaccordion.js"></script>

<script type="text/javascript" src="../page/js/js.js"></script>

<script type="text/javascript" src="../page/js/jquery.ui.datepicker.js"></script>

<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

function DoNav(theUrl)

{

	var URL = 'unchecked_voucher_view_popup_purchase.php?'+theUrl;

	popUp(URL);

}



function popUp(URL)

{

day = new Date();

id = day.getTime();

eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=800,left = 383,top = -16');");

}

</script>

    <? do_calander('#voucher_date');?><? do_calander('#bill_date');?>

<style type="text/css">

/* CSS Document */
body 
{
	background-color: #ffffff;	
	margin: 0px auto -1px auto; 
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
	line-height:20px;
	margin-top:20px;
	color:#000000;
	text-align:none;
	text-decoration:none;
}
.header
{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
	color:#000000;
	text-decoration:none;
	line-height:25px;
}

.tabledesign {
	width:820px;
	border-color:000000;
	border-collapse:collapse;
	font-size:14px;
	text-align:center;
}
.tabledesign td{
	padding:3px;
	border:solid 1px;
	border-color:000000;
	
}
.tabledesign1 {
	width:860px;
	border-color:000000;
	border-collapse:collapse;
	font-size:14px;
	text-align:center;
	line-height:12px;
}
.tabledesign1 td{
	border:solid 0px;
	border-color:000000;
	
}

.title{
	font-weight:bold;
	font-size:25px;
	line-height:30px;
}
.logo{
	width:300px;
	height:50px;
}
.debit_box{
	width:380px;
	height:32px;
	font-weight:bold;
	font-size:25px;
	margin: 0px auto -1px auto; 
	}
.line{
	background:url(../images/line.jpg) repeat-x;
	height:2px;
	line-height:0px;}
	
.tabledesign_text{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	line-height:20px;
	margin-top:20px;
	color:#000000;
	text-align:none;
	text-decoration:none;
	}

.style1 {

	color: #FFFFFF;

	font-weight: bold;

}

.style3 {color: #FFFFFF; font-weight: bold; font-size: 12px; }

-->

</style>


</head>

<body><form action="" method="post">

<table width="820" border="0" cellspacing="0" cellpadding="0" align="center">

  





  <tr>

<?php if($jv->grn_inventory_type='Asset'){
		$link_view='chalan_view_asset.php'; } else {
		$link='chalan_view2.php'; }?>

    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" class="tabledesign_text">
<div id="pr">
<? if($jv->checked!='YES'){?>
<h1 style="text-align:center; color:red">Unchecked</h1>



<a target="_blank" href="<?=$link_view;?>?v_no=<?=$pr->pr_no?>"></a></div><? }else{?>
<h1 style="text-align:center; color:red">Checked</h1>
<? }?>
</div></td>

        </tr>




 

 

  <tr>

    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" class="tabledesign">

      <tr>

        <td align="center"><div align="center">SL</div></td>

        <td align="center">A/C Code </td>

        <td align="center">Particulars</td>

        <td>Debit</td>

        <td>Credit</td>

      </tr>



	  <?

$sql2=mysqli_query($conn, "SELECT a.ledger_id,a.ledger_name,sum(dr_amt) as dr_amt,sum(cr_amt) as cr_amt FROM accounts_ledger a, secondary_journal b where b.tr_no='$jv_no' and a.ledger_id=b.ledger_id group by b.ledger_id desc");
while($info=mysqli_fetch_object($sql2)){

	  ?>

      <tr>

        <td align="left"><div align="center">

          <?=++$s;

		  ?>

        </div></td>

        <td align="left"><?=$info->ledger_id?></td>

        <td align="left"><?=$info->ledger_name;?></td>

        <td align="right"><? echo number_format($info->dr_amt,2); $ttd = $ttd + $info->dr_amt;?></td>

        <td align="right"><? echo number_format($info->cr_amt,2); $ttc = $ttc + $info->cr_amt;?></td>

        </tr>

<?php }?>

<?
$sql2=mysqli_query($conn, "SELECT a.ledger_id,a.ledger_name,sum(cr_amt) as cr_amt FROM accounts_ledger a, secondary_journal b where b.jv_no='$jv_no' and a.ledger_id=b.ledger_id and jv_no=$jv_no and cr_amt>0 group by b.ledger_id desc");
while($info=mysqli_fetch_object($sql2)){

	  ?>

      <tr>
        <td align="left"><div align="center"><?=++$s;?></div></td>
        <td align="left"><?=$info->ledger_id?></td>
        <td align="left"><?=$info->ledger_name?></td>
        <td align="right"><? echo number_format($info->dr_amt,2); $ttd = $ttd + $info->dr_amt;?></td>
        <td align="right"><? echo number_format($info->cr_amt,2); $ttc = $ttc + $info->cr_amt;?></td>
        </tr>

<?php }?>



      <tr>
        <td colspan="3" align="right">Total Taka: </td>
        <td align="right"><?=number_format($ttd,2)?></td>
        <td align="right"><?=number_format($ttc,2)?></td>
        </tr>



    </table></td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>Amount in Word :



	 (<? echo convertNumberCustom($ttc)?>)	 </td>

  </tr>

 
 
  
  

  <tr>

    <td>&nbsp;</td>

  </tr>

</table></form>

</body>

</html>

