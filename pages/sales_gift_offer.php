<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$now=time();
$unique='id';
$unique_field='offer_name';
$table="sale_gift_offer";
$page="sales_gift_offer.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$title='Trade Scheme';

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        //unset($_POST);
        unset($$unique);
    }

//for modify PS information ...........................
        if(isset($_POST['modify']))
        {   $_POST['edit_at']=time();
            $_POST['edit_by']=$_SESSION['userid'];
            $crud->update($unique);
            $type=1;
            unset($_POST);
        }
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    //echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$sql = "SELECT typeshorname, typedetails from distributor_type
                        where 1 order by typedetails";
$sql_item = "SELECT item_id, concat(item_id,' : ',finish_goods_code,' : ', item_name) from item_info
                        where product_nature in ('Salable','Both') order by finish_goods_code";
$res="SELECT ts.id,ts.offer_name,ts.start_date,ts.end_date,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name) as Buy_item,ts.item_qty as Buy_qty,(select item_name from item_info where item_id=ts.gift_id) as Get_item_name,ts.gift_qty,ts.gift_type from ".$table." ts, item_info i where ts.item_id=i.item_id";
?>



<?php require_once 'header_content.php'; ?>
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
                <h5 class="modal-title">Add New TS
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Trade Scheme Name<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="text" id="offer_name" style="width:100%; font-size: 12px"  required   name="offer_name" value="<?=($_GET[$unique]>0)? $offer_name : $_POST[offer_name]; ?>" class="form-control col-md-7 col-xs-12" >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Offer for Customer Type<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="dealer_type" id="dealer_type">
                    <option></option>
                    <?=advance_foreign_relation($sql,($_GET[$unique]>0)? $dealer_type : $_POST['dealer_type']);?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Start Date<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="date" id="start_date" style="width:100%; font-size: 11px"  required   name="start_date" value="<?=($_GET[$unique]>0)? $start_date : $_POST['start_date']; ?>" class="form-control col-md-7 col-xs-12" >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">End Date<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="date" id="end_date" style="width:100%; font-size: 11px"  required   name="end_date" value="<?=($_GET[$unique]>0)? $end_date : $_POST['end_date']; ?>" class="form-control col-md-7 col-xs-12" >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Buy Item Name<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="item_id" id="item_id">
                    <option></option>
                    <?=advance_foreign_relation($sql_item,$item_id);?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Buy Qty:<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="text" id="item_qty" style="width:100%; font-size: 11px; left:left" name="item_qty" value="<?=$item_qty;?>" class="form-control col-md-7 col-xs-12" placeholder="Buy Qty" title="Buy Qty">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Get Item Name<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="gift_id" id="gift_id">
                    <option></option>
                    <?=advance_foreign_relation($sql_item,$gift_id);?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Get Qty / Cash amount:<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <input type="number" id="gift_qty" style="width:100%; font-size: 11px; left:left" name="gift_qty" value="<?=$gift_qty;?>" class="form-control col-md-7 col-xs-12" placeholder="Get Qty / Amount" title="Get Qty / Amount" step="any">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Gift Type:<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <select style="width: 100%" class="select2_single form-control" name="gift_type" id="gift_type">
                    <option></option>
                    <?=$sql11="select type,type from sales_TS_type where status>0"?>
                    <?=advance_foreign_relation($sql11,($_GET[$unique]>0)? $gift_type : $_POST['gift_type']);?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Calculation Mode:<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                <select style="width: 100%" class="select2_single form-control" name="calculation" id="calculation">
                    <option></option>
                    <option value="1" <?php if($calculation=='Auto') echo 'selected' ?>>Auto</option>
                    <option value="0" <?php if($calculation=='Manual') echo 'selected' ?>>Manual</option>
                </select></div>
        </div>
        <?php if($_GET[$unique]):  ?>
            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify the TS</button>
                </div></div>
        <?php else : ?>
            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New TS</button>
                </div>
            </div>
        <?php endif; ?>
    </form>
    </div>
    </div>
    </div>
<?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
<?php if(!isset($_GET[$unique])):?>
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();ob_flush(); ?>