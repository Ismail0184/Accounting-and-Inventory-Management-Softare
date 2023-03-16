<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");
$title='Good Received Note (GRN)';
$now=time();
$unique='pr_no';
$unique_field='name';
$table="purchase_receive";
$table_secondary_journal="secondary_journal";
$lc_lc_received_batch_split="lc_lc_received_batch_split";
$journal_item="journal_item";
$sj_unique='tr_no';
$page='QC_good_received_note.php';
$page_worksheet='inspection_print_view_pr.php';
$page_inspection_sheet='Inspection_Work_Sheet.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$condition="create_date='".date('Y-m-d')."'";


if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>self.opener.location = '".$page."'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $sql="Select * from lc_lc_received_batch_split where ".$unique."=".$_GET[$unique]." and source in ('PO')";
        $res=mysqli_query($conn, $sql);
        while($data=mysqli_fetch_object($res)){
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $data->item_id;
            $_POST['warehouse_id'] = $data->warehouse_id;
            $_POST['item_in'] = $data->qty;
            $_POST['item_price'] = $data->rate;
            $_POST['total_amt'] = $data->qty*$data->rate;;
            $_POST['tr_from'] = 'Purchase';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $_GET[$unique];
            $_POST['batch'] = $data->batch;
            $_POST['expiry_date'] = $data->mfg;
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:i:s');
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();   // inventory received
        }
	    	
        if(isset($_GET[custom_grn_no])){
        $up_master=mysqli_query($conn,"UPDATE purchase_receive_master SET status='CHECKED',checked_by='".$_SESSION[userid]."',checked_at='".$todayss."' where custom_grn_no=".$_GET[custom_grn_no]."");
		$up_master=mysqli_query($conn,"UPDATE grn_service_receive SET status='CHECKED',qc_by='".$_SESSION[userid]."',QC_at='".$todayss."' where custom_grn_no=".$_GET[custom_grn_no]."");
        $up_details=mysqli_query($conn,"UPDATE ".$table_secondary_journal." SET checked='PENDING',QC_by='$_SESSION[userid]',QC_at='$todayss' where ".$sj_unique."=".$$unique." and tr_from in ('Purchase')");
		} else 
		{
        $up_master=mysqli_query($conn,"UPDATE ".$table." SET status='CHECKED',batch_split_status='CHECKED',qc_by='$_SESSION[userid]',QC_at='$todayss' where ".$unique."=".$$unique."");
        $up_details=mysqli_query($conn,"UPDATE ".$table_secondary_journal." SET checked='PENDING',QC_by='$_SESSION[userid]',QC_at='$todayss' where ".$sj_unique."=".$$unique." and tr_from in ('Purchase')");
			}
        unset($_POST);
        echo "<script>self.opener.location = '".$page."'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    if(isset($_POST['record_batch_split']))
    {
        $condition="create_date='".date('Y-m-d')."'";
        $_POST['warehouse_id'] = $_POST[warehouse_id]; 
        $_POST['po_no'] = $_POST[po_no];
        $_POST['pr_no'] = $_GET[pr_no];
        $_POST['lc_id'] = $po_no;
        $_POST['item_id'] = $_GET[item_id];
        $_POST['line_id'] = $_GET[line_id];
        $_POST['source'] = 'PO';
        $_POST['status'] = 'PROCESSING';
        $_POST['create_date'] = date('Y-m-d');
    
        $_POST[ip]=$ip;
        $_POST[entry_at] = date('Y-m-d H:s:i');
        $crud      =new crud($lc_lc_received_batch_split);
        if($_POST[qty_1]>0) {
            $_POST[qty]=$_POST[qty_1];
            $_POST[batch_no]=$_POST[batch_1];
            $_POST[rate]=$_POST[rate_1];
            $_POST[mfg]=$_POST[exp_date_1];
            $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
            $crud->insert();
        }if($_POST[qty_2]>0) {
        $_POST[qty]=$_POST[qty_2];
        $_POST[rate]=$_POST[rate_2];
        $_POST[batch_no]=$_POST[batch_2];
        $_POST[mfg]=$_POST[exp_date_2];
        $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
        $crud->insert();
    }if($_POST[qty_3]>0) {
        $_POST[qty]=$_POST[qty_3];
        $_POST[rate]=$_POST[rate_3];
        $_POST[batch_no]=$_POST[batch_3];
        $_POST[mfg]=$_POST[exp_date_3];
        $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
        $crud->insert();
    }if($_POST[qty_4]>0) {
        $_POST[qty]=$_POST[qty_4];
        $_POST[rate]=$_POST[rate_4];
        $_POST[batch_no]=$_POST[batch_4];
        $_POST[mfg]=$_POST[exp_date_4];
        $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
        $crud->insert();
    }if($_POST[qty_5]>0) {
        $_POST[qty]=$_POST[qty_5];
        $_POST[rate]=$_POST[rate_5];
        $_POST[batch_no]=$_POST[batch_5];
        $_POST[mfg]=$_POST[exp_date_5];
        $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
        $crud->insert();
    }
        $update=mysqli_query($conn, "Update purchase_receive set batch_split_status='CHECKED' where ".$unique."=".$_GET[$unique]." and id=".$_GET[line_id]." and item_id=".$_GET[item_id]."");
        echo "<script>self.opener.location = '$page?".$unique."=$_GET[$unique]'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_deatils);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);
        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($_SESSION['ps_id']);
        unset($_SESSION['pi_id']);
        unset($_SESSION['initiate_daily_production']);
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

if(isset($_POST[viewreport])) {
    $resultss = "Select g.pr_no,g.pr_no as GRN_NO,g.rcv_Date as 'GRN_date',g.po_no as 'PO No',w.warehouse_name as 'Warehouse / CMU',v.vendor_name,(select concat(id,' : ',MAN_ID) from MAN_master where MAN_ID=g.MAN_ID) as 'MAN ID',FORMAT(SUM(g.amount),2) as 'GRN_amount',concat(u.fname,'<br>','at: ', g.entry_at) as GRN_by,g.status
from 
" . $table . " g,
warehouse w,
users u,
vendor v
where
g.entry_by=u.user_id and 
w.warehouse_id=g.warehouse_id and  
v.vendor_id=g.vendor_id and 
g.status not in ('UNCHECKED','MANUAL') and
g.rcv_Date between '".$_POST['f_date']."' and '".$_POST['t_date']."'
 group by g.pr_no
order by g." . $unique . " DESC ";
} else {
    $resultss = "Select g.pr_no,g.pr_no as GRN_NO,g.rcv_Date as 'GRN_date',g.po_no as 'PO No',w.warehouse_name as 'Warehouse / CMU',v.vendor_name,(select concat(id,' : ',MAN_ID) from MAN_master where MAN_ID=g.MAN_ID) as 'MAN ID',FORMAT(SUM(g.amount),2) as 'GRN_amount',concat(u.fname,'<br>','at: ', g.entry_at) as GRN_by,g.status
from 
" . $table . " g,
warehouse w,
users u,
vendor v
where
g.entry_by=u.user_id and 
w.warehouse_id=g.warehouse_id and  
v.vendor_id=g.vendor_id and 
g.status in ('UNCHECKED','MANUAL') group by g.pr_no
order by g." . $unique . " DESC ";
}
$pquery=mysqli_query($conn, $resultss);
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 260,top = -1");}
    </script>
    <script type="text/javascript">
        function DoNavPOPUPs(lk)
        {myWindow = window.open("<?=$page?>?custom_grn_no="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 260,top = -1");}
    </script>
    <script type="text/javascript">
        function OpenPopupCenter(pageURL, title, w, h) {
            var left = (screen.width - w) / 2;
            var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
            var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }</script>
<style>
td{
   vertical-align: middle;
}
</style>
<?php if(isset($_GET[$unique])):
    require_once 'body_content_without_menu.php'; else :
    require_once 'body_content.php'; endif;  ?>

<?php if(isset($_GET[custom_grn_no])){ ?>
<?php $prm=find_all_field('purchase_receive_master','','custom_grn_no='.$_GET[custom_grn_no].''); ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>Service Description</th>
                            <th style="width:15%; text-align:center">Month</th>
                            <th style="text-align:center;">No. of months</th>
                            <th style="text-align:center; ">Monthly Charge</th>
                            <th style="text-align:center;">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $results="select b.*,concat(m.monthfullName,', ',b.year) as monthfullName from grn_service_receive b,monthname m where b.custom_grn_no = '".$_GET[custom_grn_no]."' and m.id=b.month";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$i;?></td>
                                <td style="vertical-align:middle"><?=$row[service_details];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[monthfullName];?></td>
                                <td align="center" style=" text-align:center"><?=number_format($row[qty]); ?></td>
                                <td align="center" style=" text-align:center"><?=$row[rate]; ?></td>
                                <td align="center" style="text-align:right"><?=number_format($row[amount],2);?></td>

                            </tr>
                            <?php 
                            $ttotal_amt=$ttotal_amt+$row[amount];  } ?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="5" style="font-weight:bold; font-size:11px" align="right">Total Amount = </td>
                            <td align="right" ><?=number_format($ttotal_amt,2);?></td>
                        </tr>
                        <?php if($prm->tax>0): ?>
                        <tr style="font-weight: bold">
                            <td colspan="5" style="font-weight:bold; font-size:11px" align="right">VAT (<?=$prm->tax?>%) = </td>
                            <td align="right" ><?=number_format($VAT_amount=$ttotal_amt*$prm->tax/100,2);?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($prm->tax>0): ?>
                        <tr style="font-weight: bold">
                            <td colspan="5" style="font-weight:bold; font-size:11px" align="right">TAX = </td>
                            <td align="right" ><?=number_format($TAX_amount=$ttotal_amt*$prm->tax_ait/100,2);?></td>
                        </tr>
                        <?php endif; ?>
                        <tr style="font-weight: bold">
                            <td colspan="5" style="font-weight:bold; font-size:11px" align="right">Total Service Received in Value = </td>
                            <td align="right" ><?=number_format($ttotal_amt+$VAT_amount+$TAX_amount,2);?></td>
                        </tr>
                    </table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED' || $GET_status=='MANUAL'){  ?>
                        <p>
                            <button style="float: left; font-size: 12px; margin-left: 1%" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right;font-size: 12px; margin-right: 1%" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward to Accounts</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This GEN has been checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(isset($_GET[$unique]) && !isset($_GET[item_id])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <input type="hidden" name="po_no" value="<?=$po_no?>">
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>Code</th>
                            <th>Material Description</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center; width: 10%">GRN Qty</th>
                            <th style="text-align:center; width: 10%">Unit Price</th>
                            <th style="text-align:center; width: 10%">Amount</th>
                            <th style="text-align:center; width: 10%">Status</th>
                            <th style="text-align:center;">Worksheet</th>
                            <th style="text-align:center;">Inspection Sheet</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php
                        $results="Select g.*,i.* from ".$table." g, item_info i  where
 g.item_id=i.item_id and 
 g.".$unique."=".$$unique." group by g.id,g.item_id order by g.id ";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr style="cursor:pointer" >
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle;" onclick='OpenPopupCenter("<?=$page?>?<?=$unique?>=<?=$_GET[$unique]?>&item_id=<?=$row['item_id']?>&line_id=<?=$row['id']?>", "TEST!?", 850, 600)'><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td style=" text-align:right;vertical-align:middle;"><?=$row[qty]; ?></td>
                                <td style=" text-align:center;vertical-align:middle;"><?=$row[rate]; ?></td>
                                <td style="text-align:right;vertical-align:middle;"><?=number_format($row[amount],2);?></td>
                                <td style="text-align:center;vertical-align:middle;"><?=($row[batch_split_status]=='CHECKED')? '<span class="label label-success" style="font-size:10px">CHECKED</span>' : '<span class="label label-default" style="font-size:10px">UNCHECKED</span>'?></td>
                                <td style="text-align:center;vertical-align:middle;" onclick='OpenPopupCenter("<?=$page_worksheet?>?item_id=<?=$row['item_id']?>&pr_no=<?=$row['pr_no']?>&id=<?=$row['id']?>", "TEST!?", 850, 600)'><img src="../assets/images/icon/worksheet.png" height="25" width="25"></td>
                                <td style="text-align:center;vertical-align:middle;" onclick='OpenPopupCenter("<?=$page_inspection_sheet?>?item_id=<?=$row['item_id']?>&pr_no=<?=$row['pr_no']?>&id=<?=$row['id']?>", "TEST!?", 850, 600)'><img src="../assets/images/icon/inspection.png" height="25" width="25"></td>

                            </tr>
                            <?php  $ttotal_unit=$ttotal_unit+$row[total_unit];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $ttotal_amt=$ttotal_amt+$row[amount];  } ?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="6" style="font-weight:bold; font-size:11px" align="right">Total Good Received in Value = </td>
                            <td align="right" ><?=number_format($ttotal_amt,2);?></td>
                            <td></td>
                        </tr>
                    </table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED' || $GET_status=='MANUAL'){  ?>
                        <p>
                            <button style="float: left; font-size: 12px; margin-left: 1%" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right;font-size: 12px; margin-right: 1%" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward to Accounts</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This GEN has been '.$GET_status.' !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
<form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
    <table align="center" style="width: 50%;">
        <tr>
            <td>
                <input type="date"  style="width:150px; font-size: 11px;" max="<?=date('Y-m-d');?>"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" />
            </td>
            <td style="width:10px; text-align:center"></td>
            <td><input type="date"  style="width:150px;font-size: 11px;"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
            <td style="width:10px; text-align:center"></td>
            <td style="padding:10px"><button type="submit" style="font-size: 11px;" name="viewreport"  class="btn btn-primary">View LC Received</button></td>
        </tr>
    </table>
    <?=$crud->report_templates_with_status($resultss);?>
</form>





<?php 
$srn=find_a_field('purchase_receive_master','COUNT(custom_grn_no)','grn_inventory_type in ("Service") and status in ("UNCHECKED")');
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
                        $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                        $to_date=date('Y-m-d' , strtotime($_POST[t_date]));

                            $resultss="Select prm.*,u.fname,v.vendor_name,(select SUM(amount) from grn_service_receive where custom_grn_no=prm.custom_grn_no) as srn_amount
from 
purchase_receive_master prm,
users u,
vendor v

 where
  prm.entry_by=u.user_id and 
 v.vendor_id=prm.vendor_id and 
 prm.status in ('UNCHECKED','MANUAL') and prm.grn_inventory_type in ('Service') group by prm.custom_grn_no
  order by prm.custom_grn_no DESC ";
                            $pquery=mysqli_query($conn, $resultss);
                        while ($rows=mysqli_fetch_array($pquery)){
                            $is=$is+1;
                            ?>
                            <tr style="font-size:11px">
                                <th style="text-align:center; cursor: pointer" onclick="DoNavPOPUPs('<?=$rows[custom_grn_no];?>', 'TEST!?', 600, 700)"><?=$is;?></th>
                                <td onclick="DoNavPOPUPs('<?=$rows[custom_grn_no];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows[custom_grn_no];?></a></td>
                                <td onclick="DoNavPOPUPs('<?=$rows[custom_grn_no];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows[rcv_Date]; ?></td>
                                <td onclick="DoNavPOPUPs('<?=$rows[custom_grn_no];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows[vendor_name];?></td>
                                <td onclick="DoNavPOPUPs('<?=$rows[custom_grn_no];?>', 'TEST!?', 600, 700)" style="cursor: pointer; text-align:right"><?=number_format($rows[srn_amount],2);?></td>
                                <td><a href="http://icpbd-erp.com/51816/cmu_mod/page/dc_documents/<?=$rows[man_id];?>_dc.pdf" target="_blank" style="text-decoration: underline; color: blue"><?=$rows[ch_no];?></a></td>
                                <td><a href="http://icpbd-erp.com/51816/cmu_mod/page/vc_documents/<?=$rows[man_id];?>_vc.pdf" target="_blank" style="text-decoration: underline; color: blue"><?=$rows[VAT_challan];?></a></td>
                                <td onclick="DoNavPOPUPs('<?=$rows[custom_grn_no];?>', 'TEST!?', 600, 700)" style="cursor: pointer"><?=$rows[fname];?></td>
                                <td style="text-align:left;cursor: pointer" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$rows[entry_at];?></td>
                            </tr>
                        <?php } ?></tbody></table>

                </div></div></div></form>
<?php endif; } ?>


<?php if(isset($_GET[item_id]) && ($_GET[line_id])){  ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <input type="hidden" name="po_no" value="<?=$po_no?>" />
                    <input type="hidden" name="warehouse_id" value="<?=$warehouse_id?>" />
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th style="width: 1%">SL</th>
                            <th>Item Name</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:center">Rate</th>
                            <th style="text-align:center">Batch</th>
                            <th style="text-align:center">Exp. Date</th>
                        </tr>
                        </thead>
                        <?php
                        $item_status=find_a_field('purchase_receive','count(id)','item_id='.$_GET[item_id].' and id='.$_GET[line_id].' and batch_split_status in ("CHECKED") and pr_no='.$_GET[pr_no]);
                        $item_name=find_a_field('item_info','item_name','item_id='.$_GET[item_id]);
                        $rs="Select * from lc_lc_received_batch_split where ".$unique."=".$_GET[$unique]." and item_id=".$_GET[item_id]." and line_id=".$_GET[line_id]."";
                        $pdetails=mysqli_query($conn, $rs);
                        while($data=mysqli_fetch_object($pdetails)){
                            ?>
                            <tr>
                                <td style="vertical-align: middle"><?=$i=$i+1;?></td>
                                <td style="text-align:left; vertical-align: middle"><?=$item_name?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><?=$data->qty?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><?=$data->rate?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><?=$data->batch_no?>:<?=$data->batch?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><?=$data->mfg?></td>
                            </tr>
                        <?php } if($item_status>0){ echo ''; } else { ?>
                            <tr>
                                <td style="vertical-align: middle">1</td>
                                <td rowspan="5" style="text-align:left; vertical-align: middle"><?=$item_name?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_1" id="qty_1"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="rate_1" id="rate_1"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_1" id="batch_1"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_1" id="exp_date_1"></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle">2</td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_2" id="qty_2"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="rate_2" id="rate_2"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_2" id="batch_2"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_2" id="exp_date_2"></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle">3</td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_3" id="qty_3"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="rate_3" id="rate_3"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_3" id="batch_3"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_3" id="exp_date_3"></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle">4</td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_4" id="qty_4"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="rate_4" id="rate_4"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_4" id="batch_4"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_4" id="exp_date_4"></td>
                            </tr><tr>
                                <td style="vertical-align: middle">5</td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_5" id="qty_5"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="rate_5" id="rate_5"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_5" id="batch_5"></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_5" id="exp_date_5"></td>
                            </tr>
                        <?php } ?>
                        <tbody></tbody></table>
                    <?php if($item_status>0){?>
                        <h6 style="text-align: center; color: red; font-weight: bold"><i>THIS ITEM HAS BEEN CHECKED !!</i></h6>
                    <?php } else { ?>
                        <p><button style="float: right;font-size: 12px" type="submit" name="record_batch_split" id="record_batch_split" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Record</button></p>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?=$html->footer_content();mysqli_close($conn);?>