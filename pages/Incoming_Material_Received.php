<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
 $title='Incoming Material Received';

$now=time();
$unique='id';
$unique_PI='MAN_ID';
$unique_field='name';
$table="MAN_master";
$table_details="MAN_details";
$unique_details='m_id';
$page="Incoming_Material_Received.php";
$crud      =new crud($table);
$create_date=date('Y-m-d');
$unique_GET = @$_GET[$unique];
if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['ip']=$ip;
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION['initiate_man_documents']=$_POST['MAN_ID'];
        $_POST['create_date']=$create_date;
        $_POST['status']='MANUAL';
        $crud->insert();
		$_SESSION['m_id']=find_a_field('MAN_master','id','MAN_ID="'.$_POST['MAN_ID'].'"');

        if($_FILES['dChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['dChallanPDF']['tmp_name'];
            $folder = "dc_documents/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'dc'.".pdf");}

        if($_FILES['VatChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['VatChallanPDF']['tmp_name'];
            $folder = "vc_documents/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'vc'.".pdf");}
        $type=1;
        unset($_POST);
        unset($$unique);
    }
	
//for modify PS information ...........................
    if(isset($_POST['modify']))
    {   $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);

        if($_FILES['dChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['dChallanPDF']['tmp_name'];
            $folder = "../../po_documents/dChallanPDF/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'dc'.".pdf");}

        if($_FILES['VatChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['VatChallanPDF']['tmp_name'];
            $folder = "../../po_documents/VatChallanPDF/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'vc'.".pdf");}
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['qty']>0) {        
        $_POST['status']="UNCHECKED";
        $_POST['mfg_year']=date('Y' , strtotime($m));
        $_POST['mfg_month']=date('m' , strtotime($m));
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $crud = new crud($table_details);
        $crud->insert();
    }}
}

$m_id = @$_SESSION['m_id'];
$initiate_man_documents = @$_SESSION['initiate_man_documents'];

//for single FG Delete..................................
$results=mysqli_query($conn,"Select m.*,i.* from ".$table_details." m,item_info i where   
m.item_id=i.item_id and 
m.m_id='".$m_id."'");
while($row=mysqli_fetch_array($results)){
    $ids=$row['id'];
    if(isset($_POST['deletedata'.$ids]))
    {$del="DELETE FROM ".$table_details." WHERE id='$ids'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);}
	 if(isset($_POST['editdata'.$ids]))
    {  mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST['item_id']."', po_no='".$_POST['po_no']."',qty='".$_POST['qty']."',mfg='".$_POST['mfg']."',no_of_pack='".$_POST['no_of_pack']."' WHERE id=".$ids));
        unset($_POST);
    }
	}
    
if(isset($_POST['confirm']))
{
    $up="UPDATE ".$table." SET status='UNCHECKED' where ".$unique."='$_SESSION[m_id]'";
    $update_table_master=mysqli_query($conn, $up);
    $up2="UPDATE ".$table_details." SET status='UNCHECKED' where ".$unique_details."='".$_SESSION['m_id']."'";
    $update_table_details=mysqli_query($conn, $up2);
    unset($_SESSION['m_id']);
    unset($_SESSION['initiate_man_documents']);
    unset($m_id);
    unset($initiate_man_documents);
    unset($_POST);
    header("'.$page.'");

} // if insert posting

//for Delete..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table_details);
    $condition =$unique_details."=".$_SESSION['m_id'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['m_id'];
    $crud->delete($condition);
    $dc_delete = 'dc_documents/'."'.$unique.'".'_'.'dc'.'.pdf';
    unlink($dc_delete);
    $vc_delete = 'vc_documents/'."'.$unique.'".'_'.'vc'.'.pdf';
    unlink($vc_delete);
    unset($_SESSION['m_id']);
    unset($_SESSION['initiate_man_documents']);
    unset($m_id);
    unset($initiate_man_documents);
    unset($_POST);
}
if (isset($_GET['id'])) {
    $edit_value=find_all_field(''.$table_details.'','','id='.$_GET['id'].'');
}
$edit_value_item_id = @$edit_value->item_id;
$edit_value_po_no = @$edit_value->po_no;
$edit_value_qty = @$edit_value->qty;
$edit_value_mfg = @$edit_value->mfg;
$edit_value_no_of_pack = @$edit_value->no_of_pack;
$edit_value_batch = @$edit_value->batch;
$m_id = @$_SESSION['m_id'];
$initiate_man_documents = @$_SESSION['initiate_man_documents'];
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)','m_id='.$m_id.'');
// data query..................................
if(isset($_SESSION['m_id']))
{   $condition=$unique."=".$_SESSION['m_id'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$man_received_date = @$man_received_date;
$man_date = @$man_date;
$delivary_challan = @$delivary_challan;
$initiate_man_documents = @$initiate_man_documents;
$delivary_challan = @$delivary_challan;
$VAT_challan = @$VAT_challan;
$delivary_challan_Date = @$delivary_challan_Date;
$VAT_challan_Date = @$VAT_challan_Date;
$remarks = @$remarks;
$warehouse_id = @$warehouse_id;
$vendor_code = @$vendor_code;

$results=mysqli_query($conn,"Select m.*,i.* from ".$table_details." m,item_info i where   
m.item_id=i.item_id and 
m.m_id='".$m_id."'");

$sql="Select m.id,m.id,m.po_no,i.item_id,i.item_name,i.unit_name as UOM,m.qty,m.mfg,m.no_of_pack from ".$table_details." m,item_info i where   
m.item_id=i.item_id and 
m.m_id='".$m_id."'";

$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id						 
							  order by i.item_name";

$Searchitem_id = @$_GET['Searchitem_id'];
if($Searchitem_id>0){
$PO_for_item=@$Searchitem_id; } else {
$PO_for_item=@$edit_value_item_id;
}
?>

<?php require_once 'header_content.php'; ?>
 
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='Incoming_Material_Received.php?Searchitem_id=' + val ;
}
</script>
<style>
    input[type=text]{
        font-size: 11px;
        height: 30px;
        width: 100%;
    }
    input[type=file]{
        font-size: 11px;
        height: 30px;
        width: 45%;
        float: right;
    }
</style>
<?php require_once 'body_content.php'; ?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <a class="btn btn-sm btn-default"  href="MAN_report.php" target="_blank">
                    <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">MAN View</span>
                </a>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page;?>" enctype="multipart/form-data" method="post" name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px">
                <table align="center" style="width:100%; margin:5px">
                    <tr>
                        <th style="width: 10%">MAN Rcvd. Date<span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <input type="date"  style="width:80%; font-size: 11px" required value="<?=$man_received_date;?>" name="man_received_date" class="form-control col-md-7 col-xs-12">
                            <input type="hidden" id="man_date"  required="required" name="man_date" value="<?=($man_date!='')? $man_date : date('Y-m-d') ?>" style="font-size:11px" max="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12" >
                        </td>

                        <th style="width: 10%">MAN No <span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <input type="text" id="MAN_ID" style="width: 80%"  required="required" name="MAN_ID" value="<?=($initiate_man_documents!='')? $initiate_man_documents : automatic_number_generate("MAN".$_SESSION['userid'],"MAN_master","MAN_ID","create_date='".date('Y-m-d')."' and entry_by=".$_SESSION['userid']."",""); ?>" class="form-control col-md-7 col-xs-12"  readonly >
                            <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="">
                        </td>

                        <th style="width: 10%">Delivery Challan <span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                        <input type="text" id="delivary_challan" style="width: 40%; float: left" value="<?=$delivary_challan;?>" name="delivary_challan" placeholder="don't use ( /,&,',*)" class="form-control col-md-7 col-xs-12" required >
                        <?php if($initiate_man_documents){ ?>
                        <a href="dc_documents/<?=$_SESSION['m_id'].'_'.'dc'.'.pdf';?>" target="_new" style="text-decoration:underline; color:blue">Challan Document View</a>
                        <?php } else { ?>
                        <input type="file" style="width: 38%; float: left; margin-left: 2%" placeholder="Delivery Challan" id="nam_date"   name="dChallanPDF"  class="form-control col-md-7 col-xs-12" >
                        <?php } ?>
                        </td>
                    </tr>

                    <tr><td style="height: 5px"></td></tr>

                    <tr>
                        <th>VAT Challan</th>
                        <th style="text-align:center">:</th>
                        <td>
                        <input type="text" id="VAT_challan" style="width: 40%" placeholder="don't use ( /,&,',*)"  value="<?=$VAT_challan;?>" name="VAT_challan" class="form-control col-md-7 col-xs-12"  >
                        <?php if($initiate_man_documents){ ?>
                        <a href="vc_documents/<?=$m_id.'_'.'vc'.'.pdf';?>" target="_new" style="text-decoration:underline; color:blue">VAT Challan View</a>
                        <?php } else { ?>
                        <input type="file" id="nam_date"  style="width: 38%; float: left; margin-left: 2%"  name="VatChallanPDF"  class="form-control col-md-7 col-xs-12" >
                        <?php } ?>
                        </td>

                        <th>Delivery Challan Date</th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="date" id="delivary_challan_Date" style="font-size:11px; width: 80%" value="<?=($delivary_challan_Date!='')? $delivary_challan_Date : ''; ?>" name="delivary_challan_Date"  class="form-control col-md-7 col-xs-12" >
                        </td>

                        <th>VAT Challan Date<span class="required text-danger">*</span></th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="date" id="VAT_challan_Date" value="<?=($VAT_challan_Date!='')? $VAT_challan_Date : ''; ?>" style="font-size:11px; width: 80%" name="VAT_challan_Date"  class="form-control col-md-7 col-xs-12" />
                        </td>
                    </tr>
                    <tr><td style="height: 5px"></td></tr>
                    <tr>
                        <th>Remarks</th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="text" style="width: 80%" name="remarks" id="remarks" value="<?=$remarks?>" class="form-control col-md-7 col-xs-12">
                        </td>
                        <th>Warehouse <span class="required text-danger">*</span></th>
                        <th style="text-align:center">:</th>
                        <td>
                            <select class="form-control" style="width:80%; font-size: 11px; height: 30px" tabindex="-1" required="required" id="warehouse_id" name="warehouse_id">
                                <option></option>
                                <?=foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, 'use_type in (\'PL\',\'WH\')','order by warehouse_id'); ?>
                            </select>
                        </td>

                        <th>Vendor <span class="required text-danger">*</span></th>
                        <th style="text-align:center">:</th>
                        <td>
                            <select class="select2_single form-control" style="width:80%; font-size: 11px;" tabindex="-1" required="required"  name="vendor_code" id="vendor_code">
                                <option></option>
                                <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $vendor_code, '1','order by vendor_name'); ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="form-group" style="margin-left:40%">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($initiate_man_documents):  ?>
                            <button type="submit" name="modify" id="modify" class="btn btn-primary" style="font-size: 11px">Update MAN Documents</button>
                        <?php else: ?>
                            <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 11px">Initiate MAN Documents</button>
                        <?php endif; ?>
                    </div></div>
            </form>
        </div>
    </div>
</div>
               
<?php ?>               


<?php if($initiate_man_documents):?>
    <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="section_id" id="section_id" value="<?=$_SESSION['sectionid'];?>">
        <input type="hidden" name="company_id" id="company_id" value="<?=$_SESSION['companyid'];?>">
        <input type="hidden" name="m_id" id="m_id" value="<?=$m_id;?>">
        <input type="hidden" name="MAN_ID" id="MAN_ID" value="<?=$_SESSION['initiate_man_documents'];?>">
        <input type="hidden" name="man_date" id="man_date" value="<?=$man_date;?>">
        <input type="hidden" name="vendor_code" id="vendor_code" value="<?=$vendor_code;?>">
        <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse_id;?>">
        <input type="hidden" name="delivary_challan" id="delivary_challan" value="<?=$delivary_challan;?>">
        <input type="hidden" name="delivary_challan_Date" id="delivary_challan_Date" value="<?=$delivary_challan_Date;?>">
        <input type="hidden" name="VAT_challan" id="VAT_challan" value="<?=$VAT_challan;?>">
        <input type="hidden" name="VAT_challan_Date" id="VAT_challan_Date" value="<?=$VAT_challan_Date;?>">
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <tr style="background-color: #3caae4; color:white">
                <th style="text-align: center">Material Details</th>
                <th style="text-align: center; width: 10%">PO No</th>
                <th style="text-align: center; width: 10%">Qty</th>
                <th style="text-align: center; width: 10%">Expiry Date</th>
                <th style="text-align: center; width: 10%">Batch</th>
                <th style="text-align: center; width: 10%">No of pack</th>
                <th style="text-align: center; width: 5%">Option</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="vertical-align:middle" align="center">
                    <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id" <?php if(@$_GET['id']>0) : echo ''; else : ?>  onchange="javascript:reload(this.form)" <?php endif; ?> >
                        <option></option>
                        <?=advance_foreign_relation($sql_item_id,($Searchitem_id!='')? $Searchitem_id : $edit_value_item_id);?>
                    </select>
                </td>
                <td style="vertical-align:middle" align="center">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="po_no" id="po_no">
                        <option></option>
                        <?php foreign_relation('purchase_invoice', 'distinct po_no', 'po_no', $edit_value_po_no, 'item_id='.$PO_for_item.' and vendor_id='.$vendor_code.'','order by po_no'); ?>
                    </select>
                </td>
                <td style="vertical-align:middle; text-align: center">
                    <input type="number" id="qty" style="width:99%; height:37px; font-weight:bold; text-align:center"  required="required"  name="qty" value="<?=$edit_value_qty?>" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                </td>
                <td style="vertical-align:middle;text-align: center">
                    <input type="date" id="mfg" style="width:99%; height:37px; font-size:11px; text-align:center"  min="<?=date('Y-m-d');?>"   name="mfg" placeholder="MFG" value="<?=$edit_value_mfg;?>" class="form-control col-md-7 col-xs-12"  >
                </td>
                <td align="center" style="vertical-align:middle; text-align: center">
                    <input type="text" id="batch" style="height:37px; font-weight:bold; text-align:center"  required="required"  name="batch" class="form-control col-md-7 col-xs-12" value="<?=$edit_value_batch;?>" autocomplete="off" >
                </td>
                <td align="center" style="vertical-align:middle">
                    <input type="text" id="no_of_pack" style=" height:37px; font-weight:bold; text-align:center"  name="no_of_pack" class="form-control col-md-7 col-xs-12" value="<?=$edit_value_no_of_pack;?>" autocomplete="off" >
                </td>
                <td align="center" style="vertical-align:middle">
                    <?php if (isset($_GET['id'])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET['id'];?>" id="editdata<?=$_GET['id'];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                    <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
<?=added_data_delete_edit($sql,$unique,$unique_GET,$COUNT_details_data,$page,'','');?>
<?php endif;?>
<?=$html->footer_content();?>