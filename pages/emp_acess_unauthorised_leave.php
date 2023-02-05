<?php
require_once 'support_file.php';
$title="Un-Authorised Leave List";
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
$required_status="Pending";
$authorused_status="Approve";
$page="emp_acess_unauthorised_leave.php";
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


 if(isset($_POST[viewreport])){		
	
$res='select r.'.$unique.',r.'.$unique.' as No,r.entry_at as Date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Application_By,r.s_date as Start_date,r.e_date as End_date,r.total_days,r.reason,(select PBI_NAME from personnel_basic_info where PBI_ID=r.leave_responsibility_name) as Responsible_Person_During_Leave
				  from '.$table.' r
				  WHERE 
				  r.incharge_status in ("Approve") and
				  r.PBI_DEPT_HEAD="'.$_SESSION[PBI_ID].'"	and 
				  r.s_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'" and r.e_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'" and
				  r.half_or_full in ("Full")
				   order by r.'.$unique.' DESC';
 } else {
$res='select r.'.$unique.',r.'.$unique.' as No,r.entry_at as Date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Application_By,r.s_date as Start_date,r.e_date as End_date,r.total_days,r.reason,(select PBI_NAME from personnel_basic_info where PBI_ID=r.leave_responsibility_name) as Responsible_Person_During_Leave
				  from '.$table.' r
				  WHERE 
				  r.incharge_status in ("Approve") and
				  r.PBI_DEPT_HEAD="'.$_SESSION[PBI_ID].'"	and 
				  dept_head_status="'.$required_status.'" and
				  r.half_or_full in ("Full")
				   order by r.'.$unique.' DESC';	 
	 }
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=700,left = 250,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[$unique])){ ?>
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >    
     <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Authorized Leave</button></td>
            </tr></table>
            
            
<?=$crud->report_templates($res,$link)?>            
            
    </form>
    <!-------------------End of  List View --------------------->
<?php } ?>
<?php if(isset($_GET[$unique])){ ?>


    <!-- input section-->

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
    <? require_once 'support_html.php';?>
                 <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px">
                     <thead>
                     <tr style="background-color: bisque">
                         <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold;">Leave Status</th>
                     </tr>

                     </thead>
                        <thead>
                        <tr>
                            <th rowspan="2" style="width: 2%; vertical-align:middle">Leave</th><?php
    $res=mysqli_query($conn, "select * from hrm_leave_type");
    while($leave_row=mysqli_fetch_object($res)){
        ?>
                            <th style="text-align: center; vertical-align:middle"><?=$leave_row->leave_type_name;?></th>
                           <?php } ?>
                            <th style="text-align: center; vertical-align:middle">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td>Policy</td>
                                <?php $res=mysqli_query($conn, "select * from hrm_leave_type");
                                while($leave_row=mysqli_fetch_object($res)){ ?>
                                <td style="text-align: center"><?=$leave_row->yearly_leave_days;?>, Days</td>
                                <?php
                                $totalpolicy=$totalpolicy+$leave_row->yearly_leave_days;
                                } ?>
                                <td style="text-align: center"><?=$totalpolicy;?>, Days</td>
                            </tr>
                            
       
        <tr>
            <td>Taken</td>
            <?php $res=mysqli_query($conn, "select * from hrm_leave_type");
            while($leave_row=mysqli_fetch_object($res)){ ?>
                <td style="text-align: center"><?php $leave_taken=find_a_field("".$table."","SUM(total_days)","type='".$leave_row->id."' and s_date between '$dfrom' and '$dto' and PBI_ID='".$leaverequest->PBI_ID."'"); if($leave_taken>0){ echo $leave_taken,', Days';} else echo ''; ?></td>
                <?php
                $total_taken=$total_taken+$leave_taken;
            } ?>
            <td style="text-align: center"><?=$total_taken;?>, Days</td>
        </tr>

        </tbody>

       
        <tr>
            <td>Available</td>
            <?php
            $res=mysqli_query($conn, "select * from hrm_leave_type");
            while($leave_row=mysqli_fetch_object($res)){
                ?>
                <th style="text-align: center"><?=$leave_row->yearly_leave_days - find_a_field("".$table."","SUM(total_days)","type='".$leave_row->id."' and s_date between '$dfrom' and '$dto' and PBI_ID='".$leaverequest->PBI_ID."'");?></th>
            <?php } ?>
            <td style="text-align: center"><?=$totalpolicy-$total_taken;?>, Days</td>
        </tr>

        </tbody></table>










                      <table align="center" class="table table-striped table-bordered" style="width:90%;font-size:11px; margin-top: -22px">
                     <thead>
                     <tr style="background-color: bisque">
                         <th colspan="7" style="text-align: center; font-size: 15px; font-weight: bold;">Leave Request</th>
                     </tr>

                     </thead>
                     <thead>
                     <tr>

                         <th style="text-align: center; vertical-align:middle">Leave Types</th>
                         <th style="text-align: center; vertical-align:middle">Start From</th>
                         <th style="text-align: center; vertical-align:middle">End To</th>
                         <th style="text-align: center; vertical-align:middle">Total Days</th>
                         <th style="text-align: center; vertical-align:middle">Reason</th>
                         <th style="text-align: center; vertical-align:middle">Responsible Person During Leave</th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr>
                         <td style="text-align: center; vertical-align:middle"><?=find_a_field("hrm_leave_type","leave_type_name","id=".$leaverequest->type."");?></td>
                         <td style="text-align: center;"><input type="text" id="s_date" style="width: 100px; font-size:11px; text-align: center"  required="required" name="s_date" value="<?php if($$unique>0){ echo date('m/d/y' , strtotime($leaverequest->s_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" ></td>
                         <td style="text-align: center"><input type="text" id="e_date" style="width: 100px; font-size:11px; text-align: center"  required="required" name="e_date" value="<?php if($$unique>0){ echo date('m/d/y' , strtotime($leaverequest->e_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" ></td>
                         <td style="text-align: center"><input type="text" id="total_days11" style="width: 80px; font-size:11px; text-align: center"  required="required" name="total_days11" value="<?=$leaverequest->total_days?>" class="form-control col-md-7 col-xs-12" ></td>
                         <td style="text-align: center; vertical-align:middle"><?=$leaverequest->reason;?></td>
                         <td style="text-align: center; vertical-align:middle"><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$leaverequest->leave_responsibility_name."");?></td>
                     </tr>

                     </tbody>
    </table>


    <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This leave has been Authorized!!</i></h6>';} else { ?>
        <table align="center" style="width:90%;font-size:12px;">

            <tr>
                <td style="width:20%">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" style="font-size:12px" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                        </div></div></td>


                <td style="width: 30%; float: right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="remarks" id="remarks" style="height: 35px; font-size:11px" placeholder="comments to HR......." >
                        </div></div></td>



                <td style="width:40%; float:right">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" style="font-size:12px" onclick='return window.confirm("Are you confirm to Recommended the Requisition?");' name="confirm" id="confirm" class="btn btn-success">Confirm & Forward to HR</button>
                        </div></div>
                </td>
            </tr></table>
    <?php } ?>

<?php } ?>


</form>




<?php require_once 'footer_content.php' ?>