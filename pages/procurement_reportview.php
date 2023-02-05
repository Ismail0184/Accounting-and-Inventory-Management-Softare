<?php require_once 'support_file.php'; $title='Procurement Report';

if(!empty($_POST['order_by'])) $order_by_GET=$_POST['order_by'];
if(isset($order_by_GET))				{$order_by=' order by '.$order_by_GET;}
if(!empty($_POST['order_by']) && !empty($_POST['sort'])) $order_by_GET=$_POST['order_by'];
if(isset($order_by_GET))				{$order_by=' order by '.$order_by_GET.' '.$_POST[sort].'';}

if(!empty($_POST['vendor_id'])) $vendor_id=$_POST['vendor_id'];
if(isset($vendor_id))				{$vendor_id_conn=' and v.vendor_id='.$vendor_id;}
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



<?php if ($_POST['report_id']=='3002001'):
    $sql="SELECT v.vendor_id,v.vendor_id,v.ledger_id,v.vendor_name,v.address,v.contact_no,vc.category_name as vendor_category,vt.vendor_type,v.email,v.contact_person_name,v.contact_person_designation,v.contact_person_mobile from vendor v, vendor_category vc,vendor_type vt
    where v.vendor_category=vc.id and v.vendor_type=vt.id and v.status in ('".$_POST[status]."') ".$order_by.""; echo reportview($sql,'Vendor Report','98');?>

<?php elseif ($_POST['report_id']=='3001001'):
    $sql="Select pm.po_no,pm.po_no,pm.po_date,concat(pm.vendor_id,' : ',v.vendor_name) as vendor,i.item_id as 'Mat. Code',i.item_name as 'Mat. Description',i.unit_name as 'UoM',pi.qty,pi.rate,pi.amount as Value,pm.tax as VAT  from
    purchase_master pm,
    purchase_invoice pi,
    vendor v,
    item_info i
    where
    pm.po_no=pi.po_no and
    pm.po_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'".$vendor_id_conn." and
    pm.vendor_id=v.vendor_id and
    pi.item_id=i.item_id order by v.vendor_id"; echo reportview($sql,'Purchase Order Report','98');

    elseif ($_POST['report_id']=='3001002'):
$sql="Select pm.po_no,pm.po_no,pi.pr_no as GRN,pm.po_date,pm.vendor_id as vendor_code,v.vendor_name,i.item_id as 'Mat. Code',i.item_name as 'Mat. Description',i.unit_name as 'UoM',pi.qty,pi.rate,SUM(pi.amount) as Value,pm.tax as 'VAT,%'  from
    purchase_master pm,
    purchase_receive pi,
    vendor v,
    item_info i
    where
    pm.po_no=pi.po_no and
    pm.po_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and
    pm.vendor_id=v.vendor_id and
    pi.item_id=i.item_id group by pi.item_id,pi.pr_no order by pm.po_no,pi.pr_no
    "; echo reportview($sql,'Purchase Received Report','98');?>



<?php endif; ?>
</body>
</html>
