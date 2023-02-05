 <?php
require_once 'support_file.php';
$title="Un-Checked Stationary Purchased";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='or_no';
$unique_field='or_date';
$table="warehouse_other_receive";
$table_details="warehouse_other_receive_detail";
$table_journal_item='journal_item';
$junique='sr_no';
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="UNCHECKED";
$page="hrm_unchecked_stationary_purchase.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
  
    if(isset($_POST['Return']))
    {		
    $_POST['status']='RETURNED';
	$_POST['return_comments']=$_POST['return_comments'];
	$_POST['return_date']=$todayss;
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

    $crud = new crud($table_journal_item);
    $condition = $junique . "=" . $$unique;
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 200,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>

 <?php if(!isset($_GET[$unique])){ ?>
     <!-------------------list view ------------------------->
     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_title">
                 <h2>List of <?=$title;?></h2>
                 <div class="clearfix"></div>
             </div>

             <div class="x_content">
                 <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;font-size:12px">
                     <thead>
                     <tr>
                         <th style="text-align:center">#</th>
                         <th style="text-align:center">PO No</th>
                         <th style="text-align:center">Purchase Date</th>
                         <th style="text-align:center">Remarks</th>
                         <th style="text-align:center">Vendor Name</th>
                         <th style="text-align:center">Chalan No</th>
                         <th style="text-align:center">Requisition From</th>
                         <th style="text-align:center">Purchased By</th>
                         <th style="text-align:center">Recommended By</th>
                         <th style="text-align:center">Status</th>
                     </tr>
                     </thead>
                     <tbody>
                     <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as PO_NO,r.'.$unique_field.' as Purchased_Date,r.recommended_date,r.requisition_from,r.vendor_name,r.chalan_no,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.or_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.recommended_by) as recommended_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by) as authorised_person,r.status,r.approved_date
				  from '.$table.' r
				  WHERE  
				  r.checked_by='.$_SESSION['PBI_ID'].' and				  
				  status="'.$required_status.'" 	  
				   order by r.'.$unique.' DESC');
                     while($req=mysql_fetch_object($res)){

                         ?>
                         <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                             <td><?=$i=$i+1;?></td>
                             <td><?=$req->$unique;?></td>
                             <td><?=$req->Purchased_Date;?></td>
                             <td><?=$req->Remarks;?></td>
                             <td><?=$req->vendor_name;?></td>
                             <td><?=$req->chalan_no;?></td>
                             <td><?=$req->requisition_from;?></td>
                             <td><?=$req->Purchased_By;?></td>
                             <td><?=$req->recommended_by;?></td>
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
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    

                                    
                                   <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                   <thead>
                    <tr>
                    <tr>
                        <th>#</th>
                        <th>Item ID</th>
                        <th>Item Description</th>
                        <th style="text-align:center">Unit Name</th>
                        <th style="text-align:center">Qty</th>
                        <th style="text-align:center">Rate</th>
                        <th style="text-align:center">Amount</th>
                     </tr>
                     </thead>
                      <tbody>
                      <?php 
if($_GET[deleteid]){
	
	mysql_query("Delete From ".$table_details." where ".$unique."=".$$unique." and id='$_GET[id]'"); ?>
<meta http-equiv="refresh" content="0;<?=$page;?>?<?=$unique;?>=<?php echo $_GET[$unique]; ?>">	
<?php } ?>
                 <? 	$res=mysql_query('Select td.*,i.* from '.$table_details.' td,
                 item_info i
				  where 
				  td.item_id=i.item_id and			  
				  td.'.$unique.'='.$_GET[$unique].'');
				   while($req_data=mysql_fetch_object($res)){
                       $request_qty=$_POST['request_qty_'.$req_data->id];
                       $amount_up=$request_qty*$req_data->rate;

                       if(isset($_POST[recommend])){
mysql_query("Update ".$table_details." SET request_qty='".$request_qty."',amount='".$amount_up."'
 where ".$unique."=".$_GET[$unique]." and id=".$req_data->id."");

					   }
				   ?>
                   <tr>

                                <td style="text-align: center"><?=$i=$i+1;?></td>
                               <td><?=$req_data->item_id;?></td>
                                <td><?=$req_data->item_name;?></td>
                                <td style="text-align: center"><?=$req_data->pack_unit;?></td>
                                <td style="text-align: center"><?=$req_data->qty;?> </td>
                                <td style="text-align: right"><?=number_format($req_data->rate,2);?></td>
                                <td style="text-align: right"><?=number_format($req_data->amount,2);?></td>
                   </tr>
                       <?php     $total_amount=$total_amount+$req_data->amount; } ?>
                      <tr style="font-weight: bold">
                          <td colspan="6" style="text-align: right">Total Amount = </td><td style="text-align: right"><?=number_format($total_amount,2)?></td>
                      </tr>
                                </tbody>

                                </table>
                                
                                <?php
                                if(isset($_POST[recommend])){
								mysql_query("Update ".$table." SET status='RECOMMENDED',recommended_date='$todayss' where ".$unique."=".$_GET[$unique]."");
					   
					   /// fint authorised person name
$chid=find_a_field(''.$table.'','authorised_person',''.$unique.'='.$_GET[$unique]);
$maild=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$chid);
$cby=find_a_field(''.$table.'','PBI_ID',''.$unique.'='.$_GET[$unique]);

///////////////////////// to authorise	
				
                $to = $maild;
				$subject = "FG Purchase Requisition";
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
                                           <input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:200px" placeholder="return comments........" >
                                             </div></div></td><td></td></tr>
                                          <tr>
                                          <td>                                          
                                             <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           <button type="submit" name="Return" id="Return" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Return?");'>Return the Stationary Purchased</button>
                                             </div></div></td>
                                             
                                             <td>                                          
                                             <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                                             </div></div></td>
                                             
                                                                                       
                                            
                                            <td><div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                           <button type="submit" onclick='return window.confirm("Are you confirm to Recommended the Requisition?");' name="recommend" id="recommend" class="btn btn-success">Recommended & Forwored</button>
                                            </div></div></td></tr></table>           
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>