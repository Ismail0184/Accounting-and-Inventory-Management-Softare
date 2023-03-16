 <?php
require_once 'support_file.php';
$title="Consumption Material Setup";

$now=time();
$unique='id';
$unique_field='name';
$table="production_line_raw";
$page="CMU_consumption_row_setup.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


if(prevent_multi_submit()){
    
if(isset($_POST['add']))
        {
			if(isset($_POST[item_id])){
            $table		='production_line_raw';
            $_POST['fg_item_id']=find_a_field('item_info','finish_goods_code','item_id="'.$_POST[item_id].'"');
            $crud      	=new crud($table);
            $crud->insert();}}
    
//for modify..................................
if(isset($_POST['modify']))
{   $crud->update($unique);
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	
$sql="Select p.id,i.item_id,i.finish_goods_code as custom_code,i.item_name,i.unit_name,w.warehouse_name,u.fname as entry_by,p.status 
from 
production_line_raw p, 
item_info i,
item_sub_group s,
warehouse w,
users u 
where 
i.item_id=p.item_id and 
s.sub_group_id=i.sub_group_id and 
p.entry_by=u.user_id and
p.line_id=w.warehouse_id order by p.line_id,i.item_id";

$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ', i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id						 
							  order by i.item_name";
$sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>

 <div class="modal fade" id="addModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Record
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button></h5>
        </div>
        <div class="modal-body">
                                <form style="font-size: 11px" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Select Warehouse<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="select2_single form-control" style="width:100%; font-size: 11px; height: 30px"  tabindex="-1" required="required"  name="line_id" id="line_id">
                        <option></option>
                        <?=advance_foreign_relation($sql_plant,$_SESSION['line_id']);?>
                                  </select> </div></div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Material List<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">                                            
                                            <select multiple class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id">
                              <option></option>
                              <?=advance_foreign_relation($sql_item_id,$item_id);?>
                          </select>
                                            </div></div>

                                            <p align="center">
                                                <button type="submit" name="add" id="add" class="btn btn-primary">Add new materials</button>
                                            </p>
                                    </form></div></div></div></div>

<?=$crud->report_templates_with_add_active_inactive($sql,$title,12,$action=$_SESSION["userlevel"],$create=1);?>

<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
$(document).on('click','.status_checks',function(){
var status = ($(this).hasClass("btn-success")) ? '0' : '1';
var msg = (status=='0')? 'Deactivate' : 'Activate';
if(confirm("Are you sure to "+ msg)){
	var current_element = $(this);
	url = "<?=$page;?>";
	$.ajax({
	type:"POST",
	url: url,
	data: {<?=$unique;?>:$(current_element).attr('data'),status:status},
	success: function(data)
		{   
			location.reload();
		}
	});
	}      
});
</script>
<?=$html->footer_content();?>