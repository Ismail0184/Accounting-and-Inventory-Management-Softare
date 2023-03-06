 <?php
require_once 'support_file.php';
$title="Food & Beverage Requisition";
$now=time();
$table="warehouse_other_issue";
$unique = 'oi_no';   // Primary Key of this Database table
$table_details='warehouse_other_issue_detail';
$details_unique = 'id';
$page="hrm_requisition_food_beverage.php";
$crud      = new crud($table);
$department=getSVALUE("personnel_basic_info", "PBI_DEPARTMENT", " where PBI_ID=".$_SESSION['PBI_ID']."");

if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {
		$_POST['section_id'] = $_SESSION['sectionid'];
		$_POST['company_id'] = $_SESSION['companyid'];
		$_POST['req_category']='1500010000';
		$_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
		$sd=$_POST['oi_date'];
		$_POST['oi_date']=date('Y-m-d' , strtotime($sd));
	    $_POST['issue_type'] = 'Office Issue';	
	    $_POST['status'] = 'MANUAL';
		$_POST['requisition_from'] = $department;
	    $_POST['warehouse_id'] = '11';
		$_POST['issued_to'] = $_SESSION['PBI_ID'];
		$_SESSION['initiate_hrm_food_beverage_requisition']=$_POST[$unique];		
        $crud->insert();
        unset($_POST);
        unset($$unique);
    }

	if(isset($_POST['add'])) {
		$_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
		$sd=$_POST['oi_date'];
		$_POST['oi_date']=date('Y-m-d' , strtotime($sd));
		$serdate=$_POST['serving_date'];
		$_POST['serving_date']=date('Y-m-d' , strtotime($serdate));
		$_POST['item_id']='1500010001';
	    $_POST['issue_type'] = 'Office Issue';	
	    $_POST['status'] = 'MANUAL';
		$_POST['requisition_from'] = $_SESSION["department"];
	    $_POST['warehouse_id'] = '11';
		$_POST['recommend_qty'] = $_POST['qty'];
		$_POST['request_qty'] = $_POST['qty'];
		$_POST['issued_to'] = $_SESSION['PBI_ID'];
		$_POST['oi_no']=$_SESSION['initiate_hrm_food_beverage_requisition'];
        $crud      =new crud($table_details);
        $crud->insert();
        unset($_POST);
        unset($$unique);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
	$sd=$_POST['oi_date'];
    $_POST['oi_date']=date('Y-m-d' , strtotime($sd));
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
	$sd=$_POST['oi_date'];
    $_POST['oi_date']=date('Y-m-d' , strtotime($sd));
    $crud->update($unique);
}}

 if (isset($_POST['confirm'])){
     $up_master=mysqli_query($conn, "UPDATE ".$table." SET status='PENDING' where ".$unique."=".$_SESSION['initiate_hrm_food_beverage_requisition']);
     unset($_SESSION['initiate_hrm_food_beverage_requisition']);
     unset($_POST);
 }

 //for Delete..................................
 if(isset($_POST['cancel']))
 {   $crud = new crud($table);
     $condition=$unique."=".$_SESSION['initiate_hrm_food_beverage_requisition'];
     $crud->delete($condition);
     $crud = new crud($table_details);
     $condition=$unique."=".$_SESSION['initiate_hrm_food_beverage_requisition'];
     $crud->delete_all($condition);
     unset($_SESSION['initiate_hrm_food_beverage_requisition']);
     unset($_POST);
 }


