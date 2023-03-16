<?php

 require_once 'support_file.php'; 

 $title='STO Transfer Return';

?>





<!DOCTYPE html>

<html lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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

    <!-- bootstrap-daterangepicker -->

    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->

    <link href="../build/css/custom.min.css" rel="stylesheet">

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

<div class="clearfix"></div>

            <div class="row">

             



              



              



              



              



              <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                <div class="x_title">

                <h2><?php echo $title; ?></h2>

                <div class="clearfix"></div>

                </div>



                  <div class="x_content">

                  <?php if($_GET[custom_pi_no]) { ?>

                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">

                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">

                   <thead>

                    <tr>

                     <th style="width: 2%">#</th>
                     <th style="width:4%">STO NO</th>
                     <th style="width:10%">Date</th>
                     <th style="width:15%">Transfer From</th> 
                     <th style="width:15%">Transfer To</th> 
                     <th style="width:8%">FG Code</th> 
                     <th style="width:20%">FG Description</th>
                     <th style="width:10%; text-align:center">Batch</th>
                     <!--th style="width:10%; text-align:center">Lot</th--->
                     <th style="width:10%; text-align:center">Qty</th>
                     </tr>

                     </thead>











                      <tbody>













<?php

$enat=date('Y-m-d h:s:i');

$resultss=mysql_query("Select * from production_issue_detail where ISSUE_TYPE='STO' and verifi_status='RETURNED' and custom_pi_no='$_GET[custom_pi_no]' order by pi_no DESC ");

while ($rows=mysql_fetch_array($resultss)){
	
$psiz=getSVALUE("item_info", "pack_size", "where item_id='".$rows['item_id']."'");

if(isset($_POST[initiate])){

    $_SESSION[initiate_production_transfer]=$_GET[custom_pi_no];





    ?>

    <meta http-equiv="refresh" content="0;production_transfer.php?transfer_from=<?=$rows['warehouse_from']?>">

<?php }



    if(isset($_POST[deletesto])){


    mysql_query("Delete FROM production_issue_detail  where custom_pi_no='$_GET[custom_pi_no]'");
    mysql_query("Delete FROm production_issue_master  where custom_pi_no='$_GET[custom_pi_no]'");





    ?>

    <meta http-equiv="refresh" content="0;sto_trasnfer_return.php">

<?php } ?>







                      <tr style="font-size:12px">



                        

                        <th style="text-align:center"><?php echo $i; ?></th>

                        <td><?php echo $rows[custom_pi_no]; ?></a></td>

                        <td><?php echo $rows[pi_date]; ?></a></td>

                      <td><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></td>
                      <td><?=$companynameto=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_to']."'");?></td>

                        <td><?=$fgcode=getSVALUE("item_info", "finish_goods_code", "where item_id='".$rows['item_id']."'");?></td>

                        <td><?=$fgname=getSVALUE("item_info", "item_name", "where item_id='".$rows['item_id']."'");?></td>

                        <td style="text-align:right"><?=$rows[batch]?></td>

                        <!--td style="text-align:right"><?=$rows[lot]?></td--->

                        <td style="text-align:right"><?=$rows[total_unit]/$psiz?>, <?=$unit=getSVALUE("item_info", "unit_name", "where item_id='".$rows['item_id']."'");?></td></tr>

                        <?php } ?>

                       

<tr style="border:none">
    <td colspan="5" style="border:none">
        <div class="form-group" style="margin-left:40%">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">STO Edit and Re-process</button>
            </div></div> </td>

                            <td colspan="5" style="border:none">

               <div class="form-group" style="margin-left:40%">
               <div class="col-md-6 col-sm-6 col-xs-12"> 
               <button type="submit" name="deletesto" id="deletesto" onclick='return window.confirm("Are you sure you want to delete?");' class="btn btn-success">Deleted STO</button>
               </div></div> 
               </td></tr>

</tbody></table></form>

                  

                  <?php } else { ?>

                  

                  

                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:8%">STO NO</th>
                     <th style="width:8%">Date</th>
                     <th style="width:15%">Transfer From</th> 
                     <th style="width:15%">Transfer To</th> 
                     <th style="width:5%">Remarks</th>
                     <th style="width:10%">Entry By</th>
                     </tr>
                     </thead>
                     <tbody>













<?php

$resultss=mysql_query("Select * from production_issue_master where ISSUE_TYPE='STO' and verifi_status='RETURNED' order by custom_pi_no DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;
$link='sto_trasnfer_return.php?custom_pi_no='.$rows[custom_pi_no]; ?>







                      <tr style="font-size:12px">
                      <th style="text-align:center"><a href="<?php echo $link; ?>" ><?php echo $i; ?></a></th>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[custom_pi_no]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[pi_date]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></a></td>

                        <td><a href="<?php echo $link; ?>" ><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_to']."'");?></a></td>
<td style="text-align:left"><a href="<?php echo $link; ?>" ><?=$rows[remarks]?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$fname=getSVALUE("users", "fname", "where user_id='".$rows['entry_by']."'");?></a></td>

                        

                        </tr>

<?php } ?></tbody></table><?php } ?>



       </div></div></div></div></div></div>



        <!-- /page content -->







        <!-- footer content -->



        <footer>



          <div class="pull-right">



          </div>



          <div class="clearfix"></div>



        </footer>



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



    <!-- iCheck -->



    <script src="../vendors/iCheck/icheck.min.js"></script>



    <!-- Datatables -->



    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>



    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>



    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>



    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>



    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>



    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>



    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>



    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>



    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>



    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>



    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>



    <script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>



    <script src="../vendors/jszip/dist/jszip.min.js"></script>



    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>



    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>







    <!-- Custom Theme Scripts -->



    <script src="../build/js/custom.min.js"></script>







    <!-- Datatables -->



    <script>



      $(document).ready(function() {



        var handleDataTableButtons = function() {



          if ($("#datatable-buttons").length) {



            $("#datatable-buttons").DataTable({



              dom: "Bfrtip",



              buttons: [



                {



                  extend: "copy",



                  className: "btn-sm"



                },



                {



                  extend: "csv",



                  className: "btn-sm"



                },



                {



                  extend: "excel",



                  className: "btn-sm"



                },



                {



                  extend: "pdfHtml5",



                  className: "btn-sm"



                },



                {



                  extend: "print",



                  className: "btn-sm"



                },



              ],



              responsive: true



            });



          }



        };







        TableManageButtons = function() {



          "use strict";



          return {



            init: function() {



              handleDataTableButtons();



            }



          };



        }();







        $('#datatable').dataTable();







        $('#datatable-keytable').DataTable({



          keys: true



        });







        $('#datatable-responsive').DataTable();







        $('#datatable-scroller').DataTable({



          ajax: "js/datatables/json/scroller-demo.json",



          deferRender: true,



          scrollY: 380,



          scrollCollapse: true,



          scroller: true



        });







        $('#datatable-fixed-header').DataTable({



          fixedHeader: true



        });







        var $datatable = $('#datatable-checkbox');







        $datatable.dataTable({



          'order': [[ 1, 'asc' ]],



          'columnDefs': [



            { orderable: false, targets: [0] }



          ]



        });



        $datatable.on('draw.dt', function() {



          $('input').iCheck({



            checkboxClass: 'icheckbox_flat-green'



          });



        });







        TableManageButtons.init();



      });



    </script>



    <!-- /Datatables -->



  </body>



