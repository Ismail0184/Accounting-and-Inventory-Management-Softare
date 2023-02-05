<?php
require_once 'support_file.php';
$title='Sample & Gift List';
$table_master='requisition_sample_gift_master';
$table_details='requisition_sample_gift_details';
$unique='oi_no';
$page="acc_cv_sample_gift_view.php";
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$crud      =new crud($table);

if(isset($_POST[viewreport])):
$date_conn=' and r.oi_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'"';
$status_conn=' and r.status="COMPLETED"';
else:
$status_conn=' and r.status="APPROVED"';
endif;

if (isset($_POST[viewreport])) {
$sql="Select r.oi_no,r.oi_no as 'Req. No',r.oi_date as date,r.requisition_cetegory as type,concat(r.oi_subject,' ',r.sample_purpose) as 'Requisition Purpose',concat(p.PBI_NAME,' : ',r.entry_at) as 'Requisition By',concat((SELECT PBI_NAME from personnel_basic_info where PBI_ID=r.recommended_by),' : ',r.checked_at) as Checked_By,concat((SELECT PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person),' : ',r.authorized_date) as authorised_By,r.status from requisition_sample_gift_master r, personnel_basic_info p
where 
r.issued_to=p.PBI_ID and r.oi_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'";} else {
    $sql="Select r.oi_no,r.oi_no as 'Req. No',r.oi_date as date,r.requisition_cetegory as type,concat(r.oi_subject,' ',r.sample_purpose) as 'Requisition Purpose',concat(p.PBI_NAME,' : ',r.entry_at) as 'Requisition By',concat((SELECT PBI_NAME from personnel_basic_info where PBI_ID=r.recommended_by),' : ',r.checked_at) as Checked_By,concat((SELECT PBI_NAME from personnel_basic_info where PBI_ID=r.authorised_person),' : ',r.authorized_date) as authorised_By,r.status from requisition_sample_gift_master r, personnel_basic_info p
    where 
    r.issued_to=p.PBI_ID and r.status='Processing'";  
}
$master_data=find_all_field(''.$table_master.'','','oi_no='.$_GET[oi_no]);
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=500,left = 250,top = -1");}
    </script>
    <SCRIPT language=JavaScript>
        function reload(form)
        {var val=form.price_type.options[form.price_type.options.selectedIndex].value;
            self.location='<?=$page?>?oi_no=<?php echo $_GET[oi_no]; ?>&price_type=' + val ;}
    </script>
</head>
<?php require_once 'body_content.php'; ?>




<?php if($_GET[oi_no]): ?>
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
$sample_purpose=find_a_field('requisition_sample_gift_master','oi_subject','oi_no='.$_GET[oi_no]);
$req_by=find_a_field('user_activity_management','fname','user_id='.$master_data->entry_by);
$remarks=$sample_purpose. ',Requisition No: '.$_GET[oi_no].', Req. By # '.$req_by;

echo $remarks;
$rowSQL = mysqli_query($conn, "SELECT MAX(do_no) AS DONO FROM `sale_do_master`" );
$rowDO = mysqli_fetch_array( $rowSQL );
$LASTDO = $rowDO['DONO']+1;
$dodate= date('Y-m-d');
if($issuetype='sample'){
	$do_type='sample';
}
if($issuetype='gift'){
	$do_type='gift';
}



if(isset($_POST[invoice])){	
	mysqli_query($conn, "INSERT INTO sale_do_master (do_no,do_date,dealer_code,area_code,territory,region,delivery_address,remarks,status,depot_id,entry_by,do_type,section_id,company_id) VALUES ('$LASTDO','$dodate','94','49','4','17','ICP Head Office','$remarks','PROCESSING','12','$enby','$do_type','$_SESSION[sectionid]','$_SESSION[companyid]')");
	
	}

$res = mysqli_query($conn, "select * from requisition_sample_gift_details where oi_no='$_GET[oi_no]'");	
while($row=mysqli_fetch_array($res)){
	
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
		
    mysqli_query($conn, "INSERT INTO sale_do_details (do_no,item_id,dealer_code,area_code,territory,region,unit_price,pkt_size,dist_unit,total_unit,total_amt,depot_id,cogs_price,d_price,t_price,m_price,status,do_date,do_type,section_id,company_id) VALUES
 ('$LASTDO','$row[item_id]','94','49','4','17','$pricetype','$pksize','$row[qty]','$row[qty]','$tamount','12','$COGSPRICE','$DPPRICE','$TPPRICE','$MRPPRICE','PROCESSING','$dodate','$do_type','$_SESSION[sectionid]','$_SESSION[companyid]')");
	mysqli_query($conn, "update requisition_sample_gift_details set rate='$setprice'  where oi_no=".$_GET[$unique]);	
	mysqli_query($conn, "update requisition_sample_gift_master set status='COMPLETED', checked_by='".$_SESSION['userid']."' where oi_no=".$_GET[$unique]);
    echo "<script>window.close(); </script>";

    ?>

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
<?php $GET_status=find_a_field(''.$table_master.'','status',''.$unique.'='.$_GET[$unique]);?>
<?php if($GET_status=='Processing'){
?>
            <button type="submit" name="invoice" id="invoice"  class="btn btn-success" onclick='return window.confirm("Are you sure your want to confirm?");'>Checked & Create Invoice</button>
        <?php } ?>
        </td>
    </tr>
</table>

        </form>
        </div></div></div>



<?php else: ?>



<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View List</button></td>
            </tr></table> 
</form>
<?=$crud->report_templates_with_status($sql,$title);?>   
<?php endif;  ?>
<?=$html->footer_content();mysqli_close($conn);?>