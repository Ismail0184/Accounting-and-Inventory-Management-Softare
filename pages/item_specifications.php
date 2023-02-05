<?php
 require_once 'support_file.php'; 
 $title='Item Specifications';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>

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
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='item_specifications.php?item_id=' + val ;
}


</script>



    <script>
        var x = 0;
        var y = 0;
        var z = 0;
        function calc(obj) {
            var e = obj.id.toString();
            if (e == 'qtys') {
                x = Number(obj.value);
                y = Number(document.getElementById('rate').value);
            } else {
                x = Number(document.getElementById('qtys').value);
                y = Number(obj.value);
            }
            z = x * y;
            document.getElementById('total').value = z;
            document.getElementById('update').innerHTML = z;
        }
		
		
		var submit = document.querySelector("input[type=submit]");
  
/* set onclick on submit input */   
submit.setAttribute("onclick", "return test()");

//submit.addEventListener("click", test);

function test() {

  if (confirm('Are you sure you want to submit this form?')) {         
    return true;         
  } else {
    return false;
  }

}
    </script>
    
    
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="<?php echo $webiste; ?>" class="site_title"><i class="fa fa-paw"></i> <span>ICPBD</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
           <?php include ("pro.php");  ?>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                         <?php include("sidebar_menus.php"); ?>

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
              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a target="_new" class="btn btn-sm btn-default"  href="production_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Production Report</span>
								</a>
                                
                                
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              
                    

<?php 
$initiate=$_POST[initiate];

$d =$_POST[ps_date];
$ps_date=date('Y-m-d' , strtotime($d)); 
$invoice=$_POST[invoice];
$billno=$_POST[billno];
$enat=date('Y-m-d h:s:i');
if(isset($initiate)){	
	
$insert=mysql_query("INSERT INTO item_SPECIFICATION (item_id,TEST_PARAMETERS,RESULT,SPECIFICATION,entry_by,entry_at,ip)  VALUES ('$_POST[item_id]','$_POST[TEST_PARAMETERS]','$_POST[RESULT]','$_POST[SPECIFICATION]','$_SESSION[userid]','$enat','$ip')");	

$_SESSION[initiate_daily_production]=$invoice;
$_SESSION[pr_no] =getSVALUE("production_floor_receive_master", "pr_no", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
; ?>
<meta http-equiv="refresh" content="0;item_specifications.php?item_id=<?php echo $_GET[item_id]; ?>">
<?php }


if(isset($_POST[Finish])){ ?>   
<meta http-equiv="refresh" content="0;item_specifications.php">
<?php } ?>

                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">








<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Item Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
						<select class="select2_single form-control" onchange="javascript:reload(this.form)" style="width:400px" tabindex="-1" required="required"  name="item_id" id="item_id" >
                            <option></option>
                          <?php if($_GET[item_id]) ?> <option selected value="<?php echo $_GET[item_id]; ?>"><?=$nam=getSVALUE("item_info", "item_name", " where item_id='$_GET[item_id]'");?></option> 
                            <?php 
							$result=mysql_query("SELECT i.*,sg.*,g.* FROM 
							item_info i,
							item_sub_group sg,
							item_group g
							
							where 
							i.sub_group_id=sg.sub_group_id and 
							sg.group_id=g.group_id and g.group_id in ('200000000','300000000','400000000','500000000','1096000100000000')
							
							   order by g.group_id,i.item_name");
							while($row=mysql_fetch_array($result)){  ?>
                  <option  value="<?php echo $row[item_id]; ?>"><?php echo $row[finish_goods_code]; ?>-<?php echo $row[item_name]; ?> (<?=$packsizeitem=getSVALUE("item_sub_group", "sub_group_name", " where sub_group_id='$row[sub_group_id]'");?>)</option>
                    <?php } ?>
                          </select></div></div> 



<?php if($_GET[item_id]) {?> 
                  <div class="form-group">                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Unit<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="remarkspro" style="width:400px"   name="remarkspro" value="<?=$nam=getSVALUE("item_info", "unit_name", " where item_id='$_GET[item_id]'");?>" class="form-control col-md-7 col-xs-12" readonly >

                      </div>  
	                </div>
<?php } ?>



                    
                    
                             
        <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">TEST PARAMETERS<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12"> 
                   <select class="select2_single form-control"  style="width:400px" tabindex="-1" required="required"  name="TEST_PARAMETERS" id="TEST_PARAMETERS" >
                            <option></option>
                          <?php if($_GET[item_id]) ?> <option selected value="<?php echo $_GET[item_id]; ?>"><?=$nam=getSVALUE("item_info", "item_name", " where item_id='$_GET[item_id]'");?></option> 
                            <?php 
							$result=mysql_query("SELECT * FROM PARAMETERS  order by PARAMETERS_CODE");
							while($row=mysql_fetch_array($result)){  ?>
                  <option  value="<?php echo $row[PARAMETERS_CODE]; ?>"><?php echo $row[PARAMETERS_CODE]; ?>-<?php echo $row[PARAMETERS_Name]; ?></option>
                    <?php } ?>
                          </select>

                      </div>  
	                </div>     
                    
                 
                      
                      
                      
                      

                      
                      
        <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">RESULT<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="RESULT" style="width:400px"   name="RESULT" value="<?php if($_SESSION[initiate_daily_production]){ echo$inirow[remarks]; } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div>
                    
                    
                    
                    <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SPECIFICATION<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            
                <textarea name="SPECIFICATION" id="SPECIFICATION" style="height:100px; width:400px"></textarea>

                      </div>  
	                </div>
        
               
                        
                       
               
               
               <div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_daily_production]){  ?>
			   
			   <!---a href="daily_production.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
               <button type="submit" name="updatePS" class="btn btn-success">Update PS Documents</button>
			   
			 <?php   } else {?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">SPECIFICATION ADD</button>
               
               
               
               <br><br>
               
               <button type="submit" name="Finish" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">SPECIFICATION ADD FINISHED</button>

               <?php } ?>
               </div></div></form></div></div></div>
            
           
              
   



 <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2><?php echo 'Item Specifications' ; ?></h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                                      <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">

                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">ITEM CODE</th>
                     <th style="width:8%">ITEM DESCRIPTION</th>
                     <th style="width:8%">PARAMETERS</th>
                     <th style="width:8%">SPECIFICATION</th>
                     <th style="width:5%; text-align:center">OPTIONS</th>
              
                     </tr>
                     </thead>





                      <tbody>






