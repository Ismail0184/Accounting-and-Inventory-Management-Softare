
<!---style>
/* unvisited link */
font:link {
    color: red;
}

/* visited link */
font:visited {
    color: green;
}

/* mouse over link */
font:hover {
    color: hotpink;
}

/* selected link */
font:active {
    color: blue;
}
</style--->


<style>

.c--anim-btn span {
 
  text-decoration: none;
  text-align: left;
  display: block;
  font-size:30px;
}

.c--anim-btn, .c-anim-btn {
  transition: 0.3s;     
}

.c--anim-btn {
  height: 50px;
  font: normal normal 700 1em/4em Arial,sans-serif;
  overflow: hidden;
  width: 200px;
  
}

.c-anim-btn{
  margin-top: 0em;   
}

.c--anim-btn:hover .c-anim-btn{
  margin-top: -1.2em;
}


</style>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("admin_action_print_view.php?action_id="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 200,top = -1");}
</script>
<?php


require_once 'support_file.php';
require_once 'dashboard_data.php';
$dyear=date('Y');
$dmon='12';
$dday='31';
$cday=date('d');

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }

 // select loggedin users detail
 $todays=date("Y-m-d");
 $res=mysqli_query($conn, "SELECT * FROM company WHERE companyid=".$_SESSION['companyid']);
 $userRow=mysqli_fetch_array($res);

if($_POST['mon']!=''){
    $mon=@$_POST['mon'];}
else{
    $mon=date('m');
}

if($_POST['year']!=''){
    $year=@$_POST['year'];}
else{
    $year=date('Y');
}
$startTime = $days1=mktime(0,0,0,($mon-1),26,$year);
$endTime = $days2=mktime(0,0,0,$mon,25,$year);
$days_in_month = date('t',$endTime);
$startTime1 = $days1=mktime(0,0,0,($mon),01,$year);
$endTime1 = $days2=mktime(0,0,0,$mon,$days_in_month,$year);
$startday = date('Y-m-d',$startTime);
$endday = date('Y-m-d',$endTime);
$start_date = $year.'-'.($mon-1).'-26';
$end_date = $year.'-'.$mon.'-25';
$days_mon=ceil(($endTime - $startTime)/(3600*24))+1;
for ($i = $startTime1; $i <= $endTime1; $i = $i + 86400) {
    $day   = date('l',$i);
    ${'day'.date('N',$i)}++;
//if(isset($$day))
//$$day .= ',"'.date('Y-m-d', $i).'"';
//else
//$$day .= '"'.date('Y-m-d', $i).'"';
}
$r_count=${'day5'};
$holy_day=find_a_field('salary_holy_day','count(holy_day)','holy_day between "'.$year.'-'.$mon.'-'.'01'.'" and "'.$year.'-'.$mon.'-'.$days_mon.'"');
$late_attendance=find_a_field('hrm_late_attendance','count(id)','attendance_date between "'.$year.'-'.$mon.'-'.'01'.'" and "'.$year.'-'.$mon.'-'.$days_mon.'" and PBI_ID="'.$_SESSION['PBI_ID'].'"');
$sdte=$year.'-'.$mon.'-'."01";
$edte=$year.'-'.$mon.'-'."31";
$current_month_leave=find_a_field('hrm_leave_info','SUM(total_days)','half_or_full="Full" and PBI_ID="'.$_SESSION['PBI_ID'].'" and s_date between "'.$sdte.'" and "'.$edte.'" and e_date between "'.$sdte.'" and "'.$edte.'"');
$current_month_early_leave=find_a_field('hrm_leave_info','COUNT(id)','half_or_full="Half" and PBI_ID="'.$_SESSION['PBI_ID'].'" and s_date between "'.$sdte.'" and "'.$edte.'"');
$current_month_od_attendance=find_a_field('hrm_od_attendance','count(id)','PBI_ID="'.$_SESSION['PBI_ID'].'" and attendance_date between "'.$sdte.'" and "'.$edte.'"');

$dashboardpermission=find_a_field('user_permissions_dashboard','COUNT(module_id)','user_id='.$_SESSION['userid'].' and module_id='.$_SESSION['module_id'].'');
?>


