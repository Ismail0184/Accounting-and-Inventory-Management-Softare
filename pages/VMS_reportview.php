<?php
require_once 'support_file.php';
$title='VMS Report';
if($_POST['warehouse_id']>0) $warehouse_id=$_POST['warehouse_id'];
if(!empty($_POST['source'])) $source=$_POST['source'];
if(!empty($_POST['order_by'])) $order_by_GET=$_POST['order_by'];
if(isset($order_by_GET))				{$order_by=' order by '.$order_by_GET;}
if(!empty($_POST['order_by']) && !empty($_POST['sort'])) $order_by_GET=$_POST['order_by'];
if(isset($order_by_GET))				{$order_by=' order by '.$order_by_GET.' '.$_POST[sort].'';}
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #f5f5f5;}
        td{}
    </style>
</head>


<body style="font-family: "Gill Sans", sans-serif;">


<div id="pr" style="margin-left:48%">
    <div align="left">
        <form id="form1" name="form1" method="post" action="">
            <p><input name="button" type="button" onclick="hide();window.print();" value="Print" /></p>
        </form>
    </div>
</div>




<?php if ($_POST['report_id']=='1301001'):

if(isset($warehouse_id))				{$warehouse_CON=' AND d.warehouse_id='.$warehouse_id;}
if(isset($source))				{$source_CON=' AND d.source="'.$source.'"';}
if($_POST[source]=='Sales'){
$sql="SELECT i.item_id,i.finish_goods_code as item_ID,itm.H_S_code,i.item_name as 'Description, ERP',(select group_name from VAT_item_group where group_id=i.VAT_item_group) as 'Description, VAT',i.pack_size,i.unit_name,d.total_unit as 'VC Qty, Pcs',d.total_price as 'SD Chargeable Value',d.amount_of_SD as 'SD Amount',
d.rate_of_VAT as 'VAT %',d.amount_of_VAT as 'VAT Amount',d.total_including_all as total_value,d.mushak_no,m.fiscal_year,d.issue_date as VAT_date,m.issue_time as 'VAT Entry Time',w.warehouse_name,d.do_no,v.dealer_name_e as vendor,v.address_e as address
 from VAT_mushak_6_3 m,VAT_mushak_6_3_details d,item_info i,dealer_info v, item_tariff_master itm,warehouse w
where 
m.do_no=d.do_no and
i.item_id=d.item_id AND
d.issue_date BETWEEN '".$_POST['f_date']."' and '".$_POST['t_date']."' AND
i.H_S_code=itm.id and d.warehouse_id=w.warehouse_id and
d.dealer_code=v.dealer_code".$warehouse_CON.$source_CON."".$order_by."";
} elseif($_POST[source]=='Purchase_Returned'){
    $sql="SELECT i.item_id,i.finish_goods_code as item_ID,itm.H_S_code,i.item_name as Description,i.pack_size,i.unit_name,d.total_unit as 'VC Qty, Pcs',d.total_price as 'SD Chargeable Value',d.amount_of_SD as 'SD Amount',
    d.rate_of_VAT as 'VAT %',d.amount_of_VAT as 'VAT Amount',d.total_including_all as total_value,d.mushak_no,d.issue_date as VAT_date,d.entry_at as 'VAT Entry Time',w.warehouse_name,d.do_no as IR,v.vendor_name as vendor,v.address
     from VAT_mushak_6_3_details d,item_info i,vendor v, item_tariff_master itm,warehouse w
    where i.item_id=d.item_id AND
    d.issue_date BETWEEN '".$_POST[f_date]."' and '".$_POST[t_date]."' AND
    i.H_S_code=itm.id and d.warehouse_id=w.warehouse_id and
    d.dealer_code=v.vendor_id".$warehouse_CON.$source_CON."".$order_by."";   
}
echo reportview($sql,'Monthly VAT 6.3','99','1','5');?>


