<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Special Invoice';
$unique='do_no';
$unique_field='do_date';
$table_master="sale_do_master";
$table_details="sale_do_details";
$page="acc_sales_special_invoice.php";
$crud      =new crud($table_master);
$$unique = $_GET[$unique];

if(prevent_multi_submit()) {
  if (isset($_POST['returned'])) {
      $_POST['returned_by']=$_SESSION[userid];
      $_POST['returned_at']=date('Y-m-d H:i:s');
      $_POST['status']="RETURNED";
      $crud->update($unique);
      unset($_POST);
      $type = 1;
      echo "<script>window.close(); </script>";
  }

    if (isset($_POST['confirmsave'])) {
      $ress="SELECT d.id,i.item_id,i.finish_goods_code,i.item_name,i.unit_name,i.pack_size,i.d_price,d.total_unit,d.unit_price,d.total_amt from item_info i, ".$table_details." d where d.item_id=i.item_id and do_no=".$_GET[$unique]."";
        $re_query = mysqli_query($conn, $ress);
        while ($data = mysqli_fetch_object($re_query)) {
        mysqli_query($conn, "UPDATE ".$table_details." SET unit_price='".$_POST['unit_price'.$data->id]."',total_amt='".$_POST['total_amt'.$data->id]."' WHERE id=".$data->id);
        }
        $up_payment=mysqli_query($conn, "UPDATE ".$table_master." SET status='PROCESSING' where ".$unique."=".$$unique."");
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }// if insert confirm
}

$ress="SELECT d.id,i.item_id,i.finish_goods_code,i.item_name,i.unit_name,i.pack_size,i.d_price,d.total_unit,d.unit_price,d.total_amt from item_info i, ".$table_details." d where d.item_id=i.item_id and do_no=".$_GET[$unique]."";


  if(isset($_POST[viewreport])){
    $res="SELECT m.do_no,m.do_no,m.do_date,m.remarks,d.dealer_name_e as dealer_name,w.warehouse_name,concat(u.fname,'<br>','at: ',m.entry_at) as entry_by,m.status from ".$table_master." m, dealer_info d,users u, warehouse w
    where m.entry_by=u.user_id and m.dealer_code=d.dealer_code and do_section='Special_invoice' and m.depot_id=w.warehouse_id and m.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'";
  } else {
      $res="SELECT m.do_no,m.do_no,m.do_date,m.remarks,d.dealer_name_e as dealer_name,w.warehouse_name,concat(u.fname,'<br>','at: ',m.entry_at) as entry_by,m.status from ".$table_master." m, dealer_info d,users u,warehouse w
      where m.entry_by=u.user_id and m.dealer_code=d.dealer_code and m.status='UNCHECKED' and do_section='Special_invoice' and m.depot_id=w.warehouse_id";}
?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 230,top = -1");}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php if(isset($_GET[$unique])){ require_once 'body_content_without_menu.php'; } else { require_once 'body_content.php';} ?>

<?php if(isset($_GET[$unique])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="width: 12%">Item Code</th>
                            <th>Item Description</th>
                            <th style="text-align:center; width: 5%">UoM</th>
                            <th style="text-align:center; width: 10%">Pack Size</th>
                            <th style="text-align:center; width: 10%">D Price</th>
                            <th style="text-align:center; width: 10%">Unit Price</th>
                            <th style="text-align:center; width: 10%">Qty</th>
                            <th style="text-align:center; width: 10%">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query=mysqli_query($conn, $ress);
                        while($data=mysqli_fetch_object($query)): ?>
                        <tr>
                            <td style="vertical-align: middle"><?=$sl=$sl+1?></td>
                            <td style="vertical-align: middle"><?=$data->finish_goods_code?></td>
                            <td style="vertical-align: middle"><?=$data->item_name?></td>
                            <td style="vertical-align: middle"><?=$data->unit_name?></td>
                            <td style="vertical-align: middle; text-align:center"><?=$data->pack_size?></td>
                            <td style="vertical-align: middle; text-align: right"><?=$data->d_price?></td>
                            <td style="vertical-align: middle; text-align: right">
                            <input type="text" id="unit_price<?=$data->id?>" style="width:99%; height:25px; font-size:11px;text-align:center"  required="required" value="<?=$data->unit_price?>" name="unit_price<?=$data->id?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" class="unit_price<?=$data->id?>" ></td>
                            <td style="vertical-align: middle; text-align: right">
                            <input type="text" id="dist_unit<?=$data->id?>" style="width:99%; height:25px; font-size:11px;text-align:center" readonly  required="required" value="<?=$data->total_unit?>" name="dist_unit<?=$data->id?>"  class="form-control col-md-7 col-xs-12"  class="dist_unit<?=$data->id?>" ></td>
                            <td style="vertical-align: middle; text-align: right">
                            <input type="text" id="total_amt<?=$data->id?>" readonly style="width:99%; height:25px; font-size:11px;text-align:center"  required="required" value="<?=$data->total_amt?>" name="total_amt<?=$data->id?>"  class="form-control col-md-7 col-xs-12" class="total_amt<?=$data->id?>" ></td>
                        </tr>
                        <script>
                            $(function(){
                                $('#unit_price<?=$data->id?>').keyup(function(){
                                    var unit_price<?=$data->id?> = parseFloat($('#unit_price<?=$data->id?>').val()) || 0;
                                    var dist_unit<?=$data->id?> = parseFloat($('#dist_unit<?=$data->id?>').val()) || 0;
                                    $('#total_amt<?=$data->id?>').val((unit_price<?=$data->id?> * dist_unit<?=$data->id?>));
                                });
                            });
                        </script>
                        <?php $total_total_amt=$total_total_amt+$data->total_amt; endwhile; ?>
                        <tr>
                            <th style="vertical-align: middle" colspan="8">Total</th>
                            <th style="vertical-align: middle; text-align: right"><?=number_format($total_total_amt,2)?></th>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                    $GET_status=find_a_field(''.$table_master.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){  ?>
                        <p>
                          <button style="float: left; margin-left:1%; font-size:12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Returned</button>
                          <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Please drop a note for the return" class="form-control col-md-7 col-xs-12" >

                            <button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="confirmsave" id="confirmsave" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Check and Confirm</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Voucher has been Checked & Confirmed !!</i></h6>'; ?>
                        <?php  }?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>


<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[f_date]) echo $_POST[f_date]; else echo date('Y-m-01');?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if($_POST[t_date]) echo $_POST[t_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Record</button></td>
            </tr></table>
    </form>

<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
