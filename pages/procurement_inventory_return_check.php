<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Inventory Return";
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todaysss=date('Y-m-d H:i:s');
$now=time();
$unique='id';
$unique_field='name';
$table="purchase_return_master";
$table_details="purchase_return_details";
$journal_item="journal_item";
$page="procurement_inventory_return_check.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


$res_details='select
d.id,m.ref_no,i.item_id,m.warehouse_id,i.item_name,i.unit_name,i.finish_goods_code,d.qty,d.qc_qty,d.po_no,d.id as did,d.rate,d.amount,d.batch,m.return_date,
(SELECT SUM(item_in-item_ex) from journal_item WHERE item_id=i.item_id and batch=d.batch and warehouse_id=d.warehouse_id) as batch_stock_get,
(SELECT rate from lc_lc_received_batch_split WHERE item_id=i.item_id and batch=d.batch and warehouse_id=d.warehouse_id and status in ("PROCESSING")) as batch_rate_get
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
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>window.close(); </script>";
    }

if(isset($_POST['checked'])){
$up=mysqli_query($conn, "UPDATE purchase_return_details SET  status='PROCESSING' where m_id='".$_GET[$unique]."'");
$up=mysqli_query($conn, "UPDATE purchase_return_master SET status='PROCESSING',checked_by_pro='".$_SESSION[userid]."',checked_by_pro_at='".$todaysss."' where id=".$_GET[$unique]." ");
unset($_POST);
echo "<script>self.opener.location = '$page'; self.blur(); </script>";
echo "<script>window.close(); </script>";

}} // prevent_multi_submit

$sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
user_plant_permission upp,
warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
upp.user_id=".$_SESSION[userid]." and upp.status>0
order by w.warehouse_id";


if (isset($_POST[viewreport])){
    $res='select m.id,m.id,m.ref_no,m.return_date as rdate,m.remarks,m.ref_no,w.warehouse_name,v.vendor_name,u.fname as entry_by,m.entry_at,m.status
    from purchase_return_master m,warehouse w,vendor v,users u where m.warehouse_id=w.warehouse_id and m.vendor_id=v.vendor_id and m.entry_by=u.user_id and m.section_id='.$_SESSION['sectionid'].' and m.company_id='.$_SESSION['companyid'].' and m.warehouse_id='.$_POST[warehouse_id].' and m.return_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'" group by m.id order by m.id desc';
  } else {
    $res='select m.id,m.id,m.ref_no,m.return_date as rdate,m.remarks,m.ref_no,w.warehouse_name,v.vendor_name,u.fname as entry_by,m.entry_at,m.status
    from purchase_return_master m,warehouse w,vendor v,users u where m.warehouse_id=w.warehouse_id and m.vendor_id=v.vendor_id and m.entry_by=u.user_id and m.section_id='.$_SESSION['sectionid'].' and m.company_id='.$_SESSION['companyid'].' and m.status="CHECKED" group by m.id order by m.id desc';}
?>



<?php require_once 'header_content.php'; ?>
 <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 250,top = -1");}
    </script>
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>

<?php
 if(isset($_GET[$unique])){
 require_once 'body_content_without_menu.php'; } else {
 require_once 'body_content.php'; }
 if(isset($_GET[$unique])): ?>

     <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
              <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? //require_once 'support_html.php';?>
                     <table align="center" style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                         <tr style="background-color: bisque">
                             <th style="vertical-align:middle">#</th>
                             <th style="vertical-align:middle">Item Id</th>
                             <th style="vertical-align:middle">Item Name</th>
                             <th style="vertical-align:middle">Unit Name</th>
                             <th style="vertical-align:middle">Batch</th>
                             <th style="vertical-align:middle">Batch Rate</th>
                             <th style="vertical-align:middle; text-align:center">Req. Qty<br>  [Apprv. Qty]</th>
                             <th style="vertical-align:middle; text-align:center">Rate</th>
                             <th style="vertical-align:middle; text-align:center">Amount</th>
                         </tr>
                         <? $data2=mysqli_query($conn, $res_details);while($data=mysqli_fetch_object($data2)){
                            $idget=$data->id;?>
                             <tr>
                                 <td style="vertical-align:middle"><?=$i=$i+1;?></td>
                                 <td style="vertical-align:middle"><?=$data->finish_goods_code;?></td>
                                 <td style="vertical-align:middle"><?=$data->item_name;?></td>
                                 <td style="vertical-align:middle"><?=$data->unit_name;?></td>
                                 <td style="vertical-align:middle"><?=$data->batch;?></td>
                                 <td style="vertical-align:middle"><?=$data->batch_rate_get;?></td>
                                 <td style="text-align:center; vertical-align:middle"><?=$data->qty;?><br><input class="form-control col-md-7 col-xs-12" type="text" name="qty<?=$data->did?>" style="width: 80px; font-size: 11px; text-align:center"  value="<?=$data->qc_qty;?>" id="qty<?=$data->did?>"></td>
                                 <td style="vertical-align:middle"><input  type="text" class="form-control col-md-7 col-xs-12" style="width: 80px; font-size: 11px; text-align:center" readonly value="<?=$data->rate;?>" name="rate<?=$data->did?>" id="rate<?=$data->did?>" autocomplete="off"></td>
                                 <td style="vertical-align:middle"><input class="form-control col-md-7 col-xs-12" style="width: 80px; font-size: 11px; text-align:right" value="<?=$data->amount;?>" readonly type='text' id='sum<?=$data->did?>' name='sum<?=$data->did?>' /></td>
                              </tr><?php $ttoal=$ttoal+$data->amount;} ?>
                          <tr><td colspan="8" style="text-align: right; font-weight: bold; vertical-align: middle; font-size: 11px">Total Inventory Return Amount= </td>
                                            <td><input style="width: 80px; font-weight: bold; font-size: 12px; text-align:right" type='text' id='totalPrice' value="<?=number_format($ttoal,2);?>" disabled /></td></tr>
                     </table>



<?php $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]); if($GET_status=='CHECKED'){  ?>
                        <p>
                            <button style="float: left;  font-size: 11px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Return to initiator</button>
                            <input type="text" id="returned_note" style="width: 200px; font-size: 11px"   name="returned_note" placeholder="Please drop a note for the return" class="form-control col-md-7 col-xs-12" >
                            <button style="float: right; font-size: 11px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Forward the Inventory Return </button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;font-weight: bold"><i>This inventory return has been checked !!</i></h6>';   }?>
                 </form>
                 </div>
             </div>
         </div>
 <?php endif; ?>


         <?php if(!isset($_GET[$unique])): ?>
            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
                <table align="center" style="width: 50%;">
                    <tr><td><input type="date"  style="width:150px; font-size: 11px; height: 30px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                        <td style="width:10px; text-align:center"> -</td>
                        <td><input type="date"  style="width:150px;font-size: 11px; height: 30px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                        <td style="width:10px; text-align:center"> -</td>
                        <td><select  class="form-control" style="width: 200px;font-size:11px; height: 30px" required="required"  name="warehouse_id" id="warehouse_id">
                                <option selected></option>
                                <?=advance_foreign_relation($sql_plant,$_POST[warehouse_id]);?>
                            </select></td>
                        <td style="padding: 10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Return Challan</button></td>
                    </tr></table>
         </form>
         <?=$crud->report_templates_with_status($res,$title);?>
         <?php endif;?>
         <?=$html->footer_content();mysqli_close($conn);?>
