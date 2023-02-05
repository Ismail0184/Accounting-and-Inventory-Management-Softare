<?php
require_once 'support_file.php';
$title='Dealer Credit Limit';

$now=time();
$unique='id';
$unique_field='fname';
$table="dealer_credit_limit_record";
$table_dealer_info="dealer_info";
$unique_dealer='dealer_code';
$page="acc_dealer_credit_limit.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$dealer_master=find_all_field('dealer_info','','dealer_code='.$_GET[id]);

if(prevent_multi_submit()) {
if(isset($_POST['modify']))
{   $_POST[dealer_code]=$_GET[id];
    $_POST[permission_by]=$_SESSION[usrid];
    $crud->insert();
    $_POST['credit_limit']=$_POST[credit_limit];
    $crud      =new crud($table_dealer_info);
    $crud->update($unique_dealer);
    $type=1;
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}

$res="Select 
d.dealer_code as dcode,
d.dealer_code as dcode,
d.account_code as account_code,
d.dealer_name_e as dealer_name,
d.dealer_type,
(select SUM(cr_amt-dr_amt) from journal where ledger_id=d.account_code) as account_balance,
d.credit_limit,
d.credit_limit_time as credit_limit_duration
from 
dealer_info d ,
accounts_ledger a
 where 
 d.account_code=a.ledger_id and
 d.canceled in ('Yes') 
 group by d.account_code
 order by d.account_code";

if(isset($$unique)>0)
{   $condition=$unique_dealer."=".$$unique;
    $data=db_fetch_object($table_dealer_info,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>

<?php if(isset($_GET[$unique])): ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
    <div class="x_title">
        <h2>Set a credit limit</h2>
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
    <form id="form2" name="form2" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
        <? require_once 'support_html.php';?>
        <div class="form-group" style="width: 100%">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Dealer Name:</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="hidden" id="<?=$unique?>" name="<?=$unique?>" value="<?=$$unique;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
                <input type="text" readonly value="<?=$dealer_master->dealer_name_e;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
            </div></div>

        <div class="form-group" style="width: 100%">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 50%">Current Account Balance:</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="credit_limit" readonly name="credit_limit" value="<?=find_a_field_sql('select sum(cr_amt-dr_amt) from journal where ledger_id='.$dealer_master->ledger_id);?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
            </div></div>

        <div class="form-group" style="width: 100%">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Credit Limit:<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="credit_limit"  required="required" name="credit_limit" value="<?=$credit_limit;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
            </div></div>

        <div class="form-group" style="width: 100%">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 50%">Credit Limit Duration<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="select2_single form-control" name="credit_limit_time" id="credit_limit_time" style="width: 100%; font-size: 12px">
                    <option></option>
                    <option value="Longtime">Unlimited</option>
                    <option value="For one time DO">Once only</option>
                </select></div></div>
        <div class="form-group" style="width: 100%">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 50%">Referance / Remarks:</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text"  name="remarks" value="<?=$remarks?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
            </div></div>


        <?php if($_GET[$unique]):  ?>
            <div class="form-group" style="margin-left:30%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="cancel" id="cancel" style="font-size:12px" class="btn btn-danger">Cancel</button>
                    <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Add Credit Limit</button>
                </div></div>
        <?php else : ?>
            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button></div></div> <?php endif; ?></form></div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>



<?php if(!isset($_GET[$unique])){ ?>
    <?=$crud->report_templates_with_title_and_class($res,$title,'12');?>
<?php } ?>
<?php require_once 'footer_content.php' ?>