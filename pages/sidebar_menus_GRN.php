<?php
require_once ('support_file.php');
$MAN_checked=find_a_field('MAN_master','COUNT(id)','status="CHECKED"');
$check_and_verify=$MAN_checked;

?>


<div class="menu_section">
    <h3></h3>
    <ul class="nav side-menu">
        <li><a href="dashboard.php"><i class="fa fa-home"></i>Home</a></li>
        <?php
        $result=mysqli_query($conn, ("Select
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
		order by dmm.sl"));
        while($mainrow=mysqli_fetch_object($result)):?>
		<?php if($mainrow->main_menu_name!="GRN Reports"): ?>
            <li><a href="#"><i class="<?=$mainrow->iconmain;?>"></i><?=$mainrow->main_menu_name;?>
                    <?php if($mainrow->main_menu_id=="10047") if($check_and_verify>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:13px"> '.$check_and_verify.' </span>]'?><?php } else {echo'';} ?>
					<?php if($mainrow->main_url=='#'){?><span class="fa fa-chevron-down"></span><?php } ?></a>
                <ul class="nav child_menu">
                    <?php
                    $zone2=mysqli_query($conn, ("Select
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
					order by dsm.sl"));
                    while($subnrow=mysqli_fetch_object($zone2)): ?>
                        <li><a href="<?=$subnrow->sub_url;?>"><?=$subnrow->sub_menu_name;?>
                                <?php if($subnrow->sub_menu_id=="20206") if($MAN_checked>0) { ?><?='[<span style="color:red;font-weight:bold; font-size:14px"> '.$MAN_checked.' </span>]'?><?php } else {echo'';} ?>

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
