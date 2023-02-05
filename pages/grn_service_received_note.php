<?php
require_once 'support_file.php';
$title='Service Receive';
$unique='custom_grn_no';
$unique_field='rcv_Date';
$table_master="purchase_receive_master";
$table_details="grn_service_receive";
$table_journal_info="journal_info";
$journal_info_unique='journal_info_no';
$page="grn_service_received_note.php";
$crud      =new crud($table_master);
$$unique = $_POST[$unique];
$config_group_class=find_all_field("config_group_class","","1");
$create_date=date('Y-m-d');




if(prevent_multi_submit()) {
    if(isset($_POST[$unique]))
    {
        if (isset($_POST['initiate'])) {
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['ip'] = $ip;
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:s:i');
            $_SESSION[custom_grn_no] = $_POST[$unique];
            $_POST['journal_type'] = 'Journal_info';
            $_POST['status'] = 'MANUAL';
			$_POST['grn_inventory_type']='Service';
            $crud->insert();
            unset($_POST);
        }

//for modify PS information ...........................
        if (isset($_POST['modify'])) {
            $_POST['edit_at'] = time();
            $_POST['edit_by'] = $_SESSION['userid'];
            $crud->update($unique);
            $type = 1;
            unset($_POST);
        }
		
		 if (isset($_POST['add'])) {
        $_POST['item_id'] = $_POST['item_id'];
        $_POST['unit_name'] = find_a_field('item_info', 'unit_name', 'item_id=' . $_POST['item_id']);
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_at'] = date('Y-m-d h:s:i');
        $_POST['edit_by'] = $_SESSION['userid'];
        $_POST['edit_at'] = date('Y-m-d h:s:i');
		$_POST['amount'] = $_POST['rate']*$_POST['qty'];
        $crud = new crud($table_details);
        $crud->insert();
    }
	
	} // end post unique
} // end prevent_multi_submit


// data query..................................
$condition=$unique."=".$_SESSION['custom_grn_no'];
    $data=db_fetch_object($table_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}

$vendor_master=find_all_field('vendor','','vendor_id='.$vendor_id.'');


