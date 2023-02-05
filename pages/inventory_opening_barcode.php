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

    <title><?php echo $_SESSION[company]; ?> | Invendory Opening Barcode</title>

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
	var val=form.product.options[form.product.options.selectedIndex].value;
	self.location='inventory_opening_barcode.php?invoicecode=<?php echo $_GET[invoicecode]; ?>&brandid=<?php echo $_GET[brandid]; ?>&modelid=<?php echo $_GET[modelid]; ?>&product=' + val ;
}



function reload2(form)
{
	var val=form.product.options[form.product.options.selectedIndex].value;
	var val3=form.brand.options[form.brand.options.selectedIndex].value;
	self.location='inventory_opening_barcode.php?invoicecode=<?php echo $_GET[invoicecode]; ?>&product=' + val + '&brandid=' + val3 ;
}



function reload3(form)
{
	var val=form.product.options[form.product.options.selectedIndex].value;
	var val3=form.brand.options[form.brand.options.selectedIndex].value;
	var val4=form.model.options[form.model.options.selectedIndex].value;
	self.location='inventory_opening_barcode.php?invoicecode=<?php echo $_GET[invoicecode]; ?>&product=' + val + '&brandid=' + val3 +'&modelid=' + val4;
}




</script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="http://raresoft.org/" class="site_title"><i class="fa fa-paw"></i> <span>Raresoft</span></a>
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
                    <h2>Inventory Opening with Barcode</h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a class="btn btn-sm btn-default"  href="accounts_transaction_cash_expenses.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Cash Expenses</span>
								</a>
                                
                                <a class="btn btn-sm btn-default"  href="accounts_transaction_bank_expenses.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Bank Expenses</span>
								</a>
                                
                                <a class="btn btn-sm btn-default"  href="accounts_transaction_bank_received.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Bank Received</span>
								</a>
                    			
                    			               
                    			<a class="btn btn-sm btn-default" style="color:#000" href="accountsreport.php">
                    				<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Ledger Report</span>
                    			</a>
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    
                    
                              
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
	$createdate=date('Y-m-d');
	
	$resultsss=mysql_query("Select * from transaction_inventorys where companyid='$_SESSION[companyid]' and cus_invoice='$_GET[invoicecode]'");
    $invrow=mysql_fetch_array($resultsss);


 			
				
				
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
 ('$_GET[inventorytype]','$_GET[categoryid]','$category','$_GET[brandid]','$_GET[brandid]','$_GET[modelid]','$_GET[modelid]','$productcode','$product','$unit','$invoice','$billno','$Jvoucherno','$supplier','$qty','$rate','$amounts','$coss','$amtcoss','$adjustlevel','$adjustamount','$lifofifoid','$note','$warehouse','$acchead','Opening Inventory','$companyid','$createby','Opening Inventory','$ttime','Normal','$createdate','$tmodifiddate','$modifiedby','$ip','$mac','Opening Invengory','$_SESSION[ponos]','$warranty')");
 
 
 purchaseinvoiceno();
  ?>
	
