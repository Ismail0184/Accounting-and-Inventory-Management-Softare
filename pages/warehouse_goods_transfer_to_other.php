<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Goods Transfer (Other)';
$sectionid_substr = @(substr($_SESSION['sectionid'],4));
$now=time();
$unique='uid';
$table="warehouse_goods_transfer_to_other_master";
$table_details="warehouse_goods_transfer_to_other_details";
$page="warehouse_goods_transfer_to_other.php";
$crud      =new crud($table);
$unique_GET = @$_GET[$unique];
$create_date=date('Y-m-d');

if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION['uniqueid']=$_POST['uid'];
        $_SESSION['status']='MANUAL';
        $_POST['type'] = 'SEND';
        $_POST['create_date']=$create_date;
        $crud->insert();
        $type=1;
        unset($_POST);
        unset($$unique);
    }

//for modify PS information ...........................
    if(isset($_POST['modify']))
    {   $d =$_POST['pi_date'];
        $_POST['pi_date']=date('Y-m-d' , strtotime($d));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add'])) {
        $_POST['status']="UNCHECKED";
        $_POST['total_unit']=$_POST['total_unit'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $sql = mysqli_query($conn, "SELECT * from item_info where 1 order by serial");
        while($data=mysqli_fetch_object($sql)):
            $item_id = $data->item_id;
            $_POST['item_id'] = $data->item_id;
            $_POST['unit_name'] = $data->unit_name;
            $_POST['unit_price'] = @$_POST['unit_price'.$item_id];
            $_POST['total_unit'] = @$_POST['total_unit'.$item_id];
            $_POST['total_amt'] = $_POST['unit_price']* $_POST['total_unit'];
        if($_POST['unit_price']>0 && $_POST['total_unit']>0 && $_POST['total_amt']):
            $crud = new crud($table_details);
        $crud->insert();
        endif;
        endwhile;
    }
} /// prevent multi submit

$unique_GET = @$_SESSION['uniqueid'];



if(isset($_POST['confirm']))
{   $up="UPDATE ".$table." SET status='UNCHECKED' where ".$unique."='".$_SESSION['uniqueid']."'";
    $update_table_master=mysqli_query($conn, $up);
    unset($_SESSION['uniqueid']);
    unset($uniqueid);
    unset($_POST);
}

//for single FG Delete..................................
$pi_tr = @$_SESSION['uniqueid'];
$initiate_production_transfer = @$_SESSION['uniqueid'];
$query="Select * from ".$table_details." where ".$unique."='".$pi_tr."'";
$res=mysqli_query($conn, $query);
while($row=mysqli_fetch_array($res)){
    $ids=$row[id];
    if(isset($_POST['deletedata'.$ids]))
    {
        $del="DELETE FROM ".$table_details." WHERE id='$ids' and ".$unique."='".$pi_tr."'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);
    }}

//for Delete..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table_details);
    $condition =$unique."=".$_SESSION['uniqueid'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['uniqueid'];
    $crud->delete($condition);
    unset($_SESSION['uniqueid']);
    unset($uniqueid);
    unset($_POST);
}
$GET_id = @$_GET['id'];
$uniqueid = @$_SESSION['uniqueid'];

if (isset($GET_id)) {$edit_value=find_all_field(''.$table_details.'','','id='.$GET_id.'');}
$edit_value_item_id = @$edit_value->item_id;
$edit_value_total_unit = @$edit_value->total_unit;
$edit_value_amount = @$edit_value->amount;
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$pi_tr.'');

if(isset($pi_tr))
{   $condition=$unique."=".$uniqueid;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$custom_no = @$custom_no;
$ogt_date = @$ogt_date;
$remarks = @$remarks;
$warehouse_id = @$warehouse_id;
$warehouse_to = @$warehouse_to;
$VATChallanno = @$VATChallanno;

$stock_balance_single=find_a_field("journal_item", "SUM(item_in-item_ex)", "item_id='".$edit_value_item_id."' and warehouse_id=".$warehouse_id." and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");

$res="Select t.id,concat(i.item_id,' : ', ' : ', i.item_name) as item_description,i.unit_name as unit,t.total_unit 
from 
".$table_details." t,item_info i 
where   
t.".$unique."=".$pi_tr." and t.item_id=i.item_id";
$query=mysqli_query($conn, $res);
while($data=@mysqli_fetch_object($query)){
  if(isset($_POST['deletedata'.$data->id]))
  {  mysqli_query($conn, ("DELETE FROM ".$table_details." WHERE id=".$data->id));
     mysqli_query($conn, ("DELETE FROM ".$table_details." WHERE gift_on_order=".$data->id));
      unset($_POST);}
  if(isset($_POST['editdata'.$data->id]))
  {   mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST['item_id']."', total_unit='".$_POST['total_unit']."' WHERE id=".$data->id));
      unset($_POST);
    }}
