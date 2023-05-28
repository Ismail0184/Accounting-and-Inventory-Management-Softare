<?php require_once 'support_file.php';require_once 'report.class.php';?>
<?//=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');

$sekeyword='Agrani#WO#';
$create_date=('Y-m-d');
$title='Create Work Order';
$table_master='purchase_master';
$table_details='purchase_invoice';
$unique='po_no';
$page="po_create_item.php";
$crud = new crud($table_master);
$$unique = @$_SESSION['initiate_po_no'];

$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
    $sec_com_connection_wa=' and 1';
} else {
    $sec_com_connection=" and j.company_id='".$_SESSION['companyid']."' and j.section_id in ('400000','".$_SESSION['sectionid']."')";
    $sec_com_connection_wa=" and company_id='".$_SESSION['companyid']."' and section_id in ('400000','".$_SESSION['sectionid']."')";
}


if(prevent_multi_submit()){
    if (isset($_POST['new'])) {
        if (!isset($_SESSION['initiate_po_no'])) {
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d h:s:i');
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_SESSION['initiate_po_id'] = $_POST['po_id'];
            $_POST['create_date']=date('Y-m-d');
            $_POST['currency'] = 'BDT';
            $crud->insert();
            $_SESSION['initiate_po_no'] = find_a_field(''.$table_master.'',''.$unique.'','po_id="'.$_POST['po_id'].'"');
            $$unique = $_SESSION['initiate_po_no'];
            $_SESSION[$unique] = $_POST[$unique];
            $_SESSION['select_vendor_PO']=$_POST['vendor_id'];
            if ($_FILES['qoutationDoc']['tmp_name'] != '') {
                $file_temp = $_FILES['qoutationDoc']['tmp_name'];
                $folder = "../page/po_documents/qoutationDoc/";
                move_uploaded_file($file_temp, $folder . $$unique . ".pdf");
            }
            if ($_FILES['mailCommDoc']['tmp_name'] != '') {
                $file_temp = $_FILES['mailCommDoc']['tmp_name'];
                $folder = "../page/po_documents/mailCommDoc/";
                move_uploaded_file($file_temp, $folder . $$unique . ".pdf");
            }
            unset($$unique);
            $type = 1;
            $msg = 'Work Order No Created. (PO No :-' . $_SESSION['initiate_po_no'] . ')';

        } else {

            $_POST['edit_by'] = $_SESSION['userid'];
            $_POST['edit_at'] = date('Y-m-d h:s:i');
            $crud->update($unique);
            if ($_FILES['qoutationDoc']['tmp_name'] != '') {
                $file_temp = $_FILES['qoutationDoc']['tmp_name'];
                $folder = "../page/po_documents/qoutationDoc/";
                move_uploaded_file($file_temp, $folder . $_SESSION['initiate_po_no'] . ".pdf");
            }

            if ($_FILES['mailCommDoc']['tmp_name'] != '') {
                $file_temp = $_FILES['mailCommDoc']['tmp_name'];
                $folder = "../page/po_documents/mailCommDoc/";
                move_uploaded_file($file_temp, $folder . $_SESSION['initiate_po_no'] . ".pdf");
            }
            $type = 1;
            $msg = 'Successfully Updated.';
        }
    }

    $$unique = @$_SESSION[$unique] = @$_POST[$unique];
    $initiate_po_no = @$_SESSION['initiate_po_no'];
    if (@$_GET['del'] > 0) {
        $crud = new crud($table_details);
        $condition = "id=" . $_GET['del'];
        $crud->delete_all($condition);
        echo $targeturl;
        $type = 1;
        $msg = 'Successfully Deleted.';
    }

    if(isset($_POST["Import"])){
        $filename=$_FILES["file"]["tmp_name"];
        if($_FILES["file"]["size"] > 0)
        { $file = fopen($filename, "r");
            while (($eData = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                $entry_at = date('Y-m-d H:i:s');
                $item_id=find_a_field("item_info","item_id","item_name in ('".$eData[3]."')");
                $item_info=find_all_field("item_info","","item_id=".$item_id);

                $sql = "INSERT INTO `purchase_invoice`
   (`po_no`,`po_id`,`po_date`,`vendor_id`,`item_id`,`warehouse_id`,`rate`,`qty`,`amount`,`entry_by`,`entry_at`,`status`,`section_id`,`company_id`)
	         VALUES ('".$_POST['po_no']."','".$_POST['po_id']."','".$_POST['po_date']."','".$_POST['vendor_id']."','".$item_id."','".$_POST['warehouse_id']."','".$item_info->d_price."','$eData[5]','".$item_info->d_price*$eData[5]."','".$_SESSION['userid']."','".$entry_at."','MANUAL','".$sectionid."','".$_SESSION['companyid']."')";

                $delete_empty_data = mysqli_query($conn, "Delete from purchase_invoice where qty=0 ");

                $result = mysqli_query( $conn, $sql);
                if(! $result )
                {
                    echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
							window.location = ".$page."
						</script>";
                }}
            fclose($file);
            //throws a message if data successfully imported to mysql database from excel file
            echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = ".$page."
					</script>";
        }

        header("Location: ".$page."");
    }

    if (isset($_POST['add'])) {
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_at'] = date('Y-m-d h:s:i');
        $_POST['po_date'] = @$_POST['po_date'];
        $_POST['po_no'] = @$_POST['po_no'];

        $sql = mysqli_query($conn, "SELECT * from item_info where 1 order by serial");
        while($data=mysqli_fetch_object($sql)):
            $item_id = $data->item_id;
            $_POST['item_id'] = $data->item_id;
            $_POST['unit_name'] = $data->unit_name;
            $_POST['rate'] = $_POST['rate'.$item_id];
            $_POST['qty'] = $_POST['qty'.$item_id];
            $_POST['amount'] = $_POST['amount'.$item_id];
            if($_POST['rate']>0 && $_POST['qty']>0 && $_POST['amount']):
                $crud = new crud($table_details);
                $crud->insert();
            endif;
        endwhile;
    }}


if (isset($_POST['confirm'])) {
    unset($_POST);
    $_POST[$unique] = $_SESSION['initiate_po_no'];
    $_POST['entry_by'] = $_SESSION['userid'];
    $_POST['entry_at'] = date('Y-m-d h:s:i');
    $_POST['status'] = 'PROCESSING';
    $crud = new crud($table_master);
    $crud->update($unique);
    $chid = find_a_field('purchase_master', 'checkby', 'po_no=' . $_SESSION['initiate_po_no']);
    $maild = find_a_field('essential_info', 'ESS_CORPORATE_EMAIL', 'PBI_ID=' . $chid);

    if ($maild != '') {
        $to = $maild;
        $subject = "New Work order";
        $txt = "<p>Dear Sir/Madam,</p>
				<p>A new work order has been created. WO No is: <b>" . $_SESSION['initiate_po_no'] . "</b></p>
				<p>Your approval is required. Please enter the <b>Employee Access Module</b> to approve the work order.</p>				
				<p>Prepared By- <b>" . find_a_field('users', 'fname', 'user_id=' . $_SESSION['userid']) . "</b></p>
				<p><i>This EMAIL is automatically generated by ERP Software.</i></p>";

        $from = 'erp@icpbd.com';
        $headers = "";
        $headers .= "From: ERP Software<erp@icpbd.com> \r\n";
        $headers .= "Reply-To:" . $from . "\r\n" . "X-Mailer: PHP/" . phpversion();
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($to, $subject, $txt, $headers);
    }
    unset($_SESSION['initiate_pono']);
    unset($_SESSION['initiate_po_no']);
    unset($$unique);
    unset($_SESSION[$unique]);
    $type = 1;
    $msg = 'Successfully Forwarded for Approval.';
}


$initiate_po_no = @$_SESSION['initiate_po_no'];
$initiate_pono = @$_SESSION['initiate_pono'];
$initiate_po_id = @$_SESSION['initiate_po_id'];

//for single FG Delete..................................
$res='select a.id, concat(b.item_id," # ", b.item_name," # ",a.item_details) as item_description,a.unit_name as unit,a.rate as unit_price,a.qty,a.amount from purchase_invoice a,item_info b where b.item_id=a.item_id and a.po_no='.$initiate_po_no;
$results=mysqli_query($conn,$res);
while($data=@mysqli_fetch_object($results)){
    $id=$data->id;
    if(isset($_POST['deletedata'.$id]))
    {$del="DELETE FROM ".$table_details." WHERE id='".$id."'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);}
    if(isset($_POST['editdata'.$id]))
    {  mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST['item_id']."', item_details='".$_POST['item_details']."',qty='".$_POST['qty']."',rate='".$_POST['rate']."',amount='".$_POST['amount']."' WHERE id=".$id));
        unset($_POST);
    }
}

