<?php
require_once 'support_file.php';
$title='Report';


$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));

list( $day,$month,$year1) = split('[/.-]', $_REQUEST['datefrom']);
$dofdate= '20'.$year1.'-'.$month.'-'.$day;

list($dayt,$montht,$yeart) = split('[/.-]', $_REQUEST['dateto']);
$dotdate= '20'.$yeart.'-'.$montht.'-'.$dayt;

$warehouseid=$_POST[warehouse_id];
$unique='PBI_ID';
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report</title>
    <script type="text/javascript">

        function hide()

        {

            document.getElementById("pr").style.display = "none";

        }

    </script>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("hrm_employee_basic_info.php?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = 5");}
    </script>

</head>

<body style="font-family: cursive;">





<div id="pr" style="margin-left:48%">
    <div align="left">
        <form id="form1" name="form1" method="post" action="">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>



<?php if ($_POST['reporttypes']=='3002'):
/////////////////////////////////////Received and Payments----------------------------------------------------------

    if($_POST['PBI_DESIGNATION']>0) 					$PBI_DESIGNATION=$_POST['PBI_DESIGNATION'];
    if(isset($PBI_DESIGNATION))				{$PBI_DESIGNATION_CON=' and p.PBI_DESIGNATION='.$PBI_DESIGNATION;}
    if($_POST['department']>0) 					$department=$_POST['department'];
    if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}
    ?>

    <h2 align="center" style="margin-top:-5px"><?=$_SESSION['company_name'];?></h2>
    <h5 align="center" style="margin-top:-15px"><?=$_POST[PBI_JOB_STATUS];?> Employee List</h5>
    <?php if($_POST['department']){?>
    <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
    <?php } ?>
     <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-size:11px; height: 30px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Emp ID</th>
            <th style="border: solid 1px #999; padding:2px; width:10%">Unique ID</th>
            <th style="border: solid 1px #999; padding:2px">Full Name</th>
            <th style="border: solid 1px #999; padding:2px">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Department</th>
            <th style="border: solid 1px #999; padding:2px">Joining Date</th>
            <th style="border: solid 1px #999; padding:2px">Date of Birth</th>
            <th style="border: solid 1px #999; padding:2px">Mobile</th>
            <th style="border: solid 1px #999; padding:2px">Corp. Mobile</th>
            <th style="border: solid 1px #999; padding:2px">Email</th>
            <th style="border: solid 1px #999; padding:2px">Blood Group</th>
        </tr></thead>
        <tbody>
        <? 	$result=mysql_query("SELECT  p.*,d.*,des.* FROM 
							 
							personnel_basic_info p,
							department d,
							designation des
							 where 							 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and 
							 p.PBI_DESIGNATION=des.DESG_ID	 and 
							 p.PBI_JOB_STATUS='".$_POST['PBI_JOB_STATUS']."'
							 ".$department_CON.$PBI_DESIGNATION_CON."			 
							  order by p.PBI_ID");
        while($data=mysql_fetch_object($result)){

        ?>
        <tr style="cursor: pointer; font-size: 12px; font-family: Calibri" onclick="DoNavPOPUP('<?=$data->PBI_ID;?>', 'TEST!?', 600, 700)">
            <td style="border: solid 1px #999; padding:2px"><?=$i=$i+1;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_ID;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_ID_UNIQUE;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_NAME;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->DESG_DESC;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->DEPT_SHORT_NAME;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_DOJ;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_DOB;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_MOBILE;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_PHONE;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->PBI_EMAIL;?></td>
            <td style="border: solid 1px #999; padding:2px"><?=$data->ESSENTIAL_BLOOD_GROUP;?></td>
        </tr>
        <?php } ?>

        </tbody>
    </table> </div>
    </div>
    </div>


<?php elseif ($_POST['reporttypes']=='2001'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-10px">Leave Summery</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px">Leave By</th>
            <th style="border: solid 1px #999; padding:2px">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Department</th>
            <th style="border: solid 1px #999; padding:2px; ">leave Type</th>
            <th style="border: solid 1px #999; padding:2px; ">Authorized By</th>
            <th style="border: solid 1px #999; padding:2px">From</th>
            <th style="border: solid 1px #999; padding:2px; ">To</th>
            <th style="border: solid 1px #999; padding:2px; ">Total Days</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and p.or_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 					$department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  p.*,l.*,
(select PBI_NAME from personnel_basic_info where PBI_ID=l.PBI_DEPT_HEAD) as authorized_by,
des.*,dep.*,t.*	
					
				from
				hrm_leave_info l,
				personnel_basic_info p,							
				designation des,
				department dep,
				hrm_leave_type t
				 
				where 
				l.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				t.id=l.type and 
				l.half_or_full in ("Full") and
				l.leave_status in ("GRANTED") and 
				l.s_date between "'.$from_date.'" and "'.$to_date.'" '.$PBI_ID_con.$department_CON.'
				
				order by l.id desc
				';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->DEPT_DESC;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->leave_type_name;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->authorized_by;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->s_date;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->e_date;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->total_days);?></td>

            </tr>
            <?php  $total_leave_days=$total_leave_days+$data->total_days; } ?>
        <tr style="font-size:12px"><td colspan="8" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($total_leave_days)?></strong></td>
        </tr>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='2002'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-10px">Early Leave Summery</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px">Leave By</th>
            <th style="border: solid 1px #999; padding:2px">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Department</th>
            <th style="border: solid 1px #999; padding:2px; ">leave Type</th>
            <th style="border: solid 1px #999; padding:2px; ">Authorized By</th>
            <th style="border: solid 1px #999; padding:2px">Date</th>
            <th style="border: solid 1px #999; padding:2px; ">Total Days</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and p.or_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 					$department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  p.*,l.*,
(select PBI_NAME from personnel_basic_info where PBI_ID=l.PBI_DEPT_HEAD) as authorized_by,
des.*,dep.*
					
				from
				hrm_leave_info l,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where 
				l.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				l.half_or_full in ("Half") and
				l.leave_status in ("GRANTED") and 
				l.s_date between "'.$from_date.'" and "'.$to_date.'" '.$PBI_ID_con.$department_CON.'
				
				order by l.id desc
				';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->DEPT_DESC;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px">Early Leave</td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->authorized_by;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->s_date;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->total_days);?></td>

            </tr>
            <?php  $total_leave_days=$total_leave_days+$data->total_days; } ?>
        <tr style="font-size:12px"><td colspan="7" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($total_leave_days)?></strong></td>
        </tr>
        </tbody>
    </table>




