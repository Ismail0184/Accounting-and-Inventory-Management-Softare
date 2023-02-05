 <?php
require_once 'support_file.php';
$title="IMS Date";

$now=time();
$unique='id';
$unique_field='ims_date';
$table="ims_date";
$page="ims_date.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $d =$_POST[$unique_field];
        $_POST[$unique_field]=date('Y-m-d' , strtotime($d));
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
    $d =$_POST[$unique_field];
    $_POST[$unique_field]=date('Y-m-d' , strtotime($d));
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
 <style>
     input[type=text]{
         font-size: 11px;
     }
 </style>
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
                               

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <?require_once 'support_html.php';?>
                                    
                                    <div class="form-group" style="display: none;">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?=$unique?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="<?=$unique?>" style="width:100%"     name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>                                   
                                    
                                    
                                    <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Month Start<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="month_start" style="width:100%"  <?php if($_GET[$unique]){  ?>readonly <?php } ?> required   name="month_start" value="<?=$month_start ?>" class="form-control col-md-7 col-xs-12" ></div></div>
        
        
                                 <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Month End<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="month_end" style="width:100%"  <?php if($_GET[$unique]){  ?>readonly <?php } ?> required   name="month_end" value="<?=$month_end ?>" class="form-control col-md-7 col-xs-12" ></div></div>


            
        
        <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Month<span class="required">*</span></label>
<div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="month" style="width:100%" <?php if($_GET[$unique]){  ?>readonly <?php } ?>  required   name="month" value="<?=$month;?>" class="form-control col-md-7 col-xs-12" ></div></div>
        
        
        <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Year<span class="required">*</span></label>
<div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="year" style="width:100%" <?php if($_GET[$unique]){  ?>readonly <?php } ?>  required   name="year" value="<?=$year;?>" class="form-control col-md-7 col-xs-12" ></div></div>


                                    <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">IMS Active Month<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="ims_target_active_month" style="width:100%"    name="ims_target_active_month" value="<?=$ims_target_active_month;?>" class="form-control col-md-7 col-xs-12" ></div></div>




                            <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Working Days<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="working_day" style="width:100%" <?php if($_GET[$unique]){  ?>readonly <?php } ?>  required   name="working_day" value="<?=$working_day ?>" class="form-control col-md-7 col-xs-12" ></div></div>
        
        
        <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Working Day Passed<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="working_day_passed" style="width:100%"  required   name="working_day_passed" value="<?=$working_day_passed;?>" class="form-control col-md-7 col-xs-12" ></div></div>
        
        
        <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">IMS Date<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="ims_date" style="width:100%"  required   name="ims_date" value="<?php if($$unique>0){ echo date('m/d/Y' , strtotime($ims_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" ></div></div>
                                    
                                   
                                        <?php if($_GET[$unique]){  ?>                                            
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify</button>
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
                                            <button type="submit" name="record" id="record"  class="btn btn-primary">Add New </button>
                                            </div></div>                                         


                                </form>
                                </div>
                                </div>
                                </div>
 <?php } ?>

                    <?php if(!isset($_GET[$unique])){ ?>
                    <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <? 	$res='select '.$unique.','.$unique.' as Code,'.$unique_field.',month_start,month_end,working_day,working_day_passed,month,year,ims_target_active_month as ims_active_month from '.$table.' where month='.$month.' order by '.$unique;
                                echo $crud->link_report_popup($res,$link);?>
                               </div>
                        </div></div>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->


                
        
<?php require_once 'footer_content.php' ?>
 <script>
     $(document).ready(function() {
         $('#ims_date').daterangepicker({

             singleDatePicker: true,
             calender_style: "picker_4",

         }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
         });
     });
 </script>
