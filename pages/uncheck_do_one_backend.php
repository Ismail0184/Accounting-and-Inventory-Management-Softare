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
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

$master=find_all_field(''.$table.'','',''.$unique.'='.$$unique.'');
$dealer_master=find_all_field('dealer_info','','dealer_code='.$master->dealer_code);

if(prevent_multi_submit()){


    if(isset($_POST['checked']))
    {
        $results="Select d.*,i.* from 
 ".$table_details." d, 
 item_info i 
 where
 d.item_id=i.item_id and 
 d.".$unique."=".$$unique." order by d.id";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)) {			
			$_POST['ji_date'] = $row[do_date];
			$_POST['dealer_id']= $row[dealer_code];
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[depot_id];
            $_POST['item_ex'] = $row[total_unit];
            $_POST['item_price'] = $row[unit_price];
            $_POST['total_amt'] = $row[total_amt];
            $_POST['tr_from'] = 'Sales';
            $_POST['do_no'] = $_GET[$unique];
            $_POST['tr_no'] = $row[id];
			$_POST['sr_no'] = find_a_field('sale_do_chalan','chalan_no','do_no='.$_GET[$unique].'');
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert(); }
        
		
		
		
        $type=1;
        unset($_POST);        
        echo "<script>window.close(); </script>";
    }



        $results="Select d.*,i.* from 
 ".$table_details." d, 
 item_info i 
 where
 d.item_id=i.item_id and 
 d.".$unique."=".$$unique." order by d.id";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)) {
            $i = $i + 1;
            $ids = $row[id];
            if(isset($_POST['single_checked_'.$ids])){
            $order_qty=$_POST['order_qty'.$ids]*$row[pack_size];
            $unit_price=$_POST['unit_price'.$ids];
            $total_unit=$_POST['total_unit_'.$ids]*$row[pack_size];
            $total_amt=$_POST['total_amt_'.$ids];
            $rev=mysqli_query($conn, "Update ".$table_details." SET order_qty='".$order_qty."',total_unit='".$total_unit."',total_amt='".$total_amt."',unit_price='".$unit_price."',status='CHECKED' where  id='$ids' and ".$unique."=".$$unique." ");
            unset($_POST);
            }
            if(isset($_POST['single_delete_'.$ids])){
                $rev=mysqli_query($conn, "Delete from ".$table_details."  where  id='$ids' and ".$unique."=".$$unique." ");
            }
        }








    //for Delete..................................
    if(isset($_POST['add']))
    {
        $add=mysqli_query($conn, "Update ".$table_details." set item_status='1' where item_id=".$_POST[item_id]." and ".$unique."=".$$unique."");
    }

    //for Delete..................................
    if(isset($_POST['add_new']))
    { $add_news="INSERT INTO ".$table_details." (target_no,PBI_ID,item_id,pack_size,TSM_PBI_ID,year,month,item_status) VALUES ('".$$unique."','$master->PBI_ID','$_POST[item_id_new]','','$master->TSM_PBI_ID','$master->year','$master->month','1')";
        $query=mysqli_query($conn, $add_news);
    }

    $results="Select d.item_id,d.id,d.pre_target_amount,d.target_proposal,et.effective_tp,d.unit_price,d.amount,i.item_name,i.unit_name,i.finish_goods_code,i.pack_size,p.PBI_ID_UNIQUE as so_code
 from ".$table_details." d, item_info i,personnel_basic_info p,effective_tp et  where
 d.item_id=i.item_id and 
 d.PBI_ID=p.PBI_ID and
 d.item_status='1' and 
 i.item_id=et.item_id and 
 d.".$unique."=".$$unique." order by i.serial";
    $query=mysqli_query($conn, $results);
    while($row=mysqli_fetch_array($query)){
        $i=$i+1;
        $ids=$row[id];

        $order_qty=$_POST['order_qty'.$ids]*$row[pack_size];
        $total_unit=$_POST['total_unit_'.$ids]*$row[pack_size];
        $total_amt=$_POST['total_amt_'.$ids];

        if(isset($_POST['add_'.$ids])){
            mysqli_query($conn, "Update ".$table_details." SET order_qty='".$order_qty."',total_unit='".$total_unit."',total_amt='".$total_amt."',status='CHECKED' where  id='$ids' and ".$unique."=".$$unique."");
        }}


//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_details);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        unset($_POST);
        echo "<script>window.close(); </script>";
    }}

$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
$all_amt=find_a_field(''.$table_details.'','SUM(total_amt)',''.$unique.'='.$_GET[$unique]);

$RCV_AMTs=(find_a_field_sql('select sum(cr_amt) from journal where ledger_id='.$dealer_master->account_code) - find_a_field_sql('select sum(total_amt) from sale_do_chalan where dealer_code='.$dealer_master->dealer_code));

if($RCV_AMTs>0){
$$RCV_AMT=$RCV_AMTs;
} else { 
$RCV_AMT=$dealer_master->credit_limit-$all_amt;
} 
$AcandCreBalance=$RCV_AMTs+$dealer_master->credit_limit;

echo $AcandCreBalance;


?>