<?php if($_SESSION['module_id']=='11') { ?>

    <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px">
        <thead>
        <tr style="background-color: #F0F8FF">
            <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold">Individual Leave Status <?=date('Y')?></th>
        </tr>

        <tr>
            <th style="vertical-align: middle">Leave Status</th><?php
            $res=mysqli_query($conn, "select * from hrm_leave_type");
            while($leave_row=mysqli_fetch_object($res)){
                ?>
                <th style="text-align: center; vertical-align: middle"><?=$leave_row->leave_type_name;?></th>
            <?php } ?>
            <th style="text-align: center; vertical-align: middle">Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Company Leave Policy</td>
            <?php $res=mysqli_query($conn, "select * from hrm_leave_type");
            while($leave_row=mysqli_fetch_object($res)){ ?>
                <td style="text-align: center"><?=$leave_row->yearly_leave_days;?>, Days</td>
                <?php
                $totalpolicy=$totalpolicy+$leave_row->yearly_leave_days;
            } ?>
            <td style="text-align: center"><?php if($_SESSION['gander']=='1'){ echo ($totalpolicy-90); } else { echo $totalpolicy;};?>, Days</td>
        </tr>




        <tr>
            <td><a href="hrm_requisition_leave_report.php" target="new">Leave Already Taken</a></td>
            <?php $res=mysqli_query($conn, "select * from hrm_leave_type");
            while($leave_row=mysqli_fetch_object($res)){ ?>
                <td style="text-align: center"><?php $leave_taken=find_a_field("hrm_leave_info","SUM(total_days)","type='".$leave_row->id."' and s_date between '$dfrom' and '$dto' and PBI_ID='".$_SESSION[PBI_ID]."'"); if($leave_taken>0){ echo $leave_taken,', Days';} else echo ''; ?></td>
                <?php
                $total_taken=$total_taken+$leave_taken;
            } ?>
            <td style="text-align: center"><?=$total_taken;?>, Days</td>
        </tr>

       


        <tr>
            <td>Available Leave</td>
            <?php
            $res=mysqli_query($conn, "select * from hrm_leave_type");
            while($leave_row=mysqli_fetch_object($res)){
                ?>
                <th style="text-align: center"><?=$leave_row->yearly_leave_days - find_a_field("hrm_leave_info","SUM(total_days)","type='".$leave_row->id."' and s_date between '$dfrom' and '$dto' and PBI_ID='".$_SESSION['PBI_ID']."'");?> , Days</th>
            <?php } ?>
            <td style="text-align: center"><?php if($_SESSION['gander']=='1'){ echo ($totalpolicy-90)-$total_taken; } else { echo $totalpolicy-$total_taken;};?>, Days</td>
        </tr>

        </tbody></table>


    <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px">
        <thead>
        <tr style="background-color: #F0F8FF">
            <th colspan="10" style="text-align: center; font-size: 15px; font-weight: bold">Current Month Attendance Status</th>
        </tr>
        <th style="text-align: center">Total Day</th>
        <th style="text-align: center">Off Day</th>
        <th style="text-align: center">Holiday</th>
        <th style="text-align: center">Present</th>
        <th style="text-align: center">Late Present</th>
        <th style="text-align: center">Leave</th>
        <th style="text-align: center">Early Leave</th>
        <th style="text-align: center">Absent</th>
        <th style="text-align: center">Outdoor Duty</th>
        <th style="text-align: center">Overtime</th>
        </thead>



        <tbody>
        <tr>
            <td style="text-align: center"><?=$days_in_month;?></td>
            <td style="text-align: center"><?=($att->od>0)?$att->od:$r_count;?></td>
            <td style="text-align: center"><?=$holy_day;?></td>
            <td style="text-align: center"><?=$days_in_month-$r_count-$holy_day;?></td>
            <td style="text-align: center"><?=$late_attendance;?></td>
            <td style="text-align: center"><?=number_format($current_month_leave);?></td>
            <td style="text-align: center"><?=number_format($current_month_early_leave);?></td>
            <td></td>
            <td style="text-align: center"><?=$current_month_od_attendance;?></td>
            <td style="text-align: center"></td>
        </tr>
        </tbody></table>


 
    <div class="col-md-3 col-xs-12 widget widget_tally_box">
        <div class="x_panel fixed_height_390">
            <div class="x_title">
                <h2><i class="fa fa-bullhorn"></i>  Latest Announcement</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="legend list-unstyled">
                    <?php
                    $res=mysqli_query($conn, "SELECT * FROM hrm_announcement WHERE STATUS in ('ACTIVE') order by ADMIN_ANN_DID desc");
                    while($row=mysqli_fetch_object($res)){
                        ?>
                        <li  style="vertical-align: middle; cursor: pointer" onclick="DoNavPOPUP('<?=$action->ADMIN_ANN_DID;?>', 'TEST!?', 600, 700)">
                            <p style="vertical-align: middle">
                                <span class="icon" ><i class="fa fa-square green"></i></span> <span class="name" style="vertical-align: middle"><?=$row->ADMIN_ANN_TYPE;?><br><font style="font-size: 10px;"><?=$row->ADMIN_ANN_SUBJECT;?></font></span>
                            </p>
                        </li>
                    <?php } ?></ul>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xs-12 widget widget_tally_box">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-bell"></i> Admin Action</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="legend list-unstyled">
                    <?php
                    $result=mysqli_query($conn, "SELECT  a.*,p.*,d.* FROM 
							 
							admin_action_detail a,
							personnel_basic_info p,
							department d						
							 where 
							 a.PBI_ID=p.PBI_ID and 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and 
							 a.PBI_ID=".$_SESSION['PBI_ID']."				 
							  order by p.PBI_NAME");
                    while($action=mysqli_fetch_object($result)){
                        ?>
                        <li style="vertical-align: middle; cursor: pointer" onclick="DoNavPOPUP('<?=$action->ADMIN_ACTION_DID;?>', 'TEST!?', 600, 700)">
                            <p style="vertical-align: middle">
                                <span class="icon" ><i class="fa fa-square blue"></i></span> <span class="name" style="vertical-align: middle"><?=$action->ADMIN_ACTION_SUBJECT;?><br><font style="font-size: 10px;"><?=$row->ADMIN_ANN_SUBJECT;?></font></span>
                            </p>
                        </li>
                    <?php } ?></ul>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xs-12 widget widget_tally_box">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-birthday-cake"></i> Birthday (This month)</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" style="overflow: auto;height: 320px">
                <ul class="legend list-unstyled">
                    <?php
    $cday=date('d');
$sdate='$year-$mon-$cday';
$todate=$dyear-$dmon-$dday;
list( $years, $months, $days) = split('[/.-]', $sdate);
list( $yeare, $monthe, $daye) = split('[/.-]', $todate);

$sdatess="$year2-$months-$days";
$edatess="$year2-$monthe-$daye";



						?>



                    <?php $res=mysqli_query($conn, 'select p2.*,d.*,de.* FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_JOB_STATUS in ("In Service") and 
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID order by p2.PBI_DOB asc ');
				   while($birthday=mysqli_fetch_object($res)){
                       $bday=$birthday->PBI_DOB;
                       list( $year2, $month2, $day2) = split('[/.-]', $bday);
                       if($month2==$mon){
                        ?>
                        <li style="vertical-align: middle; cursor: pointer">
                            <p style="vertical-align: middle">
                                <span class="icon" ><i class="fa fa-square grey"></i></span> <span class="name" style="vertical-align: middle"><?=$birthday->PBI_NAME;?></span>

                            </p>
                            <p style="font-size: 10px; margin-top: -10px"><?=$birthday->DESG_DESC;?></p>
                            <p style="font-size: 10px;margin-top: -10px; color: red"><?=date("d M", strtotime($birthday->PBI_DOB));?> (<strong><?=date("D", strtotime($birthday->PBI_DOB));?></strong>)</p>
                        </li>
                    <?php }} ?></ul>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xs-12 widget widget_tally_box">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-calendar"></i> Upcoming Holiday</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="legend list-unstyled">
                    <?php
                    $res=mysqli_query($conn, "SELECT * FROM salary_holy_day WHERE holy_day between '$year-$mon-$cday' and '$dyear-$dmon-$dday' order by id asc limit 7");
                    while($holiday=mysqli_fetch_object($res)){
                                               ?>
                    <li style="vertical-align: middle; cursor: pointer">
                        <p style="vertical-align: middle">
                            <span class="icon" ><i class="fa fa-square dark"></i></span> <span class="name" style="vertical-align: middle"><?=$holiday->reason;?><br><font style="font-size: 10px;"><?=date("d M Y", strtotime($holiday->holy_day));?> (<strong><?=date("D", strtotime($holiday->holy_day));?></strong>)</font></span>
                        </p>
                    </li>
                    <?php } ?></ul>

            </div>
        </div>
    </div>


    <?php } elseif($_SESSION['module_id']=='10') {

if($dashboardpermission>0){
$totalemployee=find_a_field('personnel_basic_info','COUNT(PBI_ID)','PBI_JOB_STATUS="In Service"');
    ?>
<table style="width: 100%"><tr><td style="width: 25%">
    <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
        <div class="well profile_view" style="width: 100%">
            <div class="col-sm-12">
                <br><br>
                <h1 style="text-align: center; color: chartreuse"><?=$totalemployee;?></h1>
                <h5 style="text-align: center">Employee</h5>
                <br><br>
            </div>
            <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="hrm_employee_report.php" target="_blank" style="color: white">View Employee</a></div>
            </div>
        </div>
    </div></td>

    <td style="width: 25%">
        <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
        <div class="well profile_view" style="width: 100%;">
            <div class="col-sm-12">
                <br><br>
                <h1 style="text-align: center; color: coral"><?=find_a_field('personnel_basic_info','COUNT(DISTINCT PBI_DEPARTMENT)','PBI_JOB_STATUS IN("In Service")');?></h1>
                <h5 style="text-align: center">Department</h5>
                <br><br>
            </div>
            <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="hrm_department_list.php" target="_blank" style="color: white">View Department</a></div>
            </div>
        </div>
    </div>
    </td>

    <td style="width: 25%">
        <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
        <div class="well profile_view" style="width: 100%;">
            <div class="col-sm-12">
                <br><br>
                <h1 style="text-align: center; color: lightseagreen"><?=find_a_field('personnel_basic_info','COUNT(DISTINCT PBI_DESIGNATION)','PBI_JOB_STATUS IN("In Service")');?></h1>
                <h5 style="text-align: center">Designation</h5>
                <br><br>
            </div>
            <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="hrm_designation_list.php" target="_blank" style="color: white">View Designation</a></div>
            </div>
        </div>
    </div>
    </td>

        <td style="width: 25%">
            <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
                <div class="well profile_view" style="width: 100%;">
                    <div class="col-sm-12">
                        <br><br>
                        <h1 style="text-align: center; color: yellowgreen"><?php
                            $datefrom=date('Y-m-01');
                            $dateto=date('Y-m-31');
                            echo find_a_field('personnel_basic_info','COUNT(PBI_ID)','PBI_JOB_STATUS IN("In Service") and PBI_DOJ between "'.$datefrom.'" and "'.$dateto.'"');?></h1>
                        <h5 style="text-align: center">New Join This Month</h5>
                        <br><br>
                    </div>
                    <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                        <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="hrm_employee_new_join.php" target="_blank" style="color: white">View New Employee</a></div>
                    </div>
                </div>
            </div>
        </td></tr></table>



    <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-calendar"></i> Manpower Statistics</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">


                <table align="left" class="table table-striped table-bordered" style="width:48%;font-size:12px">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th style="text-align: center">Gander</th>
                        <th style="text-align: center">No. of Employee</th>
                        <th style="text-align: center">Percentage(%)</th>
                    </tr>
                    </thead>
                    <tbody>
<?php
$resgender=mysqli_query($conn, "SELECT PBI_SEX,COUNT(PBI_ID) as noofemployee from personnel_basic_info where PBI_JOB_STATUS='In Service' group by PBI_SEX");
while($gender=mysqli_fetch_object($resgender)){
?>
                    <tr>
                        <td style="text-align: center"><?=$gender->PBI_SEX;?></td>
                        <td style="text-align: center"><?=$gender->noofemployee;?></td>
                        <td style="text-align: center"><?=number_format($gender->noofemployee/$totalemployee*100,2);?> %</td>
                    </tr>
        <?php } ?>
                    </tbody></table>


                <table align="right" class="table table-striped table-bordered" style="width:48%;font-size:12px">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th style="text-align: center">Marital Status</th>
                        <th style="text-align: center">No. of Employee</th>
                        <th style="text-align: center">Percentage(%)</th>
                    </tr>
                    </thead>
                    <tbody>
                                        <?php
$resmarital=mysqli_query($conn, "SELECT PBI_MARITAL_STA,COUNT(PBI_ID) as noofemployee from personnel_basic_info where PBI_JOB_STATUS='In Service' group by PBI_MARITAL_STA");
while($marital=mysqli_fetch_object($resmarital)){
?>
                    <tr>
                        <td style="text-align: center"><?=$marital->PBI_MARITAL_STA;?></td>
                        <td style="text-align: center"><?=$marital->noofemployee;?></td>
                        <td style="text-align: center"><?=number_format($marital->noofemployee/$totalemployee*100,2);?> %</td>
                    </tr>
                    <?php } ?>
                    </tbody></table>
            </div>
        </div>
    </div>


    <div class="col-md-3 col-xs-12 widget widget_tally_box">
        <div class="x_panel fixed_height_390">
            <div class="x_title">
                <h2><i class="fa fa-bullhorn"></i>  Latest Announcement</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="legend list-unstyled">
                    <?php
                    $res=mysqli_query($conn, "SELECT * FROM hrm_announcement WHERE STATUS in ('ACTIVE') order by ADMIN_ANN_DID desc");
                    while($row=mysqli_fetch_object($res)){
                        ?>
                        <li  style="vertical-align: middle; cursor: pointer" onclick="DoNavPOPUP('<?=$row->ADMIN_ANN_DID;?>', 'TEST!?', 600, 700)">
                            <p style="vertical-align: middle">
                                <span class="icon" ><i class="fa fa-square green"></i></span> <span class="name" style="vertical-align: middle"><?=$row->ADMIN_ANN_TYPE;?><br><font style="font-size: 10px;"><?=$row->ADMIN_ANN_SUBJECT;?></font></span>
                            </p>
                        </li>
                    <?php } ?></ul>
            </div>
        </div>
    </div>



    <div class="col-md-3 col-xs-12 widget widget_tally_box">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-birthday-cake"></i> Birthday (This month)</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" style="overflow: auto;height: 320px">
                <ul class="legend list-unstyled">
                    <?php
                    $cday=date('d');
                    $sdate='$year-$mon-$cday';
                    $todate=$dyear-$dmon-$dday;
                    list( $years, $months, $days) = split('[/.-]', $sdate);
                    list( $yeare, $monthe, $daye) = split('[/.-]', $todate);
                    $sdatess="$year2-$months-$days";
                    $edatess="$year2-$monthe-$daye";

                    $res=mysqli_query($conn, 'select p2.*,d.*,de.* FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_JOB_STATUS in ("In Service") and 
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID order by p2.PBI_DOB asc ');
                    while($birthday=mysqli_fetch_object($res)){
                        $dsplit=$birthday->PBI_DOB;
                        $dsplit = explode('-', $dsplit);
                        //$day   = $dsplit[2];
                        $month = $dsplit[1];
                        //$year  = $dsplit[0];
                        if($month==$mon){?>
                            <li style="vertical-align: middle; cursor: pointer">
                                <p style="vertical-align: middle">
                                    <span class="icon" ><i class="fa fa-square grey"></i></span> <span class="name" style="vertical-align: middle"><?=$birthday->PBI_NAME;?></span>
                                </p>
                                <p style="font-size: 10px; margin-top: -10px"><?=$birthday->DESG_DESC;?></p>
                                <p style="font-size: 10px;margin-top: -10px; color: red"><?=date("d M", strtotime($birthday->PBI_DOB));?> (<strong><?=date("D", strtotime($birthday->PBI_DOB));?></strong>)</p>
                            </li>
                        <?php }} ?></ul>
            </div>
        </div>
    </div>




        <div class="col-md-3 col-xs-12 widget widget_tally_box">
            <div class="x_panel fixed_height_390" >
                <div class="x_title">
                    <h2><i class="fa fa-bell"></i> Admin Action</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <ul class="legend list-unstyled">
                        <?php
                        $result=mysqli_query($conn, "SELECT  a.*,p.*,d.* FROM 
							 
							admin_action_detail a,
							personnel_basic_info p,
							department d						
							 where 
							 a.PBI_ID=p.PBI_ID and 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and 
							 a.PBI_ID=".$_SESSION['PBI_ID']."				 
							  order by p.PBI_NAME");
                        while($action=mysqli_fetch_object($result)):
                            ?>
                            <li style="vertical-align: middle; cursor: pointer" onclick="DoNavPOPUP('<?=$action->ADMIN_ACTION_DID;?>', 'TEST!?', 600, 700)">
                                <p style="vertical-align: middle">
                                    <span class="icon" ><i class="fa fa-square blue"></i></span> <span class="name" style="vertical-align: middle"><?=$action->ADMIN_ACTION_SUBJECT;?><br><p style="font-size: 10px;"></p></span>
                                </p>
                            </li>
                        <?php endwhile; ?></ul>
                </div>
            </div>
        </div>

    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-calendar"></i> Holiday Calender</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="legend list-unstyled">
                    <?php
                    $res=mysqli_query($conn, "SELECT * FROM salary_holy_day WHERE holy_day between '$year-$mon-$cday' and '$dyear-$dmon-$dday' order by id asc limit 5");
                    while($holiday=mysqli_fetch_object($res)){
                        ?>
                        <li style="vertical-align: middle; cursor: pointer">
                            <p style="vertical-align: middle">
                                <span class="icon" ><i class="fa fa-square dark"></i></span> <span class="name" style="vertical-align: middle"><?=$holiday->reason;?><br><font style="font-size: 10px;"><?=date("d M Y", strtotime($holiday->holy_day));?> (<strong><?=date("D", strtotime($holiday->holy_day));?></strong>)</font></span>
                            </p>
                        </li>
                    <?php } ?></ul>

            </div>
        </div>
    </div>
    <?php } ?>



<?php } elseif($_SESSION['module_id']=='1') {
if($dashboardpermission>0){  ?>
    <table style="width: 100%"><tr><td style="width: 25%">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
                    <div class="well profile_view" style="width: 100%">
                        <div class="col-sm-12">
                            <h1 style="text-align: center; color: darkturquoise; font-weight: bold"><?=number_format($_SESSION['todaycollection_accounts'],2);?></h1>
                            <h5 style="text-align: center">Today's Collection </h5>
                            <br>
                            <h5 style="text-align: center; color: #FF0000; font-weight: bold"><?=number_format($_SESSION['collectionMDT_accounts'],2);?></h5>
                            <h6 style="text-align: center">MTD Collection </h6>
                            <br>
                        </div>
                        <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                            <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="#" target="_blank" style="color: white">View Collection</a></div>
                        </div>
                    </div>
                </div></td>

            <td style="width: 25%">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
                    <div class="well profile_view" style="width: 100%">
                        <div class="col-sm-12">
                            <h1 style="text-align: center; color: coral; font-weight: bold"><?=number_format($_SESSION['todayshipment_accounts'],2);?></h1>
                            <h5 style="text-align: center">Today's Shipment </h5>
                            <br>
                            <h5 style="text-align: center; color: darkturquoise; font-weight: bold"><?=number_format($_SESSION['shipmentMDT_accounts'],2);?></h5>
                            <h6 style="text-align: center">MTD Shipment </h6>
                            <br>
                        </div>
                        <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                            <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="#" target="_blank" style="color: white">View Shipment</a></div>
                        </div>
                    </div>
                </div>
            </td>

            <td style="width: 25%">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
                    <div class="well profile_view" style="width: 100%">
                        <div class="col-sm-12">
                            <h1 style="text-align: center; color: lightseagreen; font-weight: bold"><?=number_format($_SESSION['todayspurchase_accounts'],2);?></h1>
                            <h5 style="text-align: center">Today's Purchase (Material)</h5>
                            <br>
                            <h5 style="text-align: center; color: yellowgreen; font-weight: bold"><?=number_format($_SESSION['purchaseMDT_accounts'],2);?></h5>
                            <h6 style="text-align: center">MTD Purchase (Material)</h6>
                            <br>
                        </div>
                        <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                            <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="#" target="_blank" style="color: white">View Purchase (Material)</a></div>
                        </div>
                    </div>
                </div>
            </td>

            <td style="width: 25%">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_details" style="width: 100%;">
                    <div class="well profile_view" style="width: 100%">
                        <div class="col-sm-12">
                            <h1 style="text-align: center; color: yellowgreen; font-weight: bold"><?=number_format($_SESSION['todayspurchaseST_accounts'],2);?></h1>
                            <h5 style="text-align: center">Today's Purchase (Stationary) </h5>
                            <br>
                            <h5 style="text-align: center; color: lightseagreen; font-weight: bold"><?=number_format($_SESSION['purchaseSTMDT_accounts'],2);?></h5>
                            <h6 style="text-align: center">MTD Payment (Stationary)</h6>
                            <br>
                        </div>
                        <div class="col-xs-12 bottom text-center" style="background-color: #4682B4">
                            <div class="col-xs-12 col-sm-12 emphasis" style="color: white; font-size: 14px"><a href="#" target="_blank" style="color: white">View Purchase (Stationary)</a></div>
                        </div>
                    </div>
                </div>

            </td></tr></table>



    <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-dollar"></i> Financial Status</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">


                <table align="left" class="table table-striped table-bordered" style="width:49%;font-size:11px">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th style="text-align: center; vertical-align: middle">No of Vendor</th>
                        <th style="text-align: center; vertical-align: middle">Vendor Outstanding Balance</th>
                        <th style="text-align: center; vertical-align: middle">Payment<br>(This Month)</th>
                        <th style="text-align: center; vertical-align: middle">Percentage(%)</th>
                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td style="text-align: center"><?=$_SESSION['noofvendor'];?></td>
                            <td style="text-align: center"><?=number_format($_SESSION['outstanding'],2);?></td>
                            <td style="text-align: center"><?=number_format($_SESSION['payment_this_month_account'],2);?></td>
                        </tr>
                    </tbody></table>

                <table align="right" class="table table-striped table-bordered" style="width:49%;font-size:11px">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th style="text-align: center; vertical-align: middle">No of Dealer</th>
                        <th style="text-align: center; vertical-align: middle">Receivable Amount</th>
                        <th style="text-align: center; vertical-align: middle">Received<br>(This Month)</th>
                        <th style="text-align: center; vertical-align: middle">Percentage(%)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $res=mysqli_query($conn, "SELECT COUNT(distinct a.ledger_id) as noofdealer, SUM(j.dr_amt-j.cr_amt) as Receivable from accounts_ledger a, journal j where a.ledger_group_id in ('1006') and a.ledger_id=j.ledger_id");
                    $vendor=mysqli_fetch_object($res);
                    ?>
                    <tr>
                        <td style="text-align: center"><?=$vendor->noofdealer;?></td>
                        <td style="text-align: center"><?=number_format($vendor->Receivable,2);?></td>
                        <td style="text-align: center"><?=number_format($gender->noofdealer/$totalemployee*100,2);?> %</td>
                    </tr>
                    </tbody></table>



            </div>
        </div>
    </div>





    <div class="col-md-3 col-sm-12 col-xs-12">
        <div class="x_panel fixed_height_390" >
            <div class="x_title">
                <h2><i class="fa fa-calendar"></i> Holiday Calender</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="legend list-unstyled">
                    <?php
                    $res=mysqli_query($conn, "SELECT * FROM salary_holy_day WHERE holy_day between '$year-$mon-$cday' and '$dyear-$dmon-$dday' order by id asc limit 5");
                    while($holiday=mysqli_fetch_object($res)){
                        ?>
                        <li style="vertical-align: middle; cursor: pointer">
                            <p style="vertical-align: middle">
                                <span class="icon" ><i class="fa fa-square dark"></i></span> <span class="name" style="vertical-align: middle"><?=$holiday->reason;?><br><font style="font-size: 10px;"><?=date("d M Y", strtotime($holiday->holy_day));?> (<strong><?=date("D", strtotime($holiday->holy_day));?></strong>)</font></span>
                            </p>
                        </li>
                    <?php } ?></ul>

            </div>
        </div>
    </div>
<?php
    accounts_session();
} else { ?>
    <h1 style="text-align:center; margin-top:200px">Welcome to <?php if($_SESSION['module_id']>0) { ?> <?=find_a_field("module_department", "modulename", "id='".$_SESSION['module_id']."'");?> Module <?php } else { echo 'ERP Software. <br><font style="font-size: 15px">Please See the above menu</font>'; }?></h1>
<?php } ?>
    <?php } else { ?>
             <h1 style="text-align:center; margin-top:200px">Welcome to <?php if($_SESSION['module_id']>0) { ?> <?=find_a_field("module_department", "modulename", " where id='".$_SESSION['module_id']."'");?> Module <?php } else { echo 'ERP Software. <br><font style="font-size: 15px">Please See the above menu</font>'; }?></h1>
       <?php } ?>
            <?php ob_end_flush(); ?>