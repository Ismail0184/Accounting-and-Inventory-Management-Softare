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



    <title><?php echo $_SESSION[company]; ?> | Market Stock Out</title>



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

	var val=form.vendor.options[form.vendor.options.selectedIndex].value;

	self.location='market_stock_out.php?ledgercode=' + val ;

}





</script>    

<style>
/* unvisited link */
p:link {
    color: red;
}

/* visited link */
p:visited {
    color: green;
}

/* mouse over link */
p:hover {
    color: hotpink;
}

/* selected link */
p:active {
    color: blue;
}
</style>

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

           



            <div class="clearfix"></div>
            <div class="row">

              



              





 <?php 

			

	$getstarted=$_POST[getstarted];
	$companyid=$_SESSION['companyid'];
	$invoice=$_POST[invoice];
	$billno=$_POST[billno];
	$vendor=$_POST[vendor];
	$warehouse=$_POST[warehouse];
	$Note=$_POST[Note];
	$category=$_POST[category];
	$product=$_POST[product];
	$productcode=$_POST[productcode];
	$createby=$_SESSION['login_email'];
	$createdate=date('Y-m-d');

	$vendorsearch=mysql_query("Select * from accounts_ledger where ledgercode='$vendor' and companyid='$_SESSION[companyid]'");
	$vendorrow=mysql_fetch_array($vendorsearch);
	$vendorphone=$_POST[vendorphone];
	$vendoraddress=$_POST[vendoraddress];

	

 ?>



     





           

                  

                  



                  

                  

              <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel"> 

                  <div class="x_title">

                    <h2>Market Stock Out</h2>

                    <ul class="nav navbar-right panel_toolbox">

                     <div class="input-group pull-right">

					<a class="btn btn-sm btn-default"  href="market_ledger.php?reporttypes=ladger">
                      <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Market Bill Ledger</span></a>
                      
                      <a class="btn btn-sm btn-default" style="color:#000" href="market_bill_collection.php">
                      <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Market Bill Collection</span>
                      </a></div></ul>

                    <div class="clearfix"></div>

                  </div>

               <div class="x_content">

                  

                    <br />

                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">

                    

                   

                

                <div class="form-group">

                   

                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sales Date<span class="required">*</span></label>

                   <div class="col-md-6 col-sm-6 col-xs-12">

	                	

	            <input type="text" id="last-name"  required="required" name="purchasedate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">



                      </div>  

	                </div>

                

                

                      

                     

                    <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Invoice Number<span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="last-name"   required="required" name="invoice" value="<?php echo $_SESSION['csalesno']; ?>" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>  

                      

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bill Number<span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="last-name"   required="required" name="billno" value="<?php echo $_SESSION['crid']; ?>" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      

                           

                      

                      

                      

                      

                      

                      

                   <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Stock Send To<span class="required">*</span></label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <select class="select2_single form-control" style="width:100%" tabindex="-1" onchange="javascript:reload(this.form)"  required="required" name="vendor" > 

                            <option value="">Choose ......</option>

                            

                            <?php 

							$result=mysql_query("Select * from accounts_ledger where companyid='$_SESSION[companyid]' and subsidiary='Market Bill' order by ledger");

							while($row=mysql_fetch_array($result)){

								

								if(($_GET[ledgercode])==$row[ledgercode]){?> 

                      

                      <option selected value="<?php echo $row[ledgercode]; ?>"><?php echo $row[ledger]; ?></option>

                        <?php } else { ?>

                  <option  value="<?php echo $row[ledgercode]; ?>"><?php echo $row[ledger]; ?></option>

                    <?php }} ?>

                          </select> <?php if($_GET[ledgercode]) { ?>

                          <?php 

			  $cashbalance=getSVALUE("transaction_cash", "SUM(Amount) as Amount", "where  ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]'");

			  ?>

                          Due Amount <b style="font-weight:bold; color:#F00">(<?php echo $cashbalance; ?> ৳)</b> <a href="sales_partywise_details.php?ledgercode=<?php echo $_GET[ledgercode]; ?>" style="color:#006; font-weight:bold" target="_new">  Previouse Sales Record</a>

                          <?php } ?>

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

                          

                       

                         

                          

                          

                          <th style="width:10%">Stock <br>Balance</th>
                          <th style="width:10%">Purchase <br>Rate</th>

                          <th style="width:10%">Qty</th>

                          <th style="width:10%">Rate</th>

                         

                        </tr>

                      </thead>





                      <tbody>

                        <?php 

				//$results=mysql_query("Select * from inventory_product where  companyid='$_SESSION[companyid]'");

				///while($row=mysql_fetch_array($results)){
					
					
					
					
					
					
					
					
					$productresult=mysql_query("SELECT distinct productcode FROM transaction_inventory where  companyid='$_SESSION[companyid]' and tfor='Purchase' order by productcode ");
					 while($productrow=mysql_fetch_array($productresult)){
						 
						 
						 $results=mysql_query("Select * from transaction_inventory where productcode='$productrow[productcode]' and  companyid='$_SESSION[companyid]' and tfor='Purchase' order by productcode");
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

				$sbalance=$_POST[sbalance.$ids];

				$qtys=$_POST[qtys.$ids];

				$rate=$_POST[rate.$ids];

				$amount=$qtys*$rate;

				$amounts=$amounts+$amount;

	            

	

	

	

	

if (isset($_POST['getstarted'])){

$valid = true;

	 





if ( $qtys>$sbalance)



    {echo "<script> alert('This $row[product] is not available more then $sbalance qty!!') </script>";

        $valid = false;}	



	 

	 

if ($valid){

	if($qtys and $rate>0){

 $result=mysql_query("INSERT INTO `transaction_inventory` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,purchaseclint,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,vendor,vendorphone,vendoraddress,vendorid) VALUES 

 ('$inventory_types','$categoryid','$category','$brandid','$brand','$modelid','$model','$ids','$product','$unit','$invoice','$billno','$Jvoucherno','$vendor','-$qtys','$rate','$amount','$coss','$amtcoss','$adjustlevel','$adjustamount','$lifofifoid','$note','$warehouse','$acchead','Cash Received','$companyid','$createby','Credit Sales','$ttime','Normal','$createdate','$tmodifiddate','$modifiedby','$ip','$mac','Sales','$vendorrow[ledger]','$vendorphone','$vendoraddress','$vendor')");

 cashsalesno();

  ?>

	

<?php }}}

				



	$stockbalance=getSVALUE("transaction_inventory", "SUM(qty) as qty", "where productcode='$row[productcode]'  and companyid='$_SESSION[companyid]'");

			  ?>














<?php
if($stockbalance>0){

 ?>

                      <tr>

                        

                        <td><?php echo $row[productcode]; ?></td>

                        <td><?php echo $row[categorys]; ?> <?php echo $row[brand]; ?> <?php echo $row[model]; ?> - <?php echo $row[product]; ?></td>

                        


                        

                        

                        



                        

                        

 <td align="center"><input type="text" readonly id="last-name"  style="width:80px; color:#F00; font-weight:bold; text-align:center" name="sbalance<?php echo $ids; ?>" value="<?php if($stockbalance>0)  { echo $stockbalance; } else { echo 0;} ?>" class="form-control col-md-7 col-xs-12"></td>

                        
 <?php
					 
					 $pr=$row[rate];
					 $prs=number_format($pr,2);
					  ?>
                     <td align="right" style="font-weight:bold; color:transparent"><p ><?php echo $prs ?></p></td>
                        

                     <td align="center">

                     

                     <input type="hidden" id="last-name" style="width:120px" name="itemcode<?php echo $ids; ?>"  class="form-control col-md-7 col-xs-12">

                     

                     <input type="text" id="last-name" style="width:80px" name="qtys<?php echo $ids; ?>"  class="form-control col-md-7 col-xs-12">

                     

                     

                     </td>

                      

                     <td align="center"><input type="text" id="last-name"  style="width:80px" name="rate<?php echo $ids; ?>"  class="form-control col-md-7 col-xs-12"></td>

                      

                    

                      

                      

                        </tr>

                        <?php }} }?>

                        

                        

                      

                      </tbody>

                    </table>  

                   

   <?php                 

 $presult=mysql_query("Select * from accounts_ledger where ledger='Sales' and companyid='$_SESSION[companyid]'");

