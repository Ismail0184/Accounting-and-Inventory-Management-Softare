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

    <title><?php echo $_SESSION[company]; ?> | Cash Sales</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
 
 
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 
 
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>




<script>
$(function() {
    $( "#vendor" ).autocomplete({
        source: 'searchs.php'
    });
});
</script> 
 
 
 <script type="text/javascript">

	function calculateTotal() {
		
		var totalAmt = document.addem.total.value;
		totalR = eval(totalAmt - (document.addem.tb1.value)-(document.addem.tb2.value));
		
		document.getElementById('update').innerHTML = totalR;
	}

</script>
 
    
<style>
/* unvisited link */
p:link {
    color: red;
}

/* visited link */
p:visited {
    color: green;
}

/* mouse over link */
p:hover {
    color: hotpink;
}

/* selected link */
p:active {
    color: blue;
}
</style>
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
            <div class="page-title">
               
             </div>

            <div class="clearfix"></div>

            <div class="row">
              

              


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
	$createdate=$_POST[salesdate];
	$vendor=$_POST[vendor];
	$vendorphone=$_POST[vendorphone];
	$vendoraddress=$_POST[vendoraddress];
	$discount=$_POST[tb1];
	            $advance=$_POST[tb2];
	            $due=$_POST[due];



 ?>

     


           
                  
                  

                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
                  <div class="x_title">
                    <h2>Cash Sales</h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="sales_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Sales Report</span>
								</a>




                                <a class="btn btn-sm btn-default" style="color:#000" href="sales_cash_edit.php?invoiceidedit=<?php echo $_GET[invoiceprint];?>">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Correction</span>
                    			</a>


                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="sales_challan_view.php?challanviewid=<?php echo $_GET[invoiceprint]; ?>">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Print</span>
                    			</a>
                                
                            
                                
		 						
								      
                    			
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
               <div class="x_content">
                  
                    <br />
                    <form id="addem" name="addem" action="sales_cash.php?invoiceidno=<?php echo $_SESSION['csalesno']; ?>" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    
                   
                
                <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sales Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="last-name"  required="required" name="salesdate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">

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
                          <input type="text" id="last-name"   required="required" name="billno" value="<?php echo $_SESSION['crid']; ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="vendor" required="required" value="<?php echo $rowsmaingroup[vendor] ?>" name="vendor" class="form-control col-md-7 col-xs-12" ></div></div>
                        
                        
                        
                        
                        
                        
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Contact Number<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name"  value="<?php echo $rowsmaingroup[vendorphone] ?>" name="vendorphone" class="form-control col-md-7 col-xs-12" ></div></div>
                        
                        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Address<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name"  value="<?php echo $rowsmaingroup[vendoraddress] ?>" name="vendoraddress" class="form-control col-md-7 col-xs-12" ></div></div>
                    
                    
                    
                    
                    
                    <!--div class="form-group">
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
                      
                    <?php } ?></select><?php } ?></div></div-->
                      
                      
                      
                    
                   
                   
                   <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                   
                   
                   
                  <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width:5%">Barcode</th>
                          <th>Product Details</th>
                          
                          
                          
                          <th style="width:8%">Model</th>
                          
                          
                          <th style="width:7%">Stock<br>Balance</th>
                          <th style="width:5%">P.Rate</th>
                          <th style="width:8%">Qty</th>
                          <th style="width:8%">Rate</th>
                          <th style="width:8%">Amount</th>
                         <th style="width:10%">Warranty</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php 
				
					
					
				$productresult=mysql_query("SELECT distinct productcode FROM transaction_inventory where  companyid='$_SESSION[companyid]' and tfor!='Sales'");
					 while($productrow=mysql_fetch_array($productresult)){
					$i=$i+1;	 
				
				
	            
			  
						 
				$results=mysql_query("Select * from transaction_inventory where productcode='$productrow[productcode]' and  companyid='$_SESSION[companyid]' and tfor!='Sales'  order by productcode");
				$row=mysql_fetch_array($results);
					
					
					$stockbalance=getSVALUE("transaction_inventory", "SUM(qty) as qty", "where productcode='$productrow[productcode]'   and companyid='$_SESSION[companyid]' order by productcode");
					
					
					$pratesss=getSVALUE("transaction_inventory", "MAX( rate ) AS max", "where productcode='$productrow[productcode]'   and companyid='$_SESSION[companyid]' and tfor!='Sales' order by productcode");	
					
				$ids=$productrow[productcode];
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
				$qtys=$_POST[qtys.$ids];
				$rate=$_POST[rate.$ids];
				$amount=$qtys*$rate;
				$amounts=$amounts+$amount;
				$warranty=$_POST[warranty.$ids];
	            
	
	
	
	
if (isset($_POST['getstarted'])){
$valid = true;
	 


if ( $qtys>$sbalance)

    {echo "<script> alert('This $row[product] is not available more then $sbalance qty!!') </script>";
        $valid = false;}	

	 
	 
if ($valid){
	if($qtys and $rate>0){
 $result=mysql_query("INSERT INTO `transaction_inventory` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,purchaseclint,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,vendor,vendorphone,vendoraddress,discount,advance,warranty) VALUES 
 ('$inventory_types','$categoryid','$category','$brandid','$brand','$modelid','$model','$ids','$product','$unit','$invoice','$billno','$Jvoucherno','$supplier','-$qtys','$rate','$amount','$coss','$amtcoss','$adjustlevel','$adjustamount','$lifofifoid','$note','$warehouse','$acchead','Cash Received','$companyid','$createby','Cash Sales','$ttime','Normal','$createdate','$tmodifiddate','$modifiedby','$ip','$mac','Sales','$vendor','$vendorphone','$vendoraddress','$discount','$advance','$warranty')");
 cashsalesno();
  ?>
	
<?php }}}
				
				
				









				
				 ?>
                 
                 
                 <?php
				 
				 if ($stockbalance>0) { ?>
				  
                      <tr>
                        
                        <td>
                        
                        
                        <?php echo $row[productcode]; ?>
                        
                        </td>
                        
                        
                        
                        
                        <td><?php echo $row[product]; ?></td>
                        
                        
                        <td><?php echo $row[model]; ?></td>
                        
                        
                        

                        
                        
 <td align="center"><input type="text"  readonly id="last-name"  style="width:60px; height:30px; color:#F00; font-weight:bold; text-align:center" name="sbalance<?php echo $ids; ?>" value="<?php if($stockbalance>0)  { echo $stockbalance; } else { echo 0;} ?>" class="form-control col-md-7 col-xs-12"></td>
                        
                     <?php
					 
					 $pr=$pratesss;
					 $prs=number_format($pr,2);
					  ?>
                    
                     <td align="right" style="font-weight:bold; color:transparent"><p><?php echo $prs ?></p></td>
                     
                        
                     <td align="center">
                     
                     <input type="hidden" id="last-name" style="width:120px; height:30px" name="itemcode<?php echo $ids; ?>" value="<?php echo $row[itemcode]; ?>" class="form-control col-md-7 col-xs-12">
                     
                     <input type="text" id="qtys" style="width:70px; height:30px; font-size:13px; text-align:center" autocomplete="off"  class='price' name="qtys<?php echo $ids; ?>" tabindex="1" >
                     
                     
                     </td>
                      
                     <td align="center"><input type="text" id="rate" autocomplete="off"   name="rate<?php echo $ids; ?>" style="width:70px; height:30px; font-size:13px; text-align:center" class='rate' tabindex="1" ></td>
                      
                    <td align="center"><input type="text"  id='amounta' autocomplete="off"    name="amount<?php echo $ids; ?>" style="width:70px; height:30px; font-size:13px; text-align:center" class='amount' tabindex="1" ></td>
                      
                      
                      <td align="center">
                     <select name="warranty<?php echo $ids; ?>" style="height:30px; width:100px" >
                     <option value="No Warranty">No Warranty</option>
                     <option value="1 Year">1 Week</option>
                     <option value="1 Year">1 Month</option>
                     <option value="1 Year">3 Months</option>
                     <option value="1 Year">6 Months</option>
                     <option value="1 Year">1 Year</option>
                     <option value="2 Year">2 Year</option>
                     <option value="3 Year">3 Year</option>
                     <option value="Life Time">Life Time</option>
                     
                     </select> 
                      </td>
                        </tr>
                        <?php } }?>
                        
                        
                     
                      
                      
                      </tbody>
                      
                       <tr>
                      
                      
                      <td colspan="5" style="font-weight:bold" align="right">Total</td>
                      <td align="center" ><input  type='text' id='totalPrice' class="form-control col-md-7 col-xs-12" style="text-align:center;width:100px; height:35px;" disabled / ></td>
                      
                      <td align="center"><input  type='text' id='totalRate' class="form-control col-md-7 col-xs-12" style="text-align:center; width:100px; height:35px;" disabled /></td>
                      
                      <td align="center"><input  type='text' id='totalAmount' class="form-control col-md-7 col-xs-12" style="text-align:center; width:100px; height:35px;" disabled /></td>
                      <td></td>
                      </tr>
                      
                       <tr><td colspan="5" style="font-weight:bold" align="right">Total Amount</td>
                      <td colspan="3"><input type='text' id='totalAmounts' class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;" disabled / ></td><td></td></tr>
                      
                      
                      <tr><td colspan="5" style="font-weight:bold" align="right">Discount</td>
                      <td colspan="3"><input type='text' id='tb1' name="tb1" autocomplete="off"  onkeyup="calculateTotal()" class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;"  ></td><td></td></tr>
                      
                      
                      <tr><td colspan="5" style="font-weight:bold" align="right">Advamce Payment</td>
                      <td colspan="3"><input type='text' id='tb2' name="tb2" autocomplete="off" onkeyup="calculateTotal()" class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;"  ></td><td></td></tr>
                      
                      <tr><td colspan="5" style="font-weight:bold" align="right">Total Due</td>
                      <td colspan="3">
                      
                      
                      
                      
                      
                      <button type='text'  class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; vertical-align:middle; height:35px;" value="200" disabled ><span id="update"></span></button>
                      
                   <!--input type="hidden" name="total" value="100" /--->
                   
                   <input type='hidden' id='total' class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;" disabled / >   
                      
                      </td><td></td></tr>
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
 ('$mcrow[accountreporttype]','$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','Main Cash','$billno','$createdate','','','$amounts','$amounts','','Sales to  $vendor','1','','','','$companyid','','$createby','$ip','$mac','$createdate','$ttime','Cash Sales','$invoice','Normal','$day')");
 
		}
	} ?>
	<meta http-equiv="refresh" content="0;sales_challan_view.php?challanviewid=<?php echo $_GET[invoiceidno]; ?>">
