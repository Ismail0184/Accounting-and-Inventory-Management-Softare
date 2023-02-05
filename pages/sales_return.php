<?php
 require_once 'support_file.php'; 
 $title='Sales Return (Saleable)';
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
	var val=form.dealer_code.options[form.dealer_code.options.selectedIndex].value;
	self.location='sales_return.php?dealer_code_GET=' + val ;
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
								<a class="btn btn-sm btn-default"  href="MAN_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">MAN Report</span>
								</a>
                                
                                
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              
                    

<?php 
$initiate=$_POST[initiate];


$d =$_POST[sr_date];
$sr_date=date('Y-m-d' , strtotime($d)); 

$nam_date=$_POST[nam_date];
$invoice=$_POST[invoice];
$billno=$_POST[billno];
$vendorid=$_POST[vendorid];
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
				$entryat=$dateTime->format("d-m-Y  h:i A");
				
				
///////////////////////////////////////// initiate start from here				
if(isset($initiate)){	
	
$insert=mysql_query("INSERT INTO sale_return_master (sr_no,ruterninvoice,do_date,dealer_code,depot_id,remarks,ip,entry_by,entry_at,status)  VALUES ('$invoice','$_POST[ruterninvoice]','$sr_date','$_POST[dealer_code]','$_POST[depot_id]','$_POST[remarks]','$ip','$_SESSION[userid]','$entryat','MANUAL')");	

$_SESSION[initiate_sr_documents]=$invoice;
}


if(isset($_POST[updateMAN])){	
	
$insert=mysql_query("UPDATE sale_return_master SET  delivary_challan='$delivary_challan',VAT_challan='$VAT_challan' WHERE MAN_ID='".$_SESSION[initiate_sr_documents]."' ");	


}



$resultsssss=mysql_query("Select * from sale_return_master where sr_no='$_SESSION[initiate_sr_documents]'");
$inirow=mysql_fetch_array($resultsssss);

 
 $qtysss=getSVALUE("MAN_details", "Sum(qty) as qty", " where MAN_ID='$_SESSION[initiate_sr_documents]'");
 $ofp=getSVALUE("MAN_details", "Sum(no_of_pack)", " where MAN_ID='$_SESSION[initiate_sr_documents]'");
 $ratesss=getSVALUE("MAN_details", "Sum(rate) as rate", " where MAN_ID='$_SESSION[initiate_sr_documents]'");
 $amountsss=getSVALUE("MAN_details", "Sum(amount) as amount", " where MAN_ID='$_SESSION[initiate_sr_documents]'");
 ?>   



                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    
                   <table style="width:100%">






<tr>
<td style="width:50%"> 
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Dealer / Customer<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <select id="first-name" required="required" style="width:250px" onchange="javascript:reload(this.form)"   name="dealer_code" class="select2_single form-control">
                        <option value="">Choose ......</option>
                        
                        <?php $resultVENDOR=mysql_query("Select * from dealer_info order by dealer_name_e");
						while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
							if($rowVENDOR[dealer_code]==$_GET[dealer_code_GET]){
						?> 
                                         
                 <option selected value="<?php echo $_GET[dealer_code_GET]; ?>"><?=$companyname=getSVALUE("dealer_info", "dealer_name_e", "where dealer_code='".$_GET[dealer_code_GET]."'");?></option>
                 <?php }  else { ?>
                      <option value="<?php echo $rowVENDOR[dealer_code]; ?>"><?php echo $rowVENDOR[dealer_name_e]; ?></option>
                    <?php }} ?></select></div></div>
                    
                    
 </td>
