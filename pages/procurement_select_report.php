<?php require_once 'support_file.php'; 
$title='Procurement Report';
$page='procurement_select_report.php';?>
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
<form class="form-horizontal form-label-left" method="POST" action="procurement_reportview.php" style="font-size: 11px" target="_blank">
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?=$crud->select_a_report(3);?>
            </div></div></div>
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php if ($_GET['report_id']=='3001001' || $_GET['report_id']=='3001002') :?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Vendor</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" name="vendor_id" >
                                <option></option>
                                <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $_POST[vendor_id], 'status in ("ACTIVE")'); ?>
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

                <?php elseif ($_GET['report_id']=='3002001'):?>
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" required style="width:100%" tabindex="-1"  name="status"  id="status">
                              <option></option>
                              <option>ACTIVE</option>
                              <option>INACTIVE</option>
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
                              <option value="vendor_id">Vendor Code</option>
                              <option value="ledger_id">Vendor Ledger</option>
                              <option value="vendor_name">Vendor Name</option>
                              <option value="vendor_category">Vendor Category</option>
                          </select>
                      </div>
                  </div>

                <?php  else:  ?><p style="text-align: center">Please select a report from left</p><?php endif; ?>
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
