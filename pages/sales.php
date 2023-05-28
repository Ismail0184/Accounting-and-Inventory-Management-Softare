<?php
require_once 'support_file.php';
$page = 'sales.php';
$table='sales_data_from_prism_software_gulshan';
$table_master='sales_data_from_prism_software_filterd';
$unique_master='id';
$sale_do_master='sale_do_master';
$sale_do_details='sale_do_details';
$sale_do_chalan='sale_do_chalan';
$journal_item='journal_item';
$journal='journal';
$crud      =new crud($table_master);

$config_group_class=find_all_field("config_group_class","","1");
$inventory_ledger=find_a_field('warehouse','ledger_id','warehouse_id='.$_SESSION['warehouse']);

if(prevent_multi_submit()){
    if(isset($_POST["Import"])){
        $filename=$_FILES["file"]["tmp_name"];
        if($_FILES["file"]["size"] > 0)
        { $file = fopen($filename, "r");
            while (($eData = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                $entry_at = date('Y-m-d H:i:s');
                $item_id_get = find_a_field("item_info","item_id","item_name='".$eData[0]."'");
                $sql = "INSERT INTO `sales_data_from_prism_software_gulshan` 
    (`item_id`,`item_name`,`R1`,`R2`,`R3`,`R4`,`R5`,`R6`,`R7`,`R8`,`R9`,`R10`,`R11`,`R12`,`R13`,`R14`,`R15`,`R16`,`R17`,`R18`,`R19`,`R20`,
`R21`,`R22`,`R23`,`R24`,`sales_date`,`entry_by`,`entry_at`,`status`,`section_id`,`company_id`) 
	         VALUES('$item_id_get','$eData[0]','$eData[1]','$eData[2]','$eData[3]','$eData[4]','$eData[5]',
'$eData[6]','$eData[7]','$eData[8]','$eData[9]','$eData[10]','$eData[11]','$eData[12]','$eData[13]','$eData[14]','$eData[15]',
'$eData[16]','$eData[17]','$eData[18]','$eData[19]','$eData[20]','$eData[21]','$eData[22]','$eData[23]','$eData[24]','".$_POST['sales_date']."','".$_SESSION['userid']."','".$entry_at."','MANUAL','".$_SESSION['sectionid']."','".$_SESSION['companyid']."')";
                $_SESSION['sales_date'] = @$_POST['sales_date'];
                $result = mysqli_query( $conn, $sql);
                $del = mysqli_query($conn, "DELETE from sales_data_from_prism_software_gulshan where item_id='0'");

                if(! $result )
                {
                    echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
							window.location = ".$page."
						</script>";
                }}
            fclose($file);
            //throws a message if data successfully imported to mysql database from excel file
            echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = ".$page."
					</script>";
        }

        header("Location: ".$page."");
    }}

if(isset($_POST['cancelAll']))
{
    mysqli_query($conn, "DELETE from sales_data_from_prism_software_gulshan where sales_date='".$_SESSION['sales_date']."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
    mysqli_query($conn, "DELETE from sales_data_from_prism_software_filterd where sales_date='".$_SESSION['sales_date']."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
    unset($_SESSION['sales_date']);
}
if(isset($_POST['search_data']))
{
    unset($_SESSION['sales_date']);
    $_SESSION['sales_date'] = @$_POST['sales_date'];
    unset($_POST);
}
$sales_date = @$_SESSION['sales_date'];
$COUNT_details_data=find_a_field("".$table."","Count(id)","sales_date='".$sales_date."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");


$count_do = find_a_field("sale_do_master","COUNT(do_no)","do_date='".$sales_date."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");

$status = @$_REQUEST['status'];
if ($status=='confirm'):
    if($count_do>0) {} else {
        $sql=mysqli_query($conn, "SELECT distinct route,sales_date,id from sales_data_from_prism_software_filterd where sales_date='".$sales_date."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."' group by route");
        while($data=mysqli_fetch_object($sql)):
            $crud = new crud($sale_do_master);
            $_POST['do_no'] = find_a_field($sale_do_master, 'max(do_no)', '1') + 1;
            $_POST['do_date'] = $_SESSION['sales_date'];
            $_POST['dealer_code'] = find_a_field("dealer_info", "dealer_code", "dealer_custom_code=" . $data->route);
            $_POST['depot_id'] = $_SESSION['warehouse'];
            $_POST['entry_at'] = date('Y-m-d H:i:s');
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['status'] = 'CHECKED';
            $_POST['do_type'] = 'sales';
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $crud->insert();
        endwhile;

        $sql3=mysqli_query($conn, "SELECT * from sales_data_from_prism_software_filterd where sales_date='".$sales_date."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
        while($data_d=mysqli_fetch_object($sql3)) {

            $sql2=mysqli_query($conn, "SELECT dm.do_no,dm.do_date,dm.dealer_code,d.dealer_custom_code,d.town_code,d.area_code,d.territory,d.region from sale_do_master dm, dealer_info d where dm.dealer_code=d.dealer_code and d.dealer_custom_code='".$data_d->route."' and dm.do_date='".$_SESSION['sales_date']."' and dm.section_id='".$_SESSION['sectionid']."' and dm.company_id='".$_SESSION['companyid']."' group by dm.do_no,d.dealer_custom_code");
            $data_m=mysqli_fetch_object($sql2);
            $find_chalan_no = $data_m->do_no;
            $crud = new crud($sale_do_details);
            $_POST['do_no'] = $data_m->do_no;
            $_POST['tr_no'] = $data_m->do_no;
            $_POST['do_date'] = $data_m->do_date;
            $_POST['ji_date'] = $data_m->do_date;
            $_POST['dealer_code'] = $data_m->dealer_code;;
            $_POST['dealer_id'] = $data_m->dealer_code;;
            $_POST['town'] = $data_m->town_code;;
            $_POST['area_code'] = $data_m->area_code;;
            $_POST['territory'] = $data_m->territory;;
            $_POST['region'] = $data_m->region;;
            $_POST['depot_id'] = $_SESSION['warehouse'];
            $_POST['warehouse_id'] = $_SESSION['warehouse'];

            $item_all = find_all_field("item_info","","item_id=".$data_d->item_id);
            $_POST['item_id'] = $data_d->item_id;
            $_POST['unit_price'] = $data_d->rate;

            $_POST['pkt_size'] = $item_all->pack_size;
            $_POST['total_unit'] = $item_all->pack_size*$data_d->qty;
            $_POST['total_amt'] = $data_d->amount;
            $_POST['cogs_price'] = $item_all->material_cost;
            $_POST['t_price'] = $item_all->t_price;
            $_POST['status'] = 'CHECKED';
            $_POST['do_type'] = 'sales';
            $_POST['tr_from'] = 'sales';
            $_POST['item_ex'] = $item_all->pack_size*$data_d->qty;;
            if($data_d->qty>0):
                $_POST['chalan_no']=$find_chalan_no;
                $crud->insert();
                $crud = new crud($sale_do_chalan);
                $crud->insert();

                $_POST['item_price'] = $item_all->production_cost;
                $_POST['total_amt'] = $data_d->qty*$item_all->production_cost;
                $crud = new crud($journal_item);
                $crud->insert();
            endif;

        }
    }
    $up=mysqli_query($conn, "UPDATE sales_data_from_prism_software_gulshan SET status='completed' where sales_date='".$sales_date."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");

endif;
?>
<?php require_once 'header_content.php'; ?>
    <style>
        input[type=text]{
            font-size: 11px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content.php'; ?>
    <form action="" name="addem" id="addem" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post">
        <input  name="section_id" type="hidden" id="section_id" value="<?=$_SESSION['sectionid']?>">
        <input style="width:155px;"  name="company_id" type="hidden" id="company_id" value="<?=$_SESSION['companyid']?>"/>
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <tr style="background-color: #3caae4; color:white">
                <th style="text-align: center">Sales Date</th>
                <th style="text-align: center">Attachment ( .csv file)</th>
                <th style="text-align: center">Option</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="date" style="font-size: 11px;" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date=date('Y-m-d') .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" value="<?=($sales_date!='')? $sales_date : date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12" required name="sales_date">
                </td>
                <td align="center">
                    <input style="font-size:11px" type="file" id="file" name="file" required class="form-control col-md-7 col-xs-12" >
                </td>
                <td align="center" style="width:5%; vertical-align:middle">
                    <button type="submit" name="Import" onclick='return window.confirm("Are you confirm to Upload?");' class="btn btn-primary" style="font-size: 11px">Upload the File</button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content"><br>
                <form method="post">
                    <table align="center" style="width:98%; font-size: 11px; margin-top: -15px">
                        <tr>
                            <th>Sales Date <span class="required text-danger">*</span></th>
                            <td><input type="date" style="font-size: 11px;" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date=date('Y-m-d') .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" value="<?=($sales_date!='')? $sales_date : date('Y-m-d') ?>" class="form-control col-md-7 col-xs-12" required name="sales_date"></td>
                            <td align="center"><button type="submit" name="search_data" class="btn btn-primary" style="font-size: 11px">Search Uploaded Data</button></td>
                            <?php
                            $searchStatus = find_a_field("sales_data_from_prism_software_gulshan","distinct status","sales_date='".$_SESSION['sales_date']."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
                            if($COUNT_details_data>0){
                                if ($searchStatus=='checked'):?>
                                    <td align="center"><button type="submit" name="cancelAll" onclick='return window.confirm("Are you confirm to Clear Data?");' class="btn btn-danger" style="font-size: 11px">Clear All Data</button></td>
                                <?php endif; if ($searchStatus=='checked'):?>
                                    <td align="center"><a href="<?=$page?>?status=confirm" onclick='return window.confirm("Are you confirm to the uploaded data?");' class="btn btn-success" style="font-size: 11px">Confirm and Finish the Process</a>
                                    </td>
                                <?php endif; } ?>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
<?php if($COUNT_details_data>0){?>
    <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Route - Section</th>
                <th>Item Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
            <?php
            $i = 0;
            $total_saless = 0;
            $total_salesss = 0;
            $searchStatus = find_a_field("sales_data_from_prism_software_gulshan","distinct status","sales_date='".$_SESSION['sales_date']."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
            $sql = mysqli_query($conn, "SELECT *  FROM dealer_info where section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."' and canceled='Yes' order by dealer_custom_code");
            while($data=mysqli_fetch_object($sql)){
                $col='R'.$data->dealer_custom_code;
                ?>
                <tr style="background-color: #00ADEE; color: white"><td colspan="8">Route : <?=($data->dealer_custom_code=='24')? 'Counter' : $data->dealer_custom_code; ?></td></tr>
                <?php
                $total_saless = 0;

                $sql2 = mysqli_query($conn, "SELECT i.item_id,i.item_name,s.sales_date,i.t_price from item_info i, sales_data_from_prism_software_gulshan s where i.item_id=s.item_id");
                while($data2=mysqli_fetch_object($sql2)){
                    $item_id = $data2->item_id;
                    $a = 'A';
                    $Get_qty = find_a_field("sales_data_from_prism_software_gulshan","SUM(".$col.")", "item_id=".$data2->item_id." and sales_date='".$_SESSION['sales_date']."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
                    $POST_route = @$_POST['route' . $item_id];
                    $POST_section = @$_POST['section' . $item_id];
                    $POST_item_id = @$_POST['item_id_' . $item_id];
                    $POST_qty = @$_POST['qty_' . $a . $item_id];
                    $_POST['item_id'] = @$data2->item_id;
                    $_POST['route'] = @$data->dealer_code;
                    $_POST['section'] = @$data->section;
                    $_POST['qty'] = @$Get_qty;
                    $_POST['rate'] = @$data2->t_price;
                    $_POST['amount'] = @$Get_qty*@$data2->t_price;
                    $_POST['entry_by'] = @$_SESSION['userid'];
                    $_POST['status'] = 'UNCHECKED';
                    $_POST['section_id'] = @$_SESSION['sectionid'];
                    $_POST['company_id'] = @$_SESSION['companyid'];
                    $_POST['sales_date'] = @$_SESSION['sales_date'];
                    if ($searchStatus=='manual') {
                        $crud->insert();
                        $up=mysqli_query($conn, "UPDATE sales_data_from_prism_software_gulshan SET status='checked' where sales_date='".$_SESSION['sales_date']."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
                        unset($_POST);
                    }
                    ?>
                        <?php if($data2->sales_date==$_SESSION['sales_date']){?>
                    <tr>
                        <td><?=$i=$i+1;?>
                            <input type="hidden" name="route<?=$data2->item_id;?>" value="<?=$data->dealer_code;?>">
                            <input type="hidden" name="item_id_<?=$data2->item_id;?>" value="<?=$data2->item_id;?>"></td>
                        <td><?=$data2->sales_date;?></td>
                        <td><?=$data->dealer_code;?></td>
                        <td><?=$data2->item_id;?> : <?=$data2->item_name;?></td>
                        <td><?=$Get_qty?><input type="text" name="qty_<?=$a.$item_id?>" style="display: none" value="<?=$Get_qty?>"></td>
                        <td><?=$data2->t_price;?></td>
                        <td style="text-align: right"><?=number_format(($total_sales=$Get_qty*$data2->t_price),3);?></td>
                    </tr>

                    <?php

                    $total_saless = $total_saless+$total_sales;
                    $total_salesss = $total_salesss+$total_sales;
                }}?>
                <tr><th colspan="6" style="text-align: right">Total Sales Value of Route - <?=$data->dealer_code;?>  = </th>
                    <th style="text-align: right"><?=number_format($total_saless,2)?></th>
                </tr>
            <?php }?>
            <tr><th colspan="6" style="text-align: right">Total Sales Value = </th>
                <th style="text-align: right"><?=number_format($total_salesss,2)?></th>
            </tr>
            </thead>
        </table>

        <?php
        $jv = 0;
        $sql_find_do = "SELECT * from ".$sale_do_master." where do_date='".$_SESSION['sales_date']."' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."' order by do_no";
        $result = mysqli_query($conn, $sql_find_do);
        while($data = mysqli_fetch_object($result)){
            $jv = next_journal_voucher_id();
            $dealer_master = find_all_field("dealer_info","","dealer_code=".$data->dealer_code." and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
            $total_sales_amount=find_a_field("sale_do_chalan","SUM(total_amt)","do_no=".$data->do_no." and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
            $COGS_amount=find_a_field("journal_item","SUM(total_amt)","do_no=".$data->do_no." and gift_type in ('none') and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
            $find_chalan_no=find_a_field("sale_do_chalan","distinct chalan_no","do_no=".$data->do_no." and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
            $narration="Sales by ".$dealer_master->dealer_name_e.', Invoice No # '.$data->do_no;
            $status = @$_REQUEST['status'];
            if ($status=='confirm') {
                $date = $_SESSION['sales_date'];
                $sales_ledger = $config_group_class->sales_ledger;
                $COGS_sales = $config_group_class->cogs_sales;

                if (($dealer_master->account_code > 0) && (($sales_ledger && $total_sales_amount) > 0)) {
                    add_to_journal_new($_SESSION['sales_date'], 0, $jv, $date, $dealer_master->account_code, $narration, $total_sales_amount, 0, 'Sales', $data->do_no, $data->do_no, 0, 0, $_SESSION['usergroup'], '', $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $data->do_no, '');
                    add_to_journal_new($_SESSION['sales_date'], 0, $jv, $date, $sales_ledger, $narration, 0, $total_sales_amount, 'Sales', $data->do_no, $data->do_no, 0, 0, $_SESSION['usergroup'], '', $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $data->do_no, '');
                } // sales start form here
                if (($COGS_sales > 0) && (($inventory_ledger && $COGS_amount) > 0)) {
                    add_to_journal_new($_SESSION['sales_date'], 0, $jv, $date, $COGS_sales, $narration, $COGS_amount, 0, 'Sales', $data->do_no, $data->do_no, 0, 0, $_SESSION['usergroup'], '', $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $data->do_no, '');
                    add_to_journal_new($_SESSION['sales_date'], 0, $jv, $date, $inventory_ledger, $narration, 0, $COGS_amount, 'Sales', $data->do_no, $data->do_no, 0, 0, $_SESSION['usergroup'], '', $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST['pc_code'], $data->do_no, '');
                } // COGS start form here
                unset($_POST);
                unset($_SESSION['sales_date']);
            } ?>
            <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px; display:none">
                <thead>
                <tr style="background-color: bisque">
                    <th>#</th>
                    <th style="width: 8%; vertical-align: middle; text-align: center">Journal</th>
                    <th style="width: 10%; vertical-align: middle; text-align: center">For</th>
                    <th style="vertical-align: middle">Accounts Description</th>
                    <th style="text-align:center; width: 25%; vertical-align: middle">Narration</th>
                    <th style="text-align:center; width: 12%; vertical-align: middle">Debit</th>
                    <th style="text-align:center; width: 12%; vertical-align: middle">Credit</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle">1</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle">Sales Journal</th>
                    <th style="text-align: center; vertical-align: middle">Customer Ledger</th>
                    <td style="vertical-align: middle">
                        <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required  name="ledger_1<?=$data->do_no?>">
                            <option  value="<?=$dealer_master->account_code;?>"><?=$dealer_master->account_code; ?>-<?=$customer_name=find_a_field('accounts_ledger','ledger_name','ledger_id='.$dealer_master->account_code.''); ?></option>
                        </select>
                    </td>
                    <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_1<?=$data->do_no?>" id="narration_1" class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$narration?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?></textarea></td>
                    <td align="center" style="vertical-align: middle"><input type="text" name="dr_amount_1<?=$data->do_no?>" readonly value="<?=$total_sales_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td align="center" style="vertical-align: middle"><input type="text" name="cr_amount_1<?=$data->do_no?>" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
                <tr>
                    <th style="text-align: center; vertical-align: middle">Sales Ledger</th>
                    <td style="vertical-align: middle"><?$sales_ledger=$config_group_class->sales_ledger;?>
                        <select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_2<?=$data->do_no?>" id="ledger_2">
                            <?=foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_ledger, 'ledger_id='.$sales_ledger);?>
                        </select></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_2<?=$data->do_no?>" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_2<?=$data->do_no?>" readonly value="<?=$total_sales_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle">2</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle">COGS Journal</th>
                    <th style="text-align: center; vertical-align: middle">COGS Ledger</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_3<?=$data->do_no?>">
                            <?$COGS_sales=$config_group_class->cogs_sales;?>
                            <option  value="<?=$COGS_sales;?>"><?=$COGS_sales; ?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$COGS_sales.''); ?></option>
                        </select></td>
                    <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_3<?=$data->do_no?>" id="narration_3" class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$narration;?><?php if(!empty($do_master->remarks)) { echo ' , Remarks # '.$do_master->remarks.''; }?></textarea></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_3<?=$data->do_no?>" readonly value="<?=$COGS_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_3<?=$data->do_no?>" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
                <tr>
                    <th style="text-align: center; vertical-align: middle">Warehouse / Inventory</th>
                    <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%" tabindex="-1" required="required"  name="ledger_4<?=$data->do_no?>" id="ledger_4">
                            <option  value="<?=$inventory_ledger;?>"><?=$inventory_ledger?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$inventory_ledger.''); ?></option>
                        </select></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_4<?=$data->do_no?>"  readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                    <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_4<?=$data->do_no?>" readonly value="<?=$COGS_amount;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                </tr>
                </tbody>
            </table>
        <?php } ?>
    </form>
    </div>
    </div>
    </div>

<?php }
$status = @$_GET['status'];
if ($status=='confirm') { ?>
    <meta http-equiv='refresh' content='0;<?=$page?>'>
<?php } ?>
<?=$html->footer_content();?>