<?php
require_once 'support_file.php';
$title='LC Report';
if(!empty($_POST['order_by'])) $order_by_GET=$_POST['order_by'];
if(isset($order_by_GET))				{$order_by=' order by lb.'.$order_by_GET;}
if(!empty($_POST['order_by']) && !empty($_POST['sort'])) $order_by_GET=$_POST['order_by'];
if(isset($order_by_GET))				{$order_by=' order by lb.'.$order_by_GET.' '.$_POST[sort].'';}

if(!empty($_POST['party_id'])) $party_id=$_POST['party_id'];
if(isset($party_id))				{$party_id_conn=' and llm.party_id='.$party_id;}
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
        #customers {
            font-family: "Gill Sans", sans-serif;
        }
        #customers td {
        }
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



<?php if ($_POST['report_id']=='2001001'): $sql="SELECT lb.party_id,lb.party_id,lb.ledger_id,lb.buyer_name,lb.contact_person,lb.contact_number,lb.email_id,lb.address,lb.origin from lc_buyer lb
where  lb.status in ('".$_POST[status]."') ".$order_by.""; echo reportview($sql,'LC Buyer Report','98');?>


<?php elseif ($_POST['report_id']=='2002001'): $sql="SELECT lb.id,lb.id,lb.pi_no,lb.pi_issue_date,b.buyer_name from lc_pi_master lb, lc_buyer b
where lb.party_id=b.party_id
"; echo reportview($sql,'PI Summery','98');?>




<?php elseif ($_POST['reporttypes']=='4003'): ?>

    <h2 align="center" style="margin-top: -5px"><?=$_SESSION[company_name];?></h2>
    <h5 align="center" style="margin-top:-15px">LC Wise Cost Summery</h5>
    <h6 align="center" style="margin-top:-15px">LC No: <?=find_a_field('lc_lc_master','lc_no','id='.$_POST[lc_id].'');?></h6>
    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse; font-size: 11px; margin-top: -5px">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th colspan="9" style="border: solid 1px #999; padding:2px; background-color: bisque">LC Information</th>
            <?php
            $lctablew=mysql_query("Select * from LC_expenses_head where status in ('1')");
            while($lcrow=mysql_fetch_array($lctablew)){
                $i=$i+1;
                ?>
            <?php } ?>
            <th colspan="<?=$i;?>" style="border: solid 1px #999; padding:2px; background-color: bisque">LC Expenses Details</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px; background-color: bisque">LC Cost</th>
        </tr>
        <tr style="border: solid 1px #999;font-size:11px">
            <th style="border: solid 1px #999; ">SL</th>
            <!--th style="border: solid 1px #999;">PI NO</th-->
            <th style="border: solid 1px #999; ">LC NO</th>
            <th style="border: solid 1px #999;">LC Date</th>
            <th style="border: solid 1px #999;">FG</th>
            <th style="border: solid 1px #999;">Material Description</th>
            <th style="border: solid 1px #999;">Unit</th>
            <th style="border: solid 1px #999;">Rate</th>
            <th style="border: solid 1px #999;">Qty</th>
            <th style="border: solid 1px #999;">LC Amount</th>
            <?php
            $lctablew=mysql_query("Select * from LC_expenses_head where status in ('1')");
            while($lcrow=mysql_fetch_array($lctablew)){
                ?><th style="border: solid 1px #999; padding:2px; "><?=$lcrow[LC_expenses_head];?></th>
            <?php } ?>
        </tr></thead>
        <tbody>
        <?php
        //$datecon=' and llm.lc_create_date between  "'.$from_date.'" and "'.$to_date.'"';
        $result='Select
				llm.id,
				llm.pi_id,
				llm.lc_issue_date,
				llm.party_id,
				llm.lc_margin,
				llm.lc_no,
				llm.lc_create_date,
				lld.*,
				i.*

				from
				lc_lc_master llm,
				lc_lc_details lld,
				item_info i

				where
				llm.id=lld.lc_id and
				lld.item_id=i.item_id and
				llm.id='.$_POST[lc_id].'
