<?php
require_once 'support_file.php';
$cname = $_GET[headname];

$sectionid=$_SESSION['sectionid'];
$companyid=$_SESSION['companyid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
} else {
    $sec_com_connection=" and j.section_id='".$sectionid."' and j.company_id='".$companyid."'";
}
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
<h5 style="text-align: center; margin-top: -10px">Date Interval : <?=$_GET[fdate];?> to <?=$_GET[tdate];?></h5>
<?php if($_GET[rno]=='1') { ?>
    <table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
            <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
            <th style="border: solid 1px #999; padding:2px; width: 12%">ledger ID</th>
            <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%; display:none">Dr. Amount</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%; display:none">Cr. Amount</th>
            <th style="border: solid 1px #999; padding:2px; width: 15%">Balance Amount</th>
        </tr>
        <?php
       $result = mysqli_query($conn,'select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 

j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 
j.cr_amt>0 and
g.com_id in ('.$_GET[com_id].')'.$sec_com_connection.' 

group by l.ledger_id
order by l.ledger_id ');
        while($row=mysqli_fetch_array($result)){?>
            <tr style="border: solid 1px #999; font-size:11px">
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i++; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$row[ledger_id]; ?></td>
                <td style="border: solid 1px #999; padding:2px;margin-left: 5px; text-align: left"><?=$row[ledger_name]; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display:"><?=number_format($row[dr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display:"><?=number_format($row[cr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[cr_amt],2); ?></td>
            </tr>
            <?php
            $total_dr_amt=$total_dr_amt+$row[dr_amt];
            $total_cr_amt=$total_cr_amt+$row[cr_amt];
        } ?>

        <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
            <td colspan="3" style="border: solid 1px #999; padding:2px; text-align: right">Total <?$cname;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display:"><?=number_format($total_dr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display:"><?=number_format($total_cr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_cr_amt,2);?></td>
        </tr>
        </tbody></table>


    <?php } elseif($_GET[rno]=='2') { ?>
        <table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
            <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
                <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
                <th style="border: solid 1px #999; padding:2px; width: 12%">ledger ID</th>
                <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
                <th style="border: solid 1px #999; padding:2px; width: 10%; display: ">Dr. Amount</th>
                <th style="border: solid 1px #999; padding:2px; width: 10%; display: ">Cr. Amount</th>
                <th style="border: solid 1px #999; padding:2px; width: 15%">Balance Amount</th>
            </tr>

            <?php
            $result = mysqli_query($conn,'select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 

j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 
j.dr_amt>0 and
g.com_id in ('.$_GET[com_id].')'.$sec_com_connection.' 

group by l.ledger_id
order by l.ledger_id ');
            while($row=mysqli_fetch_array($result)){?>
                <tr style="border: solid 1px #999; font-size:11px">
                    <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i++; ?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$row[ledger_id]; ?></td>
                    <td style="border: solid 1px #999; padding:2px;margin-left: 5px; text-align: left"><?=$row[ledger_name]; ?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[dr_amt],2);?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[cr_amt],2);?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt],2); ?></td>
                </tr>
                <?php
                $total_dr_amt=$total_dr_amt+$row[dr_amt];
                $total_cr_amt=$total_cr_amt+$row[cr_amt];
            } ?>

            <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
                <td colspan="3" style="border: solid 1px #999; padding:2px; text-align: right">Total <?$cname;?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right; display: "><?=number_format($total_dr_amt,2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right; display: "><?=number_format($total_cr_amt,2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt,2);?></td>
            </tr>
            </tbody></table>

