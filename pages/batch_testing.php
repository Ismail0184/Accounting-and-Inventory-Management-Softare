<?php
require_once 'support_file.php';
$title='DO Details';
$now=time();
$unique='do_no';
$table="sale_do_master";
$table_details="sale_do_details";
$table_sale_do_chalan="sale_do_chalan";
$journal_item="journal_item";


$page='uncheck_do_one.php';
$pages='unchecked_do_list.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$master=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique].'');
$dealer_master=find_all_field('dealer_info','','dealer_code='.$master->dealer_code);
if($master->do_section=='Special_invoice'){
    $target_page='sales_special_invoice.php';
} else {
    $target_page='sales_regular_invoice.php';
}


?>


<?php require_once 'header_content.php'; ?>
<style>
    #customers {}
    #customers td {}
    #customers tr:ntd-child(even)
    {background-color: #f0f0f0;}
    #customers tr:hover {background-color: #f5f5f5;}
    td{}
</style>
<?php if(isset($_GET[$unique])){
 require_once 'body_content_without_menu.php'; } else {
 require_once 'body_content.php'; } ?>




    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque; vertical-align: middle">
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Code</th>
                            <th style="vertical-align: middle">Finish Goods</th>
                            <th style="text-align:center;vertical-align: middle">Batch</th>
                            <th style="text-align:center;vertical-align: middle">Expiry Date</th>
                            <th style="text-align:center;vertical-align: middle">Status</th>
                            <th style="width:5%; text-align:center;vertical-align: middle">Available Stock</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $fifocheck=mysqli_query($conn, "select distinct qb.batch, SUM(j.item_in-j.item_ex) as qty, j.item_price as rate,qb.mfg,j.item_id,qb.status  from journal_item j, lc_lc_received_batch_split qb
          where qb.batch=j.batch and j.item_id=qb.item_id and j.item_id='".$_GET[item_id]."'  and  j.warehouse_id='12'
          group by qb.batch order by qb.mfg asc,qb.batch asc");
                        while($data=mysqli_fetch_object($fifocheck)){
                          $available_stock=find_a_field('journal_item','SUM(item_in-item_ex)','warehouse_id='.$master->depot_id.' and item_id='.$data->item_id);
                          $unrec_qty=$available_stock-$data->total_unit;?>
                            <tr <?php if($unrec_qty<0){$cow++;?> style="background-color:red; color:white" <?php } ?>>
                                <td style="width:3%; vertical-align:middle"><?=$i=$i+1; ?></td>
                                <td style="vertical-align:middle"><?=$data->item_id;?></td>
                                <td style="vertical-align:middle;"><?=find_a_field('item_info','item_name','item_id='.$data->item_id);?></td>

                                <td style="vertical-align:middle;"><?=$data->batch;?></td>
                                <td style="vertical-align:middle;"><?=$data->mfg;?></td>
                                <td style="vertical-align:middle;"><?=$data->status;?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$data->qty?></td>
                            </tr>
                            <?php 
                        $total_result=$total_result+$data->qty;
                        } echo $total_result; ?>
                    </table>
                </form>
            </div>
        </div>
    </div>


<?=$html->footer_content();mysqli_close($conn);?>
