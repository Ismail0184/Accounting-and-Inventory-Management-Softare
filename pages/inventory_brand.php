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

    <title><?php echo $_SESSION[company]; ?> | Create Inventory Category</title>

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
	$inventory_types=$_POST[inventory_types];
	$category=$_POST[category];
    $createby=$_SESSION['login_email'];	
	$createdate=date('Y-m-d');
	
	



if (isset($_POST['getstarted'])){
     $valid = true;
	 
	 
	$flag=mysql_query("Select inventory_categorys from brand where brand='$brand'");
	if ( mysql_num_rows($flag)>0)

    {echo "<script> alert('This Brand Name Already Input!! Please Try Another Brand') </script>";
        $valid = false;} 
	 
	 
if ($valid){
$result=mysql_query("INSERT INTO `inventory_categorys` (inventory_types,category,createby,createdate,companyid) VALUES ('$inventory_types','$category','$createby','$createdate','$companyid')"); ?>
	<meta http-equiv="refresh" content="0;inventory_brand.php">	
<?php }}



$edit=$_POST[edit];
if(isset($edit)){
	
mysql_query("Update inventory_categorys SET 
inventory_types='$inventory_types', 
category='$category',
modifiddate='$createdate', 
modifidby='$createby' 

 where id='$_GET[brandid]'");
?>
<meta http-equiv="refresh" content="0;inventory_brand.php">
<?php } ?>               
                  


<?php
						  
$delete=$_POST[delete];	
$deleteid=$_GET[brandiddelete];
if(isset($delete)){
$result=mysql_query("Delete from inventory_categorys where id='$deleteid'");	?>
	
	<meta http-equiv="refresh" content="0;inventory_brand.php">	
<?php } ?> 

 <?php 
$result=mysql_query("Select * from inventory_categorys where id='$_GET[brandid]'");
$row=mysql_fetch_array($result);
?>                
                  
                  
                  
                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
                <div class="x_title">
                    <h2>Create Inventory Category <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<!--a class="btn btn-sm btn-default" href="inventory_brand.php">
									<i class="fa fa-plus-circle"></i> <span class="language">Groups</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" href="inventory_categorys.php">
                    				<i class="fa fa-plus-circle"></i> <span class="language">Category</span>
                    			</a>
		 						<a class="btn btn-sm btn-default fshow" href="inventory_model.php">
									<i class="fa fa-plus-circle"></i> <span class="language">Brand</span>
								</a>
		 						<a class="btn btn-sm btn-default timel" href="inventory_product.php">
									<i class="fa fa-plus-circle"></i> <span class="language">Product</span>
								</a>
                                
                                <a class="btn btn-sm btn-default timel" href="inventory_sku.php">
									<i class="fa fa-plus-circle"></i> <span class="language">SKU</span>
								</a--->
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
               <div class="x_content">
                  
                    <br />
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    
                    
                   
                
                      
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Inventory Type<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
                        <?php if($_GET[type]){ ?>
                                                 
                       <input type="text" id="first-name" required="required" value="<?php echo $row[inventory_types] ?>" name="inventory_types" class="form-control col-md-7 col-xs-12">
                        
                        <?php } else { ?>
                        
                        
                        <select id="first-name" required="required"  name="inventory_types" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose......</option>
                        <?php
						$result=mysql_query("Select * from inventory_type where companyid='$_SESSION[companyid]' order by inventory_type");
						while($row=mysql_fetch_array($result)){
						
						 ?>
                        
                        <option value="<?php echo $row[inventory_type]; ?>"><?php echo $row[inventory_type]; ?></option>
                        <?php } ?>
                        
                        </select>
                        <?php } ?>
                        </div>
                      </div>
 
                      
                     
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Category Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="category" value="<?php echo $row[category] ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                      
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                        <?php 
						if($_GET[type]){
						?>
                        <a href="inventory_brand.php" class="btn btn-primary">Cancel</a>
                        <button type="submit" name="edit" class="btn btn-success">Edit Category</button>
                        <?php } else { ?>
                          <a href="inventory_brand.php" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarted" class="btn btn-success">Create Category</button>
                          
                          <?php } ?>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>

          

               

                
                    <form id="demo-form" data-parsley-validate></form>
 










              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Inventory Category</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                           <th style="width:5%">SL</th>
                         
                          <th>Category</th>
                          <th style="width:20%">Inventory Type</th>
                          <th style="width:15%">Option</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				$result=mysql_query("Select * from inventory_categorys order by id");
				while($row=mysql_fetch_array($result)){
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $i; ?></td>
                        
                        <td><?php echo $row[category]; ?></td>
                        <td><?php echo $row[inventory_types]; ?></td>
                        
                          <td align="center">
                         
                          
      <a href="inventory_brand.php?type=edit&brandid=<?php echo $row[id] ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a>
      <a href="inventory_brand.php?brandiddelete=<?php echo $row[id] ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i> Delete </a>
                    
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