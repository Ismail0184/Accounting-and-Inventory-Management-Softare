<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Inventory Cycle Counting";
$now=date('Y-m-d H:i:s');
$unique='cc_no';
$unique_field='cc_date';
$table="acc_cycle_counting_master";
$table_details="acc_cycle_counting_detail";
$page="QC_cycle_counting.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

$res_details='SELECT
m.'.$unique.',
d.id,
i.item_name,
i.unit_name,
i.finish_goods_code,
d.qty,
d.item_price,
d.total_amt,
d.cc_type,
d.batch,
d.mfg

FROM
'.$table.' m,
'.$table_details.' d,
item_info i

WHERE
m.'.$unique.'='.$_GET[$unique].' and
m.'.$unique.'=d.'.$unique.' and
d.item_id=i.item_id';

if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['returned_by']=$_SESSION[userid];
        $_POST['returned_at']=$now;
        $_POST['status']="RETURNED";
        $crud->update($unique);
        $crud      =new crud($table_details);
        $crud->update($unique);
        unset($_POST);
        echo "<script>self.opener.location = '".$page."'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }
    if (isset($_POST['checked'])) {
        $data2=mysqli_query($conn, $res_details);
        while($data=mysqli_fetch_object($data2)){
          $batch=$_POST['batch'.$data->id];
          $expiry_date=$_POST['mfg'.$data->id];
          mysqli_query($conn, "UPDATE ".$table_details." SET batch='".$batch."', mfg='".$expiry_date."' where ".$unique."=".$_GET[$unique]." and id=".$data->id."");
                             }
        $_POST['checked_by_qc']=$_SESSION[userid];
        $_POST['checked_by_qc_at']=$now;
        $_POST['status']="CHECKED";
        $crud->update($unique);
        $crud      =new crud($table_details);
        $crud->update($unique);
        unset($_POST);
        echo "<script>self.opener.location = '".$page."'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

  }

    if (isset($_POST[viewreport])) {
        $res='SELECT m.cc_no,m.cc_no,m.cc_date as date,m.remarks,w.warehouse_name,concat(uam.fname,"<br>","at: ",m.entry_at) as entry_by,IF(m.checked_by_qc>0,concat((SELECT fname from user_activity_management where user_id=m.checked_by_qc),"<br>","at: ",m.checked_by_qc_at), "PENDING " ) AS QC_check_Status,
        IF(m.checked_by_acc>0,concat((SELECT fname from user_activity_management where user_id=m.checked_by_acc),"<br>","at: ",m.checked_by_qc_at), "PENDING " ) AS Accounts_check_status,m.status
        from '.$table.' m, warehouse w,user_activity_management uam
        where
        m.warehouse_id=w.warehouse_id and
        m.entry_by=uam.user_id and m.warehouse_id='.$_POST[warehouse_id].' order by m.cc_no';
      } else {
        $res='SELECT m.cc_no,m.cc_no,m.cc_date as date,m.remarks,w.warehouse_name,concat(uam.fname,"<br>","at: ",m.entry_at) as entry_by,m.status
        from '.$table.' m, warehouse w,user_activity_management uam
        where
        m.warehouse_id=w.warehouse_id and
        m.entry_by=uam.user_id and
        m.status="UNCHECKED" order by m.cc_no';
      }
?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=0, directories=no, status=0, menubar=0, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 250,top = -1");}
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
                             <th>Batch</th>
                             <th>Expiry Date</th>
                             <th>CC Type</th>
                         </tr>
                         <? $data2=mysqli_query($conn, $res_details);
                         while($data=mysqli_fetch_object($data2)){?>
                             <tr>
                                 <td style="vertical-align:middle;"><?=$i=$i+1;?></td>
                                 <td style="vertical-align:middle;"><?=$data->finish_goods_code;?></td>
                                 <td style="vertical-align:middle;"><?=$data->item_name;?></td>
                                 <td style="vertical-align:middle;"><?=$data->unit_name;?></td>
                                 <td style="vertical-align:middle;"><?=$data->qty;?></td>
                                 <td style="text-align:right;vertical-align:middle"><?=number_format($data->item_price,2);?></td>
                                 <td style="text-align:right;vertical-align:middle;"><?=number_format($data->total_amt,2);?></td>
                                 <td style="text-align:right;vertical-align:middle;"><input type="text" name="batch<?=$data->id?>" style="width:80px;font-size:11px" readonly value="<?=$data->batch?>"></td>
                                 <td style="text-align:right;vertical-align:middle;"><input type="date" name="mfg<?=$data->id?>" style="width:110px;font-size:11px" readonly id="mfg" value="<?=$data->mfg?>"></td>
                                 <td align="right" style="vertical-align:middle; text-align:center"><select style="width: 99%; font-size:11px" tabindex="-1"  required="required">
                                 <option value="<?=$data->cc_type?>"><?php if($data->cc_type=='+') echo 'Stock In'; else echo 'Stock Out'; ?></option>
                                 </select></td>

                             </tr>
                             <?php }
    $stock_in=find_a_field(''.$table_details.'','SUM(total_amt)','cc_type="+" and '.$unique.'='.$_GET[$unique]);
    $stock_out=find_a_field(''.$table_details.'','SUM(total_amt)','cc_type="-" and '.$unique.'='.$_GET[$unique]); ?>
                         </tbody>
                         <?php if($stock_out>0){ ?>
                         <tr style="font-weight: bold">
                             <td colspan="6" style="font-weight:bold; font-size:11px" align="right">Total Inventory Shortage = </td>
                             <td align="right" ><?=number_format($stock_out,2);?></td>
                         </tr>
                         <?php } if($stock_in>0){ ?>
                         <tr style="font-weight: bold">
                             <td colspan="6" style="font-weight:bold; font-size:11px" align="right">Total Inventory Surplus = </td>
                             <td align="right" ><?=number_format($stock_in,2);?></td>
                         </tr><?php } ?></table>
                     <?php $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]); if($GET_status=='UNCHECKED'){  ?>
                                             <p>
                                                 <button style="float: left;  font-size: 11px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Return to initiator</button>
                                                 <input type="text" id="remarks_returned" style="width: 200px; font-size: 11px" name="remarks_returned" placeholder="Please drop a note for the return" class="form-control col-md-7 col-xs-12" >
                                                 <button style="float: right; font-size: 11px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Checked & Forward to Accounts</button>
                                             </p>
                                         <? } else { ?><h6 style="text-align: center;color: red;font-weight: bold"><i>This CC has been <?=$GET_status?> !!</i></h6><?php } ?>
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
