 <?php
require_once 'support_file.php';
$title="Un-Checked AP List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='po_no';
$unique_field='po_no';
$table="purchase_master";
$table_details="purchase_invoice";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="recommended";
$page="emp_acess_asset_purchased_unauthorized.php";
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

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
     
     <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Approved Asset Purchase</button></td>
            </tr></table>

     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
          <div class="x_content">
          <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="text-align:center">#</th>
                            <th style="text-align:center">PO No</th>
                            <th style="text-align:center">Purchase Date</th>
                            <th style="text-align:center">Remarks</th>
                            <th style="text-align:center">Vendor Name</th>
                            <th style="text-align:center">Purchased By</th>
                            <th style="text-align:center">Entry At</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? 
						if(isset($_POST[viewreport])){
						$res='select r.'.$unique.',r.'.$unique.' as PO_NO,r.po_date as Purchased_Date,r.entry_at,v.vendor_name,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.ERP_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.po_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.checkby) as checked_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorise) as authorised_person,r.status,r.authorized_date
				  from '.$table.' r,
				  vendor v
				  WHERE 
				  r.vendor_id=v.vendor_id and 
				  r.authorise='.$_SESSION['PBI_ID'].' and
				  r.po_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'" and 
				  r.po_type in ("Asset")				   	  
				   order by r.'.$unique.' DESC';
						} else {
						$res='select r.'.$unique.',r.'.$unique.' as PO_NO,r.po_date as Purchased_Date,r.entry_at,v.vendor_name,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.ERP_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.po_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.checkby) as checked_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorise) as authorised_person,r.status,r.authorized_date
				  from '.$table.' r,
				  vendor v
				  WHERE 
				  r.vendor_id=v.vendor_id and 
				  r.authorise='.$_SESSION['PBI_ID'].' and
				  r.status="'.$required_status.'" and
				  r.po_type in ("Asset") 				   	  
				   order by r.'.$unique.' DESC';	
						}
                            $pquery=mysqli_query($conn, $res);
                            while($req=mysqli_fetch_object($pquery)){?>
                                <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                    <td><?=$i=$i+1;?></td>
                                    <td><?=$req->$unique;?></td>
                                    <td><?=$req->Purchased_Date;?></td>
                                    <td><?=$req->Remarks;?></td>
                                    <td><?=$req->vendor_name;?></td>
                                    <td><?=$req->Purchased_By;?></td>
                                    <td><?=$req->entry_at;?></td>
                                    <td><?=$req->status;?></td>
                                </tr>
                            <?php } ?></tbody>
                            </table>
                            </div></div></div></form>
     <!-------------------End of  List View --------------------->
 <?php } ?>
<?php if(isset($_GET[$unique])){ ?>


                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                        <div class="x_content">
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                     <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>Asset Code</th>
                            <th>Asset Description</th>
                            <th style="width:5%; text-align:center">Department</th>
                            <th style="text-align:center">Where Kept</th>                            
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Discount</th>
                            <th style="text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php
                        $results="Select srd.*,i.*,ar.*,d.DEPT_SHORT_NAME as department 
						from 
						".$table_details." srd, item_info i, asset_register ar, department d  where
 srd.item_id=i.item_id and ar.item_id=i.item_id and d.DEPT_ID=ar.DEPT_ID and 
 srd.".$unique."=".$$unique." order by srd.id";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[asset_code];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?> : <?=$row[specification];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[department];?></td>          
                                <td align="center" style=" text-align:left;vertical-align:middle;"><?=$row[where_kept]; ?></td>
                                <td align="center" style=" text-align:center;vertical-align:middle;"><?=$row[qty]; ?></td>
                                <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[rate]; ?></td>
                                <td align="center" style=" text-align:center;vertical-align:middle;"><?=$row[discount]; ?></td>
                                <td align="center" style="text-align:right;vertical-align:middle;"><?=number_format($row[amount],2);?></td>

                            </tr>
                            <?php  
							$ttotal_unit=$ttotal_unit+$row[total_unit];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $total=$total+$row[amount];  } ?>







                      

                          <tr style="font-weight: bold">

                              <td colspan="8" align="right">TOTAL:</td>
                              <td align="right"><strong>
                                      <?  echo number_format(($total),2);?>
                                  </strong></td>
                          </tr>


<? if($cash_discount>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">Discount:</td>
                              <td align="right"><strong>
                                      <? if($cash_discount>0) echo number_format($cash_discount,2); else echo '0.00';?>
                                  </strong></td>
                          </tr>
                      <? }?>




                      <? if($tax_ait>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">AIT/Tax (<?=$tax_ait?>%): </td>
                              <td align="right"><strong> <? echo number_format((($total*$tax_ait)/100),2);?> </strong></td>
                          </tr>
                      <? } $totaltaxait=($total*$tax_ait)/100; ?>

                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">SUB TOTAL:</td>
                              <td align="right"><strong>
                                      <?  echo number_format(($subtotal=$total+$asf+$totaltaxait-$cash_discount),2) ?>
                                  </strong></td>
                          </tr>

                          <? if($tax>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">VAT(<?=$tax;?> %):</td>
                              <td align="right"><strong><?  echo number_format((($subtotal*$tax)/100),2);?></strong></td>
                          </tr>
                          <? }
                          $tax_totals=($subtotal*$tax)/100;
                          ?>


                      <? if($transport_bill>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">Transport Bill: </td>
                              <td align="right"><strong> <? echo number_format(($transport_bill),2);?> </strong></td>
                          </tr>
                      <? }?>
                      <? if($labor_bill>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">Labor Bill: </td>
                              <td align="right"><strong> <? echo number_format(($labor_bill),2);?> </strong></td>
                          </tr>
                      <? }?>


                      <tr style="font-weight: bold">
                      <td colspan="8" align="right">Grand Total:</td>
                      <td align="right"><strong> <? echo number_format(($subtotal+$tax_totals+$transport_bill+$labor_bill-$cash_discount),2);?> </strong></td>
                      </tr>
                      </tbody>
                                </table>

                                
<?php
if(isset($_POST[checked])){
mysqli_query($conn, "Update ".$table." SET status='PROCESSING',authorized_date='$todayss' where ".$unique."=".$_GET[$unique]."");
$maild=find_a_field('user_activity_management','email','PBI_ID='.$master->entry_by);
///////////////////////// to authorise	
				
                //$to = $maild;
				$subject = "An asset has been Approved";
				$txt = "<p>Dear Sir/Madam,</p>
				<p>A new asset has been purchased. Purchased ID No is: <b>".$_GET[$unique]."</b></p>
				<p>Your approval is required. Please enter the <b>Employee Access module</b> to approval the AP.</p>
				<p>Checked By- <strong>".find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$master->checkby)."</strong></p>
				<p>Prepared By- <strong>".find_a_field('personnel_basic_info','PBI_NAME','ERP_ID='.$master->entry_by)."</strong></p>
				
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
                                    
                                     <?php if($current_status!=$required_status){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This purchase order has been Approved!!</i></h6>';} else { ?>
                                    
                                          
                                         
                                           <button type="submit" name="Return" id="Return" style="font-size:12px; float:left; margin-left:1%" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Return?");'>Return the Purchase Order</button>
                                            
                                              <input type="text" id="return_comments"   name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:166px; font-size:11px; margin-right:1%" placeholder="return comments........" >
                                            
                                          
                                             
                                                                                       
                                            
                                            
                                           <button type="submit" style="font-size:12px; float:right" onclick='return window.confirm("Are you confirm to Approved the AP?");' name="checked" id="checked" class="btn btn-success">Approved & Forward to Purchase</button>
                                                    
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>