<?php
require_once 'support_file.php';
$title='Mushak 6.3';
$page="acc_mushak_6.3.php";
$table='sale_do_chalan';
$unique='do_no';
$$unique=$_GET[$unique];
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$timess = $dateTime->format("h:i A");
$year=date('Y');
$now=date('Y-m-d h:s:i');
$table_VAT_Master='VAT_mushak_6_3';
$table_VAT_details='VAT_mushak_6_3_details';
$chalan_no=find_a_field('sale_do_chalan','distinct chalan_no','do_no='.$_GET[do_no]);
$jv_no=find_a_field('journal','distinct jv_no','tr_from="Sales" and tr_no='.$chalan_no);
$mushak=find_all_field('VAT_mushak_6_3','','do_no='.$_GET[do_no]);
$fiscal_year=find_a_field('fiscal_term','fiscal_year','status="1"');
$fs_year=find_all_field('fiscal_term','','fiscal_year='.$mushak->fiscal_year);
$VAT_narration='VAT against sale, Do No # '.$_GET[do_no].', Challan No # '.$chalan_no.', VAT 6.3 No # ';
$SD_narration='SD against sale, Do No # '.$_GET[do_no].', Challan No # '.$chalan_no.', VAT 6.3 No # ';


if(prevent_multi_submit()){
    if(isset($_POST[delete])){
        $sql_1=mysqli_query($conn, "INSERT INTO VAT_mushak_6_3_deleted (R_id,do_no,mushak_no,warehouse_id,dealer_code,issue_date,issue_time,responsible_person,source,entry_by,entry_at,checked_by,checked_at,duplicate_status) 
        SELECT id,do_no,mushak_no,warehouse_id,dealer_code,issue_date,issue_time,responsible_person,source,entry_by,entry_at,checked_by,checked_at,duplicate_status FROM VAT_mushak_6_3 WHERE
        do_no=".$_GET[do_no]." and mushak_no=".$_POST[delete_mushak_no]."");

        $sql_1=mysqli_query($conn, "INSERT INTO VAT_mushak_6_3_details_deleted (R_id,do_no,issue_date,item_id,in_total_unit,total_unit,unit_price,total_price,rate_of_SD,amount_of_SD,rate_of_VAT,amount_of_VAT,total_including_all,entry_by,entry_at,warehouse_id,mushak_no,dealer_code,source) 
        SELECT id,do_no,issue_date,item_id,in_total_unit,total_unit,unit_price,total_price,rate_of_SD,amount_of_SD,rate_of_VAT,amount_of_VAT,total_including_all,entry_by,entry_at,warehouse_id,mushak_no,dealer_code,source FROM VAT_mushak_6_3_details WHERE
        do_no=".$_GET[do_no]." and mushak_no=".$_POST[delete_mushak_no]."");
        mysqli_query($conn, "Update VAT_mushak_6_3_deleted set deleted_by='".$_SESSION[userid]."' where do_no=".$_GET[do_no]." and mushak_no=".$_POST[delete_mushak_no]."");
        mysqli_query($conn, "DELETE FROM journal WHERE tr_no=".$_POST[chalan_no]." and tr_from='Sales' and ledger_id in ('4015000100000000','1005000400000000','4016000100000000','1005000700000000')");
        mysqli_query($conn, "DELETE FROM VAT_mushak_6_3_details WHERE do_no=".$_GET[do_no]." and mushak_no=".$_POST[delete_mushak_no]."");
        mysqli_query($conn, "DELETE FROM VAT_mushak_6_3 WHERE do_no=".$_GET[do_no]." and mushak_no=".$_POST[delete_mushak_no]."");
        mysqli_query($conn, "Update sale_do_master set mushak_challan_status='UNRECORDED' where do_no=".$_GET[do_no]."");



    }

if(isset($_POST[record])){
  $mushak_no_validation_check=find_a_field('VAT_mushak_6_3','mushak_no','mushak_no='.$_POST[mushak_no].' and fiscal_year="'.$fiscal_year.'" and warehouse_id='.$_POST[warehouse_id].'');
  if($mushak_no_validation_check == $_POST[mushak_no]) {
    $message='This Mushak No has already been input!!';
    echo "<script>alert('$message');</script>";

  }
  elseif($_POST[mushak_no]>0 && !empty($_POST[issue_date])){
  $_POST[do_no]=$_GET[do_no];
  $_POST[mushak_no]=$_POST[mushak_no];
  $_POST[warehouse_id]=$_POST[warehouse_id];
  $_POST[dealer_code]=$_POST[dealer_code];
  $_POST[issue_date]=$_POST[issue_date];
  $_POST[issue_time]=$_POST[issue_time];
  $_POST[responsible_person]=$_POST[responsible_person];
  $_POST[entry_by]=$_SESSION[userid];
  $_POST[entry_at]=$now;
  $_POST[year]=$year;
  $_POST[fiscal_year]=$fiscal_year;
  $crud = new crud($table_VAT_Master);
  $crud->insert();

  $query="SELECT sdc.*,SUM(sdc.total_unit) as total_unit,i.item_name,i.unit_name,i.SD AS VAT from ".$table." sdc, item_info i where sdc.item_id=i.item_id and i.item_id not in ('1096000100010312') and sdc.".$unique."=".$$unique." group by i.item_id order by i.finish_goods_code";
  $result=mysqli_query($conn, $query);
  while($data=mysqli_fetch_object($result)):
    $id=$data->item_id;
    $_POST[do_no]=$_GET[do_no];
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
}
    mysqli_query($conn, "Update sale_do_master SET mushak_challan_status='RECORDED' where do_no=".$_GET[do_no]);

}


if(isset($_POST[create_journal])){
  if($_POST[chalan_no]>0){
  if (($_POST[ledger_1] > 0) && (($_POST[ledger_2] && $_POST[dr_amount_1]) > 0) && ($_POST[cr_amount_2] > 0)) {
      add_to_journal_new($mushak->issue_date, $proj_id, $_POST[jv_no], $date, $_POST[ledger_1], $_POST[narration_1], $_POST[dr_amount_1], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
      add_to_journal_new($mushak->issue_date, $proj_id, $_POST[jv_no], $date, $_POST[ledger_2], $_POST[narration_1], 0, $_POST[cr_amount_2], Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
  }

  if (($_POST[ledger_3] > 0) && (($_POST[ledger_4] && $_POST[dr_amount_3]) > 0) && ($_POST[cr_amount_4] > 0)) {
      add_to_journal_new($mushak->issue_date, $proj_id, $_POST[jv_no], $date, $_POST[ledger_3], $_POST[narration_3], $_POST[dr_amount_3], 0, Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
      add_to_journal_new($mushak->issue_date, $proj_id, $_POST[jv_no], $date, $_POST[ledger_4], $_POST[narration_3], 0, $_POST[cr_amount_4], Sales, $_POST[chalan_no], $$unique, 0, 0, $_SESSION[usergroup], $c_no, $c_date, $create_date, $ip, $now, $day, $thisday, $thismonth, $thisyear, $_POST[pc_code], $_SESSION[wpc_DO]);
  }
  mysqli_query($conn, "Update sale_do_master SET mushak_challan_status='RECORDED' where do_no=".$_GET[do_no]);
  unset($_POST);
  //echo "<script>self.opener.location = '$page'; self.blur(); </script>";
  echo "<script>window.close(); </script>";
}
}} // prevent_multi_submit

$mushak=find_all_field('VAT_mushak_6_3','','do_no='.$_GET[do_no].'');
$COUNT_mushak=find_a_field('VAT_mushak_6_3','COUNT(mushak_no)','do_no='.$_GET[do_no].'');
$COUNT_journal=find_a_field('journal','COUNT(id)','ledger_id in ("4015000100000000") and tr_from="Sales" and tr_no="'.$chalan_no.'" and tr_id='.$_GET[do_no]);


$do_master=find_all_field('sale_do_master','','do_no='.$_GET[$unique]);
$warehouse_master=find_all_field('warehouse','','warehouse_id='.$do_master->depot_id);
$dealer_master=find_all_field('dealer_info','','dealer_code='.$do_master->dealer_code);
$dealer_master=find_all_field('dealer_info','','dealer_code='.$do_master->dealer_code);


$status=find_a_field('VAT_mushak_6_3','COUNT(id)','do_no='.$_GET[do_no]);
$VAT_master=find_all_field('VAT_mushak_6_3','','do_no='.$_GET[do_no]);
$latest_id=find_a_field('VAT_mushak_6_3','MAX(mushak_no)','fiscal_year='.$fiscal_year.' and warehouse_id='.$do_master->depot_id);

if($status>0){
    if($_GET[group_by]=='VAT_item_group'){
        $query="SELECT mus.*,SUM(mus.total_unit) as total_unit,mus.total_price,vtg.group_name as item_name,mus.rate_of_SD,SUM(mus.amount_of_SD) as amount_of_SD,mus.rate_of_VAT,SUM(mus.amount_of_VAT) as amount_of_VAT,SUM(mus.total_including_all) as total_including_all,i.unit_name,i.SD AS VAT 
        from item_info i,VAT_mushak_6_3_details mus, VAT_item_group vtg
        where i.VAT_item_group=vtg.group_id and mus.item_id=i.item_id and i.item_id not in ('1096000100010312') and source='Sales' and mus.".$unique."=".$$unique." 
        group by i.VAT_item_group order by i.finish_goods_code";
    } else {
        $query="SELECT mus.*,SUM(mus.total_unit) as total_unit,mus.total_price,i.item_name,mus.rate_of_SD,mus.amount_of_SD,mus.rate_of_VAT,mus.amount_of_VAT,mus.total_including_all,i.unit_name,i.SD AS VAT from item_info i,VAT_mushak_6_3_details mus where mus.item_id=i.item_id and i.item_id not in ('1096000100010312') and source='Sales' and mus.".$unique."=".$$unique." group by mus.item_id order by i.finish_goods_code";

    }
} else {
  $query="SELECT sdc.*,SUM(sdc.total_unit) as total_unit,i.item_name,i.unit_name,i.SD AS VAT,i.VAT_percentage,i.SD_percentage from ".$table." sdc, item_info i where sdc.item_id=i.item_id and i.item_id not in ('1096000100010312') and sdc.".$unique."=".$$unique." group by i.item_id order by i.finish_goods_code";
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
  <input type="hidden" name="warehouse_id" value="<?=$do_master->depot_id?>">
  <input type="hidden" name="dealer_code" value="<?=$do_master->dealer_code?>">
  <input type="hidden" name="responsible_person" value="<?=$warehouse_master->VAT_responsible_person?>">
  <input type="hidden" name="chalan_no" value="<?=$chalan_no?>">
  <input type="hidden" name="jv_no" value="<?=$jv_no?>">
  <input type="hidden" name="jvdate" value="<?=$mushak->issue_date?>">
  <input type="hidden" name="source" value="Sales">
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
<div style="text-align: center">চালানপত্র ইস্যুর ঠিকানা : <?=$warehouse_master->VMS_address?></div>
<br>
<table style="width: 100%;">
    <tr>
        <th style="width: 10%; text-align: left">ক্রেতার নাম</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->dealer_name_e?></td>
        <th style="width: 10%; text-align: right">চালানপত্র নম্বর</th><th style="width: 1%">:</th><td style="width: 29%">
<?php if($status>0){
        if($do_master->depot_id==5){
            echo $fs_year->term_year.'-CW-'.$VAT_master->mushak_no;
        } else {
            echo $fs_year->term_year.'-DK-'.$VAT_master->mushak_no;
        }

     } else { ?>
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
        <th style="width: 10%; text-align: left">সরবরাহের গন্তব্যস্থল</th><th style="width: 1%">:</th><td style="width: 50%"><?=$dealer_master->address_e?></td>
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
    <span style="margin-left: 50px"><?=number_format($total_total_price,2)?></span>
</div>
<?php
    if($status>0){?>
     <?php } else { ?>
        <h1 align="center">
            <input type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to Record & Create?");' name="record" value="Record & Create VAT Challan"></p>
          <?php } ?>



<br><br>


          <?php if($_GET[do_no]>0 && $COUNT_mushak>0 && $COUNT_journal==0): ?>
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
                <?php if($total_amount_of_VAT>0):?>
              <tr>
                  <th rowspan="2" style="text-align: center; vertical-align: middle">1</th>
                  <th rowspan="2" style="text-align: center; vertical-align: middle">VAT Journal</th>
                  <th style="text-align: center; vertical-align: middle">Expenses Ledger</th>
                  <td style="vertical-align: middle">
                      <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="ledger_1"  name="ledger_1">
                          <option  value="4015000100000000">4015000100000000-<?=$customer_name=find_a_field('accounts_ledger','ledger_name','ledger_id="4015000100000000"'); ?></option>
                      </select>
                  </td>
                  <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_1" id="narration_1" class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$VAT_narration?><?=$mushak->mushak_no?>, <?=$warehouse_master->warehouse_name?>, Fiscal Year # <?=$fs_year->term_year?></textarea></td>
                  <td align="center" style="vertical-align: middle"><input type="text" name="dr_amount_1" readonly value="<?=$total_amount_of_VAT;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                  <td align="center" style="vertical-align: middle"><input type="text" name="cr_amount_1" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
              </tr>
              <tr>
                  <th style="text-align: center; vertical-align: middle">Current Account Ledger</th>
                  <td style="vertical-align: middle"><?$VAT_current_account=1005000400000000;?>
                      <select class="select2_single form-control" style="width:100%; font-size:11px" tabindex="-1" required="required"  name="ledger_2" id="ledger_2">
                          <?=foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $VAT_current_account, 'ledger_id='.$VAT_current_account); ?>
                      </select></td>
                  <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_2" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                  <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_2" readonly value="<?=$total_amount_of_VAT;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
              </tr>
              <?php endif; ?>
              <?php if($total_amount_of_SD>0):?>
              <tr>
                  <th rowspan="2" style="text-align: center; vertical-align: middle">2</th>
                  <th rowspan="2" style="text-align: center; vertical-align: middle">SD Journal</th>
                  <th style="text-align: center; vertical-align: middle">Expenses Ledger</th>
                  <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%;font-size:11px" tabindex="-1" required="required"  name="ledger_3" id="ledger_3">
                          <?$SD_expenses_ledger='4016000100000000';$SD_advance_ledger='1005000700000000';

                          ?>
                          <option  value="<?=$SD_expenses_ledger;?>"><?=$SD_expenses_ledger; ?>-<?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$SD_expenses_ledger.''); ?></option>
                      </select></td>
                  <td rowspan="2" style="text-align: center; vertical-align: middle"><textarea name="narration_3" id="narration_3" class="form-control col-md-7 col-xs-12" style="width:100%; height:92px; font-size: 11px; text-align:center"><?=$SD_narration;?><?=$mushak->mushak_no?>, <?=$warehouse_master->warehouse_name?>, Fiscal Year # <?=$fs_year->term_year?></textarea></td>
                  <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_3" readonly value="<?=$total_amount_of_SD;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                  <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_3" readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
              </tr>
              <tr>
                  <th style="text-align: center; vertical-align: middle">Advance SD Ledger</th>
                  <td style="vertical-align: middle"><select class="select2_single form-control" style="width:100%;font-size:11px" tabindex="-1" required="required"  name="ledger_4" id="ledger_4">
                          <option  value="<?=$SD_advance_ledger;?>"><?=$SD_advance_ledger?> : <?=find_a_field('accounts_ledger','ledger_name','ledger_id='.$SD_advance_ledger.''); ?></option>
                      </select></td>
                  <td style="text-align: right; vertical-align: middle"><input type="text" name="dr_amount_4"  readonly value="" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
                  <td style="text-align: right; vertical-align: middle"><input type="text" name="cr_amount_4" readonly value="<?=$total_amount_of_SD;?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:35px; font-size: 11px; text-align:center" ></td>
              </tr>
              <?php endif; ?>
              </tbody>
          </table>
          <h1 align="center">
          <input type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to Record & Create?");' name="create_journal" value="Journal Create"></p>
          <input type="hidden" name="delete_mushak_no" value="<?=$VAT_master->mushak_no?>">                              

          <button type="submit" name="delete" class="btn btn-primary" style="font-size: 11px" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to delete?");'>Delete the Mushak - <?=$VAT_master->mushak_no?></button>
  
        </h1>
        <?php endif; ?>
        
        <?php
            if($status>0 && $COUNT_journal>0){?>
            <h3 style="text-align: center;color: red;  font-weight: bold"><i>Mushak challan has been recorded & forwarded to the releavent warehouse!!</i></h3>

            <h3 style="text-align: center;color: red;  font-weight: bold">  
            <button type="submit" name="delete" class="btn btn-primary" style="font-size: 11px" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to delete?");'>Delete the Mushak - <?=$VAT_master->mushak_no?></button>
</h3>
             <?php } else { ?><?php } ?></form>
</body>
</html>
