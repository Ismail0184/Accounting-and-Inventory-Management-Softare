<?php

require_once 'support_file.php';
$title='Report';
$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));

$pfrom_date=date('Y-m-d' , strtotime($_POST[pf_date]));
$pto_date=date('Y-m-d' , strtotime($_POST[pt_date]));


$ledger_id=$_REQUEST["ledger_id"];
list( $day,$month,$year1) = split('[/.-]', $_REQUEST['datefrom']);
$dofdate= '20'.$year1.'-'.$month.'-'.$day;

list($dayt,$montht,$yeart) = split('[/.-]', $_REQUEST['dateto']);
$dotdate= '20'.$yeart.'-'.$montht.'-'.$dayt;

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$now = $dateTime->format("d/m/Y  h:i:s A");

$warehouseid=$_POST[warehouse_id];
$_SESSION['company_name']=getSVALUE('company','company_name','where company_id="'.$_SESSION['companyid'].'"');
$sectionid=$_SESSION['sectionid'];
$companyid=$_SESSION['companyid'];

if($sectionid=='400000'){

    $sec_com_connection=' and 1';

} else {

    $sec_com_connection=" and j.section_id='".$sectionid."' and j.company_id='".$companyid."'";

}?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
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
        #customers tr:hover {background-color:#f5f5f5;}
    </style>
</head>
<body style="font-family: cursive;">











<div id="pr" style="margin-left:48%">
    <div align="left">
        <form id="form1" name="form1" method="post" action="">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>







<?php if ($_POST['report_id']=='1'):?>
<h1></h1>

<?php elseif ($_POST['report_id']=='5002002'):
if($_POST['warehouse_id']>0) 					$warehouse_id=$_POST['warehouse_id'];
if(isset($warehouse_id))				{$plat_con=' and w.warehouse_id='.$warehouse_id;} 
if($_POST['item_id']>0) 					$item_id=$_POST['item_id'];
if(isset($item_id))				{$item_con=' and i.item_id='.$item_id;}	

$res="Select i.item_id,isg.sub_group_name,w.warehouse_name as 'CMU / Plant', i.finish_goods_code as FG_Code,i.item_name as FG_Name,i.unit_name as unit,i.pack_size as 'Pcs/ Case',ig.group_name as 'Mat. Type',pid.raw_item_id as 'Mat. Code',mi.item_name as 'Component Description',mi.unit_name as 'UOM',pid.unit_batch_qty as 'BOM Qty',IF(pid.status >=1, 'Active', 'Inactive') as status
from 
production_ingredient_detail pid, 
item_info i,
item_sub_group isg,
warehouse w,
item_group ig,
item_info mi
where 
i.item_id=pid.item_id and 
isg.sub_group_id=i.sub_group_id and 
w.warehouse_id=pid.line_id and
ig.group_id=isg.group_id and 
mi.item_id=pid.raw_item_id".$plat_con.$item_con."
order by pid.line_id,i.item_id,pid.raw_item_id";
echo reportview($res,'SKU Wise Consumption Formula','99');
?>    







<?php elseif ($_POST['report_id']=='5000'):
$warehouse_name=find_a_field('warehouse','warehouse_name','warehouse_id='.$_POST[warehouse_id].'')?>
    <title>Production Report of <?=$warehouse_name;?></title>
    <?php
        $datecon=' and d.pr_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['warehouse_id']>0) 			 $warehouse_id=$_POST['warehouse_id'];
        if(isset($warehouse_id))				{$warehouse_id_CON=' and d.warehouse_from='.$warehouse_id;}
		if($_POST['item_id']>0) 			 $item_id=$_POST['item_id'];
        if(isset($item_id))				{$item_id_CON=' and d.item_id='.$item_id;}
        $sql='select d.pr_no,d.pr_no,d.custom_pr_no as PS_slip,d.pr_date,i.finish_goods_code,i.item_name as finish_goods_name,i.unit_name,i.pack_size,FORMAT((d.total_unit/i.pack_size),0) as Qty_in_Unit,d.total_unit as Qty_in_Pcs,u.fname as production_by,m.status as qc_status
from 
production_floor_receive_detail d,
production_floor_receive_master m,
item_info i,
user_activity_management u
where
i.item_id=d.item_id and 
m.pr_no=d.pr_no and 
m.entry_by=u.user_id
'.$datecon.$warehouse_id_CON.$item_id_CON.' group by d.id
order by d.id';?>
        <?=reportview($sql,'Production Report','98');?>


<?php elseif ($_POST['report_id']=='5001'):
    $warehouse_name=find_a_field('warehouse','warehouse_name','warehouse_id='.$_POST[warehouse_id].'')?>
    <title>Consumption Report of <?=$warehouse_name;?></title>
        <?php
        $datecon=' and d.pi_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['warehouse_id']>0) 			 $warehouse_id=$_POST['warehouse_id'];
        if(isset($warehouse_id))				{$warehouse_id_CON=' and d.warehouse_from='.$warehouse_id;}
        if($_POST['item_id']>0) 			 $item_id=$_POST['item_id'];
        if(isset($item_id))				{$item_id_CON=' and d.item_id='.$item_id;}

        $sql='select d.pi_no,d.pr_no,d.pi_no,d.pi_date,i.finish_goods_code as custom_code,i.item_name as material_name,i.unit_name,i.pack_size,FORMAT((d.total_unit/i.pack_size),0) as Qty_in_Unit,d.total_unit as Qty_in_Pcs

from 
production_floor_issue_detail d,
item_info i
where
i.item_id=d.item_id 
'.$datecon.$warehouse_id_CON.$item_id_CON.' group by d.id
order by d.id'; ?>
    <?=reportview($sql,'Consumption Report','98');?>




<?php elseif ($_POST['report_id']=='5002'):?>
    <?php
        $datecon=' and d.pi_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['warehouse_id']>0) 			 $warehouse_id=$_POST['warehouse_id'];
        if(isset($warehouse_id))				{$warehouse_id_CON=' and d.warehouse_from='.$warehouse_id;}
        if($_POST['item_id']>0) 			 $item_id=$_POST['item_id'];
        if(isset($item_id))				{$item_id_CON=' and d.item_id='.$item_id;}
        $sql='select d.pi_no,d.custom_pi_no as Custom_no,d.pi_no,d.pi_date as date,w_f.warehouse_name as Transfer_from,w_t.warehouse_name as Transfer_to,i.finish_goods_code as Item_Code,i.item_name as Finish_Goods,i.unit_name as unit,i.pack_size,FORMAT((d.total_unit/i.pack_size),0) as Qty_in_Unit,d.total_unit as Qty_in_Pcs,d.total_unit_received as "Rcvd. Qty in Pcs"
from 
production_issue_detail d,
item_info i,
warehouse w_f,
warehouse w_t
where
i.item_id=d.item_id  and 
d.warehouse_from=w_f.warehouse_id and 
d.warehouse_to=w_t.warehouse_id      
'.$datecon.$warehouse_id_CON.$item_id_CON.' group by d.id
order by d.id'; ?>
<?=reportview($sql,'FG Transfer Report (STO)','98');?>













<?php elseif ($_POST['report_id']=='5005'):

/////////////////////////////////////Received and Payments----------------------------------------------------------

    ?>









    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

