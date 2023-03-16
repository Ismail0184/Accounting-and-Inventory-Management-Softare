<?php
 require_once 'support_file.php';
 $title='Permission :: Plan / Warehouse';
$now=time();
$unique='id';
$table="user_plant_permission";
$page='user_plant_permission.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if(isset($_POST['add_permission']))
    {
        $warehouse_ids= $_POST['warehouse_id'];
        foreach ($warehouse_ids as $i) {
            $warehouse_ids = $i;
            $_POST['warehouse_id']=$warehouse_ids;
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['powerby'] = $_SESSION['userid'];
            $_POST['user_id'] = $_GET[user_id];
            $_POST['status'] = '1';
            $_POST['power_date'] = date('Y-m-d H:s:i');
            $_POST[ip]=$ip;
            $crud->insert();
            $type=1;
            $msg='New Entry Successfully Inserted.';

        }
        unset($_POST);
        unset($$unique);
    }

//for modify..................................
    if(isset($_POST['modify']))
    {   $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }



//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$_GET[$unique];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{	var val=form.user_id.options[form.user_id.options.selectedIndex].value;
	self.location='<?=$page;?>?user_id=' + val ;
}</script>



<?php require_once 'body_content.php'; ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              <div class="x_title">
              <h2><?=$title?></h2>
              <div class="clearfix"></div>
              </div>
              <div class="x_content">
               <div class="col-md-9 col-sm-9 col-xs-12">
               <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                   <?php if($_GET[$unique]){ ?>
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Active User<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="hidden" name="<?=$unique?>" id="<?=$unique?>" value="<?=$$unique;?>">
                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="user_id" id="user_id" onchange="javascript:reload(this.form)">
                                <option></option>
                                <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							users u
							 where 
							 1 and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID			 
							  order by p.PBI_NAME";
                                advance_foreign_relation($sql_user_id,$user_id);?>
                            </select>
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Plan / Warehouse<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control"  style="width:100%; font-size: 11px;" tabindex="-1" required="required"  name="warehouse_id" id="warehouse_id">
                               <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, '1'); ?>
                            </select>
                        </div>
                      </div>


                   <div class="form-group">
                       <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status<span class="required">*</span></label>
                       <div class="col-md-6 col-sm-6 col-xs-12">
                           <select  class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="status" id="status">
                               <option></option>
                               <option value="1" <?php if($status=='1') echo 'selected'; else echo '';?>>Active</option>
                               <option value="0" <?php if($status=='0') echo 'selected'; else echo '';?>>Inactive</option>
                           </select>
                       </div>
                   </div>



                   <div class="form-group">
                       <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                               <button type="submit" class="btn btn-danger" name="deleted" id="deleted" style="font-size: 12px; float: left">Delete Permission</button>
                               <button type="submit" name="modify" id="modify" class="btn btn-primary"  style="font-size: 12px; float: right">Modify Permission</button>
                           </div></div>


                   <?php } else { ?>




                   <div class="form-group">
                       <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Active User<span class="required">*</span>
                       </label>
                       <div class="col-md-6 col-sm-6 col-xs-12">
                           <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="user_id" id="user_id" onchange="javascript:reload(this.form)">
                               <option></option>
                               <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							users u
							 where 
							 1 and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID			 
							  order by p.PBI_NAME";
                               advance_foreign_relation($sql_user_id,$_GET[user_id]);?>
                           </select>
                       </div>
                   </div>
                   <div class="form-group">
                       <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Plan / Warehouse<span class="required">*</span>
                       </label>
                       <div class="col-md-6 col-sm-6 col-xs-12">
                           <select class="select2_single form-control" multiple style="width:100%; font-size: 11px;" tabindex="-1" required="required"  name="warehouse_id[]" id="warehouse_id">
                               <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, 'warehouse_id not in (select warehouse_id from user_plant_permission where user_id='.$_GET[user_id].')'); ?>
                           </select>
                       </div>
                   </div>
                   
                   <div class="form-group">
                       <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</a>
						<button type="submit" name="add_permission" class="btn btn-primary" style="font-size: 12px">Add Plan / Warehouse</button>
                        </div></div>
                   <?php } ?>
               </form>
               </div></div></div></div>

<?php if(isset($_GET[user_id])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <? 	$res='select p.'.$unique.',p.'.$unique.' as Code,w.warehouse_name,u.fname as User_name,p.power_date,p.status from 
                '.$table.' p, 
                users u,
                warehouse w 
                 where 
                p.user_id="'.$_GET[user_id].'" and 
                p.user_id=u.user_id and
                p.warehouse_id=w.warehouse_id
                 order by p.'.$unique;
                echo $crud->link_report_popup($res,$link);?>
            </div>
        </div></div>
<?php } ?>

<?php require_once 'footer_content.php' ?>