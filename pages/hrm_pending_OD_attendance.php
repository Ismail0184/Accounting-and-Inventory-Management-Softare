<?php
require_once 'support_file.php';
$title="Pending OD Attendance List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dfromM=date('Y-m-1');
$dtoM=date('Y-m-d');

$resultdets=mysqli_query($conn, "Select * from warehouse_other_issue where ".$unique."='".$_GET[$unique]."'") ;
$getid=mysqli_fetch_array($resultdets);
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='id';
$unique_field='PBI_ID';
$table="hrm_od_attendance";

$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="RECOMMENDED";
$authorused_status="Approve";
$page="hrm_pending_OD_attendance.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

$leaverequest=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique]);

if(prevent_multi_submit()){


//for modify..................................
    if(isset($_POST['confirm']))
    {

        $_POST['status']="GRANTED";
        $_POST['approved_by']=$_SESSION[PBI_ID];
        $crud->update($unique);
        $type=1;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['Deleted']))
    {   $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
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
    $res='select r.'.$unique.',r.'.$unique.' as Req_No,r.attendance_date as Date,r.place as Duty_Place,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,
							 r.late_reason as late_reason,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_by) as Approved_by, authorised_at as Approved_at,r.status
				  from '.$table.' r
				  WHERE 
				    r.status not in ("GRANTED")
				   order by r.'.$unique.' DESC';
} else {
    $res='select r.'.$unique.',r.'.$unique.' as Req_No,r.attendance_date as Date,r.place as Duty_Place,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,
							 r.late_reason as late_reason,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_by) as Approved_by, authorised_at as Approved_at,r.status
				  from '.$table.' r
				  WHERE 
				    r.status not in ("GRANTED")
				   order by r.'.$unique.' DESC';}?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=400,left = 300,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[$unique])){ ?>
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
<?php if(isset($_GET[$unique])){ ?>


<!-- input section-->

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
    <? require_once 'support_html.php';?>

    <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px; margin-top: -22px">
        <thead>
        <tr style="background-color: #4682B4">
            <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold; color: white">Outdoor Duty Attendance Request</th>
        </tr>

        </thead>
        <thead>
        <tr>

            <th style="text-align: center">Types</th>
            <th style="text-align: center">Date</th>
            <th style="text-align: center">Duty Place</th>
            <th style="text-align: center">Purpose</th>
            <th style="text-align: center">Authorized Person</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: center; vertical-align: middle">Outdoor Duty Attendance</td>
            <td style="text-align: center;"><?php if($$unique>0){ echo date('m/d/Y' , strtotime($leaverequest->attendance_date)); } else { echo ''; } ?></td>
            <td style="text-align: center"><?=$leaverequest->place;?></td>
            <td style="text-align: center; vertical-align: middle"><?=$leaverequest->late_reason;?></td>
            <td style="text-align: center; vertical-align: middle"><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$leaverequest->authorised_by."");?></td>
        </tr>

        </tbody>
    </table>


    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This OD application has not yet been approved ! Please wait until approval !!</i></h6>';} else { ?>
        <table align="center" style="width:90%;font-size:12px;">

            <tr>
                <td style="width:20%">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                        </div></div></td>


                <td style="width:40%; float:right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to the Application?");' name="confirm" id="confirm" class="btn btn-success">Granted the Application</button>
                        </div></div>

                </td>
            </tr></table>
    <?php } ?>

    <?php } ?>


</form>




<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#s_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#e_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
