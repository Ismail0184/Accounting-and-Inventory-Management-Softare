<?php
 require_once 'support_file.php'; 
 $title='Production Consumption Report';
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
     <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
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


<form action="" method="post">
<!-- page content -->
<div class="right_col" role="main">
<div class="">
<div class="clearfix"></div>
            <div class="row">
             

              

              

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <div class="clearfix">

                    <?php if (!isset($_GET[psno])){ ?>
                    <table align="center"><tr>
                
                <td><input type="text" id="f_date" style="width:100px; height:30px" required="required" name="f_date" value="<?=date('m')?>/01/<?=date('Y')?>"  class="form-control col-md-7 col-xs-12" placeholder="From Date" ></td>
               
               
                <td style="width:10px; text-align:center"> -</td>
                
                
                <td><input type="text" id="t_date" style="width:100px; height:30px" required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date"></td>
                <td style="width:10px; text-align:center"> -</td>
                <td> <select id="first-name" required="required" style="width:200px; height:30px"   name="warehouse_id" class="select2_single form-control">
                        
                        <option value="">Choose ......</option>
                        
                        <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL','WH')  order by warehouse_id");
						while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
						?> 
                                         
                 <option value="<?php echo $rowVENDOR[warehouse_id]; ?>" <?php if($_POST[warehouse_id]==$rowVENDOR[warehouse_id]) { echo 'selected';} else echo ''; ?>><?php echo $rowVENDOR[warehouse_name]; ?></option>
                      
                    <?php } ?></select></td>
                
                <td style="padding:10px"><button type="submit" name="viewreport"  class="btn btn-success">View Report</button></td>
                
                </tr></table><?php } ?></div>
                </div>

                  <div class="x_content">
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">PS NO</th>
                     <th style="width:10%">PS Date</th>
                     <th style="width:20%">CMU</th> 
                     <th style="width:20%">Relv. FG</th> 
                     <th style="width:5%">Mat. Code</th> 
                     <th style="width:20%">Mat. Description</th>
                     <th style="width:5%">Lot</th>
                     <th style="width:5%; text-align:center">Qty</th>
                     <th style="width:5%; text-align:center">UOM</th>                     
                     </tr>
                     </thead>





                      <tbody>






<?php
$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));


if(isset($_POST[viewreport])){
$resultss=mysql_query("Select p.*,i.*,w.*
from 
item_info i,
production_floor_issue_detail p,
warehouse w
where 
p.item_id=i.item_id and  
p.warehouse_from=w.warehouse_id and 
p.pi_date between '$from_date' and '$to_date' order by p.pi_no DESC ");
}



if(isset($_GET[psno])){
    $resultss=mysql_query("Select p.*,i.*,w.*
from 
item_info i,
production_floor_issue_detail p,
warehouse w
where 
p.item_id=i.item_id and  
p.warehouse_from=w.warehouse_id and p.production_for_pr_no='".$_GET[psno]."'");
}



while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='min_print_view.php?fgid='.$rows[item_id].'&'.'custom_pr_no='.$rows[custom_pr_no].'&prno='.$rows[pr_no];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><?php echo $rows[production_for_pr_no]; ?></td>
                        <td><?php echo $rows[pi_date]; ?></td>
                        <td><?=$rows[warehouse_name];?></td>
                        <td><?=$fgname=getSVALUE("item_info", "item_name", "where item_id='".$rows['fg_id']."'");?></a></td>
                        <td><?php echo $rows[item_id]; ?></td>
                        <td><?=$rows[item_name]?></td>
                        <td style="text-align:right"><?=$rows[batch];?></td>
                        <td style="text-align:right"><?=$rows[total_unit]/$psize=getSVALUE("item_info", "pack_size", "where item_id='".$rows['item_id']."'");?></td>
                        <td style="text-align:center"><?=getSVALUE("item_info", "unit_name", "where item_id='".$rows['item_id']."'");?></td>
                        </tr>
<?php } ?></tbody></table>

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
</form>


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

 <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>

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
<!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- /Datatables -->
    
    
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
 <script>
      $(document).ready(function() {
        $('#f_date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    
    
    <script>
      $(document).ready(function() {
        $('#t_date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
  </body>

