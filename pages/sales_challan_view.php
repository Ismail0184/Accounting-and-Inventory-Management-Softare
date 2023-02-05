<?php
 ob_start();
 session_start();
 require_once 'base.php';
  require_once 'create_id.php';
  require_once 'module.php';
  require_once 'in_word.php';
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
<title>Sales Invoice</title>

<style>
#header { height:160px;}
#body { height:710px;}

#bimage {background-repeat:no-repeat;  
  
 opacity:0.1;
 z-index:99;
 color:#000;
 margin-top:100px
 }



#footer { height:110px;}

</style>


<script type="text/javascript">

			function hide()

			{

				document.getElementById("pr").style.display = "none";

			}

		</script>



</head>

<body style=" font-family:Arial, Helvetica, sans-serif; font-size:13px;width:90%; margin-left:5%;margin-right:5%;">



<!------------------------------------first ------------------------------------->
<div id="header">



<table  style="width:100%; float:center;">





<tr>
<td valign="top" style="width:10%" rowspan="2">
<a href="dashboard.php"><img src="<?php echo $userRow[logourl];?>" style="height:130px; width:120px" /></a>
</td>


<td valign="top" style=" width:90%; padding-left:20px" colspan="4" > <font style="font-weight:bold; font-size:30px; color:#606; ">
 	<font style="color:green">Chowdhury</font> <font style="color:red">Computer</font> <font style="color:green">Service</font>


<?php //echo $userRow[company]; ?></font><br />

<font style="font-size:13px; color:blue; font-family:Tahoma, Geneva, sans-serif; "> <i>Your IT Solution.</i></font>   <?php //echo $userRow[slogan]; ?>
</td>
</tr>





<tr style="font-size:11px">
<td style="width:40%; padding-left:20px">
<b><u>Corporate Address: </u></b><br />

Bikrampur Plaza, Level-2, Shop No: 78,<br /> Jurain Railgate, Dhaka-1204. <br />Cell: +88 01679 634084
<br />Email: chowhurycms@yahoo.com
<br />Website: chowdhurycomputerservice.com
</td>


<td style="width:30%">
<b><u>Mirpur Branch: </u></b><br />

Shop: 10, Soiket Plaza,<br /> Mirpur 14, Dhaka-1216 
<br />Cell: +88 01689 995926
</td>


<td style="width:30%">
<b><u>Jurai Branch: </u></b><br />

Shop: 120, Level-2,<br /> Bikrampur Plaza, Dhaka-1204. 
<br />Cell: +88 01628 933601
</td>


</tr>
</table>

<table style="width:100%">

<tr>
<td style=" background-color:#000; height:0.1px"></td>
</tr>
</table>

</div>













<div id="body" align="center">




<img align="center" src="body.jpg" / id="bimage">


<div style="margin-top:-610px">

