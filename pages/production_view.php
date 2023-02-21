<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='pr_no';
$unique_field='name';
$table="production_floor_receive_master";
$table_deatils="production_floor_receive_detail";

$production_table_issue_master="production_floor_issue_master";
$production_table_issue_detail="production_floor_issue_detail";
$journal_item="journal_item";
$journal_accounts="journal";
$page='production_view.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if (isset($_POST['reprocess'])) {


        $crud = new crud($production_table_issue_detail);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $deleted_journal_item="Delete from ".$journal_item." where sr_no=".$$unique." and tr_from='Consumption'";
        $query=mysqli_query($conn, $deleted_journal_item);

        $deleted_journal="Delete from ".$journal_accounts." where tr_no=".$$unique." and tr_from in ('Consumption','Production')";
        $queryj=mysqli_query($conn, $deleted_journal);

        $deleted_production_gain="Delete from ".$table_deatils." where ".$unique."=".$$unique." and p_type in ('Gain')";
        $queryg=mysqli_query($conn, $deleted_production_gain);


        $_POST['status'] = 'MANUAL';
        $crud->update($table);

        $_SESSION['ps_id'] = $_GET[$unique];
        $_SESSION['pi_id']=getSVALUE("production_floor_issue_master", "pi_no", " where pr_no=".$_GET[$unique]."");
        $_SESSION['initiate_daily_production']=getSVALUE("production_floor_receive_detail", "custom_pr_no", " where ".$unique."=".$_GET[$unique]."");
        $type = 1;
        echo "<script>self.opener.location = 'daily_production.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['deleted']))
    {

            $crud = new crud($table_deatils);
            $condition =$unique."=".$$unique;
            $crud->delete_all($condition);

            $crud = new crud($table);
            $condition=$unique."=".$$unique;
            $crud->delete($condition);

            $crud = new crud($production_table_issue_master);
            $condition=$unique."=".$$unique;
            $crud->delete($condition);

            $crud = new crud($production_table_issue_detail);
            $condition =$unique."=".$$unique;
            $crud->delete_all($condition);

            $deleted_journal_item="Delete from ".$journal_item." where sr_no=".$$unique." and tr_from='Consumption'";
            $query=mysqli_query($conn, $deleted_journal_item);

            $deleted_journal="Delete from ".$journal_accounts." where tr_no=".$$unique." and tr_from in ('Consumption','Production')";
            $queryj=mysqli_query($conn, $deleted_journal);



            unset($_SESSION['ps_id']);
            unset($_SESSION['pi_id']);
            unset($_SESSION['initiate_daily_production']);
            unset($_POST);
            unset($$unique);

        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
	
	
if(isset($_POST[viewreport])){
$resultss="Select 
p.pr_no,
p.pr_no as 	PS_NO,
p.pr_date as PS_Date,
w.warehouse_name as 'Warehouse / CMU',	
(SELECT COUNT(item_id) from production_floor_receive_detail where pr_no=p.pr_no) as 'No. of FG',
u.fname as production_by,
p.entry_at as production_at,
p.status
from 
production_floor_receive_master p,
warehouse w,
user_activity_management u
where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and  
p.pr_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and 
p.warehouse_from=".$_POST[warehouse_from]."
order by p.pr_no DESC ";                                            
} else {
$resultss="Select 
p.pr_no,
p.pr_no as 	PS_NO,
p.pr_date as PS_Date,
w.warehouse_name as 'Warehouse / CMU',	
(SELECT COUNT(item_id) from production_floor_receive_detail where pr_no=p.pr_no) as 'No. of FG',
u.fname as production_by,
p.entry_at as production_at,
p.status
from 
production_floor_receive_master p,
warehouse w,
user_activity_management u
 where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and 
p.status='UNCHECKED'
order by p.pr_no DESC ";
}
$GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 280,top = -1");}
</script>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; }
?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th>SL</th>
                            <th>PS NO</th>
                            <th>FG Name</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align:center">Batch</th>
                            <th style="text-align:center">Type</th>
                            <th style="text-align:center">Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $rs="Select d.*,i.*
