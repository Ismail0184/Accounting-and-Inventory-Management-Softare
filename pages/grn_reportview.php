<?php
require_once 'support_file.php';
$title='Goods Received Status';
if($_POST['warehouse_id']>0) $warehouse_id=$_POST['warehouse_id'];
if($_POST['vendor_id']>0) $vendor_id=$_POST['vendor_id'];
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
        {document.getElementById("pr").style.display = "none";}
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




<?php if ($_POST['report_id']=='4003003'):
    $vendor_name=getSVALUE('vendor','vendor_name','where vendor_id='.$_REQUEST['vendor_id']);
    ?>
    <style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #FFCCFF;}
        td{}
    </style>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("MAN_popup.php?id="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<title><?=$vendor_name;?> | MAN vs GRN</title>
        <p align="center" style="margin-top:-5px; font-weight: bold; font-size: 22px"><?=$_SESSION['company_name'];?></p>
        <p align="center" style="margin-top:-18px; font-size: 15px">MAN vs GRN</p>
        <?php if($_POST[vendor_id]){ ?>
        <p align="center" style="margin-top:-10px; font-size: 12px"><strong>Vendor Name:</strong> <?=$vendor_name;?>)</p>
        <?php } ?>
        <p align="center" style="margin-top:-10px; font-size: 11px"><strong>Period From :</strong> <?=$_POST[f_date]?> <strong>to</strong> <?=$_POST[t_date]?></p>
        <table align="center" id="customers"  style="width:95%; border: solid 1px #999; border-collapse:collapse; ">
            <thead>
            <p style="width:95%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
                echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
            <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
                <th style="border: solid 1px #999; padding:2px">SL</th>
                <th style="border: solid 1px #999; padding:2px; width:10%">MAN</th>
                <th style="border: solid 1px #999; padding:2px; width:10%">Date of Received</th>
                <th style="border: solid 1px #999; padding:2px;">Vendor</th>
                <th style="border: solid 1px #999; padding:2px; width:8%">Mat. Code</th>
                <th style="border: solid 1px #999; padding:2px;">Mat. Description</th>
                <th style="border: solid 1px #999; padding:2px">UOM</th>
                <th style="border: solid 1px #999; padding:2px">MAN Qty</th>
                <th style="border: solid 1px #999; padding:2px; width: 10%">Ref. PO</th>
                <th style="border: solid 1px #999; padding:2px">MAN By</th>
                <th style="border: solid 1px #999; padding:2px">GRN Status</th>
            </tr></thead>
            <tbody>
        <?php
		$datecon=' and m.man_date between  "'.$_POST[f_date].'" and "'.$_POST[t_date].'"';
        if($_POST['vendor_id']>0) 			 $vendor_id=$_POST['vendor_id'];
        if(isset($vendor_id))				{$vendor_id_CON=' and m.vendor_code='.$vendor_id;}

		if($_POST['vendor_id']>0) 			 $vendor_id=$_POST['vendor_id'];
        if(isset($vendor_id))				{$vendor_id_CON=' and m.vendor_code='.$vendor_id;}

		if($_POST['MAN_RCV_STATUS']!=='All') 		 $MAN_RCV_STATUS=$_POST['MAN_RCV_STATUS'];
        if(isset($MAN_RCV_STATUS))				{$MAN_RCV_STATUS_CON=' and m.MAN_RCV_STATUS in ("'.$MAN_RCV_STATUS.'")';}

        $query=mysqli_query($conn, "SELECT m.*, i.*,u.*,v.vendor_name as vendor from MAN_details m,item_info i, users u, vendor v where
		m.item_id=i.item_id and
		m.entry_by=u.user_id and
		v.vendor_id=m.vendor_code
		".$datecon.$vendor_id_CON.$MAN_RCV_STATUS_CON."");
		while($data=mysqli_fetch_object($query)){?>
            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$i=$i+1;?></td>
                <td align="center" style="border: solid 1px #999; padding:2px; cursor:pointer; text-decoration:underline; color:blue" onclick="DoNavPOPUP('<?=$data->m_id;?>', 'TEST!?', 600, 700)"><?=$data->m_id;?>, <?=$data->MAN_ID;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->man_date;?></td>
            <td align="left" style="border: solid 1px #999; padding:2px"><?=$data->vendor;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->item_id;?></td>
            <td align="left" style="border: solid 1px #999; padding:2px"><?=$data->item_name;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->unit_name;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->qty;?></td>
            <td align="center"  style="border: solid 1px #999; padding:2px"><a href="po_print_view.php?po_no=<?=$data->po_no;?>" target="_new"><?=$data->po_no;?></a></td>
            <td align="left"  style="border: solid 1px #999; padding:2px"><?=$data->fname;?></td>
            <td align="center"  style="border: solid 1px #999; padding:2px"><? if($data->MAN_RCV_STATUS=='Done') echo $data->MAN_RCV_STATUS; else echo 'Pending';?></td>
        </tr>
        <?php } ?>

    </tbody>
    </table>


  <?php elseif ($_POST['report_id']=='4003004'):
  if(isset($vendor_id)){$vendor_id_CON=' AND pm.vendor_id='.$vendor_id;}
  $query="SELECT po.po_no,pm.po_date,i.finish_goods_code,i.item_name,i.unit_name as uom,po.qty,po.rate,po.amount,w.warehouse_name,u.fname as prepared_by,
  pm.entry_at,pbi.PBI_NAME as checked_by,pbi2.PBI_NAME as recommended_by,pbi3.PBI_NAME as authorised_by,pm.checkby_date,pm.recommended_date,pm.authorized_date,
  (SELECT SUM(qty) from purchase_receive where po_no=pm.po_no and item_id=po.item_id) as grn_qty
  from
  purchase_master pm,
  purchase_invoice po,
  item_info i,
  warehouse w,
  users u,
  personnel_basic_info pbi,
  personnel_basic_info pbi2,
  personnel_basic_info pbi3
  where pm.po_no=po.po_no and pm.checkby=pbi.PBI_ID and pm.recommended=pbi2.PBI_ID and pm.authorise=pbi3.PBI_ID and pm.entry_by=u.user_id and w.warehouse_id=po.warehouse_id and po.item_id=i.item_id and  pm.po_date BETWEEN '".$_POST[f_date]."' and '".$_POST[t_date]."'".$vendor_id_CON." order by pm.po_no,i.item_name";
  $sql=mysqli_query($conn, $query);?>

    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h5 align="center" style="margin-top:-15px">PO vs GRN</h5>
    <?php if($_POST[vendor_id]>0){ ?>
    <h6 align="center" style="margin-top:-15px">Vendor: <?=find_a_field('vendor','vendor_name','vendor_id='.$_POST[vendor_id]);?> </h6><?php } ?>
    <h6 align="center" style="margin-top:-15px">Date Interval from <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h6>
    <table align="center" id="customers" style="width:99%; border: solid 1px #999; border-collapse:collapse; font-size:11px">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; background-color:#f5f5f5">
            <th rowspan="2" style="border: solid 1px #999; padding:2px">#</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">PO NO</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">PO Date</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Mat Code</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Descriptions</div></th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">UOM</th>
            <th colspan="3" style="border: solid 1px #999; padding:2px">PO</th>
            <th colspan="2" style="border: solid 1px #999; padding:2px">GRN</th>
            <th colspan="2" style="border: solid 1px #999; padding:2px">Difference</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Warehouse / CMU Name</th>
            <th colspan="4" style="border: solid 1px #999; padding:2px">Authorization Status</th>
        </tr>
        <tr style="border: solid 1px #999;font-weight:bold; background-color:#f5f5f5">
          <th style="border: solid 1px #999; padding:2px">QTY</th>
          <th style="border: solid 1px #999; padding:2px">Rate</th>
          <th style="border: solid 1px #999; padding:2px">Value</th>
          <th style="border: solid 1px #999; padding:2px">QTY</th>
          <th style="border: solid 1px #999; padding:2px">Value</th>
          <th style="border: solid 1px #999; padding:2px">QTY</th>
          <th style="border: solid 1px #999; padding:2px">Value</th>
          <th style="border: solid 1px #999; padding:2px">Prepared By</th>
          <th style="border: solid 1px #999; padding:2px">Checked By</th>
          <th style="border: solid 1px #999; padding:2px">Recommended By</th>
          <th style="border: solid 1px #999; padding:2px">Approved By</th>
        </tr>
        </thead>
        <tbody>
        <?php while($data=mysqli_fetch_object($sql)){ ?>
        <tr><td style="border: solid 1px #999; text-align:center"><?=$i=$i+1;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=$data->po_no;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->po_date;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->finish_goods_code;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->item_name;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=$data->uom;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=number_format($data->qty);?></td>
        <td style="border: solid 1px #999; text-align:right"><?=$data->rate;?></td>
        <td style="border: solid 1px #999; text-align:right"><?=number_format($data->amount,2);?></td>

        <td style="border: solid 1px #999; text-align:center"><?=($data->grn_qty>0)? number_format($data->grn_qty) : '-'; ?></td>
        <td style="border: solid 1px #999; text-align:right"><?=($data->rate*$data->grn_qty>0)? $data->rate*$data->grn_qty : '-'; ?></td>

        <td style="border: solid 1px #999; text-align:center"><?=($data->qty-$data->grn_qty>0)? number_format($data->qty-$data->grn_qty) : '-'; ?></td>
        <td style="border: solid 1px #999; text-align:right"><?=($data->qty-$data->grn_qty>0)? number_format(($data->qty-$data->grn_qty)*$data->rate,2) : '-'; ?></td>

        <td style="border: solid 1px #999; text-align:left"><?=$data->warehouse_name;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->prepared_by;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->checkby_date>0)? $data->checked_by : 'PENDING'; ?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->recommended_by>0)? $data->recommended_by : 'PENDING'; ?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->authorised_by>0)? $data->authorised_by : 'PENDING'; ?></td>


      </tr>
      <?php } ?>
        </tbody>
      </table>

      <?php elseif ($_POST['report_id']=='4003005'):
  if(isset($vendor_id)){$vendor_id_CON=' AND pm.vendor_id='.$vendor_id;}
  $query="SELECT po.po_no,pm.po_date,i.finish_goods_code,i.item_name,i.unit_name as uom,po.qty,po.rate,po.amount,w.warehouse_name,u.fname as prepared_by,
  pm.entry_at,pbi.PBI_NAME as checked_by,pbi2.PBI_NAME as recommended_by,pbi3.PBI_NAME as authorised_by,pm.checkby_date,pm.recommended_date,pm.authorized_date,
  (SELECT SUM(qty) from purchase_receive where po_no=pm.po_no and item_id=po.item_id) as grn_qty,
  (SELECT SUM(qty) from MAN_details where po_no=pm.po_no and item_id=po.item_id) as man_qty
  from
  purchase_master pm,
  purchase_invoice po,
  item_info i,
  warehouse w,
  users u,
  personnel_basic_info pbi,
  personnel_basic_info pbi2,
  personnel_basic_info pbi3
  where pm.po_no=po.po_no and pm.checkby=pbi.PBI_ID and pm.recommended=pbi2.PBI_ID and pm.authorise=pbi3.PBI_ID and pm.entry_by=u.user_id and w.warehouse_id=po.warehouse_id and po.item_id=i.item_id and  pm.po_date BETWEEN '".$_POST[f_date]."' and '".$_POST[t_date]."'".$vendor_id_CON." order by pm.po_no,i.item_name";
  $sql=mysqli_query($conn, $query);?>

    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h5 align="center" style="margin-top:-15px">PO vs MAN vs GRN</h5>
    <?php if($_POST[vendor_id]>0){ ?>
    <h6 align="center" style="margin-top:-15px">Vendor: <?=find_a_field('vendor','vendor_name','vendor_id='.$_POST[vendor_id]);?> </h6><?php } ?>
    <h6 align="center" style="margin-top:-15px">Date Interval from <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h6>
    <table align="center" id="customers" style="width:99%; border: solid 1px #999; border-collapse:collapse; font-size:11px">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; background-color:#f5f5f5">
            <th rowspan="2" style="border: solid 1px #999; padding:2px">#</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">PO NO</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">PO Date</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Mat Code</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Descriptions</div></th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">UOM</th>
            <th colspan="3" style="border: solid 1px #999; padding:2px">PO</th>
            <th colspan="1" style="border: solid 1px #999; padding:2px">MAN</th>
            <th colspan="1" style="border: solid 1px #999; padding:2px">GRN</th>
            <th colspan="3" style="border: solid 1px #999; padding:2px">Difference</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Warehouse / CMU Name</th>
            <th colspan="4" style="border: solid 1px #999; padding:2px">Authorization Status</th>
        </tr>
        <tr style="border: solid 1px #999;font-weight:bold; background-color:#f5f5f5">
          <th style="border: solid 1px #999; padding:2px">Qty</th>
          <th style="border: solid 1px #999; padding:2px">Rate</th>
          <th style="border: solid 1px #999; padding:2px">Value</th>
          <th style="border: solid 1px #999; padding:2px">Qty</th>
          <th style="border: solid 1px #999; padding:2px">Qty</th>

          <th style="border: solid 1px #999; padding:2px">PO vs MAN</th>
          <th style="border: solid 1px #999; padding:2px">MAN vs GRN</th>
          <th style="border: solid 1px #999; padding:2px">PO vs GRN</th>

          <th style="border: solid 1px #999; padding:2px">Prepared By</th>
          <th style="border: solid 1px #999; padding:2px">Checked By</th>
          <th style="border: solid 1px #999; padding:2px">Recommended By</th>
          <th style="border: solid 1px #999; padding:2px">Approved By</th>
        </tr>
        </thead>
        <tbody>
        <?php while($data=mysqli_fetch_object($sql)){ ?>
        <tr><td style="border: solid 1px #999; text-align:center"><?=$i=$i+1;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=$data->po_no;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->po_date;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->finish_goods_code;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->item_name;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=$data->uom;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=number_format($data->qty);?></td>
        <td style="border: solid 1px #999; text-align:right"><?=$data->rate;?></td>
        <td style="border: solid 1px #999; text-align:right"><?=number_format($data->amount,2);?></td>
        <td style="border: solid 1px #999; text-align:center"><?=($data->man_qty>0)? number_format($data->man_qty) : '-'; ?></td>
        <td style="border: solid 1px #999; text-align:center"><?=($data->grn_qty>0)? number_format($data->grn_qty) : '-'; ?></td>

        <td style="border: solid 1px #999; text-align:center"><?=($data->qty-$data->man_qty>0)? number_format($data->qty-$data->man_qty) : '-'; ?></td>
        <td style="border: solid 1px #999; text-align:right"><?=($data->man_qty-$data->grn_qty>0)? number_format(($data->man_qty-$data->grn_qty)) : '-'; ?></td>
        <td style="border: solid 1px #999; text-align:right"><?=($data->qty-$data->grn_qty>0)? number_format(($data->qty-$data->grn_qty)) : '-'; ?></td>


        <td style="border: solid 1px #999; text-align:left"><?=$data->warehouse_name;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->prepared_by;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->checkby_date>0)? $data->checked_by : 'PENDING'; ?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->recommended_by>0)? $data->recommended_by : 'PENDING'; ?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->authorised_by>0)? $data->authorised_by : 'PENDING'; ?></td>
      </tr>
      <?php } ?>
        </tbody>
      </table>      

      <?php elseif ($_POST['report_id']=='4003002'):
  if(isset($vendor_id)){$vendor_id_CON=' AND pm.vendor_id='.$vendor_id;}
  $query="SELECT po.po_no,pm.po_date,i.finish_goods_code,i.item_name,i.unit_name as uom,po.qty,po.rate,po.amount,w.warehouse_name,u.fname as prepared_by,
  pm.entry_at,pbi.PBI_NAME as checked_by,pbi2.PBI_NAME as recommended_by,pbi3.PBI_NAME as authorised_by,pm.checkby_date,pm.recommended_date,pm.authorized_date,
  (SELECT SUM(qty) from MAN_details where po_no=pm.po_no and item_id=po.item_id) as grn_qty
  from
  purchase_master pm,
  purchase_invoice po,
  item_info i,
  warehouse w,
  users u,
  personnel_basic_info pbi,
  personnel_basic_info pbi2,
  personnel_basic_info pbi3
  where pm.po_no=po.po_no and pm.checkby=pbi.PBI_ID and pm.recommended=pbi2.PBI_ID and pm.authorise=pbi3.PBI_ID and pm.entry_by=u.user_id and w.warehouse_id=po.warehouse_id and po.item_id=i.item_id and  pm.po_date BETWEEN '".$_POST[f_date]."' and '".$_POST[t_date]."'".$vendor_id_CON." order by pm.po_no,i.item_name";
  $sql=mysqli_query($conn, $query);?>

    <h2 align="center"><?=$_SESSION['company_name'];?></h2>
    <h5 align="center" style="margin-top:-15px">PO vs MAN</h5>
    <?php if($_POST[vendor_id]>0){ ?>
    <h6 align="center" style="margin-top:-15px">Vendor: <?=find_a_field('vendor','vendor_name','vendor_id='.$_POST[vendor_id]);?> </h6><?php } ?>
    <h6 align="center" style="margin-top:-15px">Date Interval from <?=$_POST[f_date]?> to <?=$_POST[t_date]?></h6>
    <table align="center" id="customers" style="width:99%; border: solid 1px #999; border-collapse:collapse; font-size:11px">
        <thead>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka')); echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; background-color:#f5f5f5">
            <th rowspan="2" style="border: solid 1px #999; padding:2px">#</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">PO NO</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">PO Date</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Mat Code</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Descriptions</div></th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">UOM</th>
            <th colspan="3" style="border: solid 1px #999; padding:2px">PO</th>
            <th colspan="1" style="border: solid 1px #999; padding:2px">MAN</th>
            <th colspan="1" style="border: solid 1px #999; padding:2px">Difference</th>
            <th rowspan="2" style="border: solid 1px #999; padding:2px">Warehouse / CMU Name</th>
            <th colspan="4" style="border: solid 1px #999; padding:2px">Authorization Status</th>
        </tr>
        <tr style="border: solid 1px #999;font-weight:bold; background-color:#f5f5f5">
          <th style="border: solid 1px #999; padding:2px">QTY</th>
          <th style="border: solid 1px #999; padding:2px">Rate</th>
          <th style="border: solid 1px #999; padding:2px">Value</th>
          <th style="border: solid 1px #999; padding:2px">QTY</th>
          <th style="border: solid 1px #999; padding:2px">QTY</th>
          <th style="border: solid 1px #999; padding:2px">Prepared By</th>
          <th style="border: solid 1px #999; padding:2px">Checked By</th>
          <th style="border: solid 1px #999; padding:2px">Recommended By</th>
          <th style="border: solid 1px #999; padding:2px">Approved By</th>
        </tr>
        </thead>
        <tbody>
        <?php while($data=mysqli_fetch_object($sql)){ ?>
        <tr><td style="border: solid 1px #999; text-align:center"><?=$i=$i+1;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=$data->po_no;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->po_date;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->finish_goods_code;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->item_name;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=$data->uom;?></td>
        <td style="border: solid 1px #999; text-align:center"><?=number_format($data->qty);?></td>
        <td style="border: solid 1px #999; text-align:right"><?=$data->rate;?></td>
        <td style="border: solid 1px #999; text-align:right"><?=number_format($data->amount,2);?></td>

        <td style="border: solid 1px #999; text-align:center"><?=($data->grn_qty>0)? number_format($data->grn_qty) : '-'; ?></td>

        <td style="border: solid 1px #999; text-align:center"><?=($data->qty-$data->grn_qty>0)? number_format($data->qty-$data->grn_qty) : '-'; ?></td>

        <td style="border: solid 1px #999; text-align:left"><?=$data->warehouse_name;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=$data->prepared_by;?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->checkby_date>0)? $data->checked_by : 'PENDING'; ?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->recommended_by>0)? $data->recommended_by : 'PENDING'; ?></td>
        <td style="border: solid 1px #999; text-align:left"><?=($data->authorised_by>0)? $data->authorised_by : 'PENDING'; ?></td>


      </tr>
      <?php } ?>
        </tbody>
      </table>

