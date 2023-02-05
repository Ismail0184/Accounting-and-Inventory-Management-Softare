<?php require_once 'support_file.php';
$title='LC Report';
$page='LC_select_report.php';?>
<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
    function reload(form)
    {
        var val=form.report_id.options[form.report_id.options.selectedIndex].value;
        self.location='<?=$page?>?report_id=' + val ;
    }
</script>
<style>
    input[type=text]{font-size: 11px;}
</style>
<?php require_once 'body_content_nva_sm.php'; ?>

<form class="form-horizontal form-label-left" method="POST" action="lc_reportview.php" target="_blank" style="font-size: 11px">
                                            <div class="col-md-5 col-sm-12 col-xs-12">
                                                <div class="x_panel">
                                                    <div class="x_content">
                                                        <?=$crud->select_a_report(2);?>
                                                    </div></div></div>
<div class="col-md-7 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_content">

                                        <?php if ($_GET['report_id']=='2001001'): ?>
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
                                                      <option value="party_id">Buyer ID</option>
                                                      <option value="buyer_name">Buyer Name</option>
                                                  </select>
                                              </div>
                                          </div>

                                        <?php elseif ($_GET['report_id']=='2003001'):?>
                            

                    <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Buyer</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="party_id" >
                                                        <option></option>
                                                        <?=foreign_relation('lc_buyer', 'party_id', 'CONCAT(party_id," : ", buyer_name)', $party_id, 'status in ("ACTIVE")'); ?>
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


                                 

                                        <?php elseif ($_GET['report_id']=='2002001'): ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Buyer</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="party_id" >
                                                        <option></option>
                                                        <?=foreign_relation('lc_buyer', 'party_id', 'CONCAT(party_id," : ", buyer_name)', $party_id, 'status in ("ACTIVE")'); ?>
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

                                            <?php elseif ($_GET['report_id']=='2003002'):?>
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
