<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Add New Unit';

$now=time();
$title='';

$unique='id';
$unique_field='unit_name';
$table='unit_management';
$page="unit.php";

$crud      =new crud($table);

$$unique = $_GET[$unique];
if(isset($_POST[$unique_field]))
{
    $$unique = $_POST[$unique];
//for Record..................................

    if(isset($_POST['record']))

    {
        $_POST['entry_at']=time();
        $_POST['entry_by']=$_SESSION['userid'];
		$_POST[status] = 1;
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);

    }





//for Modify..................................



    if(isset($_POST['modify']))

    {
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['user']['id'];
        $crud->update($unique);
        $type=1;
        echo $targeturl;

    }

//for Delete..................................



    if(isset($_POST['delete']))

    {	$condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';

    }



}

if(isset($$unique))

{

    $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}

}
$res='select '.$unique.','.$unique.' as Code,'.$unique_field.',unit_detail,IF(status=1, "Active", "Inactive") as status from '.$table.' order by '.$unique;
?>


<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>
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

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size:11px">
                                    <?require_once 'support_html.php';?>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Unit Short Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="unit_name" style="width:100%"  required   name="unit_name" value="<?=$unit_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Unit Detail<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea name="unit_detail" id="unit_detail" class="form-control col-md-7 col-xs-12"><?=$unit_detail;?></textarea>
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
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New Unit</button></div></div> <?php endif; ?> </form> </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>

<?php if(!isset($_GET[$unique])):?> 
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>                  
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>