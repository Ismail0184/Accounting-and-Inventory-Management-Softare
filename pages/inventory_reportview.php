<?php
require_once 'support_file.php';
$title='Report';


$from_date=date('Y-m-d' , strtotime($_POST[f_date]));
$to_date=date('Y-m-d' , strtotime($_POST[t_date]));

list( $day,$month,$year1) = split('[/.-]', $_REQUEST['datefrom']);
$dofdate= '20'.$year1.'-'.$month.'-'.$day;

list($dayt,$montht,$yeart) = split('[/.-]', $_REQUEST['dateto']);
$dotdate= '20'.$yeart.'-'.$montht.'-'.$dayt;

$warehouseid=$_POST[warehouse_id];
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report</title>
    <script type="text/javascript">

        function hide()

        {

            document.getElementById("pr").style.display = "none";

        }

    </script>

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



<?php if ($_POST['reporttypes']=='5006'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>









    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">Cash Collection and Shipment in Value (Total Country)</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[fdate]?> to <?=$_POST[tdate]?></h5>


    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px; width:10%">Accounts Code</th>
            <th style="border: solid 1px #999; padding:2px">Dealder Name</th>
            <th style="border: solid 1px #999; padding:2px">Town</th>
            <th style="border: solid 1px #999; padding:2px">Territory</th>
            <!---th style="border: solid 1px #999; padding:2px">Area</th--->
            <th style="border: solid 1px #999; padding:2px">Region</th>
            <!--th style="border: solid 1px #999; padding:2px">Adjustment</th>
            <th style="border: solid 1px #999; padding:2px">Collection</th--->
            <th style="border: solid 1px #999; padding:2px">Actual Collection</th>
            <th style="border: solid 1px #999; padding:2px">Shipment</th>

        </tr></thead>


        <tbody>
        <?php
        $datecon=' and j.jv_date between  "'.$from_date.'" and "'.$to_date.'"';
        $result='Select 
				d.dealer_code,
				d.dealer_custom_code as Customcode,
				d.account_code,
				d.dealer_name_e as dealername,
				t.town_name as town,
				a.AREA_NAME as territory,				
				b.BRANCH_NAME as region
				
				
				from
				
				dealer_info d,
				town t,
				area a,				
				branch b,
				sale_do_master m
				
				 
				where 
				
				 
				d.customer_type not in ("display","gift","export") and 
				d.dealer_category not in ("Rice") AND 
				d.town_code=t.town_code and 
				a.AREA_CODE=d.area_code and 
				b.BRANCH_ID=d.region 
				
				group by d.dealer_code order by b.sl,a.AREA_NAME,t.town_name,d.dealer_code';
        $query2 = mysql_query($result);



        while($data=mysql_fetch_object($query2)){

            $collection = find_a_field('journal','SUM(cr_amt-dr_amt)','jvdate between "'.$from_date.'" and "'.$to_date.'" and tr_from not in ("Sales","SalesReturn","Journal_info") and ledger_id='.$data->account_code);

            $shipment=find_a_field('sale_do_details','SUM(total_amt)','do_type in ("sales","") and do_date between "'.$from_date.'" and "'.$to_date.'"  and dealer_code='.$data->dealer_code);


            if($collection>0 || $shipment>0) {

                $i=$i+1; ?>
                <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                    <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                    <td style="border: solid 1px #999; text-align:center"><?php echo $data->Customcode; ?></td>
                    <td style="border: solid 1px #999; text-align:center"><?php echo $data->account_code; ?></td>
                    <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->dealername; ?></td>
                    <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->town;?></td>
                    <td style="border: solid 1px #999; padding:5px"><?=$data->territory;?></td>
                    <!---td style="border: solid 1px #999; text-align:left; padding:5px"><?=$data->area;?></td--->
                    <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->region;?></td>

                    <td style="border: solid 1px #999; text-align:right;padding:2px"><?php  echo number_format($collection,2);?></td>
                    <td style="border: solid 1px #999; text-align:right;padding:2px"><?php echo number_format($shipment,2) ?></td>

                </tr>
                <?php
                $totaladjustment=$totaladjustment+$adjustment;
                $totalcollection=$totalcollection+$collection;
                $totalactualcollection=$totalactualcollection+$actualcollection;
                $totalcollectionreport=$totalcollectionreport+$collection;
                $totalshipment=$totalshipment+$shipment;


            }} ?>
        <tr><td colspan="5" style="text-align:right;border: solid 1px #999;">Total</td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totaladjustment,2)?></strong></td>
            <td style="text-align:right;border: solid 1px #999;"><strong></strong></td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalcollectionreport,2)?></strong></td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalshipment,2)?></strong></td>

        </tr>
        </tbody>
    </table> </div>
    </div>
    </div>