<h4 align="center" style="margin-top:-10px">Profit & Loss Against Rice</h4>

    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>







    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">

        <thead>

        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));

            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>



        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">

            <th style="border: solid 1px #999; padding:2px">SL</th>

            <th style="border: solid 1px #999; padding:2px; %">PO</th>

            <th style="border: solid 1px #999; padding:2px; ">Dealer Details</th>

            <th style="border: solid 1px #999; padding:2px; ">Town</th>

            <th style="border: solid 1px #999; padding:2px; ">DO</th>

            <th style="border: solid 1px #999; padding:2px; ">DO Date</th>

            <th style="border: solid 1px #999; padding:2px">Delivery Date</th>





            <th style="border: solid 1px #999; padding:2px; ">Invoice Amount</th>

            <th style="border: solid 1px #999; padding:2px;">Commission</th>

            <th style="border: solid 1px #999; padding:2px">Transport Cost</th>



            <th style="border: solid 1px #999; padding:2px">Net Sales</th>

            <th style="border: solid 1px #999; padding:2px">COGS</th>

        <th style="border: solid 1px #999; padding:2px">Profit / Loss</th>

        <th style="border: solid 1px #999; padding:2px">Payment</th>

        <th style="border: solid 1px #999; padding:2px">Outstanding</th>









            </tr></thead>





        <tbody>

      <?php

        $datecon=' and m.po_date between  "'.$from_date.'" and "'.$to_date.'"';

		if($_POST[delivarystatus]=='Deliverd'){

		$delivarystatus=' and sdm.challan_date!="0000-00-00"';

		}



		elseif($_POST[delivarystatus]=='UnDeliverd'){

		$delivarystatus=' and sdm.challan_date="0000-00-00"';

		}

		else {

			$delivarystatus='';

			}



        $result='Select 

		        distinct sdd.do_no,

				m.*,

				di.*,

				

				pi.po_no as PO,

				pi.po_date,

				pi.vendor_id,

				pi.item_id,

				pi.warehouse_id,

				pi.rate,

				pi.do_rate as dorate,

				pi.qty,

				pi.amount,

				pi.do_no as DONO,

				SUM(sdd.total_amt) as INVOICEAMOUNT,

				sdm.commission_amount as COMMISSIONAMOUNT,

				sdm.transport_cost as TRANSPORTMOUNT,

				SUM(pi.rate*sdd.total_unit) as COGS,

				pi.section_id,

				sdm.challan_date,

				t.town_name as town,

				(select SUM(dr_amt) from journal where do_no=sdm.do_no) as payment

				

				

				

				

				

				

				

				from

				purchase_master m,

				purchase_invoice pi,

				dealer_info di,

				item_info i,

				sale_do_details sdd,

				sale_do_master sdm,

				town t

				 

				where 

				m.po_no=pi.po_no and 

				i.item_id=sdd.item_id and

				sdd.item_id=pi.item_id and 

				sdd.item_id not in ("1096000100010313") and

				m.work_order_for_department in ("Sales") and

				sdm.dealer_code=di.dealer_code and 

				sdm.do_no=sdd.do_no and 

				di.town_code=t.town_code and

				

				sdd.do_no=pi.do_no 

				  '.$datecon.$delivarystatus.' 

				

				group by  sdm.do_no order by sdm.do_no DESC';

        $query2 = mysql_query($result);







        while($data=mysql_fetch_object($query2)){











            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">

                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>

                <td style="border: solid 1px #999; text-align:center"><a href="po_print_view.php?potype=Sales&po_no=<?=$data->PO?>" target="_blank"><?php echo $data->PO; ?></a></td>

                <td style="border: solid 1px #999; text-align:left"><?php echo $data->dealer_name_e; ?></td>

                <td style="border: solid 1px #999; text-align:left"><?php echo $data->town; ?></td>

                <td style="border: solid 1px #999; text-align:center"><a href="chalan_bill_distributorsrice.php?v_no=<?=$data->DONO;?>" target="_blank"><?php echo $data->DONO; ?></a></td>

                <td style="border: solid 1px #999; text-align:center; padding:5px"><?php echo $data->po_date; ?></td>

                <td style="border: solid 1px #999; text-align:center; padding:5px"><?php if($data->challan_date!=='0000-00-00') echo $data->challan_date; else echo '<font style="color:red; font-weight:bold">Not yet delivered!</font>'; ?></td>



       <td style="border: solid 1px #999; text-align:right; padding:5px"><?php echo number_format($data->INVOICEAMOUNT,2); ?></td>

       <td style="border: solid 1px #999; text-align:right; padding:5px"><?=number_format($data->COMMISSIONAMOUNT,2); ?></td>

       <td style="border: solid 1px #999; text-align:right; padding:5px"><?php if($data->TRANSPORTMOUNT>0) echo number_format($data->TRANSPORTMOUNT,2); else echo '-'; ?></td>





                <td style="border: solid 1px #999; padding:5px;text-align:right;"><?PHP $NETSALES=$data->INVOICEAMOUNT-($data->COMMISSIONAMOUNT+$data->TRANSPORTMOUNT); echo number_format($NETSALES,2);?></td>

                <td style="border: solid 1px #999; text-align:right; padding:2px"><?=number_format($data->COGS,2);?></td>

                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?php $totalprofitandloss=$data->INVOICEAMOUNT-($data->COMMISSIONAMOUNT+$data->TRANSPORTMOUNT+$data->COGS); echo number_format($totalprofitandloss,2);?></td>



       <td style="border: solid 1px #999; text-align:right; padding:5px">

	   <?php



	   $payment=$data->payment;

	   if($payment>0) echo number_format($payment,2); else echo '-'; ?></td>

       <td style="border: solid 1px #999; text-align:right; padding:5px">

	   <?php

	   $outstanding=$data->COGS-$payment;

	   if($outstanding>0 || $outstanding<0) echo number_format($outstanding,2); else echo '-'; ?>

	   </td>



        </tr>

            <?php

            $totalINVOICEAMOUNT=$totalINVOICEAMOUNT+$data->INVOICEAMOUNT;

			 $totaCOMMISSIONAMOUNT=$totaCOMMISSIONAMOUNT+$data->COMMISSIONAMOUNT;

			  $totalTRANSPORTAMOUNT=$totalTRANSPORTAMOUNT+$data->TRANSPORTMOUNT;

			  $totalCOGSAMOUNT=$totalCOGSAMOUNT+$data->COGS;

			  $TOTALNETSALES=$TOTALNETSALES+$NETSALES;

			  $totalproandossAMOUNT=$totalproandossAMOUNT+$totalprofitandloss;

			  $paymentTotal=$paymentTotal+$payment;

			  $outstandingTotal=$outstandingTotal+$outstanding;





        } ?>

        <tr style="font-size:11px; font-weight:bold">



        <td style="text-align:right;border: solid 1px #999; padding:5px" colspan="7"><strong>Total</strong></td>

         <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($totalINVOICEAMOUNT,2)?></strong></td>

           <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($totaCOMMISSIONAMOUNT,2)?></strong></td>

            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($totalTRANSPORTAMOUNT,2)?></strong></td>

            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($TOTALNETSALES,2)?></strong></td>

            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($totalCOGSAMOUNT,2)?></strong></td>

            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($totalproandossAMOUNT,2)?></strong></td>

            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($paymentTotal,2)?></strong></td>

            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($outstandingTotal,2)?></strong></td>

      </tbody>

    </table></div>

    </div>

    </div>



















<?php elseif ($_POST['report_id']=='5006'):

/////////////////////////////////////Received and Payments----------------------------------------------------------

    ?>









    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