<?php elseif ($_POST['reporttypes']=='2003'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-10px">Outdoor Duty Attendance Summery</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px">Leave By</th>
            <th style="border: solid 1px #999; padding:2px">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Department</th>
            <th style="border: solid 1px #999; padding:2px; ">leave Type</th>
            <th style="border: solid 1px #999; padding:2px; ">Authorized By</th>
            <th style="border: solid 1px #999; padding:2px">Date</th>
            <th style="border: solid 1px #999; padding:2px; ">Total Days</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and p.or_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 					$department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  p.*,l.*,
(select PBI_NAME from personnel_basic_info where PBI_ID=l.authorised_by) as authorized_by,
des.*,dep.*
					
				from
				hrm_od_attendance l,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where 
				l.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				l.status in ("GRANTED") and 
				l.attendance_date between "'.$from_date.'" and "'.$to_date.'" '.$PBI_ID_con.$department_CON.'
				
				order by l.id desc
				';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->DEPT_DESC;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px">Early Leave</td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->authorized_by;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->attendance_date;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px">1</td>

            </tr>
            <?php  $total_leave_days=$total_leave_days+$data->total_days; } ?>
        <tr style="font-size:12px"><td colspan="7" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($total_leave_days)?></strong></td>
        </tr>
        </tbody>
    </table>



<?php elseif ($_POST['reporttypes']=='2004'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-10px">Late Attendance Summery</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px">Leave By</th>
            <th style="border: solid 1px #999; padding:2px">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Department</th>
            <th style="border: solid 1px #999; padding:2px; ">leave Type</th>
            <th style="border: solid 1px #999; padding:2px; ">Authorized By</th>
            <th style="border: solid 1px #999; padding:2px">Date</th>
            <th style="border: solid 1px #999; padding:2px">Late Reason</th>
            <th style="border: solid 1px #999; padding:2px; ">Total Days</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and p.or_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 					$department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  p.*,l.*,
(select PBI_NAME from personnel_basic_info where PBI_ID=l.authorised_by) as authorized_by,
des.*,dep.*
					
				from
				hrm_late_attendance l,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where 
				l.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				l.status in ("APPROVED") and 
				l.attendance_date between "'.$from_date.'" and "'.$to_date.'" '.$PBI_ID_con.$department_CON.'
				
				order by l.id desc
				';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->DEPT_DESC;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px">Late Attendance</td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->authorized_by;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->attendance_date;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->late_reason;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$total_days='1';?></td>

            </tr>
            <?php  $total_leave_days=$total_leave_days+$total_days; } ?>
        <tr style="font-size:12px"><td colspan="8" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($total_leave_days)?></strong></td>
        </tr>
        </tbody>
    </table>




<?php elseif ($_POST['reporttypes']=='6001'):

    $page="print_preview_requisition.php";
    $unique="oi_no";
    ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=600,left = 200,top = 5");}
    </script>

    <h2 align="center" style="margin-top: -5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Stationary Requisition Report</h4>
    <?php if($_POST['department']){?>
        <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
    <?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Req. No</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px;">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px;">Designation</th>
            <th style="border: solid 1px #999; padding:2px;">Department</th>
            <th style="border: solid 1px #999; padding:2px">Stationary Description</th>
            <th style="border: solid 1px #999; padding:2px">Category</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px; ">Qty</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and l.oi_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 			 $department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,p.*,i.item_id,i.item_name,i.unit_name as unit,s.*,l.*,
des.*,dep.*		
					
				from
				warehouse_other_issue m,
				warehouse_other_issue_detail l,
				item_info i,							
				item_sub_group s,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where
				m.oi_no=l.oi_no and
				m.issued_to=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				l.item_id=i.item_id and 
				s.sub_group_id=i.sub_group_id and 
				m.req_category not in ("1500010000") and
				m.status in ("UNCHECKED") 
		       '.$datecon.$department_CON.$PBI_ID_con.' group by l.id order by m.oi_date DESC,l.id asc';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>


            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal; cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique?>', 'TEST!?', 900, 600)">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->oi_no;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->oi_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->DEPT_DESC;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->item_id;?>-<?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->unit;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->qty;?></td>

            </tr>
            <?php  $total_qty=$total_qty+$data->qty; } ?>
        <tr style="font-size:12px"><td colspan="9" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_qty,2)?></strong></td>
        </tr>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='6003'):
$page="print_preview_travel_claim_exp.php";
$unique="trvClaim_id";
?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=600,left = 200,top = 5");}
    </script>
    <h2 align="center" style="margin-top: -5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Travel Exp. Claim Report</h4>
    <?php if($_POST['department']){?>
        <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
    <?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px;">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px;">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Requisition purpose</th>
            <th style="border: solid 1px #999; padding:2px">Travel <br>(From - To)</th>
            <th style="border: solid 1px #999; padding:2px">Transport</th>
            <th style="border: solid 1px #999; padding:2px">Transport Cost</th>
            <th style="border: solid 1px #999; padding:2px">Loding Details</th>
            <th style="border: solid 1px #999; padding:2px">Loding Exp.</th>
            <th style="border: solid 1px #999; padding:2px">Breakfast</th>
            <th style="border: solid 1px #999; padding:2px">Lunch</th>
            <th style="border: solid 1px #999; padding:2px">Dinner</th>
            <th style="border: solid 1px #999; padding:2px; ">Total Amount</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.application_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 			 $department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,p.*,l.*,
des.*,dep.*		
					
				from
				travel_application_claim_master m,
				travel_application_claim_details l,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where
				m.trvClaim_id=l.trvClaim_id and
				m.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and  
				m.status in ("APPROVED") 
		       '.$datecon.$department_CON.$PBI_ID_con.'   order by m.application_date DESC,l.id asc';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>


            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal; cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique?>', 'TEST!?', 900, 600)">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center; width: 5%"><?=$data->application_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  width: 10%;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->travel_purpose;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->travel_from;?> - <?=$data->travel_from;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->mode_of_transport;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->transport_fair;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->lodging_expense;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->lodging_fair;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->breakfast;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->lunch;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->dinner;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->total_amount;?></td>

            </tr>
            <?php
            $total_transport_fair=$total_transport_fair+$data->transport_fair;
            $total_lodging_fair=$total_lodging_fair+$data->lodging_fair;
            $total_breakfast=$total_breakfast+$data->breakfast;
            $total_lunch=$total_lunch+$data->lunch;
            $total_dinner=$total_dinner+$data->dinner;
            $total_amount=$total_amount+$data->total_amount; } ?>
        <tr style="font-size:12px"><td colspan="7" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_transport_fair,2)?></strong></td>
            <td></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_lodging_fair,2)?></strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_breakfast,2)?></strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_lunch,2)?></strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_dinner,2)?></strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_amount,2)?></strong></td>
        </tr>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='6004'):
