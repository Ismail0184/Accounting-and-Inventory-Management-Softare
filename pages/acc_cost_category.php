<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Cost Center Category';
$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and a.company_id='".$_SESSION['companyid']."' and a.section_id in ('400000','".$_SESSION['sectionid']."')";
}

$now=time();
$unique='id';
$unique_field='category_name';
$table='cost_category';
$page="accounts_cost_category.php";
$crud      =new crud($table);
$$unique = @$_GET[$unique];

if(isset($_POST[$unique_field]))

{
    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }

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
}}


if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	
$sql="select a.".$unique.",a.".$unique." as Code,a.".$unique_field.",s.section_name as branch,
 IF(a.status=1, 'Active',IF(a.status='SUSPENDED','SUSPENDED','Inactive')) as status
 from ".$table." a, company s
 where 
 a.section_id=s.section_id".$sec_com_connection." order by a.".$unique;
?>
<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=500,height=500,left = 383,top = -1");}</script>

</head>
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
        <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
                                    <? require_once 'support_html.php';?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Category Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="category_name" style="width:100%"  required   name="category_name" value="<?=$category_name;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>

                                       <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%"></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                  <button type="submit" name="record" id="record" onclick="return checkUserName()" class="btn btn-primary">Record</button>
                                                        </div></div>
                                                        </form></div>
      </div>
    </div>
  </div>

<?=$crud->report_templates_with_add_new($sql,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?=$html->footer_content();?>