<?php require_once 'header_content.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=600,left = 230,top = -1");}
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
                        <tr style="background-color: bisque; vertical-align: middle">
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Code</th>
                            <th style="vertical-align: middle">Finish Goods</th>
                            <th style="width:5%; text-align:center;vertical-align: middle">UOM</th>
                            <th style="text-align:center; vertical-align: middle">Order Qty</th>
                            <th style="text-align:center; vertical-align: middle">Revised Qty</th>
                            <th style="text-align:center; vertical-align: middle">Unit Price</th>
                            <th style="text-align:center; vertical-align: middle">Amount</th>
                            <th style="vertical-align: middle; text-align: center">Option</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $results="Select d.*,i.*
 from 
 ".$table_details." d, 
 item_info i
 
 where
 d.item_id=i.item_id and 
 d.".$unique."=".$$unique." order by d.id";
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
                                <td align="center" style=" text-align:center; vertical-align: middle"><input type="text" style="width: 100%; text-align: center" name="order_qty<?=$ids;?>" id="order_qty<?=$ids;?>" readonly value="<?php if($row[order_qty]>0) echo $row[order_qty]/$row[pack_size]; else echo $row[total_unit]/$row[pack_size];?>"></td>
                                <input  type="hidden" style="height: 25px; vertical-align: middle" value="<?=$row[pack_size]?>" name="pack_size_<?=$ids;?>" id="pack_size_<?=$ids;?>" class='pack_size_<?=$ids;?>'>
                                <td align="center" style=" text-align:center;vertical-align: middle"><input type="text" style="width: 100%; text-align: center" name="total_unit_<?=$ids;?>" id="total_unit_<?=$ids;?>" value="<?php if($row[total_unit]>0) echo $row[total_unit]/$row[pack_size]; else echo '';?>" class="total_unit_<?=$ids;?>"></td>
                                <td align="center" style=" text-align:right;vertical-align: middle"><input type="text" style="width: 100%; text-align: right" name="unit_price<?=$ids;?>" id="unit_price<?=$ids;?>" value="<?=$row[unit_price];?>"  class="unit_price<?=$ids;?>"></td>
                                <td align="center" style=" text-align:right;vertical-align: middle"><input type="text" style="width: 100%; text-align: right" name="total_amt_<?=$ids;?>" id="total_amt_<?=$ids;?>" readonly value="<?php if($row[total_amt]>0) echo $row[total_amt]; else echo '';?>" class="sum"></td>
                                <td style="vertical-align: middle; width: 20%; text-align: center">
                                    <?php
                                    if($GET_status=='UNCHECKED' || $GET_status=='PROCESSING' || $GET_status=='MANUAL'){ ?>
                                    <button type="submit" class="btn btn-danger" name="single_delete_<?=$ids;?>" id="single_delete_<?=$ids;?>" style="font-size: 11px" onclick='return window.confirm("Are you confirm to Deleted?");'>Del</button>
                                    <button type="submit" class="btn btn-primary" name="single_checked_<?=$ids;?>" id="single_checked_<?=$ids;?>" style="font-size: 11px" onclick='return window.confirm("Are you confirm to Checked?");'>Check</button>

                                    <?php } else echo 'Checked'; ?>
                                </td>
                            </tr>
                            <script>
                                $(function(){
                                    $('#unit_price<?=$ids;?>, #total_unit_<?=$ids;?>').keyup(function(){
                                        var unit_price<?=$ids;?> = parseFloat($('#unit_price<?=$ids;?>').val()) || 0;
                                        var total_unit_<?=$ids;?> = parseFloat($('#total_unit_<?=$ids;?>').val()) || 0;
                                        var pack_size_<?=$ids;?> = parseFloat($('#pack_size_<?=$ids;?>').val()) || 0;
                                        $('#total_amt_<?=$ids;?>').val(((total_unit_<?=$ids;?> * pack_size_<?=$ids;?>)*unit_price<?=$ids;?>).toFixed(2));
                                    });
                                });
                            </script>
                            <?php $ttotalamount=$ttotalamount+$row[total_amt];
                        } ?>
                        </tbody>
                        <script>
                            // we used jQuery 'keyup' to trigger the computation as the user type
                            $('.sum').blur(function () {
                                // initialize the sum (total price) to zero
                                var sum = 0;
                                // we use jQuery each() to loop through all the textbox with 'price' class
                                // and compute the sum for each loop
                                $('.sum').each(function() {
                                    sum += Number($(this).val());
                                });
                                // set the computed value to 'totalPrice' textbox
                                $('#totalPrice').val((sum).toFixed(2));
                            });
                        </script>

                        <tr style="font-weight: bold">
                            <td colspan="7" style="font-weight:bold; font-size:11px" align="right">Total Order in Amount</td>
                            <td align="right" ><input style="height: 25px; width: 100%;font-size: 11px; text-align: right" type='text' id='totalPrice' value="<?=number_format($ttotalamount,2);?>" disabled /></td>
                            <td style="text-align:right"></td>
                        </tr>


                    </table>
                    <?php mysqli_close($conn); ?>

                   
                        <p>
                            <button style="float: left; margin-left: 1%; font-size: 12px" type="submit" name="deleted" id="deleted" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Deleted?");'>Deleted the Order</button>
                            <button style="float: right;font-size: 12px;margin-left: 1%;" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Forword to <?=find_a_field('warehouse','warehouse_name','warehouse_id='.$master->depot_id);?> </button>
                        </p>
                       
                </form>
            </div>
        </div>
    </div>

<?php } ?>
<?php require_once 'footer_content.php' ?>