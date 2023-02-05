<?php
require_once ('support_file.php');
$sample_gift_recommended=find_a_field('requisition_sample_gift_master','count(oi_no)','status="PENDING" and recommended_by='.$_SESSION['PBI_ID']);
$sample_gift_authorise=find_a_field('requisition_sample_gift_master','count(oi_no)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);

$fg_purchased_recommended=find_a_field('purchase_fg_employee','count(oi_no)','status="PENDING" and recommended_by='.$_SESSION['PBI_ID']);
$fg_purchased_authorise=find_a_field('purchase_fg_employee','count(oi_no)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);

$stationary_purchased_checked = find_a_field('warehouse_other_receive','count(or_no)','status="UNCHECKED" and checked_by='.$_SESSION['PBI_ID']);


$APcheck = find_a_field('purchase_master','count(po_no)','status="UNCHECKED" and po_type="Asset" and checkby='.$_SESSION['PBI_ID']);
$APrecommended = find_a_field('purchase_master','count(po_no)','status="CHECKED" and po_type="Asset" and recommended='.$_SESSION['PBI_ID']);
$APathu = find_a_field('purchase_master','count(po_no)','status="recommended" and po_type="Asset" and authorise='.$_SESSION['PBI_ID']);



$workordercheck = find_a_field('purchase_master','count(po_no)','status="UNCHECKED" and po_type not in ("Asset") and checkby='.$_SESSION['PBI_ID']);
$workorderrecommended = find_a_field('purchase_master','count(po_no)','status="CHECKED" and po_type not in ("Asset") and recommended='.$_SESSION['PBI_ID']);
$workorderathu = find_a_field('purchase_master','count(po_no)','status="recommended" and po_type not in ("Asset") and authorise='.$_SESSION['PBI_ID']);


$unApprovedLeave = find_a_field('hrm_leave_info','count(id)','incharge_status="Pending" and half_or_full in ("Full") and PBI_IN_CHARGE='.$_SESSION['PBI_ID']);

$unAuthorisedLeave = find_a_field('hrm_leave_info','count(id)','incharge_status in ("Approve") and dept_head_status="Pending" and half_or_full in ("Full") and PBI_DEPT_HEAD='.$_SESSION['PBI_ID']);
$unAuthorisedearlyLeave = find_a_field('hrm_leave_info','count(id)','dept_head_status="Pending" and half_or_full in ("Half") and PBI_DEPT_HEAD='.$_SESSION['PBI_ID']);
$unAuthorisedLate = find_a_field('hrm_late_attendance','count(id)','status="Pending" and authorised_by='.$_SESSION['PBI_ID']);
$unAuthorisedOD = find_a_field('hrm_od_attendance','count(id)','status="PENDING" and authorised_by='.$_SESSION['PBI_ID']);
$unApprovedReq = find_a_field('warehouse_other_issue','count(oi_no)','status="PENDING" and req_category not in ("1500010000") and recommended_by='.$_SESSION['PBI_ID']);
$unApprovedReqFood = find_a_field('warehouse_other_issue','count(oi_no)','status="PENDING" and req_category in ("1500010000") and recommended_by='.$_SESSION['PBI_ID']);

$unAuthorisedReq = find_a_field('warehouse_other_issue','count(oi_no)','status="RECOMMENDED" and req_category not in ("1500010000") and authorised_person='.$_SESSION['PBI_ID']);
$unAuthorisedReqFood = find_a_field('warehouse_other_issue','count(oi_no)','status="RECOMMENDED" and req_category in ("1500010000") and authorised_person='.$_SESSION['PBI_ID']);

$unApprovedTravel = find_a_field('travel_application_master','count(trvApp_id)','status="PENDING" and approved_by='.$_SESSION['PBI_ID']);
$unAuthorisedTravel = find_a_field('travel_application_master','count(trvApp_id)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);


$unApprovedTravelExp = find_a_field('travel_application_claim_master','count(trvClaim_id)','status="UNCHECKED" and checked_by='.$_SESSION['PBI_ID']);
$unAuthorisedTravelExp = find_a_field('travel_application_claim_master','count(trvClaim_id)','status="CHECKED" and approved_by='.$_SESSION['PBI_ID']);

$unApprovedIOU = find_a_field('user_IOU','count(id)','status="UNCHECKED" and recommended_by='.$_SESSION['PBI_ID']);
$unAuthorisedIOU = find_a_field('user_IOU','count(id)','status="RECOMMENDED" and authorized_by='.$_SESSION['PBI_ID']);


$unApprovedVehicle = find_a_field('vehicle_application_master','count(vehApp_id)','status="PENDING" and approved_by='.$_SESSION['PBI_ID']);
$unAuthorisedVehicle = find_a_field('vehicle_application_master','count(vehApp_id)','status="RECOMMENDED" and authorised_person='.$_SESSION['PBI_ID']);
$unApprovedManpower = find_a_field('man_power_application','count(manPowerApp_id)','status="PENDING" and recommend_by='.$_SESSION['PBI_ID']);
$unAuthorisedManpower = find_a_field('man_power_application','count(manPowerApp_id)','status="RECOMMENDED" and authorise_by='.$_SESSION['PBI_ID']);
$unCHECKEDHandover = find_a_field('handover_application_details','count(id)','takeOver_status="PENDING" and takeOver_person='.$_SESSION['PBI_ID']);

$totChecked = $workordercheck+$stationary_purchased_checked+$unCHECKEDHandover+$APcheck;
$totApproval = $unApprovedLeave+$unApprovedReq+$unApprovedTravel+$unApprovedTravelExp+$unApprovedVehicle+$unApprovedManpower+$workorderrecommended+$sample_gift_recommended+$unApprovedReqFood+$fg_purchased_recommended+$unApprovedIOU+$APrecommended;
$totUnauthorised = $unAuthorisedLeave+$unAuthorisedReq+$unAuthorisedTravel+$unAuthorisedTravelExp+$unAuthorisedVehicle+$unAuthorisedManpower+$unAuthorisedLate+$workorderathu+$sample_gift_authorise+$unAuthorisedReqFood+$unAuthorisedearlyLeave+$fg_purchased_authorise+$unAuthorisedOD+$unAuthorisedIOU+$APathu;
$_SESSION[totCheckedemployee_access]=$totChecked+$totApproval+$totUnauthorised;

?>

 <!-- sidebar menu -->

              <div class="menu_section">
                <ul class="nav side-menu">
                    <li><a href="dashboard.php"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#"><i class="fa fa-clock-o"></i>Attendance <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="emp_acess_apply_for_leave.php">Apply for Leave</a></li>
                    <li><a href="emp_acess_apply_for_early_leave.php">Apply for Early Leave</a></li>
                    <li><a href="emp_acess_apply_for_late_attendance.php">Apply for Late Attendance</a></li>
                    <li><a href="emp_acess_apply_for_outdoor_duty.php">Apply for Outdoor Duty</a></li>
                    </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-wpforms"></i>Requisition <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="emp_access_requisition_stationary.php">Stationary Requisition</a></li>
                    <li><a href="hrm_requisition_food_beverage.php">Food & Beverage Requisition</a></li>
                    <li><a href="user_IOU_requisition.php">Create IOU</a></li>
                    <li><a href="emp_access_requisition_travel_exp_claim.php">Travel Exp. Claim</a></li>
                    <li><a href="emp_access_requisition_vehicle_application.php">Vehicle Application</a></li>
                    <li><a href="hrm_requisition_manpower_application.php">Man Power Application Form</a></li>                    
                    <li><a href="hrm_requisition_handover_takeover.php">Handover - Takeover</a></li>
                    <li><a href="hrm_requisition_sample_gift.php">Sample/Gift</a></li>
                    <li><a href="hrm_requisition_fg_purchase.php">FG Purchase</a></li>
                    <li><a href="emp_acess_requisition_task.php">Task Requisition</a></li>
                    </ul>
                    </li>
                        
                    <li><a href="#"><i class="fa fa-circle-o"></i>Un-Checked <?php if($totChecked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totChecked.' </span>]'?> <?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="hrm_unchecked_stationary_purchase.php">Stationary Purchase <?php if($stationary_purchased_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$stationary_purchased_checked.' </span>]'?> <?php } else {echo'';} ?></a></li>
                    <li><a href="emp_acess_unchecked_work_order.php">Work Order / PO <?php if($workordercheck>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$workordercheck.' </span>]'?> <?php } else {echo'';} ?></a></li>
                    <li><a href="emp_acess_asset_purchased_check.php">Asset Purchase <?php if($APcheck>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$APcheck.' </span>]'?> <?php } else {echo'';} ?></a></li>
                     <li><a href="hrm_unchecked_handover_takeover.php">Handover - Takeover <?php if($unCHECKEDHandover>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unCHECKEDHandover.' </span>]'?><?php } else {echo'';} ?></a></li>
                    </ul>
                    </li>
                    
                    <li><a href="#"><i class="fa fa-check-square-o"></i>Un-Approved <?php if($totApproval>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totApproval.' </span>]'?> <?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     <li><a href="emp_acess_unapproved_leave.php">Leave <?php if($unApprovedLeave>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedLeave.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_stationary.php">Stationary Requisition <?php if($unApprovedReq>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedReq.' </span>]'?> <?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_food_beverage.php">Food & Beverage <?php if($unApprovedReqFood>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedReqFood.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="user_unapproved_requisition_IOU.php">IOU Requisition<?php if($unApprovedIOU>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedIOU.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="emp_access_unapproved_requisition_travel_exp_claim.php">Travel Exp. Claim <?php if($unApprovedTravelExp>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedTravelExp.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_vehicle_application.php">Vehicle Application <?php if($unApprovedVehicle>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedVehicle.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_manpower_application.php">Man Power Application <?php if($unApprovedManpower>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unApprovedManpower.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_sample_gift.php">Sample/Gift <?php if($sample_gift_recommended>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$sample_gift_recommended.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_requisition_fg_purchase.php">FG Purchase <?php if($fg_purchased_recommended>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$fg_purchased_recommended.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unapproved_work_order.php">Work Order/P.O <?php if($workorderrecommended>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$workorderrecommended.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="emp_acess_asset_purchased_unapproved.php">Asset Purchased <?php if($APrecommended>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$APrecommended.' </span>]'?><?php } else {echo'';} ?></a></li>
                    </ul>
                    </li>
                    
                    <li><a href="#"><i class="fa fa-check"></i>Un-Authorised <?php if($totUnauthorised>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$totUnauthorised.' </span>]'?><?php } else {echo'';} ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="emp_acess_unauthorised_leave.php">Leave <?php if($unAuthorisedLeave>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedLeave.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_early_leave.php">Early Leave <?php if($unAuthorisedearlyLeave>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedearlyLeave.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_outdoor_duty.php">Outdoor Duty <?php if($unAuthorisedOD>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedOD.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_late_attendance.php">Late Attendance <?php if($unAuthorisedLate>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedLate.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_requisition_stationary.php">Stationary Requisition <?php if($unAuthorisedReq>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedReq.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_requisition_food_beverage.php">Food & Beverage <?php if($unAuthorisedReqFood>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedReqFood.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="user_unauthorised_requisition_IOU.php">IOU Requisition<?php if($unAuthorisedIOU>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedIOU.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="emp_access_unauthorized_requisition_travel_exp_claim.php">Travel Exp. Claim <?php if($unAuthorisedTravelExp>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedTravelExp.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_vehicle_application.php">Vehicle Application <?php if($unAuthorisedVehicle>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedVehicle.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_manpower_application.php">Man Power Application <?php if($unAuthorisedManpower>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$unAuthorisedManpower.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_requisition_sample_gift.php">Sample/Gift <?php if($sample_gift_authorise>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$sample_gift_authorise.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorised_requisition_fg_purchase.php">FG Purchase <?php if($fg_purchased_authorise>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$fg_purchased_authorise.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="hrm_unauthorized_work_order.php">Work Order/P.O <?php if($workorderathu>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$workorderathu.' </span>]'?><?php } else {echo'';} ?></a></li>
                    <li><a href="emp_acess_asset_purchased_unauthorized.php">Asset Purchased <?php if($APathu>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$APathu.' </span>]'?><?php } else {echo'';} ?></a></li>
                    </ul>
                    </li>



                    <li><a href="#"><i class="fa fa-database"></i>Report <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li><a href="hrm_requisition_leave_report.php">Leave</a></li>
                    <li><a href="hrm_requisition_early_leave_report.php">Early Leave</a></li>
                    <li><a href="hrm_requisition_late_attendance_report.php">Late Attendance</a></li>
                    <li><a href="emp_access_report_outdoor_duty.php">Outdoor Duty</a></li>
                    <li><a href="hrm_requisition_stationary_report.php">Stationary Requisition</a></li>
                    <li><a href="user_IOU_requisition_report.php">IOU Report</a></li>
                    <li><a href="hrm_requisition_food_beverage_report.php">Food & Beverage Requisition</a></li>
                    <li><a href="emp_access_report_requisition_travel_exp_claim.php">Travel Exp. Claim</a></li>
                    <li><a href="hrm_requisition_vehicle_application_report.php">Vehicle Application</a></li>
                    <li><a href="hrm_requisition_manpower_application_report.php">Man Power Application Form</a></li>                    
                    <li><a href="hrm_requisition_handover_takeover_report.php">Handover - Takeover</a></li>
                    <li><a href="hrm_requisition_sample_gift_report.php">Sample/Gift</a></li>
                    <li><a href="hrm_requisition_fg_purchase_report.php">FG Purchased</a></li>
                    <li><a href="hrm_requisition_purchase_order_report.php">Purchase Order</a></li>                    
                    <li><a href="emp_acess_field_force_report.php">Field Force Report</a></li>
                    </ul>
                    </li>
                </ul>                <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

              </div>
           
            