if (isset($_POST['cancel'])) {
    $initiate_po_no = @$_SESSION['initiate_po_no'];
    $crud = new crud($table_master);
    $condition = $unique . "=" . $initiate_po_no;
    $crud->delete($condition);
    $crud = new crud($table_details);
    $condition = $unique . "=" . $initiate_po_no;
    $crud->delete_all($condition);
    $qoutationDoc_delete = '../page/po_documents/qoutationDoc/'.$initiate_po_no.'.pdf';
    unlink($qoutationDoc_delete);
    $mailCommDoc_delete = '../page/po_documents/mailCommDoc/'.$initiate_po_no.'.pdf';
    unlink($mailCommDoc_delete);
    unset($_SESSION['initiate_po_id']);
    unset($_SESSION['initiate_po_no']);
    unset($initiate_po_no);
    unset($$unique);
    unset($_SESSION[$unique]);
    $type = 1;
    $msg = 'Successfully Deleted.';
}

$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name) FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  
							i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 i.status in ('Active') and 
							 i.product_nature in ('Both','Purchasable')					 
							  order by i.serial";

$sql_checked_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM							 
							personnel_basic_info p,
							department d,
							essential_info e
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and
							 p.PBI_ID=e.PBI_ID and 
							 e.ESS_JOB_LOCATION=1 group by p.PBI_ID							 
							  order by p.PBI_NAME";
