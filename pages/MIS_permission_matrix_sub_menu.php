<?php
require_once 'support_file.php';
$now=date("Y-m-d H:i:s");
$table="user_permission_matrix_sub_menu";
if(prevent_multi_submit()) {
    extract($_POST);
    $sub_menu_id = mysqli_real_escape_string($conn, $sub_menu_id);
    $status = mysqli_real_escape_string($conn, $status);
    $main_menu_id=find_a_field('dev_sub_menu','main_menu_id','sub_menu_id='.$sub_menu_id.'');
    $sub_menu_id_in_database=find_a_field(''.$table.'','COUNT(sub_menu_id)','sub_menu_id='.$sub_menu_id.' and user_id="'.$_SESSION[MIS_permission_matrix].'"');
    if($sub_menu_id>0){
    if($sub_menu_id_in_database>0) {
        $sql = mysqli_query($conn, "UPDATE ".$table." SET status='".$status."',powerby='".$_SESSION[userid]."',powerdate='".$now."',ip='".$ip."' WHERE sub_menu_id='".$sub_menu_id."' and user_id='" . $_SESSION[MIS_permission_matrix] . "'");
    } else {
        $sql = mysqli_query($conn, "INSERT INTO ".$table." (main_menu_id,sub_menu_id,user_id,powerby,powerdate,status,section_id,company_id,ip) 
        VALUES ('$main_menu_id','$sub_menu_id','$_SESSION[MIS_permission_matrix]','$_SESSION[userid]','$now','1','$_SESSION[sectionid]','$_SESSION[companyid]','$ip')");
    }}}

?>