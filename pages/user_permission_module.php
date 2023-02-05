<?php
 require_once 'support_file.php'; 
 $title='Module Permission';

$now=time();
$unique='id';
$table="user_permissions_module";
$page='user_permission_module.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if(isset($_POST['add_permission']))
    {   $module_ids= $_POST['module_id'];
        foreach ($module_ids as $i) {
            $module_ids = $i;
            $_POST['module_id'] = $module_ids;
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['powerby'] = $_SESSION['userid'];
            $_POST['power_date'] = date('Y-m-d');
            $_POST[ip] = $ip;
            $_POST[status] = '1';
            $crud->insert();
            $type = 1;
            $msg = 'New Entry Successfully Inserted.';
        }
        unset($_POST);
        unset($$unique);
    }

//for Delete..................................
    if(isset($_POST['deleted']))
    {

        $crud = new crud($table_deatils);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);

        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.user_id.options[form.user_id.options.selectedIndex].value;
	self.location='<?=$page;?>?user_id=' + val ;
}
function reload2(form)
{
	var val=form.module_id.options[form.module_id.options.selectedIndex].value;
	self.location='<?=$page;?>?user_id=<?=$_GET[user_id]?>&module_id=' + val ;
}
</script>



<?php require_once 'body_content.php'; ?>

              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              <div class="x_title">
              <h2><?=$title?></h2>
              <div class="clearfix"></div>
              </div>

                  




               <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Active User<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="user_id" id="user_id" onchange="javascript:reload(this.form)">
                                <option></option>
                                <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							user_activity_management u
							 where p.PBI_JOB_STATUS='In Service' and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID			 
							  order by p.PBI_NAME";
                                advance_foreign_relation($sql_user_id,$_GET[user_id]);?>
                            </select>
                        </div>
                      </div>

               <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Module List<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select multiple class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="module_id[]" id="module_id">
                                <option></option>
                                <? $module_query="SELECT  md.id,concat(md.id,' : ',md.modulename) FROM 
                              module_department md
							 where 							 
							 md.status>0 and md.module_id not in (select module_id from user_permissions_module where user_id=".$_GET[user_id].") 
							  order by md.module_id";
                                advance_foreign_relation($module_query,$module_id);?>
                            </select>
                        </div>
                      </div>



                   <div class="form-group">
                   <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                     <?php if($_GET[$unique]){ ?>
                        <button type="cancel" class="btn btn-primary" style="font-size: 11px">Cancel</button>
						<button type="submit" name="edit" class="btn btn-success" style="font-size: 11px">Edit</button>
                        <?php } else { ?>
                        <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 11px">Cancel</a>
						<button type="submit" name="add_permission" class="btn btn-primary" style="font-size: 11px">Add Permission</button>
							<?php } ?>
                   </div></div></form>
              </div></div>


<?php if(isset($_GET[user_id])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <? 	$res='select p.'.$unique.',p.'.$unique.' as Code,m.module_short_name,m.modulename as module_name,u.fname as User_Name, p.status as Active_status from 
                '.$table.' p, 
                module_department m,
                user_activity_management u 
                 where 
                p.user_id="'.$_GET[user_id].'" and 
                p.module_id=m.module_id and 
                p.user_id=u.user_id
                 order by p.'.$unique;
                echo $crud->link_report_popup($res,$link);?>
            </div>
        </div></div>
<?php } ?>


<?php require_once 'footer_content.php' ?>