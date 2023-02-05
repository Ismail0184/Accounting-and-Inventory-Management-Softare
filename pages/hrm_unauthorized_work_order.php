<?php
require_once 'support_file.php';
$title="Un-Approved WO / PO List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='po_no';
$unique_field='po_details';
$table="purchase_master";
$table_details="purchase_invoice";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="recommended";
$page="hrm_unauthorized_work_order.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
  
    if(isset($_POST['Return']))
    {		
    $_POST['status']='CANCELED';
	$_POST['return_comments']=$_POST['return_comments'];
	$_POST['return_at']=$todayss;
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
	
$master=find_all_field("".$table."","","".$unique."=".$_GET[$unique]."");	


if(isset($_POST[Approved])){
mysqli_query($conn, "Update ".$table." SET status='PROCESSING',authorized_date='$todayss' where ".$unique."=".$_GET[$unique]."");
$maild=find_a_field('user_activity_management','email','user_id='.$master->entry_by);
$CC_checkby=find_a_field('user_activity_management','email','user_id='.$master->checkby);
$CC_recommended=find_a_field('user_activity_management','email','user_id='.$master->recommended);
$CC_authorise=find_a_field('user_activity_management','email','user_id='.$master->authorise);
                $to = $maild;
				$subject = "Work Order has been Approved!!";
				$message .= '<table border="0" cellpadding="0" cellspacing="0" width="100%"> 
    <tr>
        <td  align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table">                
                <tr>
                    <td align="center" valign="top" style="padding: 40px 0px 40px 0px;"><img alt="Example" src="http://icpbd-erp.com/51816/cmu_mod/icon/title.png" width="100" style="display: block;" border="0" class="responsive-image">
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="padding: 0px 10px 20px 10px;">
					<p style="font-family: sans-serif; font-size: 24px; font-weight: bold; line-height: 28px; margin: 0;">Work Order has been Approved!!</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="padding: 0px 10px 10px 10px;">
                        <p style="font-family: sans-serif; font-size: 13px; font-weight: normal; line-height: 24px; margin: 0;">Your Work Order has been approved. WO No is: <b>'.$_GET[$unique].'</b>. This is pending for GRN. Please go to the <b>GRN Module</b> to receive goods.</p>
                    </td>
                </tr> 
            </table>
        </td>
    </tr>
	<tr>
	<td>
	<table align="center" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;font-family: sans-serif; font-size: 11px; padding-top:-200px" width="80%" class="responsive-table">                
                <tr bgcolor="#00a9f7">
                    <th align="center" valign="top">#</th>
					<th align="center" valign="top">Prepared By</th>
					<th align="center" valign="top">Checked By</th>
					<th align="center" valign="top">Recommended By</th>
					<th align="center" valign="top">Authorized By</th>
                </tr>
                <tr>
                    <th align="center" valign="top">Name</th>
					<td>'.find_a_field('user_activity_management','fname','user_id='.$master->entry_by).'</td>
					<td>'.find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$master->checkby).'</td>
					<td>'.find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$master->recommended).'</td>
					<td>'.find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$master->authorise).'</td>
                </tr>
                <tr>
                    <th align="center" valign="top">Time: </th>
					<td>'.$master->entry_at.'</td>
					<td>'.$master->checkby_date.'</td>
					<td>'.$master->recommended_date.'</td>
					<td>'.$todayss.'</td>
                </tr> 
            </table></td>
	</tr>
</table>';
$message .= '<p align="center" valign="top" style="padding: 0px 10px 100px 10px;font-style:italic; font-size:10px">This EMAIL is automatically generated by ERP Software.</i>';
$from = 'erp@icpbd.com';
$headers = "";
$headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
$headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
$headers .= 'Cc: '.$CC_checkby.','.$CC_recommended.','.$CC_authorise.',ismail@lbcme.com' . "\r\n";      
mail($to,$subject,$message,$headers);  
echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                       echo "<script>window.close(); </script>";
}
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 230,top = -1");}
    </script>
   
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>

 <?php if(!isset($_GET[$unique])){ ?>
 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >    
     <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Authorized Work Order</button></td>
            </tr></table>
     
     
     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_content">             
             <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                   <thead>
                    <tr style="background-color: bisque">
                     <th style="width: 2%">#</th>
                     <th style="width: 6%">PO NO</th>
                     <th style="width: 10%">Date</th>                     
                     <th>Vendor</th>
                     <th>Remarks</th>
                     <th style="width:15%">Entry By</th>
                     <th style="width:15%">Checked By</th>
                     <th style="width:15%">Recommended By</th>
                     <?php if(isset($_POST[viewreport])){ ?>
                     <th style="width:15%">Authorized By</th>
					 <?php } ?>                     
                     <th style="width:10%">Current Status</th>
                     </tr>
                     </thead>
                      <tbody>
                 <?php
				 if(isset($_POST[viewreport])){	
				 $res=mysqli_query($conn, 'select v.*,r.'.$unique.',r.'.$unique.',r.'.$unique_field.',r.po_date,r.entry_by,r.status as current_status,r.checkby,r.checkby_date,r.entry_at,r.recommended,r.recommended_date,r.authorise,r.authorized_date,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM  
							personnel_basic_info p2,
							department d,
							designation de,
							user_activity_management u 
							 where 
							 p2.PBI_ID=u.PBI_ID and
							 u.user_id=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as entry_by,r.po_details as Remarks
							 from '.$table.' r,
				  vendor v
				  WHERE r.authorise='.$_SESSION['PBI_ID'].' and 
				  r.vendor_id=v.vendor_id and 
				  r.po_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"		  
				   order by r.'.$unique.' DESC');
				   
				   } else {
					    $res=mysqli_query($conn, 'select v.*,r.'.$unique.',r.'.$unique.',r.'.$unique_field.',r.po_date,r.entry_by,r.status as current_status,r.checkby,r.checkby_date,r.entry_at,r.recommended,r.recommended_date,r.recommended,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de,
							user_activity_management u 
							 where 
							 p2.PBI_ID=u.PBI_ID and
							 u.user_id=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as entry_by,r.po_details as Remarks
							 from '.$table.' r,
				  vendor v
				  WHERE r.authorise='.$_SESSION['PBI_ID'].' and
				  r.status="'.$required_status.'" and 
				  r.vendor_id=v.vendor_id			  
				   order by r.'.$unique.' DESC');
 }
				   while($req=mysqli_fetch_object($res)){ ?>
                   <tr style="cursor: pointer">
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=$i=$i+1;?></td>
                                <td style="text-align: center; vertical-align: middle"><a href="../page/po_documents/qoutationDoc/<?=$req->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Quotation Attached"><u><strong><?=$req->$unique;?></strong></u></a></td>
                                <td style="vertical-align: middle"><a href="../page/po_documents/mailCommDoc/<?=$req->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Email Conversation Attached"><u><strong><?=$req->po_date;?></strong></u></a></td>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=$req->vendor_name;?></td>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=$req->$unique_field;?></td>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=$req->entry_by;?><br><?=$req->entry_at;?></td>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$req->checkby);?><br><?=$req->checkby_date;?></td>
                                 <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$req->recommended);?><br><?=$req->recommended_date;?></td>
                                <?php if(isset($_POST[viewreport])){ ?>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$req->authorise);?><br><?=$req->authorized_date;?></td>
                                <?php } ?>                                
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)" style="vertical-align: middle"><?=$req->current_status;?></td>
                                </tr>
                                <?php } ?>
                                
                                </tbody>
                                </table>    
          </div></div></div></form>            
          
