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
 
?>

<!DOCTYPE html>

<html lang="en">

  <head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Meta, title, CSS, favicons, etc. -->

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <title><?php echo $_SESSION[company]; ?> | Service </title>



    <!-- Bootstrap -->

    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->

    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- NProgress -->

    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">

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

              <a href="dashboard.php" class="site_title"><i class="fa fa-paw"></i> <span>Raresoft</span></a>

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

            <?php include("menu_footer.php"); ?>

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

              <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                  <div class="x_title">

                    <h2>New Service Entry</h2>

                   

                    <div class="clearfix"></div>

                  </div>

                  

                  

                  

                  

                  

                  

                  

                  

                  

                  

                  

                  

                  

                  

                  <div class="x_content">

                   

                    <div class="col-md-9 col-sm-9 col-xs-12">





                      <div class="" role="tabpanel" data-example-id="togglable-tabs">

                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">

<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Computer & Laptop</a></li>

<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Mobile</a></li>

                        </ul>

                       

                       

                       

                       

                       

                       

                       

                        <div id="myTabContent" class="tab-content">

                          

                          

                          

                          

                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">



 

 <?php 

 ////////// Computer Service code start from here

 





				

	$getstarted=$_POST[getstarted];

	$getstarted2=$_POST[getstarted2];

	$getstarted3=$_POST[getstarted3];

	$serviceid=$_SESSION['srid'];

	$brand=$_POST[brand];

	$model=$_POST[model];

	$ime=$_POST[ime];

	$cp=$_POST[cp];

	$cn=$_POST[cn];

	$address=$_POST[address];

	$dd=$_POST[dd];


$power=$_POST[power];
$display=$_POST[display];
$Processor=$_POST[Processor];
	
$ram=$_POST[ram];
$HDD=$_POST[HDD];
$hang=$_POST[hang];
$ssetup=$_POST[ssetup];
$productdetails='RAM-'.$ram.','.'Hard Disk-'.$HDD.','. 'Processor-'.$Processor;
	$sc=$_POST[sc];

	$inputby=$_SESSION['login_email'];

	$companyid=$_SESSION['companyid'];
    

    $note=$_POST[note];
	$delivarystatus='Null';

	$accounthead='Revenue from Services';

	$subhead='Computer';

	

	$create_date=date('Y-m-d');

	

	











