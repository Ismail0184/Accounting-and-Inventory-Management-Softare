<?php
 require_once 'support_file.php'; 
 $title='Production Report';
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
           
           

            <div class="row">
              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a target="_new" class="btn btn-sm btn-default"  href="production_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Production Report</span>
								</a>
                                
                                
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              
                    

<?php 
$initiate=$_POST[addpre];

$d =$_POST[ps_date];
$ps_date=date('Y-m-d' , strtotime($d)); 
$invoice=$_POST[invoice];
$billno=$_POST[billno];
$enat=date('Y-m-d h:s:i');
if(isset($initiate)){	
	
$insert=mysql_query("INSERT INTO PARAMETERS (PARAMETERS_CODE,PARAMETERS_Name)  VALUES ('$_POST[PARAMETERS_CODE]','$_POST[PARAMETERS_Name]')");	

$_SESSION[initiate_daily_production]=$invoice;
$_SESSION[pr_no] =getSVALUE("production_floor_receive_master", "pr_no", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
; ?>
<meta http-equiv="refresh" content="0;PARAMETERS.php">
<?php }


if(isset($_POST[Finish])){ ?>   
<meta http-equiv="refresh" content="0;item_specifications.php">
<?php } ?>

                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">







<div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">PARAMETERS CODE<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="PARAMETERS_CODE" style="width:400px"  required  name="PARAMETERS_CODE" value="<?=$_SESSION[PARAMETERS_CODE];?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div>   



                    
                    
                             
        <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">NEW PARAMETERS<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="PARAMETERS_Name" style="width:400px"  required  name="PARAMETERS_Name" value="<?php if($_SESSION[initiate_daily_production]){ echo$inirow[remarks]; } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div>     
                    
                 
                      
                      
                      
                      

              
        
               
                        
                       
               
               
               <div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_daily_production]){  ?>
			   
			   <!---a href="daily_production.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
               <button type="submit" name="updatePS" class="btn btn-success">Update PS Documents</button>
			   
			 <?php   } else {?>
               <button type="submit" name="addpre" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">PARAMETERS ADD</button>
               
               
               

               <?php } ?>
               </div></div>   
               
               
                          
               
               
               </form>
               
               
               
               
               

   
                  </div>

                </div>

              </div>
            
           
              
   













              
          </div>
        </div>




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
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">PS NO</th>
                     <th style="width:8%">PS Date</th>
                     <th style="width:15%">CMU</th> 
                     <th style="width:5%">FG Code</th> 
                     <th style="width:20%">FG Description</th>
                     <th style="width:5%">Batch</th>
                     <th style="width:5%; text-align:center">Qty</th>                     
                     </tr>
                     </thead>





                      <tbody>






<?php
$resultss=mysql_query("Select * from production_floor_receive_detail order by pr_no DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='min_print_view.php?fgid='.$rows[item_id].'&'.'custom_pr_no='.$rows[custom_pr_no].'&prno='.$rows[pr_no];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><a href="<?php echo $link; ?>" target="_new"><?php echo $rows[custom_pr_no]; ?></a></td>
                        <td><?php echo $rows[pr_date]; ?></td>
                        <td><a href="<?php echo $link; ?>" target="_new"><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></a></td>
                        <td><?php echo $rows[item_id]; ?></td>
                        <td><?=$fgname=getSVALUE("item_info", "item_name", "where item_id='".$rows['item_id']."'");?></td>
                        <td style="text-align:right"><?=$rows[batch];?></td>
                        <td style="text-align:right"><?=$rows[total_unit];?></td>
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

