<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Accounts Report';
$page='acc_select_report.php';

$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
item_sub_group sg,
item_group g WHERE  i.sub_group_id=sg.sub_group_id and
sg.group_id=g.group_id
order by i.item_name";
$report_id = @$_REQUEST['report_id'];
$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
    $sec_com_connection_wa=' and 1';
} else {
    $sec_com_connection=" and al.company_id='".$_SESSION['companyid']."' and al.section_id in ('400000','".$_SESSION['sectionid']."')";
    $sec_com_connection_wa=" and company_id='".$_SESSION['companyid']."' and section_id in ('400000','".$_SESSION['sectionid']."')";
}
?>



<?php require_once 'header_content.php'; ?>
 <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.report_id.options[form.report_id.options.selectedIndex].value;
	self.location='acc_select_report.php?report_id=' + val ;
}
function reload1(form)
{
	var val=form.report_id.options[form.report_id.options.selectedIndex].value;
	var val2=form.ledgercode.options[form.ledgercode.options.selectedIndex].value;
	self.location='acc_select_report.php?report_id=' + val +'&ledgercode=' + val2 ;
}

</script>
<style>
    input[type=text]{
        font-size: 11px;
    }

</style>
<?php require_once 'body_content_nva_sm.php'; ?>

<form class="form-horizontal form-label-left" method="POST" action="accounts_reportview.php" style="font-size: 11px" target="_blank">
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?=$crud->select_a_report(1);?>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><small class="text-danger">field marked with * are mandatory</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if ($report_id=='1001001'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" tabindex="-1" required name="PBI_JOB_STATUS"  id="PBI_JOB_STATUS">
                                <option></option>
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='1001002'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" required tabindex="-1"  name="status"  id="status">
                                <option></option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Order by <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" required tabindex="-1"  name="order_by"  id="order_by">
                                <option></option>
                                <?php
                                $sql = mysqli_query($conn, "SHOW COLUMNS FROM item_info like 'item_ids'");
                                while($row=mysqli_fetch_assoc($sql)){ ?>
                                    <option><?=$row['Field']?></option>
                                <?php } ?>
                                <option></option>
                                <option value="serial">Item serial</option>
                                <option value="item_id">ERP Id</option>
                                <option value="finish_goods_code">Custom Code</option>
                                <option value="item_name">Item Name</option>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='1007001'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                <?php elseif ($report_id=='1007002'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">LC Number</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="lc_id" >
                                <option></option>
                                <option value="%">All</option>
                                <?php foreign_relation('lc_lc_master', 'id', 'CONCAT(id," : ", lc_no)',1, '1'); ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='1002001'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" name="ledger_id" >
                                <option></option>
                                <option value="%">All Transactions</option>
                                <?php foreign_relation("accounts_ledger", "ledger_id", "CONCAT(ledger_id,' : ', ledger_name)",1, "status=1".$sec_com_connection_wa.""); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                    <option></option>
                                    <?=foreign_relation("cost_center", "id", "CONCAT(id,' : ', center_name)",1, "status=1".$sec_com_connection_wa.""); ?>
                                </select>
                            </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Transaction Type</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="tr_from" id="tr_from" >
                                <option value="">Select Type</option>
                                <?=foreign_relation('journal', 'distinct tr_from', 'tr_from',1, '1','order by tr_from');?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>

       <?php elseif ($report_id=='1006002'): ?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Vendor</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="ledger_id" >
                   <option></option>
                       <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',1, 'ledger_group_id in ("2002")'); ?>
               </select>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required text-danger">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
           </div>
       </div>

       <?php elseif ($report_id=='1006001'): ?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Vendor</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="ledger_id" >
                   <option></option>
                       <?=foreign_relation("accounts_ledger", "ledger_id", "CONCAT(ledger_id,' : ', ledger_name)",1,"ledger_group_id=2002".$sec_com_connection_wa."");?>
               </select>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As on <span class="required text-danger">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px;100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date" ></td>
           </div>
       </div>




                <?php elseif ($report_id=='1002001'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="ledger_id" >
                                <option></option>
                                <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $ledger_id, 'ledger_group_id in ("2002")'); ?>
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
                                    <option  value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?>-<?php echo $row['lc_no']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>







     <?php elseif ($report_id=='1002004'):?>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Users </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="user_id" id="user_id" >
                   <option></option>
                   <?=foreign_relation('users', 'user_id', 'CONCAT(user_id," : ", fname)',1, 'account_status in ("active")'); ?>
                 </select>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
               <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
           </div>
       </div>






    <?php elseif ($report_id=='1010001'):  ?>

                                       <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="dealer_code" >
                                                  <option></option>
                                                  <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)',1, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">DO type :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="do_type" >
                                                  <option></option>
                                                  <?php foreign_relation('sale_do_master', 'distinct do_type', 'do_type',1, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" name="warehouse_id" >
                                                  <option></option>
                                                  <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),'');?>                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required text-danger">*</span></label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                                              <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                                          </div>
                                      </div>





                                  <?php elseif ($report_id=='1008001'):  ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">

                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id">
                                                  <option></option>
                                                  <?=advance_foreign_relation($sql_item_id,'');?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="warehouse_id" >
                                                  <option></option>
                                                  <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),'');?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                          <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                                          <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
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


                                  <?php elseif ($report_id=='1010002'):  ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id" required>
                                                  <option value="0">All</option>
                                                  <?=advance_foreign_relation($sql_item_id,'');?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required text-danger">*</span></label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                                              <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                                          </div>
                                      </div>
                                  <?php elseif ($report_id == '1008002' || $report_id == '1008003'):?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="warehouse_id" id="warehouse_id">
                                              <option value="">-- select a warehouse --</option>
                                              <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),'');?>                                              </select>
                                          </div>
                                      </div>



                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required text-danger">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                  <?php elseif ($report_id == '1002003'):?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">LC Number<span class="required text-danger">*</span></label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="lc_id" >
                                                  <option></option>
                                                  <option value="%">All</option>
                                                  <?php foreign_relation('lc_lc_master', 'id', 'CONCAT(id," : ", lc_no)',1, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Expenses Head</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 11px" name="subledger_id">
                                                  <option></option>
                                                  <?=foreign_relation('LC_expenses_head', 'LC_exp_ledger', 'LC_expenses_head',1, 'status in (\'1\')'); ?>
                                              </select>
                                          </div>
                                      </div>



<?php elseif ($report_id == '1011001'):?>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select multiple class="select2_single form-control"  style="width: 100%;" name="group_id[]" id="group_id">
                                                  <option></option>
                                                  <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)',1, '1'); ?>
                                              </select>
                                          </div>
                                      </div>
                                  <?php elseif ($report_id == '1008004'):?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="warehouse_id" id="warehouse_id">
                                              <option></option>
                                              <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),'');?>                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="group_id" id="group_id">
                                                  <option></option>
                                                  <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)',1, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date"  placeholder="From Date" autocomplete="off"></td>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required text-danger">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"   placeholder="to Date" autocomplete="off"></td>
                                          </div>
                                      </div>




