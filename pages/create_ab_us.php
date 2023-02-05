<?php
 ob_start();
 session_start();
 require_once 'base.php';
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['user_id']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM register WHERE m_id=".$_SESSION['m_id']);
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

    <title>Batch Mates | Create About Us </title>

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
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Batch Mates</span></a>
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
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
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
            <div class="row" style="width:70%; margin-left:15%">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
  <?php 

				
	$getstarted=$_POST[getstarted];
	$about_us=$_POST[about_us];
	
	$create_date=date('Y-m-d');
	
	
if (isset($_POST['getstarted'])){
     $valid = true;
	 
	 

		
		
	if ($valid){
  $result=mysql_query("INSERT INTO create_about_us 
(about_us,update_date) VALUES 

('$about_us','$create_date')");
	
}}

$edit=$_POST[edit];
if(isset($edit)){
	
$result=mysql_query("Update create_about_us set
about_us='$about_us'");
?>
<meta http-equiv="refresh" content="0;create_ab_us.php">
<?php } ?>               
                  <div class="x_content">
                  
                    <br />
                    <?php 
					$result=mysql_query("Select * from create_about_us where id='$_GET[ab_id]'");
					$row=mysql_fetch_array($result);
					
					?>
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">

                                            
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Details About us<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea id="message" required="required" class="form-control" name="about_us" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="10000" data-parsley-validation-threshold="10"><?php echo $row[about_us] ?></textarea>
                        </div>
                      </div>
                   
                      
                      
                     
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                        <?php 
						if($_GET[type]){
						?>
                        <button type="cancel" class="btn btn-primary">Cancel</button>
                        <button type="submit" name="edit" class="btn btn-success">Edit About Us</button>
                        <?php } else { ?>
                          <button type="cancel" class="btn btn-primary">Cancel</button>
                          <button type="submit" name="getstarted" class="btn btn-success">Create About Us</button>
                          
                          <?php } ?>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>

          

               

                
                    <form id="demo-form" data-parsley-validate></form>
                   


          

  <div class="row" style="width:70%; margin-left:15%">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>About Us List</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
          
 <table class="table table-striped projects">
                      <thead>
                        <tr>
                          <th style="width: 1%">Sl</th>
                          <th style="">About us</th>
                           
                          <th align="center" style="width: 20%">Option</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      
                      <?php 
				$result=mysql_query("Select * from create_about_us");
				while($row=mysql_fetch_array($result)){
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row[about_us]; ?></td>
                        
                          <td align="center">
                          <form method="post" action="create_ab_us.php?dabout_id=<?php echo $row[id] ?>" style="margin:none">
                          <?php
						  
$delete=$_POST[delete];	
if(isset($delete)){
$result=mysql_query("Delete from create_about_us where id='$_GET[dabout_id]'");	?>
	
	<meta http-equiv="refresh" content="0;create_ab_us.php">	
<?php } ?>
                            <a href="create_ab_us.php?type=edit&ab_id=<?php echo $row[id] ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                           
                            
                            <button type="submit" name="delete" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</button>
                            </form>
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
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
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

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

   
  </body>
</html>
<?php ob_end_flush(); ?>