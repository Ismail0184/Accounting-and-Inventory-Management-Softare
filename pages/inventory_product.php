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

    <title><?php echo $_SESSION[company]; ?> | Inventory Product</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
 
    
    
    <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.inventory_types.options[form.inventory_types.options.selectedIndex].value;
	self.location='inventory_product.php?inventorytype=' + val ;
}





function reload2(form)
{
	var val=form.inventory_types.options[form.inventory_types.options.selectedIndex].value;
	var val2=form.category.options[form.category.options.selectedIndex].value;
	self.location='inventory_product.php?inventorytype=' + val +'&categoryid=' + val2 ;
}


function reload3(form)
{
	var val=form.inventory_types.options[form.inventory_types.options.selectedIndex].value;
	var val2=form.category.options[form.category.options.selectedIndex].value;
	var val3=form.brand.options[form.brand.options.selectedIndex].value;
	self.location='inventory_product.php?inventorytype=' + val +'&categoryid=' + val2 +'&brandid=' + val3 ;
}

function reload4(form)
{
	var val=form.inventory_types.options[form.inventory_types.options.selectedIndex].value;
	var val2=form.category.options[form.category.options.selectedIndex].value;
	var val3=form.brand.options[form.brand.options.selectedIndex].value;
	var val4=form.model.options[form.model.options.selectedIndex].value;
	self.location='inventory_product.php?inventorytype=' + val +'&categoryid=' + val2 +'&brandid=' + val3 +'&modelid=' + val4;
}

</script>

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
                <h2>Create Inventory Product <small></small></h2>
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
            <div class="row">
              

              


 <?php 

				
	$getstarted=$_POST[getstarted];
	$companyid=$_SESSION['companyid'];
	
	
	
	$category=$_POST[category];
	$brand=$_POST[brand];
	$model=$_POST[model];
	
	$productcode=$_POST[productcode];
	$product=$_POST[product];
	$unit=$_POST[unit];
    $createby=$_SESSION['login_email'];	
	$createdate=date('Y-m-d');
	
	



if (isset($_POST['getstarted'])){
     $valid = true;
	 
	 
	 
	 
$flag=mysql_query("Select model from inventory_product where brandid='$_GET[brandid]' and model='$_GET[modelid]' and product='$product' and companyid='$_SESSION[companyid]'");
	if ( mysql_num_rows($flag)>0)

    {echo "<script> alert('This Product Name Already Input!! Please Try Another Product') </script>";
        $valid = false;}
		
		


$flag=mysql_query("Select productcode from inventory_product where productcode='$productcode' and companyid='$_SESSION[companyid]'");
	if ( mysql_num_rows($flag)>0)

    {echo "<script> alert('This Productcode is Already Input!! Please Try Another Productcode') </script>";
        $valid = false;}

		
	 
	 
if ($valid){
 $result=mysql_query("INSERT INTO `inventory_product` (inventory_types,categoryid,category,brandid,brand,modelid,model,productcode,product,unit,createby,createdate,companyid) VALUES ('Computer','$category','$category','1','$brand','2','$model','$productcode','$product','$unit','$createby','$createdate','$companyid')"); ?>
	<meta http-equiv="refresh" content="0;inventory_product.php">	
<?php }}



