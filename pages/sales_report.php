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

$res=mysql_query("SELECT * FROM company WHERE companyid='$_SESSION[companyid]'");
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



    <title><?php echo $_SESSION[company]; ?> | Sales Report</title>



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

                    <h2>Sales Report</h2>

                    <ul class="nav navbar-right panel_toolbox">

                     <div class="input-group pull-right">

								<a class="btn btn-sm btn-default"  href="sales_old.php">

									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Sales</span>

								</a>

                    			

                    			               

                    			<a class="btn btn-sm btn-default" style="color:#000" href="sales_report.php">



                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Create Report</span>

                    			</a>

		 						

								</a>

								</div>

                    </ul>

                    <div class="clearfix"></div>

                  </div>

                  <div class="x_content">

                    

                    <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">

                      <thead>

                        <tr>

                           <th style="width:2%">SL</th>

                          <th style="width:6%">Date</th>

                          <th>Invoice</th>

                          

                          <th style="width:20%">Customer Name</th>
                          <th style="width:10%">Contact Number</th>

                          
                          <th style="width:5%">Amount</th>
                          <th style="width:15%">Option</th>

                        </tr>

                      </thead>





                      <tbody>

                        <?php 
						
						 
 if($_SESSION["user_type"]=="3"){
 


				$result=mysql_query("Select distinct invoiceno from transaction_inventory where tfor='Sales' and companyid='$_SESSION[companyid]' and transactionby='$_SESSION[login_email]' order by  tdate DESC,	invoiceno DESC");
				
 } else {
	 
	 $result=mysql_query("Select distinct invoiceno from transaction_inventory where tfor='Sales' and companyid='$_SESSION[companyid]' order by tdate DESC,	invoiceno DESC");
 }
				
				

				while($row=mysql_fetch_array($result)){

					

					$detailsresult=mysql_query("Select * from transaction_inventory where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");

					$invrow=mysql_fetch_array($detailsresult);

					

				

				$amounts=getSVALUE("transaction_inventory", "Sum(amount) as amount", " where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");

				

				$vendorid=getSVALUE("procurement_supplier", "sname", " where id='$invrow[vendorid]' and companyid='$_SESSION[companyid]'");

				

				$amountss=number_format($amounts,2);

					

				$i=$i+1;

				

				 ?>

                      <tr>

                        

                        <td><a href="sales_report.php?deleteid=<?php echo $row[invoiceno]; ?>"><?php echo $i; ?></a></td>

                        <td><?php echo $invrow[tdate]; ?></td>

                        <td style="width:15%"><?php echo $row[invoiceno]; ?></td>

                        

                        <td><?php echo $invrow[vendor];; ?></td>
                        <td><?php echo $invrow[vendorphone];; ?></td>
                        <td align="right"><?php echo $amountss ; ?></td>
                       

                        

                       

                       

                          <td align="center">







<a href="sales_challan_view.php?type=delete&challanviewid=<?php echo $row[invoiceno]; ?>" target="_new" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> View </a> 



<?php
$results11=mysql_query("Select * from company where companyid='$_SESSION[companyid]'");
$comprow=mysql_fetch_array($results11);

$ress=mysql_query("Select * from inventory_transaction where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");
$crows=mysql_fetch_array($ress);
$inveditlimit=$comprow[inveditlimit];
$ivdd=$invrow[tdate];
$canceldate=date('Y-m-d', strtotime($ivdd. ' + '. $inveditlimit. 'days'));
$today=date('Y-m-d');

if($today>$canceldate){} else {
 ?>
 
 
<a href="sales_edit.php?type=edit&challanviewid=<?php echo $row[invoiceno]; ?>" target="_new" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a>

<a href="#"  class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i> Cancel </a>               
<?php } ?>
                          </td>

                        </tr>

                        <?php }
						
						$totalsaless=getSVALUE("transaction_cash", "Sum(creditamount) as creditamount", " where ledger='Sales' and companyid='$_SESSION[companyid]'");
						$totalsales=number_format($totalsaless,2);
						
						 ?>

                      

                      </tbody>
                      
                      <tr><td colspan="5" style="font-weight:bold; font-size:13px; text-align:right; color:red">Total Sales Amount</td><td style="color:red; font-weight:bold" align="right"><?php echo $totalsales; ?></td><td></td></tr>

                    </table>

                  </div>

                </div>

              </div>



              



              



              

              

              

              

              

              

              

              

              

 <?php 
 /// invoice delete funcion start from here--------------------------------------------
 
 if($_GET[deleteid]){
 
 
 
 $delete1=mysql_query("Delete From transaction_inventory where invoiceno='$_GET[deleteid]' and companyid='$_SESSION[companyid]'");
 
 $delete1=mysql_query("Delete From transaction_cash where invoiceno='$_GET[deleteid]' and companyid='$_SESSION[companyid]'");
 
 ?>
 <meta http-equiv="refresh" content="0;sales_report.php">
 <?php }  ?>             

              

              

              

              

              

              

              

              

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