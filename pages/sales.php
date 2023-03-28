<?php
require_once 'support_file.php';
$page = 'LBC_sales_invoice.php';
$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$table_pg='payment_gateway_data';
$unique_detail='id';



if(prevent_multi_submit()){


if(isset($_POST["Import"])){
    echo $filename=$_FILES["file"]["tmp_name"];
    if($_FILES["file"]["size"] > 0)
    {
        $file = fopen($filename, "r");
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
        {   //It wiil insert a row to our subject table from our csv file`
            if(!empty($emapData[3]) && $emapData[7]>0) {
                $trdate= date('Y-m-d H:i', strtotime($emapData[0]));
                $sql = "INSERT INTO `payment_gateway_data` (`user_id`,`do_no`, `tran_date`, `transactionID`,`bank`,`store_id`,`bankID`,`currency_amount`,`discount_amount`,`amount`,`SSLCharge`,`receivable`,`cardType`,`cardNumber`,`cardHolderGivenName`,`issuerBank`,`issuerCountry`,`validated`,`status`,`settlementStatus`) 
	         VALUES('$emapData[30]','".$_SESSION['unique_master_for_SP']."','$trdate','$emapData[3]','$emapData[4]','$emapData[6]','$emapData[5]','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','$emapData[11]','$emapData[12]','$emapData[13]','$emapData[14]','$emapData[15]','Bangladesh','$emapData[17]','$emapData[22]','$emapData[24]')";
            }
            //we are using mysql_query function. it returns a resource on true else False on error
            //echo 'this is test';
            $result = mysqli_query( $conn, $sql);
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
            <? require_once 'support_html.php';?>
            <input type="hidden" name="do_no" id="do_no" value="<?=$_SESSION['unique_master_for_SP'];?>">
            <input type="hidden" name="do_date" id="do_date" value="<?=$do_date;?>">
            <input type="hidden" name="dealer_code" id="dealer_code" value="<?=$_SESSION[select_dealer_do_SP];?>">
            <input type="hidden" name="dealer_type" id="dealer_type" value="<?=$dealer->dealer_type;?>">
            <input type="hidden" name="<?=$unique_master;?>" id="<?=$unique_master;?>" value="<?=$_SESSION['unique_master_for_SP'];?>">
            <input type="hidden" name="town" id="town" value="<?=$dealer->town_code;?>">
            <input type="hidden" name="area_code" id="area_code" value="<?=$dealer->area_code;?>">
            <input type="hidden" name="territory" id="territory" value="<?=$dealer->territory;?>">
            <input type="hidden" name="region" id="region" value="<?=$dealer->region;?>">
            <input  name="t_price" type="hidden" id="t_price" value="<?=$item_all->t_price?>" readonly="readonly"/>
            <input  name="cogs_price" type="hidden" id="cogs_price" value="<?=$item_all->production_cost?>" readonly="readonly"/>
            <input  name="d_price" type="hidden" id="d_price" value="<?=$item_all->d_price?>" readonly="readonly"/>
            <input  name="m_price" type="hidden" id="m_price" value="<?=$item_all->m_price?>" readonly="readonly"/>
            <input style="width:155px;"  name="do_type" type="hidden" id="do_type" value="<?=$do_type;?>" readonly/>

            <input  name="section_id" type="hidden" id="section_id" value="<?=$_SESSION[sectionid]?>">
            <input style="width:155px;"  name="company_id" type="hidden" id="company_id" value="<?=$_SESSION[companyid]?>"/>
            <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                <thead>
                <tr style="background-color: bisque">
                    <th style="text-align: center">Finish Goods Code</th>
                    <!--th style="text-align: center">In Stock</th-->
                    <th style="text-align: center">Invoice Qty</th>
                    <th style="text-align: center">Unit Price</th>
                    <th style="text-align: center">Unit Amount</th>
                    <th style="text-align: center"></th>
                </tr>
                </thead>



                <tbody>
                <tr>
                    <td align="center">
                        <select class="select2_single form-control"  tabindex="-1"  name="item_id" id="item_id"  onchange="javascript:reload(this.form)">
                            <option></option>
                            <? advance_foreign_relation($sql_item_id,$_GET[item_id]);?>
                        </select><br>Or Upload a file <br>
                        <input style="font-size:11px" type="file" id="file" name="file" value="<?=$file;?>" class="form-control col-md-7 col-xs-12" >
                    </td>






                    <td style="width:12%; vertical-align:middle" align="center" valign="middle">
                        <input  name="pkt_unit" type="text" id="pkt_unit" style="width:100%; height:37px" onkeyup="avail_amount(),count()" required="required" class="form-control col-md-7 col-xs-12" value="<?=($_GET[item_id]!='')? '' : '0';?>"  tabindex="4"/>
                        <input placeholder="Pcs" class="form-control col-md-7 col-xs-12" name="dist_unit" type="hidden"  id="dist_unit" style="width:45%; height:37px; margin-left:5px" onkeyup="avail_amount(),count()"/>
                        <input name="pkt_size" type="hidden" class="input3" id="pkt_size"  style="width:55px;"  value="<?=$item_all->pack_size?>" readonly/>
                    </td>

                    <td style="width:10%; vertical-align:middle" align="center">
                        <input type="text" id="unit_price" style="width:99%; height:37px; font-size:11px;text-align:center"  required="required"  name="unit_price" value="<?=($_GET[item_id]!='')? find_a_field('item_info','d_price','item_id='.$_GET[item_id].'') : '0';?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" class="unit_price" ></td>



                    <td align="center" style="width:10%; vertical-align:middle">
                        <input type="text" id="total_amt" style="width:99%; height:37px; font-size:11px;text-align:center"  required="required"  name="total_amt"  class="form-control col-md-7 col-xs-12" readonly autocomplete="off" >
                    </td>

                    <td align="center" style="width:5%; vertical-align:middle">
                        <?php
                        if($_GET[item_id]>0){
                            ?>
                            <button type="submit" class="btn btn-primary" style="font-size: 12px" name="add" id="add">Add</button>
                        <?php } else { ?>
                            <button type="submit" name="Import" onclick='return window.confirm("Are you confirm to Upload?");' class="btn btn-primary" style="font-size: 11px">Upload the File</button>
                        <?php } ?>
                    </td></tr> </tbody>


                <script>
                    $(function(){
                        $('#unit_price, #pkt_unit').keyup(function(){
                            var unit_price = parseFloat($('#unit_price').val()) || 0;
                            var pkt_unit<?=$item[item_id]?> = parseFloat($('#pkt_unit<?=$item[item_id]?>').val()) || 0;
                            $('#total_amt').val((unit_price * pkt_unit<?=$item[item_id]?>).toFixed(2));
                        });
                    });
                </script>
            </table></form>








        <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
            <input type="hidden" name="pc_code" id="pc_code" value="<?=$pc_code;?>">
            <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                <thead>
                <tr style="background-color: bisque">
                    <th style="vertical-align:middle">S/L</th>
                    <th style="vertical-align:middle">Package Name</th>
                    <th style="vertical-align:middle">Tran ID</th>
                    <th style="text-align:center;vertical-align:middle">Tran Date</th>
                    <th style="vertical-align:middle">Request Amount</th>
                    <th style="vertical-align:middle; text-align:center">Discounted Amount</th>
                    <th style="vertical-align:middle; text-align:center">Package Amount</th>
                    <th style="vertical-align:middle; text-align:center">PG Charge</th>
                    <th style="vertical-align:middle; text-align:center">Receivable</th>
                    <th style="vertical-align:middle; text-align:center">VAT (@ 15%)</th>
                    <th style="vertical-align:middle; text-align:center">PM (@ <?=$vendor->profit_margin;?>%)</th>
                    <th style="vertical-align:middle; text-align:center; width:10%">COGS</th>
                </tr>

                </thead>
                <tbody>
                <? while($data=mysqli_fetch_object($query)){
                    $id=$data->id;VAT_amount
                    ?>

                    <tr>
                        <td><?=++$z;?></td>
                        <td><input style="width: 50%; text-align:right" name="item_id<?=$id;?>" id="item_id<?=$id;?>"  type="hidden" value="<?=$data->item_id;?>">
                            <?=$data->item_name;?></td>
                        <td><?=$data->transactionID?></td>
                        <td><?=$data->tran_date?></td>
                        <td align="right"><?=$data->currency_amount?></td>
                        <td align="right"><?=($data->discount_amount>0)? $data->discount_amount : '';?></td>
                        <td align="right"><?=$data->amount?></td>
                        <td align="right"><?=$data->SSLCharge?></td>
                        <td align="right"><?=$data->receivable;?></td>
                        <td align="right"><?=$VAT=((($data->receivable/1.15)*15)/100);?> <input style="width: 50%; text-align:right" name="VAT_amount<?=$id;?>" id="VAT_amount<?=$id;?>"  type="hidden" value="<?=$VAT;?>"></td>
                        <td align="right"><?=$PM=(($data->receivable-$VAT)*$vendor->profit_margin/100);?></td>
                        <td align="right"><input style="width: 100%; text-align:right" name="cogs_price<?=$id;?>" id="cogs_price<?=$id;?>" readonly type="text" value="<?=$cogs=$PM;?>"></td>
                    </tr>
                    <?
                    $total_currency_amount=$total_currency_amount+$data->currency_amount;
                    $total_discount_amount=$total_discount_amount+$data->discount_amount;
                    $total_amount=$total_amount+$data->amount;
                    $total_SSLCharge=$total_SSLCharge+$data->SSLCharge;
                    $total_receivable=$total_receivable+$data->receivable;
                    $total_VAT=$total_VAT+$VAT;
                    $total_COGS=$total_COGS+$cogs;


                } ?>
                </tbody>
                <tr style="font-weight:bold">
                    <td colspan="4" style="text-align:right;">Total:</td>
                    <td align="right"><?=number_format($total_currency_amount,2)?></td>
                    <td align="right"><?=number_format($total_discount_amount,2)?></td>
                    <td align="right"><?=number_format($total_amount,2)?></td>
                    <td align="right"><?=number_format($total_SSLCharge,2)?></td>
                    <td align="right"><?=number_format($total_receivable,2)?></td>
                    <td align="right"><?=$total_VAT;?></td>
                    <td align="right"><?=$total_COGS;?></td>
                    <td align="right"><input style="width: 100%; text-align:right" readonly type="text" value="<?=$total_COGS;?>"></td>
                </tr>
            </table>


            <button type="submit" style="float: left; margin-left: 1%; font-size:12px" name="delete" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to the Production Deleted?");' class="btn btn-danger">Cancel the Invoice</button>
            <?php if($COUNT_details_data>0) { ?>
                <button type="submit" style="float: right; margin-right: 1%; font-size:12px" onclick='return window.confirm("Are you want to Finished?");' name="confirm" id="confirm" class="btn btn-success">Confirm and Finish the Invoice </button>
            <?php } else { echo '';} ?>

        </form>
            </div>
            </div>
            </div>



<?=$html->footer_content();?>