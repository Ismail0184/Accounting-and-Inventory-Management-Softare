<?php
 ob_start();
 session_start();
require_once ('support_file.php');
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }


$candv = find_a_field('requisition_sample_gift_master', 'COUNT(oi_no)', 'status="APPROVED"');
$bankvoucher = find_a_field('secondary_journal_bank', 'COUNT(distinct jv_no)', 'status=""');
$salesreturn = find_a_field('sale_return_master', 'COUNT(distinct sr_no)', 'status="CHECKED"');
$stocktransfertoCMU = find_a_field('production_issue_master', 'COUNT(distinct custom_pi_no)', 'verifi_status="CHECKED" and status="ISSUE"');
$salaryUN = find_a_field('purchase_return_master', 'COUNT(distinct id)', 'status="ROCOMMENDED"');
$grnverifi = find_a_field('secondary_journal', 'COUNT(distinct tr_no)', 'checked="PENDING" and tr_from="Purchase"');
$grnverifiqc = find_a_field('secondary_journal', 'COUNT(distinct tr_no)', 'checked="NO" and tr_from="Purchase"');
$stocehck = find_a_field('production_issue_master', 'COUNT(distinct pi_no)', 'verifi_status="processing" and status="SEND" and warehouse_from not in ("5","12","17","9")');
$veriri1cc = find_a_field('cycle_counting_master', 'COUNT(pr_no)', 'status="checked"');
$cctchecked = find_a_field('code_to_code_transfer', 'COUNT(ctct_id)', 'status="CHECKED"');
$stationary_purchased_checked_AC= find_a_field('warehouse_other_receive','count(or_no)','status="CHECKED"');
$checkandverified_accounts=$candv+$bankvoucher+$salesreturn+$stocktransfertoCMU+$dailyProduction+$salaryUN+$grnverifi+$veriri1cc+$stocehck+$stationary_purchased_checked_AC;
$_SESSION['accounts_notication']=$checkandverified_accounts;