$page="print_preview_vehicle.php";
$unique="vehApp_id";
?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=600,left = 250,top = -1");}
    </script>
    <h2 align="center" style="margin-top: -5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Vehicle Application</h4>
    <?php if($_POST['department']){?>
        <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
    <?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px;">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px;">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Travel Purpose</th>
            <th style="border: solid 1px #999; padding:2px">Travel From</th>
            <th style="border: solid 1px #999; padding:2px">Travel To </th>
            <th style="border: solid 1px #999; padding:2px">Time For </th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.application_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 			 $department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,p.*,l.*,
des.*,dep.*		
					
				from
				vehicle_application_master m,
				vehicle_application_details l,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where
				m.vehApp_id=l.vehApp_id and
				m.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and  
				m.status in ("APPROVED") 
		       '.$datecon.$department_CON.$PBI_ID_con.'   order by m.application_date DESC,l.id asc';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal; cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique?>', 'TEST!?', 900, 600)">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center; width: 5%"><?=$data->application_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  width: 10%;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->travel_purpose;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->travel_from;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->travel_to;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->time_for;?></td>

            </tr>
            <?php  } ?>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='6005'):
$page="print_preview_manPower.php";
$unique="manPowerApp_id";
?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=600,left = 250,top = -1");}
    </script>
    <h2 align="center" style="margin-top: -5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Manpower Application</h4>
<?php if($_POST['department']){?>
    <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
<?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px;">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px;">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Requisition for Designation</th>
            <th style="border: solid 1px #999; padding:2px">Requisition for Department</th>
            <th style="border: solid 1px #999; padding:2px">Preferred Education </th>
            <th style="border: solid 1px #999; padding:2px">No of Vacancies</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.application_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 			 $department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,p.*,
des.*,dep.*		
					
				from
				man_power_application m,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where
				m.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and  
				m.status in ("APPROVED") 
		       '.$datecon.$department_CON.$PBI_ID_con.'   order by m.application_date DESC';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal;cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique?>', 'TEST!?', 900, 600)">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center; width: 5%"><?=$data->application_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  width: 15%;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->requisition_for_designation;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->requisition_for_department;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->preferred_education;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->no_of_vacancies;?></td>

            </tr>
        <?php  } ?>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='6008'):
$page="print_preview_handOver.php";
$unique="handOver_id";
?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=600,left = 250,top = -1");}
    </script>
    <h2 align="center" style="margin-top: -5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Handover/Takeover Application</h4>
<?php if($_POST['department']){?>
    <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
<?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px;">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px;">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Reason for Handover</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.application_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 			 $department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,p.*,
des.*,dep.*		
					
				from
				handover_application_master m,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where
				m.PBI_ID=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and  
				m.status in ("APPROVED") 
		       '.$datecon.$department_CON.$PBI_ID_con.'   order by m.application_date DESC';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal; cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique?>', 'TEST!?', 900, 600)">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center; width: 5%"><?=$data->application_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  width: 15%;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->reason_for_handover;?></td>
            </tr>
        <?php  } ?>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='6002'):
$page="print_preview_requisition_food.php";
$unique="oi_no";
?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=600,left = 200,top = 5");}
    </script>
    <h2 align="center" style="margin-top: -5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Food & Beverage Requisition Report</h4>
    <?php if($_POST['department']){?>
        <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
    <?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Req. No</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px;">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px;">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Item Description</th>
            <th style="border: solid 1px #999; padding:2px">Serving Date</th>
            <th style="border: solid 1px #999; padding:2px">Serving Time</th>
            <th style="border: solid 1px #999; padding:2px">Requisition purpose</th>
            <th style="border: solid 1px #999; padding:2px; ">Qty</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and l.oi_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 			 $department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,p.*,i.item_id,i.item_name,i.unit_name as unit,s.*,l.*,
des.*,dep.*		
					
				from
				warehouse_other_issue m,
				warehouse_other_issue_detail l,
				item_info i,							
				item_sub_group s,
				personnel_basic_info p,							
				designation des,
				department dep
				 
				where
				m.oi_no=l.oi_no and
				m.issued_to=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				l.item_id=i.item_id and 
				s.sub_group_id=i.sub_group_id and 
				m.req_category in ("1500010000") and
				m.status in ("UNCHECKED") 
		       '.$datecon.$department_CON.$PBI_ID_con.' group by l.id order by m.oi_date DESC,l.id asc';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal; cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique?>', 'TEST!?', 900, 600)">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->oi_no;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->oi_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_details;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->serving_date;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->serving_time;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->requisition_purpose;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->qty;?></td>

            </tr>
            <?php  $total_qty=$total_qty+$data->qty; } ?>
        <tr style="font-size:12px"><td colspan="8" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=number_format($total_qty,2)?></strong></td>
        </tr>
        </tbody>
    </table>




