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
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM company WHERE companyid=".$_SESSION['companyid']);
 $userRow=mysql_fetch_array($res);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $_SESSION[company]; ?> | Market Bill Collection</title>

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

function doAlert(form)
{
var val=form.amount.value;
var val2=form.balance.value;

if (Number(val)>Number(val2)){
alert('oops!! Exceed Due Balance!! Thanks');

form.amount.value='';
}
form.amount.focus();
}</script> 
  <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.ledger.options[form.ledger.options.selectedIndex].value;
	self.location='market_bill_collection.php?ledgercode=' + val ;
}


</script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>Raresoft</span></a>
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
                    <h2>Cash with Barcode</h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="market_ledger.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Market Ledger</span>
								</a>
                                
                                
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="market_stock_out.php">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Stock Out</span>
                    			</a>
		 						
								</a>
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    
                    
<?php 

				
	$getstarted=$_POST[getstarted];
	$companyid=$_SESSION['companyid'];
	$invoice=$_POST[invoice];
	$billno=$_POST[billno];
	$supplier=$_POST[supplier];
	$warehouse=$_POST[warehouse];
	$Note=$_POST[Note];
	$category=$_POST[category];
	$product=$_POST[product];
	$productcode=$_POST[productcode];
    $createby=$_SESSION['login_email'];	
	$createdate=date('Y-m-d');
	$vendor=$_POST[vendor];
	$vendorphone=$_POST[vendorphone];
	$vendoraddress=$_POST[vendoraddress];
	



 ?>                   
                    
                    
                    
                    
                    
                    
                    <?php
					$barcode=$_POST[barcode];
					
					if($_GET[barcode]){ } else {
					
					 ?>
                   <form action="sales_cash_barcode.php?productcode=<?php echo $barcode; ?>" id="demo-form2" method="get" data-parsley-validate class="form-horizontal form-label-left">
                   
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Barcode</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="-1"   required="required" name="barcode" >
                            <option></option>
                            
                            <?php 
							$result=mysql_query("Select * from transaction_inventory where  companyid='$_SESSION[companyid]'");
							while($row=mysql_fetch_array($result)){ ?>
								
					<?php
					if($row[tfor]=='Sales'){
						
					} else {
					 ?>			
                  <option  value="<?php echo $row[productcode]; ?>"><?php echo $row[productcode]; ?></option>
                    <?php } }?>
                          </select>
                        </div>
                      </div>
                      
                      
                      
                      
                       <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                       
                          
                          <button type="submit" name="getstarted" value="ok"  class="btn btn-success">Search Barcode </button>
                          
                          
                        </div>
                      </div>  
                    </form>
                    <?php } ?>
                    
                    
                    <?php if($_GET[barcode]){ ?>
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    
                   
                
                <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sales Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="last-name"  required="required" name="purchasedate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">

                      </div>  
	                </div>
                
                
                      
                     
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Invoice Number<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   required="required" name="invoice" value="<?php echo $_SESSION['csalesno']; ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>  
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bill Number<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"   required="required" name="billno" value="<?php echo $_SESSION['svoucher']; ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $rowsmaingroup[vendor] ?>" name="vendor" class="form-control col-md-7 col-xs-12" ></div></div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Contact Number<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $rowsmaingroup[vendorphone] ?>" name="vendorphone" class="form-control col-md-7 col-xs-12" ></div></div>
                        
                        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Address<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name" required="required" value="<?php echo $rowsmaingroup[vendoraddress] ?>" name="vendoraddress" class="form-control col-md-7 col-xs-12" ></div></div>
                    
                    
                    
                    
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Destination Warehouse<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        <input type="text" id="first-name" required="required" value="<?php echo $rowsmaingroup[warehousename] ?>" name="warehouse" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required"   name="warehouse" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        
                        <?php $result=mysql_query("Select * from warehouse where companyid='$_SESSION[companyid]'");
						while($rowmaingroup=mysql_fetch_array($result)){
						?> 
                                         
                 <option value="<?php echo $rowmaingroup[warehousename]; ?>"><?php echo $rowmaingroup[warehousename]; ?></option>
                      
                    <?php } ?></select><?php } ?></div></div>
                      
                      
                      
                      
                     
                     
                  
                    
                   
                   
                 
                    
                    
                   
                   
                   
                   
                   
                   
                   <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                   
                   
                   
                  <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width:5%">Barcode</th>
                          <th>Product</th>
                          
                          
                          
                          
                          
                          <th style="width:10%">Stock <br>Balance</th>
                          <th style="width:10%">Purchase <br>Rate</th>
                          <!---th style="width:10%">Qty</th--->
                          <th style="width:10%">Rate</th>
                         
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				$results=mysql_query("Select * from transaction_inventory where productcode='$_GET[barcode]' and companyid='$_SESSION[companyid]' and tfor='Purchase'");
				while($row=mysql_fetch_array($results)){
				$ids=$row[productcode];
				$product=$row[product];
				$categoryid=$row[categoryid];
				$category=$row[categorys];
				$brandid=$row[brandid];
				$brand=$row[brand];
				$modelid=$row[modelid];
				$model=$row[model];
				$unit=$row[unit];
				$inventory_types=$row[inventory_types];
				$itemcode=$_POST[itemcode.$ids];
				$sbalance=$_POST[sbalance.$ids];
				$qtys='1';
				$rate=$_POST[rate];
				$amount=$qtys*$rate;
				$amounts=$amounts+$amount;
	            
	
	
	
	
if (isset($_POST['getstarted'])){
$valid = true;
	 


if ( $qtys>$sbalance)

    {echo "<script> alert('This $row[product] is not available more then $sbalance qty!!') </script>";
        $valid = false;}	

	 
	 
if ($valid){
	if($rate>0){
 $result=mysql_query("INSERT INTO `transaction_inventory` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,purchaseclint,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,vendor,vendorphone,vendoraddress) VALUES 
 ('$inventory_types','$categoryid','$category','$brandid','$brand','$modelid','$model','$ids','$product','$unit','$invoice','$billno','$Jvoucherno','$supplier','-$qtys','$rate','$amount','$coss','$amtcoss','$adjustlevel','$adjustamount','$lifofifoid','$note','$warehouse','$acchead','Cash Received','$companyid','$createby','Cash Sales','$ttime','Normal','$createdate','$tmodifiddate','$modifiedby','$ip','$mac','Sales','$vendor','$vendorphone','$vendoraddress')");
 //cashsalesno();
  ?>
	
<?php }}}
				
				
				









				
				 ?>
                      <tr>
                        
                        <td><?php echo $row[productcode]; ?></td>
                        <td><?php echo $row[categorys]; ?> - <?php echo $row[brand]; ?> - <?php echo $row[model]; ?> - <?php echo $row[productcode]; ?></td>
                        
                        
                        
                        
                        
<?php 
	$stockbalance=getSVALUE("transaction_inventory", "SUM(qty) as qty", "where productcode='$row[productcode]'  and companyid='$_SESSION[companyid]'"); ?>
                        
<?php $purchaserate=getSVALUE("transaction_inventory", "rate", "where productcode='$row[productcode]'  and companyid='$_SESSION[companyid]'"); ?>

<?php if($stockbalance>0) {  ?>
     <td align="center"><input type="text" readonly id="last-name"  style="width:80px; color:#F00; font-weight:bold; text-align:center" name="sbalance<?php echo $ids; ?>" value="<?php if($stockbalance>0)  { echo $stockbalance; } else { echo 0;} ?>" class="form-control col-md-7 col-xs-12"></td>
   
   
<td align="center"><?php echo $purchaserate; ?></td> 

 <input type="hidden" id="last-name" style="width:120px" name="itemcode<?php echo $ids; ?>" value="<?php echo $row[itemcode]; ?>" class="form-control col-md-7 col-xs-12">
                     
                     <input type="hidden" id="last-name" style="width:80px" name="qtys" value="<?php echo $row[qty] ?>" class="form-control col-md-7 col-xs-12">                       
                        
                     <!--td align="center">
                     
                    
                     
                     
                     </td-->
                      
                     <td align="center"><input type="text" id="last-name"  style="width:80px" name="rate" value="" class="form-control col-md-7 col-xs-12"></td>

<?php } else { ?>
                        

    <td align="center" colspan="3" style="color:#F00; font-weight:bold">Sold Out</td>                  
    <?php } ?>               
                      
                      
                        </tr>
                        <?php } ?>
                        
                        
                      
                      </tbody>
                    </table>  
                   
   <?php                 
 $presult=mysql_query("Select * from accounts_ledger where ledger='Sales' and companyid='$_SESSION[companyid]'");
$prow=mysql_fetch_array($presult);

$mcresult=mysql_query("Select * from accounts_ledger where ledger='Main Cash' and companyid='$_SESSION[companyid]'");
$mcrow=mysql_fetch_array($mcresult);
$targetamount='0';

if (isset($_POST['getstarted'])){
$valid = true;
	 


$flag=mysql_query("Select invoiceno from transaction_inventory where invoiceno='$invoice' and companyid='$_SESSION[companyid]'");
	if ( mysql_num_rows($flag)==0)
{echo "<script> alert('Opps!! Invaild Transaction!!') </script>";
        $valid = false;}


	 
	 
if ($valid){
				
		if ( $amounts>$targetamount){	
	$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$prow[accountreporttype]','$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Sales','$billno','$createdate','','','-$amounts','','$amounts','Main Cash, Sales To  $vendor','0','','','','$companyid','','$createby','$ip','$mac','$createdate','$ttime','Cash Sales','$invoice','Normal','$day')");		
 
 
 
 
 $result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$mcrow[accountreporttype]','$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','Main Cash','$billno','$createdate','','','$amounts','$amounts','','Sales to  $supplier','1','','','','$companyid','','$createby','$ip','$mac','$createdate','$ttime','Cash Sales','$invoice','Normal','$day')");
 
		}
	} ?>
	<meta http-equiv="refresh" content="0;sales_cash_barcode.php">
<?php 	}      ?>                
                  <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                       
                          <a href="purchase_order.php" type="cancel" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarted" class="btn btn-success">Cash Sales </button>
                          
                          
                        </div>
                      </div>  
                      
                      

                    </form>
                    <?php } ?>
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

    <!-- jQuery autocomplete -->
    <script>
      $(document).ready(function() {
        var countries = { AD:"Andorra",A2:"Andorra Test",AE:"United Arab Emirates",AF:"Afghanistan",AG:"Antigua and Barbuda",AI:"Anguilla",AL:"Albania",AM:"Armenia",AN:"Netherlands Antilles",AO:"Angola",AQ:"Antarctica",AR:"Argentina",AS:"American Samoa",AT:"Austria",AU:"Australia",AW:"Aruba",AX:"Åland Islands",AZ:"Azerbaijan",BA:"Bosnia and Herzegovina",BB:"Barbados",BD:"Bangladesh",BE:"Belgium",BF:"Burkina Faso",BG:"Bulgaria",BH:"Bahrain",BI:"Burundi",BJ:"Benin",BL:"Saint Barthélemy",BM:"Bermuda",BN:"Brunei",BO:"Bolivia",BQ:"British Antarctic Territory",BR:"Brazil",BS:"Bahamas",BT:"Bhutan",BV:"Bouvet Island",BW:"Botswana",BY:"Belarus",BZ:"Belize",CA:"Canada",CC:"Cocos [Keeling] Islands",CD:"Congo - Kinshasa",CF:"Central African Republic",CG:"Congo - Brazzaville",CH:"Switzerland",CI:"Côte d'Ivoire",CK:"Cook Islands",CL:"Chile",CM:"Cameroon",CN:"China",CO:"Colombia",CR:"Costa Rica",CS:"Serbia and Montenegro",CT:"Canton and Enderbury Islands",CU:"Cuba",CV:"Cape Verde",CX:"Christmas Island",CY:"Cyprus",CZ:"Czech Republic",DD:"East Germany",DE:"Germany",DJ:"Djibouti",DK:"Denmark",DM:"Dominica",DO:"Dominican Republic",DZ:"Algeria",EC:"Ecuador",EE:"Estonia",EG:"Egypt",EH:"Western Sahara",ER:"Eritrea",ES:"Spain",ET:"Ethiopia",FI:"Finland",FJ:"Fiji",FK:"Falkland Islands",FM:"Micronesia",FO:"Faroe Islands",FQ:"French Southern and Antarctic Territories",FR:"France",FX:"Metropolitan France",GA:"Gabon",GB:"United Kingdom",GD:"Grenada",GE:"Georgia",GF:"French Guiana",GG:"Guernsey",GH:"Ghana",GI:"Gibraltar",GL:"Greenland",GM:"Gambia",GN:"Guinea",GP:"Guadeloupe",GQ:"Equatorial Guinea",GR:"Greece",GS:"South Georgia and the South Sandwich Islands",GT:"Guatemala",GU:"Guam",GW:"Guinea-Bissau",GY:"Guyana",HK:"Hong Kong SAR China",HM:"Heard Island and McDonald Islands",HN:"Honduras",HR:"Croatia",HT:"Haiti",HU:"Hungary",ID:"Indonesia",IE:"Ireland",IL:"Israel",IM:"Isle of Man",IN:"India",IO:"British Indian Ocean Territory",IQ:"Iraq",IR:"Iran",IS:"Iceland",IT:"Italy",JE:"Jersey",JM:"Jamaica",JO:"Jordan",JP:"Japan",JT:"Johnston Island",KE:"Kenya",KG:"Kyrgyzstan",KH:"Cambodia",KI:"Kiribati",KM:"Comoros",KN:"Saint Kitts and Nevis",KP:"North Korea",KR:"South Korea",KW:"Kuwait",KY:"Cayman Islands",KZ:"Kazakhstan",LA:"Laos",LB:"Lebanon",LC:"Saint Lucia",LI:"Liechtenstein",LK:"Sri Lanka",LR:"Liberia",LS:"Lesotho",LT:"Lithuania",LU:"Luxembourg",LV:"Latvia",LY:"Libya",MA:"Morocco",MC:"Monaco",MD:"Moldova",ME:"Montenegro",MF:"Saint Martin",MG:"Madagascar",MH:"Marshall Islands",MI:"Midway Islands",MK:"Macedonia",ML:"Mali",MM:"Myanmar [Burma]",MN:"Mongolia",MO:"Macau SAR China",MP:"Northern Mariana Islands",MQ:"Martinique",MR:"Mauritania",MS:"Montserrat",MT:"Malta",MU:"Mauritius",MV:"Maldives",MW:"Malawi",MX:"Mexico",MY:"Malaysia",MZ:"Mozambique",NA:"Namibia",NC:"New Caledonia",NE:"Niger",NF:"Norfolk Island",NG:"Nigeria",NI:"Nicaragua",NL:"Netherlands",NO:"Norway",NP:"Nepal",NQ:"Dronning Maud Land",NR:"Nauru",NT:"Neutral Zone",NU:"Niue",NZ:"New Zealand",OM:"Oman",PA:"Panama",PC:"Pacific Islands Trust Territory",PE:"Peru",PF:"French Polynesia",PG:"Papua New Guinea",PH:"Philippines",PK:"Pakistan",PL:"Poland",PM:"Saint Pierre and Miquelon",PN:"Pitcairn Islands",PR:"Puerto Rico",PS:"Palestinian Territories",PT:"Portugal",PU:"U.S. Miscellaneous Pacific Islands",PW:"Palau",PY:"Paraguay",PZ:"Panama Canal Zone",QA:"Qatar",RE:"Réunion",RO:"Romania",RS:"Serbia",RU:"Russia",RW:"Rwanda",SA:"Saudi Arabia",SB:"Solomon Islands",SC:"Seychelles",SD:"Sudan",SE:"Sweden",SG:"Singapore",SH:"Saint Helena",SI:"Slovenia",SJ:"Svalbard and Jan Mayen",SK:"Slovakia",SL:"Sierra Leone",SM:"San Marino",SN:"Senegal",SO:"Somalia",SR:"Suriname",ST:"São Tomé and Príncipe",SU:"Union of Soviet Socialist Republics",SV:"El Salvador",SY:"Syria",SZ:"Swaziland",TC:"Turks and Caicos Islands",TD:"Chad",TF:"French Southern Territories",TG:"Togo",TH:"Thailand",TJ:"Tajikistan",TK:"Tokelau",TL:"Timor-Leste",TM:"Turkmenistan",TN:"Tunisia",TO:"Tonga",TR:"Turkey",TT:"Trinidad and Tobago",TV:"Tuvalu",TW:"Taiwan",TZ:"Tanzania",UA:"Ukraine",UG:"Uganda",UM:"U.S. Minor Outlying Islands",US:"United States",UY:"Uruguay",UZ:"Uzbekistan",VA:"Vatican City",VC:"Saint Vincent and the Grenadines",VD:"North Vietnam",VE:"Venezuela",VG:"British Virgin Islands",VI:"U.S. Virgin Islands",VN:"Vietnam",VU:"Vanuatu",WF:"Wallis and Futuna",WK:"Wake Island",WS:"Samoa",YD:"People's Democratic Republic of Yemen",YE:"Yemen",YT:"Mayotte",ZA:"South Africa",ZM:"Zambia",ZW:"Zimbabwe",ZZ:"Unknown or Invalid Region" };

        var countriesArray = $.map(countries, function(value, key) {
          return {
            value: value,
            data: key
          };
        });

        // initialize autocomplete with custom appendTo
        $('#autocomplete-custom-append').autocomplete({
          lookup: countriesArray
        });
      });
    </script>
    <!-- /jQuery autocomplete -->

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
    </script>
    <!-- /Starrr -->
  </body>
</html>
