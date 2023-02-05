<?php require_once 'support_file.php'; ?>
<?php //(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='API Received';
$unique='id';
$table=substr(basename($_SERVER['SCRIPT_NAME']),0,-4);
$page=basename($_SERVER['SCRIPT_NAME']);
$crud      =new crud($table);
$$unique = $_GET[$unique];
if(prevent_multi_submit()) {
if (isset($_POST['submit'])) {
    $_POST[build_at]=date("Y-m-d");
    $_POST[created_by]=$_SESSION[userid];
    $_POST[created_at]=date('Y-m-d H:s:i');
    $crud->insert();
    unset($_POST);
}
//for modify information ...........................
    if (isset($_POST['modify'])) {
        $crud->update($unique);
        $type = 1;
        unset($_POST);
    }
}
$res="select id,client_id,API_key,API_endpoint,valid_at,IF(status >=1, 'Active', 'Inactive') as status from ".$table." where 1";
$intercompany_ledger="Select i.client_id,CONCAT(i.client_id,' : ', a.ledger_name) from accounts_ledger a,acc_intercompany i where a.ledger_id=i.ledger_id";



// data query..................................
$condition=$unique."=".$$unique;
$data=db_fetch_object($table,$condition);
while (list($key, $value)=each($data))
{ $$key=$value;}
?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'header_content.php'; ?>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title;?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?=$page;?>" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                    <? require_once 'support_html.php';?>
                    <table align="center" style="width:100%">
                        <tr>
                            <th style="width:10%;">API ID</th><th style="width: 2%;">:</th>
                            <td style="width: 40%"><input type="text" id="id" readonly  required="required" name="id" value="<?=($$unique>0)? $$unique : find_a_field(''.$table.'','MAX(id)','1')+1;?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" ></td>
                            <th style="width:10%;">API Key</th><th style="width: 2%">:</th>
                            <td><input type="text" class="form-control col-md-7 col-xs-12" value="<?=$API_key?>" required="required" name="API_key" style="width: 90%; font-size: 11px; height: 33px" id="API_key"></input>
                            </td>
                        </tr>
                        <tr>
                            <th style="width:10%;">Client ID</th><th style="width: 2%;">:</th>
                            <td style="width: 30%"><select class="select2_single form-control" style="width:90%; font-size: 11px;" tabindex="-1" required="required"  name="client_id">
                                    <option></option>
                                    <?=advance_foreign_relation($intercompany_ledger,$client_id);?>
                                </select></td>
                            <th style="width:10%;">API Endpoint</th><th style="width: 2%">:</th>
                            <td><input type="text" class="form-control col-md-7 col-xs-12"  required="required" name="API_endpoint" id="API_endpoint" value="<?=$API_endpoint?>" style="width: 90%; font-size: 11px;"></td>
                        </tr>

                        <tr>
                            <th style="width:10%;">Valid at</th><th style="width: 2%;">:</th>
                            <td style="width: 30%"><input type="date" id="valid_at"  required="required" name="valid_at" value="<?=$valid_at?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle;margin-top:5px" ></td>
                            <th style="width:10%;">Status</th><th style="width: 2%">:</th>
                            <td><select class="select2_single form-control" name="status" id="status" style="width: 90%; font-size: 12px">
                                    <option></option>
                                    <option value="Longtime" <?php if($status=='1') echo 'selected' ?>>Active</option>
                                    <option value="For one time DO" <?php if($status=='0') echo 'selected'?>>Inactive</option>
                                </select></td>
                        </tr>
                    </table>
                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="submit" id="submit"  class="btn btn-primary" style="font-size: 11px">Record the Key</button>
                        </div></div>
                </form></div></div></div>
<?php if (!isset($_GET[$unique])) : ?>
    <?=$crud->report_templates_with_status($res,$title);?><?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();ob_flush(); ?>