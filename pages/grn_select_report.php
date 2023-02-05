<?php require_once 'support_file.php';
$title='Goods Received Report';
$page='grn_select_report.php';
$target_reportview_page='grn_reportview.php';


$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
item_sub_group sg,
item_group g WHERE  i.sub_group_id=sg.sub_group_id and
sg.group_id=g.group_id
order by i.item_name";
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
    input[type=text]{font-size: 11px;}
</style>
<?php require_once 'body_content_nva_sm.php'; ?>
                  <form class="form-horizontal form-label-left" method="POST" action="<?=$target_reportview_page?>" style="font-size: 11px" target="_blank">
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                  <div class="x_panel">
                                    <div class="x_content">
                                        <?=$crud->select_a_report(4);?>
                                    </div></div></div>
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <div class="x_panel">
                                                <div class="x_content">
                                                    <?php if ($_GET['report_id']=='4001001'):?>
                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">

                                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id">
                                                                  <option></option>
                                                                  <?=advance_foreign_relation($sql_item_id,$item_id);?>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" required="required" name="warehouse_id" >
                                                                  <option></option>
                                                                  <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
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
                                                    <?php elseif ($_GET['report_id']=='4002001' || $_GET['report_id']=='4003001' || $_GET[report_id]=='4003002' || $_GET[report_id]=='4003003' || $_GET[report_id]=='4003004' || $_GET[report_id]=='4003005'):?>

                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Vendor :</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1" name="vendor_id" >
                                                                  <option></option>
                                                                  <?=foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $_POST[vendor_id], 'status="ACTIVE"'); ?>
                                                              </select>
                                                          </div>
                                                      </div>

                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span>
                                                          </label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                              <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                                                              <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                                                          </div>
                                                      </div>



                                                    <?php elseif ($_GET['report_id']=='1301002' || $_GET['report_id']=='1301003' || $_GET['report_id']=='1302001'):?>
                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval <span class="required">*</span>
                                                          </label>
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
                                                                  <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
                                                              </select>
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
                                   <?php  else: endif; ?>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                  <?=$html->footer_content();?>
