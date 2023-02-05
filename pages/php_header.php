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