<?php elseif ($_POST['reporttypes']=='5001'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">Transaction Statment</h4>
    
    <?php if($_POST['status']=='Received'){?> 
            <h4 align="center" style="margin-top:-10px">Status : Received</h4>
            <?php } elseif ($_POST['status']=='Issue'){?> 
			<h4 align="center" style="margin-top:-10px">Status : Issue</h4>
			<?php } ?>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>


    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:98%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">T.ID</th>
            <th style="border: solid 1px #999; padding:2px; %">Trns. Date</th>          
            
            
            
            
            
            <th style="border: solid 1px #999; padding:2px">FG Code</th>
            <th style="border: solid 1px #999; padding:2px">FG Description</th>
            <th style="border: solid 1px #999; padding:2px">Category</th>
            
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">Pack<br>Size</th>
            
            <th style="border: solid 1px #999; padding:2px">Source</th>
            <th style="border: solid 1px #999; padding:2px; ">Warehoues Name</th>
            <?php if($_POST['status']=='Received'){?> 
            <th style="border: solid 1px #999; padding:2px; ">PO NO</th>
            <?php } elseif ($_POST['status']=='Issue'){?> 
			<th style="border: solid 1px #999; padding:2px; ">DO NO</th>
			<?php } ?>
            <th style="border: solid 1px #999; padding:2px; ">Tr No</th>
            <th style="border: solid 1px #999; padding:2px; ">C.No</th>
            <th style="border: solid 1px #999; padding:2px; ">Entry At</th>
            <th style="border: solid 1px #999; padding:2px; ">User</th>
            <th style="border: solid 1px #999; padding:2px">IN (Pcs)</th>
            <th style="border: solid 1px #999; padding:2px">OUT (Pcs)</th>
            
            
            
            </tr></thead>


        <tbody>
        <?php
        $datecon=' and a.ji_date between  "'.$from_date.'" and "'.$to_date.'"';
		if($_POST['warehouse_id']>0) 				$warehouse_id=$_POST['warehouse_id'];
		if(isset($warehouse_id)) 				{$warehouse_con=' and a.warehouse_id='.$warehouse_id;}
		if($_POST['item_id']>0) 					$item_id=$_POST['item_id'];
		if(isset($item_id))				{$item_con=' and a.item_id='.$item_id;} 
		
			
			if($_POST['status']=='Received')
			{$status_con=' and a.item_in>0';}
			
			elseif($_POST['status']=='Issue')
			{$status_con=' and a.item_ex>0';}
			

		
		
		
        $result='select 
		
		a.id as ID,
		a.ji_date as `Trnsdate`,
		i.finish_goods_code as fg_code,
		i.item_name,
		i.unit_name as UOM,
		s.sub_group_name as Category,
		i.pack_size as packsize,
		a.item_in as `INPcs`,
		a.item_ex as `OUTPcs`,
		a.item_price as rate,
		((a.item_in+a.item_ex)*a.item_price) as amount,
		a.tr_from as Source,
		(select warehouse_name from warehouse where warehouse_id=a.warehouse_id) as warehouse,
		a.tr_no,
		a.custom_no,
		a.entry_at,
		a.do_no,
		a.po_no,
		c.fname as User 
				
				
				
				from
				journal_item a,
				item_info i,
				users c,				
				item_sub_group s
				 
				where c.user_id=a.entry_by and s.sub_group_id=i.sub_group_id and

		    a.item_id=i.item_id '.$datecon.$warehouse_con.$item_con.$status_con.' order by a.ji_date,a.id asc';
        $query2 = mysql_query($result);



        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->ID; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->Trnsdate; ?></td>
                
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->fg_code;?></td>                
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->item_name;?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->Category; ?></td>
                
                
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->packsize;?></td>
                
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->Source;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->warehouse;?></td>
                
                <?php if($_POST['status']=='Received'){?> 
            <td style="border: solid 1px #999; text-align:center;  padding:2px"><? if ($data->po_no>0) echo $data->po_no;?></td>
            <?php } elseif ($_POST['status']=='Issue'){?> 
			<td style="border: solid 1px #999; text-align:center;  padding:2px"><? if ($data->do_no>0) echo $data->do_no;?></td>
			<?php } ?>
                
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->tr_no;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->custom_no;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->entry_at;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->User;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><? if ($data->INPcs>0) echo $data->INPcs;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><? if ($data->OUTPcs>0) echo $data->OUTPcs;?></td>
                
            </tr>
            <?php 
			$intotal=$intotal+$data->INPcs;
			$outtotal=$outtotal+$data->OUTPcs;
			} ?>
            <tr style="font-size:12px"><td colspan="<?php if($_POST['status']=='Received'){ echo 14; } elseif ($_POST['status']=='Issue'){ echo '14'; } else {echo '14';}?> " style="text-align:right; "><strong>Total</strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($intotal,2)?></strong></td>
            <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($outtotal,2)?></strong></td>
            </tr>
        </tbody>
    </table>











    </div>
    </div>
    </div>






