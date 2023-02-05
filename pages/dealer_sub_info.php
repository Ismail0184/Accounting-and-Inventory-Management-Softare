 <?php
require_once 'support_file.php';
$title="Outlet Info";

$now=time();
$unique='sub_dealer_code';
$unique_field='sub_dealer_name';
$table="dealer_sub_info";
$page="dealer_sub_info.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
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
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>



<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                                        </a-->
                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    
                                    <div class="form-group" style="display:none">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?=$unique?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="<?=$unique?>" style="width:100%"  name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>                                   
                                    
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12">Company / Dealer Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="dealer_code" id="dealer_code" >

                      <option></option>
                      <?php
                      $result=mysql_query("SELECT  * from dealer_info where canceled in ('YES') order by dealer_name_e");
                      while($row=mysql_fetch_array($result)){  ?>
                          <option  value="<?=$row[dealer_code]; ?>" <?php if($dealer_code==$row[dealer_code]) echo 'selected' ?>><?=$row[dealer_code]; ?>#><?=$row[dealer_name_e];?></option>
                      <?php } ?>
                  </select>
                                    </div></div>
                                    
                                    
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Dealer / Outlet Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="<?=$unique_field?>" style="width:100%"  required   name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    
                                    
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12">Contact Person<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="contact_person" style="width:100%"  name="contact_person" value="<?=$contact_person?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    
                                    
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12">Designation<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="contact_person_desig" style="width:100%"  name="contact_person_desig" value="<?=$contact_person_desig?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    
                                    
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12">Contact Number<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="contact_number" style="width:100%"  name="contact_number" value="<?=$contact_number?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    
                                    
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12">Email ID<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="email_id" style="width:100%"  name="email_id" value="<?=$email_id?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    
                                    
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12">Address<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  id="address" style="width:100%"  name="address"  class="form-control col-md-7 col-xs-12" ><?=$address;?></textarea>
                                    </div></div>
                                    
                          

                                        <br>
                                        <?php if($_GET[$unique]){  ?>                                            
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-success">Modify</button>
                                            </div></div>
                                            <? if($_SESSION['userid']=="10019"){?>                                            
                                             <div class="form-group" style="margin-left:40%;">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                             <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                                             </div></div>                                             
                                             <? }?>                                         
                                            <?php } else {?>                                           
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  class="btn btn-success">Add New </button>
                                            </div></div>                                                                                        
                                            <?php } ?> 


                                </form>
                                </div>
                                </div>
                                </div>

                    <?php if(!isset($_GET[$unique])){ ?>
                    <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>List of Outlet</h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <? 	$res='select sd.'.$unique.',sd.'.$unique.' as Code, (select dealer_name_e from dealer_info where dealer_code=sd.dealer_code) as Company_name, '.$unique_field.',sd.contact_person,sd.contact_person_desig as designation,sd.contact_number,sd.email_id as email, address as address from '.$table.' sd order by sd.'.$unique;
                                echo $crud->link_report_popup($res,$link);?>
                                <?=paging(10);?>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->


                
        
<?php require_once 'footer_content.php' ?>