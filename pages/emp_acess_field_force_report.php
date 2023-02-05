<?php
require_once 'support_file.php';
$title='Accounts Report';
$page="emp_acess_field_force_report.php";
?>



<?php require_once 'header_content.php'; ?>
 <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.reporttypes.options[form.reporttypes.options.selectedIndex].value;
	self.location='<?=$page;?>?reporttypes=' + val ;
}
function reload1(form)
{
	var val=form.reporttypes.options[form.reporttypes.options.selectedIndex].value;
	var val2=form.ledgercode.options[form.ledgercode.options.selectedIndex].value;
	self.location='<?=$page;?>?reporttypes=' + val +'&ledgercode=' + val2 ;
}

</script>
<style>
    input[type=text]{
        font-size: 11px;
    }

</style>
<?php require_once 'body_content.php'; ?>

<?php
if($_GET[reporttypes]=='500001'){
    $link='ims_report_view.php';
} else {
    $link='sales_reportview.php';
}
?>


<form class="form-horizontal form-label-left" method="POST" action="<?=$link;?>" style="font-size: 11px" target="_blank">
              <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">

                    

                        <select id="first-name" required="required" required="required" size="25" style="font-size: 12px; border: none;white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;" name="reporttypes" onchange="javascript:reload(this.form)" class="form-control col-md-7 col-xs-12">


                            <optgroup label="Primary Shipments Report">
                                <option  style="height:20px" value="510" <?php if ($_GET[reporttypes]=='510') echo 'selected';?>>YTD Report (Dealer Wise)</option>
                                <option  style="height:20px" value="1510" <?php if ($_GET[reporttypes]=='1510') echo 'selected';?>>YTD Report (Region Wise)</option>
                                <option  style="height:20px" value="dealertypewise" <?php if ($_GET[reporttypes]=='dealertypewise') echo 'selected';?>>YTD Report (Customer Type Wise)</option>
                                <option  style="height:20px" value="3811" <?php if ($_GET[reporttypes]=='3811') echo 'selected';?>>MDT Report (Dealer Wise)</option>
                                <option  style="height:20px" value="3810" <?php if ($_GET[reporttypes]=='3810') echo 'selected';?>>MTD Report (Territory Wise)</option>
                                <option  style="height:20px" value="380" <?php if ($_GET[reporttypes]=='380') echo 'selected';?>>MTD Report (Region Wise)</option>
                                <option  style="height:20px" value="50000" <?php if ($_GET[reporttypes]=='50000') echo 'selected';?>>MTD Report (Brand Wise)</option>
                                <option  style="height:20px" value="7" <?php if ($_GET[reporttypes]=='7') echo 'selected';?>>Report (SKU & Category Wise)</option>
                                <option  style="height:20px" value="50000" <?php if ($_GET[reporttypes]=='50000') echo 'selected';?>>MTD Report (Customer Type Wise)</option>

                                <option></option>
                            </optgroup>

                            <optgroup label="Secondary Shipment Report">
                            <option  style="height:20px" value="5000" <?php if ($_GET[reporttypes]=='5000') echo 'selected';?>>Sales Info Master</option>
                            <option  style="height:20px" value="5007" <?php if ($_GET[reporttypes]=='5007') echo 'selected';?>>Monthly IMS Target Report</option>
                            <option  style="height:20px" value="500002" <?php if ($_GET[reporttypes]=='500002') echo 'selected';?>>Daily IMS Report</option>
                            <option  style="height:20px" value="5003" <?php if ($_GET[reporttypes]=='5003') echo 'selected';?>>Super DB Opening</option>
                            <option  style="height:20px" value="5004" <?php if ($_GET[reporttypes]=='5004') echo 'selected';?>>Sub DB Opening</option>
                            <option  style="height:20px" value="5002" <?php if ($_GET[reporttypes]=='5002') echo 'selected';?>>Stock Lifting</option>
                            <option  style="height:20px" value="5006" <?php if ($_GET[reporttypes]=='5006') echo 'selected';?>>SO Attendance</option>
                            <option  style="height:20px" value="500001" <?php if ($_GET[reporttypes]=='500001') echo 'selected';?>>IMS Helper</option>
                            <option></option>
                            </optgroup>





                                <optgroup label="Inventory Report">
                                <option  style="height:20px" value="60010" <?php if ($_GET[reporttypes]=='60010') echo 'selected';?>>Present Stock (Material)</option>
                                <option  style="height:20px" value="60011" <?php if ($_GET[reporttypes]=='60011') echo 'selected';?>>Present Stock (FG)</option>
                            </optgroup>


                        </select>

                  </div></div></div>


















                      <div class="col-md-7 col-sm-12 col-xs-12">
                          <div class="x_panel">
                              <div class="x_content">



                                  <?php if ($_GET['reporttypes']=='50000'):
                                  /////////////////////////////////////cash Received and Payment----------------------------------------------------------
                                  ?>



                                  <div class="form-group">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                          <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="PBI_JOB_STATUS"  id="PBI_JOB_STATUS">
                                              <option></option>
                                              <option value="Active" selected>Active</option>
                                              <option value="Inactive">Inactive</option>
                                          </select>
                                      </div>
                                  </div>


                                  <?php elseif ($_GET['reporttypes']=='1000'):
