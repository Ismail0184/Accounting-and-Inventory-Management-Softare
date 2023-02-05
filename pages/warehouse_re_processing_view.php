 <?php
require_once 'support_file.php';
$now=time();
$unique='pi_no';
$unique_field='custom_pi_no';
$table="re_processing_master";
$table_details="re_processing_detail";
$page="warehouse_re_processing_view.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];	 
$title="GRN / SRN View";

$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="UNCHECKED";
?>



<?php require_once 'header_content.php'; ?>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>

<?php 
if(!isset($_GET[$unique])){	
if(isset($_POST[viewreport])){	
$res='select r.'.$unique.',r.'.$unique.' as pi_no,r.'.$unique_field.',r.pi_date,w.warehouse_name,
u.fname as entry_by,r.entry_at
from 
'.$table.' r,
warehouse w,
user_activity_management u
where 
w.warehouse_id=r.warehouse_from and 
r.entry_by=u.user_id and r.pi_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"
group by r.'.$unique.'
 order by r.'.$unique.' desc';
 ?>


<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Re-processing</button></td>
            </tr></table>
<?=$crud->report_templates_with_data($res,$title='GRN/SRN List');?>   
</form>
<?php }} ?>
<?php
if(isset($_GET[$unique])){	
$res='select r.pi_no,i.item_id,i.item_name as product_description,i.unit_name,r.total_unit
from 
'.$table_details.' r,
item_info i
where 
r.'.$unique.'='.$$unique.' and
r.item_id=i.item_id';?>
<?=$crud->report_templates($res,$title);?>
<?php if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED" && $current_status!="UNCHECKED"){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This RP has been CHECKED!!</i></h6>';} else { ?>
                                     <p>
                                     <button type="submit" style="font-size:12px; float:left; margin-left:10px" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-processing</button>                                           <button type="submit" style="font-size:12px; float:right; margin-right:10px" onclick='return window.confirm("Are you confirm to Deleted the Requisition?");' name="Deleted" id="Deleted" class="btn btn-danger">Deleted</button>
                                     </p>           
                                            <?php }} ?>        
<?php require_once 'footer_content.php';  ?>


