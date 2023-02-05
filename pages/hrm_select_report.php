<?php
require_once 'support_file.php';
$title='Report';
$page='hrm_select_report.php';
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
<?php require_once 'body_content.php'; ?>

<form class="form-horizontal form-label-left" method="POST" action="hrm_reportview.php" style="font-size: 12px" target="_blank">
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">

                                            <select id="first-name" required="required" size="25" style="font-size: 12px; border: none;white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;"  name="reporttypes" onchange="javascript:reload(this.form)" class="form-control col-md-7 col-xs-12">
                                                <optgroup label="Employee Info">
                                                    <option  style="height:20px" value="3002" <?php if ($_GET[reporttypes]=='3002') echo 'selected';?>>Employee List</option>
                                                </optgroup>
                                                    <optgroup label="Attendance Report">

                                                        <option  style="height:20px" value="2001" <?php if ($_GET[reporttypes]=='2001') echo 'selected';?>>Leave</option>
                                                        <option  style="height:20px" value="2002" <?php if ($_GET[reporttypes]=='2002') echo 'selected';?>>Early Leave</option>
                                                        <option  style="height:20px" value="2003" <?php if ($_GET[reporttypes]=='2003') echo 'selected';?>>Outdoor Duty Attendance</option>
                                                        <option  style="height:20px" value="2004" <?php if ($_GET[reporttypes]=='2004') echo 'selected';?>>Late Attendance</option>
                                                        <option  style="height:20px" value="2000" <?php if ($_GET[reporttypes]=='2000') echo 'selected';?>>Monthly Attendance</option>
                                                    </optgroup>

                                                <optgroup label="Payroll Report">
                                                    <option  style="height:20px" value="4001" <?php if ($_GET[reporttypes]=='4001') echo 'selected';?>>Salary Top Sheet</option>
                                                    <option  style="height:20px" value="4002" <?php if ($_GET[reporttypes]=='4002') echo 'selected';?>>Salary Sheet Summery</option>
                                                </optgroup>

                                                <optgroup label="Requisition Report">
                                                    <option  style="height:20px" value="6001" <?php if ($_GET[reporttypes]=='6001') echo 'selected';?>>Stationary</option>
                                                    <option  style="height:20px" value="6002" <?php if ($_GET[reporttypes]=='6002') echo 'selected';?>>Food & Beverage</option>
                                                    <option  style="height:20px" value="6003" <?php if ($_GET[reporttypes]=='6003') echo 'selected';?>>Travel Exp. Claim</option>
                                                    <option  style="height:20px" value="6004" <?php if ($_GET[reporttypes]=='6004') echo 'selected';?>>Vehicle Application</option>
                                                    <option  style="height:20px" value="6005" <?php if ($_GET[reporttypes]=='6005') echo 'selected';?>>Manpower Requisition</option>
                                                    <option  style="height:20px" value="6008" <?php if ($_GET[reporttypes]=='6008') echo 'selected';?>>Handover/Takeover</option>
                                                    <option  style="height:20px" value="6006" <?php if ($_GET[reporttypes]=='6006') echo 'selected';?>>Sample & Gift</option>
                                                    <option  style="height:20px" value="6007" <?php if ($_GET[reporttypes]=='6007') echo 'selected';?>>FG Purchased</option>

                                                </optgroup>

                                                <optgroup label="Stationary Purchase & Stock">
                                                    <option  style="height:20px" value="5001" <?php if ($_GET[reporttypes]=='5001') echo 'selected';?>>Stationary Transaction Statement</option>
                                                    <option  style="height:20px" value="5002" <?php if ($_GET[reporttypes]=='5002') echo 'selected';?>>Stationary Purchase Report</option>
                                                    <option  style="height:20px" value="5003" <?php if ($_GET[reporttypes]=='5003') echo 'selected';?>>Stationary Issue Report</option>
                                                    <option  style="height:20px" value="5004" <?php if ($_GET[reporttypes]=='5004') echo 'selected';?>>Stationary Present Stock</option>


                                                </optgroup>
                                            </select></div></div></div>





