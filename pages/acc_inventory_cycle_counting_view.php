<?php
require_once 'support_file.php';
$title="Inventory Cycle Counting";
$now=time();
$unique='cc_no';
$unique_field='cc_date';
$table="acc_cycle_counting_master";
$table_details="acc_cycle_counting_detail";
$page="acc_inventory_cycle_counting_view.php";
$$unique = $_GET[$unique];
$target_page="acc_inventory_cycle_counting.php";


if(prevent_multi_submit()){
    if(isset($_POST['reprocess'])){
        $_SESSION['cc_unique']=$$unique;
        echo "<script>self.opener.location = '$target_page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

    if(isset($_GET[$unique]))
    {   $condition=$unique."=".$_GET[$unique];
        $data=db_fetch_object($table,$condition);
        while (list($key, $value)=each($data))
        { $$key=$value;}}

if (isset($_POST[viewreport])) {
    $res='SELECT m.cc_no,m.cc_no,m.cc_date as date,m.remarks,w.warehouse_name,concat(uam.fname,"<br>","at: ",m.entry_at) as entry_by,IF(m.checked_by_qc>0,concat((SELECT fname from user_activity_management where user_id=m.checked_by_qc),"<br>","at: ",m.checked_by_qc_at), "PENDING " ) AS QC_check_Status,
    IF(m.checked_by_acc>0,concat((SELECT fname from user_activity_management where user_id=m.checked_by_acc),"<br>","at: ",m.checked_by_qc_at), "PENDING " ) AS Accounts_check_status,m.status
    from '.$table.' m, warehouse w,user_activity_management uam
    where
    m.warehouse_id=w.warehouse_id and
    m.entry_by=uam.user_id and m.warehouse_id='.$_POST[warehouse_id];
  }
  $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
  user_plant_permission upp,
  warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
  upp.user_id=".$_SESSION[userid]." and upp.status>0
  order by w.warehouse_id";

$res_details='SELECT
m.'.$unique.',
m.'.$unique.',
i.item_name,
i.unit_name,
i.finish_goods_code,
d.qty,
d.item_price,
d.total_amt

FROM
'.$table.' m,
'.$table_details.' d,
item_info i

WHERE
m.'.$unique.'='.$_GET[$unique].' and
m.'.$unique.'=d.'.$unique.' and
d.item_id=i.item_id';
?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=0, directories=no, status=0, menubar=0, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
</script>

<?php
 if(isset($_GET[$unique])){
 require_once 'body_content_without_menu.php'; } else {
 require_once 'body_content.php'; }
 if(isset($_GET[$unique])){ ?>
     <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
     <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                    <? require_once 'support_html.php';?>
                     <table align="center" style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                         <tr style="background-color: bisque">
                             <th>#</th>
                             <th>Item Id</th>
                             <th>Item Name</th>
                             <th>Unit Name</th>
                             <th>Qty</th>
                             <th>Rate</th>
                             <th>Amount</th>
                         </tr>
                         <? $data2=mysqli_query($conn, $res_details);
                         while($data=mysqli_fetch_object($data2)){?>
                             <tr>
                                 <td><?=$i=$i+1;?></td>
                                 <td><?=$data->finish_goods_code;?></td>
                                 <td><?=$data->item_name;?></td>
                                 <td><?=$data->unit_name;?></td>
                                 <td><?=$data->qty;?></td>
                                 <td style="text-align:right"><?=number_format($data->item_price,2);?></td>
                                 <td style="text-align:right"><?=number_format($data->total_amt,2);?></td>
                             </tr>
                         <?php  $ttoal=$ttoal+$data->total_amt; } ?>
                          <tr><td colspan="6" style="text-align: right; font-weight: bold; vertical-align: middle; font-size: 11px">Total Amount= </td><td style="text-align:right"><?=number_format($ttoal,2);?></td></tr>
                     </table>



<?php $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);?>
<?php if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='CANCELED'){
if($entry_by==$_SESSION[userid]){ ?>
<p align="center">
<button style="font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-process the CC</button>
</p>
<? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This CC was created by another user. So you are not able to do anything here!!</i></h6>';
}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This CC has been checked !!</i></h6>';}?>
                 </div>
             </div>
         </div>
     </form>
 <?php } ?>


<?php if(!isset($_GET[$unique])): ?>
   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
       <table align="center" style="width: 50%;">
           <tr><td><input type="date"  style="width:150px; font-size: 11px; height: 30px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
               <td style="width:10px; text-align:center"> -</td>
               <td><input type="date"  style="width:150px;font-size: 11px; height: 30px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
               <td style="width:10px; text-align:center"> -</td>
               <td><select  class="form-control" style="width: 200px;font-size:11px; height: 30px" required="required"  name="warehouse_id" id="warehouse_id">
                       <option selected></option>
                       <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_POST[warehouse_id]);?>
                   </select></td>
               <td style="padding: 10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Cycle Counting</button></td>
           </tr></table>
</form>
<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
