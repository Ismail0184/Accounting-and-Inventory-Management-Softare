<?php
 require_once 'support_file.php'; 
 $title='Warehouse Damaged Checked';
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
                  <?php if($_GET[manual_or_no]) { ?>
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">DM NO</th>
                     <th style="width:8%">DM Date</th>
                     <th style="width:15%">Warehouse / Depot /CMU</th> 
                     <th style="width:5%">FG Code</th> 
                     <th style="width:20%">FG Description</th>
                     <th style="width:10%; text-align:center">Qty</th>
                     <th style="width:10%; text-align:center">Rate</th>
                     </tr>
                     </thead>





                      <tbody>






<?php
$enat=date('Y-m-d h:s:i');
$resultss=mysql_query("Select 
d.*,
i.*,
w.* 

from 
warehouse_damage_receive_detail d,
warehouse w,
item_info i

where 
d.status='UNCHECKED' and 
d.or_no='".$_GET[manual_or_no]."' and
d.item_id=i.item_id and 
w.warehouse_id=d.warehouse_id


order by d.id DESC ");
while ($rows=mysql_fetch_array($resultss)){
$i=$i+1;
	

if(isset($_POST[initiate])){
	
$item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse,item_ex,tr_from,tr_no,sr_no,entry_by,entry_at,lot_number,batch,custom_no,section_id,company_id,ip) VALUES 
('$rows[or_date]','$rows[item_id]','$rows[warehouse_id]','$rows[warehouse_id]','$rows[qty]','DamagedStock','$rows[id]','$rows[or_no]','$_SESSION[userid]','$enat','','','$rows[manual_or_no]','$_SESSION[sectionid]','$_SESSION[companyid]','$ip')");



$item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse,item_in,tr_from,tr_no,sr_no,entry_by,entry_at,lot_number,batch,custom_no,section_id,company_id,ip) VALUES 
('$rows[or_date]','$rows[item_id]','$rows[warehouse_id]01','$rows[warehouse_id]01','$rows[qty]','DamagedStock','$rows[id]','$rows[or_no]','$_SESSION[userid]','$enat','','','$rows[manual_or_no]','$_SESSION[sectionid]','$_SESSION[companyid]','$ip')");




mysql_query("UPDATE warehouse_damage_receive set status='CHECKED' where or_no='$_GET[manual_or_no]'");
mysql_query("UPDATE warehouse_damage_receive_detail set status='CHECKED' where or_no='$_GET[manual_or_no]'");


?>
<meta http-equiv="refresh" content="0;warehouse_damaged_checked.php">
<?php } ?>

<?php 
if(isset($_POST[deleteandback])){
	
	$delete1=mysql_query("Delete from warehouse_damage_receive_detail  where or_no='$_GET[manual_or_no]'");
	$delete2=mysql_query("Delete from warehouse_damage_receive where or_no='$_GET[manual_or_no]'"); ?>
<meta http-equiv="refresh" content="0;warehouse_damaged_checked.php">
<?php } ?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><?=$rows[manual_or_no]; ?></a></td>
                        <td><?=$rows[or_date]; ?></a></td>
                        <td><?=$rows[warehouse_name]; ?></td>
                        <td><?=$rows[finish_goods_code]; ?></td>
                        <td><?=$rows[item_name]; ?></td>
                        <td style="text-align:right"><?=$rows[qty]; ?>, pcs</td>
                        <td style="text-align:right"><?=$rows[rate]; ?></td>                       
                        </tr>
                        <?php } ?>
                       
                        <tr style="border:none">                        
                        <td colspan="4" style="border:none; text-align:right">   <button type="submit" name="deleteandback" onclick='return window.confirm("Are you confirm to Delete?");' class="btn btn-success">Deleted and Clear All data</button></td>
                        
                        <td colspan="4" style="border:none">
                        <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Damaged Checked and Forwored</button></td>
                        
                        </tr>
</tbody></table></form>
                  
                  <?php } else { ?>
                  
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:10%">DM NO</th>
                     <th style="width:10%">DM Date</th>
                     <th style="">Warehouse / Dpot / CMU </th> 
                     <th style="width:5%">Remarks</th>
                     <th style="width:20%">Production By</th>
                     <th style="width:15%">Entry At</th>
                     </tr>
                     </thead>





                      <tbody>






<?php
$resultss=mysql_query("Select d.*,w.*,u.*
from 
warehouse_damage_receive d,
user_activity_management u,
warehouse w

where d.status='UNCHECKED' AND 
u.user_id=d.entry_by AND 
w.warehouse_id=d.warehouse_id



 order by or_no DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='warehouse_damaged_checked.php?manual_or_no='.$rows[or_no];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><a href="<?php echo $link; ?>" ><?php echo $i; ?></a></th>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[manual_or_no]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$rows[or_date];?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$rows[warehouse_name];?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[remarks]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[fname]; ?></a></td>
                        <td style="text-align:center"><a href="<?php echo $link; ?>" ><?=$rows[entry_at]?></a></td>
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