//for single FG Delete..................................
if($_SESSION['custom_grn_no']>0){ 
$rs="select td.id,td.service_details,concat(m.monthfullName,', ',td.year) as month,td.documents_list,rate as Monthly_Charge,td.qty as 'No. of months',td.amount as total_amount		
from 
".$table_details." td,
monthname m
  where
  
 td.".$unique."='".$_SESSION['custom_grn_no']."' and 
 m.id=td.month";
$re_query=mysqli_query($conn, $rs);
while($uncheckrow=mysqli_fetch_array($re_query)){
    $ids=$uncheckrow[id];
	$total_amount=$total_amount+$uncheckrow[total_amount];
    if(isset($_POST['deletedata'.$ids]))
    {  mysqli_query($conn, ("DELETE FROM ".$table_details." WHERE id='".$ids."'"));
        unset($_POST);
    }
    if(isset($_POST['editdata'.$ids]))
    {  mysqli_query($conn, ("UPDATE ".$table_details." SET service_details='".$_POST[service_details]."', month='".$_POST[month]."',documents_list='".$_POST[documents_list]."',rate='".$_POST[rate]."',qty='".$_POST[qty]."',amount='".$_POST[qty]*$_POST[rate]."' WHERE id=".$ids));
        unset($_POST);
    }}

if (isset($_POST['confirm'])) {
    
	$jv=$_SESSION['userid'].$_SESSION['custom_grn_no']; 
        $get_tax_ait=$tax_ait;
        $pr_amt  = $total_amount;

        if($_POST[legderid]>0){
            $purchase_ledger=$_POST[legderid]; } else {
            $purchase_ledger = $config_group_class->purchase_ledger;
        }
        if($tax_ait>0){
            $tax_ait_amount=($total_amount*$tax_ait)/100;
            $tax_ait_ledger="2004000500000000";
        }

        if($tax<15){
            $tax_ledgercr =   '2004000200000000';
            $tax_amtcr = (($total_amount*$tax)/100);
        }else{
            $tax_ledger =   '1005000400000000';
            $tax_amt = (($total_amount*$tax)/100);
        }
        if($_POST[other_cost_accounts_head]>0){
            $others_costsss=$_POST['others_costsss'];
            $other_cost_accounts_head=$_POST['other_cost_accounts_head'];
        }
		$vendor_ledger=$vendor_master->ledger_id;			
		$nerration='SRN#'.$_SESSION['custom_grn_no'].'), Chalan / Invoice No # '.$ch_no.',Remarks # '. $Remarks.', Service Teken for # '.$advertisers.' ';
		
	
	/////////////// purchase amount calculations
	$pr_amt = $pr_amt+$asf_amt;
    if($tax_amt>0){
        $purchaseamt=$pr_amt;
    }
    elseif($tax_amtcr>0){
        $purchaseamt=$pr_amt+$tax_amtcr; }
    else
    { $purchaseamt=$pr_amt; } 
	if($tax_amt>0){
        $vendoramount=$pr_amt+$tax_amt+$tax_ait; }
    elseif($tax_amtcr>0){
        $vendoramount=$pr_amt+$tax_ait;
    } else {$vendoramount=$pr_amt;}

	if($purchaseamt>0){
		insert_into_secondary_journal($rcv_Date, $proj_id, $jv, $date, $purchase_ledger, $nerration, $purchaseamt, 0, Service, $pr_no, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Service);
		if($tax_ait_amount>0){
		$nerration_tax=$nerration.', Tax # '.$get_tax_ait.' %';	
		insert_into_secondary_journal($rcv_Date, $proj_id, $jv, $date, $tax_ait_ledger, $nerration_tax, $tax_ait, 0, Service, $pr_no, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Service);			
		}
		if($tax_amt>0){
		$nerration_VAT=$nerration.', VAT # '.$tax.'%, VAT Date # '.$VAT_challan_Date;
		insert_into_secondary_journal($rcv_Date, $proj_id, $jv, $date, $tax_ledger, $nerration_VAT, $tax_amt, 0, Service, $pr_no, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Service);			
		}
		if($tax_amtcr>0){
			$nerration_VAT=$nerration.', VAT # '.$tax.'%, VAT Date # '.$VAT_challan_Date;
		 insert_into_secondary_journal($rcv_Date, $proj_id, $jv, $date, $tax_ledgercr, $nerration_VAT, 0, $tax_amtcr, Service, $pr_no, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Service);	
		}
        insert_into_secondary_journal($rcv_Date, $proj_id, $jv, $date, $vendor_ledger, $nerration, 0, $vendoramount, Service, $pr_no, $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear,Service);
		}
	
	$up_journal1="UPDATE ".$table_details." SET status='UNCHECKED' where ".$unique."=".$_SESSION['custom_grn_no']."";
	$up_query1=mysqli_query($conn, $up_journal1);
	$up_journal="UPDATE ".$table_master." SET status='UNCHECKED' where ".$unique."=".$_SESSION['custom_grn_no']."";
    $up_query=mysqli_query($conn, $up_journal);
    unset($_SESSION['custom_grn_no']);
    unset($_POST);
    unset($$unique);
	header("Location: ".$page."");
}


if (isset($_GET[id])) {
    $edit_value=find_all_field(''.$table_details.'','','id='.$_GET[id].'');
}
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$_SESSION['custom_grn_no'].'');

//for Delete..................................
if (isset($_POST['cancel'])) {
    $crud = new crud($table_journal_info);
    $condition =$journal_info_unique."=".$_SESSION['custom_grn_no'];
    $crud->delete_all($condition);
    $crud = new crud($table_master);
    $condition=$unique."=".$_SESSION['custom_grn_no'];
    $crud->delete($condition);
    unset($_SESSION['custom_grn_no']);
    unset($_POST);
    unset($$unique);
	header("Location: ".$page."");
}

}

$sql2="select prm.custom_grn_no,prm.ch_no as invoice,v.vendor_name from purchase_receive_master prm,vendor v where prm.vendor_id=v.vendor_id and prm.grn_inventory_type in ('Service') and prm.status not in ('MANUAL')  group by prm.custom_grn_no  order by prm.custom_grn_no desc limit 10";


