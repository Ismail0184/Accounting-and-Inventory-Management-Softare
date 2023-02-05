<?php
 require_once 'support_file.php'; 
 $title='Bill Checked';
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
                  <?php if($_GET[bill_id]) { ?>
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   
                  

  

<tr style="height:30px">
<th style="text-align:center; width:2%">S/N</th>
<th style="text-align:center; width:5%">Bill No</th>
<th style="text-align:center; width:12%">Rcv. Date</th>
<th style="text-align:center">Vendor Name</th>
<th style="text-align:center">PO No</th>
<th style="text-align:center">GRN No</th>
<th style="text-align:center;width:15%">GRN Amount</th>
</tr>

<?php
$resu=mysql_query("Select * from Bill_Received_Entry_details where bill_master_id='$_GET[bill_id]'");
while($Detailsrow=mysql_fetch_array($resu)){
	$j=$j+1;

 ?>
<tr style="background-color:#FFF">
<td style="width:2%; text-align:center"><?php echo $p; ?></td>

<td style="text-align:left"><?php echo $Detailsrow[bill_no]; ?></td>
<td style="text-align:center"><?php echo $Detailsrow[rcv_Date]; ?></td>
<td style=" text-align:left"><?=$tot=getSVALUE("vendor","vendor_name","where vendor_id='".$Detailsrow[vendor_code]."'"); ?></td>
<td style="width:8%; text-align:right"><?php echo $Detailsrow[po_no]; ?></td>
<td style="width:8%; text-align:right"><a href="chalan_view2.php?v_no=<?php echo $Detailsrow[grn_no]; ?>"  target="_blank"><?php echo $Detailsrow[grn_no]; ?></a></td>
<td style="text-align:right"><?php $amount = getSVALUE('purchase_receive','SUM(amount)','where pr_no='.$Detailsrow[grn_no]); echo number_format($amount,2); ?></td>


</tr>

<?php
$amounttotal=$amounttotal+$amount;

}
$PO = getSVALUE('Bill_Received_Entry','po_no','where id='.$_GET[bill_id]);
$vat = getSVALUE('purchase_receive','distinct MAX(tax)','where po_no='.$PO);
$TAX = getSVALUE('purchase_receive','distinct MAX(tax_ait)','where po_no='.$PO);
 ?>
<tr style="background-color:#FFF">
<td colspan="6" style="text-align:right"><strong>Total</strong></td>
<td style="text-align:right"><strong><?=number_format($amounttotal,2)?></strong></td>

</tr>
<tr style="background-color:#FFF">
<td colspan="6" style="text-align:right"><strong>VAT (<font style="color:red"><?=number_format($vat,0)?>%</font>)</strong></td>
<?php 
// VAT Calculation start from here
$vatamount=$amounttotal*$vat/100;
$totalamountafterVAT=$amounttotal-$vatamount;
$TAXamount=$totalamountafterVAT*$TAX/100;
$totalpayableamount=$totalamountafterVAT-$TAXamount;
?>

<td style="text-align:right"><strong><?=number_format($vatamount,2)?></strong></td>

</tr>
<tr style="background-color:#FFF">
<td colspan="6" style="text-align:right"><strong>TAX (<font style="color:red"><?=number_format($TAX,0)?>%</font>)</strong></td>
<td style="text-align:right"><strong><?=number_format($TAXamount,2)?></strong></td>

</tr>

<tr style="background-color:#FFF">
<td colspan="6" style="text-align:right"><strong>Net Payable Amount</strong></td>
<td style="text-align:right"><strong><?=number_format($totalpayableamount,2)?></strong></td>

</tr>
</table>




<table width="100%" style="border-collapse:collapse; margin-top:10px" cellspacing="0" cellpadding="5">


<?php 

$checked=$_POST[checked];
if(isset($checked)){
$del1=mysql_query("UPDATE  Bill_Received_Entry set bill_status='RECOMMENDED',cehck_by='".$_SESSION['userid']."',cehck_at='$todaysss' where id='$_GET[bill_id]'");
 ?>
<meta http-equiv="refresh" content="0;bill_received_checked.php">	
	<?php } ?>  
    
    
<?php 
$Deletefinal=$_POST[Deletefinal];
if(isset($Deletefinal)){
$del1=mysql_query("Update Bill_Received_Entry SET bill_status='UNCHECKED' where id='$_GET[bill_id]'");

?>
<meta http-equiv="refresh" content="0;bill_received_checked.php">	
	<?php } ?>    

<tr style="border:none">
<td align="center" colspan="7" style="text-align:center; border:none">
<input name="Deletefinal" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Return?");' type="submit" class="btn1" value="Return" style="width:100px;color:green; font-weight:bold; font-size:11px; height:30px; margin-left:20%" />

<input name="checked" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Checked?");' type="submit" class="btn1" value="CHECKED" style="width:100px;color:green; margin-left:20px;font-weight:bold; font-size:11px;height:30px" /></td></tr>



</table></form>
                  
                  <?php } else { ?>
                  
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:10%">Bill NO</th>
                     <th style="width:12%">Rev. Date</th>
                     <th >Vendor</th> 
                     <th style="width:15%">PO No</th>
                     <th style="width:15%">Delivary Challan</th> 
                     <th style="width:15%">VAT Challan</th>
                     <th style="width:15%">Entry By</th>
                     </tr>
                     </thead>





                      <tbody>






<?php
$resultss=mysql_query("Select * from Bill_Received_Entry where bill_status='CHECKED' order by id DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='bill_received_checked.php?bill_id='.$rows[id];
$delivary_challan='/51816/cmu_mod/page/dc_documents/'.$rows[MAN_ID].'_'.$rows[delivary_challan];
$VAT_challan='/51816/cmu_mod/page/vc_documents/'.$rows[MAN_ID].'_'.$rows[VAT_challan];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><a href="<?php echo $link; ?>" ><?php echo $i; ?></a></th>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[bill_no]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[rcv_Date]; ?></a></td>                        
                        <td><a href="<?php echo $link; ?>" ><?=$tot=getSVALUE("vendor","vendor_name","where vendor_id='".$rows[vendor_code]."'"); ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[po_no]; ?></a></td>
                        <td><a href="<?php echo $delivary_challan; ?>.pdf" target="_blank" style="color:#06F"><u><strong><?php echo $rows[delivary_challan]; ?></strong></u></a></td>
                        <td><a href="<?php echo $VAT_challan; ?>.pdf" target="_blank" style="color:#06F"><u><strong><?php echo $rows[VAT_challan]; ?></strong></u></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$fname=getSVALUE("user_activity_management", "fname", "where user_id='".$rows['entey_by']."'");?></a></td>
                       
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

