<?php require_once 'support_file.php';
$title='Dashboard Permission';
$now=date("Y-m-d H:i:s");
$unique='id';
$table="user_permissions_module";
$page='MIS_permission_matrix_dashboard.php';
$page_module='MIS_permission_matrix_module.php';
$page_dashboard='MIS_permission_matrix_dashboard.php';
$page_main_menu='MIS_permission_matrix_main_menu.php';
$page_sub_menu='MIS_permission_matrix_sub_menu.php';
$page_warehouse='MIS_permission_matrix_warehouse.php';
$page_reports='MIS_permission_matrix_reports.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(isset($_POST['view_report']))
    {$_SESSION[MIS_permission_matrix]=$_POST[user_id];}

if(isset($_POST['cancel']))
        {unset($_SESSION[MIS_permission_matrix]);}   
?>

<?php require_once 'header_content.php'; ?>
<style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #f5f5f5;}
        td{}
    </style>

<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<?php require_once 'body_content.php'; ?>

<div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              <div class="x_title">
              <h2>User Permission Matrix <small><?=find_a_field('users','concat(user_id," : ",fname)','user_id='.$_SESSION[MIS_permission_matrix]);?></small></h2>
              <div class="clearfix"></div>
              </div>

              <?php if(isset($_SESSION[MIS_permission_matrix])): else:  ?>
              <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Active User<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 50%; flot:left" tabindex="-1" required="required" name="user_id" id="user_id">
                                <option></option>
                                <? $sql_user_id="SELECT  u.user_id,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' (',d.DEPT_SHORT_NAME,')') FROM 						 
							personnel_basic_info p,
							department d,
							users u
							 where p.PBI_JOB_STATUS='In Service' and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID and 
							 u.PBI_ID=p.PBI_ID		 
							  order by p.PBI_NAME";
                                advance_foreign_relation($sql_user_id,$_SESSION[MIS_permission_matrix]);?>
                            </select>
                       <?php if(isset($_SESSION[MIS_permission_matrix])){ ?>
                       <?php } else { ?>
						<button type="submit" name="view_report" class="btn btn-primary" style="font-size: 12px; margin-left:5%">Proceed to the next</button>
                       <?php } ?>
                   </div></div></form><?php endif; ?>



                   
                <?php if(isset($_SESSION[MIS_permission_matrix])): ?>
                  <div class="x_content">
                    <div id="wizard" class="form_wizard wizard_horizontal">
                      <ul class="wizard_steps">
                        <li>
                          <a href="#step-1">
                            <span class="step_no">1</span>
                            <span class="step_descr">Module<br />
                                              <small>Permission</small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-2">
                            <span class="step_no">2</span>
                            <span class="step_descr">Dashboard<br />
                                              <small>Module wise dashboard</small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-3">
                            <span class="step_no">3</span>
                            <span class="step_descr">Main Menu<br />
                                              <small>Module wise main menu</small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-4">
                            <span class="step_no">4</span>
                            <span class="step_descr">Sub menu<br />
                                              <small>Sub menu according to master menu</small>
                                          </span>
                          </a>
                        </li>
                        

                        <li>
                          <a href="#step-5">
                            <span class="step_no">5</span>
                            <span class="step_descr">Reports<br />
                                              <small>Reports by module</small>
                                          </span>
                          </a>
                        </li>

                        <li>
                          <a href="#step-6">
                            <span class="step_no">6</span>
                            <span class="step_descr">Warehouse<br />
                                              <small>Warehouse / Plant / Depot / CMU</small>
                                          </span>
                          </a>
                        </li>
                      </ul>
                      
                      
                      <div id="step-1">
                      <h2 class="StepTitle">Step 1 Module Permission</h2>
                          
                <table id="customers" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Module Name</th>
                        <th>Module Short Name</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT m.module_id,m.modulename,m.module_short_name,dpm.module_id,
       (select p.status from user_permissions_module p where p.module_id=m.module_id and p.user_id='".$_SESSION[MIS_permission_matrix]."') as status
       FROM module_department m, dev_permission_module dpm  WHERE m.module_id=dpm.module_id and dpm.status>0 and dpm.user_id=".$_SESSION[userid]." ORDER BY m.module_id");
                    while($data=mysqli_fetch_object($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$data->module_id?>" class="status_checks_module btn <?php echo ($data->status)? 'btn-success' : 'btn-danger'?>"  <?php echo ($data->status=='1')? 'checked' : ''?>></td>
                            <td><?=$data->module_id?> : <?=$data->modulename; ?></td>
                            <td><?=$data->module_short_name?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
    <script type="text/javascript">
        $(document).on('click','.status_checks_module',function(){
            var status = ($(this).hasClass("btn-success")) ? '0' : '1';
            var msg = (status=='0')? 'Deactivate' : 'Activate';
            //if(confirm("Are you sure to "+ msg)){
                var current_element = $(this);
                url = "<?=$page_module;?>";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {module_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    { location.reload();
                    }
                });
            //}
        });
    </script>

                      </div>


                      <div id="step-2">
                        <h2 class="StepTitle">Step 2 Dashboard</h2>
                        
                <table id="customers" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Dashboard of the Modules</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT m.module_id,m.modulename,m.module_short_name,upm.module_id,
       (select p.status from user_permissions_dashboard p where p.module_id=m.module_id and p.user_id='".$_SESSION[MIS_permission_matrix]."') as status
       FROM module_department m, user_permissions_module upm  WHERE m.module_id=upm.module_id and upm.status>0 and upm.user_id=".$_SESSION[MIS_permission_matrix]." ORDER BY m.module_id");
                    while($data=mysqli_fetch_object($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$data->module_id?>" class="status_checks btn <?php echo ($data->status)? 'btn-success' : 'btn-danger'?>"  <?php echo ($data->status=='1')? 'checked' : ''?>></td>
                            <td><?=$data->module_id?> : <?=$data->modulename; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
    <script type="text/javascript">
        $(document).on('click','.status_checks',function(){
            var status = ($(this).hasClass("btn-success")) ? '0' : '1';
            var msg = (status=='0')? 'Deactivate' : 'Activate';
            //if(confirm("Are you sure to "+ msg)){
                var current_element = $(this);
                url = "<?=$page_dashboard;?>";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {module_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    {//location.reload();
                    }
                });
            //}
        });
    </script>
    </div>
                      
    
                      <div id="step-3">
                        <h2 class="StepTitle">Step 3 Master Menu</h2>
                <table id="customers" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Main Manu Name</th>
                        <th>Details</th>
                        <th>URL</th>
                        <th>Fa Icon</th>
                        <th>Module</th>
                        <th>Menu Status</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT dmm.main_menu_id,dmm.main_menu_name,dmm.main_menu_details,dmm.url,dmm.faicon,md.module_short_name,dpm.module_id,dpm.status,
       (select pmm.status from user_permission_matrix_main_menu pmm where pmm.main_menu_id=dmm.main_menu_id and pmm.user_id='".$_SESSION[MIS_permission_matrix]."') as status,md.status as master_menu_status
       FROM dev_main_menu dmm, module_department md,user_permissions_module dpm  WHERE 
       dmm.module_id=md.module_id and 
       md.module_id=dpm.module_id and 
       dpm.status>0 and 
       dpm.user_id='".$_SESSION[MIS_permission_matrix]."' 
       ORDER BY md.module_id,dmm.main_menu_id");
                    while($data=mysqli_fetch_object($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$data->main_menu_id?>" class="status_checks_main_menu btn <?php echo ($data->status)? 'btn-success' : 'btn-danger'?>"  <?php echo ($data->status=='1')? 'checked' : ''?>></td>
                            <td><?=$data->main_menu_id?> : <?=$data->main_menu_name; ?></td>
                            <td><?=$data->main_menu_details?></td>
                            <td><?=$data->url?></td>
                            <td><?=$data->faicon?></td>
                            <td><?=$data->module_short_name?></td>
                            <td><?=($data->master_menu_status==1)? "Active" : "Inactive"?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
        <script type="text/javascript">
        $(document).on('click','.status_checks_main_menu',function(){
            var status = ($(this).hasClass("btn-success")) ? '0' : '1';
            var msg = (status=='0')? 'Deactivate' : 'Activate';
            //if(confirm("Are you sure to "+ msg)){
                var current_element = $(this);
                url = "<?=$page_main_menu;?>";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {main_menu_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    { //location.reload();
                    }
                });
            //}
        });
    </script>
                      </div>




                      <div id="step-4">
                        <h2 class="StepTitle">Step 4 Sub Menu</h2>
                <table id="customers" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Sub Manu Name</th>
                        <th>URL</th>
                        <th>Main Manu</th>
                        <th>Module</th>
                        <th>Sub Menu Status</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT dsm.sub_menu_id,dsm.sub_menu_name,dsm.sub_url,
                    dmm.main_menu_id,dmm.main_menu_name,dpm.status as mobule_permission_status,
                    md.module_short_name,
       (select psm.status from user_permission_matrix_sub_menu psm where psm.sub_menu_id=dsm.sub_menu_id and psm.user_id='".$_SESSION[MIS_permission_matrix]."') as status,dsm.status as sub_menu_status
       FROM dev_main_menu dmm, module_department md,dev_sub_menu dsm,user_permissions_module dpm  
       
       WHERE 
       dpm.module_id=md.module_id and 
       dpm.user_id='".$_SESSION[MIS_permission_matrix]."' and
       dpm.status>0 and 
       dsm.main_menu_id=dmm.main_menu_id and
       dmm.module_id=md.module_id and
       dsm.module_id=md.module_id 
       ORDER BY md.module_id,dmm.main_menu_id,dsm.sub_menu_id");
                    while($data=mysqli_fetch_object($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$data->sub_menu_id?>" class="status_check_sub_menu btn <?php echo ($data->status)? 'btn-success' : 'btn-danger'?>"  <?php echo ($data->status=='1')? 'checked' : ''?>></td>
                            <td><?=$data->sub_menu_id?> : <?=$data->sub_menu_name; ?></td>
                            <td><?=$data->sub_url?></td>
                            <td><?=$data->main_menu_id?> : <?=$data->main_menu_name; ?></td>
                            <td><?=$data->module_short_name?></td>
                            <td><?=($data->sub_menu_status==1)? "Active" : "Inactive"?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
    <script type="text/javascript">
        $(document).on('click','.status_check_sub_menu',function(){
            var status = ($(this).hasClass("btn-success")) ? '0' : '1';
            var msg = (status=='0')? 'Deactivate' : 'Activate';
            //if(confirm("Are you sure to "+ msg)){
                var current_element = $(this);
                url = "<?=$page_sub_menu;?>";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {sub_menu_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    { //location.reload();
                    }
                });
            //}
        });
    </script>
                      </div>



                      <div id="step-6">
                        <h2 class="StepTitle">Step 6 Warehouse</h2>
                        <table id="customers" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Warehouse Name</th>
                        <th>Address</th>
                        <th>Incharge Person</th>
                        <th>Designation</th>
                        <th>Contact No</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT w.warehouse_id,w.warehouse_name,w.address,w.ledger_id,w.ap_name,w.ap_designation,w.contact_no,
       (select p.status from user_permission_matrix_warehouse p where p.warehouse_id=w.warehouse_id and p.user_id='".$_SESSION[MIS_permission_matrix]."') as status
       FROM warehouse w   WHERE 1 ORDER BY w.warehouse_id");
                    while($data=mysqli_fetch_object($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$data->warehouse_id?>" class="status_check_warehouse_id btn <?php echo ($data->status)? 'btn-success' : 'btn-danger'?>"  <?php echo ($data->status=='1')? 'checked' : ''?>></td>
                            <td><?=$data->warehouse_id?> : <?=$data->ledger_id; ?> : <?=$data->warehouse_name; ?></td>
                            <td><?=$data->address?></td>
                            <td><?=$data->ap_name?></td>
                            <td><?=$data->ap_designation?></td>
                            <td><?=$data->contact_no?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
    <script type="text/javascript">
        $(document).on('click','.status_check_warehouse_id',function(){
            var status = ($(this).hasClass("btn-success")) ? '0' : '1';
            var msg = (status=='0')? 'Deactivate' : 'Activate';
            //if(confirm("Are you sure to "+ msg)){
                var current_element = $(this);
                url = "<?=$page_warehouse;?>";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {warehouse_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    { //location.reload();
                    }
                });
            //}
        });
    </script>
                      </div>
                      <div id="step-5">
                        <h2 class="StepTitle">Step 5 Reports</h2>
                        <table class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <tr>
                        <th style="width: 2%">Action</th>
                        <th>Report ID</th>
                        <th>Report Name</th>
                        <th>Report Group</th>
                        <th>Module</th>
                    </tr>
                    <?php $sql=mysqli_query($conn, "SELECT g.optgroup_label_name,r.report_name as subzonename,r.report_id,m.module_id,m.modulename,dpm.module_id,
       (select status from user_permission_matrix_reportview where report_id=r.report_id and user_id=".$_SESSION[MIS_permission_matrix].") as status       
       FROM 
       module_reportview_optgroup_label g, 
       module_reportview_report r,
       module_department m,
       user_permissions_module dpm
       WHERE
       dpm.module_id=g.module_id and 
       dpm.user_id='".$_SESSION[MIS_permission_matrix]."' and
       dpm.status>0 and 
       g.optgroup_label_id = r.optgroup_label_id  and g.module_id=m.module_id
ORDER BY m.module_id,g.sl, r.sl");
                    while($data=mysqli_fetch_object($sql)):?>
                        <tr>
                            <td style="text-align: center"><input type="checkbox" data="<?=$data->report_id;?>" class="status_check_reports btn <?php echo ($data->status)? 'btn-success' : 'btn-danger'?>"  <?php echo ($data->status=='1')? 'checked' : ''?>></td>
                            <td><?=$i=$i+1;?> : <?=$data->report_id;?></td>
                            <td><?=$data->subzonename;?></td>
                            <td><?=$data->optgroup_label_name;?></td>
                            <td><?=$data->modulename;?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
    <script type="text/javascript">
        $(document).on('click','.status_check_reports',function(){
            var status = ($(this).hasClass("btn-success")) ? '0' : '1';
            var msg = (status=='0')? 'Deactivate' : 'Activate';
            //if(confirm("Are you sure to "+ msg)){
                var current_element = $(this);
                url = "<?=$page_reports;?>";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {report_id:$(current_element).attr('data'),status:status},
                    success: function(data)
                    {
                        //location.reload();
                    }
                });
            //}
        });
    </script>
      </div>

                      
                    </div>
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                    <?php if(isset($_SESSION[MIS_permission_matrix])){ ?>
                        <button type="submit" name="cancel" class="btn btn-danger"  style="font-size: 12px; margin-left:5%">Cancel</button>
                       <?php } ?>
                    </form>
                 </div>
                 <?php endif;?>
                 
                </div>
              </div>
              
            </div>
          </div>
        </div>
        <!-- /page content -->


        <footer>
        <div class="pull-right">Powered By: <strong>Raresoft</strong> </div>
        <div class="clearfix">©2022<strong> Raresoft</strong> All Rights Reserved</div>
        </footer>
      </div>
    </div>

    <!-- jQuery -->
    <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../assets/vendors/nprogress/nprogress.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="../assets/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../assets/build/js/custom.min.js"></script>
    <!-- Select2 -->
    <script src="../assets/vendors/select2/dist/js/select2.full.min.js"></script>

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

    <!-- jQuery Smart Wizard -->
    <script>
      $(document).ready(function() {
        $('#wizard').smartWizard();

        $('#wizard_verticle').smartWizard({
          transitionEffect: 'slide'
        });

        $('.buttonNext').addClass('btn btn-success');
        $('.buttonPrevious').addClass('btn btn-primary');
        $('.buttonFinish').addClass('btn btn-default');
      });
    </script>
    <!-- /jQuery Smart Wizard -->
  </body>
</html>
