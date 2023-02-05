 <?php

require_once 'support_file.php';
$title="SO Information";
$now=time();
$unique='PBI_ID';
$unique_field='PBI_ID_UNIQUE';
$table="personnel_basic_info";
$table_essential_info='essential_info';
$page="ims_so_info.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";



if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))



//for insert..................................

{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))

    {


        $_POST[PBI_JOB_STATUS]='In Service';
        $table_input="personnel_basic_info";
        $crud      =new crud($table_input);
        $crud->insert();
        $table_essential_info="essential_info";
        $crud      =new crud($table_essential_info);
        $crud->insert();
        mysql_query("UPDATE $table SET status='CHECKED' where PBI_ID='$_GET[PBI_ID]'");
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    

    

//for modify..................................

if(isset($_POST['modify']))
{   $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $d =$_POST[PBI_DOJ];
    $_POST[PBI_DOJ]=date('Y-m-d' , strtotime($d));
    $crud->update($unique);
	
	
	$crud      =new crud($table_essential_info);
	$crud->update($unique);
	
	
	
    $type=1;
    //echo $targeturl;
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

$res='select p.'.$unique.',p.'.$unique.' as ERP_Code,p.'.$unique_field.' as SO_code,p.PBI_NAME as SO_name,p.so_type,p.PBI_PHONE as official_contact,e.ESS_BASIC_SALARY as Salary,e.PBI_TA_DA as "TA/DA	",e.PBI_MONTHLY_IMS as MONTHLY_IMS,
								(SELECT PBI_NAME from '.$table.' where PBI_ID=p.tsm) as TSM_Name, (select sub_dealer_name_e from sub_db_info where sub_db_code=p.sub_db_code)	as sub_dealer			
								
								from 
								'.$table.' p ,
								essential_info e								
															
								where 
								p.PBI_ID=e.PBI_ID and 
								p.PBI_JOB_STATUS in ("In Service") and 				
								
								p.PBI_DESIGNATION like "60" group by p.PBI_ID order by tsm,p.PBI_ID asc'
?>







<?php require_once 'header_content.php'; ?>
 <style>
     input[type=text] {
         font-size: 11px;
     }
     input[type=number] {
         font-size: 11px;
     }
     textarea {
         font-size: 11px;
     }
 </style>
 <style>
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #ddd;}
    </style>
<?php require_once 'body_content.php'; ?>





 <?php if(isset($_GET[$unique])){ ?>

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
             <? require_once 'support_html.php';?>

             
                              <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sub DB<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="sub_db_code" id="sub_db_code">
                             <option></option>
                             <?php foreign_relation('sub_db_info', 'sub_db_code', 'CONCAT(sub_db_code," : ", sub_dealer_name_e)', $sub_db_code, '1'); ?>
                         </select>
                     </div></div>



             

                 <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SO COde<span class="required">*</span></label>
                 <div class="col-md-6 col-sm-6 col-xs-12">               
                 <input type="text" id="<?=$unique_field?>" style="width:100%"     name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control col-md-7 col-xs-12" >
                 </div></div>





                 <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SO Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_NAME" style="width:100%"     name="PBI_NAME" value="<?=$PBI_NAME?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date of Joining<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="date" id="PBI_DOJ" style="width:100%; font-size:11px"     name="PBI_DOJ" value="<?=$PBI_DOJ;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Official Contact<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_PHONE" style="width:100%"     name="PBI_PHONE" value="<?=$PBI_PHONE?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Personal Number<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_MOBILE" style="width:100%"     name="PBI_MOBILE" value="<?=$PBI_MOBILE?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Religion<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                        <select style="width: 100%" class="select2_single form-control"  name="PBI_RELIGION" id="PBI_RELIGION">
                             <option value="">Select</option>
                             <option value="Islam" <?php if($PBI_RELIGION=='Islam'){echo 'selected'; } else echo ''; ?>>Islam</option>
                             <option value="Hinduism" <?php if($PBI_RELIGION=='Hinduism'){echo 'selected'; } else echo ''; ?>>Hinduism</option>
                             <option value="Others" <?php if($PBI_RELIGION=='Others'){echo 'selected'; } else echo ''; ?>>Others</option>
                         </select>
                     </div></div>
                     
                     
                     
                      <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Permanent Address<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea type="text" id="PBI_PERMANENT_ADD" style="width:100%; font-size: 11px"     name="PBI_PERMANENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$PBI_PERMANENT_ADD;?></textarea>
                     </div></div>


                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Persent Address<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea type="text" id="PBI_PRESENT_ADD" style="width:100%; font-size: 11px"     name="PBI_PRESENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$PBI_PRESENT_ADD;?></textarea>
                     </div></div>


                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Educational Qualification<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="PBI_EDU_QUALIFICATION" id="PBI_EDU_QUALIFICATION">
                             <option></option>
                             <?php foreign_relation('edu_qua', 'EDU_QUA_DESC', 'CONCAT(EDU_QUA_CODE," : ", EDU_QUA_DESC)', $PBI_EDU_QUALIFICATION, '1'); ?>
                         </select>
                     </div></div>





                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Person (if any Emergency)<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="EMR_FULL_NAME" style="width:100%"     name="EMR_FULL_NAME" class="form-control col-md-7 col-xs-12" value="<?=$EMR_FULL_NAME;?>" >
                     </div></div>





                     <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Emergency Contact Number<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                     <input type="text" id="EMR_MOBILE" style="width:100%"     name="EMR_MOBILE" class="form-control col-md-7 col-xs-12" value="<?=$EMR_MOBILE;?>" >
                     </div></div>



                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">TSM Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="tsm" id="tsm">
                             <option></option>
                             <? $sql_tsm="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and p.PBI_DESIGNATION not in ('60')				 
							  order by p.PBI_NAME";
                             advance_foreign_relation($sql_tsm,$tsm);?>
                         </select>
                     </div></div>
                     
                     
                     
                     <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Service Status<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                        <select style="width: 100%" class="select2_single form-control"  name="PBI_JOB_STATUS" id="PBI_JOB_STATUS">
                             <option value="">Select</option>
                             <option value="In Service" <?php if($PBI_JOB_STATUS=='In Service'){echo 'selected'; } else echo ''; ?>>In Service</option>
                             <option value="Not In Service" <?php if($PBI_JOB_STATUS=='Not In Service'){echo 'selected'; } else echo ''; ?>>Not In Service</option>
                         </select>
                     </div></div>

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SO Type<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select style="width: 100%" class="select2_single form-control"  name="so_type" id="so_type">
                             <option value="">Select</option>
                             <option value="SO" <?php if($so_type=='SO'){echo 'selected'; } else echo ''; ?>>SO</option>
                             <option value="CD" <?php if($so_type=='CD'){echo 'selected'; } else echo ''; ?>>Commission DB</option>
                         </select>
                     </div></div>


<?php 
// data query..................................

if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table_essential_info,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

?>



                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Accounts Number<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BANK_ACC_NO" style="width:100%"     name="ESS_BANK_ACC_NO" value="<?=$ESS_BANK_ACC_NO?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>



                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ESS_BANK" id="ESS_BANK">
                             <option></option>
                             <?php foreign_relation('bank', 'distinct BANK_NAME', 'CONCAT(BANK_CODE," : ", BANK_NAME)', $ESS_BANK, '1'); ?>
                         </select>
                     </div></div>





                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Branch<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ESS_BANK_BRANCH" id="ESS_BANK_BRANCH">
                             <option></option>
                             <?php foreign_relation('bank', 'distinct BRANCH', 'CONCAT(BANK_CODE," : ", BRANCH)', $ESS_BANK_BRANCH, '1'); ?>
                         </select>
                     </div></div>





                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Routing / SWIFT<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BANK_SWIFT" style="width:100%"     name="ESS_BANK_SWIFT" value="<?=$ESS_BANK_SWIFT?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>







                 <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Monthly IMS Target<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_MONTHLY_IMS" style="width:100%"     name="PBI_MONTHLY_IMS" value="<?=$PBI_MONTHLY_IMS?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>







                 <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Salary<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BASIC_SALARY" style="width:100%"     name="ESS_BASIC_SALARY" value="<?=$ESS_BASIC_SALARY?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>







                 <div class="form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">TA/DA<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_TA_DA" style="width:100%"     name="PBI_TA_DA" value="<?=$PBI_TA_DA?>" class="form-control col-md-7 col-xs-12" >
                     </div></div






                 ><br>

                 <?php if($_GET[$unique]){  ?>

                     <table align="center" style="width: 100%">
<tr>
<td>
                             <div class="form-group">
                                 <div class="col-md-6 col-sm-6 col-xs-12" style="margin-left: 35%">
                                     <button type="submit" name="modify" id="modify" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Modify SO Info</button>
                                 </div></div>
                         </td>
                     </tr>

                     </table>
                 <?php } else {?>
                 <?php } ?>
             </form>
         </div>
     </div>
 </div>



 <?php } if(!isset($_GET[$unique])){ ?>

                    <!-------------------list view ------------------------->

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                 <div class="input-group pull-right">
                 
                     <a target="_new" class="btn btn-sm btn-default"  href="ims_so_info_inactive.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Inactive SO List</span>                     </a>
                 
                     <a target="_new" class="btn btn-sm btn-default"  href="ims_so_info_new.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Add New SO</span>                     </a>
                 
                     <a target="_new" class="btn btn-sm btn-default"  href="ims_so_info_request.php">
                         <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Unchecked SO List</span>
                     </a>

                 </div>

             </ul>
                            <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                            <?=$crud->link_report_voucher($res,$title);?>
                            </div></div></div>

<?php } ?>
<?php require_once 'footer_content.php' ?>