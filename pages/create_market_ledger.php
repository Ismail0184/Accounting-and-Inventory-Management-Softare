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

    <title><?php echo $_SESSION[company]; ?> | Create Market Ledger</title>

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
	$sname=$_POST[sname];
	$cp=$_POST[cp];
	$cno=$_POST[cno];
	$address=$_POST[address];
	$email=$_POST[email];
	$website=$_POST[website];
	$phone=$_POST[phone];
	$fax=$_POST[fax];
	$country=$_POST[country];
    $createby=$_SESSION['login_email'];	
	$createdate=date('Y-m-d');
	$ledgerid=$_SESSION['ledger_id'];
	

	
if (isset($_POST['getstarted'])){
$valid = true;
	 

 
	 
if ($valid){
	
 $result=mysql_query("INSERT INTO `market_ledger` (ledgercode,sname,cp,cno,address,email,website,phone,fax,country,createby,createdate,companyid) VALUES 
 ('$ledgerid','$sname','$cp','$cno','$address','$email','$website','$phone','$fax','$country','$createby','$createdate','$_SESSION[companyid]')"); 
 
 
$result=mysql_query("INSERT INTO `accounts_ledger` 
(accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,companyid,createby,createdate,ip,mac) VALUES('Balance Sheet','RL170222002','Property and Assets','MG170222004','Current Assets','SUB170222002','Market Bill','$ledgerid','$sname','$companyid','$createby','$createdate','$ip','$mac')");
 
 
 } ?>
  <meta http-equiv="refresh" content="0;create_market_ledger.php">
<?php }
 ?>
	


     
<?php
////////////////////////////////////// data edit function start from here-----------------------------------------
$edit=$_POST[edit];
if(isset($edit)){
	
mysql_query("Update `sales_customer` SET 
sname='$sname',
cp='$cp',
cno='$cno',
address='$address', 
email='$email',
website='$website',
phone='$phone',
fax='$fax',
country='$country',
modifiredby='$inputby', modifieddate='$create_date' where ledgercode='$_GET[ledgereditid]' and companyid='$_SESSION[companyid]'");
?>
<meta http-equiv="refresh" content="0;sales_customer_list.php">
<?php } 





////////////////////////////////////// data Delete function start from here-----------------------------------------

if($_GET[subsidiarydeleteid]){
	$deleteid=$_GET[subsidiarydeleteid];
		$delete=mysql_query("Delete from sales_customer where rlid='$_GET[rleditdeleteid]' and companyid='$_SESSION[companyid]'"); ?>
        <meta http-equiv="refresh" content="0;supplier.php"> 
		<?php  } ?>
        
        
        
        
  <?php
if($_GET[ledgereditid]){  

$result=mysql_query("Select * from sales_customer where ledgercode='$_GET[ledgereditid]' and companyid='$_SESSION[companyid]'");
$rowsmaingroup=mysql_fetch_array($result);
}
?> 

           
                  
                  
                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
                <div class="x_title">
                    <h2>Create Market Ledger</h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="market_ledger.php?reporttypes=ladger">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Market Ledger</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="market_bill_collection.php">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Market Bill Collection</span>
                    			</a>
		 						
								</a>
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
               <div class="x_content">
                  
                    <br />
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    
                   
                 
                 
                 
                 
                 <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Market Ledger Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<input type="text" id="first-name" required="required"  name="sname" value="<?php echo $rowsmaingroup[sname]; ?>" class="form-control col-md-7 col-xs-12" >
                        </div></div>
                        
                        
                        
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Person<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   required="required" name="cp" value="<?php echo $rowsmaingroup[cp]; ?>"  class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>  
                      
                      
                     
                     
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Contact Number<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   required="required" name="cno" value="<?php echo $rowsmaingroup[cno]; ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div> 
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Address<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea id="last-name"   required="required" name="address" class="form-control col-md-7 col-xs-12"><?php echo $rowsmaingroup[address]; ?></textarea>
                        </div>
                      </div>
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="email" id="last-name"   required="required" name="email" value="<?php echo $rowsmaingroup[email]; ?>"  class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                      <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Website<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   name="website" value="<?php echo $rowsmaingroup[website]; ?>"  class="form-control col-md-7 col-xs-12">
                        </div>
                      </div-->
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Phone<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   name="phone" value="<?php echo $rowsmaingroup[phone]; ?>"  class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                      
                      <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fax<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   name="fax" value="<?php echo $rowsmaingroup[fax]; ?>"  class="form-control col-md-7 col-xs-12">
                        </div>
                      </div--->
                      
                      
                      
                      <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Country<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        <input type="text" id="first-name" required="required" value="<?php echo $rowsmaingroup[country] ?>" name="country" class="form-control col-md-7 col-xs-12" ><?php } else { ?>
                        <select id="first-name" required="required"   name="country" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                         
                                         
                        <option  value="Bangladesh">Bangladesh</option>
                        <option value="Bangladesh">India</option>
                        </select><?php } ?></div></div--->
                      
                       <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                       
                          
                          
                          <?php if($_GET[type]){ ?>

                        <a href="supplier.php" type="cancel" class="btn btn-primary">Cancel</a>
						<button type="submit" name="edit" class="btn btn-success">Edit</button>

                        <?php } else { ?>

                        <a href="supplier.php" type="cancel" class="btn btn-primary">Cancel</a>
						<button type="submit" name="getstarted" class="btn btn-success">Save</button>
							<?php } ?>
                          
                        </div>
                      </div>  
                   
                   
               
                      
                      

                    </form>
                  </div>
                </div>
              </div>
            

        
               

                
                    <form id="demo-form" data-parsley-validate></form>
 


 
              
              
              
              
              
           
        
        
        
        
        
 
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Market Ledger List</h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="accountsreport.php?reporttypes=ladger">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Customer Ledger</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="sales_customer.php">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Create Customer</span>
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
                           <th style="width:5%">SL</th>
                          <th style="width:5%">Code</th>
                          <th>Ledger</th>
                          <th style="width:10%">Name</th>
                          <th style="width:10%">Phone</th>
                          <th style="width:10%">Email</th>
                          <th style="width:15%">Address</th>
                          <th style="width:15%">Option</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				$result=mysql_query("Select * from market_ledger order by ledgercode");
				while($row=mysql_fetch_array($result)){
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row[ledgercode]; ?></td>
                        <td><?php echo $row[sname]; ?></td>
                        <td><?php echo $row[cp]; ?></td>
                        <td><?php echo $row[cno]; ?></td>
                        <td><?php echo $row[email]; ?></td>
                        <td><?php echo $row[address]; ?></td>
                          <td align="center">
<a href="sales_customer.php?type=edit&ledgereditid=<?php echo $row[ledgercode] ?>" class="btn btn-primary btn-xs">
<i class="fa fa-pencil"></i> Edit </a>
                           
<a href="sales_customer.php?type=delete&ledgerdeleteid=<?php echo $row[ledgercode] ?>" class="btn btn-danger btn-xs">
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