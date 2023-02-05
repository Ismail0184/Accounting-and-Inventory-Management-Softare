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

    <title><?php echo $_SESSION[company]; ?> | Date Change </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Ion.RangeSlider -->
    <link href="../vendors/normalize-css/normalize.css" rel="stylesheet">
    <link href="../vendors/ion.rangeSlider/css/ion.rangeSlider.css" rel="stylesheet">
    <link href="../vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
    <!-- Bootstrap Colorpicker -->
    <link href="../vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">

    <link href="../vendors/cropper/dist/cropper.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Gentellela Alela!</span></a>
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
            

       





            <div class="row">
              <div class="col-md-12">

                <!-- form date pickers -->
                <div class="x_panel" style="">
                  <div class="x_title">
                    <h2>Date Change</h2>
                     <ul class="nav navbar-right panel_toolbox">

                     <div class="input-group pull-right">

								<a class="btn btn-sm btn-default"  href="reportview.php?reporttypes=ladger&ledgercode=<?php echo $_GET[ledgercode]; ?>&subledgercode=<?php echo $_GET[subledgercode]; ?>&datefrom=<?php echo $_GET[datefrom]; ?>&dateto=<?php echo $_GET[dateto]; ?>&getstarted=">

									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Back To Report</span>

								</a>

								</div>

                    </ul>
                    <div class="clearfix"></div>
                  </div>
                 

                      
<form id="demo-form2" method="get" data-parsley-validate class="form-horizontal form-label-left" action="reportview.php?getstarted=">
                      
                         

                        
                           <div class="form-group">
                           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >From<span class="required"></span></label>
                            
                             <div class="col-md-6 col-sm-6 col-xs-12">
                             
                             
                             <input type="hidden" class="form-control col-md-7 col-xs-12" id="first-name" name="reporttypes"  placeholder="Year-month-date"  value="ladger">
                             
                             <input type="hidden" class="form-control col-md-7 col-xs-12" id="first-name" name="ledgercode"  placeholder="Year-month-date"  value="<?php echo $_GET[ledgercode]; ?>">
                             
                             
                             <input type="hidden" class="form-control col-md-7 col-xs-12" id="first-name" name="subledgercode"  placeholder="Year-month-date"  value="<?php echo $_GET[subledgercode]; ?>">
                             
                             
                             
                             
                             
                             
                             
                                <input type="text" class="form-control col-md-7 col-xs-12" id="first-name" name="datefrom" required="required" placeholder="Year-month-date"  value="<?php echo date('Y-m-d'); ?>">
                                
                            </div>
                          </div>
                        
                        
                        
                        
                        
                         
                           <div class="form-group">
                           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >To<span class="required"></span></label>
                           
                           <div class="col-md-6 col-sm-6 col-xs-12">
                              
                                <input type="text" class="form-control col-md-7 col-xs-12" id="first-name" name="dateto" required="required" placeholder="year-month-date"  value="<?php echo date('Y-m-d'); ?>">
                                
                            </div>
                          </div>
                    
                        
                        
                        
                        
                        
                        
                        
                        
                          <div class="form-group">
                   <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                    

                  
						<button type="submit" name="getstarted" value="Date Apply" class="btn btn-success">Date Apply</button>
							

                        </div></div>
                        
                     
                      
                      
                      
                      
                      
                      
                       
</form>
                      


                       
                      </div>
                    </div>

                  </div>
                </div>
                <!-- /form datepicker -->


               

               
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
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- Ion.RangeSlider -->
    <script src="../vendors/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>
    <!-- Bootstrap Colorpicker -->
    <script src="../vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <!-- jquery.inputmask -->
    <script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jQuery Knob -->
    <script src="../vendors/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- Cropper -->
    <script src="../vendors/cropper/dist/cropper.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- bootstrap-daterangepicker -->
   

    

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