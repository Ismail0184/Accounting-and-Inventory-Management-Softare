<?php
require_once 'support_file.php';
$title='Mushak 6.3';
$page="VAT_mushak_6.3.php";
$table='sale_do_chalan';
$unique='do_no';
$$unique=$_GET[$unique];
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$timess = $dateTime->format("h:i A");
$year=date('Y');
$now=date('Y-m-d h:s:i');
$table_VAT_Master='VAT_mushak_6_3';
$table_VAT_details='VAT_mushak_6_3_details';
$do_master=find_all_field('sale_do_master','','do_no='.$$unique);
$warehouse_master=find_all_field('warehouse','','warehouse_id='.$do_master->depot_id);
$dealer_master=find_all_field('dealer_info','','dealer_code='.$do_master->dealer_code);
$query="SELECT mus.*,SUM(mus.total_unit) as total_unit,i.item_name,i.unit_name,i.VAT from item_info i,VAT_mushak_6_3_details mus where mus.item_id=i.item_id and i.item_id not in ('1096000100010312') and mus.".$unique."=".$$unique." group by mus.item_id order by i.finish_goods_code";
$result=mysqli_query($conn, $query);
$status=find_a_field('VAT_mushak_6_3','COUNT(id)','do_no='.$_GET[do_no]);
$VAT_master=find_all_field('VAT_mushak_6_3','','do_no='.$_GET[do_no]);
$latest_id=find_a_field('VAT_mushak_6_3','MAX(mushak_no)','year='.$year.' and warehouse_id='.$do_master->depot_id);
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
    .watermark {

      position: relative;
          bottom: 10%;
          left: 20%;
        opacity: 0.1;
        color: BLACK;
        font-size: 90px;
        font-weight: strong;
        transform: rotate(-20deg);
    }
    </style>
</header>
<body style="font-size: 11px">
  <?php if($status>0){ ?>
<div id="pr" style="margin-left:48%">
    <div align="left">
        <form id="form1" name="form1" method="post" action="">
            <p><input name="button" type="button" onclick="hide();window.print();" value="Print" /></p>
        </form>
    </div>
</div>
<?php } ?>
<form method="post" action="">
  <input type="hidden" name="warehouse_id" value="<?=$do_master->depot_id?>">
  <input type="hidden" name="dealer_code" value="<?=$do_master->dealer_code?>">
  <input type="hidden" name="responsible_person" value="<?=$warehouse_master->VAT_responsible_person?>">
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
<div style="text-align: center">চালানপত্র ইস্যুর ঠিকানা : <?=find_a_field('warehouse','VMS_address','warehouse_id='.$do_master->depot_id.' and company_id="'.$_SESSION[companyid].'" and section_id='.$_SESSION[sectionid])?></div>

<br>
<table style="width: 100%;">
    <tr>
        <th style="width: 10%; text-align: left">ক্রেতার নাম</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->dealer_name_e?></td>
        <th style="width: 10%; text-align: right">চালানপত্র নম্বর</th><th style="width: 1%">:</th><td style="width: 29%"><?=$VAT_master->mushak_no;?></td>
    </tr>
    <tr>
        <th style="width: 10%; text-align: left">ক্রেতার বিআইএন</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->TIN_BIN?></td>
        <th style="width: 10%; text-align: right">ইস্যুর তারিখ</th><th style="width: 1%">:</th><td style="width: 29%"><?=$VAT_master->issue_date;?></td>
    </tr>
    <tr>
        <th style="width: 10%; text-align: left">সরবরাহের গন্তব্যস্থল</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->address_e?></td>
        <th style="width: 10%; text-align: right">ইস্যুর সময়</th><th style="width: 1%">:</th><td style="width: 29%"><?=$VAT_master->issue_time;?></td>
    </tr>
