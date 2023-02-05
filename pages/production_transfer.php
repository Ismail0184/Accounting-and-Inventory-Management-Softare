<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Stock Transfer (STO)';

$now=time();
$unique='pi_no';
$table="production_issue_master";
$table_details="production_issue_detail";

$page="production_transfer.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');



if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {
        if ($_POST[warehouse_from] == $_POST[warehouse_to]) {
            echo "<script>alert('Transfer from & Transfer to are the same. Please select a different.!!'); window.location.href='".$page."';</script>";

        } else {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST[ISSUE_TYPE]='STO';
        $d =$_POST[pi_date];
        $_POST[pi_date]=date('Y-m-d' , strtotime($d));
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION[initiate_production_transfer]=$_POST[custom_pi_no];
        $_SESSION['pi_tr'] =$_POST[$unique];
        $_SESSION['production_warehouse'] =$_POST[warehouse_to];
        $_POST[create_date]=$create_date;
        $_POST[ip]=$ip;
        $crud->insert();
        $type=1;
        unset($_POST);
        unset($$unique);
    }}

//for modify PS information ...........................
    if(isset($_POST['modify']))
    {   $d =$_POST[pi_date];
        $_POST[pi_date]=date('Y-m-d' , strtotime($d));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['total_unit']>0) {
        $_POST[status]="MANUAL";
        $_POST[ISSUE_TYPE]="STO";
        $_POST[ip]=$ip;
        $_POST[total_unit]=$_POST[total_unit];
        $_POST[conversion_cost]=find_a_field('item_info','conversion_cost','item_id='.$_POST[item_id].'');
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $crud = new crud($table_details);
        $crud->insert();
    }}



} /// prevent multi submit

if(isset($_POST['confirm']))
{   $up="UPDATE ".$table." SET verifi_status='UNCHECKED' where ".$unique."='$_SESSION[pi_tr]'";
    $update_table_master=mysqli_query($conn, $up);
    $up2="UPDATE ".$table_details." SET verifi_status='UNCHECKED',status='UNCHECKED' where ".$unique."='$_SESSION[pi_tr]'";
    $update_production_floor_issue_master=mysqli_query($conn, $up2);
    unset($_SESSION['pi_tr']);
    unset($_SESSION['initiate_production_transfer']);
    unset($_POST);
}


//for single FG Delete..................................
$query="Select * from ".$table_details." where ".$unique."='$_SESSION[pi_tr]'";
$res=mysqli_query($conn, $query);
while($row=mysqli_fetch_array($res)){
    $ids=$row[id];
    if(isset($_POST['deletedata'.$ids]))
    {
        $del="DELETE FROM ".$table_details." WHERE id='$ids' and ".$unique."='$_SESSION[pi_tr]'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);
    }}

//for Delete..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table_details);
    $condition =$unique."=".$_SESSION['pi_tr'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['pi_tr'];
    $crud->delete($condition);
    unset($_SESSION['pi_tr']);
    unset($_SESSION['initiate_production_transfer']);
    unset($_POST);
}

if (isset($_GET[id])) {$edit_value=find_all_field(''.$table_details.'','','id='.$_GET[id].'');}
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$_SESSION['pi_tr'].'');

if(isset($_SESSION['pi_tr']))
{   $condition=$unique."=".$_SESSION['pi_tr'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$res="Select t.id,concat(i.item_id,' : ', i.finish_goods_code, ' : ', i.item_name) as item_description,i.unit_name as unit,t.total_unit 
from 
".$table_details." t,item_info i 
where   
t.".$unique."=".$_SESSION[pi_tr]." and t.item_id=i.item_id";
$query=mysqli_query($conn, $res);
while($data=mysqli_fetch_object($query)){
  if(isset($_POST['deletedata'.$data->id]))
  {  mysqli_query($conn, ("DELETE FROM ".$table_details." WHERE id=".$data->id));
     mysqli_query($conn, ("DELETE FROM ".$table_details." WHERE gift_on_order=".$data->id));
      unset($_POST);}
  if(isset($_POST['editdata'.$data->id]))
  {   mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST[item_id]."', total_unit='".$_POST[total_unit]."' WHERE id=".$data->id));
      unset($_POST);
    }}

