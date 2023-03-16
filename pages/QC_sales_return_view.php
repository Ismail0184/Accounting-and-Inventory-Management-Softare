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
$lc_lc_received_batch_split = "lc_lc_received_batch_split";
$page='QC_sales_return_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$condition="create_date='".date('Y-m-d')."'";


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
        
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:s:i');
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];

        $results="Select srd.*,i.* from sale_return_details srd, item_info i  where
 srd.item_id=i.item_id and
 srd.".$unique."=".$$unique." group by srd.id order by srd.id";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)){
            $new_batch = automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
            $_POST['ji_date'] = $ji_date;
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[depot_id];
            $_POST['item_in'] = $row[total_qty];
            $_POST['item_price'] = $row[cogs_rate];
            $_POST['batch'] = $new_batch;
            $_POST['lot_number'] = $row[batch];
            $_POST['expiry_date'] = $row[expiry_date];
            $_POST['total_amt'] = $row[total_qty]*$row[cogs_rate];
            $_POST['tr_from'] = 'SalesReturn';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();


            $_POST['po_no'] = $_GET[$unique];
            $_POST['create_date'] = date('Y-m-d');
            $_POST['lc_id'] = $_GET[$unique];
            $_POST['warehouse_id'] = $row[depot_id];
            $_POST['batch_no'] = find_a_field('lc_lc_received_batch_split','batch_no','batch='.$row[batch]);
            $_POST['item_id'] = $row[item_id];
            $_POST['qty'] = $row[total_qty];
            $_POST['rate'] = $row[cogs_rate];
            $_POST['batch'] = $new_batch;
            $_POST['mfg'] = $row[expiry_date];
            $_POST['status'] = 'PROCESSING';
            $_POST['source'] = 'SR';
            $_POST['line_id'] = $row[id];

            $crud      =new crud($lc_lc_received_batch_split);
            $crud->insert();
            


            
        }
        $up_master="UPDATE ".$table." SET status='CHECKED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='CHECKED' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
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

$cashdiscount=find_a_field('sale_return_master','cashdiscount','do_no='.$_GET[do_no].'');

if(isset($_POST[viewreport])){
$resultss="Select p.do_no,p.sr_no,p.do_date as 'Date',w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as 'Dealer Name',p.remarks,concat(u.fname, '<br> at: ' ,p.entry_at) as entry_by,(SELECT COUNT(item_id) from ".$table_deatils." where ".$unique."=p.".$unique.") as No_of_Items,p.status as status
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
$resultss="Select p.do_no,p.sr_no,p.do_date as 'Date',w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as 'Dealer Name',p.remarks,concat(u.fname, '<br> at: ' ,p.entry_at) as entry_by,(SELECT COUNT(item_id) from ".$table_deatils." where ".$unique."=p.".$unique.") as No_of_Items,p.status as status
from
".$table." p,
warehouse w,
users u,
dealer_info d
where
p.entry_by=u.user_id and
w.warehouse_id=p.depot_id and
d.dealer_code=p.dealer_code and
p.status in ('UNCHECKED')
order by p.".$unique." DESC ";
}


$results="Select srd.*,i.* from sale_return_details srd, item_info i  where
srd.item_id=i.item_id and
srd.".$unique."=".$$unique." order by srd.id";

?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 230,top = -1");}
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
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Total Qty</th>
                            <th style="text-align:center">Amount</th>
                            <th style="text-align:center; width:20%">Batch Details</th>
                            </tr>
                        </thead>
                        <tbody>


                        <?php
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                        $batch_get_data=find_all_field('lc_lc_received_batch_split','','batch='.$row[batch]);
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$i=$i+1?></td>
                                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td align="center" style=" text-align:center; vertical-align:middle"><?=$row[total_unit];?></td>
                                <td align="center" style=" text-align:center; vertical-align:middle"><?=$row[free_qty];?></td>
                                <td align="center" style=" text-align:right; vertical-align:middle"><?=$row[discount];?></td>
                                <td align="center" style=" text-align:right; vertical-align:middle"><?=$row[unit_price]; ?></td>
                                <td align="center" style=" text-align:center; vertical-align:middle"><?=$row[total_qty]; ?></td>
                                <td align="center" style="text-align:right; vertical-align:middle"><?=number_format($row[total_amt],2);?></td>
                                <td align="center" style=" text-align:center; vertical-align:middle"><strong>Batch :</strong> <?=$row[batch]; ?>(<?=$batch_get_data->batch_no?>)<br><strong>Batch Status:</strong> <?=$batch_get_data->status?><br><strong>Rate:</strong>  <?=$row[cogs_rate]; ?><br><strong>Exp. Date:</strong>  <?=$row[expiry_date]; ?></td>

                            </tr>
                            <?php  $ttotal_unit=$ttotal_unit+$row[total_unit];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $ttotal_amt=$ttotal_amt+$row[total_amt];  } ?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Sales Return</td>
                            <td style="text-align:center"><?=$ttotal_unit;?></td>
                            <td style="text-align:center"><?=$tfree_qty;?></td>
                            <td style="text-align:right"><?=number_format($tdiscount,2);?></td>
                            <td align="center" ></td>
                            <td align="center" ><?=$ttotal_qty;?></td>
                            <td align="right" ><?=number_format($ttotal_amt,2);?></td>
                            <td></td>
                        </tr>
                        <?php if($cashdiscount>0){ ?>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Cash Discount</td>
                            <td align="right" colspan="6"><?=number_format($cashdiscount,2);?></td>
                            <td></td>
                        </tr>
                        <?php } ?>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Sales Return</td>
                            <td align="right" colspan="6"><?=number_format($ttotal_amt+$cashdiscount,2);?></td>
                            <td></td>
                        </tr>
                    </table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){  ?>
                        <p>
                            <button style="float: left; font-size: 12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; font-size: 12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward to Accounts</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This sales return has been checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])): ?>
<form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
    <table align="center" style="width: 50%;">
        <tr>
            <td><input type="date"  style="width:150px; font-size: 11px;" max="<?=date('Y-m-d');?>"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" /></td>
            <td style="width:10px; text-align:center"></td>
            <td><input type="date"  style="width:150px;font-size: 11px;"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
            <td style="width:10px; text-align:center"></td>
            <td style="padding:10px"><button type="submit" style="font-size: 12px;" name="viewreport"  class="btn btn-primary">View Report</button></td>
        </tr>
    </table>
    <?=$crud->report_templates_with_status($resultss);?>
</form>     
<?php endif; ?>
<?=$html->footer_content();?>