<h4 align="center" style="margin-top:-10px">Accounts Receivable Status</h4>

    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>







    <table align="center"  style="width:95%; border: solid 1px #999; border-collapse:collapse;">

        <thead>

        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));

            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>



        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">

            <th style="border: solid 1px #999; padding:2px">SL</th>

            <th style="border: solid 1px #999; padding:2px; %">PO</th>

            <th style="border: solid 1px #999; padding:2px; ">Dealer Details</th>

            <th style="border: solid 1px #999; padding:2px; ">Town</th>

            <th style="border: solid 1px #999; padding:2px; ">DO</th>

            <th style="border: solid 1px #999; padding:2px; ">DO Date</th>

            <th style="border: solid 1px #999; padding:2px">Delivery Date</th>







            <th style="border: solid 1px #999; padding:2px">COGS</th>

            <th style="border: solid 1px #999; padding:2px; ">Net Sales</th>

            <th style="border: solid 1px #999; padding:2px">Received Amount</th>

            <th style="border: solid 1px #999; padding:2px">Due Amount</th>









            </tr></thead>





        <tbody>

      <?php

        $datecon=' and m.po_date between  "'.$from_date.'" and "'.$to_date.'"';

        $result='Select 

		        distinct sdd.do_no,

				m.*,

				di.*,

				

				pi.po_no as PO,

				pi.po_date,

				pi.vendor_id,

				pi.item_id,

				pi.warehouse_id,

				pi.rate,

				pi.do_rate as dorate,

				pi.qty,

				pi.amount,

				pi.do_no as DONO,

				SUM(sdd.total_amt) as INVOICEAMOUNT,

				sdm.commission_amount as COMMISSIONAMOUNT,

				sdm.transport_cost as TRANSPORTMOUNT,

				SUM(pi.rate*sdd.total_unit) as COGS,

				pi.section_id,

				sdm.challan_date,

				sdm.amount_received_status as ReceivedStatus,

				t.town_name as town,

			   (select SUM(cr_amt) from journal where tr_from in ("receipt") and do_no=sdm.do_no) as receivedamount

				

				

				

				

				

				

				

				from

				purchase_master m,

				purchase_invoice pi,

				dealer_info di,

				item_info i,

				sale_do_details sdd,

				sale_do_master sdm,

				town t

				 

				where 

				m.po_no=pi.po_no and 

				i.item_id=sdd.item_id and

				sdd.item_id=pi.item_id and 

				sdd.item_id not in ("1096000100010313") and

				m.work_order_for_department in ("Sales") and

				sdm.dealer_code=di.dealer_code and 

				sdm.do_no=sdd.do_no and 

				di.town_code=t.town_code and

				

				sdd.do_no=pi.do_no 

				  '.$datecon.' 

				

				group by  sdm.do_no order by sdm.do_no DESC';

        $query2 = mysql_query($result);







        while($data=mysql_fetch_object($query2)){











            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">

                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>

                <td style="border: solid 1px #999; text-align:center"><a href="po_print_view.php?potype=Sales&po_no=<?=$data->PO?>" target="_blank"><?php echo $data->PO; ?></a></td>

                <td style="border: solid 1px #999; text-align:left; vertical-align:middle" valign="middle"><?php





$daysLeft = 0;

if($data->challan_date!=='0000-00-00'){

$fromDate = $data->challan_date;

} else  {

$fromDate=date('Y-m-d');

		}

$curDate = date('Y-m-d');

$daysLeft = abs(strtotime($curDate) - strtotime($fromDate));

$curdate = $daysLeft/(60 * 60 * 24);









                if($data->ReceivedStatus!='COMPLETED') {

				if($curdate>12){



				echo '<font style="color:red; font-weight:bold">'.$data->account_code.'-'.$data->dealer_name_e.'</font>

				<img src="bell-512.png" style="height:15px; weight:20px" />';

				} else { echo $data->account_code.'-'.$data->dealer_name_e;  ?> <?php }







				} else {



				echo '<font style="color:green; font-weight:bold">'.$data->account_code.'-'.$data->dealer_name_e.'</font>

				 <img src="received.png" style="height:15px; weight:20px" />'; } ?></td>





                <td style="border: solid 1px #999; text-align:left"><?php echo $data->town; ?></td>

                <td style="border: solid 1px #999; text-align:center"><a href="chalan_bill_distributorsrice.php?v_no=<?=$data->DONO;?>" target="_blank"><?php echo $data->DONO; ?></a></td>

                <td style="border: solid 1px #999; text-align:center; padding:5px"><?php echo $data->po_date; ?></td>

                <td style="border: solid 1px #999; text-align:center; padding:5px"><?php if($data->challan_date!=='0000-00-00') echo $data->challan_date; else echo '<font style="color:red; font-weight:bold">Not yet delivered!</font>'; ?></td>



        <td style="border: solid 1px #999; text-align:right; padding:2px"><?=number_format($data->COGS,2);?></td>

       <td style="border: solid 1px #999; text-align:right; padding:5px"><?PHP $NETSALES=$data->INVOICEAMOUNT-($data->COMMISSIONAMOUNT+$data->TRANSPORTMOUNT); echo number_format($NETSALES,2);?></td>





                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?php $receivedamount=$data->receivedamount; echo number_format($receivedamount,2);?></td>





                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?php $due=$NETSALES-$receivedamount; echo number_format($due,2);?></td>





        </tr>

            <?php

            $totalINVOICEAMOUNT=$totalINVOICEAMOUNT+$data->INVOICEAMOUNT;

			 $totaCOMMISSIONAMOUNT=$totaCOMMISSIONAMOUNT+$data->COMMISSIONAMOUNT;

			  $totalTRANSPORTAMOUNT=$totalTRANSPORTAMOUNT+$data->TRANSPORTMOUNT;

			  $totalCOGSAMOUNT=$totalCOGSAMOUNT+$data->COGS;

			  $TOTALNETSALES=$TOTALNETSALES+$NETSALES;

			  $totalproandossAMOUNT=$totalproandossAMOUNT+$totalprofitandloss;

			  $dueTotal=$dueTotal+$due;

			  $receivedamountTOTAL=$receivedamountTOTAL+$receivedamount;





        } ?>

        <tr style="font-size:11px; font-weight:bold">



        <td style="text-align:right;border: solid 1px #999; padding:5px" colspan="7"><strong>Total</strong></td>

         <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($totalCOGSAMOUNT,2)?></strong></td>





            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($TOTALNETSALES,2)?></strong></td>

            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($receivedamountTOTAL,2)?></strong></td>



            <td style="text-align:right;border: solid 1px #999; padding:5px"><strong><?=number_format($dueTotal,2)?></strong></td>



      </tbody>

    </table></div>

    </div>

    </div>







<?php elseif ($_POST['report_id']=='allcurrent'):

/////////////////////////////////////Received and Payments----------------------------------------------------------

    ?>









<h2 align="center">International Consumer Products Bangladesh Ltd.</h2>



    <h4 align="center" style="margin-top:-10px">All Customer Current Balance</h4>

    <h5 align="center" style="margin-top:-10px">Report as at <?=$_POST[tdate]?> </h5>







    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse; ">

        <thead>

        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));

            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>



        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">

            <th style="border: solid 1px #999; padding:2px">SL</th>

            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>

            <th style="border: solid 1px #999; padding:2px; width:10%">Accounts Code</th>

            <th style="border: solid 1px #999; padding:2px">Customer Name</th>

            <th style="border: solid 1px #999; padding:2px">Town</th>

            <th style="border: solid 1px #999; padding:2px">Territory</th>

            <!---th style="border: solid 1px #999; padding:2px">Area</th--->

            <th style="border: solid 1px #999; padding:2px">Region</th>

            <th style="border: solid 1px #999; padding:2px">Current Balance</th></tr></thead>





        <tbody>

        <?php

        $datecon=' and j.jv_date<"'.$tdate.'"';

        $result='Select 

				d.dealer_code,

				d.account_code,

				d.dealer_name_e as dealername,

				t.town_name as town,

				a.AREA_NAME as territory,

				

				b.BRANCH_NAME as region, 

				

				SUM(j.cr_amt-j.dr_amt) actualcollection

				from

				

				dealer_info d,

				town t,

				area a,

				

				branch b,

				journal j

				 

				where 

				

				d.canceled!="No" and 

				d.customer_type not in ("display","vip","gift") and 

				d.town_code=t.town_code and 

				a.AREA_CODE=d.area_code and 

				

				 d.region=b.BRANCH_ID and

				j.ledger_id=d.account_code  '.$datecon.' 

				

				group by d.dealer_code order by b.sl,a.AREA_NAME,t.town_name';

        $query2 = mysql_query($result);







        while($data=mysql_fetch_object($query2)){











            $i=$i+1; ?>

            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">

                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>

                <td style="border: solid 1px #999; text-align:center"><?php echo $data->dealer_code; ?></td>

                <td style="border: solid 1px #999; text-align:center"><?php echo $data->account_code; ?></td>

                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->dealername; ?></td>

                <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->town;?></td>

                <td style="border: solid 1px #999; padding:5px"><?=$data->territory;?></td>

                <!---td style="border: solid 1px #999; text-align:left; padding:5px"><?=$data->area;?></td-->

                <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->region;?></td>

                <td style="border: solid 1px #999; text-align:right; padding:2px"><strong><?=number_format($actualcollection=$data->actualcollection,2);?></strong></td>

            </tr>

            <?php

            $totaladjustment=$totaladjustment+$adjustment;

            $totalcollection=$totalcollection+$collection;

            $totalactualcollection=$totalactualcollection+$actualcollection;



        } ?>

        <tr><td colspan="7" style="text-align:right;border: solid 1px #999;">Total</td>



            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalactualcollection,2)?></strong></td>

        </tr>

        </tbody>

    </table></div>

</div>

    </div>





    <br><br></div></div>







