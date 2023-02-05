<?php
     session_start();

	$db_host = "localhost";
	$db_name = "laksura";
	$db_user = "laksura";
	$db_pass = "Allahis1!!@@##";
	
	try{
		
		$db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}


?>