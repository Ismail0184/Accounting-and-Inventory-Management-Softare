<?php
require_once 'support_file.php';
$title='Mushak 6.3';
$page="acc_mushak_6.3.php";
$table='purchase_return_details';
$unique='id';
$$unique=$_GET[$unique];
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$timess = $dateTime->format("h:i A");
$year=date('Y');
$now=date('Y-m-d h:s:i');
$table_VAT_Master='VAT_mushak_6_3';
$table_VAT_details='VAT_mushak_6_3_details';
$mushaks=find_all_field('VAT_mushak_6_3','','source in ("Purchase_Returned") and do_no='.$$unique);

if(prevent_multi_submit()){
if(isset($_POST[record])){
  if($_POST[mushak_no]>0 && !empty($_POST[issue_date])){
  $_POST[do_no]=$$unique;
  $_POST[mushak_no]=$_POST[mushak_no];
  $_POST[warehouse_id]=$_POST[warehouse_id];
  $_POST[dealer_code]=$_POST[dealer_code];
  $_POST[issue_date]=$_POST[issue_date];
  $_POST[issue_time]=$_POST[issue_time];
  $_POST[responsible_person]=$_POST[responsible_person];
  $_POST[entry_by]=$_SESSION[userid];
  $_POST[entry_at]=$now;
  $_POST[year]=$year;
  $crud = new crud($table_VAT_Master);
  $crud->insert();

  $query="SELECT sdc.*,SUM(sdc.amount) as total_unit,i.item_name,i.unit_name,i.VAT from ".$table." sdc, item_info i where sdc.item_id=i.item_id and i.item_id not in ('1096000100010312') and sdc.".$unique."=".$$unique." group by i.item_id order by i.finish_goods_code";
  $result=mysqli_query($conn, $query);
  while($data=mysqli_fetch_object($result)):
    $id=$data->item_id;
    $_POST[item_id]=$id;
    $_POST[total_unit]=$_POST['total_unit'.$id];
    $_POST[unit_price]=$_POST['unit_price'.$id];
    $_POST[total_price]=$_POST['total_price'.$id];
    $_POST[rate_of_SD]=$_POST['rate_of_SD'.$id];
    $_POST[amount_of_SD]=$_POST['amount_of_SD'.$id];
    $_POST[rate_of_VAT]=$_POST['rate_of_VAT'.$id];
    $_POST[amount_of_VAT]=$_POST['amount_of_VAT'.$id];
    $_POST[total_including_all]=$_POST['total_including_all'.$id];
    if($_POST['total_unit'.$id]>0){
    $crud = new crud($table_VAT_details);
    $crud->insert();}
  endwhile;
  mysqli_query($conn, "Update purchase_return_master SET mushak_challan_status='RECORDED' where id=".$_GET[$unique]);
  unset($_POST);
  //echo "<script>window.close(); </script>";

}}}

$mushak=find_all_field('VAT_mushak_6_3','','source in ("Purchase_Returned") and do_no='.$_GET[$unique]);
$COUNT_mushak=find_a_field('VAT_mushak_6_3','COUNT(mushak_no)','source in ("Purchase_Returned") and do_no='.$_GET[$unique]);
$status=find_a_field('VAT_mushak_6_3','COUNT(id)','source in ("Purchase_Returned") and do_no='.$_GET[$unique]);
$do_master=find_all_field('purchase_return_master','','id='.$_GET[$unique]);
$warehouse_master=find_all_field('warehouse','','warehouse_id='.$do_master->warehouse_id);
$dealer_master=find_all_field('vendor','','vendor_id='.$do_master->vendor_id);
$VAT_master=find_all_field('VAT_mushak_6_3','','source="Purchase_Returned" and do_no='.$_GET[$unique]);
$latest_id=find_a_field('VAT_mushak_6_3','MAX(mushak_no)','year='.$year.' and warehouse_id='.$do_master->warehouse_id);

