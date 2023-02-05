<?php
 require_once 'support_file.php'; 
 $title='MANUAL & Returned Production List';
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
                  <?php if($_GET[custom_pr_no]) { ?>
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">PS NO</th>
                     <th style="width:8%">PS Date</th>
                     <th style="width:15%">CMU</th> 
                     <th style="width:5%">FG Code</th> 
                     <th style="width:20%">FG Description</th>
                     <th style="width:10%; text-align:center">Batch</th>
                     <th style="width:10%; text-align:center">Lot</th>
                     <th style="width:10%; text-align:center">Qty</th>
                     <!---th style="width:10%; text-align:center">Option</th--->
                     </tr>
                     </thead>





                      <tbody>






<?php
$enat=date('Y-m-d h:s:i');
$resultss=mysql_query("Select * from production_floor_receive_detail where status in ('RETURNED','RETURNED') and custom_pr_no='$_GET[custom_pr_no]' order by pr_no DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;
	
$psiz=getSVALUE("item_info", "pack_size", "where item_id='".$rows['item_id']."'");

$fgin=$rows[total_unit];
if(isset($_POST[initiate])){

    $update1=mysql_query("UPDATE production_floor_receive_detail SET status='MANUAL' where  custom_pr_no='$_GET[custom_pr_no]'");
    $update2=mysql_query("UPDATE production_floor_receive_master SET status='MANUAL' where  custom_pr_no='$_GET[custom_pr_no]'");
    $_SESSION[initiate_daily_production]=$_GET[custom_pr_no];

?>
<meta http-equiv="refresh" content="0;daily_production.php">
<?php } ?>

<?php 
if(isset($_POST[deleteandback])){
	
	$delete1=mysql_query("Delete from production_floor_receive_detail  where  custom_pr_no='$_GET[custom_pr_no]'");
	$delete2=mysql_query("Delete from production_floor_receive_master where custom_pr_no='$_GET[custom_pr_no]'");
	
	 ?>
    <meta http-equiv="refresh" content="0;production_manual_and_return.php">
<?php } ?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><?php echo $rows[custom_pr_no]; ?></a></td>
                        <td><?php echo $rows[pr_date]; ?></a></td>
                      <td><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></td>
                        <td><?=$fgcode=getSVALUE("item_info", "finish_goods_code", "where item_id='".$rows['item_id']."'");?></td>
                        <td><?=$fgname=getSVALUE("item_info", "item_name", "where item_id='".$rows['item_id']."'");?></td>
                        <td style="text-align:right"><?=$rows[batch]?></td>
                        <td style="text-align:right"><?=$rows[lot]?></td>
                        <td style="text-align:right"><?=$rows[total_unit]/$psiz?>, <?=$unit=getSVALUE("item_info", "unit_name", "where item_id='".$rows['item_id']."'");?></td>
                        <!---td style="text-align:center"><?php $dones=getSVALUE('QC_Inspection_Work_Sheet_master','COUNT(item_id)','where t_id='.$rows[id].' and inspection_for="FG" and MAN_ID="'.$_GET[custom_pr_no].'" and item_id='.$rows[item_id]); if($dones>0){ ?>
<img src="done.png" style="margin-left:10px" height="25" width="25" />
<?php } else { ?>
<a href="inspection_work_sheet_FG.php?custom_pr_no=<?php echo $_GET[custom_pr_no]; ?>&item_id=<?php echo $rows[item_id]; ?>&id=<?php echo $rows[id]; ?>" style="text-decoration:none"><img src="add.png" style="margin-left:10px" height="25" width="25" /></a>
<?php }?></td--->
                        </tr>
                        <?php } ?>
                       
                        <tr style="border:none">

                            <td colspan="5" style="border:none">
                                <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Edit and Re-Process</button></td>
                        
                            <td colspan="5" style="border:none; text-align:right">
                                <button type="submit" name="deleteandback" onclick='return window.confirm("Are you confirm to Delete?");' class="btn btn-success">Deleted and Clear All data</button></td>
                        

                        
                        </tr>
</tbody></table></form>
                  
                  <?php } else { ?>
                  
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">PS NO</th>
                     <th style="width:8%">PS Date</th>
                     <th style="width:15%">CMU</th> 
                     <th style="width:5%">Remarks</th> 
                     <th style="width:8%">Type</th>
                     <th style="width:20%">Production By</th>
                     <th style="width:10%">Entry At</th>
                        <th style="width:10%">Status</th>
                     </tr>
                     </thead>





                      <tbody>






<?php
$resultss=mysql_query("Select * from production_floor_receive_master where status in ('RETURNED','MANUAL') and entry_by='".$_SESSION[userid]."' order by pr_no DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='production_manual_and_return.php?custom_pr_no='.$rows[custom_pr_no];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><a href="<?php echo $link; ?>" ><?php echo $i; ?></a></th>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[custom_pr_no]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[pr_date]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[remarks]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php if($rows[p_type]=='Re-processing'){ echo $rows[p_type];} else {echo 'Regular';} ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$fname=getSVALUE("user_activity_management", "fname", "where user_id='".$rows['entry_by']."'");?></a></td>
                        <td style="text-align:right"><a href="<?php echo $link; ?>" ><?=$rows[entry_at]?></a></td>
                          <td style="text-align:right"><a href="<?php echo $link; ?>" ><?=$rows[status]?></a></td>
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

