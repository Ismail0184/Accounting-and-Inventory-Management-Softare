<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='do_no';
$unique_field='name';
$table="sale_do_master";
$table_details="sale_do_details";
$table_challan="sale_do_chalan";

$page='account_unchecked_invoice_list.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        //echo "<script>self.opener.location = 'QC_sales_return_view.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $results="Select srd.*,i.* from ".$table_challan." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique."=".$$unique." order by srd.id";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)) {
            $i = $i + 1;
            $ids = $row[id];
            $SD_VAT_total_unit=$_POST['qty_'.$ids];
            $up=mysqli_query($conn, "Update ".$table_challan." set SD_VAT_total_unit='$SD_VAT_total_unit' where id='$ids'");

        }

        $jv=next_journal_voucher_id();
        $transaction_date=date('Y-m-d');
        $enat=date('Y-m-d h:s:i');
        $cd =$_POST[c_date];
        $c_date=date('Y-m-d' , strtotime($cd));
        $invoice=$_POST[invoice];
        $date=date('d-m-y' , strtotime($transaction_date));
        $j=0;
        for($i=0;$i<strlen($date);$i++)
        {
            if(is_numeric($date[$i]))
            { $time[$j]=$time[$j].$date[$i];
            } else {
                $j++; } }
        $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
        $re=mysqli_query($conn, "Select distinct chalan_no from sale_do_chalan where do_no='".$$unique."' order by chalan_no desc limit 1");
        $ch_row=mysqli_fetch_array($re);

        if($_POST[dr_amount_1]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], $_POST[cr_amount_1], Journal_info, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_2], $_POST[narration_2], $_POST[dr_amount_2], $_POST[cr_amount_2], Journal_info, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
        if($_POST[cr_amount_3]>0) {
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_3], $_POST[narration_3], $_POST[dr_amount_3], $_POST[cr_amount_3], Journal_info, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
            add_to_journal_new($transaction_date, $proj_id, $jv, $date, $_POST[ledger_4], $_POST[narration_4], $_POST[dr_amount_4], $_POST[cr_amount_4], Journal_info, $ch_row[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear);
        }
        $up_master=mysqli_query($conn, "UPDATE ".$table." SET SD_VAT_status='COMPLETED' where ".$unique."=".$$unique."");
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }



}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
$dealer_info=find_all_field("dealer_info","","dealer_code=".$dealer_code."");
$config_group_class=find_all_field("config_group_class","","1");

