 <?php
require_once 'support_file.php';
$title="Vehicle Register";

$now=time();
$unique='id';
$unique_field='registration_no';
$table="vehicle_registration";
$page="hrm_vehicle_vehicle_entry.php";
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

                                    <table class="table table-striped table-bordered" style="width:100%;font-size:12px">
                                    <div class="form-group" style="display: none">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?=$unique?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="<?=$unique?>" style="width:100%"    name="<?=$unique?>" value="<?=$$unique?>" class="form-control col-md-7 col-xs-12" >
                                    </div></div>


                                        
                                        <tr>
                                            <td><strong>Vehicle Reg. NO</strong></td>
                                            <td><input name="registration_no" type="text" style="width: 150px; height: 25px" id="registration_no" value="<?=$registration_no;?>"/></td>
                                            <td><strong>Vehicle  Description</strong></td>
                                            <td>
                                                <input name="description" type="text" style="width: 150px; height: 25px" id="description" value="<?=$description;?>"/>
                                            </td></tr>

                                        <tr>
                                        <td><strong>Chassis  No:</strong></td>
                                            <td   class="oe_form_group_cell"><input name="chassis" type="text" style="width: 150px; height: 25px" id="chassis" value="<?=$chassis;?>"/></td>
                                            <td><strong>Engine No. </strong></td>
                                            <td><input name="engine_no" type="text" style="width: 150px; height: 25px" id="engine_no" value="<?=$engine_no;?>"/></td></tr>




                                        <tr>
                                            <td>
                                                <strong>CC: </strong>
                                            </td>
                                            <td><input name="cc" type="text" id="cc" style="width: 150px; height: 25px" value="<?=$cc;?>"/></td>

                                            <td><strong>Vehicle  Color:</strong></td>
                                            <td><input name="vehicle_color" type="text" id="vehicle_color" style="width: 150px; height: 25px" value="<?=$vehicle_color;?>"/></td>
                                        </tr>

                                        <tr>
                                            <td><strong>Owner  Name:</strong> </td>
                                            <td   class="oe_form_group_cell"><input name="owner_name" style="width: 150px; height: 25px" type="text" id="owner_name" value="<?=$owner_name;?>"/></td>


                                            <td><strong>Owner  address:</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <input name="address" type="text" style="width: 150px; height: 25px" id="address" value="<?=$address;?>"/>
                                            </td></tr>


                                        <tr>
                                        <td>
                                                <strong>Registration Certificate :</strong></td>
                                            <td  class="oe_form_group_cell">
                                                <select  name="certificate" style="width: 150px; height: 25px">
                                                    <option selected="selected">
                                                        <?=$certificate;?>
                                                    </option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select></td>

                                        <td bgcolor="#FFFFFF"  >
                                                <strong>Digital Number Plate:</strong>
                                            </td>

                                            <td><select name="number_plate" style="width: 150px; height: 25px">
                                                    <option selected="selected"><?=$number_plate;?></option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select></td></tr>

                                        <tr>
                                            <td><strong>Fitness :</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <select name="fitness" style="width: 150px; height: 25px">
                                                    <option selected="selected"><?=$fitness?></option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>


                                            </td>

                                            <td><strong>Fitness Lifetime :</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <input type="text"  name="fitness_amount" value="<?=$fitness_amount;?>" placeholder="amount" style="width: 150px; height: 25px"><br />
                                                <input type="text"  style="width: 150px; height: 25px; margin-top: 5px" name="fitness_date_from" placeholder="from" id="s_date" value="<?=$fitness_date_from;?>"  ></br>
                                                <input type="text" style="margin-top:5px" name="fitness_date_to" id="e_date" placeholder="to" value="<?=$fitness_date_to;?>">
                                                <input name="fitnessday" type="hidden" id="total_days"  value="" />
                                                &nbsp;&nbsp;<b id="total_leave"> Total
                                            </td></tr>

                                        <tr>
                                            <td><strong>Tax Token :</strong></td>
                                            <td   class="oe_form_group_cell"><select name="tax_token" style="width: 150px; height: 25px">
                                                    <option selected="selected"><?=$fitness?></option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </td>

                                            <td><strong>Tax Token  Lifetime :</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <input type="text"  name="tax_token_amount" value="<?=$tax_token_amount;?>" placeholder="amount"><br />
                                                <input type="text" style="margin-top:5px" value="<?=$tax_token_date_from;?>" name="tax_token_date_from" placeholder="from" id="tf_date"></br>
                                                <input type="text" style="margin-top:5px" value="<?=$tax_token_date_to;?>" name="tax_token_date_to" placeholder="to" id="tt_date">
                                                <input name="taxtokenday" type="hidden" id="total_dayss"  value="" /></td></tr>

                                        <tr>
                                            <td><strong>Insurance :</strong></td>
                                            <td   class="oe_form_group_cell"><select name="insurance" style="width: 150px; height: 25px">
                                                    <option selected><?=$insurance;?></option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select></td>

                                            <td><strong>Insurance Lifetime :</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <input type="text"  name="insurance_amount" placeholder="amount" value="<?=$insurance_amount;?>"><br />
                                                <input type="text" style="margin-top:5px" name="insurance_date_from" id="insurance_date_from" value="<?=$insurance_date_from;?>" placeholder="from" ></br>
                                                <input type="text" style="margin-top:5px" name="insurance_date_to" placeholder="to" id="insurance_date_to" value="<?=$insurance_date_from;?>">
                                                <input name="insurancedays" type="hidden" id="total_daysss"  value="" />
                                                &nbsp;&nbsp;<b id="total_leavess">Total</td>
                                        </tr>

                                        <tr>
                                        <td><strong>Employee Details :</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <select style="width: 250px; height: 25px" class="select2_single form-control" name="employee_id" id="employee_id">
                                                    <option></option>
                                                    <?php
                                                    $result=mysql_query("SELECT  p.*,d.* FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME");
                                                    while($row=mysql_fetch_array($result)){  ?>
                                                        <option  value="<?=$row[PBI_ID]; ?>" <?php if($employee_id==$row[PBI_ID]) echo 'selected' ?>><?=$row[PBI_ID_UNIQUE]; ?>#><?=$row[PBI_NAME];?>#> (<?=$row[DEPT_SHORT_NAME];?>)</option>
                                                    <?php } ?></select></td>

                                            <td><strong>Duration:</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <input type="text" name="employee_date_from" placeholder="from" id="employee_date_from" value="<?=$employee_date_from;?>"></br>
                                                <input type="text" style="margin-top:5px" name="employee_date_to" placeholder="to" id="employee_date_to" value="<?=$employee_date_to;?>"></br>
                                            </td></tr>



                                        <tr>
                                            <td><strong>Driver Name:</strong></td>
                                            <td   class="oe_form_group_cell">
                                                <input style="width: 150px; height: 25px" type="text" name="driver_name" id="driver_name" value="<?=$driver_name;?>"></br>

                                            </td></tr>


                                        </tbody>
                                    </table>
                                    
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
                                            <button type="submit" name="record" id="record"  class="btn btn-primary">New Vehicle Register</button>
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
                                <h2>List of <?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <? 	$res='select '.$unique.','.$unique.' as Code,'.$unique_field.',description,chassis,engine_no,cc,vehicle_color,owner_name,address,certificate,number_plate from '.$table.' order by '.$unique;
                                echo $crud->link_report_popup($res,$link);?>
                                <?=paging(10);?>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->


                
        
<?php require_once 'footer_content.php' ?>