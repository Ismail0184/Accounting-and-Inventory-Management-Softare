<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Cost Center";

$now=time();
$unique='id';
$unique_field='center_name';
$table="cost_center";
$page="acc_cost_center.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    //echo $targeturl;
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

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	
$sql='select '.$unique.','.$unique.' as Code,'.$unique_field.' from '.$table.' order by '.$unique;	
?>



<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>











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
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size:11px">
                                    <?php require_once 'support_html.php';?>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Category<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%" name="category_id" id="category_id">
                                                <option></option>
                                                <?=foreign_relation('cost_category', 'id', 'CONCAT(id," : ", category_name)', $_POST[under], '1','1'); ?>
                                            </select>
                                        </div></div>

                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Cost Center<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="center_name" style="width:100%"  required   name="center_name" value="<?=$center_name;?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    
                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%"></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <button type="submit" name="record" id="record"  class="btn btn-primary">Record</button>
                                                        </div></div>
                                    

                                        </form>
                                        </div>
                                        </div>
                                        </div>
                                        </div>


<?=$crud->report_templates_with_add_new($sql,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?=$html->footer_content();?>