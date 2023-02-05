<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Sub Class';
$now=date('Y-m-d h:i:s');
$unique='id';
$unique_field='sub_class_name';
$table='acc_sub_class';
$page="acc_ledger_sub_class.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(prevent_multi_submit()) {
if(isset($_REQUEST['sub_class_name'])||isset($_REQUEST['id'])){
    $sub_class_name			= mysqli_real_escape_string($conn, $_REQUEST['sub_class_name']);
    $sub_class_type_id		= mysqli_real_escape_string($conn, $_REQUEST['sub_class_type_id']);
    $sub_class_id			= mysqli_real_escape_string($conn, $_REQUEST['id']);
    if(isset($_POST['record']) && !empty($sub_class_name))
    {
      $crud->insert();
      unset($_POST);
    }





//for Modify..................................



    if(isset($_POST['mgroup']))

    {

        $sql="UPDATE `acc_sub_class` SET 

		`sub_class_name` = '$sub_class_name',

		`sub_class_type_id` ='$sub_class_type_id'

		WHERE `id` = $sub_class_id LIMIT 1";

        $qry=mysql_query($sql);

        $type=1;

        echo $targeturl;

    }

//for Delete..................................



    if(isset($_POST['dgroup']))

    {



        $sql="UPDATE `acc_sub_class` SET 

		`status` = '0'

		WHERE `id` = $sub_class_id LIMIT 1";

        $query=mysql_query($sql);

        $type=1;

        $msg='Successfully Deleted.';

    }
}



//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$_GET['id'];
    $crud->delete($condition);
    unset($_GET['id']);
    $type=1;
    $msg='Successfully Deleted.';
    echo $targeturl;

}}


if(isset($_GET['id']))
{   $condition=$unique."=".$_GET['id'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$res='select sc.'.$unique.',sc.'.$unique.' as Code,sc.'.$unique_field.',ac.class_name as class,(select COUNT(group_id) from ledger_group where group_class=sc.class_id) as "No. of Child",u.fname as entry_by,sc.entry_at from 
                                '.$table.' sc,
                                 acc_class ac,
								 users u
                                 where 
                                 sc.class_id=ac.id and
								 sc.entry_by=u.user_id
                                 order by sc.'.$unique;	
?>





<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php';?> 


    
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
          <form name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post"> 
          <? require_once 'support_html.php';?> 
                                    <div class="form-group">
                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Class</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                           <select class="select2_single form-control" name="class_id" id="class_id" required />
                                                <option></option>
                                                <?php foreign_relation('acc_class', 'id', 'CONCAT(id," : ", class_name)',  $class_id, '1','order by class_name,priority'); ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Sub Class  Name :<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" name="sub_class_name" id="sub_class_name"  style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                                        </div>
                                    </div>   

								   <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="record" id="record" style="font-size:12px"  class="btn btn-primary">Record</button></div></div>
                                                
          </form>
        </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
        <?php if(!isset($_GET[$unique])):?> 
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>  
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>