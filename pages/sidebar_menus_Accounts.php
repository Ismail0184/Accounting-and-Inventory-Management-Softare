<?php
require_once ('support_file.php');
$candv = find_a_field('requisition_sample_gift_master', 'COUNT(oi_no)', 'status="Processing"');
$APAPPROVED = find_a_field('purchase_master_asset', 'COUNT(or_no)', 'status="APPROVED"');
$bankvoucher = find_a_field('secondary_journal_bank', 'COUNT(distinct jv_no)', 'status=""');
$stocktransfertoCMU = find_a_field('production_issue_master', 'COUNT(distinct custom_pi_no)', 'verifi_status="CHECKED" and status="ISSUE"');
$salaryUN = find_a_field('purchase_return_master', 'COUNT(distinct id)', 'status="ROCOMMENDED"');
$grnverifi = find_a_field('secondary_journal', 'COUNT(distinct tr_no)', 'checked="PENDING" and tr_from="Purchase"');
$accounts_conversion_charge = find_a_field('production_issue_master', 'COUNT(distinct pi_no)', 'verifi_status="processing" and status="SEND" and warehouse_from not in ("5","12","17","9")');
$veriri1cc = find_a_field('cycle_counting_master', 'COUNT(pr_no)', 'status="checked"');
$cctchecked = find_a_field('code_to_code_transfer', 'COUNT(ctct_id)', 'status="CHECKED"');
$stationary_purchased_checked_AC= find_a_field('warehouse_other_receive','count(or_no)','status="CHECKED"');
$accounts_sales_return_view= find_a_field('sale_return_master','count(do_no)','status="CHECKED"');
$accounts_inventory_return_view= find_a_field('purchase_return_master','count(id)','status="PROCESSING" and mushak_challan_status not in ("UNRECORDED")');
$acc_inventory_cycle_counting_check= find_a_field('acc_cycle_counting_master','count(cc_no)','status="CHECKED"');
$E_C_travel_expenses= find_a_field('travel_application_claim_master','count(trvClaim_id)','status="APPROVED" and accounts_viewed=""');
$external_receipt_voucher= find_a_field('receipt','count(id)','entry_status="UNCHECKED" and cr_amt>0 and received_from="External"');
$mushak_challan= find_a_field('sale_do_master','count(do_no)','mushak_challan_status="UNRECORDED" and status="COMPLETED"');
$special_invoice= find_a_field('sale_do_master','count(do_no)','status="UNCHECKED" and do_section="Special_invoice"');
$dailyProduction = 0;
$accounts_expenses_claim=$E_C_travel_expenses;
$SD_VAT_TAX=$mushak_challan;
$checkandverified_accounts=$special_invoice+$acc_inventory_cycle_counting_check+$external_receipt_voucher+$candv+$bankvoucher+$accounts_sales_return_view+$stocktransfertoCMU+$dailyProduction+$salaryUN+$grnverifi+$veriri1cc+$accounts_conversion_charge+$stationary_purchased_checked_AC+$accounts_inventory_return_view+$APAPPROVED;
$_SESSION['accounts_notication']=$checkandverified_accounts;
$module_id = @$_SESSION['module_id'];

