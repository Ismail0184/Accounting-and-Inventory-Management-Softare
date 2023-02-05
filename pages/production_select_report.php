<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Production Report';
$page='production_select_report.php';

$sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
user_plant_permission upp,
warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
upp.user_id=".$_SESSION[userid]." and upp.status>0
order by w.warehouse_id";

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
    input[type=text]{
        font-size: 11px;
    }

</style>
<?php require_once 'body_content.php'; ?>




<form class="form-horizontal form-label-left" method="POST" action="production_reportview.php" style="font-size: 11px" target="_blank">
              <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                      <?=$crud->select_a_report(5);?>
                  </div></div></div>
                      <div class="col-md-7 col-sm-12 col-xs-12">
                          <div class="x_panel">
                              <div class="x_content">
                                  <?php if ($_GET['report_id']=='5002001'):?>
                                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">CMU / Warehouse</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control"  style="width:100%;font-size: 11px" tabindex="-1" required="required"  name="warehouse_id" id="warehouse_id">
                                <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
                                advance_foreign_relation($sql_plant,$warehouse_id);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Items Name:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id">
                                <option></option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and  i.status in ('Active')	 							 
							  order by i.item_name";
                                advance_foreign_relation($sql_item_id,$item_id);?>
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


   <?php elseif ($_GET['report_id']=='5008' || $_GET['report_id']=='1005002'): ?>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Current Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date"  style="font-size: 11px; width: 235px; margin-left: 20px; height: 30px" class="form-control col-md-7 col-xs-12"  max="<?=date('Y-m-d') ?>" name="t_date" value="<?=date('Y-m-d') ?>"  autocomplete="off"></td>

           </div>
       </div>


       <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Previous Period<span class="required">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="date"  style="font-size: 11px; width: 235px; margin-left: 20px; height: 30px" class="form-control col-md-7 col-xs-12" max="<?=date('Y-m-d') ?>" name="pt_date" placeholder="From Date" autocomplete="off"></td>

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