if (isset($_GET[id])) {
$$item_id=$edit_value->item_id;
} else {
    $$item_id=$_GET[item_id];  
}


$item_info=find_all_field("item_info", "", "item_id=".$$item_id."");


$present_stock_sql=mysqli_query($conn, "Select i.item_id,i.finish_goods_code,i.item_name,i.unit_name,i.pack_size,
    REPLACE(FORMAT(SUM(j.item_in-j.item_ex), 0), ',', '') as Available_stock_balance
    from
    item_info i,
    journal_item j,
    lc_lc_received_batch_split bsp
    where
    j.item_id=i.item_id and
    j.warehouse_id='".$warehouse_from."' and
    bsp.batch=j.batch and 
    bsp.status='PROCESSING' and 
    j.item_id='".$$item_id."'
    group by j.item_id order by i.item_id");
    $ps_data=mysqli_fetch_object($present_stock_sql);
$stock_balance_GET=$ps_data->Available_stock_balance;
$Manual_item=find_a_field("production_issue_detail", "SUM(total_unit)", "item_id='".$$item_id."' and warehouse_from=".$warehouse_from." and status in ('MANUAL','UNCHECKED')");
$stock_balance=$stock_balance_GET-$Manual_item;?>
<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
    function reloaditem(form)
    {   var val=form.item_id.options[form.item_id.options.selectedIndex].value;
        self.location='<?=$page;?>?item_id=' + val;
    }
</script>
<SCRIPT language=JavaScript>
    function doAlert(form)
    {
        var val=form.total_unit.value;
        var val2=form.stock_balance.value;
        if (Number(val)>Number(val2)){
            alert('oops!! Exceed Stock Balance!! Thanks');
            form.total_unit.value='';
        }
        form.total_unit.focus();
    }</script>
<style>
    input[type=text]{
        font-size: 11px;
    }
    input[type=date]{
        font-size: 11px;
    }
</style>

<?php require_once 'body_content_nva_sm.php'; ?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
                    <a target="_new" style="float: right" class="btn btn-sm btn-default"  href="warehouse_STO_view.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Stock Transfer View</span>
                    </a>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page?>" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <table style="width:100%">
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">STO NO<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?
                                    $pi_nos=find_a_field(''.$table.'','max('.$unique.')','1');
                                    if($_SESSION['pi_tr']>0) {
                                        $pi_noGET = $_SESSION['pi_tr'];
                                    } else {
                                        $pi_noGET=$pi_nos+1;
                                        if($pi_nos<1) $pi_noGET = 1;
                                    }
                                    ?>
                                    <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$pi_noGET;?>">
                                    <input type="text" id="custom_pi_no" style="width:100%" readonly name="custom_pi_no" value="<?=($_SESSION[initiate_production_transfer]!='')? $custom_pi_no : automatic_number_generate("STO","production_issue_master","custom_pi_no","create_date='".$idatess."' and   custom_pi_no like '$sekeyword%'");?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">STO Date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" id="pi_date" style="width:100%" max="<?=date('Y-m-d')?>"  required="required" name="pi_date" value="<?=($pi_date!='')? $pi_date : date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12" >
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Remarks</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="remarks" style="width:100%" name="remarks" value="<?=$remarks;?>" class="form-control col-md-7 col-xs-12" >
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:35%; vertical-align: middle">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Transfer From<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                                        <option></option>
                                        <?php if(isset($_SESSION['initiate_production_transfer'])>0): ?>
                                        <option value="<?=$warehouse_from?>" selected><?=find_a_field('warehouse','warehouse_name','warehouse_id='.$warehouse_from)?></option>
                                        <?php else: ?>
                                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_from);?>
                                        <?php endif; ?></select>
                                </div></div>
                            </td>
                            <td style="width:35%">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Transfer To<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="warehouse_to" id="warehouse_to">
                                            <option></option>
                                            <?php if(isset($_SESSION['initiate_production_transfer'])>0): ?>
                                        <option value="<?=$warehouse_to?>" selected><?=find_a_field('warehouse','warehouse_name','warehouse_id='.$warehouse_to)?></option>
                                        <?php else: ?>
                                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_to);?>
                                        <?php endif; ?></select>
                                        </div></div>
                            </td>
                            <td style="width:30%">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">VAT Challan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="VATChallanno" style="width:100%"  name="VATChallanno" value="<?=$VATChallanno;?>" class="form-control col-md-7 col-xs-12"  Placeholder="Challan & Date" >
                                    </div>
                                </div>
                            </td>
                    </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Transporter<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="transporter" id="transporter">
                                            <option></option>
                                            <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $transporter, 'vendor_category in (\'30\') order by vendor_name'); ?>
                                        </select>
                                    </div></div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Track No.<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="ps_date" style="width:100%"  name="track_no" value="<?=$track_no?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Driver Info<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="remarkspro" style="width:100%"  required="required" name="driver_info" value="<?=$driver_info;?>" class="form-control col-md-7 col-xs-12" Placeholder="Name & mobile No" >
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                        <td colspan="3">
                            <div class="form-group" style="margin-left:40%">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php if($_SESSION[initiate_production_transfer]){  ?>
                                        <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Update Information</button>
                                    <?php   } else {?>
                                        <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Initiate Entry</button>
                                    <?php } ?>
                                </div></div>
                        </td>

                        </tr>
                </table></form></div></div></div>



