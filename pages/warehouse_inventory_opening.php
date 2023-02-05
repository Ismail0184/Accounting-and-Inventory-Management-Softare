<?php
require_once 'support_file.php';
$title="Inventory Opening";

$dfrom=date('Y-1-1');
$dto=date('Y-m-d');

$now=time();

$table="warehouse_inventory_opening";
$unique = 'or_no';   // Primary Key of this Database table
$table_details = 'warehouse_inventory_opening_detail';
$details_unique = 'id';
$table_journal_item='journal_item';
$page="warehouse_inventory_opening.php";
$crud      =new crud($table);
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {   $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['PBI_ID'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $sd=$_POST[or_date];
        $_POST[or_date]=date('Y-m-d' , strtotime($sd));
        $_POST['issue_type'] = 'Opening';
        $_POST['status'] = 'MANUAL';
        $_POST['issued_to'] = $_SESSION[PBI_ID];
        $_SESSION['initiate_warehouse_inventory_opening']=$_POST[$unique];
        $crud->insert();
        $_SESSION['item_sg']=$_POST[item_sg];
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }





//for modify..................................
    if(isset($_POST['modify']))
    {
        $sd=$_POST[or_date];
        $_POST[or_date]=date('Y-m-d' , strtotime($sd));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        //echo $targeturl;
        unset($_SESSION['item_sg']);
        $_SESSION['item_sg']=$_POST[item_sg];
        unset($_POST);
    }


//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_details);
        $condition =$unique."=".$_SESSION['initiate_warehouse_inventory_opening'];
        $crud->delete_all($condition);
        $crud = new crud($table);
        $condition=$unique."=".$_SESSION['initiate_warehouse_inventory_opening'];
        $crud->delete($condition);
        unset($_SESSION['initiate_warehouse_inventory_opening']);
        unset($_SESSION['item_sg']);
        unset($_POST);
    }


if(isset($_POST['add']))
{
    $_POST['entry_by'] = $_SESSION['userid'];
    $_POST['entry_at'] = date('Y-m-d H:s:i');
    $_POST[or_date]=$_POST[or_date];
    $_POST['receive_type'] = 'Opening';
    $_POST['warehouse_id'] = $_POST['warehouse_id'];
$result="SELECT i.*,sg.*,g.* FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        sg.sub_group_id=".$_SESSION['item_sg']."
                        order by i.item_name";
$res_item=mysqli_query($conn, $result);
while($row=mysqli_fetch_array($res_item)){
    $id=$row[item_id];
    if(($_POST['qty'.$id] && $_POST['rate'.$id])>0){
    $_POST['lot_number'] = $_POST['lot_number'.$id];
    $_POST['item_id'] = $id;
    $_POST['rate'] = $_POST['rate'.$id];
    $_POST['qty'] = $_POST['qty'.$id];
    $_POST[amount]=$_POST['qty']*$_POST['rate'];
    $_POST[or_no]=$_SESSION['initiate_warehouse_inventory_opening'];
    $crud      =new crud($table_details);
    $crud->insert();
    }}
    unset($_POST);
}


$result="SELECT i.*,sg.*,g.*
 FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        sg.sub_group_id=".$_SESSION['item_sg']."
                        order by i.item_name";