group by lld.id
				order by llm.id, lld.id';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $g=$g+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?=$g;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->lc_no; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->lc_create_date; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->fg; ?></td>
                <td style="border: solid 1px #999; text-align:left;padding:2px;"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?=$data->unit_name; ?></td>
                <td style="border: solid 1px #999; text-align:right; padding:5px"><?=$data->rate; ?></td>
                <td style="border: solid 1px #999; text-align:right; padding:5px"><?=$data->qty; ?></td>
                <td style="border: solid 1px #999; text-align:right; padding:5px"><?=number_format($data->amount,2); ?></td>
                <?php
                $totallcamount=$totallcamount+$data->amount;
                $totalqty=find_a_field('lc_lc_details','SUM(qty)','lc_id='.$_POST[lc_id].'');
                $totalactualcollection=$totalactualcollection+$actualcollection;
                $lctablew=mysql_query("Select lh.* from LC_expenses_head lh where lh.status in ('1')");
                $pwisecosetotal=0;
                while($lcrow=mysql_fetch_array($lctablew)){
                ?><td style="border: solid 1px #999; text-align:right; padding:2px"><?php $COST=find_a_field('lc_lc_master',''.$lcrow[db_column_name].'',''.$lcrow[db_column_name].'='.$lcrow[db_column_name].' and id='.$_POST[lc_id].'');?>
                    <?php
                    $pwisecose=$COST/$totalqty*$data->qty;
                    if($pwisecose>0) echo number_format($pwisecose,2); else echo '-';
                    $pwisecosetotal=$pwisecosetotal+$pwisecose;
                    $grandtotal=$pwisecosetotal;

                    }
                    $grandtotals=$grandtotals+$grandtotal;

                    ?>
                </td>

                <td style="border: solid 1px #999; text-align:right"><?=number_format($grandtotal,2);?></td>
            </tr>
            <?php

        } ?>
        <tr><td colspan="7" style="border: solid 1px #999; text-align:right">Total = </td>
            <td style="border: solid 1px #999; text-align:right"><?=$totalqty;?></td>
            <td style="border: solid 1px #999; text-align:right"><?=number_format($totallcamount,2);?></td>
            <?php
            $lctablew=mysql_query("Select lh.* from LC_expenses_head lh where lh.status in ('1')");
            while($lcrow=mysql_fetch_array($lctablew)){
                ?><td style="border: solid 1px #999; text-align:right; padding:2px"><?php $COST=find_a_field('lc_lc_master',''.$lcrow[db_column_name].'',''.$lcrow[db_column_name].'='.$lcrow[db_column_name].' and id='.$_POST[lc_id].''); if($COST>0) echo $COST; else echo '';?></td>
            <?php } ?>
            <td style="border: solid 1px #999; text-align:right"><?=number_format($grandtotals,0);?></td>
        </tr>
        </tbody>
    </table>
    </div>
    </div>
    </div>


    


<?php elseif ($_POST['reporttypes']=='6005'):
$page="print_preview_manPower.php";
$unique="manPowerApp_id";
?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=600,left = 250,top = -1");}
    </script>
    <h2 align="center" style="margin-top: -5px"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-15px">Manpower Application</h4>
<?php if($_POST['department']){?>
    <h5 align="center" style="margin-top:-15px">Department : <?=find_a_field('department','DEPT_DESC','DEPT_ID='.$_POST[department].'')?></h5>
<?php } ?>
    <h5 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>
    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">Date</th>
            <th style="border: solid 1px #999; padding:2px;">Requisition By</th>
            <th style="border: solid 1px #999; padding:2px;">Designation</th>
            <th style="border: solid 1px #999; padding:2px">Requisition for Designation</th>
            <th style="border: solid 1px #999; padding:2px">Requisition for Department</th>
            <th style="border: solid 1px #999; padding:2px">Preferred Education </th>
            <th style="border: solid 1px #999; padding:2px">No of Vacancies</th>
        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.application_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['PBI_ID']>0) 				$PBI_ID=$_POST['PBI_ID'];
        if(isset($PBI_ID)) 				{$PBI_ID_con=' and p.PBI_ID='.$PBI_ID;}

        if($_POST['department']>0) 			 $department=$_POST['department'];
        if(isset($department))				{$department_CON=' and p.PBI_DEPARTMENT='.$department;}

        $result='select  m.*,p.*,
des.*,dep.*

				from
				man_power_application m,
				personnel_basic_info p,
				designation des,
				department dep

				where
				m.PBI_ID=p.PBI_ID and
				p.PBI_DESIGNATION=des.DESG_ID and
				p.PBI_DEPARTMENT=dep.DEPT_ID and
				m.status in ("APPROVED")
		       '.$datecon.$department_CON.$PBI_ID_con.'   order by m.application_date DESC';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){
            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal;cursor: pointer" onclick="DoNavPOPUP('<?=$data->$unique?>', 'TEST!?', 900, 600)">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center; width: 5%"><?=$data->application_date;?></td>
                <td style="border: solid 1px #999; text-align:left;  width: 15%;  padding:2px"><?=$data->PBI_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->DESG_DESC;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->requisition_for_designation;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->requisition_for_department;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->preferred_education;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->no_of_vacancies;?></td>

            </tr>
        <?php  } ?>
        </tbody>
    </table>

    <?php elseif ($_POST['report_id']=='2003001'):
    $sql="Select llm.id,llm.id as LC_Id,llm.lc_no,llm.pi_id,llm.lc_issue_date as issue_date,concat(llm.party_id,' : ',lb.buyer_name) as Buyer,i.item_id as 'Mat. Code',i.item_name as 'Mat. Description',i.unit_name as 'UoM',lld.qty,lld.rate,lld.amount as Value from
    lc_lc_master llm,
    lc_lc_details lld,
    lc_buyer lb,
    item_info i
    where
    llm.id=lld.lc_id and
    llm.lc_issue_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'".$party_id_conn." and
    llm.party_id=lb.party_id and
    lld.item_id=i.item_id order by llm.id,lld.item_id"; echo reportview($sql,'LC Summery Report','98');?>

    <?php elseif ($_POST['report_id']=='2003002'):
    $sql="Select 
    l.lc_id,
    l.lc_id,
    i.item_id,
    i.item_name,
    i.unit_name,
    lr.qty,
    l.per_unit_cost
    
    from

    item_info i, 
    LC_item_wise_cost_sheet l,
    lc_lc_received lr

    where l.item_id=i.item_id and
    lr.item_id=l.item_id and
    l.lc_id=lr.lc_id
    order by l.lc_id,i.item_id"; echo reportview($sql,'Item Wise Cost Sheet','98');?>

<?php endif; ?>
</body>
</html>