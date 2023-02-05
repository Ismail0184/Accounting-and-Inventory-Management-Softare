 <?php
require_once 'support_file.php';
$title="Apply for Early Leave";

$now=time();
$unique='id';
$unique_field='type';
$table="hrm_leave_info";
$page="hrm_apply_for_early_leave.php";
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
		$_POST[total_days]=		"0.5";
		$_POST[PBI_ID]=$_SESSION[PBI_ID];
		$_POST[leave_status] = "Waiting";
        $_POST[entry_at] = date('Y-m-d H:i:s');
		$_POST[half_or_full]='Half';
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=600,height=600,left = 383,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){	
$res='select l.'.$unique.',l.'.$unique.' as Leave_ID,l.s_date as start_date,l.total_days,l.reason,l.leave_status as status,(select PBI_NAME from personnel_basic_info where PBI_ID=l.PBI_DEPT_HEAD) as approved_by,approved_at from '.$table.' l
								where 
								half_or_full in ("Half") and
								l.PBI_ID='.$_SESSION[PBI_ID].'
								 order by l.id DESC';	}
	
	 ?>

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Early Leaves</button></td>
            </tr></table>
<?=$crud->report_templates_with_data($res,$title);?>

</form>
<?php } ?>    
                          

                 
                
        
<?php require_once 'footer_content.php' ?>
