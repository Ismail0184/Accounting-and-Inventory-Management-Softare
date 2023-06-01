<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();

$unique='uid';
$table="warehouse_goods_transfer_to_other_master";
$table_details="warehouse_goods_transfer_to_other_details";
$journal_item = 'journal_item';
$page='warehouse_transfer_other_view.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$masterDATA=find_all_field(''.$table.'','',''.$unique.'='.$_GET['uid'].'');
$DealerMaster=find_all_field('corporate_dealer_info','','dealer_code='.$masterDATA->dealer_code);
$WarehouseMaster=find_all_field('warehouse','','warehouse_id='.$masterDATA->warehouse_id);
$jv=next_journal_voucher_id();
if(prevent_multi_submit()){
    if (isset($_POST['reprocess'])) {
        $up="UPDATE ".$table." SET status='MANUAL' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up);
        $_SESSION['uniqueid'] = $_GET[$unique];
        $type = 1;
        echo "<script>self.opener.location = 'warehouse_goods_transfer_to_other.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    if(isset($_POST['confirm']))
    {
        $total_amount = 0;
        $sql="Select * from warehouse_goods_transfer_to_other_details where ".$unique."=".$_GET[$unique]."";
        $res=mysqli_query($conn, $sql);
        while($data=mysqli_fetch_object($res)){
            $_POST['ji_date'] = $data->ogt_date;
            $_POST['item_id'] = $data->item_id;
            $_POST['warehouse_id'] = $data->warehouse_id;
            $_POST['item_in'] = $data->total_unit;
            $_POST['item_price'] = $data->unit_price;
            $_POST['total_amt'] = $data->total_unit*$data->unit_price;;
            $_POST['tr_from'] = 'GoodsTransferToOther';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $_GET[$unique];
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:i:s');
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $crud      =new crud($journal_item);
            $crud->insert();   // goods transfer
            $total_amount = $total_amount+$data->total_amt;
        }

        $narration = "Goods Transfer to ".$DealerMaster->dealer_name.", OGT No # ".$_GET['uid']."";
        $up_master=mysqli_query($conn,"UPDATE ".$table." SET status='COMPLETED',checked_by='".$_SESSION['userid']."',checked_at='".date('Y-m-d H:i:s')."' where uid=".$_GET['uid']."");
        $debit_ledger = @$DealerMaster->ledger_id;
        $credit_ledger = @$WarehouseMaster->ledger_id_FG;
        if($total_amount > 0 && ($debit_ledger>0) && $credit_ledger>0)  {
            add_to_journal_new($masterDATA->ogt_date, $proj_id, $jv, $masterDATA->ogt_date, $debit_ledger, $narration, $total_amount,0, 'GoodsTransferToOther', $$unique, $$unique, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,'','','');
            add_to_journal_new($masterDATA->ogt_date, $proj_id, $jv, $masterDATA->ogt_date, $credit_ledger, $narration,0, $total_amount, 'GoodsTransferToOther', $$unique, $$unique, 0, 0, $_SESSION['usergroup'], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,'','','');
        }
        unset($_POST);
        echo "<script>self.opener.location = '".$page."'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['deleted']))
    {
            $crud = new crud($table_details);
            $condition =$unique."=".$$unique;
            $crud->delete_all($condition);
            $crud = new crud($table);
            $condition=$unique."=".$$unique;
            $crud->delete($condition);
            unset($_POST);
            unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

if(isset($_POST['viewreport'])){

    $sql="SELECT m.uid,m.custom_id,m.ogt_date as 'date',w.warehouse_name as warehouse,dealer_name as party,m.remarks,m.status as status from 
    ".$table." m, warehouse w,corporate_dealer_info d where
    w.warehouse_id=m.warehouse_id and 
    d.dealer_code=m.dealer_code and 
    m.type='SEND'";
}
?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 280,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>



<?php if(($_GET[$unique]>0)){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th>SL</th>
                            <th>Code</th>
                            <th>Item Description</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align: center">Stock Balance</th>
                            <th style="text-align:center">Transfer Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rs="Select d.*,i.* from ".$table_details." d,item_info i where i.item_id=d.item_id  and d.".$unique."=".$$unique." order by d.id";
                        $pdetails=mysqli_query($conn, $rs);
                        $js = 0;
                        while($data=mysqli_fetch_object($pdetails)){
                            $stock_balance = find_a_field('journal_item','SUM(item_in-item_ex)','item_id="'.$data->item_id.'" and warehouse_id="'.$masterDATA->warehouse_id.'"');
                            $unrec_qty = $stock_balance-$data->total_unit; ?>
                            <tr <?php if($unrec_qty<0){$cow++;?> style="background-color:red; color:white" <?php } ?>>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td><?=$data->item_id;?></td>
                                <td style="text-align:left"><?=$data->item_name;?></td>
                                <td style="text-align:center"><?=$data->unit_name;?></td>
                                <td style="vertical-align:middle; text-align:center" align="center"><input type="text" name="available_stock" style="width:80px; height:20px; font-size:11px" value="<?=$stock_balance?>" readonly class="form-control col-md-7 col-xs-12"></td>
                                <td align="right" style="width:15%; text-align:center"><?=$data->total_unit;?></td>
                            </tr>
                            <?php $amountqty=$amountqty+$data->total_unit;  } ?>
                        <tr style="font-weight: bold"><td colspan="5" style="text-align: right">Total = </td>
                            <td style="text-align: center"><?=number_format($amountqty,2)?></td>
                        </tr>
                        </tbody></table>
                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($cow<1){
                    if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='Manual' || $GET_status=='RETURNED'){
                        if($masterDATA->entry_by==$_SESSION['userid']){ ?>
                    <p>
                        <button style="float: left; font-size: 12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Re-process & Update</button>
                        <button style="float: right;font-size: 12px" type="submit" name="confirm" id="deleted" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Confirm & Finish</button>
                    </p>
                    <? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>Oops!! This STO was created by another user. So you are not able to do anything here!!</i></h6>'; } ?>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Stock Transfer has been checked by QC !!</i></h6>';}?>
                        <?php } else { ?>
                    <h6 style="text-align: center;color: red;  font-weight: bold"><i>Oops! Some of the items have exceeded the stock balance!!</i></h6> <?php }?>

                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
    <table align="center" style="width: 50%;">
        <tr><td>
            <input type="date"  style="width:150px; font-size: 11px;"  value="<?=($_POST['f_date']!='')? $_POST['f_date'] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
            <td style="width:10px; text-align:center"> -</td>
            <td><input type="date"  style="width:150px;font-size: 11px;"  value="<?=($_POST['t_date']!='')? $_POST['t_date'] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
            <td style="width:10px; text-align:center"> -</td>
            <td><select class="form-control" style="width:200px; font-size: 11px;" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                    <option selected></option>
                    <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),($_POST['warehouse_id']>0 ? $_POST['warehouse_id'] : $_SESSION['warehouse']));?>
                </select></td>
            <td style="padding:10px"><button type="submit" style="font-size: 11px;" name="viewreport"  class="btn btn-primary">View Data</button></td>
        </tr></table>
    <?=$crud->report_templates_with_status($sql,$title='STO View');?>
</form>
    <?php } ?>
<?=$html->footer_content();?>