<?php
 ob_start();
 session_start();
 require_once 'base.php';
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['user_id']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM register WHERE m_id=".$_SESSION['m_id']);
 $userRow=mysql_fetch_array($res);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <title>Batch Mates | News Feed </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="dashboard.php" class="site_title"><i class="fa fa-paw"></i> <span>Batch Mates</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <?php include ("pro.php");  ?>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <?php include("sidebar_menu.php"); ?>
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
        <div class="right_col" role="main" >
          <div class="">
            
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="x_panel" style="background-color:transparent; border:none">
                  
                  

                    
                    
                    <?php include("indexx.php");
					//include("like.php");
					
					 ?>
                    

                  
                </div>
              </div>

              
                    
                    
                   <?php include("batch_mates.php"); ?>
                   <?php include("advertisment.php"); ?>
				   <?php include ("event.php"); ?>
                   <?php include ("help.php"); ?>
                     
                    
                    


             </div>
</div>
    </div>

    <!-- jQuery -->
    <!--script src="../vendors/jquery/dist/jquery.min.js"></script--->
    <!-- Bootstrap -->
    <!--script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script--->
    <!-- FastClick -->
    <!--script src="../vendors/fastclick/lib/fastclick.js"></script--->
    <!-- NProgress -->
    <!--script src="../vendors/nprogress/nprogress.js"></script-->
    <!-- ECharts -->
    <!--script src="../vendors/echarts/dist/echarts.min.js"></script--->
    <!--script src="../vendors/echarts/map/js/world.js"></scrip---t>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    
  </body>
</html>
<?php ob_end_flush(); ?>