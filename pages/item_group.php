<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Add New Group';
$unique='group_id';
$unique_field='group_name';
$table='item_group';
$page="item_group.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(isset($_POST[$unique_field]))

{

    $$unique = $_POST[$unique];
    if(isset($_POST['record']))

    {

        $_POST['entry_at']=time();
        $_POST['entry_by']=$_SESSION['user']['id'];
        $_POST['ledger_group_id']=$inventory;

        $min=number_format(($inventory*1000000000000)+100000000, 0, '.', '');
        $max=number_format(($inventory*1000000000000)+1000000000000, 0, '.', '');
        $_POST[$unique]=number_format(next_value('group_id','item_group','100000000',$min,$min,$max), 0, '.', '');
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
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;

        echo $targeturl;
    }


//for Delete..................................

    if(isset($_POST['delete']))
    {
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
    }}



if(isset($$unique))
{
    $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$res='select '.$unique.','.$unique.' as group_code,group_name from '.$table.' order by '.$unique;
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
                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">


                                    <div class="form-group">
                                        <? require_once 'support_html.php';?>
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Group Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            
                                            <input type="text" id="group_name" style="width:100%"  required   name="group_name" value="<?php echo $group_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>


                                    <?php if($_GET[$unique]):  ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify Group</button>
                                        </div></div>
                                    <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New Group</button></div></div> <?php endif; ?>

                                </form> </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>

<?php if(!isset($_GET[$unique])):?> 
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>                  
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>