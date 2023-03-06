<?php
 ob_start();
 session_start();
 
require ("../app/db/base.php");
require ("../app/classes/create_id.php");
require ("../app/classes/module.php");
require ("../app/classes/function_mod.php");
require ("../app/classes/curd_function.php");
require ("../app/classes/director.class.php");
require ("../app/db/db.php");
require ("../app/classes/function_module_create.php");
$crud      = new crud();
$html      = new htmldiv();
$sectionid = @$_GET['sectionid'];
$moduleGET = @$_GET['module'];
$languageGET = @$_GET['language'];


 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }

if($_SESSION['language']=='Bangla') {
    $module_get = "Select m.id as id,p.module_id,m.fa_icon,m.fa_icon_color,m.modulename_BN as module_details,m.module_short_name as modulename  from 
dev_modules m,user_permissions_module p where 
 m.module_id=p.module_id and m.status>0 and p.user_id='" . $_SESSION['userid'] . "' and 
p.status>0  
order by m.sl";
} else if($_SESSION['language']=='English') {
    $module_get = "Select m.id as id,p.module_id,m.fa_icon,m.fa_icon_color,m.modulename as module_details,m.module_short_name as modulename  from 
dev_modules m,user_permissions_module p where 
 m.module_id=p.module_id and m.status>0 and p.user_id='" . $_SESSION['userid'] . "' and 
p.status>0  
order by m.sl";
}
if($_SESSION['language']=='Bangla') {
$main_manu_get="SELECT dmm.main_menu_id,dmm.quick_access_url,dmm.faicon,dmm.main_menu_name_BN as main_menu_name from dev_main_menu dmm, user_permission_matrix_main_menu pmm where dmm.main_menu_id=pmm.main_menu_id and dmm.module_id=".$_SESSION['module_id']." and pmm.user_id=".$_SESSION["userid"]." order by dmm.sl";
} else if($_SESSION['language']=='English') {
    $main_manu_get="SELECT dmm.main_menu_id,dmm.quick_access_url,dmm.faicon,dmm.main_menu_name from dev_main_menu dmm, user_permission_matrix_main_menu pmm where dmm.main_menu_id=pmm.main_menu_id and dmm.module_id=".$_SESSION['module_id']." and pmm.user_id=".$_SESSION["userid"]." order by dmm.sl";
}
$url_current=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$link='?module=';
if($sectionid){
    unset($_SESSION['sectionid']);
    unset($_SESSION['section_name']);
    $_SESSION['sectionid']=$sectionid;
}
if($moduleGET){
    unset($_SESSION['module_id']);
    unset($_SESSION['module_name']);
    $_SESSION['module_id']=$moduleGET;
    $_SESSION['module_name']=find_a_field('module_department','module_short_name','module_id='.$_SESSION['module_id'].'');
    header('Location: dashboard.php');
}
if($languageGET){
    unset($_SESSION['language']);
    $_SESSION['language']=$languageGET;
    header('Location: dashboard.php');
}
ob_end_flush();?>