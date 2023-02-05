<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$entry_at=date('Y-m-d H:s:i');
$unique='dealer_code';
$unique_field='name';
$table="production_floor_receive_master";
$table_deatils="production_floor_receive_detail";

$production_table_issue_master="production_floor_issue_master";
$production_table_issue_detail="production_floor_issue_detail";
$journal_item="journal_item";
$journal_accounts="journal";
$page='accounts_collection_entry.php';

$dealer=find_all_field("dealer_info","","dealer_code=".$_GET[dealer_code]."");

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
        //echo "<script>self.opener.location = 'QC_sales_return_view.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }



    $res=mysqli_query($conn, "select sl.sub_ledger_id,sl.sub_ledger,SUM(r.dr_amt) as collection
from sub_ledger sl, receipt r 
where 
r.ledger_id=sl.sub_ledger_id and 
r.receiptdate between '".$_GET[from_data]."' and '".$_GET[to_date]."' and 
r.dealer_code=".$_GET[dealer_code]." and
sl.ledger_id in ('1002000900000000') group by r.ledger_id");
    while($ledgerrow=mysqli_fetch_object($res)) {
        $id = $ledgerrow->sub_ledger_id;
        $bank_ledger=$_POST['bank_ledger_'.$id];
        $amount=$_POST['amount_'.$id];
        if(isset($_POST['transfer_'.$id])){
               $update=mysqli_query($conn, "INSERT INTO rice_amount_transferred (dealer_code,dealer_ledger,bank_ledger,transferred_date,amount,entry_by,entry_at) VALUES ('$_GET[dealer_code]','$dealer->account_code','$bank_ledger','$entry_at','$amount','$_SESSION[userid]','$entry_at') ");
        }}


}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));

$se = mysqli_query($conn, "select  receipt_no,dealer_code from receipt where ledger_id=$dealer->account_code and receiptdate between '" . $_GET[from_data] . "' and '" . $_GET[to_date] . "' order by receipt_no");
while ($voucher = mysqli_fetch_object($se)) {
    if ($voucher->dealer_code == 0) {
        mysqli_query($conn, "Update receipt set dealer_code='" . $_GET[dealer_code] . "' where receipt_no=" . $voucher->receipt_no . "");
    }}

?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?from_data=<?=$from_date;?>&to_date=<?=$to_date;?>&<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 280,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?php require_once 'support_html.php';?>
                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="width: 2%">#</th>
                            <th style="">Code</th>
                            <th style="">Dealer Name</th>
                            <th style="">Territory</th>
                            <th>Invoice Amount</th>
                            <th>Others</th>
                            <th style="">Rice Amount</th>
                            <th style="">Collection</th>
                            <th style="">Transferred</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $resultss="Select m.*,SUM(sd.total_amt) as invoice_amount,d.dealer_name_e,d.dealer_custom_code,d.account_code,a.AREA_NAME as territory,