?>

<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]:focus {
        background-color: lightblue;
    }
</style>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("grn_service_received_view.php?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>

<div class="col-md-8 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page;?>" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
            <?php require_once 'support_html.php'?>
            <table align="center" style="width:100%">
                    <tr>
                        <th style="width:15%;">Receive Date<span class="required">*</span></th><th style="width: 2%;">:</th>
                        <td><input type="date" id="rcv_Date"  required="required" name="rcv_Date" value="<?=($rcv_Date!='')? $rcv_Date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" ></td>

                        <th style="width:15%;">Chalan / Invoice<span class="required">*</span></th><th style="width: 2%">:</th>
                        <td><input type="hidden" name="warehouse_id" id="warehouse_id"  value="11" />
                        <input type="hidden" required="required" name="custom_grn_no" id="custom_grn_no"  value="<?=($_SESSION['custom_grn_no']!='')? $_SESSION['custom_grn_no'] : automatic_number_generate("","purchase_receive_master","custom_grn_no","rcv_Date='".date('Y-m-d')."'"); ?>" class="form-control col-md-7 col-xs-12" readonly style="width: 90%; font-size: 11px;">
                        <input type="text" required="required" name="ch_no" id="ch_no"  value="<?=$ch_no;?>" class="form-control col-md-7 col-xs-12"  style="width: 90%; font-size: 11px;">
                        </td>
                    </tr>
                    
                    <tr>
                        <th style="">VAT Challan No</th><th>:</th>
                        <td><input type="text" id="VAT_challan"   value="<?=$VAT_challan;?>" name="VAT_challan"  class="form-control col-md-7 col-xs-12"  style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" /></td>

                        <th>V.C Date</th><th>:</th>
                        <td><input type="date" id="VAT_challan_Date"   value="<?=$VAT_challan_Date;?>" name="VAT_challan_Date"  class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" /></td>
                    </tr>

                    <tr>
                        <th style="">VAT</th><th>:</th>
                        <td><input type="number" id="tax" value="<?=$tax;?>" name="tax"  class="form-control col-md-7 col-xs-12"  style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" step="any"   placeholder="%" /></td>

                        <th>TAX</th><th>:</th>
                        <td><input type="number" id="tax_ait" value="<?=$tax_ait;?>" name="tax_ait"  class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" step="any"  placeholder="%" /></td>
                    </tr>
                    <tr><td style="height:2px"></td></tr>
                    
                    <tr>
                        <th style="">Payment Trems</th><th>:</th>
                        <td><input type="text" id="payments_terms"   value="<?=$payments_terms;?>" name="payments_terms"  class="form-control col-md-7 col-xs-12"  style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" /></td>

                        <th>Advertiser</th><th>:</th>
                        <td><input type="text" id="advertisers"   value="<?=$advertisers;?>" name="advertisers"  class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px; height: 38px; font-size: 11px; vertical-align: middle" /></td>
                    </tr>
<tr><td style="height:5px"></td></tr>
                    <tr>
                        <th style="">Vendor</th><th>:</th>
                        <td><select class="select2_single form-control" style="width:90%; font-size: 11px;" tabindex="-1" required="required"  name="vendor_id" id="vendor_id">
                                    <option></option>
                                    <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $vendor_id, 'status="ACTIVE"'); ?>
                                </select></td>

                        <th>Remarks</th><th>:</th>
                        <td><input type="text" id="Remarks"   value="<?=$Remarks;?>" name="Remarks"  class="form-control col-md-7 col-xs-12"  style="width: 90%; margin-top: 5px; font-size: 11px; vertical-align: middle"></td>
                    </tr>
                </table>

                <?php if($_SESSION[custom_grn_no]){
                    if($COUNT_details_data>0) {
                        $ml='40';
                        $display='style="margin-left:40%; margin-top: 22px;"';

                    } else {
                        $ml='40';
                        $display='style="margin-left:40%; margin-top: 15px; display: none"';
                    }
                    ?>
                    <div class="form-group" style="margin-left:<?=$ml;?>%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 11px">Update Service Receive</button>
                        </div></div>

                    <div class="form-group" <?=$display;?>>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <a  href="grn_service_received_view.php?custom_grn_no=<?=$_SESSION[custom_grn_no];?>" target="_blank" style="color: blue; text-decoration: underline; font-size: 11px; font-weight: bold; vertical-align: middle">View Service Receive Copy</a>
                        </div></div>
                <?php   } else {?>
                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="initiate" class="btn btn-primary" style="font-size: 11px">Initiate Service Receive</button>
                        </div></div>
                <?php } ?>

            </form></div></div></div>


