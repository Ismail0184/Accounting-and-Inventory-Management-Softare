 <?php
require_once 'support_file.php';
$title="Late Attendance History";

$now=time();
$unique='id';
$unique_field='attendance_date';
$table="hrm_late_attendance";
$page="emp_acess_apply_for_late_attendance.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {		
		$sd=$_POST[attendance_date]; 
		$_POST[attendance_date]=date('Y-m-d' , strtotime($sd));	
		$_POST[PBI_ID]=$_SESSION[PBI_ID];
		$_POST[entry_by]=$_SESSION[PBI_ID];
		$_POST[status] = "PENDING";
        $_POST[entry_at] = date('Y-m-d H:i:s');
        $at=$_POST['late_entry_at'];
        $_POST['late_entry_at']=$at.',  '.$_POST[am_pm];
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';

    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $at=$_POST['late_entry_at'];
    $_POST['late_entry_at']=$at.',  '.$_POST[am_pm];

    $crud->update($unique);
    $type=1;
    //echo $targeturl;
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}}

?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=600,height=600,left = 383,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){	
$res="select l.id as rid, a.PBI_NAME, l.attendance_date, l.late_entry_at, l.late_reason,status,entry_at, (select PBI_NAME from personnel_basic_info where PBI_ID=l.authorised_by) as Approved_by,authorised_at as Approved_at from 
								personnel_basic_info a, hrm_late_attendance l where  a.PBI_ID=l.PBI_ID and  l.PBI_ID='".$_SESSION[PBI_ID]."' and l.attendance_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'
								order by l.attendance_date desc";	}
	
	 ?>

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Late Attendance</button></td>
            </tr></table>
<?=$crud->report_templates_with_data($res,$title);?>

</form>
<?php } ?>                



<?php require_once 'footer_content.php' ?>