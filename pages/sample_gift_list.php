<?php
require_once 'support_file.php';
$title='Sample & Gift List';
$table_master='requisition_sample_gift_master';
$table_details='requisition_sample_gift_details';
$unique='oi_no';
$page="sample_gift_list.php";
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 250,top = -1");}
    </script>
    <SCRIPT language=JavaScript>
        function reload(form)
        {var val=form.price_type.options[form.price_type.options.selectedIndex].value;
            self.location='sample_gift_list.php?oi_no=<?php echo $_GET[oi_no]; ?>&price_type=' + val ;}
    </script>
</head>
<?php require_once 'body_content.php'; ?>




<?php if($_GET[oi_no]){ ?>
<div class="col-md-6 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_content">

<form id="form2" name="form2" method="post" action="" class="form-horizontal form-label-left">
<table align="center" cellspacing="0" class="table table-striped table-bordered" style="font-size: 11px">
    <thead>
<tr>
<th style="text-align:center">SL</th>
<th style="text-align:center">code</th>
<th style="text-align:center">FG code</th> 
<th style="text-align:center">Finish good code</th> 
<th style="text-align:center">Qty</th> 
<th style="text-align:center">
    <select name="price_type" onchange="javascript:reload(this.form)" style="width:100px">
<option value="<?php echo $_GET[price_type]; ?> " selected="selected"><?php echo $_GET[price_type]; ?></option>
<option value="Select Price" <?php if(isset($_GET[price_type])) echo ''; else echo 'selected' ?>>Select Price</option>
<option value="Trade Price">Trade Price</option>
<option value="Dealer Price">Dealer Price</option>
<option value="MRP Price">MRP Price</option>
<option value="COGS Price">COGS Price</option>
<option value="Custome">Custome</option>
</select>
</th>
<th style="text-align:center">Amount</th>
</tr>
    </thead>
<tbody>

<? 
$enby=$_SESSION['userid'];
$sample_purpose=find_a_field('requisition_sample_gift_master','sample_purpose','oi_no='.$_GET[oi_no]);
$remarks=$sample_purpose. ',Requisition No: '.$_GET[oi_no];
$rowSQL = mysql_query( "SELECT MAX(do_no) AS DONO FROM `sale_do_master`" );
$rowDO = mysql_fetch_array( $rowSQL );
$LASTDO = $rowDO['DONO']+1;
$dodate= date('Y-m-d');
if($issuetype='sample'){
	$do_type='sample';
}
if($issuetype='gift'){
	$do_type='gift';
}



if(isset($_POST[invoice])){	
	mysql_query("INSERT INTO sale_do_master (do_no,do_date,dealer_code,area_code,territory,region,delivery_address,remarks,status,depot_id,entry_by,do_type,section_id,company_id) VALUES ('$LASTDO','$dodate','94','49','4','17','ICP Head Office','$remarks','PROCESSING','12','$enby','$do_type','$_SESSION[sectionid]','$_SESSION[companyid]')");
	
	}

$res = mysql_query("select * from requisition_sample_gift_details where oi_no='$_GET[oi_no]'");	
while($row=mysql_fetch_array($res)){
	
	if($_GET[price_type]=='Trade Price'){
	$pricetype=find_a_field('item_info','t_price','item_id='.$row[item_id]);
	} elseif ($_GET[price_type]=='MRP Price'){
	$pricetype=find_a_field('item_info','m_price','item_id='.$row[item_id]);
	} elseif ($_GET[price_type]=='Dealer Price'){
	$pricetype=find_a_field('item_info','d_price','item_id='.$row[item_id]);
	} elseif ($_GET[price_type]=='COGS Price'){
	$pricetype=find_a_field('item_info','production_cost','item_id='.$row[item_id]);
	}
	
	$pksize=find_a_field('item_info','pack_size','item_id='.$row[item_id]);
	$TPPRICE=find_a_field('item_info','t_price','item_id='.$row[item_id]);
	$DPPRICE=find_a_field('item_info','d_price','item_id='.$row[item_id]);
	$MRPPRICE=find_a_field('item_info','m_price','item_id='.$row[item_id]);
	$COGSPRICE=find_a_field('item_info','production_cost','item_id='.$row[item_id]);
	$tamount=$row[qty]*$pricetype;
	
	
	$i=$i+1;
	$id=$row[oi_no];
	$done=$_POST[invoice];
	$idrow=$row[id];
	$setprice=$_POST['setprice'.$idrow];
	if(isset($done)){
		
    mysql_query("INSERT INTO sale_do_details (do_no,item_id,dealer_code,area_code,territory,region,unit_price,pkt_size,dist_unit,total_unit,total_amt,depot_id,cogs_price,d_price,t_price,m_price,status,do_date,do_type,section_id,company_id) VALUES
 ('$LASTDO','$row[item_id]','94','49','4','17','$pricetype','$pksize','$row[qty]','$row[qty]','$tamount','12','$COGSPRICE','$DPPRICE','$TPPRICE','$MRPPRICE','PROCESSING','$dodate','$do_type','$_SESSION[sectionid]','$_SESSION[companyid]')");
	mysql_query("update requisition_sample_gift_details set rate='$setprice'  where oi_no='$id'");	
	mysql_query("update requisition_sample_gift_master set status='Processing', checked_by='".$_SESSION['userid']."' where oi_no='$id'");
	
		 ?>
    <meta http-equiv="refresh" content="0;sample_gift_list.php">	
	<?php } ?>
    
    

<tr>
<td style="vertical-align:middle; text-align:left"><?php echo $i; ?></td> 
<td style="vertical-align:middle; text-align:left"><?php echo $row[item_id] ?></td> 
<td style="vertical-align:middle; text-align:left"><?=find_a_field('item_info','finish_goods_code','item_id='.$row[item_id]);?></td>
<td style="vertical-align:middle; text-align:left"><?=find_a_field('item_info','item_name','item_id='.$row[item_id]);?></td> 
<td style="vertical-align:middle; text-align:left"><?php echo $row[qty]; ?></td>
<td align="center" style="text-align:center; vertical-align:middle;">
<input type="text" name="setprice<?php echo $row[id]; ?>" id="setprice<?php echo $row[id]; ?>" value="<?=$pricetype?>" required style="width:100px; text-align:right"  />
</td>
<td align="center" style="text-align:right; vertical-align:middle;">
    <? echo number_format(($toal=$row[qty]*$pricetype),2)?></td> </tr>
    <?php  $toals=$toals+$toal; } ?>
    <tr style="text-align:right; background-color:#FFF">
    <td colspan="6" style="text-align:right; font-weight:bold">Total Amount</td>
    <td><?php
  echo number_format($toals,2);
  ?></td>
    </tr>
</tbody>

<tr style="border:none">
<td colspan="4" style="text-align:center;border:none" align="center">
        <select  style="width: 200px; height: 25px" required name="warehouse_id" id="warehouse_id">
            <option value="0">select a warehouse</option>
            <? foreign_relation('warehouse','warehouse_id','warehouse_name',$warehouse_id);
            ?>
</select>
</td>
        <td colspan="3" style="text-align:center;border:none" align="center">
            <button type="submit" name="invoice" id="invoice"  class="btn btn-success" onclick='return window.confirm("Are you sure your want to confirm?");'>Checked & Create Invoice</button>
        </td>
    </tr>
</table>

        </form>
        </div></div></div>



<?php } else { ?>

<!-- input section-->
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
      <table align="center" cellspacing="0"  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
      <tbody>
      <tr>
      <th width="4%">SL</th>
      <th style="text-align:center;">Req. No</th>
      <th style="text-align:center;">Entry Date</th>
      <th style="text-align:center; ">Type</th>
      <th>Requisition Purpose</th>
      <th>Requisition By</th>
      <th>Check by</th>
      <th >Approved By</th>
      </tr>

	 
 <?php
$dotoday=date('Y-m-d');
$result=mysql_query("Select * from requisition_sample_gift_master where status='APPROVED'");
 while($row=mysql_fetch_array($result)){
	$i=$i+1;
	$warehouse_info=mysql_query("select * from dealer_info where dealer_code='$row[dealer_code]'");
	$wrow=mysql_fetch_array($warehouse_info);
	 $dealername = find_a_field('dealer_info','dealer_name_e','dealer_code='.$row[dealer_code]);
	 $dealerledgerid = find_a_field('accounts_ledger','ledger_id','ledger_id='.$wrow[account_code]);
	 $entryby = find_a_field('user_activity_management','fname','user_id='.$row[entry_by]); 
	 $doamount = find_a_field('sale_do_details','SUM(total_amt)','do_no='.$row[do_no]); 
	 $payablevendor=$dramount-$cramount;
	$username=mysql_query("Select * from user_activity_management where user_id='$row[entry_by]'");
	$userrow=mysql_fetch_array($username);
	
 ?>

<tr style="background-color:#FFF;cursor: pointer" onclick="DoNavPOPUP('<?=$row[oi_no];?>')">
<td align="center" style="width:2%"><?php echo $i; ?></td>
<td align="center"><?php echo $row[oi_no]; ?></td>
<td align="left" style=""><?php echo $row[oi_date]; ?></td>
<td align="left" style=""><?php echo $row[requisition_cetegory]; ?></td>
<td align="left" style=""><?php echo $row[sample_purpose]; ?></td>
<td align="left" style=""><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$row[issued_to]);?></td>
<td align="left" style="width:20%"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$row[recommended_by]);?></td>
<td align="left" style="width:25%"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$row[authorised_person]);?></td>
</tr>
<?php } ?>    
     
  
  

  </tbody></table>

  </form>
        </div>
    </div>
</div>
<?php } ?>




<?php require_once 'footer_content.php' ?>
