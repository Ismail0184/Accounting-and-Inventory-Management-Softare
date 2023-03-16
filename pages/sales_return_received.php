<?php
 require_once 'support_file.php'; 
 $title='Pending Sales Return List';
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
                  <?php if($_GET[sr_id]) { ?>
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   
                  

  

<tr style="height:30px">
<th style="text-align:center; width:2%">S/N</th>
<th style="text-align:center">Code</th>
<th style="text-align:center">FG  Code</th>
<th style="text-align:center">Material Description</th>
<th style="text-align:center">Unit</th>
<th style="text-align:center">Return Qty</th>
<th style="text-align:center">Rcv. Qty</th>
</tr>

<?php
$dat=date('Y-m-d');
$time_now = date('Y-m-d H:s:i');
$enby=$_SESSION['userid'];

$custom_no=getSVALUE('sale_return_details','sr_no','where do_no="'.$_GET[sr_id].'"');
$resu=mysql_query("Select * from sale_return_details where do_no='$_GET[sr_id]'");

while($MANdetrow=mysql_fetch_array($resu)){
	$j=$j+1;
	
	$id=$MANdetrow['id'];
	
	$rcvqty=$_POST['rcvqty'.$MANdetrow[id]];
	
	 if(isset($_POST[confirmandverify])){
		 
		 if($rcvqty>0){
	  
 $item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, pre_stock, pre_price, item_in, item_ex, item_price, final_stock, tr_from,tr_no,sr_no,entry_by,entry_at,lot_number,return_from_dealder,ip,custom_no) VALUES 
('$dat','$MANdetrow[item_id]','$MANdetrow[depot_id]','0','','','$rcvqty','','$setprice','','SalesReturn','$MANdetrow[do_no]','$MANdetrow[id]','$enby','$time_now','','$MANdetrow[dealer_code]','$ip','$custom_no')");	  
	 
  }
  
  mysql_query("update sale_return_details set status='COMPLETED'  where do_no='$_GET[sr_id]'");	
	mysql_query("update sale_return_master set status='COMPLETED' where do_no='$_GET[sr_id]'"); ?>
    <meta http-equiv="refresh" content="0;sales_return_received.php">	
  
 <?php  } ?>
<tr style="background-color:#FFF">
<td style="width:2%; text-align:center"><?php echo $j; ?></td>
<td style="width:5%; text-align:center"><?php echo $MANdetrow[item_id]; ?></td>
<td style="width:10%; text-align:center"><?=$fg = getSVALUE('item_info','finish_goods_code','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="text-align:left"><?=$md = getSVALUE('item_info','item_name','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="width:5%; text-align:center"><?=$unit = getSVALUE('item_info','unit_name','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="width:8%; text-align:right"><?php echo $MANdetrow[total_unit]; ?></td>
<td style="width:8%; text-align:right"><input type="text" style="width:100px; text-align:center" name="rcvqty<?=$id?>" id="rcvqty<?=$id?>" value="<?php echo $MANdetrow[total_unit]; ?>"></td>

</tr>
<?php } ?>


</table>




<table width="100%" style="border-collapse:collapse; margin-top:10px" cellspacing="0" cellpadding="5">
<tr style="border:none">
<td colspan="8" style="text-align:center; border:none"><input type="checkbox" required name="terms" style="float:none"> <font style="color:#000">I have checked the Document.</font></td></tr>

<?php 

$checked=$_POST[checked];
if(isset($checked)){
$del1=mysql_query("UPDATE  MAN_master set status='CHECKED',cehck_by='".$_SESSION['userid']."',cehck_at='$todaysss' where MAN_ID='$_GET[man_id]'");
$del1=mysql_query("UPDATE  MAN_details set status='CHECKED' where MAN_ID='$_GET[man_id]'"); ?>
<meta http-equiv="refresh" content="0;MAN_checked.php">	
	<?php } ?>  
    
    
<?php 
$Deletefinal=$_POST[Deletefinal];
if(isset($Deletefinal)){
$del1=mysql_query("Delete from MAN_master where MAN_ID='$_GET[man_id]'");
$del1=mysql_query("Delete from MAN_details where MAN_ID='$_GET[man_id]'"); 


unlink("51816/cmu_mod/page/dc_documents/".$_GET['man_id'].'_'.$row['delivary_challan'].'.pdf');
unlink("51816/cmu_mod/page/dc_documents/".$_GET['man_id'].'_'.$row['VAT_challan'].'.pdf');

?>
<meta http-equiv="refresh" content="0;MAN_checked.php">	
	<?php } ?>    

<tr style="border:none">
<td align="center" colspan="8" style="text-align:center; border:none">
<!---input name="Deletefinal" onclick="return confirmation();" type="submit" class="btn1" value="DELETE" style="width:100px;color:green; font-weight:bold; font-size:11px; height:30px; margin-left:30%" /--->

<input name="confirmandverify" onclick="return confirmation();" type="submit" class="btn1" value="Received" style="width:100px;color:green; margin-left:20px;font-weight:bold; font-size:11px;height:30px" /></td></tr>



</table></form>
                  
                  <?php } else { ?>
                  
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">SR NO</th>
                     <th style="width:12%">SR Date</th>
                     <th >Dealer Name</th> 
                     <th >Dpot. Name</th> 
                     <th style="width:15%">Entry By</th>
                     <th style="width:15%">Check By</th>
                     </tr>
                     </thead>





                      <tbody>






<?php

if($_SESSION["userlevel"]=='4'){
$resultss=mysql_query("SELECT m.*,d.*,u.*,w.* from 

sale_return_master m,
dealer_info d,
users u,
warehouse w

 where 
 m.dealer_code=d.dealer_code and 
 m.entry_by=u.user_id and
 m.status in ('PROCESSING') and 
 m.depot_id=w.warehouse_id and 
 w.warehouse_id=$_SESSION[warehouse]
  order by m.do_no DESC");
} else { 

$resultss=mysql_query("SELECT m.*,d.*,u.*,w.* from 

sale_return_master m,
dealer_info d,
users u,
warehouse w

 where 
 m.dealer_code=d.dealer_code and 
 m.entry_by=u.user_id and
 m.status in ('PROCESSING') and 
 m.depot_id=w.warehouse_id
  order by m.do_no DESC");


}
  
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='sales_return_received.php?sr_id='.$rows[do_no];
$delivary_challan='/51816/cmu_mod/page/dc_documents/'.$rows[MAN_ID].'_'.$rows[delivary_challan];
$VAT_challan='/51816/cmu_mod/page/vc_documents/'.$rows[MAN_ID].'_'.$rows[VAT_challan];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><a href="<?php echo $link; ?>" ><?php echo $i; ?></a></th>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[sr_no]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[do_date]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[dealer_name_e]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[warehouse_name]; ?></a></td>
                        
                        <td><a href="<?php echo $link; ?>" ><?=$rows[fname]?></a></td>
                        
                        <td><a href="<?php echo $link; ?>" ><?=$fname=getSVALUE("users", "fname", "where user_id='".$rows['checked_by']."'");?></a></td>
                       
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

