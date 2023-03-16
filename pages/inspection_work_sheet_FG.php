<?php
 require_once 'support_file.php'; 
 $title='Inspection Work Sheet (FG)';
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
	var val=form.productcode.options[form.productcode.options.selectedIndex].value;
	self.location='purchase.php?productcodeget=' + val ;
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
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Inspection Work Sheet Report</span>
								</a>
                                
                                
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              
                    

<?php 
        $_POST['entry_at']=date('Y-m-d h:s:i');
		$_POST['entry_by']=$_SESSION[userid];
		$warehouseid=$_SESSION['user']['depot'];
		$inspdate=date('Y-m-d');
        $wareid=getSVALUE("production_floor_receive_master","warehouse_to","where custom_pr_no='".$_GET[custom_pr_no]."'");
		
if(isset($_POST[initite])){
	
	mysql_query("INSERT INTO QC_Inspection_Work_Sheet_master (
	t_id,
	warehouse_id,
	MAN_ID,
	item_id,
	inspection_date,
	inspection_lot_no,
	vendor_code,
	delivary_challan,
	VAT_challan,
	Mfg,
	Exp_Date,
	Receipt_Date,
	Release_Date,
	packing_labeling,
	Physical_Properties,
	batch,
	Sample_Size,
	Received_Qty,
	No_of_pack_physically_checked,
	No_of_sample_scraped,
	entry_by,
	entry_at,
	opinion,
	sample_qty,
	accepted_qty,
	rejected_qty,
	hold_qty,
	production_lot,
	status,
	inspection_for,ip,analyst
	) VALUES ('$_GET[id]','$wareid','$_GET[custom_pr_no]','$_GET[item_id]','$inspdate','$_POST[inspection_lot_no]','$_POST[vendor_code]','$delivary_challan','$VAT_challan','$_POST[Mfg_Date]','$_POST[Exp_date]','$_POST[Receipt_date]','$_POST[Release_Date]','$_POST[packing_labeling]','$_POST[Physical_Properties]','$_POST[batch]','$_POST[Sample_Size]','$_POST[Received_Qty]','$_POST[No_of_pack_physically_checked]','$_POST[No_of_sample_scraped]','".$_POST['entry_by']."','".$_POST['entry_at']."','$_POST[opinion]','$_POST[sample_qty]','$_POST[accepted_qty]','$_POST[rejected_qty]','$_POST[hold_qty]','$_POST[production_lot]','CHECKED','FG','$ip','$_POST[analyst]')"); ?>
	
<meta http-equiv="refresh" content="0;production_checked.php?custom_pr_no=<?php echo $_GET[custom_pr_no]?>">	
	
	
<?php } ?>   



                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">


<table width="95%"  style="border:none; margin-top:10px; color:#999; " cellspacing="0" cellpadding="1">

<tr style="border:none"><th style="text-align:left; width:15%">Item Name</th><th style="text-align:center; width:5%">:</th><td colspan="4" style="text-align:left; font-size:18px;"><strong><em><?=$item_id=getSVALUE("item_info", "item_name", " where item_id='$_GET[item_id]'"); ?></em></strong></td></tr>

<tr style="border:none"><th style="text-align:left; width:15%">Insp. Lot No.</th><th style="text-align:center; width:2%">:</th><td colspan="4" style="text-align:left; font-size:18px; color:red">

<input type="text" id="inspection_lot_no"  required="required" name="inspection_lot_no" value="<?php echo $_SESSION['POFGunique_id']; ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px" readonly >
</td></tr>



<tr style="border:none">
<th style="text-align:left; width:15%">Mfg Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="Mfg_Date" id="Mfg_date" value="<?=getSVALUE("production_floor_receive_detail","mfg","where custom_pr_no='".$_GET[custom_pr_no]."' and item_id='".$_GET[item_id]."'");?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>

<th style="text-align:left; width:15%">Batch No</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="batch" id="batch" value="<?=$btch=getSVALUE("production_floor_receive_detail","batch","Where custom_pr_no='".$_GET[custom_pr_no]."' and item_id=".$_GET[item_id]); ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
</tr>



<tr style="border:none">
<th style="text-align:left; width:15%">Exp Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="Exp_date" id="Exp_date"  class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" /></td>

<th style="text-align:left; width:15%">Lot No</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="production_lot" id="production_lot" value="<?=$btch=getSVALUE("production_floor_receive_detail","lot","Where custom_pr_no='".$_GET[custom_pr_no]."' and item_id=".$_GET[item_id]); ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
</tr>


<tr style="border:none">
<th style="text-align:left; width:15%">Receipt Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="Receipt_date" id="Receipt_date" value="<?=$lot= getSVALUE('MAN_details','man_date','MAN_ID="'.$_GET[manid].'" and item_id='.$_GET[item_id]); ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>

<th style="text-align:left; width:15%;">Qty.</th><th style="text-align:center; width:2%">:</th><td style="color:#000; font-size:12px; vertical-align:bottom"><input type="hidden" name="accepted_qty" id="accepted_qty" value="<?=$total_unit=getSVALUE("production_floor_receive_detail","SUM(total_unit)","Where custom_pr_no='$_GET[custom_pr_no]' and id='$_GET[id]'"); ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" >


<input type="text" name="accepted_qtyname" id="accepted_qtyname" value="<?php 

