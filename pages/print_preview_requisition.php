<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once 'support_file.php';
$unique='oi_no';
$unique_details='oi_no';
$table="travel_application_claim_master";
$table_details="travel_application_claim_details";
$$unique 		= $_REQUEST[$unique];



$datas=find_all_field('warehouse_other_issue','','oi_no='.$$unique);
$row=find_all_field('personnel_basic_info','','PBI_ID='.$datas->issued_to);
$full_desg = find_a_field('designation','DESG_DESC','DESG_ID='.$row->PBI_DESIGNATION);
$full_dept = find_a_field('department','DEPT_DESC','DEPT_ID='.$row->PBI_DEPARTMENT);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Print Preview :.</title>
    <link href="../../css/report.css" type="text/css" rel="stylesheet" />
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

</style>
</head>
<body style="font-family:Tahoma, Geneva, sans-serif">
<br />
<br />
<br />
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div class="header">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><?=$_SESSION['company_name']?></td>
                            </tr>
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><span class="style1">Stationary Requisition</span></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <td  align="left" valign="middle">Requisition By: </td>
                        <td ><?php echo $row->PBI_NAME;?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle">Designation :</td>
                        <td><?=$full_desg?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="top"> Requisition Date :</td>
                        <td><?=$datas->oi_date?></td>
                      </tr>
                      
                    </table></td>
                  <td width="40%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <td width="48%" align="right" valign="middle"><div align="left">Requisition No : </div></td>
                        <td width="52%"><?=$datas->oi_no?></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle"><div align="left">Department : </div></td>
                        <td><?=$full_dept?></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle"><div align="left">Requisition Category :</div></td>
                        <td><?=find_a_field('item_sub_group','sub_group_name','sub_group_id='.$datas->req_category)?></td>
                      </tr>
                      
                    </table></td>
                </tr>
              </table></td>
          </tr>
        </table>
      </div></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td><div id="pr">
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
      <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="3" style="font-size:11px; border-collapse: collapse">
        <tr>
          <td align="center" bgcolor="#FFFFFF"><strong>SL</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Stationary Details</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Remarks</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Applied</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Recommended</strong></td>
          <td align="center" bgcolor="#FFFFFF"><strong>Approved</strong></td>
        </tr>
        <? 
$g_s_date=date('Y-01-01');

$g_e_date=date('Y-12-31');



$res = 'select d.id, i.item_name, d.request_qty as applied_qty, d.recommend_qty, d.remarks_details, d.qty as approved_qty from item_info i, warehouse_other_issue_detail d where d.item_id = i.item_id and d.oi_no= '.$datas->oi_no;
$query = mysql_query($res);
while($data=mysql_fetch_object($query)){

?>
        <tr>
          <td align="center" valign="top"><?=++$i?></td>
          <td align="left" valign="top"><? echo $data->item_name ?></td>
          <td align="left" valign="top"><? echo $data->remarks_details ?></td>
          <td align="center" valign="top"><? echo $data->applied_qty ?></td>
          <td align="center" valign="top"><? echo $data->recommend_qty ?></td>
          <td align="center" valign="top"><? echo $data->approved_qty ?></td>
        </tr>
        <? }?>
      </table></td>
  </tr>
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        
        
        <tr>
          <td colspan="2" align="center">
           
           
          
             <table style="font-size:11px; margin-top:50px" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
               
              </tr>
              <tr>
                <td align="center" ><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->recommended_by)?>
                </span><br /> <font style="font-size:11px; padding-bottom::-50px">(<?=$datas->recommended_date;?>)</font></td>
                <td align="center"><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorised_person)?>
                </span><br /> <font style="font-size:11px; padding-bottom::-50px">(<?=$datas->authorised_date;?>)</font></td>
                
              </tr>
              <tr>
                <td align="center" style="text-decoration:overline"><strong>Recommended By </strong></td>
                <td align="center" style="text-decoration:overline"><strong>Authorised By </strong></td>
                
              </tr>
            </table>
            <strong><br />
            </strong></td>
        </tr>
        
      </table>
    <div class="footer1"> </div></td>
  </tr>
</table>
</body>
</html>
