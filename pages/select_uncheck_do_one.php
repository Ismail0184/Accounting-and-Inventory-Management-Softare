<?php

session_start();
ob_start();
require "../../support/inc.all.php";

$title='Demand Order Approved';
do_calander('#fdate');
do_calander('#tdate');
$table_master='sale_do_master';
$unique_master='do_no';
$table_detail='sale_do_details';
$unique_detail='id';
$table_chalan='sale_do_chalan';
$unique_chalan='id';
$$unique_master=$_GET[$unique_master];
$table='sale_do_master';
$show='dealer_code';
$id='do_no';
$text_field_id='old_do_no';
//$target_url = '../do/do_check.php';
$target_url = 'select_uncheck_do_one.php';
$crud = new crud($table_master);

$current_status=find_a_field("".$table_master."","status","".$unique_master."=".$_GET[$unique_master]."");
$required_status="PROCESSING";

if(prevent_multi_submit()){


    if(isset($_POST['delete']))

    {


        $crud   = new crud($table_master);
        $condition=$unique_master."=".$$unique_master;
        $crud->delete($condition);

        $crud   = new crud($table_detail);
        $crud->delete_all($condition);


        unset($$unique_master);
        unset($_POST[$unique_master]);
        unset($_POST);
        $type=1;
        $msg='Successfully Deleted.';
    }

}
?>


<style>
    th.{margin: 5px}
    td.{margin: 5px}

</style>




    <div class="form-container_large">
        <form  method="post" name="cz" id="cz">
                        <table width="100%" border="1" style="border-collapse: collapse; background-color: bisque" cellspacing="5" cellpadding="5">

                            <?php
                            $res="select
m.*,
concat(d.dealer_custom_code,'- ',d.dealer_name_e) as dealer_name,
d.dealer_code,
d.account_code,
d.customer_type,
u.fname as entryby,
d.credit_limit


from

sale_do_master m,
dealer_info d,
users u

where
m.do_no='".$_GET[do_no]."' and
m.dealer_code=d.dealer_code and
u.user_id=m.entry_by";
                            $query = mysql_query($res);
                            $dataMaster=mysql_fetch_object($query);

                            ?>


                            <tr>
                                <td style="width: 20%"><strong>DO :</strong></td>
                                <td style="text-align: left"><?=$_GET[do_no];?></td>
                                <td><strong>DO Date :</strong></td>
                                <td style="width: 20%; text-align: left"><?=$dataMaster->do_date;?></td>
                            </tr>


                            <tr>
                                <td><strong>Dealer Name : </strong></td><td style="text-align: left"><?=$dataMaster->dealer_name;?></td>
                                <td><strong>Dealer Type : </strong></td><td style="text-align: left"><?=$dataMaster->customer_type;?></td>
                            </tr>


                            <tr>
                                <td><strong>Entry By : </strong></td><td style="text-align: left"><?=$dataMaster->entryby;?></td>
                                <td><strong>Entry At : </strong></td><td style="text-align: left"><?=$dataMaster->entry_at;?></td>
                            </tr>


                            <tr>
                                <td><strong>Account Balance : </strong></td><td style="text-align: left"><?=$accountbalance=find_a_field('journal','SUM(cr_amt-dr_amt)','ledger_id='.$dataMaster->account_code);?> BDT</td>
                                <td><strong>Credit Limit : </strong></td><td style="text-align: left"><?=$dataMaster->credit_limit;?> BDT</td>
                            </tr>


                            </table>







                            <table width="100%" border="1" style="border-collapse: collapse; background-color: aliceblue" cellspacing="0" cellpadding="0" id="grp">
                            <tbody>

                            <tr style="height:30px; text-align: center">

                                <th>Item Code</th>
                                <th>Item Description</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>

                            </tr>





                            <?
                            $enat=date('Y-m-d h:s:i');
                            $enby=$_SESSION['user']['id'];
                            $res="select
m.do_no,
m.do_section,
m.do_date,
m.depot_id,
m.commission,
m.commission_amount,
concat(d.dealer_custom_code,'- ',d.dealer_name_e) as dealer_name,
d.credit_limit_time as limittime,
d.dealer_code,
d.account_code,
dt.item_id,
dt.total_unit as QTY,
dt.unit_price as PriceGET,
dt.total_amt as total_amt,
dt.id as tr_no,
m.received_amt as RCV_AMT,
i.item_name,
i.finish_goods_code as fgcode

from

sale_do_master m,
dealer_info d ,
sale_do_details dt,
item_info i