<?php elseif ($_POST['reporttypes']=='6006'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-10px">Sample & Gift Report</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px">Req. No</th>
            <th style="border: solid 1px #999; padding:2px">Date</th>
            <th style="border: solid 1px #999; padding:2px">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Department</th>
            <th style="border: solid 1px #999; padding:2px; ">Item Name</th>
            <th style="border: solid 1px #999; padding:2px; ">Sub Group</th>
            <th style="border: solid 1px #999; padding:2px">Unit</th>
            <th style="border: solid 1px #999; padding:2px">Qty</th>
            <th style="border: solid 1px #999; padding:2px; ">Rate</th>
            <th style="border: solid 1px #999; padding:2px; ">Amount</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.oi_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 					$department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,d.*,p.*,des.*,dep.*,i.*,s.*
					
				from
				requisition_sample_gift_master m,
				requisition_sample_gift_details d,
				personnel_basic_info p,							
				designation des,
				department dep,
				item_info i,							
				item_sub_group s
				 
				where 
				m.oi_no=d.oi_no and 
				m.issued_to=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				i.item_id=d.item_id and
				s.sub_group_id=i.sub_group_id and 
				m.status in ("COMPLETED") and 
				m.oi_date between "'.$from_date.'" and "'.$to_date.'" '.$PBI_ID_con.$department_CON.'
				
				order by d.id desc
				';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->oi_no;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->oi_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->DEPT_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->pack_unit;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->qty;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->rate;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=number_format($amount=$data->qty*$data->rate,2);?></td>

            </tr>
            <?php  $total_amount=$total_amount+$amount; } ?>
        <tr style="font-size:12px"><td colspan="11" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($total_amount)?></strong></td>
        </tr>
        </tbody>
    </table>



<?php elseif ($_POST['reporttypes']=='6007'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-10px">FG Purchased Report</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px">Req. No</th>
            <th style="border: solid 1px #999; padding:2px">Date</th>
            <th style="border: solid 1px #999; padding:2px">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Department</th>
            <th style="border: solid 1px #999; padding:2px; ">Item Name</th>
            <th style="border: solid 1px #999; padding:2px; ">Sub Group</th>
            <th style="border: solid 1px #999; padding:2px">Unit</th>
            <th style="border: solid 1px #999; padding:2px">Qty</th>
            <th style="border: solid 1px #999; padding:2px; ">Rate</th>
            <th style="border: solid 1px #999; padding:2px; ">Amount</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.oi_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 					$department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,d.*,p.*,des.*,dep.*,i.*,s.*
					
				from
				purchase_fg_employee m,
				purchase_fg_employee_details d,
				personnel_basic_info p,							
				designation des,
				department dep,
				item_info i,							
				item_sub_group s
				 
				where 
				m.oi_no=d.oi_no and 
				m.issued_to=p.PBI_ID and 
				p.PBI_DESIGNATION=des.DESG_ID and 
				p.PBI_DEPARTMENT=dep.DEPT_ID and 
				i.item_id=d.item_id and
				s.sub_group_id=i.sub_group_id and 
				m.status in ("COMPLETED") and 
				m.oi_date between "'.$from_date.'" and "'.$to_date.'" '.$PBI_ID_con.$department_CON.'
				
				order by d.id desc
				';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->oi_no;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->oi_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->DEPT_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->pack_unit;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->qty;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->rate;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=number_format($amount=$data->qty*$data->rate,2);?></td>

            </tr>
            <?php  $total_amount=$total_amount+$amount; } ?>
        <tr style="font-size:12px"><td colspan="11" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($total_amount)?></strong></td>
        </tr>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='5001'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>
    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>
    <h4 align="center" style="margin-top:-10px">Stationary Transaction Statment</h4>
    <?php if($_POST['status']=='Received'){?> 
            <h4 align="center" style="margin-top:-10px">Status : Received</h4>
            <?php } elseif ($_POST['status']=='Issue'){?> 
			<h4 align="center" style="margin-top:-10px">Status : Issue</h4>
			<?php } ?>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">T.ID</th>
            <th style="border: solid 1px #999; padding:2px; %">Trns. Date</th>
            <th style="border: solid 1px #999; padding:2px">FG Code</th>
            <th style="border: solid 1px #999; padding:2px">FG Description</th>
            <th style="border: solid 1px #999; padding:2px">Category</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">Pack<br>Size</th>
            <th style="border: solid 1px #999; padding:2px">Source</th>
            <th style="border: solid 1px #999; padding:2px; ">Warehoues Name</th>
            <?php if($_POST['status']=='Received'){?> 
            <th style="border: solid 1px #999; padding:2px; ">PO NO</th>
            <?php } elseif ($_POST['status']=='Issue'){?> 
			<th style="border: solid 1px #999; padding:2px; ">DO NO</th>
			<?php } ?>
            <th style="border: solid 1px #999; padding:2px; ">Tr No</th>
            <th style="border: solid 1px #999; padding:2px; ">C.No</th>
            <th style="border: solid 1px #999; padding:2px; ">Entry At</th>
            <th style="border: solid 1px #999; padding:2px; ">User</th>
            <th style="border: solid 1px #999; padding:2px">IN (Pcs)</th>
            <th style="border: solid 1px #999; padding:2px">OUT (Pcs)</th>
            </tr></thead>


        <tbody>
        <?php
        $datecon=' and a.ji_date between  "'.$from_date.'" and "'.$to_date.'"';
		if($_POST['warehouse_id']>0) 				$warehouse_id=$_POST['warehouse_id'];
		if(isset($warehouse_id)) 				{$warehouse_con=' and a.warehouse_id='.$warehouse_id;}
		if($_POST['item_id']>0) 					$item_id=$_POST['item_id'];
		if(isset($item_id))				{$item_con=' and a.item_id='.$item_id;} 
		
			
			if($_POST['status']=='Received')
			{$status_con=' and a.item_in>0';}
			
			elseif($_POST['status']=='Issue')
			{$status_con=' and a.item_ex>0';}
			

			$result='select 
		
		a.id as ID,
		a.ji_date as `Trnsdate`,
		i.finish_goods_code as fg_code,
		i.item_name,
		i.unit_name as UOM,
		s.sub_group_name as Category,
		i.pack_size as packsize,
		a.item_in as `INPcs`,
		a.item_ex as `OUTPcs`,
		a.item_price as rate,
		((a.item_in+a.item_ex)*a.item_price) as amount,
		a.tr_from as Source,
		(select warehouse_name from warehouse where warehouse_id=a.warehouse_id) as warehouse,
		a.tr_no,
		a.custom_no,
		a.entry_at,
		a.do_no,
		a.po_no,
		c.fname as User 
		from
				journal_item a,
				item_info i,
				users c,				
				item_sub_group s
				 
				where c.user_id=a.entry_by and s.sub_group_id=i.sub_group_id and

		    a.item_id=i.item_id '.$datecon.$warehouse_con.$item_con.$status_con.' order by a.ji_date,a.id asc';
        $query2 = mysql_query($result);



        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->ID; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->Trnsdate; ?></td>
                
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->fg_code;?></td>                
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->Category; ?></td>
                
                
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->packsize;?></td>
                
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->Source;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->warehouse;?></td>
                
                <?php if($_POST['status']=='Received'){?> 
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><? if ($data->po_no>0) echo $data->po_no;?></td>
            <?php } elseif ($_POST['status']=='Issue'){?> 
			<td style="border: solid 1px #999; text-align:center;  padding:2px"><? if ($data->do_no>0) echo $data->do_no;?></td>
			<?php } ?>
                
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->tr_no;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->custom_no;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->entry_at;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->User;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><? if ($data->INPcs>0) echo $data->INPcs;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><? if ($data->OUTPcs>0) echo $data->OUTPcs;?></td>
                
            </tr>
            <?php 
			$intotal=$intotal+$data->INPcs;
			$outtotal=$outtotal+$data->OUTPcs;
			} ?>
            <tr style="font-size:12px"><td colspan="<?php if($_POST['status']=='Received'){ echo 14; } elseif ($_POST['status']=='Issue'){ echo '14'; } else {echo '14';}?> " style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($intotal,2)?></strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($outtotal,2)?></strong></td>
            </tr>
        </tbody>
    </table>
    </div>
    </div>
    </div>






