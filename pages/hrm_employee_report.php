 <?php
require_once 'support_file.php';
$title="Employee Report";

$now=time();
$unique='PBI_ID';
$unique_field='PBI_ID_UNIQUE';
$table="personnel_basic_info";
$page="hrm_employee_report.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
 $jobinfo="hrm_employee_job_info.php".'?'.$unique.'='.$$unique;
 $targeturlJOBINFO="<meta http-equiv='refresh' content='0;$jobinfo'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['goback']))
    {
        echo "$targeturlJOBINFO";
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
	
$res='select p.'.$unique.',p.'.$unique.' as Code,p.'.$unique_field.' as Employee_ID,p.PBI_NAME as Name, (select DESG_SHORT_NAME from designation where DESG_ID=p.PBI_DESIGNATION) as designation,
                                 (select DEPT_DESC from department where DEPT_ID=p.PBI_DEPARTMENT) as Department,DATE_FORMAT(p.PBI_DOJ, "%M %d, %Y") as DOJ,p.PBI_EMAIL,p.PBI_MOBILE as mobile,p.PBI_JOB_STATUS as status
                                 from '.$table.' p where p.PBI_JOB_STATUS in ("In Service","Not In Service") order by p.'.$unique;	
$family_member_view='SELECT f.id,f.fi_name as name,r.RELATION_NAME,f.fi_contact_number from hrm_emp_family_info f,relation r where f.fi_relationship=r.RELATION_CODE';                                 

$education_view='SELECT e.id,en.EXAM_NAME as Education,e.ei_passing_year as passed_year,e.ei_grade as Grade,i.institute_name as Institute  from 
edu_exam_title en,hrm_emp_education_info e,institute i where e.ei_education_degree=en.EXAM_CODE and e.ei_institute=i.institute_id'; 
 
 $education_view1='SELECT e.id,en.EXAM_NAME as Education,e.ei_passing_year as Passed_Year,e.Grade,i.institute_name as Institute 
from hrm_emp_education_info e,institute i,edu_exam_title en
 where e.ei_institute=i.institute_id and e.ei_education_degree=en.EXAM_CODE'; 

 $employment_history_view='SELECT em.id,em.eh_company_name as company_name,em.eh_job_title as job_title,em.eh_start_date as start_date,em.eh_end_date as end_date 
 from hrm_emp_employment_history em where 1'; 
$hrm_emp_supervisor_info='SELECT hes.id,concat(p.PBI_ID_UNIQUE," : ",p.PBI_NAME) as supervisor,hes.level,hes.effective_date from hrm_emp_supervisor_info hes, personnel_basic_info p where hes.supervisor=p.PBI_ID';

$supv="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 							 
							personnel_basic_info p,
							department d,
							essential_info e
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and
							 p.PBI_ID=e.PBI_ID and 
							 e.ESS_JOB_LOCATION=1 group by p.PBI_ID							 
							  order by p.PBI_NAME";
 $hrm_emp_language_skill='SELECT el.id,concat(l.language_code," : ",l.language_name) as language,p.proficiency_name as proficiency from hrm_emp_language_skill el, languages l,proficiency p where 
 el.ls_language=l.id and el.ls_proficiency=p.id';

$hrm_emp_passport_info='SELECT p.id,c.en_short_name as country,p.pi_passport_no as Passport_no,p.pi_issued_date as Issued_date,p.pi_expire_date as expiry_date from hrm_emp_passport_info p, apps_nationality c where p.pi_country=c.num_code';
$hrm_emp_talent_info ='SELECT ht.id,t.talent_type as talent,ht.pi_talent_details as Talent_Details from hrm_emp_talent_info ht, talent t where ht.pi_talent_ype=t.id';
$hrm_emp_bank_account_info ='SELECT ba.id,b.BANK_NAME,ba.bai_account_no as account_no,ba.bai_account_name as account_name,ba.bai_routing_no as routing_no from hrm_emp_bank_account_info ba, bank b where ba.bai_bank=b.BANK_CODE';
$hrm_emp_social_media_info ='SELECT hsm.id,sm.name,hsm.sm_profile_name as profile_name,hsm.sm_profile_URL as profile_URL from hrm_emp_social_media_info hsm, social_media sm where hsm.sm_id=sm.sm_id';

?>



 <?php require_once 'header_content.php'; ?>
 <script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 230,top = 5");}
 </script>

 <style>
     input[type=text]{font-size: 11px;}
     select{font-size: 11px;}.rcom{color:red}
 </style>



<?php require_once 'body_content.php'; ?>

 <?php if(isset($_GET[$unique])): ?>
<div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right"></div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                            <?php else: ?>

<div class="modal fade" id="addModal" >
    <div class="modal-dialog modal-md" style="width:90%">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Employee
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
          </h5>
        </div>
        <div class="modal-body">
        <div class="clearfix"></div>
        <?php endif; ?>
 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
 <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Personal</a></li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Contact</a></li>
                          <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Job Info</a></li>
                          
                          <?php if($_SESSION['entry_PBI_ID']>0):?>
                          <li role="presentation" class=""><a href="#tab_content4" id="tab" role="profile-tab4" data-toggle="tab" aria-expanded="false">Family</a></li>
                          <li role="presentation" class=""><a href="#tab_content5" role="tab" id="profile-tab5" data-toggle="tab" aria-expanded="false">Education</a></li>
                          <li role="presentation" class=""><a href="#tab_content6" role="tab" id="profile-tab6" data-toggle="tab" aria-expanded="false">Employment</a></li>
                          <li role="presentation" class=""><a href="#tab_content7" role="tab" id="profile-tab7" data-toggle="tab" aria-expanded="false">Supervisor</a></li>
                          <li role="presentation" class=""><a href="#tab_content8" id="tab" role="profile-tab8" data-toggle="tab" aria-expanded="false">Documents</a></li>
                          <li role="presentation" class=""><a href="#tab_content9" role="tab" id="profile-tab9" data-toggle="tab" aria-expanded="false">Language</a></li>
                          <li role="presentation" class=""><a href="#tab_content10" role="tab" id="profile-tab10" data-toggle="tab" aria-expanded="false">Passport</a></li>
                          <li role="presentation" class=""><a href="#tab_content11" role="tab" id="profile-tab11" data-toggle="tab" aria-expanded="false">Talent</a></li>
                          <li role="presentation" class=""><a href="#tab_content12" role="tab" id="profile-tab12" data-toggle="tab" aria-expanded="false">Bank A/c</a></li>
                          <li role="presentation" class=""><a href="#social_media" role="tab" id="profile-tab12" data-toggle="tab" aria-expanded="false">Social Media</a></li>
                          <?php endif; ?>

                        
                        </ul>
                        <div id="myTabContent" class="tab-content">

                        
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        
                        <table style="width: 100%;">
                        <tr><td>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">ERP ID <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_ID_UNIQUE"  required="required" readonly name="PBI_ID_UNIQUE" value="<? if($$unique>0) { echo  $$unique; } else { echo find_a_field($table,'max('.$unique.')+1','1');
                                                if($$unique<1) $$unique = 1;}?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Employee Code <span class="required rcom">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_ID_UNIQUE"  required="required" name="PBI_ID_UNIQUE" value="<?$PBI_ID_UNIQUE;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div></div>
                            

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Password <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="password"  required="required" name="password" value="<?$password;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Father's Name</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_FATHER_NAME" name="PBI_FATHER_NAME" value="<?=$PBI_FATHER_NAME;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Mother's Name</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_MOTHER_NAME" name="PBI_MOTHER_NAME" value="<?=$PBI_MOTHER_NAME;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div></div>

                                <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Spouse Name</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_SPOUSE" name="PBI_SPOUSE" value="<?=$PBI_SPOUSE;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div></div>
                            </td>
                                



                            <td>
                            <div class="form-group" style="width: 100%;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Date of Birth</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="PBI_DOB"   name="PBI_DOB" value="<?=$PBI_DOB;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Blood Group <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ESSENTIAL_BLOOD_GROUP" id="ESSENTIAL_BLOOD_GROUP">
                            <option></option>
                            <?=foreign_relation('blood', 'id', 'CONCAT(id," : ", name)',$ESSENTIAL_BLOOD_GROUP, '1','order by id'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Religion <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="PBI_RELIGION" id="PBI_RELIGION">
                            <option></option>
                            <?=foreign_relation('hrm_religion', 'id', 'CONCAT(id," : ", religion)',$PBI_RELIGION, '1','order by id'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Sex <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="PBI_SEX" id="PBI_SEX">
                            <option selected="selected"><?=$PBI_SEX?></option>
                            <option>Male</option>
                            <option>Female</option><option>Other</option>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Marital Status</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="PBI_MARITAL_STA" id="PBI_MARITAL_STA">
                            <option></option>
                            <option selected="selected"><?=$PBI_MARITAL_STA?> </option>
                            <option value="Married">Married</option>
                            <option value="Unmarried">Unmarried</option>   
                            </select>                         
                            </div></div>
                        
                        
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Nationality:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="PBI_NATIONALITY" id="PBI_NATIONALITY">
                            <option></option>
                            <?=foreign_relation('apps_nationality', 'num_code', 'CONCAT(num_code," : ", nationality)',$PBI_NATIONALITY, 'status>0','order by nationality'); ?></select>
                            </div></div>
                        </td>




                            
                            <td>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">NID No<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ESSENTIAL_NATIONAL_ID" name="ESSENTIAL_NATIONAL_ID" value="<?=$ESSENTIAL_NATIONAL_ID;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Birth Certificate</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ESSENTIAL_BIRTH_CERT" name="ESSENTIAL_BIRTH_CERT" value="<?=$ESSENTIAL_BIRTH_CERT;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Passport No</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ESSENTIAL_PASSPORT_NO" name="ESSENTIAL_PASSPORT_NO" value="<?=$ESSENTIAL_PASSPORT_NO;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Driving License</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ESSENTIAL_DRIVING_LICENSE_NO" name="ESSENTIAL_DRIVING_LICENSE_NO" value="<?=$ESSENTIAL_DRIVING_LICENSE_NO;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Photo</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" id="myfile" name="myfile" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px">
                            </div></div>
                            </td></tr></table> 

                            <hr>
                          
                          <?php if($_GET[$unique]):  ?>
                          <button type="submit" name="cancel" id="cancel" style="font-size:12px; float:left" class="btn btn-danger">Cancel</button>
                          <button type="submit" name="modify" id="modify" style="font-size:12px; float:right" class="btn btn-primary">Modify</button>
                          <?php else: ?>
                          <button type="button" class="btn btn-primary" style="font-size:12px; float:right; margin-right:110px">Save Employee Info</button>
                          <?php endif; ?>
                          </div>
                          
 
 
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        <table style="width: 100%;">
                        <tr><td><h5>Contact Details</h5><hr>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Mobile No <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_MOBILE" required="required" name="PBI_MOBILE" value="<?$PBI_MOBILE?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Alternative Mobile</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_MOBILE_ALTR"  name="PBI_MOBILE_ALTR" value="<?$PBI_MOBILE_ALTR;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                                </div></div>
                            

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Email Id <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="PBI_EMAIL"  name="PBI_EMAIL" value="<?$PBI_EMAIL;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Alternative Email</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="PBI_EMAIL_ALT" name="PBI_EMAIL_ALT" value="<?=$PBI_EMAIL_ALT;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div> </td>
                                



                            <td><h5>Present Address</h5>
                            <hr>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="ci_present_address_address" name="ci_present_address_address" class="form-control col-md-7 col-xs-12" placeholder="House, Road, & Area" style="width: 100%; font-size:11px" ><?=$ci_present_address_address?></textarea>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Country <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_present_address_country" id="ci_present_address_country">
                            <option></option>
                            <?=foreign_relation('apps_nationality', 'num_code', 'CONCAT(num_code," : ", en_short_name)',$ci_present_address_country, 'status="1"','order by num_code'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">State <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_present_address_state" id="ci_present_address_state">
                            <option></option>
                            <?=foreign_relation('hrm_emp_state', 'id', 'CONCAT(id," : ", state_name)',$ci_present_address_state, '1','order by id'); ?></select>
                            </div></div>
                            

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">City</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_present_address_city" id="ci_present_address_city">
                            <option></option>
                            <?=foreign_relation('hrm_emp_city', 'id', 'CONCAT(id," : ", city_name)',$ci_present_address_city, '1','order by id'); ?></select>  
                            </select>                         
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Police Station:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_present_address_police_station" id="ci_present_address_police_station">
                            <option></option>
                            <?=foreign_relation('location', 'l_id', 'l_name',$ci_present_address_police_station, 'l_type="TH"','order by l_name'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Post Office:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_present_address_post_office" id="ci_present_address_post_office">
                            <option></option>
                            <?=foreign_relation('apps_nationality', 'num_code', 'CONCAT(num_code," : ", nationality)',$ci_present_address_police_station, 'status="1"','order by nationality'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Zip Code:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ci_present_address_zip_code" name="ci_present_address_zip_code" value="<?=$ci_present_address_zip_code;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                        </div></div>
                        </td>




                            
                        <td><h5>Permanent Address</h5>
                            <hr>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="ci_permanent_address_address" name="ci_permanent_address_address" class="form-control col-md-7 col-xs-12" placeholder="House, Road, & Area" style="width: 100%; font-size:11px" ><?=$ci_permanent_address_address?></textarea>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Country <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_permanent_address_country" id="ci_permanent_address_country">
                            <option></option>
                            <?=foreign_relation('apps_nationality', 'num_code', 'CONCAT(num_code," : ", en_short_name)',$ci_permanent_address_country, 'status="1"','order by num_code'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">State <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_permanent_address_state" id="ci_permanent_address_state">
                            <option></option>
                            <?=foreign_relation('hrm_emp_state', 'id', 'CONCAT(id," : ", state_name)',$ci_permanent_address_state, '1','order by id'); ?></select>
                            </div></div>
                            

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">City</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_permanent_address_city" id="ci_permanent_address_city">
                            <option></option>
                            <?=foreign_relation('hrm_emp_city', 'id', 'CONCAT(id," : ", city_name)',$ci_permanent_address_city, '1','order by id'); ?></select>  
                            </select>                         
                            </div></div>
                            

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Police Station:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_permanent_address_police_station" id="ci_permanent_address_police_station">
                            <option></option>
                            <?=foreign_relation('location', 'l_id', 'l_name',$ci_permanent_address_police_station, 'l_type="TH"','order by l_name'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Post Office:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ci_permanent_address_post_office" id="ci_permanent_address_post_office">
                            <option></option>
                            <?=foreign_relation('apps_nationality', 'num_code', 'CONCAT(num_code," : ", nationality)',$ci_permanent_address_post_office, 'status="1"','order by nationality'); ?></select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Zip Code:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ci_permanent_address_zip_code" name="ci_permanent_address_zip_code" value="<?=$ci_permanent_address_zip_code;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                        </div></div></tr>
                    </table>



                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Employment Type  <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="EMPLOYMENT_TYPE" id="EMPLOYMENT_TYPE">
                            <option></option>
                            <?=foreign_relation('employment_type','id','employment_type_name',$EMPLOYMENT_TYPE,'1');?>
                            </select>
                            </div></div>


                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Job Location <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ESS_JOB_LOCATION" id="ESS_JOB_LOCATION">
                            <option></option>
                            <?=foreign_relation('job_location_type','id','job_location_name',$ESS_JOB_LOCATION,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Department <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ESS_DEPARTMENT" id="ESS_DEPARTMENT">
                            <option></option>
                            <?=foreign_relation('department','DEPT_ID','DEPT_DESC',$ESS_DEPARTMENT,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Designation <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ESS_DESIGNATION" id="ESS_DESIGNATION">
                            <option></option>
                            <?=foreign_relation('designation','DESG_ID','DESG_DESC',$ESS_DESIGNATION,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="PBI_JOB_STATUS" id="PBI_JOB_STATUS" style="width:100%;font-size: 11px" class="select2_single form-control">
                                <option <?php if($PBI_JOB_STATUS=='In Service') echo 'Selected';?>>In Service</option>
                                <option <?php if($PBI_JOB_STATUS=='Not In Service') echo 'Selected';?>>Not In Service</option>
                            </select>
                            </div></div>
                        </td>
                            <td>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Appointment Ref. No</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ESSENTIAL_APPOINT_REF_NO" name="ESSENTIAL_APPOINT_REF_NO" value="<?=$ESSENTIAL_APPOINT_REF_NO;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Appoinment Date</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="ESSENTIAL_APPOINT_DATE" name="ESSENTIAL_APPOINT_DATE" value="<?=$ESSENTIAL_APPOINT_DATE;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Coinfirmation Date</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="ESSENTIAL_CONFIRM_DATE" name="ESSENTIAL_CONFIRM_DATE" value="<?=$ESSENTIAL_CONFIRM_DATE;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Joining Date</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="ESSENTIAL_JOINING_DATE" name="ESSENTIAL_JOINING_DATE" value="<?=$ESSENTIAL_JOINING_DATE;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Corporate Mobile No.</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ESS_CORPORATE_PHONE" name="ESS_CORPORATE_PHONE" value="<?=$ESS_CORPORATE_PHONE;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Corporate Email ID</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ESS_CORPORATE_EMAIL" name="ESS_CORPORATE_EMAIL" value="<?=$ESS_CORPORATE_EMAIL;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            </td></tr></table>
                          </div>


                          <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Name  <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="fi_name" name="fi_name" value="<?=$fi_name;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>


                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Relationship <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="fi_relationship" id="fi_relationship">
                            <option></option>
                            <?=foreign_relation('relation','RELATION_CODE','RELATION_NAME',$fi_relationship,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Gender</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="fi_gender" id="fi_gender">
                            <option></option>
                            <?=foreign_relation('gender','id','gender',$fi_gender,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">NID </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="fi_NID" name="fi_NID" value="<?=$fi_NID;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Phone No. <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="fi_contact_number" name="fi_contact_number" value="<?=$fi_contact_number;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Email </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="fi_email" name="fi_email" value="<?=$fi_email;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Profession</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="profession" name="profession" value="<?=$profession;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12"><input type="checkbox" id="fi__emergecy_contact" name="fi__emergecy_contact" value="No">
                            <label for="fi__emergecy_contact">Set As Emergecy Contact</label></div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($family_member_view,'','','240px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                          </div>

                          <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Education <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ei_education_degree" id="ei_education_degree">
                            <option></option>
                            <?=foreign_relation('edu_exam_title','EXAM_CODE','EXAM_NAME',$ei_education_degree,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Grade</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ei_grade" name="ei_grade" value="<?=$ei_grade;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Passing Year</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ei_passing_year" name="ei_passing_year" value="<?=$ei_passing_year;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">CGPA</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ei_CGPA" name="ei_CGPA" value="<?=$ei_CGPA;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Scale</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="ei_scale" name="ei_scale" value="<?=$ei_scale;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Institute <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ei_institute" id="ei_institute">
                            <option></option>
                            <?=foreign_relation('institute','institute_id','institute_name',$ei_institute,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Institute Type<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ei_institute_type" id="ei_institute_type">
                            <option>Local</option>
                            <option>Foreign</option>
                            <option>Professional</option>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12"><input type="checkbox" id="fi__emergecy_contact" name="fi__emergecy_contact" value="No">
                            <label for="fi__emergecy_contact">Last Education</label></div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($education_view,'','','240px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                          </div>

                          <div role="tabpanel" class="tab-pane fade" id="tab_content6" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Company Name <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="eh_company_name" name="eh_company_name" require value="<?=$eh_company_name;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Address</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="eh_address" name="eh_address" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" ><?=$eh_address;?></textarea>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Job Title <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="eh_job_title" name="eh_job_title" value="<?=$eh_job_title;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Start <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="eh_start_date" name="eh_start_date" value="<?=$eh_start_date;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">End <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="eh_end_date" name="eh_end_date" value="<?=$eh_end_date;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Last Salary <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="eh_last_salary" name="eh_last_salary" value="<?=$eh_last_salary;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Exper. Letter <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select require class="select2_single form-control" style="width:100%" name="eh_experiance_letter" id="eh_experiance_letter">
                            <option></option>
                            <option>No</option>
                            <option>Yes</option>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Remarks</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="eh_remarks" name="eh_remarks" value="<?=$eh_remarks;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($employment_history_view,'','','240px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                            </div>




                          <div role="tabpanel" class="tab-pane fade" id="tab_content7" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Supervisor Name <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="supervisor" id="supervisor">
                            <option></option>
                            <?=advance_foreign_relation($supv,$supervisor);?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Level <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="level" name="level" value="<?=$level;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Effective Date <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="effective_date" name="effective_date" value="<?=$effective_date;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($hrm_emp_supervisor_info,'','','150px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                            </div>

                          <div role="tabpanel" class="tab-pane fade" id="tab_content8" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>

                          <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Title <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="doc_title" name="doc_title" value="<?=$doc_title;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Category </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="doc_category" id="doc_category">
                            <option></option>
                            <?=advance_foreign_relation($categoryofdocuments,$doc_category);?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Doc. ID <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="level" name="level" value="<?=$level;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Doc. File <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" id="doc_file" name="doc_file" value="<?=$doc_file;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Reamrks </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="doc_remarks" name="doc_remarks" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" ><?=$doc_remarks;?></textarea>
                            </div></div>
                            </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($hrm_emp_supervisor_info,'','','150px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                            </div>

                          <div role="tabpanel" class="tab-pane fade" id="tab_content9" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>
                          <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Language <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ls_language" id="ls_language">
                            <option></option>
                            <?=foreign_relation('languages','id','concat(language_code," : ",language_name)',$ls_language,'status="1"');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Proficiency <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ls_proficiency" id="ls_proficiency">
                            <option></option>
                            <?=foreign_relation('proficiency','id','proficiency_name',$ls_proficiency,'status="1"');?>
                            </select>
                            </div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($hrm_emp_language_skill,'','','150px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                          </div>

                          <div role="tabpanel" class="tab-pane fade" id="tab_content10" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>

                          <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Passport No <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="level" name="level" value="<?=$level;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Issue Date <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="level" name="level" value="<?=$level;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Expire Date <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="level" name="level" value="<?=$level;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                          <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Passport Type <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="passport_type" id="passport_type">
                            <option></option>
                            <?=foreign_relation('passport_type','id','passport_type',$ls_language,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Country <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="ls_proficiency" id="ls_proficiency">
                            <option></option>
                            <?=foreign_relation('apps_nationality','num_code','concat(en_short_name," : ",nationality)',$ls_language,'status="1"');?>
                            </select>
                            </div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($hrm_emp_passport_info,'','','150px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                          </div>

                          <div role="tabpanel" class="tab-pane fade" id="tab_content11" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>

                          <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Talent Type <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="pi_talent_ype" id="pi_talent_ype">
                            <option></option>
                            <?=foreign_relation('talent','id','talent_type',$pi_talent_ype,'status="1"');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Effective Date <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="pi_effective_date" name="pi_effective_date" value="<?=$pi_effective_date;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Remarks <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="pi_talent_details" name="pi_talent_details" value="<?=$pi_talent_details;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($hrm_emp_talent_info,'','','150px','','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                          </div>







                          <div role="tabpanel" class="tab-pane fade" id="tab_content12" aria-labelledby="profile-tab">
                          <table style="width: 100%;">
                          <tr><td>
                          <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Bank <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="bai_bank" id="bai_bank">
                            <option></option>
                            <?=foreign_relation('bank','BANK_CODE','concat(BANK_NAME," : ",BRANCH)',$bai_bank,'1');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Account No <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="bai_account_no" name="bai_account_no" value="<?=$bai_account_no;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Account Name <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="bai_account_name" name="bai_account_name" value="<?=$bai_account_name;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Routing No <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="bai_routing_no" name="bai_routing_no" value="<?=$bai_routing_no;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>
                        </td>
                            <td style="vertical-align:top">
                            <?=recentdataview_model($hrm_emp_bank_account_info,'','','150px','Family Member List','hrm_requisition_leave_report.php','90');?>
                            </td></tr></table>
                          </div>






                          <div role="tabpanel" class="tab-pane fade" id="social_media" aria-labelledby="profile-tab">
                          <div class="container" id="crudApp">
                              
                          <table style="width: 100%;">
                          <tr><td>
                          <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Social Media <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="bai_bank" id="bai_bank">
                            <option></option>
                            <?=foreign_relation('social_media','sm_id','name',$bai_bank,'status="1"');?>
                            </select>
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Account Name <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="bai_account_no" v-model="first_name"  name="bai_account_no" value="<?=$bai_account_no;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">URL <span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="bai_account_name" v-model="last_name" name="bai_account_name" value="<?=$bai_account_name;?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size:11px" >
                            </div></div>

                            <div align="center">
                            <input type="hidden" v-model="hiddenId" />
                            <input type="button" class="btn btn-primary" style="font-size:12px" v-model="actionButton" @click="submitData" />
                        
                        
                        </div>
                        </td>
                            <td style="vertical-align:top"></td></tr></table>
                          </div>


                          <div class="modal-footer">
                          <?php if($_GET[$unique]):  ?>
                          <button type="submit" name="cancel" id="cancel" style="font-size:12px; float:left" class="btn btn-danger">Cancel</button>
                          <button type="submit" name="modify" id="modify" style="font-size:12px; float:right" class="btn btn-primary">Modify</button>
                          <?php else: ?>
                          <button type="button" class="btn btn-danger" style="font-size:12px; float:" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" style="font-size:12px; float:right; margin-right:110px">Save Employee Info</button>
                          <?php endif; ?>
                          </div></div></div></div></form></div></div></div></div></div></div></div>

<?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
<?php if(!isset($_GET[$unique])){ echo $crud->report_templates_with_add_new($res,$title,'12',$action=$_SESSION["userlevel"],$create=1);}?>
<?=$html->footer_content();?>
