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

    <title><?php echo $_SESSION[company]; ?> | Sales</title>

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
	self.location='saless.php?productcodeget=' + val ;
}


</script>



    <script>
        var x = 0;
        var y = 0;
        var z = 0;
        function calc(obj) {
            var e = obj.id.toString();
            if (e == 'qtysa') {
                x = Number(obj.value);
                y = Number(document.getElementById('rate').value);
            } else {
                x = Number(document.getElementById('qtysa').value);
                y = Number(obj.value);
            }
            z = x * y;
            document.getElementById('total').value = z;
            document.getElementById('update').innerHTML = z;
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
                    <h2>Back Date Sales Form</h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
                     
                     <a class="btn btn-sm btn-default"  href="saless.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Sales Entry</span>
								</a>
                     
								<a class="btn btn-sm btn-default"  href="sales_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Sales Report</span>
								</a>
                                
                                <a class="btn btn-sm btn-default"  href="sales_pending_list.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Pending Sales Invoice</span>
								</a>
                                
                                
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              
                    

<?php 
$initiate=$_POST[initiate];
$salesdate=$_POST[salesdate];
$invoice=$_POST[invoice];
$billno=$_POST[billno];
$vendor=$_POST[vendor];
$vendorphone=$_POST[vendorphone];
$vendoraddress=$_POST[vendoraddress];


if(isset($initiate)){
	
$del=mysql_query("Delete from transaction_inventory_details where invoiceno='$invoice' and companyid='$_SESSION[companyid]'");	
	
$insert=mysql_query("INSERT INTO transaction_inventory_details (invoiceno,voucherno,invoice_date,customer_id,mobileno,address,companyid)  VALUES ('$invoice','$billno','$salesdate','$vendor','$vendorphone','$vendoraddress','$_SESSION[companyid]')");	

$_SESSION[initiate_invoice_sales_back]=$invoice;

//echo $_SESSION[initiate_invoice_sales_back];
//unset($_SESSION["initiate_invoice_sales_back"]);

}



$resultsssss=mysql_query("Select * from transaction_inventory_details where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
$inirow=mysql_fetch_array($resultsssss);
?>

                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">









                 <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sales Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
                <?php if($_SESSION[initiate_invoice_sales_back]){ ?>
                        
                        
	            <input type="text" id="last-name"  required="required" name="salesdate" value="<?php echo $inirow[invoice_date] ?>" class="form-control col-md-7 col-xs-12"  readonly  >
                
                <?php } else { ?>
                
                <input type="text" id="last-name"  required="required" name="salesdate" value="<?php echo date('Y-m-d'); ?>" class="form-control col-md-7 col-xs-12"    >
                
                <?php } ?>

                      </div>  
	                </div>
                    
                    
                    
                    
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Invoice Number<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
                          <input type="text" id="last-name"   required="required" name="invoice" value="<?php if($_SESSION[initiate_invoice_sales_back]){ echo $inirow[invoiceno]; } else {echo $_SESSION['csalesnos']; } ?>" class="form-control col-md-7 col-xs-12"  readonly >
                          
                          
                          <?php

if($_SESSION[initiate_invoice_sales_back]){

 ?>

                          <input type="hidden" id="last-name"   required="required" name="billno" value="<?php echo $inirow[voucherno] ?>" class="form-control col-md-7 col-xs-12">
                          
                          <?php } else { ?>
                          <input type="hidden" id="last-name"   required="required" name="billno" value="<?php echo $_SESSION['crid']; ?>" class="form-control col-md-7 col-xs-12">
                          <?php } ?>
                        </div>
                      </div>  
                      
                      
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="vendor" required="required" value="<?php echo $inirow[customer_id] ?>" name="vendor" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_invoice_sales_back]){ ?> readonly <?php } ?> ></div></div>
                        
                        
                        
                        
                        
                        
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Contact Number<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="first-name"  value="<?php echo $inirow[mobileno] ?>" name="vendorphone" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_invoice_sales_back]){ ?> readonly <?php } ?> ></div></div>
                       
                    


                       

                    <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Address<span class="required">*</span></label>
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <textarea id="first-name"  style="width:100%"   name="vendoraddress" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_invoice_sales_back]){ ?> readonly <?php } ?>><?php echo $inirow[address] ?></textarea></div></div>   
               
               
               <div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_invoice_sales_back]){ ?>
               <?php } else { ?>
               <button type="submit" name="initiate" class="btn btn-success">Initiate Sales invoice</button>
               <?php } ?>
               </div></div>   
               
               
                          
               
               
               </form>
               
               
               
               
               
