<?php
require_once 'support_file.php';
$title='Warehouse Report';
$page='inventory_select_report.php';
$sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM
user_plant_permission upp,warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and
upp.user_id=".$_SESSION[userid]." and upp.status>0
order by w.warehouse_name";
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
            </div></div></div>





    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php if ($_GET['report_id']=='50000'):?>
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
                    
                <?php elseif ($_GET['report_id']=='7001001'|| $_GET['report_id']=='7001002'): ?>
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                         <option></option>
                         <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
                       </select>
                      </div>
                  </div>
                  <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">DO Type :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="do_type" >
                                                  <option></option>
                                                  <?php foreign_relation('sale_do_master', 'distinct do_type', 'do_type', $_POST[do_type], '1'); ?>
                                              </select>
                                          </div>
                                      </div>

                                       <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Dealer Name :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control" style="width:100%; font-size: 12px" tabindex="-1"  name="dealer_code" >
                                                  <option></option>
                                                  <?=foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $dealer_code, '1'); ?>
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



                <?php elseif ($_GET['report_id']=='500001'):?>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">LC NO</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 12px" tabindex="-1"  name="lc_id" id="lc_id" >
                                <option></option>
                                <?php
                                $sql="Select * from lc_lc_master where status not in ('MANUAL') order by id";
                                $result1=mysqli_query($conn, $sql);
                                while($row=mysqli_fetch_array($result1)){ ?>
                                    <option  value="<?php echo $row[id]; ?>"><?php echo $row[id]; ?>-<?php echo $row[lc_no]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="f_date" style="font-size: 12px"  required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="t_date" style="font-size: 12px"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>






                <?php elseif ($_GET['report_id']=='7003004'): ?>
                    <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                                             <option></option>
                                                             <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
                                                           </select>
                                                          </div>
                                                      </div>

                                                      <div class="form-group" style="display:none">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                              <select class="select2_single form-control"  required style="width: 100%;" name="group_id" id="group_id">
                                                                  <option selected>500000000</option>
                                                                  <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)', $group_id, '1'); ?>
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


                  











                <?php elseif ($_GET['report_id']=='5002'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                    ?>




                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="cc_code" id="cc_code" >
                                <option></option>

                                <?php
                                $result=mysql_query("Select * from cost_center where 1 order by id");
                                while($row=mysql_fetch_array($result)){ ?>

                                    <option  value="<?php echo $row[id]; ?>"><?=$row[id];?>-<?=$row[center_name];?></option>
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









                <?php elseif ($_GET['report_id']=='5003'): ?>







                    <div class="form-group" style="display: none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="last-name"  required="required" name="datefrom" value="<?php echo date('Y-m-01') ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>




                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="last-name"  required="required" name="dateto" value="<?php echo date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>




                <?php elseif ($_GET['report_id']=='5004'):
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


                  <?php elseif ($_GET['report_id'] == '7003001'):?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                           <option></option>
                           <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
                         </select>
                        </div>
                    </div>

                                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                                <option value="0"></option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and
							 sg.group_id=g.group_id
							  order by i.item_name";
                                advance_foreign_relation($sql_item_id,$_POST[item_id]);?>
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
                                            </div></div>


                                    <?php elseif ($_GET['report_id'] == '7003002' || $_GET['report_id'] == '7003003'):?>

                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                                             <option></option>
                                                             <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
                                                           </select>
                                                          </div>
                                                      </div>

                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                              <select class="select2_single form-control"  required style="width: 100%;" name="group_id" id="group_id">
                                                                  <option></option>
                                                                  <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)', $group_id, '1'); ?>
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

                                                    <?php elseif ($_GET['report_id'] == '7004001' || $_GET[report_id]=='7004002' || $_GET[report_id]=='7004003' || $_GET[report_id]=='7004004' || $_GET[report_id]=='7004005' || $_GET[report_id]=='7004006'):?>
                                                      <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :<span class="required">*</span></label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="select2_single form-control" style="width:100%; font-size: 11px ;height: 25px" tabindex="-1" required   name="warehouse_id" id="warehouse_id">
                                                             <option></option>
                                                             <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION[warehouse]);?>
                                                           </select>
                                                          </div>
                                                      </div>

                                                                         <div class="form-group">
                                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name :</label>
                                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                                              <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                                                                  <option value="0"></option>
                                                                  <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
                                                item_sub_group sg,
                                                item_group g WHERE  i.sub_group_id=sg.sub_group_id and
                                                 sg.group_id=g.group_id
                                                  order by i.item_name";
                                                                  advance_foreign_relation($sql_item_id,$_POST[item_id]);?>
                                                              </select>
                                                          </div>
                                                      </div>
                                                        <div class="form-group">
                                                                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date Interval :<span class="required">*</span></label>
                                                                              <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                  <input type="date" style="font-size: 11px; width: 49%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-01');?>" class="form-control col-md-7 col-xs-12" required name="f_date">
                                                                                  <input type="date" style="font-size: 11px; width: 49%; margin-left:2%" max="<?=date('Y-m-d');?>" value="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12"  required name="t_date">
                                                                              </div></div>




                                       <?php elseif ($_GET['report_id'] == '60012'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                                      ?>
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="warehouse_id" id="warehouse_id">
                                                  <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, '1'); ?>
                                              </select>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Group :</label>
                                          <div class="col-md-6 col-sm-6 col-xs-12">
                                              <select class="select2_single form-control"  required style="width: 100%;" name="group_id" id="group_id">
                                                  <option></option>
                                                  <?php foreign_relation('item_group', 'group_id', 'CONCAT(group_id," : ", group_name)', $group_id, '1'); ?>
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

                <?php elseif ($_GET['report_id']=='60010'):
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


                <?php elseif ($_GET['report_id']=='60011'):
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




                <?php elseif ($_GET['report_id']=='5005'):   /////////////////////////////////////cash Journal---------------------------------------------------------- ?>


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



                <?php elseif ($_GET['report_id']=='5006'):   /////////////////////////////////////cash Journal---------------------------------------------------------- ?>


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
                            <select class="select2_single form-control" style="width:100%" tabindex="-1"  required="required" name="delivarystatus" >
                                <option value="all">All</option>
                                <option value="Deliverd">Deliverd</option>
                                <option value="UnDeliverd">UnDeliverd</option>


                            </select>
                        </div>
                    </div>


                <?php elseif ($_GET['report_id']=='5010'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px"  required="required" name="t_date" value="<?=$_POST[t_date];?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($_GET['report_id']=='5012'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">As On <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px"  required="required" name="t_date" value="<?=$_POST[t_date];?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($_GET['report_id']=='5013'): ?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger Group : </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="group_id" id="group_id">
                                <option></option>
                                <? foreign_relation('ledger_group','group_id','CONCAT(group_id, " : ", group_name)',$_REQUEST['group_id'],"group_for=".$_SESSION['usergroup']);?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($_GET['report_id']=='5014'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($_GET['report_id']=='5015'): ?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="cc_code" id="cc_code" >
                                <option></option>
                                <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $cc_code, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>

                <?php elseif ($_GET['report_id']=='5016'): ?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                        </div>
                    </div>

                    <div class="form-group" style="display: none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ledger Group : </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" name="group_id" id="group_id">
                                <option value="1006"></option>
                                <? foreign_relation('ledger_group','group_id','CONCAT(group_id, " : ", group_name)',$_REQUEST['group_id'],"group_for=".$_SESSION['usergroup']);?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Profit Center</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1"  name="pc_code" id="pc_code" >
                                <option></option>
                                <?php foreign_relation('profit_center', 'id', 'CONCAT(id," : ", profit_center_name)', $pc_code, 'status in ("1")'); ?>
                            </select>
                        </div>
                    </div>



                <?php elseif ($_GET['report_id']=='5007'): ?>


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



                <?php elseif ($_GET['report_id']=='5008'): ?>


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
                    <h5 align="center">Please select a report from left</h5>
                <?php endif; ?>









                <?php if ($_GET['report_id']>0): ?>

                    <div class="ln_solid"></div>
                    <div class="form-group" style="margin-left: 10%">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <a href="accounts_select_report.php"  class="btn btn-danger" style="font-size: 12px">Cancel</a>
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