<?php elseif ($_POST['reporttypes']=='5002'):?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">Stock Verification</h4>
    <h4 align="center" style="margin-top:-10px">CMU / Warehouse Name : <?= getSVALUE('warehouse','warehouse_name','WHERE warehouse_id="'.$_POST[warehouse_id].'"');?> </h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>



    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
        
        
 

      <th style="border: solid 1px #999; padding:2px">S/L</th>
      <th style="border: solid 1px #999; padding:2px">Code</th>
      <th style="border: solid 1px #999; padding:2px">Mat. Type</th>
      <th style="border: solid 1px #999; padding:2px">Custom Code</div></th>
      <th style="border: solid 1px #999; padding:2px">Material Description</th>
      <th style="border: solid 1px #999; padding:2px">UOM</th>
      <th style="border: solid 1px #999; padding:2px">Pk. Size</th>
      <th style="border: solid 1px #999; padding:2px">Opening Stock</th>
      <th style="border: solid 1px #999; padding:2px">Received Qty</th>
      <th style="border: solid 1px #999; padding:2px">Issued Qty</th>
      <th style="border: solid 1px #999; padding:2px">Closing Stock</th>
      <th style="border: solid 1px #999; padding:2px">Rate</th>
      <th style="border: solid 1px #999; padding:2px">Cl. Stock Value</th>

      

      </tr>



</thead>





<tbody>

<?php

