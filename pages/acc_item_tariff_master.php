<?php
ob_start();
require_once 'support_file.php';
$title='Create Tariff';
$unique='id';
$unique_field='H_S_code';
$table='item_tariff_master';
$page="acc_item_tariff_master.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


if(isset($_POST[$unique_field]))
{ $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $_POST['entry_at']=time();
        $_POST['entry_by']=$_SESSION['userid'];
        $_POST['status']='Drafted';
        $_POST['active_status']='Inactive';
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);}

    //for Modify..................................
    if(isset($_POST['modify']))
    {   $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";}

//for Delete..................................
    if(isset($_POST['delete']))
    {   $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
    }}



if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}



$res='select 
                                i.'.$unique.',
                                i.'.$unique.' as code,
                                i.H_S_code as "H.S Code",
                                i.description,
								i.product_example,
								i.CD,
								i.RD,
								i.SD,
								i.VAT,
								i.AIT,
								i.ATV,
								i.TTI,
								i.Tariff_Section_Record_Value_in_USD,
								i.uom,
								i.remarks
								                               
                                from                                                                
                                '.$table.' i
                                
                                WHERE 1                                                                
                                order by i.'.$unique;

$sql = "SELECT sg.sub_group_id,concat(sg.sub_group_id,' : ',sg.sub_group_name,' : ',g.group_name) FROM                        
                        item_sub_group sg,
                        item_group g
                        where
                        sg.group_id=g.group_id 
                        order by sg.sub_group_id";
$sql_unit="select unit_name, unit_name from unit_management";
$sql_item_type="Select item_type,item_type from item_type";
$sql_brand="Select brand_name,brand_name from brand";
$sql_brand_category="Select category_name,category_name from brand_category"
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">H.S Code<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="text" name="H_S_code" id="H_S_code" value="<?=$H_S_code?>" style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
            </div></div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Description (as per BD Tariff)</label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <textarea id="description" style="width:100%; height: 80px; font-size: 12px" name="description" class="form-control col-md-7 col-xs-12" ><?=$description;?></textarea>
            </div>
        </div>



        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Product Example</label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="text" id="product_example" style="width:100%; font-size: 12px" name="product_example" value="<?=$product_example;?>" class="form-control col-md-7 col-xs-12" >
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Duty Structure, %<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="text" id="CD" style="width:33%; font-size: 11px; float:left"    name="CD" value="<?=$CD;?>" class="form-control col-md-7 col-xs-12" placeholder="CD" title="CD">
                <input type="text" id="RD" style="width:33%; font-size: 11px; margin-left:1px" name="RD" value="<?=$RD;?>" class="form-control col-md-7 col-xs-12" placeholder="RD" title="RD">
                <input type="text" id="SD" required style="width:33%; font-size: 11px; float:right" name="SD" value="<?=$SD;?>" class="form-control col-md-7 col-xs-12" placeholder="SD" title="SD">
                <br><br>
                <input type="text" id="VAT" style="width:24%; font-size: 11px; float:left; margin-top: 3px"    name="VAT" value="<?=$VAT;?>" class="form-control col-md-7 col-xs-12" placeholder="VAT" title="VAT">
                <input type="text" id="AIT" style="width:25%; font-size: 11px; margin-left:1px; margin-top: 3px" name="AIT" value="<?=$AIT;?>" class="form-control col-md-7 col-xs-12" placeholder="AIT" title="AIT">
                <input type="text" id="TTI" required style="width:25%; font-size: 11px; margin-left: 1px; float:right; margin-top: 3px" name="TTI" value="<?=$TTI;?>" class="form-control col-md-7 col-xs-12" placeholder="TTI" title="TTI">
                <input type="text" id="ATV" required style="width:25%; font-size: 11px; float:right; margin-top: 3px" name="ATV" value="<?=$ATV;?>" class="form-control col-md-7 col-xs-12" placeholder="ATV" title="ATV">

            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Tariff/Section/Record Value in USD:<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="text" id="Tariff_Section_Record_Value_in_USD" style="width:100%; font-size: 12px" name="Tariff_Section_Record_Value_in_USD" value="<?=$Tariff_Section_Record_Value_in_USD;?>" class="form-control col-md-7 col-xs-12" >
            </div>
        </div>


        <?php if($_GET[$unique]):  ?>
            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify</button>
                </div></div>
        <?php else : ?>
            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button></div></div> <?php endif; ?>


    </form>
    </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
<?php if(!isset($_GET[$unique])):?>
    <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>