<?php
 ob_start();
 session_start();
 require_once 'base.php';
  require_once 'create_id.php';
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

    <title><?php echo $_SESSION[company]; ?> | Procurement Requisitions</title>

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

				
	$getstarted=$_POST[getstarted];
	$companyid=$_SESSION['companyid'];
	$requisitionno=$_POST[requisitionno];
	$title=$_POST[title];
	$priority=$_POST[priority];
	$requestedby=$_POST[requestedby];
	$deliverydate=$_POST[deliverydate];
	$category=$_POST[category];
	$product=$_POST[product];
	$productcode=$_POST[productcode];
    $createby=$_SESSION['login_email'];	
	$createdate=date('Y-m-d');
	
	



 ?>

     


           
                  
                  
                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
               <div class="x_content">
                  
                    <br />
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    
                   
                
                      
                     
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Requisition No <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   required="required" name="requisitionno" value="<?php echo $_SESSION['rqnos']; ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>  
                     
                     
                  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Requisition Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="title" value="" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>     
                     
                   <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Priority <span class="required">*</span>
                        </label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	                	<select class="form-control language" required="required" name="priority">
	                		<option value="">Select...</option>
	                		<option value="Normal">Normal</option>
	                		<option value="Preferable">Preferable</option>
	                		<option value="Urgent">Urgent</option>
	                		<option value="Top-urgent">Top-urgent</option>
	                	</select>
                      </div>  
	                </div>
                    
                    
                    
                    
                    <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Requested By <span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="last-name"  required="required" name="requestedby" value="" class="form-control col-md-7 col-xs-12">

                      </div>  
	                </div>
                   
                   
                 
                    
                    <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Delivery Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="last-name"  required="required" name="deliverydate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">

                      </div>  
	                </div>
                   
                   
                   
                   
                   
                   <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                   
                   
                   
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width:5%">Code</th>
                          <th>Product</th>
                          <th>Unit</th>
                          
                          <th>Category</th>
                          <th>Group</th>
                          
                          
                          
                          <th style="width:10%">Qty</th>
                          <th style="width:10%">Rate</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				$results=mysql_query("Select * from inventory_matarial_item order by itemcode");
				while($row=mysql_fetch_array($results)){
				$ids=$row[itemcode];
				$typeid=$row[typeid];
				$matarial_type=$row[matarial_type];
				$categoryid=$row[categoryid];
				$categoryname=$row[categoryname];
				$item=$row[item];
				$unit=$row[unit];
				$itemcode=$_POST[itemcode.$ids];
				$qtys=$_POST[qtys.$ids];
				$rate=$_POST[rate.$ids];
				$value=$qtys*$rate;
	            
	
	
	
	
if (isset($_POST['getstarted'])){
$valid = true;
	 
	 
if ($valid){
	if($qtys>0){
 $result=mysql_query("INSERT INTO `procurement_requisitions` (requisitionno,title,priority,requestedby,date,deliverydate,status,createby,createdate,approvedby,approvedate,typeid,matarial_type,categoryid,categoryname,productcode,item,unit,qty,rate,value,companyid) VALUES 
 ('$requisitionno','$title','$priority','$requestedby','$date','$deliverydate','$status','$createby','$createdate','$approvedby','$approvedby','$typeid','$matarial_type','$categoryid','$categoryname','$itemcode','$item','$unit','$qtys','$rate','$value','$_SESSION[companyid]')"); ?>
	rqnumbercreate();
<?php }}}
				
				
				
				
				 ?>
                      <tr>
                        
                        <td><?php echo $row[itemcode]; ?></td>
                        <td><?php echo $row[item]; ?></td>
                        <td style="width:10%"><?php echo $row[unit]; ?></td>
                        <td style="width:12%"><?php echo $row[categoryname]; ?></td>
                        <td style="width:12%"><?php echo $row[matarial_type]; ?></td>
                        
                        
                        
                        
                        
                     <td align="center">
                     
                     <input type="hidden" id="last-name" style="width:120px" name="itemcode<?php echo $ids; ?>" value="<?php echo $row[itemcode]; ?>" class="form-control col-md-7 col-xs-12">
                     
                     <input type="text" id="last-name" style="width:120px" name="qtys<?php echo $ids; ?>" value="<?php echo $row[quantity] ?>" class="form-control col-md-7 col-xs-12">
                     
                     
                     </td>
                      
                     <td align="center"><input type="text" id="last-name"  style="width:120px" name="rate<?php echo $ids; ?>" value="<?php echo $row[rate] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                      
                      
                        </tr>
                        <?php } ?>
                        
                        
                      
                      </tbody>
                    </table>  
                   
                   
                       
                  <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                       
                          <a href="procurement_requisitions.php" type="cancel" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarted" class="btn btn-success">Create Requisition </button>
                          
                          
                        </div>
                      </div>  
                      
                      

                    </form>
                  </div>
                </div>
              </div>
            </div>

          

               

                
                    <form id="demo-form" data-parsley-validate></form>
 



              

              

              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
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