<td style="width:50%">                   
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Invoice / DO<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_SESSION[initiate_sr_documents]){ ?>
                        <input type="text" id="first-name" style="width:250px" value="<?php if($_SESSION[initiate_sr_documents]){ echo $inirow[ruterninvoice];} else { echo ''; } ?>" name="ruterninvoice" class="form-control col-md-7 col-xs-12"  >
						
						<?php } else { ?>
                        <select id="first-name" required="required" style="width:250px"   name="ruterninvoice" class="select2_single form-control">
                        <option value="">Choose ......</option>
                        
                        <?php $resultINVOICE=mysql_query("Select distinct chalan_no,do_no from sale_do_chalan where dealer_code='$_GET[dealer_code_GET]' order by chalan_no Desc");
						while($rowINVOICE=mysql_fetch_array($resultINVOICE)){
							
						?> 
                      <option value="<?php echo $rowINVOICE[chalan_no]; ?>"><?php echo $rowINVOICE[chalan_no]; ?> (DO - <?php echo $rowINVOICE[do_no]; ?>)</option>
                    <?php }s ?></select><?php } ?></div></div>
                    
 </td>                   
                    


 <tr>
            <td> 

<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SR NO<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="last-name" style="width:250px"   required="required" name="invoice" value="<?php if($_SESSION[initiate_sr_documents]){ echo $inirow[sr_no];} else { echo $_SESSION['SRT']; } ?>" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_sr_documents]){ ?> readonly <?php } ?> >
                          </div>
                      </div> </td>



<td>
                 <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SR Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
                        
                 <input type="text" id="sr_date" style="width:250px"  required="required" name="sr_date" value="<?php if($_SESSION[initiate_sr_documents]){ echo date('m/d/y' , strtotime($inirow[do_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >       
                        
	            <!---input type="text" id="last-name"  required="required" name="sr_date" value="<?php if($_SESSION[initiate_sr_documents]){ echo $inirow[do_date]; } else { echo date('Y-m-d'); } ?>" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_sr_documents]){ ?> readonly <?php } ?> --->

                      </div>  
	                </div>
  </td></tr>                  
                    
                    
            <tr><td>        
               <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Receiving / CMU / Depot<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
					    <select id="depot_id" required="required" style="width:250px"   name="depot_id" class="select2_single form-control">
                        <?php if($_SESSION[initiate_sr_documents]){ ?>
                        <option value="<?php echo $inirow[depot_id]; ?>" selected><?=$warename=getSVALUE("warehouse", "warehouse_name", " where warehouse_id='$inirow[depot_id]'");?></option>
                        <?php } ?>
                        <option value="">Choose ......</option>
                        
                        <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL','WH')  order by warehouse_id");
						while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
						?> 
                                         
                 <option value="<?php echo $rowVENDOR[warehouse_id]; ?>"><?php echo $rowVENDOR[warehouse_name]; ?></option>
                      
                    <?php } ?></select></div></div>   </td>
                      
                      
                      
                       

                      
                      
        
        
       

                  <td>  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Note<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                
                        <input type="text" id="last-name" style="width:250px"  required="required" name="remarks" value="<?php if($_SESSION[initiate_sr_documents]){ echo $inirow[remarks];} else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >
                        </div></div></td></tr>
                        
                        
                        
                        
                       
                        
                       
               
              <tr>
       <td colspan="4">  
               <div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_sr_documents]){  ?>
			   
			   <!---a href="sales_return.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
               <button type="submit" name="updateMAN" class="btn btn-success">Update Sales Return</button>
			   
			 <?php   } else {?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Initiate Sales Return</button>
               <?php } ?>
               </div></div></td></tr> 
               </table>
               
                          
               
               
               </form>
               
  