<!------------------------------------2nd ------------------------------------->
<?php 
$results=mysql_query("Select * from transaction_inventory where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
$quorow=mysql_fetch_array($results);

?>

<table  style="width:100%; float:right; font-size:11px; margin-top:50px">
<tr>

<td align="center"  colspan="3" style=""> <font style="font-weight:bold; font-size:25px; color:#09C; "><!--a href="sales_cash_edit.php?invoiceidedit=<?php echo $_GET[challanviewid];?>" style="text-decoration:underline"--><u>Sales Invoice</u><!--/a---></font></td></tr>
<tr></tr>
<tr></tr>

</table>





<div id="pr" style="margin-left:48%">
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





<table align="left" style="width:100%; margin-top:80px;  float:left;font-size:15px;">
<thead>
<tr style="text-decoration:underline; font-weight:bold">
<td colspan="3">Customer Information</td></tr>
</thead>


<tbody style="font-size:12px">

<?php
$re=mysql_query("Select * from transaction_inventory where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
$venrow=mysql_fetch_array($re);

 ?>
<tr>


<td style="width:25%">Customer Name</td>      <td>:</td> <td style="width:25%"> <?php echo $venrow[vendor]; ?></td>
<td align="right" style="width:25%">Invoice No: </td><td style=" width:25%" align="right"><?php echo $quorow[invoiceno]; ?></td></td>

</tr>








<tr>
<td>Contact Number</td> <td>:</td> <td> <?php echo $venrow[vendorphone]; ?></td>
<td align="right">Invoice Date: </td><td style=" width:5%" align="right"><?php echo $quorow[tdate]; ?></td>

</tr>




<tr>
<td>Address</td> <td>:</td> <td> <?php echo $venrow[vendoraddress]; ?></td>
<td align="right">Create By: </td><td style=" width:5%" align="right"><?php echo $quorow[transactionby]; ?></td>

</tr>

</tbody>
</table>









<?php 
$result=mysql_query("Select distinct invoiceno,tdate,vendorid,discount,advance from transaction_inventory where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
$rows=mysql_fetch_array($result);
?>

<table  style="width:100%; float:left; border:solid 1px #000; margin-top:20px; border-collapse:collapse;" cellpadding="5">
<thead style="background-color:#F7F7F7">
<tr style="height:30px; font-size:13px; border:solid 1px #000;">
<th style="border:solid 1px #000; width:3%">SL</th>
<th style="border:solid 1px #000;">Product Description</th>
<th style="border:solid 1px #000; width:10%">Warranty</th>
<th style="border:solid 1px #000;">Quantity</th>
<th style="border:solid 1px #000;">Rate</th>
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
	$qtytotals=substr($qtytotal,1);
	$qtyrow=substr($row[qty],1);
	$pricetotal=$pricetotal+$row[rate];
	$pricetotals=number_format($pricetotal,2);
	$amountotal=$amountotal+$row[amount];
	$totalamountsrow=$totalamountsrow+$amountotal;
	$totalamountsrows=number_format($totalamountsrow,2);
	$amountotals=number_format($amountotal,2);
	$rate=number_format($row[rate],2);
	$substrqty=substr($row[qty],1);
	$amo=$substrqty*$row[rate];
	$amos=number_format($amo,2);
	$toamos=$toamos+$amo;
	$toamo=number_format($toamos,2);
	
	 ?>
<tr align="center" style="border:solid 1px #000;">
<td style="border:solid 1px #000;"><?php echo $i; ?></td>
<td align="left" style="border:solid 1px #000;"><?php echo $row[product]; ?>-<?php echo $row[brand]; ?> - <?php echo $row[model]; ?><br /> Serial / Bar Code - <?php echo $row[productcode]; ?></td>
<td align="center" style="border:solid 1px #000;"><?php echo $row[warranty]; ?></td>
<td align="center" style="border:solid 1px #000;"><?php echo substr($row[qty],1); ?></td>
<td align="right" style="border:solid 1px #000;"><?php echo $rate; ?> </td>
<td align="right" style="border:solid 1px #000;"><?php echo $amos; ?></td>

</tr>
<?php 

$english_format_number = number_format($amountotal);
$toamoss=number_format($toamos,2);
?>

<?php } ?>

<!--tr style="background-color:#F7F7F7; font-weight:bold; font-size:13px"><td colspan="3" align="right" style="border:solid 1px #000;">Total Sales</td>
<td align="center" style="border:solid 1px #000;"><?php echo $qtytotals; ?></td>
<td></td>
<td align="right" style="border:solid 1px #000;"><?php echo $toamoss; ?> ৳</td>

</tr--->


</tbody>
</table>


<?php

$toamossss=getSVALUE("transaction_inventory", "Sum(Amount) as Amount", " where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");

$toamosssss=number_format($toamossss,2);
 ?>


<?php 

$discount=getSVALUE("transaction_inventory", "Sum(discount) as discount", " where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");

$advance=getSVALUE("transaction_inventory", "Sum(advance) as advance", " where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
 $due=getSVALUE("transaction_inventory", "Sum(due) as due", " where invoiceno='$_GET[challanviewid]' and companyid='$_SESSION[companyid]'");
 
?>


<table  style="width:100%; margin-top:10px; font-weight:" align="right">

<tr>
<td align="left" style="width:65%"><b>Amount in word:</b> <?php echo "".numberTowords("$toamos").""; ?> taka only</td>

<td style="35%">Discount</td><td><input type="text" readonly="readonly"  name="discount" value="<?php echo $discount; ?> ৳" style="text-align:right; font-weight:bold; border:solid 1px #000" /></td></tr>


<tr>
<td align="left" style="width:65%"></td>

<td style="35%">Advance</td><td><input type="text" readonly="readonly" name="advance" value="<?php echo $advance; ?> ৳" style="text-align:right; font-weight:bold; border:solid 1px #000" /></td></tr>

<tr>
<td align="left" style="width:65%"></td>
<td style="35%">Due</td><td><input type="text" readonly="readonly" name="due" value="<?php echo $due; ?> ৳" style="text-align:right; font-weight:bold; border:solid 1px #000" /></td></tr>

<tr>

<td align="left" style="width:50%; color:; text-decoration:underline">Terms & Conditions:</td>
<td style="50%">Total</td><td><input readonly="readonly" type="text" value="<?php echo $toamosssss; ?> ৳" name="total" style="text-align:right; font-weight:bold; border:solid 1px #000" /></td></tr>





<tr>
<td align="left" style="width:90%;">
1. Sales conducted on a cash basis, no credit.<br />
2. Product once sold will not be taken back.<br />
3. For warranty claim, contact with customer service.<br />
4. Warranty will be invalid if Sticker removed or Physical Damage & Burn Case.<br />



</td>
<td style="10%"></td><td></td></tr>



</table>








</div>
</div>
<div id="footer">
<table align="left" style="width:100%;   font-weight:bold; font-size:13px">



<tbody>
<tr>
<td style="width:50%; text-decoration:overline;" align="left">Customer's Signature</td>
<td style="width:50%; " align="right"><font style="text-decoration:overline;" >For <?php echo $userRow[company]; ?></font><br /></td>

</tr>

<tr style="background-color:transparent"><td colspan="2" style="height:30px"></td></tr>

<tr style="background-color:red"><td colspan="2" style="height:20px;"></td></tr>
<tr style="background-color:green"><td colspan="2" style="height:20px; text-align:center; color:#FFF">All kinds of Desktop , Laptop, Accessories, Sales & Serviceing.</td></tr>
</tbody>
</table>
</div>







</body>
</html>