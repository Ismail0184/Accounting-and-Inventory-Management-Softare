<?php
 ob_start();
 session_start();
 require_once ('dbconfig.php');
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }
 
?>

 <!-- sidebar menu -->

              <div class="menu_section">
                <h3></h3>
                <ul class="nav side-menu">
                  <li><a href="dashboard.php"><i class="fa fa-home"></i> Home </a></li>


 <?php 
 if($_SESSION["user_type"]=="2"){
 
 ?>
                     <li><a><i class="fa fa-area-chart"></i>Company Manage <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      
                      
                      
                       <li class="sub_menu"><a href="company.php">Edit Company Info</a></li>
                       <li class="sub_menu"><a href="user_list.php">Manage User</a></li>
                       <!--li class="sub_menu"><a href="pwordchange.php">Change Password</a></li--->
                       
                       </ul>
                       </li>
               <?php } ?>
                  
                  
                  
                 <li><a><i class="fa fa-area-chart"></i>Accounts <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <!---li><a href="accounts_reportlevel.php">Report Level</a></li-->
                      
                      
                       <li class="sub_menu"><a href="#">Chirt of Accounts<span class="fa fa-chevron-down"></span></a>
                       
                       <ul class="nav child_menu">
                       <li><a href="accounts_maingroup.php">Group</a></li>
                      <li><a href="accounts_subsidiary.php">Sub Group</a></li>
                      
                      <li><a href="accounts_ledger.php">Ledger</a></li>
                      <li><a href="accounts_subledger.php">Sub Ledger</a></li>
                      <li><a href="accounts_coalist.php">COA List</a></li>
                      <li><a href="accounts_transaction_opening_balance.php">Opening Balance</a></li>
                      
                       </ul>
                       </li>
                       
                       
                       
                      
                      
                      <li class="sub_menu"><a href="#">Transaction<span class="fa fa-chevron-down"></span></a>
                       
                       <ul class="nav child_menu">
                      <li><a href="accounts_transaction_cash_received.php">Cash Receive</a></li>
                      <li><a href="accounts_transaction_cash_expenses.php">Cash Payment</a></li>
                      <li><a href="accounts_transaction_bank_received.php">Bank Receive</a></li>
                      <li><a href="accounts_transaction_bank_expenses.php">Bank Payment</a></li>
                      <li><a href="#">Journal Entry</a></li>
                      
                       </ul>
                       </li>
                       
                       
                       <?php 
 if($_SESSION["user_type"]=="2"){
 
 ?>
                       <li><a href="accountsreports.php">Reports</a></li>
                       <?php } ?>
                    </ul>
                  </li>
                  
                  
                  
                  
                  
                  
                  <li><a><i class="fa fa-bar-chart"></i>Service<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="create_service.php">Create Service</a></li>
                      <li><a href="servicelist.php">Service List</a></li>
                      <li><a href="transaction_service.php">Bill Collection</a></li>
                    </ul>
                  </li>
                  
                  
                  
                  
                  
                  
                  
                  <li><a><i class="fa fa-dropbox"></i> Inventory <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                   
                      <li><a href="inventory_brand.php">Category</a></li>
                       <li><a href="inventory_categorys.php">Brand</a></li>
                       <!--li><a href="inventory_model.php">Model</a></li-->
                        <li><a href="inventory_product.php">Product</a></li>
                        <li><a href="inventory_productlist.php">Product List</a></li>
                        <li><a href="inventory_opening_barcode.php">Inventory Opening (Barcode)</a></li>
                      <li><a href="inventory_opening.php">Inventory Opening</a></li>
                      <li><a href="inventory_opening_report.php">Opening Report</a></li>
                       </ul>
                       </li>
                      
                 
                  
                  
                  
                  
                   <li><a><i class="fa fa-shopping-cart"></i> Purchase <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                    
                    <li class="sub_menu"><a href="#">Supplier<span class="fa fa-chevron-down"></span></a>
                       
                      <ul class="nav child_menu">
                      
                      <li><a href="supplier.php">Create New Supplier</a></li>
                      <li><a href="procurement_supplierlist.php">Supplier List</a></li>
                      </ul>
                       </li>
                       
                       <li class="sub_menu"><a href="warehouse.php">Warehouse</a>
                       
                      
                       </li>
                    
                    
                       <li class="sub_menu"><a href="purchase_barcode.php">Purchase</a></li>
                       <!---li class="sub_menu"><a href="purchase.php">Purchase</a></li---->
                       
                       
                       
                       
                       
                       
                       
                         <li><a href="purchase_report.php">Purchase Repor</a></li>
                       
                       
                       
                    </ul>
                  </li>
                  
                  
                 
                 <li><a href="stock_report.php"><i class="fa fa-archive"></i> Stock Report <!--span class="fa fa-chevron-down"></span---></a>
                 
                 
                  <li><a><i class="fa fa-archive"></i> Sales <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="sub_menu"><a href="#">Customer<span class="fa fa-chevron-down"></span></a>
                       
                       <ul class="nav child_menu"><li><a href="sales_customer.php">Create New Custmer</a></li>
                       <li><a href="sales_customer_list.php">Customer List</a></li></ul>
                       </li>
                       
                       
                       
                       <li class="sub_menu"><a href="#">Quotation<span class="fa fa-chevron-down"></span></a>
                       
                       <ul class="nav child_menu"><li><a href="sales_quotation.php">Create Quotation</a></li>
                       <li><a href="sales_quotation_list.php">Quotation List</a></li></ul>
                       </li>
                          
                           
                          <!--li class="sub_menu"><a href="sales_cash_barcode.php">Sales (Barcode)<!--span class="fa fa-chevron-down"></span--></a>
                       
                       <!--ul class="nav child_menu"><li><a href="sales_cash_barcode.php">Cash Sales</a></li>
                       <li><a href="sales_credit_barcode.php">Credit Sales</a></li></ul-->
                       <!--/li-->
                       
                       
                       
                         <li class="sub_menu"><a href="sales.php">Sales<!--span class="fa fa-chevron-down"></span--></a>
                       
                       <!--ul class="nav child_menu"><li><a href="sales_cash.php">Cash Sales</a></li>
                       <li><a href="sales_credit.php">Credit Sales</a></li></ul--->
                       </li>
                       
                       
                       <li class="sub_menu"><a href="sales_report.php">Sales Report</a></li>
                       <li class="sub_menu"><a href="sales_return.php">Sales Return</a></li>
                       <!--ul class="nav child_menu">
                       <li><a href="sales_challan_list.php">Challan List</a></li>
                       <!--li><a href="sales_report.php">Sales Report</a></li--->
                       <!--li><a href="sales_stock_report.php">Stock Report</a></li></ul>
                       </li-->
                    </ul>
                  </li>
                  
                  
                  
                  
                   <li><a><i class="fa fa-th-large"></i>Market Bill<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     <li><a href="create_market_ledger.php">Create Market Ledger</a></li>
                      <li><a href="market_stock_out.php">Stock Out</a></li>
                      <li><a href="market_bill_collection.php">Bill Collection</a></li>
                      <li><a href="market_ledger.php?reporttypes=ladger">Market Ledger</a></li>
                    </ul>
                  </li>
                  
                 
                  
                  
                  
                 
                  
                </ul>
                <br />
                <p align="center" style="color:#F00">Your IP: <?php echo $ip; ?></p>
              </div>
           
            