$fgresult=mysql_query("Select distinct j.item_id, i.item_id,i.item_name,i.finish_goods_code,i.unit_name,i.pack_size,i.serial, s.sub_group_id, s.group_id, g.group_id,s.sub_group_name,

SUM(j.item_in) as ReceivedQty,
SUM(j.item_ex) as IssuedQty

from

item_info i,
journal_item j,

item_sub_group s,

item_group g 



where 


j.item_id=i.item_id and 
j.warehouse_id='".$_POST[warehouse_id]."' and 
j.ji_date between '".$from_date."' and '".$to_date."' and 
i.sub_group_id=s.sub_group_id and 

s.group_id=g.group_id group by j.item_id order by g.group_id DESC,i.serial");



while($data=mysql_fetch_object($fgresult)){ 

if($data->sub_group_id=='200010000')
$rate=getSVALUE('item_costing','fg_cost','where status="ON" and item_id='.$data->item_id);
else 
$rate=getSVALUE('item_landad_cost','landad_cost','where status="Active" and item_id='.$data->item_id);

 ?>

<tr style="border: solid 1px #999; font-size:11px; font-weight:normal">

<td style="border: solid 1px #999; text-align:center"><?=$ismail=$ismail+1;?></td>
<td style="border: solid 1px #999; text-align:center"><?=$data->item_id;?></td>
<td style="border: solid 1px #999; text-align:center"><?=$data->sub_group_name;?></td>
<td style="border: solid 1px #999; text-align:center"><?=$data->finish_goods_code;?></td>
<td style="border: solid 1px #999; text-align:left"><?=$data->item_name;?></td>
<td style="border: solid 1px #999; text-align:center"><?=$data->unit_name;?></td>
<td style="border: solid 1px #999; text-align:center"><?=$data->pack_size;?></td>
<td style="border: solid 1px #999; text-align:center"><?php 



$openingstock=getSVALUE('journal_item','SUM(item_in-item_ex)','where item_id="'.$data->item_id.'" and warehouse_id="'.$_POST[warehouse_id].'" and ji_date <="'.$from_date.'"');

print number_format($openingstock,2);?></td>

<td style="border: solid 1px #999; text-align:center"><?php $ReceivedQty=$data->ReceivedQty;print number_format($ReceivedQty,2);?></td>
<td style="border: solid 1px #999; text-align:center"><?php $IssuedQty=$data->IssuedQty; print number_format($IssuedQty,2);?></td>
<td style="border: solid 1px #999; text-align:center"><?php $closingstock=($openingstock+$ReceivedQty)-$IssuedQty; print number_format($closingstock,2);?></td>

<td style="border: solid 1px #999; text-align:right"><?=$rate?></td>
<td style="border: solid 1px #999; text-align:right"><?php $totalclosing=($closingstock)*$rate; echo number_format($totalclosing,2) ?></td>

</tr>



<?php 
$ttotalclosing=$ttotalclosing+$totalclosing;

} ?> 

<tr style="font-size:13; font-weight:bold; border: solid 1px #999;">
<td colspan="12" style="text-align:right;border: solid 1px #999;"> Total</td>
<td style="text-align:right;border: solid 1px #999;"><?=number_format($ttotalclosing,2)?></td>
</tr>
        </tbody>
    </table></div>
    </div>
    </div>

















<?php elseif ($_POST['reporttypes']=='5004'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

   
    <h4 align="center" style="margin-top:-10px">Sales Summery</h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>



    <table align="center"  style="width:110%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; %">T.ID</th>
            <th style="border: solid 1px #999; padding:2px; ">Depot</th>
            
            <th style="border: solid 1px #999; padding:2px; ">Code</th>            
            <th style="border: solid 1px #999; padding:2px; ">Dealder Name</th>
            <th style="border: solid 1px #999; padding:2px">D.Type</th>
            
            
            <th style="border: solid 1px #999; padding:2px; ">DO</th>            
            <th style="border: solid 1px #999; padding:2px;">DO Date</th>
            <th style="border: solid 1px #999; padding:2px">DO.Type</th>
            
            <th style="border: solid 1px #999; padding:2px">Territory</th>
            <th style="border: solid 1px #999; padding:2px">Region</th>
            
            <th style="border: solid 1px #999; padding:2px">FG Code</th>
            <th style="border: solid 1px #999; padding:2px">FG Description</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">Pack Size</th>
            <th style="border: solid 1px #999; padding:2px">Unit Price</th>
            <th style="border: solid 1px #999; padding:2px">Qty</th>
            <th style="border: solid 1px #999; padding:2px">Amount</th>
            <th style="border: solid 1px #999; padding:2px">Remarks</th>
            
            
            
            </tr></thead>


        <tbody>
        <?php
		if($_POST['warehouse_id']>0) 				$warehouse_id=$_POST['warehouse_id'];
		if(isset($warehouse_id)) 				{$warehouse_con=' and sd.depot_id='.$warehouse_id;}
        $datecon=' and sd.do_date between  "'.$from_date.'" and "'.$to_date.'"';
        $result='Select 
				sd.*,
				d.dealer_custom_code,
				d.dealer_name_e,
				d.dealer_type,
				w.warehouse_name,
				a.AREA_NAME,
				b.BRANCH_NAME,
				i.item_id as itemid,
				i.finish_goods_code as FGCODE,
				i.item_name as FGdescription,
				i.pack_unit as UOM,
				i.pack_size as psize
				
				
				
				from
				sale_do_details sd,
				dealer_info d,
				area a,				
				branch b,
				warehouse w,
				item_info i
				 
				where 
				i.item_id=sd.item_id and
				sd.depot_id=w.warehouse_id and 
				sd.dealer_code=d.dealer_code and  
				sd.region=b.BRANCH_ID and 
				
				sd.area_code=a.AREA_CODE  '.$datecon.$warehouse_con.' 
				
				order by sd.id DESC';
        $query2 = mysql_query($result);



        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->id; ?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->warehouse_name; ?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->dealer_custom_code; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->dealer_name_e; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->dealer_type; ?></td>
                
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->do_no; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->do_date; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px"><?php echo $data->do_type; ?></td>
                
                
                <td style="border: solid 1px #999; padding:5px"><?=$data->AREA_NAME;?></td>
                <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->BRANCH_NAME;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->FGCODE;?></td>                
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->FGdescription;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->psize;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->unit_price,2);?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->total_unit;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->total_amt,2);?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?php if($data->total_amt=="0.00") {echo 'Free';} else echo 'Sales';;?></td>
            </tr>
            <?php
            $totaladjustment=$totaladjustment+$adjustment;
            $totalcollection=$totalcollection+$collection;
            $totalactualcollection=$totalactualcollection+$actualcollection;

        } ?>
        
        </tbody>
    </table></div>
    </div>
    </div>





