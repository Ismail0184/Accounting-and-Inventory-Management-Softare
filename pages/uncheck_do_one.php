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


$results="Select d.*,i.*
from
".$table_details." d,
item_info i

where
d.item_id=i.item_id and
d.".$unique."=".$$unique." order by d.id";

if(prevent_multi_submit()){
  if(isset($_POST['reprocess'])){
    if($master->do_section=='Special_invoice'){
    $_SESSION[select_dealer_do_SP]=$master->dealer_code;
      $_SESSION['unique_master_for_SP']=$$unique;} else {
        $_SESSION[select_dealer_do_regular]=$master->dealer_code;
      $_SESSION['unique_master_for_regular']=$$unique;  
      }
      echo "<script>self.opener.location = '$target_page'; self.blur(); </script>";
      echo "<script>window.close(); </script>";
  }


 if(isset($_POST['checked'])){
   $sent_to_warehouse_at=date('Y-m-d H:s:i');
   $results="Select d.*,i.*
   from
   ".$table_details." d,
   item_info i
   where
   d.item_id=i.item_id and
   d.".$unique."=".$_GET[$unique]." order by d.id";
        $query=mysqli_query($conn, $results);
        while($data=mysqli_fetch_object($query)) {
          if($data->total_amt>0){
              $item_status='buy';
          } else {
              $item_status='get';
          }
          $_SESSION['bqty_while_do_send']=$data->total_unit;
          $fifocheck=mysqli_query($conn, "select distinct qb.batch, SUM(j.item_in-j.item_ex) as qty, j.item_price as rate,qb.mfg  from journal_item j, lc_lc_received_batch_split qb
          where qb.batch=j.batch and qb.status='PROCESSING' and j.item_id=qb.item_id  and j.item_id='".$data->item_id."' and j.warehouse_id='".$data->depot_id."'
          group by qb.batch order by qb.mfg asc,qb.batch asc");
          
          while ($fifocheckrow=mysqli_fetch_object($fifocheck)){
              if ($_SESSION['bqty_while_do_send']<=$fifocheckrow->qty && $_SESSION['bqty_while_do_send']>0 && $fifocheckrow->qty>0){
                  mysqli_query($conn, "INSERT INTO journal_item (ji_date,item_id,warehouse_id,dealer_id,item_ex,item_price,total_amt,tr_from,do_no,entry_by,entry_at,ip,tr_no,section_id,company_id,batch,expiry_date,Remarks,gift_type) VALUES ('" . $data->do_date . "','$data->item_id','$data->depot_id','$data->dealer_code','$_SESSION[bqty_while_do_send]','$fifocheckrow->rate','".$_SESSION[bqty_while_do_send]*$fifocheckrow->rate."','Sales','$data->do_no','$_SESSION[userid]','$sent_to_warehouse_at','$ip','$data->tr_no','$_SESSION[sectionid]','$_SESSION[companyid]',$fifocheckrow->batch,'$fifocheckrow->mfg','$item_status','$data->gift_type')");
                  $_SESSION['bqty_while_do_send']= 0;
              } else if ($_SESSION['bqty_while_do_send']>=$fifocheckrow->qty && $_SESSION['bqty_while_do_send']>0 && $fifocheckrow->qty>0){
                  mysqli_query($conn, "INSERT INTO journal_item (ji_date,item_id,warehouse_id,dealer_id,item_ex,item_price,total_amt,tr_from,do_no,entry_by,entry_at,ip,tr_no,section_id,company_id,batch,expiry_date,Remarks,gift_type) VALUES ('" . $data->do_date . "','$data->item_id','$data->depot_id','$data->dealer_code','$fifocheckrow->qty','$fifocheckrow->rate','".$fifocheckrow->qty*$fifocheckrow->rate."','Sales','$data->do_no','$_SESSION[userid]','$sent_to_warehouse_at','$ip','$data->tr_no','$_SESSION[sectionid]','$_SESSION[companyid]',$fifocheckrow->batch,'$fifocheckrow->mfg','$item_status','$data->gift_type')");
                  $_SESSION['bqty_while_do_send']= intval($_SESSION['bqty_while_do_send'])-$fifocheckrow->qty;
              }}



        }
        $up=mysqli_query($conn, "Update ".$table." set status='CHECKED',checked_by='$_SESSION[userid]',checked_at='$now',sent_to_warehuse_at='".$sent_to_warehouse_at."' where ".$unique."=".$$unique."");
        $up=mysqli_query($conn, "Update ".$table_details." set status='CHECKED' where ".$unique."=".$$unique."");
        $type=1;
        unset($_POST);
        echo "<script>self.opener.location = '$pages'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
$accountbalance=find_a_field('journal','SUM(cr_amt-dr_amt)','ledger_id='.$dealer_master->account_code);
$do_amount=find_a_field_sql('select sum(total_amt) from sale_do_details where do_no='.$_GET[do_no])-find_a_field('sale_do_master','commission_amount','do_no='.$_GET[do_no]);
$accountbalance_final=$accountbalance+$dealer_master->credit_limit;
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



<?php if(isset($_GET[$unique])){ ?>
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
                            <th style="width:5%; text-align:center;vertical-align: middle">UOM</th>
                            <th style="width:5%; text-align:center;vertical-align: middle">Available Stock</th>
                            <th style="text-align:center; vertical-align: middle">Order Qty</th>
                            <th style="text-align:center; vertical-align: middle">Unit Price</th>
                            <th style="text-align:center; vertical-align: middle">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $results="Select SUM(d.total_unit) as total_unit,SUM(d.total_amt) as total_amt,d.unit_price,i.*
                        from
                        ".$table_details." d,
                        item_info i
                        where
                        d.item_id=i.item_id and
                        d.item_id not in ('1096000100010312') and
                        d.".$unique."=".$$unique." group by d.item_id order by d.id";$query=mysqli_query($conn, $results);
                        while($data=mysqli_fetch_object($query)){

                            $present_stock_sql=mysqli_query($conn, "Select i.item_id,i.finish_goods_code,i.item_name,i.unit_name,i.pack_size,
    REPLACE(FORMAT(SUM(j.item_in-j.item_ex), 0), ',', '') as Available_stock_balance
    from
    item_info i,
    journal_item j,
    lc_lc_received_batch_split bsp
    
    where
    
    j.item_id=i.item_id and
    j.warehouse_id='".$master->depot_id."' and
    bsp.batch=j.batch and 
    bsp.status='PROCESSING' and 
    j.item_id='".$data->item_id."'
    group by j.item_id order by i.item_id");
    $ps_data=mysqli_fetch_object($present_stock_sql);
                         
                            $available_stock=$ps_data->Available_stock_balance;
                          
                          
                          $unrec_qty=$available_stock-$data->total_unit;?>
                            <tr <?php if($unrec_qty<0){$cow++;?> style="background-color:red; color:white" <?php } ?>>
                                <td style="width:3%; vertical-align:middle"><?=$i=$i+1; ?></td>
                                <td style="vertical-align:middle"><?=$data->item_id;?> - <?=$data->finish_goods_code;?></td>
                                <td style="vertical-align:middle;"><?=$data->item_name; if($data->total_amt==0){ echo '<font style="color: red; margin-left: 5px">[Free]</font>'; } elseif($data->total_amt<0){echo '<font style="color: red; margin-left: 5px">[Discounted]</font>'; }?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$data->unit_name;?></td>
                                <td style="vertical-align:middle; text-align:center"><input type="text" name="available_stock" style="width:80px; height:20px; font-size:11px" value="<?=$available_stock?>" readonly class="form-control col-md-7 col-xs-12"></td>
                                <td align="center" style=" text-align:center;vertical-align: middle"><?=$data->total_unit;?></td>
                                <td align="center" style=" text-align:right;vertical-align: middle"><?=($data->unit_price==0)? '-' : $data->unit_price?></td>
                                <td align="center" style=" text-align:right;vertical-align: middle"><?=($data->total_amt==0)? '-' : $data->total_amt?></td>
                            </tr>
                        <?php $ttotalamount=$ttotalamount+$data->total_amt;}
                              $cash_discount=find_a_field(''.$table_details.'','SUM(total_amt)','item_id="1096000100010312" and do_no="'.$_GET[do_no].'"');
                              $cash_discounts=substr($cash_discount,1)
                         ?>
                        </tbody>
                        <?php if($cash_discounts>0):?>
                        <tr style="font-weight: bold">
                            <td colspan="7" style="font-weight:bold; font-size:11px" align="right">Less: Cash Discount</td>
                            <td align="right" ><?=number_format($cash_discounts,2);?></td>
                        </tr><?php endif;?>
                        <tr style="font-weight: bold">
                            <td colspan="7" style="font-weight:bold; font-size:11px" align="right">Total Order in Amount</td>
                            <td align="right" ><?=number_format($ttotalamount+$cash_discount,2);?></td>
                        </tr>
                        <?php
                        $commission=find_a_field('sale_do_master','commission','do_no='.$_GET[do_no]);
                        if($commission>0) { ?>
                        <tr style="font-weight: bold">
                            <td colspan="7" style="font-weight:bold; font-size:11px" align="right">Less: Commission</td>
                            <td style="text-align:right"><?=number_format($master->commission_amount,2);?></td>
                        </tr>
                        <tr style="font-weight: bold">
                            <td colspan="7" style="font-weight:bold; font-size:11px" align="right">Total Receivable Amount</td>
                            <td style="text-align:right"><?=number_format(($ttotalamount+$cash_discount)-$master->commission_amount,2);?></td>
                        </tr>
                      <?php } ?>


                    </table>
                    <?php
                    if($cow<1){
                    if($GET_status=='PROCESSING' || $GET_status=='MANUAL' || $GET_status=='RETURNED'){
						        if($accountbalance_final>=$do_amount){ if($GET_status=='RETURNED'){?><p style="text-align: center; font-size: 12px; color:red;font-weight:bold;"><i>Returned Remarks: <?=find_a_field(''.$table.'','returned_remarks','do_no='.$_GET[do_no]);?></i></p><?php } ?>
                        <p>
                            <button style="float: left; margin-left: 1%; font-size: 12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Deleted?");'>Re-Processing</button>
                          <?php if ($GET_status!=='RETURNED' || $GET_status=='MANUAL') { ?>
                            <button style="float: right;font-size: 12px;margin-left: 1%;" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Forword to <?=find_a_field('warehouse','warehouse_name','warehouse_id='.$master->depot_id);?> </button>
                           <?php } ?>
                        </p>
                        <?php } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>The amount has not yet been found. Talk to the Accounts Department for credit limits !!</i></h6>';}  ?>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This invoice has been '.$GET_status.'!!</i></h6>';}} else { ?>                             
                        <?php if($GET_status=='PROCESSING' || $GET_status=='MANUAL' || $GET_status=='RETURNED'){?>
                        <button style="float: left; margin-left: 1%; font-size: 12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Deleted?");'>Re-Processing</button>
                        <?php } else { } ?>
                  <h6 style="text-align: center;color: red;  font-weight: bold"><i>Oops! Some of the items have exceeded the stock balance!!</i></h6> <?php }?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>
<?=$html->footer_content();mysqli_close($conn);?>