<div class="col-md-7 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_content">

                                        <?php if ($_GET['reporttypes']=='3002'): ?>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Designation</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="PBI_DESIGNATION" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from designation where 1 order by DESG_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DESG_ID]; ?>"><?php echo $row[DESG_DESC]; ?> - <?php echo $row[DESG_SHORT_NAME]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>


                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Service Status</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="PBI_JOB_STATUS"  id="PBI_JOB_STATUS">
                                                    <option></option>
                                                    <option value="In Service">In Service</option>
                                                    <option value="Not In Service">Not In Service</option>
                                                </select>
                                            </div>
                                        </div>


                                 <?php elseif ($_GET['reporttypes']=='5001'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                        ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">CMU / Warehouse</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%" tabindex="-1"  required="required" name="warehouse_id" >
                                                    <option value="11">Admin Store</option>
                                                    <option></option>

                                                    <?php
                                                    $result=mysql_query("Select * from warehouse where 1 order by warehouse_id");
                                                    while($row=mysql_fetch_array($result)){ ?>

                                                        <option  value="<?php echo $row[warehouse_id]; ?>"><?php echo $row[warehouse_name]; ?></option>
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


                                        <?php elseif ($_GET['reporttypes']=='2001'): ?>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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

                                        <?php elseif ($_GET['reporttypes']=='2002'): ?>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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

                                        <?php elseif ($_GET['reporttypes']=='2003'):
                                            /////////////////////////////////////Outdoor Duty Attendance----------------------------------------------------------
                                            ?>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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


                                        <?php elseif ($_GET['reporttypes']=='2004'):
                                            /////////////////////////////////////Late Attendance----------------------------------------------------------
                                            ?>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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

                                        <?php elseif ($_GET['reporttypes']=='6001'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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


                                        <?php elseif ($_GET['reporttypes']=='6002'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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


                                        <?php elseif ($_GET['reporttypes']=='6003'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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


                                        <?php elseif ($_GET['reporttypes']=='6004'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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


                                        <?php elseif ($_GET['reporttypes']=='6005'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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

                                        <?php elseif ($_GET['reporttypes']=='6008'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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

                                        <?php elseif ($_GET['reporttypes']=='6006'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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


                                        <?php elseif ($_GET['reporttypes']=='6007'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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

                                    <?php elseif ($_GET['reporttypes']=='5002'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                        ?>




                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">CMU / Warehouse</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="warehouse_id" >
                                                    <option value="11" selected>Admin Store</option>

                                                    <?php
                                                    $result=mysql_query("Select * from warehouse where 1 order by warehouse_id");
                                                    while($row=mysql_fetch_array($result)){ ?>

                                                        <option  value="<?php echo $row[warehouse_id]; ?>"><?php echo $row[warehouse_name]; ?></option>
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


                                    <?php elseif ($_GET['reporttypes']=='5003'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                        ?>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">CMU / Warehouse</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="warehouse_id" >
                                                        <option value="11" selected>Admin Store</option>
                                                        <?php
                                                        $result=mysql_query("Select * from warehouse where 1 order by warehouse_id");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[warehouse_id]; ?>"><?php echo $row[warehouse_name]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  id="item_id" name="item_id" >
                                                        <option></option>

                                                        <?php
                                                        $result=mysql_query("SELECT i.*,sg.*,g.* FROM 							
							item_info i,
							item_sub_group sg,
							item_group g
							 where 
							 i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id  in ('600000000') 
							 
							  order by i.item_name");
                                                        while($row=mysql_fetch_array($result)){  ?>
                                                            <option  value="<?php echo $row[item_id]; ?>" <?php if($_GET[item_code_GET]==$row[item_id]) echo 'selected' ?>><?php echo $row[item_id]; ?>-<?php echo $row[item_name]; ?> (<?php echo $row[sub_group_name]; ?>)</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                                        <option></option>
                                                        <?php
                                                        $result=mysql_query("Select * from department where 1 order by DEPT_DESC");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[DEPT_ID]; ?>"><?php echo $row[DEPT_DESC]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee ID</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
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


                                    <?php elseif ($_GET['reporttypes']=='5004'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                        ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">CMU / Warehouse</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="warehouse_id" >
                                                        <option value="11" selected>Admin Store</option>
                                                        <?php
                                                        $result=mysql_query("Select * from warehouse where 1 order by warehouse_id");
                                                        while($row=mysql_fetch_array($result)){ ?>
                                                            <option  value="<?php echo $row[warehouse_id]; ?>"><?php echo $row[warehouse_name]; ?></option>
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

                                    <?php elseif ($_GET['reporttypes']=='3007'):
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
                                                <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td></div></div>


                                    <?php elseif ($_GET['reporttypes']=='5006'):
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
                                                <input type="text" id="t_date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td></div></div>

                                    <?php  else:  ?>
                                    <?php endif; ?>


                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <a href="<?=$page;?>"  class="btn btn-primary">Cancel</a>
                                            <button type="submit" class="btn btn-success" name="getstarted">Go Report</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div></div></div></div></div></div>
</form>



<?php mysqli_close($conn); ?>
<?=$html->footer_content();?>