<?php elseif ($_POST['reporttypes']=='5005'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>


    <h4 align="center" style="margin-top:-10px">Sales Summery</h4>
    <h4 align="center" style="margin-top:-10px">Dealer Name: <?=getSVALUE('dealer_info','dealer_name_e','where dealer_code="'.$_POST[dealer_code].'"');?></h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>



    <table align="center"  style="width:80%; border: solid 1px #999; border-collapse:collapse;">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
            <th style="border: solid 1px #999; padding:2px">SL</th>




            <th style="border: solid 1px #999; padding:2px">FG Code</th>
            <th style="border: solid 1px #999; padding:2px">FG Description</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">Pack Size</th>
            <th style="border: solid 1px #999; padding:2px">Commercial Qty</th>
            <th style="border: solid 1px #999; padding:2px">Free Qty</th>
            <th style="border: solid 1px #999; padding:2px">Total Qty</th>
            <th style="border: solid 1px #999; padding:2px">Unit Price</th>
            <th style="border: solid 1px #999; padding:2px">Value, Commercial Qty</th>



        </tr></thead>


        <tbody>
        <?php
        if($_POST['warehouse_id']>0) 				$warehouse_id=$_POST['warehouse_id'];
        if(isset($warehouse_id)) 				{$warehouse_con=' and sd.depot_id='.$warehouse_id;}
        $datecon=' and sd.do_date between  "'.$from_date.'" and "'.$to_date.'"';
        $result='Select 
				sd.*,
				d.dealer_code,
				d.dealer_custom_code,
				d.dealer_name_e,
				d.dealer_type,
				
				i.item_id as itemid,
				i.finish_goods_code as FGCODE,
				i.item_name as FGdescription,
				i.pack_unit as UOM,
				i.pack_size as psize,
				i.serial
				
				
				
				from
				sale_do_details sd,
				dealer_info d,
				
				item_info i
				 
				where 				
				i.item_id=sd.item_id and	
				i.item_id not in ("1028","1029","1034","80100","20011096000100010312") and 			
				sd.dealer_code=d.dealer_code and 				
				i.sub_group_id in ("200010000","1096000100010000") 
				 '.$datecon.$warehouse_con.' 
				GROUP by i.item_id
				order by i.serial DESC';
        $query2 = mysql_query($result);



        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>

                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->FGCODE;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->FGdescription;?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$data->psize;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$commercialqty=getSVALUE('sale_do_details','SUM(total_unit)','where item_id="'.$data->item_id.'" and total_amt!=0 and  dealer_code="'.$_POST[dealer_code].'" and do_date between  "'.$from_date.'" and "'.$to_date.'"');?> </td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$freeqty=getSVALUE('sale_do_details','SUM(total_unit)','where item_id="'.$data->item_id.'" and total_amt=0 and  dealer_code="'.$_POST[dealer_code].'" and do_date between  "'.$from_date.'" and "'.$to_date.'"');?> </td>
                </td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?$total=$commercialqty+$freeqty;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->unit_price,2);?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?php $totamcommercialvalue=$commercialqty*$data->unit_price; echo number_format($totamcommercialvalue,2);?></strong></td>
            </tr>
            <?php
            $totamcommercialvalues=$totamcommercialvalues+$totamcommercialvalue;
            $commercialqtytotal=$commercialqtytotal+$commercialqty;
            $freetotal=$freetotal+$freeqty;

        } ?>
        <tr style="border: solid 1px #999; font-size:12px; font-weight:normal">
            <td colspan="5" align="right" style="border: solid 1px #999; text-align:right;  padding:2px"><strong>Total</strong></td>
            <td align="right" style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($commercialqtytotal,2)?></strong></td>
            <td align="right" style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($freetotal,2)?></strong></td>
            <td align="right" style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($commercialqtytotal+$freetotal,2)?></strong></td>
            <td></td>
            <td align="right" style="border: solid 1px #999; text-align:right;  padding:2px"><strong><?=number_format($totamcommercialvalues,2)?></strong></td>
            </tr>

        </tbody>
    </table></div>
    </div>
    </div>








