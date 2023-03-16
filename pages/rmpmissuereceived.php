<?php
 require_once 'support_file.php'; 
 $title='RM/PM Received Pending';
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


<form action="" method="post">
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
                  
              <?php if($_GET[pi_no]){
				$sql=mysql_query("Select m.*,d.* from
				production_issue_master m ,
				production_issue_detail d
				where 
				m.pi_no='".$_GET[pi_no]."' and 
				m.pi_no=d.pi_no group by m.pi_no
				") ;
				$data=mysql_fetch_object($sql)
				  
				  ?>    
                  
              <table  class="table table-striped table-bordered" style="width:100%;">
              <tr>
              <td><strong style="color:#000">ISSUE NO</strong></td><td>:</td><td><?=$_GET[custom_pi_no]; echo  '('.$_GET[pi_no].')';?></td>
              <td><strong style="color:#000">ISSUE DATE</strong></td><td>:</td><td><?=$data->pi_date;?></td>
              </tr>
              
              
              <tr>
              <td><strong style="color:#000">From</strong></td><td>:</td><td><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$data->warehouse_from."'");?></td>
              <td><strong style="color:#000">Rcvd. To</strong></td><td>:</td><td><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$data->warehouse_to."'");?></td>
              </tr>
              </table>    
                  
                  
                  
                  
                  
                  
              <table  class="table table-striped table-bordered" style="width:100%;">
              <tr>
              <th>#</th>
              <th>Met. Code</th>
              <th>Met. Description</th>
              <th>UOM</th>
              <th>Issue Qty</th>
              <th>Rcvd. Qty</th>
              </tr>
              
              <?php 
			  $enat=date('Y-m-d h:s:i');
			  $sql=mysql_query("Select m.*,
			  
			    d.pi_no,
				 d.item_id,
				  d.total_unit,
				   d.lot,
				   d.unit_price as PRICE,
				    d.id as RcvID,
					 
				
				i.* 
				from
				production_issue_master m ,
				production_issue_detail d,
				item_info i
				where 
				d.item_id=i.item_id and 
				m.pi_no='".$_GET[pi_no]."' and 
				m.pi_no=d.pi_no 
				") ;
			  while($datas=mysql_fetch_object($sql)){
				  
			$RCVQTY=$_POST['rcvQTY_'.$datas->RcvID];	
			
			$value=$RCVQTY*$datas->PRICE;
			$totalVALUE=$totalVALUE+$value;
				  
	///////////////////////////////////// transfer from warehouse to Transit//////////////////////////////////////////////
if (isset($_POST['verified'])){	

	
$item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, pre_stock, pre_price, item_in, item_ex, item_price, final_stock, tr_from,tr_no,sr_no,entry_by,entry_at,consumption_for_fg,ip,custom_no,lot_number) VALUES 
('".$datas->pi_date."','".$datas->item_id."','15','".$datas->warehouse_to."','','','','".$RCVQTY."','','','RMPMISSUERCV','".$datas->RcvID."','".$datas->custom_pi_no."','$_SESSION[userid]','$enat','','$ip','".$datas->custom_pi_no."','".$datas->lot."')");


$item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, pre_stock, pre_price, item_in, item_ex, item_price, final_stock, tr_from,tr_no,sr_no,entry_by,entry_at,consumption_for_fg,ip,custom_no,lot_number) VALUES 
('".$datas->pi_date."','".$datas->item_id."','".$datas->warehouse_to."','15','','','".$RCVQTY."','','','','RMPMISSUERCV','".$datas->RcvID."','".$datas->custom_pi_no."','$_SESSION[userid]','$enat','','$ip','".$datas->custom_pi_no."','".$datas->lot."')");


mysql_query("UPDATE production_issue_detail SET total_unit_received='$RCVQTY' where id='".$data->RcvID."'");
}
/// end of row material consumption						  
				  
				  
				  
				  
			  ?>
              <tr>
              <td><?=$i=$i+1;?></td>
              <td><?=$datas->finish_goods_code;?></td>
              <td><?=$datas->item_name;?></td>
              <td><?=$datas->unit_name;?></td>
              <td><?=$datas->total_unit;?></td>
               <td><input type="text" name="rcvQTY_<?=$datas->RcvID?>" id="rcvQTY_<?=$datas->RcvID?>" value="<?=$datas->total_unit;?>"></td>
              </tr>
              <?php }
			  
      $datereal=$data->pi_date;
	 list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $daterea);	
	 $date=$day.'-'.$month.'-'.$year1;
	 
	 //voucher date decode
	$j=0;
	for($i=0;$i<strlen($date);$i++)
	{
		if(is_numeric($date[$i]))
		{
			$time[$j]=$time[$j].$date[$i];
		}
		else 
		{
			$j++;
		}
	}
	$date=mktime(0,0,0,$time[1],$time[0],$time[2]);
	//////////////////////
	//check date decode
	$j=0;
	for($i=0;$i<strlen($c_date);$i++)
	{
	if(is_numeric($c_date[$i]))
	$ptime[$j]=$ptime[$j].$c_date[$i];
	else $j++;
	}
	$c_date=mktime(0,0,0,$ptime[1],$ptime[0],$ptime[2]);
	//////////////////////////	
	
	$rowSQL = mysql_query( "SELECT MAX( jv_no ) AS jv_no FROM `journal`;" );
