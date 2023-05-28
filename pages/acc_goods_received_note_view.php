<?php
require_once 'support_file.php';
$title='GRN Checked';
$page='purchase_sec_print_view.php';
$unique='jv_no';

if(isset($_POST['viewreport'])){
	$sql = "SELECT DISTINCT 
                  j.jv_no,
				  r.po_no as PO,
				  j.tr_no as GRN,	
				  r.rcv_Date as Date,			  
				  v.vendor_name as Vendor,
				  r.ch_no as Challan,
				  l.ledger_name as 'Goods/Services Ledger',
				  u.fname as GRN_By,
				  j.dr_amt as amount,
				  j.checked as status

				FROM
					
				  secondary_journal j,
				  accounts_ledger l,
				  purchase_receive r,
				  warehouse w,
				  users u,
				  vendor v

				WHERE 
				
				  checked!='NO' and 
				  w.warehouse_id=r.warehouse_id AND
				  j.tr_no = r.pr_no AND
				  j.tr_from = 'Purchase' AND 
				  j.user_id = u.user_id AND
				  j.jv_date between '" . strtotime($_POST['f_date']) . "' AND  '" . strtotime($_POST['t_date']) . "' AND 
                  v.vendor_id=r.vendor_id AND
				  j.ledger_id = l.ledger_id group by j.jv_no order by j.tr_no desc";
                        } else {
                            $sql = "SELECT DISTINCT 
                  j.jv_no,
				  r.po_no as PO,
				  j.tr_no as GRN,
				  r.rcv_Date as Date,
				  v.vendor_name as Vendor,				  
				  r.ch_no as Challan,
				  l.ledger_name as 'Goods/Services Ledger',
				  u.fname as GRN_By,
				  j.dr_amt as amount,
				  j.checked as status

				FROM
					
				  secondary_journal j,
				  accounts_ledger l,
				  purchase_receive r,
				  warehouse w,
				  users u,
				  vendor v

				WHERE 
				
				  w.warehouse_id=r.warehouse_id AND
				  j.tr_no = r.pr_no AND
				  j.tr_from = 'Purchase' AND 
				  j.user_id = u.user_id AND
				  j.checked ='PENDING' AND 
                  v.vendor_id=r.vendor_id AND
				  j.ledger_id = l.ledger_id group by j.jv_no order by j.tr_no desc";
                           
                        }
?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
</script>

<script type="text/javascript">
    function DoNavPOPUPs(lk)
    {myWindow = window.open("service_sec_print_view.php?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>





    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
        <table align="center" style="width: 50%;">
            <tr><td><input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?=($_POST['f_date']!='')? $_POST['f_date'] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?=($_POST['t_date']!='')? $_POST['t_date'] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td>
                    <select name="checked" id="checked" class="form-control col-md-7 col-xs-12" style="width:auto; font-size:11px; height:25px">
                        <option value=""> Status</option>
                        <option value="PENDING" <?=($_POST['checked']=='PENDING')?'Selected':'';?>>PENDING</option>
                        <option value="YES" <?=($_POST['checked']=='YES')?'Selected':'';?>>YES</option>
                    </select>
                </td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Goods / Services Received</button></td>
            </tr></table>
                
<?=$crud->report_templates_with_status($sql);?>
<?=$html->footer_content();?>