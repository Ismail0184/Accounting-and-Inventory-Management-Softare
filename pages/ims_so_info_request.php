 <?php

require_once 'support_file.php';
$title="Unchecked SO List";
$now=time();
$unique='PBI_ID';
$unique_field='PBI_ID_UNIQUE';
$table="personnel_basic_info_requisition";
$page="ims_so_info_request.php";
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
		$_POST[PBI_ID]='';
		$_POST[PBI_ORG]='2';
		$_POST[PBI_DEPARTMENT]='3';
		$_POST[PBI_DESIGNATION]='60';
		
        $table_input="personnel_basic_info";
        $crud      =new crud($table_input);
        $crud->insert();
		
		$a=mysql_query("SELECT MAX(PBI_ID) AS PBI_ID_GET FROM personnel_basic_info");
		$ROWpbi=mysql_fetch_array($a);
		
		$_POST[PBI_ID]=$ROWpbi['PBI_ID_GET'];
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





 <?php if(isset($_GET[$unique])){ ?>

 <!-- input section-->

 <div class="col-md-12 col-sm-12 col-xs-12">

     <div class="x_panel">

         <div class="x_title">
             <h2><?=$title;?></h2>
             <div class="clearfix"></div>
         </div>

         <div class="x_content">

             <br />



             <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
             <?require_once 'support_html.php';?>

                 <div class="form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?=$unique_field?><span class="required">*</span></label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="hidden" id="<?=$unique?>" style="width:100%"  required   name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                 <input type="text" id="<?=$unique_field?>" style="width:100%"  required   name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control col-md-7 col-xs-12" >
                 </div></div>
                 

                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Dealer Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select style="width: 100%" class="select2_single form-control" required name="PBI_DEALER_ID" id="PBI_DEALER_ID">
                             <option value="">Select</option>
                             <?php
                             $result=mysql_query("SELECT  * from dealer_info where 1 ");
                             while($row=mysql_fetch_array($result)){  ?>
                             <option  value="<?=$row[dealer_code]; ?>" <?php if($PBI_DEALER_ID==$row[dealer_code]) echo 'selected' ?>><?=$row[dealer_name_e]; ?></option>
                             <?php } ?>
                         </select>
                     </div></div>


                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sub Dealer Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <select style="width: 100%" class="select2_single form-control" name="IMS_sub_dealer" id="IMS_sub_dealer">
                             <option value="">Select</option>
                             <?php
                             $result=mysql_query("SELECT  * from sub_db_info where 1 ");
                             while($row=mysql_fetch_array($result)){  ?>
                                 <option  value="<?=$row[sub_db_code]; ?>" <?php if($IMS_sub_dealer==$row[sub_db_code]) echo 'selected' ?>><?=$row[sub_dealer_name_e]; ?></option>
                             <?php } ?>
                         </select>
                     </div></div>



                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SO Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_NAME" style="width:100%"  required   name="PBI_NAME" value="<?=$PBI_NAME?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date of Joining<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <input type="text" id="PBI_DOJ" style="width:100%"  required   name="PBI_DOJ" value="<?= date('m/d/y' , strtotime($PBI_DOJ));?>" class="form-control col-md-7 col-xs-12" >



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

                         <select style="width: 100%" class="select2_single form-control" required name="PBI_RELIGION" id="PBI_RELIGION">

                             <option value="">Select</option>

                             <option value="Islam" <?php if($PBI_RELIGION=='Islam'){echo 'selected'; } else echo ''; ?>>Islam</option>

                             <option value="Hinduism" <?php if($PBI_RELIGION=='Hinduism'){echo 'selected'; } else echo ''; ?>>Hinduism</option>

                             <option value="Others" <?php if($PBI_RELIGION=='Others'){echo 'selected'; } else echo ''; ?>>Others</option>

                         </select>

                     </div></div>





                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Accounts Number<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <input type="text" id="ESS_BANK_ACC_NO" style="width:100%"     name="ESS_BANK_ACC_NO" value="<?=$ESS_BANK_ACC_NO?>" class="form-control col-md-7 col-xs-12" >

                     </div></div>



                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Name<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <select style="width: 100%" class="select2_single form-control" required name="ESS_BANK" id="ESS_BANK">

                             <option value="">Select</option>

                             <?php

                             $result=mysql_query("SELECT  distinct BANK_NAME FROM bank 

							 where 1 order by BANK_NAME");

                             while($row=mysql_fetch_array($result)){  ?>

                                 <option  value="<?=$row[BANK_NAME]; ?>" <?php if($ESS_BANK==$row[BANK_NAME]) echo 'selected' ?>><?=$row[BANK_NAME]; ?></option>

                             <?php } ?>

                         </select>

                     </div></div>





                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Branch<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <select style="width: 100%" class="select2_single form-control" required name="ESS_BANK_BRANCH" id="ESS_BANK_BRANCH">

                             <option value="">Select</option>

                             <?php

                             $result=mysql_query("SELECT  distinct BRANCH FROM bank 

							 where 1 order by BRANCH");

                             while($row=mysql_fetch_array($result)){  ?>

                                 <option  value="<?=$row[BRANCH]; ?>" <?php if($ESS_BANK_BRANCH==$row[BRANCH]) echo 'selected' ?>><?=$row[BRANCH]; ?></option>

                             <?php } ?>

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

                     </div></div>







                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Permanent Address<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <textarea type="text" id="PBI_PERMANENT_ADD" style="width:100%"     name="PBI_PERMANENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$PBI_PERMANENT_ADD;?></textarea>

                     </div></div>





                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Persent Address<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <textarea type="text" id="PBI_PRESENT_ADD" style="width:100%"     name="PBI_PRESENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$PBI_PRESENT_ADD;?></textarea>

                     </div></div>





                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Educational Qualification<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <select style="width: 100%" class="select2_single form-control" required name="PBI_EDU_QUALIFICATION" id="PBI_EDU_QUALIFICATION">

                             <option value="">Select</option>

                             <?php

                             $result=mysql_query("SELECT  * FROM edu_qua 

							 where 1 order by EDU_QUA_DESC");

                             while($row=mysql_fetch_array($result)){  ?>

                                 <option  value="<?=$row[EDU_QUA_DESC]; ?>" <?php if($PBI_EDU_QUALIFICATION==$row[EDU_QUA_DESC]) echo 'selected' ?>><?=$row[EDU_QUA_DESC];?></option>

                             <?php } ?>

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
                         <select class="select2_single form-control" style="width:100%;margin-top: 2px;" tabindex="-1"   name="tsm" id="tsm" >

                             <option></option>
                             <?php
                             $result=mysql_query("SELECT  p.*,d.*,des.* FROM 
							 
							personnel_basic_info p,
							department d,
							designation des
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and 
							 p.PBI_DESIGNATION=des.DESG_ID				 
							  order by p.PBI_NAME");
                             while($row=mysql_fetch_array($result)){  ?>
                                 <option  value="<?=$row[PBI_ID]; ?>" <?php if($tsm==$row[PBI_ID]) echo 'selected' ?>><?=$row[PBI_ID_UNIQUE]; ?>#><?=$row[PBI_NAME];?>#> (<?=$row[DESG_SHORT_NAME];?> - <?=$row[DEPT_SHORT_NAME];?>)</option>
                             <?php } ?>
                         </select>
                     </div></div>





                 <br><br><br>



                 <br>

                 <?php if($_GET[$unique]){  ?>

                     <table style="width: 100%">
<tr>
                     <td>
                         <div class="form-group">
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                 <input  name="delete" type="submit" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");' id="delete" value="Delete"/>
                             </div></div>

                     </td>


                         <td>
                             <div class="form-group">
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                     <button type="submit" name="modify" id="modify" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Modify</button>
                                 </div></div>
                         </td>

                         <td>
                     <div class="form-group">
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <button type="submit" name="record" id="record"  class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>Confirm & Send to HR </button>
                         </div></div></td></tr>

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

                                <div class="clearfix"></div>

                            </div>



                            <div class="x_content">

                                <? 	$res='select '.$unique.','.$unique.' as Code,'.$unique_field.' as SO_code,PBI_NAME,PBI_DOJ as "Date of Join",PBI_PHONE as official_contact,ESS_BASIC_SALARY as salary,status from '.$table.' where status in ("UNCHECKED") order by '.$unique;

                                echo $crud->link_report_popup($res,$link);?>

                                <?=paging(10);?>

                            </div>



                        </div></div>

                    <!-------------------End of  List View --------------------->

<?php } ?>

                    <!---page content----->





                

        

<?php require_once 'footer_content.php' ?>