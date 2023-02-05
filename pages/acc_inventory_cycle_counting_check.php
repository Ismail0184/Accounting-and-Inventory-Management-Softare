<?php
require_once 'support_file.php';
$title='Inventory Cycle Counting';
$now=date('Y-m-d H:i:s');
$unique='cc_no';
$unique_field='cc_date';
$table="acc_cycle_counting_master";
$table_details="acc_cycle_counting_detail";

$journal_item="journal_item";
$journal_accounts="journal";
$page='acc_inventory_cycle_counting_check.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$todaysss=date('Y-m-d H:i:s');
$jv=next_journal_voucher_id();
$masterDATA=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique] );
$lc_lc_received_batch_split = "lc_lc_received_batch_split";
$condition="create_date='".date('Y-m-d')."'";
$new_batch = automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');


$res_details='SELECT
m.'.$unique.',
m.'.$unique.',
i.item_name,
i.unit_name,
i.finish_goods_code,
d.id,
d.qty,
d.item_price,
d.total_amt,
d.batch,
d.cc_type,
d.mfg,
d.item_id,
d.warehouse_id,
m.cc_date

FROM
'.$table.' m,
'.$table_details.' d,
item_info i

WHERE
m.'.$unique.'='.$_GET[$unique].' and
m.'.$unique.'=d.'.$unique.' and
d.item_id=i.item_id';

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
                                $_POST['entry_by'] = $_SESSION['userid'];
                                $_POST['entry_at'] = date('Y-m-d H:i:s');
                                $_POST['section_id'] = $_SESSION['sectionid'];
                                $_POST['company_id'] = $_SESSION['companyid'];

                                $idget=$data->id;
                                $_POST[ji_date]=$data->cc_date;
                                $_POST[item_id]=$data->item_id;
                                $_POST[warehouse_id]=$data->warehouse_id;
                                if($data->cc_type=='-'){
                                  $_POST[item_in]=0;
                                  $_POST[item_ex]=$data->qty;
                                } elseif ($data->cc_type=='+') {
                                  $_POST[item_in]=$data->qty;
                                  $_POST[item_ex]=0;
                                }
                                $_POST[item_price]=$data->item_price;
                                $_POST[total_amt]=$data->total_amt;
                                $_POST[tr_from]='Cycle_Counting';
                                $_POST[tr_no]=$$unique;
                                $_POST[sr_no]=$data->id;

                                if($data->cc_type=='-'){
                                    $_POST[batch]=$data->batch;
                                    $_POST[lot_number]=0;
                                }
                                if ($data->cc_type=='+') {
                                    $new_batch = automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                                    $_POST[batch]=$new_batch;
                                    $_POST[lot_number]=$data->batch;

                                    $_POST['po_no'] = $_GET[$unique];
                                $_POST['create_date'] = date('Y-m-d');
                                $_POST['lc_id'] = $_GET[$unique];
                                $_POST['batch_no'] = $data->batch;
                                $_POST[item_id]=$data->item_id;
                                $_POST[warehouse_id]=$data->warehouse_id;
                                $_POST['qty'] = $data->qty;
                                $_POST['rate'] = $data->item_price;
                                $_POST['batch'] = $new_batch;
                                $_POST['mfg'] = $data->mfg;
                                $_POST['status'] = 'PROCESSING';
                                $_POST['source'] = 'CC';
                                $_POST['line_id'] = $data->id;
                                $crud      =new crud($lc_lc_received_batch_split);
                                $crud->insert();

                                }

                                $_POST[expiry_date] = $data->mfg;
                                $_POST[section_id]=$_SESSION[sectionid];
                                $_POST[company_id]=$_SESSION[companyid];
                                $crud      =new crud($journal_item);
                                $crud->insert();
                                

                              }
          if (($_POST[dr_amount_1] > 0) && ($_POST[cr_amount_2] > 0)) {
            add_to_journal_new($masterDATA->cc_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1],0, Cycle_Counting, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($masterDATA->cc_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_1],0, $_POST[cr_amount_2], Cycle_Counting, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
          }

            if (($_POST[dr_amount_1] > 0) && ($_POST[cr_amount_2] > 0)) {
              add_to_journal_new($masterDATA->cc_date, $proj_id, $jv, $date, $_POST[ledger_3], $_POST[narration_3], $_POST[dr_amount_3],0, Cycle_Counting, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
              add_to_journal_new($masterDATA->cc_date, $proj_id, $jv, $date, $_POST[ledger_4], $_POST[narration_3],0, $_POST[cr_amount_4], Cycle_Counting, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
              }

        $up_master=mysqli_query($conn,"UPDATE ".$table." SET status='COMPLETED',checked_by_acc='".$_SESSION[userid]."',checked_by_acc_at='".$todaysss."' where ".$unique."=".$_GET[$unique]."");
        $up_details=mysqli_query($conn,"UPDATE ".$table_details." SET status='COMPLETED' where ".$unique_details."=".$_GET[$unique]."");
        $type=1;
        unset($_POST);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

} // prevent_multi_submit


if (isset($_POST[viewreport])) {
    $sql='SELECT m.cc_no,m.cc_no,m.cc_date as date,m.remarks,w.warehouse_name,concat(uam.fname,"<br>","at: ",m.entry_at) as entry_by,IF(m.checked_by_qc>0,concat((SELECT fname from user_activity_management where user_id=m.checked_by_qc),"<br>","at: ",m.checked_by_qc_at), "PENDING " ) AS QC_check_Status,
    IF(m.checked_by_acc>0,concat((SELECT fname from user_activity_management where user_id=m.checked_by_acc),"<br>","at: ",m.checked_by_qc_at), "PENDING " ) AS Accounts_check_status,m.status
    from '.$table.' m, warehouse w,user_activity_management uam
    where
    m.warehouse_id=w.warehouse_id and
    m.entry_by=uam.user_id and m.warehouse_id='.$_POST[warehouse_id].' order by m.cc_no';
  } else {
    $sql='SELECT m.cc_no,m.cc_no,m.cc_date as date,m.remarks,w.warehouse_name,concat(uam.fname,"<br>","at: ",m.entry_at) as entry_by,IF(m.checked_by_qc>0,concat((SELECT fname from user_activity_management where user_id=m.checked_by_qc),"<br>","at: ",m.checked_by_qc_at), "PENDING " ) AS QC_check_Status,m.status
    from '.$table.' m, warehouse w,user_activity_management uam
    where
    m.warehouse_id=w.warehouse_id and
    m.entry_by=uam.user_id and
    m.status="CHECKED" order by m.cc_no';
  } $config_group_class=find_all_field("config_group_class","","1"); ?>

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
                    <? //require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                          <th style="vertical-align:middle">#</th>
                          <th style="vertical-align:middle">Item Id</th>
                          <th style="vertical-align:middle">Item Name</th>
                          <th style="vertical-align:middle">Unit Name</th>
                          <th style="vertical-align:middle">Batch</th>
                          <th style="vertical-align:middle">Expiry Date</th>
                          <th style="vertical-align:middle">Qty</th>
                          <th style="vertical-align:middle; text-align:center">Rate</th>
                          <th style="vertical-align:middle; text-align:center">Amount</th>
                          <th style="vertical-align:middle; text-align:center">CC Type</th>
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
                              <td style="vertical-align:middle"><?=$data->mfg;?></td>
                              <td style="text-align:center; vertical-align:middle"><?=$data->qty;?></td>
                              <td align="right" style="vertical-align:middle; text-align:right"><?=$data->item_price;?></td>
                              <td align="right" style="vertical-align:middle; text-align:right"><?=$data->total_amt;?></td>
                              <td align="right" style="vertical-align:middle; text-align:center"><?=($data->cc_type=='+')? "Stock In" : "Stock Out" ?></td>
                            </tr><?php
                          }