$row = mysql_fetch_array( $rowSQL );
$largestNumber = $row['max'];
$jv=$row['jv_no']+1;

$transfer_toLedger=getSVALUE("warehouse", "ledger_id_RM", " where warehouse_id=".$data->warehouse_to);	

if (isset($_POST['verified'])){

$journal="INSERT INTO `journal` (
									`proj_id` ,
									`jv_no` ,
									`jv_date` ,
									`ledger_id` ,
									`narration` ,
									`dr_amt` ,
									`cr_amt` ,
									`tr_from` ,
									`sub_ledger` ,
									`tr_no`,
									`tr_id`,
									`cc_code` 
									,user_id
									,group_for,custom_no,jvdate
									)
					VALUES ('', '$jv', '$date', '$transfer_toLedger', 'RM/PM REceived, ISSUENO#$_GET[custom_pi_no]/$_GET[pi_no]', '$totalVALUE','', 'RMPMISSUERCV','', '$_GET[pi_no]','', '','$user_id','".$_SESSION['user']['group']."', '$_GET[custom_pi_no]','$data->pi_date')";					
				$query_journal = mysql_query($journal);	
				
		

$journal2="INSERT INTO `journal` (
									`proj_id` ,
									`jv_no` ,
									`jv_date` ,
									`ledger_id` ,
									`narration` ,
									`dr_amt` ,
									`cr_amt` ,
									`tr_from` ,
									`sub_ledger` ,
									`tr_no`,
									`tr_id`,
									`cc_code` 
									,user_id
									,group_for,custom_no,jvdate
									)
					VALUES ('', '$jv', '$date', '1007002900070000', 'RM/PM REceived,ISSUENO#$_GET[custom_pi_no]/$_GET[pi_no]', '','$totalVALUE', 'RMPMISSUERCV','', '$_GET[pi_no]','', '','$user_id','".$_SESSION['user']['group']."', '$_GET[custom_pi_no]','$data->pi_date')";					
				$query_journal2 = mysql_query($journal2);
				
				mysql_query("UPDATE production_issue_master SET verifi_status='COMPLETED' where pi_no='$_GET[pi_no]'")
					?>
                <meta http-equiv="refresh" content="0;rmpmissuereceived.php">
			 <?php }  ?>
              
              
              
              </table>   
                  
              <div align="center"><button type="submit" name="verified" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Checked & Forwarded</button>  </div>  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  <?php } else { ?>
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">PI NO</th>
                     <th style="width:8%">PI Date</th>
                     <th style="width:15%">Received From</th> 
                     
                     <th style="width:5%; text-align:center">Entry By</th>
                     <th style="width:5%; text-align:center">Checked By</th>                     
                     </tr>
                     </thead>





                      <tbody>






<?php


$resultss=mysql_query("Select * from production_issue_master where status='ISSUE' and verifi_status='CHECKED' order by pi_no DESC ");

while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='rmpmissuereceived.php?pi_no='.$rows[pi_no].'&'.'custom_pi_no='.$rows[custom_pi_no].'&prno='.$rows[pr_no];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><a href="<?php echo $link; ?>" target="_blank"><?=$rows[custom_pi_no];?> (<?=$rows[pi_no];?>)</a></td>
                        <td><a href="<?php echo $link; ?>" target="_blank"><?php echo $rows[pi_date]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" target="_blank"><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></a></td>
                        
                       <td><a href="<?php echo $link; ?>" target="_blank"><?=$fgname=getSVALUE("users", "username", "where user_id='".$rows['entry_by']."'");?></a></td>
                       <td><a href="<?php echo $link; ?>" target="_blank"><?=$fgname=getSVALUE("users", "username", "where user_id='".$rows['verifi_by']."'");?></a></td>
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
</form>


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
<!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- /Datatables -->
 <script>
      $(document).ready(function() {
        $('#f_date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    
    
    <script>
      $(document).ready(function() {
        $('#t_date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
  </body>

