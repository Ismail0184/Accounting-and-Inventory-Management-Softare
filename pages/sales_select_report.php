<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Sales Report';
$page="sales_select_report.php";
$report_id = @$_REQUEST['report_id'];
$link = 'sales_reportview.php';
?>
<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
    function reload(form)
    {
        var val=form.report_id.options[form.report_id.options.selectedIndex].value;
        self.location='<?=$page?>?report_id=' + val ;
    }
</script>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php require_once 'body_content_nva_sm.php'; ?>

<form class="form-horizontal form-label-left" method="POST" action="<?=$link;?>" style="font-size: 11px" target="_blank">
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?=$crud->select_a_report(9);?>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php if ($report_id=='9001001'):  ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" required="required" name="f_date" class="form-control col-md-7 col-xs-12"  placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;height: 30px; width: 100%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12" placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="dealer_code" >
                                <option></option>
                                <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)',0, '1'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" name="warehouse_id" >
                                <option></option>
                                <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$_SESSION['warehouse']);?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($report_id=='9002008'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id" required>
                                <option value="0">All</option>
                                <?=advance_foreign_relation(find_all_item($product_nature="'Salable','Both','Purchasable'"),0);?>
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
                <?php elseif ($report_id=='9004002'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" required style="width:100%" tabindex="-1"  name="canceled">
                                <option></option>
                                <option value="Yes">ACTIVE</option>
                                <option value="No">INACTIVE</option>
                            </select>
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
                                <option value="dealer_code">Customer Code</option>
                                <option value="account_code">Customer Ledger</option>
                                <option value="dealer_name_e">Customer Name</option>
                                <option value="dealer_type">Customer Category</option>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='9002009'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="dealer_code" >
                                <option></option>
                                <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)',0, '1'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer Type:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" name="dealer_type_con" >
                                <option></option>
                                <?php foreign_relation('distributor_type', 'typeshorname', 'typedetails',0, '1'); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Filter By:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" required tabindex="-1" name="order_by" >
                                <option></option>
                                <option value="serial">Item serial</option>
                                <option value="item_id">ERP Id</option>
                                <option value="finish_goods_code">Custom Code</option>
                                <option value="item_name">Item Name</option>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='9002010'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" name="warehouse_id" >
                                <option></option>
                                <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),'');?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Brand :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="brand_id" >
                                <option></option>
                                <?php foreign_relation('item_brand', 'brand_id', 'CONCAT(brand_id," : ", brand_name)',1, '1'); ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='9001002'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id" required>
                                <option value="0">All</option>
                                <?=advance_foreign_relation(find_all_item($product_nature="'Salable','Both','Purchasable'"),1);?>
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
                <?php elseif ($report_id=='9005001'):?>
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
                 <?php elseif ($report_id=='9005002'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span></label>
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
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Region</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="BRANCH_ID" >
                                <option></option>
                                <?php foreign_relation('branch', 'BRANCH_ID', 'CONCAT(BRANCH_ID," : ", BRANCH_NAME)',0, '1'); ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='9005003' || $report_id=='9002005'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Territory</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="AREA_CODE" >
                                <option></option>
                                <?php foreign_relation('area', 'AREA_CODE', 'CONCAT(AREA_CODE," : ", AREA_NAME)',0, 'Territory_CODE>0'); ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='9005004'):?>
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
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Town</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="town_code" >
                                <option></option>
                                <?php foreign_relation('town', 'town_code', 'CONCAT(town_code," : ", town_name)',0, '1'); ?>
                            </select>
                        </div>
                    </div>
                <?php elseif ($report_id=='9005005'):?>
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
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="dealer_code" >
                                <option></option>
                                <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)',0, '1'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($report_id=='9006001'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                            <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="ledger_id" >
                                <option></option>
                                <?php foreign_relation('dealer_info', 'account_code', 'CONCAT(account_code," : ", dealer_name_e)',0, '1'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($report_id=='9006002'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As at <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="height: 30px; width: 100%; font-size: 11px"  required="required" name="t_date" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" autocomplete="off"></td>
                        </div>
                    </div>

                <?php elseif ($report_id=='9005008'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span></label>
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
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Commission Status</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" required style="width:100%; font-size: 11px" tabindex="-1" name="commission_status" >
                                <option></option>
                                <option value="0">Without Commission</option>
                                <option value="1">With Commission</option>
                            </select>
                        </div>
                    </div>

                <?php elseif ($report_id=='9004001'):?>
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

                <?php elseif ($report_id=='9003002'):?>
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
                                advance_foreign_relation($sql_tsm,'');?>
                                <option value="0">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date"  style="font-size: 11px; height: 30px; width: 100%"  required="required" name="f_date" class="form-control col-md-7 col-xs-12" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date"  style="font-size: 11px; height: 30px; width: 100%"  required="required" name="t_date" class="form-control col-md-7 col-xs-12" autocomplete="off"></td>
                        </div>
                    </div>

                <?php elseif ($report_id=='9003001'):?>
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
                                advance_foreign_relation($sql_tsm,'');?>
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
                                <?php foreign_relation('monthname', 'month', 'CONCAT(month," : ", monthfullName)',1, '1'); ?>
                            </select>
                        </div>
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

                <?php  else:  ?>
                    <p style="text-align: center">Please select a report from left</p>
                <?php endif; ?>
                <?php if(isset($report_id)): ?>
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