?>
<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
    function reloaditem(form)
    {   var val=form.item_id.options[form.item_id.options.selectedIndex].value;
        self.location='<?=$page;?>?item_id=' + val;
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<style>
    input[type=text]{
        font-size: 11px;
    }
    input[type=date]{
        font-size: 11px;
    }
    #customers {}
    #customers td {}
    #customers tr:ntd-child(even)
    {background-color: #f0f0f0;}
    #customers tr:hover {background-color: #7FFFD4;}
    td{}
</style>
<SCRIPT language=JavaScript>
    function doAlert(form)
    {
        var val=form.total_unit.value;
        var val2=form.stock_balance.value;
        if (Number(val)>Number(val2)){
            alert('oops!! Exceed Stock Balance!! Thanks');
            form.total_unit.value='';
        }
        form.total_unit.focus();
    }
</script>

<?php require_once 'body_content_nva_sm.php';
$pi_nos = find_a_field('' . $table . '', 'max(' . $unique . ')', '1');
$create_date = date('Y-m-d');
if ($pi_tr > 0) {
    $pi_noGET = @$_SESSION['uniqueid'];
} else {
    $pi_noGET = $pi_nos + 1;
    if ($pi_nos < 1) $pi_noGET = 1;
} ?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?> <small class="text-danger">field marked with * are mandatory</small></h2>
                    <a target="_new" style="float: right" class="btn btn-sm btn-default"  href="warehouse_add_corporate_party.php">
                        <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Add Party</span>
                    </a>
            <a target="_new" style="float: right" class="btn btn-sm btn-default"  href="warehouse_transfer_other_view.php">
                <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">View Report</span>
            </a>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?=$page?>" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <table style="width:100%">
                    <tr>
                        <th style="width: 10%">Ref. No <span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$pi_noGET;?>">
                            <input type="text" id="custom_id" style="width:90%" readonly name="custom_id" value="<?=($uniqueid!='')? $custom_id : automatic_voucher_number_generate($table,'custom_id',1,'1'.$sectionid_substr);?>" class="form-control col-md-7 col-xs-12">
                        </td>
                        <th style="width: 10%">Date <span class="required text-danger">*</span></th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <input type="date" id="ogt_date" style="width:90%" max="<?=date('Y-m-d')?>"  required="required" name="ogt_date" value="<?=($ogt_date!='')? $ogt_date : date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12" >
                        </td>
                        <th style="width: 10%">Remarks</th>
                        <th style="width:2%; text-align:center">:</th>
                        <td style="width: 20%">
                            <textarea id="remarks" style="width:90%; font-size: 11px" name="remarks" class="form-control col-md-7 col-xs-12" ><?=$remarks;?></textarea>
                        </td>
                    </tr>


                    <tr><td style="height: 5px"></td></tr>
                    <tr>
                        <th>Transfer From <span class="required text-danger">*</span></th>
                        <th style="text-align:center">:</th>
                        <td>
                            <select class="form-control" style="width:90%; font-size: 11px" tabindex="-1" required="required"  name="warehouse_id" id="warehouse_id">
                                <?php if(isset($uniqueid)>0): ?>
                                    <option value="<?=$warehouse_id?>" selected><?=$warehouse_id?> : <?=find_a_field('warehouse','warehouse_name','warehouse_id='.$warehouse_id)?></option>
                                <?php else: ?>
                                    <?=foreign_relation("warehouse","warehouse_id","concat(warehouse_id,' : ',warehouse_name)",$warehouse_id, "warehouse_id in ('".$_SESSION['warehouse']."')");?>
                                <?php endif; ?>
                            </select>
                        </td>
                        <th>Transfer to <span class="required text-danger">*</span></th>
                        <th style="text-align:center">:</th>
                        <td>
                            <select class="form-control" style="width:90%; font-size: 11px" tabindex="-1" required="required"  name="dealer_code" id="dealer_code">
                                <option></option>
                                <?php if(isset($uniqueid)>0): ?>
                                    <option value="<?=$dealer_code?>" selected><?=$dealer_code?> : <?=find_a_field('corporate_dealer_info','dealer_name','dealer_code='.$dealer_code)?></option>
                                <?php else: ?>
                                    <?php foreign_relation("corporate_dealer_info", "dealer_code", "CONCAT(dealer_code,' : ', dealer_name)", $dealer_code, "status='yes'".$sec_com_connection_wa."","order by dealer_code"); ?>
                                <?php endif; ?>
                            </select>
                        </td>

                        <th>VAT Challan</th>
                        <th style="text-align:center">:</th>
                        <td>
                            <input type="text" id="VATChallanno" style="width:90%"  name="VATChallanno" value="<?=$VATChallanno;?>" class="form-control col-md-7 col-xs-12"  Placeholder="Challan & Date" >
                        </td>
                    </tr>

                    <tr><td style="height: 15px"></td></tr>
                    <tr>
                        <td colspan="9" style="text-align: center">
                                    <?php if($uniqueid){  ?>
                                        <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Update Information</button>
                                    <?php   } else {?>
                                        <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Initiate Transfer Entry</button>
                                    <?php } ?>
                        </td>
                        </tr>
                </table>
            </form>
        </div>
    </div>
</div>



<?php if($uniqueid):?>
    <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
        <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$_SESSION['uniqueid'];?>" >
        <input type="hidden" name="ogt_date" id="ogt_date" value="<?=$ogt_date;?>">
        <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse_id;?>">
        <input type="hidden" name="section_id" id="section_id" value="<?=$_SESSION['sectionid'];?>">
        <input type="hidden" name="company_id" id="company_id" value="<?=$_SESSION['companyid'];?>">

        <?php if($GET_id>0): ?>
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <tr style="background-color: bisque">
                <th style="text-align: center">Search Items</th>
                <th style="text-align: center">Unit</th>
                <th style="text-align: center">Stock in Pcs</th>
                <th style="text-align: center">Qty in Pcs</th>
                <th style="text-align: center">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="vertical-align: middle">
                    <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id" onchange="javascript:reloaditem(this.form)">
                        <option></option>
                        <? advance_foreign_relation(find_all_item($product_nature="'Salable','Both'"),($_GET['item_id']>0)? $_GET['item_id'] : $edit_value_item_id);?>
                    </select>
                </td>
                <td style="vertical-align: middle;width:10%; text-align:center">
                    <input type="text" id="unit" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="unit" readonly  class="form-control col-md-7 col-xs-12" value="<?=$item_info->unit_name;?>" tabindex="-1" >
                </td>

                <td style="vertical-align: middle;width:10%; text-align:center">
                    <input type="text" id="stock_balance" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="stock_balance" readonly  class="form-control col-md-7 col-xs-12" value="<?=$stock_balance_single;?>" tabindex="-1" />
                </td>

                <td style="vertical-align: middle;width:10%; text-align:center">
                    <input type="text" id="total_unit" onkeyup="doAlert(this.form);" name="total_unit" value="<?=$edit_value_total_unit?>" style="width:100%; height:37px;text-align:center"  required="required"  class="form-control col-md-7 col-xs-12" autocomplete="off" tabindex="1" /></td>

                <td style="vertical-align: middle;width:5%; text-align:center">
                    <?php if (isset($GET_id)) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$GET_id;?>" id="editdata<?=$GET_id;?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                    <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>
            </tr>
            </tbody>
        </table>
        <?php else:  ?>
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered" id="customers">
            <thead>
            <tr style="background-color: #3caae4; color:white">
                <th style="width: 2%">#</th>
                <th style="text-align: center">Item Description</th>
                <th style="text-align: center">Unit</th>
                <th style="text-align: center">Stock Balance</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: center; display: none">Rate</th>
                <th style="text-align: center; display: none">Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $i = 0;
                $sql = mysqli_query($conn, "SELECT * from item_info where d_price>0 order by serial");
                while($data=mysqli_fetch_object($sql)):
                $item_id = @$data->item_id;
                $present_stock_sql=find_a_field("journal_item", "SUM(item_in-item_ex)", "item_id='".$data->item_id."' and warehouse_id=".$warehouse_id." and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");;
                    $stock_balance_GET=$present_stock_sql;
                    $Manual_item=find_a_field("warehouse_goods_transfer_to_other_details", "SUM(total_unit)", "item_id='".$data->item_id."' and warehouse_from=".$warehouse_from." and status in ('MANUAL','UNCHECKED') and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
                    $stock_balance=$stock_balance_GET-$Manual_item;
                ?>
                    <script>
                        function doAlert<?=$item_id?>(form)
                        {
                            var val=form.total_unit<?=$item_id?>.value;
                            var val2=form.stock_balance<?=$item_id?>.value;
                            if (Number(val)>Number(val2)){
                                alert('oops!! Exceed Stock Balance!! Thanks');
                                form.total_unit<?=$item_id?>.value='';
                            }
                            form.total_unit<?=$item_id?>.focus();
                        }
                    </script>
            <tr>

                <td style="vertical-align: middle"><?=$i=$i+1;?></td>
                <td style="vertical-align: middle"><?=$data->item_id?> - <?=$data->item_name?></td>
                <td style="vertical-align: middle;width:10%; text-align:center"><?=$data->unit_name?></td>
                <td style="vertical-align: middle;width:10%; text-align:center">
                    <input type="text" id="stock_balance<?=$item_id?>" style="width:96%; margin-left:2%; height:25px;font-size: 11px; text-align:center;"   name="stock_balance<?=$item_id?>" readonly  class="form-control col-md-7 col-xs-12" value="<?=$stock_balance;?>" tabindex="-1" />
                </td>

                <td style="vertical-align: middle;width:10%; text-align:center">
                    <input type="text" id="total_unit<?=$item_id?>" onkeyup="doAlert<?=$item_id?>(this.form);" name="total_unit<?=$item_id?>" value="<?=$edit_value_total_unit?>" style="width:96%; margin-left:2%; height:25px;font-size: 11px; text-align:center;"  class="sum" tabindex="1" />
                </td>
                <td style="vertical-align:middle;width: 10%; display: none">
                    <input type="number" name="unit_price<?=$item_id?>"   id="unit_price<?=$item_id?>" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:25px;font-size: 11px; text-align:center;" value="<?=$data->d_price;?>"  required="required" readonly step="any" min="0" class="unit_price<?=$item_id?>" />
                </td>
                <td style="vertical-align:middle;width: 12%; display: none">
                    <input type="text" name="total_amt<?=$item_id?>" readonly id="total_amt<?=$item_id?>" style="width:98%; margin-left:2%; height:25px;text-align:center;" value="<?=$edit_value_amount?>"  step="any" min="1"  />
                </td>
            </tr>
            <?php endwhile; ?>
            <script>
                // we used jQuery 'keyup' to trigger the computation as the user type
                $('.sum').keyup(function () {
                    // initialize the sum (total price) to zero
                    var sum = 0;
                    // we use jQuery each() to loop through all the textbox with 'price' class
                    // and compute the sum for each loop
                    $('.sum').each(function() {
                        sum += Number($(this).val());
                    });
                    // set the computed value to 'totalPrice' textbox
                    $('#totalPrice').val((sum).toFixed(2));
                });
            </script>
            <tr><th colspan="4">Total</th>
                <td><input type="number" id="totalPrice" style="width:98%; margin-left:2%; height:25px;text-align:center;font-size: 12px" readonly class="form-control col-md-7 col-xs-12" class="total_qty"></td></tr>
            <tr><td colspan="6"><button  type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="add" class="btn btn-primary" style="float: right; font-size: 12px; margin-right: 1%">Add items and proceed next</button></td></tr>
            </tbody>
        </table>
        <?php endif; ?>
    </form>
<?php
    $total = find_a_field('warehouse_goods_transfer_to_other_details','SUM(total_unit)','uid='.$uniqueid.'');
    ?>
    <?=added_data_delete_edit($res,$unique,$unique_GET,$COUNT_details_data,$page,$total,'3');
    endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
