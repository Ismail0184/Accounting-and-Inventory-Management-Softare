<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Sales Return';
$now=time();
$unique='do_no';
$unique_field='sr_no';
$table="sale_return_master";
$table_deatils="sale_return_details";

$page="sales_return_all.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$create_date=date('Y-m-d');

if(prevent_multi_submit()){

    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION['initiate_sr_documents']=$_POST[sr_no];
        $_SESSION['sales_return_id'] =$_POST[$unique];
        $_POST['warehouse_to']=$_POST[warehouse_from];
        $_SESSION['production_warehouse'] =$_POST[warehouse_to];
        $_POST[create_date]=$create_date;
        $crud->insert();

        $type=1;
        unset($_POST);
        unset($$unique);
    }

//for modify PS information ...........................
    if(isset($_POST['modify']))
    {
        $d =$_POST[pr_date];
        $_POST[pr_date]=date('Y-m-d' , strtotime($d));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['total_qty']>0) {
        $_POST['pkt_size']=getSVALUE("item_info", "pack_size", " where item_id='$_POST[item_id]'");
        $_POST['pkt_unit']=$_POST['total_unit'];
        $_POST[status]="UNCHECKED";
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $crud = new crud($table_deatils);
        $crud->insert();
    }   unset($_POST);
    }



//for single FG Delete..................................
    $results="Select srd.*,i.* from ".$table_deatils." srd, item_info i  where
 srd.item_id=i.item_id and
 srd.do_no='$_SESSION[sales_return_id]' order by srd.id";
    $query=mysqli_query($conn, $results);
    while($row=mysqli_fetch_array($query)){
        $ids=$row[id];
        if(isset($_POST['deletedata'.$ids]))
        {
            $del="DELETE FROM ".$table_deatils." WHERE id='$ids' and ".$unique."=".$_SESSION['sales_return_id']."";
            $del_item=mysqli_query($conn, $del);
            unset($_POST);
        }}

//for Delete..................................
    if(isset($_POST['cancel']))
    {   $crud = new crud($table_deatils);
        $condition =$unique."=".$_SESSION['sales_return_id'];
        $crud->delete_all($condition);
        $crud = new crud($table);
        $condition=$unique."=".$_SESSION['sales_return_id'];
        $crud->delete($condition);
        unset($_SESSION['sales_return_id']);
        unset($_SESSION['initiate_sr_documents']);
        unset($_POST);
    }

    $COUNT_details_data=find_a_field(''.$table_deatils.'','Count(id)',''.$unique.'='.$_SESSION['sales_return_id'].'');







    if(isset($_POST['confirmsave']))
    {
        $up_master="UPDATE ".$table." SET status='UNCHECKED' where ".$unique."='$_SESSION[sales_return_id]'";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$production_table_issue_master." SET status='UNCHECKED' where ".$unique."='$_SESSION[sales_return_id]'";
        $update_table_details=mysqli_query($conn, $up_details);


        unset($_SESSION['sales_return_id']);
        unset($_SESSION['initiate_sr_documents']);
        unset($_POST);
    } // if insert posting
}

$results="Select srd.*,i.* from sale_return_details srd, item_info i  where
srd.item_id=i.item_id and
srd.do_no='$_SESSION[sales_return_id]' order by srd.id";