<?php } if(isset($_GET[$unique])){ ?>


                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                             <? require_once 'support_html.php';?>
                             <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                   <thead>
                    <tr style="background-color: bisque">
                     <th style="width: 2%">#</th>
                     <th style="">Item Code</th>
                     <th style="">Description of the Goods</th>
                     <th style="text-align: center">UOM</th>
                     <th style="text-align: center">Qty</th>
                     <th style="text-align: center">Rate</th>
                     <th style="text-align: center">Amount</th>
                     </tr>
                     </thead>
                      <tbody>
                      <?php 	$res=mysqli_query($conn,'Select td.*,i.* from '.$table_details.' td,
				 item_info i
				  where td.item_id=i.item_id and 				  
				  td.'.$unique.'='.$_GET[$unique].'');
				   while($req_data=mysqli_fetch_object($res)){
				   ?>
                   <tr>
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req_data->finish_goods_code;?></td>
                                <td><?=$req_data->item_name; ?> # <?=$req_data->item_details; ?></td>
                                <td style="text-align:center"><?=$req_data->unit_name;?></td>
                                <td style="text-align:center"><?=number_format($req_data->qty,2);?></td>
                                <td style="text-align:center"><?=number_format($req_data->rate,2);?></td>
                                <td style="text-align: right"><?=number_format($req_data->amount,2);?></td>
                                </tr>
                                <?php $total=$total+$req_data->amount;  } ?>
                                
								<? if($cash_discount>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="6" align="right">Discount:</td>
                              <td align="right"><strong>
                                      <? if($cash_discount>0) echo number_format($cash_discount,2); else echo '0.00';?>
                                  </strong></td>
                          </tr>
                      <? }?>



                          <tr style="font-weight: bold">

                              <td colspan="6" align="right">TOTAL:</td>
                              <td align="right"><strong>
                                      <?  echo number_format(($total),2);?>
                                  </strong></td>
                          </tr>





                      <? if($tax_ait>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="6" align="right">AIT/Tax (<?=$tax_ait?>%): </td>
                              <td align="right"><strong> <? echo number_format((($total*$tax_ait)/100),2);?> </strong></td>
                          </tr>
                      <? } $totaltaxait=($total*$tax_ait)/100; ?>

                          <tr style="font-weight: bold">
                              <td colspan="6" align="right">SUB TOTAL:</td>
                              <td align="right"><strong>
                                      <?  echo number_format(($subtotal=$total+$asf+$totaltaxait),2) ?>
                                  </strong></td>
                          </tr>

                          <? if($tax>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="6" align="right">VAT(<?=$tax;?> %):</td>
                              <td align="right"><strong><?  echo number_format((($subtotal*$tax)/100),2);?></strong></td>
                          </tr>
                          <? }
                          $tax_totals=($subtotal*$tax)/100;
                          ?>


                      <? if($transport_bill>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="6" align="right">Transport Bill: </td>
                              <td align="right"><strong> <? echo number_format(($transport_bill),2);?> </strong></td>
                          </tr>
                      <? }?>
                      <? if($labor_bill>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="6" align="right">Labor Bill: </td>
                              <td align="right"><strong> <? echo number_format(($labor_bill),2);?> </strong></td>
                          </tr>
                      <? }?>


                      <tr style="font-weight: bold">
                      <td colspan="6" align="right">Grand Total:</td>
                      <td align="right"><strong> <? echo number_format(($subtotal+$tax_totals+$transport_bill+$labor_bill-$cash_discount),2);?> </strong></td>
                      </tr>
                      </tbody>
                                </table>
<?php if($current_status!=$required_status){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This Work Order has been authorized!!</i></h6>';} else { ?>
                                     <table style="width:100%;font-size:12px">
                                     <tr><td> <div class="form-group">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                           
                                             </div></div></td><td></td></tr>
                                          <tr>
                                          <td>
                                          <input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:166px; height:32px; font-size:11px" placeholder="return comments........" >
                                           <button type="submit" name="Return" style="font-size:12px; margin-left:5px" id="Return" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Return?");'>Return the Work Order</button></td>
                                             
                                          <td align="right"><button type="submit" style="font-size:12px" onclick='return window.confirm("Are you confirm to Approved the Work Order?");' name="Approved" id="Approved" class="btn btn-primary">Approve & Forward to Further Processing</button></td></tr></table>           
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>
<?=$html->footer_content();mysqli_close($conn);?>