$initiate_po_no = @$_SESSION['initiate_po_no'];
$initiate_pono = @$_SESSION['initiate_pono'];
$initiate_po_id = @$_SESSION['initiate_po_id'];
if($initiate_po_no>0):
    $condition=$unique."=".$initiate_po_no;
    $data=db_fetch_object($table_master,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}

    if (isset($_GET['id'])) {
        $edit_value = find_all_field('' . $table_details . '', '', 'id=' . $_GET['id'] . '');
    }
    $edit_value_item_id = @$edit_value->item_id;
    $edit_value_item_details = @$edit_value->item_details;
    $edit_value_qty = @$edit_value->qty;
    $edit_value_rate = @$edit_value->rate;
    $edit_value_amount = @$edit_value->amount;
    $edit_value_commission_amount = @$edit_value->commission_amount;

endif;

$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$initiate_po_no.'');
$initiate_po_no = @$initiate_po_no;
$vendor_id = @$vendor_id;
$pono = @$pono;
$warehouse_id = @$warehouse_id;
$po_date = @$po_date;
$quotation_date = @$quotation_date;
$delivery_within = @$delivery_within;
$commission = @$commission;
$transport_bill = @$transport_bill;
$labor_bill = @$labor_bill;
$tax = @$tax;
$tax_ait = @$tax_ait;
$asf = @$asf;
$po_details = @$po_details;
$qoutationDoc = @$qoutationDoc;
$mailCommDoc = @$mailCommDoc;
$checkby = @$checkby;
$recommended = @$recommended;
$authorise = @$authorise;
$po_id = @$po_id;
$return_comments = @$return_comments;