<?php elseif ($_POST['report_id']=='1301002'):?>
  <?php

  if(isset($warehouse_id))				{$warehouse_CON=' AND md.warehouse_id='.$warehouse_id;}
  $result=mysqli_query($conn, "SELECT md.mushak_no,md.issue_date,SUM(md.unit_price) as unit_price,SUM(md.total_unit) as total_unit,SUM(md.total_price) as total_price,SUM(md.amount_of_SD) as amount_of_SD,SUM(md.amount_of_VAT) as amount_of_VAT,SUM(md.total_including_all) as total_including_all,d.dealer_name_e,d.address_e from VAT_mushak_6_3_details md, dealer_info d
    where md.dealer_code=d.dealer_code and issue_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'".$warehouse_CON." group by md.mushak_no order by md.mushak_no");?>
  <div style="text-align: center; font-size:11px"><strong><?=$_SESSION[company_name]?></strong></div>
  <div style="text-align: center; font-size:11px"><?=$_SESSION[company_address]?></div>
  <div style="text-align: center; font-size:11px">BIN: <?=find_a_field('company','BIN','company_id="'.$_SESSION[companyid].'" and section_id='.$_SESSION[sectionid])?></div>
  <div style="text-align: center; font-size:11px">সাবর্ফম - ক</div>
  <div style="text-align: center; font-size:11px">(নোট ১,২,৩,৪,৫,৭,১০,১১,১২,১৩,১৪,১৫,১৬,১৭,১৯,২০,২১,২২ এর জন্য প্রযোজ্য)</div>

  <br>

  <table style="border-collapse: collapse; border: 1px solid #CCC; width: 100%; font-size:11px">
      <thead>
        <tr><th rowspan="2" style="border: 1px solid #CCC;width: 1%">ক্রমিক নং</th>
            <th colspan="11" style="border: 1px solid #CCC; text-align:center">বিক্রয় হিসাব তথ্য</th>
        </tr>
      <tr>
          <th style="border: 1px solid #CCC;width: 5%">চালান পত্র নং</th>
          <th style="border: 1px solid #CCC;width: 5%">ইস্যুর তারখি</th>
          <th style="border: 1px solid #CCC;">বিক্রেতার নাম</th>
          <th style="border: 1px solid #CCC;">বিক্রেতার ঠিকানা</th>
          <th style="border: 1px solid #CCC;width: 5%">পরিমাণ</th>
          <th style="border: 1px solid #CCC;width: 5%">একক মূল্য</th>
          <th style="border: 1px solid #CCC;width: 5%">মূল্য (ক)</th>
          <th style="border: 1px solid #CCC;width: 5%">সম্পূরক শুল্ক (খ)</th>
          <th style="border: 1px solid #CCC;width: 5%">মূল্য সংযোজন কর (গ)</th>
          <th style="border: 1px solid #CCC;width: 5%">র্সবমোট মূল্য</th>
          <th style="border: 1px solid #CCC;width: 10%">মন্তব্য</th>
      </tr></thead>
      <tbody>
      <?php while($data=mysqli_fetch_object($result)): ?>
      <tr>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$i=$i+1?></td>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$data->mushak_no?></td>
      <td style="border: 1px solid #CCC;text-align: center"><?=$data->issue_date?></td>
      <td style="border: 1px solid #CCC;text-align: left"><?=$data->dealer_name_e?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$data->address_e?></td>
      <td style="border: 1px solid #CCC;text-align: center;"><?=$data->total_unit;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->unit_price;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->total_price;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->amount_of_SD;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->amount_of_VAT;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->total_including_all;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->remarks?></td>
      </tr>
      <?php
          $total_units=$total_units+$data->total_unit;
          $unit_prices=$unit_prices+$data->unit_price;
          $total_prices=$total_prices+$data->total_price;
          $amount_of_SDs=$amount_of_SDs+$data->amount_of_SD;
          $amount_of_VATs=$amount_of_VATs+$data->amount_of_VAT;
          $total_including_alls=$total_including_alls+$data->total_including_all;
      endwhile; ?>
      <tr><th colspan="5" style="text-align:left">Total</th>
          <th style="border: 1px solid #CCC;text-align: center;"><?=$total_units?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($unit_prices,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_prices,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($amount_of_SDs,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($amount_of_VATs,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($total_including_alls,2)?></th>
      </tr>
      </tbody>
  </table>
  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <div style="font-size:11px">দায়িত্ব ব্যক্তির স্বাক্ষর  </div>
  <div style="font-size:11px">নাম : <?=$_SESSION[username];?></div>
  <div style="font-size:11px">তারিখ : <?=date('Y-m-d')?></div>


<?php elseif ($_POST['report_id']=='1302001'):?>
<title><?=$title?> | DO vs VAT 6.3</title>
  <?php
  if(isset($warehouse_id))				{$warehouse_CON=' AND md.depot_id='.$warehouse_id;}
  //$result=mysqli_query($conn, "SELECT i.finish_goods_code,hs.H_S_code,i.item_name,i.unit_name,i.pack_size,SUM(md.total_unit) as total_unit,(SELECT SUM(total_unit) from VAT_mushak_6_3_details where do_no=md.do_no and item_id=md.item_id) as vat_qty,md.do_no,md.do_date,w.warehouse_name,
  //(select mushak_no from VAT_mushak_6_3 WHERE do_no=md.do_no) as mushak_no,(select issue_date from VAT_mushak_6_3 WHERE do_no=md.do_no) as issue_date
  //from sale_do_chalan md, item_info i,item_tariff_master hs,warehouse w
    //where md.item_id=i.item_id and
    //md.depot_id=w.warehouse_id and
    //i.H_S_code=hs.id and i.finish_goods_code not in ('2001') and md.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'".$warehouse_CON." group by md.item_id,md.do_no order by md.do_no,md.item_id");


    $result=mysqli_query($conn, "SELECT
      i.finish_goods_code,
      i.item_name,
      i.unit_name,
      i.pack_size,
      SUM(md.total_unit) as total_unit,
      md.do_no,
      md.do_date,
      w.warehouse_name,
      hs.H_S_code,
      (SELECT SUM(total_unit) from VAT_mushak_6_3_details where do_no=md.do_no and item_id=md.item_id and warehouse_id=md.depot_id) as vat_qty,
      (select mushak_no from VAT_mushak_6_3_details WHERE do_no=md.do_no and item_id=md.item_id and warehouse_id=md.depot_id) as mushak_no,(select issue_date from VAT_mushak_6_3_details WHERE do_no=md.do_no and item_id=md.item_id and warehouse_id=md.depot_id) as issue_date

      from sale_do_chalan md,
      item_info i,
      warehouse w,
      item_tariff_master hs

      where md.item_id=i.item_id and
      md.depot_id=w.warehouse_id and
      i.H_S_code=hs.id and
      i.finish_goods_code not in ('2001') and
      md.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'".$warehouse_CON." group by md.item_id,md.do_no order by md.do_no,md.item_id");
    ?>
  <div style="text-align: center; font-size:11px"><strong><?=$_SESSION[company_name]?></strong></div>
  <div style="text-align: center; font-size:11px"><?=$_SESSION[company_address]?></div>
  <div style="text-align: center; font-size:11px">DO vs VAT 6.3</div>
  <br>
  <table id=customers style="border-collapse: collapse; border: 1px solid #CCC; width: 100%; font-size:11px">
      <thead>
        <tr style="background-color:#f5f5f5"><th rowspan="2" style="border: 1px solid #CCC;width: 1%">#</th>
            <th rowspan="2" style="border: 1px solid #CCC; text-align:center">FG Code</th>
            <th rowspan="2" style="border: 1px solid #CCC; text-align:center">HS Code</th>
            <th rowspan="2" style="border: 1px solid #CCC;width:">FG Description</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">UOM</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">Pack Size</th>
                <th colspan="3" style="border: 1px solid #CCC; text-align:center">Qty in Pcs</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">Ratio</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">Warehouse</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">Do No</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">Do Date</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">VAT NO</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">VAT Date</th>
                <th rowspan="2" style="border: 1px solid #CCC; text-align:center">Remark</th>
        </tr>
      <tr style="background-color:#f5f5f5">
          <th style="border: 1px solid #CCC;width: 5%">DO</th>
          <th style="border: 1px solid #CCC;width: 5%">VAT 6.3</th>
          <th style="border: 1px solid #CCC;">Variance</th>
      </tr></thead>
      <tbody>
      <?php while($data=mysqli_fetch_object($result)): ?>
      <tr>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$i=$i+1?></td>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$data->finish_goods_code?></td>
      <td style="border: 1px solid #CCC;text-align: center"><?=$data->H_S_code?></td>
      <td style="border: 1px solid #CCC;text-align: left"><?=$data->item_name?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$data->unit_name?></td>
      <td style="border: 1px solid #CCC;text-align: center;"><?=$data->pack_size;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->total_unit;?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=($data->vat_qty)? $data->vat_qty : '-'; ?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=(($data->total_unit-$data->vat_qty)>0)? $data->total_unit-$data->vat_qty : '-'; ?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=((($data->vat_qty/$data->total_unit)*100)>0)? number_format(($data->vat_qty/$data->total_unit)*100,2) : '-'; ?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->warehouse_name?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->do_no?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->do_date?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->mushak_no?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->issue_date?></td>
      <td style="border: 1px solid #CCC;text-align: right;"><?=$data->remarks?></td>
      </tr>
      <?php
          $total_units=$total_units+$data->total_unit;
          $vat_qty_total=$vat_qty_total+$data->vat_qty;
          $total_variance=$total_variance+($data->total_unit-$data->vat_qty);
          $amount_of_SDs=$amount_of_SDs+$data->amount_of_SD;
          $amount_of_VATs=$amount_of_VATs+$data->amount_of_VAT;
          $total_including_alls=$total_including_alls+$data->total_including_all;
      endwhile; ?>
      <tr><th colspan="6" style="text-align:left">Total</th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$total_units?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$vat_qty_total?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$total_variance?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=number_format($amount_of_SDs,2)?></th>
          <th style="border: 1px solid #CCC;text-align: right;"></th>
          <th style="border: 1px solid #CCC;text-align: right;"></th>
          <th style="border: 1px solid #CCC;text-align: right;"></th>
          <th style="border: 1px solid #CCC;text-align: right;"></th>
          <th style="border: 1px solid #CCC;text-align: right;"></th>
          <th style="border: 1px solid #CCC;text-align: right;"></th>
      </tr>
      </tbody>
  </table>

<?php elseif ($_POST['report_id']=='1301003'):
$sql="SELECT m.mushak_no,m.issue_date,(select SUM(total_price) from VAT_mushak_6_3_details where mushak_no=m.mushak_no) as price,d.dealer_name_e,d.address_e,d.TIN_BIN,d.national_id FROM
VAT_mushak_6_3 m,dealer_info d WHERE m.dealer_code=d.dealer_code group by m.mushak_no";?>
  <table style="width: 100%; font-size:11px">
  <td style="width: 30%; text-align: right;"><!--img src="bd.png" width="50" height="50" style="margin-top: 50px;
    padding:0px;"--></td>
      <td style="text-align: center">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার <br>জাতীয় রাজস্ব র্বোড</td>
      <td style="width: 30%"><div style="text-align: center;height: 30px; margin-top: 50px; vertical-align:middle; width: 100px; border: 1px solid black; font-size: 13px;"><strong>মূসক ৬.১০</strong></div></td>
  </table>


  <div style="text-align: center;font-size:11px"><strong>২ (দুই) লক্ষ টাকার অধিক মূল্যমানের ক্রয়-বিক্রয় চালান পত্রের তথ্য</strong></div>
  <div style="text-align: center;font-size:11px">[বিধি ৪২ এর উপ-বিধি (১) দ্রষ্টব্য]</div>

  <br>
  <table style="width: 100%;font-size:11px">
      <tr>
          <td style="text-align: left; width:15%">নিবন্ধিত/তালিকাভুক্ত ব্যক্তির নাম</td><td style="width: 1%">:</td><td style="width: 50%"><?=$_SESSION[company_name]?></td>
      </tr>
      <tr>
          <td style="text-align: left;">বি আই এন</td><td style="width: 1%">:</td><td style="width: 50%"><?=find_a_field('company','BIN','company_id="'.$_SESSION[companyid].'" and section_id='.$_SESSION[sectionid])?></td>
      </tr>
  </table>
  <br>  <br>
<div style="text-align: left;font-size:11px; font-weight:bold">অংশ-খ: বিক্রয় হিসাব তথ্য</div>
  <table style="border-collapse: collapse; border: 1px solid #CCC; width: 100%;font-size:11px">
      <thead>
      <tr>
          <th rowspan="2" style="border: 1px solid #CCC;width: 1%">ক্রমিক নং</th>
          <th colspan="6" style="border: 1px solid #CCC;">বিক্রয়</th>
      </tr>
      <tr>
          <th style="border: 1px solid #CCC;width:">চালান পত্র নং</th>
          <th style="border: 1px solid #CCC;">ইস্যুর তারিখ</th>
          <th style="border: 1px solid #CCC;">মূল্য</th>
          <th style="border: 1px solid #CCC;">বিক্রেতার নাম</th>
          <th style="border: 1px solid #CCC;">বিক্রেতার ঠিকানা</th>
          <th style="border: 1px solid #CCC;">বিক্রেতার বি আই এন/ জাতীয় পরিচয় পত্র নং*</th>

      </tr>
      <tr>
          <th style="border: 1px solid #CCC; text-align: center">(1)</th>
          <th style="border: 1px solid #CCC; text-align: center">(2)</th>
          <th style="border: 1px solid #CCC; text-align: center">(3)</th>
          <th style="border: 1px solid #CCC; text-align: center">(4)</th>
          <th style="border: 1px solid #CCC; text-align: center">(5)</th>
          <th style="border: 1px solid #CCC; text-align: center">(6)</th>
          <th style="border: 1px solid #CCC; text-align: center">(7)</th>
      </tr>
      </thead>
      <tbody>
      <?php $result=mysqli_query($conn, $sql);while($data=mysqli_fetch_object($result)):?>
      <tr>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$i=$i+1?></td>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$data->mushak_no?></td>
      <td style="border: 1px solid #CCC;text-align: center"><?=$data->issue_date?></td>
      <td style="border: 1px solid #CCC;text-align: right"><?=number_format($data->price,2)?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$data->dealer_name_e;?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$data->address_e;?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=($data->TIN_BIN>0)? $data->TIN_BIN : $data->national_id;?></td>
      </tr>
      <?php endwhile; ?>
      <tr><th colspan="3">Total</th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=($total_amount_of_SD>0)? $total_amount_of_SD : '-';?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$c?></th>
      </tr>
      </tbody>
  </table>
  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <div style="font-size:11px">দায়িত্ব ব্যক্তির স্বাক্ষর : </div>
  <div style="font-size:11px">নাম : <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$warehouse_master->VAT_responsible_person);?></div>
  <div style="font-size:11px">তারিখ : <?=date('d M Y')?></div><br><br>
  <div style="font-size:11px">*যেইক্ষেত্রে অনিবন্ধিত ব্যক্তির নিকট হইতে পণ্য/সেবা ক্রয় করা হইবে বা অনিবন্ধিত ব্যক্তির নিকট পণ্য/সেবা বিক্রয় করা হইবে, সেইক্ষেত্রে উক্ত ব্যক্তির নাম,ঠিকানা ও জাতীয় পরিচয় নম্বর <br>যথাযথ সংশ্লিষ্ট কলামে [(৭),(৮) ও (৯)] আবশ্যিকভাবে উল্লেখ করিতে হইবে।</div>
  </body>
  </html>
<?php elseif ($_POST['report_id']=='1301004'):
$sql="SELECT m.mushak_no,m.issue_date,(select SUM(total_price) from VAT_mushak_6_3_details where mushak_no=m.mushak_no) as price,d.dealer_name_e,d.address_e,d.TIN_BIN,d.national_id FROM
VAT_mushak_6_3 m,dealer_info d WHERE m.dealer_code=d.dealer_code group by m.mushak_no";?>
  <table style="width: 100%; font-size:11px">
  <td style="width: 30%; text-align: right;"><!--img src="bd.png" width="50" height="50" style="margin-top: 50px;
    padding:0px;"--></td>
      <td style="text-align: center">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার <br>জাতীয় রাজস্ব র্বোড</td>
      <td style="width: 30%"><div style="text-align: center;height: 30px; margin-top: 50px; vertical-align:middle; width: 100px; border: 1px solid black; font-size: 13px;"><strong>মূসক ৬.২.১</strong></div></td>
  </table>


  <div style="text-align: center;font-size:11px"><strong>ক্রয়-বিক্রয় হিসাব পুস্তক</strong></div>
  <div style="text-align: center;font-size:11px">(পণ্য বা সেবা প্রক্রিয়াকরণে সম্পৃক্ত এমন নিবন্ধিত বা তালিকাভুক্ত ব্যক্তির জন্য প্রযোজ্য)</div>
  <div style="text-align: center;font-size:11px">[বিধি ৪০(১) এর দফা (ক) এবং ৪১ এর দফা (খখ) দ্রষ্টব্য]</div>

  <br>
  <table style="width: 100%;font-size:11px">
      <tr>
          <td style="text-align: left; width:15%">ক্রেতার নাম</td><td style="width: 1%">:</td><td style="width: 50%"><?=$_SESSION[company_name]?></td>
      </tr>
      <tr>
          <td style="text-align: left;">ক্রেতার বিআইএন</td><td style="width: 1%">:</td><td style="width: 50%"><?=find_a_field('company','BIN','company_id="'.$_SESSION[companyid].'" and section_id='.$_SESSION[sectionid])?></td>
      </tr>
      <tr>
          <td style="text-align: left;">সরবরাহের গন্তব্যস্থল</td><td style="width: 1%">:</td><td style="width: 50%"><?=$_SESSION[company_address]?></td>
      </tr>
  </table><br>
<div style="text-align: left;font-size:11px; font-weight:bold"><?=$item_name=find_a_field('item_info','item_name','item_id='.$_POST[item_id])?></div><br>
  <table style="border-collapse: collapse; border: 1px solid #CCC; width: 100%;font-size:11px">
      <thead>
      <tr>
          <th rowspan="2" style="border: 1px solid #CCC;width: 1%">ক্রমিক সংখ্যা</th>
          <th rowspan="2" style="border: 1px solid #CCC;">তারিখ</th>
          <th colspan="2" style="border: 1px solid #CCC;">বিক্রয়যোগ্য পণ্যের প্রারম্ভিক জের</th>
          <th colspan="2" style="border: 1px solid #CCC;">ক্রয়</th>
          <th style="border: 1px solid #CCC;">মোট পণ্য</th>
          <th colspan="3" style="border: 1px solid #CCC;">বিক্রেতার তথ্য</th>
          <th colspan="2" style="border: 1px solid #CCC;">ক্রয় চালানপত্রের/বিল অব এন্টির বিবরণ</th>
          <th colspan="5" style="border: 1px solid #CCC;">বিক্রিত/সরবরাহকৃত পণ্যের বিবরণ</th>
          <th colspan="3" style="border: 1px solid #CCC;">ক্রেতার তথ্য</th>
          <th colspan="2" style="border: 1px solid #CCC;">বিক্রয় চালান পত্রের বিবরণ</th>
          <th  style="border: 1px solid #CCC;">পণ্যের প্রান্তিক জের</th>
          <th  rowspan="2" style="border: 1px solid #CCC;">মন্তব্য</th>
      </tr>

      <tr>
          <th style="border: 1px solid #CCC;width:">বিবরণ</th>
          <th style="border: 1px solid #CCC;">পরিমাণ (একক)</th>
          <th style="border: 1px solid #CCC;">পরিমাণ (একক)</th>
          <th style="border: 1px solid #CCC;">মূল্য (সকল প্রকার কর ব্যতীত)</th>
          <th style="border: 1px solid #CCC;">পরিমাণ (একক)</th>
          <th style="border: 1px solid #CCC;">নাম</th>
          <th style="border: 1px solid #CCC;">ঠিকানা</th>
          <th style="border: 1px solid #CCC;">নিবন্ধন/  তালিকাভুক্তি/ জাতীয় পরিচয়পত্র নং</th>
          <th style="border: 1px solid #CCC;">নম্বর</th>
          <th style="border: 1px solid #CCC;">তারিখ</th>
          <th style="border: 1px solid #CCC;">বিবরণ</th>
          <th style="border: 1px solid #CCC;">পরিমাণ (একক)</th>
          <th style="border: 1px solid #CCC;">মূল্য  (সকল প্রকার কর ব্যতীত)</th>
          <th style="border: 1px solid #CCC;">সম্পূরক  শুল্ক  (যদি থাকে)</th>
          <th style="border: 1px solid #CCC;">মূসক</th>
          <th style="border: 1px solid #CCC;">নাম</th>
          <th style="border: 1px solid #CCC;">ঠিকানা</th>
          <th style="border: 1px solid #CCC;">নিবন্ধন/  তালিকাভুক্তি/ জাতীয় পরিচয়পত্র নং</th>
          <th style="border: 1px solid #CCC;">নম্বর</th>
          <th style="border: 1px solid #CCC;">তারিখ</th>
          <th style="border: 1px solid #CCC;">পরিমাণ (একক)</th>
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
          <th style="border: 1px solid #CCC; text-align: center">(12)</th>
          <th style="border: 1px solid #CCC; text-align: center">(13)</th>
          <th style="border: 1px solid #CCC; text-align: center">(14)</th>
          <th style="border: 1px solid #CCC; text-align: center">(15)</th>
          <th style="border: 1px solid #CCC; text-align: center">(16)</th>
          <th style="border: 1px solid #CCC; text-align: center">(17)</th>
          <th style="border: 1px solid #CCC; text-align: center">(18)</th>
          <th style="border: 1px solid #CCC; text-align: center">(19)</th>
          <th style="border: 1px solid #CCC; text-align: center">(20)</th>
          <th style="border: 1px solid #CCC; text-align: center">(21)</th>
          <th style="border: 1px solid #CCC; text-align: center">(22)</th>
          <th style="border: 1px solid #CCC; text-align: center">(23)</th>
          <th style="border: 1px solid #CCC; text-align: center">(24)</th>
      </tr>
      </thead>
      <tbody>
      <?php $result=mysqli_query($conn, $sql);while($data=mysqli_fetch_object($result)):?>
      <tr>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$i=$i+1?></td>
      <td style="border: 1px solid #CCC;text-align: center; margin: 10px"><?=$data->mushak_no?></td>
      <td style="border: 1px solid #CCC;text-align: left"><?=$item_name?></td>
      <td style="border: 1px solid #CCC;text-align: right"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>

      <td style="border: 1px solid #CCC;text-align: right"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>

      <td style="border: 1px solid #CCC;text-align: right"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$item_name?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>
      <td style="border: 1px solid #CCC;text-align: left;"><?=$c?></td>
      </tr>
      <?php endwhile; ?>
      <tr><th colspan="3">Total</th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=($total_amount_of_SD>0)? $total_amount_of_SD : '-';?></th>
          <th style="border: 1px solid #CCC;text-align: right;"><?=$c?></th>
      </tr>
      </tbody>
  </table>

<?php elseif ($_POST['report_id']=='1006001'):
$sql="Select v.ledger_id,v.vendor_id,v.ledger_id,v.vendor_name,FORMAT(SUM(j.dr_amt),2) as Dr_amt,FORMAT(SUM(j.cr_amt),2) as Cr_amt,FORMAT(SUM(j.dr_amt-j.cr_amt),2) as Closing_Balance  from
vendor v,
journal j
where
v.ledger_id=j.ledger_id group by v.ledger_id order by v.vendor_name"; echo reportview($sql,'Outstanding Balance','80'); ?>


<?php elseif ($_POST['report_id']=='1011001'):
if($_POST[v_type]!=''){$v_type .= "AND j.tr_from = '".$_POST[v_type]."'";}
$sql="Select i.item_id,i.item_id,i.finish_goods_code as custom_code,i.item_name,i.unit_name, s.sub_group_name, g.group_name,lc.landad_cost,lc.entry_date as last_updated_date from
item_info i,
item_sub_group s,
item_group g,
item_landad_cost lc
where
i.item_id=lc.item_id and
lc.status='Active' and
i.sub_group_id=s.sub_group_id and
s.group_id=g.group_id and
s.group_id in (".selectmultipleoptions($_POST['group_id']).")"; echo reportview($sql,'Material Costing','80'); ?>

<?php else:?>
<h3 style="text-align:center">The report is under construction!!</h3>
<?php endif;  ?>
</body>
</html>
