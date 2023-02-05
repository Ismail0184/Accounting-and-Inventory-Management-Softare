<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Ledger Config";

$now=time();
$unique='id';
$unique_field='warehouse_id';
$table="warehouse_essential_data";
$page="acc_ledger_config_for_warehouse.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


if(prevent_multi_submit()){

if(isset($_POST[add_cash_ledger])){
  $_POST[entry_by] = $_SESSION['userid'];
  $_POST[parameter_name] = 'cash_ledger';
  $_POST[status] = '1';
  $crud->insert();
  unset($_POST);
}

if(isset($_POST[add_bank_ledger])){
  $_POST[entry_by] = $_SESSION['userid'];
  $_POST[parameter_name] = 'bank_ledger';
  $_POST[status] = '1';
  $crud->insert();
  unset($_POST);
}

if(isset($_POST[add_expenses_ledger])){
  $_POST[entry_by] = $_SESSION['userid'];
  $_POST[parameter_name] = 'expenses_ledger';
  $_POST[status] = '1';
  $crud->insert();
  unset($_POST);
}

if(isset($_POST[add_cost_center])){
  $_POST[entry_by] = $_SESSION['userid'];
  $_POST[parameter_name] = 'cc_code';
  $_POST[status] = '1';
  $crud->insert();
  unset($_POST);
}

}


	
$res='select p.'.$unique.',p.'.$unique.' as Code,p.'.$unique_field.' as Employee_ID,p.PBI_NAME as Name, (select DESG_SHORT_NAME from designation where DESG_ID=p.PBI_DESIGNATION) as designation,
                                 (select DEPT_DESC from department where DEPT_ID=p.PBI_DEPARTMENT) as Department,DATE_FORMAT(p.PBI_DOJ, "%M %d, %Y") as DOJ,p.PBI_EMAIL,p.PBI_MOBILE as mobile,p.PBI_JOB_STATUS as status
                                 from '.$table.' p where p.PBI_JOB_STATUS in ("In Service","Not In Service") order by p.'.$unique;	
$cash_ledger_view="SELECT distinct wed.value,concat(wed.value,' : ', al.ledger_name) as Ledger_name,w.warehouse_name as warehouse FROM                        
warehouse_essential_data wed, accounts_ledger al,warehouse w where wed.parameter_name='cash_ledger' and al.ledger_id=wed.value and wed.status>0 and w.warehouse_id=wed.warehouse_id";                                 

$bank_ledger_view="SELECT distinct wed.value,concat(wed.value,' : ', al.ledger_name) as Ledger_name,w.warehouse_name as warehouse FROM                        
warehouse_essential_data wed, accounts_ledger al,warehouse w where wed.parameter_name='bank_ledger' and al.ledger_id=wed.value and wed.status>0 and w.warehouse_id=wed.warehouse_id";                                 

$expenses_ledger_view="SELECT distinct wed.value,concat(wed.value,' : ', al.ledger_name) as Ledger_name,w.warehouse_name as warehouse FROM                        
warehouse_essential_data wed, accounts_ledger al,warehouse w where wed.parameter_name='expenses_ledger' and al.ledger_id=wed.value and wed.status>0 and w.warehouse_id=wed.warehouse_id";                                 

$cost_center_view="SELECT distinct wed.value,concat(wed.value,' : ', cc.center_name) as cost_Center_name  FROM                        
warehouse_essential_data wed, cost_center cc where wed.parameter_name='cc_code' and warehouse_id='".$_SESSION['warehouse']."' and cc.id=wed.value and wed.status>0 ";
?>



 <?php require_once 'header_content.php'; ?>
 <script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 230,top = 5");}
 </script>
 <style>
     input[type=text]{font-size: 11px;}
     select{font-size: 11px;}.rcom{color:red}
 </style>