$res_item=mysqli_query($conn, $result);
while($row=mysqli_fetch_array($res_item)){
    $id=$row[item_id];
    if(isset($_POST['deletedata'.$id]))
    {
        $del="DELETE FROM warehouse_inventory_opening_detail WHERE item_id=".$id." and ".$unique."=".$_SESSION[initiate_warehouse_inventory_opening]."";
        $del_item=mysqli_query($conn, $del);
    }}

    if (isset($_POST['confirmsave'])) {
        mysqli_query($conn,"Update " . $table . " set status='UNCHECKED' where " . $unique . "=" . $_SESSION['initiate_warehouse_inventory_opening'] . "");
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['PBI_ID'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $result="SELECT i.*,sg.*,g.*,d.* FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g,
                        warehouse_inventory_opening_detail d
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        d.item_id=i.item_id and 
                        ".$unique."=".$_SESSION[initiate_warehouse_inventory_opening]."
                        order by i.item_name";
        $res_item=mysqli_query($conn, $result);
        while($row=mysqli_fetch_array($res_item)){

            $_POST['ji_date'] = $row[or_date];
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[warehouse_id];
            $_POST['item_in'] = $row[qty];
            $_POST['item_price'] = $row[rate];
            $_POST['total_amt'] = $row[amount];
            $_POST['tr_from'] = 'Opening';
            $_POST['tr_no'] = $_SESSION[initiate_warehouse_inventory_opening];
            $_POST['sr_no'] = $row[id];
            $lot_number = automatic_number_generate("","journal_item","lot_number","ji_date='".date('Y-m-d')."'");
			$_POST['lot_number'] =  $lot_number++;
            $crud      =new crud($table_journal_item);
            $crud->insert();
    }
            unset($_POST);
            unset($_SESSION['item_sg']);
            unset($_SESSION['initiate_warehouse_inventory_opening']);
    }}
// data query..................................
if(isset($_SESSION[initiate_warehouse_inventory_opening]))
{   $condition=$unique."=".$_SESSION[initiate_warehouse_inventory_opening];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>
<?php require_once 'header_content.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php require_once 'body_content.php'; ?>

<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right">
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>


        <div class="x_content">
            <form action="" enctype="multipart/form-data" style="font-size: 11px" method="post" name="addem" id="addem" >
                <? //require_once 'support_html.php';?>
                <table style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:50%;">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Ref. No<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<?
                                    $idGETs=find_a_field($table,'max('.$unique.')','1');

                                    if($_SESSION['initiate_warehouse_inventory_opening']>0) {
                                        $idGET=$_SESSION['initiate_warehouse_inventory_opening'];
                                    } else
                                    {
                                        $idGET=$idGETs+1;
                                        if($idGETs<1) $idGET = 1;
                                    }
                                    echo $idGET; ?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%">
                                </div>
                            </div></td>


                        <td style="width:50%">
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Opening Date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date"  required="required" name="or_date" value="<?=$or_date;?>" class="form-control col-md-7 col-xs-12" style="width:100% ;font-size: 11px" >      </div>
                            </div>
                        </td></tr>

<tr><td style="height: 5px"></td></tr>
                    <tr>
                       <td>
                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Warehouse<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" style="width:100%; font-size: 11px;" tabindex="-1" required="required"  name="warehouse_id" id="warehouse_id">
                                    <option selected></option>
                                    <?php
                                    if($_SESSION["userlevel"]=='5')
                                        foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, '1');
                                    else
                                        foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, 'warehouse_id='.$_SESSION[warehouse].'');
                                    ?>
                                </select>
                            </div></div>
                        </td>
                        <td style="width:25%;">
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Item Sub Group<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" style="width:100%; font-size: 11px;" tabindex="-1" required="required" name="item_sg" id="item_sg">
                                        <option></option>
                                        <? $sql = "SELECT sg.sub_group_id,concat(sg.sub_group_id,' : ',sub_group_name),g.* FROM                        
                        item_sub_group sg,
                        item_group g
                        where
                        sg.group_id=g.group_id 
                        order by sg.sub_group_id";
                                        $result = mysqli_query($conn, $sql);
                                        advance_foreign_relation($sql,$_SESSION['item_sg']);?>
                                    </select>
                                </div></div>
                        </td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                    </table>


    <div class="form-group" style="margin-left:40%; margin-top: 15px">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php if($_SESSION[initiate_warehouse_inventory_opening]){  ?>
                <button type="submit" style="font-size: 11px" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");'>Update <?=$title;?></button>
            <?php   } else {?>
                <button type="submit" style="font-size: 11px" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">Initiate <?=$title;?></button>
            <?php } ?>
        </div></div>
    </form></div></div></div>