if($status>0){
  if($_GET[group_by]=='VAT_item_group'){

  $query="SELECT
  mus.*,
  SUM(mus.total_unit) as total_unit,
  mus.total_price,
  vtg.group_name as item_name,
  mus.rate_of_SD,
  mus.amount_of_SD,
  mus.rate_of_VAT,
  mus.amount_of_VAT,
  mus.total_including_all,
  i.unit_name,
  i.SD AS VAT,
  mus.source
  from
  item_info i,
  VAT_mushak_6_3_details mus,
  VAT_item_group vtg
  where
  i.VAT_item_group=vtg.group_id and
  mus.source in ('Purchase_Returned') and
  mus.item_id=i.item_id and
  i.item_id not in ('1096000100010312') and
  mus.do_no=".$_GET[$unique]."
  group by i.VAT_item_group order by i.finish_goods_code";
} else {
  $query="SELECT
  mus.*,
  SUM(mus.total_unit) as total_unit,
  mus.total_price,
  i.item_name,
  mus.rate_of_SD,
  mus.amount_of_SD,
  mus.rate_of_VAT,
  mus.amount_of_VAT,
  mus.total_including_all,
  i.unit_name,
  i.SD AS VAT,
  mus.source
  from
  item_info i,
  VAT_mushak_6_3_details mus
  where
  mus.source in ('Purchase_Returned') and
  mus.item_id=i.item_id and
  i.item_id not in ('1096000100010312') and
  mus.do_no=".$_GET[$unique]."
  group by mus.item_id order by i.finish_goods_code";
}


} else {
$query="SELECT sdc.*,SUM(sdc.qc_qty) as total_unit,i.item_name,i.unit_name,i.SD AS VAT,i.VAT_percentage,i.SD_percentage from ".$table." sdc, item_info i where sdc.item_id=i.item_id and i.item_id not in ('1096000100010312') and sdc.m_id=".$_GET[$unique]." group by i.item_id order by i.finish_goods_code";
}
$result=mysqli_query($conn, $query);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<header>
    <title><?=$title?></title>
    <script type="text/javascript">
        function hide()
        {
            document.getElementById("pr").style.display = "none";
        }
    </script>
    <style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #white;}
        #customers tr:hover {background-color: #F0F0F0;}
        td{}
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
</header>
<body style="font-size: 11px">
  <?php if($status>0){ ?>
<div id="pr" style="margin-left:48%">
    <div align="left">
        <form id="form1" name="form1" method="post" action="">
            <p><input name="button" type="button" onclick="hide();window.print();" value="Print" /></p>
        </form>
    </div>
<form action="<?=$pate?>" method="get">
<input type="hidden" name="<?=$unique?>"  value="<?=$$unique?>" />
<?php if($_GET[group_by]=='item_id'){?>
          <input type="hidden" name="group_by"  value="VAT_item_group" />
                  <p><input type="submit"  value="View by GROUP" /></p>
<?php } elseif($_GET[group_by]=='VAT_item_group'){?>
<input type="hidden" name="group_by"  value="item_id" />
        <p><input type="submit"  value="View by Item" /></p>
      <?php } else { ?>
        <input type="hidden" name="group_by"  value="VAT_item_group" />
                <p><input type="submit"  value="View by GROUP" /></p>
      <?php } ?>
    </form>
</div>
<?php } ?>
<form method="post" action="">
  <input type="hidden" name="warehouse_id" value="<?=$do_master->warehouse_id?>">
  <input type="hidden" name="dealer_code" value="<?=$do_master->vendor_id?>">
  <input type="hidden" name="responsible_person" value="<?=$warehouse_master->VAT_responsible_person?>">
  <input type="hidden" name="chalan_no" value="<?=$chalan_no?>">
  <input type="hidden" name="jv_no" value="<?=$jv_no?>">
  <input type="hidden" name="jvdate" value="<?=$mushak->issue_date?>">
  <input type="hidden" name="source" value="Purchase_Returned">