<?php 	}      ?>                
                  <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                       
                          <a href="sales_cash.php" type="cancel" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarted" class="btn btn-success">Sales Submit </button>
                          
                          
                        </div>
                      </div>  
                      
                     

                    </form>
                  </div>
                </div>
              </div>
            </div>

          

               

                
                    <form id="demo-form" data-parsley-validate></form>
 



              

              

              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
            </div>
          </div>
        </div>
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




<script>


// we used jQuery 'keyup' to trigger the computation as the user type
$('.rate').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.rate').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#totalRate').val(sum);
	
});





// we used jQuery 'keyup' to trigger the computation as the user type
$('.price').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.price').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#totalPrice').val(sum);
	
});



// we used jQuery 'keyup' to trigger the computation as the user type
$('.amount').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.amount').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#totalAmount').val(sum);
	
});




// we used jQuery 'keyup' to trigger the computation as the user type
$('.amount').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.amount').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#amounts').val(sum);
	
});



// we used jQuery 'keyup' to trigger the computation as the user type
$('.amount').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.amount').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#totalAmounts').val(sum);
	
});



// we used jQuery 'keyup' to trigger the computation as the user type
$('.amount').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.amount').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#total').val(sum);
	
});








// we used jQuery 'keyup' to trigger the computation as the user type
$('.amount').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.amount').each(function() {
        sum += Number($(this).val());
    });
	
	
	
	
	// set the computed value to 'totalPrice' textbox
	//$('#amounta').val(sum);
	
})



</script> 
    <!-- jQuery -->
    <!--script src="../vendors/jquery/dist/jquery.min.js"></script-->
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
    
    
    <!--script>
    $('#rate').keyup(function(){
        var qtys;
        var rate;
        qtys = parseFloat($('#qtys').val());
        rate = parseFloat($('#rate').val());
		
        var amounta = qtys * rate;
        $('#amounta').val(amounta.toFixed(2));


    });
</script-->
    <!-- /Datatables -->
  </body>
</html>