<?php if($_SESSION[initiate_warehouse_inventory_opening]){  ?>
    <form action="" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post">


                    <? require_once 'support_html.php';?>
                    <input type="hidden" id="vendor_id" name="vendor_id" value="<?=$vendor_id;?>" >
                    <input type="hidden" id="vendor_name" name="vendor_name" value="<?=$vendor_name;?>" >
                    <input type="hidden" id="warehouse_id" name="warehouse_id" value="<?=$warehouse_id;?>" >
                    <input type="hidden"  name="or_date" value="<?=$or_date;?>">
                    <input type="hidden" id="<?=$unique;?>" name="<?=$unique;?>" value="<?=$_SESSION[initiate_warehouse_inventory_opening];?>">

                    <table align="center"  class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>Code</th>
                            <th>Item Description</th>
                            <th style="text-align:center">Sub Group</th>
                            <th style="text-align:center">Group</th>
                            <th style="text-align:center">Unit</th>
                            <th style="text-align:center">Qty in Pcs</th>
                            <th style="text-align:center">Rate</th>
                            <th style="text-align:center">Amount</th>

                        </tr>
                        <tbody>
<?php
                        $result="SELECT i.*,sg.*,g.*,
 (select SUM(qty) from warehouse_inventory_opening_detail where item_id=i.item_id and or_no=$_SESSION[initiate_warehouse_inventory_opening]) as doneqty,
  (select rate from warehouse_inventory_opening_detail where item_id=i.item_id and or_no=$_SESSION[initiate_warehouse_inventory_opening]) as donerate

 FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        sg.sub_group_id=".$_SESSION['item_sg']."
                        order by i.item_name";
                        $res_item=mysqli_query($conn, $result);
                        while($row=mysqli_fetch_array($res_item)){
                            $id=$row[item_id];
							$lot=automatic_number_generate("","journal_item","lot_number","ji_date='".date('Y-m-d')."'")
                           ?>
                        <tr>
                            <td style="width: 1%"><?=$i=$i+1;?></td>
                            <td><?php echo $row[item_id]; ?></td>
                            <td align="left" ><?php echo $row[item_name]; ?></td>
                            <td align="left" ><?php echo $row[sub_group_name]; ?></td>
                            <td align="left" ><?php echo $row[group_name]; ?></td>
                            <td align="left" ><?php echo $row[unit_name]; ?></td>
                            <td align="center" style="vertical-align: middle">
                                <input type="hidden" name="lot_number<?=$id;?>" id="lot_number<?=$id;?>" value="<?=$lot++;?>">
                                <?php if($row[doneqty]>0){ echo $row[doneqty];} else { ?>
                                <input align="center"  type="text" style="width:80px; height:20px; font-size: 11px; text-align:center"   name="qty<?=$id;?>" id="qty<?=$id;?>" autocomplete="off" class='qty' class='qty<?=$id;?>'>
                            <?php } ?>
                            </td>

                            <td align="center">
                                <?php if($row[doneqty]>0){ echo $row[donerate];} else { ?>
                                <input align="center" type="text" style="width:80px; height:20px; font-size: 11px; text-align:center" value="" name="rate<?=$id;?>" id="rate<?=$id;?>" autocomplete="off"  class='rate<?=$id;?>'>

<?php } ?>                            </td>

                            <td align="center">
                                <?php if($row[doneqty]>0){ echo  number_format($row[doneqty]*$row[donerate],2); ?>
                                    <button type="submit" name="deletedata<?=$id;?>" id="deletedata<?=$id;?>" style="background-color:transparent; border:none; float: right" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Deleted?");'><img src="delete.png" style="width:18px;  height:15px"></button>
                                <?php } else { ?>
                                <input  style="width:120px; height:20px; font-size: 11px; text-align:center" readonly type='text' id='amount<?=$id;?>'  name='amount<?=$id;?>' class='sum<?=$id;?>' />
                            <?php } ?>




                            </td>
                        </tr>
                            <script>
                                $(function(){
                                    $('#rate<?=$id;?>, #qty<?=$id;?>').keyup(function(){
                                        var rate<?=$id;?> = parseFloat($('#rate<?=$id;?>').val()) || 0;
                                        var qty<?=$id;?> = parseFloat($('#qty<?=$id;?>').val()) || 0;
                                        $('#amount<?=$id;?>').val((rate<?=$id;?> * qty<?=$id;?>).toFixed(2));
                                    });
                                });
                            </script>
<?php } ?>
<script>
    // we used jQuery 'keyup' to trigger the computation as the user type
    $('.sum').blur(function () {
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


                        </tbody>
                        <tr><td colspan="8" style="text-align: right; color: red; font-weight: bold; vertical-align: middle; font-size: 13px">Total = </td>
                        <td align="center" style="text-align:center"><input style="height: 20px;width: 120px; font-weight: bold; font-size: 11px; text-align: right" type='text' id='totalPrice' disabled /></td></tr>
                    </table>
                <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="add" id="add" class="btn btn-primary" style="float: right; margin-right: 1%; font-size: 11px">Add Opening Item</button>



    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->


        <table align="center"  class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <thead>
            <tr>
                <th>SL</th>
                <th>Code</th>
                <th>Item Description</th>
                <th style="text-align:center">Sub Group</th>
                <th style="text-align:center">Group</th>
                <th style="text-align:center">Unit</th>
                <th style="text-align:center">Qty</th>
                <th style="text-align:center">Rate</th>
                <th style="text-align:center">Amount</th>

            </tr>
            <tbody>
            <?php
            $result="SELECT i.*,sg.*,g.*,d.* FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g,
                        warehouse_inventory_opening_detail d
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        d.item_id=i.item_id and 
                        ".$unique."=".$_SESSION[initiate_warehouse_inventory_opening]."
                        order by i.item_name";
            $res_item=mysqli_query($conn, $result);
            while($row=mysqli_fetch_array($res_item)){
                $id=$row[item_id];


                ?>


                <tr>
                    <td style="width: 1%"><?=$ij=$ij+1;?></td>
                    <td><?php echo $row[item_id]; ?></td>
                    <td align="left" ><?php echo $row[item_name]; ?></td>
                    <td align="left" ><?php echo $row[sub_group_name]; ?></td>
                    <td align="left" ><?php echo $row[group_name]; ?></td>
                    <td align="left" ><?php echo $row[unit_name]; ?></td>
                    <td align="center" style="vertical-align: middle"><?=$row[qty];?></td>
                    <td align="center"><?=$row[rate];?></td>
                    <td align="right"><?=$row[amount];?></td>
                </tr>
            <?php
            $tiv=$tiv+$row[amount];
            } ?>



            </tbody>
            <tr style="text-align: right; color: red; font-weight: bold; vertical-align: middle; font-size: 13px"><td colspan="8">Total Inventory Value= </td>
                <td align="center" style="text-align:right"><?=number_format($tiv,2)?></td></tr>

        </table>


        <button type="submit"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Purchase?");' name="deleted" id="deleted" class="btn btn-danger" style="float: left; font-size: 12px; margin-left: 1%">Delete the Inventory Opening </button>
        <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="confirmsave" id="confirmsave" class="btn btn-success" style="float: right;font-size: 12px; margin-right: 1%">Confirm and Finish Inventory Opening </button>
    </form><br>
<?php } ?>
<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#or_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>
<script>
    $(function(){
        $('#price, #qty').keyup(function(){
            var price = parseFloat($('#price').val()) || 0;
            var qty = parseFloat($('#qty').val()) || 0;
            $('#sum').val((price * qty).toFixed(2));
        });
    });
</script>
