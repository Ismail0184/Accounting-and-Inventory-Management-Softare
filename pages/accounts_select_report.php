<?php
require_once 'support_file.php';
$title='Accounts Report';
?>



<?php require_once 'header_content.php'; ?>
 <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.reporttypes.options[form.reporttypes.options.selectedIndex].value;
	self.location='accounts_select_report.php?reporttypes=' + val ;
}
function reload1(form)
{
	var val=form.reporttypes.options[form.reporttypes.options.selectedIndex].value;
	var val2=form.ledgercode.options[form.ledgercode.options.selectedIndex].value;
	self.location='accounts_select_report.php?reporttypes=' + val +'&ledgercode=' + val2 ;
}

</script>
<style>
    input[type=text]{
        font-size: 11px;
    }

</style>
<?php require_once 'body_content.php'; ?>




<form class="form-horizontal form-label-left" method="POST" action="accounts_reportview.php" style="font-size: 11px" target="_blank">
              <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">

                    

                        <select id="first-name"  required="required" size="27" style="font-size: 11px; border: none;white-space: nowrap;
  overflow: scroll;
  text-overflow: ellipsis;" name="reporttypes" onchange="javascript:reload(this.form)" class="form-control col-md-7 col-xs-12">


                            <optgroup label="Ledger Report">
                                <option  style="height:20px" value="50000" <?php if ($_GET[reporttypes]=='50000') echo 'selected';?>>Chart of Accounts</option>
                            </optgroup>

                            <optgroup label="General Report">
                            <option  style="height:20px" value="5000" <?php if ($_GET[reporttypes]=='5000') echo 'selected';?>>Transaction Statement (Ledger)</option>
                            <option  style="height:20px" value="500001" <?php if ($_GET[reporttypes]=='500001') echo 'selected';?>>Transaction Statement (LC Wise)</option>
                            <option  style="height:20px" value="5002" <?php if ($_GET[reporttypes]=='5002') echo 'selected';?>>Receipt & Payment Statement</option>
                            <option  style="height:20px" value="5006" <?php if ($_GET[reporttypes]=='5006') echo 'selected';?>>Accounts Receivable Status </option>
                             <option  style="height:20px" value="5016" <?php if ($_GET[reporttypes]=='5016') echo 'selected';?>>Party / Customer Statement (Profit Center Wise)</option>
                                <option  style="height:20px" value="718_922" <?php if ($_GET[reporttypes]=='718_922') echo 'selected';?>>Imbalance Voucher</option>
                            </optgroup>
                            
                            <optgroup label="Vendor Reports">
                                
                                <option  style="height:20px" value="2251441518_02" <?php if ($_GET[reporttypes]=='2251441518_02') echo 'selected';?>>Outstanding Balance</option>
                                <option  style="height:20px" value="2251441518_03" <?php if ($_GET[reporttypes]=='2251441518_03') echo 'selected';?>>Balance Confirmation Report</option>
                                </optgroup>
                            
                                
                                <optgroup label="Trial Balance Reports">
                                
                            <option  style="height:20px" value="5010" <?php if ($_GET[reporttypes]=='5010') echo 'selected';?>>Trial Balance </option>
                            <option  style="height:20px" value="5012" <?php if ($_GET[reporttypes]=='5012') echo 'selected';?>>Trial Balance (Group)</option>
                                <option  style="height:20px" value="5014" <?php if ($_GET[reporttypes]=='5014') echo 'selected';?>>Periodical Trial Balance (Details)</option>
                                <option  style="height:20px" value="5013" <?php if ($_GET[reporttypes]=='5013') echo 'selected';?>>Periodical Trial Balance </option>
        
                                <option  style="height:20px" value="5015" <?php if ($_GET[reporttypes]=='5015') echo 'selected';?>>Periodical Trial Balance (Group)</option></optgroup>
                               
                            </optgroup>

                            <optgroup label="Financial Report">
                                <!--option  style="height:20px" value="5007" <?php if ($_GET[reporttypes]=='5007') echo 'selected';?>>Profit & Loss Statement </option-->
                                <option  style="height:20px" value="50070" <?php if ($_GET[reporttypes]=='50070') echo 'selected';?>>Profit & Loss Statement</option>
                                <!--option  style="height:20px" value="5008" <?php if ($_GET[reporttypes]=='5008') echo 'selected';?>>Financial Statement</option-->
                                <option  style="height:20px" value="5009" <?php if ($_GET[reporttypes]=='5009') echo 'selected';?>>Financial Statement</option>

                            </optgroup>


                            <optgroup label="LC Report">
                                <option  style="height:20px" value="1000" <?php if ($_GET[reporttypes]=='1000') echo 'selected';?>>LC Report</option>
                                <option  style="height:20px" value="1001" <?php if ($_GET[reporttypes]=='1001') echo 'selected';?>>LC Wise Cost Sheet</option>
                            </optgroup>

                                <optgroup label="Inventory Report">
                                <option  style="height:20px" value="60000" <?php if ($_GET[reporttypes]=='60000') echo 'selected';?>>Inventory Register</option>
                                <option  style="height:20px" value="60010" <?php if ($_GET[reporttypes]=='60010') echo 'selected';?>>Present Stock (Material)</option>
                                <option  style="height:20px" value="60011" <?php if ($_GET[reporttypes]=='60011') echo 'selected';?>>Present Stock (FG)</option>
                                    <option  style="height:20px" value="60012" <?php if ($_GET[reporttypes]=='60012') echo 'selected';?>>Present Stock (Group wise)</option>
                            </optgroup>

                            <optgroup label="Purchase Report">
                                <option  style="height:20px" value="7001" <?php if ($_GET[reporttypes]=='7001') echo 'selected';?>>Purchase Report</option>
                            </optgroup>

                            <optgroup label="Sales Report">
                            <option  style="height:20px" value="50040" <?php if ($_GET[reporttypes]=='50040') echo 'selected';?>>Sales Invoice List</option>
                                <option  style="height:20px" value="5004" <?php if ($_GET[reporttypes]=='5004') echo 'selected';?>>Sales Report</option>
                                <option  style="height:20px" value="5005" <?php if ($_GET[reporttypes]=='5005') echo 'selected';?>>Item wise Sales (COGS Price)</option>
                            </optgroup>
                            
                            
                            <optgroup label="Material Standard Costing">
                            <option  style="height:20px" value="12_19_3_1" <?php if ($_GET[reporttypes]=='12_19_3_1') echo 'selected';?>>Standard Costing</option>                               
                            </optgroup>



                        </select>

                  </div></div></div>


















                      <div class="col-md-7 col-sm-12 col-xs-12">
                          <div class="x_panel">
                              <div class="x_content">



                                  <?php if ($_GET['reporttypes']=='50000'):?>
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



