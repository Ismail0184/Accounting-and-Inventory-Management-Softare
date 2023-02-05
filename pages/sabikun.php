<?php
require_once 'support_file.php';
$page = 'sales_special_invoice.php';
$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='journal_item';
$unique_detail='id';

if(isset($_POST['add_inventory'])){
    $inventoryin=mysqli_query($conn,"INSERT INTO `journal_item`
		(item_id,item_in,batch,expiry_date) VALUES ($_POST[item_id_in],$_POST[item_in],$_POST[batch_in],'".$_POST[expiry_date_in]."')");
}

if(isset($_POST[inventory_ex])){
$item_id=$_POST[item_id];

$_SESSION['bqty']=$_POST[input_qty];
$fifocheck=mysqli_query($conn,"select distinct batch, SUM(item_in-item_ex) as qty, item_price as rate,expiry_date  from journal_item where batch>0 and item_id='$item_id' group by item_id, batch order by expiry_date,batch");
while ($fifocheckrow=mysqli_fetch_array($fifocheck)){
    if ( $_SESSION['bqty']<=$fifocheckrow['qty'] && $_SESSION['bqty']>0){
        $productiondetails =mysqli_query($conn,"INSERT INTO journal_item
		(item_id,item_ex,batch,expiry_date) VALUES 
($item_id,$_SESSION[bqty],$fifocheckrow[batch],$fifocheckrow[expiry_date])");
        $_SESSION['bqty']= 0;
    } else if ($_SESSION['bqty']>=$fifocheckrow['qty'] && $_SESSION['bqty']>0){
        $productiondetails =mysqli_query($conn,"INSERT INTO journal_item
		(item_id, item_ex,batch,expiry_date) VALUES 
('$item_id',$fifocheckrow[qty],$fifocheckrow[batch],$fifocheckrow[expiry_date])");
        $_SESSION['bqty']= intval($_SESSION['bqty'])-$fifocheckrow['qty'];}}}
?>

<form method="post" action="">
    <table>
        <tr><td>
    <input type="text" name="item_id_in" id="item_id_in" placeholder="item id">
    <input type="text" name="item_in" id="item_in" placeholder="purchase qty">
    <input type="text" name="batch_in" id="batch_in" placeholder="batch">
    <input type="date" name="expiry_date_in" id="expiry_date_in" value="<?=date('Y-m-d')?>">
    <input type="submit" name="add_inventory" id="add_inventory" value="add Inventory">
            </td></tr>
        <tr><td style="height: 10px"></td></tr>
        <tr>
        <td>
                <input type="text" name="item_id" id="item_id" placeholder="item_id">
                <input type="text" name="input_qty" id="input_qty" placeholder="sales qty">
                <input type="submit" name="inventory_ex" id="inventory_ex" value="Inventory exit">
            </td>
        </tr>
    </table>
<br><br>
    <table style="border: 1px solid #CCC; font-size: 11px; collapse: collapse; ">
        <tr>
            <th style="border: 1px solid #CCC">Item ID</th>
            <th style="border: 1px solid #CCC">Custom Code</th>
            <th style="border: 1px solid #CCC">Item Name</th>
            <th style="border: 1px solid #CCC">Batch</th>
            <th style="border: 1px solid #CCC">Expiry Date</th>
            <th style="border: 1px solid #CCC">In</th>
            <th style="border: 1px solid #CCC">Out</th>
            <th style="border: 1px solid #CCC">Balance</th>
        </tr>
        <?php
        $res=mysqli_query($conn, "select i.item_id,i.item_name,SUM(j.item_in) as item_in,SUM(j.item_ex) as item_ex,j.batch,j.expiry_date,i.finish_goods_code from item_info i,journal_item j where i.item_id=j.item_id group by j.batch,j.item_id order by i.item_id");
        while($data=mysqli_fetch_object($res)){ ?>
            <tr>
            <td style="border: 1px solid #CCC"><?=$data->item_id;?></td>
            <td style="border: 1px solid #CCC"><?=$data->finish_goods_code;?></td>
            <td style="border: 1px solid #CCC"><?=$data->item_name;?></td>
            <td style="border: 1px solid #CCC"><?=$data->batch;?></td>
            <td style="border: 1px solid #CCC"><?=$data->expiry_date;?></td>
            <td style="border: 1px solid #CCC"><?=$data->item_in;?></td>
            <td style="border: 1px solid #CCC"><?php if ($data->item_ex>0) echo $data->item_ex; else "-";?></td>
            <td style="border: 1px solid #CCC"><?=$data->item_in-$data->item_ex;?></td></tr>
       <?php }
        ?>
    </table>
</form>