<?php elseif ($_POST['report_id']=='5010'):?>



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



    <h2 align="center" style="margin-top: -8px"><?=$_SESSION['company_name'];?></h2>
    <p align="center" style="margin-top:-20px">Trial Balance</p>
    <p align="center" style="margin-top:-12px; font-size: 11px">As On: <?=$_POST[f_date]?></p>
    <table align="center" id="customers" style="width:75%; border: solid 1px #999; border-collapse:collapse; ">

        <thead>

        <p style="width:85%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px" >
            <th style="border: solid 1px #999; padding:2px; width: 4%"><strong>SL</strong></th>
            <th style="border: solid 1px #999; padding:2px;"><strong>Head Of Accounts</strong></th>
            <th style="border: solid 1px #999; padding:2px; width:15%"><strong>Debit Amount</strong></th>
            <th style="border: solid 1px #999; padding:2px; width:15%"><strong>Credit Amount</strong></th>
            <th style="border: solid 1px #999; padding:2px; width:15%"><strong>Balance</strong></th>
        </tr></thead>

        <tbody>

        <?php



        if($sectionid=='400000'){
            $sec_com_connectionT=' and 1';
        } else {
            $sec_com_connectionT=" and b.section_id='".$_SESSION[sectionid]."' and b.company_id='".$_SESSION[companyid]."'";
        }
        $total_dr=0;
        $total_cr=0;
        $cc_code = (int) $_REQUEST['cc_code'];
        if($cc_code > 0)
        { $g="select DISTINCT c.group_name,SUM(dr_amt),SUM(cr_amt),c.group_id from accounts_ledger a, journal b,ledger_group c where a.ledger_id=b.ledger_id and a.ledger_group_id=c.group_id and b.jvdate <= '$from_date' and 1 AND b.cc_code=$cc_code and c.group_for=".$_SESSION['usergroup']." ".$sec_com_connectionT."  group by c.group_name";} else {
            $g="select DISTINCT c.group_name,SUM(dr_amt),SUM(cr_amt),c.group_id 	

		from accounts_ledger a, 
		journal b,
		ledger_group c 
		where 
		a.ledger_id=b.ledger_id and 
		a.ledger_group_id=c.group_id and
		b.jvdate <= '$from_date' and 
		c.group_for=".$_SESSION['usergroup']." ".$sec_com_connectionT." 
		group by c.group_name";
        }

        $gsql=mysql_query($g);
        while($g=mysql_fetch_row($gsql))

        {   $total_dr=0;
            $total_cr=0;  ?>
            <tr bgcolor="#FFCCFF" style="font-size: 11px; height: 20px"><th colspan="5" align="left"><?php echo $g[0];?></th></tr>

            <?php
            $cc_code = (int) $_REQUEST['cc_code'];
            if($cc_code > 0)
            { $p="select DISTINCT a.ledger_name,SUM(dr_amt),SUM(cr_amt) from accounts_ledger a, journal b where a.ledger_id=b.ledger_id and b.jvdate<= '$from_date' and a.ledger_group_id='$g[3]' and 1 AND b.cc_code=$cc_code ".$sec_com_connectionT."  group by ledger_name order by a.ledger_name";
            }else {
                $p="select DISTINCT a.ledger_name,SUM(dr_amt),SUM(cr_amt),a.ledger_id from accounts_ledger a, journal b where a.ledger_id=b.ledger_id and b.jvdate<= '$from_date' and a.ledger_group_id='$g[3]' and 1 ".$sec_com_connectionT."  group by ledger_name order by a.ledger_name";
 }

            $pi=0;
            $sql=mysql_query($p);
            while($p=mysql_fetch_row($sql)){
                $pi++;
                $dr=$p[1];
                $cr=$p[2];
                ?>



                <tr style="border: solid 1px #999; font-size:11px">
                    <td style="border: solid 1px #999; padding:2px; text-align: center"><?php echo $pi;?></td>
                    <td style="border: solid 1px #999; padding:2px 10px 2px 2px; text-align: left"><?php echo $p[0];?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right"><?php echo number_format($dr,2);?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right"><?php echo number_format($cr,2);?></td>
                    <td style="border: solid 1px #999; padding:2px; text-align: right"><?php echo number_format($dr-$cr,2);?></td>
                </tr>



                <?php



                $total_dr=$total_dr+$dr;
                $total_cr=$total_cr+$cr;
                $t_dr=$t_dr+$dr;
                $t_cr=$t_cr+$cr;
            }?>



            <tr bgcolor="#FFCCFF" style="font-size: 11px">
                <th colspan="2"  style="border: solid 1px #999;  text-align: right; ">Total <?php echo $g[0];?>:</th>
                <th style="border: solid 1px #999; text-align: right;"><?php echo number_format($total_dr,2);?></th>
                <th style="border: solid 1px #999; text-align: right;"><?php echo number_format($total_cr,2)?></th>
                <th style="border: solid 1px #999; text-align: right;"><?=number_format($total_dr-$total_cr,2)?></th>

            </tr>



        <?php }?>



        <tr  style="font-size: 12px">
            <th colspan="2" style="border: solid 1px #999;  text-align: right;"><strong>Total Balance : </strong></th>
            <th style="border: solid 1px #999; text-align: right;"><strong><?php echo number_format($t_dr,2);?></strong></th>
            <th style="border: solid 1px #999; text-align: right;"><strong><?php echo number_format($t_cr,2)?></strong></th>
            <th style="border: solid 1px #999; text-align: right;"><strong><?=number_format(($t_dr-$t_cr),2);?></strong></th>
        </tr>
        </tbody>

    </table></div>

    </div>

    </div>
















<?php elseif ($_POST['report_id']=='500002'):?>


<title>Daily IMS Report</title>
<h2 align="center"><?=$_SESSION['company_name'];?></h2>
<h4 align="center" style="margin-top:-13px">Daily IMS Report</h4>
<?php if($_POST['tsm']) { ?><h6 align="center" style="margin-top:-13px"><?=find_a_field("personnel_basic_info","PBI_NAME","PBI_ID=".$_POST[tsm]."");?></h6><?php } ?>
<h6 align="center" style="margin-top:-13px">From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h6>

