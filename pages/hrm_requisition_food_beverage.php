 <?php
require_once 'support_file.php';
$title="Food & Beverage Requisition";

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$now=time();

$table="warehouse_other_issue";
$unique = 'oi_no';   // Primary Key of this Database table
$table_deatils = 'warehouse_other_issue_detail';
$details_unique = 'id';
$page="hrm_requisition_food_beverage.php";
$crud      =new crud($table);
$taken=getSVALUE("".$table_deatils."", "SUM(qty)", " where oi_date between '$dfrom' and '$dto' and  issued_to='".$_SESSION[PBI_ID]."' and item_id=".$_GET[item_code_GET]."");
$unit=getSVALUE("item_info", "unit_name", " where item_id=".$_GET[item_code_GET]."");
$department=getSVALUE("personnel_basic_info", "PBI_DEPARTMENT", " where PBI_ID=".$_SESSION[PBI_ID]."");
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
   
    if(isset($_POST['initiate']))
    {		
		
		
		$_POST['section_id'] = $_SESSION['sectionid'];
		$_POST['company_id'] = $_SESSION['companyid'];
		$_POST['req_category']='1500010000';
		$_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
		$sd=$_POST[oi_date]; 
		$_POST[oi_date]=date('Y-m-d' , strtotime($sd));		
	    $_POST['issue_type'] = 'Office Issue';	
	    $_POST['status'] = 'MANUAL';
		$_POST['requisition_from'] = $department;
	    $_POST['warehouse_id'] = '11';
		$_POST['issued_to'] = $_SESSION[PBI_ID];
		$_SESSION['initiate_hrm_food_beverage_requisition']=$_POST[$unique];		
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';		

        unset($_POST);
        unset($$unique);
    }
	
	
	if(isset($_POST['add']))
    {			
		
		$_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
		$sd=$_POST[oi_date]; 
		$_POST[oi_date]=date('Y-m-d' , strtotime($sd));
		
		$serdate=$_POST[serving_date]; 
		$_POST[serving_date]=date('Y-m-d' , strtotime($serdate));	
		$_POST[item_id]='1500010001';
		
			
	    $_POST['issue_type'] = 'Office Issue';	
	    $_POST['status'] = 'MANUAL';
		$_POST['requisition_from'] = $_SESSION["department"];
	    $_POST['warehouse_id'] = '11';
		$_POST['recommend_qty'] = $_POST['qty'];
		$_POST['request_qty'] = $_POST['qty'];
		$_POST['issued_to'] = $_SESSION[PBI_ID];
		$_POST[oi_no]=$_SESSION['initiate_hrm_food_beverage_requisition'];	
        $crud      =new crud($table_deatils);
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';		

        unset($_POST);
        unset($$unique);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
	$sd=$_POST[oi_date]; 
    $_POST[oi_date]=date('Y-m-d' , strtotime($sd));
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
	$sd=$_POST[oi_date]; 
    $_POST[oi_date]=date('Y-m-d' , strtotime($sd));
    $crud->update($unique);
    $type=1;
    //echo $targeturl;
    
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
}}

