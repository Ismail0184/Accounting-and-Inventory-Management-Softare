 <?php
require_once 'support_file.php';
$title="Un-Checked WO/PO List";
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
$required_status="UNCHECKED";
$page="hrm_unchecked_work_order.php";
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

	
	
if(isset($_POST[checked])){
mysql_query("Update ".$table." SET status='CHECKED',checkby_date='$todayss' where ".$unique."=".$_GET[$unique]."");
$maild=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$master->recommended);
$to = $maild;
				$subject = "A New Work Order";
				$txt = "<p>Dear Sir/Madam,</p>
				<p>A new Work Order has been created. WO No is: <b>".$_GET[$unique]."</b></p>
				<p>Your Recommendation is required. Please enter <b>The Employee Access Module</b> to Recommended the Work Order.</p>
				
				<p>Checked By- <strong>".find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$master->checkby)."</strong></p>
				<p>Prepared By- <strong>".find_a_field('user_activity_management','fname','user_id='.$master->entry_by)."</strong></p>
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



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 200,top = -1");}
    </script>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>

 <?php if(!isset($_GET[$unique])){ ?>
     <!-------------------list view ------------------------->
     <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
     
     <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Checked Work Order</button></td>
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
                     <th style="width:20%">Entry By</th>
                     <?php if(isset($_POST[viewreport])){ ?>
                     <th style="width:15%">Checked By</th>
					 <?php } ?>                     
                     <th style="width:10%">Current Status</th>
                     </tr>
                     </thead>
                      <tbody>
                 <?php  if(isset($_POST[viewreport])){	
				 $res=mysql_query('select v.*,r.'.$unique.',r.'.$unique.',r.'.$unique_field.',po_date,r.status as current_status,r.checkby,r.checkby_date,
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
				  WHERE r.checkby='.$_SESSION['PBI_ID'].' and 
				  r.vendor_id=v.vendor_id and 
				  r.po_type not in ("Asset") and
				  r.po_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"		  
				   order by r.'.$unique.' DESC');
				  
				   } else {
					    $res=mysql_query('select v.*,r.'.$unique.',r.'.$unique.',r.'.$unique_field.',po_date,r.status as current_status,
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
				  WHERE r.checkby='.$_SESSION['PBI_ID'].' and
				  r.status="'.$required_status.'" and 
				  r.vendor_id=v.vendor_id and
				  r.po_type not in ("Asset")		  
				   order by r.'.$unique.' DESC');
 }
				   while($req=mysql_fetch_object($res)){ ?>
                   <tr style="cursor: pointer">
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$i=$i+1;?></td>
                                <td style="text-align: center"><a href="../page/po_documents/qoutationDoc/<?=$req->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Quotation Attached"><u><strong><?=$req->$unique;?></strong></u></a></td>
                                <td><a href="../page/po_documents/mailCommDoc/<?=$req->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Email Conversation Attached"><u><strong><?=$req->po_date;?></strong></u></a></td>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->vendor_name;?></td>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->$unique_field;?></td>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->entry_by;?><br>
								<?=$req->entry_at;?></td>
                                <?php if(isset($_POST[viewreport])){ ?>
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$req->checkby);?><br><?=$req->checkby_date;?></td>
                                <?php } ?>
                                
                                <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->current_status;?></td>
                                </tr>
                                <?php } ?>
                                
                                </tbody>
                                </table>
                
             </div></div></div></form>
     <!-------------------End of  List View --------------------->
 <?php } ?>
<?php if(isset($_GET[$unique])){ ?>
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                        <div class="x_content">
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
                      <?php 	$res=mysql_query('Select td.*,i.* from '.$table_details.' td,
				 item_info i
				  where td.item_id=i.item_id and 				  
				  td.'.$unique.'='.$_GET[$unique].'');
				   while($req_data=mysql_fetch_object($res)){
				   ?>
                   <tr>
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req_data->finish_goods_code;?></td>
                                <td><?=$req_data->item_name;?></td>
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
								<?php if($current_status!=$required_status){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This work order has been checked!!</i></h6>';} else { ?>
                                     <table style="width:100%;font-size:12px">
                                          <td><input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:166px; font-size:11px; height:32px"  placeholder="return comments........" ><button type="submit" name="Return" style="font-size:12px" id="Return" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Return?");'>Return the Work Order</button></td>
                                         
                                         <td align="right"><button type="submit" style="font-size:12px" onclick='return window.confirm("Are you confirm to Checked the Purchase Order?");' name="checked" id="checked" class="btn btn-primary">Checked & Forward</button></td></tr></table>           
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                
                                </div></div> </div></form>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>