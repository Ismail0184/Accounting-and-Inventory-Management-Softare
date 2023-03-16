<?php
 require_once 'support_file.php'; 
 $title='User Permission (Sub Menu)';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $userRow[proj_name]; ?>  | <?=$title?></title>

 <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">

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
    <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.user_id.options[form.user_id.options.selectedIndex].value;
	self.location='user_permission2.php?user_id=' + val ;
}
</script>

<SCRIPT language=JavaScript>
function reload2(form)
{
	var val=form.master_menu.options[form.master_menu.options.selectedIndex].value;
	self.location='user_permission2.php?user_id=<?=$_GET[user_id]?>&master_menu=' + val ;
}
</script>
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
<?php include("sidebar_menus.php"); ?>
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
              <h2><?=$title?></h2>
              <div class="clearfix"></div>
              </div>

                  

              <div class="x_content">
               <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="" role="tabpanel" data-example-id="togglable-tabs">
               <div id="myTabContent" class="tab-content">
               <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">



 

 <?php 

 ////////// data Insert code start from here

    
	$getstarted3=$_POST[getstarted3];
	$reportlevelname= $_POST[reportlevelname];
	$maingroup=$_POST[maingroup];
    $inputby=$_SESSION['login_email'];
    $companyid=$_SESSION['companyid'];
    $create_date=date('Y-m-d');

	