<!----------------------------------- initiate end--------------------------------------------------------------------->               
               
               
               
               
               
 <?php                    



                 $catresult=mysql_query("Select * from transaction_inventory where productcode='$_GET[productcodeget]' and                  companyid='$_SESSION[companyid]'");
                 $catrow=mysql_fetch_array($catresult);
                 
				 
				 $category=$catrow[category];
                 $brand=$catrow[brand];
				 $model=$catrow[model];
				 $product=$catrow[product];

				 $warranty=$_POST[warranty];
				 
				 
				$unit=$row[unit];
				$inventory_types=$row[inventory_types];
				$productcode=$_POST[productcode];
				$rate=$_POST[rate];
				$qtys=$_POST[qtys];
				$amounts=$rate*$qtys;
				$prate=$_POST[prate];
				$amtcoss=$prate*$qtys;
				

				$tdates=date("Y-m-d");
				$idatess=date('Y-m-d'); 
                $day = date('l', strtotime($idatess));
				$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
				$timess=$dateTime->format("d/m/y  H:i A");
				//echo "$timess";


$add=$_POST[add];                    
if (isset($_POST['add'])){
	
	
	
$valid = true;
	 



	 
	 
if ($valid){
	if($rate>0){
		
	$_SESSION[spinvoice]=$invoice;	
		
 $result=mysql_query("INSERT INTO `transaction_inventorys` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,vendor,vendorphone,vendoraddress,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,cus_invoice,warranty) VALUES 
 ('$_GET[inventorytype]','$_GET[categoryid]','$category','$brand','$brand','$model','$model','$productcode','$product','$unit','$_SESSION[initiate_invoice_sales_back]','$inirow[voucherno]','$Jvoucherno','$inirow[customer_id]','$inirow[mobileno]','$inirow[address]','-$qtys','$rate','$amounts','$prate','$amtcoss','$adjustlevel','$adjustamount','$lifofifoid','$note','$warehouse','$acchead','Cash Received','$_SESSION[companyid]','$_SESSION[login_email]','Cash Sales','$timess','Normal','$inirow[invoice_date]','$tmodifiddate','$modifiedby','$ip','$mac','Sales','$_SESSION[ponos]','$warranty')");
 
 
 //unset($_SESSION["spinvoice"]);
 cashsalesnos();
  ?>
	
<?php }}} ?>
                   
                      




<?php

if($_SESSION[initiate_invoice_sales_back]){

 ?>
 
 
 
<SCRIPT language=JavaScript>

function doAlert(form)
{
var val=form.qtys.value;
var val2=form.stockbalance.value;

if (Number(val)>Number(val2)){
alert('oops!! Exceed Stock Balance!! Thanks');

form.qtys.value='';
}
form.qtys.focus();
}</script> 



<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
               

 <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                  

 <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      


                      <tbody>
                       <tr>
                        
                      <td style="width:20%">
                      
                      <select class="select2_single form-control" style="width:280px" tabindex="-1" onchange="javascript:reload(this.form)"  required="required" placeholder="product code" name="productcode" >
                            <option></option>
                            
                            <?php 
							$result=mysql_query("SELECT distinct productcode FROM transaction_inventory where  companyid='$_SESSION[companyid]' and tfor!='Sales'");
							while($row=mysql_fetch_array($result)){
								
								
							$pname=getSVALUE("transaction_inventory", "product as product", "where productcode='$row[productcode]'   and companyid='$_SESSION[companyid]'");
							
							
							$modelname=getSVALUE("transaction_inventory", "model as model", "where productcode='$row[productcode]'   and companyid='$_SESSION[companyid]'");
							
							
							$stockbalance=getSVALUE("transaction_inventory", "SUM(qty) as qty", "where productcode='$_GET[productcodeget]'   and companyid='$_SESSION[companyid]' ");
							
							
							$rate=getSVALUE("transaction_inventory", "MAX( rate ) AS max", " where 	productcode='$_GET[productcodeget]' and companyid='$_SESSION[companyid]' and tfor!='Sales'");		 
				
					
						
								
								if(($_GET[productcodeget])==$row[productcode]){
									
									?> 
                                         
                 
                 
                 
                 <option selected value="<?php echo $row[productcode]; ?>"><?php echo $row[productcode]; ?>-<?php echo $pname; ?>-<?php echo $modelname; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $row[productcode]; ?>"><?php echo $row[productcode]; ?>-<?php echo $pname; ?>-<?php echo $modelname; ?></option>
                    <?php }} ?>
                          </select></td>
                          
                          
<td align="center" style="width:5%">
                     <select class="select2_single form-control" name="warranty<?php echo $ids; ?>" style="width:120px" >
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


<td style="width:5%">
                        <input type="text" id="stockbalance" style="width:80px; height:37px; color:red; font-weight:bold; text-align:center" readonly required="required" value="<?php echo $stockbalance ?>" name="stockbalance" class="form-control col-md-7 col-xs-12" >
                      </td>
                      
                      
                      
<td style="width:5%">
                        <input type="text" id="prate" style="width:80px; height:37px; color:red; font-weight:bold; text-align:center" readonly required="required" value="<?php echo $rate ?>" name="prate" class="form-control col-md-7 col-xs-12" >
                      </td>
                      
                      
                      
                      
                      

<td style="width:5%">
                        <input type="text" autocomplete="off" id="qtys" style="width:80px; height:37px; font-weight:bold; text-align:center"  required="required" onkeyup="doAlert(this.form);"  name="qtys" placeholder="qtys"   class="qtys" >
                        
                        
<input type="hidden" id="qtysa" style="width:80px; height:37px; font-weight:normal; text-align:center"    name="qtysa" onkeyup="calc(this)"  class="form-control col-md-7 col-xs-12" >                        

</td>

<td style="width:5%">
                        <input type="text" id="rate" style="width:80px; height:37px;  font-weight:normal; text-align:center"  required="required" autocomplete="off"  name="rate" placeholder="rate" onkeyup="calc(this)"  class="form-control col-md-7 col-xs-12" >
</td>
                    
                     
                        
                     <td align="center" style="width:5%">
                     
                        
                        <button type='text' placeholder="amount"  class="form-control col-md-7 col-xs-12" style="text-align:center;width:80px; vertical-align:middle; height:37px;" readonly ><span id="update" style="vertical-align:middle; font-weight:bold"></span></button>
                      
                        <input type="hidden" id="total" name="total" value="0" />
    
                     
                     </td>
                      
            <td align="center" style="width:5%">
            <button type="submit" class="btn btn-success" name="add">Add</button></td>
                      

                      
                        </tr>
                        
                        
                     
                      
                      
                      </tbody>
                     </table> 
                 </form>
                 
                 



              












<!-----------------------Data Save Confirm ------------------------------------------------------------------------->  

<?php 
							if($_GET[type]=='delete'){
								if($_GET[productdeletecode]){
								
							$results=mysql_query("Delete from transaction_inventorys where id='$_GET[productdeletecode]' and companyid='$_SESSION[companyid]'"); ?>
							<meta http-equiv="refresh" content="0;saless.php">
	
								
							<?php }} ?>
                      
<form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
                     <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                        <th>SL</th>
                          <th>Barcode</th>
                          <th>Product</th>
                          
                          
                          <th>Warranty</th>
                          
                          
                          
                          <th style="width:10%">Qty</th>
                          <th style="width:10%">Rate</th>
                         <th style="width:10%">Amount</th>
                         <th style="width:10%">Options</th>

                        </tr>
                      </thead>


                      <tbody>
                        <?php
$resultssss=mysql_query("Select * from transaction_inventorys where companyid='$_SESSION[companyid]' and invoiceno='$_SESSION[initiate_invoice_sales_back]'");
$invrows=mysql_fetch_array($resultssss);						
						
$presult=mysql_query("Select * from accounts_ledger where ledger='Sales' and companyid='$_SESSION[companyid]'");
$prow=mysql_fetch_array($presult);

$mcresult=mysql_query("Select * from accounts_ledger where ledger='Main Cash' and companyid='$_SESSION[companyid]'");
$mcrow=mysql_fetch_array($mcresult);


$supresult=mysql_query("Select * from accounts_ledger where ledger='Accounts Receivable' and companyid='$_SESSION[companyid]'");
$suprow=mysql_fetch_array($supresult);



$inacresult=mysql_query("Select * from accounts_ledger where ledger='Inventory A/c' and companyid='$_SESSION[companyid]'");
$invacrow=mysql_fetch_array($inacresult);

$costresult=mysql_query("Select * from accounts_ledger where ledger='Cost of Sales' and companyid='$_SESSION[companyid]'");
$costrow=mysql_fetch_array($costresult);




	
$companyid=$_SESSION[companyid];							
$createby=$_SESSION[login_email];						
$amountssss=$_POST[raresoft2];
$billno=$_POST[billno];
$raresoft1=$_POST[raresoft1];
$raresoft2=$_POST[raresoft2];
$dueamounts=$_POST[dueamounts];
if (isset($_POST['confirmsave'])){
$valid = true;
	 


 if($dueamounts>0){





$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$suprow[accountreporttype]','$suprow[rlid]','$suprow[reportlevelname]','$suprow[mgid]','$suprow[maingroup]','$suprow[subsidiaryid]','$suprow[subsidiary]','$suprow[ledgercode]','$suprow[ledger]','$_SESSION[SCDid]','$invrows[tdate]','','','$dueamounts','$dueamounts','','Sales to  $suprow[ledger], Due Amount','1','DR','','','$companyid','','$createby','$ip','$mac','$idatess','$timess','Cash Sales','$inirow[invoiceno]','Normal','$day')");	


	
	
$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$prow[accountreporttype]','$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Sales','$_SESSION[SCDid]','$invrows[tdate]','','','-$dueamounts','','$dueamounts','$suprow[ledger], Sales to  $suprow[ledger], Due Amount','0','CR','','','$companyid','','$createby','$ip','$mac','$idatess','$timess','Cash Sales','$inirow[invoiceno]','Normal','$day')");		
 
 salescashdue();
 
 
 	 
	 
}
	 
	 
if ($valid){
				


$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$mcrow[accountreporttype]','$mcrow[rlid]','$mcrow[reportlevelname]','$mcrow[mgid]','$mcrow[maingroup]','$mcrow[subsidiaryid]','$mcrow[subsidiary]','$mcrow[ledgercode]','Main Cash','$inirow[voucherno]','$invrows[tdate]','','','$amountssss','$amountssss','','Sales to  $suprow[ledger]','1','DR','','','$companyid','','$createby','$ip','$mac','$idatess','$timess','Cash Sales','$inirow[invoiceno]','Normal','$day')");
 


			
	$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 ('$prow[accountreporttype]','$prow[rlid]','$prow[reportlevelname]','$prow[mgid]','$prow[maingroup]','$prow[subsidiaryid]','$prow[subsidiary]','$prow[ledgercode]','Sales','$inirow[voucherno]','$invrows[tdate]','','','-$amountssss','','$amountssss','Main Cash, Sales to  $suprow[ledger]','0','CR','','','$companyid','','$createby','$ip','$mac','$idatess','$timess','Cash Sales','$inirow[invoiceno]','Normal','$day')");	
 
 
 

 
 
 
 
 //$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 //('$invacrow[accountreporttype]','$invacrow[rlid]','$invacrow[reportlevelname]','$invacrow[mgid]','$invacrow[maingroup]','$invacrow[subsidiaryid]','$invacrow[subsidiary]','$invacrow[ledgercode]','Inventory A/c','$inirow[voucherno]','$invrows[tdate]','','','$amountssss','$amountssss','','Sales to  $suprow[ledger]','1','DR','','','$companyid','','$createby','$ip','$mac','$invrows[tdate]','$timess','Cash Sales','$inirow[invoiceno]','Normal','$day')");
 


			
	//$result=mysql_query("INSERT INTO `transaction_cash` (accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,	VNumber,TDate,qty,rate,Amount,debitamount,creditamount,Note,SubID,journal,Person,CheckNumber,companyid,company,Username,IPAdress,	MAC,IDate,time,ttype,invoiceno,status,day) VALUES 
 //('$costrow[accountreporttype]','$costrow[rlid]','$costrow[reportlevelname]','$costrow[mgid]','$costrow[maingroup]','$costrow[subsidiaryid]','$costrow[subsidiary]','$costrow[ledgercode]','Cost of Sales','$inirow[voucherno]','$invrows[tdate]','','','-$amountssss','','$amountssss','Main Cash, Sales to  $suprow[ledger]','0','CR','','','$companyid','','$createby','$ip','$mac','$invrows[tdate]','$timess','Cash Sales','$inirow[invoiceno]','Normal','$day')");		
	
 
 
 $deletes=mysql_query("Delete From transaction_inventory_details where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
					   
 
 
 
 
cashreceivedid();}}					
						
						
						
						
						
						 
				$results=mysql_query("Select * from transaction_inventorys where  companyid='$_SESSION[companyid]' and invoiceno='$_SESSION[initiate_invoice_sales_back]'");
				while($row=mysql_fetch_array($results)){ 
				$i=$i+1;
				
				
				
				
				
				
if (isset($_POST['confirmsave'])){
$valid = true;
	 

	 
	 
if ($valid){
 
 
 $result=mysql_query("INSERT INTO `transaction_inventory` (inventory_types,categoryid,categorys,brandid,brand,modelid,model,productcode,product,unit,invoiceno,voucherno,Jvoucherno,purchaseclint,qty,rate,amount,coss,amtcoss,adjustlevel,adjustamount,lifofifoid,note,warehouse,acchead,btype,companyid,transactionby,transactiontype,ttime,tstatus,tdate,tmodifiddate,modifiedby,ip,mac,tfor,warranty,vendor,vendorphone,vendoraddress) VALUES 
 ('$row[inventory_types]','$row[categoryid]','$row[categorys]','$row[brandid]','$row[brand]','$row[modelid]','$row[model]','$row[productcode]','$row[product]','$row[unit]','$row[invoiceno]','$row[voucherno]','$row[Jvoucherno]','$row[purchaseclint]','$row[qty]','$row[rate]','$row[amount]','$row[coss]','$row[amtcoss]','$row[adjustlevel]','$row[adjustamount]','$row[lifofifoid]','$row[note]','$row[warehouse]','$row[acchead]','$row[btype]','$row[companyid]','$row[transactionby]','$row[transactiontype]','$row[ttime]','$row[tstatus]','$row[tdate]','$row[tmodifiddate]','$row[modifiedby]','$row[ip]','$row[mac]','$row[tfor]','$row[warranty]','$row[vendor]','$row[vendorphone]','$row[vendoraddress]')");
 cashsalesnos();
 
$delete=mysql_query("Delete from transaction_inventorys where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");


$update1=mysql_query("Update transaction_inventory set advance='$raresoft2' where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");

$update2=mysql_query("Update transaction_inventory set due='$dueamounts' where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");



if($raresoft1>0){
$update=mysql_query("Update transaction_inventory set discount='$raresoft1' where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
	
}

  
unset($_SESSION['initiate_invoice_sales_back']); 
  ?>
	
<meta http-equiv="refresh" content="0;saless.php">
<?php }} ?>
                      <tr>
                        <td style="width:3%"><?php echo $i; ?></td>
                        <td style="width:15%"><?php echo $row[productcode]; ?></td>
                        <td><?php echo $row[product]; ?>-<?php echo $row[model]; ?></td>
                        
                        <td style="width:12%"><?php echo $row[warranty]; ?></td>
                        
                        
                        
                        
                        
                     <td align="center" style="width:8%">
                     
                     <input type="hidden" id="last-name" style="width:120px" name="itemcode<?php echo $ids; ?>" value="<?php echo $row[itemcode]; ?>" class="form-control col-md-7 col-xs-12">
                     
                     
                     <input type="text" id="last-name" style="width:80px; text-align:center" name="qtys<?php echo $ids; ?>" value="<?php echo substr($row[qty],1) ?>" class="form-control col-md-7 col-xs-12">
                     
                     
                     </td>
                      
                     <td align="center" style="width:8%"><input type="text" id="last-name"  style="width:80px; text-align:center" name="rate<?php echo $ids; ?>" value="<?php echo $row[rate] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    </td>
                    
                    
                    <td align="center" style="width:8%"><input type="text" id="last-name"  style="width:80px; text-align:center" name="amount<?php echo $ids; ?>" value="<?php echo $row[amount] ?>" class="form-control col-md-7 col-xs-12"></td>
                      
                    </td>
                    
                    
                    
                  <td align="center" style="width:10%">
                  <a href="saless.php?type=delete&productcodeget=<?php echo $_GET[productcodeget] ?>&productdeletecode=<?php echo $row[id] ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i> Delete </a>
                            
                            
                          </td>
                      
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                      
                      
                        </tr>
                        
                        
 <?php 
 $qtysss=getSVALUE("transaction_inventorys", "Sum(qty) as qty", " where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
 $ratesss=getSVALUE("transaction_inventorys", "Sum(rate) as rate", " where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
 $amountsss=getSVALUE("transaction_inventorys", "Sum(amount) as amount", " where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
 ?>                       
                        <?php } ?>
                        
                        
                      
                      </tbody>
                      
                      
                      
                  
 <tr>
                      
                      
                      <td colspan="4" style="font-weight:bold" align="right">Total</td>
                      <td align="center" ><input  type='text' id='totalPrice' class="form-control col-md-7 col-xs-12" style="text-align:center;width:100px; height:35px;" value="<?php echo substr($qtysss,1); ?>" disabled / ></td>
                      
                      <td align="center"><input  type='text' id='totalRate' class="form-control col-md-7 col-xs-12" style="text-align:center; width:100px; height:35px;" disabled /></td>
                      
                      <td align="center"><input  type='text' id='totalAmount' class="form-control col-md-7 col-xs-12" style="text-align:center; width:100px; height:35px;" value="<?php echo $amountsss; ?>" disabled /></td>
                      <td></td>
                      </tr>
                      
                      
 
 <script type="text/javascript">

	function calculateTotal() {
		
		var totalAmt = document.ismail.raresoft3.value;
		totalR = eval(totalAmt - (document.ismail.raresoft1.value)-(document.ismail.raresoft2.value));
		
		document.getElementById('dueamounts').value = totalR;
	}

</script>							

                      
                       <tr><td colspan="4" style="font-weight:bold" align="right">Total Amount</td>
                      <td colspan="3"><input type='text' id='totalAmounts' class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;" value="<?php echo $amountsss; ?>"  disabled / ></td><td></td></tr>
                      
                      
                      <tr><td colspan="4" style="font-weight:bold" align="right">Discount</td>
                      <td colspan="3"><input type='text' id='raresoft1' name="raresoft1" autocomplete="off"  onkeyup="calculateTotal()" class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;"  ></td><td></td></tr>
                      
                      
                      <tr><td colspan="4" style="font-weight:bold" align="right">Received Amount</td>
                      <td colspan="3"><input type='text' id='raresoft2' name="raresoft2" autocomplete="off" required="required" onkeyup="calculateTotal()" class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;"  ></td><td></td></tr>
                      
                      <tr><td colspan="4" style="font-weight:bold" align="right">Total Due</td>
                      <td colspan="3">
                      
                      
                      
                      
                      
                      
                      <input type="text" name="dueamounts" id="dueamounts" class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; vertical-align:middle; height:35px;"   readonly >
                     
                      
                   <!--input type="hidden" name="total" value="100" /--->
                   
                   <input type='hidden' id='raresoft3' value="<?php echo $amountsss; ?>" class="form-control col-md-7 col-xs-12" style="text-align:center;width:335px; height:35px;" disabled / >   
                      
                      </td><td></td></tr>                    
                    
                    
                     
                  
                  
                  
                      
                      
                      
                      <tr>
                      <td colspan="8" style="text-align:center">
                     
                        
                       <?php 
					   
					   $cancel=$_POST[cancel];
					   
					   if(isset($cancel)){
					   $deletes=mysql_query("Delete From transaction_inventory_details where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
					   
					   $deletes=mysql_query("Delete From transaction_inventorys where invoiceno='$_SESSION[initiate_invoice_sales_back]' and companyid='$_SESSION[companyid]'");
					   
					   unset($_SESSION["initiate_invoice_sales_back"]);
					   
					   
					   ?>
                       <meta http-equiv="refresh" content="0;saless.php">

                       <?php } ?>
                          
                          <button type="submit" name="cancel" class="btn btn-primary">Delete Invoice </button>
                          <button type="submit" name="confirmsave" class="btn btn-success">Confirm and Finish Invoice </button>
                          
                          
                       
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
	
	// we used jQuery 'keyup' to trigger the computation as the user type
$('.qtys').keyup(function () {

	// initialize the sum (total price) to zero
    var sum = 0;
	
	// we use jQuery each() to loop through all the textbox with 'price' class
	// and compute the sum for each loop
    $('.qtys').each(function() {
        sum += Number($(this).val());
    });
	
	// set the computed value to 'totalPrice' textbox
	$('#qtysa').val(sum);
	
});

	
	
	
	
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