<?php }}} ?>

                    
                    
                    <form action="inventory_opening_barcode.php?inventorytype=<?php echo $_GET[inventorytype]; ?>&categoryid=<?php echo $_GET[categoryid]; ?>&brandid=<?php echo $_GET[brandid]; ?>&modelid=<?php echo $_GET[modelid]; ?>&invoicecode=<?php echo $_SESSION[ponos]; ?>&product=<?php echo $_GET[product]; ?>" class="form-horizontal form-label-left" method="post">



                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="1" onchange="javascript:reload(this.form)"  required="required" name="product" >
                            <option></option>
                            
           <?php 
			$result=mysql_query("Select distinct product from inventory_product where companyid='$_SESSION[companyid]' order by product");
			while($row=mysql_fetch_array($result)){
			if(($_GET[product])==$row[product]){?>
            <option selected value="<?php echo $row[product]; ?>"><?php echo $row[product]; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $row[product]; ?>"><?php echo $row[product]; ?></option>
                    <?php }} ?>
                          </select>
                        </div>
                      </div>




                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Inventory Brand</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="2" onchange="javascript:reload2(this.form)"  required="required" name="brand" >
                            <option></option>
                            
           <?php 
			$result=mysql_query("Select * from inventory_brand where companyid='$_SESSION[companyid]' order by brand");
			while($row=mysql_fetch_array($result)){
			if(($_GET[brandid])==$row[brand]){?>
            <option selected value="<?php echo $row[brand]; ?>"><?php echo $row[brand]; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $row[brand]; ?>"><?php echo $row[brand]; ?></option>
                    <?php }} ?>
                          </select>
                        </div>
                      </div>
                      
                      
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Inventory Model</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="3" onchange="javascript:reload3(this.form)"  required="required" name="model" >
                            <option></option>
                            
           <?php 
			$result=mysql_query("Select distinct model from inventory_product where  companyid='$_SESSION[companyid]' order by model");
			while($row=mysql_fetch_array($result)){
			if(($_GET[modelid])==$row[model]){?>
            <option selected value="<?php echo $row[model]; ?>"><?php echo $row[model]; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $row[model]; ?>"><?php echo $row[model]; ?></option>
                    <?php }} ?>
                          </select>
                        </div>
                      </div>
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Barcode Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
        <input type="text" id="last-name"  required="required" name="productcode" value="<?php  echo $_GET[productcode];?>" class="form-control col-md-7 col-xs-12" tabindex="4">            
                          
                        </div>
                      </div>
                      
                     
                     
                     
                      <!--div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Qty <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12"-->
                          <input type="hidden" id="last-name" tabindex="4" required="required" name="qty" value="1" class="form-control col-md-7 col-xs-12">
                        <!--/div>
                      </div--->  
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Rate <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name"  required="required" name="rate" value="<?php echo $row[rate] ?>" class="form-control col-md-7 col-xs-12" tabindex="5">
                        </div>
                      </div>
               




<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warranty</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" style="width:100%" tabindex="6"   required="required" name="warranty" >
                            
           <option value="">Choose ......</option>
                     <option value="No Warranty">No Warranty</option>
                     <option value="1 Week">1 Week</option>
                     <option value="1 Month">1 Month</option>
                     <option value="3 Month">3 Months</option>
                     <option value="6 Month">6 Months</option>
                     <option value="1 Year">1 Year</option>
                     <option value="2 Year">2 Year</option>
                     <option value="3 Year">3 Year</option>
                     <option value="Life Time">Life Time</option>
                                    </select>
                        </div>
                      </div>
                    




               
                <?php if($_GET[invoicecode]){ ?>
               <input type="hidden" id="last-name"   required="required" name="billno" value="<?php echo $invrow['voucherno']; ?>" class="form-control col-md-7 col-xs-12">
               <?php } else { ?>
               <input type="hidden" id="last-name"   required="required" name="billno" value="<?php echo $_SESSION['ceshexpenses']; ?>" class="form-control col-md-7 col-xs-12">
               <?php }  ?>
               
               
               
               
               
               
               
               
               <?php if($_GET[invoicecode]){ ?>
               <input type="hidden" id="last-name"   required="required" name="invoice" value="<?php echo $invrow['invoiceno']; ?>" class="form-control col-md-7 col-xs-12" readonly>
               <?php } else { ?>
               <input type="hidden" id="last-name" readonly   required="required" name="invoice" value="<?php echo $_SESSION['inventoryopening']; ?>" class="form-control col-md-7 col-xs-12">
               
               
               <?php }  ?>
                   
                   
                   
                   
                   <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Opening Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                
                    <?php if($_GET[invoicecode]){ ?>	
	            
                <input type="text" id="last-name"  required="required" name="purchasedate" value="<?php echo $invrow[tdate]; ?>" class="form-control col-md-7 col-xs-12" readonly>
                <?php } else { ?>
                <input type="text" id="last-name"  required="required" name="purchasedate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12">
                <?php }  ?>
                </div></div>
              


              
                  
                      

                     


                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <a href="inventory_opening_barcodes.php"  class="btn btn-primary">Cancel</a>
                          <button type="submit" class="btn btn-success" name="getstarted">Add</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>


















