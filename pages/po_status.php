<?php
require_once 'support_file.php';

$title='View Work Order';

$table = 'purchase_master';
$table_details="purchase_invoice";
$unique = 'po_no';
$status = 'UNCHECKED';
$page="po_status.php";
$print_page="po_print_view.php";
$crud      =new crud($table);
$$unique=$_GET[$unique];
if (isset($_POST['reprocess'])) {

        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_po_no'] = $_GET[$unique];
        $type = 1;
        echo "<script>self.opener.location = 'po_create_item.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }
$po_master=find_all_field(''.$table.'','',''.$unique.'='.$$unique.'');	
$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);

if(isset($_POST['viewreport'])){

    $res='select  
    a.po_no,
    a.vendor_id, 
    a.po_no, 
    a.exim_status as type, 
    a.po_date as Work_order_Date, 
    v.vendor_name, 
    b.warehouse_name as final_Destination,
    a.work_order_for_department as Created_By_Department,
    c.fname,
    p.PBI_NAME as Check_By,
    a.delivery_within,a.status 
    
    from 
    purchase_master a,
    warehouse b,
    user_activity_management c,
    vendor v,
    personnel_basic_info p 
    
    where  
    a.warehouse_id=b.warehouse_id and 
    a.entry_by=c.user_id and 
    a.checkby=p.PBI_ID and 
    a.vendor_id=v.vendor_id and
    a.po_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"
    order by a.po_no desc';
    } else { 
    $res='select  
    a.po_no,
    a.vendor_id, 
    a.po_no, 
    a.return_comments,
    a.exim_status as type, 
    a.po_date as Work_order_Date, 
    v.vendor_name, 
    b.warehouse_name as final_Destination,
    a.work_order_for_department as Created_By_Department,
    c.fname,
    p.PBI_NAME as Check_By,
    a.delivery_within,a.status 
    
    from 
    purchase_master a,
    warehouse b,
    user_activity_management c,
    vendor v,
    personnel_basic_info p 
    
    where  
    a.warehouse_id=b.warehouse_id and 
    a.entry_by=c.user_id and 
    a.checkby=p.PBI_ID and 
    a.vendor_id=v.vendor_id and
    a.status in ("MANUAL","CANCELED") 
    order by a.po_no desc';
    }
	?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
    </script>
</head>


