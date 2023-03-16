<?php
 require_once 'support_file.php'; 
 $title='MAN MANUAL & Return List';
 $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
				$todaysss=$dateTime->format("d/m/Y  h:i A");
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
  <a href="<?php echo $webiste; ?>" class="site_title"><i class="fa fa-paw"></i> <span>ICPBD</span></a>
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
                <h2><?php echo $title; ?></h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                  <?php if($_GET[man_id]) { ?>
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   
                  

  

<tr style="height:30px">
<th style="text-align:center; width:2%">S/N</th>
<th style="text-align:center">Code</th>
<th style="text-align:center">Custom Code</th>
<th style="text-align:center">Material Description</th>
<th style="text-align:center">Unit</th>
<th style="text-align:center">Qty</th>
<th style="text-align:center">MFG</th>
<th style="text-align:center">No of Pack	</th>
<!--th style="text-align:center">Inspection<br />Add</th-->
</tr>

<?php
$resu=mysql_query("Select * from MAN_details where MAN_ID='$_GET[man_id]'");
while($MANdetrow=mysql_fetch_array($resu)){
	$j=$j+1;

 ?>
<tr style="background-color:#FFF">
<td style="width:2%; text-align:center"><?php echo $j; ?></td>
<td style="width:5%; text-align:center"><?php echo $MANdetrow[item_id]; ?></td>
<td style="width:5%; text-align:center"><?=$fg = getSVALUE('item_info','finish_goods_code','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="text-align:left"><?=$md = getSVALUE('item_info','item_name','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="width:5%; text-align:center"><?=$unit = getSVALUE('item_info','unit_name','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="width:8%; text-align:right"><?php echo $MANdetrow[qty]; ?></td>
<td style="width:15%; text-align:right"><?php echo $MANdetrow[mfg]; ?></td>
<td style="width:10%; text-align:right"><?php echo $MANdetrow[no_of_pack]; ?></td>

<!---td align="center" style="width:10%">
<?php $dones = getSVALUE('QC_Inspection_Work_Sheet_master','COUNT(item_id)','where MAN_ID="'.$_GET[man_id].'" and item_id='.$MANdetrow[item_id]); if($dones>0){ ?>
<img src="done.png" style="margin-left:10px" height="25" width="25" />
<?php } else { ?>
<a href="Inspection_Work_Sheet.php?manid=<?php echo $_GET[man_id]; ?>&item_id=<?php echo $MANdetrow[item_id]; ?>&t_id=<?php echo $MANdetrow[id]; ?>" style="text-decoration:none"><img src="add.png" style="margin-left:10px" height="25" width="25" /></a>
<?php }?>
</a></td--->
</tr>
<?php 
$tqty=$tqty+$MANdetrow[qty];
$tamount=$tqty+$MANdetrow[amount];
} ?>

<tr style="background-color:#0FF; height:25px; font-weight:bold"><td colspan="5">Total</td>
<td style="text-align:right"><?php echo $tqty; ?></td>
<td style="text-align:right"></td><td style="text-align:right"></td>

</tr>
</table>




<table width="100%" style="border-collapse:collapse; margin-top:10px" cellspacing="0" cellpadding="5">

<?php 

$checked=$_POST[initiate];
if(isset($checked)){

    $del1=mysql_query("UPDATE MAN_master SET status='MANUAL'  where MAN_ID='$_GET[man_id]'");
    $del1=mysql_query("UPDATE MAN_details SET status='MANUAL' where MAN_ID='$_GET[man_id]'");

    $_SESSION[initiate_man_documents]=$_GET[man_id];

?>
<meta http-equiv="refresh" content="0;Incoming_Material_Received.php">
	<?php } ?>  
    
    
<?php 
$Deletefinal=$_POST[Deletefinal];
if(isset($Deletefinal)){
$del1=mysql_query("Delete from MAN_master where MAN_ID='$_GET[man_id]'");
$del1=mysql_query("Delete from MAN_details where MAN_ID='$_GET[man_id]'"); 


unlink("51816/cmu_mod/page/dc_documents/".$_GET['man_id'].'_'.$row['delivary_challan'].'.pdf');
unlink("51816/cmu_mod/page/dc_documents/".$_GET['man_id'].'_'.$row['VAT_challan'].'.pdf');

?>
<meta http-equiv="refresh" content="0;MAN_return_list.php">
	<?php } ?>






    <tr style="border:none">
        <td colspan="5" style="border:none">

            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="initiate" id="returnMAN" onclick='return window.confirm("Are you sure you want to Edit?");' class="btn btn-success">Edit and Re-Process</button>
                </div></div>
        </td>

        <td colspan="5" style="border:none">

            <div class="form-group" style="margin-left:40%">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button type="submit" name="Deletefinal" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Deleted</button>
                </div></div>

        </td></tr>




<!--tr style="border:none">
<td align="center" colspan="8" style="text-align:center; border:none">
<input name="Deletefinal" onclick="return confirmation();" type="submit" class="btn1" value="DELETE" style="width:100px;color:green; font-weight:bold; font-size:11px; height:30px; margin-left:30%" />

<input name="checked" onclick="return confirmation();" type="submit" class="btn1" value="CHECKED" style="width:100px;color:green; margin-left:20px;font-weight:bold; font-size:11px;height:30px" /></td></tr-->



</table></form>
                  
                  <?php } else { ?>
                  
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">MAN NO</th>
                     <th style="width:12%">MAN Date</th>
                     <th >Vendor</th> 
                     <th style="width:15%">Delivary Challan</th> 
                     <th style="width:15%">VAT Challan</th>
                     <th style="width:15%">Entry At</th>
                        <th style="width:15%">Status</th>
                     </tr>
                     </thead>





                      <tbody>






<?php
$resultss=mysql_query("Select * from MAN_master where status in ('RETURNED','MANUAL') order by MAN_ID DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='MAN_return_list.php?man_id='.$rows[MAN_ID];
$delivary_challan='/51816/cmu_mod/page/dc_documents/'.$rows[MAN_ID].'_'.$rows[delivary_challan];
$VAT_challan='/51816/cmu_mod/page/vc_documents/'.$rows[MAN_ID].'_'.$rows[VAT_challan];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><a href="<?php echo $link; ?>" ><?php echo $i; ?></a></th>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[MAN_ID]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[man_date]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$tot=getSVALUE("vendor","vendor_name","where vendor_id='".$rows[vendor_code]."'"); ?></a></td>
                        <td><a href="<?php echo $delivary_challan; ?>.pdf" target="_blank" style="color:#06F"><u><strong><?php echo $rows[delivary_challan]; ?></strong></u></a></td>
                        <td><a href="<?php echo $VAT_challan; ?>.pdf" target="_blank" style="color:#06F"><u><strong><?php echo $rows[VAT_challan]; ?></strong></u></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$fname=getSVALUE("users", "fname", "where user_id='".$rows['entry_by']."'");?></a></td>
                          <td><a href="<?php echo $link; ?>" ><?php echo $rows[status]; ?></a></td>
                       
                        </tr>
<?php } ?></tbody></table><?php } ?>

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

