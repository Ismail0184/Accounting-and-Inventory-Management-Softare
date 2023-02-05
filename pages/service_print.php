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

<title>Service Invoice</title>
<style>


#table, #th, #td, #td {
   border: 1px solid black;
}
</style>

</head>



<body style=" font-family:Arial, Helvetica, sans-serif; font-size:13px">

<div align="center" style=" width:90%; margin-left:5%;margin-right:5%">



<table  style="width:100%; float:left;">

<tr>

<!------------------------------------first ------------------------------------->

<td style="width:60%">

<table  style="width:100%; float:left;">

<tr>

<td valign="top" style="height:170px; width:10%"><img src="<?php echo $userRow[	logourl]; ?>" style="height:100px" /></td>

<td valign="top" style="float:left"> <font style="font-weight:bold; font-size:30px; color:#606; "><?php echo $userRow[company]; ?></font><br />



<font style="font-size:11px; color:#333; font-family:; float:left"><?php echo $userRow[slogan]; ?><br /><?php echo $userRow[address]; ?><br />Tel: <?php echo $userRow[cnumber]; ?><br />Email: <?php echo $userRow[email]; ?><br />Website: <?php echo $userRow[website]; ?></font>

</td>

</tr>

</table>

</td>




<!------------------------------------2nd ------------------------------------->

<?php 

$results=mysql_query("Select * from service where serviceid='$_GET[serviceid]' and companyid='$_SESSION[companyid]'");

$quorow=mysql_fetch_array($results);



?>

<td style="width:40%">

<table  style="width:100%; float:right; font-size:11px">

<tr>



<td align="right"  colspan="3" style=""> <font style="font-weight:bold; font-size:25px; color:#09C; ">Receiving Copy</font></td></tr>

<tr><th align="right">CCS No: </th><td style=" width:5%" align="left"><?php echo $_GET[serviceid]; ?></td></tr>

<tr><th align="right">Received Date: </th><td style=" width:5%" align="left"><?php echo $quorow[createdate]; ?></td></tr>

<tr><th align="right">Delivery Date: </th><td style=" width:5%" align="left"><?php echo $quorow[dd]; ?></td></tr>





</table>

</td>

</tr>



</table>





















<table align="left" style="width:30%; margin-top:50px;  float:left;font-size:20px;" >

<thead>

<tr style="text-decoration:underline; font-weight:bold">

<td colspan="3">Customer Details</td></tr>

</thead>





<tbody style="font-size:13px">



<?php

$re=mysql_query("Select * from service where serviceid='$_GET[serviceid]' and companyid='$_SESSION[companyid]'");

$venrow=mysql_fetch_array($re);



 ?>

<tr><td style="width:30%">Name</td>      <td>:</td> <td> <?php echo $venrow[cp]; ?></td></tr>

<tr><td>Contact Number</td> <td>:</td> <td> <?php echo $venrow[cn]; ?></td></tr>

<tr><td>Address</td> <td>:</td> <td> <?php echo $venrow[address]; ?></td></tr>



</tbody>

</table>























<table  style="width:100%;  margin-top:30px; border-collapse:collapse" id="table">

<thead style="font-size:13px; background-color:#CCC">

<tr style="height:30px; font-size:14px; border:solid 1px #000;">

<th style="width:3%" id="th">SL</th>

<th style="" id="th">Product Details</th>

<th style="" id="th">Problem Details</th>



<th style="" id="th">Service Charge</th>



</tr>

</thead>









<tbody>


<?php 

$result=mysql_query("Select * from service where serviceid='$_GET[serviceid]' and companyid='$_SESSION[companyid]'");

$rows=mysql_fetch_array($result);
$amountotal=$rows[sc];
$amountotals=number_format($amountotal,2);
?>


<tr align="center" id="th">

<td id="td">1</td>

<td align="left" id="td">Brand: <?php echo $rows[brand]; ?><br />

Model: <?php echo $rows[model]; ?><br />
Serial: <?php echo $rows[ime]; ?><br />
<?php echo $rows[productdetails]; ?></td>

<td align="center" id="td">

<?php if ($rows[power]=='Yes'){ ?>Power: <?php echo $rows[power]; ?>, <br><?php } ?>
                            <?php if ($rows[display]=='Yes'){ ?>Display: <?php echo $rows[display]; ?>, <br><?php } ?>
                            <?php if ($rows[hang]=='Yes'){ ?>Hang: <?php echo $rows[hang]; ?>, <br><?php } ?>
                            <?php if ($rows[ssetup]=='Yes'){ ?>Setup: <?php echo $rows[ssetup]; ?>, <br><?php } ?>

</td>



<td align="right" id="td"><?php echo $amountotals; ?> ৳</td>



</tr>







<tr style="background-color:#CCC; font-weight:bold; font-size:14px"><td colspan="3" align="right" id="td">Total Service Charge </td>
<td align="right" id="td"><?php echo $amountotals; ?> ৳</td>



</tr>









</tbody>

</table>





<table align="left" style="width:100%; margin-top:100px;  font-weight:bold; font-size:12px">






<tbody>

<tr>

<td style="width:50%; text-decoration:overline;" align="left">Receiver's Signature</td>

<td style="width:50%; text-decoration:overline;" align="right">For <?php echo $userRow[company];; ?></td>



</tr>

</tbody>

</table>















</div>

</body>

</html>