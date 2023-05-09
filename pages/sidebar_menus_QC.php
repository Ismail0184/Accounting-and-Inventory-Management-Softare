<?php
require_once ('support_file.php');
$QC_production_checked=find_a_field('production_floor_receive_master','COUNT(pr_no)','status="UNCHECKED"');
$QC_sales_return_checked=find_a_field('sale_return_master','COUNT(do_no)','status="UNCHECKED"');
$QC_MAN_checked=find_a_field('MAN_master','COUNT(MAN_ID)','status="UNCHECKED"');
$QC_STO_checked=find_a_field("production_issue_master","COUNT(pi_no)","verifi_status='UNCHECKED' and section_id=".$_SESSION['sectionid']." and company_id=".$_SESSION['companyid']."");
$QC_GRN_checked=find_a_field("purchase_receive","COUNT(distinct pr_no)","status in ('UNCHECKED') and section_id=".$_SESSION['sectionid']." and company_id=".$_SESSION['companyid']."");
$QC_Cycle_counting_checked=find_a_field('acc_cycle_counting_master','COUNT(cc_no)','status in ("UNCHECKED")');
$QC_CC_transfer_checked=find_a_field('code_to_code_transfer','COUNT(distinct ctct_id)','status in ("UNCHECKED","MANUAL")');
$QC_material_issued_to_CMU=find_a_field('production_issue_master','COUNT(distinct pi_no)','verifi_status in ("UNCHECKED")');
$QC_inventory_returned=find_a_field('purchase_return_master','COUNT(id)','status in ("UNCHECKED")');
$QC_LC_received=find_a_field('lc_lc_received','COUNT(distinct lcr_no)','status in ("UNCHECKED")');
$QC_total_cehcked_and_verified=$QC_production_checked+$QC_sales_return_checked+$QC_MAN_checked+$QC_STO_checked+$QC_GRN_checked+$QC_Cycle_counting_checked+$QC_CC_transfer_checked+$QC_inventory_returned;
?>





<div class="menu_section">
    <ul class="nav side-menu">
        <li><a href="dashboard.php"><i class="fa fa-home"></i>Home</a></li>
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
		dmm.module_id='".$_SESSION['module_id']."' and
		dmm.main_menu_id not in ('10052','10005') and
		dmm.status=1 and pmm.status=1
		order by dmm.sl";
        } else if($_SESSION['language']=='English') {
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
		dmm.main_menu_id not in ('10052','10005') and
		dmm.status=1 and pmm.status=1
		order by dmm.sl";

        }
        $master_result=mysqli_query($conn, $result);
        while($mainrow=mysqli_fetch_object($master_result)):?>
                <li><a href="#"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name;?>
                        <?php if($mainrow->main_menu_id=="10044") if($checkandverified>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$checkandverified.'</span>]'?><?php else : echo'';endif; ?>
                        <?php if($mainrow->main_url=='#'){?><span class="fa fa-chevron-down"></span><?php } ?></a>
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
					dsm.module_id='".$_SESSION['module_id']."' and
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
					dsm.module_id='".$_SESSION['module_id']."' and
					dsm.status=1 and psm.status=1
					order by dsm.sl";
                        }
                        $sub_menu=mysqli_query($conn, $result);
                        while($subnrow=mysqli_fetch_object($sub_menu)): ?>
                            <li><a href="<?=$subnrow->sub_url;?>"><?=$subnrow->sub_menu_name;?></a></li>
                        <?php endwhile; ?></ul></li>
        <?php endwhile; ?>


    <?php
$zone2="Select
psm.*,
dsm.sub_menu_id,
dsm.sub_menu_name,
dsm.sub_url,
dsm.faicon
from
user_permission_matrix_sub_menu psm,
dev_sub_menu dsm
where
dsm.sub_menu_id=psm.sub_menu_id and
psm.user_id='".$_SESSION["userid"]."' and
psm.company_id='".$_SESSION['companyid']."' and
dsm.module_id='".$_SESSION['module_id']."' and
dsm.status=1 and psm.status=1 and dsm.main_menu_id=0
order by dsm.sl";
$sub_menu=mysqli_query($conn, $zone2);
while($subnrow=mysqli_fetch_object($sub_menu)): ?>
    <li><a href="<?=$subnrow->sub_url;?>"><i class="<?=$subnrow->faicon;?>"></i><?=$subnrow->sub_menu_name;?>
    <?php if($subnrow->sub_menu_id=="20005") if($QC_production_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_production_checked.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20157") if($QC_sales_return_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_sales_return_checked.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20169") if($QC_MAN_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px">'.$QC_MAN_checked.'</span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20170") if($QC_STO_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_STO_checked.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20186") if($QC_GRN_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_GRN_checked.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20187") if($QC_Cycle_counting_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_Cycle_counting_checked.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20188") if($QC_CC_transfer_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_CC_transfer_checked.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20211") if($QC_material_issued_to_CMU>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_material_issued_to_CMU.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20193") if($QC_inventory_returned>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$QC_inventory_returned.' </span>]'?><?php } else {echo'';} ?>
    <?php if($subnrow->sub_menu_id=="20215") if($QC_LC_received>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px">'.$QC_LC_received.'</span>]'?><?php } else {echo'';} ?>
      </a>
   </li>
   <?php endwhile; ?>
      
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
				dmm.main_menu_id not in ('10068') and 
				dmm.status=1 and pmm.status=1
				order by dmm.sl";
        $master_result=mysqli_query($conn, $result);
        while($mainrow=mysqli_fetch_object($master_result)):  ?>
            <?php if($mainrow->main_menu_name!="Production Reports"): ?>
                <li><a href="<?=$mainrow->main_url;?>"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name;?>
                        <?php if($mainrow->main_menu_id=="10014") if($checkandverified_accounts>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$checkandverified_accounts.'</span>]'?><?php  else : echo''; endif; ?>
                        <?php if($mainrow->main_menu_id=="10040") if($accounts_expenses_claim>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$accounts_expenses_claim.'</span>]'?><?php else : echo''; endif; ?>
                        <?php if($mainrow->main_menu_id=="10043") if($SD_VAT_TAX>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$SD_VAT_TAX.'</span>]'?><?php else : echo''; endif; ?>

                        <?php if($mainrow->main_url=='#'):?><span class="fa fa-chevron-down"></span><?php endif; ?></a></li>
            <?php else : ?>
                <li><a href="<?=$mainrow->main_url;?>"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name?></a></li>
            <?php endif; ?>
        <?php endwhile; ?>
    </ul>



  <br /><br />
  </div>
