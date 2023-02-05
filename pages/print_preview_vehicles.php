<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once 'support_file.php';
$request_id 		= $_REQUEST['id'];
$datas=find_all_field('warehouse_other_issue','','oi_no='.$request_id);
$row=find_all_field('personnel_basic_info','','PBI_ID='.$datas->issued_to);
$full_desg = find_a_field('designation','DESG_DESC','DESG_ID='.$row->PBI_DESIGNATION);
$full_dept = find_a_field('department','DEPT_DESC','DEPT_ID='.$row->PBI_DEPARTMENT);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Print Preview :.</title>
<link href="../css/invoice.css" type="text/css" rel="stylesheet"/>
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

<?php 



$res=mysql_query('select v.*,p.*,des.*,dep.* FROM 
							 
							personnel_basic_info p,
							department dep,
							designation des,
							vehicle_registration v
							 where 
							 v.employee_id=p.PBI_ID and 
							 p.PBI_DESIGNATION=des.DESG_ID and  							 
							 p.PBI_DEPARTMENT=dep.DEPT_ID
				  	  
				   order by v.id DESC');
$vdata=mysql_fetch_object($res);


?>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div class="header">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><?=$_SESSION['company_name']?></td>
                            </tr>
                            <tr>
                              <td style="text-align:center; font-size:18px; font-weight:bold;"><span class="style1">Vehicle Information</span></td>
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
                        <td  align="left" valign="middle">Employee Name : </td>
                        <td ><?=$vdata->PBI_NAME; ?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle">Designation :</td>
                        <td><?=$vdata->DESG_DESC; ?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="top"> Department :</td>
                        <td><?=$vdata->DEPT_DESC; ?></td>
                      </tr>
                      
                    </table></td>
                  <td width="40%"><table width="100%" border="0" cellspacing="0" cellpadding="3"  style="font-size:13px">
                      <tr>
                        <td width="48%" align="right" valign="middle"><div align="left">Joining Date : </div></td>
                        <td width="52%"><?=$vdata->PBI_DOJ; ?></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle"><div align="left">From : </div></td>
                        <td><?=$vdata->employee_date_from; ?></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle"><div align="left">To :</div></td>
                        <td><?=$vdata->employee_date_to; ?></td>
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
      <table width="100%" class="tabledesign" border="1" bordercolor="#000000" cellspacing="0" cellpadding="2" style="font-size:11px; border-collapse:collapse">
        <tr>
          <td align="center" bgcolor="#FFFFFF"><strong>Reg. No</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->registration_no; ?></td></tr>
          
          <tr>
          <td align="center" bgcolor="#FFFFFF"><strong>Description</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->description; ?></td></tr>
          
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Chassis No</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->chassis; ?></td></tr>
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Engine No</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->engine_no; ?></td></tr>
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>CC</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->cc; ?></td></tr>
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Color</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->vehicle_color; ?></td></tr>
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Owner Name</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->owner_name; ?></td></tr>
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Address</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->address; ?></td></tr>
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Registration Certificate</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->certificate; ?></td></tr>
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Digital Number Plate</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->number_plate; ?></td></tr>
          
          
        
        <tr><td align="center" bgcolor="#FFFFFF"><strong>Fitness</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->fitness; ?><br />
          Date From - <?=$vdata->fitness_date_from; ?><br />
          Date to - <?=$vdata->fitness_date_to; ?><br />
          Amount - <?=$vdata->fitness_amount; ?></td>
          </tr>
          
          
          
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Tax Token</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->tax_token; ?><br />
          Date From - <?=$vdata->tax_token_date_from; ?><br />
          Date to - <?=$vdata->tax_token_date_to; ?><br />
          Amount - <?=$vdata->tax_token_amount; ?></td>
          </tr>
          
          
          
          
          
          
          <tr><td align="center" bgcolor="#FFFFFF"><strong>Insurance</strong></td>
          <td align="center">:</td>
          <td><?=$vdata->insurance; ?><br />
          Date From - <?=$vdata->insurance_date_from; ?><br />
          Date to - <?=$vdata->insurance_date_to; ?><br />
          Amount - <?=$vdata->insurance_amount; ?></td>
          </tr>
          
        
       </table></td>
  </tr>
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        
        
        <tr>
          <td colspan="2" align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->recommended_by)?>
                </span></td>
                <td align="center"><span class="oe_form_group_cell oe_form_group_cell_label">
                  <?=find_a_field('personnel_basic_info','PBI_NAME','PBI_ID='.$datas->authorised_person)?>
                </span></td>
              </tr>
              <tr style="height:40px"><td></td></tr>
              <tr>
                <td align="center" style="text-decoration:overline"><strong>Approved By </strong></td>
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
