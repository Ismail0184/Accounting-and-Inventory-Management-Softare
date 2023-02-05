<?php require_once 'support_file.php';
$title='Accounts Report';
$page='acc_select_report.php';

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
<?php require_once 'body_content.php'; ?>


<form class="form-horizontal form-label-left" method="POST" action="accounts_reportview.php" style="font-size: 11px" target="_blank">
              <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                      <?=$crud->select_a_report(1);?>
                  </div></div></div>
                      <div class="col-md-7 col-sm-12 col-xs-12">
                          <div class="x_panel">
                              <div class="x_content">
                                  


                <select id="first-name" required="required" size="25" style="font-size: 12px; border: none;white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;" name="report_id" onchange="javascript:reload(this.form)" class="form-control col-md-7 col-xs-12">
                    <optgroup label="Material Report">
                        <option  style="height:20px" value="4000" <?php if ($_GET[report_id]=='4000') echo 'selected';?>>Material Received</option>
                        <option  style="height:20px" value="4001" <?php if ($_GET[report_id]=='4001') echo 'selected';?>>Material Transferred</option>
                        <option  style="height:20px" value="4002" <?php if ($_GET[report_id]=='4002') echo 'selected';?>>Material Movement Summery</option>
                    </optgroup>

                    <optgroup label="Production Report">
                        <option  style="height:20px" value="5000" <?php if ($_GET[report_id]=='5000') echo 'selected';?>>Production Report</option>
                        <option  style="height:20px" value="5001" <?php if ($_GET[report_id]=='5001') echo 'selected';?>>Material Consumption Report</option>
                        <option  style="height:20px" value="5002" <?php if ($_GET[report_id]=='5002') echo 'selected';?>>FG Transfer Report (STO)</option>

                    </optgroup>
                    <optgroup label="Inventory Report">
                        <option  style="height:20px" value="60000" <?php if ($_GET[report_id]=='60000') echo 'selected';?>>Inventory Transaction Statement</option>
                        <option  style="height:20px" value="60010" <?php if ($_GET[report_id]=='60010') echo 'selected';?>>Present Stock (Material)</option>
                        <option  style="height:20px" value="60011" <?php if ($_GET[report_id]=='60011') echo 'selected';?>>Present Stock (FG)</option>
                    </optgroup>
                    <optgroup label="Bill of Material (BOM) Report">
                        <option  style="height:20px" value="70000" <?php if ($_GET[report_id]=='70000') echo 'selected';?>>BOM</option>
                        <option  style="height:20px" value="70001" <?php if ($_GET[report_id]=='70001') echo 'selected';?>>BOM History</option>

                    </optgroup>
                </select>                  </div></div></div>


















    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">



                <?php if ($_GET['report_id']=='50000'):?>



                <?php elseif ($_GET['report_id']=='70000'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Plant / CMU / Warehouse</label>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Finish Good Name:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id">
                                <option></option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							                   i.product_nature in ('Both','Salable')
							  order by i.item_name";
                                advance_foreign_relation($sql_item_id,$item_id);?>
                            </select>
                        </div>
                    </div>




                <?php elseif ($_GET['report_id']=='5000' || $_GET['report_id']=='5001' || $_GET['report_id']=='5002'): ?>
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





                <?php elseif ($_GET['report_id']=='5003'): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">CMU / Warehouse</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%;font-size: 11px" tabindex="-1" required="required"  name="warehouse_id" id="warehouse_id">
                                <option selected></option>
                                <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
                                advance_foreign_relation($sql_plant,$_POST[warehouse_id]);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Material Name:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id">
                                <option></option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id 							 
							  order by i.item_name";
                                advance_foreign_relation($sql_item_id,$item_id);?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px; height: auto"  required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="font-size: 11px;height: auto"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
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


                <?php elseif ($_GET['report_id']=='60000'):  ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id">
                                <option></option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id 							 
							  order by i.item_name";
                                advance_foreign_relation($sql_item_id,$item_id);?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="height: auto; font-size: 11px" required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="height: auto; font-size: 11px"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
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


                <?php elseif ($_GET['report_id']=='5005'):  ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Name:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="item_id" id="item_id" required>
                                <option value="0">All</option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id  and i.product_nature in ('Salable','Both')							 
							  order by i.item_id";
                                advance_foreign_relation($sql_item_id,$item_id);?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Invoice No:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="do_no" id="do_no" >
                                <option value="">If Necessary Select an Invoice.</option>
                                <? $sql_do_no="select m.do_no,concat(m.do_no, ' : ',m.do_date) from sale_do_master m where 1 order by do_no desc";
                                advance_foreign_relation($sql_do_no,$do_no);?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="height: auto; font-size: 11px" required="required" name="f_date"  class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" style="height: auto; font-size: 11px"  required="required" name="t_date" max="<?=date('Y-m-d');?>"  class="form-control col-md-7 col-xs-12" value="<?=date('Y-m-d');?>" autocomplete="off"></td>
                        </div>
                    </div>


                <?php elseif ($_GET['report_id']=='60010' || $_GET['report_id']=='60011'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                    ?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse :</label>
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
                    <h5 align="center">Select a report from the left</h5>
                <?php endif; ?>









                <?php if ($_GET['report_id']>0): ?>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <a href="accounts_select_report.php"  class="btn btn-danger" style="font-size: 12px">Cancel</a>
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
