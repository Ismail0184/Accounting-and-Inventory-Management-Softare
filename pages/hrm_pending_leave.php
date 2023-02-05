<?php
require_once 'support_file.php';
$title="Pending Leave Request";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$resultdets=mysql_query("Select * from warehouse_other_issue where ".$unique."='".$_GET[$unique]."'") ;
$getid=mysql_fetch_array($resultdets);
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='id';
$unique_field='PBI_DEPT_HEAD';
$table="hrm_leave_info";
$table_details="warehouse_other_issue_detail";
$current_status=find_a_field("".$table."","dept_head_status","".$unique."=".$_GET[$unique]."");
$required_status="Approve";
$authorused_status="GRANTED";
$page="hrm_pending_leave.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

$leaverequest=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique]);

if(prevent_multi_submit()){


//for modify..................................
    if(isset($_POST['confirm']))
    {
        $sd=$_POST[s_date];
        $ed=$_POST[e_date];
        $_POST[s_date]=date('Y-m-d' , strtotime($sd));
        $_POST[e_date]=date('Y-m-d' , strtotime($ed));
        $date1=date_create($_POST[s_date]);
        $date2=date_create($_POST[e_date]);
        $diff=date_diff($date1,$date2);
        $_POST[total_days]=		$diff->format("%R%a")+1;

        $_POST['leave_status']="GRANTED";
        $_POST['approved_at']=date("Y-m-d h:i:sa");
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
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=700,left = 250,top = -1");}
</script>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php require_once 'body_content.php'; ?>
<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){	
$res='select r.'.$unique.',r.'.$unique.' as ID,r.entry_at as Application_date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Application_by,CONCAT(r.s_date, " to " ,r.e_date) as Leave_Date,r.total_days,r.reason,r.dept_head_status as status,
							CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.PBI_IN_CHARGE), "\r\n" ,r.incharge_entry_at) as check_by,
							 CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.PBI_DEPT_HEAD), "\r\n" ,r.dept_head_aprv_at) as approve_by
				  from '.$table.' r
				  WHERE 
				  r.s_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'" and 
				  r.e_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'" and 
				  r.half_or_full in ("Full")
				   order by r.'.$unique.' DESC';	
				   } else {
					   $res='select r.'.$unique.',r.'.$unique.' as ID,r.entry_at as Application_date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Application_by,CONCAT(r.s_date, " to " ,r.e_date) as Leave_Date,r.total_days,r.reason,r.dept_head_status as status,
							 CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.PBI_IN_CHARGE), "\r\n" ,r.incharge_entry_at) as check_by,
							 CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.PBI_DEPT_HEAD), " at ", r.dept_head_aprv_at) as approve_by
				  from '.$table.' r
				  WHERE 
				  r.leave_status="Waiting" and 
				  r.half_or_full in ("Full")
				   order by r.'.$unique.' DESC';}?>

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Leave</button></td>
            </tr></table>
<?=$crud->report_templates_with_data($res,$title);?>