$sample_gift_recommended=find_a_field('requisition_sample_gift_master','count(oi_no)','status="PENDING" and recommended_by='.$_SESSION['PBI_ID']);
$sample_gift_authorise=find_a_field('requisition_sample_gift_master','count(oi_no)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);

$fg_purchased_recommended=find_a_field('purchase_fg_employee','count(oi_no)','status="PENDING" and recommended_by='.$_SESSION['PBI_ID']);
$fg_purchased_authorise=find_a_field('purchase_fg_employee','count(oi_no)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);

$stationary_purchased_checked = find_a_field('warehouse_other_receive','count(or_no)','status="UNCHECKED" and checked_by='.$_SESSION['PBI_ID']);
$workordercheck = find_a_field('purchase_master','count(po_no)','status="UNCHECKED" and checkby='.$_SESSION['PBI_ID']);

$workorderrecommended = find_a_field('purchase_master','count(po_no)','status="CHECKED" and recommended='.$_SESSION['PBI_ID']);
$workorderathu = find_a_field('purchase_master','count(po_no)','status="recommended" and authorise='.$_SESSION['PBI_ID']);


$unAuthorisedLeave = find_a_field('hrm_leave_info','count(id)','dept_head_status="Pending" and half_or_full in ("Full") and PBI_DEPT_HEAD='.$_SESSION['PBI_ID']);
$unAuthorisedearlyLeave = find_a_field('hrm_leave_info','count(id)','dept_head_status="Pending" and half_or_full in ("Half") and PBI_DEPT_HEAD='.$_SESSION['PBI_ID']);
$unAuthorisedLate = find_a_field('hrm_late_attendance','count(id)','status="Pending" and authorised_by='.$_SESSION['PBI_ID']);
$unAuthorisedOD = find_a_field('hrm_od_attendance','count(id)','status="Pending" and authorised_by='.$_SESSION['PBI_ID']);
$unApprovedReq = find_a_field('warehouse_other_issue','count(oi_no)','status="PENDING" and req_category not in ("1500010000") and recommended_by='.$_SESSION['PBI_ID']);
$unApprovedReqFood = find_a_field('warehouse_other_issue','count(oi_no)','status="PENDING" and req_category in ("1500010000") and recommended_by='.$_SESSION['PBI_ID']);

$unAuthorisedReq = find_a_field('warehouse_other_issue','count(oi_no)','status="RECOMMENDED" and req_category not in ("1500010000") and authorised_person='.$_SESSION['PBI_ID']);
$unAuthorisedReqFood = find_a_field('warehouse_other_issue','count(oi_no)','status="RECOMMENDED" and req_category in ("1500010000") and authorised_person='.$_SESSION['PBI_ID']);

$unApprovedTravel = find_a_field('travel_application_master','count(trvApp_id)','status="PENDING" and approved_by='.$_SESSION['PBI_ID']);
$unAuthorisedTravel = find_a_field('travel_application_master','count(trvApp_id)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);


$unApprovedTravelExp = find_a_field('travel_application_claim_master','count(trvClaim_id)','status="PENDING" and approved_by='.$_SESSION['PBI_ID']);
$unAuthorisedTravelExp = find_a_field('travel_application_claim_master','count(trvClaim_id)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);

$unApprovedIOU = find_a_field('user_IOU','count(id)','status="UNCHECKED" and recommended_by='.$_SESSION['PBI_ID']);
$unAuthorisedIOU = find_a_field('user_IOU','count(id)','status="RECOMMENDED" and authorized_by='.$_SESSION['PBI_ID']);


$unApprovedVehicle = find_a_field('vehicle_application_master','count(vehApp_id)','status="PENDING" and approved_by='.$_SESSION['PBI_ID']);
$unAuthorisedVehicle = find_a_field('vehicle_application_master','count(vehApp_id)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);
$unApprovedManpower = find_a_field('man_power_application','count(manPowerApp_id)','status="PENDING" and recommend_by='.$_SESSION['PBI_ID']);
$unAuthorisedManpower = find_a_field('man_power_application','count(manPowerApp_id)','status="RECOMMENDED" and authorise_by='.$_SESSION['PBI_ID']);
$unCHECKEDHandover = find_a_field('handover_application_details','count(id)','takeOver_status="PENDING" and takeOver_person='.$_SESSION['PBI_ID']);

$totChecked = $workordercheck+$stationary_purchased_checked+$unCHECKEDHandover;
$totApproval = $unApprovedReq+$unApprovedTravel+$unApprovedTravelExp+$unApprovedVehicle+$unApprovedManpower+$workorderrecommended+$sample_gift_recommended+$unApprovedReqFood+$fg_purchased_recommended+$unApprovedIOU;
$totUnauthorised = $unAuthorisedLeave+$unAuthorisedReq+$unAuthorisedTravel+$unAuthorisedTravelExp+$unAuthorisedVehicle+$unAuthorisedManpower+$unAuthorisedLate+$workorderathu+$sample_gift_authorise+$unAuthorisedReqFood+$unAuthorisedearlyLeave+$fg_purchased_authorise+$unAuthorisedOD+$unAuthorisedIOU;
$_SESSION[totCheckedemployee_access]=$totChecked+$totApproval+$totUnauthorised;

$notViewedReq = find_a_field('warehouse_other_issue','count(oi_no)','user_viwed="NO" and status="APPROVED" and issued_to='.$_SESSION['PBI_ID']);
$notViewedTravel = find_a_field('travel_application_master','count(trvApp_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$notViewedVehicle = find_a_field('vehicle_application_master','count(vehApp_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$notViewedManpower = find_a_field('man_power_application','count(manPowerApp_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$notViewedHandover = find_a_field('handover_application_master','count(handOver_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$totNotViewed = $notViewedReq + $notViewedTravel + $notViewedVehicle + $notViewedManpower + $notViewedHandover;
?>

 <!-- sidebar menu -->

              <div class="menu_section">
                <h3></h3>
                <ul class="nav side-menu">
                <li><?php  if($_SESSION['module_id']==11)  { echo '<br></br>';} else {echo '<a href="dashboard.php"><i class="fa fa-home"></i>Home</a>';} ?></li>

                    <?php  if($_SESSION['module_id']==11){ ?>
                    <li><a href="#"><i class="fa fa-check"></i>Attendance <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="hrm_apply_for_leave.php">Apply for Leave</a></li>
                    <li><a href="hrm_apply_for_early_leave.php">Apply for Early Leave</a></li>
                    <li><a href="hrm_apply_for_late_attendance.php">Apply for Late Attendance</a></li>
                    <li><a href="hrm_apply_for_outdoor_duty.php">Apply for Outdoor Duty</a></li>
                    </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-check"></i>Requisition <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="hrm_requisition_stationary.php">Stationary Requisition</a></li>
                    <li><a href="hrm_requisition_food_beverage.php">Food & Beverage Requisition</a></li>
                    <li><a href="user_IOU_requisition.php">Create IOU</a></li>
                    <li><a href="hrm_requisition_travel_exp_claim.php">Travel Exp. Claim</a></li>
                    <li><a href="hrm_requisition_vehicle_application.php">Vehicle Application</a></li>
                    <li><a href="hrm_requisition_manpower_application.php">Man Power Application Form</a></li>                    
                    <li><a href="hrm_requisition_handover_takeover.php">Handover - Takeover</a></li>
                    <li><a href="hrm_requisition_sample_gift.php">Sample/Gift</a></li>
                    <li><a href="hrm_requisition_fg_purchase.php">FG Purchase</a></li>
                    </ul>
                    </li>
                        
                    <li><a href="#"><i class="fa fa-check"></i>Un-Checked List <?php if($totChecked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totChecked.' </span>]'?> <?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     <li><a href="hrm_unchecked_stationary_purchase.php">Stationary Purchase <?php if($stationary_purchased_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$stationary_purchased_checked.' </span>]'?> <?php } else {echo'';} ?></a></li>
                     <li><a href="hrm_unchecked_work_order.php">Work Order / PO <?php if($workordercheck>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$workordercheck.' </span>]'?> <?php } else {echo'';} ?></a></li>
                     <li><a href="hrm_unchecked_handover_takeover.php">Handover - Takeover <?php if($unCHECKEDHandover>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unCHECKEDHandover.' </span>]'?><?php } else {echo'';} ?></a></li>
                    </ul>
                    </li>
                    
                    <li><a href="#"><i class="fa fa-check"></i>Un-Approved List <?php if($totApproval>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totApproval.' </span>]'?> <?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="hrm_unapproved_requisition_stationary.php">Stationary Requisition <?php if($unApprovedReq>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedReq.' </span>]'?> <?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_food_beverage.php">Food & Beverage <?php if($unApprovedReqFood>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedReqFood.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="user_unapproved_requisition_IOU.php">IOU Requisition<?php if($unApprovedIOU>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedIOU.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_travel_exp_claim.php">Travel Exp. Claim <?php if($unApprovedTravelExp>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedTravelExp.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_vehicle_application.php">Vehicle Application <?php if($unApprovedVehicle>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedVehicle.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_manpower_application.php">Man Power Application <?php if($unApprovedManpower>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedManpower.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_sample_gift.php">Sample/Gift <?php if($sample_gift_recommended>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$sample_gift_recommended.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_fg_purchase.php">FG Purchase <?php if($fg_purchased_recommended>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$fg_purchased_recommended.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_work_order.php">Work Order/P.O <?php if($workorderrecommended>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$workorderrecommended.' </span>]'?><?php } else {echo'';} ?></a></li>
                    </ul>
                    </li>
                    
                    <li><a href="#"><i class="fa fa-check"></i>Un-Authorised List <?php if($totUnauthorised>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totUnauthorised.' </span>]'?><?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="hrm_unauthorised_leave.php">Leave <?php if($unAuthorisedLeave>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedLeave.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_early_leave.php">Early Leave <?php if($unAuthorisedearlyLeave>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedearlyLeave.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_outdoor_duty.php">Outdoor Duty <?php if($unAuthorisedOD>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedOD.' </span>]'?><?php } else {echo'';} ?></a></li>
                        <li><a href="hrm_unauthorised_late_attendance.php">Late Attendance <?php if($unAuthorisedLate>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedLate.' </span>]'?><?php } else {echo'';} ?></a></li>
                        <li><a href="hrm_unauthorised_requisition_stationary.php">Stationary Requisition <?php if($unAuthorisedReq>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedReq.' </span>]'?><?php } else {echo'';} ?></a></li>
                        <li><a href="hrm_unauthorised_requisition_food_beverage.php">Food & Beverage <?php if($unAuthorisedReqFood>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedReqFood.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="user_unauthorised_requisition_IOU.php">IOU Requisition<?php if($unAuthorisedIOU>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedIOU.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_requisition_travel_exp_claim.php">Travel Exp. Claim <?php if($unAuthorisedTravelExp>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedTravelExp.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_vehicle_application.php">Vehicle Application <?php if($unAuthorisedVehicle>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedVehicle.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_manpower_application.php">Man Power Application <?php if($unAuthorisedManpower>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedManpower.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_requisition_sample_gift.php">Sample/Gift <?php if($sample_gift_authorise>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$sample_gift_authorise.' </span>]'?><?php } else {echo'';} ?></a></li>
                        <li><a href="hrm_unauthorised_requisition_fg_purchase.php">FG Purchase <?php if($fg_purchased_authorise>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$fg_purchased_authorise.' </span>]'?><?php } else {echo'';} ?></a></li>
                        <li><a href="hrm_unauthorized_work_order.php">Work Order/P.O <?php if($workorderathu>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$workorderathu.' </span>]'?><?php } else {echo'';} ?></a></li>
                    
                    </ul>
                    </li>



                    <li><a href="#"><i class="fa fa-check"></i>Report <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="hrm_requisition_leave_report.php">Leave</a></li>
                        <li><a href="hrm_requisition_early_leave_report.php">Early Leave</a></li>
                        <li><a href="hrm_requisition_late_attendance_report.php">Late Attendance</a></li>
                    <li><a href="hrm_requisition_stationary_report.php">Stationary Requisition</a></li>
                    <li><a href="user_IOU_requisition_report.php">IOU Report</a></li>
                    <li><a href="hrm_requisition_food_beverage_report.php">Food & Beverage Requisition</a></li>
                    <li><a href="hrm_requisition_travel_exp_claim_report.php">Travel Exp. Claim</a></li>
                    <li><a href="hrm_requisition_vehicle_application_report.php">Vehicle Application</a></li>
                    <li><a href="hrm_requisition_manpower_application_report.php">Man Power Application Form</a></li>                    
                    <li><a href="hrm_requisition_handover_takeover_report.php">Handover - Takeover</a></li>
                    <li><a href="hrm_requisition_sample_gift_report.php">Sample/Gift</a></li>
                    <li><a href="hrm_requisition_fg_purchase_report.php">FG Purchased</a></li>
                    <li><a href="hrm_requisition_purchase_order_report.php">Purchase Order</a></li>
                    </ul>
                    </li>

                <?php } else {

				 $attendance_leave_pending=find_a_field('hrm_leave_info','COUNT(id)','leave_status="Waiting" and half_or_full in ("Full")');
                 $attendance_early_leave_pending=find_a_field('hrm_leave_info','COUNT(id)','leave_status="Waiting" and half_or_full in ("Half")');
                 $attendance_late_attendance_pending=find_a_field('hrm_late_attendance','COUNT(id)','status not in ("APPROVED")');
                 $attendance_OD_attendance_pending=find_a_field('hrm_od_attendance','COUNT(id)','status not in ("GRANTED")');
				 $total_attendance=$attendance_leave_pending+$attendance_early_leave_pending+$attendance_late_attendance_pending+$attendance_OD_attendance_pending;

				 $hrm_pending_stationary_requisition=find_a_field('warehouse_other_issue','COUNT(oi_no)','hrm_viewed="NO" and req_category not in ("1500010000")');
                 $hrm_pending_foodandbeverage_requisition=find_a_field('warehouse_other_issue','COUNT(oi_no)','hrm_viewed="NO" and req_category in ("1500010000")');
                 $hrm_pending_travel_exp_claim=find_a_field('travel_application_claim_master','COUNT(trvClaim_id)','hrm_viewed="NO"');
                 $hrm_pending_vehicle_application=find_a_field('vehicle_application_master','COUNT(vehApp_id)','hrm_viewed="NO"');
                 $hrm_pending_manpower_application=find_a_field('man_power_application','COUNT(manPowerApp_id)','hrm_viewed="NO"');
                 $hrm_pending_handover_takeover_application=find_a_field('handover_application_master','COUNT(handOver_id)','hrm_viewed="NO"');
                 $hrm_pending_sample_gift_application=find_a_field('requisition_sample_gift_master','COUNT(oi_no)','hrm_viewed="NO"');
                 $hrm_pending_FG_Purchased_Requisition=find_a_field('purchase_fg_employee','COUNT(oi_no)','hrm_viewed="NO"');
				 $hrm_pending_requisition_total=$hrm_pending_stationary_requisition+$hrm_pending_foodandbeverage_requisition+$hrm_pending_travel_exp_claim+$hrm_pending_vehicle_application+$hrm_pending_manpower_application+$hrm_pending_handover_takeover_application+$hrm_pending_sample_gift_application+$hrm_pending_FG_Purchased_Requisition;

                 $QC_production_checked=find_a_field('production_floor_receive_master','COUNT(pr_no)','status="UNCHECKED"');
                 $QC_sales_return_checked=find_a_field('sale_return_master','COUNT(do_no)','status="UNCHECKED"');
                 $QC_total_cehcked_and_verified=$QC_production_checked+$QC_sales_return_checked;

				    ?>
                
                <?php
				$result=mysql_query("Select 
				p.*,
				zm.faicon as iconmain,
				zm.zonename,
				zm.sl
				from 
				user_permissions p,
				zone_main zm
				where 
				p.zonecode=zm.zonecode and 
				p.user_id='".$_SESSION["userid"]."' and 
				p.companyid='".$_SESSION['companyid']."'  and 
				zm.module='".$_SESSION['module_id']."' and 
				zm.zonecode not in ('10014','10009')
				
				order by zm.sl");
				while($mainrow=mysql_fetch_object($result)){
					
					
					 ?>
					
					
				
                <li><a href="#"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->zonename;?>
                        <?php if($mainrow->zonecode=="10027") if($total_attendance>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$total_attendance.' </span>]'?><?php } else {echo'';} ?>
                        <?php if($mainrow->zonecode=="10029") if($hrm_pending_requisition_total>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_requisition_total.' </span>]'?><?php } else {echo'';} ?>
                        <?php if($mainrow->zonecode=="10005") if($QC_total_cehcked_and_verified>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$QC_total_cehcked_and_verified.' </span>]'?><?php } else {echo'';} ?>





                        <span class="fa fa-chevron-down"></span></a>
					
 <?php ////////////////////////////////////////////// sub menu////////////////////// ?>                   
                 <ul class="nav child_menu">
                <?php
				$zone2=mysql_query("Select 
				ptow.*,
				zs.zonecodesub,
				zs.zonename as subzonename,
				zs.url
				from 
				user_permissions2 ptow,
				zone_sub zs
				where 
				zs.zonecodesub=ptow.zonecode and 
				ptow.user_id='".$_SESSION["userid"]."' and 
				ptow.companyid='".$_SESSION['companyid']."' and 
				ptow.zonecodemain='".$mainrow->zonecode."' 
				order by zs.id");
				while($subnrow=mysql_fetch_object($zone2)){
					
					
					 ?> 
                 <li><a href="<?=$subnrow->url;?>"><?=$subnrow->subzonename;?>


                         <?php if($subnrow->zonecode=="20095") if($attendance_leave_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_leave_pending.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20096") if($attendance_early_leave_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_early_leave_pending.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20097") if($attendance_late_attendance_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_late_attendance_pending.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20098") if($attendance_OD_attendance_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_OD_attendance_pending.' </span>]'?><?php } else {echo'';} ?>

                         <?php if($subnrow->zonecode=="20103") if($hrm_pending_stationary_requisition>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_stationary_requisition.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20104") if($hrm_pending_foodandbeverage_requisition>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_foodandbeverage_requisition.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20105") if($hrm_pending_travel_exp_claim>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_travel_exp_claim.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20106") if($hrm_pending_vehicle_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_vehicle_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20107") if($hrm_pending_manpower_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_manpower_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20108") if($hrm_pending_handover_takeover_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_handover_takeover_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20109") if($hrm_pending_sample_gift_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_sample_gift_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20110") if($hrm_pending_FG_Purchased_Requisition>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_FG_Purchased_Requisition.' </span>]'?><?php } else {echo'';} ?>

                         <?php if($subnrow->zonecode=="20005") if($QC_production_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$QC_production_checked.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->zonecode=="20157") if($QC_sales_return_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$QC_sales_return_checked.' </span>]'?><?php } else {echo'';} ?>

















                     </a>
                 
<?php ///////////////////////////////// sub sub menu/////////////////////////////////////// ?>



<ul class="nav child_menu">
                <?php
				$zone3=mysql_query("Select pthree.*, 
				zss.zonecodesubsub,
				zss.zonenamesubsub,
				zss.url,
				zss.sl
				from 
				user_permissions3 pthree,
				zone_subsub zss 
				where 
				zss.zonecodesubsub=pthree.zonecodesubsub and 
				pthree.userid='".$_SESSION["userid"]."' and 
				pthree.companyid='".$_SESSION['companyid']."' and 
				pthree.zonecodemain='".$mainrow->zonecode."' and 
				pthree.zonecodesub='".$subnrow->zonecode."' 
				order by zss.sl");
				while($subsubnrow=mysql_fetch_object($zone3)){
					
					
					 ?> 
                 <li><a href="<?=$subsubnrow->url;?>"><?=$subsubnrow->zonenamesubsub;?></a></li>
                 <?php } ?>
                 </ul>
                 
                 </li>
                 <?php } ?>
                 </ul>   
                    
                    
                </li>    
				<?php } ?>



                    <?php  if($_SESSION['module_id']==3)  { ?>
                    <li><a href="#"><i class="fa fa-check"></i>Checked & Verified <?php if($totalPurchase>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totalPurchase.' </span>]'?><?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="grn.php">MAN <?php if($a>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$a.' </span>]'?><?php } else {echo'';} ?></a></li>

                    </ul>
                    </li>

                        <?php } elseif($_SESSION['module_id']==5)  { ?>
                        <?php
                        $candv = find_a_field('requisition_sample_gift_master','COUNT(oi_no)','status="APPROVED"');
                        $bankvoucher = find_a_field('secondary_journal_bank','COUNT(distinct jv_no)','status=""');
                        $salesreturn = find_a_field('sale_return_master','COUNT(distinct sr_no)','status="CHECKED"');
                        $totalPurchase=$a+$b;
                        ?>
                            <li><a href="#"><i class="fa fa-check"></i>Checked & Verified <?php if($totalPurchase>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totalPurchase.' </span>]'?><?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="grn.php">MAN <?php if($a>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$a.' </span>]'?><?php } else {echo'';} ?></a></li>

                                </ul>
                            </li>

                        

                    <?php } elseif($_SESSION['module_id']==1) {  ?>

                        <li><a href="#"><i class="fa fa-check"></i>Checked & Verified [<font style="color:red; font-weight: bold"><?=$checkandverified_accounts;?></font>]<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php
                                $zone2=mysql_query("Select 
				ptow.*,
				zs.zonecodesub,
				zs.zonename as subzonename,
				zs.url
				from 
				user_permissions2 ptow,
				zone_sub zs
				where 
				zs.zonecodesub=ptow.zonecode and 
				ptow.user_id='".$_SESSION["userid"]."' and 
				ptow.companyid='".$_SESSION['companyid']."' and 
				ptow.zonecodemain in ('10014') and 
				zs.module=".$_SESSION['module_id']."				
				order by zs.zonecodesub");
                                while($subnrows=mysql_fetch_object($zone2)){ ?>
                                    <li><a href="<?=$subnrows->url;?>"><?=$subnrows->subzonename;?>
                                            <?php if($subnrows->zonecode=="20023") if($grnverifi>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$grnverifi.' </span>]'?><?php } else {echo'';} ?>
                                            <?php if($subnrows->zonecode=="20032") if($bankvoucher>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$bankvoucher.' </span>]'?><?php } else {echo'';} ?>
                                            <?php if($subnrows->zonecode=="20101") if($stationary_purchased_checked_AC>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$stationary_purchased_checked_AC.' </span>]'?><?php } else {echo'';} ?>
                                            <?php if($subnrows->zonecode=="20031") if($candv>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$candv.' </span>]'?><?php } else {echo'';} ?>












                                        </a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <li><a href="#"><i class="fa fa-indent"></i>Setup<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php
                            $zone2=mysql_query("Select 
				ptow.*,
				zs.zonecodesub,
				zs.zonename as subzonename,
				zs.url
				from 
				user_permissions2 ptow,
				zone_sub zs
				where 
				zs.zonecodesub=ptow.zonecode and 
				ptow.user_id='".$_SESSION["userid"]."' and 
				ptow.companyid='".$_SESSION['companyid']."' and 
				ptow.zonecodemain in ('10019') and 
				zs.module=".$_SESSION['module_id']."
				order by zs.id");
                            while($subnrows=mysql_fetch_object($zone2)){ ?>
                                <li><a href="<?=$subnrows->url;?>"><?=$subnrows->subzonename;?></a></li>
                            <?php } ?>
                        </ul>
                    </li>

                    <li><a href="#"><i class="fa fa-indent"></i>Report<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php
                            $zone2=mysql_query("Select 
				ptow.*,
				zs.zonecodesub,
				zs.zonename as subzonename,
				zs.url
				from 
				user_permissions2 ptow,
				zone_sub zs
				where 
				zs.zonecodesub=ptow.zonecode and 
				ptow.user_id='".$_SESSION["userid"]."' and 
				ptow.companyid='".$_SESSION['companyid']."' and 
				ptow.zonecodemain in ('10009') and 
				zs.module=".$_SESSION['module_id']."
				
				order by zs.id");
                            while($subnrows=mysql_fetch_object($zone2)){ ?>
                                <li><a href="<?=$subnrows->url;?>"><?=$subnrows->subzonename;?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    
                    <?php } ?>
                
                </ul>
               
      
                </ul>
                <br /><br /><br />
                <p align="center" style="color:#F00">Your IP: <?php echo $ip; ?></p>
              </div>
           
            
