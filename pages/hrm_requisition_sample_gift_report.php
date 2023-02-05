 <?php
require_once 'support_file.php';
$title="Sample/Gift Requsition List";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$now=time();
$unique='oi_no';
$unique_field='oi_date';
$table="requisition_sample_gift_master";
$table_details="requisition_sample_gift_details";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="PENDING";
$page="hrm_requisition_sample_gift_report.php";
$target_page="hrm_requisition_sample_gift.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){  
    if(isset($_POST['reprocess'])){
        $_SESSION['initiate_hrm_sample_gift_requisition']=$$unique;
        echo "<script>self.opener.location = '$target_page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$results="Select d.*,i.*
                        from
                        ".$table_details." d,
                        item_info i
                        where
                        d.item_id=i.item_id and
                        d.".$unique."=".$$unique." order by d.id";
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>


<?php if(isset($_GET[$unique])):?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque; vertical-align: middle">
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Code</th>
                            <th style="vertical-align: middle">Finish Goods</th>
                            <th style="width:5%; text-align:center;vertical-align: middle">UOM</th>
                            <th style="text-align:center; vertical-align: middle">Order Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $query=mysqli_query($conn, $results);
                        while($data=mysqli_fetch_object($query)): ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$i=$i+1; ?></td>
                                <td style="vertical-align:middle"><?=$data->finish_goods_code;?></td>
                                <td style="vertical-align:middle;"><?=$data->item_name;?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$data->unit_name;?></td>
                                <td align="center" style=" text-align:center;vertical-align: middle"><?=$data->qty;?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody></table>
                    
                    
<?php $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);?>
<?php if($GET_status=='PENDING' || $GET_status=='MANUAL' || $GET_status=='CANCELED'){
if($entry_by==$_SESSION[userid]){ ?>
<p align="center">
<button style="font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Re-process?");'>Re-process the Requisition</button>
</p>
<? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Req. was created by another user. So you are not able to do anything here!!</i></h6>';
}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Req. has been checked !!</i></h6>';}?>
                    </form>
                        </div></div></div>
<?php endif; ?>
                  
<?php if(!isset($_GET[$unique])):
if(isset($_POST[viewreport])):	
$res='select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.' as Req_Date,r.oi_subject as purpose,
if(r.recommended_date!=" ",CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.recommended_by), "<br> at: ", r.recommended_date),CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.recommended_by), " - ", "<b>PENDING</b>")) as recommended_by,
if(r.authorized_date!=" ",CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person), "<br> at: ", r.recommended_date), CONCAT((select PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person), " - ", "<b>PENDING</b>")) as authorized_by, r.status 
from '.$table.' r  WHERE r.issued_to='.$_SESSION['PBI_ID'].'';endif?>
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Requisitions</button></td>
            </tr></table>
<?=$crud->report_templates_with_status($res,$title);?>
</form>
<?php endif; ?>         
<?=$html->footer_content();mysqli_close($conn);?>