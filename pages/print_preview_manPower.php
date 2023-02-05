<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();
require_once 'support_file.php';

$crud = new crud();
$unique='manPowerApp_id';
$unique_details='vehApp_id';
$table="vehicle_application_master";
$table_details="vehicle_application_details";
$$unique 		= $_REQUEST[$unique];


$datas=find_all_field('man_power_application','','manPowerApp_id='.$$unique);
$row=find_all_field('personnel_basic_info','','PBI_ID='.$datas->PBI_ID);
$full_desg = find_a_field('designation','DESG_DESC','DESG_ID='.$row->PBI_DESIGNATION);
$full_dept = find_a_field('department','DEPT_DESC','DEPT_ID='.$row->PBI_DEPARTMENT);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Print Preview :.</title>

    <link href="../../css/report.css" type="text/css" rel="stylesheet" />

<link href="normalize.css" type="text/css" rel="stylesheet"   />
<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

</script>
<style type="text/css">

<!--

.style1 {color: #000000}

-->

.divStyle div{
padding:1px;
}

</style>
</head>
<body style="font-family:Tahoma, Geneva, sans-serif;margin:0 auto;width:90%;">

<input id="pr"  type="button" value="Print" onclick="hide();window.print();"/><br>
<div style="width:100%; margin:60px auto">
<div style="float:left; width:100%; text-align:center"><?=$_SESSION['company_name']?></div>
</div>

<div style="width:100%; margin:60px auto">
<div style="float:left; width:100%; text-align:center">Man Power Application</div><br>
</div>

    <table align="center"  style="width:90%; border: solid 1px #999; border-collapse:collapse; font-size: 12px">

  <tr>
    <td style="text-align: left;border:1px solid black; width: 20%; font-weight: bold">Name : </td>
    <td style="text-align: left;border:1px solid black;"><?=$row->PBI_NAME;?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Requisition No : </td>
    <td style="text-align: left;border:1px solid black;"><?=$datas->manPowerApp_id;?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Designation : </td>
    <td style="text-align: left;border:1px solid black;"><?=$full_desg?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Department : </td>
    <td style="text-align: left;border:1px solid black;"><?=$full_dept?></td>
  </tr>
  <tr>
    <td colspan="4" style="text-align: left;border:0px solid black;">&nbsp;</td>
  </tr>

  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Application Date  :</td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->application_date;?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Requisition for Designation :</td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->requisition_for_designation;?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Requisition for Department  :</td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->requisition_for_department;?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">&nbsp;Preferred Related Experience :</td>
    <td style="text-align: left;border:1px solid black;"><?=$datas->preferred_related_experience_1.', '.$datas->preferred_related_experience_1?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Reason for Requisition  :</td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->reason_for_requisition_1.', '.$datas->reason_for_requisition_2;?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Experience (Year) :</td>
    <td style="text-align: left;border:1px solid black;"><?=$datas->preferred_experience?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Preferred Education :</td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->preferred_education;?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Age Limit : </td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->age_limit;?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Gender : </td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->preferred_gender;?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Type of Engagement :</td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->type_of_engagement;?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">No of Vacancies :</td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->no_of_vacancies;?></td>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Date of Joining : </td>
    <td style="text-align: left;border:1px solid black;"><?php echo $datas->preferred_date_of_joining;?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Job Location : </td>
    <td colspan="3" style="text-align: left;border:1px solid black;"><?php echo $datas->job_location;?></td>
  </tr>
  <tr> 
    <td style="text-align: left;border:1px solid black;font-weight: bold">Key Skills and Abilities :</td>
    <td colspan="3" style="text-align: left;border:1px solid black;"><?php echo $datas->key_skills;?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Key Responsible Area's :</td>
    <td colspan="3" style="text-align: left;border:1px solid black;"><?php echo $datas->key_responsible;?></td>
  </tr>
  <tr>
    <td style="text-align: left;border:1px solid black;font-weight: bold">Training/ Project/ Professional Qualification </td>
    <td colspan="3" style="text-align: left;border:1px solid black;"><?php echo $datas->professional_qualification;?></td>
  </tr>
</table>


<div style="width:100%; margin:60px auto; font-size: 12px">
<div style="float:left; width:50%; text-align:center"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->recommend_by)?><br>(<?=$datas->recommend_at;?>)<br>-------------------<br><b>Approved By</b></div>
<div style="float:left; width:50%; text-align:center"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorise_by)?><br>(<?=$datas->authorized_at;?>)<br>-------------------<br><b>Authorised By</b></div>
</div>
<br>
</body>
</html>