<?php elseif ($_GET['reporttypes']=='5011'): ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" name="ledger_id" >
                                                  <option></option>
                                                  <option value="%">All</option>
                                                  <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $dealer_code, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; height: auto"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;height: auto"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>

   <?php elseif ($_GET['reporttypes']=='5000'): ?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" name="ledger_id" >
                   <option></option>
                   <option value="%">All</option>
                       <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, 'status=1'); ?>
               </select>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                   <option></option>
                   <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
               </select>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Transaction Type</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="tr_from" id="tr_from" >
                       <option value="">Select Type</option>
                       <?php foreign_relation('journal', 'distinct tr_from', 'tr_from', $_POST[tr_from], '1','order by tr_from'); ?>
                   </select>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>

       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
           </div>
       </div>
       
       
       <?php elseif ($_GET['reporttypes']=='718_922'): ?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px; height: 30px; width: 100%"  required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>

       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px;height: 30px; width: 100%"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
           </div>
       </div>
       
       
       <?php elseif ($_GET['reporttypes']=='2251441518_03'): ?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Vendor</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="ledger_id" >
                   <option></option>
                       <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, 'ledger_group_id in ("2002")'); ?>
               </select>
           </div>
       </div>     
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
           </div>
       </div>
       
       <?php elseif ($_GET['reporttypes']=='2251441518_02'): ?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Vendor</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="ledger_id" >
                   <option></option>
                       <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, 'ledger_group_id in ("2002")'); ?>
               </select>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As on<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
           </div>
       </div>




                                  <?php elseif ($_GET['reporttypes']=='500001'):
