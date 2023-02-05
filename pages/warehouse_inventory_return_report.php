<?php
require_once 'support_file.php';
$title="Inventory Return List";
$now=time();
$unique='id';
$unique_field='name';
$table="purchase_return_master";
$page="warehouse_inventory_return_report.php";
$target_page='warehouse_inventory_return.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todaysss=$dateTime->format("d/m/Y  h:i A");
$IR_master=find_all_field(''.$table.'','',''.$unique.'='.$$unique);

if(prevent_multi_submit()){
    $$unique = $_GET[$unique];
//check by qc..................................
    if(isset($_POST['reprocess']))
    {
        $_SESSION['wir_unique']=$$unique;
        echo "<script>self.opener.location = '$target_page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//check by qc..................................
    if(isset($_POST['modifyqc']))
    {
        $_POST['checked_at']=$todaysss;
        $_POST['status']='CHECKED';
        $crud->update($unique);
        $type=1;

        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }


//for Delete..................................
    if(isset($_POST['delete']))
    {   $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}
if (isset($_POST[viewreport])) {
    $res='select
    m.id,
    m.id,
    m.ref_no,
    m.return_date as rdate,
    m.remarks,
    m.ref_no,
    w.warehouse_name,
    v.vendor_name,
    m.status
    from
    purchase_return_master m,
    warehouse w,
    vendor v
    where
    m.warehouse_id=w.warehouse_id and
    m.vendor_id=v.vendor_id and
    m.section_id='.$_SESSION['sectionid'].' and
    m.company_id='.$_SESSION['companyid'].' and
    m.warehouse_id='.$_POST[warehouse_id].'
    group by m.id order by m.id desc';
  }
    $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
    user_plant_permission upp,
    warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
    upp.user_id=".$_SESSION[userid]." and upp.status>0
    order by w.warehouse_id";?>

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
                         <?
						 $res='select
m.id,
m.ref_no,
i.item_name,
i.unit_name,
i.finish_goods_code,
d.qty,
d.po_no,
d.id as did,
d.rate,
d.amount
from
purchase_return_master m,
purchase_return_details d,
warehouse w,
vendor v,
item_info i
where
m.id=d.m_id and
i.item_id=d.item_id and
m.warehouse_id=w.warehouse_id and
m.vendor_id=v.vendor_id and
m.id='.$_GET[$unique].'
group by d.id';
                         $data2=mysql_query($res);
                         while($data=mysql_fetch_object($data2)){?>
                             <tr>
                                 <td><?=$i=$i+1;?></td>
                                 <td><?=$data->finish_goods_code;?></td>
                                 <td><?=$data->item_name;?></td>
                                 <td><?=$data->unit_name;?></td>
                                 <td><?=$data->qty;?></td>
                                 <td style="text-align:right"><?=number_format($data->rate,2);?></td>
                                 <td style="text-align:right"><?=number_format($data->amount,2);?></td>
                             </tr>
                         <?php  $ttoal=$ttoal+$data->amount; } ?>
                          <tr><td colspan="6" style="text-align: right; font-weight: bold; vertical-align: middle; font-size: 11px">Total Inventory Return Amount= </td><td style="text-align:right"><?=number_format($ttoal,2);?></td></tr>
                     </table>



<?php $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);?>
<?php if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='CANCELED'){
if($IR_master->entry_by==$_SESSION[userid]){ ?>
<p align="center">
<button style="font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-process the IR</button>
</p>
<? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This IR was created by another person. So you are not able to do anything here!!</i></h6>';
}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This IR has been checked !!</i></h6>';}?>
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
                       <?=advance_foreign_relation($sql_plant,$_POST[warehouse_id]);?>
                   </select></td>
               <td style="padding: 10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Return Challan</button></td>
           </tr></table>
</form>
<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