from 
production_floor_receive_detail d,
item_info i

 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$$unique."
 order by d.id";
                        $pdetails=mysqli_query($conn, $rs);
                        while($uncheckrow=mysqli_fetch_array($pdetails)){
                            ?>

                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td><?=$uncheckrow[custom_pr_no];?></td>
                                <td style="text-align:left"><?=$uncheckrow[item_name];?></td>
                                <td style="text-align:left"><?=$uncheckrow[unit_name];?></td>
                                <td align="center" style="width:10%; text-align:right"><?=$uncheckrow[batch];?></td>
                                <td align="center" style="width:10%; text-align:center"><?=$uncheckrow[p_type];?></td>
                                <td align="center" style="width:15%; text-align:center"><?=number_format($tunit=$uncheckrow[total_unit]/$uncheckrow[pack_size],0);?></td>
                            </tr>
                            <?php  $amountqty=$amountqty+$tunit;  } ?>
                        <tr style="font-weight: bold"><td colspan="6" style="text-align: right">Total Production = </td>
                            <td style="text-align: center"><?=$amountqty?></td>
                        </tr>
                        </tbody></table>



                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr>
                            <th style="vertical-align:middle">SL</th>
                            <th style="vertical-align:middle">PI NO</th>
                            <th style="vertical-align:middle">Code</th>
                            <th style="vertical-align:middle">Material Description</th>
                            <th style="text-align:center; vertical-align:middle">Unit</th>
                            <th style="text-align:center; vertical-align:middle">BOM</th>
                            <th style="text-align:center; vertical-align:middle">FG Production</th>
                            <th style="text-align:center; vertical-align:middle">Material Consumption</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $FG_res="select d.*,i.* from production_floor_receive_detail d, item_info i where d.p_type in ('Production')  and  i.item_id=d.item_id and  d.pr_no=".$_GET[$unique]." group by d.item_id";
                        $FG_query=mysqli_query($conn, $FG_res);
                        while($FG_ROW=mysqli_fetch_object($FG_query)){?>

                            <tr style="background-color: blanchedalmond">
<th colspan="8"><?=$FG_ROW->item_name;?></th>
                            </tr>
                            <?php

                        $rs="Select d.*,i.*
from 
production_floor_issue_detail d,
item_info i

 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$$unique." and
 fg_id='$FG_ROW->item_id'  order by d.id";
                        $pdetails=mysqli_query($conn, $rs);
                        while($uncheckrow=mysqli_fetch_array($pdetails)){
                            ?>

                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$jsi=$jsi+1;?></td>
                                <td><?=$uncheckrow[pi_no];?></td>
                                <td style="text-align:left"><?=$uncheckrow[item_id];?></td>
                                <td style="text-align:left"><?=$uncheckrow[item_name];?></td>
                                <td style="text-align:left"><?=$uncheckrow[unit_name];?></td>
                                <td align="center" style="width:10%; text-align:right"><?=$uncheckrow[BOM];?></td>
                                <td align="center" style="width:10%; text-align:right"><?=$uncheckrow[fg_unit_qty]/$FG_ROW->pack_size;?></td>
                                <td align="center" style="width:10%; text-align:center"><?=$uncheckrow[total_unit]/$uncheckrow[pack_size];?></td>
                            </tr>
                            <?php  $total_consumption=$total_consumption+($uncheckrow[total_unit]+$uncheckrow[pack_size]);  } ?>
                        <tr style="font-weight: bold"><td colspan="7" style="text-align: right">Material Consumption for (<?=$FG_ROW->item_name;?>) = </td>
                            <td style="text-align: center"><?=$total_consumption;?></td>
                        </tr>
                            <?php } ?>
                        <tr style="font-weight: bold"><td colspan="7" style="text-align: right">Total Consumption = </td>
                            <td style="text-align: center"><?=$total_consumption;?></td>
                        </tr>
                        </tbody></table>


<?php
if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='RETURNED'){
					if($entry_by==$_SESSION[userid]){ ?>
                         <p>
                        <button style="float: left; font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Re-process & Update</button>
                        <button style="float: right;font-size:12px" type="submit" name="deleted" id="deleted" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>DELETED</button>
                    </p>
                    <? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold; font-size:11px"><i>This production has been created by another user. So you are not able to do anything here!!</i></h6>';
					}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold; font-size:11px"><i>This production has been checked !!</i></h6>';}?>




                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
    <table align="center" style="width: 50%;">
        <tr><td>
                <input type="date"  style="width:150px; font-size: 11px;"  value="<?php if(isset($_POST[f_date])) { echo $_POST[f_date];} else {echo date('Y-m-01'); }?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
            <td style="width:10px; text-align:center"> -</td>
            <td><input type="date"  style="width:150px;font-size: 11px;"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date];} else {echo date('Y-m-d'); }?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
            <td style="width:10px; text-align:center"> -</td>
             <td><select  class="form-control" style="width: 200px;font-size:11px; vertical-align:middle" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                        <option selected></option>
                     <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_POST[warehouse_from]);?>
                 </select></td>
            <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Producion</button></td>
           
        </tr></table>
        <?=$crud->report_templates_with_status($resultss,"Production View");?>
                                </form>
    <?php } ?>

<?=$html->footer_content();?>