/////////////////////////////////////cash Received and Payment----------------------------------------------------------
                                      ?>


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
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">LC NO</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 12px" tabindex="-1"  name="lc_id" id="lc_id" >
                                                  <option></option>

                                                  <?php
                                                  $sql="Select * from lc_lc_master where status not in ('MANUAL') order by id";
                                                  $result1=mysqli_query($conn, $sql);
                                                  while($row=mysqli_fetch_array($result1)){ ?>


                                                      <option  value="<?php echo $row[id]; ?>"><?php echo $row[id]; ?>-<?php echo $row[lc_no]; ?></option>
                                                  <?php } ?>
                                              </select>
                                          </div>
                                      </div>
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
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center :</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="cc_code" id="cc_code" >
                   <option></option>

                   <?php
                   $result=mysql_query("Select * from cost_center where 1 order by id");
                   while($row=mysql_fetch_array($result)){ ?>

                           <option  value="<?php echo $row[id]; ?>"><?=$row[id];?>-<?=$row[center_name];?></option>
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







       <div class="form-group" style="display: none">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="last-name"  required="required" name="datefrom" value="<?php echo date('Y-m-01') ?>" class="form-control col-md-7 col-xs-12">
           </div>
       </div>




       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="last-name"  required="required" name="dateto" value="<?php echo date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12">
           </div>
       </div>
    
    
    
    
    
    <?php elseif ($_GET['reporttypes']=='50040'):  ?>                                     


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      
                                       <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="dealer_code" >
                                                  <option></option>
                                                  <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $dealer_code, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" name="warehouse_id" >
                                                  <option></option>
                                                  <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>
    
    
    


                                  <?php elseif ($_GET['reporttypes']=='60000'):  ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">

                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id">
                                                  <option></option>
                                                  <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id 							 
							  order by i.item_name";
                                                  advance_foreign_relation($sql_item_id,$item_id);?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="warehouse_id" >
                                                  <option></option>
                                                  <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"  name="status" >
                                                  <option value=""></option>
                                                  <option value='Received'>Received</option>
                                                  <option value='Issue'>Issue</option>
                                              </select>
                                          </div>
                                      </div>


                                  <?php elseif ($_GET['reporttypes']=='5005' || $_GET['reporttypes']=='5004'):  ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">

                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id" required>
                                                  <option value="0">All</option>
                                                  <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id  and i.product_nature in ('Salable','Both')							 
							  order by i.item_id";
                                                  advance_foreign_relation($sql_item_id,$item_id);?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Invoice No:</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">

                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="do_no" id="do_no" >
                                                  <option value="">If Necessary Select an Invoice.</option>
                                                  <? $sql_do_no="select m.do_no,concat(m.do_no, ' : ',m.do_date) from sale_do_master m where 1 order by do_no desc";
                                                  advance_foreign_relation($sql_do_no,$do_no);?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="height: 30px; width: 100%; font-size: 11px" required="required" name="f_date"   placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="height: 30px; width: 100%; font-size: 11px"  required="required" name="t_date" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" autocomplete="off"></td>
                                          </div>
                                      </div>


                                  <?php elseif ($_GET['reporttypes'] == '60010' || $_GET['reporttypes'] == '60011'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="warehouse_id" id="warehouse_id">
                                                  <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>



                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>

<?php elseif ($_GET['reporttypes'] == '12_19_3_1'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                      ?>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select multiple class="select2_single form-control"  style="width: 100%;" name="group_id[]" id="group_id">
                                                  <option></option>
                                                  <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)', $group_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['reporttypes'] == '60012'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="warehouse_id" id="warehouse_id">
                                                  <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="group_id" id="group_id">
                                                  <option></option>
                                                  <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)', $group_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
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
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On:  <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
           </div>
       </div>

     <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer Type: </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="dealer_type" id="dealer_type" >
                                                  <option></option>
                                                  <?php foreign_relation('distributor_type', 'typeshorname', 'CONCAT(typedetails)', $dealer_typee, '1'); ?>
                                              </select>
                                          </div>
                                      </div>   
     

   <?php elseif ($_GET['reporttypes']=='5010'): ?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px"  required="required" max="<?=date('Y-m-d');?>" name="t_date" value="<?=$_POST[t_date];?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                                  <option></option>
                                                  <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                                              </select>
                                          </div>
                                      </div>

   <?php elseif ($_GET['reporttypes']=='5012'): ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px"  required="required" name="t_date" value="<?=$_POST[t_date];?>" MAX="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                                  <option></option>
                                                  <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['reporttypes']=='5013'): ?>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger Group : </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="group_id" id="group_id">
                                                  <option></option>
                                                  <? foreign_relation('ledger_group','group_id','CONCAT(group_id, " : ", group_name)',$_REQUEST['group_id'],"group_for=".$_SESSION['usergroup']);?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                                  <option></option>
                                                  <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['reporttypes']=='5014'): ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                     <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                                  <option></option>
                                                  <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['reporttypes']=='5015'): ?>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                                  <option></option>
                                                  <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['reporttypes']=='5016'): ?>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>

                                      <div class="form-group" style="display: none">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger Group : </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="group_id" id="group_id">
                                                  <option value="1006"></option>
                                                  <? foreign_relation('ledger_group','group_id','CONCAT(group_id, " : ", group_name)',$_REQUEST['group_id'],"group_for=".$_SESSION['usergroup']);?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Profit Center</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="pc_code" id="pc_code" >
                                                  <option></option>
                                                  <?php foreign_relation('profit_center', 'id', 'CONCAT(id," : ", profit_center_name)', $pc_code, 'status in ("1")'); ?>
                                              </select>
                                          </div>
                                      </div>



   <?php elseif ($_GET['reporttypes']=='5007' || $_GET['reporttypes']=='50070'): ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="margin-top: 20px">Current Period<span class="required">*</span></label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date"  style="font-size: 11px; width: 235px;height: 30px"   name="f_date" max="<?=date('Y-m-d')?>" autocomplete="off">
               <input type="date"  style="font-size: 11px; width: 235px; margin-top: 10px;height: 30px" max="<?=date('Y-m-d')?>"  name="t_date" value="<?=date('Y-m-d')?>"  autocomplete="off">
           </div>
       </div>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="margin-top: 20px">Previous Period<span class="required">*</span></label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px; width: 235px;height: 30px"  name="pf_date" max="<?=date('Y-m-d')?>"  autocomplete="off"></td>
               <input type="date" style="font-size: 11px; width: 235px; margin-top: 10px; height: 30px" max="<?=date('Y-m-d')?>" name="pt_date"   autocomplete="off"></td>
           </div>
       </div>



   <?php elseif ($_GET['reporttypes']=='5008' || $_GET['reporttypes']=='5009'): ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Current Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date"  style="font-size: 11px; width: 235px; margin-left: 20px; height: 30px"   max="<?=date('Y-m-d') ?>" name="t_date" value="<?=date('Y-m-d') ?>"  autocomplete="off"></td>

           </div>
       </div>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Previous Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date"  style="font-size: 11px; width: 235px; margin-left: 20px; height: 30px"  max="<?=date('Y-m-d') ?>" name="pt_date" placeholder="From Date" autocomplete="off"></td>

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



<?=$html->footer_content();?>

    