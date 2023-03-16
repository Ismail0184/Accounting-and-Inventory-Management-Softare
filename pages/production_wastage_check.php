<?php
 require_once 'support_file.php'; 
 $title='Production Wastage Checked';
?>

<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]{
        font-size: 11px;}
</style>
<?php require_once 'body_content.php'; ?>
             

              

              

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                  <?php if($_GET[custom_pr_no]) { ?>
                   <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">PS NO</th>
                     <th style="width:8%">PS Date</th>
                     <th style="width:15%">CMU</th> 
                     <th style="width:5%">FG Code</th> 
                     <th style="width:20%">FG Description</th>
                     <th style="width:10%; text-align:center">Batch</th>                     
                     <th style="width:10%; text-align:center">STD. Qty</th>
                     <th style="width:10%; text-align:center">Wastage<br>Qty</th>
                     <th style="width:10%; text-align:center">Wastage (%)</th>
                     <th style="width:10%; text-align:center">Remarks</th>
                   
                     </tr>
                     </thead>





                      <tbody>






<?php
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
				$todaysss=$dateTime->format("d/m/Y  h:i A");
$resultss=mysql_query("Select * from production_westage_detail where status='UNCHECKED' and ref_no='$_GET[custom_pr_no]' order by pi_no DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;
	$enat=date('Y-m-d h:s:i');
	$entrydate=date('Y-m-d');
	$entryby=$_SESSION['userid'];
	$nows=time();
$psiz=getSVALUE("item_info", "pack_size", "where item_id='".$rows['item_id']."'");
if(isset($_POST[confirmandissue])){
	
mysql_query("INSERT INTO journal_item (ji_date,item_id,warehouse_id,item_ex,tr_from,custom_no,tr_no,sr_no,entry_by,entry_at,batch,consumption_for_fg,ip) VALUES ('$entrydate','$rows[item_id]','$rows[warehouse_from]','$rows[total_unit]','ProductionWastage','$_GET[custom_pr_no]','$rows[id]','$rows[pi_no]','$entryby','$enat','$rows[batch]','$rows[fg_for]','$ip')");
	
	
	mysql_query("Update production_westage_detail SET status='CHECKED' where  ref_no='$_GET[custom_pr_no]'");
	mysql_query("Update production_wastage_master SET status='CHECKED',checked_by='$entryby',checked_at='$todaysss' where  ref_no='$_GET[custom_pr_no]'"); ?>
    <meta http-equiv="refresh" content="0;production_wastage_check.php">
<?php } ?>



<?php 
if(isset($_POST[deleteandback])){
	
	mysql_query("Delete from production_westage_detail  where  ref_no='".$_GET[custom_pr_no]."'");
	mysql_query("Delete from production_wastage_master where ref_no='".$_GET[custom_pr_no]."'"); ?>
    <meta http-equiv="refresh" content="0;production_wastage_check.php">
<?php } ?>


                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><?php echo $rows[ref_no]; ?></a></td>
                        <td><?php echo $rows['date']; ?></a></td>
                      <td><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></td>
                        <td><?=$fgcode=getSVALUE("item_info", "finish_goods_code", "where item_id='".$rows['item_id']."'");?></td>
                        <td><?=$fgname=getSVALUE("item_info", "item_name", "where item_id='".$rows['item_id']."'");?></td>
                        <td style="text-align:right"><?=$rows[batch]?></td>
                        
                        <td style="text-align:right"><?=$cunqty=getSVALUE("production_floor_issue_detail", "SUM(total_unit)", "where batch_for='$rows[batch]' and item_id='".$rows['item_id']."'");?></td>
                        <td style="text-align:right"><?=$rows[total_unit]?></td>
                        <td style="text-align:right"><?=number_format(($rows[total_unit]/$cunqty)*100,2)?>%</td>
                        <td style="text-align:right"><?=$rows[remarks]?></td>
                       
                        </tr>
                        <?php } ?>
                       
                        <tr style="border:none">
                        
                        <td colspan="5" style="border:none; text-align:right">   <button type="submit" name="deleteandback" onclick='return window.confirm("Are you confirm to Delete?");' class="btn btn-success">Deleted</button></td>
                        
                        <td colspan="6" style="border:none">
                        <button type="submit" name="confirmandissue" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Production Wastage Issue Checked</button></td>
                        
                        </tr>
</tbody></table></form>
                  
                  <?php } else { ?>
                  
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">PS NO</th>
                     <th style="width:8%">PS Date</th>
                     <th style="width:15%">CMU</th> 
                     <th style="width:5%">Remarks</th> 
                     <th style="width:20%">Production By</th>
                     <th style="width:10%">Entry At</th>
                     </tr>
                     </thead>





                      <tbody>






<?php
$resultss=mysql_query("Select * from production_wastage_master where status='UNCHECKED' order by pi_no DESC ");
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;

$link='production_wastage_check.php?custom_pr_no='.$rows[ref_no];

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><a href="<?php echo $link; ?>" ><?php echo $i; ?></a></th>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[ref_no]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[date]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$companyname=getSVALUE("warehouse", "warehouse_name", "where warehouse_id='".$rows['warehouse_from']."'");?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?php echo $rows[remarks]; ?></a></td>
                        <td><a href="<?php echo $link; ?>" ><?=$fname=getSVALUE("users", "fname", "where user_id='".$rows['entry_by']."'");?></a></td>
                        <td style="text-align:right"><a href="<?php echo $link; ?>" ><?=$rows[entry_at]?></a></td>
                        </tr>
<?php } ?></tbody></table><?php } ?>

       </div></div></div></div></div></div>


<?php require_once 'footer_content.php' ?>