<?php elseif ($_POST['reporttypes']=='5002'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-10px">Stationary Purchase Report</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px">Code</th>
            <th style="border: solid 1px #999; padding:2px">Stationary Description</th>
            <th style="border: solid 1px #999; padding:2px">Category</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px; ">Rate</th>
            <th style="border: solid 1px #999; padding:2px; ">Qty</th>
            <th style="border: solid 1px #999; padding:2px; ">Amount</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and p.or_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['warehouse_id']>0) 				$warehouse_id=$_POST['warehouse_id'];
        if(isset($warehouse_id)) 				{$warehouse_con=' and p.warehouse_id='.$warehouse_id;}

        $result='select  p.*,i.*,s.*		
					
				from
				warehouse_other_receive_detail p,
				item_info i,							
				item_sub_group s
				 
				where 
				p.item_id=i.item_id and 
				s.sub_group_id=i.sub_group_id  
		       '.$datecon.$warehouse_con.' order by p.or_date DESC,p.id asc';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->or_date; ?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->item_id;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->unit_name;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->rate;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->qty;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->amount;?></td>

            </tr>
            <?php  $total_amount=$total_amount+$data->amount; } ?>
        <tr style="font-size:12px"><td colspan="8" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($total_amount,2)?></strong></td>
        </tr>
        </tbody>
    </table>


<?php elseif ($_POST['reporttypes']=='5003'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>

    <h2 align="center" style="margin-top:-5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Stationary Issued Report</h4>
    <?php if ($_POST['department']>0){ ?><h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST['department'].'')?></h5> <?php } ?>
    <?php if ($_POST['PBI_ID']>0){ ?><h5 align="center" style="margin-top:-15px">Issued To : <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$_POST['PBI_ID'].'')?></h5> <?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px">Code</th>
            <th style="border: solid 1px #999; padding:2px">Stationary Description</th>
            <th style="border: solid 1px #999; padding:2px">Category</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">Issue To</th>
            <th style="border: solid 1px #999; padding:2px; ">Rate</th>
            <th style="border: solid 1px #999; padding:2px; ">Qty</th>
            <th style="border: solid 1px #999; padding:2px; ">Amount</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and iss.oi_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['warehouse_id']>0) 				$warehouse_id=$_POST['warehouse_id'];
        if(isset($warehouse_id)) 				{$warehouse_con=' and iss.warehouse_id='.$warehouse_id;}

        if($_POST['department']>0) 				$PBI_DEPARTMENT=$_POST['department'];
        if(isset($PBI_DEPARTMENT)) 				{$PBI_DEPARTMENT_CON=' and p.PBI_DEPARTMENT='.$PBI_DEPARTMENT;}

        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID=' and iss.issued_to='.$PBI_ID;}

        if($_POST['item_id']>0) 				$item_id=$_POST['item_id'];
        if(isset($item_id)) 				{$item_id_con=' and iss.item_id='.$item_id;}

        $result='select  iss.*,i.*,s.*,p.*	
					
				from
				warehouse_other_issue_detail iss,
				item_info i,							
				item_sub_group s,
				personnel_basic_info p
				 
				where 
				iss.item_id=i.item_id and 
				p.PBI_ID=iss.issued_to and 
				s.sub_group_id=i.sub_group_id  
		       '.$datecon.$warehouse_con.$PBI_ID.$PBI_DEPARTMENT_CON.$item_id_con.' order by iss.oi_date DESC,iss.id asc';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->oi_date; ?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->item_id;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->unit_name;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->rate;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=number_format($data->qty);?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->amount;?></td>

            </tr>
            <?php  $total_qty=$total_qty+$data->qty; } ?>
        <tr style="font-size:12px"><td colspan="8" style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><strong><?=$total_qty;?></strong></td>
            <td></td>
        </tr>
        </tbody>
    </table>