</table>
<br>
<?php
if($VAT_master->duplicate_status==1){?>
<div class="watermark">Duplicate Copy</div>
<?php } ?>
<table style="border-collapse: collapse; border: 1px solid #CCC; width: 100%">
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
    while($data=mysqli_fetch_object($result)):
      $id=$data->item_id;
      $ab=$data->rate_of_SD;$ef=$data->rate_of_VAT;
      ?>
    <tr>
    <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$i=$i+1?></td>
    <td style="border: 1px solid #CCC;text-align: left; margin: 10px"><?=$data->item_name?></td>
    <td style="border: 1px solid #CCC;text-align: center"><?=$data->unit_name?></td>
    <td style="border: 1px solid #CCC;text-align: center"><?=$data->total_unit?></td>
    <td style="border: 1px solid #CCC;text-align: right;"><?=number_format($data->unit_price,2);?></td>
    <td style="border: 1px solid #CCC;text-align: right;"><?=number_format($data->total_price,2);?></td>
    <td style="border: 1px solid #CCC;text-align: right;"><?=($ab>0)? $ab : '-';?></td>
    <?php $cd=$data->total_unit*$data->unit_price*$ab?>
    <td style="border: 1px solid #CCC;text-align: right;"><?=($cd>0)? $cd : '-';?></td>
    <td style="border: 1px solid #CCC;text-align: right;"><?=number_format($ef).'%';?></td>
    <td style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_VAT=$data->amount_of_VAT,2);?></td>
    <td style="border: 1px solid #CCC;text-align: right;"><?=number_format($actual_VAT=$data->total_including_all,2)?></td>
    </tr>
    <?php
    $total_unit=$total_unit+$data->total_unit;
    $total_unit_amounts=$total_unit_amounts+$data->total_price;
    $total_VATs=$total_VATs+$total_VAT;
        $actual_VATs=$actual_VATs+$actual_VAT;
        $total_amount_of_SD=$total_amount_of_SD+$amount_of_SD;
    endwhile; ?>
    <tr><th>Total</th><td></td><td></td><th style="border: 1px solid #CCC;text-align: center;"><?=$total_unit?></th>
    <td></td>
        <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_unit_amounts,2)?></th>
        <th style="border: 1px solid #CCC;text-align: right;"><?=$a?></th>
        <th style="border: 1px solid #CCC;text-align: right;"><?=($total_amount_of_SD>0)? $total_amount_of_SD : '-';?></th>
        <th style="border: 1px solid #CCC;text-align: right;"><?=$c?></th>
        <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_VATs,2)?></th>
        <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($actual_VATs,2)?></th>
    </tr>
    </tbody>
</table>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>প্রতিষ্ঠান কতৃপক্ষের দায়িত্বপ্রাপ্ত ব্যক্তির নাম : <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$warehouse_master->VAT_responsible_person);?></div>
<div>পদবী : Depot Incharge</div>
<div style="margin-top:10px">স্বাক্ষর :</div><br>
<div style="margin-top:10px">*  উৎসে কর্তনযোগ্য সরবরাহের ক্ষেত্রে ফরমটি সমন্বিত কর চালানপত্র ও উৎসে কর কর্তন সনদপত্র হিসাবে বিবেচিত হইবে এবং উহা উৎসে কর কর্তনযোগ্য সরবরাহের ক্ষেত্রে প্রযোজ্য হবে।</div>
<div style="border-bottom: 1px solid black; width: 28%; margin-top: 30px;border-collapse: collapse;"></div>
<div style="width:28%;">
    <span style="">সকল প্রকার কর ব্যতীত মূল্য :</span>
    <span style="margin-left: 50px"><?=number_format($total_unit_amounts,2)?></span>
</div>
</body>
<?php
if(isset($_GET[do_no])){
  $id=$$unique;
  $table_master='sale_do_master';
  $vars['duplicate_status']='1';
  $vars['checked_by']=$_SESSION[userid];
  $vars['checked_at']=date('Y-m-d h:i:s');
  db_update($table_VAT_Master, $id, $vars, $unique);
  mysqli_query($conn, 'Update '.$table_master.' set mushak_challan_status="COMPLETED" where '.$unique.'='.$$unique);
}?>
</html>
