<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='do_no';
$unique_field='name';
$table="sale_return_master";
$table_deatils="sale_return_details";
$journal_item="journal_item";
$journal_accounts="journal";
$page='warehouse_goods_receive_from_sales_return.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
?>


<?php require_once 'header_content.php'; ?>

</head>
<?php 
 if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>
             

              

              

              

              

              
                  <?php if($_GET[sr_id]) { ?>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   
                  

  

<tr style="height:30px">
<th style="text-align:center; width:2%">S/N</th>
<th style="text-align:center">Code</th>
<th style="text-align:center">FG  Code</th>
<th style="text-align:center">Material Description</th>
<th style="text-align:center">Unit</th>
<th style="text-align:center">Return Qty</th>
<th style="text-align:center">Rcv. Qty</th>
</tr>

<?php
$dat=date('Y-m-d');
$time_now = date('Y-m-d H:s:i');
$enby=$_SESSION['userid'];

$custom_no=getSVALUE('sale_return_details','sr_no','where do_no="'.$_GET[sr_id].'"');
$resu=mysql_query("Select * from sale_return_details where do_no='$_GET[sr_id]'");

while($MANdetrow=mysql_fetch_array($resu)){
	$j=$j+1;
	
	$id=$MANdetrow['id'];
	
	$rcvqty=$_POST['rcvqty'.$MANdetrow[id]];
	
	 if(isset($_POST[confirmandverify])){
		 
		 if($rcvqty>0){
	  
 $item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, pre_stock, pre_price, item_in, item_ex, item_price, final_stock, tr_from,tr_no,sr_no,entry_by,entry_at,lot_number,return_from_dealder,ip,custom_no) VALUES 
('$dat','$MANdetrow[item_id]','$MANdetrow[depot_id]','0','','','$rcvqty','','$setprice','','SalesReturn','$MANdetrow[do_no]','$MANdetrow[id]','$enby','$time_now','','$MANdetrow[dealer_code]','$ip','$custom_no')");	  
	 
  }
  
  mysql_query("update sale_return_details set status='COMPLETED'  where do_no='$_GET[sr_id]'");	
	mysql_query("update sale_return_master set status='COMPLETED' where do_no='$_GET[sr_id]'"); ?>
    <meta http-equiv="refresh" content="0;sales_return_received.php">	
  
 <?php  } ?>
<tr style="background-color:#FFF">
<td style="width:2%; text-align:center"><?php echo $j; ?></td>
<td style="width:5%; text-align:center"><?php echo $MANdetrow[item_id]; ?></td>
<td style="width:10%; text-align:center"><?=$fg = getSVALUE('item_info','finish_goods_code','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="text-align:left"><?=$md = getSVALUE('item_info','item_name','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="width:5%; text-align:center"><?=$unit = getSVALUE('item_info','unit_name','where item_id='.$MANdetrow[item_id]); ?></td>
<td style="width:8%; text-align:right"><?php echo $MANdetrow[total_unit]; ?></td>
<td style="width:8%; text-align:right"><input type="text" style="width:100px; text-align:center" name="rcvqty<?=$id?>" id="rcvqty<?=$id?>" value="<?php echo $MANdetrow[total_unit]; ?>"></td>

</tr>
<?php } ?>


</table>




<table width="100%" style="border-collapse:collapse; margin-top:10px" cellspacing="0" cellpadding="5">
<tr style="border:none">
<td colspan="8" style="text-align:center; border:none"><input type="checkbox" required name="terms" style="float:none"> <font style="color:#000">I have checked the Document.</font></td></tr>

<?php 

$checked=$_POST[checked];
if(isset($checked)){
$del1=mysql_query("UPDATE  MAN_master set status='CHECKED',cehck_by='".$_SESSION['userid']."',cehck_at='$todaysss' where MAN_ID='$_GET[man_id]'");
$del1=mysql_query("UPDATE  MAN_details set status='CHECKED' where MAN_ID='$_GET[man_id]'"); ?>
<meta http-equiv="refresh" content="0;MAN_checked.php">	
	<?php } ?>  
    
    
<?php 
$Deletefinal=$_POST[Deletefinal];
if(isset($Deletefinal)){
$del1=mysql_query("Delete from MAN_master where MAN_ID='$_GET[man_id]'");
$del1=mysql_query("Delete from MAN_details where MAN_ID='$_GET[man_id]'"); 


unlink("51816/cmu_mod/page/dc_documents/".$_GET['man_id'].'_'.$row['delivary_challan'].'.pdf');
unlink("51816/cmu_mod/page/dc_documents/".$_GET['man_id'].'_'.$row['VAT_challan'].'.pdf');

?>
<meta http-equiv="refresh" content="0;MAN_checked.php">	
	<?php } ?>    

<tr style="border:none">
<td align="center" colspan="8" style="text-align:center; border:none">
<!---input name="Deletefinal" onclick="return confirmation();" type="submit" class="btn1" value="DELETE" style="width:100px;color:green; font-weight:bold; font-size:11px; height:30px; margin-left:30%" /--->

<input name="confirmandverify" onclick="return confirmation();" type="submit" class="btn1" value="Received" style="width:100px;color:green; margin-left:20px;font-weight:bold; font-size:11px;height:30px" /></td></tr>



</table></form></div></div></div>
                  
                  <?php } else { ?>
                  
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[f_date]) echo $_POST[f_date]; else echo date('Y-m-01');?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if($_POST[t_date]) echo $_POST[t_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Sales Return</button></td>
            </tr></table>
<?php 
if(isset($_POST[viewreport])){
$res="Select p.do_no,p.sr_no as SR_NO,DATE_FORMAT(p.do_date, '%d %M, %Y') as SR_date,w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as dealer_name,p.remarks,(SELECT COUNT(item_id) from ".$table_deatils." where ".$unique."=p.".$unique.") as nooffg,u.fname as entry_by,p.entry_at,p.status

from 
".$table." p,
warehouse w,
users u,
dealer_info d

 where
  p.entry_by=u.user_id and 
 w.warehouse_id=p.depot_id and  
 d.dealer_code=p.dealer_code and 
 p.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' 
 p.depot_id= =".$row[warehouse_id]." 
 order by p.".$unique." DESC ";
} else {
$res="Select p.do_no,p.sr_no as SR_NO,DATE_FORMAT(p.do_date, '%d %M, %Y') as SR_date,w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as dealer_name,p.remarks,(SELECT COUNT(item_id) from ".$table_deatils." where ".$unique."=p.".$unique.") as nooffg,u.fname as entry_by,p.entry_at,p.status
from 
".$table." p,
warehouse w,
users u,
dealer_info d

 where
  p.entry_by=u.user_id and 
 w.warehouse_id=p.depot_id and  
 d.dealer_code=p.dealer_code and 
 p.status in ('PROCESSING') and
 p.depot_id= =".$row[warehouse_id]." 
 order by p.".$unique." DESC ";	
}
echo $crud->report_templates_with_data($res,$title);?>
</form>
<?php } ?>                 
                 

<?php require_once 'footer_content.php' ?>
      