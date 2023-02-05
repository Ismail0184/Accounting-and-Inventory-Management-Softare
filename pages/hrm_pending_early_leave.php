<?php
require_once 'support_file.php';
$title="Pending Early Leave Request";
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
$page="hrm_pending_early_leave.php";
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
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=400,left = 250,top = -1");}
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

                <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                    <thead>
                    <tr>
                        <th style="width: 2%">#</th>
                        <th style="">Req. No</th>
                        <th style="">Req. Date</th>
                        <th style="">Total Days</th>
                        <th style="">Request By</th>
                        <th style="">Reason</th>
                        <th style="">Leave Status</th>
                        <th style="">Approved By</th>
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
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.s_date as Start_date,r.e_date as End_date,r.reason,r.dept_head_status as Approved_status,(select PBI_NAME from personnel_basic_info where PBI_ID=r.PBI_DEPT_HEAD) as approved_by, dept_head_aprv_at as approved_at
				  from '.$table.' r
				  WHERE 
				  r.leave_status="Waiting" and 
				  r.half_or_full in ("Half")
				   order by r.dept_head_status DESC');
                    while($req=mysql_fetch_object($res)){

                        ?>
                        <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                            <td><?=$i=$i+1;?></td>
                            <td><?=$req->$unique;?></td>
                            <td><?=$req->Date;?></td>
                            <td><?=$req->total_days;?></td>
                            <td><?=$req->Req_By;?></td>
                            <td><?=$req->reason;?></td>
                            <td><?=$req->Approved_status;?></td>
                            <td><?php if($req->Approved_status=="Approve") {?> <?=$req->approved_by;?><br><?=$req->approved_at;?> <?php } else { echo ''; } ?></td>

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

        <table align="center" class="table table-striped table-bordered" style="width:95%;font-size:11px; margin-top: -22px">
        <thead>
        <tr style="background-color: #4682B4">
            <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold; color: white">Leave Request</th>
        </tr>

        </thead>
        <thead>
        <tr>

            <th style="text-align: center">Leave Types</th>
            <th style="text-align: center">Date</th>
            <th style="text-align: center">Departure Time</th>
            <th style="text-align: center">Reason</th>
            <th style="text-align: center">Responsible Person</th>
            <th style="text-align: center">Approved Person</th>
        </tr>
        </thead>
        <tbody>
        <tr style="vertical-align: middle">
            <td style="text-align: center">Half</td>
            <td style="text-align: center;"><?=$leaverequest->s_date;?></td>
            <td style="text-align: center"><?=$leaverequest->departure_time;?></td>
            <td style="text-align: center"><?=$leaverequest->total_days?></td>
            <td style="text-align: center"><?=$leaverequest->reason;?></td>
            <td style="text-align: center"><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$leaverequest->leave_responsibility_name."");?></td>
        </tr>

        </tbody>
    </table>


    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This leave application has not yet been approved ! Please wait until approval !!</i></h6>';} else { ?>
        <table align="center" style="width:95%;font-size:12px;">
            <tr>
                <td style="width:45%">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                        </div></div></td>
                <td style="width:45%; float:right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" onclick='return window.confirm("Are you confirm to Approved?");' name="confirm" id="confirm" class="btn btn-success">Approved the Application</button>
                        </div></div>
                </td>
            </tr>
        </table>
    <?php } ?>

    <?php } ?>


</form>
<?php require_once 'footer_content.php' ?>