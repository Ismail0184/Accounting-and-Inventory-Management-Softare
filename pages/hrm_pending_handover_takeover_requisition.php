 <?php
require_once 'support_file.php';
$title="Pending Handover/Takeover Application";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='handOver_id';
$unique_field='application_date';
$table="handover_application_master";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$page="hrm_pending_handover_takeover_requisition.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
  
    if(isset($_POST['Return']))
    {		
    $_POST['status']='RETURNED';
	$_POST['return_comments']=$_POST['return_comments'];
	$_POST['recommended_date']=$todayss;	
    $crud->update($unique);
    $type=1;
    //echo $targeturl;
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    //echo $targeturl;
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['Deleted']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);	
	$crud = new crud($table_details);
    $condition = $unique . "=" . $$unique;
    $crud->delete_all($condition);	
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 250,top = -1");}
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
                     <th style="">Appli. No</th>
                     <th style="">Appli. Date</th>
                     <th style="">Application By</th>
                     <th style="">Reason for Handover</th>
                     <th style="">Status</th>
                     </tr>
                     </thead>
                      <tbody>
                 <? 	$res=mysql_query('select m.'.$unique.', m.handOver_id as App_No,m.application_date as date,m.reason_for_handover,m.status,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=m.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By
				  from '.$table.' m
				  WHERE
				   
				  m.hrm_viewed="NO" 
				   order by m.handOver_id DESC');
				   while($req=mysql_fetch_object($res)){
				   
				   ?>
                   <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req->App_No;?></td>
                                <td><?=$req->date;?></td>
                                <td><?=$req->Req_By;?></td>
                                <td><?=$req->reason_for_handover;?></td>
                                <td><?=$req->status;?></td>
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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Handover Details</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    
<?php
$datas=find_all_field("".$table."","","".$unique."=".$_GET[$unique]."");
$p_details=find_all_field("personnel_basic_info","","PBI_ID=".$datas->PBI_ID."");

?>
                                    
                                   <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                                           <tr>
                                               <th style="width:20%;">Application No: </th>
                                               <td style="width:30%;"><?=$$unique;?></td>


                                               <th style="width:20%">Application Date : </th>
                                               <td style="width:30%;"><?=$datas->application_date;?></td>
                                           </tr>




                                           <tr>
                                               <th>Application By :</th>
                                                       <td><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$datas->PBI_ID."");?></td>
                                               <th>Designation :</th>
                                                     <td><?=find_a_field("designation","DESG_DESC","DESG_ID=".$p_details->PBI_DESIGNATION."");?></td>
                                           </tr>


                                       <tr>
                                           <th>Reason for Handover:</th>
                                           <td><?=find_a_field("handover_application_master","reason_for_handover","handOver_id=".$datas->handOver_id."");?></td>
                                           <th>Department : </th>
                                           <td><?=find_a_field("department","DEPT_DESC","DEPT_ID=".$p_details->PBI_DEPARTMENT."");?></td>
                                       </tr>

                                       </table>


                                    <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                                        <th>SL</th>
                                        <th>Take Over Department</th>
                                        <th>Take Over Person</th>
                                        <th>Take Over Details</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                        <th>Approved At</th>

<?php
$res=mysql_query("SELECT * FROM handover_application_details WHERE handOver_id=".$$unique."");
while($drow=mysql_fetch_object($res)){?>
                                        <tr>
                                            <td><?=$i=$i+1;?></td>
                                            <td><?=$drow->takeOver_department;?></td>
                                            <td><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$drow->takeOver_person."");?></td>
                                            <td><?=$drow->takeOver_details;?></td>
                                            <td><?=$drow->takeOver_remarks;?></td>
                                            <td><?=$drow->takeOver_status;?></td>
                                            <td><?=$drow->checked_at;?></td>

                                        </tr>
    <?php } ?>

                                    </table>
                                
                                <?php
                                if(isset($_POST[recommend])){
								mysql_query("Update ".$table." SET status='APPROVED',hrm_viewed='YES',hrm_viewed_date='$todayss' where ".$unique."=".$_GET[$unique]."");
					   echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                       echo "<script>window.close(); </script>";
								}
								?>
                                    
                                     <?php if($current_status!=$required_status){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This Application has been Approved!!</i></h6>';} else { ?>
                                     <table style="width:100%;font-size:12px">
                                          <tr>
                                          <td>                                          
                                             <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           <button type="submit" name="Return" id="Return" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Return?");'>Return the Requisition</button>
                                             </div></div></td>
                                             
                                             <td>                                          
                                             <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                                             </div></div></td>
                                             
                                                                                       
                                            
                                            <td><div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                           <button type="submit" onclick='return window.confirm("Are you confirm?");' name="recommend" id="recommend" class="btn btn-success">Granted</button>
                                            </div></div></td></tr></table>           
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>