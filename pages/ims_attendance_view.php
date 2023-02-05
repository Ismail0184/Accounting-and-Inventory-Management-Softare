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

    <title>Attendance Report</title>
    <script type="text/javascript">

        function hide()

        {

            document.getElementById("pr").style.display = "none";

        }

    </script>
    <style>
        #namedd2 { text-align:center; font-size:12px; background-color:#8B2323}
        #namedd1 { text-align:center; font-size:12px; background-color:#458B00}
        #namedd30 {text-align:center; font-size:12px; background-color:#FFF68F}
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






    <table align="center"  style="width:98%; border: solid 1px #999; border-collapse:collapse; font-size: 11px ">
        <thead>
        <h3 align="center"><?=$_SESSION['company_name'];?></h3>
        <p align="center" style="margin-top: -10px; font-size: 11px">Attendance from <?php $date=date_create("$from_date");echo date_format($date,"d-m-Y"); ?> to
            <?php $tdate=date_create("$to_date");echo date_format($tdate,"d-m-Y"); ?>
        </p>
        <p style="width:90%; text-align:right; font-size:11px; font-weight:normal">Reporting Time: <?php $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
            echo $now=$dateTime->format("d/m/Y  h:i:s A");?></p>
        <tr style="border: solid 1px #999;font-weight:bold; font-size:12px">
            <th style="border: solid 1px #999; padding:2px; width: 1%">SL</th>
            <th style="border: solid 1px #999; padding:2px; width: 5%">SO CODE</th>
            <th style="border: solid 1px #999; padding:2px; width: 20%">Name of SO</th>
            <?php
            $res=mysql_query("select distinct  day from hrm_attendance_info where working_day between  '$from_date' and '$to_date' order by day asc");
             while($item=mysql_fetch_array($res)){
                 $id=$item[item_id]; ?>
            <th style="border: solid 1px #999; padding:2px; "><?=$item[day];?></th>
            <?php } ?>
        </tr></thead>


        <tbody>
        <?php
        $res=mysql_query('select p.*                                
                                from 
                                personnel_basic_info p 
                                where  p.PBI_DESIGNATION like "60" and p.PBI_JOB_STATUS="In Service" group by PBI_ID order by p.sl ');
        while($attdata=mysql_fetch_object($res)){
            $i=$i+1; ?>
            <tr style="border: solid 1px #999; font-size:11px; font-weight:normal">
                <td style="border: solid 1px #999; text-align:center;"><?=$j=$j+1; ?></td>
                <td style="border: solid 1px #999; text-align:center;"><?=$attdata->PBI_ID_UNIQUE;?></td>
                <td style="border: solid 1px #999; text-align:left; padding-left: 10px"><?=$attdata->PBI_NAME;?></td>
                <?php
                $dres=mysql_query("select distinct d.day, 
(select attendance from hrm_attendance_info where day=d.day and PBI_ID='$attdata->PBI_ID') as attendance
from hrm_attendance_info d where d.working_day between  '$from_date' and '$to_date' order by d.day asc");
                while($day=mysql_fetch_object($dres)){
                    if ($day->attendance == 'L'){
                        echo "<td id='namedd1' style='border: solid 1px #999; padding:2px; text-align: center'><font color='#FFFFFF'>". $day->attendance."</font></td>";
                    }
                    else if ($day->attendance == 'A'){
                        echo "<td id='namedd2' style='border: solid 1px #999; padding:2px; text-align: center'><font color='#FFFFFF'>". $day->attendance ."</font></td>";
                    }

                    else if ($day->attendance == 'H'){
                        echo "<td id='namedd30' style='border: solid 1px #999; padding:2px; text-align: center'><font color='#'>". $day->attendance ."</font></td>";
                    }
                    else if ($day->attendance == 'P'){
                        echo "<td id='namedd' style='border: solid 1px #999; padding:2px; text-align: center'><font color=''>". $day->attendance ."</font></td>";
                    }
                    else if ($day->attendance == '0'){
                        echo "<td id='namedd' style='border: solid 1px #999; padding:2px; text-align: center'><font color=''>". $day->attendance ."</font></td>";
                    } else {
                        echo "<td style='border: solid 1px #999; padding:2px; text-align: center'><?=$day->attendance;?></td>";
                    }
                } ?>


            </tr>
            <?php


        } ?>

        </tbody>
    </table>











    </div>
    </div>
    </div>









</body>
</html>

</html>