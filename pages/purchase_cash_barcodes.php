<?php

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
 $res=mysql_query("SELECT * FROM users WHERE companyid=".$_SESSION['companyid']);
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

    <title><?php echo $_SESSION[company]; ?> | Cash Purchase with Barcode</title>

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
    
    
 <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.inventory_types.options[form.inventory_types.options.selectedIndex].value;
	self.location='purchase_cash_barcode.php?invoicecode=<?php echo $_GET[invoicecode]; ?>&inventorytype=' + val ;
}





function reload2(form)
{
	var val=form.inventory_types.options[form.inventory_types.options.selectedIndex].value;
	var val2=form.category.options[form.category.options.selectedIndex].value;
	self.location='purchase_cash_barcode.php?invoicecode=<?php echo $_GET[invoicecode]; ?>&inventorytype=' + val +'&categoryid=' + val2 ;
}


function reload3(form)
{
	
	var val3=form.brand.options[form.brand.options.selectedIndex].value;
	self.location='purchase_cash_barcode.php?invoicecode=<?php echo $_GET[invoicecode]; ?>&brandid=' + val3 ;
}

function reload4(form)
{
	
	var val3=form.brand.options[form.brand.options.selectedIndex].value;
	var val4=form.model.options[form.model.options.selectedIndex].value;
	self.location='purchase_cash_barcode.php?invoicecode=<?php echo $_GET[invoicecode]; ?>&brandid=' + val3 +'&modelid=' + val4;
}

</script>

