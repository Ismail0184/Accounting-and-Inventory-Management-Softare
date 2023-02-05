<?php
 require_once 'support_file.php'; 
 $title='Sales Return Report';
?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>



    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
    <table align="center" style="width: 50%;">
        <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date"  >
            <td style="width:10px; text-align:center"> -</td>
            <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date" ></td>
            <td style="padding:10px"><button type="submit" style="font-size: 11px;" name="viewreport"  class="btn btn-primary">View Sales Return</button></td>


        </tr></table>

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                   <thead>
                    <tr>
                     <th style="width:02%;vertical-align: middle">#</th>
                     <th style="width:04%;vertical-align: middle">SR NO</th>
                     <th style="width:07%;vertical-align: middle">Date</th>
                     <th style="width:12%;vertical-align: middle">Depot.</th>
                     <th style="width:15%;vertical-align: middle">Dealer</th>
                     <th style="width:05%;vertical-align: middle">FG Code</th>
                     <th>FG Description</th>
                     <th style="width:5%;vertical-align: middle">Unit</th>
                     <th style="width:5%;vertical-align: middle;text-align:left">Qty</th>
                     <th style="width:5%;vertical-align: middle;text-align:left">Free</th>
                     <th style="width:5%;vertical-align: middle;text-align:left">Price</th>
                     <th style="width:5%;vertical-align: middle;text-align:left">Amount</th>
                                    
                     </tr>
                     </thead>





                      <tbody>






<?php
$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));
if(isset($_POST[viewreport])){
$resultss=mysqli_query($conn, "Select m.do_date as DODATE,

m.do_no,
m.sr_no as SRNO,
m.dealer_code,
d.dealer_code as Dcode,
d.*,
i.*,
w.*,
di.dealer_name_e as dealer_name

from 
sale_return_master m,
sale_return_details d,
item_info i,
warehouse w,
dealer_info di

where 
m.do_no=d.do_no and
d.item_id=i.item_id and
di.dealer_code=m.dealer_code and 
w.warehouse_id=m.depot_id and 
m.do_date between '$from_date' and '$to_date' order by m.do_no DESC ");
}
while ($rows=mysqli_fetch_array($resultss)){
	$i=$i+1;

$link='min_print_view.php?fgid='.$rows[item_id].'&'.'custom_pr_no='.$rows[custom_pr_no].'&prno='.$rows[pr_no];

?>



                      <tr style="font-size:11px">
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><?=$rows[SRNO]; ?></td>
                        <td><?=$rows[DODATE];?></td>
                        <td><?=$rows[warehouse_name];?></td>
                        <td><?=$rows[dealer_name];?></td>
                        <td><?=$rows[finish_goods_code];?></td>
                        <td><?=$rows[item_name]; if($rows[total_amt]==0.00) echo '  <strong style="color:red">[Free]</strong>';?></td>
                        <td><?=$rows[unit_name];?></td>
                        <td style="text-align:right"><?=$rows[total_unit];?></td>
                        <td style="text-align:right"><?=$rows[free_qty];?></td>
                        <td style="text-align:right"><?=$rows[unit_price];?></td>
                        <td style="text-align:right"><?=$rows[total_amt];?></td>
                        
                        </tr>
<?php } ?></tbody></table>
                  </div></div></div></form>

<?php require_once 'footer_content.php' ?>