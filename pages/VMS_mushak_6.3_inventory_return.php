<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Inventory Return List';
$unique='id';
$table="purchase_return_master";
$table_details="purchase_return_details";
$page='VMS_mushak_6.3_IR.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
if (isset($_POST[viewreport])) {
    $res = "SELECT  m.id,m.id,m.return_date,v.vendor_name,
    w.warehouse_name as warehouse,uam.fname as prepared_by,m.entry_at as prepared_at,m.mushak_challan_status as status FROM
							".$table." m,
							vendor v,
							user_activity_management uam,
              warehouse w
							 where
							 m.vendor_id=v.vendor_id and
							 m.return_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and
							 m.entry_by=uam.user_id and
               m.warehouse_id=w.warehouse_id and
               m.warehouse_id=".$_POST[depot_id]."
							  order by m.id"; } else {
    $res = "SELECT  m.id,m.id,m.return_date,v.vendor_name,
    w.warehouse_name as warehouse,uam.fname as prepared_by,m.entry_at as prepared_at,m.mushak_challan_status as status FROM
							".$table." m,
							vendor v,
							user_activity_management uam,
              warehouse w
							 where
							 m.vendor_id=v.vendor_id and
               m.status in ('PROCESSING') and
               m.mushak_challan_status='UNRECORDED' AND
							 m.entry_by=uam.user_id and
               m.warehouse_id=w.warehouse_id and
               m.warehouse_id=".$_SESSION[warehouse]."
							  order by m.id";
} ?>
<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=0, directories=no, status=0, menubar=0, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=1500,height=1000,left = 250,top = -1");}
    </script>

<?php require_once 'body_content.php';?>
<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 30px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 30px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select  class="form-control" style="width: 200px;font-size:11px; height: 30px" required="required"  name="depot_id" id="depot_id">
                        <option selected></option>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_POST[depot_id]);?>
                    </select></td>
                <td style="padding: 10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Delivered Challan</button></td>
            </tr></table>
    </form>

<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