<script type="text/javascript">

	function calculateTotal() {
		
		var totalAmt = document.addem.total.value;
		totalR = eval(totalAmt - (document.addem.tb1.value));
		
		document.getElementById('dueamounts').value = totalR;
		
		//document.getElementById('subt').value = result1;
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
            <div class="page-title">
               
             </div>

            <div class="clearfix"></div>

            <div class="row">
              

              


 <?php 

				
	$getstarted=$_POST[getstarted];
	$getstarteds=$_POST[getstarteds];
	$companyid=$_SESSION['companyid'];
	$invoice=$_POST[invoice];
	$billno=$_POST[billno];
	$supplier=$_POST[supplier];
	$warehouse=$_POST[warehouse];
	$Note=$_POST[Note];
	$category=$_POST[category];
	$rate=$_POST[rate];
	$qty=$_POST[qty];
	$productcode=$_POST[productcode];
    $createby=$_SESSION['login_email'];	
	$createdate=$_POST[purchasedate];
	
	$resultsss=mysql_query("Select * from transaction_inventorys where companyid='$_SESSION[companyid]' and cus_invoice='$_GET[invoicecode]'");
    $invrow=mysql_fetch_array($resultsss);


 ?>

     


           
                  
                  
                  
                  
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
                  <div class="x_title">
                    <h2>Cash Purchase with Barcode</h2>
                    <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="purchase_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Purchase Report</span>
								</a>
                                
                                <a class="btn btn-sm btn-default"  href="purchase_pending_list.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Pending List</span>
								</a>
                    			
                    			               
                    			
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
               <div class="x_content">
                  
                    <br />
                    
                    
           
                   
                
                       
                        
                        
                      
                    
                   
   <?php                 



?>                    
                    
                    
                    
                    <form id="demo-form2" method="post" action="purchase_cash_barcode.php?inventorytype=<?php echo $_GET[inventorytype]; ?>&categoryid=<?php echo $_GET[categoryid]; ?>&brandid=<?php echo $_GET[brandid]; ?>&modelid=<?php echo $_GET[modelid]; ?>&invoicecode=<?php echo $_SESSION[ponos]; ?>" data-parsley-validate class="form-horizontal form-label-left">
                    
                   
                 <?php 
				
				
				
				 $catresult=mysql_query("Select * from inventory_product where categoryid='$_GET[categoryid]' and                  companyid='$_SESSION[companyid]'");
                 $catrow=mysql_fetch_array($catresult);
                 $category=$catrow[category];


                 $brandresult=mysql_query("Select * from inventory_product where brandid='$_GET[brandid]' and                  companyid='$_SESSION[companyid]'");
                 $brarow=mysql_fetch_array($brandresult);
                 $brand=$brarow[brand];



                 $modelresult=mysql_query("Select * from inventory_product where modelid='$_GET[modelid]' and                  companyid='$_SESSION[companyid]'");
                 $modelrow=mysql_fetch_array($modelresult);
				 $model=$modelrow[model];
				 
				 
				 $warranty=$_POST[warranty];
				 
				 
				$unit=$row[unit];
				$inventory_types=$row[inventory_types];
				$productcode=$_POST[productcode];
				$product=$_POST[product];
				$rate=$_POST[rate];
				$amounts=$rate*$qty;
				
	            
	
	
	
	
if (isset($_POST['getstarted'])){
$valid = true;
	 



	 
	 
if ($valid){
	if($rate>0){
 $result=mysql_query("INSERT INTO `transaction_inventorys` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,purchaseclint,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,cus_invoice,warranty) VALUES 
 ('$_GET[inventorytype]','$_GET[categoryid]','$category','$_GET[brandid]','$_GET[brandid]','$_GET[modelid]','$_GET[modelid]','$productcode','$product','$unit','$invoice','$billno','$Jvoucherno','$supplier','$qty','$rate','$amounts','$coss','$amtcoss','$adjustlevel','$adjustamount','$lifofifoid','$note','$warehouse','$acchead','Cash Expenses','$companyid','$createby','Cash Purchase','$ttime','Normal','$createdate','$tmodifiddate','$modifiedby','$ip','$mac','Purchase','$_SESSION[ponos]','$warranty')");
 
 
 purchaseinvoiceno();
  ?>
	
<?php }}} ?>
                
                     
                      
                    
                    
                      
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Inventory Brand<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
     <input type="text" id="first-name" readonly required="required"  value="<?php echo $row[brand] ?>" name="brand" class="form-control col-md-7 col-xs-12"><?php } else { ?>
                        
    <select id="first-name" required="required" onchange="javascript:reload3(this.form)"  name="brand" class="form-control col-md-7 col-xs-12">
     
                <option value="">Choose......</option>
                 <?php 
				$result=mysql_query("Select * from inventory_brand where companyid='$_SESSION[companyid]' order by brand");
				while($row=mysql_fetch_array($result)){
				if(($_GET[brandid])==$row[brand]){ ?>
                        
                <option selected value="<?php echo $row[brand]; ?>"><?php echo $row[brand]; ?></option>
                <?php } else { ?>
                <option value="<?php echo $row[brand]; ?>"><?php echo $row[brand]; ?></option>
                <?php }} ?>
                </select><?php } ?>
                        </div>
                      </div>  
                      
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Inventory Model<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        
    
    
    <select id="first-name" required="required" onchange="javascript:reload4(this.form)"  name="model" class="form-control col-md-7 col-xs-12">
    <option value="">Choose......</option>
                 <?php 
				$result=mysql_query("Select * from inventory_model where  companyid='$_SESSION[companyid]' order by model");
				while($row=mysql_fetch_array($result)){
				if(($_GET[modelid])==$row[model]){ ?>
                <option selected value="<?php echo $row[model]; ?>"><?php echo $row[model]; ?></option>
                <?php } else { ?>
                <option value="<?php echo $row[model]; ?>"><?php echo $row[model]; ?></option>
                <?php }} ?>
                </select>
                        </div>
                      </div>  
                      
                      
                      
                      
                      
                      
                      <?php if ($_GET[productname]) { ?>
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <?php if($_GET[invoicecode]){ ?>
               <input type="hidden" id="last-name"   required="required" name="billno" value="<?php echo $invrow['productname']; ?>" class="form-control col-md-7 col-xs-12">
               <?php } else { ?>
    
                        <select id="first-name" required="required"   name="product" class="form-control col-md-7 col-xs-12">
                        <option value="<?php echo $_GET[productname]; ?>"><?php echo $_GET[productname]; ?></option>
                        </select>
                     <?php } ?>
                      </div>
                      </div>
                      
                      
                  <?php } else { ?>    
                  
                  
                  
                  
                  
                  
                  
                  
                  
                   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
    
                        <select id="first-name" required="required"   name="product" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose......</option>
                 <?php 
				$result=mysql_query("Select distinct product from inventory_product where companyid='$_SESSION[companyid]' order by product");
				while($row=mysql_fetch_array($result)){
				 ?>
                        <option value="<?php echo $row[product]; ?>"><?php echo $row[product]; ?></option>
                         <?php } ?>
                        </select>
                     
                      </div>
                      </div>
                  
                  <?php } ?>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                      
                      
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Barcode Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
        <input type="text" id="last-name"  required="required" name="productcode" value="<?php  echo $_GET[productcode];?>" class="form-control col-md-7 col-xs-12">            
                          
                        </div>
                      </div>
                      
                     
                     
                     
                      <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Qty <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12"-->
                          <input type="hidden" id="last-name"  required="required" name="qty" value="1" class="form-control col-md-7 col-xs-12">
                        <!--/div>
                      </div--->  
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Rate <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="rate" value="<?php echo $row[rate] ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                     
                  
                  
                  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Purchase Warranty <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="warranty"  class="form-control col-md-7 col-xs-12">
                          <option value="">Choose ......</option>
                     <option value="No Warranty">No Warranty</option>
                     <option value="1 Week">1 Week</option>
                     <option value="1 Month">1 Month</option>
                     <option value="3 Month">3 Months</option>
                     <option value="6 Month">6 Months</option>
                     <option value="1 Year">1 Year</option>
                     <option value="2 Year">2 Year</option>
                     <option value="3 Year">3 Year</option>
                     
                     </select> 
                        </div>
                      </div>
                  
                  
                 
                    
                  
                    
                    
                    
                    
                    
                   
                   
                 
                   
                   
                   <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                   
                   
                   
               <div class="form-group">
                   
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Purchase Invoice<span class="required">*</span></label>
               <div class="col-md-6 col-sm-6 col-xs-12">
               
                <?php if($_GET[invoicecode]){ ?>
               <input type="hidden" id="last-name"   required="required" name="billno" value="<?php echo $invrow['voucherno']; ?>" class="form-control col-md-7 col-xs-12">
               <?php } else { ?>
               <input type="hidden" id="last-name"   required="required" name="billno" value="<?php echo $_SESSION['ceshexpenses']; ?>" class="form-control col-md-7 col-xs-12">
               <?php }  ?>
               
               
               
               
               
               
               
               
               <?php if($_GET[invoicecode]){ ?>
               <input type="text" id="last-name"   required="required" name="invoice" value="<?php echo $invrow['invoiceno']; ?>" class="form-control col-md-7 col-xs-12" readonly>
               <?php } else { ?>
               <input type="text" id="last-name"   required="required" name="invoice" value="<?php echo $_SESSION['ponos']; ?>" class="form-control col-md-7 col-xs-12">
               
               
               <?php }  ?>
               </div> </div>
                   
                   
                   
                   
                   <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Purchase Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                
                    <?php if($_GET[invoicecode]){ ?>	
	            
                <input type="text" id="last-name"  required="required" name="purchasedate" value="<?php echo $invrow[tdate]; ?>" class="form-control col-md-7 col-xs-12" readonly>
                <?php } else { ?>
                <input type="text" id="last-name"  required="required" name="purchasedate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">
                <?php }  ?>
                </div></div>
                
                
                      
                     
                    
              
                    
                    
                    
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Purchase From<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[invoicecode]){ ?>
                        <input type="text" id="first-name" required="required" value="<?php echo $invrow[purchaseclint] ?>"  name="supplier" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required"   name="supplier" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        
                        <?php $result=mysql_query("Select * from procurement_supplier where companyid='$_SESSION[companyid]'");
						while($rowmaingroup=mysql_fetch_array($result)){
						?> 
                                         
                 <option value="<?php echo $rowmaingroup[ledgercode]; ?>"><?php echo $rowmaingroup[sname]; ?></option>
                      
                    <?php } ?></select><?php } ?></div></div> 
                    
                    
                    
                    <input type="hidden" id="first-name" required="required" value="Main House" name="warehouse" class="form-control col-md-7 col-xs-12" readonly>
                    
                    
                    
                    
                    <!---div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Destination Warehouse<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[invoicecode]){ ?>
                        <input type="text" id="first-name" required="required" value="<?php echo $invrow[warehouse] ?>" name="warehouse" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required"   name="warehouse" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        
                        <?php $result=mysql_query("Select * from warehouse where companyid='$_SESSION[companyid]'");
						while($rowmaingroup=mysql_fetch_array($result)){
						?> 
                                         
                 <option value="<?php echo $rowmaingroup[warehousename]; ?>"><?php echo $rowmaingroup[warehousename]; ?></option>
                      
                    <?php } ?></select><?php } ?></div></div--->
                    
                    
                   
                   
                      
                      
                   
                        
                  <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                       
                          <a href="purchase_order.php" type="cancel" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarted" class="btn btn-success">Add </button>
                          
                          
                          
                          <a href="purchase_cash_barcode.php?inventorytype=&categoryid=&brandid=<?php echo $_GET[brandid]; ?>&modelid=<?php echo $_GET[modelid]; ?>&invoicecode=<?php echo $_GET[invoicecode]; ?>" type="cancel" >Refresh</a>
                        </div>
                      </div>  
                      
                      

                    </form>
                  </div>
                </div>
              </div>
            </div>

          

               

                
                   
 



              

              

    <?php
	
	if($_GET[invoicecode]){
	 ?>          
              
   <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                  <div class="x_title">

                    <h2>Today's Service List</h2>

                    <div class="clearfix"></div>

                  </div>

                  <div class="x_content">

                    
<form id="addem" name="addem"  method="post"  data-parsley-validate class="form-horizontal form-label-left">
                     <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                        <th>SL</th>
                          <th style="width:5%">Code</th>
                          <th>Product</th>
                          
                          
                          <th>Category</th>
                          <th>Warranty</th>
                          
                          
                          
                          <th style="width:10%">Qty</th>
                          <th style="width:10%">Rate</th>
                         <th style="width:10%">Amount</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php
$resultssss=mysql_query("Select * from transaction_inventorys where companyid='$_SESSION[companyid]' and cus_invoice='$_GET[invoicecode]'");
$invrows=mysql_fetch_array($resultssss);						
						
$presult=mysql_query("Select * from accounts_ledger where ledger='Purchase' and companyid='$_SESSION[companyid]'");
$prow=mysql_fetch_array($presult);

$mcresult=mysql_query("Select * from accounts_ledger where ledger='Main Cash' and companyid='$_SESSION[companyid]'");
$mcrow=mysql_fetch_array($mcresult);


$supresult=mysql_query("Select * from accounts_ledger where ledgercode='$invrows[purchaseclint]' and companyid='$_SESSION[companyid]'");
$suprow=mysql_fetch_array($supresult);





	
							
						
$amountssss=$_POST[tb1];
$billno=$_POST[billno];

$dueamounts=$_POST[dueamounts];
if (isset($_POST['getstarteds'])){
$valid = true;
	 


 if($dueamounts>0){
	
	
$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$prow[accountreporttype]','$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Purchase','$invrows[voucherno]','$invrows[tdate]','','','$dueamounts','$dueamounts','','$suprow[ledger], Purchase from  $suprow[ledger], Due Amount','1','','','','$companyid','','$createby','$ip','$mac','$invrows[tdate]','$ttime','Cash Purchase','$invrow[invoiceno]','Normal','$day')");		
 
 
 
 
 $result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$mcrow[accountreporttype]','$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$suprow[ledgercode]','$suprow[ledger]','$invrows[voucherno]','$invrows[tdate]','','','-$dueamounts','','$dueamounts','Purchase from  $suprow[ledger], Due Amount','0','','','','$companyid','','$createby','$ip','$mac','$invrows[tdate]','$ttime','Cash Purchase','$invrow[invoiceno]','Normal','$day')");	
	 
	 
}
	 
	 
if ($valid){
				
			
	$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$prow[accountreporttype]','$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Purchase','$invrows[voucherno]','$invrows[tdate]','','','$amountssss','$amountssss','','Main Cash, Purchase from  $suprow[ledger]','1','','','','$companyid','','$createby','$ip','$mac','$invrows[tdate]','$ttime','Cash Purchase','$invrow[invoiceno]','Normal','$day')");		
 
 
 
 
 $result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$mcrow[accountreporttype]','$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','Main Cash','$invrows[voucherno]','$invrows[tdate]','','','-$amountssss','','$amountssss','Purchase from  $suprow[ledger]','0','','','','$companyid','','$createby','$ip','$mac','$invrows[tdate]','$ttime','Cash Purchase','$invrow[invoiceno]','Normal','$day')");
ceshexpensesid();}}					
						
						
						
						
						
						 
				$results=mysql_query("Select * from transaction_inventorys where  companyid='$_SESSION[companyid]' and cus_invoice='$_GET[invoicecode]'");
				while($row=mysql_fetch_array($results)){ 
				$i=$i+1;
				
				
				
				
				
				
if (isset($_POST['getstarteds'])){
$valid = true;
	 

	 
	 
if ($valid){
 
 
 $result=mysql_query("INSERT INTO `transaction_inventory` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,purchaseclint,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,warranty) VALUES 
 ('$row[inventory_types]','$row[categoryid]','$row[categorys]','$row[brandid]','$row[brand]','$row[modelid]','$row[model]','$row[productcode]','$row[product]','$row[unit]','$row[invoiceno]','$row[voucherno]','$row[Jvoucherno]','$row[purchaseclint]','$row[qty]','$row[rate]','$row[amount]','$row[coss]','$row[amtcoss]','$row[adjustlevel]','$row[adjustamount]','$row[lifofifoid]','$row[note]','$row[warehouse]','$row[acchead]','$row[btype]','$row[companyid]','$row[transactionby]','$row[transactiontype]','$row[ttime]','$row[tstatus]','$row[tdate]','$row[tmodifiddate]','$row[modifiedby]','$row[ip]','$row[mac]','$row[tfor]','$row[warranty]')");
 purchaseinvoiceno();
 
$delete=mysql_query("Delete from transaction_inventorys where cus_invoice='$_GET[invoicecode]' and companyid='$_SESSION[companyid]'");
  
 
  ?>
	
<meta http-equiv="refresh" content="0;purchase_cash_barcode.php">
<?php }} ?>
                      <tr>
                        <td style="width:3%"><?php echo $i; ?></td>
                        <td style="width:10%"><?php echo $row[productcode]; ?></td>
                        <td><?php echo $row[product]; ?></td>
                        
                        <td style="width:12%"><?php echo $row[categorys]; ?></td>
                        <td style="width:12%"><?php echo $row[warranty]; ?></td>
                        
                        
                        
                        
                        
                     <td align="center" style="width:8%">
                     
                     <input type="hidden" id="last-name" style="width:120px" name="itemcode<?php echo $ids; ?>" value="<?php echo $row[itemcode]; ?>" class="form-control col-md-7 col-xs-12">
                     
                     
                     <input type="text" id="last-name" style="width:80px; text-align:center" name="qtys<?php echo $ids; ?>" value="<?php echo $row[qty] ?>" class="form-control col-md-7 col-xs-12">
                     
                     
                     </td>
                      
                     <td align="center" style="width:8%"><input type="text" id="last-name"  style="width:80px; text-align:center" name="rate<?php echo $ids; ?>" value="<?php echo $row[rate] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    </td>
                    
                    
                    <td align="center" style="width:8%"><input type="text" id="last-name"  style="width:80px; text-align:center" name="amount<?php echo $ids; ?>" value="<?php echo $row[amount] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    </td>
                      
                      
                        </tr>
                        
                        
 <?php 
 $qtysss=getSVALUE("transaction_inventorys", "Sum(qty) as qty", " where cus_invoice='$_GET[invoicecode]' and companyid='$_SESSION[companyid]'");
 $ratesss=getSVALUE("transaction_inventorys", "Sum(rate) as rate", " where cus_invoice='$_GET[invoicecode]' and companyid='$_SESSION[companyid]'");
 $amountsss=getSVALUE("transaction_inventorys", "Sum(amount) as amount", " where cus_invoice='$_GET[invoicecode]' and companyid='$_SESSION[companyid]'");
 ?>                       
                        <?php } ?>
                        
                        
                      
                      </tbody>
                      
                      
                    <tr style="font-size:14px; font-weight:bold">
                    <td colspan="5" align="right">Total</td>
                    <td align="center"><?php echo $qtysss; ?></td>
                    <td align="center"><?php echo $ratesss; ?></td>
                    <td align="center"><input type="text" id="totalAmounts" name="amountssss" value="<?php echo $amountsss; ?>" style="width:80px; text-align:center; border:none; background-color:transparent"  class="form-control col-md-7 col-xs-12"></td>
                    </tr>  
                      
                      
                    <tr style="font-size:14px; font-weight:bold">
                    <td colspan="7" align="right">Payment</td>
                    <td align="center"><input type="text" id='tb1' name="tb1" autocomplete="off"  onkeyup="calculateTotal()"  style="width:80px; text-align:center;" class="form-control col-md-7 col-xs-12" required="required" ></td>
                    </tr>     
                      
                      
                      
                  
                    <tr style="font-size:14px; font-weight:bold">
                    <td colspan="7" align="right">Due Amount</td>
                    <td align="center">
                    
                    <!--input type="text" name="due" value="" style="width:80px; text-align:center;" class="form-control col-md-7 col-xs-12"--->
                    
                    
                    
                     <input type="text" name="dueamounts" id="dueamounts" class="form-control col-md-7 col-xs-12" style="text-align:center;width:80px; vertical-align:middle; height:35px;"   readonly >
                      
                   
                   <input type='hidden' id='total' value="<?php echo $amountsss; ?>" name="ttts" class="form-control col-md-7 col-xs-12" style="text-align:center;width:80px; height:35px;" disabled / >   </td>
                    </tr>     
                      
                  
                  
                  
                      
                      
                      
                      <tr>
                      <td colspan="8" style="text-align:center">
                     
                        
                       
                          <a href="purchase_order.php" type="cancel" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarteds" class="btn btn-success">Finish Purchase </button>
                          
                          
                       
                      </td></tr> 
                    </table>  
                   
</form>
                  </div>

                </div>

              </div>
            
               
              <?php } ?>
              
              
              
              
              
              
              
              
              
              
              
              
              
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
    //this calculates values automatically 
    sum();
    $("#num1, #num2").on("keydown keyup", function() {
        sum();
    });
});

function sum() {
            var num1 = document.getElementById('num1').value;
            var num2 = document.getElementById('num2').value;
			var result = parseInt(num1) + parseInt(num2);
			var result1 = parseInt(num2) - parseInt(num1);
            if (!isNaN(result)) {
                document.getElementById('sum').value = result;
				document.getElementById('subt').value = result1;
            }
        }
	
	
	
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
</html>