<?php require_once 'php_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $_SESSION[company]; ?> | Company</title>



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
  <?php include ("pro.php");  ?> <br />
  <!-- /menu profile quick info -->


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
              <h2>Manage User</h2>
              <div class="clearfix"></div>
              </div>

                  

              <div class="x_content">
               <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="" role="tabpanel" data-example-id="togglable-tabs">
               <div id="myTabContent" class="tab-content">
               <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">



 

 <?php 

 ////////// data Insert code start from here

    $edit=$_POST[edit];
	$fullname=$_POST[fullname];
	$email=$_POST[email];
	$cnumber=$_POST[cnumber];
	$usertype=$_POST[usertype];
	$password=$_POST[password];
        $inputby=$_SESSION['login_email'];
        $companyid=$_SESSION['companyid'];
        $create_date=date('Y-m-d');
        


////////////////////////////////////// data edit function start from here-----------------------------------------
$edit=$_POST[edit];
if(isset($edit)){
	
mysql_query("Update `users` SET 
fullname='$fullname',
 email='$email',
  password='$password',
 
  cnumber='$cnumber',
  usertype='$usertype'
    
   where userid='$_GET[userid]'");
?>
<meta http-equiv="refresh" content="0;user_list.php">
<?php }  ?>





     
        
        
        
        
        
<?php
$results=mysql_query("Select * from users where userid='$_GET[userid]' and companyid='$_SESSION[companyid]'");
$comprow=mysql_fetch_array($results);

 ?>

               <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
               
               
               
               
               
               <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Full Name
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $comprow[fullname] ?>" name="fullname" class="form-control col-md-7 col-xs-12" >
                        </div></div>
                        
                        
                        
                        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Username
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $comprow[email] ?>" name="email" class="form-control col-md-7 col-xs-12" >
                        </div></div>
                        
                        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Password
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $comprow[password] ?>" name="password" class="form-control col-md-7 col-xs-12" >
                        </div></div>
                        
                        
                        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Contact Number
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $comprow[cnumber] ?>" name="cnumber" class="form-control col-md-7 col-xs-12" >
                        </div></div>
                        
                        
                        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Type 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $comprow[usertype] ?>" name="usertype" class="form-control col-md-7 col-xs-12" >
                        </div></div>
                        
                       
                      

                   <div class="ln_solid"></div>
                   <div class="form-group">
                   <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                     
                        <button type="cancel" class="btn btn-primary">Cancel</button>
						<button type="submit" name="edit" class="btn btn-success">Update User Information</button>
							

                        </div></div></form><br>
                        </div></div></div></div></div></div></div>

              

              

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2>User List</h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                   <thead>
                    <tr>
                     <th style="width: 5%">SL</th>
                     <th style="width: 5%">Userid</th>
                     <th style="">Fullname</th>
                     <th style="">Username</th>
                     <th style="">Password </th>
                     <th style="">Cnumber</th>
                     <th style="">Usertype</th> 
                     <th style="width:20%" align="center">Option</th>

                        </tr>

                      </thead>





                      <tbody>

                       <?php 

					   $today=date("Y-m-d");

				$result=mysql_query("Select * from users where usertype!='1' and companyid='$_SESSION[companyid]'  order by fullname");

				while($row=mysql_fetch_array($result)){ 

				$i=$i+1; ?>

                      <tr>

                        
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row[userid]; ?></td>
                        <td><?php echo $row[fullname]; ?></td>
                        <td><?php echo $row[email]; ?></td>
                        <td><?php echo $row[password]; ?></td>
                        <td><?php echo $row[cnumber]; ?></td>
                        <td><?php echo $row[usertype]; ?></td>
                        <td align="center">


<a href="user_list.php?type=edit&userid=<?php echo $row[userid] ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a>



<a href="user_list.php?type=delete&userdeleteid=<?php echo $row[userid] ?>"class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>                           
                            
 
                           
                           
</td>
</tr>
<?php } ?></tbody></table>

       </div></div></div></div></div></div>
              

              

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

              buttons: [{
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