<?php elseif ($_POST['reporttypes']=='3004'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">GRN Pending Report (Rice)</h4>    
    <?php if($_POST[po_no]){ ?><h4 align="center" style="margin-top:-10px">PO NO: <?=$_POST[po_no] ?></h4>   <?php } ?>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>



    <table align="center"  style="width:95%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px; height: 30px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px">Item Description</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">PO</th>
            <th style="border: solid 1px #999; padding:2px">DO</th>

            <th style="border: solid 1px #999; padding:2px">Order Qty</th>
            <th style="border: solid 1px #999; padding:2px">Record Qty</th>
            <th style="border: solid 1px #999; padding:2px">Pending Qty</th>
            <th style="border: solid 1px #999; padding:2px; width: 25%">Remarks</th></tr></thead>


        <tbody>
        <?php
		if($_POST[po_no]) { $pocon=' and pm.po_no="'.$_POST[po_no].'"'; }  
        $dateconGRN=' and pm.po_date between "'.$from_date.'" and "'.$to_date.'"';
        $result='Select 
				pm.*,
				pi.*,
				i.finish_goods_code as code,
				i.item_name as itemname,
				i.unit_name as UOM,
				pr.qty as receivedQTY,
				pi.edit_resone
				
				from
				
				purchase_master pm,
				purchase_invoice pi,
				item_info i,
				purchase_receive pr
				 
				where 
				
				pm.po_no=pi.po_no and 
				pi.item_id=i.item_id AND 
				pm.section_id in ("400002") and 
				i.item_id not in ("1096000100010313") and 
				pi.item_id=pr.item_id and 
				pi.po_no=pr.po_no
				'.$pocon.$dateconGRN.' 
				
				order by pi.item_id,pr.do_no ';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->code; ?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->itemname; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; padding:5px"><?=$data->po_no;?></td>
                <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->do_no;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->qty,2);?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=number_format($data->receivedQTY,2);?></td>
                <td style="border: solid 1px #999; text-align:right; padding:2px"><?php $pendingQTY=$data->qty-$data->receivedQTY; echo number_format($pendingQTY,2);?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->edit_resone;?></td>
            </tr>
            <?php
            $totaladjustment=$totaladjustment+$adjustment;
            $totalcollection=$totalcollection+$collection;
            $totalactualcollection=$totalactualcollection+$actualcollection;

        } ?>

        </tbody>
    </table></div>
    </div>
    </div>



<?php elseif ($_POST['reporttypes']=='3007'):
/////////////////////////////////////Received and Payments----------------------------------------------------------?>

    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">GRN Pending Report (Rice)</h4>    
    <?php if($_POST[po_no]){ ?><h4 align="center" style="margin-top:-10px">PO NO: <?=$_POST[po_no] ?></h4>   <?php } ?>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h5>



    <table align="center"  style="width:95%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px; height: 30px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px">Item Description</th>
            <th style="border: solid 1px #999; padding:2px">UOM</th>
            <th style="border: solid 1px #999; padding:2px">PO</th>


            <th style="border: solid 1px #999; padding:2px">Order Qty</th>
            <th style="border: solid 1px #999; padding:2px">Record Qty</th>
            <th style="border: solid 1px #999; padding:2px">Pending Qty</th>
            <th style="border: solid 1px #999; padding:2px; width: 25%">Remarks</th></tr></thead>


        <tbody>
        <?php
		if($_POST[po_no]) { $pocon=' and pm.po_no="'.$_POST[po_no].'"'; }  
        $dateconGRN=' and pm.po_date between "'.$from_date.'" and "'.$to_date.'"';
        $result='Select 
				pm.*,
				pi.*,
				i.finish_goods_code as code,
				i.item_name as itemname,
				i.unit_name as UOM,
				
				
				pi.edit_resone
				
				from
				
				purchase_master pm,
				purchase_invoice pi,
				item_info i,
				purchase_receive pr
				 
				where 
				
				pm.po_no=pi.po_no and 
				pi.item_id=i.item_id AND 
				pm.section_id in ("400002") and 
				i.item_id not in ("1096000100010313") and 
				pi.item_id=pr.item_id and 
				pi.po_no=pr.po_no
				'.$pocon.$dateconGRN.' 
				GROUP BY pi.po_no,pi.item_id
				order by pi.po_no,pi.item_id ';
        $query2 = mysql_query($result);
        while($data=mysql_fetch_object($query2)){





            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center"><?php echo $i; ?></td>
                <td style="border: solid 1px #999; text-align:center"><?php echo $data->code; ?></td>
                <td style="border: solid 1px #999; text-align:left"><?php echo $data->itemname; ?></td>
                <td style="border: solid 1px #999; text-align:left; padding:5px; width:10%"><?=$data->UOM;?></td>
                <td style="border: solid 1px #999; padding:5px"><?=$data->po_no;?></td>

                <td style="border: solid 1px #999; text-align:right;  padding:2px">
                    <?=$oderqty=getSVALUE('purchase_invoice','SUM(qty)','where item_id="'.$data->item_id.'" and  po_no="'.$data->po_no.'"');?> </h4>
                  </td>

                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$rcvqty=getSVALUE('purchase_receive','SUM(qty)','where item_id="'.$data->item_id.'" and  po_no="'.$data->po_no.'"');?> </h4>
                </td>
                <td style="border: solid 1px #999; text-align:right; padding:2px"><?php $pendingQTY=$oderqty-$rcvqty; echo number_format($pendingQTY,2);?></td>
                <td style="border: solid 1px #999; text-align:left;  padding:2px"><?=$data->edit_resone;?></td>
            </tr>
            <?php
            $totalorder=$totalorder+$oderqty;
            $totalreceived=$totalreceived+$rcvqty;



        } ?>
        <tr style="font-weight:bold"><td colspan="5" style="border: solid 1px #999; text-align:right">Total</td>

            <td style="border: solid 1px #999; text-align:right"><?=$totalorder;?></td>
            <td style="border: solid 1px #999; text-align:right"><?=$totalreceived;?></td>
            <td style="border: solid 1px #999; text-align:right"><?=$totalorder-$totalreceived;?></td>
            <td></td>
        </tr>

        </tbody>
    </table></div>
    </div>
    </div>





