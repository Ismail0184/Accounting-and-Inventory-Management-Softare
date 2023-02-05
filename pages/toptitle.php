<style>

.c--anim-btn span {
 
  text-decoration: none;
  text-align: left;
  display: block;
  font-size:30px;
}

.c--anim-btn, .c-anim-btn {
  transition: 0.3s;     
}

.c--anim-btn {
  height: 50px;
  font: normal normal 700 1em/4em Arial,sans-serif;
  overflow: hidden;
  width: 200px;
  
}

.c-anim-btn{
  margin-top: 0em;   
}

.c--anim-btn:hover .c-anim-btn{
  margin-top: -1.2em;
}


</style>
<?php
 ob_start();
 session_start();
 require_once 'base.php';
 require_once 'module.php';
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['login_email']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $todays=date("Y-m-d");
 $res=mysql_query("SELECT * FROM company WHERE companyid=".$_SESSION['companyid']);
 $userRow=mysql_fetch_array($res);
?>

              <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i> Today Cash Collection</span>
               <?php
			   
			   
$tdate=date('d-m-y');
$fdate=date('d-m-y');
$j=0;

for($i=0;$i<strlen($fdate);$i++){
if(is_numeric($fdate[$i]))
$time1[$j]=$time1[$j].$fdate[$i]; 
else $j++;}



$fdate=mktime(0,0,-1,$time1[1],$time1[0],$time1[2]);
//tdate-------------------
$j=0;
for($i=0;$i<strlen($tdate);$i++)
{
if(is_numeric($tdate[$i]))
$time[$j]=$time[$j].$tdate[$i];
else $j++;
}

$tdate=mktime(23,59,59,$time[1],$time[0],$time[2]);
//comparisonF date 
$j=0;
for($i=0;$i<strlen($comparisonF);$i++)
{
if(is_numeric($comparisonF[$i]))
$time3[$j]=$time3[$j].$comparisonF[$i];
else $j++;}

$comparisonF=mktime(0,0,-1,$time3[1],$time3[0],$time3[2]);
$j=0;

for($i=0;$i<strlen($comparisonT);$i++){
if(is_numeric($comparisonT[$i]))
$time2[$j]=$time2[$j].$comparisonT[$i];
else $j++;}
$comparisonT=mktime(23,59,59,$time2[1],$time2[0],$time2[2]);



			   
$result=mysql_query("Select distinct account_code from dealer_info order by account_code");
while($row=mysql_fetch_array($result)){
	
	$warehouse_info=mysql_query("select * from dealer_info where account_code='$row[account_code]'");
	$wrow=mysql_fetch_array($warehouse_info);
	
	
	 $cramount=getSVALUE("journal", "SUM(cr_amt)", "where  jv_date between '".$fdate."' and '".$tdate."' and ledger_id='".$row[account_code]."'");
	
	  $dramount=getSVALUE("journal", "SUM(cr_amt)", "where  tr_from!='Sales' and  jv_date between '".$fdate."' and '".$tdate."' and ledger_id='".$row[account_code]."'");
	  
	 $receiableamount=$cramount-$dramount;
	 
	
	
////////////// total debit collection start form here
$debittotal=$debittotal+$dramount;
////////////// total credit collection start form here
$credittotal=$credittotal+$cramount;
////////////total collection code start from here
$receiableamounttotal=$receiableamounttotal+$receiableamount;
 }
 

