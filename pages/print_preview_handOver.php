<?php
require_once 'support_file.php';

$crud = new crud();
$unique='handOver_id';
$unique_details='handOver_id';
$table="handover_application_master";
$table_details="handover_application_details";
$$unique 		= $_REQUEST[$unique];


$datas=find_all_field('handover_application_master','',''.$unique.'='.$$unique);
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
<body style="font-family:Tahoma, Geneva, sans-serif;width:82%;margin:0 auto;">


<div style="width:100%; margin:60px auto">
<div style="float:left; width:100%; text-align:center"><?=$_SESSION['company_name']?></div>
</div>

<div style="width:100%; margin:60px auto">
<div style="float:left; width:100%; text-align:center">Hand Over Take Over Application</div><br>
</div>
<table width="100%%" border="1" cellspacing="0" cellpadding="0" style="border:0px solid white;margin:2% 0; font-size: 12px">
  <tr>
    <td style="text-align: left;border:0px solid white;font-weight: bold">Employee Name :</td>
    <td style="text-align: left;border:0px solid white;"><?=$row->PBI_NAME;?></td>
    <td style="text-align: left;border:0px solid white;font-weight: bold">Application No :</td>
    <td style="text-align: left;border:0px solid white;">
      <?=$datas->handOver_id;?>    </td>
  </tr>
  <tr>
    <td style="text-align: left;border:0px solid white;font-weight: bold">Designation :</td>
    <td style="text-align: left;border:0px solid white;">
      <?=$full_desg?>    </td>
    <td style="text-align: left;border:0px solid white;font-weight: bold">Department :</td>
    <td style="text-align: left;border:0px solid white;">
      <?=$full_dept?>    </td>
  </tr>
  <tr>
    <td style="text-align: left;border:0px solid white;font-weight: bold">Application Date :</td>
    <td style="text-align: left;border:0px solid white;"><?php echo $datas->application_date;?></td>
    <td style="text-align: left;border:0px solid white;font-weight: bold">Reason for Handover   :</td>
    <td style="text-align: left;border:0px solid white;">
      <?=$datas->reason_for_handover?>    </td>
  </tr>
</table>


<table width="100%" style="font-size: 11px;border-collapse: collapse" border="1" cellpadding="2" cellspacing="0" >
    <thead>
    <tr><th>S/L</th>
        <th>Id</th>
        <th>Take Over Department</th>
        <th>Take Over Person</th>
        <th>Take Over Details</th>
        <th>Remarks</th>
        <th>Status</th>
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
            <td><?=$print_data->takeOver_department;?></td>
            <td><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$print_data->takeOver_person.'');?></td>
            <td><?=$print_data->takeOver_details;?></td>
            <td><?=$print_data->takeOver_remarks;?></td>
            <td><?=$print_data->takeOver_status;?></td>
        </tr>
    <?php } ?>
    </tbody></table>
<div style="width:100%; margin:60px auto">


<div style="float:left; width:50%; text-align:center; font-size: 12px"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->approved_by)?><br>(<?=$datas->approved_at;?>)<br>-------------------<br>Approved By</div>
<div style="float:left; width:50%; text-align:center; font-size: 12px"><?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorised_person)?><br>(<?=$datas->authorized_at;?>)<br>-------------------<br>Authorised By</div>

</div>
</body>
</html>
