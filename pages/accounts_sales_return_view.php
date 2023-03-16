<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='do_no';
$unique_field='name';
$table="sale_return_master";
$table_deatils="sale_return_details";
$journal_item="journal_item";
$journal_accounts="journal";
$page='accounts_sales_return_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $enat=date('Y-m-d h:s:i');
        $cd =$_POST[c_date];
        $c_date=date('Y-m-d' , strtotime($cd));
        $invoice=$_POST[invoice];
        $date=date('d-m-y' , strtotime($transaction_date));
        $j=0;
        for($i=0;$i<strlen($date);$i++)
        {
            if(is_numeric($date[$i]))
            { $time[$j]=$time[$j].$date[$i];
            } else {
                $j++; } }
        $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
        if($_POST[dr_amount_1]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_1], $_POST[dr_amount_2], $_POST[cr_amount_2], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
        if($_POST[dr_amount_3]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_3], $_POST[narration_1], $_POST[dr_amount_3], $_POST[cr_amount_3], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_4], $_POST[narration_1], $_POST[dr_amount_4], $_POST[cr_amount_4], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
       if($_POST[dr_amount_5]>0) {
           add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_5], $_POST[narration_5], $_POST[dr_amount_5], $_POST[cr_amount_5], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
           add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_6], $_POST[narration_6], $_POST[dr_amount_6], $_POST[cr_amount_6], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
       }

        $up_master="UPDATE ".$table." SET status='COMPLETED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='COMPLETED' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }


    //for modify PS information ...........................
    if(isset($_POST['checkeds']))
    {
        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $enat=date('Y-m-d h:s:i');
        $cd =$_POST[c_date];
        $c_date=date('Y-m-d' , strtotime($cd));
        $invoice=$_POST[invoice];
        $date=date('d-m-y' , strtotime($transaction_date));
        $j=0;
        for($i=0;$i<strlen($date);$i++)
        {
            if(is_numeric($date[$i]))
            { $time[$j]=$time[$j].$date[$i];
            } else {
                $j++; } }
        $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
        if($_POST[dr_amount_1]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_1], $_POST[dr_amount_2], $_POST[cr_amount_2], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
        if($_POST[dr_amount_3]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_3], $_POST[narration_1], $_POST[dr_amount_3], $_POST[cr_amount_3], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_4], $_POST[narration_1], $_POST[dr_amount_4], $_POST[cr_amount_4], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
        if($_POST[dr_amount_5]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_5], $_POST[narration_5], $_POST[dr_amount_5], $_POST[cr_amount_5], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_6], $_POST[narration_6], $_POST[dr_amount_6], $_POST[cr_amount_6], SalesReturn, $$unique, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }


        unset($_POST);
        echo "<script>window.close(); </script>";
    }



//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_deatils);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);
        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($_SESSION['ps_id']);
        unset($_SESSION['pi_id']);
        unset($_SESSION['initiate_daily_production']);
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$dealer_info=find_all_field("dealer_info","","dealer_code=".$dealer_code."");
$config_group_class=find_all_field("config_group_class","","1");
$srm=find_all_field('sale_return_master','','do_no='.$_GET[do_no].'')
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>



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
                            <th>Code</th>
                            <th>Finish Goods</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center">Sales Qty</th>
                            <th style="text-align:center">Free Qty</th>
                            <th style="text-align:center">Discount</th>
                            <th style="text-align:center">COGS</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Total Qty</th>
                            <th style="text-align:center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $results="Select srd.*,i.* from sale_return_details srd, item_info i  where
 srd.item_id=i.item_id and
 srd.".$unique."=".$$unique." order by srd.id";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td align="center" style=" text-align:center"><?=$row[total_unit];?></td>
                                <td align="center" style=" text-align:center"><?=$row[free_qty];?></td>
                                <td align="center" style=" text-align:right"><?=$row[discount];?></td>
                                <td align="center" style=" text-align:right"><?=$cogs_rate=find_a_field('lc_lc_received_batch_split','rate','item_id="'.$row[item_id].'" and batch="'.$row[batch].'"'); ?></td>
                                <td align="center" style=" text-align:right"><?=$row[unit_price]; ?></td>
                                <td align="center" style=" text-align:center"><?=$row[total_qty]; ?></td>
                                <td align="center" style="text-align:right"><?=number_format($row[total_amt],2);?></td>

                            </tr>
                            <?php
                            //$item_info=find_all_field('item_info','','item_id='.$row[item_id].'');
                            $ttotal_unit=$ttotal_unit+$row[total_unit];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $ttotal_amt=$ttotal_amt+$row[total_amt];
                            $sales_qty_cogs=$cogs_rate*$row[total_qty];
                            $free_qty_cogs=$cogs_rate*$row[free_qty];
                            $total_sales_qty_cogs=$total_sales_qty_cogs+$sales_qty_cogs;
                            $total_free_qty_cogs=$total_free_qty_cogs+$free_qty_cogs;
                        }


                        ?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Sales Return</td>
                            <td style="text-align:center"><?=$ttotal_unit;?></td>
                            <td style="text-align:center"><?=$tfree_qty;?></td>
                            <td style="text-align:right"><?=number_format($tdiscount,2);?></td>
                            <td align="center" ></td>
                            <td align="center" ></td>
                            <td align="center" ><?=$ttotal_qty;?></td>
                            <td align="right" ><?=number_format($ttotal_amt,2);?></td>
                        </tr>
                        <?php if($srm->cashdiscount>0){ ?>
                            <tr style="font-weight: bold">
                                <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Cash Discount</td>
                                <td align="right" colspan="7"><?=number_format($srm->cashdiscount,2);?></td>
                            </tr>
                        <?php } ?>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Sales Return</td>
                            <td align="right" colspan="7"><?=number_format($ttotal_amt+$srm->cashdiscount,2);?></td>
                        </tr>
                    </table>



                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="width: 12%">For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 25%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center">1</td>
                            <td style="text-align: center">Sales Return Ledger</td>
                            <td><?$sales_return_ledger=$config_group_class->sales_return;?>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_1"  name="ledger_1">
                                    <option></option>
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_return_ledger, 'ledger_id='.$sales_return_ledger); ?>
                                </select>
                            </td>

                            <td style="text-align: center" rowspan="4"><textarea  name="narration_1" id="narration_1" class="form-control col-md-7 col-xs-12" style="width:100%; height:202px; font-size: 11px; text-align:center">Total Return Amount = <?=$ttotal_amt+$tdiscount;?>, Cash Discount = <?=$tdiscount;?>, SR NO#<?=$sr_no;?>, Dealer#<?=$dealer_info->dealer_name_e;?>, ID#<?=$_GET[$unique];?>, Remarks#<?=$srm->remarks;?></textarea></td>
                            <td align="center"><input type="text" name="dr_amount_1" readonly value="<?=$ttotal_amt;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center"><input type="text" name="cr_amount_1" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center">2</td>
                            <td style="text-align: center">Dealer</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_2" id="ledger_2">
                                 <option  value="<?=$dealer_info->account_code;?>"><?=$dealer_info->account_code; ?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$dealer_info->account_code.''); ?></option>
                                </select></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_2" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_2" readonly value="<?=$ttotal_amt;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center">3</td>
                            <td style="text-align: center">Inventory Ledger</td>
                            <td><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_3" id="ledger_3">
                                    <?$inventory_ledger=find_a_field('warehouse','ledger_id_FG','warehouse_id='.$depot_id.'');?>
                                   <option  value="<?=$inventory_ledger;?>"><?=$inventory_ledger;?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$inventory_ledger.''); ?></option>
                                   </select></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_3" readonly value="<?=$total_sales_qty_cogs-$total_free_qty_cogs;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_3" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center">4</td>
                            <td style="text-align: center">COGS Sales</td>
                            <td><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_4" id="ledger_4">
                                    <?$COGS_sales=$config_group_class->cogs_sales;?>
                                        <option  value="<?=$COGS_sales;?>"><?=$COGS_sales; ?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$COGS_sales.''); ?></option>
                                   </select></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_4"  readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_4" readonly value="<?=$total_sales_qty_cogs-$total_free_qty_cogs;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