<table style="width: 100%">
<td style="width: 30%; text-align: right;"><img src="bd.png" width="50" height="50" style="margin-top: 50px;
  padding:0px;"></td>
    <td style="text-align: center">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার জাতীয় রাজস্ব র্বোড</td>
    <td style="width: 30%"><div style="text-align: center;height: 30px; margin-top: 50px; vertical-align:middle; width: 100px; border: 1px solid black; font-size: 13px;"><strong>মূসক-৬.৩</strong></div></td>
</table>
<div style="text-align: center"><strong>কর চালানপত্র</strong></div>
<div style="text-align: center">[বিধি ৪০ এর উপ-বিধি (১) এর দফা (গ) ও দফা (চ) দ্রষ্টব্য]</div>
<div style="text-align: center">নিবন্ধিত ব্যক্তির নাম: <?=$_SESSION[company_name]?></div>
<div style="text-align: center">নিবন্ধিত ব্যক্তির বিআইএন: <?=find_a_field('company','BIN','company_id="'.$_SESSION[companyid].'" and section_id='.$_SESSION[sectionid])?></div>
<div style="text-align: center">চালানপত্র ইস্যুর ঠিকানা : <?=$_SESSION['company_address'];?></div>
<br>
<table style="width: 100%;">
    <tr>
        <th style="width: 10%; text-align: left">ক্রেতার নাম</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->vendor_name?></td>
        <th style="width: 10%; text-align: right">চালানপত্র নম্বর</th><th style="width: 1%">:</th><td style="width: 29%">
<?php if($status>0){ echo $VAT_master->mushak_no; } else { ?>
          <input type="text" style="font-size:11px;width:40px" value="<?=$latest_id?>" readonly name="mushak_no_current">
          <input type="text" style="font-size:11px;width:73px" value="<?=$latest_id+1?>" name="mushak_no">
<?php } ?>
        </td>
    </tr>
    <tr>
        <th style="width: 10%; text-align: left">ক্রেতার বিআইএন</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->TIN_BIN?></td>
        <th style="width: 10%; text-align: right">ইস্যুর তারিখ</th><th style="width: 1%">:</th><td style="width: 29%"><?php if($status>0){ echo $VAT_master->issue_date; } else { ?>
                  <input type="date" value="<?=date('Y-m-d')?>" style="font-size:11px;" name="issue_date">
        <?php } ?></td>
    </tr>
    <tr>
        <th style="width: 10%; text-align: left">সরবরাহের গন্তব্যস্থল</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->address?></td>
        <th style="width: 10%; text-align: right">ইস্যুর সময়</th><th style="width: 1%">:</th><td style="width: 29%"><?php if($status>0){ echo $VAT_master->issue_time; } else { ?>
                  <input type="text" value="<?=$timess;?>" style="font-size:11px;" name="issue_time"><?php } ?></td>
    </tr>
</table>
<br>

