<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Sub Ledger';
$page="acc_sub_ledger.php";
$now=time();
$separator	= $_SESSION['separator'];
$table="sub_ledger";
$table_ledger="accounts_ledger";
$unique="sub_ledger_id";
$unique_ledger="ledger_id";
$crud      =new crud($table);
$$unique=$_GET[$unique];
$$unique_ledger=$_GET[$unique];

if(isset($_REQUEST['name'])||isset($_REQUEST['id']))

{   $id=$_REQUEST['id'];
    $name		= mysqli_real_escape_string($conn, $_REQUEST['name']);
    $name		= str_replace("'","",$name);
    $name		= str_replace("&","",$name);
    $name		= str_replace('"','',$name);
    $under		= mysqli_real_escape_string($conn, $_REQUEST['under']);
    $balance	= mysqli_real_escape_string($conn, $_REQUEST['balance']);
    //end

    if(isset($_POST['record']))

    {

        $check="select sub_ledger_id from sub_ledger where sub_ledger='$name'";
        if(mysqli_num_rows(mysqli_query($conn, $check))>0)

        {   $aaa=mysqli_num_rows(mysqli_query($conn, $check));
            $ledger_id=$aaa[0];
            $type=0;
            $msg='Given Name('.$name.') is already exists.';

        } else {
            $sql_check="select ledger_group_id, balance_type, budget_enable from accounts_ledger where ledger_id='".$under."' limit 1";
            $sql_query=mysqli_query($conn, $sql_check);
            if(mysqli_num_rows($sql_query)>0){
                $ledger_data=mysqli_fetch_row($sql_query);
                if(!ledger_excess($name))
                {

                    $type=0;
                    $msg='Given Name('.$name.') is already exists as Ledger.';

                }  else  {

                    $sub_ledger_id=number_format(next_sub_ledger_id($under), 0, '.', '');
                    sub_ledger_generate($sub_ledger_id,$name, $under, $balance, $now, $proj_id);
                    $ledger_type='sub-ledger';
                    ledger_generate($sub_ledger_id,$name,$ledger_data[0],'',$ledger_data[1],'','', time(),$proj_id,$ledger_data[2],$ledger_type);
                    $type=1;
                    $msg='New Entry Successfully Inserted.';
                    unset($_POST);
                } }  else  {

                $type=0;
                $msg='Invalid Accounts Ledger!!!';
            }}}}

if(isset($_POST['modify']))
{   $_POST['sub_ledger']=$name;
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    mysqli_query($conn, "UPDATE ".$table_ledger." SET ledger_name='".$name."',status='".$_POST['status']."' where ledger_id=".$$unique);
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

if(isset($_POST['cancel'])){echo "<script>window.close(); </script>";}
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}
$res='select a.sub_ledger_id,a.sub_ledger_id,a.sub_ledger, (select ledger_name from accounts_ledger where ledger_id=a.ledger_id) as ledger_name, c.group_name,(select COUNT(ledger_id) from journal where ledger_id=a.sub_ledger_id) as has_transactions,
IF(a.status=1, "Active",IF(a.status="SUSPENDED", "SUSPENDED","Inactive")) as status
FROM sub_ledger a,accounts_ledger b,ledger_group c where
a.sub_ledger_id=b.ledger_id and b.ledger_group_id=c.group_id';
$query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
    if(isset($_POST['deletedata'.$row->$unique]))
    { if($row->has_transactions == 0){
        mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$row->$unique.""));
        mysqli_query($conn, ("DELETE FROM ".$table_ledger." WHERE ".$unique_ledger."=".$row->$unique.""));
    } else { echo "<h4 style='color:red'>It has transactions (".$row->has_transactions."). Hence you cannot delete the Sub-Ledger ID (".$row->sub_ledger_id.")</h4>";}
        unset($_POST);
    }} // end of deletedata
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
    <form id="form2" name="form2" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
        <? require_once 'support_html.php';?>
        <div class="form-group" style="width: 100%">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Sub Ledger:<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="hidden" id="<?=$unique?>" name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
                <input type="text" id="name"  required="required" name="name" value="<?=$sub_ledger?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
            </div></div>


        <div class="form-group" style="width: 100%">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Under Ledger<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="select2_single form-control" required name="under" id="under" style="width: 100%; font-size: 12px">
                    <option value=""></option>
                    <?=foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', ($_POST["under"]>0) ? $_POST["under"] : $ledger_id, '1','order by ledger_id,ledger_name'); ?>   </select></div></div>


        <?php if($_GET[$unique]):  ?>
            <div class="form-group" style="width: 100%">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="select2_single form-control" style="width:100%; font-size:11px" name="status" id="status">
                        <option value="1"<?=($status=='1')? ' Selected' : '' ?>>Active</option>
                        <option value="0"<?=($status=='0')? ' Selected' : '' ?>>Inactive</option>
                        <option value="SUSPENDED"<?=($status=='SUSPENDED')? ' Selected' : '' ?>>SUSPENDED</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-left:30%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="cancel" id="cancel" style="font-size:12px" class="btn btn-danger">Cancel</button>
                    <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                </div></div>
        <?php else : ?>
            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button></div></div> <?php endif; ?></form></div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
<?php if(!isset($_GET[$unique])):?>
    <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>