$zonenamemain=getSVALUE("zone_main", "zonename", "where zonecode='$_POST[master_menu]'");
$zonenamesub=getSVALUE("zone_sub", "zonename", "where zonecodesub='$_POST[sub_menu]'");

	
if (isset($_POST['getstarted'])){

$valid = true;


$flag=mysql_query("Select maingroup from accounts_maingroup where maingroup='$maingroup' and companyid='$_SESSION[companyid]'");
	if ( mysql_num_rows($flag)>0)

    {echo "<script> alert('This Main Group is Already Input!! Please Try Another Name') </script>";
        $valid = false;}


if ($valid){
	
	$resultreport=mysql_query("Select rlid from accounts_reportlevel where reportlevelname='$reportlevelname' and companyid='$_SESSION[companyid]'");
    $rowreport=mysql_fetch_array($resultreport);
	$rlid=$rowreport['rlid'];

$result=mysql_query("INSERT INTO `user_permissions2` 
(zonecodemain,zonenamemain,zonecode,zonename,user_id,powerby,powerdate,ip,companyid) VALUES('$_POST[master_menu]','$zonenamemain','$_POST[sub_menu]','$zonenamesub','$_GET[user_id]','$_SESSION[userid]','$create_date','$ip','500001')");

	maingroupid(); ?>
    <meta http-equiv="refresh" content="0;user_permission2.php?user_id=<?=$_GET[user_id]?>&master_menu=<?=$_GET[master_menu]?>"><?php }}  ?>




<?php
////////////////////////////////////// data edit function start from here-----------------------------------------
$edit=$_POST[edit];
if(isset($edit)){
	
mysql_query("Update `accounts_maingroup` SET 
maingroup='$maingroup', modifiredby='$inputby', modifieddate='$create_date' where rlid='$_GET[rleditid]' and mgid='$_GET[mgroupeditid]' and companyid='$_SESSION[companyid]'");
?>
<meta http-equiv="refresh" content="0;accounts_maingroup.php">
<?php } 





////////////////////////////////////// data Delete function start from here-----------------------------------------

if($_GET[mgroupdeleteid]){
	$deleteid=$_GET[mgroupdeleteid];
		$delete=mysql_query("Delete from accounts_maingroup where rlid='$_GET[rleditdeleteid]' and mgid='$deleteid' and companyid='$_SESSION[companyid]'"); ?>
        <meta http-equiv="refresh" content="0;accounts_maingroup.php"> 
		<?php  } ?>
        
        
        
        
  <?php
if($_GET[mgroupeditid]){  
$result=mysql_query("Select * from accounts_maingroup where rlid='$_GET[rleditid]' and mgid='$_GET[mgroupeditid]' and companyid='$_SESSION[companyid]'");
$rowsmaingroup=mysql_fetch_array($result);
}
?>       
        
        
        
        
        


               <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
               
               
               
               
               
               <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User List<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
                        <?php if($_GET[type]){ ?>
                                                 
                       <input type="text" id="first-name" required="required" value="<?php echo $rowsmaingroup[reportlevelname] ?>" name="user_id" class="form-control col-md-7 col-xs-12" readonly>
                        
                        <?php } else { ?>
                        
                        
                        <select class="select2_single form-control" id="first-name" required="required"   name="user_id"  onchange="javascript:reload(this.form)">
                        
                        
                        <option value="">Choose......</option>
                        
                        <?php
						$result=mysql_query("Select * from users where 1 order by user_id");
						while($rowmaingroup=mysql_fetch_array($result)){
						if(($_GET[user_id])==$rowmaingroup[user_id]){
						
						 ?> 
                                         
                        <option selected value="<?php echo $rowmaingroup[user_id]; ?>"><?php echo $rowmaingroup[fname]; ?></option>
                        
                        <?php } else { ?>
                        
                         <option  value="<?php echo $rowmaingroup[user_id]; ?>"><?php echo $rowmaingroup[fname]; ?></option>
                       
                        <?php }} ?>
                        </select>
                        <?php } ?>
                        </div>
                      </div>
               
               
               
               
               
               <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Master Menu<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
                       
                        
                        <select id="first-name" required="required"   name="master_menu" class="select2_single form-control" onchange="javascript:reload2(this.form)">                       
                        
                        <option value="">Choose......</option> 
                        <?php $zoneresult=mysql_query("Select * from zone_main order by zonecode");  
						while($mainzonerow=mysql_fetch_array($zoneresult)){ 
						if(($_GET[master_menu])==$mainzonerow[zonecode]){    ?>  
                         <option selected value="<?php echo $mainzonerow[zonecode]; ?>"><?php echo $mainzonerow[zonename]; ?></option>
                        
                        <?php } else { ?>               
                        <option  value="<?php echo $mainzonerow[zonecode]; ?>"><?php echo $mainzonerow[zonename]; ?></option>
                        <?php }} ?>
                        </select>
                        </div>
                      </div>
                      
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sub Menu<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
                       
                        
                        <select id="first-name" required="required"   name="sub_menu" class="select2_single form-control">                       
                        
                        <option value="">Choose......</option> 
                        <?php $zonesubresult=mysql_query("Select * from zone_sub where zonecodemain='$_GET[master_menu]' order by zonename");  
						while($subzonerow=mysql_fetch_array($zonesubresult)){     ?>                 
                        <option  value="<?php echo $subzonerow[zonecodesub]; ?>"><?php echo $subzonerow[zonename]; ?></option>
                        <?php } ?>
                        </select>
                        </div>
                      </div>

               
                      

                      

                   <div class="ln_solid"></div>
                   <div class="form-group">
                   <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                     <?php if($_GET[type]){ ?>

                        <button type="cancel" class="btn btn-primary">Cancel</button>
						<button type="submit" name="edit" class="btn btn-success">Edit</button>

                        <?php } else { ?>

                        <button type="cancel" class="btn btn-primary">Cancel</button>
						<button type="submit" name="getstarted" class="btn btn-success">Save</button>
							<?php } ?>

                        </div></div></form><br>
                        </div></div></div></div></div></div></div>

              

              

              

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2><? //$title?></h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                   <thead>
                    <tr>
                     <th style="width: 5%">SL</th>
                     <th style="">Sub Menu Name</th>
                     <th style="">Master Menu Name</th>
                     <th style="width:20%">User ID</th> 
                     <th style="width:20%">Permission By</th>                     
                     <th style="width:15%">Create Date</th>
                     <!---th style="width:20%" align="center">Option</th--->

                        </tr>

                      </thead>





                      <tbody>

                       <?php 

					   $today=date("Y-m-d");

if($_GET[user_id]){
								
				$result=mysql_query("Select p.*,m.zonecode,m.zonename as mustermenu,z.*,u.* from 
				user_permissions2 p ,
				zone_main m,
				zone_sub z,
				users u
				where 
				m.zonecode=p.zonecodemain and 
				u.user_id=p.user_id and 
				p.user_id='$_GET[user_id]' and 
				
				p.zonecode=z.zonecodesub 
				order by p.id");
				} else { 
				
				$result=mysql_query("Select p.*,m.zonecode,m.zonename as mustermenu,z.*,u.* from 
				user_permissions2 p ,
				zone_main m,
				zone_sub z,
				users u
				where 
				m.zonecode=p.zonecodemain and 
				u.user_id=p.user_id and 
				
				
				p.zonecode=z.zonecodesub 
				order by p.id");
				}

				while($row=mysql_fetch_object($result)){ 

				$i=$i+1; ?>

                      <tr>

                        
                        <td><?php echo $i; ?></td>
                        <td><?=$row->zonename; ?></td>
                        <td><?=$row->mustermenu; ?></td>
                        <td><?=$row->fname; ?></td>
                        
                        <td><?=$zonename=getSVALUE("users", "fname", "where user_id='".$row->powerby."'");?></td>
                        <td><?=$row->powerdate; ?></td>
                                                
                            
 
                           
                           
</td--->
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

    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>

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
<!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "select your choice",
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

  </body>