<table id="customers" style="border-collapse: collapse; border: 1px solid #CCC; width: 100%">
    <thead>
    <tr>
        <th style="border: 1px solid #CCC;width: 1%">ক্রমিক</th>
        <th style="border: 1px solid #CCC;">পণ্য বা সেবার বর্ণনা (প্রযোজ্য ক্ষেত্রে ব্রান্ড নামসহ)</th>
        <th style="border: 1px solid #CCC;width: 5%">সরবরাহের একক</th>
        <th style="border: 1px solid #CCC;width: 5%">পরিমাণ</th>
        <th style="border: 1px solid #CCC;width: 5%">একক মূল্য১ (টাকায়)</th>
        <th style="border: 1px solid #CCC;width: 10%">মোট মূল্য১ (টাকায়)</th>
        <th style="border: 1px solid #CCC;width: 5%">সম্পূরক শুল্কেরহার</th>
        <th style="border: 1px solid #CCC;width: 10%">মূল্যসম্পূরক শুল্কের পরিমাণ (টাকায়)</th>
        <th style="border: 1px solid #CCC; width: 5%">মূল্য সংযোজন করের হার/ সুনির্দিষ্ট কর</th>
        <th style="border: 1px solid #CCC;width: 10%">মূল্য সংযোজন
            কর/ সুনির্দিষ্ট কর
            এর পরিমণ
            (টাকায়)</th>
        <th style="border: 1px solid #CCC;width: 10%">সকল প্রকার শুল্ক ও
            করসহ মূল্য</th>
    </tr>
    <tr>
        <th style="border: 1px solid #CCC; text-align: center">(1)</th>
        <th style="border: 1px solid #CCC; text-align: center">(2)</th>
        <th style="border: 1px solid #CCC; text-align: center">(3)</th>
        <th style="border: 1px solid #CCC; text-align: center">(4)</th>
        <th style="border: 1px solid #CCC; text-align: center">(5)</th>
        <th style="border: 1px solid #CCC; text-align: center">(6)</th>
        <th style="border: 1px solid #CCC; text-align: center">(7)</th>
        <th style="border: 1px solid #CCC; text-align: center">(8)</th>
        <th style="border: 1px solid #CCC; text-align: center">(9)</th>
        <th style="border: 1px solid #CCC; text-align: center">(10)</th>
        <th style="border: 1px solid #CCC; text-align: center">(11)</th>
    </tr>
    </thead>


    <tbody>
      <?php
      if($status>0):
      while($data=mysqli_fetch_object($result)):
        $id=$data->item_id;
        $ab=$data->SD_percentage;$ef=$data->VAT_percentage;
        $cd=$data->total_unit*$data->VAT*$ab;?>
      <tr>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$i=$i+1?></td>
      <td style="border: 1px solid #CCC;text-align: left; margin: 10px"><?=$data->item_name?></td>
      <td style="border: 1px solid #CCC;text-align: center"><?=$data->unit_name?></td>
      <td style="border: 1px solid #CCC;text-align: center"><?=$data->total_unit?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=number_format($data->unit_price,2)?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=number_format($data->total_price,2)?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=($data->rate_of_SD>0)? $data->rate_of_SD : '0' ?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=($data->amount_of_SD>0)? number_format($data->amount_of_SD,2) : '-' ?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=($data->rate_of_VAT>0)? number_format($data->rate_of_VAT) : '0' ?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=($data->amount_of_VAT>0)? number_format($data->amount_of_VAT,2) : '-' ?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=($data->total_including_all>0)? number_format($data->total_including_all,2) : '-' ?></td>
      </tr>


      <?php
      $total_unit=$total_unit+$data->total_unit;
      $total_total_price=$total_total_price+$data->total_price;
      $total_amount_of_SD=$total_amount_of_SD+$data->amount_of_SD;
          $total_amount_of_VAT=$total_amount_of_VAT+$data->amount_of_VAT;
          $total_total_including_all=$total_total_including_all+$data->total_including_all;
      endwhile; ?>
      <tr><th>Total</th><td></td><td></td><th style="border: 1px solid #CCC;text-align: center;"><?=$total_unit?></th>
      <td></td>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_total_price,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_amount_of_SD,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$c?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_amount_of_VAT,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_total_including_all,2)?></th>
      </tr>








    <?php else:
      while($data=mysqli_fetch_object($result)):
        $id=$data->item_id;
        $ab=$data->SD_percentage;$ef=$data->VAT_percentage;
        $cd=$data->total_unit*$data->VAT*$ab;?>
      <tr>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$i=$i+1?></td>
      <td style="border: 1px solid #CCC;text-align: left; margin: 10px"><?=$data->item_name?></td>
      <td style="border: 1px solid #CCC;text-align: center"><?=$data->unit_name?></td>
      <td style="border: 1px solid #CCC;text-align: center">
        <input type="hidden" value="<?=$data->total_unit?>" style="font-size:11px; text-align:center;" name="do_qty<?=$id?>" id="do_qty<?=$id?>">
        <SCRIPT language=JavaScript>
            function doAlert<?=$id?>(form)
            {   var val=form.total_unit<?=$id?>.value;
                var val2=form.do_qty<?=$id?>.value;
                if (Number(val)>Number(val2)){
                    alert('oops!! exceed stock limit!! Thanks');
                    form.total_unit<?=$id?>.value='';}
                form.total_unit<?=$id?>.focus();
            }</script>
      <input type="number" value="<?=$data->total_unit?>" onkeyup="doAlert<?=$id?>(this.form);" style="font-size:11px; text-align:center;" name="total_unit<?=$id?>" id="total_unit<?=$id?>" class="total_unit<?=$id?>"></td>

      <td style="border: 1px solid #CCC;text-align: right;">
        <input type="number" tabindex="-1" readonly value="<?=$data->VAT?>" style="font-size:11px; text-align:right;border: 1px solid #999999;background-color: #F0F0F0;color: #666666;" name="unit_price<?=$id?>" id="unit_price<?=$id?>" class="unit_price<?=$id?>"></td>

      <td style="border: 1px solid #CCC;text-align: right;">
                <input type="number" tabindex="-1" readonly value="<?=$total_unit_amount=$data->total_unit*$data->VAT?>" style="font-size:11px; text-align:right;border: 1px solid #999999;background-color: #F0F0F0;color: #666666;" name="total_price<?=$id?>" id="total_price<?=$id?>" class="total_price<?=$id?>"></td>

      <td style="border: 1px solid #CCC;text-align: right;">
                <input type="number" tabindex="-1" readonly value="<?=($data->SD_percentage>0)? $data->SD_percentage : '0';?>" style="font-size:11px; text-align:right;border: 1px solid #999999;background-color: #F0F0F0;color: #666666;" name="rate_of_SD<?=$id?>" id="rate_of_SD<?=$id?>" class="rate_of_SD<?=$id?>"></td>

      <td style="border: 1px solid #CCC;text-align: right;">
                <input type="number" tabindex="-1" readonly value="<?=$amount_of_SD=(($total_unit_amount*$data->SD_percentage)/100)?>" style="font-size:11px; text-align:right;border: 1px solid #999999;background-color: #F0F0F0;color: #666666;" name="amount_of_SD<?=$id?>" id="amount_of_SD<?=$id?>" class="amount_of_SD<?=$id?>"></td>

      <td style="border: 1px solid #CCC;text-align: right;">
                <input type="number" tabindex="-1" readonly value="<?=$data->VAT_percentage?>" style="font-size:11px; text-align:right;border: 1px solid #999999;background-color: #F0F0F0;color: #666666;" name="rate_of_VAT<?=$id?>" id="rate_of_VAT<?=$id?>" class="rate_of_VAT<?=$id?>"></td>

      <td style="border: 1px solid #CCC;text-align: right;">
                <input type="number" tabindex="-1" readonly value="<?=$total_VAT=((($total_unit_amount+$amount_of_SD)*$data->VAT_percentage)/100);?>" style="font-size:11px; text-align:right;border: 1px solid #999999;background-color: #F0F0F0;color: #666666;" name="amount_of_VAT<?=$id?>" id="amount_of_VAT<?=$id?>" class="amount_of_VAT<?=$id?>"></td>

      <td style="border: 1px solid #CCC;text-align: right;">
                <input type="number" tabindex="-1" readonly value="<?=$actual_VAT=$total_unit_amount+$amount_of_SD+$total_VAT;?>" style="font-size:11px; text-align:right;border: 1px solid #999999;background-color: #F0F0F0;color: #666666;" name="total_including_all<?=$id?>" id="total_including_all<?=$id?>" class="total_including_all<?=$id?>"></td>
      </tr>

      <script>
          $(function(){
              $('#total_unit<?=$id;?>').keyup(function(){
                  var total_unit<?=$id;?> = parseFloat($('#total_unit<?=$id;?>').val()) || 0;
                  var unit_price<?=$id;?> = parseFloat($('#unit_price<?=$id;?>').val()) || 0;
                  $('#total_price<?=$id;?>').val((total_unit<?=$id;?> * unit_price<?=$id;?>));
              });
          });
          $(function(){
              $('#total_unit<?=$id;?>').keyup(function(){
                  var total_price<?=$id;?> = parseFloat($('#total_price<?=$id;?>').val()) || 0;
                  var rate_of_SD<?=$id;?> = parseFloat($('#rate_of_SD<?=$id;?>').val()) || 0;
                  $('#amount_of_SD<?=$id;?>').val(((total_price<?=$id;?> * rate_of_SD<?=$id;?>)/100));
              });
          });
          $(function(){
              $('#total_unit<?=$id;?>').keyup(function(){
                  var total_price<?=$id;?> = parseFloat($('#total_price<?=$id;?>').val()) || 0;
                  var amount_of_SD<?=$id;?> = parseFloat($('#amount_of_SD<?=$id;?>').val()) || 0;
                  var rate_of_VAT<?=$id;?> = parseFloat($('#rate_of_VAT<?=$id;?>').val()) || 0;
                  $('#amount_of_VAT<?=$id;?>').val(((total_price<?=$id;?> + amount_of_SD<?=$id;?>)*rate_of_VAT<?=$id;?>)/100);
              });
          });
          $(function(){
              $('#total_unit<?=$id;?>').keyup(function(){
                  var total_price<?=$id;?> = parseFloat($('#total_price<?=$id;?>').val()) || 0;
                  var amount_of_SD<?=$id;?> = parseFloat($('#amount_of_SD<?=$id;?>').val()) || 0;
                  var amount_of_VAT<?=$id;?> = parseFloat($('#amount_of_VAT<?=$id;?>').val()) || 0;
                  $('#total_including_all<?=$id;?>').val(total_price<?=$id;?> + amount_of_SD<?=$id;?> + amount_of_VAT<?=$id;?>);
              });
          });
      </script>
      <?php
      $total_unit=$total_unit+$data->total_unit;
      $total_unit_amounts=$total_unit_amounts+$total_unit_amount;
      $total_VATs=$total_VATs+$total_VAT;
          $actual_VATs=$actual_VATs+$actual_VAT;
          $total_SD_amount=$total_SD_amount+$data->amount_of_SD;
      endwhile; ?>
      <tr><th>Total</th><td></td><td></td><th style="border: 1px solid #CCC;text-align: center;"><?=$total_unit?></th>
      <td></td>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_unit_amounts,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$a?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$b?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$c?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_VATs,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($actual_VATs,2)?></th>
      </tr>
    <?php endif; ?>
    </tbody>
