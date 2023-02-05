 <?php
require_once 'support_file.php';
$title="Purchase Order List";
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
$page="hrm_requisition_purchase_order_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if(isset($_POST['reprocess']))
        {   $_POST['status']='MANUAL';
            $crud->update($table);
            $_SESSION[$unique]=$$unique;
            $type=1;
            echo "<script>self.opener.location = 'po_create_item.php'; self.blur(); </script>";
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 200,top = -1");}
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
                     <th style="width: 6%">PO NO</th>
                     <th style="width: 10%">Date</th>
                     <th style="">Remarks</th>
                     <th style="width:30%">Vendor</th>
                     <th style="">Status</th>
                     <th style="width:20%">Create By</th>
                     </tr>
                     </thead>
                      <tbody>
                 <? 	$res=mysql_query('select v.*,r.'.$unique.',r.'.$unique.',r.'.$unique_field.',po_date,r.status,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as entry_by,r.po_details as Remarks
							 from '.$table.' r,
				  vendor v
				  WHERE r.entry_by='.$_SESSION['PBI_ID'].' and
				  r.vendor_id=v.vendor_id			  
				   order by r.'.$unique.' DESC');
				   while($req=mysql_fetch_object($res)){
				   
				   ?>
                   <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                <td><?=$i=$i+1;?></td>
                                <td style="text-align: center"><?=$req->$unique;?></td>
                                <td><?=$req->po_date;?></td>
                                <td><?=$req->$unique_field;?></td>
                                <td><?=$req->vendor_name;?></td>
                                <td><?=$req->status;?></td>
                                <td><?=$req->entry_by;?></td>
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

                                     <?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="CANCELED"){ echo '<h5 style="text-align:center; color:red; font-weight:bold"><i>This purchase order has been recommended!!</i></h5>';} else { ?>
                                     <table style="width:100%;font-size:12px">
                                          <tr>
                                              <td>
                                                  <div class="form-group">
                                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                                          <button type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-processing the Purchase Order</button>
                                                      </div></div></td>

                                              <td style="float: right">
                                                  <div class="form-group">
                                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                                          <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted All Data</button>
                                                      </div></div></td></tr></table>
                                            <?php } ?>                               
                                                                                                                                   
                                    


                                </form>
                                </div>
                                </div>
                                </div>
<?php } ?>


                
        
<?php require_once 'footer_content.php' ?>