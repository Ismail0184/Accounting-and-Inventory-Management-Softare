<?php
require_once 'support_file.php';
$title="Un-Authorised Early Leave List";
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
$unique_field='PBI_DEPT_HEAD';
$table="hrm_leave_info";
$table_details="warehouse_other_issue_detail";
$current_status=find_a_field("".$table."","dept_head_status","".$unique."=".$_GET[$unique]."");
$required_status="Pending";
$authorused_status="Approve";
$page="hrm_unauthorised_early_leave.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

$leaverequest=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique]);

if(prevent_multi_submit()){


//for modify..................................
    if(isset($_POST['confirm']))
    {
        $sd=$_POST[s_date];
        $_POST[s_date]=date('Y-m-d' , strtotime($sd));
        $_POST['dept_head_status']="Approve";
        $_POST['dept_head_aprv_at']=date("Y-m-d h:i:sa");
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
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=700,left = 200,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[$unique])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title;?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                    <thead>
                    <tr>
                        <th style="width: 2%">#</th>
                        <th style="">Req. No</th>
                        <th style="">Req. Date</th>
                        <th style="">Total Days</th>
                        <th style="">Requisition By</th>
                        <th style="">Reason</th>
                        <th style="">Recommended By</th>
                        <!--th style="">Recommended At</th-->
                    </tr>
                    </thead>
                    <tbody>
                    <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as Req_No,r.entry_at as Date,r.total_days,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.s_date as Start_date,r.e_date as End_date,r.reason,(select PBI_NAME from personnel_basic_info where PBI_ID=r.PBI_DEPT_HEAD) as recommended_by, approved_at as approved_at
				  from '.$table.' r
				  WHERE 
				  dept_head_status="'.$required_status.'"	and
				  PBI_DEPT_HEAD="'.$_SESSION[PBI_ID].'"	and
				  r.half_or_full in ("Half")
				   order by r.'.$unique.' DESC');
                    while($req=mysql_fetch_object($res)){

                        ?>
                        <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                            <td><?=$i=$i+1;?></td>
                            <td><?=$req->$unique;?></td>
                            <td><?=$req->Date;?></td>
                            <td><?=$req->total_days;?></td>
                            <td><?=$req->Req_By;?></td>
                            <td><?=$req->reason;?></td>
                            <td><?=$req->recommended_by;?></td>
                            <!--td><?=$req->recommended_at;?></td-->
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>

            </div>

        </div></div>
    <!-------------------End of  List View --------------------->
<?php } ?>
<?php if(isset($_GET[$unique])){ ?>


    <!-- input section-->

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
    <? require_once 'support_html.php';?>

                      <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:12px; margin-top: -22px">
                     <thead>
                     <tr style="background-color: #4682B4">
                         <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold; color: white">Early Leave Request</th>
                     </tr>

                     </thead>
                     <thead>
                     <tr>

                         <th style="text-align: center">Leave Types</th>
                         <th style="text-align: center">Start From</th>
                         <th style="text-align: center">Departure Time</th>
                         <th style="text-align: center">Taken Days<br>(Current Year)</th>
                         <th style="text-align: center">Taken Days<br>(Current Month)</th>
                         <th style="text-align: center">Reason</th>
                         <th style="text-align: center">Responsible Person</th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr>
                         <td style="text-align: center; vertical-align: middle">Early Leave</td>
                         <td style="text-align: center;"><input type="text" id="s_date" style="width: 120px; text-align: center"  required="required" name="s_date" value="<?php if($$unique>0){ echo date('m/d/y' , strtotime($leaverequest->s_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" ></td>
                         <td style="text-align: center"><input type="text" id="departure_time" style="width: 120px; text-align: center"   name="departure_time" value="<?=$leaverequest->departure_time;?>" class="form-control col-md-7 col-xs-12" ></td>
                         <td style="text-align: center"><input type="text" id="total_days11" style="width: 120px; text-align: center"  name="total_days11" readonly value="<?php $leave_taken=find_a_field("".$table."","SUM(total_days)","s_date between '$dfrom' and '$dto' and half_or_full in ('Half') and PBI_ID='".$leaverequest->PBI_ID."'"); if($leave_taken>0){ echo $leave_taken,', Days';} else echo ''; ?>" class="form-control col-md-7 col-xs-12" ></td>
                         <td style="text-align: center"><input type="text" id="total_days11" style="width: 120px; text-align: center"  name="total_days11" readonly value="<?php $leave_takenM=find_a_field("".$table."","SUM(total_days)","s_date between '$dfromM' and '$dtoM' and half_or_full in ('Half') and PBI_ID='".$leaverequest->PBI_ID."'"); if($leave_takenM>0){ echo $leave_takenM,', Days';} else echo ''; ?>" class="form-control col-md-7 col-xs-12" ></td>
                         <td style="text-align: center; vertical-align: middle"><?=$leaverequest->reason;?></td>
                         <td style="text-align: center; vertical-align: middle"><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$leaverequest->leave_responsibility_name."");?></td>
                     </tr>

                     </tbody>
    </table>


    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h5 style="text-align:center; color:red; font-weight:bold"><i>This requisition has been Authorized!!</i></h5>';} else { ?>
        <table align="center" style="width:90%;font-size:12px;">

            <tr>
                <td style="width:20%">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                        </div></div></td>


                <td style="width: 30%; float: right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="remarks" id="remarks" style="height: 35px" placeholder="comments to HR......." >
                        </div></div></td>



                <td style="width:40%; float:right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to Recommended the Requisition?");' name="confirm" id="confirm" class="btn btn-success">Confirm & Forward to HR</button>
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
