<?php
require_once 'support_file.php';
$title='Inventory Return View';
$now=time();
$unique='id';
$unique_field='name';
$table="purchase_return_master";
$table_details="purchase_return_details";
$unique_details="m_id";
$journal_item="journal_item";
$journal_accounts="journal";
$page='acc_inventory_return_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$todaysss=date('Y-m-d H:i:s');
$jv=next_journal_voucher_id();
$masterDATA=find_all_field('purchase_return_master','','id='.$_GET[$unique] );
$lc_lc_received_batch_split = "lc_lc_received_batch_split";
$condition="create_date='".date('Y-m-d')."'";

$res_details='select
d.id,m.ref_no,i.item_id,m.warehouse_id,i.item_name,i.unit_name,i.finish_goods_code,d.qty,d.qc_qty,d.po_no,d.id as did,d.rate,d.amount,d.batch,m.return_date,
(SELECT SUM(item_in-item_ex) from journal_item WHERE item_id=i.item_id and batch=d.batch and warehouse_id=d.warehouse_id) as batch_stock_get,
d.cogs_price as batch_rate_get
from
'.$table.' m,'.$table_details.' d,warehouse w,vendor v,item_info i
where
m.id=d.m_id and
i.item_id=d.item_id and
m.warehouse_id=w.warehouse_id and
m.vendor_id=v.vendor_id and
m.id='.$_GET[$unique].'
group by d.id';

if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by_acc']=$_SESSION[userid];
        $_POST['checked_by_acc_at']=$todaysss;
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>window.close(); </script>";
    }