// data query..................................
if(isset($_SESSION[initiate_hrm_food_beverage_requisition]))
{   $condition=$unique."=".$_SESSION[initiate_hrm_food_beverage_requisition];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>

<SCRIPT language=JavaScript>
function reload(form){
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='<?=$page;?>?item_code_GET=' + val ;}
</script>

<?php require_once 'body_content.php'; ?>




                    <div class="col-md-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo $title; ?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            
                            
                            <div class="x_content"> 
                            <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
                                 <? //require_once 'support_html.php';?>
                                     <table style="width:100%"  cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="width:50%;">
                                            <div class="form-group">                                            
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Requisition* No<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<? if($_SESSION['initiate_hrm_food_beverage_requisition']>0) { echo  $_SESSION['initiate_hrm_food_beverage_requisition']; 
											
														} else 
											
											{ echo find_a_field($table,'max('.$unique.')+1','1');											
											if($$unique<1) $$unique = 1;}?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%">
                                                    </div>
                                                </div></td>


                                            <td style="width:50%">
                                            <div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Requisition Date<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="oi_date" readonly  required="required" name="oi_date" value="<?php if($_SESSION[initiate_hrm_food_beverage_requisition]>0){ echo date('m/d/y' , strtotime($oi_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%" >      </div>
                                                </div>
                                            </td></tr>

<tr><td style="height:5px"></td></tr>
<tr>
                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Priority<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12"><select style="width: 100%" class="select2_single form-control" name="Priority" id="Priority">
                      <option></option>
                      <option value="Urgent" <?php if ($Priority=='Urgent') echo 'selected'; else echo '';?> >Urgent</option>
                      <option value="High" <?php if ($Priority=='High') echo 'selected'; else echo '';?>>High</option>
                      <option value="Medium" <?php if ($Priority=='Medium') echo 'selected'; else echo '';?>>Medium</option>
                      <option value="Low" <?php if ($Priority=='Low') echo 'selected'; else echo '';?>>Low</option>                      
                      </select>
                                                    </div></div></td>
                                                    
                                                    

                                            <td><div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Remarks<span class="required">*</span></label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="oi_subject" id="oi_subject" value="<?=$oi_subject?>" class="form-control col-md-7 col-xs-12" style="width: 100%;"></div></div></td>
                                        </tr>

<tr><td style="height:5px"></td></tr>
                                    <tr>
                                    <td>
                                    <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Recommended By<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
           <select style="width: 100%" class="select2_single form-control" name="recommended_by" id="recommended_by">
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
                          <option  value="<?=$row[PBI_ID]; ?>" <?php if($recommended_by==$row[PBI_ID]) echo 'selected' ?>><?=$row[PBI_ID_UNIQUE]; ?>#><?=$row[PBI_NAME];?>#> (<?=$row[DEPT_SHORT_NAME];?>)</option>
                      <?php } ?></select>
                                        </div></div> 
                                        </td>
                                        
                                        
                                                                          
                                    <td>
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Authorised By<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
           <select style="width: 100%;" class="select2_single form-control" name="authorised_person" id="authorised_person">
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
                          <option  value="<?=$row[PBI_ID]; ?>" <?php if($authorised_person==$row[PBI_ID]) echo 'selected' ?>><?=$row[PBI_ID_UNIQUE]; ?>#><?=$row[PBI_NAME];?>#> (<?=$row[DEPT_SHORT_NAME];?>)</option>
                      <?php } ?></select>
                                    </div></div>
                                    </td>
                                    </tr>

                                        </table>











                                    <div class="form-group" style="margin-left:40%; margin-top: 15px">

                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_SESSION[initiate_hrm_food_beverage_requisition]){  ?>
                                               <button type="submit" name="modify" class="btn btn-success" onclick='return window.confirm("Are you confirm to Update?");'>Update <?=$title;?></button>
                                                
                                                
                                                <!--button type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Update?");'>Deleted</button-->

                                            <?php   } else {?>
                                                <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Initiate <?=$title;?></button>
                                            <?php } ?>
                                        </div></div>
                                </form></div></div></div>











                    <?php if($_SESSION[initiate_hrm_food_beverage_requisition]){  ?>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <form action="" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post"><? require_once 'support_html.php';?>
                                    <table class="table table-striped table-bordered" style="width:100%">
                                    
                                    <thead>
                                    <th style="text-align:center">Serving<br />Date</th>
                                    <th style="text-align:center">Serving<br />Time</th>
                                    <th style="text-align:center">Serving<br />Place</th>
                                    <th style="text-align:center">Purpose of Requisition</th>
                                    <th style="text-align:center">Person number to be Served</th>
                                    <th style="text-align:center">Preffered Item</th>
                                    <th style="text-align:center">Number of Item</th>
                                    <th style="text-align:center">Price</th>
                                    <th style="text-align:center">Prefered Restaurent/Shop:</th>
                                    </thead>
                                        <tbody>
                                        <tr>
                                            <td align="left">
                                            <input type="hidden" name="oi_date" id="oi_date" value="<?=$oi_date;?>"  />
                                            <input type="text" name="serving_date" style="width:82px; height:37px; font-size: 12px; text-align:center" id="serving_date" value="<?=$serving_date;?>" class="form-control col-md-7 col-xs-12" />
                                            
      </td>
                                           
                                               <td align="center">
      <input type="text" id="serving_time" style="width:60px; height:37px; font-size: 12px; text-align:center" name="serving_time" class="form-control col-md-7 col-xs-12"  ></td>


                                            <td align="center">
                                                 <input type="text" id="serving_place" style="width:100px; height:37px; font-size: 12px; text-align:center" name="serving_place" placeholder="place" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 <td align="center">
                                                 <input type="text" id="requisition_purpose" style="width:80px; height:37px; font-size: 12px; text-align:center" name="requisition_purpose" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 <td align="center">
                                                 <input type="text" id="served_person" style="width:80px; height:37px; font-size: 12px; text-align:center" name="served_person" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 <td align="center">
                                                 <input type="text" id="item_details" style="width:80px; height:37px; font-size: 12px; text-align:center" name="item_details" class="form-control col-md-7 col-xs-12" ></td> 
                                                 
                                                 <td align="center">
                                                 <input type="number" id="qty" style="width:80px; height:37px; font-size: 12px; text-align:center" name="qty"  class="form-control col-md-7 col-xs-12" ></td>  
                                                 
                                                 <td align="center">
                                                 <input type="text" id="rate" style="width:80px; height:37px; font-size: 12px; text-align:center" name="rate" placeholder="price" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 <td align="center">
                                                 <input type="text" id="restaurent" style="width:100px; height:37px; font-size: 12px; text-align:center" name="restaurent" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 
                                            <td align="center" style="width:5%"><button type="submit" class="btn btn-success" name="add" id="add">Add</button></td></tr>
                                            </tbody>
                                    </table>
                                    <input name="count" id="count" type="hidden" value="" />
                                </form>
                            </div></div></div></div>


                <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

              
                <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
                    <table  class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th style="text-align:center">Serving<br />Date</th>
                                    <th style="text-align:center">Serving<br />Time</th>
                                    <th style="text-align:center">Serving<br />Place</th>
                                    <th style="text-align:center">Purpose of Requisition</th>
                                    <th style="text-align:center">Person number to be Served</th>
                                    <th style="text-align:center">Preffered Item</th>
                                    <th style="text-align:center">Number of Item</th>
                                    <th style="text-align:center">Price</th>
                                    <th style="text-align:center">Prefered Restaurent/Shop:</th>
                            <th style="width:15%; text-align:center">Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                     
                        $rs=mysql_query("Select 
d.*,i.*
from 
warehouse_other_issue_detail d,
item_info i
  where 
 d.item_id=i.item_id and 
 d.oi_no='".$_SESSION['initiate_hrm_food_beverage_requisition']."'
 ");
                        while($uncheckrow=mysql_fetch_array($rs)){


                            //if(prevent_multi_submit()){
                            if (isset($_POST['confirmsave'])){
                                mysql_query("Update warehouse_other_issue set status='PENDING' where oi_no=".$_SESSION['initiate_hrm_food_beverage_requisition']."");
								

$name=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$_SESSION[PBI_ID]);
$emailId=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$recommended_by);
$emailIds=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$authorised_person);
			$to = $emailId;
				$subject = "Requisition for Food & Beverage";
				$txt1 = "<p>Dear Sir,</p>				
				<p>A requisition is pending for your Recommendation/Authorization. Please enter Employee Access module to approve the requisition. </p>				<p>Requisition By- ".$name."</p>				
				<p><b><i>This EMAIL is automatically generated by ERP Software.</i></b></p>";				
				$txt=$txt1.$txt2.$tr;				
				$from = 'erp@icpbd.com';
				$headers = "";
$headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
$headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
mail($to,$subject,$txt,$headers); 
								
								
                                unset($_SESSION['initiate_hrm_food_beverage_requisition']);
                                ?> <meta http-equiv="refresh" content="0;<?=$page;?>"> <?php }





                            $js=$js+1;
                            $ids=$uncheckrow[id];
							$dateup=$_POST['serving_date_up_'.$ids]; 
                            $serving_date_up=date('Y-m-d' , strtotime($dateup));
                            $serving_time_up=$_POST['serving_time_up_'.$ids];
							$serving_place_up=$_POST['serving_place_up_'.$ids];
							$requisition_purpose_up=$_POST['requisition_purpose_up_'.$ids];
							$served_person_up=$_POST['served_person_up_'.$ids];
							$item_details_up=$_POST['item_details_up_'.$ids];
							$qty_up=$_POST['qty_up_'.$ids];
							$rate_up=$_POST['rate_up_'.$ids];
							$restaurent_up=$_POST['restaurent_up_'.$ids];
							
                            


                            if(isset($_POST['deletedata'.$ids]))
                            {
                                mysql_query("DELETE FROM warehouse_other_issue_detail WHERE id='$ids'"); ?>
                                <meta http-equiv="refresh" content="0;<?=$page?>">
                                <?php
                            }

                            if(isset($_POST['editdata'.$ids]))
                            {
                                mysql_query("Update ".$table_deatils." set serving_date='$serving_date_up',serving_time='$serving_time_up',serving_place='$serving_place_up',requisition_purpose='$requisition_purpose_up',served_person='$served_person_up',item_details='$item_details_up',qty='$qty_up',rate='$rate_up',restaurent='$restaurent_up' WHERE id='$ids'"); ?>
                                <meta http-equiv="refresh" content="0;<?=$page?>">
                            <?php }?>


                            <tr>                               
                                <td style="vertical-align:middle">
                                <input type="text" name="serving_date_up_<?=$ids?>" style="width:82px; height:37px; font-size: 12px; text-align:center" id="serving_date_up_<?=$ids?>" value="<?php if($_SESSION[initiate_hrm_food_beverage_requisition]>0){ echo date('m/d/Y' , strtotime($uncheckrow[serving_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" />
                                </td>
                         <td align="center">
      <input type="text" id="serving_time_up_<?=$ids?>" style="width:60px; height:37px; font-size: 12px; text-align:center" name="serving_time_up_<?=$ids?>" value="<?=$uncheckrow[serving_time];?>" class="form-control col-md-7 col-xs-12"  ></td>


                                            <td align="center">
                                                 <input type="text" id="serving_place_up_<?=$ids?>" style="width:100px; height:37px; font-size: 12px; text-align:center" name="serving_place_up_<?=$ids?>" value="<?=$uncheckrow[serving_place];?>" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 
                                                 <td align="center">
                                                 <input type="text" id="requisition_purpose_up_<?=$ids?>" style="width:80px; height:37px; font-size: 12px; text-align:center" name="requisition_purpose_up_<?=$ids?>" value="<?=$uncheckrow[requisition_purpose];?>" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 <td align="center">
                                                 <input type="text" id="served_person_up_<?=$ids?>" style="width:80px; height:37px; font-size: 12px; text-align:center" name="served_person_up_<?=$ids?>" value="<?=$uncheckrow[served_person];?>" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 <td align="center">
                                                 <input type="text" id="item_details_up_<?=$ids?>" style="width:80px; height:37px; font-size: 12px; text-align:center" name="item_details_up_<?=$ids?>" value="<?=$uncheckrow[item_details];?>" class="form-control col-md-7 col-xs-12" ></td> 
                                                 
                                                 <td align="center">
                                                 <input type="number" id="qty_up_<?=$ids?>" style="width:80px; height:37px; font-size: 12px; text-align:center" name="qty_up_<?=$ids?>" value="<?=$uncheckrow[qty];?>"  class="form-control col-md-7 col-xs-12" ></td>  
                                                 
                                                 <td align="center">
                                                 <input type="text" id="rate_up_<?=$ids?>" style="width:80px; height:37px; font-size: 12px; text-align:center" name="rate_up_<?=$ids?>" value="<?=$uncheckrow[rate];?>" class="form-control col-md-7 col-xs-12" ></td>
                                                 
                                                 <td align="center">
                                                 <input type="text" id="restaurent_up_<?=$ids?>" style="width:100px; height:37px; font-size: 12px; text-align:center" name="restaurent_up_<?=$ids?>" value="<?=$uncheckrow[restaurent];?>" class="form-control col-md-7 col-xs-12" ></td>


                                <td align="center" style="width:10%;vertical-align:middle">
                                    <button type="submit" name="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Edit Date?");'><img src="update.jpg" style="width:20px;  height:20px"></button>
                                    <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete Credit Voucher?");'><img src="delete.png" style="width:20px;  height:18px"></button>
                                </td>

                            </tr>
                            <script>
    $(document).ready(function() {
        $('#serving_date_up_<?=$ids?>').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
                            <?php  } ?>





                        </tbody>

                       <tr>
                            <td colspan="10" style="text-align:center">
                                <?php
                                if(isset($_POST[cancel])){
                                    $deletes=mysql_query("Delete From warehouse_other_issue where oi_no='$_SESSION[initiate_hrm_food_beverage_requisition]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                                    $deletes=mysql_query("Delete From warehouse_other_issue_detail where oi_no='$_SESSION[initiate_hrm_food_beverage_requisition]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                                    unset($_SESSION["initiate_hrm_food_beverage_requisition"]); ?>
                                    <meta http-equiv="refresh" content="0;<?=$page;?>">
                                <?php } ?>
                                <button type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the requisition?");' class="btn btn-danger">Delete the Requisition </button>
                                
                                    <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Confirm the requisition?");' name="confirmsave" class="btn btn-success">Confirm and Finish the Requisition </button>
                                


                            </td></tr></table></form>
    <?php } ?>
<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#oi_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#serving_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