(select SUM(total_amt) from sale_do_details where item_id in ('1023','200010003') and dealer_code=".$_GET[dealer_code]." and do_date between '".$_GET[from_data]."' and '".$_GET[to_date]."' ) as rice_amount,
(select SUM(cr_amt) from receipt where ledger_id=d.account_code and receiptdate between '".$_GET[from_data]."' and '".$_GET[to_date]."') as collection,
(select SUM(amount) from rice_amount_transferred where dealer_code=d.dealer_code and transferred_date between '".$_GET[from_data]."' and '".$_GET[to_date]."') as transferred
from 
sale_do_master m,
sale_do_details sd,
dealer_info d,
area a

 where
  m.do_no=sd.do_no and 
  m.dealer_code=d.dealer_code and  
  m.do_date between '".$_GET[from_data]."' and '".$_GET[to_date]."' and 
  a.AREA_CODE=d.area_code and m.dealer_code=".$_GET[dealer_code]."
  group by m.dealer_code
  order by m.dealer_code";
                            $pquery=mysqli_query($conn, $resultss);
                            while ($rows=mysqli_fetch_array($pquery)){
                            $i=$i+1;
                            ?>
                            <tr>
                                <th style="text-align:center;vertical-align: middle"><?php echo $i; ?></th>
                                <td style="vertical-align: middle"><?=$rows[dealer_custom_code];?></td>
                                <td style="vertical-align: middle"><?=$rows[dealer_name_e];?></td>
                                <td style="vertical-align: middle"><?=$rows[territory];?></td>
                                <td style="text-align: right;vertical-align: middle"><?=number_format($rows[invoice_amount],2);?></td>
                                <td style="text-align: right;vertical-align: middle"><?=number_format($other=$rows[invoice_amount]-$rows[rice_amount],2);?></td>
                                <td style="text-align: right;vertical-align: middle"><?=number_format($rows[rice_amount],2);?></td>
                                <td style="text-align:right;vertical-align: middle"><?=number_format($rows[collection],2);?></td>
                                <td style="text-align:right;vertical-align: middle"><?=number_format($rows[transferred],2);?></td>
                                </tr>
                            <?php
                            $total_invoice_amount=$total_invoice_amount+$rows[invoice_amount];
                            $total_other_amount=$total_other_amount+$other;
                            $total_rice_amount=$total_rice_amount+$rows[rice_amount];
                            $total_collection_amount=$total_collection_amount+$rows[collection];
                            $total_transferred_amount=$total_transferred_amount+$rows[transferred];
                        } ?></tbody>
                        <tr style="font-weight: bold; font-size: 12px">
                            <td colspan="4" align="right">Total </td>
                            <td style="text-align: right"><?=number_format($total_invoice_amount,2);?></td>
                            <td style="text-align: right"><?=number_format($total_other_amount,2);?></td>
                            <td style="text-align: right"><?=number_format($total_rice_amount,2);?></td>
                            <td style="text-align: right"><?=number_format($total_collection_amount,2);?></td>
                            <td style="text-align: right"><?=number_format($total_transferred_amount,2);?></td>
                        </tr>
                    </table>







                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="text-align: center">Bank Details</th>
                            <th style="text-align: center">Collection</th>
                            <th style="text-align: center">Balance</th>
                            <th style="text-align: center">Transfer to Rice Account</th>
                            <th style="text-align: center">Add</th>
                        </tr>
                        </thead>


                        <tbody>

                            <?php

                            $res=mysqli_query($conn, "select sl.sub_ledger_id,sl.sub_ledger,SUM(r.dr_amt) as collection
from sub_ledger sl, receipt r 
where 
r.ledger_id=sl.sub_ledger_id and 
r.receiptdate between '".$_GET[from_data]."' and '".$_GET[to_date]."' and 
r.dealer_code=".$_GET[dealer_code]." and
sl.ledger_id in ('1002000900000000') group by r.ledger_id");
                            while($ledgerrow=mysqli_fetch_object($res)){
                                $id=$ledgerrow->sub_ledger_id;


                                ?>
                        <tr>
                            <td style="vertical-align: middle">
                                <input type="hidden" name="bank_ledger_<?=$id;?>" value="<?=$ledgerrow->sub_ledger_id;?>">
                                <?=$ledgerrow->sub_ledger_id;?> : <?=$ledgerrow->sub_ledger;?></td>
                            <td style="vertical-align: middle; text-align: right"><?=number_format($ledgerrow->collection,2);?></td>
                            <td style="vertical-align: middle"><?=number_format($ledgerrow->ricecollection,2);?></td>
                            <td style="vertical-align: middle"><input type="text" name="amount_<?=$id;?>" value="" id="amount_<?=$id;?>"> </td>
                            <td align="center" style="vertical-align: middle"><button style="font-size: 12px" type="submit" name="transfer_<?=$id;?>" id="transfer_<?=$id;?>" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Add</button></td>

                        </tr>

                            <?php }  ?>



                        <tbody>

                        </tbody>
                    </table>




                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>


    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 30px" min="2019-12-01" value="<?=$_POST[f_date];?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height:30px"  value="<?=$_POST[t_date]?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Available Dealer</button></td>
            </tr></table>



        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 2%">#</th>
                            <th style="">Code</th>
                            <th style="">Dealer Name</th>
                            <th style="">Territory</th>
                            <th>Invoice Amount</th>
                            <th>Others</th>
                            <th style="">Rice Amount</th>
                            <th style="">Collection</th>
                            <th style="">Transferred</th>
                            <!--th style="text-align: center">Transfer Entry</th>
                            <th style="text-align: center">#</th-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($_POST[viewreport])){
                            $resultss="Select m.*,SUM(sd.total_amt) as invoice_amount,d.dealer_name_e,d.dealer_custom_code,d.account_code,a.AREA_NAME as territory,
(select SUM(total_amt) from sale_do_details where item_id in ('1023','200010003') and dealer_code=m.dealer_code and do_date between '$from_date' and '$to_date' ) as rice_amount,
(select SUM(cr_amt) from receipt where ledger_id=d.account_code and receiptdate between '$from_date' and '$to_date') as collection,
(select SUM(amount) from rice_amount_transferred where dealer_code=d.dealer_code and transferred_date between '$from_date' and '$to_date') as transferred
from 
sale_do_master m,
sale_do_details sd,
dealer_info d,
area a

 where
  m.do_no=sd.do_no and 
  m.dealer_code=d.dealer_code and  
  m.do_date between '$from_date' and '$to_date' and 
  a.AREA_CODE=d.area_code  
  group by m.dealer_code
  order by m.dealer_code";
                            $pquery=mysqli_query($conn, $resultss);
                        }
                        while ($rows=mysqli_fetch_array($pquery)){
                            $i=$i+1;
                            ?>
                            <tr style="font-size:11px; cursor: pointer" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)">
                                <th style="text-align:center;vertical-align: middle"><?php echo $i; ?></th>
                                <td style="vertical-align: middle"><?=$rows[dealer_custom_code];?></td>
                                <td style="vertical-align: middle"><?=$rows[dealer_name_e];?></td>
                                <td style="vertical-align: middle"><?=$rows[territory];?></td>
                                <td style="text-align: right;vertical-align: middle"><?=number_format($rows[invoice_amount],2);?></td>
                                <td style="text-align: right;vertical-align: middle"><?=number_format($other=$rows[invoice_amount]-$rows[rice_amount],2);?></td>
                                <td style="text-align: right;vertical-align: middle"><?=number_format($rows[rice_amount],2);?></td>
                                <td style="text-align:right;vertical-align: middle"><?=number_format($rows[collection],2);?></td>
                                <td style="text-align:right;vertical-align: middle"><?=number_format($rows[transferred],2);?></td>
                                <!---td style="text-align:center; vertical-align: middle"><input type="text" style="width: 98%" name="collection_<?=$dealer_code?>" value=""> </td-->
                                <!--td align="center"><button type="submit" style="font-size: 11px;" name="add_collection"  class="btn btn-primary">Add</button></td--->
                            </tr>
                        <?php
                            $total_invoice_amount=$total_invoice_amount+$rows[invoice_amount];
                            $total_other_amount=$total_other_amount+$other;
                            $total_rice_amount=$total_rice_amount+$rows[rice_amount];
                            $total_collection_amount=$total_collection_amount+$rows[collection];
                            $total_transferred_amount=$total_transferred_amount+$rows[transferred];
                        } ?></tbody>
                    <tr style="font-weight: bold; font-size: 12px">
                        <td colspan="4" align="right">Total </td>
                        <td style="text-align: right"><?=number_format($total_invoice_amount,2);?></td>
                        <td style="text-align: right"><?=number_format($total_other_amount,2);?></td>
                        <td style="text-align: right"><?=number_format($total_rice_amount,2);?></td>
                        <td style="text-align: right"><?=number_format($total_collection_amount,2);?></td>
                        <td style="text-align: right"><?=number_format($total_transferred_amount,2);?></td>

                    </tr>
                    </table>

                </div></div></div></form>
<?php } ?>

<?php require_once 'footer_content.php' ?>