$vendor=find_all_field('vendor','','vendor_id='.$vendor_id.'');
$vendor_commission = @$vendor->commission;
if($initiate_po_no>0) $btn_name='Update WO Info'; else $btn_name='Initiate Work Order';
?>
<?php require_once 'header_content.php'; ?>
<style>
    input[type=text],input[type=file] {
        width: 80%;
    }
    input[type=text]{
        font-size: 11px;
        width: 80%;}

    input[type=file]{
        font-size: 11px;
        width: 80%;}
    .col-xs-12{
        font-size: 11px;
    }

    #customers {}
    #customers td {}
    #customers tr:ntd-child(even)
    {background-color: #f0f0f0;}
    #customers tr:hover {background-color: #f5f5f5;}
    td{}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content_nva_sm.php'; ?>
<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?><small>Bulk Item Add </small> <small class="text-danger">field marked with * are mandatory</small></h2>
            <a style="float: right" class="btn btn-sm btn-default"  href="po_create_item_multiple.php">
                <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Single Item Add</span>
            </a>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page;?>" enctype="multipart/form-data" class="form-horizontal form-label-left" method="post" name="codz" id="codz" onSubmit="if(!confirm('Are You Sure Execute this?')){return false;}">
                <table align="center" style="width:100%;font-size: 11px; padding:50px">
                    <tr>
                        <th style="width: 10%">WO No <span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <input type="text" id="po_no" style="width:20%" name="po_no" value="<?=$initiate_po_no;?>" readonly class="form-control col-md-7 col-xs-12" >
                            <input type="text" id="pono" style="width:60%"  name="pono" value="<?php if(!isset($pono)) echo automatic_number_generate($sekeyword,"purchase_master","pono","create_date='".date('Y-m-d')."'","000"); else echo $pono;?>"  class="form-control col-md-7 col-xs-12" >
                            <input type="hidden" id="po_id"   required="required" name="po_id" value="<?=($po_id!='')? $po_id : automatic_number_generate("","purchase_master","po_id","create_date='".date('Y-m-d')."'",""); ?>" class="form-control col-md-7 col-xs-12"  readonly >
                        </td>
                        <th style="width: 10%">Vendor <span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <select style="width: 80%;" class="select2_single form-control"  required name="vendor_id" id="vendor_id">
                                <option></option>
                                <?=foreign_relation("vendor","vendor_id","concat(vendor_id,' : ',vendor_name)",$vendor_id, "status='ACTIVE'".$sec_com_connection_wa."");?>
                            </select>
                        </td>
                        <th style="width: 10%">Receive Destination <span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <select class="select2_single form-control" style="width: 80%;" required name="warehouse_id" id="warehouse_id">
                                <option></option>
                                <?php if(isset($_SESSION['initiate_po_no'])>0): ?>
                                    <option value="<?=$warehouse_id?>" selected><?=find_a_field('warehouse','warehouse_name','warehouse_id='.$warehouse_id)?></option>
                                <?php else: ?>
                                    <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$warehouse_id);?>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td style="height: 5px"></td></tr>
                    <tr>
                        <th>WO Date <span class="required text-danger">*</span></th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="date"  name="po_date" MAX="<?=date('Y-m-d');?>" value="<?=($po_date!='')? $po_date : date('Y-m-d') ?>" style="width: 80%; font-size:11px" class="form-control col-md-7 col-xs-12" >
                        </td>
                        <th>Quotation Date</th>
                        <th style="text-align:center">:</th>
                        <td><input type="date"  name="quotation_date" MAX="<?=date('Y-m-d');?>" style="width: 80%;font-size:11px" value="<?=$quotation_date;?>" class="form-control col-md-7 col-xs-12" >
                        </td>
                        <th>Delivery Within <span class="required text-danger">*</span></th>
                        <th style="text-align:center">:</th>
                        <td><input type="date" name="delivery_within" MIN=<?=date('Y-m-d');?> value="<?=$delivery_within;?>" style="width: 80%;font-size:11px" class="form-control col-md-7 col-xs-12" >
                        </td>
                    </tr>
                    <tr><td style="height: 5px"></td></tr>
                    <tr>
                        <th>VAT</th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="text" id="tax" name="tax" value="<?=$tax;?>" placeholder="%" class="form-control col-md-7 col-xs-12" >
                        </td>
                        <th>Tax</th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="text" id="tax_ait" name="tax_ait" placeholder="%" value="<?=$tax_ait;?>" class="form-control col-md-7 col-xs-12" >
                        </td>
                        <th>Remarks</th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="text" style="width: 80%;" value="<?=$po_details;?>"  id="po_details" name="po_details"  class="form-control col-md-7 col-xs-12" >
                        </td>
                    </tr>
                    <tr><td style="height: 5px"></td></tr>
                    <tr>
                        <td align="center" colspan="9" style="vertical-align: middle">
                            <button type="submit" name="new" style="margin-top: 15px; font-size: 11px"  class="btn btn-primary"><?=$btn_name?></button>
                            <?php if ($COUNT_details_data>0) : ?><a align="center" href="po_print_view.php?po_no=<?=$$unique;?>" target="_new"><img src="/../assets/images/print.png" width="30" height="30" /></a> <?php endif;?>
                        </td>
                    </tr>
                    <? $field= 'return_comments'; if($$field!=''){?>
                        <tr>
                        <td  style="text-align: center; font-weight: bold; color:red; font-size: 12px">Return Remarks: <?=$$field;?></td></tr><?php } ?>
                </table>
            </form>
        </div>
    </div>
</div>