?>

 <!-- sidebar menu -->

              <div class="menu_section">
                <h3></h3>
                <ul class="nav side-menu">
                <li><a href="dashboard.php"><i class="fa fa-home"></i>
                        <?php
                if($_SESSION['language']=='Bangla') {?>
                    হোম <?php } else if($_SESSION['language']=='English') {?> Home
                    <?php } ?>
                    </a>
                </li>

                <?php
                $result = mysqli_query($conn, "SET NAMES utf8");//the main trick
                if($_SESSION['language']=='Bangla') {
				$result="Select
				pmm.*,
				dmm.faicon as iconmain,
				dmm.main_menu_name_BN as main_menu_name,
				dmm.sl,
				dmm.url as main_url
				from
				user_permission_matrix_main_menu pmm,
				dev_main_menu dmm
				where
				pmm.main_menu_id=dmm.main_menu_id and
				pmm.user_id='".$_SESSION["userid"]."' and
				pmm.company_id='".$_SESSION['companyid']."'  and
				dmm.module_id='".$module_id."' and
				dmm.status=1 and pmm.status=1
				order by dmm.sl";
                } else if($_SESSION['language']=='English') {
                    $result = "Select
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
				pmm.user_id='" . $_SESSION["userid"] . "' and
				pmm.company_id='" . $_SESSION['companyid'] . "'  and
				dmm.module_id='".$module_id."' and
				dmm.status=1 and pmm.status=1
				order by dmm.sl";
                }

				$master_result=mysqli_query($conn, $result);
				while($mainrow=mysqli_fetch_object($master_result)):  ?>
                    <?php if($mainrow->main_menu_name!="Accounts Report"): ?>
                        <li><?php if($mainrow->main_url=='#'){ ?><a href="#"> <?php } else {?><a href="<?=$mainrow->main_url;?>"> <?php } ?><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name;?>
                        <?php if($mainrow->main_menu_id=="10014") if($checkandverified_accounts>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$checkandverified_accounts.'</span>]'?><?php  else : echo''; endif; ?>
                        <?php if($mainrow->main_menu_id=="10040") if($accounts_expenses_claim>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$accounts_expenses_claim.'</span>]'?><?php else : echo''; endif; ?>
                        <?php if($mainrow->main_menu_id=="10043") if($SD_VAT_TAX>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$SD_VAT_TAX.'</span>]'?><?php else : echo''; endif; ?>
               <?php if($mainrow->main_url=='#'){?><span class="fa fa-chevron-down"></span><?php } ?></a>
               <?php if($mainrow->main_url=='#'){ ?>
               <ul class="nav child_menu">
                <?php

                if($_SESSION['language']=='Bangla') {
                $result="Select
				psm.*,
				dsm.sub_menu_id,
				dsm.sub_menu_name_BN as sub_menu_name,
				dsm.sub_url
				from
				user_permission_matrix_sub_menu psm,
				dev_sub_menu dsm
				where
				dsm.sub_menu_id=psm.sub_menu_id and
				psm.user_id='".$_SESSION["userid"]."' and
				psm.company_id='".$_SESSION['companyid']."' and
				psm.main_menu_id='".$mainrow->main_menu_id."' and
				dsm.module_id='".$module_id."' and
				dsm.status=1 and psm.status=1
				order by dsm.sl";
                } else if($_SESSION['language']=='English') {
                    $result="Select
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
				dsm.module_id='".$module_id."' and
				dsm.status=1 and psm.status=1
				order by dsm.sl";
                }
				$sub_menu=mysqli_query($conn, $result);
				while($subnrow=mysqli_fetch_object($sub_menu)): ?>
                <li><a href="<?=$subnrow->sub_url;?>"><?=$subnrow->sub_menu_name;?>
                        <?php if($subnrow->sub_menu_id=="20023") if($grnverifi>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$grnverifi.' </span>]'?><?php  else : echo''; endif; ?>
                        <?php if($subnrow->sub_menu_id=="20032") if($bankvoucher>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$bankvoucher.' </span>]'?><?php else : echo''; endif; ?>
                        <?php if($subnrow->sub_menu_id=="20196") if($APAPPROVED>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$APAPPROVED.' </span>]'?><?php  else : echo''; endif; ?>
                        <?php if($subnrow->sub_menu_id=="20101") if($stationary_purchased_checked_AC>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$stationary_purchased_checked_AC.' </span>]'?><?php  else : echo''; endif; ?>
                        <?php if($subnrow->sub_menu_id=="20031") if($candv>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$candv.' </span>]'?><?php  else : echo''; endif;?>
                        <?php if($subnrow->sub_menu_id=="20033") if($accounts_sales_return_view>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$accounts_sales_return_view.' </span>]'?><?php else : echo'';endif; ?>
                        <?php if($subnrow->sub_menu_id=="20136") if($accounts_conversion_charge>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$accounts_conversion_charge.' </span>]'?><?php else : echo'';endif; ?>
                        <?php if($subnrow->sub_menu_id=="20082") if($accounts_inventory_return_view>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$accounts_inventory_return_view.' </span>]'?><?php else : echo'';endif; ?>
                        <?php if($subnrow->sub_menu_id=="20176") if($E_C_travel_expenses>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$E_C_travel_expenses.' </span>]'?><?php else : echo'';endif; ?>
                        <?php if($subnrow->sub_menu_id=="20218") if($external_receipt_voucher>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$external_receipt_voucher.' </span>]'?><?php else : echo'';endif; ?>
                        <?php if($subnrow->sub_menu_id=="20183") if($mushak_challan>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$mushak_challan.' </span>]'?><?php else : echo'';endif; ?>
                        <?php if($subnrow->sub_menu_id=="20179") if($acc_inventory_cycle_counting_check>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$acc_inventory_cycle_counting_check.' </span>]'?><?php else : echo'';endif; ?>
                        <?php if($subnrow->sub_menu_id=="20223") if($special_invoice>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$special_invoice.' </span>]'?><?php else : echo'';endif; ?>
                    </a>
                 </li>
                 <?php endwhile; ?></ul><?php } ?></li>
                    <?php else : ?>
                    <li><a href="<?=$mainrow->main_url;?>"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name?></a></li>
                    <?php endif; ?>
                 <?php endwhile; ?>
                </ul>
                  <p style="height: 300px"></p>
                </div>