?>  
			  
              
              
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="#"><font><?php if($receiableamounttotal>0){  echo $receiableamounttotal; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div>
              
              
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
            
            
            
            
            
            
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i> Today's Shipment</span>
			  <?php 
			  $pcashbalance=getSVALUE("transaction_cash", "SUM(Amount) as Amount", "where TDate='$todays' and ledger='Petty Cash' and companyid='$_SESSION[companyid]'");
			  ?><div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="accounts_market_due.php" style="text-decoration:none" ><font><?php if($pcashbalance>0){  echo $pcashbalance; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div>
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
            
            
            
            
            
            
            
            
            
            
            
            
             <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i>Today's Cash Received</span>
			  <?php 
			  $pcashbalance=getSVALUE("transaction_cash", "SUM(debitamount) as debitamount", "where TDate='$todays' and ledger='Main Cash' and companyid='$_SESSION[companyid]'");
			  ?>
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              <span style="font-size:20px"> <a href="reportview.php?reporttypes=ladger&ledgercode=10003&subledgercode=&datefrom=<?php echo date('Y-m-d'); ?>&dateto=<?php echo date('Y-m-d'); ?>&getstarted=" style="text-decoration:none" ><font><?php if($pcashbalance>0){  echo $pcashbalance; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div>
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
           
            
            
            
            
            
             <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i> Today's Expenses</span>
			  <?php $todaysexpenses=getSVALUE("transaction_cash", "SUM(Amount) as Amount", "where  maingroup='General Expenses' and TDate='$todays' and companyid='$_SESSION[companyid]'"); ?>
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              <span style="font-size:20px"> <a href="accounts_cash_expenses.php" style="text-decoration:none" ><font><?php if($todaysexpenses>0){  echo $todaysexpenses; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div>
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
            
            
                   
            
            
            
            
            
            
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i> Total Member</span>
               <?php 
			  $totalmember=getSVALUE("sales_customer", "COUNT(id) as id", "where companyid='$_SESSION[companyid]'");
			  ?>
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              <span style="font-size:20px"> <a href="memberlist.php" style="text-decoration:none" ><font><?php if($totalmember>0){  echo $totalmember; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div>
              <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i><?php echo $totalmember; ?>% </i> From Total</span>
              </div>
           
           
           
           
           
           
           
           
            <!--div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Collection</span>
             <?php 
			  $salsebalance=getSVALUE("transaction_cash", "SUM(creditamount) as creditamount", "where TDate='$todays' and ledger='Sales' and companyid='$_SESSION[companyid]'");
			  ?>
              <div class="count"><?php echo $total4; ?>00</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i><?php echo $tper6; ?></i> From Total this month</span>
            </div--->
            
            
            
            
            
            
            
            
            
           
            
            
            
            
            
            
            
            
            
            
            
           <br /><br /><br /><br /><br /><br /><br /><br /><br />
           
           <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i> Bank Balance</span>
  
  
  <?php $todaysexpenses=getSVALUE("transaction_cash", "SUM(Amount) as Amount", "where  subsidiary='Cash at Bank' and TDate='$todays' and companyid='$_SESSION[companyid]'"); ?>
                           
              
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="accounts_cash_expenses.php" style="text-decoration:none" ><font><?php if($todaysexpenses>0){  echo $todaysexpenses; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div>
              
              <span class="count_bottom"><i class="green"><?php echo date('Y-m-d'); ?> </i> to date</span>
              </div>
            
            
            
            
            
            
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i>Member Fee</span>
              
			  
			  <?php 
			  $membershipfee=getSVALUE("transaction_cash", "SUM(creditamount) as creditamount", "where TDate='$todays' and ledgercode='10014' and companyid='$_SESSION[companyid]'");
			  ?>
                           
              
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="reportview.php?reporttypes=ladger&ledgercode=10014&subledgercode=&datefrom=<?php echo date('Y-m-d'); ?>&dateto=<?php echo date('Y-m-d'); ?>&getstarted=" style="text-decoration:none;" ><font><?php if($membershipfee>0){  echo $membershipfee; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div> 
              
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
            
            
            
            
            
            
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-clock-o"></i> Monthly Installment</span>
             
			 <?php 
			  $monthlyinstallment=getSVALUE("transaction_cash", "SUM(creditamount) as creditamount", "where TDate='$todays' and ledgercode='10017' and companyid='$_SESSION[companyid]'");
			  ?>
               
              
             
             
            <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="reportview.php?reporttypes=ladger&ledgercode=10017&subledgercode=&datefrom=<?php echo date('Y-m-d'); ?>&dateto=<?php echo date('Y-m-d'); ?>&getstarted=" style="text-decoration:none;" ><font><?php if($monthlyinstallment>0){  echo $monthlyinstallment; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div> 
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i><?php echo $monthlyinstallment; ?>% </i> From Total</span>
            </div>
            
            
            
            
            
            
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i>Renew Fee</span>
              
			  
			  <?php 
			  $renewfee=getSVALUE("transaction_cash", "SUM(creditamount) as creditamount", "where TDate='$todays' and ledgercode='10022' and companyid='$_SESSION[companyid]'");
			  ?>
                           
              
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="reportview.php?reporttypes=ladger&ledgercode=10022&subledgercode=&datefrom=<?php echo date('Y-m-d'); ?>&dateto=<?php echo date('Y-m-d'); ?>&getstarted=" style="text-decoration:none;" ><font><?php if($renewfee>0){  echo $renewfee; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div> 
              
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
            
            
            
            
            
            
            
            
            
            
            
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i>Kollan Fund</span>
              
			  
			  <?php 
			  $kollanfund=getSVALUE("transaction_cash", "SUM(creditamount) as creditamount", "where TDate='$todays' and ledgercode='10019' and companyid='$_SESSION[companyid]'");
			  ?>
                           
              
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="reportview.php?reporttypes=ladger&ledgercode=10019&subledgercode=&datefrom=<?php echo date('Y-m-d'); ?>&dateto=<?php echo date('Y-m-d'); ?>&getstarted=" style="text-decoration:none;" ><font><?php if($kollanfund>0){  echo $kollanfund; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div> 
              
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
            
           
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
           <br /><br /><br /><br /><br /><br />
           
           <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="width:20%">
              <span class="count_top"><i class="fa fa-user"></i>Service Fee</span>
              
			  
			  <?php 
			  $servicefee=getSVALUE("transaction_cash", "SUM(creditamount) as creditamount", "where TDate='$todays' and ledgercode='10018' and companyid='$_SESSION[companyid]'");
			  ?>
                           
              
              <div class="c--anim-btn">
              <span class="c-anim-btn" style="font-size:40px">00 ৳</span>
              
              <span style="font-size:20px"> <a href="reportview.php?reporttypes=ladger&ledgercode=10018&subledgercode=&datefrom=<?php echo date('Y-m-d'); ?>&dateto=<?php echo date('Y-m-d'); ?>&getstarted=" style="text-decoration:none;" ><font><?php if($servicefee>0){  echo $servicefee; } else { echo "00";} ?> ৳</font>
              </a>
              </span>
              </div> 
              
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
              </div>
             
            
            
            
            
            
            
            
            
            
            
            
           
           
           
           
           
           
           
            <!--div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Collection</span>
             <?php 
			  $salsebalance=getSVALUE("transaction_cash", "SUM(creditamount) as creditamount", "where TDate='$todays' and ledger='Sales' and companyid='$_SESSION[companyid]'");
			  ?>
              <div class="count"><?php echo $total4; ?>00</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i><?php echo $tper6; ?></i> From Total this month</span>
            </div--->
            
            
            
            
            
            
            
            
            
            
            
            <?php ob_end_flush(); ?>