<table align="center" id="customers" style="width:98%; border: solid 1px #999; border-collapse:collapse; ">
    <thead>
    <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
        echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
    <thead>
    <tr bgcolor="#FFCCFF" style="border: solid 1px #999;font-weight:bold; font-size:11px">
        <th style="border: solid 1px #999; padding:2px;">Sl</th>
        <th style="border: solid 1px #999; padding:2px;">IMS NO</th>
        <th style="border: solid 1px #999; padding:2px;">IMS Date</th>
        <th style="border: solid 1px #999; padding:2px;">TSM Name</th>
        <th style="border: solid 1px #999; padding:2px;">Tarritory</th>
        <th style="border: solid 1px #999; padding:2px;">DB / Super DB</th>
        <th style="border: solid 1px #999; padding:2px;">SO Code</th>
        <th style="border: solid 1px #999; padding:2px;">SO Name</th>
        <th style="border: solid 1px #999; padding:2px;">FG Code</th>
        <th style="border: solid 1px #999; padding:2px;">FG Description</th>
        <th style="border: solid 1px #999; padding:2px;">Unit</th>
        <th style="border: solid 1px #999; padding:2px;">IMS Qty</th>
        <th style="border: solid 1px #999; padding:2px;">Effect TP</th>
        <th style="border: solid 1px #999; padding:2px;">Amount</th>
    </tr>
    </thead>

    <tbody>

    <?php
    $datecon=' and d.ims_date between  "'.$_POST[f_date].'" and "'.$_POST[t_date].'"';
    if($_POST['tsm']>0) 				$tsm=$_POST['tsm'];
    if(isset($tsm)) 				{$tsm_con=' and d.TSM_PBI_ID='.$tsm;}
    $query=mysqli_query($conn, 'Select d.*,i.*,p.PBI_NAME as tsm_name,p2.PBI_NAME as so_name,p2.PBI_ID_UNIQUE as socode,a.AREA_NAME as territory

from 

ims_details d,
item_info i,
personnel_basic_info p,
personnel_basic_info p2,
area a
  
  where 
  
  d.item_id=i.item_id and  
  d.total_unit_today>0 and 
  d.PBI_ID=p2.PBI_ID and
  d.TSM_PBI_ID=p.PBI_ID and
  d.TSM_PBI_ID=a.PBI_ID'.$datecon.$tsm_con.' group by d.id order by d.ims_date,p.PBI_NAME,p2.PBI_NAME,d.ims_no,d.item_id asc');
    while($target_row=mysqli_fetch_object($query)){


        ?>

        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i=$i+1;?></td>
            <td style="border: solid 1px #999; padding:2px 10px 2px 2px; text-align: left"><?=$target_row->ims_no;?></td>
            <td style="border: solid 1px #999; padding:2px 10px 2px 2px; text-align: left"><?=$target_row->ims_date;?></td>
            <td style="border: solid 1px #999; padding:2px 10px 2px 2px; text-align: left"><?=$target_row->tsm_name;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=$target_row->territory;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=$target_row->dealer;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: left"><?=$target_row->socode;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: left"><?=$target_row->so_name;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$target_row->finish_goods_code;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: left"><?=$target_row->item_name;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$target_row->unit_name;?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($target_row->total_unit_today,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($target_row->unit_price,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($target_row->total_amt_ims,2);?></td>
        </tr>
        <?php
        $total_target_amount=$total_target_amount+$target_row->total_amt_ims;
    } ?>
    <tr style="font-size: 12px; font-weight: bold">
        <td colspan="13" style="text-align: right;border: solid 1px #999; padding:2px;">Total Target in amount = </td>
        <td style="text-align: right;border: solid 1px #999; padding:2px;"><?=number_format($total_target_amount,2);?></td>
    </tr>

    </tbody>




<?php elseif ($_POST['report_id']=='5007'):?>

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


<title><?=$_SESSION['company_name'];?> | Monthly Target Report</title>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-13px">Monthly Target Report</h4>
<h6 align="center" style="margin-top:-13px">For the month of <?=find_a_field("monthname","monthfullName","month=".$_POST[month]."");?>, <?=$_POST[year];?></h6>

<table align="center" id="customers" style="width:90%; border: solid 1px #999; border-collapse:collapse; ">
    <thead>
    <p style="width:85%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
        echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
    <thead>
    <tr bgcolor="#FFCCFF" style="border: solid 1px #999;font-weight:bold; font-size:11px">
        <th style="border: solid 1px #999; padding:2px;">Sl</th>
        <th style="border: solid 1px #999; padding:2px;">TSM Name</th>
        <th style="border: solid 1px #999; padding:2px;">Tarritory</th>
        <th style="border: solid 1px #999; padding:2px;">DB / Super DB</th>
        <th style="border: solid 1px #999; padding:2px;">SO Code</th>
        <th style="border: solid 1px #999; padding:2px;">SO Name</th>
        <th style="border: solid 1px #999; padding:2px;">FG Code</th>
        <th style="border: solid 1px #999; padding:2px;">FG Description</th>
        <th style="border: solid 1px #999; padding:2px;">Unit</th>
        <th style="border: solid 1px #999; padding:2px;">Target Qty</th>
        <th style="border: solid 1px #999; padding:2px;">Target Amount</th>
       </tr>
    </thead>

    <tbody>

 <?php
 $query=mysqli_query($conn, "Select d.*,i.*,p.PBI_NAME as tsm_name,p2.PBI_NAME as so_name,p2.PBI_ID_UNIQUE as socode,a.AREA_NAME as territory,
 (select dealer_name_e from dealer_info where dealer_code=(select super_dealer_code from sub_db_info where sub_db_code=p2.sub_db_code)) as dealer
 from 
ims_monthly_target_details d,
item_info i,
personnel_basic_info p,
personnel_basic_info p2,
area a
  
  where 
  d.item_id=i.item_id and  
  d.amount not in ('0') and 
  d.PBI_ID=p2.PBI_ID and
  d.TSM_PBI_ID=p.PBI_ID and
  d.TSM_PBI_ID=a.PBI_ID and
  d.month=".$_POST[month]." and d.year=".$_POST[year]." group by d.id order by p.PBI_ID,p2.PBI_ID asc,d.item_id");
 while($target_row=mysqli_fetch_object($query)){


 ?>

    <tr style="border: solid 1px #999; font-size:11px">
        <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$i=$i+1;?></td>
        <td style="border: solid 1px #999; padding:2px 10px 2px 2px; text-align: left"><?=$target_row->tsm_name;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=$target_row->territory;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=$target_row->dealer;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: left"><?=$target_row->socode;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: left"><?=$target_row->so_name;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$target_row->finish_goods_code;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: left"><?=$target_row->item_name;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: center"><?=$target_row->unit_name;?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($target_row->target_revised/$target_row->pack_size,2);?></td>
        <td style="border: solid 1px #999; padding:2px; text-align: right"><?=number_format($target_row->amount,2);?></td>
    </tr>
    <?php
 $total_target_amount=$total_target_amount+$target_row->amount;
 } ?>
    <tr style="font-size: 12px; font-weight: bold">
        <td colspan="10" style="text-align: right;border: solid 1px #999; padding:2px;">Total Target in amount = </td>
        <td style="text-align: right;border: solid 1px #999; padding:2px;"><?=number_format($total_target_amount,2);?></td>
    </tr>

    </tbody>



    </div></div></div>







<?php elseif ($_POST['report_id']=='5008'):?>
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
<title><?=$_SESSION['company_name'];?> | Financial Statement<</title>



    <?php include ("bl_support.php") ?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h4 align="center" style="margin-top:-13px">Financial Statement</h4>


    <table align="center" id="customers" style="width:70%; border: solid 1px #999; border-collapse:collapse; ">
        <thead><p style="width:85%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>


        <tr bgcolor="#FFCCFF" style="border: solid 1px #999;font-weight:bold; font-size:13px">
            <th width="40%" style="border: solid 1px #999; padding:2px;"><span class="style1">PARTICULARS</span></th>
            <th width="30%" align="center" style="border: solid 1px #999; padding:2px;"><div align="center">Current Period<br>( <?=$_REQUEST['t_date'];?> )</div></th>
            <th width="30%" align="center" style="border: solid 1px #999; padding:2px;"><div align="center">Previous Period<br>( <?=$_REQUEST['pt_date'];?> )</div></th> </tr></thead>









        <tr style="background:#FFF0F5; font-weight:bold; color:#FFF; font-size:14px;">
            <td colspan="3" style="color:#000;border: solid 1px #999; padding:2px;; text-align: left" ><em>ASSETS</em></td></tr>
        <tr style="font-weight:bold; color:#000; font-size:13px;"><td colspan="3" style="border: solid 1px #999; padding:2px; text-align: left;">Non current Assets :</td></tr>

        <tr style="border: solid 1px #999; font-size:11px">

            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px">
                <a href="bl_group_details.php?group_type=asset&groupid=1001&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Property Plant Equipment</a></td>
            <td  style="border: solid 1px #999; padding:2px; text-align: right;"><strong>

                    <?php
                    $PPESearch=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
                        a.ledger_group_id, 
                        SUM(j.dr_amt-j.cr_amt)  as TotalPPE 
                        from  
                        accounts_ledger a,
                        journal j 
                        WHERE  
                        a.ledger_id=j.ledger_id and 
                        a.ledger_group_id in ('1001') and 
                        j.jvdate <= '$to_date' ".$sec_com_connection."");
                    while($PPESearchRow=mysql_fetch_object($PPESearch)){
                        $TotalPPE=$PPESearchRow->TotalPPE;
                    }echo number_format($TotalPPE,2); ?></strong></td>







            <td  style="border: solid 1px #999; padding:2px; text-align: right;"><strong>

                    <?php

                    $PPESearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as TotalPPEPRE
				   
				   from 			   

				   accounts_ledger a,
				   journal j
				   
				   WHERE  

				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('1001') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");

                    while($PPESearchRowPrevious=mysql_fetch_object($PPESearchPrevious)){
                        $TotalPPEPrevious=$PPESearchRowPrevious->TotalPPEPRE;
                    }  echo number_format($TotalPPEPrevious,2);?></strong>
            </td></tr>











        <tr style="border: solid 1px #999; font-size:11px">

            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2012&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Less: Accumulated Depreciation</a></td>

            <td  style="border: solid 1px #999; padding:2px; text-align: right;font-size:12px"><?php

                $ADSearch=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,	   

				   SUM(j.dr_amt-j.cr_amt)  as ADCurrent
				    from 
		   

				   accounts_ledger a,
				   journal j
				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2012') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($ADSearchRow=mysql_fetch_object($ADSearch)){







                    $TotalADCurrent=$ADSearchRow->ADCurrent;

                }

                echo number_format($TotalADCurrent,2);

                ?></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;font-size:12px"><?php

                $ADSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as ADPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2012') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($ADSearchRowPrevious=mysql_fetch_object($ADSearchPrevious)){



                    $TotalADPrevious=$ADSearchRowPrevious->ADPrevious;

                }echo number_format($TotalADPrevious,2);?></td>

        </tr>







        <tr style="font-weight:bold; font-size: 12px">
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><strong></strong></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><? $grossAssetsCurrent = ($TotalPPE-$TotalADCurrent);
                if($grossAssetsCurrent>0){
                    $grossAssetsCurrents=number_format($grossAssetsCurrent,2);
                } else {
                    $grossAssetsCurrents=	"(".number_format(substr($grossAssetsCurrent,1),2).")";
                }
                echo $grossAssetsCurrents;?></td>


            <td style="border: solid 1px #999; padding:2px; text-align: right;">
                <? $grossAssetsPrevious = ($TotalPPEPrevious-$TotalADPrevious);
                if($grossAssetsPrevious>0){
                    $grossAssetsPreviouss=number_format($grossAssetsPrevious,2);
                } else {
                    $grossAssetsPreviouss=	"(".number_format(substr($grossAssetsPrevious,1),2).")";
                }
                echo $grossAssetsPreviouss;?>
            </td>

        </tr>









        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px">
                <a href="bl_group_details.php?group_type=asset&groupid=1007&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Inventory</a></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php
                $InventorySearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as InventoryCurrent
				    from 
				   accounts_ledger a,
				   journal j
				    WHERE  
					a.ledger_id=j.ledger_id and 
					a.ledger_group_id in ('1007','1011') and 
					j.jvdate <= '$to_date' ".$sec_com_connection."");
                while($InvendoryROWCurrent=mysql_fetch_object($InventorySearchCurrent)){
                    $TotalInventoryCurrent=$InvendoryROWCurrent->InventoryCurrent;
                } echo number_format($TotalInventoryCurrent,2);
                ?>
            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $InventorySearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   SUM(j.dr_amt-j.cr_amt)  as InventoryPrevious

				   from 				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1007','1011') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($InvendoryROWPrevious=mysql_fetch_object($InventorySearchPrevious)){



                    $TotalInventoryPrevious=$InvendoryROWPrevious->InventoryPrevious;

                }

                echo number_format($TotalInventoryPrevious,2);

                ?></td></tr>

















        <tr style="border: solid 1px #999; font-size:11px">

            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=asset&groupid=1006&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Accounts Receivable</a></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $ARSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,	   

				   SUM(j.dr_amt-j.cr_amt)  as ARCurrent
				    from 			   

				   accounts_ledger a,
				   journal j
				    WHERE  

					a.ledger_id=j.ledger_id and 
					a.ledger_group_id in ('1006') and 
					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($ARROWCurrent=mysql_fetch_object($ARSearchCurrent)){



                    $TotalARCurrent=$ARROWCurrent->ARCurrent;

                }

                echo number_format($TotalARCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $ARSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as ARPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1006') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($ARROWPrevious=mysql_fetch_object($ARSearchPrevious)){

                    $TotalARPrevious=$ARROWPrevious->ARPrevious;

                }

                echo number_format($TotalARPrevious,2);

                ?></td></tr>









        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=asset&groupid=1003&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Advance, Deposit & Prepayment</a></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php
                $ADPSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as ADPCurrent
				   
				   from 			   

				   accounts_ledger a,
				   journal j
				    WHERE  
					a.ledger_id=j.ledger_id and 
					a.ledger_group_id in ('1003') and 
					j.jvdate <= '$to_date' ".$sec_com_connection."");
                while($ADPROWCurrent=mysql_fetch_object($ADPSearchCurrent)){
                    $TotalADPCurrent=$ADPROWCurrent->ADPCurrent;
                }
                echo number_format($TotalADPCurrent,2);
                ?>
            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php
                $ADPSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as ADPPrevious

				    from 
				    accounts_ledger a,
				    journal j

				    WHERE
					a.ledger_id=j.ledger_id and 
					a.ledger_group_id in ('1003') and 
					j.jvdate <= '$pto_date' ".$sec_com_connection."");
                while($ADPROWPrevious=mysql_fetch_object($ADPSearchPrevious)){
                    $TotalADPPrevious=$ADPROWPrevious->ADPPrevious;
                }
                echo number_format($TotalADPPrevious,2);

                ?></td></tr>















        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=asset&groupid=1009&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Long Term Investment</a></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php
                $LTISearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
	   

				   

				   SUM(j.dr_amt-j.cr_amt)  as LTICurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1009') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($LTIROWCurrent=mysql_fetch_object($LTISearchCurrent)){





                    $TotalLTICurrent=$LTIROWCurrent->LTICurrent;

                }

                echo number_format($TotalLTICurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $LTISearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as LTIPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1009') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($LTIROWPrevious=mysql_fetch_object($LTISearchPrevious)){



                    $TotalLTIPrevious=$LTIROWPrevious->LTIPrevious;

                }

                echo number_format($TotalLTIPrevious,2);

                ?></td></tr>











        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=asset&groupid=1008&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Deferred Expenses</a></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php
                $DESearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,
				   SUM(j.dr_amt-j.cr_amt)  as DEPCurrent
				    from 


				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1008') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($DEROWCurrent=mysql_fetch_object($DESearchCurrent)){

                    $TotalDEPCurrent=$DEROWCurrent->DEPCurrent;

                }

                echo number_format($TotalDEPCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $DESearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   SUM(j.dr_amt-j.cr_amt)  as DEPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1008') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($DEROWPrevious=mysql_fetch_object($DESearchPrevious)){

                    $TotalDEPrevious=$DEROWPrevious->DEPrevious;

                }

                echo number_format($TotalDEPrevious,2);

                ?></td></tr>









        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=asset&groupid=1005&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Advance Income Tax
                </a></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php
                $AITSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,
				   

				   SUM(j.dr_amt-j.cr_amt)  as AITCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1005') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($AITROWCurrent=mysql_fetch_object($AITSearchCurrent)){





                    $TotalAITCurrent=$AITROWCurrent->AITCurrent;

                }

                echo number_format($TotalAITCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $AITSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as AITPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1005') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($AITROWPrevious=mysql_fetch_object($AITSearchPrevious)){



                    $TotalAITPrevious=$AITROWPrevious->AITPrevious;

                }

                echo number_format($TotalAITPrevious,2);

                ?></td></tr>







        <tr style="border: solid 1px #999; font-size:11px">

            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=asset&groupid=1002&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Cash & Cash Equivalents

                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $CCESearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as CCECurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1002') and 

					j.jvdate <= '$to_date' and a.ledger_id between '1002000100010000' and '1002000101000000' ".$sec_com_connection."");



                while($CCEROWCurrent=mysql_fetch_object($CCESearchCurrent)){

                    $TotalCCECurrent=$CCEROWCurrent->CCECurrent;

                }

                echo number_format($TotalCCECurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $CCESearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as CCEPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1002') and 

					j.jvdate <= '$pto_date' and a.ledger_id between '1002000100010000' and '1002000101000000' ".$sec_com_connection."");

                while($CCEROWPrevious=mysql_fetch_object($CCESearchPrevious)){



                    $TotalCCEPrevious=$CCEROWPrevious->CCEPrevious;

                }

                echo number_format($TotalCCEPrevious,2);

                ?></td></tr>











        <tr style="border: solid 1px #999; font-size:11px">

            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=asset&groupid=1002&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Bank Balance

                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $BBSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as BBCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1002') and 

					j.jvdate <= '$to_date' and a.ledger_id between '1002000900010000' and '1002000901000000' ".$sec_com_connection."");

                while($BBROWCurrent=mysql_fetch_object($BBSearchCurrent)){

                    $TotalBBCurrent=$BBROWCurrent->BBCurrent;

                }

                echo number_format($TotalBBCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $BBSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   

				   

				   SUM(j.dr_amt-j.cr_amt)  as BBPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('1002') and 

					j.jvdate <= '$pto_date' and a.ledger_id between '1002000900010000' and '1002000901000000' ".$sec_com_connection."");

                while($BBROWPrevious=mysql_fetch_object($BBSearchPrevious)){



                    $TotalBBPrevious=$BBROWPrevious->BBPrevious;

                }

                echo number_format($TotalBBPrevious,2);

                ?></td></tr>











        <tr style="font-weight:bold; font-size: 13px;">
            <td style="text-align:right;"><strong>Total Current Assets</strong></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><? $TotalAssetsCurrent = ($TotalInventoryCurrent+$TotalARCurrent+$TotalADPCurrent+$TotalDEPCurrent+$TotalAITCurrent+$TotalCCECurrent+$TotalBBCurrent+$TotalLTICurrent);
                if($TotalAssetsCurrent>0){
                    $TotalAssetsCurrents=number_format($TotalAssetsCurrent,2);
                } else {
                    $TotalAssetsCurrents='('.number_format(substr($TotalAssetsCurrent,1),2).')';
                }
                echo $TotalAssetsCurrents; ?></td>


            <td style="border: solid 1px #999; padding:2px; text-align: right;">
                <? $TotalAssetsPrevious = ($TotalInventoryPrevious+$TotalARPrevious+$TotalADPPrevious+$TotalDEPrevious+$TotalAITPrevious+$TotalCCEPrevious+$TotalBBPrevious+$TotalLTIPrevious);
                if($TotalAssetsPrevious>0){
                    $TotalAssetsPreviouss=number_format($TotalAssetsPrevious,2);
                } else {
                    $TotalAssetsPreviouss='('.number_format(substr($TotalAssetsPrevious,1),2).')';
                }
                echo $TotalAssetsPreviouss; ?>
            </td>
        </tr>











        <tr style="font-size: 14px">
            <td  style="border: solid 1px #999; padding:2px; text-align: right;"><strong><i>Total Asset</i></strong></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><strong><? $TotalAssetsCurrent = ($grossAssetsCurrent+$TotalAssetsCurrent);
                    if($TotalAssetsCurrent>0){
                        $TotalAssetsCurrents=number_format($TotalAssetsCurrent,2);
                    } else {
                        $TotalAssetsCurrents='('.number_format(substr($TotalAssetsCurrent,1),2).')';
                    }
                    echo $TotalAssetsCurrents; ?></strong></td>

            <td style="border: solid 1px #999; padding:2px; text-align: right;"><strong><? $TotalAssetsPrevious = ($grossAssetsPrevious+$TotalAssetsPrevious);
                    if($TotalAssetsPrevious>0){
                        $TotalAssetsPreviouss=number_format($TotalAssetsPrevious,2);
                    } else {
                        $TotalAssetsPreviouss='('.number_format(substr($TotalAssetsPrevious,1),2).')';
                    }
                    echo $TotalAssetsPreviouss; ?></strong>
            </td>
        </tr>















        <tr style="background:#FFF0F5; font-weight:bold; color:#FFF; font-size:14px;">
            <td colspan="3" style="color:#000;border: solid 1px #999; padding:2px; text-align: left" ><em>EQUITY AND LIABILITIES</em>
            </td>
        </tr>





        <tr style="font-weight:bold; color:#000; font-size:13px;"><td colspan="3" style="border: solid 1px #999; padding:2px; text-align: left;">Shareholder's Equity:</td></tr>







        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px">
                <a href="bl_group_details.php?group_type=liabilities&groupid=2001&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Share Capital</a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $SCSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id,	   

				   SUM(j.cr_amt-j.dr_amt)  as SCCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2001') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($SCROWCurrent=mysql_fetch_object($SCSearchCurrent)){



                    $TotalSCCurrent=$SCROWCurrent->SCCurrent;

                }

                echo number_format($TotalSCCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $SCSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as SCPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2001') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($SCROWPrevious=mysql_fetch_object($SCSearchPrevious)){





                    $TotalSCPrevious=$SCROWPrevious->SCPrevious;

                }

                echo number_format($TotalSCPrevious,2);

                ?></td></tr>



















        <tr style="border: solid 1px #999; font-size:11px">

            <td style="padding-left:20px; text-align: left"><a href="bl_group_details.php?group_type=liabilities&groupid=2005&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Reserves & Surplus

                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $RNSSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as RNSCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2005') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($RNSROWCurrent=mysql_fetch_object($RNSSearchCurrent)){





                    $TotalRNSCurrent=$RNSROWCurrent->RNSCurrent;

                }

                echo number_format($TotalRNSCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $RNSSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as RNSPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2005') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($RNSROWPrevious=mysql_fetch_object($RNSSearchPrevious)){



                    $TotalRNSPrevious=$RNSROWPrevious->RNSPrevious;

                }

                echo number_format($TotalRNSPrevious,2);

                ?></td></tr>







        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px;padding-left:20px; text-align: left;"><a href="bl_group_details.php?group_type=liabilities&groupid=2001&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">P/L</a></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; font-size: 12px"><strong>
                <?php
                $patCurrent=$patCurrent;
                echo number_format($patCurrent,2);?></strong>
            </td>


            <td style="border: solid 1px #999; padding:2px; text-align: right; font-size: 12px">
                <strong><?php
                $patPrevious=$patPrevious;
                echo number_format($patPrevious,2);?></strong>
            </td>
        </tr>









        <tr style="border: solid 1px #999; padding:2px; text-align: right;"><td></td>

            <td style="border: solid 1px #999; padding:2px; text-align: right;font-size: 12px">

                <strong><?php $totalSEQUITYCurrent=$TotalSCCurrent+$TotalRNSCurrent+$patCurrent; echo number_format($totalSEQUITYCurrent,2);?></strong></td>

            <td style="border: solid 1px #999; padding:2px; text-align: right;font-size: 12px">
                <strong><?php $totalSEQUITYPrevious=$TotalSCPrevious+$TotalRNSPrevious+$patPrevious; echo number_format($totalSEQUITYPrevious,2);?></strong></td>

        </tr>





        <tr style="font-weight:bold; color:#000; font-size:13px;"><td colspan="3" style="border: solid 1px #999; padding:2px; text-align: left;"><strong>LONG TERM LOAN:</strong></td></tr>







        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2011&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Bank Loan(HPSM)

                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $BLHPSMSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as BLHPSMCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2011') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($BLHPSMROWCurrent=mysql_fetch_object($BLHPSMSearchCurrent)){





                    $TotalBLHPSMCurrent=$BLHPSMROWCurrent->BLHPSMCurrent;

                }

                echo number_format($TotalBLHPSMCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $BLHPSMSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as BLHPSMPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2011') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($BLHPSMROWPrevious=mysql_fetch_object($BLHPSMSearchPrevious)){





                    $TotalBLHPSMPrevious=$BLHPSMROWPrevious->BLHPSMPrevious;

                }

                echo number_format($TotalBLHPSMPrevious,2);

                ?></td></tr>











        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2010&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Unsecured Loan</a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php
                $UNLSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,
				   a.ledger_group_id, 	   

				   SUM(j.cr_amt-j.dr_amt)  as UNLCurrent
				    from 
			   

				   accounts_ledger a,
				  journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2010') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($UNLROWCurrent=mysql_fetch_object($UNLSearchCurrent)){





                    $TotalUNLCurrent=$UNLROWCurrent->UNLCurrent;

                }

                echo number_format($TotalUNLCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $UNLSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as UNLPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2010') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($UNLROWPrevious=mysql_fetch_object($UNLSearchPrevious)){



                    $TotalUNLPrevious=$UNLROWPrevious->UNLPrevious;

                }

                echo number_format($TotalUNLPrevious,2);

                ?></td></tr>









        <tr style="font-weight:bold;"><td></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; font-size: 12px"><?php $totalLTOCurrent=$TotalBLHPSMCurrent+$TotalUNLCurrent; echo number_format($totalLTOCurrent,2)?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right; font-size: 12px"><?php $totalLTOPrevious=$TotalBLHPSMPrevious+$TotalUNLPrevious; echo number_format($totalLTOPrevious,2);?></td>

        </tr>









        <tr style="font-weight:bold; color:#000; font-size:13px;"><td colspan="3" style="border: solid 1px #999; padding:2px; text-align: left;"><strong>CURRENT LIABILITIES:</strong></td></tr>









        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2003&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Short Term Loan

                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $STLOANSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as STLOANCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2003') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($STLOANROWCurrent=mysql_fetch_object($STLOANSearchCurrent)){





                    $TotalSTLOANSMCurrent=$STLOANROWCurrent->STLOANCurrent;

                }

                echo number_format($TotalSTLOANSMCurrent,2);

                ?>

            </td>







            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $BSTLOANSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as STLOANPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2003') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($STLOANROWPrevious=mysql_fetch_object($BSTLOANSearchPrevious)){

                    $TotalSTLOANPrevious=$STLOANROWPrevious->STLOANPrevious;

                }

                echo number_format($TotalSTLOANPrevious,2);

                ?></td></tr>











        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2007&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Provision for expenses

                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $PFESearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as PFECurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2007') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($PFEROWCurrent=mysql_fetch_object($PFESearchCurrent)){





                    $TotalPFECurrent=$PFEROWCurrent->PFECurrent;

                }

                echo number_format($TotalPFECurrent,2);

                ?>

            </td>

            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $PFESearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as PFEPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2007') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($PFEROWPrevious=mysql_fetch_object($PFESearchPrevious)){



                    $TotalPFEPrevious=$PFEROWPrevious->PFEPrevious;

                }

                echo number_format($TotalPFEPrevious,2);

                ?></td></tr>







        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2002&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Accounts Payable



                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $APSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as APCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2002','2014') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($APROWCurrent=mysql_fetch_object($APSearchCurrent)){

                    $TotalAPCurrent=$APROWCurrent->APCurrent;

                }

                echo number_format($TotalAPCurrent,2);

                ?>

            </td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $APSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as APPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2002','2014') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($APROWPrevious=mysql_fetch_object($APSearchPrevious)){



                    $TotalAPPrevious=$APROWPrevious->APPrevious;

                }

                echo number_format($TotalAPPrevious,2);

                ?></td></tr>









        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2004&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Statutory Payables





                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $SPSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as SPCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2004') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($SPROWCurrent=mysql_fetch_object($SPSearchCurrent)){

                    $TotalSPCurrent=$SPROWCurrent->SPCurrent;

                }

                echo number_format($TotalSPCurrent,2);

                ?>

            </td>





            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $SPSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as SPPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2004') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($SPROWPrevious=mysql_fetch_object($SPSearchPrevious)){



                    $TotalSPPrevious=$SPROWPrevious->SPPrevious;

                }

                echo number_format($TotalSPPrevious,2);

                ?></td></tr>









        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2009&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Intercompany Payable







                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $IPSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as IPCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2009') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($IPMROWCurrent=mysql_fetch_object($IPSearchCurrent)){

                    $TotalIPCurrent=$IPMROWCurrent->IPCurrent;

                }

                echo number_format($TotalIPCurrent,2);

                ?>

            </td>





            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $IPSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as IPPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2009') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($IPROWPrevious=mysql_fetch_object($IPSearchPrevious)){



                    $TotalIPPrevious=$IPROWPrevious->IPPrevious;

                }

                echo number_format($TotalIPPrevious,2);

                ?></td></tr>















        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2013&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Share money deposit









                </a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $SMDSearchCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as SMDCurrent

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2013') and 

					j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($SMDROWCurrent=mysql_fetch_object($SMDSearchCurrent)){



                    $TotalSMDCurrent=$SMDROWCurrent->SMDCurrent;

                }

                echo number_format($TotalSMDCurrent,2);

                ?>

            </td>





            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $SMDSearchPrevious=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,   

				   

				   SUM(j.cr_amt-j.dr_amt)  as SMDPrevious

				    from 

				   

				   accounts_ledger a,

				   journal j

				    WHERE  

					a.ledger_id=j.ledger_id and 

					a.ledger_group_id in ('2013') and 

					j.jvdate <= '$pto_date' ".$sec_com_connection."");

                while($SMDROWPrevious=mysql_fetch_object($SMDSearchPrevious)){



                    $TotalSMDPrevious=$SMDROWPrevious->SMDPrevious;

                }

                echo number_format($TotalSMDPrevious,2);

                ?></td></tr>











        <tr style="border: solid 1px #999; font-size:11px">
            <td style="border: solid 1px #999; padding:2px; text-align: left; padding-left:20px"><a href="bl_group_details.php?group_type=liabilities&groupid=2006&asdateCurrent=<?php echo $to_date; ?>&&asdatePrevious=<?php echo $pto_date; ?>" target="_new" style="text-decoration:none">Liability for Employee Benefits</a></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $LEBQueryCurrent=mysql_query("Select distinct j.ledger_id, a.ledger_id,a.ledger_name,

				   a.ledger_group_id,

				   SUM(j.cr_amt-j.dr_amt)  as LEBCurrent

				   from 

				   				   

				   accounts_ledger a,

				   journal j

				   WHERE  

				   a.ledger_id=j.ledger_id and 

				   a.ledger_group_id in ('2006') and 

				   j.jvdate <= '$to_date' ".$sec_com_connection."");

                while($LEBROWCurrent=mysql_fetch_object($LEBQueryCurrent)){

                    $TotalLEBCurrent=$LEBROWCurrent->LEBCurrent;

                }

                echo number_format($TotalLEBCurrent,2);

                ?>

            </td>





            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php

                $LEBQueryPrevious=mysql_query("Select distinct 

				   j.ledger_id, 

				   a.ledger_id,

				   a.ledger_name,

				   a.ledger_group_id, 				   

				   SUM(j.cr_amt-j.dr_amt)  as LEBPrevious

				   from 				   				   

				   accounts_ledger a,

				   journal j

				   WHERE  

				   a.ledger_id=j.ledger_id and 
				   a.ledger_group_id in ('2006') and 
				   j.jvdate <= '$pto_date' ".$sec_com_connection."");
                while($LEBROWPrevious=mysql_fetch_object($LEBQueryPrevious)){
                    $TotalLEBPrevious=$LEBROWPrevious->LEBPrevious; }
                echo number_format($TotalLEBPrevious,2);

                ?></td></tr>







        <tr style="font-weight:bold; font-size: 12px; text-align: right"><td>Total Current Liabilities</td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php $totalCLIABILITIESCurrent=$TotalSTLOANSMCurrent+$TotalPFECurrent+$TotalAPCurrent+$TotalSPCurrent+$TotalIPCurrent+$TotalSMDCurrent+$TotalLEBCurrent; echo number_format($totalCLIABILITIESCurrent,2);?></td>
            <td style="border: solid 1px #999; padding:2px; text-align: right;"><?php $totalCLIABILITIESPrevious=$TotalSTLOANPrevious+$TotalPFEPrevious+$TotalAPPrevious+$TotalSPPrevious+$TotalIPPrevious+$TotalSMDPrevious+$TotalLEBPrevious; echo number_format($totalCLIABILITIESPrevious,2)?></td>
        </tr>







































        <tr>
            <td  style="border: solid 1px #999; padding:2px; text-align: right;"><strong><i>Total Equity and Liabilities</i></strong></td>



            <td style="border: solid 1px #999; padding:2px; text-align: right;"><strong><? $TOTALENLCurrent =$totalSEQUITYCurrent+ $totalCLIABILITIESCurrent+$totalLTOCurrent;

                    if($TOTALENLCurrent>0){

                        echo number_format($TOTALENLCurrent,2); } else { echo '('.number_format(substr($TOTALENLCurrent,1),2).')'; } ?></strong></td>





            <td style="border: solid 1px #999; padding:2px; text-align: right;"><strong><? //$patPrevious = $pbtPrevious-$incomeTaxPrevious; echo number_format($patPrevious,2); ?>



                    <?

                    $TOTALENLPrevious =$totalSEQUITYPrevious+ $totalCLIABILITIESPrevious+$totalLTOPrevious;

                    if($TOTALENLPrevious>0){

                        echo number_format($TOTALENLPrevious,2); } else { echo '('.number_format(substr($TOTALENLPrevious,1),2).')'; } ?></strong></td>

        </tr>





    </table>





    <br><br>

    </div>

    </div>

    </div>


<?php elseif ($_POST['report_id']=='60010'):?>




    <style>
        #customers {
            font-family: Calibri;
        }
        #customers td {
        }
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #FFCCFF;}
        td{
            text-align: center;
        }</style>
    <title><?=$_SESSION['company_name'];?> | Present Stock</title>

    <h2 align="center"><?=$_SESSION['company_name'];?></h2>

    <h5 align="center" style="margin-top:-15px">Present Stock (Material)</h5>
    <h6 align="center" style="margin-top:-15px">Warehouse Name: <?= getSVALUE('warehouse','warehouse_name','WHERE warehouse_id="'.$_POST[warehouse_id].'"');?> </h6>
    <h6 align="center" style="margin-top:-15px">Report as on <?=$_POST[t_date]?></h6>
    <table align="center" id="customers"  style="width:80%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr bgcolor="#FFCCFF" style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px">S/L</th>
            <th style="border: solid 1px #999; padding:2px">Code</th>
            <th style="border: solid 1px #999; padding:2px">Material Description</th>
            <th style="border: solid 1px #999; padding:2px">Material Sub Group</th>
            <th style="border: solid 1px #999; padding:2px">Material Group</div></th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th>Rate</th>
            <th style="border: solid 1px #999; padding:2px">Present Stock</th>
            <th>Amount</th>
        </tr>
        </thead>

        <tbody>