// data query..................................
if(isset($_SESSION['sales_return_id']))
{   $condition=$unique."=".$_SESSION['sales_return_id'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$batch_get=find_all_field('lc_lc_received_batch_split','','item_id="'.$_GET[item_id].'" and batch="'.$_GET[batch].'" and warehouse_id="'.$depot_id.'"');?>

<?php require_once 'header_content.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<script type="text/javascript">  
function reload(form){
var val=form.item_id.options[form.item_id.options.selectedIndex].value;
self.location='<?=$page;?>?item_id=' + val ;}
function reload_batch(form){
var val=form.batch.options[form.batch.options.selectedIndex].value;
self.location='<?=$page;?>?item_id=<?=$_GET['item_id']?>&batch=' + val ;}
</script>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php require_once 'body_content.php'; ?>



              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                      <a target="_new" style="float: right" class="btn btn-sm btn-default"  href="warehouse_sales_return_view.php">
                          <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Sales Return View</span>
                      </a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form  name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
                    <table style="width:100%">

                    <tr>
                     <th style="width:10%">SR NO</th>
                     <th style="width:1%; text-align:center">:</th>
                     <td style="width:20%; text-align:center">
                     <?
                        $sr_ids=find_a_field(''.$table.'','max('.$unique.')','1');
                        if($_SESSION['sales_return_id']>0) {
                            $sr_idGET = $_SESSION['sales_return_id'];
                        } else {
                            $sr_idGET=$sr_ids+1;
                            if($sr_ids<1) $sr_idGET = 1;
                        }
                        ?>
                        <input type="text" readonly style="width:25%" class="form-control col-md-7 col-xs-12" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$sr_idGET;?>">
                     <input type="text" id="sr_no"   required="required" style="width:65%" name="sr_no" value="<?=($_SESSION[initiate_sr_documents]!='')? $_SESSION[initiate_sr_documents] : automatic_number_generate("SR","sale_return_master","sr_no","inspection_date='".date('Y-m-d')."' and sr_no like '$sekeyword%'");?>" class="form-control col-md-7 col-xs-12" ></td>

                 <th style="width:10%">SR Date </th>
                        <th style="width:1%; text-align:center">:</th>
                        <td style="width:20%; text-align:center">
                        <input type="date" required="required" style="width:90%;font-size:11px" MAX="<?=date('Y-m-d')?>" name="do_date" value="<?=($do_date>0)?  $do_date : date('Y-m-d') ;?>" class="form-control col-md-7 col-xs-12" ></td>


                       <th style="width:5%">Type</th>
                        <th style="width:1%; text-align:center">:</th>
                        <td style="width:20%;">
                        <select class="select2_single form-control"  required style="width: 90%;" name="sr_type" id="sr_type">
                <option></option>
                <option value="saleable" <?php if($sr_type=='saleable') echo "selected"; ?>>Saleable</option>
                <option value="damage" <?php if($sr_type=='damage') echo "selected"; ?>>Damage</option>
                <option value="expired" <?php if($sr_type=='expired') echo "selected"; ?>>Expired</option>

            </select></td></tr>


                    <tr><td style="height:5px"></td></tr>
                    <tr>
                     <th style="width:10%"> Dealer / Customer</th>
                        <th style="width:1%; text-align:center">:</th>
                        <td style="width:20%;">
                        <select class="select2_single form-control"  required style="width: 90%;" name="dealer_code" id="dealer_code">
                        <option></option>
                        <?php foreign_relation('dealer_info', 'dealer_code', 'CONCAT(dealer_code," : ", dealer_name_e)', $dealer_code, '1');?>
                        </select></td>

                        <th style="width:10%">Received Destination</th>
                        <th style="width:1%; text-align:center">:</th>
                        <td style="width:20%;">
                        <select class="select2_single form-control"  required style="width: 90%;" name="depot_id" id="depot_id">
                        <option></option>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$depot_id);?>
                        </select>
                        </td>

                        <th>Remarks</th>
                        <th style="width:1%; text-align:center">:</th>
                        <td style="width:20%;">
                        <input type="text" id="last-name" name="remarks" style="width:90%" value="<?=$remarks;?>" class="form-control col-md-7 col-xs-12"></td>
                    </tr>









              <tr><td colspan="9"> <div class="form-group" style="margin-left:40%">

               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_sr_documents]){  ?>
               <button type="submit" style="font-size: 12px; margin-top:10px" name="modify" id="modify" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Update Return Info</button>
			 <?php   } else {?>
               <button type="submit" style="font-size: 12px; margin-top:10px" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Initiate Sales Return</button>
               <?php } ?>
               </div></div></td></tr></table></form>
                  </div></div></div>


