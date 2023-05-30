<?php ob_start(); require_once 'support_file.php';?>
<?php $title='Add Party';
$unique='dealer_code';
$unique_field='dealer_name';
$table='corporate_dealer_info';
$page="warehouse_add_corporate_party.php";
$crud      =new crud($table);
$unique_GET = @$_GET[$unique];

$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and i.company_id='".$_SESSION['companyid']."' and i.section_id in ('400000','".$_SESSION['sectionid']."')";
}


if(prevent_multi_submit()) {
    if (isset($_POST['record'])) {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $crud->insert();
        unset($_POST);
    }

    if (isset($_POST['modify'])) {
        $d = $_POST['voucher_date'];
        $_POST['voucher_date'] = date('Y-m-d', strtotime($d));
        if($_POST['Cheque_Date']>0){
            $ckd = $_POST['Cheque_Date'];
            $_POST['Cheque_Date'] = date('Y-m-d', strtotime($ckd));
        } else {
            $_POST['Cheque_Date']='';
        }
        $_POST['edit_at'] = time();
        $_POST['edit_by'] = $_SESSION['userid'];
        $crud->update($unique);
        unset($_POST);
        }

}





if(isset($unique_GET))
{   $condition=$unique."=".$unique_GET;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}
$finish_goods_code = @$finish_goods_code;
$item_name = @$item_name;
$item_description = @$item_description;
$sub_group_id = @$sub_group_id;
$consumable_type = @$consumable_type;
$product_nature = @$product_nature;
$exim_status = @$exim_status;
$brand_category = @$brand_category;
$brand_id = @$brand_id;
$unit_name = @$unit_name;
$pack_unit = @$pack_unit;
$pack_size = @$pack_size;
$g_weight = @$g_weight;
$shelf_life = @$shelf_life;
$material_cost = @$material_cost;
$conversion_cost = @$conversion_cost;
$production_cost = @$production_cost;
$m_price = @$m_price;
$d_price = @$d_price;
$t_price = @$t_price;
$com_on_m_price = @$com_on_m_price;
$com_on_d_price = @$com_on_d_price;
$com_on_t_price = @$com_on_t_price;
$SD_percentage = @$SD_percentage;
$SD = @$SD;
$VAT_percentage = @$VAT_percentage;
$VAT = @$VAT;
$quantity_type = @$quantity_type;
$status = @$status;
$commission_status = @$commission_status;
$revenue_persentage = @$revenue_persentage;
$VAT_item_group = @$VAT_item_group;
$H_S_code = @$H_S_code;
$serial = @$serial;

$query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
    if(isset($_POST['deletedata'.$row->$unique]))
    { if($row->has_entry == 0){
        mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$row->$unique.""));
    } else { echo "It has entry (".$row->has_transaction."). Hence you cannot delete the Item Id (".$row->item_id.")";}
        unset($_POST);
    }}

$res = "SELECT d.dealer_code,d.dealer_name,d.contact_person,d.contact_number,d.contact_person_desig,d.address,d.ledger_id from ".$table." d";
?>
<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php if(isset($_GET[$unique])):
    require_once 'body_content_without_menu.php'; else :
    require_once 'body_content.php'; endif;  ?>
<?php if(isset($_GET[$unique])): ?>
<div class="col-md-5 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right"></div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php else: ?>

            <div class="modal fade" id="addModal">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Add New Record
                                <button class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <?php endif; ?>
                            <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                                <? require_once 'support_html.php';?>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Party Name <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" id="dealer_name" style="width:100%; font-size: 12px"  required   name="dealer_name" value="<?=$dealer_name;?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Contact Person</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" id="contact_person" style="width:100%; font-size: 12px" name="contact_person" value="<?=$contact_person;?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Contact Number</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" id="contact_number" style="width:100%; font-size: 12px" name="contact_number" value="<?=$contact_number;?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Contact Person Designation</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" id="contact_person_desig" style="width:100%; font-size: 12px" name="contact_person_desig" value="<?=$contact_number;?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <textarea id="address" style="width:100%; height: 80px; font-size: 12px" name="address" class="form-control col-md-7 col-xs-12" ><?=$address?></textarea>
                                    </div>
                                </div>



                                <hr/>
                                <?php if($unique_GET>0):  ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify Info</button>
                                        </div></div>
                                <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New Party</button>
                                        </div>
                                    </div> <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if(!isset($unique_GET)): ?>
            </div>
        <?php endif; ?>
            <?php if(!isset($_GET[$unique])):?>
                <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1,$page);?>
            <?php endif; ?>
            <?=$html->footer_content();mysqli_close($conn);?>
            <?php ob_end_flush();?>
