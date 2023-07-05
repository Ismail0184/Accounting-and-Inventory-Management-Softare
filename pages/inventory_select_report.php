<?php require_once 'support_file.php';
$title='Warehouse Report';
$page='inventory_select_report.php';
$report_id = @$_GET['report_id'];
?>
<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
 var val=form.report_id.options[form.report_id.options.selectedIndex].value;
 self.location='<?=$page?>?report_id=' + val ;
}
function reload1(form)
{
 var val=form.report_id.options[form.report_id.options.selectedIndex].value;
 var val2=form.ledgercode.options[form.ledgercode.options.selectedIndex].value;
 self.location='<?=$page?>?report_id=' + val +'&ledgercode=' + val2 ;
}

</script>
<style>
    input[type=text]{
        font-size: 11px;
    }

</style>
<?php require_once 'body_content_nva_sm.php'; ?>
<form class="form-horizontal form-label-left" method="POST" action="warehouse_reportview.php" style="font-size: 11px" target="_blank">
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content"><?=$crud->select_a_report(7);?>
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
                <?php if ($report_id=='50000'):?>
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
                <?php elseif ($report_id=='7001001'|| $report_id=='7001002'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                <option></option>
                                <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_SESSION['warehouse']);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">DO Type :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="do_type" >
                                <option></option>
                                <?php foreign_relation('sale_do_master', 'distinct do_type', 'do_type','', '1'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="dealer_code" >
                                <option></option>
                                <?=foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)',0, '1'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                <?php elseif ($report_id=='7003004'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                <option></option>
                                <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_SESSION['warehouse']);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="display:none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control"  required style="width: 100%;" name="group_id" id="group_id">
                                <option selected>500000000</option>
                                <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)',1, '1'); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="display:none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As at Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 100%;" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sort</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="sort"  id="sort">
                                <option value="asc" selected>Sort A to Z</option>
                                <option value="desc">Sort Z to A</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Filter</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="order_by"  id="order_by">
                                <option></option>
                                <option value="serial">Serial</option>
                                <option value="item_id">ERP ID</option>
                                <option value="finish_goods_code">Custom Code</option>
                                <option value="item_name">Item Name</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Expiry Date Range</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="no_of_days" style="font-size: 12px"  required="required" name="no_of_days"  class="form-control col-md-7 col-xs-12"  placeholder="no. of days" autocomplete="off"></td>
                        </div>
                    </div>

                  <?php elseif ($report_id == '7003001'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                           <option></option>
                           <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_SESSION['warehouse']);?>
                         </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                                <option value="0"></option>
                                <?=advance_foreign_relation(find_all_item($product_nature="'Salable','Both','Purchasable'"),1);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Batch :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="batch" id="batch" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                <?php elseif ($report_id == '7003002' || $report_id == '7003003'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                <option></option>
                                <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_SESSION['warehouse']);?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As at Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 100%;" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sort</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="sort"  id="sort">
                                <option value="asc" selected>Sort A to Z</option>
                                <option value="desc">Sort Z to A</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Filter</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="order_by"  id="order_by">
                                <option></option>
                                <option value="serial">Serial</option>
                                <option value="item_id">ERP ID</option>
                                <option value="finish_goods_code">Custom Code</option>
                                <option value="item_name">Item Name</option>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id == '7004001' || $report_id=='7004002' || $report_id=='7004003' || $report_id=='7004004' || $report_id=='7004005' || $report_id=='7004006'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                <option></option>
                                <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_SESSION['warehouse']);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                                <option value="0"></option>
                                <?=advance_foreign_relation(find_all_item($product_nature="'Salable','Both','Purchasable'"),1);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                <?php elseif ($report_id=='7004007'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse <span class="required text-danger">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                <option></option>
                                <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_SESSION['warehouse']);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                                <option value="0"></option>
                                <?=advance_foreign_relation(find_all_item($product_nature="'Salable','Both','Purchasable'"),1);?>
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
                <?php  else:  ?>
                    <p style="text-align: center">Please select a report from left</p>
                <?php endif; ?>
                <?php if ($report_id>0): ?>
                    <div class="ln_solid"></div>
                    <div class="form-group" style="margin-left: 10%">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <a href="<?=$page;?>"  class="btn btn-danger" style="font-size: 12px">Cancel</a>
                            <button type="submit" class="btn btn-primary" name="submit" id="submit" style="font-size: 12px">View Report</button>
                        </div>
                    </div>
                <?php  else:  ?>
                <?php endif; ?>

</form>
</div>
</div>
</div>
<?=$html->footer_content();?>
