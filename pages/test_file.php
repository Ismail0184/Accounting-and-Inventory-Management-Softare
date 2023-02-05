<?php
require_once 'support_file.php';
$title='Blank';

$now=time();
$unique='id';
$unique_field='zonecodesub';
$table='zone_sub';
$page="module_create_sub.php";
$crud      =new crud($table);
//$$unique = $_GET[$unique];


    $jv_no=mysql_query("SELECT MAX(zonecodesub) AS MAXCODE FROM zone_sub where 1");

    $jv_noROW=mysql_fetch_array($jv_no);
        $zonecodeN=$jv_noROW[MAXCODE]+1;


$zonecodeNEXT=$zonecodeN;



if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

{

$$unique = $_POST[$unique];

if(isset($_POST['record']))
{
    $crud->insert();
    $type=1;
    $msg='New Entry Successfully Inserted.';
    unset($_POST);
    unset($$unique);

}}}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>

    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">

    <script type="text/javascript">
        function OpenPopupCenter(pageURL, title, w, h) {
            var left = (screen.width - w) / 2;
            var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
            var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }

    </script>
    <SCRIPT language=JavaScript>
        function reload2(form)
        {
            var val=form.zonecodemain.options[form.zonecodemain.options.selectedIndex].value;
            self.location='module_create_sub.php?zonecodemain=' + val ;
        }
    </script>
</head>



<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="<?php echo $webiste; ?>" class="site_title"><i class="fa fa-paw"></i> <span>ICPBD</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <?php include ("pro.php");  ?> <br />
                <!-- /menu profile quick info -->


                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <?php include("sidebar_menus.php"); ?>
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



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                                        </a-->
                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <?require_once 'support_html.php';?>
                                </form>

                            </div></div></div>
                    <!-- input section-->

<!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>List of <?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                                    <thead>

                                    </thead>

                                    <tbody>
                                    </tbody>


                                </table>
                            </div>

                        </div></div>
<!-------------------End of  List View --------------------->
                </div>
            </div>
        </div>
<!---page content----->







<?php require_once 'footer_content.php' ?>