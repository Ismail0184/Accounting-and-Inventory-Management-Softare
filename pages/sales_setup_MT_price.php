<?php
require_once 'support_file.php';
$title='MT Dealer Price Setup';
$unique='id';
$unique_field='dealer_code';
$table_master="sales_setup_MT_price";
$page="sales_setup_MT_price.php";
$crud      =new crud($table_master);
$$unique = $_POST[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');


if(prevent_multi_submit()) {
    if (isset($_POST['initiate'])) {
        unset($_SESSION[MT_dealer_price_Setup]);
        $_SESSION[MT_dealer_price_Setup] = $_POST[dealer_code];
        unset($_POST);
    }



//for single FG Add...........................
    if (isset($_POST['add'])) {
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST[dealer_code] = $_SESSION[MT_dealer_price_Setup];
        $crud->insert();
        unset($_POST);
        }


//for Delete..................................
    if (isset($_POST['ismail'])) {
        unset($_SESSION[MT_dealer_price_Setup]);
        unset($_POST);
        unset($$unique);
    }


}
$COUNT_details_data=find_a_field(''.$table_master.'','Count(id)','dealer_code='.$_SESSION['MT_dealer_price_Setup'].'');

// data query..................................
if(isset($_SESSION['initiate_credit_note'])) {
    $condition = $unique . "=" . $_SESSION['initiate_credit_note'];
    $data = db_fetch_object($table_journal_master, $condition);
    while (list($key, $value) = each($data)) {
        $$key = $value;
    }



}
$a_ledger = mysqli_query($conn,"SELECT * from dealer_info  where dealer_type in ('SuperShop')");
?>

<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]:focus {
        background-color: lightblue;
    }
</style>
<script type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }
</script>
<?php require_once 'body_content.php'; ?>

<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                <table align="center" style="width:80%">
                    <tr>




                        <td ><div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Available MT Dealer No<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12"><select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="dealer_code">
                                        <option></option>
                                        <?php
                                        while($row=mysqli_fetch_array($a_ledger)){  ?>
                                            <option  value="<?php echo $row[dealer_code]; ?>" <?php if($_SESSION[MT_dealer_price_Setup]==$row[dealer_code]) echo 'selected'; ?>><?php echo $row[dealer_custom_code]; ?>-<?php echo $row[dealer_name_e]; ?></option>
                                            <?php var_dump($row); } ?>
                                    </select>
                                </div>
                            </div>
                        </td>
                                <td><button type="submit" name="initiate" class="btn btn-primary" style="font-size: 12px">Search MT Dealer</button></td>

                    </tr>




                  </table>

                <?php if($_SESSION[initiate_credit_note]){
                    if($COUNT_details_data>0) {
                        $ml='40';
                        $display='style="margin-left:40%; margin-top: 22px;"';

                    } else {
                        $ml='40';
                        $display='style="margin-left:40%; margin-top: 15px; display: none"';
                    }
                    ?>
                    <div class="form-group" style="margin-left:<?=$ml;?>%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 12px">Update Receipt Voucher</button>
                        </div></div>

                    <div class="form-group" <?=$display;?>>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <a  href="voucher_print_preview.php?v_type=Receipt&vo_no=<?=$_SESSION[initiate_credit_note];?>&v_date=<?=$voucher_date;?>" target="_blank" style="color: blue; text-decoration: underline; font-size: 12px; font-weight: bold; vertical-align: middle">View Receipt Voucher</a>
                        </div></div>
                <?php   } else {?>
                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                        <div class="col-md-6 col-sm-6 col-xs-12">

                        </div></div>
                <?php } ?>

            </form></div></div></div>