<?php elseif ($_POST['reporttypes']=='dealer'):
/////////////////////////////////////Received and Payments----------------------------------------------------------
    ?>




    <h2 align="center">International Consumer Products Bangladesh Ltd.</h2>

    <h4 align="center" style="margin-top:-10px">Cash Collection (Territory Wise)</h4>
    <h4 align="center" style="margin-top:-10px">Dealer Name : <?= find_a_field('dealer_info','dealer_name_e','dealer_code="'.$_POST[dealer].'"');?> </h4>
    <h5 align="center" style="margin-top:-10px">Report From <?=$_POST[fdate]?> to <?=$_POST[tdate]?> </h5>



    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse; ">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>

        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px">SL</th>
            <th style="border: solid 1px #999; padding:2px; width:5%">Code</th>
            <th style="border: solid 1px #999; padding:2px; width:10%">Accounts Code</th>
            <th style="border: solid 1px #999; padding:2px">Dealder Name</th>
            <th style="border: solid 1px #999; padding:2px">Town</th>
            <th style="border: solid 1px #999; padding:2px">Territory</th>
            <!--th style="border: solid 1px #999; padding:2px">Aria</th--->
            <th style="border: solid 1px #999; padding:2px">Region</th>
            <th style="border: solid 1px #999; padding:2px">Adjustment</th>
            <th style="border: solid 1px #999; padding:2px">Collection</th>
            <th style="border: solid 1px #999; padding:2px">Actual Collection</th></tr></thead>


        <tbody>
        <?php
        $datecon=' and d.dealer_code="'.$_POST[dealer].'" and j.jv_date between  "'.$fdate.'" and "'.$tdate.'"';
        $result='Select 
				d.dealer_code,
				d.account_code,
				d.dealer_name_e as dealername,
				t.town_name as town,
				a.AREA_NAME as territory,
				
				b.BRANCH_NAME as region, 
				SUM(j.dr_amt) adjustment,
				SUM(j.cr_amt) collection,
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
				j.ledger_id=d.account_code and j.tr_from not in ("Sales","SalesReturn","Journal_info") '.$datecon.' 
				
				group by d.dealer_code';
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
                <!--td style="border: solid 1px #999; text-align:left; padding:5px"><?=$data->area;?></td--->
                <td style="border: solid 1px #999; text-align:left; padding:2px"><?=$data->region;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$adjustment=$data->adjustment;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$collection=$data->collection;?></td>
                <td style="border: solid 1px #999; text-align:right; padding:2px"><?=$actualcollection=$data->actualcollection;?></td>
            </tr>
            <?php
            $totaladjustment=$totaladjustment+$adjustment;
            $totalcollection=$totalcollection+$collection;
            $totalactualcollection=$totalactualcollection+$actualcollection;

        } ?>
        <tr><td colspan="7" style="text-align:right;border: solid 1px #999;">Total</td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totaladjustment,2)?></strong></td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalcollection,2)?></strong></td>
            <td style="text-align:right;border: solid 1px #999;"><strong><?=number_format($totalactualcollection,2)?></strong></td>
        </tr>
        </tbody>
    </table></div>
    </div>
    </div>


