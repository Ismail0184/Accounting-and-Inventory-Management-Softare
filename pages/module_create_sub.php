<?php
require_once 'support_file.php';
$title='Sub Module Create';

$now=time();
$unique='id';
$unique_field='zonecodesub';
$table='zone_sub';
$page="module_create_sub.php";
$crud      =new crud($table);
//$$unique = $_GET[$unique];

$jv_no=mysqli_query($conn,"SELECT MAX(zonecodesub) AS MAXCODE FROM zone_sub where 1");
$jv_noROW=mysqli_fetch_array($jv_no);
$zonecodeN=$jv_noROW[MAXCODE]+1;
$zonecodeNEXT=$zonecodeN;


$targeturl="<meta http-equiv='refresh' content='0;$_POST[url].php'>";
if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

{
$$unique = $_POST[$unique];
if(isset($_POST['record']))
{
    create_mysql_table($_POST[table_name]);
    create_php_file($_POST[url],$_POST[zonename],$_POST[table_name]);
    $crud->insert();
    $type=1;
    $msg='New Entry Successfully Inserted.';
    echo $targeturl;
    unset($_POST);
    unset($$unique);

}}}

?>

<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function OpenPopupCenter(pageURL, title, w, h) {
            var left = (screen.width - w) / 2;
            var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
            var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }
    </script>
    <SCRIPT language=JavaScript>
        function reload2(form)
        {
            var val=form.zonecodemain.options[form.zonecodemain.options.selectedIndex].value;
            self.location='module_create_sub.php?zonecodemain=' + val ;
        }
    </script>
<style>
    input[type=text]{
        font-size: 11px;    }
</style>
<?php require_once 'body_content.php'; ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo $title; ?></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <?php require_once 'support_html.php';?>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Under Module<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" id="module"  name="module">
                                                <option></option>
                                                <?php foreign_relation('module_department', 'id', 'CONCAT(id," : ", module_short_name)', $module, 'status in (\'1\')'); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Master Menu<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="zonecodemain" id="zonecodemain">
                                                <option></option>
                                                <? $sql_zonecodemain="SELECT  zm.zonecode,concat(zm.zonecode,' : ',zm.zonename,' (',md.module_short_name,')') FROM 						 
							zone_main zm,
							module_department md
							 where 							 
							 zm.module=md.module_id order by zm.zonename";
                                                advance_foreign_relation($sql_zonecodemain,$_GET[zonecodemain]);?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group" style="display: none">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sub Module Code<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="zonecodesub" style="width:100%"  required readonly  name="zonecodesub" value="<?=$zonecodeNEXT?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sub Module Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="zonename" style="width:100%"  required  name="zonename" value="" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sub Module Details<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="subzonedetails" style="width:100%"   name="subzonedetails" value="<?=$data->subzonedetails;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sub Module URL<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="url" style="width:100%"   name="url" value="<?=$data->zonedetails;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Database Table Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="table_name" style="width:100%"   name="table_name"  class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>


                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_GET[mood]){  ?>
                                                <!---a href="daily_production.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
                                                <button type="submit" name="updatePS" class="btn btn-success">Update Module Information</button>
                                            <?php   } else {?>
                                                <button type="submit" name="record"  class="btn btn-primary">Create Module</button>
                                            <?php } ?>
                                        </div></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo 'SUB MODULE LIST' ; ?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <? 	$res='select '.$unique.','.$unique.' as Code,'.$unique_field.',zonename,url from '.$table.' order by '.$unique;
                                echo $crud->link_report_popup($res,$link);?>
                            </div></div></div>


<?php require_once 'footer_content.php' ?>