</table>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>প্রতিষ্ঠান কতৃপক্ষের দায়িত্বপ্রাপ্ত ব্যক্তির নাম : <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$warehouse_master->VAT_responsible_person);?></div>
<div>পদবী : Depot Incharge</div>
<div>স্বাক্ষর :</div>
<div>*  উৎসে কর্তনযোগ্য সরবরাহের ক্ষেত্রে ফরমটি সমন্বিত কর চালানপত্র ও উৎসে কর কর্তন সনদপত্র হিসাবে বিবেচিত হইবে এবং উহা উৎসে কর কর্তনযোগ্য সরবরাহের ক্ষেত্রে প্রযোজ্য হবে।</div>
<div style="border-bottom: 1px solid black; width: 28%; margin-top: 30px;border-collapse: collapse;"></div>
<div style="width:28%;">
    <span style="">সকল প্রকার কর ব্যতীত মূল্য :</span>
    <span style="margin-left: 50px"><?=number_format($total_unit_amounts,2)?></span>
</div>

        <?php
        $GET_status=find_a_field('purchase_return_master','COUNT(id)','d='.$_GET[$unique]);
            if($status>0){?><h3 style="text-align: center;color: red;  font-weight: bold"><i>Mushak challan has been recorded & forwarded to the releavent warehouse!!</i></h3>
          <?php } else {?><h1 align="center"><input type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to Record & Create?");' name="record" value="Record & Create VAT Challan"></h1>
<?php } ?>
        </form>
</body>
</html>
