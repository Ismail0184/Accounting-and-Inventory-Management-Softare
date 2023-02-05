<?php
require_once 'support_file.php';
$title='Asset Purchase Report';
$now=time();
$unique='or_no';
$unique_field='or_no';
$table="purchase_master_asset";
$table_details="purchase_invoice_asset";
$journal_item="journal_item";
$journal_accounts="journal";
$page='accounts_asset_purchased_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['reprocess'])) {

        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_hrm_stationary_purchase'] = $_GET[$unique];
        $type = 1;
        echo "<script>self.opener.location = 'hrm_stationary_purchase.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
         
	
		 $results=mysqli_query($conn,"Select srd.*,i.*,ar.*,d.DEPT_SHORT_NAME as department 
						from 
						purchase_invoice_asset srd, item_info i, asset_register ar, department d  where
 srd.item_id=i.item_id and ar.item_id=i.item_id and d.DEPT_ID=ar.DEPT_ID and 
 srd.".$unique."=".$_GET[$unique]." order by srd.id");
                        while($row=mysqli_fetch_array($results)){
							$ids=$row[id];
            $_POST['ji_date'] = $ji_date;
            $_POST['item_id'] = $row[item_id];
			$_POST['asset_id'] = $row[asset_id];
            $_POST['warehouse_id'] = $row[warehouse_id];
            $_POST['item_in'] = $row[total_qty];
            $_POST['item_price'] = $row[unit_price];
            $_POST['total_amt'] = $row[total_amt];
            $_POST['tr_from'] = 'Asset_Purchase';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();
        }

        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $enat=date('Y-m-d h:s:i');
        $cd =$_POST[c_date];
        $c_date=date('Y-m-d' , strtotime($cd));
        $invoice=$_POST[invoice];
        $date=date('d-m-y' , strtotime($transaction_date));
        $narration='Purchase, PO#'.$$unique;
        $j=0;
        for($i=0;$i<strlen($date);$i++)
        {
            if(is_numeric($date[$i]))
            { $time[$j]=$time[$j].$date[$i];
            } else {
                $j++; } }
        $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
        add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], Purchase, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_2], $_POST[dr_amount_2], $_POST[cr_amount_2], Purchase, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);

        $up_master="UPDATE ".$table." SET status='COMPLETED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='COMPLETED' where ".$unique."=".$$unique."";
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
                            <th style="text-align:center">Person</th>
                            <th style="text-align:center">Where Kept</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Qty</th>
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
                                <td style="vertical-align:middle"><?=$row[asset_id];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?> : <?=$row[specification];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[department];?></td>
                                <td align="center" style=" text-align:left;vertical-align:middle;"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$row[PBI_ID].'');?></td>             
                                <td align="center" style=" text-align:left;vertical-align:middle;"><?=$row[where_kept]; ?></td>
                                <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[unit_price]; ?></td>
                                <td align="center" style=" text-align:center;vertical-align:middle;"><?=$row[total_qty]; ?></td>
                                <td align="center" style="text-align:right;vertical-align:middle;"><?=number_format($row[total_amt],2);?></td>

                            </tr>
                            <?php  
							$ttotal_unit=$ttotal_unit+$row[total_unit];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $total=$total+$row[total_amt];  } ?>







                      <? if($cash_discount>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">Discount:</td>
                              <td align="right"><strong>
                                      <? if($cash_discount>0) echo number_format($cash_discount,2); else echo '0.00';?>
                                  </strong></td>
                          </tr>
                      <? }?>



                          <tr style="font-weight: bold">

                              <td colspan="8" align="right">TOTAL:</td>
                              <td align="right"><strong>
                                      <?  echo number_format(($total),2);?>
                                  </strong></td>
                          </tr>





                      <? if($tax_ait>0){?>
                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">AIT/Tax (<?=$tax_ait?>%): </td>
                              <td align="right"><strong> <? echo number_format((($total*$tax_ait)/100),2);?> </strong></td>
                          </tr>
                      <? } $totaltaxait=($total*$tax_ait)/100; ?>

                          <tr style="font-weight: bold">
                              <td colspan="8" align="right">SUB TOTAL:</td>
                              <td align="right"><strong>
                                      <?  echo number_format(($subtotal=$total+$asf+$totaltaxait),2) ?>
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
                      <td align="right"><strong> <? echo number_format(($gt=$subtotal+$tax_totals+$transport_bill+$labor_bill-$cash_discount),2);?> </strong></td>
                      </tr>
                      </tbody>
                                </table>


                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="width: 12%">For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 30%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center; vertical-align:middle">1</td>
                            <td style="text-align: center; vertical-align:middle">Asset Ledger</td>
                            <td style="text-align: center; vertical-align:middle"><?php $sales_return_ledger=$config_group_class->sales_return;?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px"  required="required"  name="ledger_1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_return_ledger, 'ledger_group_id="1001"'); ?>
                                </select>
                            </td>

                            <td style="text-align: center;vertical-align:middle"><textarea  name="narration_1" id="narration_1"  class="form-control col-md-7 col-xs-12" style="width:100%; height:60px; font-size: 11px; text-align:center">Asset Purchase, Delivery Challan#<?=$chalan_no;?>, ID#<?=$_GET[$unique];?></textarea></td>
                            <td style="text-align: center;vertical-align:middle"><input type="text" name="dr_amount_1" readonly value="<?=$gt;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: center;vertical-align:middle"><input type="text" name="cr_amount_1" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center; vertical-align:middle">2</td>
                            <td style="text-align: center; vertical-align:middle">Vendor Ledger</td>
                            <td style="vertical-align:middle">

                                <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_2" id="ledger_2">
                                    <option  value="<?=$vendor_info->ledger_id;?>"><?=$vendor_info->ledger_id; ?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$vendor_info->ledger_id.''); ?></option>

                                </select></td>

                            <td style="text-align: center;vertical-align:middle "><textarea  name="narration_2" id="narration_2" class="form-control col-md-7 col-xs-12" style="width:100%; height:60px; font-size: 11px; text-align:center">Asset Purchase, Delivery Challan#<?=$chalan_no;?>, ID#<?=$_GET[$unique];?></textarea></td>
                            <td style="text-align: right; vertical-align:middle"><input type="text" name="dr_amount_2" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center; vertical-align:middle" ></td>
                            <td style="text-align: right; vertical-align:middle"><input type="text" name="cr_amount_2" readonly value="<?=$gt;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        </tbody>
                    </table>




                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='APPROVED'){  ?>
                        <p>
                            <button style="float: left; margin-left: 1%; font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Return the Asset Purchase</button>
                            <button style="float: right; margin-right: 1%; font-size:12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Completed the AP</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Asset Purchase has been completed !!</i></h6>';}?>
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
                            <th style="text-align:center">Chalan No</th>
                            <th style="text-align:center">Purchased By</th>
                            <th style="text-align:center">Entry At</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                        $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                        $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                        if(isset($_POST[viewreport])){

                            $res='select r.'.$unique.',r.'.$unique.' as PO_NO,r.'.$unique_field.' as Purchased_Date,r.entry_at,r.chalan_no,v.vendor_name,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.or_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.checked_by) as checked_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorise) as authorised_person,r.status,r.authorized_date
				  from '.$table.' r,
				  vendor v
				  WHERE 
				  r.vendor_id=v.vendor_id and 
				  r.or_date between "'.$from_date.'" and "'.$to_date.'"  	  
				   order by r.'.$unique.' DESC'; } else {
					   
					 $res='select r.'.$unique.',r.'.$unique.' as PO_NO,r.'.$unique_field.' as Purchased_Date,r.entry_at,r.chalan_no,v.vendor_name,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.or_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.checked_by) as checked_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.authorise) as authorised_person,r.status,r.authorized_date
				  from '.$table.' r,
				  vendor v
				  WHERE 
				  r.vendor_id=v.vendor_id and 
				  r.status="APPROVED"  	  
				   order by r.'.$unique.' DESC';   
				   }
                            $pquery=mysqli_query($conn, $res);
                            while($req=mysqli_fetch_object($pquery)){

                                ?>
                                <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                                    <td><?=$i=$i+1;?></td>
                                    <td><?=$req->$unique;?></td>
                                    <td><?=$req->Purchased_Date;?></td>
                                    <td><?=$req->Remarks;?></td>
                                    <td><?=$req->vendor_name;?></td>
                                    <td><?=$req->chalan_no;?></td>
                                    <td><?=$req->Purchased_By;?></td>
                                    <td><?=$req->entry_at;?></td>
                                    <td><?=$req->status;?></td>
                                </tr>
                            <?php } ?></tbody></table>

                </div></div></div></form>
<?php } ?>

<?php require_once 'footer_content.php' ?>