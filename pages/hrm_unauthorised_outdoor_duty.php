<?php
require_once 'support_file.php';
$title="Un-Authorised Outdoor Duty List";
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
$table="hrm_od_attendance";

$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$authorused_status="Approve";
$page="hrm_unauthorised_outdoor_duty.php";
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
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=400,left = 300,top = -1");}
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
                        <th style="">App. No</th>
                        <th style="">Date</th>
                        <th style="">Duty Place</th>
                        <th style="">Application By</th>
                        <th style="">Reason</th>
                        <th style="">Authorized Person</th>
                        <!--th style="">Recommended At</th-->
                    </tr>
                    </thead>
                    <tbody>
                    <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as Req_No,r.attendance_date as Date,r.place,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,
							 r.late_reason as late_reason,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_by) as authorised_by, authorised_at as authorised_at
				  from '.$table.' r
				  WHERE 
				    r.authorised_by="'.$_SESSION[PBI_ID].'" and 
				    r.status="'.$required_status.'" 
				   order by r.'.$unique.' DESC');
                    while($req=mysql_fetch_object($res)){

                        ?>
                        <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                            <td><?=$i=$i+1;?></td>
                            <td><?=$req->$unique;?></td>
                            <td><?=$req->Date;?></td>
                            <td><?=$req->place;?></td>
                            <td><?=$req->Req_By;?></td>
                            <td><?=$req->late_reason;?></td>
                            <td><?=$req->authorised_by;?></td>
                            <!--td><?=$req->authorised_at;?></td-->
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
                            <button type="submit" onclick='return window.confirm("Are you confirm to Authorized the Application?");' name="confirm" id="confirm" class="btn btn-success">Confirm & Forward to HR</button>
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
