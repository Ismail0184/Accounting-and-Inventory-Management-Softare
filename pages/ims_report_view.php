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
$unique='ims_no';
$page="get_ims_data_all.php";
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
        {document.getElementById("pr").style.display = "none";
        }

    </script>
    <style>
        #customers {
        }
        #customers td {
        }
        #customers tr:ntd-child(even)
        {background-color: #f0f0f0;}
        #customers tr:hover {background-color: #FFCCFF;}
        td{text-align: center;}
    </style>


    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=730,height=600,left = 383,top = -1");}
    </script>

</head>

<body style="font-family: cursive;">
<div id="pr" style="margin-left:48%">
    <div align="left">
        <form id="form1" name="form1" method="post" action="">
            <!--table width="50%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
                </tr>
            </table-->
        </form>
    </div>
</div>


<table align="center"  style="width:100%; border: solid 1px #999; border-collapse:collapse; " id="customers">

        <h4 style="margin-top: -5px" align="center"><?=$_SESSION['company_name'];?></h4>
    <?php if($_POST[tsm]){ ?>
        <h5 style="margin-top: -20px" align="center">Incharge Person / TSM : <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$_POST[tsm].'');?></h5>
    <?php } ?>
        <h5 style="margin-top: -20px" align="center">ISM Date : <?php
$date=date_create("$from_date");
echo date_format($date,"d M Y");
?> </h5>

    <thead>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px">SO CODE</th>
            <?php
            //$res=mysqli_query($conn,"select * from item_info where sub_group_id in ('200010000') and exim_status not in ('Export') and t_price>0 and brand_category not in ('Rice') and status in ('Active') order by serial");




            $res=mysqli_query($conn,"SELECT i.*,sg.sub_group_name,g.group_name,tp.*
					 FROM  item_info i,
							item_sub_group sg,
							item_group g,
							effective_tp tp														
							 WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id	in ('500000000') and 
							 i.status in ('Active')	and
							 i.exim_status not in ('Export') and
							 i.brand_category not in ('Rice') and 
							 i.item_id not in ('1096000100010312','1096000100010313','700020001') and 
							 i.item_id=tp.item_id  order by i.serial");
            while($item=mysqli_fetch_array($res)){
                 $id=$item[item_id]; ?>
            <th style="border: solid 1px #999; padding:2px; "><?=$item[item_name];?> - <?=$i=$i+1;?></th>
            <?php } ?>

            <th style="border: solid 1px #999; padding:2px">Total Day Products Value</th>
            <th style="border: solid 1px #999; padding:2px">Workday Count</th>
            <th style="border: solid 1px #999; padding:2px">Call</th>
            <th style="border: solid 1px #999; padding:2px">Prod. Call</th>
            <th style="border: solid 1px #999; padding:2px">TLS</th>



        </tr></thead>


        <tbody>
        <?php
        $datecon=' and m.order_date between  "'.$from_date.'" and "'.$to_date.'"';
        if($_POST['tsm']>0) 				$tsm=$_POST['tsm'];
        if(isset($tsm)) 				{$tsm_con=' and m.TSM_PBI_ID='.$tsm;}
        $result=mysqli_query($conn, 'Select 
				m.*,
				id.*,
				p.*,
				SUM(id.total_amt_today) as amt
				
				from
								
				ims_master m,
				ims_details id,
				personnel_basic_info p
				 
				where 
				m.PBI_ID=p.PBI_ID and				 
				m.ims_no=id.ims_no'.$datecon.$tsm_con.'				
				group by m.ims_no order by p.sl');
        while($data=mysqli_fetch_object($result)){
            $i=$i+1; ?>


            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center; cursor: pointer" onclick="DoNavPOPUP('<?=$data->ims_no?>', 'TEST!?', 900, 600)"><?=$data->PBI_ID_UNIQUE; ?></td>
                <?php





            $res=mysqli_query($conn, "SELECT i.*,sg.sub_group_name,g.group_name,tp.*,ims.* 
					 FROM  item_info i,
							item_sub_group sg,
							item_group g,
							effective_tp tp,
							ims_details ims 							
							 WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id	in ('500000000') and 
							 i.status in ('Active')	and
							 i.exim_status not in ('Export') and
							 i.brand_category not in ('Rice') and 
							 i.item_id not in ('1096000100010312','1096000100010313','700020001') and 
							 i.item_id=tp.item_id  and
							 ims.ims_no='".$data->ims_no."' group by ims.id order by i.serial");
             while($item=mysqli_fetch_array($res)){?>
                <td style="border: solid 1px #999; text-align:center"><?=$item[total_unit_today]; ?></td>
                <?php } ?>

                <td style="border: solid 1px #999; text-align:right; padding:2px"><?=$data->amt;?></td>
                <td style="border: solid 1px #999; text-align:right;  padding:2px"><?=$adjustment=$data->adjustment;?></td>
                <td style="border: solid 1px #999; text-align:center;  padding:2px"><?=$data->total_call;?></td>
                <td style="border: solid 1px #999; text-align:center; padding:2px"><?=$data->productive_call;?></td>
                <td style="border: solid 1px #999; text-align:center; padding:2px"><?=$data->total_line;?></td>
            </tr>
            <?php
            $totaladjustment=$totaladjustment+$adjustment;
            $totalcollection=$totalcollection+$collection;
            $totalactualcollection=$totalactualcollection+$actualcollection;

        } ?>

        </tbody>
    </table>


<p style="width:100%; text-align:left; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
    echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>








    </div>
    </div>
    </div>









</body>
</html>

</html>