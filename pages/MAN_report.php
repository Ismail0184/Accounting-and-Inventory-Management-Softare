<?php
require_once 'support_file.php';
$title='MAN Report';
$unique='id';
$unique_field='MAN_ID';
$table="MAN_master";
$table_details="MAN_details";
$unique_details="m_id";



$page='MAN_report.php';
$re_page='Incoming_Material_Received.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$masterDATA=find_all_field(''.$table.'','','id='.$_GET[$unique] );
if(prevent_multi_submit()){
	if(isset($_POST['reprocess']))
    {   $_POST['status']='MANUAL';
        $crud->update($table);
        $_SESSION['m_id']=$_GET[$unique];
        $_SESSION['initiate_man_documents']=find_a_field(''.$table.'','MAN_ID',''.$unique.'='.$_GET[$unique].'');
        $type=1;
        echo "<script>self.opener.location = '$re_page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['Deleted']))
    {
        $crud = new crud($table_details);
        $condition =$unique_details."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);

        $dc_delete = 'dc_documents/'."$_GET[$unique]".'_'.'dc'.'.pdf';
        unlink($dc_delete);


        $vc_delete = 'vc_documents/'."$_GET[$unique]".'_'.'vc'.'.pdf';
        unlink($vc_delete);

        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

$results="Select i.item_id,i.item_id,i.finish_goods_code,i.item_name,i.unit_name as UOM,srd.qty from ".$table_details." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique_details."=".$$unique." order by srd.id";


$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
if(isset($_POST[viewreport])){
    if($_POST['vendor_code']>0) 			 $vendor_code=$_POST['vendor_code'];
    if(isset($vendor_code))				{$vendor_code_CON=' and m.vendor_code='.$vendor_code;}

    if($_POST['warehouse_id']>0) 			 $warehouse_id=$_POST['warehouse_id'];
    if(isset($warehouse_id))				{$warehouse_id_CON=' and m.warehouse_id='.$warehouse_id;}

$sql="Select m.id,m.id as ID,m.MAN_ID as MAN_NO,m.man_date as date,w.warehouse_name as warehouse,v.vendor_name,m.remarks,m.return_resone,m.delivary_challan,m.VAT_challan,concat(u.fname,' - ',m.entry_at) as entry_by,m.status
from 
".$table." m,
warehouse w,
user_activity_management u,
vendor v

 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_id and  
 v.vendor_id=m.vendor_code and 
 m.man_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' ".$vendor_code_CON.$warehouse_id_CON." order by m.".$unique." DESC ";

} else {
$sql="Select m.id,m.id as ID,m.MAN_ID as MAN_NO,m.man_date as date,w.warehouse_name as warehouse,v.vendor_name,m.remarks,m.return_resone,m.delivary_challan,m.VAT_challan,concat(u.fname,' - ',m.entry_at) as entry_by,m.status
from 
".$table." m,
warehouse w,
user_activity_management u,
vendor v

 where
  m.entry_by=u.user_id and 
 w.warehouse_id=m.warehouse_id and 
 m.status in ('MANUAL','UNCHECKED','RETURNED') and
 v.vendor_id=m.vendor_code order by m.".$unique." DESC ";	
	
	}
$sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>


<?php if(isset($_GET[$unique])): ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
					<?=dataview($results,$unique,$unique_GET,$COUNT_details_data,$page);?>
                    <?php if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='RETURNED'):
					if($masterDATA->entry_by==$_SESSION[userid]): ?>
                        <p>
                            <button type="submit" style="float: left; margin-left: 1%; font-size: 12px" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-processing the MAN</button>
                            <button type="submit" style="float: right; margin-right: 1%; font-size: 12px" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted All Data</button>
                        </p>
                     
					<?  else : echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>Sorry !! This MAN was created by another user.</i></h6>'; endif;
					else : echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This MAN has been Checked !!</i></h6>'; endif;?>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
        <table align="center" style="width: 50%;">
            <tr>
                <td><input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px;height: 25px" value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select class="select2_single form-control" style="width:150px; font-size: 11px ;height: 25px" tabindex="-1"   name="warehouse_id" id="warehouse_id">     <option selected></option>
                         <?=advance_foreign_relation($sql_plant,$_POST[warehouse_id]);?>
                    </select></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select class="select2_single form-control" style="width:200px; font-size: 11px ;height: 25px" tabindex="-1"   name="vendor_code" id="vendor_code">
                        <option selected></option>
                        <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_name)', $_POST[vendor_code], '1 order by vendor_name');  ?>
                    </select></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View MAN</button></td>
            </tr></table>
            </form>
<?=$crud->report_templates_with_status($sql);?>                
<?php endif; ?>
<?=$html->footer_content();?> 