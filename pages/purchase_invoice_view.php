<?php
 ob_start();
 session_start();
 require_once 'base.php';
  require_once 'create_id.php';
  require_once 'module.php';
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
  $res=mysql_query("SELECT * FROM company WHERE companyid='$_SESSION[companyid]'");
 $userRow=mysql_fetch_array($res);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Purchase Invoice</title>
</head>

<body style=" font-family:Arial, Helvetica, sans-serif; font-size:13px">
<div align="center" style=" width:90%; margin-left:5%;margin-right:5%">

<table  style="width:100%; float:left;">
<tr>
<!------------------------------------first ------------------------------------->
<td style="width:60%">
<table  style="width:100%; float:left;">
<tr>
<td valign="top" style="height:150px; width:10%"><img src="<?php echo $userRow[logourl];?>" style="height:120px" /></td>
<td valign="top" style="float:left"> <font style="font-weight:bold; font-size:30px; color:#606; "><?php echo $userRow[company]; ?></font><br />

<font style="font-size:11px; color:#333; font-family:; float:left"><?php echo $userRow[slogan]; ?><br /><?php echo $userRow[address]; ?><br />Tel: <?php echo $userRow[cnumber]; ?><br />Email: <?php echo $userRow[email]; ?><br />Website: <?php echo $userRow[website]; ?></font>
</td>
</tr>
</table>
</td>
















<!------------------------------------2nd ------------------------------------->
<?php 
$results=mysql_query("Select * from transaction_inventory where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
$quorow=mysql_fetch_array($results);

?>
<td style="width:40%">
<table  style="width:100%; float:right; font-size:11px">
<tr>

<td align="right"  colspan="3" style=""> <font style="font-weight:bold; font-size:25px; color:#09C; ">Purchase Invoice</font></td></tr>
<tr><th align="right">Invoice No: </th><td style=" width:5%" align="left"><?php echo $quorow[invoiceno]; ?></td></tr>
<tr><th align="right">Date of Purchase: </th><td style=" width:5%" align="left"><?php echo $quorow[tdate]; ?></td></tr>



</table>
</td>
</tr>

</table>










<table align="left" style="width:30%; margin-top:80px;  float:left;font-size:15px;">
<thead>
<tr style="text-decoration:underline; font-weight:bold">
<td colspan="3">Supplier Information</td></tr>
</thead>


<tbody style="font-size:13px">

<?php
$re=mysql_query("Select * from transaction_inventory where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
$venrow=mysql_fetch_array($re);


$vendorid=getSVALUE("procurement_supplier", "sname", " where ledgercode='$venrow[purchaseclint]' and companyid='$_SESSION[companyid]'");
$cnumber=getSVALUE("procurement_supplier", "cno", " where ledgercode='$venrow[purchaseclint]' and companyid='$_SESSION[companyid]'");
$caddress=getSVALUE("procurement_supplier", "address", " where ledgercode='$venrow[purchaseclint]' and companyid='$_SESSION[companyid]'");
 ?>
<tr><td>Company / Supplier Name</td>      <td>:</td> <td> <?php echo $vendorid; ?></td></tr>
<tr><td>Contact Number</td> <td>:</td> <td> <?php echo $cnumber; ?></td></tr>
<tr><td>Address</td> <td>:</td> <td> <?php echo $caddress; ?></td></tr>

</tbody>
</table>









<?php 
$result=mysql_query("Select distinct invoiceno,tdate,vendorid from transaction_inventory where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
$rows=mysql_fetch_array($result);
?>

<table  style="width:100%; float:left; border:solid 1px #000; margin-top:20px; border-collapse:collapse;" cellpadding="5">
<thead style="background-color:#CCC">
<tr style="height:30px; font-size:15px; border:solid 1px #000;">
<th style="border:solid 1px #000; width:3%">SL</th>
<th style="border:solid 1px #000;">Product Description</th>
<th style="border:solid 1px #000;">Warranty</th>
<th style="border:solid 1px #000;">Rate</th>
<th style="border:solid 1px #000;">Quantity</th>
<th style="border:solid 1px #000;">Amount</th>

</tr>
</thead>




<tbody>

<?php
$result=mysql_query("Select * from transaction_inventory where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
while($row=mysql_fetch_array($result)){
	$i=$i+1;
	
	$amounts=number_format($row[amount],2);
	$qtytotal=$qtytotal+$row[qty];
	$qtytotals=$qtytotal;
	$qtyrow=$row[qty];
	$pricetotal=$pricetotal+$row[rate];
	$pricetotals=number_format($pricetotal,2);
	$amountotal=$amountotal+$row[amount];
	$totalamountsrow=$totalamountsrow+$amountotal;
	$totalamountsrows=number_format($totalamountsrow,2);
	$amountotals=number_format($amountotal,2);
	$rate=number_format($row[rate],2);
	$amo=$qtyrow*$row[rate];
	$toamos=$toamos+$amo;
	$toamo=number_format($toamos,2);
	 ?>
<tr align="center" style="border:solid 1px #000;">
<td style="border:solid 1px #000;"><?php echo $i; ?></td>
<td align="left" style="border:solid 1px #000;"><?php echo $row[categorys]; ?>-<?php echo $row[brand]; ?>-<?php echo $row[model]; ?>- <?php echo $row[product]; ?> -  <?php echo $row[productcode]; ?></td>
<td align="center" style="border:solid 1px #000;"><?php echo $row[warranty]; ?> </td>
<td align="right" style="border:solid 1px #000;"><?php echo $rate; ?> </td>
<td align="center" style="border:solid 1px #000;"><?php echo $qtyrow; ?></td> 
<td align="right" style="border:solid 1px #000;"><?php echo $amo; ?></td>

</tr>
<?php 

$english_format_number = number_format($amountotal);
?>

<?php } ?>

<tr style="background-color:#CCC; font-weight:bold; font-size:15px"><td colspan="4" align="right" style="border:solid 1px #000;">Total</td>
<td align="center" style="border:solid 1px #000;"><?php echo $qtytotals; ?></td>

<td align="right" style="border:solid 1px #000;"><?php echo $toamo; ?> ৳</td>

</tr>




</tbody>
</table>


<table align="left" style="width:100%; margin-top:100px;  font-weight:bold; font-size:13px">

<!--tr style="padding-top:20px; padding-bottom:20px"><td colspan="5">In Word: <?php echo $english_format_number; ?></td></tr--->

<tbody>
<tr>
<td style="width:50%; text-decoration:overline;" align="left">Seller's Signature</td>
<td style="width:50%; text-decoration:overline;" align="right">For <?php echo $userRow[company];; ?></td>

</tr>
</tbody>
</table>







</div>
</body>
</html>