<?php if($initiate_po_no>0):?>
<form action="<?=$page;?>" method="post" enctype="multipart/form-data" name="cloud" id="cloud" class="form-horizontal form-label-left">
    <input  name="po_no" type="hidden" id="<?=$unique?>" value="<?=$_SESSION['initiate_po_no'];?>"/>
    <input  name="po_id" type="hidden" id="po_id" value="<?=$_SESSION['initiate_po_id'];?>"/>
    <input  name="warehouse_id" type="hidden" id="warehouse_id" value="<?=$warehouse_id?>"/>
    <input  name="po_date" type="hidden" value="<?=$po_date?>"/>
    <input  name="vendor_id" type="hidden" id="vendor_id" value="<?=$vendor_id?>"/>
    <input  name="pono" type="hidden" id="pono" value="<?=$initiate_pono;?>"/>
    <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
        <thead>
        <tr style="background-color: #3caae4; color:white">
            <th style="text-align: center">Attachment ( .csv file)</th>
            <th style="text-align: center">Option</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td align="center">
                <input style="font-size:11px" type="file" id="file" name="file" required class="form-control col-md-7 col-xs-12" >
            </td>
            <td align="center" style="width:5%; vertical-align:middle">
                <button type="submit" name="Import" onclick='return window.confirm("Are you confirm to Upload?");' class="btn btn-primary" style="font-size: 11px">Upload the File</button>
            </td>
        </tr>
        </tbody>
    </table>
    <? $group_for = find_a_field('warehouse','group_for','warehouse_id='.$warehouse_id.' ');
    if($vendor->ledger_id==0): ?>
        <table width="80%" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr>
                <td bgcolor="#FF3333"><div align="center" class="style1">VERDOR IS BLOCKED. NO ACCOUNT CODE FOUND</div></td>
            </tr>
        </table>
    <? else:?>
        <?php if($_GET['id']>0): ?>
            <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                <thead>
                <tr style="background-color: bisque">
                    <th style="text-align: center">Item Name</th>
                    <th style="text-align: center">Item Details</th>
                    <th style="text-align: center">Buy Qty</th>
                    <th style="text-align: center">Unit Price</th>
                    <th style="text-align: center">Amount</th>
                    <th style="text-align: center">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="vertical-align:middle"><input  name="<?=$unique?>" type="hidden" id="<?=$unique?>" value="<?=$initiate_po_no;?>"/>
                        <input  name="po_id" type="hidden" id="po_id" value="<?=$_SESSION['initiate_po_id'];?>"/>
                        <input  name="warehouse_id" type="hidden" id="warehouse_id" value="<?=$warehouse_id?>"/>
                        <input  name="po_date" type="hidden" value="<?=$po_date?>"/>
                        <input  name="vendor_id" type="hidden" id="vendor_id" value="<?=$vendor_id?>"/>
                        <input  name="pono" type="hidden" id="pono" value="<?=$initiate_pono;?>"/>
                        <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                            <option></option>
                            <?=advance_foreign_relation($sql_item_id,$edit_value_item_id);?>
                        </select>
                    </td>

                    <td style="vertical-align:middle; width: 20%">
                        <textarea type="text" name="item_details" id="item_details" class="form-control col-md-7 col-xs-12" style="width: 98%; margin-left: 1%; height: 38px; text-align: center; vertical-align: middle; font-size: 11px"  /><?=$edit_value_item_details?></textarea>
                    </td>
                    <script>function doMath() {
                            var rate = parseFloat(document.getElementById('rate').value);
                            var qty = parseFloat(document.getElementById('qty').value);
                            var amount = (rate * qty).toFixed(2);
                            document.getElementById('amount').value = amount; }</script>
                    <td style="vertical-align:middle;width: 10%">
                        <input type="number" name="qty"   id="qty" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px; font-weight:bold; font-size: 11px; text-align:center;" value="<?=$edit_value_qty?>" step="any" min="0" class="qty;" />
                    </td>
                    <td style="vertical-align:middle;width: 10%">
                        <input type="number" name="rate"   id="rate" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px; font-weight:bold;font-size: 11px; text-align:center;" value="<?=$edit_value_rate?>"  required="required" step="any" min="0" class="rate" />
                        <input type="hidden" name="vat_amount" id="vat_amount" value=""  class="vat_amount" />
                        <input type="hidden" name="commission_amount" id="commission_amount" value="<?=$edit_value_commission_amount?>"  class="commission_amount" />
                    </td>

                    <td align="center" style="vertical-align:middle;width: 12%">
                        <input type="number" name="amount" readonly id="amount" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px; font-size: 11px; font-weight:bold; text-align:center;" value="<?=$edit_value_amount?>" class="amount" step="any" min="1" />
                    </td>

                    <td align="center" style="vertical-align:middle;width:7%">
                        <?php if (isset($_GET['id'])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET['id'];?>" id="editdata<?=$_GET['id'];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                        <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>
                </tr>
            </table>
        <?php else:  ?><?php endif; ?>
        </form>
    <? endif;?>

    <?php $commission_amount=find_a_field('purchase_invoice','SUM(commission_amount)','po_no='.$initiate_po_no); ?>
    <?=added_data_delete_edit_purchase_order($res,$unique,$initiate_po_no,$COUNT_details_data,$page,5,5,$commission_amount,$tax);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
