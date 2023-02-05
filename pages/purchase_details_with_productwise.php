<?php

 ob_start();

 session_start();

 require_once 'base.php';

  require_once 'create_id.php';

  require_once 'module.php';

 // if session is not set this will redirect to login page

 if( !isset($_SESSION['login_email']) ) {

  header("Location: index.php");

  exit;

 }

 // select loggedin users detail

 $res=mysql_query("SELECT * FROM company WHERE companyid=".$_SESSION['companyid']);

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



    <title><?php echo $_SESSION[company]; ?> | Previouse Sales Record</title>



    <!-- Bootstrap -->

    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->

    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- NProgress -->

    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- iCheck -->

    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- Datatables -->

    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">

    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">

    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">

    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">



    <!-- Custom Theme Style -->

    <link href="../build/css/custom.min.css" rel="stylesheet">

  </head>



  <body class="nav-md">

    <div class="container body">

      <div class="main_container">

        <div class="col-md-3 left_col">

          <div class="left_col scroll-view">

            <div class="navbar nav_title" style="border: 0;">

              <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>Raresoft</span></a>

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

            <div class="page-title">

               

             </div>



            <div class="clearfix"></div>



            <div class="row">

              





              <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                  <div class="x_title">

                  <?php 

			  $productnames=getSVALUE("transaction_inventory", "product", "where  productcode='$_GET[productcode]' and companyid='$_SESSION[companyid]'");
			  
			  
			  

			  ?>

                    

                    <h2>Product Name: <b style="color:#F00"><?php echo $productnames; ?></b></h2>

                    <ul class="nav navbar-right panel_toolbox">

                     <div class="input-group pull-right">

								<a class="btn btn-sm btn-default"  href="sales_cash.php">

									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Cash Sales</span>

								</a>

                    			

                    			               

                    			<a class="btn btn-sm btn-default" style="color:#000" href="sales_chalan.php">



                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Create Chalan</span>

                    			</a>

		 						

								</a>

								</div>

                    </ul>

                    <div class="clearfix"></div>

                  </div>

                  <div class="x_content">

                    

                    <table style=" width:100%" id="datatable-buttons" class="table table-striped table-bordered">

                      <thead>

                        <tr>

                         

                          <th style="width:2%">SL</th>

                         <th style="width:10%">Date</th>

                          <th>Item Details</th>
                          <th>Purchase From</th>
                          <th style="width:10%">Qty</th>

                          <th style="width:10%">Rate</th>

                          <th style="width:10%">Amount</th>

                        </tr>

                      </thead>





                      <tbody>

                        <?php 

				$result=mysql_query("Select distinct invoiceno from transaction_inventory where productcode='$_GET[productcode]' and tfor='Purchase'  and companyid='$_SESSION[companyid]' order by invoiceno,tdate  DESC");
				while($row=mysql_fetch_array($result)){ ?>

					

	<tr style="height:15px; border:none"><td colspan="6" style="color:#00F; font-weight:bold; font-size:15px;  "><?php echo $row[invoiceno]; ?></td></tr>

				

				 

                 

                 <?php

				 $resuls=mysql_query("select * from transaction_inventory where productcode='$_GET[productcode]' and invoiceno='$row[invoiceno]' and tfor='Purchase'  and companyid='$_SESSION[companyid]'");
				 while($venrow=mysql_fetch_array($resuls)){
				 $i=$i+1;

				 
				 
				 
				 $qtytotal=getSVALUE("transaction_inventory", "Sum(qty) as qty", " where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");
				 
				 $ratetotal=getSVALUE("transaction_inventory", "Sum(rate) as rate", " where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");
				 $ratetotals=number_format($ratetotal,2);
				 
				 
				 
				 
				  $amounttotal=getSVALUE("transaction_inventory", "Sum(amount) as amount", " where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");
				  $amounttotals=number_format($amounttotal,2);



$vendorname=getSVALUE("accounts_ledger", "ledger", "where  ledgercode='$venrow[purchaseclint]' and companyid='$_SESSION[companyid]'");
				  ?>

                        <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $venrow[tdate]; ?></td>
                        <td align="left"><?php echo $venrow[categorys]; ?> <?php echo $venrow[brand]; ?> <?php echo $venrow[model]; ?> <?php echo $venrow[product]; ?> <?php echo $venrow[productcode]; ?></td>
                        <td align="center"><?php echo $vendorname; ?></td>
                        <td align="center"><?php echo $venrow[qty]; ?></td>
                        <td align="right"><?php echo $venrow[rate]; ?></td>
                        <td align="right"><?php echo $venrow[amount]; ?></td>
                        </tr>
                        
                        

                        <?php } ?>
                        <!--tr style="color:#F00; font-weight:bold; font-size:14px"><td colspan="4" align="right" style="">Total</td><td align="center"><?php echo $qtytotal; ?></td><td align="right"><?php echo $ratetotals; ?></td><td align="right"><?php echo $amounttotals; ?></td></tr--->
                        <?php }?>

                      

                      </tbody>

                    </table>

                  </div>

                </div>

              </div>



              



              



              

              

              

    
              

              

              

              

              

            </div>

          </div>

        </div>

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

</html>