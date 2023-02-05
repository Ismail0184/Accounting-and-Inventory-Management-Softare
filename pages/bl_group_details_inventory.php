<?php
require_once 'support_file.php';





$tdate=$_REQUEST['tdate'];
//fdate-------------------
$fdate=$_REQUEST["fdate"];
$comparisonF=$_REQUEST['comparisonF'];
$comparisonT=$_REQUEST["comparisonT"];

$j=0;
for($i=0;$i<strlen($fdate);$i++){

if(is_numeric($fdate[$i]))
$time1[$j]=$time1[$j].$fdate[$i]; 
else $j++;}



$fdate=mktime(0,0,-1,$time1[1],$time1[0],$time1[2]);
//tdate-------------------
$j=0;
for($i=0;$i<strlen($tdate);$i++)
{
if(is_numeric($tdate[$i]))
$time[$j]=$time[$j].$tdate[$i];
else $j++;
}

$tdate=mktime(23,59,59,$time[1],$time[0],$time[2]);
//comparisonF date 
$j=0;
for($i=0;$i<strlen($comparisonF);$i++)
{
if(is_numeric($comparisonF[$i]))
$time3[$j]=$time3[$j].$comparisonF[$i];
else $j++;}

$comparisonF=mktime(0,0,-1,$time3[1],$time3[0],$time3[2]);
//comparisonF date


//comparisonT date 

$j=0;

for($i=0;$i<strlen($comparisonT);$i++){
if(is_numeric($comparisonT[$i]))
$time2[$j]=$time2[$j].$comparisonT[$i];
else $j++;}
$comparisonT=mktime(23,59,59,$time2[1],$time2[0],$time2[2]);



//comparisonT date
//echo $fdate.'<br>'.$tdate.'<br>'.$comparisonF.'<br>'.$comparisonT.'<br>';
if(isset($_REQUEST['tdate'])&&$_REQUEST['tdate']!='')
$report_detail.='<br>Reporting Period: '.$_REQUEST['fdate'].' to '.$_REQUEST['tdate'].'';


$cname = find_a_field('ledger_group','group_name','group_id='.$_GET[groupid])
?>







<title>Trial Balance</title>
<style>
    #customers {
        font-family: "Gill Sans", sans-serif;
    }
    #customers td {
    }
    #customers tr:ntd-child(even)
    {background-color: #f0f0f0;}
    #customers tr:hover {background-color: #ddd;}
    td{
        text-align: center;

    }
</style>

<h2 style="text-align: center"><?=$cname;?></h2>
<h2 style="text-align: center">As on: <?=$cname;?></h2>

      <table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
      <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
          <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
          <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
          <th style="border: solid 1px #999; padding:2px; width: 10%">Dr. Amount</th>
          <th style="border: solid 1px #999; padding:2px; width: 10%">Cr. Amount</th>
          <th style="border: solid 1px #999; padding:2px; width: 10%">Balance Amount</th>
      </tr>

	 
<?php

$sectionid=$_SESSION['sectionid'];
$companyid=$_SESSION['companyid'];

if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and j.section_id='".$sectionid."' and j.company_id='".$companyid."'";
}

$result=mysqli_query($conn, "Select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id  from accounts_ledger l, journal j where 
l.ledger_group_id in ('1007','1011') and 
l.ledger_id=j.ledger_id and j.jvdate <='".$_GET[asdateCurrent]."'".$sec_com_connection."
group by l.ledger_id
order by l.ledger_id");
while($row=mysqli_fetch_array($result)){?>
    <tr style="border: solid 1px #999; font-size:11px">
        <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i++; ?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: left"><?=$row[ledger_id]; ?> - <?=$row[ledger_name]; ?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt],2);?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[cr_amt],2);?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt]-$row[cr_amt],2); ?></td>
</tr>
<?php
    $total_dr_amt=$total_dr_amt+$row[dr_amt];
    $total_cr_amt=$total_cr_amt+$row[cr_amt];
} ?>



  <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
      <td colspan="2" style="border: solid 1px #999; padding:2px; text-align: right">Total <?$cname;?></td>
      <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt,2);?></td>
      <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_cr_amt,2);?></td>
      <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt-$total_cr_amt,2);?></td>
  
  </tr>   
     
  

  </tbody></table>
