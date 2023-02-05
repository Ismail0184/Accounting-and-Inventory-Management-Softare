 <?php
require_once 'support_file.php';
$title="Apply for Leave";

$now=time();
$unique='id';
$unique_field='type';
$table="hrm_leave_info";
$page="hrm_apply_for_leave.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
		$sd=$_POST[s_date];
		$ed=$_POST[e_date];
		$_POST[s_date]=date('Y-m-d' , strtotime($sd));
        $_POST[e_date]=date('Y-m-d' , strtotime($ed));
		$date1=date_create($_POST[s_date]);
        $date2=date_create($_POST[e_date]);
        $diff=date_diff($date1,$date2);
		$_POST[total_days]=		$diff->format("%R%a")+1;
		$_POST[PBI_ID]=$_SESSION[PBI_ID];
		$_POST[leave_status] = "Waiting";
        $_POST[entry_at] = date('Y-m-d H:i:s');
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }


//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>window.close(); </script>";
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 250,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>


<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){
$res='select l.'.$unique.',l.'.$unique.' as Code,l.s_date as start_date,l.e_date as end_date,l.total_days,l.reason,l.dept_head_status as status,(select PBI_NAME from personnel_basic_info where PBI_ID=l.PBI_DEPT_HEAD) as approved_by,dept_head_aprv_at from '.$table.' l
								where
								half_or_full in ("Full") and
								l.PBI_ID='.$_SESSION[PBI_ID].'
								 order by l.id DESC';	}

	 ?>

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Leave</button></td>
            </tr></table>
<?=$crud->report_templates_with_status($res,$title);?>

</form>
<?php } ?>





<?php endif; mysqli_close($conn); ?>
<?=$html->footer_content();?>
