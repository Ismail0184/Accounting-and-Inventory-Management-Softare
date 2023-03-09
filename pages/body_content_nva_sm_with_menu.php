<?php
$module_id = @$_SESSION['module_id'];
$module_name = @$_SESSION['module_name'];
if($module_id ==1):
    $sidebar_menus = 'sidebar_menus_Accounts.php';
elseif($module_id ==2):
    $sidebar_menus = 'sidebar_menus_LC.php';
elseif($module_id ==3):
    $sidebar_menus = 'sidebar_menus_Procurement.php';
elseif($module_id ==4):
    $sidebar_menus = 'sidebar_menus_GRN.php';
elseif($module_id ==5):
    $sidebar_menus = 'sidebar_menus_Production.php';
elseif($module_id ==6):
    $sidebar_menus = 'sidebar_menus_QC.php';
elseif($module_id ==7):
    $sidebar_menus = 'sidebar_menus_Warehouse.php';
elseif($module_id ==8):
    $sidebar_menus = 'sidebar_menus_Marketing.php';
elseif($module_id ==9):
    $sidebar_menus = 'sidebar_menus_Sales.php';
elseif($module_id ==10):
    $sidebar_menus = 'sidebar_menus_HRM.php';
elseif($module_id ==11):
    $sidebar_menus = 'sidebar_menus_EmployeeAccess.php';
elseif($module_id ==12):
    $sidebar_menus = 'sidebar_menus_MIS.php';
elseif($module_id ==13):
    $sidebar_menus = 'sidebar_menus_VMS.php';
elseif($module_id ==14):
    $sidebar_menus = 'sidebar_menus_Developer.php';
else:
    $sidebar_menus = 'sidebar_menu.php';
endif;
?>
<body class="nav-sm">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0; <?php  if($_SESSION['module_id']==11)  { ?>text-align: center <?php } else {echo 'text-align: center';} ?>">
                    <?php  if($module_id==11)  : ?>
                        <a href="dashboard.php"><img src="<?=$_SESSION['userpic'];?>" width="60" height="60" style=" margin-top: 10px;border: 1px solid <?=$_SESSION[logo_color]?>;
    border-radius: 25px; background-color:#069" title="Company Logo"></a>

                    <?php else: ?>
                    <a href="dashboard.php"><img src="../assets/images/icon/title.png" width="50" height="50" style=" margin-top: 10px;" title="Company Logo"></a><?php endif; ?>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <?php include ("pro.php");  ?>
                <!-- /menu profile quick info -->
                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <?php include("".$sidebar_menus.""); ?>
                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <?php include("menu_footer.php"); ?>
                <!-- /menu footer buttons -->
            </div>
        </div>


        <!-- top navigation -->
        <div class="top_nav">
            <?php include("top.php"); ?>
        </div>
        <!-- /top navigation -->


        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="row">