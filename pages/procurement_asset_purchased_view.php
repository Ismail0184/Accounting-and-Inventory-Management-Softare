<?php
require_once 'support_file.php';
$title='Asset Purchase Report';
$now=time();
$unique='po_no';
$unique_field='po_date';
$table="purchase_master";
$table_details="purchase_invoice";
$journal_item="journal_item";
$journal_accounts="journal";
$page='procurement_asset_purchased_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['reprocess'])) {
        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_procurement_asset_purchase'] = $_GET[$unique];
        $type = 1;
        echo "<script>self.opener.location = 'procurement_asset_purchase.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {   $up_master="UPDATE ".$table." SET status='CHECKED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='CHECKED' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$vendor_info=find_all_field("vendor","","vendor_id=".$vendor_id."");
$po_master=find_all_field(''.$table.'','',''.$unique.'='.$$unique.'');	
$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <input type="hidden" name="dr_ledger" id="dr_ledger" value="<?=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$warehouse_id.'');?>">
                    <input type="hidden" name="cr_ledger" id="cr_ledger" value="<?=find_a_field('vendor','ledger_id','vendor_id='.$vendor_id.'');?>">
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
							$ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$i=$i+1;?></td>
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
							$ttotal_unit=$ttotal_unit+$row[qty];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $total=$total+$row[amount];
							  }
							$total=$total-$cash_discount;
							
							   ?>







                      


                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">TOTAL:</td>
                              <td align="right"><strong> <?=number_format(($total),2);?></strong></td>
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
                      <td align="right"><strong> <? echo number_format(($gt=$subtotal+$tax_totals+$transport_bill+$labor_bill),2);?> </strong></td>
                      </tr>
                      </tbody>
                                </table>





                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='CANCELED'){
						if($po_master->entry_by==$_SESSION[userid]){
						
						  ?>
                        <p>
                            <button style="float: left; margin-left: 1%; font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Re-process the AP</button>
                            <!--button style="float: right; margin-right: 1%; font-size:12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Completed</button-->
                        </p>
                    <? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This work order was created by another person. So you are not able to do anything here!!</i></h6>';
					}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This asset purchase has been checked !!</i></h6>';}?>
                    
                    
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Asset Purchased</button></td>
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
                            <th>Print</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                        $from_date=$_POST[f_date];
                        $to_date=$_POST[t_date];
                        if(isset($_POST[viewreport])){
                            $res='select r.'.$unique.',r.'.$unique.' as PO_NO,r.'.$unique_field.' as Purchased_Date,r.entry_at,v.vendor_name,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.po_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.checked_by) as checked_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorise) as authorised_person,r.status,r.authorized_date
				  from '.$table.' r,
				  vendor v
				  WHERE 
				  r.vendor_id=v.vendor_id and 
				  r.po_type in ("Asset") and
				  r.po_date between "'.$from_date.'" and "'.$to_date.'"  	  
				   order by r.'.$unique.' DESC';
                            $pquery=mysqli_query($conn, $res);
                            while($req=mysqli_fetch_object($pquery)){ ?>
                                <tr style="cursor: pointer">
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$i=$i+1;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->$unique;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->Purchased_Date;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->Remarks;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->vendor_name;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->Purchased_By;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->entry_at;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->status;?></td>
                                    <td style="text-align: center; vertical-align: middle"  ><a target="_blank" href="ap_print_view.php?po_no=<?=$req->po_no;?>"><img src="http://icpbd-erp.com/51816/warehouse_mod/images/print.png" width="20" height="20" /></a></td>
                                </tr>
                            <?php }} ?></tbody></table>

                </div></div></div></form>
<?php } ?>

<?php require_once 'footer_content.php' ?>