<?=recentdataview($sql2,'grn_service_received_view.php','purchase_receive_master','263px','Recent SRNs','grn_service_received_view.php','4');?>
<?php if($_SESSION[custom_grn_no]):  ?>
    <form action="<?=$page;?>" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="<?=$unique;?>" id="<?=$unique?>" value="<?=$_SESSION[custom_grn_no];?>">
        <input type="hidden" name="rcv_Date" id="rcv_Date" value="<?=$rcv_Date;?>">
        <input type="hidden" name="ch_no" id="ch_no" value="<?=$ch_no;?>">
        <input type="hidden" name="tax" id="tax" value="<?=$tax;?>">
        <input type="hidden" name="tax_ait" id="tax_ait" value="<?=$tax_ait;?>">
        <input type="hidden" name="vendor_id" id="vendor_id" value="<?=$vendor_id;?>">
        <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse_id;?>">
        
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tbody>
            <tr style="background-color: bisque">
                <th style="text-align: center; vertical-align:middle">Service Details</th>
                <th style="text-align: center; vertical-align:middle">Month</th>
                <th style="text-align: center; vertical-align:middle">Attachment</th>                
                <th style=" text-align:center; vertical-align:middle">Documents List</th>
                <th style="text-align: center; vertical-align:middle">Monthly Charge</th>
                <th style="text-align: center; vertical-align:middle">No. of months</th>
                <th style="text-align:center; vertical-align:middle">Action</th>
            </tr>
            <tbody>
            <tr>
                <td style="width: 25%; vertical-align: middle" align="center">
                <input type="hidden" name="item_id" id="item_id" value="1600020003">
                <textarea id="service_details" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="service_details" class="form-control col-md-7 col-xs-12" required autocomplete="off"><?=$edit_value->service_details;?></textarea></td>
                <td align="center" style="width: 10%;vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="month" id="month">
                        <option></option>
                        <?php foreign_relation('monthname', 'month', 'CONCAT(month," : ", monthfullName)',''.$edit_value->month.'', ''); ?>
                    </select>
                    <input type="hidden" name="year" id="year" value="<?=date('Y');?>">
                    </td>

                <td style="width:10%;vertical-align: middle" align="center">
                    <input type="file" id="attachment" style="width:100%; height:37px; font-size: 11px; text-align:center"    name="attachment" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                    <td align="center" style="width:10%;vertical-align: middle"><textarea  id="documents_list" style="width:98%; font-size: 11px; text-align:center"  name="documents_list" class="form-control col-md-7 col-xs-12"  autocomplete="off"><?=$edit_value->documents_list;?></textarea></td>
                    
                     <td style="width:10%;vertical-align: middle" align="center">
                      <input type="number" id="rate" style="width:98%;; font-size: 11px; text-align:center"  value="<?=$edit_value->rate;?>" name="rate" class="form-control col-md-7 col-xs-12" autocomplete="off" step="any" min="1" /></td>
                <td align="center" style="width:10%; vertical-align: middle"><input type="number" id="qty" style="width:98%; font-size: 11px; text-align:center"  name="qty" class="form-control col-md-7 col-xs-12" value="<?=$edit_value->qty;?>" autocomplete="off" step="any" min="1" /></td>
                
                

                <td align="center" style="width:5%; vertical-align: middle "><?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you wanna cancel?");' class="btn btn-danger">Cancel</a>
                    <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
            </tbody>
        </table>
        <input name="count" id="count" type="hidden" value="" />
    </form>


    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

<?=added_data_delete_edit($rs,$unique,$_SESSION['custom_grn_no'],$COUNT_details_data);?><br><br>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>