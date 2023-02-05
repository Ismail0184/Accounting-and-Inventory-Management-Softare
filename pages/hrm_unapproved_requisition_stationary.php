 <?php
require_once 'support_file.php';
$title="Un-Approved Stationary Requisition";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='oi_no';
$unique_field='oi_date';
$table="warehouse_other_issue";
$table_details="warehouse_other_issue_detail";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$page="hrm_unapproved_requisition_stationary.php";
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

 if(isset($_POST[viewreport])) {
     $res = 'select r.' . $unique . ',r.' . $unique . ' as Req_No,r.' . $unique_field . ' as Req_Date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.issued_to and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.oi_subject as Remarks,r.Priority,(select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person) as authorised_person,r.recommended_date
				  from ' . $table . ' r
				  WHERE r.recommended_by=' . $_SESSION['PBI_ID'] . ' and
				  r.req_category not in ("1500010000") and 	
				   r.oi_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"	  
				   order by r.' . $unique . ' DESC';
 } else {
     $res = 'select r.' . $unique . ',r.' . $unique . ' as Req_No,r.' . $unique_field . ' as Req_Date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.issued_to and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Req_By,r.oi_subject as Remarks,r.Priority,(select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person) as authorised_person
				  from ' . $table . ' r
				  WHERE r.recommended_by=' . $_SESSION['PBI_ID'] . ' and
				  r.req_category not in ("1500010000") and 	
				  status="' . $required_status . '"				  
				   order by r.' . $unique . ' DESC';
 }
?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
    </script>
 <?php
 if(isset($_GET[$unique])){
     require_once 'body_content_without_menu.php'; } else {
     require_once 'body_content.php'; }?>
 <?php if(!isset($_GET[$unique])){ ?>
     <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
         <table align="center" style="width: 50%;">
             <tr><td>
                     <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" ></td>
                 <td style="width:10px; text-align:center"> -</td>
                 <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                 <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View approved requisition</button></td>
             </tr></table>
         <?=$crud->report_templates_with_data($res,$link)?>
     </form>
 <?php } ?>
<?php if(isset($_GET[$unique])){ ?>


                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                   <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                   <thead>
                    <tr style="background-color: bisque">
                     <th style="width: 2%;vertical-align: middle">#</th>
                     <th style="vertical-align: middle">Req. No</th>
                     <th style="vertical-align: middle">Item Description</th>
                     <th style="vertical-align: middle">Unit</th>
                     <th style="vertical-align: middle">Request Qty</th>
                     <th style="vertical-align: middle">Recommend Qty</th>
                     <th style="vertical-align: middle">Already Taken<br /> (Current Year)</th>
                     <th style="vertical-align: middle">Action</th>
                     </tr>
                     </thead>
                      <tbody>
                      <?php 
if($_GET[deleteid]){
	
	mysqli_query($conn, "Delete From ".$table_details." where ".$unique."=".$$unique." and id='$_GET[id]'"); ?>
<meta http-equiv="refresh" content="0;<?=$page;?>?<?=$unique;?>=<?php echo $_GET[$unique]; ?>">	
<?php } ?>
                 <? 	$res=mysqli_query($conn, 'Select td.*,i.* from '.$table_details.' td,
				 item_info i
				  where td.item_id=i.item_id and 				  
				  td.oi_no='.$_GET[$unique].'');
				   while($req_data=mysqli_fetch_object($res)){
					   
					   
					   
					   $recommend_qty=$_POST['recemmended_qty_'.$req_data->id];
					   if(isset($_POST[recommend])){
					   
mysqli_query($conn, "Update ".$table_details." SET recommend_qty=".$recommend_qty." where oi_no=".$_GET[$unique]." and id=".$req_data->id."");

					   }
				   ?>
                   <tr>
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req_data->$unique;?></td>
                                <td><?=$req_data->item_name;?></td>
                                <td><?=$req_data->unit_name;?></td>
                                <td style="text-align:center"><?=$req_data->qty;?></td>
                                <td><input type="text" name="recemmended_qty_<?=$req_data->id;?>" id="recemmended_qty_<?=$req_data->id;?>" value="<?=$req_data->qty;?>" style="width:80px" /></td>
                                <td style="text-align:center"><?=$taken=getSVALUE("".$table_details."", "SUM(qty)", " where oi_date between '$dfrom' and '$dto' and  item_id=".$req_data->item_id." and issued_to=".$req_data->issued_to)-$req_data->qty;?>, <?=$req_data->unit_name;?>'s</td>
                                <td style="text-align:center">
                                <?php if($current_status!=$required_status){ echo 'Done';} else { ?>
                                <a onclick='return window.confirm("Mr. <?php echo $_SESSION['userfname']; ?>, Are you sure you want to Delete the Item?");' href="<?=$page?>?<?=$unique?>=<?php echo $_GET[$unique]; ?>&id=<?=$req_data->id;?>&deleteid=confrim" style="text-align:center"><img src="delete.png" style="margin-left:10px" height="15" width="15" /></a>
                                <?php } ?>
                                </td>
                                
                                </tr>
                                <?php } ?>
                                
                                </tbody>
                                </table>
                                
                                <?php
                                if(isset($_POST[recommend])){
								mysqli_query($conn, "Update ".$table." SET status='RECOMMENDED',recommended_date='$todayss' where oi_no=".$_GET[$unique]."");
					   
					   /// fint authorised person name
$chid=find_a_field('warehouse_other_issue','authorised_person',''.$unique.'='.$_GET[$unique]);
$maild=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$chid);

$cby=find_a_field('warehouse_other_issue','issued_to','oi_no='.$_GET[$unique]);

///////////////////////// to authorise	
				
                $to = $maild;
				$subject = "Stationary Requisition";
				$txt = "<p>Dear Sir/Madam,</p>
				<p>A new Requisition has been created. Requisition No is: <b>".$_GET[$unique]."</b></p>
				<p>Need your Authorization. <b>Please enter Employee Access module to Authorise the Requisition.<b></p>
				
				<p>Recommended By- <strong>".find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$_SESSION['PBI_ID'])."</strong></p>
				<p>Prepared By- <strong>".find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$cby)."</strong></p>
				<p><i>This EMAIL is automatically generated by ERP Software.</i></p>";
				
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
                                           <button type="submit" name="Return" id="Return" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Return?");' style="font-size: 12px">Return the Requisition</button>
                                             </div></div></td>
                                             
                                             <!--td>
                                             <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger" style="font-size: 12px">Cancel & Deleted</button>
                                             </div></div></td-->
                                             
                                                                                       
                                            
                                            <td>
                                           <button type="submit" onclick='return window.confirm("Are you confirm to Recommend?");' name="recommend" id="recommend" class="btn btn-primary" style="font-size: 12px; float: right">Recommend & Forward</button></td></tr></table>
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>