where
m.do_no='".$_GET[do_no]."' and
m.dealer_code=d.dealer_code and
m.do_no=dt.do_no and
dt.item_id=i.item_id and
m.status in ('PROCESSING')
 order by dt.id";
                            $query = mysql_query($res);
                            while($data = mysql_fetch_object($query))

                            {


if(isset($_POST['confirm'])) {
    mysql_query("INSERT INTO journal_item (ji_date,item_id,warehouse_id,dealer_id,item_ex,tr_from,do_no,entry_by,entry_at,ip,tr_no,section_id,company_id) VALUES ('" . $data->do_date . "','$data->item_id','$data->depot_id','$data->dealer_code','$data->QTY','Sales','$data->do_no','$enby','$enat','$ip','$data->tr_no','','')");
    mysql_query("UPDATE sale_do_master SET status='CHECKED' where do_no='" . $_GET[do_no] . "'");
    mysql_query("UPDATE sale_do_details SET status='CHECKED',do_date='" . $data->do_date . "' where do_no='" . $_GET[do_no] . "'");
    mysql_query("Delete from sale_do_details where item_id='1096000100010313' and do_no='" . $_GET[do_no] . "'");

    if($data->limittime=='For one time DO')
    { mysql_query("UPDATE dealer_info SET credit_limit_time='',credit_limit ='' where dealer_code='".$data->dealer_code."'")	;}

    }  ?>

                                <tr>
                                    <td style="padding:0;text-align:left" width="0">&nbsp;<?=$data->fgcode;?></td>
                                    <td style="padding:0;text-align:left" width="0">&nbsp;<?=$data->item_name; if($data->total_amt==0){ echo '<font style="color: red; margin-left: 5px">[Free]</font>';} ?></td>
                                    <td style="padding:0; text-align:right" width="0"><?=$data->QTY?> </td>
                                    <td style="padding:0; text-align:right" width="0"><?=$data->PriceGET?> </td>
                                    <td style="padding:0; text-align:right" width="0"><?=number_format($data->total_amt,2)?> </td> </tr>

                                <?
                            $warehouseid=$data->depot_id;
                            $totalamount=$totalamount+$data->total_amt;
                            $commissionamount=$data->commission_amount;
                            }




if(isset($_POST['confirm']))

