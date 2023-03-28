<?php
require_once 'support_file.php';
$now=date("Y-m-d H:i:s");
$table="user_permission_matrix_reportview";

if(prevent_multi_submit()) {
    extract($_POST);
    $get_optgroup_label_id=find_a_field('module_reportview_report','optgroup_label_id','report_id='.$report_id);
    $module_id=find_a_field('module_reportview_report','module_id','report_id='.$report_id);
    $report_id = mysqli_real_escape_string($conn, $report_id);
    $status = mysqli_real_escape_string($conn, $status);
    $report_in_database=find_a_field(''.$table.'','COUNT(report_id)','report_id='.$report_id.' and user_id="'.$_SESSION['MIS_permission_matrix'].'"');
    if($report_id>0){
    if($report_in_database>0) {
        $sql = mysqli_query($conn, "UPDATE ".$table." SET status='$status',entry_by='".$_SESSION['userid']."',entry_at='".$now."' WHERE report_id='" . $report_id . "' and user_id='" . $_SESSION['MIS_permission_matrix'] . "'");
    } else {
        $sql = mysqli_query($conn, "INSERT INTO ".$table." (report_id,optgroup_label_id,module_id,user_id,entry_by,entry_at,status,section_id,company_id) 
        VALUES ('$report_id','$get_optgroup_label_id','$module_id','".$_SESSION['MIS_permission_matrix']."','".$_SESSION['userid']."','$now','1','".$_SESSION['sectionid']."','".$_SESSION['companyid']."')");
    }}}?>