<?php elseif ($_POST['reporttypes']=='5004'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>


    <style>

        #customers td {
        }
        #customers tr:nth-child(even)
        {background-color: white;}
        #customers tr:hover {background-color: #FFF5EE;}
        td{
            text-align: center;
        }   </style>



    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Present Stock</h4>
    <h5 align="center" style="margin-top:-15px">Store: <?=getSVALUE('warehouse','warehouse_name','where warehouse_id="'.$_POST[warehouse_id].'"');?></h5>
    <h5 align="center" style="margin-top:-15px">Report As On <?=$_POST[t_date]?></h5>



    <table align="center" id="customers" style="width:80%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px; background-color: #FFF5EE">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px">Code</th>
            <th style="border: solid 1px #999; padding:2px">Stationary Description</th>
            <th style="border: solid 1px #999; padding:2px">Sub Group Name</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">Total Qty</th>
        </tr></thead>


        <tbody>
        <?php
        if($_POST['warehouse_id']>0) 				$warehouse_id=$_POST['warehouse_id'];
        if(isset($warehouse_id)) 				{$warehouse_con=' and j.warehouse_id='.$warehouse_id;}
        $datecon=' and j.ji_date <= "'.$to_date.'"';
        $result='Select 
				j.item_id,
				SUM(j.item_in-j.item_ex) as qty,
				i.*,sg.*,g.*		
				
				
				from
				journal_item j,				
				item_info i,
				item_sub_group sg,
				item_group g
				 
				where 				
				i.item_id=j.item_id and	
				i.sub_group_id=sg.sub_group_id and 
				sg.group_id=g.group_id  
				 '.$datecon.$warehouse_con.' 
				GROUP by i.item_id
				order by i.item_name ';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){

            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->item_id;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->unit_name;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?php if($data->qty>0) echo number_format($data->qty); else echo '-';?></td>
                </tr>
            <?php

            $freetotal=$freetotal+$freeqty;

        } ?>

        </tbody>
    </table>
    </div>
    </div>
    </div>








<?php elseif ($_POST['reporttypes']=='3004'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">GRN Pending Report (Rice)</h4>    
    <?php if($_POST[po_no]){ ?><h4 align="center" style="margin-top:-10px">PO NO: <?=$_POST[po_no] ?></h4>   <?php } ?>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>



    <table align="center"  style="width:95%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px; height: 30px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px">Item Description</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">PO</th>
            <th style="border: solid 1px #999; padding:2px">DO</th>

            <th style="border: solid 1px #999; padding:2px">Order Qty</th>
            <th style="border: solid 1px #999; padding:2px">Record Qty</th>
            <th style="border: solid 1px #999; padding:2px">Pending Qty</th>
            <th style="border: solid 1px #999; padding:2px; width: 25%">Remarks</th></tr></thead>


        <tbody>
        <?php
		if($_POST[po_no]) { $pocon=' and pm.po_no="'.$_POST[po_no].'"'; }  
        $dateconGRN=' and pm.po_date between "'.$from_date.'" and "'.$to_date.'"';
        $result='Select 
				pm.*,
				pi.*,
				i.finish_goods_code as code,
				i.item_name as itemname,
				i.unit_name as UOM,
				pr.qty as receivedQTY,
				pi.edit_resone
				
				from
				
				purchase_master pm,
				purchase_invoice pi,
				item_info i,
				purchase_receive pr
				 
				where 
				
				pm.po_no=pi.po_no and 
				pi.item_id=i.item_id AND 
				pm.section_id in ("400002") and 
				i.item_id not in ("1096000100010313") and 
				pi.item_id=pr.item_id and 
				pi.po_no=pr.po_no
				'.$pocon.$dateconGRN.' 
				
				order by pi.item_id,pr.do_no ';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->code; ?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->itemname; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; padding:5px"><?=$data->po_no;?></td>
                <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->do_no;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->qty,2);?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->receivedQTY,2);?></td>
                <td style="border: solid 1px #999; text-align:right; padding:2px"><?php $pendingQTY=$data->qty-$data->receivedQTY; echo number_format($pendingQTY,2);?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->edit_resone;?></td>
            </tr>
            <?php
            $totaladjustment=$totaladjustment+$adjustment;
            $totalcollection=$totalcollection+$collection;
            $totalactualcollection=$totalactualcollection+$actualcollection;

        } ?>

        </tbody>
    </table></div>
    </div>
    </div>



<?php elseif ($_POST['reporttypes']=='3007'):
/////////////////////////////////////Received and Payments----------------------------------------------------------?>

    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">GRN Pending Report (Rice)</h4>    
    <?php if($_POST[po_no]){ ?><h4 align="center" style="margin-top:-10px">PO NO: <?=$_POST[po_no] ?></h4>   <?php } ?>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>



    <table align="center"  style="width:95%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px; height: 30px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px">Item Description</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">PO</th>


            <th style="border: solid 1px #999; padding:2px">Order Qty</th>
            <th style="border: solid 1px #999; padding:2px">Record Qty</th>
            <th style="border: solid 1px #999; padding:2px">Pending Qty</th>
            <th style="border: solid 1px #999; padding:2px; width: 25%">Remarks</th></tr></thead>


        <tbody>
        <?php
		if($_POST[po_no]) { $pocon=' and pm.po_no="'.$_POST[po_no].'"'; }  
        $dateconGRN=' and pm.po_date between "'.$from_date.'" and "'.$to_date.'"';
        $result='Select 
				pm.*,
				pi.*,
				i.finish_goods_code as code,
				i.item_name as itemname,
				i.unit_name as UOM,
				
				
				pi.edit_resone
				
				from
				
				purchase_master pm,
				purchase_invoice pi,
				item_info i,
				purchase_receive pr
				 
				where 
				
				pm.po_no=pi.po_no and 
				pi.item_id=i.item_id AND 
				pm.section_id in ("400002") and 
				i.item_id not in ("1096000100010313") and 
				pi.item_id=pr.item_id and 
				pi.po_no=pr.po_no
				'.$pocon.$dateconGRN.' 
				GROUP BY pi.po_no,pi.item_id
				order by pi.po_no,pi.item_id ';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->code; ?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->itemname; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; padding:5px"><?=$data->po_no;?></td>

                <td style="border: solid 1px #999; text-align:right;  padding:2px">
                    <?=$oderqty=getSVALUE('purchase_invoice','SUM(qty)','where item_id="'.$data->item_id.'" and  po_no="'.$data->po_no.'"');?> </h4>
                  </td>

                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$rcvqty=getSVALUE('purchase_receive','SUM(qty)','where item_id="'.$data->item_id.'" and  po_no="'.$data->po_no.'"');?> </h4>
                </td>
                <td style="border: solid 1px #999; text-align:right; padding:2px"><?php $pendingQTY=$oderqty-$rcvqty; echo number_format($pendingQTY,2);?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->edit_resone;?></td>
            </tr>
            <?php
            $totalorder=$totalorder+$oderqty;
            $totalreceived=$totalreceived+$rcvqty;



        } ?>
        <tr style="font-weight:bold"><td colspan="5" style="border: solid 1px #999; text-align:right">Total</td>

            <td style="border: solid 1px #999; text-align:right"><?=$totalorder;?></td>
            <td style="border: solid 1px #999; text-align:right"><?=$totalreceived;?></td>
            <td style="border: solid 1px #999; text-align:right"><?=$totalorder-$totalreceived;?></td>
            <td></td>
        </tr>

        </tbody>
    </table></div>
    </div>
    </div>