<?php if($_SESSION[initiate_production_transfer]):?>
            <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$_SESSION[pi_tr];?>" >
                <input type="hidden" name="custom_pi_no" id="custom_pi_no" value="<?=$custom_pi_no;?>" >
                <input type="hidden" name="pi_date" id="pi_date" value="<?=$pi_date;?>">
                <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
                <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
                <input type="hidden" name="section_id" id="section_id" value="<?=$_SESSION[sectionid];?>">
                <input type="hidden" name="company_id" id="company_id" value="<?=$_SESSION[companyid];?>">
                <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                    <thead>
                    <tr style="background-color: bisque">
                        <th style="text-align: center">Search Items</th>
                        <th style="text-align: center">Unit</th>
                        <th style="text-align: center">Pack Size</th>
                        <th style="text-align: center">Stock in Pcs</th>
                        <th style="text-align: center">Qty in Pcs</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td style="vertical-align: middle">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id" onchange="javascript:reloaditem(this.form)">
                                <option></option>
                                <? advance_foreign_relation(find_all_item($product_nature="'Salable','Both'"),($_GET[item_id]>0)? $_GET[item_id] : $edit_value->item_id);?>
                            </select>
                        </td>
                        <td style="vertical-align: middle;width:10%; text-align:center">
                            <input type="text" id="unit" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="unit" readonly  class="form-control col-md-7 col-xs-12" value="<?=$item_info->unit_name;?>" >
                        </td>
                        <td style="vertical-align: middle;width:10%; text-align:center">
                            <input type="text" id="pack_size" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="pack_size" readonly  class="form-control col-md-7 col-xs-12" value="<?=$item_info->pack_size;?>" >
                        </td>

                        <td style="vertical-align: middle;width:10%; text-align:center">
                            <input type="text" id="stock_balance" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="stock_balance" readonly  class="form-control col-md-7 col-xs-12" value="<?=number_format($stock_balance,2);?>" >
                        </td>

                        <td style="vertical-align: middle;width:10%; text-align:center">
                            <input type="text" id="total_unit" onkeyup="doAlert(this.form);" name="total_unit" value="<?=$edit_value->total_unit?>" style="width:100%; height:37px;text-align:center"  required="required"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        
                        <td style="vertical-align: middle;width:5%; text-align:center">
                        <?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                       <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>
                        </tr>
                    </tbody>
                </table>
            </form>



   
<?=added_data_delete_edit($res,$unique,$unique_GET,$COUNT_details_data);
endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
