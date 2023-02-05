<?php
require_once 'support_file.php';
$title='Damaged Entry';
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todaysss=$dateTime->format("d/m/Y  h:i A");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">

    <SCRIPT language=JavaScript>
        function reload(form)
        {
            var val=form.item_id.options[form.item_id.options.selectedIndex].value;
            self.location='production_wastage_issue.php?item_id=' + val ;
        }


    </script>



    <script>
        var x = 0;
        var y = 0;
        var z = 0;
        function calc(obj) {
            var e = obj.id.toString();
            if (e == 'qtys') {
                x = Number(obj.value);
                y = Number(document.getElementById('rate').value);
            } else {
                x = Number(document.getElementById('qtys').value);
                y = Number(obj.value);
            }
            z = x * y;
            document.getElementById('total').value = z;
            document.getElementById('update').innerHTML = z;
        }


        var submit = document.querySelector("input[type=submit]");

        /* set onclick on submit input */
        submit.setAttribute("onclick", "return test()");

        //submit.addEventListener("click", test);

        function test() {

            if (confirm('Are you sure you want to submit this form?')) {
                return true;
            } else {
                return false;
            }

        }
    </script>



</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="<?php echo $webiste; ?>" class="site_title"><i class="fa fa-paw"></i> <span>ICPBD</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <?php include ("pro.php");  ?>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <?php include("sidebar_menus.php"); ?>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <?php include("menu_footer.php"); ?>
                </div>
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




                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo $title; ?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        <a target="_new" class="btn btn-sm btn-default"  href="warehouse_damage_report.php">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Damaged Report</span>
                                        </a>


                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />



                                <?php
                                $initiate=$_POST[initiate];
                                $todaysss=date('Y-m-d');
                                $d =$_POST[ps_date];
                                $ps_date=date('Y-m-d' , strtotime($d));
                                $invoice=$_POST[invoice];
                                $billno=$_POST[billno];
                                $enat=date('Y-m-d h:s:i');
                                if(isset($initiate)){

                                    $insert=mysql_query("INSERT INTO warehouse_damage_receive (manual_or_no,vendor_id,vendor_name,warehouse_id,or_date,status,remarks,create_date,entry_by,entry_at,section_id,company_id)  
VALUES ('$invoice','','','$_POST[warehouse_id]','$ps_date','MANUAL','$_POST[remarks]','$todaysss','$_SESSION[userid]','$enat','$_SESSION[sectionid]','$_SESSION[companyid]')");

                                    $_SESSION[initiate_damaged_entry]=$invoice;
                                    $_SESSION[pi_no] =getSVALUE("production_wastage_master", "pi_no", " where ref_no='$_SESSION[initiate_damaged_entry]'");
                                    ;

                                }


                                if(isset($_POST[updatePS])){
                                    mysql_query("UPDATE production_wastage_master SET  date='$ps_date',warehouse_from='$_POST[warehouse_id]',remarks='$_POST[remarkspro]' WHERE ref_no='".$_SESSION[initiate_damaged_entry]."' ");
                                }



                                $resultsssss=mysql_query("Select * from warehouse_damage_receive where manual_or_no='$_SESSION[initiate_damaged_entry]'");
                                $inirow=mysql_fetch_array($resultsssss);




                                ?>




                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">


                                    <table style="width:100%">
                                        <tr>
                                            <td style="width:50%">

                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ref. NO<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="last-name" style="width:250px" required="required" name="invoice" value="<?=($_SESSION[initiate_damaged_entry]!='')? $inirow[manual_or_no] : automatic_number_generate("DM","warehouse_damage_receive","manual_or_no","create_date='".date('Y-m-d')."' and manual_or_no like '$sekeyword%'"); ?>" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_damaged_entry]){ ?> readonly <?php } ?> >
                                                    </div>
                                                </div> </td>



                                            <td>
                                                <div class="form-group">

                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                                        <input type="text" id="ps_date" style="width:250px" required="required" name="ps_date" value="<?php if($_SESSION[initiate_damaged_entry]){ echo date('m/d/y' , strtotime($inirow[or_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                                                    </div>
                                                </div>
                                            </td></tr>






                                        <tr><td>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Warehouse / Depot / CMU<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">






                                                        <select id="first-name" required="required" style="width:250px"   name="warehouse_id" class="select2_single form-control">
                                                            <?php if($_SESSION[initiate_damaged_entry]){ ?>
                                                                <option value="<?php echo $inirow[warehouse_id]; ?>" selected><?=$warename=getSVALUE("warehouse", "warehouse_name", " where warehouse_id='$inirow[warehouse_id]'");?></option>
                                                            <?php } ?>
                                                            <option value="">Choose ......</option>

                                                            <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL','WH')  order by warehouse_id");
                                                            while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
                                                                ?>

                                                                <option value="<?php echo $rowVENDOR[warehouse_id]; ?>"><?php echo $rowVENDOR[warehouse_name]; ?></option>

                                                            <?php } ?></select></div></div> </td>


                                            <td>
                                                <div class="form-group">

                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Note<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                                        <input type="text" id="remarks" style="width:250px"   name="remarks" value="<?php if($_SESSION[initiate_damaged_entry]){ echo$inirow[remarks]; } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                                                    </div>
                                                </div></td></tr>




                                        <tr><td colspan="2">

                                                <div class="form-group" style="margin-left:40%">

                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <?php if($_SESSION[initiate_damaged_entry]){  ?>
                                                            <button type="submit" name="updatePS" class="btn btn-success">Update Damaged Entry Info</button>
                                                        <?php   } else {?>
                                                            <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Initiate Damaged Entry</button>
                                                        <?php } ?>
                                                    </div></div>
                                        </tr></table>




                                </form>





                                <!----------------------------------- initiate end--------------------------------------------------------------------->





                                <?php


                                ;
                                $item_id=$_POST[item_id];
                                $rate=$_POST[rate];
                                $qtys=$_POST[qtys];
                                $amounts=$rate*$qtys;
                                $mfg=$_POST[mfg];
                                $no_of_pack=$_POST[no_of_pack];
                                $po_no=$_POST[po_no];

                                $tdates=date("Y-m-d");
                                $idatess=date('Y-m-d');
                                $day = date('l', strtotime($idatess));
                                $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
                                $timess=$dateTime->format("d-m-y  h:i A");

                                //echo "$timess";


                                $add=$_POST[add];
                                if (isset($_POST['add'])){
                                    $valid = true;
                                    $packsize=getSVALUE("item_info", "pack_size", " where item_id='$item_id'");
                                    list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $_POST[mfg]);
                                    $totalqtys=($_POST[qtys]*$packsize);
                                    $batch=$_POST[batch];
                                    $m =$_POST[mfg];
                                    $mfg=date('Y-m-d' , strtotime($m));


                                    if ($valid){
                                        if($qtys>0){

                                            $_SESSION[spinvoice]=$invoice;


                                            $productiondetails =mysql_query("INSERT INTO production_westage_detail
		(pi_no,date,ref_no, item_id, warehouse_from, warehouse_to, total_unit, unit_price, total_amt,lot, batch, mfg,ip) VALUES 

('".$_SESSION[pi_no]."','".$inirow['date']."','".$_SESSION[initiate_damaged_entry]."','$item_id','$inirow[warehouse_to]','$inirow[warehouse_to]','$totalqtys','','".$total_amt."','$_POST[lot]','$batch','$mfg','$ip')");

                                            ?>

                                        <?php }}} ?>






                                <?php

                                if($_SESSION[initiate_damaged_entry]){

                                ?>


                                <form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">


                                    <div class="x_title">

                                        <div class="clearfix"></div>
                                    </div>



                                    <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">



                                        <tbody>
                                        <tr>

                                            <td style="width:20%" align="center">

                                                <select class="select2_single form-control" style="width:400px" tabindex="-1" required="required"  name="item_id" >
                                                    <option></option>



                                                    <?php
                                                    $result=mysql_query("SELECT * FROM  item_info  where
							1  order by item_id");
                                                    while($row=mysql_fetch_array($result)){  ?>
                                                        <option  value="<?php echo $row[item_id]; ?>"><?php echo $row[finish_goods_code]; ?>-<?php echo $row[item_name]; ?> (<?=$packsizeitem=getSVALUE("item_sub_group", "sub_group_name", " where sub_group_id='$row[sub_group_id]'");?>)</option>
                                                    <?php } ?>
                                                </select></td>


                                            <td style="width:15%" align="center">
                                                <div class="form-group">


                                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                                        <input type="text" id="qty" style="width:250px"   name="qty" placeholder="Damaged Qty in pcs" class="form-control col-md-7 col-xs-12" >

                                                    </div>
                                                </div>


                                            </td>

                                            <td align="center" style="width:5%">
                                                <button type="submit" class="btn btn-success" name="add" id="add">Add</button></td></tr>





                                        </tbody>
                                    </table>
                                </form>


















                                <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

                                <?php
                                if($_GET[type]=='delete'){
                                    if($_GET[productdeletecode]){

                                        $results=mysql_query("Delete from warehouse_damage_receive_detail where id='$_GET[productdeletecode]'"); ?>
                                        <meta http-equiv="refresh" content="0;warehouse_damaged_entry.php">


                                    <?php }} ?>

                                <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
                                    <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Warehouse Name</th>
                                            <th>Code</th>
                                            <th>Mat. Description</th>
                                            <th style="width:5%; text-align:center">UOM</th>
                                            <th style="width:10%; text-align:center">Damaged Qty</th>
                                        </tr>
                                        </thead>



                                        <tbody>



                                        <?php

                                        if(isset($_POST[add])){

                                            $insert=mysql_query("INSERT INTO warehouse_damage_receive_detail (or_no,manual_or_no,item_id,vendor_id,vendor_name,or_date,warehouse_id,rate,qty,amount,section_id,company_id) VALUES 
('$inirow[or_no]','$_SESSION[initiate_damaged_entry]','$_POST[item_id]','','','$inirow[or_date]','$inirow[warehouse_id]','$rate','$_POST[qty]','$amount','$_SESSION[sectionid]','$_SESSION[companyid]')")	;



                                        }
 $results=mysql_query("Select 
i.item_id,
i.item_name,
i.finish_goods_code,
i.pack_unit as UOM,


d.*,w.* from 
item_info i,
warehouse_damage_receive_detail d,
warehouse w
 where d.manual_or_no='$_SESSION[initiate_damaged_entry]' and 
  i.item_id=d.item_id and 
   
   w.warehouse_id=d.warehouse_id
   order by d.id
  
  ");
                                        while($row=mysql_fetch_array($results)){
                                            ?>
                                            <tr>
                                                <td style="width:3%; vertical-align:middle"><?php echo $i=$i+1; ?></td>
                                                <td style="vertical-align:middle"><?=$row[warehouse_name];?></td>
                                                <td style="width:8%; vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                                <td style="vertical-align:middle"><?=$row[item_name];?></td>
                                                <td style="vertical-align:middle; text-align:center"><?=$row[UOM];?></td>
                                                <td style="vertical-align:middle; text-align:center"><?=$row[qty];?></td>
                                            </tr>
                                        <?php }?>
                                        </tbody>























                                        <tr>
                                            <td colspan="9" style="text-align:center">





                                                <?php

                                                if(isset($_POST[cancel])){
                                                    mysql_query("Delete from warehouse_damage_receive_detail where manual_or_no='".$_SESSION["initiate_damaged_entry"]."'");
                                                    mysql_query("Delete from warehouse_damage_receive where manual_or_no='".$_SESSION["initiate_damaged_entry"]."'");
                                                    unset($_SESSION["initiate_damaged_entry"]);
                                                    unset($_SESSION[post_item_id]);
                                                    unset($_SESSION[post_batch]);

                                                    ?>
                                                    <meta http-equiv="refresh" content="0;warehouse_damaged_entry.php">
                                                <?php } ?>


                                                    <?php
                                                    if(isset($_POST[confirmsave])){
                                                    mysql_query("Update warehouse_damage_receive_detail SET status='UNCHECKED' where manual_or_no='".$_SESSION["initiate_damaged_entry"]."'");
                                                    mysql_query("Update warehouse_damage_receive SET status='UNCHECKED' where manual_or_no='".$_SESSION["initiate_damaged_entry"]."'");
                                                    unset($_SESSION["initiate_damaged_entry"]);
                                                    ?>  <meta http-equiv="refresh" content="0;warehouse_damaged_entry.php"><?php } ?>

                                                <button type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Cancel?");' class="btn btn-primary">Cancel Damaged Entry</button>
                                                <button type="submit" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Forword Damaged Entry </button>
                                            </td></tr>
                                    </table></form></div></div></div><?php } ?>
















                </div>
            </div>
            <!-- /page content -->

            <!-- footer content -->

            <!-- /footer content -->
        </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../vendors/starrr/dist/starrr.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- bootstrap-daterangepicker -->
    <script>
        $(document).ready(function() {
            $('#ps_date').daterangepicker({

                singleDatePicker: true,
                calender_style: "picker_4",

            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            $('#mfg').daterangepicker({

                singleDatePicker: true,
                calender_style: "picker_4",

            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>
    <!-- /bootstrap-daterangepicker -->



    <!-- Select2 -->
    <script>
        $(document).ready(function() {
            $(".select2_single").select2({
                placeholder: "select your choice",
                allowClear: true
            });
            $(".select2_group").select2({});
            $(".select2_multiple").select2({
                maximumSelectionLength: 4,
                placeholder: "With Max Selection limit 4",
                allowClear: true
            });
        });
    </script>
    <!-- /Select2 -->












    <!-- Starrr -->
    <script>
        $(document).ready(function() {
            $(".stars").starrr();

            $('.stars-existing').starrr({
                rating: 4
            });

            $('.stars').on('starrr:change', function (e, value) {
                $('.stars-count').html(value);
            });

            $('.stars-existing').on('starrr:change', function (e, value) {
                $('.stars-count-existing').html(value);
            });
        });



        $('#rate').keyup(function(){
            var qtys;
            var rate;
            qtys = parseFloat($('#qtys').val());
            rate = parseFloat($('#rate').val());

            var amounta = qtys * rate;
            $('#amounta').val(amounta.toFixed(2));


        });
    </script>
    <!-- /Starrr -->
</body>
</html>