/////////////////////////////////////cash Received and Payment----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="f_date" style="font-size: 12px"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="t_date" style="font-size: 12px"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['reporttypes']=='1001'):
/////////////////////////////////////cash Received and Payment----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">LC Number</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="lc_id" >
                                                  <option></option>
                                                  <option value="%">All</option>

                                                  <?php
                                                  $result=mysql_query("Select * from lc_lc_master where 1 order by id");
                                                  while($row=mysql_fetch_array($result)){  ?>

                                                      <option  value="<?php echo $row[id]; ?>"><?php echo $row[id]; ?>-<?php echo $row[lc_no]; ?></option>
                                                  <?php } ?>
                                              </select>
                                          </div>
                                      </div>



   <?php elseif ($_GET['reporttypes']=='5000'):
/////////////////////////////////////cash Received and Payment----------------------------------------------------------
					   ?>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="PBI_JOB_STATUS"  id="PBI_JOB_STATUS">
                                                  <option></option>
                                                  <option value="Active" selected>Active</option>
                                                  <option value="Inactive">Inactive</option>
                                              </select>
                                          </div>
                                      </div>




                                  <?php elseif ($_GET['reporttypes']=='500001'):
/////////////////////////////////////cash Received and Payment----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">TSM Name</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="tsm" id="tsm">
                                                  <option></option>

                                                  <? $sql_tsm="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and p.PBI_DESIGNATION in ('56','57','102')				 
							  order by p.PBI_NAME";
                                                  advance_foreign_relation($sql_tsm,$tsm);?>
                                                  <option value="0">All</option>
                                              </select>
                                          </div>
                                      </div>






                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="f_date" style="font-size: 12px"  required="required" name="f_date"   class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>




                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="t_date" style="font-size: 12px"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


                                          </div>
                                      </div>


                                  <?php elseif ($_GET['reporttypes']=='500002'):
/////////////////////////////////////cash Received and Payment----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">TSM Name</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width: 100%;" tabindex="-1" name="tsm" id="tsm">
                                                  <option></option>
                                                  <? $sql_tsm="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and p.PBI_DESIGNATION in ('56','57','102')				 
							  order by p.PBI_NAME";
                                                  advance_foreign_relation($sql_tsm,$tsm);?>
                                                  <option value="0">All</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date"  style="font-size: 11px; height: 30px; width: 100%"  required="required" name="f_date"  autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date"  style="font-size: 11px; height: 30px; width: 100%"  required="required" name="t_date" autocomplete="off"></td>
                                          </div>
                                      </div>


                                  <?php elseif ($_GET['reporttypes']=='5007'):