<?php elseif ($_POST['reporttypes']=='dealer'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">Cash Collection (Territory Wise)</h4>
    <h4 align="center" style="margin-top:-10px">Dealer Name : <?= find_a_field('dealer_info','dealer_name_e','dealer_code="'.$_POST[dealer].'"');?> </h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[fdate]?> to <?=$_POST[tdate]?> </h5>



    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px; width:10%">Accounts Code</th>
            <th style="border: solid 1px #999; padding:2px">Dealder Name</th>
            <th style="border: solid 1px #999; padding:2px">Town</th>
            <th style="border: solid 1px #999; padding:2px">Territory</th>
            <!--th style="border: solid 1px #999; padding:2px">Aria</th--->
            <th style="border: solid 1px #999; padding:2px">Region</th>
            <th style="border: solid 1px #999; padding:2px">Adjustment</th>
            <th style="border: solid 1px #999; padding:2px">Collection</th>
            <th style="border: solid 1px #999; padding:2px">Actual Collection</th></tr></thead>


        <tbody>
        <?php
        $datecon=' and d.dealer_code="'.$_POST[dealer].'" and j.jv_date between  "'.$fdate.'" and "'.$tdate.'"';
        $result='Select 
				d.dealer_code,
				d.account_code,
				d.dealer_name_e as dealername,
				t.town_name as town,
				a.AREA_NAME as territory,
				
				b.BRANCH_NAME as region, 
				SUM(j.dr_amt) adjustment,
				SUM(j.cr_amt) collection,
				SUM(j.cr_amt-j.dr_amt) actualcollection
				from
				
				dealer_info d,
				town t,
				area a,
				
				branch b,
				journal j
				 
				where 
				
				d.canceled!="No" and 
				d.customer_type not in ("display","vip","gift") and 
				d.town_code=t.town_code and 
				a.AREA_CODE=d.area_code and 
				
				 d.region=b.BRANCH_ID and
				j.ledger_id=d.account_code and j.tr_from not in ("Sales","SalesReturn","Journal_info") '.$datecon.' 
				
				group by d.dealer_code';
        $query2 = mysql_query($result);



        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->dealer_code; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->account_code; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->dealername; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->town;?></td>
                <td style="border: solid 1px #999; padding:5px"><?=$data->territory;?></td>
                <!--td style="border: solid 1px #999; text-align:left; padding:5px"><?=$data->area;?></td--->
                <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->region;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$adjustment=$data->adjustment;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$collection=$data->collection;?></td>
                <td style="border: solid 1px #999; text-align:right; padding:2px"><?=$actualcollection=$data->actualcollection;?></td>
            </tr>
            <?php
            $totaladjustment=$totaladjustment+$adjustment;
            $totalcollection=$totalcollection+$collection;
            $totalactualcollection=$totalactualcollection+$actualcollection;

        } ?>
        <tr><td colspan="7" style="text-align:right;border: solid 1px #999;">Total</td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totaladjustment,2)?></strong></td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalcollection,2)?></strong></td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalactualcollection,2)?></strong></td>
        </tr>
        </tbody>
    </table></div>
    </div>
    </div>


