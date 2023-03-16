<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');

$title='Production Report';
$now=time();
$entry_at=date('Y-m-d H:s:i');
$unique='pr_no';
$unique_field='name';
$table="production_floor_receive_master";
$table_deatils="production_floor_receive_detail";

$production_table_issue_master="production_floor_issue_master";
$production_table_issue_detail="production_floor_issue_detail";
$journal_item="journal_item";
$journal_accounts="journal";
$page='QC_production_view.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        //echo "<script>self.opener.location = 'QC_sales_return_view.php'; self.blur(); <script>";
        echo "<script>window.close(); </script>";
    }



    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
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
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $uncheckrow[item_id];
            $_POST['warehouse_id'] = $uncheckrow[warehouse_from];
            $_POST['item_in'] = $uncheckrow[total_unit];
            $_POST['item_price'] = $uncheckrow[unit_price];
            $_POST['total_amt'] = $uncheckrow[total_amt];
            $_POST['lot_number'] = $uncheckrow[lot];
            $_POST['batch'] = $uncheckrow[batch];
            $_POST['tr_from'] = 'Production';
            $_POST['custom_no'] = $uncheckrow[custom_pr_no];
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['entry_at'] = $entry_at;
            $_POST['sr_no'] = $uncheckrow[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();
        }
        $up_master="UPDATE ".$table." SET status='CHECKED',qc_by=".$_SESSION[userid].",qc_at='$entry_at' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='CHECKED' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
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
users u
where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and  
p.pr_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' 

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
users u
 where
p.entry_by=u.user_id and 
w.warehouse_id=p.warehouse_from and 
p.status='UNCHECKED'
order by p.pr_no DESC ";
}
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
                            <th>SL</th>
                            <th>PI NO</th>
                            <th>Code</th>
                            <th>Material Description</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align:center">BOM</th>
                            <th style="text-align:center">FG Production</th>
                            <th style="text-align:center">Material Consumption</th>
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
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){  ?>
                    <p>
                        <button style="float: left; font-size:12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned</button>
                                <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                        <button style="float: right;font-size:12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward</button>
                    </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Production has been checked !!</i></h6>';}?>
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
            <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Producion</button></td>
        </tr></table>
        <?=$crud->report_templates($resultss,$link);?>                                
                                </form>
    <?php } ?>
    <?=$html->footer_content();mysqli_close($conn);?>