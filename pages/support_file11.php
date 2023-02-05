<?php
 ob_start();
 session_start();






require_once 'base.php';
require_once 'create_id.php';
require_once 'module.php';
require_once 'function_mod.php';
require_once 'curd_function.php';
require_once 'director.class.php';
require_once 'db.php';
require_once 'function_module_create.php';



 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM project_info WHERE com_id='".$_SESSION['companyid']."'");
 $userRow=mysql_fetch_array($res);

 $webiste='http://http://icpbd-erp.com/51816/cmu_mod/page/dashboard.php';

?>



<?php ob_end_flush(); ?>