<?php
require_once 'support_file.php';
$title='Sales Report';
$page="sales_select_report.php";

$sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
user_plant_permission upp,
warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
upp.user_id=".$_SESSION[userid]." and upp.status>0
order by w.warehouse_id";

$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
item_sub_group sg,
item_group g WHERE  i.sub_group_id=sg.sub_group_id and
sg.group_id=g.group_id  and i.product_nature in ('Salable','Both')
order by i.item_id";

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
                      <?=$crud->select_a_report(9);?>
                  </div></div></div>
       <div class="col-md-7 col-sm-12 col-xs-12">
                          <div class="x_panel">
                              <div class="x_content">
                                  <?php if ($_GET['report_id']=='9001001'):  ?>
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
                                                  <?=advance_foreign_relation($sql_plant,$warehouse_id_GET);?>                                              </select>
                                          </div>
                                      </div>
                                        <?php elseif ($_GET['report_id']=='9002008'):?>
                                          <div class="form-group">
                                              <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                                              <div class="col-md-6 col-sm-6 col-xs-12">
                                                  <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id" required>
                                                      <option value="0">All</option>
                                                      <?=advance_foreign_relation($sql_item_id,$item_id);?>
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




                                        <?php elseif ($_GET['report_id']=='9004002'):?>
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
                                    <?php elseif ($_GET['report_id']=='9002009'):?>
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
                                                  <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $dealer_code, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer Type:</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" name="dealer_type_con" >
                                                  <option></option>
                                                  <?php foreign_relation('distributor_type', 'typeshorname', 'typedetails', $dealer_type_con, '1'); ?>
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

                                      
                                      <?php elseif ($_GET['report_id']=='9002010'):?>
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
                                                  <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_id);?>
                                              </select>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Brand :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="brand_id" >
                                                  <option></option>
                                                  <?php foreign_relation('item_brand', 'brand_id', 'CONCAT(brand_id," : ", brand_name)', $brand_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>



                                  <?php elseif ($_GET['report_id']=='9001002'):?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id" required>
                                                  <option value="0">All</option>
                                                  <?=advance_foreign_relation($sql_item_id,$item_id);?>
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


                                  <?php elseif ($_GET['report_id']=='9005001'):?>
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

                                  <?php elseif ($_GET['report_id']=='9005002'):?>
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
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Region</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="BRANCH_ID" >
                                                  <option></option>
                                                  <?php foreign_relation('branch', 'BRANCH_ID', 'CONCAT(BRANCH_ID," : ", BRANCH_NAME)', $BRANCH_ID, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['report_id']=='9005003' || $_GET['report_id']=='9002005'):?>
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
                                                  <?php foreign_relation('area', 'AREA_CODE', 'CONCAT(AREA_CODE," : ", AREA_NAME)', $BRANCH_ID, 'Territory_CODE>0'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['report_id']=='9005004'):?>
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
                                                  <?php foreign_relation('town', 'town_code', 'CONCAT(town_code," : ", town_name)', $town_code, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['report_id']=='9005005'):?>
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
                                                  <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $dealer_code, '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['report_id']=='9006001'):?>
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
                                                  <?php foreign_relation('dealer_info', 'account_code', 'CONCAT(account_code," : ", dealer_name_e)', $_POST[ledger_id], '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['report_id']=='9006002'):?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As at <span class="required">*</span>
                                          </label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <input type="date" style="height: 30px; width: 100%; font-size: 11px"  required="required" name="t_date" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" autocomplete="off"></td>
                                          </div>
                                      </div>

                                  <?php elseif ($_GET['report_id']=='9005008'):?>
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
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Commission Status</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="form-control" required style="width:100%; font-size: 11px" tabindex="-1" name="commission_status" >
                                                  <option></option>
                                                  <option value="0">Without Commission</option>
                                                  <option value="1">With Commission</option>
                                              </select>
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



   <?php elseif ($_GET['report_id']=='9004001'):
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


                                  <?php elseif ($_GET['report_id']=='9003002'):
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


                                  <?php elseif ($_GET['report_id']=='9003001'):
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
               <input type="date"   required="required" name="f_date" style="font-size: 11px" value="<?=date('Y-m-01')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date"   required="required" name="t_date" style="font-size: 11px" value="<?=date('Y-m-d')?>" class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>


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
                                      <p style="text-align: center">Please select a report from left</p>
                                  <?php endif; ?>









                                  <?php if ($_GET['report_id']>0): ?>
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
