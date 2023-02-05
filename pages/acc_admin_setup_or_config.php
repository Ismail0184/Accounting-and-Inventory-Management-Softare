<?php require_once 'support_file.php';
$page= basename($_SERVER['SCRIPT_NAME']);?>
<?=(check_permission_main_menu(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: page_403.php');
$page_id=find_a_field('dev_main_menu','main_menu_id','`url` LIKE "'.$page.'"');
$sub_menu_query = "
Select
				psm.sub_menu_id,
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
				psm.main_menu_id='".$page_id."' and
				dsm.module_id='".$_SESSION['module_id']."' and
				dsm.status=1 and psm.status=1
				order by dsm.sl
";
?>
<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php';?>

                    

<div class="x_panel">
    <div class="x_title">
        <h2><?=$page_id_GET=find_a_field('dev_main_menu','main_menu_name','`url` LIKE "'.$page.'"');?></h2>
        
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
    <?=$crud->get_submenu_under_mainmenu($sub_menu_query,$url_current,$link);?> 
    </div>
</div>


<?=$html->footer_content();?> 