<?php
        $fgresult="Select  j.item_id, i.item_id,i.item_name,i.finish_goods_code,i.unit_name,i.pack_size,i.serial, s.sub_group_id, s.group_id, g.group_id,s.sub_group_name,g.group_name,
SUM(j.item_in-j.item_ex) as presentstock,
j.item_price
from
item_info i,
journal_item j,
item_sub_group s,
item_group g 
where 
j.item_id=i.item_id and 
j.warehouse_id='".$_POST[warehouse_id]."' and 
j.ji_date <= '".$to_date."' and 
i.sub_group_id=s.sub_group_id and 
s.group_id=g.group_id and
g.group_id not in ('500000000')
group by j.item_id,j.item_price order by j.item_id,s.sub_group_id, g.group_id DESC,i.serial";
        $persentrow = mysqli_query($conn, $fgresult);
        while($data=mysqli_fetch_object($persentrow)){ ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?=$ismail=$ismail+1;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->item_id;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->group_name;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->unit_name;?></td>

                <td style="border: solid 1px #999; text-align:center"><?=number_format($data->item_price,2);?></td>
                <td style="border: solid 1px #999; text-align:center"><?=number_format($pstock=$data->presentstock,2);?></td>
                <td style="border: solid 1px #999; text-align:center"><?=number_format($amount=$pstock*$data->item_price,2);?></td>
            </tr>
            <?php $ttotalclosing=$ttotalclosing+$pstock;
            $ttamount=$ttamount+$amount;
        } ?>
        <tr style="font-size:12px; font-weight:bold; border: solid 1px #999;">
            <td colspan="7" style="text-align:right;border: solid 1px #999;"> Total</td>
            <td style="text-align:center;border: solid 1px #999; width: auto"><?=number_format($ttotalclosing,2)?></td>
            <td style="text-align:center;border: solid 1px #999; width: auto"><?=number_format($ttamount,2)?></td>
        </tr>
        </tbody>
    </table></div>
    </div>
    </div>