if (isset($_POST['getstarted'])){

     $valid = true;

	 

	 

if ($valid){

 $result=mysql_query("INSERT INTO service 

(serviceid,brand,model,ime,cp,cn,address,dd,sc,servicetype,inputby,companyid,createdate,accounthead,subhead,delivarystatus,note,productdetails,power,display,hang,ssetup) VALUES 



('$serviceid','$brand','$model','$ime','$cp','$cn','$address','$dd','$sc','Computer','$inputby','$companyid','$create_date','$accounthead','$subhead','$delivarystatus','$note','$productdetails','$power','$display','$hang','$ssetup')");

	serviceid();

}}





if (isset($_POST['getstarted3'])){

     $valid = true;

	 

	 

if ($valid){

 $result=mysql_query("INSERT INTO service 

(serviceid,brand,model,ime,cp,cn,address,dd,sc,servicetype,inputby,companyid,createdate,accounthead,subhead,delivarystatus,productdetails) VALUES 



('$serviceid','$brand','$model','$ime','$cp','$cn','$address','$dd','$sc','Mobile','$inputby','$companyid','$create_date','$accounthead','Mobile','$delivarystatus','$productdetails')");

	serviceid();

}}









$edit=$_POST[edit];

if(isset($edit)){

	

mysql_query("Update service SET 



brand='$brand',
note='$note',
model='$model',

ime='$ime',

cp='$cp',

cn='$cn',

cn='$cn',

address='$address',

dd='$dd',

sc='$sc'



 where serviceid='$_GET[serviceid]' and companyid='$_SESSION[companyid]'");

?>

<meta http-equiv="refresh" content="0;servicelist.php">

<?php } ?> 

 

 

 

 

 

 

 

 

 

 

 

 

 

                            

                            

                            

                            <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">

                            

                            <?php

							if($_GET[servicetype]=='Computer'){

$resultcom=mysql_query("Select * from service where serviceid='$_GET[serviceid]' and companyid='$_SESSION[companyid]'");

$rowcom=mysql_fetch_array($resultcom);

?> 

<?php } ?>



                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Brand <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowcom[brand] ?>" name="brand" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Model <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowcom[model] ?>" name="model" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Serial Number <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowcom[ime] ?>" name="ime" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>
                      
                      
                      
                      
                     
                      
                      
                      
                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Details <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          
                          <input type="text" placeholder="RAM" id="first-name"  style="width:105px"  name="ram" class="form-control col-md-7 col-xs-12">
                          <input type="text" placeholder="HDD" id="first-name"  style="width:105px; margin-left:20px"  name="HDD" class="form-control col-md-7 col-xs-12">
                          <input type="text" placeholder="Processor" id="first-name"  style="width:107px;margin-left:20px"  name="Processor" class="form-control col-md-7 col-xs-12">
                         

                        </div>

                      </div>
                      
                      
                      

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Contact Person <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowcom[cp] ?>" name="cp" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Contact Number <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowcom[cn] ?>" name="cn" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Address <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                           <textarea id="first-name" required="required" value="" name="address" class="form-control col-md-7 col-xs-12"><?php echo $rowcom[address] ?></textarea>

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Delivery Date <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowcom[dd] ?>" placeholder="year-month-day" name="dd" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Service Charge <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowcom[sc] ?>" name="sc" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                   


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Power<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        
                        <input type="text" id="first-name" required="required" value="" name="power" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required"   name="power" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        </select><?php } ?>
                        </div></div>




                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Display<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        
                        <input type="text" id="first-name" required="required" value="" name="display" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required"   name="display" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        </select><?php } ?>
                        </div></div>




<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Hang<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        
                        <input type="text" id="first-name" required="required" value="" name="hang" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required"   name="hang" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        </select><?php } ?>
                        </div></div>







                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Software Setup<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        
                        <input type="text" id="first-name" required="required" value="" name="ssetup" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required"   name="ssetup" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        </select><?php } ?>
                        </div></div>











                <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Others <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                           <textarea id="first-name" required="required" value="" name="note" class="form-control col-md-7 col-xs-12"><?php echo $rowcom[note] ?></textarea>

                        </div>

                      </div>     

                      

                     

                      <div class="ln_solid"></div>

                      <div class="form-group">

                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                        

                        <?php 

						if($_GET[type]){

						?>

                        <button type="cancel" class="btn btn-primary">Cancel</button>

                        <button type="submit" name="edit" class="btn btn-success">Edit Computer Service</button>

                        <?php } else { ?>

                          <button type="cancel" class="btn btn-primary">Cancel</button>

                          <button type="submit" name="getstarted" class="btn btn-success">Add Computer Service</button>

                          

                          <?php } ?>

                        </div>

                      </div>



                    </form>

                            

                           <br>

                           

                            



                          </div>

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                         

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          

                          <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">

                             <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">

                             

                             

                              <?php

							if($_GET[servicetype]=='Mobile'){

$resultmobile=mysql_query("Select * from service where serviceid='$_GET[serviceid]' and companyid='$_SESSION[companyid]'");

$rowmobile=mysql_fetch_array($resultmobile);

?> 

<?php } ?>



                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Mobile Brand <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowmobile[brand] ?>" name="brand" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Model <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowmobile[model] ?>" name="model" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">IME Number <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowmobile[ime] ?>" name="ime" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      




 <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Note <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name"  value="<?php echo $rowcom[note] ?>" name="note" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Contact Person <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowmobile[cp] ?>" name="cp" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Contact Number <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowmobile[cn] ?>" name="cn" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Address <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowmobile[address] ?>" name="address" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  for="first-name">Delivery Date <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" placeholder="year-month-day" value="<?php echo $rowmobile[dd] ?>" name="dd" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                      

                      

                      <div class="form-group">

                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Service Charge <span class="required">*</span>

                        </label>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <input type="text" id="first-name" required="required" value="<?php echo $rowmobile[sc] ?>" name="sc" class="form-control col-md-7 col-xs-12">

                        </div>

                      </div>

                   

                      

                      

                     

                      <div class="ln_solid"></div>

                      <div class="form-group">

                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                        

                        <?php 

						if($_GET[type]){

						?>

                        <button type="cancel" class="btn btn-primary">Cancel</button>

                        <button type="submit" name="edit" class="btn btn-success">Edit Mobile Service</button>

                        <?php } else { ?>

                          <button type="cancel" class="btn btn-primary">Cancel</button>

                          <button type="submit" name="getstarted3" class="btn btn-success">Add Mobile Service</button>

                          

                          <?php } ?>

                        </div>

                      </div>



                    </form>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

              </div>

              

              

              

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                  <div class="x_title">

                    <h2>Today's Service List</h2>

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

					   $today=date("Y-m-d");

				$result=mysql_query("Select * from service where createdate='$today' and companyid='$_SESSION[companyid]'  order by id DESC");

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

                        <td style="width:10%"><?php echo $row[sc]; ?></td>

                        

                          <td align="center">

                          

                          <?php 

						  if($_GET[servicedeleteid]){

							  

							  

							$delete=mysql_query("Delete from service where serviceid='$_GET[servicedeleteid]' and and companyid='$_SESSION[companyid]'"); 

							?>

                            <meta http-equiv="refresh" content="0;servicelist.php"> 

						 <?php  } ?>

                          

                          

                          

                          

                          

                            <a target="_new" href="service_print.php?type=print&serviceid=<?php echo $row[serviceid] ?>&servicetype=<?php echo $row[servicetype] ?>" class="btn btn-primary btn-xs"><i class="fa fa-user"> </i> Print </a>

                           

                            

                            

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

<?php //ob_end_flush(); ?>