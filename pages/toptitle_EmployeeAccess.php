
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



if($_POST['mon']!=''){
    $mon=$_POST['mon'];}
else{
    $mon=date('m');
}

if($_POST['year']!=''){
    $year=$_POST['year'];}
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
                <td style="text-align: center"><?php $leave_taken=find_a_field("hrm_leave_info","SUM(total_days)","type='".$leave_row->id."' and s_date between '$dfrom' and '$dto' and PBI_ID='".$_SESSION['PBI_ID']."'"); if($leave_taken>0){ echo $leave_taken,', Days';} else echo ''; ?></td>
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
    <?php } else { ?>
             <h1 style="text-align:center; margin-top:200px">Welcome to <?php if($_SESSION['module_id']>0) { ?> <?=find_a_field('module_department', 'modulename','id='.$_SESSION['module_id']);?> Module <?php } else { echo 'ERP Software. <br><font style="font-size: 15px">Please See the above menu</font>'; }?></h1>
       <?php } ?><?php ob_end_flush(); ?>