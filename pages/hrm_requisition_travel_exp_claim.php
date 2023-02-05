 <?php
require_once 'support_file.php';
$title="Travel Expenses Claim Requisition";

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$now=time();

$table="travel_application_claim_master";
$unique = 'trvClaim_id';   // Primary Key of this Database table
$table_deatils = 'travel_application_claim_details';
$details_unique = 'trvClaim_id';
$page="hrm_requisition_travel_exp_claim.php";
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
        $_POST['hrm_viewed']='NO';
		
		$_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
		
		$ap=$_POST[application_date]; 
		$_POST[application_date]=date('Y-m-d' , strtotime($ap));
		
		
		$tf=$_POST[trvDate_from]; 
        $_POST[trvDate_from]=date('Y-m-d' , strtotime($tf));
	
	
	    $tto=$_POST[trvDate_to]; 
        $_POST[trvDate_to]=date('Y-m-d' , strtotime($tto));
	
	
	    $dd=$_POST[departure_date]; 
        $_POST[departure_date]=date('Y-m-d' , strtotime($dd));
	
	
	    $rd=$_POST[return_date]; 
        $_POST[return_date]=date('Y-m-d' , strtotime($rd));
				
	    $_POST['issue_type'] = 'Office Issue';	
	    $_POST['status'] = 'MANUAL';
		$_POST['requisition_from'] = $department;
	    $_POST['warehouse_id'] = '11';
		$_POST['PBI_ID'] = $_SESSION[PBI_ID];
		$_SESSION['initiate_travel_exp_claim_requisition']=$_POST[$unique];		
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
		$sd=$_POST[travel_date]; 
		$_POST[travel_date]=date('Y-m-d' , strtotime($sd));		
	    $_POST['issue_type'] = 'Office Issue';	
	    $_POST['status'] = 'MANUAL';
		$_POST['requisition_from'] = $_SESSION["department"];
	    $_POST['warehouse_id'] = '11';
		$_POST['recommend_qty'] = $_POST['qty'];
		$_POST['request_qty'] = $_POST['qty'];
		$_POST['issued_to'] = $_SESSION[PBI_ID];
		$_POST[oi_no]=$_SESSION['initiate_travel_exp_claim_requisition'];	
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
	
	$ap=$_POST[application_date]; 
		$_POST[application_date]=date('Y-m-d' , strtotime($ap));
	$sd=$_POST[trvDate_from]; 
    $_POST[trvDate_from]=date('Y-m-d' , strtotime($sd));
	
	
	$sd=$_POST[trvDate_to]; 
    $_POST[trvDate_to]=date('Y-m-d' , strtotime($sd));
	
	
	$sd=$_POST[departure_date]; 
    $_POST[departure_date]=date('Y-m-d' , strtotime($sd));
	
	
	$sd=$_POST[return_date]; 
    $_POST[return_date]=date('Y-m-d' , strtotime($sd));
	
	
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
{   $crud = new crud($table);
    $condition=$unique."=".$_SESSION['initiate_travel_exp_claim_requisition'];
    $crud->delete($condition);

    $crud = new crud($table_deatils);
    $condition = $unique . "=" . $_SESSION['initiate_travel_exp_claim_requisition'];
    $crud->delete_all($condition);
    unset($_SESSION['initiate_travel_exp_claim_requisition']);
    $type=1;
    $msg='Successfully Deleted.';
    echo $targeturl;

}}


if (isset($_POST['confirmsave'])){

mysql_query("Update ".$table." set status='PENDING' where ".$unique."=".$_SESSION['initiate_travel_exp_claim_requisition']."");$approved_by= find_a_field('travel_application_claim_master','approved_by','trvClaim_id='.$_SESSION['initiate_travel_exp_claim_requisition']);
$authorised_person=find_a_field('travel_application_claim_master','authorised_person','trvClaim_id='.$_SESSION['initiate_travel_exp_claim_requisition']);
$myid=$_SESSION[PBI_ID];
$name=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$myid);
$emailId=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$approved_by);
$emailIds=find_a_field('essential_info','ESS_CORPORATE_EMAIL','PBI_ID='.$authorised_personss);
	
	 
		//if($emailId!=''){
				$to = $emailId;
				$subject = "Requisition for Travel Exp. Claim" ;
				$txt1 = "<p>Dear Sir,</p>				
				<p>A Travel Exp. Claim requisition is pending for your Recommendation/Authorization. Please enter Employee Access module to approve the requisition. </p>				<p><strong>Requisition By-</strong> ".$name."</p>				
				<p><b><i>This EMAIL is automatically generated by ERP Software.</i></b></p>";
				
				$txt=$txt1.$txt2.$tr;
				
				
				$from = 'erp@icpbd.com';
				$headers = "";
    $headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
$headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
mail($to,$subject,$txt,$headers);
								
								
                                unset($_SESSION['initiate_travel_exp_claim_requisition']);
}

// data query..................................
if(isset($_SESSION[initiate_travel_exp_claim_requisition]))
{   $condition=$unique."=".$_SESSION[initiate_travel_exp_claim_requisition];
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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
   
<script>
    $('#lodging_fair_rqst').keyup(function(){
        var transport_fair_rqst;
        var lodging_fair_rqst;
        transport_fair_rqst = parseFloat($('#transport_fair_rqst').val());
        lodging_fair_rqst = parseFloat($('#lodging_fair_rqst').val());
        var total_amount = transport_fair_rqst + lodging_fair_rqst;
        $('#total_amount').val(total_amount.toFixed(2));


    });
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
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Requisition No<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<? if($_SESSION['initiate_travel_exp_claim_requisition']>0) { echo  $_SESSION['initiate_travel_exp_claim_requisition']; 
											
														} else 
											
											{ echo find_a_field($table,'max('.$unique.')+1','1');											
											if($$unique<1) $$unique = 1;}?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%">
                                                    </div>
                                                </div></td>


                                            <td style="width:50%">
                                            <div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Requisition Date<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="application_date" readonly  required="required" name="application_date" value="<?php if($_SESSION[initiate_travel_exp_claim_requisition]>0){ echo date('m/d/y' , strtotime($application_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width:100%" >      </div>
                                                </div>
                                            </td></tr>
                                            
                                            
<tr><td style="height:3px"></td></tr>                                            
                                            <tr>
                                    <td>
                                    <div class="form-group">
             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Travel Date From<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">                         
	                	
	            <input type="text" id="trvDate_from"  required="required" name="trvDate_from" value="<?php if($_SESSION[initiate_travel_exp_claim_requisition]>0){ echo date('m/d/y' , strtotime($trvDate_from)); } else { echo ''; } ?>"  class="form-control col-md-7 col-xs-12" >
                                        </div></div> 
                                        </td>
                                        
                                        
                                                                          
                                    <td>
                                    <div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">To<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
       <input type="text" id="trvDate_to" style="width:100%" value="<?php if($_SESSION[initiate_travel_exp_claim_requisition]>0){ echo date('m/d/y' , strtotime($trvDate_to)); } else { echo ''; } ?>" required   name="trvDate_to"  class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    </td>
                                    </tr>
                    <tr><td style="height:5px"></td></tr>                   
                                    
                                     <tr>
                                    <td>
                                    <div class="form-group">
             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Departure Date<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">                         
	                	
	           <input type="text" id="departure_date" value="<?php if($_SESSION[initiate_travel_exp_claim_requisition]>0){ echo date('m/d/y' , strtotime($departure_date)); } else { echo ''; } ?>" required="required" name="departure_date"  class="form-control col-md-7 col-xs-12" >
                                        </div></div> 
                                        </td>                                      
                                        
                                                                          
                                    <td>
                                    <div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Return Date<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
       <input type="text" id="return_date" style="width:100%" value="<?php if($_SESSION[initiate_travel_exp_claim_requisition]>0){ echo date('m/d/y' , strtotime($return_date)); } else { echo ''; } ?>" required   name="return_date"  class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                                    </td>
                                    </tr>

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
                      </select></div></div>
                      <br />
                      <div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Advance Amount Taken *:<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
       <input type="text" id="advance_amount" style="width:100%; margin-top:5px" value="<?=$advance_amount;?>" required   name="advance_amount"  class="form-control col-md-7 col-xs-12" >
                                    </div></div>
                      </td>
                                                    
                                                    

                                            <td><div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Travel Purpos<span class="required">*</span></label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                 <textarea style="width:100%" name="travel_purpose" id="travel_purpose"><?=$travel_purpose?></textarea>
                 </div></div></td>
                                        </tr>

<tr><td style="height:5px"></td></tr>
                                    <tr>
                                    <td>
                                    <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Recommended By<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="approved_by" id="approved_by">
                                                <option></option>
                                                <? $sql_approved_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";
                                                advance_foreign_relation($sql_approved_by,$approved_by);?>
                                            </select>
                                        </div></div>
                                        </td>
                                        
                                        
                                                                          
                                    <td>
                                    <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Authorised By<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="authorised_person" id="authorised_person">
                                                <option></option>
                                                <? $sql_authorised_person="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";
                                                advance_foreign_relation($sql_authorised_person,$authorised_person);?>
                                            </select>
                                    </div></div>
                                    </td>
                                    </tr>

                                        </table>











                                    <div class="form-group" style="margin-left:30%; margin-top: 15px">

                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_SESSION[initiate_travel_exp_claim_requisition]){  ?>
                                               <button type="submit" name="modify" class="btn btn-success" onclick='return window.confirm("Are you confirm to Update?");'>Update <?=$title;?></button>
                                               <?php   } else {?>
                                                <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Initiate <?=$title;?></button>
                                            <?php } ?>
                                        </div></div>
                                </form></div></div></div>











                    <?php if($_SESSION[initiate_travel_exp_claim_requisition]){  ?>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <form action="" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post"><? require_once 'support_html.php';?>
                                    <table style="width:100%">
                                    
                                   <thead>
                                   <tr><th style="background-color:#F6C; text-align:center">Date</th>
                                   <th colspan="2" style="background-color:#CC3; text-align:center">Place/location</th>
                                   <th colspan="2" style="background-color:#FC0; text-align:center">Mode of Transport </th>
                                   <th colspan="2" style="background-color:#CF3; text-align:center">Lodging Expense</th> 
                                   <th style="background-color:#F6C; text-align:center">Breakfast</th>
                                   <th style="background-color:#F6C; text-align:center">Lunch</th>
                                   <th style="background-color:#F6C; text-align:center">Dinner</th>
                                   <th style="background-color:#0FF; text-align:center">Total</th>
                                   
                                    
                                   </tr>
                                   </thead> 
                                        <tbody>
                                        <tr>
<td style="background-color:#F6C; text-align:center">
<input type="hidden" id="trvClaim_id" style="width:100px"   name="trvClaim_id" value="<?=$trvClaim_id?>"  class="form-control col-md-7 col-xs-12" >

<input type="text" id="travel_date" style="width:100px"  required="required" name="travel_date"  class="form-control col-md-7 col-xs-12" >
</td>

<td align="center" style="background-color:#CC3;">
<input type="text" id="travel_from" style="width:80px"   required="required" name="travel_from" placeholder="from" class="form-control col-md-7 col-xs-12" >
</td>

<td style="background-color:#CC3;text-align:center">
<input type="text" id="travel_to" style="width:80px"   required="required" name="travel_to" placeholder="to"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="background-color:#FC0;text-align:center">
<input type="text" id="mode_of_transport" style="width:80px"  placeholder="details"  required="required" name="mode_of_transport"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="background-color:#FC0;text-align:center">
<input type="number" id="transport_fair_rqst" style="width:90px" placeholder="Fair Cost"  required="required" name="transport_fair_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="background-color:#CF3;text-align:center">
<input type="text" id="lodging_expense" style="width:80px" placeholder="details" name="lodging_expense"  class="form-control col-md-7 col-xs-12" >
</td>


<td style="background-color:#CF3;text-align:center">
<input type="number" id="lodging_fair_rqst" style="width:90px"  placeholder="Cost" name="lodging_fair_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="background-color:#F6C; text-align:center">
<input type="number" id="breakfast_rqst" style="width:90px"    name="breakfast_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="background-color:#F6C; text-align:center">
<input type="number" id="lunch_rqst" style="width:90px"    name="lunch_rqst"  class="form-control col-md-7 col-xs-12" >
</td>

<td style="background-color:#F6C; text-align:center">
<input type="number" id="dinner_rqst" style="width:90px"    name="dinner_rqst"  class="form-control col-md-7 col-xs-12" >
</td>


<td style="background-color:#0FF; text-align:center">
<input type="number" id="total_amount" style="width:80px"  name="total_amount"  class='total_amount' readonly="readonly">
</td>

<script>
                                                $(function(){
                                                    $('#transport_fair_rqst, #lodging_fair_rqst,#breakfast_rqst,#lunch_rqst,#dinner_rqst').keyup(function(){
                                                        var transport_fair_rqst = parseFloat($('#transport_fair_rqst').val()) || 0;
                                                        var lodging_fair_rqst = parseFloat($('#lodging_fair_rqst').val()) || 0;
														var breakfast_rqst = parseFloat($('#breakfast_rqst').val()) || 0;
														var lunch_rqst = parseFloat($('#lunch_rqst').val()) || 0;
														var dinner_rqst = parseFloat($('#dinner_rqst').val()) || 0;
                                                        $('#total_amount').val((transport_fair_rqst + lodging_fair_rqst + breakfast_rqst+lunch_rqst+dinner_rqst).toFixed(2));
                                                    });
                                                });
                                            </script>                                     

                                           
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
                            <th style="text-align:center">Date</th>
                            <th style="text-align:center">Place/location<br />(from - to)</th>
                            <th style="text-align:center">Mode of Transport <br /> (Details - Cost)</th>
                            <th style="text-align:center">Lodging Expense <br /> (Details - Cost)</th>
                            <th style="text-align:center">B</th>
                            <th style="text-align:center">L</th>
                            <th style="text-align:center">D</th>
                            <th style="text-align:center">Total</th>
                            <th style="text-align:center">Option</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                     
                        $rs=mysql_query("Select 
d.*
from 
".$table_deatils." d

where 
  
 d.trvClaim_id='".$_SESSION['initiate_travel_exp_claim_requisition']."'
 ");
                        while($uncheckrow=mysql_fetch_array($rs)){


                           





                            $js=$js+1;
                            $ids=$uncheckrow[id];
							$ap=$_POST['travel_date_upd'.$ids]; 
		                    $travel_date_upd=date('Y-m-d' , strtotime($ap));
                            $travel_from_upd=$_POST['travel_from_upd'.$ids];
                            $travel_to_upd=$_POST['travel_to_upd'.$ids];
							
							$mode_of_transport_upd=$_POST['mode_of_transport_upd'.$ids];
							$transport_fair_rqst_upd=$_POST['transport_fair_rqst_upd'.$ids];
							$lodging_expense_upd=$_POST['lodging_expense_upd'.$ids];
							$lodging_fair_rqst_upd=$_POST['lodging_fair_rqst_upd'.$ids];
							$breakfast_rqst_upd=$_POST['breakfast_rqst_upd'.$ids];
							$lunch_rqst_upd=$_POST['lunch_rqst_upd'.$ids];
							$dinner_rqst_upd=$_POST['dinner_rqst_upd'.$ids];
							$toalupdate=$transport_fair_rqst_upd+$lodging_fair_rqst_upd+$breakfast_rqst_upd+$lunch_rqst_upd+$dinner_rqst_upd;


                            if(isset($_POST['deletedata'.$ids]))
                            {
                                mysql_query("DELETE FROM ".$table_deatils." WHERE id='$ids'"); ?>
                                <meta http-equiv="refresh" content="0;<?=$page?>">
                                <?php
                            }

                            if(isset($_POST['editdata'.$ids]))
                            {
                                mysql_query("Update ".$table_deatils." set travel_date='$travel_date_upd',travel_from='$travel_from_upd',travel_to='$travel_to_upd',mode_of_transport='$mode_of_transport_upd',transport_fair_rqst='$transport_fair_rqst_upd',lodging_expense='$lodging_expense_upd',lodging_fair_rqst='$lodging_fair_rqst_upd',breakfast_rqst='$breakfast_rqst_upd',lunch_rqst='$lunch_rqst_upd',dinner_rqst='$dinner_rqst_upd',total_amount='$toalupdate' WHERE id='$ids'"); ?>
                                <meta http-equiv="refresh" content="0;<?=$page?>">
                            <?php }?>


                            <tr>
                                <td style="vertical-align:middle; width:8%">
                                <input type="text" id="travel_date_upd<?php echo $ids; ?>" style="width:100px;height:30px; font-weight:bold; text-align:center; float:none" name="travel_date_upd<?php echo $ids; ?>"  value="<?php if($_SESSION[initiate_travel_exp_claim_requisition]>0){ echo date('m/d/y' , strtotime($uncheckrow[travel_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >
                                </td>
                                
<script>
    $(document).ready(function() {
        $('#travel_date_upd<?php echo $ids; ?>').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_4",
        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
                                
                                
                                <td style="width:18%">
                                <input type="text" id="travel_from_upd<?php echo $ids; ?>" style="width:80px;; height:30px; font-weight:bold; text-align:center; float:none"    name="travel_from_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[travel_from];?>" >
                                <input type="text" id="travel_to_upd<?php echo $ids; ?>" style="width:80px;; height:30px; font-weight:bold; text-align:center; float:none"    name="travel_to_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[travel_to];?>" >
                                </td>
                                
                                
                         
                              

                                <td align="center" style="text-align:center; width:17%">
                        <input type="text" id="mode_of_transport_upd<?php echo $ids; ?>" style="width:80px;; height:30px; font-weight:bold; text-align:center; float:none"    name="mode_of_transport_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[mode_of_transport];?>" >                        <input type="text" id="transport_fair_rqst_upd<?php echo $ids; ?>" style="width:80px;; height:30px; font-weight:bold; text-align:center; float:none"    name="transport_fair_rqst_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[transport_fair_rqst];?>" ></td>



<td align="center" style="text-align:center; width:17%">
                        <input type="text" id="lodging_expense_upd<?php echo $ids; ?>" style="width:80px;; height:30px; font-weight:bold; text-align:center; float:none"    name="lodging_expense_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[lodging_expense];?>" >                        <input type="text" id="lodging_fair_rqst_upd<?php echo $ids; ?>" style="width:80px;; height:30px; font-weight:bold; text-align:center; float:none"    name="lodging_fair_rqst_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[lodging_fair_rqst];?>" ></td>


<td align="center" style="text-align:center;">
                        <input type="text" id="breakfast_rqst_upd<?php echo $ids; ?>" style="width:50px;; height:30px; font-weight:bold; text-align:center; float:none"    name="breakfast_rqst_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[breakfast_rqst];?>" >                        </td>
                        
                        <td align="center" style="text-align:center; ">
                        <input type="text" id="lunch_rqst_upd<?php echo $ids; ?>" style="width:50px;; height:30px; font-weight:bold; text-align:center; float:none"    name="lunch_rqst_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[lunch_rqst];?>" >                        </td>
                        
                        <td align="center" style="text-align:center;">
                        <input type="text" id="dinner_rqst_upd<?php echo $ids; ?>" style="width:50px;; height:30px; font-weight:bold; text-align:center; float:none"    name="dinner_rqst_upd<?php echo $ids; ?>"  value="<?=$uncheckrow[dinner_rqst];?>" >                        </td>
                        
                        <td align="center" style="text-align:center;">
                        <input type="text"  style="width:80px;; height:30px; font-weight:bold; text-align:center; float:none" readonly="readonly" value="<?=$uncheckrow[total_amount];?>" >                        </td>


                                <td align="center" style="vertical-align:middle">
                                    <button type="submit" name="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Edit Date?");'><img src="update.jpg" style="width:20px;  height:20px"></button>
                                    
                                    
                                    <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete Credit Voucher?");'><img src="delete.png" style="width:20px;  height:18px"></button>
                                </td>

                            </tr>
                            
							<?php 
							$totalamount=$totalamount+$uncheckrow[total_amount];
							} ?>





                        </tbody>
                        <tr style="font-weight:bold"><td colspan="7" style="text-align:right">Total Amount = </td><td style="text-align:center"><?php echo number_format($totalamount,2);?></td><td></td></tr>

                       <tr>
                            <td colspan="9" style="text-align:center">
                                <?php
                                if(isset($_POST[cancel])){
                                    $deletes=mysql_query("Delete From ".$table." where oi_no='$_SESSION[initiate_travel_exp_claim_requisition]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                                    $deletes=mysql_query("Delete From ".$table_deatils." where oi_no='$_SESSION[initiate_travel_exp_claim_requisition]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                                    unset($_SESSION["initiate_travel_exp_claim_requisition"]); ?>
                                    <meta http-equiv="refresh" content="0;<?=$page;?>">
                                <?php } ?>
                                <button type="submit" id="delete" name="delete" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the requisition?");' class="btn btn-danger">Delete the Requisition </button>
                                
                                    <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the requisition?");' name="confirmsave" class="btn btn-success">Confirm and Finish Requisition </button>
                                


                            </td></tr></table></form>
    <?php } ?>
<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#application_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#trvDate_from').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#trvDate_to').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#departure_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#return_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#travel_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#travel_date_upd').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
