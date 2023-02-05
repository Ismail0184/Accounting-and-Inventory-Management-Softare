 <?php
require_once 'support_file.php';
$title="Vehicle Application List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='vehApp_id';
$unique_field='application_date';
$table="vehicle_application_master";
$table_details="vehicle_application_details";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$page="hrm_requisition_vehicle_application_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    // for re-processing data..................................

    if(isset($_POST['reprocess']))

    {   $_POST['status']='MANUAL';
        $crud->update($table);
        $_SESSION['initiate_vehicle_application_requisition']=$_GET[$unique];
        $type=1;
        echo "<script>self.opener.location = 'hrm_requisition_vehicle_application.php'; self.blur(); </script>";
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 300,top = -1");}
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
                     <th style="">Remarks</th>
                     <th style="">Priority</th>
                     <th style="">Recommended By</th>
                     <th style="">Recommended At</th>
                     </tr>
                     </thead>
                      <tbody>
                 <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.',r.approved_at,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.travel_purpose,r.Priority,(select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by) as approved_by
				  from '.$table.' r
				  WHERE 
				  r.PBI_ID='.$_SESSION['PBI_ID'].' 				  	  
				   order by r.'.$unique.' DESC');
				   while($req=mysql_fetch_object($res)){
				   
				   ?>
                   <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req->$unique;?></td>
                                <td><?=$req->$unique_field;?></td>
                                <td><?=$req->Req_By;?></td>
                                <td><?=$req->travel_purpose;?></td>
                                <td><?=$req->Priority;?></td>
                                <td><?=$req->approved_by;?></td>
                                <td><?=$req->approved_at;?></td>
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
                                <h2><?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    

                                    
                                   <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                   <thead>
                    <tr>
                    <tr>
                        <th>SL</th>
                        <th style="text-align:center">Current Location</th>
                        <th>Travel From</th>
                        <th style="text-align:center">Travel To</th>
                        <th style="text-align:center">Time For</th>
                     </tr>
                     </thead>
                      <tbody>
                      <?php 
if($_GET[deleteid]){
	
	mysql_query("Delete From ".$table_details." where ".$unique."=".$$unique." and id='$_GET[id]'"); ?>
<meta http-equiv="refresh" content="0;<?=$page;?>?<?=$unique;?>=<?php echo $_GET[$unique]; ?>">	
<?php } ?>
                 <? 	$res=mysql_query('Select td.*  from '.$table_details.' td
				  where 		  
				  td.'.$unique.'='.$_GET[$unique].'');
				   while($req_data=mysql_fetch_object($res)){

				   ?>
                   <tr>

                                <td style="text-align: center"><?=$i=$i+1;?></td>
                                <td style="text-align: center"><?=$req_data->current_location;?></td>
                                <td><?=$req_data->travel_from;?></td>
                                <td style="text-align: center"><?=$req_data->travel_to;?></td>
                                <td style="text-align: center"><?=$req_data->time_for;?></td>


                                
                                </tr>
                                <?php } ?>
                                
                                </tbody>
                                </table>
                                
                                <?php
                                if(isset($_POST[recommend])){
								mysql_query("Update ".$table." SET status='APPROVED',authorized_at='$todayss' where ".$unique."=".$_GET[$unique]."");
					   
					   /// fint authorised person name
$chid=find_a_field(''.$table.'','authorised_person',''.$unique.'='.$_GET[$unique]);
$maild=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$chid);
$cby=find_a_field(''.$table.'','PBI_ID',''.$unique.'='.$_GET[$unique]);

///////////////////////// to authorise	
				
                $to = $maild;
				$subject = "Vehicle Application Requisition";
				$txt = "<p>Dear Sir/Madam,</p>
				<p>A new Requisition has been created. Requisition No is: <b>".$_GET[$unique]."</b></p>
				<p>Need your Authorization. <b>Please enter Employee Access module to Authorise the Requisition.</b></p>
				
				<p>Recommended By- <strong>".find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$_SESSION['PBI_ID'])."</strong></p>
				<p>Prepared By- <strong>".find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$cby)."</strong></p>
				<p><strong><i>This EMAIL is automatically generated by ERP Software.</i></strong></p>";
				
				$from = 'erp@icpbd.com';
				$headers = "";
$headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
$headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
mail($to,$subject,$txt,$headers); 
					   
					   
					   echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                       echo "<script>window.close(); </script>";
								}
								?>
                                    
                                     <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This vehicle application has been Authorized!!</i></h6>';} else { ?>
                                     <table style="width:100%;font-size:12px">
                                     <tr>
                                         <td style="width:50%">
                                             <div class="form-group">
                                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                                     <button type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-processing the Application</button>
                                                 </div></div></td>
                                         <td style="width:50%; float:right">
                                             <div class="form-group">
                                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                                     <button type="submit" onclick='return window.confirm("Are you confirm to Deleted the Requisition?");' name="Deleted" id="Deleted" class="btn btn-danger">Deleted the requisition</button>
                                                 </div></div>
                                         </td>
                                         </tr></table>
                                            <?php } ?>                         
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>