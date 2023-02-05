 <?php
require_once 'support_file.php';
$title="Un-Authorized Manpower Application";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='manPowerApp_id';
$unique_field='application_date';
$table="man_power_application";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="RECOMMENDED";
$authorused_status="APPROVED";
$page="hrm_unauthorised_manpower_application.php";
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
                     <th style="">No of Vacancies</th>
                     <th style="">Priority</th>
                     <th style="">Recommended By</th>
                     <th style="">Recommended At</th>
                     </tr>
                     </thead>
                      <tbody>
                 <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.',r.recommend_at,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.PBI_ID and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.no_of_vacancies,r.Priority,(select PBI_NAME from personnel_basic_info where PBI_ID=r.recommend_by) as recommend_by
				  from '.$table.' r
				  WHERE 
				  r.recommend_by='.$_SESSION['PBI_ID'].' and
				  status="'.$required_status.'"
				  	  
				   order by r.'.$unique.' DESC');
				   while($req=mysql_fetch_object($res)){
				   
				   ?>
                   <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req->$unique;?></td>
                                <td><?=$req->$unique_field;?></td>
                                <td><?=$req->Req_By;?></td>
                                <td><?=$req->no_of_vacancies;?></td>
                                <td><?=$req->Priority;?></td>
                                <td><?=$req->recommend_by;?></td>
                                <td><?=$req->recommend_at;?></td>
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
                                <h2>Manpower Application Details</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    
<?php
$datas=find_all_field("".$table."","","".$unique."=".$_GET[$unique]."");

?>
                                    
                                   <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                                           <tr>
                                               <th style="width:20%;">Requisition No: </th>
                                               <td style="width:30%;"><?=$$unique;?></td>


                                               <th style="width:20%">Requisition Date</th>
                                               <td style="width:30%;"><?php if($unique_field>0){ echo date('m/d/y' , strtotime($datas->$unique_field)); } else { echo ''; } ?></td>
                                           </tr>




                                           <tr>
                                               <th>Requisition for Department :</th>
                                                       <td><?=$datas->requisition_for_department;?></td>
                                               <th>Requisition for Designation</th>
                                                     <td><?=$datas->requisition_for_designation;?></td>
                                           </tr>

                                       <tr>
                                           <th>Reason for Requisition (1) :</th>
                                           <td><?=$datas->preferred_related_experience_1;?></td>
                                           <th>Reason for Requisition (2)</th>
                                           <td><?=$datas->reason_for_requisition_2;?></td>
                                       </tr>
                                       <tr>
                                           <th>Preferred Related Experience (1) :</th>
                                           <td><?=$datas->reason_for_requisition_1;?></td>
                                           <th>Preferred Related Experience (2) : </th>
                                           <td><?=$datas->preferred_related_experience_2;?></td>
                                       </tr>
                                       <tr>
                                           <th>Preferred Education :</th>
                                           <td><?=$datas->preferred_education;?></td>
                                           <th>Experience (Year) : </th>
                                           <td><?=$datas->preferred_experience;?></td>
                                       </tr>
                                       <tr>
                                           <th>Gender :</th>
                                           <td><?=$datas->preferred_gender;?></td>
                                           <th>Age Limit : </th>
                                           <td><?=$datas->age_limit;?></td>
                                       </tr>
                                       <tr>
                                           <th>No of Vacancies :</th>
                                           <td><?=$datas->no_of_vacancies;?></td>
                                           <th>Type of Engagement : </th>
                                           <td><?=$datas->type_of_engagement;?></td>
                                       </tr>

                                       <tr>
                                           <th>Job Location :</th>
                                           <td><?=$datas->job_location;?></td>
                                           <th>Date of Joining : </th>
                                           <td><?=$datas->preferred_date_of_joining;?></td>
                                       </tr>

                                       <tr>
                                           <th>Key Skills and Abilities :</th>
                                           <td><?=$datas->key_skills;?></td>
                                           <th>Training/ Project/ Professional Qualification : </th>
                                           <td><?=$datas->professional_qualification;?></td>
                                       </tr>

                                       <tr>
                                           <th>Recommended By :</th>
                                           <td><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$datas->recommend_by."");?></td>
                                           <th>Recommended At : </th>
                                           <td><?=$datas->recommend_at;?></td>
                                       </tr>

                                       </table>
                                
                                <?php
                                if(isset($_POST[recommend])){
								mysql_query("Update ".$table." SET status='$authorused_status',authorized_at='$todayss' where ".$unique."=".$_GET[$unique]."");
					   
					   /// fint authorised person name
$chid=find_a_field(''.$table.'','authorised_person',''.$unique.'='.$_GET[$unique]);
$maild=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$chid);
$cby=find_a_field(''.$table.'','PBI_ID',''.$unique.'='.$_GET[$unique]);

///////////////////////// to authorise	
				
                $to = $maild;
				$subject = "Manpower Application Requisition";
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
                                    
                                     <?php if($current_status!=$required_status){ echo '<h5 style="text-align:center; color:red; font-weight:bold"><i>This requisition has been recommended!!</i></h5>';} else { ?>
                                     <table style="width:100%;font-size:12px">
                                     <tr><td> <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           <input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:166px" placeholder="return comments........" >
                                             </div></div></td><td></td></tr>
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
                                           <button type="submit" onclick='return window.confirm("Are you confirm to Recommended the Requisition?");' name="recommend" id="recommend" class="btn btn-success">Authorized & Forward to HR</button>
                                            </div></div></td></tr></table>           
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>