<?php elseif ($_POST['reporttypes']=='dealerledger'):
/////////////////////////////////////Received and Payments----------------------------------------------------------

    $ledger_id=$_POST[account_code];	 ?>




    <div class="book">
    <div class="page" style="background-image:url(letter.jpg);background-repeat:no-repeat">
    <table align="center" style="width:80%; font-size:11px">
        <tr><td style="text-align:center"><h1 style="margin-top:-5px;">International Consumer Products Bangladesh Ltd.</h1></td></tr>

        <tr><td style="text-align:center"><h2 style="margin-top:-15px;">Customer Name: <?php echo $customer = find_a_field('dealer_info','dealer_name_e','account_code='.$_POST[account_code]);  ?></h2></td></tr>


        <tr><td style="text-align:center">Address: <?php echo $address = find_a_field('dealer_info','address_e','account_code='.$_POST[account_code]);  ?></td></tr>
        <tr><td style="text-align:center">Date Interval: <?=$_REQUEST['fdate'];?> to <?=$_REQUEST['tdate'];?></td></tr>




    </table>




    <table align="center" border="1" style="width:80%; border-collapse:collapse; margin-top:30px;   font-size:11px">
        <tr>
            <th>S/N</th>
            <th>Date</th>
            <th>Particulars</th>
            <th>Source</th>
            <th>Dr Amt</th>
            <th>Cr Amt</th>
            <th>Balance</th>
        </tr>




        <?php




        $total_sql = "select sum(a.dr_amt),sum(a.cr_amt) from journal a,accounts_ledger b where a.ledger_id=b.ledger_id and a.jv_date between '$fdate' AND '$tdate' and a.ledger_id like '$ledger_id'";

        $total=mysql_fetch_row(mysql_query($total_sql));


        $c="select sum(a.dr_amt)-sum(a.cr_amt) from journal a,accounts_ledger b where a.ledger_id=b.ledger_id and a.jv_date<'$fdate' and a.ledger_id like '$ledger_id'";


        $p="select a.jv_date,b.ledger_name,a.dr_amt,a.cr_amt,a.tr_from,a.narration,a.jv_no,a.tr_no,a.jv_no,a.cheq_no,a.cheq_date, a.user_id, a.PBI_ID , a.cc_code from journal a,accounts_ledger b where a.ledger_id=b.ledger_id and a.jv_date between '$fdate' AND '$tdate' and a.ledger_id like '$ledger_id' order by a.jv_date,a.id";

        $sql=mysql_query($p);



        if($total[0]>$total[1])
        {
            $t_type="(Dr)";
            $t_total=$total[0]-$total[1];
        }else{
            $t_type="(Cr)";
            $t_total=$total[1]-$total[0];
        }


        /* ===== Opening Balance =======*/
        $psql=mysql_query($c);
        $pl = mysql_fetch_row($psql);
        $blance=$pl[0];




        ?>
        <tr style="background-color:#FFCCFF">


            <td colspan="4" style="text-align:right"><b>Opening Balance</b></td>

            <td></td>
            <td></td>
            <td align="right" bgcolor="#FFCCFF"><?php if($blance>0) echo '(Dr)'.number_format($blance,0,'.',''); elseif($blance<0) echo '(Cr) '.number_format(((-1)*$blance),0,'.','');else echo "0.00"; ?></td>
        </tr>

        <?php


        while($data=mysql_fetch_array($sql)){
            $pi++;
            ?>

            <tr>



                <td align="center"><?php echo $pi;?></td>








                <td align="center" style="width:70px"><?php
                    $trdate = find_a_field('sale_do_chalan','do_no','chalan_no='.$data[7]);
                    $dodate = find_a_field('sale_do_master','do_date','do_no='.$trdate);
                    if ($dodate>0) echo $dodate; else echo date("Y-m-d",$data[0]);?></td>



                <td align="left"><?=$data[5];?></td>

                <!--td align="left"><?=$data[5];?><?=(($data[9]!='')?'-Cq#'.$data[9]:'');?><?=(($data[10]>943898400)?'-Cq-Date#'.date('d-m-Y',$data[10]):'');?></td-->



                <td align="center"><?php echo $data[4];?></td>
                <td align="right"><?php if($data[2]=='0') echo ''; else echo number_format($data[2],0,'.',',');?></td>
                <td align="right"><?php if($data[3]=='0') echo ''; else echo number_format($data[3],0,'.',',');?></td>
                <td align="right" bgcolor="#FFCCFF"><?php $blance = $blance+($data[2]-$data[3]);
                    if($blance>0) echo '(Dr)'.number_format($blance,2,'.',',');
                    elseif($blance<0) echo '(Cr) '.number_format(((-1)*$blance),2,'.',',');else echo "0.00"; ?></td>
            </tr>
        <?php } ?>


        <tr>







            <th colspan="4"  style="text-align:right"><strong>Total : </strong></th>
            <th align="right" style="text-align:right"><strong><?php echo number_format($total[0],2);?></strong></th>
            <th align="right" style="text-align:right"><strong><?php echo number_format($total[1],2);?></strong></th>
            <th align="right" style="text-align:right;">
                <div style="width:100px; text-align:right"><?php $blance = $blance+($data[2]-$data[3]);
                    if($blance>0) echo '(Dr)'.number_format($blance,2,'.',',');
                    elseif($blance<0) echo '(Cr) '.number_format(((-1)*$blance),2,'.',',');else echo "0.00"; ?></div>
            </th>
        </tr>

    </table>





<?php elseif ($_POST['reporttypes']=='allcurrent'):
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
<?php endif; ?>




</body>
</html>

</html>