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
 $res=mysql_query("SELECT * FROM create_company WHERE company_id=".$_SESSION['companyid']);
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

    <title><?php echo $_SESSION[company]; ?> | LC Documents List</title>

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
              

<?php
						  
$delete=$_POST[delete];	
$deleteid=$_GET[lcdeletenumber];
if($_GET[lcdeletenumber]){
$result=mysql_query("Delete from lc_documents where id='$deleteid'");	?>
	
	<meta http-equiv="refresh" content="0;lc_list.php">	
<?php } ?> 
               
                  
                  
                  
              

          

               

                
                    <form id="demo-form" data-parsley-validate></form>
 










              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>LC Documents List</h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="lc_create.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Create LC Documents</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="credit_purchase.php">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000"></span>
                    			</a>
		 						
								</a>
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                           <th style="width:5%">SL</th>
                          <th style="width:5%">Date</th>
                          <th>LC ID</th>
                          <th style="width:10%">LC Velue</th>
                          <th style="width:20%">Bank Name</th>
                          
                          
                         
                          <th style="width:20%">Option</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				$result=mysql_query("Select distinct invoiceno from lc_documents where companyid='$_SESSION[companyid]' order by invoiceno");
				while($row=mysql_fetch_array($result)){
					
					$detailsresult=mysql_query("Select * from lc_documents where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");
					$invrow=mysql_fetch_array($detailsresult);
				
				$amounts=getSVALUE("lc_documents", "Sum(amount) as amount", " where invoiceno='$row[invoiceno]' and companyid='$_SESSION[companyid]'");
				
				$amountss=number_format($amounts,2);
					
				$i=$i+1;
				
				 ?>
                      <tr>
                        
                        <td><?php echo $i; ?></td>
                        <td><?php echo $invrow[tdate]; ?></td>
                        <td><?php echo $row[invoiceno]; ?></td>
                        <td align="right"><?php echo $amountss ; ?></td>
                        <td><?php echo $invrow[bankname]; ?></td>
                        
                       
                       
                          <td align="center">
<a href="#" target="_new" class="btn btn-primary btn-xs">
<i class="fa fa-pencil"></i> View Details </a>
                           
<a href="lc_list.php?lcdeletenumber=<?php echo $invrow[id]; ?>" class="btn btn-danger btn-xs">
<i class="fa fa-pencil"></i> Delete </a>                 
                          </td>
                        </tr>
                        <?php } ?>
                      
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