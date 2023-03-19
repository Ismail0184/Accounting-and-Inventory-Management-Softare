<?php require_once 'support_file.php'; ?>
<?=(($_SESSION["userlevel"])==1)? '' : header('Location: page_403.php');
$now=time();
$unique='warehouse_id';
$unique_field='warehouse_name';
$table="warehouse";
$page="developer_create_warehouse.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$title='Create Company';

if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $_POST['status']=1;
            $_POST['use_type'] = 'WH';
            $_POST['warehouse_type'] = 'Both';
            $crud->insert();
            unset($_POST);
        }

//for modify..................................
        if(isset($_POST['modify']))
        {
            $_POST['edit_at']=time();
            $_POST['edit_by']=$_SESSION['userid'];
            $crud->update($unique);
            $type=1;
            echo "<script>self.opener.location = '$page'; self.blur(); </script>";
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

if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$res="SELECT warehouse_id,IF(status=1, 'Active','Inactive') as status from ".$table."";
$result=mysqli_query($conn, $res);
while($data=mysqli_fetch_object($result)){
    $id=$data->ZONE_CODE;

    if(isset($_POST['deletedata'.$id]))
    { $del=mysqli_query($conn, "Delete from ".$table." where ".$unique."=".$id."");}
}?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=320,left = 230,top = 5");}
</script>
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
                            <h5 class="modal-title">Add New
                                <button class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <?php endif; ?>
                            <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Warehouse Name <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="hidden" class="form-control" style="font-size: 11px" name="warehouse_id" value="<?=$warehouse_id?>">
                                        <input type="text" class="form-control" style="font-size: 11px" required name="warehouse_name" value="<?=$warehouse_name?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <textarea type="text" class="form-control" required style="font-size: 11px" name="address"><?=$address?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">VAT Address <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <textarea type="text" class="form-control" required style="font-size: 11px" name="VMS_address"><?=$VMS_address?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Contact Person Name</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" name="ap_name" value="<?=$ap_name?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Designation</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" name="ap_designation" value="<?=$ap_designation?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Contact Number</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" name="contact_no" value="<?=$contact_no?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Email</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <input type="text" class="form-control" style="font-size: 11px" name="email" value="<?=$email?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Ledger ID <span class="required text-danger">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <select class="select2_single form-control" style="width:98%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id" id="ledger_id">
                                            <option></option>
                                            <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, 'status=1'); ?>
                                        </select>
                                    </div>
                                </div>

                                <?php if(isset($_GET[$unique])): ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="status">
                                                <option></option>
                                                <?=foreign_relation('status', 'id', 'name', $status, 'status=1'); ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <hr>

                                <?php if($_GET[$unique]):  ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-danger" onclick="self.close()">Close</button>
                                            <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                                        </div></div>
                                <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <a name="modify"  style="font-size:12px" class="btn btn-danger" data-dismiss="modal">Close</a>
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New</button></div></div> <?php endif; ?>
                            </form>
                        </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
            <?php if(!isset($_GET[$unique])):?>
                <?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
            <?php endif; ?>
            <?=$html->footer_content();mysqli_close($conn);?>
            <?php ob_end_flush();ob_flush(); ?>