$prow=mysql_fetch_array($presult);



$mcresult=mysql_query("Select * from accounts_ledger where ledgercode='$vendor' and companyid='$_SESSION[companyid]'");

$mcrow=mysql_fetch_array($mcresult);





if (isset($_POST['getstarted'])){

$valid = true;

	 





$flag=mysql_query("Select invoiceno from transaction_inventory where invoiceno='$invoice' and companyid='$_SESSION[companyid]'");

	if ( mysql_num_rows($flag)==0)

{echo "<script> alert('Opps!! Invaild Transaction!!') </script>";

        $valid = false;}





	 

	 

if ($valid){

				

				

	$result=mysql_query("INSERT INTO `transaction_cash` (rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 

 ('$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Sales','$billno','$createdate','','','-$amounts','','$amounts','Main Cash, Sales To  $vendor','0','','','','$companyid','','$createby','$ip','$mac','$createdate','$ttime','Cash Sales','$invoice','Normal','$day')");		

 

 

 

 

 $result=mysql_query("INSERT INTO `transaction_cash` (rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 

 ('$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','$mcrow[ledger]','$billno','$createdate','','','$amounts','$amounts','','Sales to  $supplier','1','','','','$companyid','','$createby','$ip','$mac','$createdate','$ttime','Cash Sales','$invoice','Normal','$day')");

 

		

	} ?>

	<meta http-equiv="refresh" content="0;sales_credit.php">

<?php 	}      ?>                

                  <div class="form-group">

                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                        

                       

                          <a href="purchase_order.php" type="cancel" class="btn btn-primary">Cancel</a>

                          <button type="submit" name="getstarted" class="btn btn-success"> Stock Out </button>

                          

                          

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