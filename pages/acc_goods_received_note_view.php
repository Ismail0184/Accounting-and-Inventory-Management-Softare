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
<?php 
$srn=find_a_field('purchase_receive_master','COUNT(custom_grn_no)','grn_inventory_type in ("Service") and status in ("CHECKED")');
if($srn>0): ?>
<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Service Received Note (SRN)</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="width: 2%">#</th>
                            <th style="">SRN NO</th>
                            <th style="">SEN Date</th>
                            <th style="">Vendor Name</th>
                            <th>SRN Amount</th>
                            <th>Challan No</th>
                            <th>VAT Challan</th>
                            <th style="">Entry By</th>
                            <th style="">Entry At</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $from_date=date('Y-m-d' , strtotime($_POST['f_date']));
                        $to_date=date('Y-m-d' , strtotime($_POST['t_date']));
                        $is = 0;
                        $resultss="Select prm.*,u.fname,v.vendor_name,(select SUM(amount) from grn_service_receive where custom_grn_no=prm.custom_grn_no) as srn_amount
from 
purchase_receive_master prm,
users u,
vendor v

 where
  prm.entry_by=u.user_id and 
 v.vendor_id=prm.vendor_id and 
 prm.status in ('CHECKED') and prm.grn_inventory_type in ('Service') group by prm.custom_grn_no
  order by prm.custom_grn_no DESC ";
                            $pquery=mysqli_query($conn, $resultss);
                        while ($rows=mysqli_fetch_array($pquery)){$is=$is+1;
                            ?>
                            <tr style="font-size:11px">
                                <th style="text-align:center; cursor: pointer" onclick="DoNavPOPUPs('<?=$rows['entry_by'].$rows['custom_grn_no'];?>', 'TEST!?', 600, 700)"><?=$is;?></th>
                                <td onclick="DoNavPOPUPs('<?=$rows['entry_by'].$rows['custom_grn_no'];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows['custom_grn_no'];?></a></td>
                                <td onclick="DoNavPOPUPs('<?=$rows['entry_by'].$rows['custom_grn_no'];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows['rcv_Date']; ?></td>
                                <td onclick="DoNavPOPUPs('<?=$rows['entry_by'].$rows['custom_grn_no'];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows['vendor_name'];?></td>
                                <td onclick="DoNavPOPUPs('<?=$rows['entry_by'].$rows['custom_grn_no'];?>', 'TEST!?', 600, 700)" style="cursor: pointer; text-align:right"><?=number_format($rows['srn_amount'],2);?></td>
                                <td><a href="http://icpbd-erp.com/51816/cmu_mod/page/dc_documents/<?=$rows['man_id'];?>_dc.pdf" target="_blank" style="text-decoration: underline; color: blue"><?=$rows['ch_no'];?></a></td>
                                <td><a href="http://icpbd-erp.com/51816/cmu_mod/page/vc_documents/<?=$rows['man_id'];?>_vc.pdf" target="_blank" style="text-decoration: underline; color: blue"><?=$rows['VAT_challan'];?></a></td>
                                <td onclick="DoNavPOPUPs('<?=$rows['entry_by'].$rows['custom_grn_no'];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows['fname'];?></td>
                                <td style="text-align:left;cursor: pointer" onclick="DoNavPOPUP('<?=$rows['entry_by'].$rows['custom_grn_no'];?>', 'TEST!?', 600, 700)"><?=$rows['entry_at'];?></td>
                            </tr>
                        <?php } ?></tbody></table>
                </div>
            </div>
</div>
    </form>
<?php  endif;?>
<?=$html->footer_content();?>