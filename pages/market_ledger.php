<?php require_once 'php_header.php'; ?>
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
    <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.reporttypes.options[form.reporttypes.options.selectedIndex].value;
	self.location='market_ledger.php?reporttypes=' + val ;
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
	$country=$_POST[country];
	$brand=$_POST[brand];
    $createby=$_SESSION['login_email'];	
	$createdate=date('Y-m-d');
	
	



if (isset($_POST['getstarted'])){
     $valid = true;
	 
	 
	$flag=mysql_query("Select brand from brand where brand='$brand' and country='$country'");
	if ( mysql_num_rows($flag)>0)

    {echo "<script> alert('This Brand Name Already Input!! Please Try Another Brand') </script>";
        $valid = false;} 
	 
	 
if ($valid){
 $result=mysql_query("INSERT INTO `brand` (country,brand,createby,createdate,companyid) VALUES ('$country','$brand','$createby','$createdate','$companyid')"); ?>
	<meta http-equiv="refresh" content="0;inventory_brand.php">	
<?php }}



$edit=$_POST[edit];
if(isset($edit)){
	
mysql_query("Update brand SET 
country='$country', 
brand='$brand',
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
$result=mysql_query("Delete from brand where id='$deleteid'");	?>
	
	<meta http-equiv="refresh" content="0;inventory_brand.php">	
<?php } ?> 

 <?php 
$result=mysql_query("Select * from brand where id='$_GET[brandid]'");
$row=mysql_fetch_array($result);
?>                
                  
                  
                  
                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
               <div class="x_content">
                  
                    <br />
                    <form id="demo-form2" method="get" action="reportview.php" data-parsley-validate class="form-horizontal form-label-left">
                    
                    
                   
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Reports<span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="first-name" required="required" size="15"  name="reporttypes" onchange="javascript:reload(this.form)" class="form-control col-md-7 col-xs-12"> 
                        
                        <?php
						$result=mysql_query("Select * from accountsreportname");
						while($rowreport=mysql_fetch_array($result)){
						if(($_GET[reporttypes])==$rowreport[phpname]){
						
						 ?> 
                                         
                        <option selected value="<?php echo $rowreport[phpname]; ?>"><?php echo $rowreport[viewname]; ?></option>
                        
                        <?php } else { ?>
                        
                         <option  value="<?php echo $rowreport[phpname]; ?>"><?php echo $rowreport[viewname]; ?></option>
                       
                        <?php }} ?>
                        </select>
                        
                        </div>
                      </div>
                      
                      
                      
                      
                      
                    
                      
                      
                   
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                     
                       
                       
                       
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Market Ladger<span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="first-name" required="required"  name="ledgercode" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose......</option>
                        <?php
						$result=mysql_query("Select * from accounts_ledger where subsidiary='Market Bill' and companyid='$_SESSION[companyid]' order by ledger");
						while($rows=mysql_fetch_array($result)){ ?>
						 <option value="<?php echo $rows[ledgercode]; ?>"><?php echo $rows[ledger]; ?></option>
						<?php } ?>
                        </select>
                        
                        </div>
                      </div>
                      
                      
                    
                       
                       
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="datefrom" value="<?php echo date('Y-m-01') ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                 
                 
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="dateto" value="<?php echo date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
            
                      
                      
                
                      
                     
                      
                      
                      
                      
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                          
                          <button type="submit" name="getstarted" class="btn btn-success" value="reportview">View Report</button>
                          
                         
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>

          <!--form id="demo-form" data-parsley-validate></form--->
 

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
   <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#single_cal1').daterangepicker({
          singleDatePicker: true,
          singleClasses: "picker_1"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
        $('#single_cal2').daterangepicker({
          singleDatePicker: true,
          singleClasses: "picker_2"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
        $('#single_cal3').daterangepicker({
          singleDatePicker: true,
          singleClasses: "picker_3"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
        $('#single_cal4').daterangepicker({
          singleDatePicker: true,
          singleClasses: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        $('#reservation').daterangepicker(null, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });

        $('#reservation-time').daterangepicker({
          timePicker: true,
          timePickerIncrement: 30,
          locale: {
            format: 'MM/DD/YYYY h:mm A'
          }
        });
      });
    </script>
    <!-- /bootstrap-daterangepicker -->
  </body>
</html>