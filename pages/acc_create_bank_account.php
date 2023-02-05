<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Create Bank Account';
$unique='id';
$unique_field='BANK_CODE';
$table='bank_account';
$page="acc_create_bank_account.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(prevent_multi_submit()) {
    if(isset($_POST['record']) )
    {
        $_POST[branch] = find_a_field('bank','BRANCH','BANK_CODE='.$_POST[BANK_CODE]);
        $_POST[status]=1;
        $crud->insert();
        $type=1;
        unset($_POST);

    }}

if(isset($_POST['modify']))
{   $_POST['group_name']=$group_names;
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    unset($_POST);}


if(isset($_POST['cancel'])){echo "<script>window.close(); </script>";}
$sql='select * from config_group_class limit 1';
$query=mysqli_query($conn, $sql);
if(mysqli_num_rows($query)>0){
    $g_class=mysqli_fetch_object($query);
    $asset=$g_class->asset_class;
    $income=$g_class->income_class;
    $expense=$g_class->expanse_class;
    $liabilities=$g_class->liabilities_class;
}

if(isset($$unique)>0)
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}
$res='Select ba.id,ba.account_name,b.BANK_NAME,b.branch,ba.account_number,ba.routing_no from bank b,bank_account ba where b.BANK_CODE=ba.BANK_CODE';
?>


<?php require_once 'header_content.php'; ?>
<?php if(isset($_GET[$unique])):
    require_once 'body_content_without_menu.php'; else :
    require_once 'body_content.php'; endif;  ?>
<?php if(isset($_GET[$unique])): ?>
<div class="col-md-12 col-sm-12 col-xs-12">
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
                            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post"  style="font-size: 11px">
                                <input type="hidden" id="<?=$unique?>" style="width:100%; font-size:11px" name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                                <? require_once 'support_html.php';?>


                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Select Bank</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" style="width:100%" name="BANK_CODE" id="BANK_CODE">
                                            <option></option>
                                            <?php foreign_relation('bank', 'BANK_CODE', 'CONCAT(BANK_NAME," : ", BRANCH)',  $BANK_CODE, '1','order by BANK_NAME'); ?>
                                        </select></div></div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Account Name</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" style="width:100%" name="account_name" id="account_name">
                                            <option></option>
                                            <?php foreign_relation('bank_account_name', 'account_name', 'account_name',  $account_name, '1'); ?>
                                        </select></div></div>


                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Account Number<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><input type="text" id="account_number" style="width:100%; font-size:11px" name="account_number" value="<?=$account_number?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Routing Number<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><input type="text" id="routing_no" style="width:100%; font-size:11px" name="routing_no" value="<?=$routing_no?>" class="form-control col-md-7 col-xs-12" >
                                    </div>
                                </div>

                                <?php if($_GET[$unique]):  ?>

                                    <div class="form-group" style="width: 100%">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%; font-size:11px" name="status" id="status">
                                                <option value="1"<?=($status=='1')? 'Selected' : '' ?>>Active</option>
                                                <option value="1"<?=($status=='0')? 'Selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div></div>

                                    <div class="form-group" style="margin-left:30%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="cancel" id="cancel" style="font-size:12px" class="btn btn-danger">Cancel</button>
                                            <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                                        </div></div>
                                <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button></div></div> <?php endif; ?>

                            </form></div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
            <?php if(!isset($_GET[$unique])):?>
                <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
            <?php endif; ?>
            <?=$html->footer_content();mysqli_close($conn);?>
            <?php ob_end_flush();
            ob_flush(); ?>                                  