/////////////////////////////////////cash Received and Payment----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">TSM Name</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="tsm" id="tsm">
                                                  <option></option>

                                                  <? $sql_tsm="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID	and p.PBI_DESIGNATION in ('56','57','102')				 
							  order by p.PBI_NAME";
                                                  advance_foreign_relation($sql_tsm,$tsm);?>
                                                  <option value="0" selected>All</option>
                                              </select>
                                          </div>
                                      </div>






                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Month <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" name="month" >
                                                  <option></option>
                                                  <option value="%">All</option>
                                                  <?php foreign_relation('monthname', 'month', 'CONCAT(month," : ", monthfullName)', $month, '1'); ?>
                                              </select>                                          </div>
                                      </div>




                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Year<span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <?php
                                              //get the current year
                                              $Startyear=date('Y');
                                              $endYear=$Startyear-10;

                                              // set start and end year range i.e the start year
                                              $yearArray = range($Startyear,$endYear);
                                              ?>
                                              <!-- here you displaying the dropdown list -->
                                              <select class="select2_single form-control" name="year" id="year" style="width:100%; font-size: 11px" required>
                                                  <option value="">Select Year</option>
                                                  <?php
                                                  foreach ($yearArray as $year) {
                                                      // this allows you to select a particular year
                                                      $selected = ($year == $Startyear) ? 'selected' : '';
                                                      echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
                                                  }
                                                  ?>
                                              </select>
                                          </div>
                                      </div>
                      
                      
                      
                      
                                      
                      
   <?php elseif ($_GET['reporttypes']=='5001'): ?>
                      


                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="ledger_id" >
                                        <option></option>
                                        <option value="%">All</option>

                                        <?php

                                        $led="Select * from accounts_ledger where section_id='$_SESSION[sectionid]' and  company_id='$_SESSION[companyid]' order by ledger_id";
                                        $led_res=mysqli_query($conn, $led);
                                        while($row=mysqli_fetch_array($led_res)){  ?>

                                            <option  value="<?php echo $row[ledger_id]; ?>"><?php echo $row[ledger_id]; ?>-<?php echo $row[ledger_name]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Ledger</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="subledgercode" >
                            <option></option>
                            
                            <?php 
							$result=mysql_query("Select * from accounts_subledger where companyid='$_SESSION[companyid]' order by subledger");
							while($row=mysql_fetch_array($result)){ ?>
								
								
                        <option  value="<?php echo $row[subledgercode]; ?>"><?php echo $row[subledger]; ?>-<?php echo $row[subledger]; ?></option>
                    <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="datefrom" value="<?php echo date('Y-m-01') ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                 
                 
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="dateto" value="<?php echo date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                      
     
     
     
     
     
     
     
     
     <?php elseif ($_GET['reporttypes']=='5002'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>




       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name :</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="dealer_code" id="dealer_code" >
                   <option></option>
                   <?php
                   $result=mysqli_query($conn, ("Select * from dealer_info where canceled in ('Yes') order by dealer_code"));
                   while($row=mysqli_fetch_array($result)){ ?>
                           <option  value="<?=$row[dealer_code];?>"><?=$row[dealer_code];?>-<?=$row[dealer_name_e];?></option>
                   <?php } ?>
               </select>
           </div>
       </div>




       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="f_date"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>




       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


           </div>
       </div>









   <?php elseif ($_GET['reporttypes']=='5003'): ?>







       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="f_date"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>




       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


           </div>
       </div>
    
    
    
    
   <?php elseif ($_GET['reporttypes']=='5004'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="f_date"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>




       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


           </div>
       </div>


                                  <?php elseif ($_GET['reporttypes']=='60010'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                      ?>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="warehouse_id" >
                                                  <?php
                                                  $ware="Select * from warehouse where 1 order by warehouse_id";
                                                  $ware_res=mysqli_query($conn, $ware);
                                                  while($row=mysqli_fetch_array($ware_res)){  ?>

                                                      <option  value="<?php echo $row[warehouse_id]; ?>"><?php echo $row[warehouse_id]; ?>-<?php echo $row[warehouse_name]; ?></option>
                                                  <?php } ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="f_date"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>


                                  <?php elseif ($_GET['reporttypes']=='60011'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                      ?>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="warehouse_id" >
                                                  <?php
                                                  $ware="Select * from warehouse where 1 order by warehouse_id";
                                                  $ware_res=mysqli_query($conn, $ware);
                                                  while($row=mysqli_fetch_array($ware_res)){  ?>

                                                      <option  value="<?php echo $row[warehouse_id]; ?>"><?php echo $row[warehouse_id]; ?>-<?php echo $row[warehouse_name]; ?></option>
                                                  <?php } ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="f_date"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>




                                  <?php elseif ($_GET['reporttypes']=='5005'):   /////////////////////////////////////cash Journal---------------------------------------------------------- ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="f_date"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


           </div>
       </div> 
       
       
  <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Delivary Status</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%" tabindex="-1"  required="required" name="warehouse_id" >
                   <option value="all">All</option>
                   <option value="Deliverd">Deliverd</option>
                   <option value="UnDeliverd">UnDeliverd</option>
                   

               </select>
           </div>
       </div>
       
       
       
<?php elseif ($_GET['reporttypes']=='5006'):   /////////////////////////////////////cash Journal---------------------------------------------------------- ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="f_date"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


           </div>
       </div>    
       
       
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Delivary Status</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%" tabindex="-1"  required="required" name="delivarystatus" >
                   <option value="all">All</option>
                   <option value="Deliverd">Deliverd</option>
                   <option value="UnDeliverd">UnDeliverd</option>
                   

               </select>
           </div>
       </div>


   <?php elseif ($_GET['reporttypes']=='5010'): ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="f_date" style="font-size: 12px"  required="required" name="f_date" value="<?=date('m')?>/<?=date('d')?>/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>


   <?php elseif ($_GET['reporttypes']=='5007'): ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Current Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="f_date" style="font-size: 12px; width: 235px;"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"><br><br>
               <input type="text" id="t_date" style="font-size: 12px; width: 235px; margin-top: 10px"  required="required" name="t_date" value="<?=date('m')?>/<?=date('d')?>/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off">
           </div>
       </div>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Previous Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="pf_date" style="font-size: 12px; width: 235px"  required="required" name="pf_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
               <input type="text" id="pt_date" style="font-size: 12px; width: 235px; margin-top: 10px"  required="required" name="pt_date" value="<?=date('m')?>/<?=date('d')?>/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>

           </div>
       </div>



   <?php elseif ($_GET['reporttypes']=='5008'): ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Current Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="t_date" style="font-size: 12px; width: 235px; margin-left: 20px"  required="required" name="t_date" value="<?=date('m')?>/<?=date('d')?>/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>

           </div>
       </div>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Previous Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="pt_date" style="font-size: 12px; width: 235px; margin-left: 20px"  required="required" name="pt_date" value="<?=date('m')?>/<?=date('d')?>/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>

           </div>
       </div>
                                  <?php  else:  ?>
                                      <h5 align="center">Please select a report from left</h5>
                                  <?php endif; ?>
                                  <?php if ($_GET['reporttypes']>0): ?>
                                  <div class="ln_solid"></div>
                                  <div class="form-group">
                                      <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                          <a href="accounts_select_report.php"  class="btn btn-danger" style="font-size: 12px">Cancel</a>
                                          <button type="submit" class="btn btn-primary" name="getstarted" style="font-size: 12px">View Report</button>
                                      </div>
                                  </div>
                                  <?php  else:  ?>
                                  <?php endif; ?>


                    </form>
                  </div>
                </div>
              </div>



<?php require_once 'footer_content.php' ?>

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select a state",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
          maximumSelectionLength: 4,
          placeholder: "With Max Selection limit 4",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->

        <script>
            $(document).ready(function() {
                $('#f_date').daterangepicker({

                    singleDatePicker: true,
                    calender_style: "picker_4",

                }, function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                });
            });

            $(document).ready(function() {
                $('#pf_date').daterangepicker({

                    singleDatePicker: true,
                    calender_style: "picker_4",

                }, function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                $('#t_date').daterangepicker({

                    singleDatePicker: true,
                    calender_style: "picker_4",

                }, function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                });
            });

            $(document).ready(function() {
                $('#pt_date').daterangepicker({

                    singleDatePicker: true,
                    calender_style: "picker_4",

                }, function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                });
            });
        </script>
    <!-- /Starrr -->
  </body>
</html>