{
    $wddid = $warehouseid;


///////////////////////// for shafipur depo sms function start from here

///////////////////////// for nazmul

    if($wddid=='5'){
        $sendto='01952244030';
        $dear='Dear Mr. Anwar';
        $line=' A new DO has been created.';
        $line2=' DO no is: '.$$unique_master.'.';
        $line3=" Please take necessary action for delivery the DO's item.";
        $by=find_a_field('users','fname','user_id='.$_SESSION['user']['id']);
        $createby=' DO Created By: '.$by;
        $message=$dear.
            $line.
            $line2.
            $line3.      $createby;

        try
        {
            $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
            $paramArray = array(
                'userName'=>"01863636363",
                'userPassword'=>"86890",
                'mobileNumber'=> $sendto,
                'smsText'=>$message,
                'type'=>"TEXT",
                'maskName'=> "DemoMask", 'campaignName'=>"icpbd.com"
            );
            $value = $soapClient->__call("OneToOne", array($paramArray));
            //print_r($value);
        }
        catch (Exception $e) {
            //echo $e;
        }}



//////////////////////// end of nazmul////////////////////////////////////


/////////////////////// for masud rana////////////////////////////////


    if($wddid=='5'){
        $sendto1='01952244099';
        $dear='Dear Masud';
        $line=' A new DO has been created.';
        $line2=' DO no is: '.$$unique_master.'.';
        $line3=" Please take necessary action for delivery the DO's item.";
        $by=find_a_field('users','fname','user_id='.$_SESSION['user']['id']);
        $createby=' DO Created By: '.$by;
        $message=$dear.
            $line.
            $line2.
            $line3.      $createby;

        try
        {
            $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
            $paramArray = array(
                'userName'=>"01863636363",
                'userPassword'=>"86890",
                'mobileNumber'=> $sendto1,
                'smsText'=>$message,
                'type'=>"TEXT",
                'maskName'=> "DemoMask", 'campaignName'=>"icpbd.com"
            );
            $value = $soapClient->__call("OneToOne", array($paramArray));
            //print_r($value);
        }
        catch (Exception $e) {
            //echo $e;
        }
        $emailId = 'm.m.rana@icpbd.com';
        if($emailId!=''){
            $to = $emailId;
            $subject = "New DO has been created";
            $txt1 = "<p>Dear Masud Rana,</p>
				<p>A new DO has been created.</p>
				<p>DO no is: <b>".$$unique_master."<b></p>
				<p>Please take necessary action for delivery DO's item</p>
				<p>DO Created By- <b>".find_a_field('users','fname','user_id='.$_SESSION['user']['id'])."<b></p>
				<p>This EMAIL is automatically generated by ERP Software.</p>";


            $txt=$txt1.$txt2.$tr;
            $from = 'erp@icpbd.com';
            $headers = "";
            $headers .= "From: ICP ERP <erp@icpbd.com> \r\n";
            $headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($to,$subject,$txt,$headers);
        }

    }







    if($wddid=='12'){

/////////////////////// for dhaka depo ////////////////////////////////
/////////////////////// shuvo
        $sendto='01952244020';
        $dear='Dear Tanvir.';
        $line=' A new DO has been created.';
        $line2=' DO no is: '.$$unique_master.'.';
        $line3=" Please take necessary action for delivery the DO's item.";
        $by=find_a_field('users','fname','user_id='.$_SESSION['user']['id']);
        $createby=' DO Created By: '.$by;
        $message=$dear.
            $line.
            $line2.
            $line3.      $createby;

        try
        {
            $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
            $paramArray = array(
                'userName'=>"01863636363",
                'userPassword'=>"86890",
                'mobileNumber'=> $sendto,
                'smsText'=>$message,
                'type'=>"TEXT",
                'maskName'=> "DemoMask", 'campaignName'=>"icpbd.com"
            );
            $value = $soapClient->__call("OneToOne", array($paramArray));
            //print_r($value);
        }
        catch (Exception $e) {
            //echo $e;
        }



    }


    if($data->dealer_code=='77'){


        $sendto='01952244067';
        $dear='Dear Mr. Asif';
        $line=' A new DO has been created.';
        $line2=' DO no is: '.$$unique_master.'.';
        $line3=" Please take necessary action for delivery the DO's item.";
        $by=find_a_field('users','fname','user_id='.$_SESSION['user']['id']);
        $createby=' DO Created By: '.$by;
        $message=$dear.
            $line.
            $line2.
            $line3.      $createby;

        try
        {
            $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
            $paramArray = array(
                'userName'=>"01863636363",
                'userPassword'=>"86890",
                'mobileNumber'=> $sendto,
                'smsText'=>$message,
                'type'=>"TEXT",
                'maskName'=> "DemoMask", 'campaignName'=>"icpbd.com"
            );
            $value = $soapClient->__call("OneToOne", array($paramArray));
            //print_r($value);
        }
        catch (Exception $e) {
            //echo $e;
        }} ?>
        <meta http-equiv="refresh" content="0;select_uncheck_do_ismail.php">
        <?php }  ?>

                            <tr style="font-size: 11px; font-weight: bold">
                                <td colspan="4">Total</td>
                                <td style="text-align:right"><?=number_format($totalamount,2);?></td>
                            </tr>

                            <?php
                            $commission=find_a_field('sale_do_master','commission','do_no='.$_GET[do_no]);
                            if($commission>0) { ?>
                            <tr style="font-size: 11px; font-weight: bold">
                                <td colspan="4">Commission</td>
                                <td style="text-align:right"><?=number_format($commissionamount,2);?></td>
                            </tr><?php } ?>
                            <tr style="font-size: 11px; font-weight: bold">
                                <td colspan="4">Total Receivable Amount</td>
                                <td style="text-align:right"><?=number_format($totalamount-$commissionamount,2);?></td>
                            </tr>
                            </tbody>
                            </table>



<?php

$do_amount=find_a_field_sql('select sum(total_amt) from sale_do_details where do_no='.$_GET[do_no])-find_a_field('sale_do_master','commission_amount','do_no='.$_GET[do_no]);
$accountbalance_final=$accountbalance+$dataMaster->credit_limit;
if($current_status!=$required_status && $current_status!="MANUAL" && $current_status!="RETURNED"){ echo '<h3 style="text-align:center; color:red; font-weight:bold"><i>This Demond Order has been checked!!</i></h3>';} else { ?>
            <table align="center">
                <tr style="background-color: transparent">
                    <input type="hidden" name="<?=$unique_master?>" id="<?=$unique_master?>" value="<?=$$unique_master?>" >
                    <td colspan="2"><input name="delete" id="delete"  type="submit" class="btn1" value="DELETE DO" onclick='return window.confirm("Are you sure you want to approved & sent to warehouse the DO?");' style="width:100px; font-weight:bold; font-size:12px;color:#F00; height:30px; float: left" /></td>
                <td align="center" colspan="3" style="border: none">
                    <?php if($accountbalance_final>$do_amount) { ?>
                    <input  name="confirm" type="submit" value="DO Approved & SEND to <?=find_a_field('warehouse','warehouse_name','warehouse_id='.$warehouseid);?>" style="width:auto; font-weight:bold; font-size:12px; margin-left:10px;height:30px; color:#090; float: right" onclick='return window.confirm("Are you sure you want to approved & sent to warehouse the DO?");' />
        <?php } else { ?> <h5 style="text-align: center; color: white; font-weight: bold">Credit is not available to proceed the order. Contact the Accounts Department for credit limits.</h5><?php } ?>
        </td>
            </tr>
            </table>
    <?php } ?>
        </form>
    </div>



<?

$main_content=ob_get_contents();

ob_end_clean();
include ("../../template/main_layout.php");

?>
