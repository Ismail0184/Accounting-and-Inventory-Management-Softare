<?php
require_once ('support_file.php');

$notViewedReq = find_a_field('warehouse_other_issue','count(oi_no)','user_viwed="NO" and status="APPROVED" and issued_to='.$_SESSION['PBI_ID']);
$notViewedTravel = find_a_field('travel_application_master','count(trvApp_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$notViewedVehicle = find_a_field('vehicle_application_master','count(vehApp_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$notViewedManpower = find_a_field('man_power_application','count(manPowerApp_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$notViewedHandover = find_a_field('handover_application_master','count(handOver_id)','user_viwed="NO" and status="APPROVED" and PBI_ID ='.$_SESSION['PBI_ID']);
$totNotViewed = $notViewedReq + $notViewedTravel + $notViewedVehicle + $notViewedManpower + $notViewedHandover;
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
?>

              <div class="menu_section">
                <h3></h3>
                <ul class="nav side-menu">
                <li><?php  if($_SESSION['module_id']==11)  { echo '<br></br>';} else {echo '<a href="dashboard.php"><i class="fa fa-home"></i>Home</a>';} ?></li>
<?php
				$result="Select
				pmm.*,
				dmm.faicon as iconmain,
				dmm.main_menu_name,
				dmm.sl,
				dmm.url as main_url
				from
				user_permission_matrix_main_menu pmm,
				dev_main_menu dmm
				where
				pmm.main_menu_id=dmm.main_menu_id and
				pmm.user_id='".$_SESSION["userid"]."' and
				pmm.company_id='".$_SESSION['companyid']."'  and
				dmm.module_id='".$_SESSION['module_id']."' and
				dmm.status=1 and pmm.status=1
				order by dmm.sl";
                $master_result=mysqli_query($conn, $result);
				while($mainrow=mysqli_fetch_object($master_result)):  ?>
                    <?php if($mainrow->main_menu_name!="HRM Report"): ?>
                        <li><a href="#"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name;?>
                        <?php if($mainrow->main_menu_id=="10027") if($total_attendance>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$total_attendance.' </span>]'?><?php } else {echo'';} ?>
                        <?php if($mainrow->main_menu_id=="10029") if($hrm_pending_requisition_total>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_requisition_total.' </span>]'?><?php } else {echo'';} ?>
                        <?php if($mainrow->main_url=='#'):?><span class="fa fa-chevron-down"></span><?php endif; ?></a>
					
                <ul class="nav child_menu">
                <?php
				$zone2="Select
				psm.*,
				dsm.sub_menu_id,
				dsm.sub_menu_name,
				dsm.sub_url
				from
				user_permission_matrix_sub_menu psm,
				dev_sub_menu dsm
				where
				dsm.sub_menu_id=psm.sub_menu_id and
				psm.user_id='".$_SESSION["userid"]."' and
				psm.company_id='".$_SESSION['companyid']."' and
				psm.main_menu_id='".$mainrow->main_menu_id."' and
				dsm.module_id='".$_SESSION['module_id']."' and
				dsm.status=1 and psm.status=1
				order by dsm.sl";
				$sub_menu=mysqli_query($conn, $zone2);
				while($subnrow=mysqli_fetch_object($sub_menu)): ?> 
                 <li><a href="<?=$subnrow->sub_url;?>"><?=$subnrow->sub_menu_name;?>
                         <?php if($subnrow->sub_menu_id=="20095") if($attendance_leave_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_leave_pending.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20096") if($attendance_early_leave_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_early_leave_pending.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20097") if($attendance_late_attendance_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_late_attendance_pending.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20098") if($attendance_OD_attendance_pending>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$attendance_OD_attendance_pending.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20103") if($hrm_pending_stationary_requisition>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_stationary_requisition.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20104") if($hrm_pending_foodandbeverage_requisition>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_foodandbeverage_requisition.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20105") if($hrm_pending_travel_exp_claim>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_travel_exp_claim.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20106") if($hrm_pending_vehicle_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_vehicle_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20107") if($hrm_pending_manpower_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_manpower_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20108") if($hrm_pending_handover_takeover_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_handover_takeover_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20109") if($hrm_pending_sample_gift_application>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_sample_gift_application.' </span>]'?><?php } else {echo'';} ?>
                         <?php if($subnrow->sub_menu_id=="20110") if($hrm_pending_FG_Purchased_Requisition>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$hrm_pending_FG_Purchased_Requisition.' </span>]'?><?php } else {echo'';} ?>
                         </a>
                 </li>
                 <?php endwhile; ?></ul></li>
                    <?php else : ?>
                    <li><a href="<?=$mainrow->main_url;?>"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name?></a></li>
                    <?php endif; ?>
                 <?php endwhile; ?>
                </ul>
                <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                </div>
