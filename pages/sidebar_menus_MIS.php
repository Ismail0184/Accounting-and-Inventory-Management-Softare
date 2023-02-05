<?php require_once ('support_file.php');?>
<div class="menu_section">
                <h3></h3>
                <ul class="nav side-menu">
                <li><a href="dashboard.php"><i class="fa fa-home"></i>Home</a></li>

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
                    <?php if($mainrow->zonename!="MIS Reports"): ?>
                        <li><a href="<?=$mainrow->main_url?>"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name;?>
                        <?php if($mainrow->url=='#'){?><span class="fa fa-chevron-down"></span><?php } ?></a>
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
                <li><a href="<?=$subnrow->sub_url;?>"><?=$subnrow->sub_menu_name;?></a>
                 </li>
                 <?php endwhile; ?></ul></li>
                    <?php else : ?>
                    <li><a href="<?=$mainrow->url;?>"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->zonename?></a></li>
                    <?php endif; ?>
                 <?php endwhile; ?>
                </ul>
                <br /><br />
                </div>

           
            

