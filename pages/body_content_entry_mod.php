<body class="<?php if ($_GET) {?>nav-md<?php } else { ?>nav-sm<?php } ?>">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0; <?php  if($_SESSION['module_id']==11)  { ?>text-align: center <?php } else {echo'';} ?>">
                    <?php  if($_SESSION['module_id']==11)  { ?>
                        <a href="dashboard.php"> <img src="<?=$_SESSION['userpic'];?>" width="150px" height="140px" style=" margin-top: 10px;border: 1px solid red;
    border-radius: 25px; background-color:#069"></a>
                    <?php } else { ?>
                    <a href="dashboard.php" class="site_title">
                        <img src="../assets/images/icon/title.png" width="60px" height="50" title="Company Logo"> <span><?=$_SESSION['com_short_name']?></span>
                        <?php } ?></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <?php include ("pro.php");  ?> <br />
                <!-- /menu profile quick info -->


                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <?php include("sidebar_menus_entry_mod.php"); ?>
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