</form>
<?php } ?>  
<?php if(isset($_GET[$unique])){ ?>


<!-- input section-->

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
    <? require_once 'support_html.php';?>
    <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px">
        <thead style="display: none">
        <tr style="background-color: aquamarine">
            <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold">Leave Policy</th>
        </tr>

        </thead>
        <thead style="display: none">
        <tr>
            <th style="width: 2%">#</th><?php
            $res=mysqli_query($conn, ("select * from hrm_leave_type"));
            while($leave_row=mysqli_fetch_object($res)){
                ?>
                <th style="text-align: center"><?=$leave_row->leave_type_name;?></th>
            <?php } ?>
            <th style="text-align: center">Total</th>
        </tr>
        </thead>
        <tbody style="display: none">
        <tr>
            <td><?=$i=$i+1;?></td>
            <?php $res=mysql_query("select * from hrm_leave_type");
            while($leave_row=mysql_fetch_object($res)){ ?>
                <td style="text-align: center"><?=$leave_row->yearly_leave_days;?>, Days</td>
                <?php
                $totalpolicy=$totalpolicy+$leave_row->yearly_leave_days;
            } ?>
            <td style="text-align: center"><?=$totalpolicy;?>, Days</td>
        </tr>

        </tbody>


        <thead>
        <tr style="background-color: aquamarine">
            <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold">Leave Already Taken</th>
        </tr>

        </thead>
        <thead>
        <tr>
            <th style="width: 2%">#</th><?php
            $res=mysql_query("select * from hrm_leave_type");
            while($leave_row=mysql_fetch_object($res)){
                ?>
                <th style="text-align: center"><?=$leave_row->leave_type_name;?></th>
            <?php } ?>
            <th style="text-align: center">Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$i=$i+1;?></td>
            <?php $res=mysql_query("select * from hrm_leave_type");
            while($leave_row=mysql_fetch_object($res)){ ?>
                <td style="text-align: center"><?php $leave_taken=find_a_field("".$table."","SUM(total_days)","type='".$leave_row->id."' and s_date between '$dfrom' and '$dto' and PBI_ID='".$leaverequest->PBI_ID."'"); if($leave_taken>0){ echo $leave_taken,', Days';} else echo ''; ?></td>
                <?php
                $total_taken=$total_taken+$leave_taken;
            } ?>
            <td style="text-align: center"><?=$total_taken;?>, Days</td>
        </tr>

        </tbody>

        <thead>
        <tr style="background-color: aquamarine">
            <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold;">Available Leave</th>
        </tr>

        </thead>
        <thead>
        <tr>
            <th style="width: 2%">#</th><?php
            $res=mysql_query("select * from hrm_leave_type");
            while($leave_row=mysql_fetch_object($res)){
                ?>
                <th style="text-align: center"><?=$leave_row->leave_type_name;?></th>
            <?php } ?>
            <th style="text-align: center">Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$i=$i+1;?></td>
            <?php
            $res=mysql_query("select * from hrm_leave_type");
            while($leave_row=mysql_fetch_object($res)){
                ?>
                <td style="text-align: center"><?=$leave_row->yearly_leave_days - find_a_field("".$table."","SUM(total_days)","type='".$leave_row->id."' and s_date between '$dfrom' and '$dto' and PBI_ID='".$leaverequest->PBI_ID."'");?></td>
            <?php } ?>
            <td style="text-align: center"><?=$totalpolicy-$total_taken;?>, Days</td>
        </tr>

        </tbody></table>










    <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px; margin-top: -22px">
        <thead>
        <tr style="background-color: #4682B4">
            <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold; color: white">Leave Request</th>
        </tr>

        </thead>
        <thead>
        <tr>

            <th style="text-align: center">Leave Types</th>
            <th style="text-align: center">Start From</th>
            <th style="text-align: center">End To</th>
            <th style="text-align: center">Total Days</th>
            <th style="text-align: center">Reason</th>
            <th style="text-align: center">Responsible Person</th>
        </tr>
        </thead>
        <tbody>
        <tr style="vertical-align: middle">
            <td style="text-align: center; vertical-align: middle"><?=find_a_field("hrm_leave_type","leave_type_name","id=".$leaverequest->type."");?></td>
            <td style="text-align: center; vertical-align: middle"><input type="text" id="s_date" style="width: 120px; text-align: center"  required="required" name="s_date" value="<?php if($$unique>0){ echo date('m/d/y' , strtotime($leaverequest->s_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" ></td>
            <td style="text-align: center; vertical-align: middle"><input type="text" id="e_date" style="width: 120px; text-align: center"  required="required" name="e_date" value="<?php if($$unique>0){ echo date('m/d/y' , strtotime($leaverequest->e_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" ></td>
            <td style="text-align: center; vertical-align: middle"><input type="text" id="total_days11" style="width: 120px; text-align: center"  required="required" name="total_days11" value="<?=$leaverequest->total_days?>" class="form-control col-md-7 col-xs-12" ></td>
            <td style="text-align: center; vertical-align: middle"><?=$leaverequest->reason;?></td>
            <td style="text-align: center; vertical-align: middle"><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$leaverequest->leave_responsibility_name."");?></td>
        </tr>

        </tbody>
    </table>


    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This leave application has not yet been approved ! Please wait until approval !!</i></h6>';} else { ?>
        <table align="center" style="width:90%;font-size:12px;">

            <tr>
                <td style="width:50%">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                        </div></div></td>


                <td style="width:50%; float:right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to Approved?");' name="confirm" id="confirm" class="btn btn-success">Approved the Application</button>
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
