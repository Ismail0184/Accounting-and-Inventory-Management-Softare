<?php
require_once 'support_file.php';
$title="Employee";

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
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>
 <style>
     input[type=text]{font-size: 11px;}
     select{font-size: 11px;}.rcom{color:red}
 </style>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<?php require_once 'body_content.php'; ?>

<div class="container" id="crudApp">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a class="btn btn-primary btn-block" @click="openModel">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:white; font-size:12px">Add New</span>
                    </a></ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">






    <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
        <thead><tr style="background-color: #3caae4; color:white"><th style="vertical-align:middle;height:45px">#</th>
                        <th style="vertical-align:middle;height:45px">First Name</th>
                        <th style="vertical-align:middle;height:45px">Last Name</th>
                        <th style="vertical-align:middle;height:45px;width: 8%; text-align: center">Action</th>
                    </tr>
        </thead><tbody>
                    <tr v-for="row in allData">
                        <td>{{ <?=$i=$i+1;?> }}</td>
                        <td>{{ row.first_name }}</td>
                        <td>{{ row.last_name }}</td>
                        <td style="vertical-align:middle; text-align:center">
                            <button type="button" style="background-color:transparent; border:none; margin:0px; font-size:13px; padding:0px"  title="View Details" data-toggle="tooltip" class="viewBtn"><i class="fa fa-eye"></i></button>
                            <button type="button" style="background-color:transparent; border:none; margin:0px; font-size:13px; padding:0px; color: green"  title="Update Record" name="edit" class="btn btn-primary btn-xs edit" @click="fetchData(row.id)"> <i class="fa fa-pencil"></i></button>
                            <button type="button" style="background-color:transparent;color:red; border:none; margin:0px; font-size:13px; padding:0px" name="delete" class="btn btn-danger btn-xs delete" @click="deleteData(row.id)" title="Delete Record"><span class="glyphicon glyphicon-trash"></span></button>
                        </td>
                    </tr>
        </tbody>
                </table>




                <div v-if="myModel">
                    <transition name="model">
                        <div class="modal-mask">
                            <div class="modal-wrapper">
                                <div class="modal-dialog modal-md" style="width:90%">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <button type="button" class="close" @click="myModel=false"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">{{ dynamicTitle }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="clearfix"></div>

                                            <div class="" role="tabpanel" data-example-id="togglable-tabs" style="font-size: 11px">
                                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                                    <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Personal</a></li>
                                                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Contact</a></li>
                                                    <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Job Info</a></li>
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
                                                                    </td></tr></table> </div>



                                                </div>












                                                    <br />
                                                <div class="modal-footer">
                                                    <input type="hidden" v-model="hiddenId" />
                                                        <button type="button" class="btn btn-danger" style="font-size:12px; float:" class="close" @click="myModel=false">Close</button>
                                                        <button type="button" class="btn btn-primary" v-model="actionButton" @click="submitData" style="font-size:12px; float:right; margin-right:110px">Record Data</button>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
<script>
    var application = new Vue({
        el:'#crudApp',
        data:{
            allData:'',
            myModel:false,
            actionButton:'Insert',
            dynamicTitle:'Add Data',
        },
        methods:{
            fetchAllData:function(){
                axios.post('action.php', {
                    action:'fetchall'
                }).then(function(response){
                    application.allData = response.data;
                });
            },
            openModel:function(){
                application.first_name = '';
                application.last_name = '';
                application.actionButton = "Insert";
                application.dynamicTitle = "Add Data";
                application.myModel = true;
            },
            submitData:function(){
                if(application.first_name != '' && application.last_name != '')
                {
                    if(application.actionButton == 'Insert')
                    {
                        axios.post('action.php', {
                            action:'insert',
                            firstName:application.first_name,
                            lastName:application.last_name
                        }).then(function(response){
                            application.myModel = false;
                            application.fetchAllData();
                            application.first_name = '';
                            application.last_name = '';
                            //alert(response.data.message);
                        });
                    }
                    if(application.actionButton == 'Update')
                    {
                        axios.post('action.php', {
                            action:'update',
                            firstName : application.first_name,
                            lastName : application.last_name,
                            hiddenId : application.hiddenId
                        }).then(function(response){
                            application.myModel = false;
                            application.fetchAllData();
                            application.first_name = '';
                            application.last_name = '';
                            application.hiddenId = '';
                            //alert(response.data.message);
                        });
                    }
                }
                else
                {
                    alert("Fill All Field");
                }
            },
            fetchData:function(id){
                axios.post('action.php', {
                    action:'fetchSingle',
                    id:id
                }).then(function(response){
                    application.first_name = response.data.first_name;
                    application.last_name = response.data.last_name;
                    application.hiddenId = response.data.id;
                    application.myModel = true;
                    application.actionButton = 'Update';
                    application.dynamicTitle = 'Edit Data';
                });
            },
            deleteData:function(id){
                if(confirm("Are you sure you want to remove this data?"))
                {
                    axios.post('action.php', {
                        action:'delete',
                        id:id
                    }).then(function(response){
                        application.fetchAllData();
                        //alert(response.data.message);
                    });
                }
            }
        },
        created:function(){
            this.fetchAllData();
        }
    });

</script>
<?=$html->footer_content();?>

