<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Ledger Group';
$unique='group_id';
$unique_field='group_name';
$table='ledger_group';
$page="acc_ledger_group.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(prevent_multi_submit()) {
if(isset($_REQUEST['group_name'])||isset($_POST['group_id']))
{   $group_id		= mysqli_real_escape_string($conn, $_POST['group_id']);
    $group_names		= mysqli_real_escape_string($conn, trim($_POST['group_name']));
    $group_names		= str_replace("'","",$group_names);
    $group_names		= str_replace("&","",$group_names);
    $group_names		= str_replace('"','',$group_names);
    $group_classs	= mysqli_real_escape_string($conn, $_POST['group_class']);
    $group_sub_classs= mysqli_real_escape_string($conn, $_POST['group_sub_class']);
	if(isset($_POST['record']) )
    { if(!group_excess($group_names,$manual_group_code))
        {   $type=0;
            $msg='Given Group Name or Manual Group Code is already exists.';
        } else {
            if(!ledger_excess($group_names))
            {	$type=0;
                $msg='Given Name('.$group_names.') is already exists as Ledger.';
            } else {
				$_POST[group_id]=next_group_id($group_classs);
				$_POST[group_name]=$group_names;
				$_POST[group_sub_class]=$group_sub_classs;
				$_POST[group_class]=$group_classs;
				$_POST[com_id]=$com_id;
				$_POST[status]=1;
			    $crud->insert();
				$type=1;
                unset($_POST);	
				}}}

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
$res='select lg.'.$unique.',lg.'.$unique.' as Code,lg.'.$unique_field.',(select COUNT(ledger_id) from accounts_ledger where ledger_group_id=lg.group_id) as no_of_child,(select sub_class_name from acc_sub_class where id=lg.group_sub_class) as sub_class,ac.class_name as class,IF(lg.status=1, "Active", "Inactive") as status from '.$table.' lg,
                                acc_class ac
                                where 
                                lg.group_class=ac.class_id                                 
                                 order by lg.'.$unique;

$query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
    if(isset($_POST['deletedata'.$row->$unique]))
    { if($row->no_of_child == 0){
        mysqli_query($conn, ("DELETE FROM ".$table." WHERE ".$unique."=".$row->$unique.""));
    } else { echo "It has Child (".$row->no_of_child."). Hence you cannot delete the Ledger Group (".$row->ledger_group_id.")";}
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
        <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post"  style="font-size: 11px">
        <input type="text" id="<?=$unique?>" style="width:100%; font-size:11px" name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
        <? require_once 'support_html.php';?>
        <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Group Name :<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="group_name" style="width:100%; font-size:11px" name="group_name" value="<?=$group_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Sub Class</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%" name="group_sub_class" id="group_sub_class">
                                                    <option></option>
                                                    <?php foreign_relation('acc_sub_class', 'id', 'CONCAT(id," : ", sub_class_name)',  $group_sub_class, '1','order by sub_class_name'); ?>                  </select></select></div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Class<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <select class="select2_single form-control" style="width:100%" required name="group_class" id="group_class">
                                                <option value=""></option>
                                                <option <? if(substr($group_class,0,1)==substr($asset,0,1)) echo 'Selected ';?>value="<?=$asset?>">Asset</option>
                                                <option <? if(substr($group_class,0,1)==substr($income,0,1)) echo 'Selected ';?>value="<?=$income?>">Income</option>
                                                <option <? if(substr($group_class,0,1)==substr($expense,0,1)) echo 'Selected ';?>value="<?=$expense?>">Expense</option>
                                                <option <? if(substr($group_class,0,1)==substr($liabilities,0,1)) echo 'Selected ';?>value="<?=$liabilities?>">Liabilities</option>
                                            </select></div>
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