<?php
require_once 'support_file.php';
$title='Master Menu Permission';
$now=time();
$unique='id';
$table="user_permissions";
$page='user_permission.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if(isset($_POST['add_permission']))
    {
        $zonecodes= $_POST['zonecode'];
        foreach ($zonecodes as $i) {
            $zonecodes = $i;
            $_POST[zonecode] = $zonecodes;
            $_POST['status'] = 1;
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['companyid'] = $_SESSION['companyid'];
            $_POST['powerby'] = $_SESSION['userid'];
            $_POST['powerdate'] = date('Y-m-d');
            $_POST['zonename'] = getSVALUE("zone_main", "zonename", "where zonecode='$_POST[zonecode]'");;
            $_POST[ip] = $ip;
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
	self.location='user_permission.php?user_id=' + val ;
}
</script>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>


<?php if(isset($_GET[$unique])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
           <div class="x_content">
                <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User List<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="user_id" id="user_id" onchange="javascript:reload(this.form)">
                                <option></option>
                                <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							user_activity_management u
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Master Menu<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select multiple class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="zonecode[]" id="zonecode">
                                <option></option>
                                <? $sql_zonecodemain="SELECT  zm.zonecode,concat(zm.zonecode,' : ',zm.zonename,' (',md.module_short_name,')') FROM 						 
							zone_main zm,
							module_department md,
							user_permissions_module upm
							 where 							 
							 zm.module=md.module_id and zm.zonecode not in (select zonecode from user_permissions where user_id=".$_GET[user_id].") and 
							 zm.module=upm.module_id and 
							 upm.user_id=".$_GET[user_id]."
							  
							  order by zm.zonename";
                                advance_foreign_relation($sql_zonecodemain,$zonecode);?>
                            </select>
                        </div>
                    </div>







                    <div class="form-group" align="center">
                        <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                            <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</a>
                                <button type="submit" name="edit" class="btn btn-primary" style="font-size: 12px">Permission Update</button>
                        </div></div>

                </form>
            </div>

        </div></div>
    <!-------------------End of  List View --------------------->
<?php } else { ?>


              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              <div class="x_title">
              <h2><?=$title?></h2>
              <div class="clearfix"></div>
              </div>

              <div class="x_content">
               <div class="col-md-9 col-sm-9 col-xs-12">
                   <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User List<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="user_id" id="user_id" onchange="javascript:reload(this.form)">
                                <option></option>
                                <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							user_activity_management u
							 where 
							 1 and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID and p.PBI_JOB_STATUS in ('In Service')		 
							  order by p.PBI_NAME";
                                advance_foreign_relation($sql_user_id,$_GET[user_id]);?>
                            </select>
                        </div>
                      </div>
               
               
               
               
               
               <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Master Menu<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select multiple class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="zonecode[]" id="zonecode">
                                <option></option>
                                <? $sql_zonecodemain="SELECT  zm.zonecode,concat(zm.zonecode,' : ',zm.zonename,' (',md.module_short_name,')') FROM 						 
							zone_main zm,
							module_department md,
							user_permissions_module upm
							 where 							 
							 zm.module=md.module_id and zm.zonecode not in (select zonecode from user_permissions where user_id=".$_GET[user_id].") and 
							 zm.module=upm.module_id and 
							 upm.user_id=".$_GET[user_id]."
							  
							  order by zm.zonename";
                                advance_foreign_relation($sql_zonecodemain,$zonecode);?>
                            </select>
                        </div>
                      </div>
               
                      





                   <div class="form-group">
                       <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                           <?php if($_GET[type]){ ?>
                               <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</button>
                               <button type="submit" name="edit" class="btn btn-success" style="font-size: 12px">Edit</button>
                           <?php } else { ?>
                               <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</a>
                               <button type="submit" name="add_permission" class="btn btn-primary" style="font-size: 12px">Add Permission</button>
                           <?php } ?>
                       </div></div>

               </form>
               </div></div></div></div>

              

              

              

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2><? //$title?></h2>
                <div class="clearfix"></div>
                </div>


                  <div class="x_content">
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width: 100%; font-size: 11px">
                   <thead>
                    <tr>
                     <th style="width: 1%">SL</th>
                     <th style="">Menu Name</th>
                     <th style="">Module / Department</th>
                     <th style="">User ID</th>
                     <th style="">Permission By</th>
                     <th style="">Create Date</th>
                     <th style="" align="center">Status</th>
                        </tr>
                      </thead>


                      <tbody>

                       <?php
				$result=mysqli_query($conn, "Select p.*,p.status as power_status,z.*,u.*,md.* from 
				user_permissions p ,
				zone_main z,
				user_activity_management u,
				module_department md
				where 
				u.user_id=p.user_id and 
				p.user_id='$_GET[user_id]' and 				
				p.zonecode=z.zonecode and 
				z.module=md.module_id
				order by p.id");
				while($row=mysqli_fetch_object($result)){
				$i=$i+1; ?>
                      <tr onclick="DoNavPOPUP('<?=$row->id;?>', 'TEST!?', 600, 700)">
                        <td><?php echo $i; ?></td>
                        <td><?=$row->zonename; ?></td>
                        <td><?=$row->modulename; ?></td>
                        <td><?=$row->fname; ?></td>
                        <td><?=$zonename=getSVALUE("user_activity_management", "fname", "where user_id='".$row->powerby."'");?></td>
                        <td><?=$row->powerdate; ?></td>
                        <td><? if($row->power_status>0) echo 'Active'; else echo 'Inactive'; ?></td>





</td--->
</tr>
<?php } ?></tbody></table>
                  </div></div></div> <?php } ?>
<?php require_once 'footer_content.php' ?>