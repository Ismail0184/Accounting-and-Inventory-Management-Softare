<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();
require_once 'support_file.php';

$crud = new crud();
$unique='vehApp_id';
$unique_details='vehApp_id';
$table="vehicle_application_master";
$table_details="vehicle_application_details";
$$unique 		= $_REQUEST[$unique];

$datas=find_all_field('vehicle_application_master','','vehApp_id='.$$unique);
$row=find_all_field('personnel_basic_info','','PBI_ID='.$datas->PBI_ID);
$full_desg = find_a_field('designation','DESG_DESC','DESG_ID='.$row->PBI_DESIGNATION);
$full_dept = find_a_field('department','DEPT_DESC','DEPT_ID='.$row->PBI_DEPARTMENT);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Print Preview :.</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
<link href="../../css/report.css" type="text/css" rel="stylesheet" />
<link href="normalize.css" type="text/css" rel="stylesheet"   />
<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

</script>
    <link href="../page/css/report.css" type="text/css" rel="stylesheet"/>
</head>
<body style="font-family:Tahoma, Geneva, sans-serif; margin: 0 auto; width:82%; font-size: 16px;">


<div style="width:100%; margin:60px auto;">
<div style="float:left; width:100%; text-align:center"><?=$_SESSION['company_name']?></div>
</div>

<div style="width:100%; margin:60px auto;">
<div style="float:left; width:100%; text-align:center">Vehicle Application</div><br>
</div>

<table width="100%%" border="1" cellspacing="0" cellpadding="0" style="border:0px solid white;margin:2% 0; font-size: 12px">
  <tr>
    <th style="text-align: left;border:0px solid white;">Employee Name :</th>
    <td style="text-align: left;border:0px solid white;"><?=$row->PBI_NAME;?></td>
    <th style="text-align: left;border:0px solid white;">Application No :</th>
    <td style="text-align: left;border:0px solid white;">
      <?=$datas->trvApp_id."5";?>
    </td>
  </tr>
  <tr>
    <th style="text-align: left;border:0px solid white;">Designation :</th>
    <td style="text-align: left;border:0px solid white;">
      <?=$full_desg?>
    </td>
    <th style="text-align: left;border:0px solid white;">Department :</th>
    <td style="text-align: left;border:0px solid white;">
      <?=$full_dept?>
    </td>
  </tr>
  <tr>
    <th style="text-align: left;border:0px solid white;">Application Date :</th>
    <td style="text-align: left;border:0px solid white;"><?php echo $datas->application_date;?></td>
    <th style="text-align: left;border:0px solid white;">Nature of Travel :</th>
    <td style="text-align: left;border:0px solid white;">
      <?=$datas->nature_of_travel?>
    </td>
  </tr>
  <tr>
    <th style="text-align: left;border:0px solid white;">Travel Date From :</th>
    <td style="text-align: left;border:0px solid white;"><?php echo $datas->trvDate_from;?></td>
    <th style="text-align: left;border:0px solid white;">To :</th>
    <td style="text-align: left;border:0px solid white;">
      <?=$datas->trvDate_to?>
    </td>
  </tr>
  <tr>
    <th style="text-align: left;border:0px solid white;">Travel Purpose :</th>
    <td style="text-align: left;border:0px solid white;"><?php echo $datas->travel_purpose;?></td>
    <th style="text-align: left;border:0px solid white;">Scope of Travel :</th>
    <td style="text-align: left;border:0px solid white;">
      <?=$datas->scop_of_travel?>
    </td>
  </tr>
</table>

<table width="100%" style="font-size: 11px" border="0" cellpadding="2" cellspacing="0">
    <thead><tr><td colspan="5" style="border:0px;"></td></tr>
    <tr><th>S/L</th>
        <th>Id</th>
        <th>Travel Date</th>
        <th>Current Location</th>
        <th>Travel From</th>
        <th>Travel To</th>
        <th>Time For</th>
    </tr></thead>
    <tbody>

    <? 	$res=mysql_query('select m.*,d.*
				  from 
				  '.$table.' m,
				  '.$table_details.' d
				  WHERE
				   
				  m.'.$unique.'=d.'.$unique_details.' and 
				  m.'.$unique.'='.$$unique.'
				   order by d.id DESC');
    while($print_data=mysql_fetch_object($res)){

    ?>
    <tr>
<td><?=$i=$i+1;?></td>
        <td><?=$print_data->$unique;?></td>
        <td><?=$print_data->application_date;?></td>
        <td><?=$print_data->current_location;?></td>
        <td><?=$print_data->travel_from;?></td>
        <td><?=$print_data->travel_to;?></td>
        <td><?=$print_data->time_for;?></td>
    </tr>
    <?php } ?>
    </tbody></table>


<div style="width:100%; margin:60px auto; font-size: 12px">
<div style="float:left; width:50%; text-align:center"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->approved_by)?><br>(<?=$datas->approved_at;?>)<br>-------------------<br>Approved By</div>
<div style="float:left; width:50%; text-align:center"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorised_person)?><br>(<?=$datas->authorized_at;?>)<br>-------------------<br>Authorised By</div>
</div>
</body>
</html>
