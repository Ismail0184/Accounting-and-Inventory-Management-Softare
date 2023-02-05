 <?php
require_once 'support_file.php';
$title="Primary DO List";
 $table_master='sale_do_master';
 $unique_master='do_no';
 $table_detail='sale_do_details';
 $unique_detail='id';
 $table_chalan='sale_do_chalan';
 $unique_chalan='id';
 $$unique_master=$_POST[$unique_master];
 $table='sale_do_master';
 $show='dealer_code';
 $id='do_no';
 $text_field_id='old_do_no';
 $target_url = 'uncheck_do_one.php';
 if(isset($_POST[viewreport])){
    if ($_POST['status'] != '' && $_POST['status'] != 'All') {
        $status_conn=" and m.status='" . $_POST['status'] . "'";
    } else {
        $status_conn=" and 1";
    }
	if ($_POST['f_date'] != '' && $_POST['t_date']!= '') {
        $date_conn=" and m.do_date between '".$_POST['f_date']."' and '".$_POST['t_date']."'";
    } else {
        $date_conn=" and 1";
    }} else {
        $status_conn = " and m.status in ('MANUAL','RETURNED','PROCESSING')";}
 ?>

 <?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
         function DoNavPOPUP(lk,lkg)
         {myWindow = window.open("<?=$target_url;?>?<?=$unique_master;?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=570,left = 280,top = -1");}
     </script>
 <?php require_once 'body_content.php'; ?>


 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
 <table align="center" style="width: 50%;">
     <tr><td>
             <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date"  >
         <td style="width:10px; text-align:center"> -</td>
         <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date" ></td>
         <td style="padding:10px"><button type="submit" style="font-size: 11px;" name="viewreport"  class="btn btn-primary">View Order</button></td>
     </tr></table>
 </form>


<?php
if(isset($_POST[viewreport])){
$res = "select m.do_no,m.do_no,m.do_date,concat(d.dealer_code,' - ',d.dealer_name_e) as dealer_name,w.warehouse_name as 'Warehouse',
concat(u.fname,'<br> at: ',m.entry_at) as Invoice_By,
SUM(dt.total_amt)	 as Order_Amount,m.remarks,m.sent_to_warehuse_at as sent_warehouse,m.status
from
sale_do_master m,
dealer_info d ,
sale_do_details dt,
user_activity_management u,
warehouse w
where
m.dealer_code=d.dealer_code and
m.do_no=dt.do_no  and
m.depot_id=w.warehouse_id and
m.entry_by=u.user_id ".$date_conn.$status_conn."
group by m.do_no order by m.do_no desc"; } else {
$res = "select m.do_no,m.do_no,m.do_date,concat(d.dealer_code,' - ',d.dealer_name_e) as dealer_name,w.warehouse_name as 'Warehouse',
concat(u.fname,'<br> at: ',m.entry_at) as Invoice_By,
(SELECT concat('DO :',do_no,' Date :',do_date,'<br>DO Status: ',status) from sale_do_master where dealer_code=d.dealer_code and do_no<m.do_no order by do_no desc limit 1) as last_invoice_status,
(SELECT SUM(cr_amt-dr_amt) from journal where ledger_id=d.account_code) as ledger_balance,
concat(d.credit_limit,'<br> validation: ', d.credit_limit_time) as credit_limit,SUM(dt.total_amt)	 as Order_Amount,m.remarks,m.status
from
sale_do_master m,
dealer_info d ,
sale_do_details dt,
user_activity_management u,
warehouse w
where
m.dealer_code=d.dealer_code and
m.do_no=dt.do_no  and
m.depot_id=w.warehouse_id and
m.entry_by=u.user_id ".$date_conn.$status_conn."
group by m.do_no order by m.do_no desc";
}
    ?>
<?=$crud->report_templates_with_status($res,$title);?>
<?=$html->footer_content();?>