$psiz=getSVALUE("item_info","pack_size","Where item_id=".$_GET[item_id]);
echo $total_unit=getSVALUE("production_floor_receive_detail","total_unit","Where custom_pr_no='".$_GET[custom_pr_no]."' and id=".$_GET[id])/$psiz; ?> (<?=$psiz=getSVALUE("item_info","unit_name","Where item_id=".$_GET[item_id]);?>)" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" readonly ></td>
</tr>



<tr style="border:none">
<th style="text-align:left; width:15%">Release Date</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="Release_Date" id="Release_Date" value="<?=date('Y-m-d')?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>

<th style="text-align:left; width:15%">Sample Size</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="text" name="Sample_Size" id="Sample_Size" value="<?=$mfg= getSVALUE('MAN_details','qty','MAN_ID="'.$_GET[manid].'" and item_id='.$_GET[item_id]); ?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:130px; margin-top:5px" ></td>
</tr>








<tr style="border:none">
<th style="text-align:left; width:15%">Analyst</th><th style="text-align:center; width:2%">:</th><td style="text-align:left; font-size:18px; color:red"><input type="hidden" name="analyst" value="<?=$_SESSION[userid]?>" class="form-control col-md-7 col-xs-12" style="height:25px; width:170px; margin-top:5px" >
<input type="text" name="" value="<?=$cmuname= getSVALUE('users','fname','where user_id='.$_SESSION[userid]);?>" readonly class="form-control col-md-7 col-xs-12" style="height:25px; width:170px; margin-top:5px" >
</td>


</tr>



</table>




<table width="95%" border="1"  style=" border-collapse:collapse; height:30px; margin-top:10px; color:#000;" cellspacing="0" cellpadding="1">
<tr><th style="text-align:center; width:5%">S. No</th><th style="text-align:center; width:30%">TEST PARAMETERS</th><th style="text-align:center; width:10%">RESULT</th><th style="text-align:center">SPECIFICATION</th></tr>

<?php
		
		$speresult=mysql_query("Select * from item_SPECIFICATION where  item_id='$_GET[item_id]'");
		while($sprow=mysql_fetch_array($speresult)){
			$i=$i+1;
		 ?>
        <tr>
          <td align="center" valign="top"><?=$i?></td>
          <td align="left" valign="top"><?=$PARAMETERS=getSVALUE("PARAMETERS", "PARAMETERS_Name", " where PARAMETERS_CODE='$sprow[TEST_PARAMETERS]'");?></td>
          <td align="left" valign="top"><input type="text" name="result" style="width:100px" value="<?=$sprow[RESULT];?>" ></td>
          <td align="center" valign="top"><?=$sprow[SPECIFICATION];?></td>
          
        </tr>
        <?php } ?>

</table>




<table width="95%"  style="border:none; margin-top:10px; " cellspacing="0" cellpadding="1">
<tr style="border:none">
<th style="text-align:left; width:15%">Opinion</th><th style="text-align:center; width:2%">:</th><td colspan="4" style="text-align:left; font-size:18px; color:red"><select style="color:#000; font-size:13px; height:25px; width:130px" name="opinion" id="opinion" required>
<option value="" selected></option>
<option value="Sample conforms to I.H.S">Sample conforms to I.H.S</option>
<option value="Sample not conform to I.H.S">Sample not conform to I.H.S</option>

</select>
</td></tr>

<tr style="border:none">
<th style="text-align:left; width:15%">Remarks</th><th style="text-align:center; width:2%">:</th><td colspan="4" style="text-align:left; font-size:18px; color:red"><input type="text" style="color:#000; font-size:13px; height:25px; width:130px; margin-top:5px" name="Remarks" id="Remarks" >

</td></tr>
<tr><th style="height:30px"></th></tr>
<tr style="border:none">





<th colspan="4" align="center">
<div class="form-group" style="margin-left:40%">               
<div class="col-md-6 col-sm-6 col-xs-12">              
<button type="submit" name="Reprocessing" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Send for Reprocessing </button></div></div> 


</th>

<th colspan="4" align="center">

<div class="form-group" style="margin-left:40%">               
<div class="col-md-6 col-sm-6 col-xs-12">              
<button type="submit" name="initite" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Confirm & Forword Inspection </button></div></div> </th>
</tr>
</table> 
                   
</form>
                  </div>

                </div>

              </div>
            
             
              
   













              
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

    <!-- bootstrap-daterangepicker -->
    <script>
      $(document).ready(function() {
        $('#ps_date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    
    
    
    <script>
      $(document).ready(function() {
        $('#mfg').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    <!-- /bootstrap-daterangepicker -->

   

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
    
    
    
    

   
    

   

   

    <!-- Starrr -->
    <script>
      $(document).ready(function() {
        $(".stars").starrr();

        $('.stars-existing').starrr({
          rating: 4
        });

        $('.stars').on('starrr:change', function (e, value) {
          $('.stars-count').html(value);
        });

        $('.stars-existing').on('starrr:change', function (e, value) {
          $('.stars-count-existing').html(value);
        });
      });
	  
	  
	  
	  $('#rate').keyup(function(){
        var qtys;
        var rate;
        qtys = parseFloat($('#qtys').val());
        rate = parseFloat($('#rate').val());
		
        var amounta = qtys * rate;
        $('#amounta').val(amounta.toFixed(2));


    });
    </script>
    <!-- /Starrr -->
  </body>
</html>
