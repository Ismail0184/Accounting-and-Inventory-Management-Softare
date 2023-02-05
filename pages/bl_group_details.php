<?php
require_once 'support_file.php';
$cname = $_GET[headname];
?>
<title><?=$cname;?></title>
<style>
    #customers {
        font-family: "Gill Sans", sans-serif;
    }
    #customers td {
    }
    #customers tr:ntd-child(even)
    {background-color: #f0f0f0;}
    #customers tr:hover {background-color: #ddd;}
    td{text-align: center; }
</style>
<script type="text/javascript">
    function hide()
    {
        document.getElementById("pr").style.display = "none";
    }
</script>

<div id="pr" style="margin-left:48%">
    <div align="left">
        <form id="form1" name="form1" method="post" action="">
            <p><input name="button" type="button" onclick="hide();window.print();" value="Print" /></p>
        </form>
    </div>
</div>

<h2 align="center"><?=$_SESSION['company_name'];?></h2>
<h4 style="text-align: center; margin-top: -10px"><?=$cname;?></h4>
<h5 style="text-align: center; margin-top: -10px">As on : <?=$_GET[tdate];?></h5>
<?php if($_GET[income] && $_GET[expenses]) { ?>
<table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
      <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
          <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
          <th style="border: solid 1px #999; padding:2px; width: 10%">Group</th>
          <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
          <th style="border: solid 1px #999; padding:2px; width: 10%; display: none">Dr. Amount</th>
          <th style="border: solid 1px #999; padding:2px; width: 10%; display: none">Cr. Amount</th>
          <th style="border: solid 1px #999; padding:2px; width: 15%">Balance Amount</th>
      </tr>

<?php
$sectionid=$_SESSION['sectionid'];
$companyid=$_SESSION['companyid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and j.section_id='".$sectionid."' and j.company_id='".$companyid."'";
}

$result = mysqli_query($conn,'select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id,g.group_class
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 
j.group_for='.$_SESSION['usergroup'].' and 
j.jvdate <= "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 
g.group_class in ("'.$_GET[income].'","'.$_GET[expenses].'")'.$sec_com_connection.' 

group by l.ledger_id
order by g.group_class,l.ledger_id ');
while($row=mysqli_fetch_array($result)){?>
    <tr style="border: solid 1px #999; font-size:11px">
        <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i++; ?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: center"><?php if($row[group_class]=='3000') echo '3000 - Income'; else echo '4000 - Expenses'; ?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: left; margin-left: 5px"><?=$row[ledger_id]; ?>-<?=$row[ledger_name]; ?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right; display: none"><?=number_format($row[dr_amt],2);?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right; display: none"><?=number_format($row[cr_amt],2);?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt]-$row[cr_amt],2); ?></td>
</tr>
<?php
    $total_dr_amt=$total_dr_amt+$row[dr_amt];
    $total_cr_amt=$total_cr_amt+$row[cr_amt];
} ?>

  <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
      <td colspan="3" style="border: solid 1px #999; padding:2px; text-align: right">Total <?$cname;?></td>
      <td style="border: solid 1px #999; padding:2px; text-align: right;display: none"><?=number_format($total_dr_amt,2);?></td>
      <td style="border: solid 1px #999; padding:2px; text-align: right;display: none"><?=number_format($total_cr_amt,2);?></td>
      <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt-$total_cr_amt,2);?></td>
 </tr>
</tbody></table>
<?php } else { ?>
<table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
            <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
            <th style="border: solid 1px #999; padding:2px; width: 12%">ledger ID</th>
            <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%; display: none">Dr. Amount</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%; display: none">Cr. Amount</th>
            <th style="border: solid 1px #999; padding:2px; width: 15%">Balance Amount</th>
        </tr>

        <?php
        $sectionid=$_SESSION['sectionid'];
        $companyid=$_SESSION['companyid'];
        if($sectionid=='400000'){
            $sec_com_connection=' and 1';
        } else {
            $sec_com_connection=" and j.section_id='".$sectionid."' and j.company_id='".$companyid."'";
        }

        $result = mysqli_query($conn,'select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 
j.jvdate <= "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 
g.com_id in ("'.$_GET[com_id].'")'.$sec_com_connection.' 

group by l.ledger_id
order by l.ledger_id ');
        while($row=mysqli_fetch_array($result)){?>
            <tr style="border: solid 1px #999; font-size:11px">
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i=$i+1; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$row[ledger_id]; ?></td>
                <td style="border: solid 1px #999; padding:2px;margin-left: 5px; text-align: left"><?=$row[ledger_name]; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: none"><?=number_format($row[dr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: none"><?=number_format($row[cr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt]-$row[cr_amt],2); ?></td>
            </tr>
            <?php
            $total_dr_amt=$total_dr_amt+$row[dr_amt];
            $total_cr_amt=$total_cr_amt+$row[cr_amt];
        } ?>

        <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
            <td colspan="3" style="border: solid 1px #999; padding:2px; text-align: right">Total <?$cname;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display: none"><?=number_format($total_dr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display: none"><?=number_format($total_cr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt-$total_cr_amt,2);?></td>
        </tr>
        </tbody></table>
<?php } ?>