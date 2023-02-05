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
<title>Quotation</title>
</head>

<body style=" font-family:Arial, Helvetica, sans-serif; font-size:13px">
<div align="center" style=" width:90%; margin-left:5%;margin-right:5%">

<table  style="width:100%; float:left;">
<tr>
<!------------------------------------first ------------------------------------->
<td style="width:60%">
<table  style="width:100%; float:left;">
<tr>
<td valign="top" style="height:150px; width:10%"><img src="<?php echo $userRow[logourl];?>" style="height:100px" /></td>
<td valign="top" style="float:left"> <font style="font-weight:bold; font-size:30px; color:#606; "><?php echo $userRow[company]; ?></font><br />

<font style="font-size:11px; color:#333; font-family:; float:left"><?php echo $userRow[slogan]; ?><br /><?php echo $userRow[address]; ?><br />Tel: <?php echo $userRow[cnumber]; ?><br />Email: <?php echo $userRow[email]; ?><br />Website: <?php echo $userRow[website]; ?></font>
</td>
</tr>
</table>
</td>
















<!------------------------------------2nd ------------------------------------->
<?php 
$results=mysql_query("Select * from sales_quotations where quotationsref='$_GET[quotationviewid]' and companyid='$_SESSION[companyid]'");
$quorow=mysql_fetch_array($results);

?>
<td style="width:40%">
<table  style="width:100%; float:right; font-size:11px">
<tr>

<td align="right"  colspan="3" style=""> <font style="font-weight:bold; font-size:25px; color:#09C; ">QUOTATION</font></td></tr>
<tr><th align="right">Ref: </th><td style=" width:5%" align="left"><?php echo $quorow[quotationsref]; ?></td></tr>
<tr><th align="right">Date of Issue: </th><td style=" width:5%" align="left"><?php echo $quorow[dateofissue]; ?></td></tr>
<tr><th align="right">Date of Expire: </th><td style=" width:5%" align="left"><?php echo $quorow[dateofexpire]; ?></td></tr>


</table>
</td>
</tr>

</table>










<table align="left" style="width:30%; margin-top:50px;  float:left;font-size:15px;">
<thead>
<tr style="text-decoration:underline; font-weight:bold">
<td colspan="3">Vendor to</td></tr>
</thead>


<tbody style="font-size:12px">
<tr><td>MRP No</td> <td>:</td> <td> <?php echo $quorow[mrp]; ?></td></tr>
<?php
$re=mysql_query("Select * from procurement_supplier where id='$quorow[vendorid]' and companyid='$_SESSION[companyid]'");
$venrow=mysql_fetch_array($re);

 ?>
<tr><td>To</td>      <td>:</td> <td> <?php echo $venrow[sname]; ?></td></tr>
<tr><td>Tel</td> <td>:</td> <td> <?php echo $venrow[cno]; ?></td></tr>
<tr><td>Address</td> <td>:</td> <td> <?php echo $venrow[address]; ?></td></tr>
<tr style="font-weight:bold; font-size:15px"><td>Subject</td> <td>:</td> <td> <?php echo $quorow[subject]; ?></td></tr>
</tbody>
</table>









<?php 
$result=mysql_query("Select distinct quotationsref,subject,dateofissue,dateofexpire,mrp,tandc,vendorid from sales_quotations where quotationsref='$_GET[quotationviewid]' and companyid='$_SESSION[companyid]'");
$rows=mysql_fetch_array($result);
?>

<table  style="width:100%; float:left; border:solid 1px #000; margin-top:20px">
<thead style="font-size:13px; background-color:#CCC">
<tr style="height:30px; font-size:14px; border:solid 1px #000;">
<th style="border:solid 1px #000;">MRP</th>
<th style="border:solid 1px #000;">Name of Goods</th>
<th style="border:solid 1px #000;">Unit</th>
<th style="border:solid 1px #000;">Qty</th>
<th style="border:solid 1px #000;">Unit Price</th>
<th style="border:solid 1px #000;">Value</th>
<th style="border:solid 1px #000;">Remarks</th>
</tr>
</thead>




<tbody>

<?php
$result=mysql_query("Select * from sales_quotations where quotationsref='$_GET[quotationviewid]' and companyid='$_SESSION[companyid]'");
while($row=mysql_fetch_array($result)){
	
	$amounts=number_format($row[amount],2);
	$qtytotal=$qtytotal+$row[qty];
	
	$pricetotal=$pricetotal+$row[rate];
	$pricetotals=number_format($pricetotal,2);
	$amountotal=$amountotal+$row[amount];
	$amountotals=number_format($amountotal,2);
	
	 ?>
<tr align="center" style="border:solid 1px #000;"><td><?php echo $row[mrp]; ?></td>
<td align="left"><?php echo $row[category]; ?>-<?php echo $row[brand]; ?>-<?php echo $row[model]; ?>-<?php echo $row[product]; ?></td>
<td align="center"><?php echo $row[unit]; ?></td>
<td align="center"><?php echo $row[qty]; ?></td>
<td align="right"><?php echo $row[rate]; ?></td>
<td align="right"><?php echo $amounts; ?></td>
<td align="right"><?php echo $row[remarks]; ?></td></tr>

<?php } ?>

<tr style="background-color:#CCC; font-weight:bold; font-size:14px"><td colspan="3" align="right">Total</td>
<td align="center"><?php echo $qtytotal; ?></td>
<td align="right"><?php echo $pricetotals; ?></td>
<td align="right"><?php echo $amountotals; ?></td>
<td></td>

</tr>



</tbody>
</table>


<table align="left" style="width:75%; margin-top:70px; float:left; border:solid 1px #000">
<thead>
<tr style="background-color:#CCC; text-decoration:underline; font-weight:bold">
<td>Terms and conditions of the above quotation be as under</td></tr>
</thead>


<tbody>
<tr><td><?php echo $rows[tandc]; ?></td></tr>


</tbody>
</table>

<table align="left" style="width:50%; margin-top:50px; float:left; font-weight:bold; font-size:12px">



<tbody>
<tr><td>With Best Regards,<br /></td></tr>
<tr><td><br /></td></tr>
<tr><td><?php echo $userRow[contactperson];?></td></tr>
<tr><td><?php echo $userRow[company];?></td></tr>
<tr><td><?php echo $userRow[address];?></td></tr>
<tr><td>Tel: <?php echo $userRow[cnumber];?></td></tr>
<tr><td>Email: <?php echo $userRow[email];?></td></tr>
<tr><td>Website: <?php echo $userRow[website];?></td></tr>
</tbody>
</table>
</div>
</body>
</html>