$edit=$_POST[edit];
if(isset($edit)){
	
mysql_query("Update inventory_product SET
category='$category',
brand='$brand',
model='$model',
product='$product',
unit='$unit',
modifiddate='$createdate', 
modifidby='$createby' 

 where productcode='$_GET[producteditcode]' and companyid='$_SESSION[companyid]'");
 
 
 
 
mysql_query("Update transaction_inventory SET 
categorys='$category',
brand='$brand',
model='$model',
product='$product'


 where productcode='$_GET[producteditcode]' and companyid='$_SESSION[companyid]'");
 
 
 
 
 
 
 
?>
<meta http-equiv="refresh" content="0;inventory_product.php">
<?php } ?>               
                  


<?php
						  
$delete=$_GET[productdeletecode];	

if(isset($delete)){
$result=mysql_query("Delete from inventory_product where id='$_GET[productdeletecode]'  and companyid='$_SESSION[companyid]'");	?>
	
	<meta http-equiv="refresh" content="0;inventory_product.php">	
<?php } ?> 

 <?php 
$result=mysql_query("Select * from inventory_product where productcode='$_GET[producteditcode]' and companyid='$_SESSION[companyid]'");
$row=mysql_fetch_array($result);
?>                
                  
                  
                  
                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
               <div class="x_content">
                  
                    <br />
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    
                    
                   
                
                      
                     
 
                      

                                                 
                       <input type="hidden" id="first-name" readonly required="required"  value="Computer" name="inventory_types" class="form-control col-md-7 col-xs-12">
                       
                        
                      
                     
                     
                       
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Inventory Category<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
     <input type="text" id="first-name"  required="required"  value="<?php echo $row[category] ?>" name="category" class="form-control col-md-7 col-xs-12"><?php } else { ?>
                        
    <select class="select2_single form-control" id="first-name" required="required"   name="category" >
     
                <option value="">Choose......</option>
                 <?php 
				$result=mysql_query("Select * from inventory_categorys where   companyid='$_SESSION[companyid]' order by category");
				while($row=mysql_fetch_array($result)){
				if(($_GET[categoryid])==$row[category]){ ?>
                        
                <option selected value="<?php echo $row[category]; ?>"><?php echo $row[category]; ?></option>
                <?php } else { ?>
                <option value="<?php echo $row[category]; ?>"><?php echo $row[category]; ?></option>
                <?php }} ?>
                </select><?php } ?>
                        </div>
                      </div>
                      
                      
                    
                    
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Product Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="product" value="<?php echo $row[product] ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                      
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Inventory Brand<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
     <input type="text" id="first-name"   value="<?php echo $row[brand] ?>" name="brand" class="form-control col-md-7 col-xs-12"><?php } else { ?>
                        
    <select id="first-name"    name="brand" class="select2_single form-control">
     
                <option value="">Choose......</option>
                 <?php 
				$result=mysql_query("Select * from inventory_brand where companyid='$_SESSION[companyid]' order by brand");
				while($row=mysql_fetch_array($result)){
				if(($_GET[brandid])==$row[id]){ ?>
                        
                <option selected value="<?php echo $row[brand]; ?>"><?php echo $row[brand]; ?></option>
                <?php } else { ?>
                <option value="<?php echo $row[brand]; ?>"><?php echo $row[brand]; ?></option>
                <?php }} ?>
                </select><?php } ?>
                        </div>
                      </div>  
                      
                    
                    
                
                    
                    
                    
                    <div class="form-group">
                       <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Inventory Model<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
     <input type="text" id="first-name"    value="<?php echo $row[model] ?>" name="model" class="form-control col-md-7 col-xs-12">
                        
   
                        </div>
                      </div>  
                
                
                
                      
                      
                      
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Product Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($_GET[type]){ ?>
        <input type="text" id="last-name"  required="required" name="productcode" value="<?php echo $row[productcode] ?>" class="form-control col-md-7 col-xs-12">
                          <?php } else { ?>
        <input type="text" id="last-name"  required="required" name="productcode" value="<?php echo $_SESSION['product_code']; ?>" class="form-control col-md-7 col-xs-12">            
                          <?php } ?>
                        </div>
                      </div>
                      
                      
                      
                      
                      
                      
                      
                     <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Unit <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="unit" value="<?php echo $row[unit] ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div-->  
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                        <?php 
						if($_GET[type]){
						?>
                        
                        <a type="cancel" href="inventory_product.php" class="btn btn-primary">Cancel</a>
                        <button type="submit" name="edit" class="btn btn-success">Edit Product</button>
                        <?php } else { ?>
                          
                          <a type="cancel" href="inventory_product.php" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarted" class="btn btn-success">Create Product</button>
                          
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
                    <h2>List of Inventory</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width:5%">SL</th>
                          <th  style="width:7%">Code</th>
                          <th>Product Name</th>
                          
                          <th style="width:8%">Model</th>
                          <th style="width:8%">Brand</th>
                          <th style="width:8%">Category</th>
                          
                          
                          
                          <th style="width:15%">Option</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				$results=mysql_query("Select * from inventory_product order by productcode");
				while($row=mysql_fetch_array($results)){
				$j=$j+1; ?>
                      <tr>
                        
                        <td><?php echo $j; ?></td>
                        <td><?php echo $row[productcode]; ?></td>
                        <td><?php echo $row[product]; ?></td>
                       
                        <td><?php echo $row[model]; ?></td>
                        <td><?php echo $row[brand]; ?></td>
                        <td><?php echo $row[category]; ?></td>
                       
                        
                        
                        
                          <td align="center">
                          
                            <a href="inventory_product.php?type=edit&categoryeditit=<?php echo $row[categoryid] ?>&brandeditid=<?php echo $row[brandid] ?>&modeleditid=<?php echo $row[modelid] ?>&producteditcode=<?php echo $row[productcode] ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                           
                            <!----a href="inventory_product.php?type=delete&categorydeleteid=<?php echo $row[categoryid] ?>&branddeleteid=<?php echo $row[brandid] ?>&modeldeleteid=<?php echo $row[modelid] ?>&productdeletecode=<?php echo $row[id] ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i> Delete </a--->
                            
                            
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
    
    
    
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../vendors/starrr/dist/starrr.js"></script>
 <!-- Select2 -->
    
    
    
    
    
    
    
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
    <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select a state",
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


    <!-- /Datatables -->
  </body>
</html>