<?php if($_SESSION[initiate_sr_documents]){  ?>

    <form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <? require_once 'support_html.php';?>
        <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$$unique;?>" >
        <input type="hidden" name="sr_no" id="sr_no" value="<?=$_SESSION[initiate_sr_documents];?>" >
        <input type="hidden" name="custom_pr_no" id="custom_pr_no" value="<?=$custom_pr_no;?>" >
        <input type="hidden" name="pr_date" id="pr_date" value="<?=$pr_date;?>">
        <input type="hidden" name="depot_id" id="depot_id" value="<?=$depot_id;?>">
        <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <tr style="background-color: bisque">
                <th style="text-align: center; vertical-align:middle">Finish Goods</th>
                <th style="text-align: center; vertical-align:middle">Batch</th>
                <th style="text-align: center; vertical-align:middle">Expiry Date</th>
                <th style="text-align: center; vertical-align:middle">Sales Qty</th>
                <th style="text-align: center; vertical-align:middle">Free Qty</th>
                <th style="text-align: center; vertical-align:middle">Discount</th>
                <th style="text-align: center; vertical-align:middle">Unit Price</th>
                <th style="text-align: center; vertical-align:middle">Total Qty</th>
                <th style="text-align: center; vertical-align:middle">Unit Amount</th>
                <th style="text-align: center; vertical-align:middle">Action</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td style="width:20%" align="center">
                    <select class="select2_single form-control" required name="item_id" id="item_id" style="width:99%;font-size: 11px" onchange="javascript:reload(this.form)">
                        <option></option>
                        <?=advance_foreign_relation(find_all_item($product_nature="'Salable','Both'"),$_GET[item_id]);?>
                    </select>
                </td>
                <td style="width:8%" align="center">
                       <select class="select2_single form-control" style="width: 99%" tabindex="-1" onchange="javascript:reload_batch(this.form)"  required="required" name="batch" id="batch" onchange="javascript:reload_batch(this.form)">
                       <option></option>
                       <?=foreign_relation('lc_lc_received_batch_split', 'batch', 'CONCAT(batch," : ", batch_no)', $_GET[batch], 'warehouse_id = "'.$depot_id.'" and item_id='.$_GET[item_id]);?>
                       </select>
                </td>
                <td style="width:8%" align="center">
                        <input align="center" type="date" id="expiry_date" style="width:100%; height:37px;   text-align:center;font-size:11px" value="<?=($_GET[batch]>0)? $batch_get->mfg : $expiry_date ?>" readonly  required   name="expiry_date"  class="form-control col-md-7 col-xs-12" >
                        <input type="hidden"  value="<?=($_GET[batch]>0)? $batch_get->rate : $edit_value; ?>" readonly  required   name="cogs_rate"  class="form-control col-md-7 col-xs-12" >

                </td>
                <td style="width:11%" align="center">
                    <input type="text" id="total_unit" style="width:100%; height:37px; font-weight:bold; text-align:center"  required="required"  name="total_unit" class="form-control col-md-7 col-xs-12" class='total_unit' autocomplete="off" >
                </td>
                <td align="center" style="width:8%">
                    <input type="text" id="free_qty" style="width:100%; height:37px; font-weight:bold; text-align:center"  name="free_qty"  class="form-control col-md-7 col-xs-12" class='free_qty' autocomplete="off" >
                </td>
                <td align="center" style="width:8%">
                    <input type="text" id="discount" style="width:100%; height:37px; font-weight:bold; text-align:center"  name="discount"  class="form-control col-md-7 col-xs-12" class='discount' autocomplete="off" >
                </td>
                <td style="width:8%" align="center">
                    <input align="center" type="text" id="unit_price" style="width:100%; height:37px;   text-align:center"  required   name="unit_price"  class="form-control col-md-7 col-xs-12" class='unit_price' >
                </td>
                <td style="width:8%" align="center">
                    <input align="center" type="text" id="total_qty" style="width:100%; height:37px;   text-align:center"  readonly   name="total_qty" class="form-control col-md-7 col-xs-12"  class='total_qty' >
                </td>
                <td style="width:10%" align="center">
                    <input type="text" id="total_amt" style="width:100%; height:37px; font-weight:bold; text-align:center" readonly  name="total_amt" class="form-control col-md-7 col-xs-12" autocomplete="off" class='total_amt' ></td>
                <td align="center" style="width:5%">
                <?php if($_GET['batch']>0): ?><button type="submit" class="btn btn-primary" style="font-size: 12px;" name="add" id="add">Add</button><?php else: echo '<strong style="color:red">Select Batch</strong>'; endif; ?></td></tr>
            </tbody>
        </table>
    </form>

<form id="ismail" name="ismail"  method="post" style="font-size: 11px"  class="form-horizontal form-label-left">
    <? require_once 'support_html.php';?>
    <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$$unique;?>" >
    <input type="hidden" name="custom_pr_no" id="custom_pr_no" value="<?=$custom_pr_no;?>" >
    <input type="hidden" name="pr_date" id="pr_date" value="<?=$pr_date;?>">
    <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
    <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
    <input type="hidden" name="lot" id="lot" value="<?=$lot;?>">

    <?php if($COUNT_details_data>0) { ?>
    <table align="center" class="table table-striped table-bordered" style="width:98%">
        <thead>
        <tr style="background-color: bisque">
            <th style="vertical-align:middle">SL</th>
            <th style="vertical-align:middle">Code</th>
            <th style="vertical-align:middle">Finish Goods</th>
            <th style="width:5%; text-align:center; vertical-align:middle">UOM</th>
            <th style="text-align:center; vertical-align:middle">Sales Qty</th>
            <th style="text-align:center; vertical-align:middle">Free Qty</th>
            <th style="text-align:center; vertical-align:middle">Discount</th>
            <th style="text-align:center; vertical-align:middle">Unit Price</th>
            <th style="text-align:center; vertical-align:middle">Total Qty</th>
            <th style="text-align:center; vertical-align:middle">Unit Amount</th>
            <th style="text-align:center; vertical-align:middle">Batch</th>
            <th style="text-align:center; vertical-align:middle">Expiry Date</th>
            <th style="text-align:center; vertical-align:middle">COGS Rate</th>
            <th style="text-align:center; vertical-align:middle">Action</th>
        </tr>
        </thead>
        <tbody>


        <?php
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)){
            $i=$i+1;
            $ids=$row[id];
            ?>
            <tr>
                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?></td>
                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                <td align="center" style=" text-align:center; vertical-align:middle"><?=$row[total_unit];?></td>
                <td align="center" style=" text-align:center; vertical-align:middle"><?=$row[free_qty];?></td>
                <td align="center" style=" text-align:right; vertical-align:middle"><?=$row[discount];?></td>
                <td align="center" style=" text-align:right; vertical-align:middle"><?=$row[unit_price]; ?></td>
                <td align="center" style=" text-align:center; vertical-align:middle"><?=$row[total_qty]; ?></td>
                <td align="center" style="text-align:right; vertical-align:middle"><?=number_format($row[total_amt],2);?></td>
                <td align="center" style=" text-align:center;vertical-align:middle"><?=$row[batch]; ?></td>
                <td align="center" style=" text-align:center;vertical-align:middle"><?=$row[expiry_date]; ?></td>
                <td align="center" style=" text-align:center;vertical-align:middle"><?=$row[cogs_rate]; ?></td>
                <td align="center" style="vertical-align:middle; vertical-align:middle">
                    <button type="submit" name="deletedata<?=$ids;?>" id="deletedata<?=$ids;?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="/../assets/images/delete.png" style="width:15px;  height:15px"></button>
                </td>
            </tr>
            <?php  $ttotal_unit=$ttotal_unit+$row[total_unit];
                   $tfree_qty=$tfree_qty+$row[free_qty];
                   $ttotal_qty=$ttotal_qty+$row[total_qty];
                   $tdiscount=$tdiscount+$row[discount];
                   $ttotal_amt=$ttotal_amt+$row[total_amt];  } ?>
        </tbody>
        <tr style="font-weight: bold">
            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Sales Return</td>
            <td style="text-align:center"><?=$ttotal_unit;?></td>
            <td style="text-align:center"><?=$tfree_qty;?></td>
            <td style="text-align:right"><?=number_format($tdiscount,2);?></td>
            <td align="center" ></td>
            <td align="center" ><?=$ttotal_qty;?></td>
            <td align="right" ><?=number_format($ttotal_amt,2);?></td>
            <td align="center" ></td>
        </tr>
    </table>
    <?php } ?>

    <button type="submit" style="float: left; font-size: 12px; margin-left: 1%" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to the Production Deleted?");' class="btn btn-danger">Delete Sales Return </button>
    <?php if($COUNT_details_data>0) { ?>
        <button type="submit" style="float: right; margin-right: 1%; font-size: 12px" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Finish Sales Return </button>
    <?php } ?>
    </form>
<?php } ?>





    <script>
        $(function(){
            $('#total_unit, #free_qty').keyup(function(){
                var total_unit = parseFloat($('#total_unit').val()) || 0;
                var free_qty = parseFloat($('#free_qty').val()) || 0;
                $('#total_qty').val((total_unit + free_qty).toFixed(2));
            });
        });
    </script>



    <script>
        $(function(){
            $('#total_unit, #unit_price').keyup(function(){
                var total_unit = parseFloat($('#total_unit').val()) || 0;
                var unit_price = parseFloat($('#unit_price').val()) || 0;
                var discount = parseFloat($('#discount').val()) || 0;
                $('#total_amt').val((total_unit * unit_price) - (discount).toFixed(2));
            });
        });
    </script>
    
<?=$html->footer_content();?>