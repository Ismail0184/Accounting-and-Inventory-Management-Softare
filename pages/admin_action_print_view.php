<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require_once 'support_file.php';
$request_id 		= $_GET['action_id'];
$datas=find_all_field('admin_action_detail','','ADMIN_ACTION_DID='.$request_id);
$row=find_all_field('personnel_basic_info','','PBI_ID='.$datas->issued_to);
$full_desg = find_a_field('designation','DESG_DESC','DESG_ID='.$row->PBI_DESIGNATION);
$full_dept = find_a_field('department','DEPT_DESC','DEPT_ID='.$row->PBI_DEPARTMENT);


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Action</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">

function hide()
{    document.getElementById("pr").style.display="none";
}
</script>


<style type="text/css">
.style1 {color: #000000}
</style>
</head>

<body style="font-family:Tahoma, Geneva, sans-serif;">
<h6 align="center" style=" margin-top:-10px"></h6>
<br />

<h4 align="center"><u>Admin Action</u></h4>
<?php
$request_id = $_GET[action_id];

$result=mysql_query("Select * from admin_action_detail where ADMIN_ACTION_DID='$request_id'");
$actionrow=mysql_fetch_array($result);

 
$actionfor=mysql_query("Select * from personnel_basic_info where PBI_ID='$actionrow[PBI_ID]'");
						$forrow=mysql_fetch_array($actionfor);
						
						
$resultss=mysql_query("Select * from designation where DESG_ID='$forrow[PBI_DESIGNATION]'");
$rows3=mysql_fetch_array($resultss);


$resultsss=mysql_query("Select * from department where DEPT_ID='$forrow[PBI_DEPARTMENT]'");
$rows4=mysql_fetch_array($resultsss);						
						
						
						$actionby=mysql_query("Select * from personnel_basic_info where PBI_ID='$actionrow[ADMIN_ACTION_BY]'");
						$byrow=mysql_fetch_array($actionby);
						$resultssdes=mysql_query("Select * from designation where DESG_ID='$byrow[PBI_DESIGNATION]'");
$desig=mysql_fetch_array($resultssdes);

$resultssssssss=mysql_query("Select * from department where DEPT_ID='$byrow[PBI_DEPARTMENT]'");
$desigs=mysql_fetch_array($resultssssssss);

												
						$copyone=mysql_query("Select * from personnel_basic_info where PBI_ID='$actionrow[copy_one]' and PBI_JOB_STATUS='In Service'");
						$copyonerow=mysql_fetch_array($copyone);
						$copytwo=mysql_query("Select * from personnel_basic_info where PBI_ID='$actionrow[copy_two]' and PBI_JOB_STATUS='In Service'");
						$copytworow=mysql_fetch_array($copytwo);
						$copythree=mysql_query("Select * from personnel_basic_info where PBI_ID='$actionrow[copy_three]' and PBI_JOB_STATUS='In Service'");
						$copythreerow=mysql_fetch_array($copythree);
						$copyfour=mysql_query("Select * from personnel_basic_info where PBI_ID='$actionrow[copy_four]' and PBI_JOB_STATUS='In Service'");
						$copyfourrow=mysql_fetch_array($copyfour);
?>



<table align="center" style="width:95%; font-size:13px;" cellpadding="5">
<tr>
<td style="text-align:left"><b>Ref No :</b>  <?php echo $actionrow[ADMIN_ACTION_MEMO_NO]; ?></td>
</tr>

<tr style="height:20px">
</tr>


<tr>
<td style="text-align:left"><?php echo $actionrow[ADMIN_ACTION_DATE]; ?></td>
</tr>

<tr style="height:20px">
</tr>


<tr>
<td style="text-align:left"><?php echo $forrow[PBI_NAME]; ?></td>
</tr>
<tr>
<td style="text-align:left"><?php echo $rows3[DESG_DESC]; ?>, <?php echo $rows4[DEPT_DESC]; ?></td>
</tr>

<tr>
<td style="text-align:left"><B><?=$_SESSION['company_name']?></B><br />
Suite C5, House-25, Road-47, Gulshan 2, Dhaka 1212, Bangladesh.</td>
</tr>


<tr style="height:20px">
</tr>


<tr>
<td style="text-align:left"><b>Subject: <?php echo $actionrow[ADMIN_ACTION_SUBJECT]; ?></b></td>
</tr>


<tr>
<td style="text-align:left"><b>Dear Mr./Mis.  <?php echo $actionrow[PBI_NAME]; ?></b></td>
</tr>


<tr>
<td style="text-align:left"><?php echo $actionrow[action_details]; ?></td>
</tr>


<tr style="height:50px">
</tr>

<tr>
<td style="text-align:left">For, <?=$_SESSION['company_name']?></td>
</tr>


<tr style="height:15px">
</tr>

<tr>
<td style="text-align:left;"><b style="text-decoration:overline"><?php echo $byrow[PBI_NAME];?></b><br /><?php echo $desig[DESG_DESC];?>,<?php echo $desigs[DEPT_DESC];?></td>
</tr>


<tr style="height:15px">
</tr>

<tr>
<td style="text-align:left;"><b>Copy to:</b><br /><br />
1. <?php echo $copyonerow[PBI_NAME];?><br />
2. <?php echo $copytworow[PBI_NAME];?><br />
3. <?php echo $copythreerow[PBI_NAME];?><br />
4. <?php echo $copyfourrow[PBI_NAME];?><br />











</td>
</tr>



</table>


</body>
</html>