<?php if($total_free_qty_cogs>0):?>
                        <tr>
                            <td style="text-align: center">5</td>
                            <td style="text-align: center">Inventory Ledger</td>
                            <td><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_5" id="ledger_5">
                                    <option  value="<?=$inventory_ledger;?>"><?=$inventory_ledger; ?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$inventory_ledger.''); ?></option>
                                    </select></td>
                            <td style="text-align: center"><input type="text" name="narration_5" id="narration_5" value="Sales Return, SR NO#<?=$sr_no;?>, Dealer#<?=$dealer_info->dealer_name_e;?>, ID#<?=$_GET[$unique];?>, Remarks#<?=$srm->remarks;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_5" readonly value="<?=$total_free_qty_cogs;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_5" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center">6</td>
                            <td style="text-align: center">Free Ledger</td>
                            <td><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_6" id="ledger_6">
                                    <?$free_own_product=$config_group_class->free_own_product;?>
                                    <option  value="<?=$free_own_product; ?>"><?=$free_own_product; ?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$free_own_product.''); ?></option>
                                </select></td>
                            <td style="text-align: center"><input type="text" name="narration_6" id="narration_6" value="Sales Return, SR NO#<?=$sr_no;?>, Dealer#<?=$dealer_info->dealer_name_e;?>, ID#<?=$_GET[$unique];?>, Remarks#<?=$srm->remarks;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text"  name="dr_amount_6" readonly value="0.00" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text"  name="cr_amount_6" readonly value="<?=$total_free_qty_cogs;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>



                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='CHECKED'){  ?>
                        <p>
                            <button style="float: left; margin-left:1%;  font-size: 11px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Completed the Sales Return </button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This SalesReturn has been checked !!</i></h6>';

                    if($_SESSION[userid]=='10019') { ?>
                        <button style="float: right;" type="submit" name="checkeds" id="checkeds" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Completed the Sales Return </button>

                   <?php  }  }?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[f_date]) echo $_POST[f_date]; else echo date('Y-m-01');?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if($_POST[t_date]) echo $_POST[t_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Sales Return</button></td>
            </tr></table>
            </form>
<?php
if(isset($_POST[viewreport])){
$res="Select p.do_no,p.sr_no as SR_NO,DATE_FORMAT(p.do_date, '%d %M, %Y') as SR_date,w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as dealer_name,p.remarks,concat(u.fname,' - ',p.entry_at) as entry_by,p.status

from
".$table." p,
warehouse w,
users u,
dealer_info d

 where
  p.entry_by=u.user_id and
 w.warehouse_id=p.depot_id and
 d.dealer_code=p.dealer_code and
 p.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' order by p.".$unique." DESC ";
} else {
$res="Select p.do_no,p.sr_no as SR_NO,DATE_FORMAT(p.do_date, '%d %M, %Y') as SR_date,w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as dealer_name,p.remarks,concat(u.fname,' - ',p.entry_at) as entry_by,p.status
from
".$table." p,
warehouse w,
users u,
dealer_info d

 where
  p.entry_by=u.user_id and
 w.warehouse_id=p.depot_id and
 d.dealer_code=p.dealer_code and
 p.status in ('CHECKED') order by p.".$unique." DESC ";
}
echo $crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
