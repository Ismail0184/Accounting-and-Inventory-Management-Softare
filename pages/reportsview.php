<?php
 //ob_start();
 session_start();
 require_once 'base.php';
  require_once 'create_id.php';
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM company WHERE companyid=".$_SESSION['companyid']."");
 $userRow=mysql_fetch_array($res);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>







<?php 
/////////////////////////////////////cash book----------------------------------------------------------					  
					  if(($_GET['reporttypes'])=='cashbook'): ?>


              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 align="center"><?php echo $_SESSION[company]; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10%">Voucher No</th>
                          
                          <th style="width: 10%">Date</th>
                          <th style="">Description</th>
                          <th style="width: 10%">Debit</th>
                          <th style="width: 10%">Credit</th>
                           <th style="width: 10%">Balance</th>
                          
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
				$result=mysql_query("Select * from transaction_cash where companyid='$_SESSION[companyid]' and TDate between '$_GET[datefrom]' and '$_GET[dateto]' and Acchead='Main Cash' order by id DESC");
				while($row=mysql_fetch_array($result)){
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $row[VNumber]; ?></td>
                        <td><?php echo $row[TDate]; ?></td>
                        <td><?php echo $row[Note]; ?></td>
                        <td><?php echo $row[debitamount]; ?></td>
                        <td><?php echo $row[creditamount]; ?></td>
                        <td><?php echo $row[debitamount]-$row[creditamount]; ?></td>
                        
                        
                          
                        </tr>
                        <?php } ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              
<?php elseif ($_GET['reporttypes']=='cashjournal'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>
              

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 align="center"><?php echo $_SESSION[company]; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 1%">Date</th>
                          
                          <th style="">Brand</th>
                          <th style="">Model</th>
                          <th style="">Serial/IME</th>
                          <th style="">Contact Person</th>
                           <th style="">Mobile</th>
                           <th style="">Address</th>
                           <th style="">Delivery Date</th>
                           <th style="">Type</th>
                           <th style="">Charge</th>
                           
                          <th>Option</th>
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
				$result=mysql_query("Select * from service where companyid='$_SESSION[companyid]' order by id DESC");
				while($row=mysql_fetch_array($result)){
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $row[createdate]; ?></td>
                        <td><?php echo $row[brand]; ?></td>
                        <td><?php echo $row[model]; ?></td>
                        <td><?php echo $row[ime]; ?></td>
                        <td><?php echo $row[cp]; ?></td>
                        <td><?php echo $row[cn]; ?></td>
                        <td><?php echo $row[address]; ?></td>
                        <td><?php echo $row[dd]; ?></td>
                        
                        <td><?php echo $row[servicetype]; ?></td>
                        <td><?php echo $row[sc]; ?></td>
                        
                          
                        </tr>
                        <?php } ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>  
              
 
 
 
 
 
 <?php elseif ($_GET['reporttypes']=='ladger'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>
              

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 align="center" style="margin-left:35%; font-weight:bold; color:#000"><?php echo $_SESSION[company]; ?></h2><br>
                    <?php
				$results=mysql_query("Select * from accounts_ledger where ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]'");
				$ledrow=mysql_fetch_array($results);
					
					 ?>
                    <h4 align="center" style="margin-left:35%; font-weight:bold; color:#000"><?php echo $ledrow[ledger]; ?></h4>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10%">Date</th>
                          
                          <th style="width:5%">Voucher</th>
                          <th style="width:5%">Account Head</th>
                          <th style="30%">Details</th>
                         
                          
                           <th align="right" style="width:5%">Debit</th>
                           <th align="right" style="width:5%">Credit</th>
                           
                           
                          
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
				$result=mysql_query("Select * from transaction_cash where ledgercode='$_GET[ledgercode]' and companyid='$_SESSION[companyid]' order by id DESC");
				while($row=mysql_fetch_array($result)){
					
					$debitamount=number_format($row[debitamount],2);
					$creditamount=number_format($row[creditamount],2);
				$i=$i+1; ?>
                      <tr>
                        
                        <td><?php echo $row[TDate]; ?></td>
                        <td><?php echo $row[VNumber]; ?></td>
                        <td><?php echo $row[ledger]; ?></td>
                        <td><?php echo $row[Note]; ?></td>
                        <td align="right"><?php if($row[debitamount]>0){ echo $debitamount; } else {} ?></td>
                        <td align="right"><?php if($row[creditamount]>0){ echo $creditamount; } else {} ?></td>
                        
                       
                        
                        
                          
                        </tr>
                        <?php } ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>              
              
            <?php  else:  ?>
            <?php endif; ?> 
</body>
</html>