<?php 
 if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } if(isset($_GET[$unique])){ ?>
     <!-------------------list view ------------------------->
     <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
     <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel"> 
         <div class="x_content">
            <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                   <thead>
                    <tr style="background-color: bisque">
                     <th style="width: 2%">#</th>
                        <th>PO</th>
                        <th>Final Destination</th>
                     <th style="">Item Code</th>
                     <th style="">Description of the Goods</th>
                     <th style="text-align: center">UOM</th>
                     <th style="text-align: center">Pre. Rate</th>
                     <th style="text-align: center">Rate</th>
                     <th style="text-align: center">Qty</th>
                     <th style="text-align: center">Amount</th>
                     </tr>
                     </thead>
                      <tbody>
                      <?php
                      $res=mysqli_query($conn, 'Select td.*,i.*,w.warehouse_name,
 (select rate from '.$table_details.' where '.$unique.'!='.$_GET[$unique].' and item_id=i.item_id order by id DESC limit 1) as pre_rate
 from '.$table_details.' td,
				 item_info i,warehouse w
				  where td.item_id=i.item_id and
				  td.warehouse_id=w.warehouse_id and  				  
				  td.'.$unique.'='.$_GET[$unique].'');
				   while($req_data=mysqli_fetch_object($res)){
				   ?>
                   <tr>
                                <td><?=$i=$i+1;?></td>
                                <td><?=$req_data->po_no;?></td>
                                <td><?=$req_data->warehouse_name;?></td>
                                <td><?=$req_data->finish_goods_code;?></td>
                                <td><?=$req_data->item_name;?></td>
                                <td style="text-align:center"><?=$req_data->unit_name;?></td>
                                <td style="text-align:center"><?=$req_data->pre_rate;?></td>
                                <td style="text-align:center"><?=number_format($req_data->rate,2);?></td>
                                <td style="text-align:center"><?=number_format($req_data->qty,2);?></td>
                                <td style="text-align: right"><?=number_format($req_data->amount,2);?></td>
                                </tr>
                                <?php $total=$total+$req_data->amount;  } ?>




                      <tr style="font-weight: bold">

                          <td colspan="9" align="right">TOTAL:</td>
                          <td align="right"><strong>
                                  <?  echo number_format(($total),2);?>
                              </strong></td>
                      </tr>


                      <? if($cash_discount>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="9" align="right">Discount:</td>
                              <td align="right"><strong>
                                      <? if($cash_discount>0) echo number_format($cash_discount,2); else echo '0.00';?>
                                  </strong></td>
                          </tr>
                      <? }?>


                      <? if($tax_ait>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="9" align="right">AIT/Tax (<?=$tax_ait?>%): </td>
                              <td align="right"><strong> <? echo number_format((($total-$cash_discount*$tax_ait)/100),2);?> </strong></td>
                          </tr>
                      <? } $totaltaxait=($total*$tax_ait)/100; ?>

                          <tr style="font-weight: bold">
                              <td colspan="9" align="right">SUB TOTAL:</td>
                              <td align="right"><strong>
                                      <?  echo number_format(($subtotal=$total+$asf+$totaltaxait-$cash_discount),2) ?>
                                  </strong></td>
                          </tr>

                          <? if($tax>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="9" align="right">VAT(<?=$tax;?> %):</td>
                              <td align="right"><strong><?  echo number_format((($subtotal*$tax)/100),2);?></strong></td>
                          </tr>
                          <? }
                          $tax_totals=($subtotal*$tax)/100;
                          ?>


                      <? if($transport_bill>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="9" align="right">Transport Bill: </td>
                              <td align="right"><strong> <? echo number_format(($transport_bill),2);?> </strong></td>
                          </tr>
                      <? }?>
                      <? if($labor_bill>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="9" align="right">Labor Bill: </td>
                              <td align="right"><strong> <? echo number_format(($labor_bill),2);?> </strong></td>
                          </tr>
                      <? }?>


                      <tr style="font-weight: bold">
                      <td colspan="9" align="right">Grand Total:</td>
                      <td align="right"><strong> <? echo number_format(($subtotal+$tax_totals+$transport_bill+$labor_bill),2);?> </strong></td>
                      </tr>
                      </tbody>
                                </table>
                                
                                <?php if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='CANCELED'){
					if($po_master->entry_by==$_SESSION[userid]){ ?>
                        <p align="center">
                            <button style="font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-process the Work Order</button>
                            <!--button style="float: right; margin-right: 1%; font-size:12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Completed</button-->
                        </p>
                    <? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This work order was created by another person. So you are not able to do anything here!!</i></h6>';
					}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This purchase has been checked !!</i></h6>';}?>
                
             </div> </div></div></form>
     <!-------------------End of  List View --------------------->
 <?php } ?>
<?php if(!isset($_GET[$unique])){ ?>

<form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px;"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px;"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Report</button></td>
            </tr></table> 
</form>



<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                <table id="datatable-buttons" class="table table-striped table-bordered th" style="width:100%; font-size: 11px">
                    <thead>
                    <tr style="background-color: #3caae4; color:white">
                        <th style="height:50px;vertical-align:middle; text-align:center">SL</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">PO</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">Date</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">Vendor Name</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">F. Destination</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">Entry By</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">Delv. Date</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">Type</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">Status</th>
                        <th style="height:50px;vertical-align:middle; text-align:center">Returned Remarks</th>
                        <th style="height:50px;vertical-align:middle; text-align:center; width:8%">Print View</th>
                    </tr>
                </thead>
                <tbody>

<? 
$qqq=mysql_query($res);
while($data=mysql_fetch_object($qqq)){
    $i=$i+1;
	$department=$data->Created_By_Department;
	$link='po_print_view.php?potype='.$department.'&po_no='.$data->po_no;
	list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $data->delivery_within); ?>

 <tr style=" cursor: pointer">
 <td style="text-align: center" onclick="DoNavPOPUP('<?=$data->po_no?>', 'TEST!?', 900, 600)"><?=$i?></td>
 <td  align="center" style="padding:5px">
 <a href="../page/po_documents/qoutationDoc/<?=$data->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Quotation Attached"><u><strong>
  <?=$data->po_no?></strong></u></a></td>
 <td  align="center" style="padding:5px;width:8%">
 <a href="../page/po_documents/mailCommDoc/<?=$data->$unique.'.pdf';?>" target="_blank" style="color:#06F" title="Email Conversation Attached"><u><strong><?=$data->Work_order_Date?></strong></u></a></td>
 <td  align="left" style="padding:5px" onclick="DoNavPOPUP('<?=$data->po_no?>', 'TEST!?', 900, 600)"><?=$data->vendor_name?></td>
     <td  align="left" style="padding:5px" onclick="DoNavPOPUP('<?=$data->po_no?>', 'TEST!?', 900, 600)"><?=$data->final_Destination?></td>
 <td  align="left" style="padding:5px" onclick="DoNavPOPUP('<?=$data->po_no?>', 'TEST!?', 900, 600)"><?=$data->fname?></td>
 <td  align="left" style="padding:5px; width:8%" onclick="DoNavPOPUP('<?=$data->po_no?>', 'TEST!?', 900, 600)"><?= $day.'-'.$month.'-'.$year1?></td>
 <td  align="center" style="padding:5px" onclick="DoNavPOPUP('<?=$data->po_no?>', 'TEST!?', 900, 600)"><?=$data->type?></td>
     <td  align="center" style=" <?php if( $data->status=='COMPLETED') { echo 'background-color:#9C6';  } else if ( $data->status=='UNCHECKED') { echo 'background-color:red';  } else if ( $data->status=='CHECKED') { echo 'background-color:yellow';  }  ?>;padding:5px"><?=$data->status?></td>
     <td  align="left" style="padding:5px" onclick="DoNavPOPUP('<?=$data->po_no?>', 'TEST!?', 900, 600)"><?=$data->return_comments?></td>     
     <td style="text-align: center; vertical-align: middle"><a target="_blank" href="<?=$print_page;?>?<?=$unique;?>=<?=$data->po_no;?>"><img src="http://icpbd-erp.com/51816/warehouse_mod/images/print.png" width="20" height="20" /></a></td>
 </tr>
 <?php } ?>

 </tbody>
</table>
</div></div></div>
    <?php } ?>
    <?=$html->footer_content();mysqli_close($conn);?>