$stock_in=find_a_field(''.$table_details.'','SUM(total_amt)','cc_type="+" and '.$unique.'='.$_GET[$unique]);
$stock_out=find_a_field(''.$table_details.'','SUM(total_amt)','cc_type="-" and '.$unique.'='.$_GET[$unique]);

                          ?>
                        </tbody>
                        <?php if($stock_out>0){ ?>
                        <tr style="font-weight: bold">
                            <td colspan="8" style="font-weight:bold; font-size:11px" align="right">Total Inventory Shortage = </td>
                            <td align="right" ><?=number_format($stock_out,2);?></td>
                            <td align="right" ></td>
                        </tr>
                        <?php } if($stock_in>0){ ?>
                        <tr style="font-weight: bold">
                            <td colspan="8" style="font-weight:bold; font-size:11px" align="right">Total Inventory Surplus = </td>
                            <td align="right" ><?=number_format($stock_in,2);?></td>
                            <td align="right" ></td>
                        </tr><?php } ?>
                    </table>



                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="vertical-align: middle">#</th>
                            <th style="width: 12%; vertical-align: middle; text-align: center">Journal For</th>
                            <th style="width: 12%; vertical-align: middle; text-align: center">Ledger For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 25%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($stock_out>0){ ?>
                        <tr>
                            <td style="text-align: center; vertical-align:middle">1</td>
                            <td rowspan="2" style="text-align: center; vertical-align:middle">Inventory Shortage</td>
                            <td style="text-align: center; vertical-align:middle">COGS Ledger</td>
                            <td style="vertical-align:middle"><?
                                $vendor_ledger=find_a_field('vendor','ledger_id','vendor_id='.$masterDATA->vendor_id.'');
                                $warehouse_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$masterDATA->warehouse_id.'');?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $config_group_class->cogs_sales, 'ledger_id='.$config_group_class->cogs_sales.''); ?>
                                </select>
                            </td>
                            <td rowspan="2" style="text-align: center; vertical-align:middle"><textarea name="narration_1" id="narration_1"  class="form-control col-md-7 col-xs-12" style="width:100%; height:102px; font-size: 11px; text-align:center">Inventory Cycle Counting (Shortage), CC No # <?=$$unique?> <?php if(!empty($masterDATA->remarks)) {?>, Remarks # <?=$masterDATA->remarks?><?php }?></textarea></td>
                            <td align="center" style="vertical-align:middle"><input type="text" name="dr_amount_1" readonly value="<?=$stock_out;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center" style="vertical-align:middle"><input type="text" name="cr_amount_1" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;vertical-align:middle">2</td>
                            <td style="text-align: center;vertical-align:middle">Inventory Ledger</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_2">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $warehouse_ledger, 'ledger_group_id in ("1007")'); ?>
                                </select>
                            </td>
                            <td style="text-align: right"><input type="text" name="dr_amount_2" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_2" readonly value="<?=$stock_out;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <?php } ?>


                        <?php if($stock_in>0){ ?>
                        <tr>
                            <td style="text-align: center; vertical-align:middle">3</td>
                            <td rowspan="2" style="text-align: center; vertical-align:middle">Inventory Surplus</td>
                            <td style="text-align: center; vertical-align:middle">Inventory Ledger</td>
                            <td style="vertical-align:middle"><?
                                $vendor_ledger=find_a_field('vendor','ledger_id','vendor_id='.$masterDATA->vendor_id.'');
                                $warehouse_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$masterDATA->warehouse_id.'');?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_3">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $warehouse_ledger, 'ledger_group_id in ("1007")'); ?>
                                </select>
                            </td>
                            <td rowspan="2" style="text-align: center; vertical-align:middle"><textarea name="narration_3" id="narration_3"  class="form-control col-md-7 col-xs-12" style="width:100%; height:102px; font-size: 11px; text-align:center">Inventory Cycle Counting (Surplus), CC No # <?=$$unique?> <?php if(!empty($masterDATA->remarks)) {?>, Remarks # <?=$masterDATA->remarks?><?php }?></textarea></td>
                            <td align="center" style="vertical-align:middle"><input type="text" name="dr_amount_3" readonly value="<?=$stock_in;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center" style="vertical-align:middle"><input type="text" name="cr_amount_3" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align:middle">4</td>
                            <td style="text-align: center;vertical-align:middle">COGS Ledger</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_4">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $config_group_class->cogs_sales, 'ledger_id='.$config_group_class->cogs_sales.''); ?>
                                </select>
                            </td>
                            <td style="text-align: right"><input type="text" name="dr_amount_4" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_4" readonly value="<?=$stock_in;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>



                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='CHECKED'):  ?>
                        <p>
                            <button style="float: left; margin-left:1%; font-size:12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="remarks_returned" style="width: 200px; font-size: 11px"   name="remarks_returned" placeholder="Please drop a note for the return" class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; margin-right:1%; font-size:12px" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Confirm the Cycle Counting </button>
                        </p>
                    <?  else : echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Inventory Cycle Counting has been '.$GET_status.' !!</i></h6>';endif;?>
                </form>
            </div>
        </div>
    </div>

<?php endif;  ?>

<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px;"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px;"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select class="form-control" style="width:200px; font-size: 11px" tabindex="-1" required="required"  name="warehouse_id" id="warehouse_id">
                    <option selected></option>
                    <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_POST[warehouse_id]);?>
                </select></td>
                <td style="width:10px; text-align:center"> -</td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Inventory Cycle Counting</button></td>
            </tr></table>
</form>
<?=$crud->report_templates_with_status($sql,$title);?>
<?php endif;  ?>
<?=$html->footer_content();?>