<?php
if($_GET[item_id]) {
$resultss=mysql_query("Select * from item_SPECIFICATION where item_id='$_GET[item_id]' order by id  ");
} else {
	$resultss=mysql_query("Select * from item_SPECIFICATION where 1 order by item_id,id ");
	}
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;
	
	
	$ids=$rows[id];
	$SPECIFICATION=$_POST['SPECIFICATION'.$ids];
				
				if(isset($_POST['editdata'.$ids]))
				{
				mysql_query("UPDATE item_SPECIFICATION SET SPECIFICATION='$SPECIFICATION' WHERE item_id='$_GET[item_id]' and  id='$ids'"); ?>
                <meta http-equiv="refresh" content="0;item_specifications.php?item_id=<?php echo $_GET[item_id]; ?>">
                <?php 
				}
				
				
				if(isset($_POST['deletedata'.$ids]))
				{
				mysql_query("DELETE FROM item_SPECIFICATION WHERE item_id='$_GET[item_id]' and  id='$ids'"); ?>
                <meta http-equiv="refresh" content="0;item_specifications.php?item_id=<?php echo $_GET[item_id]; ?>">
                <?php 
				}	

$link='#';

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><a href="<?php echo $link; ?>"><?php echo $rows[item_id]; ?></a></td>
                        <td><?=$nam=getSVALUE("item_info", "item_name", " where item_id='$rows[item_id]'");?></td>
                        <td><a href="<?php echo $link; ?>"><?=$PARAMETERS=getSVALUE("PARAMETERS", "PARAMETERS_Name", " where PARAMETERS_CODE='$rows[TEST_PARAMETERS]'");?></a></td>
                        <td><textarea  name="SPECIFICATION<?php echo $ids; ?>" style="width:100%" id="SPECIFICATION<?php echo $ids; ?>"><?php echo $rows[SPECIFICATION]; ?></textarea></td>
                        
                        <td style="text-align:center">
                        
                         <button type="submit" name="editdata<?php echo $ids; ?>" id="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none;" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Update?");'><img src="update-icon.png" style="width:25px;  height:25px"></button>
                         
                         <button type="submit" name="deletedata<?php echo $ids; ?>" id="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none; margin-left:20px" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:20px;  height:20px"></button>
                          
                          
                          
                          
                          
                         </td>
                        
                        </tr>
<?php } ?></tbody></table></form>

       </div></div></div>









              
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        
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
    
    <!-- /Datatables -->

  </body>