<!----------------------------------data view code from here------------------------------->
            
 <?php
	
	if($_GET[invoicecode]){
	 ?>          
              
   <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                  <div class="x_title">

                    <h2>Enlisted Item</h2>

                    <div class="clearfix"></div>

                  </div>

                  <div class="x_content">

 <?php 
							if($_GET[type]=='delete'){
								if($_GET[productdeletecode]){
								
							$results=mysql_query("Delete from transaction_inventorys where id='$_GET[productdeletecode]' and companyid='$_SESSION[companyid]'"); ?>
							<meta http-equiv="refresh" content="0;inventory_opening_barcode.php?type=delete&brandid=<?php echo $_GET[brandid] ?>&modelid=<?php echo $_GET[modelid] ?>&invoicecode=<?php echo $_GET[invoicecode] ?>&product=<?php echo $_GET[product] ?>">
	
								
							<?php }} ?>
                                               
<form id="addem" name="addem"  method="post"  class="form-horizontal form-label-left">
                     <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                        <th>SL</th>
                          <th style="width:5%">Barcode</th>
                          <th>Product</th>
                          
                          
                          <!---th>Category</th--->
                          <th>Warranty</th>
                          
                          
                          
                          <th style="width:10%">Qty</th>
                          <th style="width:10%">Rate</th>
                         <th style="width:10%">Amount</th>
                         <th style="width:15%">Options</th>

                        </tr>
                      </thead>


                      <tbody>
                        <?php
$resultssss=mysql_query("Select * from transaction_inventorys where companyid='$_SESSION[companyid]' and cus_invoice='$_GET[invoicecode]'");
$invrows=mysql_fetch_array($resultssss);						
						
$presult=mysql_query("Select * from accounts_ledger where ledger='Inventory A/c' and companyid='$_SESSION[companyid]'");
$prow=mysql_fetch_array($presult);

$mcresult=mysql_query("Select * from accounts_ledger where ledger='Capital A/c' and companyid='$_SESSION[companyid]'");
$mcrow=mysql_fetch_array($mcresult);


$supresult=mysql_query("Select * from accounts_ledger where ledgercode='$invrows[purchaseclint]' and companyid='$_SESSION[companyid]'");
$suprow=mysql_fetch_array($supresult);





	
							
						
$amountssss=$_POST[tb1];
$billno=$_POST[billno];

$dueamounts=$_POST[dueamounts];
if (isset($_POST['getstarteds'])){
$valid = true;
	 



	 
	 
if ($valid){
				
			
	$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day,journal) VALUES 
 ('$prow[accountreporttype]','$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Inventory A/c','$invoice','$createdate','','','$amounts','$amounts','','Opening Inventory','1','','','$companyid','','$row[tdate]','$ip','$mac','$createdate','$ttime','Opening Inventory','$invoice','Normal','$day','DR')");		
 
 
 
 
 $result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day,journal) VALUES 
 ('$mcrow[accountreporttype]','$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','Capital A/c','$invoice','$createdate','','','-$amounts','','$amounts','Opening Inventory','0','','','$companyid','','$row[tdate]','$ip','$mac','$createdate','$ttime','Opening Inventory','$invoice','Normal','$day','CR')");
 inventoryopening();




}}					
						
						
						
						
						
						 
				$results=mysql_query("Select * from transaction_inventorys where  companyid='$_SESSION[companyid]' and cus_invoice='$_GET[invoicecode]'");
				while($row=mysql_fetch_array($results)){ 
				$i=$i+1;
				
				
				
				
				
				
if (isset($_POST['getstarteds'])){
$valid = true;
	 

	 
	 
if ($valid){
 
 
 $result=mysql_query("INSERT INTO `transaction_inventory` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,purchaseclint,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,warranty) VALUES 
 ('$row[inventory_types]','$row[categoryid]','$row[categorys]','$row[brandid]','$row[brand]','$row[modelid]','$row[model]','$row[productcode]','$row[product]','$row[unit]','$row[invoiceno]','$row[voucherno]','$row[Jvoucherno]','$row[purchaseclint]','$row[qty]','$row[rate]','$row[amount]','$row[coss]','$row[amtcoss]','$row[adjustlevel]','$row[adjustamount]','$row[lifofifoid]','$row[note]','$row[warehouse]','$row[acchead]','Opening Inventory','$row[companyid]','Opening','$row[transactiontype]','$row[ttime]','$row[tstatus]','$row[tdate]','$row[tmodifiddate]','$row[modifiedby]','$row[ip]','$row[mac]','Opening Inventory','$row[warranty]')");
 purchaseinvoiceno();
 
$delete=mysql_query("Delete from transaction_inventorys where cus_invoice='$_GET[invoicecode]' and companyid='$_SESSION[companyid]'");
  
 
  ?>
	
<meta http-equiv="refresh" content="0;inventory_opening_barcode.php">
<?php }} ?>
                      <tr>
                        <td style="width:3%"><?php echo $i; ?></td>
                        <td style="width:10%"><?php echo $row[productcode]; ?></td>
                        <td><?php echo $row[product]; ?> - <?php echo $row[model]; ?> - <?php echo $row[brand]; ?></td>
                        
                        <!---td style="width:12%"><?php echo $row[categorys]; ?></td--->
                        <td style="width:12%"><?php echo $row[warranty]; ?></td>
                        
                        
                        
                        
                        
                     <td align="center" style="width:8%">
                     
                     <input type="hidden" id="last-name" style="width:120px" name="itemcode<?php echo $ids; ?>" value="<?php echo $row[itemcode]; ?>" class="form-control col-md-7 col-xs-12">
                     
                     
                     <input type="text" id="last-name" style="width:80px; text-align:center" name="qtys<?php echo $ids; ?>" value="<?php echo $row[qty] ?>" class="form-control col-md-7 col-xs-12">
                     
                     
                     </td>
                      
                     <td align="center" style="width:8%"><input type="text" id="last-name"  style="width:80px; text-align:center" name="rate<?php echo $ids; ?>" value="<?php echo $row[rate] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    </td>
                    
                    
                    <td align="center" style="width:8%"><input type="text" id="last-name"  style="width:80px; text-align:center" name="amount<?php echo $ids; ?>" value="<?php echo $row[amount] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    </td>
                    
                    <td align="center" style="width:10%">
                          
                            <!--a href="inventory_opening_barcode.php?type=edit&categoryeditit=<?php echo $row[categoryid] ?>&brandeditid=<?php echo $row[brandid] ?>&modeleditid=<?php echo $row[modelid] ?>&producteditcode=<?php echo $row[id] ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a--->
                            
                            
                            
                            
                            
                           
                            <a href="inventory_opening_barcode.php?type=delete&brandid=<?php echo $_GET[brandid] ?>&modelid=<?php echo $_GET[modelid] ?>&invoicecode=<?php echo $_GET[invoicecode] ?>&product=<?php echo $_GET[product] ?>&productdeletecode=<?php echo $row[id] ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i> Delete </a>
                            
                            
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
                    <td colspan="4" align="right">Total</td>
                    <td align="center"><?php echo $qtysss; ?></td>
                    <td align="center"><?php echo $ratesss; ?></td>
                    <td align="center"><input type="text" id="totalAmounts" name="amountssss" value="<?php echo $amountsss; ?>" style="width:80px; text-align:center; border:none; background-color:transparent"  class="form-control col-md-7 col-xs-12"></td>
                    <td></td>
                    </tr>  
                      
                      
                    <tr style="font-size:14px; font-weight:bold">
                    <td colspan="6" align="right">Total Amount</td>
                    <td align="center"><input type="text" id='tb1' name="tb1" autocomplete="off"  onkeyup="calculateTotal()"  style="width:80px; text-align:center;" class="form-control col-md-7 col-xs-12" value="<?php echo $amountsss; ?>" readonly required="required" ></td>
                    </tr>     
                      
                      
                      
                  
                  
                    
                    
                     <input type="hidden" name="dueamounts" id="dueamounts" class="form-control col-md-7 col-xs-12" style="text-align:center;width:80px; vertical-align:middle; height:35px;"   readonly >
                      
                   
                   <input type='hidden' id='total' value="<?php echo $amountsss; ?>" name="ttts" class="form-control col-md-7 col-xs-12" style="text-align:center;width:80px; height:35px;" disabled / >   
                  
                  
                  
                      
                      
                      
                      <tr>
                      <td colspan="8" style="text-align:center">
                     
                        
                       
                          <a href="purchase_order.php" type="cancel" class="btn btn-primary">Cancel</a>
                          <button type="submit" name="getstarteds" class="btn btn-success">Finish Opening </button>
                          
                          
                       
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
