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
             
             
             <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Serial<span class="required">*</span></label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="hidden" id="<?=$unique?>" style="width:100%"  required   name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                 <input type="text" id="sl" style="width:100%"  name="sl" value="<?=$sl?>" class="form-control col-md-7 col-xs-12" >
                 </div></div>
             

                 <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SO COde<span class="required">*</span></label>
                 <div class="col-md-6 col-sm-6 col-xs-12">               
                 <input type="text" id="<?=$unique_field?>" style="width:100%"  required   name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control col-md-7 col-xs-12" >
                 </div></div>
                 

                 <!--div class="form-group">
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
                     </div></div--->



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
                     
                     
                     
                     <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Service Status<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                        <select style="width: 100%" class="select2_single form-control" required name="PBI_JOB_STATUS" id="PBI_JOB_STATUS">
                             <option value="">Select</option>
                             <option value="In Service" <?php if($PBI_JOB_STATUS=='In Service'){echo 'selected'; } else echo ''; ?>>In Service</option>
                             <option value="Not In Service" <?php if($PBI_JOB_STATUS=='Not In Service'){echo 'selected'; } else echo ''; ?>>Not In Service</option>

                            

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







                




                 <br><br><br>



                 <br>

                 <?php if($_GET[$unique]){  ?>

                     <table style="width: 100%">
<tr>
                     <!--td>
                         <div class="form-group">
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                 <input  name="delete" type="submit" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");' id="delete" value="Delete"/>
                             </div></div>

                     </td-->


                         <td>
                             <div class="form-group">
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                     <button type="submit" name="modify" id="modify" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Modify</button>
                                 </div></div>
                         </td>

                         <!--td>
                     <div class="form-group">
                         <div class="col-md-6 col-sm-6 col-xs-12">
                             <button type="submit" name="record" id="record"  class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>Confirm & Send to HR </button>
                         </div></div></td--></tr>

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
                 
                     <a target="_new" class="btn btn-sm btn-default"  href="ims_so_info.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Active SO List</span>                     </a>
                 
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
                            <table  class="table table-striped table-bordered" style="width:100%; font-size:12px">
                      <thead>
                        <tr>
                        <th>SL</th>
                        <th>ERP Code</th>
                          <th>SO Code</th>
                          <th>SO Name</th>   
                          <!--th style="width:10%; text-align:center">Date of Joing</th-->
                          
                         <th style="text-align:center">Official <br>Contact</th>
                         <th style="text-align:center">Salary</th>
                         <th style="text-align:center">TA/DA</th>
                         <th style="text-align:center">IMS Target</th>
                         <th style="text-align:center">TSM Name</th>
                         <th style="text-align:center">Status</th>

                        </tr>
                      </thead>


                      <tbody>
                                <? 	$res=mysql_query('select p.'.$unique.',p.'.$unique.' as Code,p.'.$unique_field.' as SO_code,p.PBI_NAME,p.PBI_DOJ as Date_of_Join,p.PBI_PHONE as official_contact, p.PBI_JOB_STATUS as status,p.sl as serial,e.*,
								(SELECT PBI_NAME from '.$table.' where PBI_ID=p.tsm) as tsm 
								
								
								from 
								'.$table.' p ,
								essential_info e
								
								where 
								p.PBI_ID=e.PBI_ID and 
								p.PBI_JOB_STATUS in ("Not In Service") and 
								p.PBI_DESIGNATION like "60" group by p.PBI_ID order by p.sl asc');
                                while($PBI_ROW=mysql_fetch_object($res)){?>
                                
                                <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$PBI_ROW->PBI_ID?>', 'TEST!?', 900, 600)">
                                <td><?=$PBI_ROW->serial;?></td>
                                <td><?=$PBI_ROW->PBI_ID;?></td>
                                <td><?=$PBI_ROW->SO_code;?></td>
                                <td><?=$PBI_ROW->PBI_NAME;?></td>
                                <!--td><?=$PBI_ROW->Date_of_Join;?></td-->
                                <td><?=$PBI_ROW->official_contact;?></td>
                                
                                <td><?=$PBI_ROW->ESS_BASIC_SALARY;?></td>
                                <td><?=$PBI_ROW->PBI_TA_DA;?></td>
                                <td><?=$PBI_ROW->PBI_MONTHLY_IMS;?></td>
                                <td><?=$PBI_ROW->tsm?></td>
                                <td><?=$PBI_ROW->status?></td>
                                </tr>
                                
                                <?php } ?>    
                                </tbody></table>                 

                            </div>
                        </div></div>

                    <!-------------------End of  List View --------------------->

<?php } ?>

                    <!---page content----->





                

        

<?php require_once 'footer_content.php' ?>