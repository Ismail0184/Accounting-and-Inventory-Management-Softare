<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();

//====================== EOF ===================

//var_dump($_SESSION);

require_once "../../config/inc.all.php";
require "../../classes/report.class.php";
require "../../../engine/tools/class.numbertoword.php";


$request_id 		= $_REQUEST['requestId'];


$crud = new crud();


$datas=find_all_field('handover_application_master','','handOver_id='.$request_id);

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
<style type="text/css">

<!--

.style1 {color: #000000}

-->

.divStyle div{
padding:1px;
}

</style>
</head>
<body style="font-family:Tahoma, Geneva, sans-serif;width:82%;margin:0 auto;">


<div style="width:100%; margin:60px auto">
<div style="float:left; width:100%; text-align:center"><?=$_SESSION['company_name']?></div>
</div>

<div style="width:100%; margin:60px auto">
<div style="float:left; width:100%; text-align:center">Hand Over Take Over Application</div><br>
</div>
<table width="100%%" border="1" cellspacing="0" cellpadding="0" style="border:0px solid white;margin:2% 0;">
  <tr>
    <td style="text-align: left;border:0px solid white;font-size:14px;">Employee Name :</td>
    <td style="text-align: left;border:0px solid white;font-size:14px;"><?=$row->PBI_NAME;?></td>
    <td style="text-align: left;border:0px solid white;font-size:14px;">Application No :</td>
    <td style="text-align: left;border:0px solid white;font-size:14px;">
      <?=$datas->handOver_id;?>    </td>
  </tr>
  <tr>
    <td style="text-align: left;border:0px solid white;font-size:14px;">Designation :</td>
    <td style="text-align: left;border:0px solid white;font-size:14px;">
      <?=$full_desg?>    </td>
    <td style="text-align: left;border:0px solid white;font-size:14px;">Department :</td>
    <td style="text-align: left;border:0px solid white;font-size:14px;">
      <?=$full_dept?>    </td>
  </tr>
  <tr>
    <td style="text-align: left;border:0px solid white;font-size:14px;">Application Date :</td>
    <td style="text-align: left;border:0px solid white;font-size:14px;"><?php echo $datas->application_date;?></td>
    <td style="text-align: left;border:0px solid white;font-size:14px;">Reason for Handover   :</td>
    <td style="text-align: left;border:0px solid white;font-size:14px;">
      <?=$datas->reason_for_handover?>    </td>
  </tr>
</table>


<?php
$res = 'select takeOver_department as take_over_department, (select p.PBI_NAME from personnel_basic_info p where p.PBI_ID=takeOver_person) as take_over_person, d.takeOver_details as take_over_details, d.takeOver_status as status from handover_application_details d where d.handOver_id= '.$datas->handOver_id;

//echo $res;

		echo report_create($res,1,'');
	
			?>
<div style="width:100%; margin:60px auto">


<div style="float:left; width:50%; text-align:center"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->approved_by)?><br>-------------------<br>Approved By</div>
<div style="float:left; width:50%; text-align:center"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorised_person)?><br>-------------------<br>Authorised By</div>

</div>
</body>
</html>