<?php elseif ($_POST['report_id']=='4003001'):$vendor_name=getSVALUE('vendor','vendor_name','where vendor_id='.$_REQUEST['vendor_id']);
$title='Material Recived Status';?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("grn_srn_view.php?pr_no="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
	<script type="text/javascript">
        function DoNavPOPUP2(lk)
        {myWindow = window.open("MAN_print_view.php?id="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
    <script type="text/javascript">
        function DoNavPOPUP1(lk)
        {myWindow = window.open("GRN_view.php?id="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<title><?=$vendor_name;?> | <?=$title;?></title>
        <p align="center" style="margin-top:-5px; font-weight: bold; font-size: 22px"><?=$_SESSION['company_name'];?></p>
        <p align="center" style="margin-top:-18px; font-size: 15px"><?=$title;?></p>
        <?php if($_POST[vendor_id]){ ?>
        <p align="center" style="margin-top:-10px; font-size: 12px"><strong>Vendor Name:</strong> <?=$vendor_name;?>)</p>
        <?php } ?>
        <p align="center" style="margin-top:-10px; font-size: 11px"><strong>Period From :</strong> <?=$_POST[f_date]?> <strong>to</strong> <?=$_POST[t_date]?></p>
        <table align="center" id="customers"  style="width:100%; border: solid 1px #999; font-size:11px; border-collapse:collapse; ">
            <thead>
            <p style="width:95%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
                echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
            <tr style="border: solid 1px #999;font-weight:bold; font-size:11px; background-color:#f5f5f5">
                <th style="border: solid 1px #999; padding:2px">SL</th>
                <th style="border: solid 1px #999; padding:2px; width:10%">MAN</th>
                <th style="border: solid 1px #999; padding:2px; width:8%">D.C</th>
                <th style="border: solid 1px #999; padding:2px; width:8%">V.C</th>
                <th style="border: solid 1px #999; padding:2px; width:8%">Mat. Code</th>
                <th style="border: solid 1px #999; padding:2px;">Mat. Description</th>
                <th style="border: solid 1px #999; padding:2px">UOM</th>
                <th style="border: solid 1px #999; padding:2px">MAN Qty</th>
                <th style="border: solid 1px #999; padding:2px">MAN Varification</th>
                <th style="border: solid 1px #999; padding:2px">Create GRN</th>
                <th style="border: solid 1px #999; padding:2px">GRN No</th>
                <th style="border: solid 1px #999; padding:2px">GRN Verification</th>
                <th style="border: solid 1px #999; padding:2px">Bill Check from A/c</th>
                <th style="border: solid 1px #999; padding:2px">Vendor</th>
                <th style="border: solid 1px #999; padding:2px">PO NO</th>
            </tr>

            </thead>
            <tbody>
        <?php
        if($_POST['vendor_id']>0) 			 $vendor_id=$_POST['vendor_id'];
        if(isset($vendor_id))				{$vendor_id_CON=' and m.vendor_code='.$vendor_id;}
        $query=mysqli_query($conn, "SELECT m.status as vstatus,m.*, i.*,v.vendor_name as vendor,
		(select COUNT(id) from purchase_receive where MAN_ID=m.MAN_ID and item_id=m.item_id) as man_create,
		(select distinct status from purchase_receive where MAN_ID=m.MAN_ID and item_id=m.item_id) as GRN_verification,
		(select SUM(amount) from purchase_receive where MAN_ID=m.MAN_ID) as grn_amount
		from MAN_details m,item_info i, vendor v
		where
		m.item_id=i.item_id and
		v.vendor_id=m.vendor_code and m.man_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'
		".$vendor_id_CON." group by m.m_id,m.item_id");
		while($data=mysqli_fetch_object($query)){
		    $pr_no=find_a_field('purchase_receive','pr_no','item_id="'.$data->item_id.'" and m_id='.$data->m_id.'');
		    ?>
            <tr style="border: solid 1px #999; font-size:10px; font-weight:normal">
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$i=$i+1;?></td>
                <td align="center" style="border: solid 1px #999; padding:2px; cursor:pointer; text-decoration:underline; color:blue" onclick="DoNavPOPUP2('<?=$data->m_id;?>', 'TEST!?', 600, 700)"><?=$data->m_id;?>, <?=$data->MAN_ID;?></td>
                <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->delivary_challan;?></td>
                <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->VAT_challan;?></td>
                <td align="left" style="border: solid 1px #999; padding:2px"><?=$data->item_id;?></td>
            <td align="left" style="border: solid 1px #999; padding:2px"><?=$data->item_name;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->unit_name;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?=$data->qty;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?php if($data->vstatus=='VERIFIED') echo $data->vstatus; else echo '';?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?php if($data->man_create>0) echo 'Done'; else echo 'Not Yet';?></td>
            <td align="center" style="border: solid 1px #999; padding:2px; cursor:pointer; text-decoration:underline; color:blue" onclick="DoNavPOPUP('<?=$pr_no;?>', 'TEST!?', 600, 700)"><?=$pr_no;?></td>
            <td align="center" style="border: solid 1px #999; padding:2px"><?php if($data->GRN_verification=='CHECKED') echo 'Done'; else echo '';?></td>

             <td align="center" style="border: solid 1px #999; padding:2px; cursor:pointer; text-decoration:underline; color:blue" onclick="DoNavPOPUP1('<?=$pr_no;?>', 'TEST!?', 600, 700)"><?=number_format($data->grn_amount,2);?></td>
            <td align="left" style="border: solid 1px #999; padding:2px"><?=$data->vendor;?></td>
            <td align="left" style="border: solid 1px #999; padding:2px"><?=$data->po_no;?></td>
        </tr>
        <?php } ?>
    </tbody>
    </table>



<?php elseif ($_POST['report_id']=='4002001'):
    $vendor_name=getSVALUE('vendor','vendor_name','where vendor_id='.$_REQUEST['vendor_id']);
	$title="MAN View";

    if($_POST['vendor_code']>0) 			 $vendor_code=$_POST['vendor_code'];
    if(isset($vendor_code))				{$vendor_code_CON=' and m.vendor_code='.$vendor_code;}

    if($_POST['warehouse_id']>0) 			 $warehouse_id=$_POST['warehouse_id'];
    if(isset($warehouse_id))				{$warehouse_id_CON=' and m.warehouse_id='.$warehouse_id;}
    ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("GRN_MAN_print_view.php?id="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<title><?=$vendor_name;?> | <?=$title;?></title>
        <p align="center" style="margin-top:-5px; font-weight: bold; font-size: 22px"><?=$_SESSION['company_name'];?></p>
        <p align="center" style="margin-top:-18px; font-size: 15px"><?=$title;?></p>
      <?php if($_POST[vendor_id]){ ?>
        <p align="center" style="margin-top:-10px; font-size: 12px"><strong>Vendor Name:</strong> <?=$vendor_name;?>)</p>
        <?php } ?>
        <p align="center" style="margin-top:-10px; font-size: 11px"><strong>Period From :</strong> <?=$_POST[f_date]?> <strong>to</strong> <?=$_POST[t_date]?></p>
        <table align="center" id="customers"  style="width:90%; border: solid 1px #999; border-collapse:collapse; font-size:11px">
                        <thead>
                        <tr style="border: solid 1px #999;font-weight:bold; font-size:11px">
                            <th style="border: solid 1px #999; padding:2px">#</th>
                            <th style="border: solid 1px #999; padding:2px">MAN ID</th>
                            <th style="border: solid 1px #999; padding:2px">MAN NO</th>
                            <th style="border: solid 1px #999; padding:2px">MAN Date</th>
                            <th style="border: solid 1px #999; padding:2px">Warehouse</th>
                            <th style="border: solid 1px #999; padding:2px">Vendor Name</th>
                            <th style="border: solid 1px #999; padding:2px">Remarks</th>
                            <th style="border: solid 1px #999; padding:2px">Delivary<br>Challan</th>
                            <th style="border: solid 1px #999; padding:2px">VAT<br>Challan</th>
                            <th style="border: solid 1px #999; padding:2px">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

						$resultss="Select m.*,m.status as man_status,w.*,u.*,v.*
from
MAN_master m,
warehouse w,
users u,
vendor v

 where
  m.entry_by=u.user_id and
 w.warehouse_id=m.warehouse_id and
 v.vendor_id=m.vendor_code and
 m.man_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' ".$vendor_code_CON.$warehouse_id_CON." order by m.id DESC ";
    $pquery=mysqli_query($conn, $resultss);
						while ($rows=mysqli_fetch_array($pquery)){ ?>
                            <tr style="font-size:11px; cursor: pointer">
                                <th style="border: solid 1px #999; padding:2px" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?=$i=$i+1;;?></th>
                                <td onclick="DoNavPOPUP('<?=$rows[id];?>', 'TEST!?', 600, 700)" style="border: solid 1px #999; padding:2px"><?=$rows[id];?></a></td>
                                <td onclick="DoNavPOPUP('<?=$rows[id];?>', 'TEST!?', 600, 700)" style="border: solid 1px #999; padding:2px"><?=$rows[MAN_ID];?></a></td>
                                <td onclick="DoNavPOPUP('<?=$rows[id];?>', 'TEST!?', 600, 700)" style="border: solid 1px #999; padding:2px"><?=$rows[man_date]; ?></td>
                                <td onclick="DoNavPOPUP('<?=$rows[id];?>', 'TEST!?', 600, 700)" style="border: solid 1px #999; padding:2px"><?=$rows[warehouse_name];?></td>
                                <td onclick="DoNavPOPUP('<?=$rows[id];?>', 'TEST!?', 600, 700)" style="border: solid 1px #999; padding:2px"><?=$rows[vendor_name];?></td>
                                <td onclick="DoNavPOPUP('<?=$rows[id];?>', 'TEST!?', 600, 700)" style="border: solid 1px #999; padding:2px"><?=$rows[remarks];?></td>
                                <td style="border: solid 1px #999; padding:2px"><a href="dc_documents/<?=$rows[$unique].'_'.'dc'.'.pdf';?>" target="_blank" style="color:#06F"><u><strong><?=$rows[delivary_challan];?></strong></u></a></td>
                                <td style="border: solid 1px #999; padding:2px"><a href="vc_documents/<?=$rows[$unique].'_'.'vc'.'.pdf';?>" target="_blank" style="color:#06F"><u><strong><?=$rows[VAT_challan];?></strong></u></a></td>
                                <td style="border: solid 1px #999; padding:2px" onclick="DoNavPOPUP('<?=$rows[$unique];?>', 'TEST!?', 600, 700)"><?php if ($rows[man_status]=='RETURNED') { echo $rows[man_status].'<br>'. '('.$rows[return_resone].')'; } else { echo $rows[man_status];}?></td>
                            </tr>
                        <?php } ?></tbody></table>
<?php endif;?>






















