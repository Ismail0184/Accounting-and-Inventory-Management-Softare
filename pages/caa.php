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
 $res=mysql_query("SELECT * FROM create_company WHERE company_id=".$_SESSION['companyid']);
 $userRow=mysql_fetch_array($res);
?>
<html>
	<head>
		<title>jQuery Sum Demo</title>
	</head>
<body>

<table>
	<tr>
		<td>Product Name</td>
		<td>Price</td>
	</tr>
	
    
    <?php
	
	$result=mysql_query("Select * from transaction_inventory order by productcode");
	while($row=mysql_fetch_array($result)){
	 ?>
    
	<tr>
		<td>MOBILE POWER BANK (2800MAH)</td>
		<td><input type='text' class='price' /></td>
	</tr>
	
	<!--tr>
		<td>DISNEY NECK REST PILLOW (CHIP)</td>
		<td><input type='text' class='price' /></td>
	</tr--->
	
    <?php } ?>
    
    
    
    
    
	<tr>
		<td></td>
		<td><input type='text' id='totalPrice' disabled /></td>
	</tr>
</table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>

<script>
// we used jQuery 'keyup' to trigger the computation as the user type
$('.price').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.price').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#totalPrice').val(sum);
	
});
</script>

</body>
</html>