<?php elseif ($_POST['reporttypes']=='dealerledger'):
/////////////////////////////////////Received and Payments----------------------------------------------------------

    $ledger_id=$_POST[account_code];	 ?>




    <div class="book">
    <div class="page" style="background-image:url(letter.jpg);background-repeat:no-repeat">
    <table align="center" style="width:80%; font-size:11px">
        <tr><td style="text-align:center"><h1 style="margin-top:-5px;">International Consumer Products Bangladesh Ltd.</h1></td></tr>

        <tr><td style="text-align:center"><h2 style="margin-top:-15px;">Customer Name: <?php echo $customer = find_a_field('dealer_info','dealer_name_e','account_code='.$_POST[account_code]);  ?></h2></td></tr>


        <tr><td style="text-align:center">Address: <?php echo $address = find_a_field('dealer_info','address_e','account_code='.$_POST[account_code]);  ?></td></tr>
        <tr><td style="text-align:center">Date Interval: <?=$_REQUEST['fdate'];?> to <?=$_REQUEST['tdate'];?></td></tr>




    </table>




    <table align="center" border="1" style="width:80%; border-collapse:collapse; margin-top:30px;   font-size:11px">
        <tr>
            <th>S/N</th>
            <th>Date</th>
            <th>Particulars</th>
            <th>Source</th>
            <th>Dr Amt</th>
            <th>Cr Amt</th>
            <th>Balance</th>
        </tr>




        <?php




        $total_sql = "select sum(a.dr_amt),sum(a.cr_amt) from journal a,accounts_ledger b where a.ledger_id=b.ledger_id and a.jv_date between '$fdate' AND '$tdate' and a.ledger_id like '$ledger_id'";

        $total=mysql_fetch_row(mysql_query($total_sql));


        $c="select sum(a.dr_amt)-sum(a.cr_amt) from journal a,accounts_ledger b where a.ledger_id=b.ledger_id and a.jv_date<'$fdate' and a.ledger_id like '$ledger_id'";


        $p="select a.jv_date,b.ledger_name,a.dr_amt,a.cr_amt,a.tr_from,a.narration,a.jv_no,a.tr_no,a.jv_no,a.cheq_no,a.cheq_date, a.user_id, a.PBI_ID , a.cc_code from journal a,accounts_ledger b where a.ledger_id=b.ledger_id and a.jv_date between '$fdate' AND '$tdate' and a.ledger_id like '$ledger_id' order by a.jv_date,a.id";

        $sql=mysql_query($p);



        if($total[0]>$total[1])
        {
            $t_type="(Dr)";
            $t_total=$total[0]-$total[1];
        }else{
            $t_type="(Cr)";
            $t_total=$total[1]-$total[0];
        }


        /* ===== Opening Balance =======*/
        $psql=mysql_query($c);
        $pl = mysql_fetch_row($psql);
        $blance=$pl[0];




        ?>
        <tr style="background-color:#FFCCFF">


            <td colspan="4" style="text-align:right"><b>Opening Balance</b></td>

            <td></td>
            <td></td>
            <td align="right" bgcolor="#FFCCFF"><?php if($blance>0) echo '(Dr)'.number_format($blance,0,'.',''); elseif($blance<0) echo '(Cr) '.number_format(((-1)*$blance),0,'.','');else echo "0.00"; ?></td>
        </tr>

        <?php


        while($data=mysql_fetch_array($sql)){
            $pi++;
            ?>

            <tr>



                <td align="center"><?php echo $pi;?></td>








                <td align="center" style="width:70px"><?php
                    $trdate = find_a_field('sale_do_chalan','do_no','chalan_no='.$data[7]);
                    $dodate = find_a_field('sale_do_master','do_date','do_no='.$trdate);
                    if ($dodate>0) echo $dodate; else echo date("Y-m-d",$data[0]);?></td>



                <td align="left"><?=$data[5];?></td>

                <!--td align="left"><?=$data[5];?><?=(($data[9]!='')?'-Cq#'.$data[9]:'');?><?=(($data[10]>943898400)?'-Cq-Date#'.date('d-m-Y',$data[10]):'');?></td-->



                <td align="center"><?php echo $data[4];?></td>
                <td align="right"><?php if($data[2]=='0') echo ''; else echo number_format($data[2],0,'.',',');?></td>
                <td align="right"><?php if($data[3]=='0') echo ''; else echo number_format($data[3],0,'.',',');?></td>
                <td align="right" bgcolor="#FFCCFF"><?php $blance = $blance+($data[2]-$data[3]);
                    if($blance>0) echo '(Dr)'.number_format($blance,2,'.',',');
                    elseif($blance<0) echo '(Cr) '.number_format(((-1)*$blance),2,'.',',');else echo "0.00"; ?></td>
            </tr>
        <?php } ?>


        <tr>







            <th colspan="4"  style="text-align:right"><strong>Total : </strong></th>
            <th align="right" style="text-align:right"><strong><?php echo number_format($total[0],2);?></strong></th>
            <th align="right" style="text-align:right"><strong><?php echo number_format($total[1],2);?></strong></th>
            <th align="right" style="text-align:right;">
                <div style="width:100px; text-align:right"><?php $blance = $blance+($data[2]-$data[3]);
                    if($blance>0) echo '(Dr)'.number_format($blance,2,'.',',');
                    elseif($blance<0) echo '(Cr) '.number_format(((-1)*$blance),2,'.',',');else echo "0.00"; ?></div>
            </th>
        </tr>

    </table>





<?php elseif ($_POST['reporttypes']=='allcurrent'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">All Customer Current Balance</h4>
    <h5 align="center" style="margin-top:-10px">Report as at <?=$_POST[tdate]?> </h5>



    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px; width:10%">Accounts Code</th>
            <th style="border: solid 1px #999; padding:2px">Customer Name</th>
            <th style="border: solid 1px #999; padding:2px">Town</th>
            <th style="border: solid 1px #999; padding:2px">Territory</th>
            <!---th style="border: solid 1px #999; padding:2px">Area</th--->
            <th style="border: solid 1px #999; padding:2px">Region</th>
            <th style="border: solid 1px #999; padding:2px">Current Balance</th></tr></thead>


        <tbody>
        <?php
        $datecon=' and j.jv_date<"'.$tdate.'"';
        $result='Select 
				d.dealer_code,
				d.account_code,
				d.dealer_name_e as dealername,
				t.town_name as town,
				a.AREA_NAME as territory,
				
				b.BRANCH_NAME as region, 
				
				SUM(j.cr_amt-j.dr_amt) actualcollection
				from
				
				dealer_info d,
				town t,
				area a,
				
				branch b,
				journal j
				 
				where 
				
				d.canceled!="No" and 
				d.customer_type not in ("display","vip","gift") and 
				d.town_code=t.town_code and 
				a.AREA_CODE=d.area_code and 
				
				 d.region=b.BRANCH_ID and
				j.ledger_id=d.account_code  '.$datecon.' 
				
				group by d.dealer_code order by b.sl,a.AREA_NAME,t.town_name';
        $query2 = mysql_query($result);



        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->dealer_code; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->account_code; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->dealername; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->town;?></td>
                <td style="border: solid 1px #999; padding:5px"><?=$data->territory;?></td>
                <!---td style="border: solid 1px #999; text-align:left; padding:5px"><?=$data->area;?></td-->
                <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->region;?></td>
                <td style="border: solid 1px #999; text-align:right; padding:2px"><strong><?=number_format($actualcollection=$data->actualcollection,2);?></strong></td>
            </tr>
            <?php
            $totaladjustment=$totaladjustment+$adjustment;
            $totalcollection=$totalcollection+$collection;
            $totalactualcollection=$totalactualcollection+$actualcollection;

        } ?>
        <tr><td colspan="7" style="text-align:right;border: solid 1px #999;">Total</td>

            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalactualcollection,2)?></strong></td>
        </tr>
        </tbody>
    </table></div>
    </div>
    </div>


    <br><br></div></div>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#example').DataTable( {
            orderFixed: [[2, 'asc']],
            rowGroup: {
                dataSrc: 2
            }
        } );

        // Change the fixed ordering when the data source is updated
        table.on( 'rowgroup-datasrc', function ( e, dt, val ) {
            table.order.fixed( {pre: [[ val, 'asc' ]]} ).draw();
        } );

        $('a.group-by').on( 'click', function (e) {
            e.preventDefault();

            table.rowGroup().dataSrc( $(this).data('column') );
        } );
    } );

</script>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/rowgroup/1.1.0/js/dataTables.rowGroup.min.js"></script>

</body>
</html>

</html>