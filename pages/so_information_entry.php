<?php
require_once 'support_file.php';
$now=time();
$unique='PBI_ID';
$unique_field='PBI_ID_UNIQUE';
$table="personnel_basic_info_requisition";
$page="so_information_entry.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))

//for insert..................................
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $_POST[tsm]=$_SESSION['userid'];
            $_POST[password]=$_POST[$unique_field];
            $d =$_POST[PBI_DOJ];
            $_POST[PBI_DOJ]=date('Y-m-d' , strtotime($d));
            $_POST[PBI_DEPARTMENT]='3';
            $_POST[PBI_DESIGNATION]='60';
            $_POST[status]='UNCHECKED';
            $table="personnel_basic_info_requisition";
            $crud->insert();
            $type=1;
            $msg='New Entry Successfully Inserted.';
            unset($_POST);
            unset($$unique);
            echo $targeturl;
        }


//for modify..................................
        if(isset($_POST['modify']))
        {

            $crud->update($unique);
            $type=1;

        }

//for Delete..................................
        if(isset($_POST['delete']))
        {   $condition=$unique."=".$_SESSION['unique'];
            $crud->delete($condition);
            unset($_SESSION['unique']);
            $type=1;
            $msg='Successfully Deleted.';

        }}}

// data query..................................
if(isset($_SESSION['unique']))
{   $condition=$unique."=".$_SESSION['unique'];
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
                                    <?require_once 'support_html.php';?>









                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Distributor Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" id="<?=$unique?>" style="width:100%"  required   name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                                            <select style="width: 100%" class="select2_single form-control" required name="PBI_DEALER_ID" id="PBI_DEALER_ID">
                                                <option value="">Select</option>
                                                <?php
                                                $result=mysql_query("SELECT  * FROM 							 
							dealer_info 
							 where 
							1 order by dealer_name_e");
                                                while($row=mysql_fetch_array($result)){  ?>
                                                    <option  value="<?=$row[dealer_code]; ?>" <?php if($PBI_DEALER_ID==$row[dealer_code]) echo 'selected' ?>><?=$row[dealer_code]; ?>#><?=$row[dealer_name_e];?>#></option>
                                                <?php } ?>
                                            </select>

                                        </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SO Code<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="<?=$unique_field?>" style="width:100%"  required   name="<?=$unique_field?>" value="<?=$$unique_field?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Name of SO<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="PBI_NAME" style="width:100%"  required   name="PBI_NAME" value="<?=$PBI_NAME;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date of Joining<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="PBI_DOJ" style="width:100%"  required   name="PBI_DOJ" value="<?=$PBI_DOJ;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Official Contact<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="PBI_PHONE" style="width:100%"  required   name="PBI_PHONE" value="<?=$PBI_PHONE;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Personal Number<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="PBI_MOBILE" style="width:100%"  required   name="PBI_MOBILE" value="<?=$PBI_MOBILE;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Religion<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select style="width: 100%" class="select2_single form-control" required name="PBI_RELIGION" id="PBI_RELIGION">
                                                <option value="">Select</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Hinduism">Hinduism</option>
                                                <option value="Others">Others</option>

                                            </select></div></div>




                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Account Number<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="ESS_BANK_ACCOUNTS" style="width:100%"  required   name="ESS_BANK_ACCOUNTS" value="<?=$ESS_BANK_ACCOUNTS;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select style="width: 80%" class="select2_single form-control"  name="ESS_BANK" id="ESS_BANK">
                                                <option value="">Select</option>
                                                <?php
                                                $result=mysql_query("SELECT  distinct BANK_NAME FROM bank 
							 where 1 order by BANK_NAME");
                                                while($row=mysql_fetch_array($result)){  ?>
                                                    <option  value="<?=$row[BANK_NAME]; ?>" <?php if($bank==$row[BANK_NAME]) echo 'selected' ?>><?=$row[BANK_NAME]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <a href="add_new_bank.php" target="_blank"><img src="add.png" style="width: 25px; height: 25px"></a>

                                        </div></div>



                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Branch Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select style="width: 100%" class="select2_single form-control"  name="ESS_BANK_BRANCH" id="ESS_BANK_BRANCH">
                                                <option value="">Select</option>
                                                <?php
                                                $result=mysql_query("SELECT  distinct BRANCH FROM bank 
							 where 1 order by BANK_NAME");
                                                while($row=mysql_fetch_array($result)){  ?>
                                                    <option  value="<?=$row[BRANCH]; ?>" <?php if($bank==$row[BRANCH]) echo 'selected' ?>><?=$row[BRANCH];?></option>
                                                <?php } ?>
                                            </select></div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Swift / Routing<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="ESS_BANK_SWIFT" style="width:100%"  required   name="ESS_BANK_SWIFT" value="<?=$ESS_BANK_SWIFT;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Monthly IMS Target<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="PBI_MONTHLY_IMS" style="width:100%"  required   name="PBI_MONTHLY_IMS" value="<?=$PBI_MONTHLY_IMS;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Salary<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="PBI_BASIC_SALARY" style="width:100%"  required   name="PBI_BASIC_SALARY" value="<?=$PBI_BASIC_SALARY;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">TA/DA<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="PBI_TA_DA" style="width:100%"  required   name="PBI_TA_DA" value="<?=$PBI_TA_DA;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Permanent Address<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea name="PBI_PERMANENT_ADD" type="text" required id="PBI_PERMANENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$PBI_PERMANENT_ADD?></textarea>
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Persent Address<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea name="PBI_PRESENT_ADD" type="text" required id="PBI_PRESENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$PBI_PRESENT_ADD?></textarea>
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Educational Qualification<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select style="width: 80%" class="select2_single form-control" required name="PBI_EDU_QUALIFICATION" id="PBI_EDU_QUALIFICATION">
                                                <option value="">Select</option>
                                                <?php
                                                $result=mysql_query("SELECT  * FROM edu_qua 
							 where 1 order by EDU_QUA_DESC");
                                                while($row=mysql_fetch_array($result)){  ?>
                                                    <option  value="<?=$row[EDU_QUA_DESC]; ?>" <?php if($PBI_EDU_QUALIFICATION==$row[EDU_QUA_DESC]) echo 'selected' ?>><?=$row[EDU_QUA_DESC];?></option>
                                                <?php } ?>
                                            </select>
                                            <a href="add_new_eq.php" target="_blank"><img src="add.png" style="width: 25px; height: 25px"></a>
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Person (if any Emergency)<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="EMR_FULL_NAME" style="width:100%"  required   name="EMR_FULL_NAME" value="<?=$EMR_FULL_NAME;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Emergency Contact Number<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="EMR_MOBILE" style="width:100%"  required   name="EMR_MOBILE" value="<?=$EMR_MOBILE;?>" class="form-control col-md-7 col-xs-12" >
                                        </div></div>
                                    

                                    
                                    <br><br><br>

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




                
        
<?php require_once 'footer_content.php' ?>