<?php if($_SESSION[initiate_sr_documents]){  ?>


<!-----------------------Data Save Confirm ------------------------------------------------------------------------->  

<?php 
							if($_GET[type]=='delete'){
								if($_GET[productdeletecode]){
								
							$results=mysql_query("Delete from MAN_details where id='$_GET[productdeletecode]'"); ?>
							<meta http-equiv="refresh" content="0;sales_return.php">
	
								
							<?php }} ?>
                      
<form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
                     <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                        <th>SL</th>
                          <th>Code</th>
                          <th>Product</th>  
                          <th style="width:10%; text-align:center">Pack Size</th> 
                          <th style="width:5%; text-align:center">UOM</th>                          
                          <th style="width:10%; text-align:center">Sales Qty (Pcs)</th>
                          <th style="width:10%; text-align:center">Return Qty</th>
                         
                        
                        

                        </tr>
                      </thead>


                      <tbody>
                        <?php
			
if (isset($_POST['confirmsave'])){
$valid = true;  

  ?>
	
<meta http-equiv="refresh" content="0;sales_return.php">
<?php } ?>						
						
						 
				<?php $results=mysql_query("Select * from sale_do_chalan where   chalan_no='$inirow[ruterninvoice]'");
				while($row=mysql_fetch_array($results)){ 
				$i=$i+1;
				$pksize=getSVALUE("item_info", "pack_size", "where item_id='".$row['item_id']."'");
				$t_price=getSVALUE("item_info", "t_price", " where item_id='$row[item_id]'");
				$ids=$row[id];
				$returnqty=$_POST['returnqty'.$ids];
				$total_amt=$returnqty*$row[unit_price];
				if (isset($_POST['confirmsave'])){
$valid = true;
if( $returnqty>0){ 
mysql_query("INSERT INTO  sale_return_details (do_no,sr_no,item_id,dealer_code,unit_price,pkt_size,pkt_unit,dist_unit,total_unit,total_amt,depot_id,t_price,status,gift_on_order,gift_on_item,gift_id,do_date,entry_time,ruterninvoice) VALUES 
('$inirow[do_no]','$inirow[sr_no]','$row[item_id]','$inirow[dealer_code]','$row[unit_price]','$pksize','$returnqty','','$returnqty','$total_amt','$inirow[depot_id]','$t_price','UNCHECKED','','','','$inirow[do_date]','','$inirow[ruterninvoice]')");

}
				
mysql_query("UPDATE sale_return_master SET status='UNCHECKED',depot_id='$inirow[depot_id]',cashdiscount='$_POST[cashdiscount]' where sr_no='$inirow[sr_no]'"); 
unset($_SESSION['initiate_sr_documents']); 				
				
				}
				 ?>
				
				
				

                      <tr>
                        <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                        <td style="width:8%; vertical-align:middle"><?= $fgcode=getSVALUE("item_info", "finish_goods_code", " where item_id='$row[item_id]'");?></td>
                        
                        
               <td style="vertical-align:middle"><?=$name=getSVALUE("item_info", "item_name", "where item_id='".$row['item_id']."'"); if($row[total_amt]==0) echo ' <strong style="color:red">[FREE]</strong>'; ?></td>
               <td style="vertical-align:middle; text-align:center"><?=$pksize?></td>
               <td style="vertical-align:middle"><?=$unit=getSVALUE("item_info", "unit_name", "where item_id='".$row['item_id']."'"); ?></td>
                        
                        
                      
                        <td align="center" style="width:6%; text-align:center"><?php echo $row[total_unit]; ?></td>                       
                        
                        <td align="center" style="width:6%; text-align:center">
                        <input type="text" id="" style="width:100px; height:20px; margin-left:1%; font-weight:bold; text-align:center; float:none"    name="returnqty<?php echo $ids;?>"  value="" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        
                       
                  </tr>
                        
                        
                     
                        <?php } ?>
                        
                        
                      
                      </tbody>
                      
               
                      
                  
 <tr>
                      
                      
                      <td colspan="4" style="font-weight:bold; font-size:14px" align="right">Total</td>           
                     
                      <td align="center" ><strong><?php echo $qtysss; ?></strong></td>
                      <td></td>
                      <td align="center" ><strong><?php echo $ofp; ?></strong></td>
                      </tr>
                      
                      
 
 					

                      <tr>
                  <td style="text-align:right" colspan="5">Cash Discount: </td>
                  
                  <td colspan="2">
                  
                  <div class="form-group">
                  
                        <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="cashdiscount"   required="required" style="width:200px"  name="cashdiscount"  class="form-control col-md-7 col-xs-12" >
                         
                      </div></div></td>
                  </tr> 
                       
                      
                     
                    
                    
                     
                  
                  
                  
                      
                      
                      
                      <tr>
                      <td colspan="8" style="text-align:center">
                     
                        
                        
                        
                        
                       <?php 
					   
					   $cancel=$_POST[cancel];
					   
					   if(isset($cancel)){
					  $delete=mysql_query("Delete from MAN_master where MAN_ID='$_SESSION[initiate_sr_documents]'");
  
  
$deletes=mysql_query("Delete From MAN_details where MAN_ID='$_SESSION[initiate_sr_documents]'");
					   
					   unset($_SESSION["initiate_sr_documents"]);
					   
					   
					   ?>
                       <meta http-equiv="refresh" content="0;sales_return.php">

                       <?php } ?>
                          
                          <button type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete Sales Return?");' class="btn btn-primary">Delete Sales Return </button>
                          <button type="submit" name="confirmsave" class="btn btn-success">Confirm and Finish Sales Return </button>
                          
                          
                       
                      </td></tr> 
                    </table>  
                   
</form>
                  </div>

                </div>

              </div>
            
<?php } ?>               
              
   













              
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
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script>
      $(document).ready(function() {
        $('#birthday').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    <!-- /bootstrap-daterangepicker -->

    <!-- bootstrap-wysiwyg -->
    <script>
      $(document).ready(function() {
        function initToolbarBootstrapBindings() {
          var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
              'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
              'Times New Roman', 'Verdana'
            ],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
          $.each(fonts, function(idx, fontName) {
            fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
          });
          $('a[title]').tooltip({
            container: 'body'
          });
          $('.dropdown-menu input').click(function() {
              return false;
            })
            .change(function() {
              $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
            })
            .keydown('esc', function() {
              this.value = '';
              $(this).change();
            });

          $('[data-role=magic-overlay]').each(function() {
            var overlay = $(this),
              target = $(overlay.data('target'));
            overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
          });

          if ("onwebkitspeechchange" in document.createElement("input")) {
            var editorOffset = $('#editor').offset();

            $('.voiceBtn').css('position', 'absolute').offset({
              top: editorOffset.top,
              left: editorOffset.left + $('#editor').innerWidth() - 35
            });
          } else {
            $('.voiceBtn').hide();
          }
        }

        function showErrorAlert(reason, detail) {
          var msg = '';
          if (reason === 'unsupported-file-type') {
            msg = "Unsupported format " + detail;
          } else {
            console.log("error uploading file", reason, detail);
          }
          $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
        }

        initToolbarBootstrapBindings();

        $('#editor').wysiwyg({
          fileUploadError: showErrorAlert
        });

        window.prettyPrint;
        prettyPrint();
      });
    </script>
    <!-- /bootstrap-wysiwyg -->

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select a state",
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

    <!-- jQuery Tags Input -->
    <script>
      function onAddTag(tag) {
        alert("Added a tag: " + tag);
      }

      function onRemoveTag(tag) {
        alert("Removed a tag: " + tag);
      }

      function onChangeTag(input, tag) {
        alert("Changed a tag: " + tag);
      }

      $(document).ready(function() {
        $('#tags_1').tagsInput({
          width: 'auto'
        });
      });
    </script>
    <!-- /jQuery Tags Input -->

    <!-- Parsley -->
    <script>
      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form .btn').on('click', function() {
          $('#demo-form').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });

      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form2 .btn').on('click', function() {
          $('#demo-form2').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form2').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });
      try {
        hljs.initHighlightingOnLoad();
      } catch (err) {}
    </script>
    <!-- /Parsley -->

    <!-- Autosize -->
    <script>
      $(document).ready(function() {
        autosize($('.resizable_textarea'));
      });
    </script>
    <!-- /Autosize -->

    <!-- bootstrap-daterangepicker -->
    <script>
      $(document).ready(function() {
        $('#sr_date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>

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
