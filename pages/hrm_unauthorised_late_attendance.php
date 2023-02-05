<?php
require_once 'support_file.php';
$title="Un-Authorised Late Attendance List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dfromM=date('Y-m-1');
$dtoM=date('Y-m-d');

$resultdets=mysql_query("Select * from warehouse_other_issue where ".$unique."='".$_GET[$unique]."'") ;
$getid=mysql_fetch_array($resultdets);
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='id';
$unique_field='PBI_ID';
$table="hrm_late_attendance";

$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$authorused_status="Approve";
$page="hrm_unauthorised_late_attendance.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

$leaverequest=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique]);

if(prevent_multi_submit()){


//for modify..................................
    if(isset($_POST['confirm']))
    {
        $_POST['status']="RECOMMENDED";
        $_POST['authorised_at']=date("Y-m-d h:i:sa");
        $crud->update($unique);
        $type=1;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['reject']))
    {
        $_POST['status']="REJECTED";		
		$_POST['authorised_by']=$_SESSION[userid];
        $_POST['authorised_at']=date("Y-m-d h:i:sa");
        $crud->update($unique);
        $type=1;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

if(isset($_POST[viewreport])){	
$res='select r.'.$unique.',r.'.$unique.' as Req_No,DATE_FORMAT(r.attendance_date, "%d %M, %Y") as Late_Date,r.late_entry_at as late_at,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Application_By,
							 r.late_reason as late_reason,
							 CONCAT((select COUNT(id) from '.$table.' where id not in ("'.$$unique.'") and status not in ("PENDING")  and attendance_date between "'.$dfromM.'" and "'.$dtoM.'" and  PBI_ID=r.PBI_ID)," Days") AS "total_late(Current Month)",
							 CONCAT((select COUNT(id) from '.$table.' where id not in ("'.$$unique.'") and status not in ("PENDING") and attendance_date between "'.$dfrom.'" and "'.$dto.'" and  PBI_ID=r.PBI_ID)," Days") as "total_late(Current Year)"
				  from '.$table.' r
				  WHERE 
				    r.authorised_by="'.$_SESSION[PBI_ID].'" and 
				    r.attendance_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"
				   order by r.'.$unique.' DESC';
} else {
	$res='select r.'.$unique.',r.'.$unique.' as Req_No,DATE_FORMAT(r.attendance_date, "%d %M, %Y") as Late_Date,r.late_entry_at as late_at,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Application_By,
							 r.late_reason as late_reason,
							 CONCAT((select COUNT(id) from '.$table.' where id not in ("'.$$unique.'") and status not in ("PENDING")  and attendance_date between "'.$dfromM.'" and "'.$dtoM.'" and  PBI_ID=r.PBI_ID)," Days") AS "total_late(Current Month)",
							 CONCAT((select COUNT(id) from '.$table.' where id not in ("'.$$unique.'") and status not in ("PENDING") and attendance_date between "'.$dfrom.'" and "'.$dto.'" and  PBI_ID=r.PBI_ID)," Days") as "total_late(Current Year)"
				  from '.$table.' r
				  WHERE 
				    r.authorised_by="'.$_SESSION[PBI_ID].'" and 
				    r.status="'.$required_status.'" 
				   order by r.'.$unique.' DESC';
	}
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=400,left = 300,top = -1");}
</script>
<?php 
 if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; }?>

<?php if(!isset($_GET[$unique])){ ?>
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >    
     <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Authorized Late Attendance</button></td>
            </tr></table>
            
            
<?=$crud->report_templates_with_data($res,$link)?>            
            
    </form>   
    
    
    <!-------------------End of  List View --------------------->
<?php } ?>
<?php if(isset($_GET[$unique])){ ?>


    <!-- input section-->

<form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size:11px" method="post">
    <? require_once 'support_html.php';?>

                      <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px;">
                     <thead>
                     <tr style="background-color: bisque">
                         <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold;">Late Attendance Request</th>
                     </tr>

                     </thead>
                     <thead>
                     <tr>

                         
                         <th style="text-align: center">Date</th>
                         <th style="text-align: center">Late At</th>
                         <th style="text-align: center">Late Days<br>(Current Year)</th>
                         <th style="text-align: center">Late Days<br>(Current Month)</th>
                         <th style="text-align: center">Late <br>Reason</th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr>
                         
                         <td style="text-align: center;vertical-align:middle"><?=$leaverequest->attendance_date;?></td>
                         <td style="text-align: center;vertical-align:middle"><?=$leaverequest->late_entry_at;?>, <?=$leaverequest->am_pm;?></td>
                         <td style="text-align: center;vertical-align:middle"><?php $leave_taken=find_a_field("".$table."","COUNT(id)","status not in ('PENDING') and attendance_date between '$dfrom' and '$dto' and  PBI_ID='".$leaverequest->PBI_ID."'"); if($leave_taken>0){ echo $leave_taken,', Days';} else echo ''; ?></td>
                         <td style="text-align: center; vertical-align:middle"><?php $leave_takenM=find_a_field("".$table."","COUNT(id)","status not in ('PENDING') and attendance_date between '$dfromM' and '$dtoM' and  PBI_ID='".$leaverequest->PBI_ID."'"); if($leave_takenM>0){ echo $leave_takenM,', Days';} else echo ''; ?></td>
                         <td style="text-align: center; vertical-align: middle"><?=$leaverequest->late_reason;?></td>
                     </tr>

                     </tbody>
    </table>


    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This Application has been Authorized!!</i></h6>';} else { ?>
        <table align="center" style="width:90%;font-size:12px;">
            <tr>                
                <td>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea type="text"  name="reasons_for_rejection" id="reasons_for_rejection" style="height: 40px; font-size:11px" placeholder="Reasons for rejection" ></textarea>
                        </div></div></td>

                <td style="float:right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea type="text"  name="reasons_for_rejection" id="reasons_for_rejection" style="height: 40px; font-size:11px" placeholder="Notes to HR" ></textarea>
                        </div></div></td>
            </tr>
            
            
            <tr>
                <td>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" style="font-size:12px" onclick='return window.confirm("Are you confirm to reject?");' name="reject" id="reject" class="btn btn-danger">Reject the Application</button>
                        </div></div></td>
                <td style="float:right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" style="font-size:12px" onclick='return window.confirm("Are you confirm to approve?");' name="confirm" id="confirm" class="btn btn-primary">Approve & Forward to HR</button>
                        </div></div>
                </td>
            </tr>           
            
            </table>
    <?php } ?>
<?php } ?>
</form>




<?php require_once 'footer_content.php' ?>
