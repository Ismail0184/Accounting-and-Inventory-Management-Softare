<?php
require_once 'support_file.php';
$title='Material Issued Report';
$now=time();
$unique='pi_no';
$unique_field='name';
$table="production_issue_master";
$table_deatils="production_issue_detail";
$production_table_issue_master="production_floor_issue_master";
$production_table_issue_detail="production_floor_issue_detail";
$journal_item="journal_item";
$journal_accounts="journal";
$page='material_issue_to_CMU_view.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(prevent_multi_submit()){

    if (isset($_POST['reprocess'])) {
        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_issue_pi_no'] = $_GET[$unique];
        $_SESSION['pi_id']=find_a_field("production_floor_issue_master", "pi_no", "pr_no=".$_GET[$unique]."");
        $_SESSION['initiate_issue_to_CMU']=find_a_field("".$table."", "custom_pi_no", "".$unique."=".$_GET[$unique]."");
        $type = 1;
        echo "<script>self.opener.location = 'rmpmissuetoCMU.php'; self.blur(); </script>";
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
p.pi_no,
p.pi_no,
p.custom_pi_no as 	Custom_no,
p.pi_date as Date,
w.warehouse_name as 'transferred from',	
wto.warehouse_name as 'transferred to',	
u.fname as Entry_by,
p.entry_at as Entry_at,
p.verifi_status as status
from 
production_issue_master p,
warehouse w,
warehouse wto,
users u
where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and  
p.pi_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and 
p.warehouse_from=".$_POST[warehouse_from]." and ISSUE_TYPE='ISSUE' and 
p.warehouse_to=wto.warehouse_id
order by p.pi_no DESC ";
} else {
    $resultss="Select 
p.pi_no,
p.pi_no,
p.custom_pi_no as 	Custom_no,
p.pi_date as Date,
w.warehouse_name as 'transferred from',	
wto.warehouse_name as 'transferred to',	
u.fname as Entry_by,
p.entry_at as Entry_at,
p.verifi_status as status
from 
production_issue_master p,
warehouse w,
warehouse wto,
users u
where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and  
p.verifi_status in ('UNCHECKED','MANUAL','RETURNED') and ISSUE_TYPE='ISSUE' and  
p.warehouse_to=wto.warehouse_id
order by p.pi_no DESC ";
}
$GET_status=find_a_field(''.$table.'','verifi_status',''.$unique.'='.$_GET[$unique]);
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
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th>SL</th>
                            <th>PI NO</th>
                            <th>Material Name</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align:center">Batch</th>
                            <th style="text-align:center">Lot</th>
                            <th style="text-align:center">MFG</th>
                            <th style="text-align:center">Total Unit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rs="Select d.*,i.*
from 
".$table_deatils." d,
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
                                <td><?=$uncheckrow[custom_pi_no];?></td>
                                <td style="text-align:left"><?=$uncheckrow[item_name];?></td>
                                <td style="text-align:left"><?=$uncheckrow[unit_name];?></td>
                                <td align="center" style="width:10%; text-align:right"><?=$uncheckrow[batch];?></td>
                                <td align="center" style="width:10%; text-align:center"><?=$uncheckrow[lot];?></td>
                                <td align="center" style="width:10%; text-align:center"><?=$uncheckrow[mfg];?></td>
                                <td align="center" style="width:15%; text-align:center"><?=number_format($tunit=$uncheckrow[total_unit]/$uncheckrow[pack_size],0);?></td>
                            </tr>
                            <?php  $amountqty=$amountqty+$tunit;  } ?>
                        </tbody></table>




                    <?php
                    if($GET_status=='UNCHECKED' || $GET_status=='Manual' || $GET_status=='RETURNED'){
                        if($entry_by==$_SESSION[userid]){ ?>
                            <p>
                                <button style="float: left; font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Re-process to modify</button>
                                <button style="float: right;font-size:12px" type="submit" name="deleted" id="deleted" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Delete the Issued</button>
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
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) { echo $_POST[f_date];} else {echo date('Y-m-01'); }?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date];} else {echo date('Y-m-d'); }?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td><select  class="form-control" style="width: 200px; height:25px; font-size:11px; vertical-align:middle" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                        <option selected></option>
                        <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
                        advance_foreign_relation($sql_plant,$_POST[warehouse_from]);?>
                    </select></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Issued Report</button></td>

            </tr></table>
        <?=$crud->report_templates_with_status($resultss,"Material Issued View");?>
    </form>
<?php } ?>

<?php require_once 'footer_content.php' ?>