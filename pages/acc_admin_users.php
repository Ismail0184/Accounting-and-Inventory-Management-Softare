<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Users";

$now=time();
$unique='user_id';
$unique_field='fname';
$table="users";
$page="acc_admin_users.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];



if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))

//for insert..................................
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $_POST[picture_url]=$link.$_POST[PBI_ID].'.jpeg';
            $_POST[group_for]=$_SESSION[usergroup];
            if($_POST[gander]=='Female') {
                $_POST[gander] = '0';
            } else {
                $_POST[gander]='1';
            }
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
if(isset($_GET[PBI_ID]))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$res='select '.$unique.','.$unique.' as User_id,username as user_name,'.$unique_field.' as display_name,email,level,entry_date,expire_date,account_status from '.$table.' where department in ("Accounts") order by '.$unique.' desc';
?>



<?php require_once 'header_content.php'; ?>
    <style>
        input[type=text]{
            font-size: 11px;
        }
        input[type=email]{
            font-size: 11px;
        }
        input[type=password]{
            font-size: 11px;
        }
        input[type=tel]{
            font-size: 11px;
        }
    </style>
    <SCRIPT language=JavaScript>
        function reload(form)
        {
            var val=form.PBI_ID.options[form.PBI_ID.options.selectedIndex].value;
            self.location='<?=$page;?>?PBI_ID=' + val ;
        }
    </script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[$unique])){ ?>
<?=$crud->report_templates_with_title_and_class($res,$title,'12');?>
<?php } ?>
<?=$html->footer_content();?>