<?php
 require_once 'support_file.php'; 
 $title='Permission ::: Sub Menu';

$now=time();
$unique='id';
$table="user_permissions2";
$page='user_permission2.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if(isset($_POST['add_permission']))
    {   $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['companyid'] = $_SESSION['companyid'];
        $_POST['powerby'] = $_SESSION['userid'];
        $_POST['powerdate'] = date('Y-m-d H:s:i');
        $_POST[zonenamemain]=find_a_field('zone_main','zonename','zonecode='.$_POST[zonecodemain].'');
        $_POST[zonename]=find_a_field('zone_sub','zonename','zonecodesub='.$_POST[zonecode].'');
        $_POST[ip]=$ip;
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }

//for Delete..................................
    if(isset($_POST['deleted']))
    {

        $crud = new crud($table);
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
	self.location='user_permission2.php?user_id=' + val ;
}
function reload2(form)
{
	var val=form.zonecodemain.options[form.zonecodemain.options.selectedIndex].value;
	self.location='user_permission2.php?user_id=<?=$_GET[user_id]?>&zonecodemain=' + val ;
}
</script>



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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Master Menu<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="zonecodemain" id="zonecodemain" onchange="javascript:reload2(this.form)">
                                <option></option>
                                <? $sql_zonecodemain="SELECT  zm.zonecode,concat(zm.zonecode,' : ',zm.zonename,' (',md.module_short_name,')') FROM 						 
							zone_main zm,
							module_department md,
							user_permissions p
							 where 							 
							 zm.module=md.module_id and 
							 zm.zonecode=p.zonecode and 
							 p.user_id=".$_GET[user_id]."
							 group by p.zonecode
							 order by md.module_id,zm.zonename";
                                advance_foreign_relation($sql_zonecodemain,$_GET[zonecodemain]);?>
                            </select>
                        </div>
                      </div>
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sub Menu<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="zonecode" id="zonecode">
                                <option></option>
                                <?php foreign_relation('zone_sub', 'zonecodesub', 'CONCAT(zonecodesub,"-", zonename)', $zonecode, 'zonecodemain='.$_GET[zonecodemain].' and zonecodesub not in (select zonecode from user_permissions2 where user_id='.$_GET[user_id].')','order by zonename'); ?>
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

                        </div></div></form><br>
               </div></div></div></div>



<?php require_once 'footer_content.php' ?>