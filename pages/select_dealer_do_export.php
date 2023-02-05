<?php
require_once 'support_file.php';
$title='Select Dealer for Export';

$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$unique_detail='id';
$table_chalan='sale_do_chalan';
$unique_chalan='id';
$$unique_master=$_POST[$unique_master];








if(isset($_POST['delete']))
{

    $crud   = new crud($table_master);
    $condition=$unique_master."=".$$unique_master;
    $crud->delete($condition);
    $crud   = new crud($table_detail);
    $crud->delete_all($condition);
    $crud   = new crud($table_chalan);
    $crud->delete_all($condition);
    unset($$unique_master);
    unset($_POST[$unique_master]);
    unset($_SESSION['COMWR']);
    $type=1;
    $msg='Successfully Deleted.';



}



if(isset($_POST['confirm']))
{		unset($_POST);
    $_POST[$unique_master]=$$unique_master;
    $_POST['entry_at']=date('Y-m-d H:s:i');
    $_POST['status']='PROCESSING';
    $crud   = new crud($table_master);
    $crud->update($unique_master);
    $crud   = new crud($table_detail);
    $crud->update($unique_master);
    $DOTY = find_a_field('sale_do_master','do_type','do_no="'.$$unique_master.'" ');
    mysql_query("UPDATE sale_do_details SET do_type='".$DOTY."' WHERE do_no='".$$unique_master."'");
    $dooo=$$unique_master;

    $dealerGETDATA = find_all_field('dealer_info','','dealer_code='.$_SESSION['dlrid']);
    $DOTOTA = find_a_field('sale_do_details','SUM(total_amt)','do_no="'.$$unique_master.'"');
    $COMAMOUNT=($DOTOTA/100)*$dealerGETDATA->commission;

    if($COMAMOUNT>0){
        mysql_query("INSERT INTO sale_do_details (do_no,item_id,dealer_code,dealer_type,town,area_code,territory,region,unit_price,pkt_size,pkt_unit,dist_unit,total_unit,total_amt,depot_id,status,do_date,do_type) VALUES ('$dooo','1096000100010313','$_SESSION[dlrid]','$dealerGETDATA->customer_type','$dealerGETDATA->town','$dealerGETDATA->area_code','$dealerGETDATA->territory','$dealerGETDATA->region','','1','','','','-$COMAMOUNT','$_SESSION[DEPID]','PROCESSING','','$DOTY')");

    }

    mysql_query("UPDATE sale_do_master SET commission_amount='$_SESSION[COMWR]' where do_no='".$$unique_master."'");

    unset($$unique_master);
    unset($_POST[$unique_master]);
    unset($_SESSION['dlrid']);
    unset($_SESSION['DEPID']);
    unset($_SESSION['COMWR']);

    $type=1;
    $msg='Successfully Instructed to Depot.';}

auto_complete_from_db('dealer_info','concat(dealer_name_e," - ",team_name," [",dealer_type,"]")','dealer_code','1  and canceled="Yes"','dealer');

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

    <script language="javascript">
        window.onload = function() {document.getElementById("dealer").focus();}
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

                    <form  name="addem" id="addem" action="do_export.php" class="form-horizontal form-label-left" method="post">
                                    <?require_once 'support_html.php';?>

                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Select a Dealer<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" required name="dealer" id="dealer" style="width: 100%; font-size: 12px">
                                    <option value=""></option>
                                    <?
                                    $sql="Select dealer_code,dealer_custom_code,dealer_name_e from dealer_info where dealer_category in ('Export')";

                                    $led=mysql_query($sql);
                                    if(mysql_num_rows($led) > 0)
                                    {
                                        while($ledg = mysql_fetch_row($led)){?>
                                            <option value="<?=$ledg[0]?>" <?php if($data[2]==$ledg[0]) echo " Selected "?>><?=$ledg[1];?>-<?=$ledg[2];?></option>
                                        <? }}?>
                                </select></div></div>


                        <div align="center" class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="submitit" id="submitit" class="btn btn-success">Create DO</button></div></div>


                    </form>

                            </div></div></div>
                    <!-- input section-->


<!-------------------End of  List View --------------------->
                </div>
            </div>
        </div>
<!---page content----->







<?php require_once 'footer_content.php' ?>