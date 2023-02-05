 <?php
require_once 'support_file.php';
$title="Basic Info";

$now=time();
$unique='PBI_ID';
$unique_field='PBI_NAME';
$table="personnel_basic_info";
$page="hrm_employee_basic_info.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
 $jobinfo="hrm_employee_job_info.php".'?'.$unique.'='.$$unique;
 $targeturlJOBINFO="<meta http-equiv='refresh' content='0;$jobinfo'>";

if(prevent_multi_submit()){
        if(isset($_POST['goback']))
        {
            echo "$targeturlJOBINFO";
            //echo "<script>window.close(); </script>";
        }


if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $_POST['active_status']='1';
        $_POST['PBI_JOB_STATUS']='In Service';
        $_SESSION['employee_selected']=$_POST['PBI_ID'];
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
    //echo "<script>self.opener.location = '$page'; self.blur(); </script>";
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


 <form  name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" method="post">
     <? require_once 'support_html.php';?>

                    <!-- input section-->
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>:: Basic Information ::</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                    <table style="width: 100%;">
                                    <tr>
                                    <td>ERP ID:</td><td><input type="text" id="<?=$unique?>" style="width:80%; height: 30px"  readonly   name="<?=$unique?>" value="<? if($$unique>0) { echo  $$unique; } else { echo find_a_field($table,'max('.$unique.')+1','1');
                                                if($$unique<1) $$unique = 1;}?>" class="form-control col-md-7 col-xs-12" ></td>
                                    <td>Employee ID:</td><td><input type="text" id="PBI_ID_UNIQUE" style="width:80%; height: 30px"  required   name="PBI_ID_UNIQUE" value="<?=$PBI_ID_UNIQUE;?>" class="form-control col-md-7 col-xs-12" ></td>
                                    </tr>
                                        <tr>
                                            <td>Full Name:</td><td><input type="text" id="PBI_NAME" style="width:80%; height: 30px; margin-top: 5px"  required   name="PBI_NAME" value="<?=$PBI_NAME;?>" class="form-control col-md-7 col-xs-12" ></td>
                                            <td>Password:</td><td><input type="text" id="password" style="width:80%; height: 30px;margin-top: 5px"  required   name="password" value="" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>
                                        <tr>
                                            <td>Father's Name:</td><td><input type="text" id="PBI_FATHER_NAME" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_FATHER_NAME" value="<?=$PBI_FATHER_NAME;?>" class="form-control col-md-7 col-xs-12" ></td>
                                            <td>Mother's Name:</td><td><input type="text" id="PBI_MOTHER_NAME" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_MOTHER_NAME" value="<?=$PBI_MOTHER_NAME;?>" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>
                                        <tr>
                                            <td>Date of Birth:</td><td><input type="text" id="PBI_DOB" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_DOB" value="<?=$PBI_DOB;?>" class="form-control col-md-7 col-xs-12" ></td>
                                            <td>Place of Birth (District):</td><td><select name="PBI_POB" id="PBI_POB" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                    <option value="<?=$PBI_POB?>">
                                                        <?=$PBI_POB?>
                                                    </option>
                                                    <? foreign_relation('district_list','district_name','district_name',$PBI_POB,' 1 order by district_name');?>
                                                </select></td>
                                        </tr>

                                        <tr>
                                            <td>Nationality:</td><td>
                                                <select name="PBI_NATIONALITY" id="PBI_NATIONALITY" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                    <option value="<?=$PBI_NATIONALITY;?>" selected="selected">
                                                        <?=$PBI_NATIONALITY?>
                                                    </option>
                                                    <option>Bangladeshi</option>
                                                    <option>Canadian</option>
                                                    <option>English</option>
                                                    <option>Indian</option>
                                                    <option>Pakistani</option>
                                                    <option>Nepali</option>
                                                </select>
                                            </td>
                                            <td>Country of Birth:</td><td><input type="text" id="PBI_COB" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_COB" value="<?=$PBI_COB;?>" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>


                                        <tr>
                                            <td>Blood Group:</td><td>
                                                <select name="ESSENTIAL_BLOOD_GROUP" id="ESSENTIAL_BLOOD_GROUP" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                    <option value="<?=$ESSENTIAL_BLOOD_GROUP?>">
                                                        <?=$ESSENTIAL_BLOOD_GROUP?>
                                                    </option>
                                                    <? foreign_relation('blood','name','name',$PBI_NATIONALITY,' 1 order by name');?>
                                                </select></td>
                                            <td>Religion:</td><td>
                                                <select name="PBI_RELIGION" id="PBI_RELIGION" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                    <option value="<?=$PBI_RELIGION;?>" selected="selected"><?=$PBI_RELIGION;?></option>
                                                    <option>Islam</option>
                                                    <option>Bahai</option>
                                                    <option>Buddhism</option>
                                                    <option>Christianity</option>
                                                    <option>Confucianism </option>
                                                    <option>Druze</option>
                                                    <option>Hinduism</option>
                                                    <option>Jainism</option>
                                                    <option>Judaism</option>
                                                    <option>Shinto</option>
                                                    <option>Sikhism</option>
                                                    <option>Taoism</option>
                                                    <option>Zoroastrianism</option>
                                                    <option>Others</option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td>Marital Status:</td><td>
                                                <select name="PBI_MARITAL_STA" id="PBI_MARITAL_STA" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                    <option selected="selected">
                                                        <?=$PBI_MARITAL_STA?>
                                                    </option>
                                                    <option value="Married">Married</option>
                                                    <option value="Unmarried">Unmarried</option>
                                                </select>
                                            </td>

                                            <td>Spouse Name:</td><td><input type="text" id="PBI_SPOUSE" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_SPOUSE" value="<?=$PBI_SPOUSE;?>" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>
                                        <tr>
                                            <td>Gender:</td><td>
                                                <select name="PBI_SEX" id="PBI_SEX" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                    <option selected="selected">
                                                        <?=$PBI_SEX?>
                                                    </option>
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select></td>
                                            <td>TIN No:</td><td><input type="text" id="ESSENTIAL_TIN_NO" style="width:80%; height: 30px;margin-top: 5px"   name="ESSENTIAL_TIN_NO" value="<?=$ESSENTIAL_TIN_NO;?>" class="form-control col-md-7 col-xs-12" ></td>

                                        </tr>

                                        <tr>
                                            <td>Mobile:</td><td><input type="text" id="PBI_MOBILE" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_MOBILE" value="<?=$PBI_MOBILE;?>" class="form-control col-md-7 col-xs-12" ></td>
                                            <td>E-mail ID:</td><td><input type="text" id="PBI_EMAIL" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_EMAIL" value="<?=$PBI_EMAIL;?>" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>
                                        <tr>
                                            <td>Alternative&nbsp;Mobile:</td><td><input type="text" id="PBI_MOBILE_ALTR" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_MOBILE_ALTR" value="<?=$PBI_MOBILE_ALTR;?>" class="form-control col-md-7 col-xs-12" ></td>
                                            <td>Alternative Email ID:</td><td><input type="text" id="PBI_EMAIL_ALT" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_EMAIL_ALT" value="<?=$PBI_EMAIL_ALT;?>" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>
                                        <tr>
                                            <td>NID:</td><td><input type="text" id="ESSENTIAL_NATIONAL_ID" style="width:80%; height: 30px;margin-top: 5px"   name="ESSENTIAL_NATIONAL_ID" value="<?=$ESSENTIAL_NATIONAL_ID;?>" class="form-control col-md-7 col-xs-12" ></td>
                                            <td>Passport No:</td><td><input type="text" id="ESSENTIAL_PASSPORT_NO" style="width:80%; height: 30px;margin-top: 5px"   name="ESSENTIAL_PASSPORT_NO" value="<?=$ESSENTIAL_PASSPORT_NO;?>" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>
                                        <tr>
                                            <td>Birth Certificate:</td><td><input type="text" id="ESSENTIAL_BIRTH_CERT" style="width:80%; height: 30px;margin-top: 5px"   name="ESSENTIAL_BIRTH_CERT" value="<?=$ESSENTIAL_BIRTH_CERT;?>" class="form-control col-md-7 col-xs-12" ></td>
                                            <td>Driving License:</td><td><input type="text" id="ESSENTIAL_DRIVING_LICENSE_NO" style="width:80%; height: 30px;margin-top: 5px"   name="ESSENTIAL_DRIVING_LICENSE_NO" value="<?=$ESSENTIAL_DRIVING_LICENSE_NO;?>" class="form-control col-md-7 col-xs-12" ></td>
                                        </tr>

                                    </table>

                                </div>
                                </div>
                                </div>


                    <!-------------------list view ------------------------->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>:: Present Address :: </h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <table style="width: 100%;">

                                    <tr>
                                        <td>Street Address :</td><td><input type="text" id="PBI_PRESENT_STREET_ADD" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_PRESENT_STREET_ADD" value="<?=$PBI_PRESENT_STREET_ADD;?>" class="form-control col-md-7 col-xs-12" ></td>
                                    </tr>
                                    <tr>
                                        <td>Apartment/Unit/Flat:</td><td><input type="text" id="PBI_PRESENT_APRT_ADD" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_PRESENT_APRT_ADD" value="<?=$PBI_PRESENT_APRT_ADD;?>" class="form-control col-md-7 col-xs-12" ></td>
                                    </tr>
                                    <tr>
                                        <td>City:</td><td><input type="text" id="PBI_PRESENT_CITY_ADD" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_PRESENT_CITY_ADD" value="<?=$PBI_PRESENT_CITY_ADD;?>" class="form-control col-md-7 col-xs-12" ></td>
                                    </tr>
                                    <tr>
                                        <td>Thana:</td><td>
                                            <select name="PBI_PRESENT_THANA_ADD" id="PBI_PRESENT_THANA_ADD" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                <option value="<?=$PBI_PRESENT_THANA_ADD?>">
                                                    <?=$PBI_PRESENT_THANA_ADD?>
                                                </option>
                                                <? foreign_relation('location','l_id','l_name',$PBI_PARM_THANA_ADD,'l_type="TH" order by l_name');?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>District:</td><td>
                                            <select name="PBI_PRESENT_DIST_ADD" id="PBI_PRESENT_DIST_ADD" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                                                <option value="<?=$PBI_PRESENT_DIST_ADD;?>"><?=$PBI_PRESENT_DIST_ADD;?></option>
                                                <? foreign_relation('district_list','district_name','district_name',$PBI_PRESENT_DIST_ADD,' 1 order by district_name');?>
                                            </select></td>
                                    </tr>
                                </table>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->



 <!-------------------list view ------------------------->
 <div class="col-md-4 col-sm-12 col-xs-12">
     <div class="x_panel">
         <div class="x_title">
             <h2>:: Parmanent Address :: </h2>
             <div class="clearfix"></div>
         </div>
         <div class="x_content">
             <table style="width: 100%;">
                 <tr>
                     <td>Street Address :</td><td><input type="text" id="PBI_PARM_STREET_ADD" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_PARM_STREET_ADD" value="<?=$PBI_PARM_STREET_ADD;?>" class="form-control col-md-7 col-xs-12" ></td>
                 </tr>
                 <tr>
                     <td>Apartment/Unit/Flat:</td><td><input type="text" id="PBI_PARM_APRT_ADD" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_PARM_APRT_ADD" value="<?=$PBI_PARM_APRT_ADD;?>" class="form-control col-md-7 col-xs-12" ></td>
                 </tr>
                 <tr>
                     <td>City:</td><td><input type="text" id="PBI_PARM_CITY_ADD" style="width:80%; height: 30px;margin-top: 5px"   name="PBI_PARM_CITY_ADD" value="<?=$PBI_PARM_CITY_ADD;?>" class="form-control col-md-7 col-xs-12" ></td>
                 </tr>
                 <tr>
                     <td>Thana:</td><td>
                         <select name="PBI_PARM_THANA_ADD" id="PBI_PARM_THANA_ADD" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                             <option value="<?=$PBI_PARM_THANA_ADD;?>">
                                 <?=$PBI_PARM_THANA_ADD;?>
                             </option>
                             <? foreign_relation('location','l_id','l_name',$PBI_PARM_THANA_ADD,'l_type="TH" order by l_name');?>
                         </select></td>
                 </tr>
                 <tr>
                     <td>District:</td><td>
                         <select name="PBI_PARM_DIST_ADD" id="PBI_PARM_DIST_ADD" style="width:80%; height: 30px;margin-top: 5px" class="form-control col-md-7 col-xs-12">
                             <option value="<?=$PBI_PARM_DIST_ADD;?>"><?=$PBI_PARM_DIST_ADD;?></option>
                             <? foreign_relation('district_list','district_name','district_name',$PBI_PARM_DIST_ADD,' 1 order by district_name');?>
                         </select></td>
                 </tr>
             </table>
         </div>
     </div></div>
 <!-------------------End of  List View --------------------->












     <!-- input section-->
     <div class="col-md-8 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_title">
                 <h2>:: Documentation ::</h2>
                 <ul class="nav navbar-right panel_toolbox">
                 </ul>
                 <div class="clearfix"></div>
             </div>
             <div class="x_content">


                 <table style="width: 100%;">
                     <tr>
                         <td>Staff Picture :</td><td><input style="width:80%; height: 35px;margin-top: 10px" name="emp_pic" type="file" id="emp_pic" class="form-control col-md-7 col-xs-12" /></td>
                     <td><a href="../../pic/staff/<?php echo $_SESSION['employee_selected']?>.jpeg" target="_blank"><img src="../../pic/staff/<?php echo $_SESSION['employee_selected']?>.jpeg" width="80" height="60"/></a></td>
                     </tr>
                     <tr>
                         <td>CV :</td><td><input style="width:80%; height: 35px;margin-top: 10px" name="cv_pic" type="file" id="cv_pic" class="form-control col-md-7 col-xs-12" /></td>
                     <td><a href="../../pic/cv/<?php echo $_SESSION['employee_selected']?>.jpeg" target="_blank"><img src="../../pic/cv/<?php echo $_SESSION['employee_selected']?>.jpeg" width="80" height="60"/></a></td>
                     </tr>
                     <tr>
                         <td>Birth Certificate :</td><td><input style="width:80%; height: 35px;margin-top: 10px" name="birthCrtf_pic" type="file" id="birthCrtf_pic" class="form-control col-md-7 col-xs-12" /></td>
                         <td><a href="../../pic/birth_crtf/<?php echo $_SESSION['employee_selected']?>.jpeg" target="_blank"><img src="../../pic/birth_crtf/<?php echo $_SESSION['employee_selected']?>.jpeg" width="80" height="60"/></a></td>
                     </tr>
                     <tr>
                         <td>Passport :</td><td><input style="width:80%; height: 35px;margin-top: 10px" name="pass_pic" type="file" id="pass_pic" class="form-control col-md-7 col-xs-12" /></td>
                     <td><a href="../../pic/nid/<?php echo $_SESSION['employee_selected']?>.jpeg" target="_blank"><img src="../../pic/nid/<?php echo $_SESSION['employee_selected']?>.jpeg" width="80" height="60"/></a></td>
                     </tr>
                     <tr>
                         <td>National ID :</td><td><input style="width:80%; height: 35px;margin-top: 10px" name="nid_pic" type="file" id="nid_pic" class="form-control col-md-7 col-xs-12" /></td>
                     <td><a href="../../pic/passport/<?php echo $_SESSION['employee_selected']?>.jpeg" target="_blank"><img src="../../pic/passport/<?php echo $_SESSION['employee_selected']?>.jpeg" width="80" height="60"/></a></td>
                     </tr>
                     <tr>
                         <td>TIN :</td><td><input style="width:80%; height: 35px;margin-top: 10px" name="tin_pic" type="file" id="tin_pic" class="form-control col-md-7 col-xs-12" /></td>
                     <td><a href="../../pic/sign/<?php echo $_SESSION['employee_selected']?>.jpeg" target="_blank"><img src="../../pic/sign/<?php echo $_SESSION['employee_selected']?>.jpeg" width="80" height="60"/></a></td>
                     </tr>
                     <tr>
                         <td>Sign :</td><td><input style="width:80%; height: 35px;margin-top: 10px" name="sign_pic" type="file" id="sign_pic" class="form-control col-md-7 col-xs-12" /></td>
                         <td><a href="../../pic/sign/<?php echo $_SESSION['employee_selected']?>.jpeg" target="_blank"><img src="../../pic/sign/<?php echo $_SESSION['employee_selected']?>.jpeg" width="80" height="60"/></a></td>
                     </tr>
                 </table>

             </div>
         </div>
     </div>



     <!-------------------list view ------------------------->
     <div class="col-md-4 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_title">
                 <h2>:: Emergency Contact :: </h2>
                 <div class="clearfix"></div>
             </div>
             <div class="x_content">
                 <table style="width: 100%;">
                     <tr>
                         <td>Full Name :</td><td><input type="text" id="EMR_FULL_NAME" style="width:80%; height: 30px;margin-top: 5px" name="EMR_FULL_NAME" value="<?=$EMR_FULL_NAME;?>" class="form-control col-md-7 col-xs-12" ></td>
                     </tr>
                     <tr>
                         <td>Relationship :</td><td><input type="text" id="EMR_RELATION" style="width:80%; height: 30px;margin-top: 5px"   name="EMR_RELATION" value="<?=$EMR_RELATION;?>" class="form-control col-md-7 col-xs-12" ></td>
                     </tr>
                     <tr>
                         <td>Address :</td><td><input type="text" id="EMR_ADDRESS" style="width:80%; height: 30px;margin-top: 5px" name="EMR_ADDRESS" value="<?=$EMR_ADDRESS;?>" class="form-control col-md-7 col-xs-12" ></td>
                     </tr>
                     <tr>
                         <td>Mobile Number 1 :</td><td><input type="text" id="EMR_MOBILE" style="width:80%; height: 30px;margin-top: 5px" name="EMR_MOBILE" value="<?=$EMR_MOBILE;?>" class="form-control col-md-7 col-xs-12" ></td>
                     </tr>
                     <tr>
                         <td>Mobile Number 2 :</td><td><input type="text" id="EMR_MOBILE_2" style="width:80%; height: 30px;margin-top: 5px" name="EMR_MOBILE_2" value="<?=$EMR_MOBILE_2;?>" class="form-control col-md-7 col-xs-12" ></td>
                     </tr>
                     <tr>
                         <td>Email :</td><td><input type="text" id="EMR_EMAIL" style="width:80%; height: 30px;margin-top: 5px" name="EMR_EMAIL" value="<?=$EMR_EMAIL;?>" class="form-control col-md-7 col-xs-12" ></td>
                     </tr>

                 </table>
             </div>
         </div></div>
     <!-------------------End of  List View --------------------->




     <!-------------------list view ------------------------->
     <div class="col-md-4 col-sm-12 col-xs-12">
         <div class="x_panel">
             <div class="x_title">
                 <h2>:: Internal Referance :: </h2>
                 <div class="clearfix"></div>
             </div>
             <div class="x_content">
                 <table style="width: 100%;">
                     <tr>
                         <td>Full Name :</td><td>

                             <select class="select2_single form-control" style="width: 100%;" tabindex="-1" name="PBI_REF3_NAME" id="PBI_REF3_NAME">
                                 <option></option>
                                 <? $sql_recommended_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";
                                 advance_foreign_relation($sql_recommended_by,$PBI_REF3_NAME);?>
                             </select>
                           </td>
                     </tr>

                     <tr>
                         <td>Relationship :</td>
                         <td><input type="text" id="PBI_REF3_RELATION" style="width:80%; height: 30px;margin-top: 5px" name="PBI_REF3_RELATION" value="<?=$PBI_REF3_RELATION;?>" class="form-control col-md-7 col-xs-12" ></td>
                     </tr>


                 </table>
             </div>
         </div></div>
     <!-------------------End of  List View --------------------->

     <table style="width: 100%">
     <tr>
         <td align="center" style="width: 50%">
             <div class="col-md-6 col-sm-6 col-xs-12">
                 <button type="submit" name="goback" id="goback" class="btn btn-primary">Go for Job Info Edit</button>
             </div></div>
         </td>

     <td style="width: 50%">
         <?php if($_GET[$unique]){  ?>
             <div class="form-group" style="margin-left:40%">
                 <div class="col-md-6 col-sm-6 col-xs-12">
                     <button type="submit" name="modify" id="modify" class="btn btn-success">Update Basic Info</button>
                 </div></div>

         <?php } else {?>
             <div class="form-group" style="margin-left:40%">
                 <div class="col-md-6 col-sm-6 col-xs-12">
                     <button type="submit" name="record" id="record"  class="btn btn-success">Add New </button>
                 </div></div>
         <?php } ?></td>

     </tr>

     </table>
 </form>

                
        
<?php require_once 'footer_content.php' ?>