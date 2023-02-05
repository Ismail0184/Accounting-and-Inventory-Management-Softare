<?php
require_once ('support_file.php');

$mushak_challan= find_a_field('sale_do_master','count(do_no)','mushak_challan_status="UNRECORDED" and status="COMPLETED"');
$SD_VAT_TAX=$mushak_challan;
?>

 <!-- sidebar menu -->

              <div class="menu_section">
                <h3></h3>
                <ul class="nav side-menu">
                <li><a href="dashboard.php"><i class="fa fa-home"></i>Home</a></li>

                <?php
				$result="Select
				p.*,
				zm.faicon as iconmain,
				zm.zonename,
				zm.sl,
				zm.url
				from
				user_permissions p,
				zone_main zm
				where
				p.zonecode=zm.zonecode and
				p.user_id='".$_SESSION["userid"]."' and
				p.companyid='".$_SESSION['companyid']."'  and
				zm.module='".$_SESSION['module_id']."' and
				zm.status=1 and p.status=1
				order by zm.sl";
				$master_result=mysqli_query($conn, $result);
				while($mainrow=mysqli_fetch_object($master_result)):  ?>
                    <?php if($mainrow->zonename!="VAT Report"): ?>
                        <li><a href="#"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->zonename;?>
                        <?php if($mainrow->zonecode=="10043") if($SD_VAT_TAX>0) : ?><?='[<span style="color:red;font-weight:bold;">'.$SD_VAT_TAX.'</span>]'?><?php else : echo''; endif; ?>
                        <span class="fa fa-chevron-down"></span></a>
               <ul class="nav child_menu">
                <?php
				$zone2="Select
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
				ptow.zonecodemain='".$mainrow->zonecode."' and
				zs.module='".$_SESSION['module_id']."' and
				zs.status=1 and ptow.status=1
				order by zs.sl";
				$sub_menu=mysqli_query($conn, $zone2);
				while($subnrow=mysqli_fetch_object($sub_menu)): ?>
                <li><a href="<?=$subnrow->url;?>"><?=$subnrow->subzonename;?>
                      <?php if($subnrow->zonecode=="20183") if($mushak_challan>0) : ?><?='[<span style="color:red;font-weight:bold; font-size:15px"> '.$mushak_challan.' </span>]'?><?php else : echo'';endif; ?>
                    </a>
                 </li>
                 <?php endwhile; ?></ul></li>
                    <?php else : ?>
                    <li><a href="<?=$mainrow->url;?>"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->zonename?></a></li>
                    <?php endif; ?>
                 <?php endwhile; ?>
                </ul>
                <br /><br />
                </div>