// data query..................................
if(isset($_SESSION['initiate_hrm_food_beverage_requisition']))
{   $condition=$unique."=".$_SESSION['initiate_hrm_food_beverage_requisition'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
 $COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$_SESSION['initiate_hrm_food_beverage_requisition'].'');
$res="SELECT id,serving_date,serving_time,serving_place,requisition_purpose as purpose,served_person as 'Person number
to be Served',item_details as 'Preferred Item',qty as 'Number of Item',rate as price,restaurent as 'Preferred Restaurant/Shop' from ".$table_details." where ".$unique."=".$_SESSION['initiate_hrm_food_beverage_requisition'];
 $query=mysqli_query($conn, $res);
while($row=mysqli_fetch_object($query)){
     if(isset($_POST['deletedata'.$row->id])){
         mysqli_query($conn, ("DELETE FROM ".$table_details." WHERE id=".$row->id));
         unset($_POST);
     }
    if(isset($_POST['editdata'.$row->id]))
    {  mysqli_query($conn, ("UPDATE ".$table_details." SET serving_date='".$_POST['serving_date']."', serving_time='".$_POST['serving_time']."',serving_place='".$_POST['serving_place']."',requisition_purpose='".$_POST['requisition_purpose']."',served_person='".$_POST['served_person']."',
        item_details='".$_POST['item_details']."',qty='".$_POST['qty']."',rate='".$_POST['rate']."',restaurent='".$_POST['restaurent']."' WHERE id=".$row->id));
        unset($_POST);}

} // end of deletedata
 if (isset($_REQUEST['id'])) {
     $edit_value=find_all_field(''.$table_details.'','','id='.$_REQUEST['id'].'');
 }
 $uniqueID = $_REQUEST['id'];
 ?>

<?php require_once 'header_content.php'; ?>
<style>
    input[type=text], input[type=date], input[type=number]{
        font-size: 11px;
        width: 100%;
    }
    .btn-font-size{
        font-size: 12px;
    }
    .th-vertical-align {
        vertical-align: middle;
    }
</style>

<?php require_once 'body_content.php'; ?>
 <div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page?>" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                <table style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:50%;">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Requisition* No<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<? if($_SESSION['initiate_hrm_food_beverage_requisition']>0) { echo  $_SESSION['initiate_hrm_food_beverage_requisition'];
                                    } else { echo find_a_field($table,'max('.$unique.')+1','1');
											if($$unique<1) $$unique = 1;}?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%">
                                </div>
                            </div>
                        </td>
                        <td style="width:50%">
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Requisition Date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date"  required="required" min="<?=date('Y-m-d')?>" name="oi_date" value="<?=$oi_date?>" class="form-control col-md-7 col-xs-12" style="width:100%" >
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Priority<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12"><select style="width: 100%" class="select2_single form-control" name="Priority" id="Priority">
                                        <option></option>
                                        <option value="Urgent" <?php if ($Priority=='Urgent') echo 'selected'; else echo '';?> >Urgent</option>
                                        <option value="High" <?php if ($Priority=='High') echo 'selected'; else echo '';?>>High</option>
                                        <option value="Medium" <?php if ($Priority=='Medium') echo 'selected'; else echo '';?>>Medium</option>
                                        <option value="Low" <?php if ($Priority=='Low') echo 'selected'; else echo '';?>>Low</option>
                                    </select>
                                </div>
                            </div>
                        </td>

                        <td><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Remarks<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="oi_subject" id="oi_subject" value="<?=$oi_subject?>" class="form-control col-md-7 col-xs-12" style="width: 100%;">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>

                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Recommended By<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select style="width: 100%" class="select2_single form-control" name="recommended_by" id="recommended_by">
                                        <option></option>
                                        <?=advance_foreign_relation(find_active_user_HO($recommended_by));?>
                                    </select>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Authorised By<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select style="width: 100%;" class="select2_single form-control" name="authorised_person" id="authorised_person">
                                        <option></option>
                                        <?=advance_foreign_relation(find_active_user_HO($authorised_person));?>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="form-group" style="margin-left:40%; margin-top: 15px">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($_SESSION['initiate_hrm_food_beverage_requisition']):  ?>
                            <button type="submit" name="modify" class="btn btn-primary btn-font-size" onclick='return window.confirm("Are you confirm to Update?");'>Update <?=$title;?></button>
                        <?php else: ?>
                            <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary btn-font-size">Initiate <?=$title;?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

 <?php if($_SESSION['initiate_hrm_food_beverage_requisition']){  ?>
                                <form action="<?=$page?>" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post"><? require_once 'support_html.php';?>
                                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                                        <tr style="background-color: bisque">
                                            <th style="text-align:center; vertical-align: middle">Serving<br />Date</th>
                                            <th style="text-align:center; vertical-align: middle">Serving<br />Time</th>
                                            <th style="text-align:center; vertical-align: middle">Serving<br />Place</th>
                                            <th style="text-align:center; vertical-align: middle">Purpose of<br />Requisition</th>
                                            <th style="text-align:center; vertical-align: middle">Person number<br />to be Served</th>
                                            <th style="text-align:center; vertical-align: middle">Preferred<br />Item</th>
                                            <th style="text-align:center; vertical-align: middle">Number of<br />Item</th>
                                            <th style="text-align:center; vertical-align: middle">Price</th>
                                            <th style="text-align:center; vertical-align: middle">Preferred<br />Restaurant/Shop</th>
                                            <th style="text-align:center; vertical-align: middle">Option</th>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; vertical-align: middle">
                                                <input type="hidden" name="oi_date" id="oi_date" value="<?=$oi_date;?>"  />
                                                <input type="date" name="serving_date" style="width:100%;text-align:center" value="<?=$edit_value->serving_date;?>" class="form-control col-md-7 col-xs-12" />
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="time" id="serving_time" style=" width: 100%;text-align:center; font-size: 11px" name="serving_time" value="<?=$edit_value->serving_time;?>" class="form-control col-md-7 col-xs-12"  >
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="text" id="serving_place"  name="serving_place" value="<?=$edit_value->serving_place;?>" class="form-control col-md-7 col-xs-12" >
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="text" id="requisition_purpose"  name="requisition_purpose" value="<?=$edit_value->requisition_purpose;?>" class="form-control col-md-7 col-xs-12" >
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="text" id="served_person" name="served_person" value="<?=$edit_value->served_person;?>" class="form-control col-md-7 col-xs-12" >
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="text" id="item_details" value="<?=$edit_value->item_details;?>" name="item_details" class="form-control col-md-7 col-xs-12" >
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="number" id="qty"  name="qty" value="<?=$edit_value->qty;?>" class="form-control col-md-7 col-xs-12" >
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="number"  name="rate" value="<?=$edit_value->rate;?>" class="form-control col-md-7 col-xs-12" >
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">
                                                <input type="text" id="restaurent"  name="restaurent" value="<?=$edit_value->restaurent;?>" class="form-control col-md-7 col-xs-12" >
                                            </td>
                                            <td style="text-align:center; vertical-align: middle" style="width:5%">
                                                <?php if (isset($uniqueID)) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$uniqueID;?>"  style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Cancel?");' class="btn btn-danger">Cancel</a>
                                                <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
 <?=added_data_delete_edit($res,$unique,$_SESSION['initiate_hrm_food_beverage_requisition'],$COUNT_details_data,$page,$total_amount,11);?><br><br>
 <?php } ?>
 <?=$html->footer_content();?>