<?php elseif ($_POST['report_id']=='60011'):?>
    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h5 align="center" style="margin-top:-15px">Present Stock (Finish Goods)</h5>
    <h6 align="center" style="margin-top:-15px">Warehouse Name: <?= getSVALUE('warehouse','warehouse_name','WHERE warehouse_id="'.$_POST[warehouse_id].'"');?> </h6>
    <h6 align="center" style="margin-top:-15px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h6>
    <table align="center"  style="width:80%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px">S/L</th>
            <th style="border: solid 1px #999; padding:2px">Code</th>
            <th style="border: solid 1px #999; padding:2px">FG Description</th>
            <th style="border: solid 1px #999; padding:2px">FG Sub Group</th>
            <th style="border: solid 1px #999; padding:2px">FG Group</div></th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">Pk. Size</th>
            <th style="border: solid 1px #999; padding:2px">Present Stock</th>
        </tr>
        </thead>

        <tbody>
        <?php
        $fgresult="Select  j.item_id, i.item_id,i.item_name,i.finish_goods_code,i.unit_name,i.pack_size,i.serial, s.sub_group_id, s.group_id, g.group_id,s.sub_group_name,g.group_name,
SUM(j.item_in-j.item_ex) as presentstock
from
item_info i,
journal_item j,
item_sub_group s,
item_group g 
where 
j.item_id=i.item_id and 
j.warehouse_id='".$_POST[warehouse_id]."' and 
j.ji_date <= '".$to_date."' and 
i.sub_group_id=s.sub_group_id and 
s.group_id=g.group_id and
g.group_id in ('500000000')
group by j.item_id order by g.group_id DESC,i.serial";
        $persentrow = mysqli_query($conn, $fgresult);
        while($data=mysqli_fetch_object($persentrow)){ ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?=$ismail=$ismail+1;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->item_id;?></td>
                <td style="border: solid 1px #999; text-align:left"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->sub_group_name;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->group_name;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->unit_name;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=$data->pack_size;?></td>
                <td style="border: solid 1px #999; text-align:center"><?=number_format($pstock=$data->presentstock,2);?></td>
            </tr>
            <?php $ttotalclosing=$ttotalclosing+$pstock;  } ?>
        <tr style="font-size:12px; font-weight:bold; border: solid 1px #999;">
            <td colspan="7" style="text-align:right;border: solid 1px #999;"> Total</td>
            <td style="text-align:center;border: solid 1px #999; width: auto"><?=number_format($ttotalclosing,2)?></td>
        </tr>
        </tbody>
    </table></div>
    </div>
    </div>





<?php endif; ?>









</body>

</html>



</html>