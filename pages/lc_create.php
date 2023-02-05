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
 $res=mysql_query("SELECT * FROM users WHERE companyid=".$_SESSION['companyid']);
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

    <title><?php echo $_SESSION[company]; ?> | Create LC Documents</title>

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
    
    
    <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.rqnom.options[form.rqnom.options.selectedIndex].value;
	self.location='purchase_order.php?getrqno=' + val ;
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
               
             </div>

            <div class="clearfix"></div>

            <div class="row">
              

              


 <?php 

				
	$getstarted=$_POST[getstarted];
	$companyid=$_SESSION['companyid'];
	$lcid=$_POST[lcid];
	$billno=$_POST[billno];
	$bankname=$_POST[bankname];
	$warehouse=$_POST[warehouse];
	$Note=$_POST[Note];
	$category=$_POST[category];
	$product=$_POST[product];
	$productcode=$_POST[productcode];
    $createby=$_SESSION['login_email'];	
	$createdate=date('Y-m-d');
	
	



 ?>

     


           
                  
                  
                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
                  <div class="x_title">
                    <h2>Create LC Documents</h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<!--a class="btn btn-sm btn-default"  href="inventory_matarial_category.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Category</span>
								</a-->
                    			
                    			               
                    			
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
               <div class="x_content">
                  
                    <br />
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    
                   
                
                <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">LC Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="last-name"  required="required" name="lcdate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">

                      </div>  
	                </div>
                
                
                      
                   
                <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">LC ID<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="last-name"  required="required" name="lcid" value="LC-<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">

                      </div>  
	                </div>     
                     
                  
                    
                   
                   
          <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bank Name<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="last-name"  required="required" name="bankname" value="" class="form-control col-md-7 col-xs-12">

                      </div>  
	                </div>       
                    
                    
                   
                   
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Note<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea id="last-name" name="note" class="form-control col-md-7 col-xs-12"><?php echo $rowsmaingroup[Note]; ?></textarea>
                        </div>
                      </div>
                   
                   
                   
                   <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                   
                   
                   
                  <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width:5%">Code</th>
                          <th>Product</th>
                          <th style="width:5%">Unit</th>
                          
                          
                          
                          
                          
                          <th style="width:10%">Qty</th>
                          <th style="width:10%">Net<br>Weight</th>
                          <th style="width:10%">Gross<br>Weight</th>
                          <th style="width:10%">Unite<br>Price</th>
                         
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				$results=mysql_query("Select * from inventory_product where  companyid='$_SESSION[companyid]'");
				while($row=mysql_fetch_array($results)){
				$ids=$row[productcode];
				$product=$row[product];
				$categoryid=$row[categoryid];
				$category=$row[category];
				$brandid=$row[brandid];
				$brand=$row[brand];
				$modelid=$row[modelid];
				$model=$row[model];
				$unit=$row[unit];
				$inventory_types=$row[inventory_types];
				$itemcode=$_POST[itemcode.$ids];
				$qtys=$_POST[qtys.$ids];
				$netw=$_POST[netw.$ids];
				$grossw=$_POST[grossw.$ids];
				
				$rate=$_POST[rate.$ids];
				$amount=$qtys*$rate;
				$amounts=$amounts+$amount;
	            
	
	
	
	
if (isset($_POST['getstarted'])){
$valid = true;
	 



	 
	 
if ($valid){
	if($qtys>0){
 $result=mysql_query("INSERT INTO `lc_documents` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,bankname,qty,rate,amount,netw,note,grossw,companyid,transactionby,transactiontype,ttime,tstatus,tdate,ip,mac,tfor) VALUES 
 ('$inventory_types','$categoryid','$category','$brandid','$brand','$modelid','$model','$ids','$product','$unit','$lcid','$bankname','$qtys','$rate','$amount','$netw','$note','$grossw','$companyid','$createby','LC Documents','$ttime','Normal','$createdate','$ip','$mac','LC Documents')");
 
  ?>
<meta http-equiv="refresh" content="0;lc_list.php">	
<?php }}}
				
				
				









				
				 ?>
                      <tr>
                        
                        <td><?php echo $row[productcode]; ?></td>
                        <td><?php echo $row[product]; ?></td>
                        <td><?php echo $row[unit]; ?></td>
                       
                        
                        
                        
                        
                        
                     <td align="center">
                     
                     <input type="hidden" id="last-name" style="width:120px" name="itemcode<?php echo $ids; ?>" value="<?php echo $row[itemcode]; ?>" class="form-control col-md-7 col-xs-12">
                     
                     <input type="text" id="last-name" style="width:80px" name="qtys<?php echo $ids; ?>" value="<?php echo $row[qty] ?>" class="form-control col-md-7 col-xs-12"></td>
                     
                     
                    <td align="center"><input type="text" id="last-name"  style="width:80px" name="netw<?php echo $ids; ?>" value="<?php echo $row[netw] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    
                  <td align="center"><input type="text" id="last-name"  style="width:80px" name="grossw<?php echo $ids; ?>" value="<?php echo $row[grossw] ?>" class="form-control col-md-7 col-xs-12"></td>   
                     
                     
                      
                     <td align="center"><input type="text" id="last-name"  style="width:80px" name="rate<?php echo $ids; ?>" value="<?php echo $row[rate] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    
                      
                      
                        </tr>
                        <?php } ?>
                        
                        
                      
                      </tbody>
                    </table>  
                   
   <?php                 
 $presult=mysql_query("Select * from accounts_ledger where ledger='Purchase' and companyid='$_SESSION[companyid]'");
$prow=mysql_fetch_array($presult);

$mcresult=mysql_query("Select * from accounts_ledger where ledger='Main Cash' and companyid='$_SESSION[companyid]'");
$mcrow=mysql_fetch_array($mcresult);


if (isset($_POST['getstarted'])){
$valid = true;
	 



	 
	 
if ($valid){
				
			
	$result=mysql_query("INSERT INTO `transaction_cash` (rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Purchase','$billno','$createdate','','','$amounts','$amounts','','Main Cash, Purchase from  $supplier','1','','','','$companyid','','$createby','$ip','$mac','$createdate','$ttime','Cash Purchase','$invoice','Normal','$day')");		
 
 
 
 
 $result=mysql_query("INSERT INTO `transaction_cash` (rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','Main Cash','$billno','$createdate','','','-$amounts','','$amounts','Purchase from  $supplier','0','','','','$companyid','','$createby','$ip','$mac','$createdate','$ttime','Cash Purchase','$invoice','Normal','$day')");
 ceshexpensesid();

	}}      ?>                
                  <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                       
                          <a href="purchase_order.php" type="cancel" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarted" class="btn btn-success">Purchase </button>
                          
                          
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