<?php if($_SESSION[MT_dealer_price_Setup]){  ?>

    <form action="" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
        <? require_once 'support_html.php';?>


        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <tbody>
            <tr style="background-color: bisque">
                <th style="text-align: center">Item Desctiption</th>
                <th style="text-align: center">Comission Margin</th>
                <th style="text-align:center">#</th>
            </tr>
            <tbody>
            <tr>
                <td style="width: 25%; vertical-align: middle" align="center">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="item_id" id="item_id">
                        <option></option>
                        <?php
                        $item=mysqli_query($conn, "SELECT * from item_info  where sub_group_id in ('200010000') and item_id not in (select item_id from sales_setup_MT_price where dealer_code=".$_SESSION[MT_dealer_price_Setup].") order by item_name");
                        while($row=mysqli_fetch_array($item)){  ?>
                            <option  value="<?php echo $row[item_id]; ?>"><?php echo $row[finish_goods_code]; ?>-<?php echo $row[item_name]; ?></option>
                            <?php var_dump($row); } ?>
                    </select></td>





                <td  style="width:10%;vertical-align: middle" align="center">
                    <input type="text" id="comission_margin" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="comission_margin" value="<?=$_POST[narration];?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>




                <td align="center" style="width:5%; vertical-align: middle "><button type="submit" class="btn btn-primary" name="add" id="add">Add</button></td></tr>





            </tbody>
        </table>
        <input name="count" id="count" type="hidden" value="" />
    </form>


    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

    <?php
    if($_GET[type]=='delete'){
        if($_GET[productdeletecode]){
            $results=mysqli_query($conn, ("Delete from receipt where id='$_GET[productdeletecode]'")); ?>
            <meta http-equiv="refresh" content="0;credit_note.php">
        <?php  } } ?>


    <form id="ismail" name="ismail"  method="post" style="font-size: 11px"  class="form-horizontal form-label-left">
        <?php if($COUNT_details_data>0) { ?>
            <table align="center" class="table table-striped table-bordered" style="width:98%">
                <thead>
                <tr style="background-color: bisque">
                    <th>SL</th>
                    <th style=" text-align:center">Dealer Name</th>
                    <th>Code</th>
                    <th style="text-align:center">Item Description</th>
                    <th style="text-align:center">UOM</th>
                    <th style="text-align:center">Pack Size</th>
                    <th style=" text-align:center">Comission Margin</th>
                    <th style="width:15%; text-align:center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rs = "Select p.*,i.*,d.*

from 
sales_setup_MT_price p,
item_info i,
dealer_info d
  where 
p.dealer_code=d.dealer_code and 
 p.item_id=i.item_id and
 p.dealer_code='" . $_SESSION['MT_dealer_price_Setup'] ."'";
                $re_query = mysqli_query($conn, $rs);
                while($uncheckrow=mysqli_fetch_array($re_query)){
                    $ids=$uncheckrow[id];
                    if(isset($_POST['deletedata'.$ids]))
                    {
                        mysqli_query($conn, ("DELETE FROM ".$table_master." WHERE id='$ids'")); ?>
                        <meta http-equiv="refresh" content="0;<?=$page;?>">
                        <?php
                    } ?>


                    <tr>
                        <td style="width:3%; vertical-align:middle"><?=$js=$js+1; ?></td>
                        <td style="vertical-align:middle"><?=$uncheckrow[dealer_name_e];?></td>
                        <td style="vertical-align:middle"><?=$uncheckrow[finish_goods_code];?></td>
                        <td style="vertical-align:middle"><?=$uncheckrow[item_name];?></td>
                        <td style="vertical-align:middle;"><?=$uncheckrow[unit_name] ;?></td>
                        <td style="vertical-align:middle; text-align: center;"><?=$uncheckrow[pack_size] ;?></td>
                        <td align="center" style=" text-align:center; vertical-align:middle"><?=$uncheckrow[comission_margin] ;?> %</td>



                        <td align="center" style="width:10%;vertical-align:middle">
                            <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                        </td>

                    </tr>



                    <?php } ?>





                </tbody>

               </table>
        <?php }  ?>

        <button style="float: left; font-size: 12px; margin-left: 1%" type="submit" name="ismail" id="ismail" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Cancel?");' class="btn btn-danger">Cancel </button>

    </form></div></div></div>
<?php } mysqli_close($conn); ?>

<?php require_once 'footer_content.php' ?>
<script>
    $(document).ready(function() {
        $('#nam_date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#Cheque_Date').daterangepicker({

            singleDatePicker: true,
            calender_style: "picker_4",

        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>