?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=600,left = 230,top = -1");}
    </script>
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content.php'; ?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Code</th>
                            <th style="vertical-align: middle">Finish Goods</th>
                            <th style="width:5%; text-align:center;vertical-align: middle">UOM</th>
                            <th style="text-align:center; vertical-align: middle">Sales Qty</th>

                            <th style="text-align:center; vertical-align: middle;">Qty</th>
                            <th style="text-align:center;background-color: darkturquoise;vertical-align: middle; color: white">SD Rate</th>
                            <th style="text-align:center;background-color: darkturquoise;vertical-align: middle; color: white">SD Amount</th>
                            <th style="text-align:center; background-color: darkmagenta;vertical-align: middle; color: white">VAT Rate</th>
                            <th style="text-align:center; background-color: darkmagenta;vertical-align: middle; color: white">VAT Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $results="Select srd.*,i.* from ".$table_challan." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique."=".$$unique." order by srd.id";
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
                                <td align="center" style=" text-align:center"><?=$row[total_unit];?></td>
                                <td align="center" style=" text-align:center"><input type="text" name="qty_<?=$ids;?>" id="qty_<?=$ids;?>" style="width: 98%; text-align: center" <?php if($row[total_amt]==0){ echo 'readonly'; }  else { echo ''; } ?> class="qty_<?=$ids;?>" autocomplete="off"></td>
                                <td align="center" style=" text-align:right"><input type="text"  style="width: 100%" value="<?=$row[SD];?>"></td>
                                <td align="center" style=" text-align:right; display: none"><input type="text" name="SD_rate_<?=$ids;?>" readonly id="SD_rate_<?=$ids;?>" style="width: 100%" value="<?=($row[SD]*$row[SD_percentage])/100;?>" class="SD_rate_<?=$ids;?>"></td>
                                <td align="center" style=" text-align:right"><input type="text" style="width: 98%;  text-align: right" readonly name="SD_sum_<?=$ids;?>" id="SD_sum_<?=$ids;?>" class="SD_sum"></td>
                                <td align="center" style=" text-align:center"><input type="text" style="width: 100%"  value="<?=$row[VAT];?>"></td>
                                <td align="center" style=" text-align:center; display: none"><input type="text" style="width: 100%"  value="<?=($row[VAT]*$row[VAT_percentage])/100;?>" name="VAT_rate_<?=$ids;?>" id="VAT_rate_<?=$ids;?>" class="VAT_rate_<?=$ids;?>"></td>
                                <td align="center" style="text-align:right"><input type="text" style="width: 98%; text-align: right" readonly name="VAT_sum_<?=$ids;?>" id="VAT_sum_<?=$ids;?>" class="VAT_sum"></td>


                                <script>
                                    $(function(){
                                        $('#SD_rate_<?=$ids;?>, #qty_<?=$ids;?>').keyup(function(){
                                            var SD_rate_<?=$ids;?> = parseFloat($('#SD_rate_<?=$ids;?>').val()) || 0;
                                            var qty_<?=$ids;?> = parseFloat($('#qty_<?=$ids;?>').val()) || 0;
                                            $('#SD_sum_<?=$ids;?>').val((SD_rate_<?=$ids;?> * qty_<?=$ids;?>).toFixed(2));
                                        });
                                    });
                                    $(function(){
                                        $('#VAT_rate_<?=$ids;?>, #qty_<?=$ids;?>').keyup(function(){
                                            var VAT_rate_<?=$ids;?> = parseFloat($('#VAT_rate_<?=$ids;?>').val()) || 0;
                                            var qty_<?=$ids;?> = parseFloat($('#qty_<?=$ids;?>').val()) || 0;
                                            $('#VAT_sum_<?=$ids;?>').val((VAT_rate_<?=$ids;?> * qty_<?=$ids;?>).toFixed(2));
                                        });
                                    });
                                </script>

                            </tr>
                            <?php } ?>
                        <script>
                            // we used jQuery 'keyup' to trigger the computation as the user type
                            $('.SD_sum').blur(function () {
                                // initialize the sum (total price) to zero
                                var sum = 0;
                                // we use jQuery each() to loop through all the textbox with 'price' class
                                // and compute the sum for each loop
                                $('.SD_sum').each(function() {
                                    sum += Number($(this).val());
                                });
                                // set the computed value to 'totalPrice' textbox
                                $('#totalSD').val((sum).toFixed(2));
                                $('#ttotalSD').val((sum).toFixed(2));
                                $('#ttotalSD2').val((sum).toFixed(2));
                            });

                            $('.VAT_sum').blur(function () {
                                // initialize the sum (total price) to zero
                                var sum = 0;
                                // we use jQuery each() to loop through all the textbox with 'price' class
                                // and compute the sum for each loop
                                $('.VAT_sum').each(function() {
                                    sum += Number($(this).val());
                                });
                                // set the computed value to 'totalPrice' textbox
                                $('#totalVAT').val((sum).toFixed(2));
                                $('#ttotalVAT').val((sum).toFixed(2));
                                $('#ttotalVAT2').val((sum).toFixed(2));
                            });
                        </script>

                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px; vertical-align: middle" align="right">Total Amount</td>
                            <td style="text-align:center; vertical-align: middle"><?=$tfree_qty;?></td>
                            <td style="text-align:right; vertical-align: middle"><?=number_format($tdiscount,2);?></td>
                            <td align="center" style="vertical-align: middle"></td>
                            <td align="center" style="vertical-align: middle"><input style="height: 25px; width: 100%; font-weight: bold; font-size: 12px; text-align: center" type='text' id='totalSD'  onkeyup="manage(this)" readonly /></td>
                            <td align="center" style="vertical-align: middle"><?=$ttotal_qty;?></td>
                            <td align="right" style="vertical-align: middle"><input style="height: 25px; width: 100%; font-weight: bold; font-size: 12px; text-align: center" type='text' id='totalVAT' onkeyup="manage(this)" readonly /></td>
                        </tr>
                    </table>
                    <?php  ?>

                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="width: 12%; text-align: center">For</th>
                            <th>Accounts Description</th>
                            <th style="text-align:center; width: 25%">Narration</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $re=mysqli_query($conn, "Select distinct chalan_no from ".$table_challan." where do_no=".$$unique." order by chalan_no desc limit 1");
                        $ch_row=mysqli_fetch_array($re);
                        $narrationSD = 'SD Expenses against CH#'.$ch_row[chalan_no].'/'.$ch_id.'(DO#'.$$unique.')';
                        $narrationVAT = 'VAT Expenses against CH#'.$ch_row[chalan_no].'/'.$ch_id.'(DO#'.$$unique.')';
                        ?>
                        <tr>
                            <td style="text-align: center; vertical-align: middle">1</td>
                            <td style="text-align: center; vertical-align: middle">SD Expenses</td>
                            <td style="vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_1"  name="ledger_1">
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_return_ledger, 'ledger_id="4016000100000000"'); ?>
                                </select>
                            </td>
                            <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_1" id="narration_1" value="<?=$narrationSD;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td align="center"><input type="text" name="dr_amount_1" id="ttotalSD"   class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td align="center"><input type="text" name="cr_amount_1" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center; vertical-align: middle">2</td>
                            <td style="text-align: center; vertical-align: middle">SD Payable Account</td>
                            <td>
                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_2"  name="ledger_2">
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $dealer_info->account_code, 'ledger_id="1005000700000000"'); ?>
                                </select>
                            </td>
                            <td style="text-align: center; vertical-align: middle"><input type="text" name="narration_2" id="narration_2" value="<?=$narrationSD;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_2" id=""  class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_2" id="ttotalSD2"  class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>

                        <tr>
                            <td style="text-align: center">3</td>
                            <td style="text-align: center">VAT Expenses</td>
                            <td><select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_3"  name="ledger_3">
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_return_ledger, 'ledger_id="4015000100000000"'); ?>
                                </select></td>

                            <td style="text-align: center"><input type="text" name="narration_3" id="narration_3" value="<?=$narrationVAT;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_3" id="ttotalVAT" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_3" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>
                        <tr>
                            <td style="text-align: center">4</td>
                            <td style="text-align: center">VAT Current Account</td>
                            <td><select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_4"  name="ledger_4">
                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $sales_return_ledger, 'ledger_id="1005000400000000"'); ?>
                                </select></td>

                            <td style="text-align: center"><input type="text" name="narration_4" id="narration_4" value="<?=$narrationVAT;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center"></td>
                            <td style="text-align: right"><input type="text" name="dr_amount_4"  class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                            <td style="text-align: right"><input type="text" name="cr_amount_4"  id="ttotalVAT2" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                        </tr>


                        </tbody>
                    </table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','SD_VAT_status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED' || $GET_status==''){  ?>
                        <p align="center">
                            <button type="submit" name="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");' style="font-size: 12px" id="btSubmit" disabled>Checked & Create SD VAT Journal </button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold; font-size: 12px"><i>This Sales Invoice has checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <?php
                    $y=date('Y');
                    $m=date('m');
                    ?>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?=$_POST[f_date];?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?=$_POST[t_date]?>" required   name="t_date" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Sales Invoice</button></td>
            </tr></table>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">

                        <?php
                        $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                        $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                        if(isset($_POST[viewreport])){
                            $res="Select m.do_no,m.do_no,m.do_date,d.dealer_name_e as dealer_name,w.warehouse_name,u.fname as entry_by,m.do_type,m.status
from

".$table." m,
warehouse w,
user_activity_management u,
dealer_info d

 where
 w.warehouse_id=m.depot_id and
 d.dealer_code=m.dealer_code and
 m.do_date between '$from_date' and '$to_date' and m.SD_VAT_status in ('UNCHECKED','') and m.status in ('COMPLETED') group by m.do_no order by m.".$unique." DESC ";
                            echo $crud->link_report_voucher($res,$link);?>
                            <?php } ?>
                            <?php mysqli_close($conn); ?>
                            </div></div></div></form>
<?php } ?>

<?php require_once 'footer_content.php' ?>
<script>
    function manage(txt) {
        var bt = document.getElementById('btSubmit');
        if (txt.value != '') {
            bt.disabled = false;
        }
        else {
            bt.disabled = true;
        }
    }
</script>