<?php require_once 'body_content.php'; ?>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right"></div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

 
 <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Cash Ledger</a></li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Bank Ledger</a></li>
                          <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Expenses Ledger</a></li>
                          <li role="presentation" class=""><a href="#tab_content4" id="tab" role="profile-tab3" data-toggle="tab" aria-expanded="false">Cost Center</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                        <table style="width: 100%;">
                        <tr><td style="width:50%">
                          
                            <div class="form-group" style="width: 100%;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Warehouse<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="warehouse_id" id="warehouse_id">
                            <option></option>
                            <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_id);?>    
                            </select>                       
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Cash Ledger<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="value">
                            <option></option>
                            <?=foreign_relation('sub_ledger', 'sub_ledger_id', 'CONCAT(sub_ledger_id," : ", sub_ledger)',$value, 'ledger_id="1002000100000000"','order by sub_ledger_id'); ?>
                            </select>
                            </div></div>
                            <hr>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="add_cash_ledger" class="btn btn-primary" style="font-size: 11px">Add Cash Ledger</button>
                            </div></div>
                            </td>


                            <td style="vertical-align:top">
                            <?=recentdataview_model($cash_ledger_view,'','','150px','Cash Ledger','acc_ledger_config_for_warehouse.php','90');?>
                            </td></td> 
                            </tr>
                            </table> 
</form>
                          </div>
                          
 
 
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                        <table style="width: 100%;">
                        <tr><td style="width:50%">
                            <div class="form-group" style="width: 100%;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Warehouse<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="warehouse_id" id="warehouse_id">
                            <option></option>
                            <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_id);?>    
                            </select>                       
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Bank Ledger<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="value">
                            <option></option>
                            <?=foreign_relation('sub_ledger', 'sub_ledger_id', 'CONCAT(sub_ledger_id," : ", sub_ledger)',$value, 'ledger_id="1002000900000000"','order by sub_ledger_id'); ?>
                            </select>
                            </div></div>
                            <hr>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="add_bank_ledger" class="btn btn-primary" style="font-size: 11px">Add Bank Ledger</button>
                            </div></div>
                            </td>


                            <td style="vertical-align:top">
                            <?=recentdataview_model($bank_ledger_view,'','','150px','Bank Ledger','acc_ledger_config_for_warehouse.php','90');?>
                            </td></td> 
                            </tr>
                            </table>
</form>
                          </div>
                          
                          
                          
                          
                          
                          <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                          <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                          <table style="width: 100%;">
                            <tr><td style="width:50%">
                            <div class="form-group" style="width: 100%;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Warehouse<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="warehouse_id" id="warehouse_id">
                            <option></option>
                            <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_id);?>    
                            </select>                       
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Expenses Ledger<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="value">
                            <option></option>
                            <?=foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',$value, 'ledger_group_id between "3000" and "5000"','order by ledger_id'); ?>
                            </select>
                            </div></div>
                            <hr>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="add_expenses_ledger" class="btn btn-primary" style="font-size: 11px">Add Expenses Ledger</button>
                            </div></div>
                            </td>


                            <td style="vertical-align:top">
                            <?=recentdataview_model($expenses_ledger_view,'','','150px','Bank Ledger','acc_ledger_config_for_warehouse.php','90');?>
                            </td></td> 
                            </tr>
                            </table>
</form>
                          </div>


                          <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                          <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                          <table style="width: 100%;">
                            <tr><td style="width:50%">
                            <div class="form-group" style="width: 100%;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Warehouse<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="warehouse_id" id="warehouse_id">
                            <option></option>
                            <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_id);?>    
                            </select>                       
                            </div></div>
                            
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Cost Center<span class="required rcom">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%" name="value">
                            <option></option>
                            <?=foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)',$value, 'status=1','order by ledger_id'); ?>
                            </select>
                            </div></div>
                            <hr>
                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="add_cost_center" class="btn btn-primary" style="font-size: 11px">Add Cost Center</button>
                            </div></div>
                            </td>


                            <td style="vertical-align:top">
                            <?=recentdataview_model($cost_center_view,'','','150px','Bank Ledger','acc_ledger_config_for_warehouse.php','90');?>
                            </td></td> 
                            </tr>
                            </table>
</form>
                          </div>

                          

                         
                        
        
                          </div></div>
                        </div>
                      </div>
             </div>
         </div>
     </div>
<?=$html->footer_content();?>