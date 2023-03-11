<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$GET_do_no = @$_GET['do_no'];
$title='Mushak Challan | DO No: '.$GET_do_no;
$unique='do_no';
$table="sale_do_master";
$table_details="sale_do_details";
$page='acc_mushak_6.3.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
if (isset($_POST['viewreport'])) {
    $res = "SELECT  m.do_no,m.do_no as 'DO',vms.mushak_no as 'Mushak',vms.issue_date as 'VAT Date',ft.term_year as fiscal_year,m.do_date,m.do_type,d.dealer_name_e as customer_name,w.warehouse_name as warehouse,uam.fname as prepared_by,m.entry_at as prepared_at,m.challan_date as delivered_time,m.mushak_challan_status as status FROM
							 sale_do_master m,
							dealer_info d,
							user_activity_management uam,
							warehouse w,
							VAT_mushak_6_3 vms,
							fiscal_term ft	
							 where
							 m.dealer_code=d.dealer_code and
							 m.do_date between '".$_POST['f_date']."' and '".$_POST['t_date']."' and
							 m.status='COMPLETED' and
							 m.mushak_challan_status not in ('UNRECORDED') and
							 m.entry_by=uam.user_id and
                             m.depot_id=w.warehouse_id and
                             m.depot_id=".$_POST['depot_id']." and
                             m.do_no=vms.do_no and 
                             vms.fiscal_year=ft.fiscal_year and
                             vms.source in ('Sales')
							  order by m.do_no"; } else {
    $res = "SELECT  m.do_no,m.do_no,m.do_date,m.do_type,d.dealer_name_e as customer_name,w.warehouse_name as warehouse,uam.fname as prepared_by,m.entry_at as prepared_at,m.challan_date as delivered_time FROM
							 sale_do_master m,
							dealer_info d,
							user_activity_management uam,
              warehouse w
							 where
							 m.dealer_code=d.dealer_code and
							 m.status in ('COMPLETED') and
               m.mushak_challan_status='UNRECORDED' and
							 m.entry_by=uam.user_id and
               m.depot_id=".$_SESSION['warehouse']." and
               m.depot_id=w.warehouse_id
							  order by m.do_no";
} ?>
<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=0, directories=no, status=0, menubar=0, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=1500,height=1000,left = 250,top = -1");}
    </script>

<?php require_once 'body_content.php';?>
<?php if(!isset($_GET[$unique])):
    $POST_f_date = @$_POST['f_date'];
    $POST_t_date = @$_POST['t_date'];
    $POST_depot_id = @$_POST['depot_id'];
    ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 30px"  value="<?=($POST_f_date!='')? $POST_f_date : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 30px"  value="<?=($POST_t_date!='')? $POST_t_date : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select  class="form-control" style="width: 200px;font-size:11px; height: 30px" required="required"  name="depot_id" id="depot_id">
                        <option selected></option>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$POST_depot_id);?>
                    </select></td>
                <td style="padding: 10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Delivered Challan</button></td>
            </tr></table>
    </form>

<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>