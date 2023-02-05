 <?php

require_once 'support_file.php';
$title="SO Information";
$now=time();
$unique='PBI_ID';
$unique_field='PBI_ID_UNIQUE';
$table="personnel_basic_info";
$table_essential_info='essential_info';
$page="ims_so_infos.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";



if(prevent_multi_submit()){
   $$unique = $_POST[$unique];
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
}}



// data query..................................
$PBI_ID_UNIQUE_GET=find_a_field("personnel_basic_info","PBI_ID_UNIQUE","PBI_ID=".$$unique."");
$datas=find_all_field("so_personnel_basic_info","","PBI_ID_UNIQUE='".$PBI_ID_UNIQUE_GET."'");

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



                 <input type="text" id="PBI_ID" style="width:100%"     name="PBI_ID" value="<?=$$unique;?>" class="form-control col-md-7 col-xs-12" >





                 <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date of Joining<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_DOJ" style="width:100%"     name="PBI_DOJ" value="<?= date('m/d/Y' , strtotime($datas->dateofjoin));?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Official Contact<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_PHONE" style="width:100%"     name="PBI_PHONE" value="<?=$datas->Office_Contact;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Personal Number<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_MOBILE" style="width:100%"     name="PBI_MOBILE" value="<?=$datas->contact_number_personal;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>



                     
                      <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Permanent Address<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea type="text" id="PBI_PERMANENT_ADD" style="width:100%"     name="PBI_PERMANENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$datas->Present_Address;?></textarea>
                     </div></div>





                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Persent Address<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <textarea type="text" id="PBI_PRESENT_ADD" style="width:100%"     name="PBI_PRESENT_ADD" class="form-control col-md-7 col-xs-12" ><?=$datas->Parmanent_Address;?></textarea>

                     </div></div>










                 <div class="form-group">

                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Person (if any Emergency)<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">

                         <input type="text" id="EMR_FULL_NAME" style="width:100%"     name="EMR_FULL_NAME" class="form-control col-md-7 col-xs-12" value="<?=$datas->imr_contactperson;?>" >

                     </div></div>





                     <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Emergency Contact Number<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                     <input type="text" id="EMR_MOBILE" style="width:100%"     name="EMR_MOBILE" class="form-control col-md-7 col-xs-12" value="<?=$datas->imr_contactnumner;?>" >
                     </div></div>







                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Accounts Number<span class="required">*</span></label>

                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BANK_ACC_NO" style="width:100%"     name="ESS_BANK_ACC_NO" value="<?=$datas->bank_account_number;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>



                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Name<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BANK_ACC_NO" style="width:100%"     name="bank_account_number" value="<?=$datas->bank_name;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Branch<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BANK_ACC_NO" style="width:100%"     name="bank_account_number" value="<?=$datas->branch;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>





                 <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Routing / SWIFT<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BANK_SWIFT" style="width:100%"     name="ESS_BANK_SWIFT" value="<?=$datas->routing_no;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>







                 <div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Monthly IMS Target<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_MONTHLY_IMS" style="width:100%"     name="PBI_MONTHLY_IMS" value="120" class="form-control col-md-7 col-xs-12" >
                     </div></div>







                 <div class="form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Salary<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="ESS_BASIC_SALARY" style="width:100%"     name="ESS_BASIC_SALARY" value="<?=$datas->salary;?>" class="form-control col-md-7 col-xs-12" >
                     </div></div>







                 <div class="form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">TA/DA<span class="required">*</span></label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" id="PBI_TA_DA" style="width:100%"     name="PBI_TA_DA" value="<?=$datas->TA_DA;?>" class="form-control col-md-7 col-xs-12" >
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
                 
                     <a target="_new" class="btn btn-sm btn-default"  href="ims_so_info_inactive.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Inactive SO List</span>                     </a>
                 
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
								p.PBI_JOB_STATUS in ("In Service") and 
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