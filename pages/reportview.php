<?php
 //ob_start();
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
 $res=mysql_query("SELECT * FROM company WHERE companyid=".$_SESSION['companyid']."");
 $userRow=mysql_fetch_array($res);
 
 if($_SESSION["user_type"]=="2"){
 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $_SESSION[company]; ?> | Accounts Report</title>

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








<?php 
/////////////////////////////////////cash book----------------------------------------------------------					  
					  if(($_GET['reporttypes'])=='cashbook'): ?>


              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 align="center"><?php echo $_SESSION[company]; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10%">Voucher No</th>
                          
                          <th style="width: 10%">Date</th>
                          <th style="">Description</th>
                          <th style="width: 10%">Debit</th>
                          <th style="width: 10%">Credit</th>
                           <th style="width: 10%">Balance</th>
                          
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
				$result=mysql_query("Select * from transaction_cash where companyid='$_SESSION[companyid]' and TDate between '$_GET[datefrom]' and '$_GET[dateto]' and Acchead='Main Cash' order by id DESC");
				
				$acchead=getSVALUE("transaction_cash", "ledger", " where VNumber='$row[VNumber]' and ledger!='Main Cash' and companyid='$_SESSION[companyid]'");

				while($row=mysql_fetch_array($result)){
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $row[VNumber]; ?></td>
                        <td><?php echo $row[TDate]; ?></td>
                        <td><?php echo $row[Note]; ?></td>
                        <td><?php echo $row[debitamount]; ?></td>
                        <td><?php echo $row[creditamount]; ?></td>
                        <td><?php echo $row[debitamount]-$row[creditamount]; ?></td>
                        
                        
                          
                        </tr>
                        <?php } ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              
<?php elseif ($_GET['reporttypes']=='cashjournal'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>
              

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 align="center"><?php echo $_SESSION[company]; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 1%">Date</th>
                          
                          <th style="">Brand</th>
                          <th style="">Model</th>
                          <th style="">Serial/IME</th>
                          <th style="">Contact Person</th>
                           <th style="">Mobile</th>
                           <th style="">Address</th>
                           <th style="">Delivery Date</th>
                           <th style="">Type</th>
                           <th style="">Charge</th>
                           
                          <th>Option</th>
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
				$result=mysql_query("Select * from service where companyid='$_SESSION[companyid]' order by id DESC");
				while($row=mysql_fetch_array($result)){
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $row[createdate]; ?></td>
                        <td><?php echo $row[brand]; ?></td>
                        <td><?php echo $row[model]; ?></td>
                        <td><?php echo $row[ime]; ?></td>
                        <td><?php echo $row[cp]; ?></td>
                        <td><?php echo $row[cn]; ?></td>
                        <td><?php echo $row[address]; ?></td>
                        <td><?php echo $row[dd]; ?></td>
                        
                        <td><?php echo $row[servicetype]; ?></td>
                        <td><?php echo $row[sc]; ?></td>
                        
                          
                        </tr>
                        <?php } ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>  
              
 
 
 
 
 
 
  <?php elseif ($_GET['reporttypes']=='receivedandpayment'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
					   ?>
              

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                
                
                
                  
                   
                    <?php
				$results=mysql_query("Select * from accounts_ledger where  companyid='$_SESSION[companyid]'");
				$ledrow=mysql_fetch_array($results);
					
					
					
					 ?>
                  
                  
                  
                  <div class="x_title">
                    <h2><b style="color:#009">Receipt and Payment</b><br></h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="date.php?reporttypes=ladger&ledgercode=<?php echo $_GET[ledgercode]; ?>&subledgercode=<?php echo $_GET[subledgercode]; ?>&datefrom=<?php echo $_GET[datefrom]; ?>&dateto=<?php echo $_GET[dateto]; ?>&getstarted=">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Date</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="#">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Ledger</span>
                    			</a>
		 						
								</a>
								</div>
                    </ul>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                     <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10%">Date</th>
                          
                          <th style="width:5%">Voucher</th>
                          <th style="width:15%">Account Head</th>
                          <th style="30%">Details</th>
                         
                          
                           <th align="right" style="width:10%">Debit</th>
                           <th align="right" style="width:10%">Credit</th>
                           
                           
                          
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
				$result=mysql_query("Select * from transaction_cash where  companyid='$_SESSION[companyid]' order by id DESC");
				while($row=mysql_fetch_array($result)){
					
$acchead=getSVALUE("transaction_cash", "ledger", " where VNumber='$row[VNumber]' and  companyid='$_SESSION[companyid]'");
					
					$debitamount=number_format($row[debitamount],2);
					$debitamounttotal=$debitamounttotal+$row[debitamount];
					$datotal=number_format($debitamounttotal,2);
					
					$creditamount=number_format($row[creditamount],2);
					$creditamounttotal=$creditamounttotal+$row[creditamount];
					$catotal=number_format($creditamounttotal,2);
					$closing=$debitamounttotal-$creditamounttotal;
					$closings=number_format($closing,2);
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $row[TDate]; ?></td>
                        <td><?php echo $row[VNumber]; ?></td>
                        <td><?php echo $acchead; ?></td>
                        <td><?php echo $row[Note]; ?></td>
                        <td align="right"><?php if($row[debitamount]>0){ echo $debitamount; } else {} ?></td>
                        <td align="right"><?php if($row[creditamount]>0){ echo $creditamount; } else {} ?></td>
                        
                       
                        
                        
                          
                        </tr>
                        <?php } ?>
                      
                      </tbody>
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="4" align="right">Total</td><td align="right"><?php echo $datotal; ?></td><td align="right"><?php echo $catotal; ?></td></tr>
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="5" align="right">Total Debit Amount</td><td align="right"><?php echo $datotal; ?></td></tr>
                      
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="5" align="right">Total Credit Amount</td><td align="right"><?php echo $catotal; ?></td></tr>
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="5" align="right">Closing Balance</td><td align="right"><?php echo $closings; ?></td></tr>
                    </table>
                  </div>
                </div>
              </div>              
              
            
 
 
 
 
 
 
 
 
 <?php elseif ($_GET['reporttypes']=='ladger'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>
              

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                
<?php
	$results=mysql_query("Select * from accounts_ledger where ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]'");
	$ledrow=mysql_fetch_array($results);
	
	$subl=mysql_query("Select * from accounts_subledger where subledgercode='$_GET[subledgercode]' and companyid='$_SESSION[companyid]'");
	$sledrow=mysql_fetch_array($subl);
	 ?>
                  
                  
                  
                  <div class="x_title">
                    <h2>Ledger report of <b style="color:#009"><?php echo $ledrow[ledger]; ?></b>
                    <?php if($_GET['subledgercode']){ ?>
                    <br><br>Subledger <b style="color:#009"><?php echo $sledrow[subledger]; ?></b>
                    <?php } ?>
                    <br><br><font style="font-size:12px">From <b style="color:#009"><?php echo $_GET[datefrom]; ?></b> to <b style="color:#009"><?php echo $_GET[dateto]; ?></b></font>
                    
                    </h2>
                   
                    
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="date.php?reporttypes=ladger&ledgercode=<?php echo $_GET[ledgercode]; ?>&subledgercode=<?php echo $_GET[subledgercode]; ?>&datefrom=<?php echo $_GET[datefrom]; ?>&dateto=<?php echo $_GET[dateto]; ?>&getstarted=">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Date</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="ledgers.php?reporttypes=ladger&ledgercode=<?php echo $_GET[ledgercode]; ?>&subledgercode=<?php echo $_GET[subledgercode]; ?>&datefrom=<?php echo $_GET[datefrom]; ?>&dateto=<?php echo $_GET[dateto]; ?>&getstarted=">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Ledger</span>
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
                          <th style="width: 10%">Date</th>
                          
                          <th style="width:5%">Voucher</th>
                          <th style="width:15%">Account Head</th>
                          <th style="30%">Details</th>
                         
                          
                           <th align="right" style="width:10%">Debit</th>
                           <th align="right" style="width:10%">Credit</th>
                           
                           
                          
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
				
				if($_GET['subledgercode']){
				$result=mysql_query("Select * from transaction_cash where ledgercode='$_GET[ledgercode]' and subledgercode='$_GET[subledgercode]' and TDate between '$_GET[datefrom]' and '$_GET[dateto]' and companyid='$_SESSION[companyid]' order by id");
				} else {
				$result=mysql_query("Select * from transaction_cash where ledgercode='$_GET[ledgercode]'  and TDate between '$_GET[datefrom]' and '$_GET[dateto]' and companyid='$_SESSION[companyid]' order by id");
				}
				while($row=mysql_fetch_array($result)){
					









$acchead=getSVALUE("transaction_cash", "ledger", " where VNumber='$row[VNumber]' and ledger!='$ledrow[ledger]' and companyid='$_SESSION[companyid]'");

$accheads=getSVALUE("transaction_cash", "ledgercode", " where VNumber='$row[VNumber]' and ledger!='$ledrow[ledger]' and companyid='$_SESSION[companyid]'");
					
					$debitamount=number_format($row[debitamount],2);
					$debitamounttotal=$debitamounttotal+$row[debitamount];
					$datotal=number_format($debitamounttotal,2);
					
					$creditamount=number_format($row[creditamount],2);
					$creditamounttotal=$creditamounttotal+$row[creditamount];
					$catotal=number_format($creditamounttotal,2);
					$closing=$debitamounttotal-$creditamounttotal;
					$closings=number_format($closing,2);
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $row[TDate]; ?></td>
                        <td><?php echo $row[VNumber]; ?></td>
                        <td>
                        <?php 
						if($_GET['subledgercode']){ ?>
                        <a href="reportview.php?reporttypes=ladger&ledgercode=<?php echo $accheads; ?>&subledgercode=<?php echo $row[subledgercode]; ?>&datefrom=<?php echo $_GET[datefrom]; ?>&dateto=<?php echo $_GET[dateto]; ?>&getstarted=" target="_new"><?php echo $acchead; ?></a>
						<?php } else { ?>
                        <a href="reportview.php?reporttypes=ladger&ledgercode=<?php echo $accheads; ?>&datefrom=<?php echo $_GET[datefrom]; ?>&dateto=<?php echo $_GET[dateto]; ?>&getstarted=" target="_new"><?php echo $acchead; ?></a>
                        <?php } ?>
						</td>
                        <td><?php echo $row[Note]; ?></td>
                        <td align="right"><?php if($row[debitamount]>0){ echo $debitamount; } else {} ?></td>
                        <td align="right"><?php if($row[creditamount]>0){ echo $creditamount; } else {} ?></td>
                        
                       
                        
                        
                          
                        </tr>
                        <?php } ?>
                      
                      </tbody>
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="4" align="right">Total</td><td align="right"><?php echo $datotal; ?></td><td align="right"><?php echo $catotal; ?></td></tr>
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="5" align="right">Total Debit Amount</td><td align="right"><?php echo $datotal; ?></td></tr>
                      
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="5" align="right">Total Credit Amount</td><td align="right"><?php echo $catotal; ?></td></tr>
                      
                      <tr style="font-weight:bold; font-size:13px;"><td colspan="5" align="right">Closing Balance</td><td align="right">
					 
                     
                     
                      <?php 
					  
					  if($closings>0) {
					  echo $closings; } else {
					  
					  
					  $abc=substr($closings,1);
					  
					  echo "($abc)";
					  }
					  
					  
					  ?>
                      
                      
                      </td></tr>
                    </table>
                  </div>
                </div>
              </div>              
              
            <?php  else:  ?>
            <?php endif; ?>   
              
              
              
              
              
              
              
              
              
              
              
              
              
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

<?php } else { ?>
<h4 align="center">You have no permission to view this page</h4>

<meta http-equiv="refresh" content="3;dashboard.php">
<?php } ?>