if(isset($_POST['checked'])){
      $data2=mysqli_query($conn, $res_details);
                             while($data=mysqli_fetch_object($data2)){
                                $new_batch = automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                                $idget=$data->id;
                                $_POST[ji_date]=$data->return_date;
                                $_POST[item_id]=$data->item_id;
                                $_POST[warehouse_id]=$data->warehouse_id;
                                $_POST[item_ex]=$data->qc_qty;
                                $_POST[item_price]=$data->rate;
                                $_POST[total_amt]=$data->qc_qty*$data->rate;
                                $_POST[tr_from]='Purchase_Returned';
                                $_POST[tr_no]=$$unique;
                                $_POST[sr_no]=$data->id;
                                $_POST[entry_by]=$_SESSION[userid];
                                $_POST[entry_at]=date('Y-m-d H:i:s');
                                $_POST[batch]=$data->batch;
                                $_POST[expiry_date] = find_a_field('lc_lc_received_batch_split','mfg','item_id='.$data->item_id.' and batch='.$data->batch.' and warehouse_id='.$data->warehouse_id.' and status in ("PROCESSING")');
                                $_POST[section_id]=$_SESSION[sectionid];
                                $_POST[company_id]=$_SESSION[companyid];
                                $crud      =new crud($journal_item);
                                $crud->insert();

                                $_POST[batch]=$new_batch;
                                $_POST[lot_number]=$data->batch;
                                $_POST['po_no'] = $_GET[$unique];
                                $_POST['create_date'] = date('Y-m-d');
                                $_POST['lc_id'] = $_GET[$unique];
                                $_POST['batch_no'] = find_a_field('lc_lc_received_batch_split','batch_no','batch='.$data->batch);
                                $_POST[item_id]=$data->item_id;
                                $_POST[warehouse_id]=$data->warehouse_id;
                                $_POST['qty'] = $data->qc_qty;
                                $_POST['rate'] = $data->cogs_price;
                                $_POST['batch'] = $new_batch;
                                $_POST['mfg'] = $data->mfg;
                                $_POST['status'] = 'PROCESSING';
                                $_POST['source'] = 'IR';
                                $_POST['line_id'] = $data->id;
                                $crud      =new crud($lc_lc_received_batch_split);
                                $crud->insert();




                              }
          if (($_POST[dr_amount_1] > 0) && ($_POST[cr_amount_2] > 0)) {
            add_to_journal_new($masterDATA->return_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1],0, Purchase_Returned, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            }
          if (($_POST[ledger_2] > 0) && ($_POST[cr_amount_2] > 0)) {
            add_to_journal_new($masterDATA->return_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_1],0, $_POST[cr_amount_2], Purchase_Returned, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            }
          if (($_POST[ledger_3] > 0) && ($_POST[cr_amount_3] > 0)) {
            add_to_journal_new($masterDATA->return_date, $proj_id, $jv, $date, $_POST[ledger_3], $_POST[narration_1],0, $_POST[cr_amount_3], Purchase_Returned, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            }
          if (($_POST[ledger_4] > 0) && ($_POST[cr_amount_4] > 0)) {
            add_to_journal_new($masterDATA->return_date, $proj_id, $jv, $date, $_POST[ledger_4], $_POST[narration_1],0, $_POST[cr_amount_4], Purchase_Returned, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            }
        $up_master=mysqli_query($conn,"UPDATE ".$table." SET status='COMPLETED',checked_by_acc='".$_SESSION[userid]."',checked_by_acc_at='".$todaysss."' where ".$unique."=".$$unique."");
        $up_details=mysqli_query($conn,"UPDATE ".$table_details." SET status='COMPLETED' where ".$unique_details."=".$$unique."");
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }

} // prevent_multi_submit


if(isset($_POST[viewreport])):
$sql="Select p.id,p.id,p.ref_no as Referance,p.remarks,p.return_date as date,w.warehouse_name,v.vendor_name,
concat(u.fname,', At: ',p.entry_at) as prepared_by,
concat((SELECT fname from user_activity_management where user_id=p.checked_by_qc),', At: ',checked_by_qc_at) as QC_By,
concat((SELECT fname from user_activity_management where user_id=p.checked_by_pro),', At: ',checked_by_pro_at) as Checked_By,
FORMAT((select SUM(amount) from purchase_return_details where m_id=p.id),2) as amount,p.status
from
".$table." p,
warehouse w,
user_activity_management u,
vendor v
 where
  p.entry_by=u.user_id and
 w.warehouse_id=p.warehouse_id and
 v.vendor_id=p.vendor_id and
 p.return_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' group by p.id order by p.".$unique." DESC ";
else :
$sql="Select p.id,p.id,p.ref_no as Referance,p.remarks,p.return_date,w.warehouse_name,v.vendor_name,
concat(u.fname,', At: ',p.entry_at) as prepared_by,
concat((SELECT fname from user_activity_management where user_id=p.checked_by_qc),', At: ',checked_by_qc_at) as QC_By,
concat((SELECT fname from user_activity_management where user_id=p.checked_by_pro),', At: ',checked_by_pro_at) as Checked_By,
FORMAT((select SUM(amount) from purchase_return_details where m_id=p.id),2) as amount,p.status
from
".$table." p,
warehouse w,
user_activity_management u,
vendor v
 where
  p.entry_by=u.user_id and
 w.warehouse_id=p.warehouse_id and
 v.vendor_id=p.vendor_id and p.status='PROCESSING' and mushak_challan_status not in ('UNRECORDED') group by p.id order by p.".$unique." DESC "; endif;  ?>

<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 250,top = -1");}
    </script>
<?php if(isset($_GET[$unique])):
 require_once 'body_content_without_menu.php'; else :
 require_once 'body_content.php'; endif;  ?>
<?php if(isset($_GET[$unique])): ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                          <th style="vertical-align:middle">#</th>
                          <th style="vertical-align:middle">Item Id</th>
                          <th style="vertical-align:middle">Item Name</th>
                          <th style="vertical-align:middle">Unit Name</th>
                          <th style="vertical-align:middle">Batch</th>
                          <th style="vertical-align:middle">Batch Rate</th>
                          <th style="vertical-align:middle; text-align:center">Inputed Qty</th>
                          <th style="vertical-align:middle; text-align:center">Apprv. Qty <br>(QC)</th>
                          <th style="vertical-align:middle; text-align:center">Rate</th>
                          <th style="vertical-align:middle; text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $query=mysqli_query($conn, $res_details);while($data=mysqli_fetch_object($query)){$ids=$row[id]; ?>
                            <tr>
                              <td style="vertical-align:middle"><?=$i=$i+1;?></td>
                              <td style="vertical-align:middle"><?=$data->finish_goods_code;?></td>
                              <td style="vertical-align:middle"><?=$data->item_name;?></td>
                              <td style="vertical-align:middle"><?=$data->unit_name;?></td>
                              <td style="vertical-align:middle"><?=$data->batch;?></td>
                              <td style="vertical-align:middle"><?=$data->batch_rate_get;?></td>
                              <td style="vertical-align:middle"><input type="text" class="form-control col-md-7 col-xs-12" name="batch_stock<?=$data->did?>" style="width: 80px; font-size: 11px; text-align:center" value="<?=$data->qty;?>" readonly id="batch_stock<?=$data->did?>"  class='batch_stock<?=$data->did?>'></td>
                              <td style="text-align:center; vertical-align:middle"><input class="form-control col-md-7 col-xs-12" type="text"  name="qty<?=$data->did?>" style="width: 80px; font-size: 11px; text-align:center" Value="<?=$data->qc_qty;?>" readonly  id="qty<?=$data->did?>"  class='qty<?=$data->did?>'></td>
                              <td style="vertical-align:middle"><input  type="text" class="form-control col-md-7 col-xs-12" style="width: 80px; font-size: 11px; text-align:center" readonly value="<?=$data->rate;?>" name="rate<?=$data->did?>" id="rate<?=$data->did?>" autocomplete="off" class='rate<?=$data->did?>'></td>
                              <td style="vertical-align:middle"><input class="form-control col-md-7 col-xs-12" style="width: 80px; font-size: 11px; text-align:right" value="<?=$data->amount;?>" readonly type='text' id='sum<?=$data->did?>' name='sum<?=$data->did?>' class='sum' /></td>
                            </tr><?php $total_amount=$total_amount+$data->amount;
$cogs_amount=$cogs_amount+($data->qc_qty*$data->batch_rate_get);
                          }?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="9" style="font-weight:bold; font-size:11px" align="right">Total Inventory Return in Value = </td>
                            <td align="right" ><?=number_format($total_amount,2);?></td>
                        </tr>
                    </table>



                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="vertical-align: middle">#</th>
                            <th style="width: 12%; vertical-align: middle; text-align: center">For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 25%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center; vertical-align:middle">1</td>
                            <td style="text-align: center; vertical-align:middle">Vendor Ledger</td>
                            <td style="vertical-align:middle"><?
                                $vendor_ledger=find_a_field('vendor','ledger_id','vendor_id='.$masterDATA->vendor_id.'');
                                $warehouse_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$masterDATA->warehouse_id.'');?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $vendor_ledger, 'ledger_id='.$vendor_ledger.''); ?>
                                </select>
                            </td>
                            <td rowspan="4" style="text-align: center; vertical-align:middle"><textarea name="narration_1" id="narration_1"  class="form-control col-md-7 col-xs-12" style="width:100%; height:205px; font-size: 11px; text-align:center">Inventory Return to <?=find_a_field('vendor','vendor_name','vendor_id='.$masterDATA->vendor_id)?>,IR No # <?=$$unique?>, Ref.No # <?=$masterDATA->ref_no;?>,<?=$masterDATA->remarks;?></textarea></td>
                            <td align="center" style="vertical-align:middle"><input type="text" name="dr_amount_1" readonly value="<?=$total_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center" style="vertical-align:middle"><!--input type="text" name="cr_amount_1" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" --></td>
                        </tr>

                        <tr>
                            <td style="text-align: center; vertical-align:middle">2</td>
                            <td style="text-align: center;vertical-align:middle">Inventory Ledger</td>
                            <td style="vertical-align:middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_2">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $warehouse_ledger, 'ledger_group_id in ("1007")'); ?>
                                </select>
                            </td>
                            <td style="text-align: right; vertical-align:middle"><!--input type="text" name="dr_amount_2" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" --></td>
                            <td style="text-align: right; vertical-align:middle"><input type="text" name="cr_amount_2" readonly value="<?=$cogs_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <?php $total_VAT=find_a_field('VAT_mushak_6_3_details','SUM(amount_of_VAT)','source="Purchase_Returned" and do_no='.$_GET[id]); if($total_VAT>0):?>
                        <tr>
                            <td style="text-align: center;vertical-align:middle">3</td>
                            <td style="text-align: center;vertical-align:middle">VAT Account</td>
                            <td style="vertical-align:middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_3">
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_3, 'ledger_id in ("1005000400000000")'); ?>
                                </select>
                            </td>
                            <td style="text-align: right; vertical-align:middle"><input type="text" name="dr_amount_3" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right; vertical-align:middle"><input type="text" name="cr_amount_3" readonly value="<?=$total_VAT;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <?php endif; $other_income=$total_amount-($cogs_amount+$total_VAT); ?>
                        <tr>
                            <td style="text-align: center; vertical-align:middle">4</td>
                            <td style="text-align: center; vertical-align:middle">Income Ledger</td>
                            <td style="vertical-align:middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_4">
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_4, 'ledger_id in ("3001000600000000")'); ?>
                                </select>
                            </td>
                            <td style="text-align: right;vertical-align:middle"><input type="text" name="dr_amount_4" readonly value="<?php if($other_income<0) echo substr($other_income,1); else echo '0.00';?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right;vertical-align:middle"><input type="text" name="cr_amount_4" readonly value="<?php if($other_income>0) echo $other_income; else echo '0.00';?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr><?php  ?>
                        </tbody>
                    </table>



                    <?php
                    $GET_status=find_a_field(''.$table.'','status','mushak_challan_status not in ("UNRECORDED") and '.$unique.'='.$_GET[$unique]);
                    if($GET_status=='PROCESSING'):  ?>
                        <p>
                            <button style="float: left; margin-left:1%; font-size:12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; margin-right:1%; font-size:12px" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Confirm the Inventory Return </button>
                        </p>
                    <?  else : echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This inventory return has been CHECKED !!</i></h6>';endif;?>
                </form>
            </div>
        </div>
    </div>

<?php endif;  ?>

<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Inventory Return</button></td>
            </tr></table>
</form>
<?=$crud->report_templates_with_status($sql,$title);?>
<?php endif;  ?>
<?=$html->footer_content();?>