<?php } elseif($_GET[rno]=='3') { ?>
    <table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
            <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
            <th style="border: solid 1px #999; padding:2px; width: 12%">ledger ID</th>
            <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%; display: ">Dr. Amount</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%; display: ">Cr. Amount</th>
            <th style="border: solid 1px #999; padding:2px; width: 15%">Balance Amount</th>
        </tr>
        <?php
       $result = mysqli_query($conn,'select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 

j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 
g.com_id in ('.$_GET[com_id].')'.$sec_com_connection.' 

group by l.ledger_id
order by l.ledger_id ');
        while($row=mysqli_fetch_array($result)){?>
            <tr style="border: solid 1px #999; font-size:11px">
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i=$i+1; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$row[ledger_id]; ?></td>
                <td style="border: solid 1px #999; padding:2px;margin-left: 5px; text-align: left"><?=$row[ledger_name]; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[dr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[cr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt],2); ?></td>
            </tr>
            <?php
            $total_dr_amt=$total_dr_amt+$row[dr_amt];
            $total_cr_amt=$total_cr_amt+$row[cr_amt];
        }?>

        <?php
        $res = mysqli_query($conn,'select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 

j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 
j.cc_code in ('.$_GET[cc_code].')'.$sec_com_connection.' 

group by l.ledger_id
order by l.ledger_id ');
        while($row=mysqli_fetch_array($res)){?>
            <tr style="border: solid 1px #999; font-size:11px">
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$j=$j+1; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$row[ledger_id]; ?></td>
                <td style="border: solid 1px #999; padding:2px;margin-left: 5px; text-align: left"><?=$row[ledger_name]; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[dr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[cr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt]-$row[cr_amt],2); ?></td>
            </tr>
            <?php
            $total_dr_amt=$total_dr_amt+$row[dr_amt];
            $total_cr_amt=$total_cr_amt+$row[cr_amt];
        }?>

        <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
            <td colspan="3" style="border: solid 1px #999; padding:2px; text-align: right">Total <?=$_GET[headname];?> = </td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display: "><?=number_format($total_dr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display: "><?=number_format($total_cr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt-$total_cr_amt,2);?></td>
        </tr>
        </tbody></table>
        <?php } elseif($_GET[rno]=='4') { 
		$result = 'select l.ledger_id,concat(l.ledger_id," - ",l.ledger_name) as ledger_name,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,SUM(j.dr_amt)-SUM(j.cr_amt) as balance
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 
j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and
j.cc_code in ('.$_GET[cc_code].')'.$sec_com_connection.' 
group by l.ledger_id
order by l.ledger_id';
		echo bl_pl_support_data_view($result,'','70')?>
        
        
 <?php } elseif($_GET[rno]=='5') { 
		$result = 'select l.ledger_id,concat(l.ledger_id," - ",l.ledger_name) as ledger_name,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,SUM(j.dr_amt)-SUM(j.cr_amt) as balance
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 
j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and
j.cc_code in ('.$_GET[cc_code].')'.$sec_com_connection.' 
group by l.ledger_id
order by l.ledger_id';
		echo bl_pl_support_data_view($result,'','70')?>       
            
    
        
            
<?php } elseif($_GET[rno]=='6') { ?>
        <table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
            <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
                <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
                <th style="border: solid 1px #999; padding:2px; width: 12%">ledger ID</th>
                <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
                <th style="border: solid 1px #999; padding:2px; width: 10%; display:">Dr. Amount</th>
                <th style="border: solid 1px #999; padding:2px; width: 10%; display:">Cr. Amount</th>
                <th style="border: solid 1px #999; padding:2px; width: 15%">Balance Amount</th>
            </tr>

            <?php
            $result = mysqli_query($conn,'select l.*,SUM(j.dr_amt) as dr_amt,SUM(j.cr_amt) as cr_amt,j.ledger_id
from 
journal j,
accounts_ledger l, 
ledger_group g 
where 

j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 

j.cc_code in ('.$_GET[cc_code].')'.$sec_com_connection.' 

group by l.ledger_id
order by l.ledger_id ');
            while($row=mysqli_fetch_array($result)){?>
                <tr style="border: solid 1px #999; font-size:11px">
                    <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i++; ?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$row[ledger_id]; ?></td>
                    <td style="border: solid 1px #999; padding:2px;margin-left: 5px; text-align: left"><?=$row[ledger_name]; ?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right;display:"><?=number_format($row[dr_amt],2);?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right;display:"><?=number_format($row[cr_amt],2);?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt],2); ?></td>
                </tr>
                <?php
                $total_dr_amt=$total_dr_amt+$row[dr_amt];
                $total_cr_amt=$total_cr_amt+$row[cr_amt];
            } ?>

            <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
                <td colspan="3" style="border: solid 1px #999; padding:2px; text-align: right">Total <?$cname;?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right; display:"><?=number_format($total_dr_amt,2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right; display:"><?=number_format($total_cr_amt,2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt-$total_cr_amt,2);?></td>
            </tr>
            </tbody></table>             
            
    <?php } else { ?>

<table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
            <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
            <th style="border: solid 1px #999; padding:2px; width: 12%">ledger ID</th>
            <th style="border: solid 1px #999; padding:2px;">Accounts Head</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%;">Dr. Amount</th>
            <th style="border: solid 1px #999; padding:2px; width: 10%;">Cr. Amount</th>
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

j.jvdate between "'.$_GET[fdate].'" and "'.$_GET[tdate].'" and 
j.ledger_id=l.ledger_id and 
l.ledger_group_id=g.group_id and 
g.com_id in ('.$_GET[com_id].')'.$sec_com_connection.' 

group by l.ledger_id
order by l.ledger_id ');
        while($row=mysqli_fetch_array($result)){?>
            <tr style="border: solid 1px #999; font-size:11px">
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i++; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$row[ledger_id]; ?></td>
                <td style="border: solid 1px #999; padding:2px;margin-left: 5px; text-align: left"><?=$row[ledger_name]; ?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[dr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right;display: "><?=number_format($row[cr_amt],2);?></td>
                <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($row[dr_amt]-$row[cr_amt],2); ?></td>
            </tr>
            <?php
            $total_dr_amt=$total_dr_amt+$row[dr_amt];
            $total_cr_amt=$total_cr_amt+$row[cr_amt];
        } ?>

        <tr style="background-color:#FFF; font-size:12px; font-weight:bold; text-align:right">
            <td colspan="3" style="border: solid 1px #999; padding:2px; text-align: right">Total <?$cname;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display: "><?=number_format($total_dr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; display: "><?=number_format($total_cr_amt,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($total_dr_amt-$total_cr_amt,2);?></td>
        </tr>
        </tbody></table>
<?php } ?>