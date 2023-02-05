<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Pending Travel Exp. Claim";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=date('Y-m-d H:i:s');

$now=time();
$unique='trvClaim_id';
$unique_field='application_date';
$table="travel_application_claim_master";
$table_details="travel_application_claim_details";
$dataS=find_all_field("".$table."","","".$unique."=".$_GET[$unique]."");
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$current_status_HR=find_a_field("".$table."","hrm_viewed","".$unique."=".$_GET[$unique]."");
$required_status="APPROVED";
$authorised_status="YES";
$page="hrm_pending_requisition_travel_exp_claim.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if(isset($_POST['return']))
    {
        $_POST['status']='RETURNED';
        $_POST['return_comments']=$_POST['return_comments'];
        $_POST['recommended_date']=$todayss;
        $crud->update($unique);
        $type=1;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

if(isset($_POST[authorised])){
$res=mysqli_query($conn, 'Select td.* from '.$table_details.' td
where 			  
td.'.$unique.'='.$_GET[$unique].'');
    while($req_data=mysqli_fetch_object($res)){
        $transport_fair_rqst=$_POST['transport_fair_rqst_'.$req_data->id];
        $lodging_fair_rqst=$_POST['lodging_fair_rqst_'.$req_data->id];
        $breakfast_rqst=$_POST['breakfast_rqst_'.$req_data->id];
        $lunch_rqst=$_POST['lunch_rqst_'.$req_data->id];
        $dinner_rqst=$_POST['dinner_rqst_'.$req_data->id];
        $total_amount=$transport_fair_rqst+$lodging_fair_rqst+$breakfast_rqst+$lunch_rqst+$dinner_rqst;
        $updt=$_POST['updt'.$req_data->id];
mysqli_query($conn, "Update ".$table_details." SET transport_fair='".$transport_fair_rqst."',lodging_fair='".$lodging_fair_rqst."',breakfast='".$breakfast_rqst."',
lunch='".$lunch_rqst."',dinner='".$dinner_rqst."',total_amount='".$total_amount."'
where ".$unique."=".$_GET[$unique]." and id=".$req_data->id.""); 
} 

if(isset($updt)){
mysqli_query($conn, "Update ".$table_details." SET transport_fair='".$transport_fair_rqst."',lodging_fair='".$lodging_fair_rqst."',breakfast='".$breakfast_rqst."',
lunch='".$lunch_rqst."',dinner='".$dinner_rqst."',total_amount='".$total_amount."'
where ".$unique."=".$_GET[$unique]." and id=".$req_data->id."");
}}

} // end of multi submit


if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$res=mysqli_query($conn, 'Select td.* from '.$table_details.' td where td.'.$unique.'='.$_GET[$unique].'');?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=1200,height=500,left = 100,top = -1");}
</script>
<?php if(isset($_GET[$unique])){
    require_once 'body_content_without_menu.php';
} else {
    require_once 'body_content.php';
} ?>
<?php if(isset($_GET[$unique])): ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="text-align:center; vertical-align:middle">Date</th>
                            <th style="text-align:center; vertical-align:middle">Place/location<br />(from - to)</th>
                            <th style="text-align:center; vertical-align:middle">Mode of Transport</th>
                            <th style="text-align:center; vertical-align:middle">Transport<br>Cost</th>
                            <th style="text-align:center; vertical-align:middle">Lodging Expense <br /> (Details - Cost)</th>
                            <th style="text-align:center; vertical-align:middle">Breakfast</th>
                            <th style="text-align:center; vertical-align:middle">Lunch</th>
                            <th style="text-align:center; vertical-align:middle">Dinner</th>
                            <th style="text-align:center; vertical-align:middle">Total</th>
                            <th style="text-align:center; vertical-align:middle">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            
                        <?php while($req_data=mysqli_fetch_object($res)):?>
                            <tr>
                                <td style="text-align: center; vertical-align:middle"><?=$req_data->travel_date;?></td>
                                <td style="vertical-align:middle"><?=$req_data->travel_from;?> - <?=$req_data->travel_to;?></td>
                                <td style="text-align: center;vertical-align:middle"><?=$req_data->mode_of_transport;?> </td>
                                <td style="text-align: center;vertical-align:middle"><input type="text" name="transport_fair_rqst_<?=$req_data->id;?>" id="transport_fair_rqst_<?=$req_data->id;?>" value="<?=$req_data->transport_fair_rqst;?>" style="width: 80px; text-align: right;" autocomplete="off"></td>
                                <td style="text-align: center;vertical-align:middle"><?=$req_data->lodging_expense;?> - <input type="text" name="lodging_fair_rqst_<?=$req_data->id;?>" id="lodging_fair_rqst_<?=$req_data->id;?>" value="<?=$req_data->lodging_fair_rqst;?>" style="width: 80px" autocomplete="off"></td>
                                <td style="text-align: center;vertical-align:middle"><input type="text" name="breakfast_rqst_<?=$req_data->id;?>" id="breakfast_rqst_<?=$req_data->id;?>" value="<?=$req_data->breakfast_rqst;?>" style="width:80px; text-align: center" autocomplete="off" /></td>
                                <td style="text-align: center;vertical-align:middle"><input type="text" name="lunch_rqst_<?=$req_data->id;?>" id="lunch_rqst_<?=$req_data->id;?>" value="<?=$req_data->lunch_rqst;?>" style="width:80px; text-align: center" autocomplete="off" /></td>
                                <td style="text-align: center;vertical-align:middle"><input type="text" name="dinner_rqst_<?=$req_data->id;?>" id="dinner_rqst_<?=$req_data->id;?>" value="<?=$req_data->dinner_rqst;?>" style="width:80px; text-align: center" autocomplete="off" /></td>
                                <td style="text-align: center;vertical-align:middle"><?=$req_data->total_amount;?></td>
                                <td style="text-align:center;vertical-align:middle">
                                <?php if($current_status!=$required_status){ echo 'Pending';} else { ?>
                                <button type="submit" onclick='return window.confirm("Are you confirm?");' name="updt<?=$req_data->id;?>" id="updt<?=$req_data->id;?>" style="background-color:transparent; border:none"><img src="update.jpg" style="width:15px;  height:15px" title="Update the expense info"></button><?php } ?>
                                </td>
                            </tr>
                        <?php $totalamount=$totalamount+$req_data->total_amount; endwhile; ?>
                        <tr style="font-weight: bold; font-size: 12px">
                        <td colspan="8" style="text-align: right">Total Amount = </td><td style="text-align: right"><?=number_format($totalamount,2);?></td>
                        <td></td>
                        </tr>
                        <?php if($dataS->advance_amount>0): ?>
                        <tr style="font-weight: bold; font-size: 12px">
                        <td colspan="8" style="text-align: right">Advance Amount</td><td style="text-align: right"><?=number_format($dataS->advance_amount,2);?></td><td></td>
                        </tr>
                        <tr style="font-weight: bold; font-size: 12px">
                        <td colspan="8" style="text-align: right">Payable Amount = </td><td style="text-align: right"><?=number_format($totalamount-$dataS->advance_amount,2);?></td><td></td>
                        </tr><?php endif; ?>
                        </tbody>
                    </table>

                    <?php
                    if(isset($_POST[authorised])){
                        mysqli_query($conn, "Update ".$table." SET hrm_viewed='".$authorised_status."',hrm_viewed_date='$todayss' where ".$unique."=".$_GET[$unique]."");
                        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                        echo "<script>window.close(); </script>";
                    }
                    ?>
    <?php if($current_status_HR==$authorised_status){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This claim has been completed !!</i></h6>';} else { ?>
        <?php if($current_status!=$required_status){ echo '<h6 style="text-align:center; color:red; font-weight:bold"><i>This claim has not yet been approved. Please wait until approval !!</i></h6>';} else { ?>
                                        <table style="width:100%;font-size:12px">
                                          <tr>
                                          <td><button type="submit" name="return" id="return" class="btn btn-danger" style='font-size:12px' onclick='return window.confirm("Are you confirm to Return?");'>Return to Initiator</button></td>
                                          <td><input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:166px; font-size:11px" placeholder="Remarks of the return" ></td>
                                          <td><button type="submit" onclick='return window.confirm("Are you confirm to Recommended the Requisition?");' style='font-size:12px; float:right' name="authorised" id="authorised" class="btn btn-primary">Granted & Forward to Accounts</button></td>
                                        </tr>
                                    </table> 
                    <?php } }?>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>



<?php if(!isset($_GET[$unique])): ?>
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[f_date]) echo $_POST[f_date]; else echo date('Y-m-01');?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if($_POST[t_date]) echo $_POST[t_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Applications</button></td>
            </tr></table>
</form>

<?php 
if(isset($_POST[viewreport])):
$res='select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.' as date,r.travel_purpose,FORMAT(d.total_amount,2) as claimed_amount,FORMAT(r.advance_amount,2) as advance_amount,
concat((SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
           personnel_basic_info p2,
           department d,
           designation de 
            where 
            p2.PBI_ID=r.PBI_ID and
            p2.PBI_DESIGNATION=de.DESG_ID and  							 
            p2.PBI_DEPARTMENT=d.DEPT_ID),"<br> at: ",r.entry_at) as application_by,concat((select PBI_NAME from personnel_basic_info where PBI_ID=r.checked_by),"<br> at: ",r.checked_at) as checked_at,
            concat((select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by),"<br> at: ",r.approved_at) as approved_by,r.status
            
 from '.$table.' r,
 '.$table_details.' d
 WHERE 	
 r.'.$unique.'=d.'.$unique.'  
  group by '.$unique.' order by r.'.$unique.' DESC';

else :

$res='select r.'.$unique.',r.'.$unique.' as Req_No,r.'.$unique_field.' as date,r.travel_purpose,FORMAT(d.total_amount,2) as claimed_amount,FORMAT(r.advance_amount,2) as advance_amount,
concat((SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
           personnel_basic_info p2,
           department d,
           designation de 
            where 
            p2.PBI_ID=r.PBI_ID and
            p2.PBI_DESIGNATION=de.DESG_ID and  							 
            p2.PBI_DEPARTMENT=d.DEPT_ID),"<br> at: ",r.entry_at) as application_by,concat((select PBI_NAME from personnel_basic_info where PBI_ID=r.checked_by),"<br> at: ",r.checked_at) as checked_at,
            concat((select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by),"<br> at: ",r.approved_at) as approved_by,r.status
 from '.$table.' r,
 '.$table_details.' d
 WHERE 
 r.status="APPROVED" and	
 r.'.$unique.'=d.'.$unique.'  
  group by '.$unique.' order by r.'.$unique.' DESC';
endif;
echo $crud->report_templates_with_status($res,$title);?>
<?php endif;?>    
<?=$html->footer_content();mysqli_close($conn);?>