<?php elseif ($report_id=='1010003'):?>
  <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required text-danger">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
          <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
      </div>
  </div>
  <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12">Invoice Type</label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="select2_single form-control" style="width:100%"  name="do_type" >
                   <option value="all">All</option>
                   <option value="sales">Sales</option>
                   <option value="sample">Sample</option>
                   <option value="display">Product Display</option>
                   <option value="gift">Gift</option>
                   <option value="free">Free</option>
               </select>
           </div>
       </div>


                <?php elseif ($report_id=='1002005'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On:  <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;width: 100%" class="form-control col-md-7 col-xs-12" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date" /></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer Type: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="dealer_type" id="dealer_type" >
                                <option></option>
                                <?php foreign_relation('distributor_type', 'typeshorname', 'CONCAT(typedetails)',1, '1'); ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='1004001' || $report_id=='1004002'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px"  required="required" max="<?=date('Y-m-d');?>" name="t_date" value="<?=date('Y-m-d')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)',1, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($report_id=='1004004'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger Group : </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="group_id" id="group_id">
                                <option></option>
                                <? foreign_relation('ledger_group','group_id','CONCAT(group_id, " : ", group_name)',1,"group_for=".$_SESSION['usergroup']);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?=foreign_relation("cost_center","id","CONCAT(id,' : ', center_name)",1, "status in ('1')");?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='1004003'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?=foreign_relation("cost_center", "id", "CONCAT(id,' : ', center_name)",1,"status=1".$sec_com_connection_wa."");?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($report_id=='1004005'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?=foreign_relation("cost_center", "id", "CONCAT(id,' : ', center_name)",1,"status=1".$sec_com_connection_wa."");?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='1002006'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group" style="display: none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger Group : </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="group_id" id="group_id">
                                <option value="1006"></option>
                                <? foreign_relation("ledger_group","group_id","CONCAT(group_id, ' : ', group_name)",1,"status=1".$sec_com_connection_wa."");?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Profit Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="pc_code" id="pc_code" >
                                <option></option>
                                <?php foreign_relation('profit_center', 'id', 'CONCAT(id," : ", profit_center_name)',1, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($report_id=='1005001'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="">Current Interval :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Previous Interval :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%"  name="pf_date" class="form-control col-md-7 col-xs-12" max="<?=date('Y-m-d')?>"  autocomplete="off"></td>
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" class="form-control col-md-7 col-xs-12" max="<?=date('Y-m-d')?>" name="pt_date"   autocomplete="off"></td>
                        </div>
                    </div>

                <?php elseif ($report_id=='1005003'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="">Current Interval :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Previous Interval :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%"  name="pf_date" class="form-control col-md-7 col-xs-12" max="<?=date('Y-m-d')?>"  autocomplete="off"></td>
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" class="form-control col-md-7 col-xs-12" max="<?=date('Y-m-d')?>" name="pt_date"   autocomplete="off"></td>
                        </div>
                    </div>

                <?php elseif ($report_id=='1005002'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Current Period <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date"  style="font-size: 11px; width: 235px; margin-left: 20px; height: 30px" class="form-control col-md-7 col-xs-12"  max="<?=date('Y-m-d') ?>" required name="t_date" value="<?=date('Y-m-d') ?>" ></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Previous Period </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date"  style="font-size: 11px; width: 235px; margin-left: 20px; height: 30px" class="form-control col-md-7 col-xs-12" max="<?=date('Y-m-d') ?>" name="pt_date" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>

                <?php  else:  ?>
                    <p style="text-align: center">Please select a report from left</p>
                <?php endif; ?>

                <?php if ($report_id>0): ?>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <a href="<?=$page?>"  class="btn btn-danger" style="font-size: 12px">Cancel</a>
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
