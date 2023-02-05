<?php require_once 'support_file.php'; ?>
<?php //(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='API Key Generate';
$unique='id';
$table=substr(basename($_SERVER['SCRIPT_NAME']),0,-4);
$page=basename($_SERVER['SCRIPT_NAME']);
$crud      =new crud($table);
$$unique = $_GET[$unique];
if(prevent_multi_submit()) {
if (isset($_POST['submit'])) {
    $_POST[API_key]=$_POST[uuid];
    $_POST[build_at]=date("Y-m-d");
    $_POST[created_by]=$_SESSION[userid];
    $_POST[created_at]=date('Y-m-d H:s:i');
    $crud->insert();
    unset($_POST);
}}

$res="select id,client_id,API_key,API_endpoint,build_at,valid_at,IF(status >=1, 'Active', 'Inactive') as status from ".$table." where 1";
?>

<?php require_once 'header_content.php'; ?>
    <script>function generateUUID() { // Public Domain/MIT
        var d = new Date().getTime();//Timestamp
        var d2 = ((typeof performance !== 'undefined') && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16;//random number between 0 and 16
            if(d > 0){//Use timestamp until depleted
                r = (d + r)%16 | 0;
                d = Math.floor(d/16);
            } else {//Use microseconds since page-load if supported
                r = (d2 + r)%16 | 0;
                d2 = Math.floor(d2/16);
            }
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }   var onClick = function(){
            document.getElementById('uuid').textContent = generateUUID();
        }
        onClick();

    </script>

<?php require_once 'body_content.php'; ?>
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
                            <td style="width: 30%"><input type="text" id="id" readonly  required="required" name="id" value="<?=($$unique>0)? $$unique : find_a_field(''.$table.'','MAX(id)','1')+1;?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle" ></td>
                            <th style="width:10%;">API Key</th><th style="width: 2%">:</th>
                            <td><textarea type="button" class="form-control col-md-7 col-xs-12" readonly required="required" name="uuid" style="width: 60%; font-size: 11px; height: 33px" id="uuid"></textarea>
                                <button type="button" name="generateUUID" id="generateUUID" onclick="onClick();" class="btn btn-success" style="font-size:11px; margin-left: 5px">Generate a Key</button>
                            </td>
                        </tr>
                        <tr>
                            <th style="width:10%;">Client ID</th><th style="width: 2%;">:</th>
                            <td style="width: 30%"><input type="text" id="client_id"  required="required" name="client_id" value="<?=$client_id?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle;margin-top:5px" ></td>
                            <th style="width:10%;">API Endpoint</th><th style="width: 2%">:</th>
                            <td><input type="text" class="form-control col-md-7 col-xs-12"  required="required" name="API_endpoint" id="API_endpoint" value="<?=$API_endpoint?>" style="width: 81%; font-size: 11px;"></td>
                        </tr>

                        <tr>
                            <th style="width:10%;">Valid at</th><th style="width: 2%;">:</th>
                            <td style="width: 30%"><input type="date" id="valid_at"  required="required" name="valid_at" value="<?=$valid_at?>" class="form-control col-md-7 col-xs-12" style="width: 90%; font-size: 11px;vertical-align:middle;margin-top:5px" ></td>
                            <th style="width:10%;">Status</th><th style="width: 2%">:</th>
                            <td><input type="text" class="form-control col-md-7 col-xs-12"  required="required" name="status" id="status" value="<?=$status?>" style="width: 81%; font-size: 11px;"></td>
                        </tr>
                    </table>
                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="submit" id="submit"  class="btn btn-primary" style="font-size: 11px">Record the Key</button>
                        </div></div>
                </form></div></div></div>
<?=$crud->report_templates_with_status($res,$title);?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();ob_flush(); ?>