
<style>/* CSS Document */
    /* CSS Document */
    body
    {
        background-color: #ffffff;
        margin: 0px auto -1px auto;
        font-family:Verdana, Arial, Helvetica, sans-serif;
        font-size:11px;
        line-height:20px;
        margin-top:20px;
        color:#000000;
        text-align:none;
        text-decoration:none;
    }
    .header
    {
        font-family:Verdana, Arial, Helvetica, sans-serif;
        font-size:11px;
        color:#000000;
        text-decoration:none;
        line-height:25px;
    }

    .tabledesign {
        width:820px;
        border-color:000000;
        border-collapse:collapse;
        font-size:14px;
        text-align:center;
    }
    .tabledesign td{
        padding:3px;
        border:solid 1px;
        border-color:000000;

    }
    .tabledesign1 {
        width:860px;
        border-color:000000;
        border-collapse:collapse;
        font-size:14px;
        text-align:center;
        line-height:12px;
    }
    .tabledesign1 td{
        border:solid 0px;
        border-color:000000;

    }

    .title{
        font-weight:bold;
        font-size:25px;
        line-height:30px;
    }
    .logo{
        width:300px;
        height:50px;
    }
    .debit_box{
        width:380px;
        height:32px;
        font-weight:bold;
        font-size:25px;
        margin: 0px auto -1px auto;
    }
    .line{
        background:url(../images/line.jpg) repeat-x;
        height:2px;
        line-height:0px;}

    .tabledesign_text{
        font-family:Verdana, Arial, Helvetica, sans-serif;
        font-size:14px;
        line-height:20px;
        margin-top:20px;
        color:#000000;
        text-align:none;
        text-decoration:none;
    }
</style><?php

session_start();



ob_start();

session_start();

require_once 'support_file.php';
//require_once 'inc.voucher.php';
require_once 'class.numbertoword.php';

$proj_id=$_SESSION['proj_id'];

$_REQUEST['v_type']=strtolower($_REQUEST['v_type']);

$vtype=$_REQUEST['v_type'];

$no=$vtype."_no";

$vdate=$vtype."_date";





$address=getSVALUE('project_info','proj_address',"where 1");

if($vtype=='receipt')$voucher_name='RECEIPT VOUCHER';

elseif($vtype=='payment')$voucher_name='PAYMENT VOUCHER';

elseif($vtype=='coutra')$voucher_name='CONTRA VOUCHER';

else $voucher_name='JOURNAL VOUCHER';

if(isset($_REQUEST['vo_no']))

{

$vo_no = getSVALUE('journal','tr_no','where jv_no='.$_REQUEST['vo_no']);

$sql1="select $no,$vdate,cc_code from $vtype where $no=$vo_no limit 1";

$data1=mysql_fetch_row(mysql_query($sql1));

$vo_no=$data1[0];

$vo_date=$data1[1];

$cccode=$data1[2];

}



$pi=0;

$cr_amt=0;

$dr_amt=0;

if($vtype=='receipt')

{

$con = " and  cr_amt>0";

$viewx = 'cr_amt';

}

if($vtype=='payment')

{

$con = " and  dr_amt>0";

$viewx = 'dr_amt';

}

$sql2="SELECT a.ledger_name,b.* FROM accounts_ledger a, $vtype b where b.$no='$vo_no' and a.ledger_id=b.ledger_id ".$con." order by b.$no desc";

?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>.: Voucher :.</title>

<link href="../css/voucher_print.css" type="text/css" rel="stylesheet"/>

<script type="text/javascript">

function hide()

{

    document.getElementById("pr").style.display="none";

}

</script></head>

<body>

<table width="820" border="0" cellspacing="0" cellpadding="0" align="center">

  <tr>

    <td><div class="header"><? echo '<br>';echo '<br>';echo '<br>';?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">

	  <tr>

	    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tr>

            <td width="1%">

			<? $path='../logo/'.$_SESSION['proj_id'].'.jpg';

			if(is_file($path)) echo '<img src="'.$path.'" height="80" />';?>			</td>

            <td width="83%"><table width="100%" border="0" cellspacing="0" cellpadding="0">

              <tr>

                <td align="center" class="title">



				<?=$_SESSION['company_name']=getSVALUE('company','company_name','where company_id="'.$_SESSION['companyid'].'"'); ?>

                </td>

              </tr>

              <tr>

                <td align="center"><?=$address?></td>

              </tr>

              <tr>

                <td align="center"><table  class="debit_box" border="0" cellspacing="0" cellpadding="0">

                    <tr>

                      <td>&nbsp;</td>

                      <td width="325"><div align="center">MONEY RECEIPT</div></td>

                      <td>&nbsp;</td>

                    </tr>

                  </table></td>

              </tr>

            </table></td>

          </tr>



        </table></td>

	    </tr>

	  <tr>

	    <td>&nbsp;</td>

	  </tr>

    </table>

    </div></td>

  </tr>

  <tr>

    

	<td>	</td>

  </tr>

  

  <tr>



    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td colspan="2" class="tabledesign_text">



<div id="pr">

<div align="left">

<input name="button" type="button" onclick="hide();window.print();" value="Print" />

</div>

</div></td>

        </tr>

      <tr>

        <td class="tabledesign_text"> Voucher No  : <?=$vo_no?></td>

        <td class="tabledesign_text">Voucher Date : <?=date('d-m-Y',$vo_date)?></td>

      </tr>

    </table></td>

  </tr>

  

  <tr>

    <td><? if($cccode>0){?>CC CODE/PROJECT NAME: <? echo getSVALUE('cost_center','center_name',"where id='$cccode'")?><? }?></td>

  </tr>

  <tr>

    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" class="tabledesign">

      <tr>

        <td align="center">A/C Ledger Head</td>

        <td align="center">Particulars</td>

        <td>Taka</td>

      </tr>

      

	  <?



$data2=mysql_query($sql2);

			  while($info=mysql_fetch_object($data2)){ $pi++;

			  $cr_amt=$cr_amt+$info->cr_amt;

			  $dr_amt=$dr_amt+$info->dr_amt;

			  if($info->bank==''&&$info->cheq_no!='')

			  $narration=$info->narration.':: Cheq # '.$info->cheq_no.'; dt= '.date("d.m.Y",$info->cheq_date);

			  elseif($info->cheq_no=='')

			  $narration=$info->narration;

			  else

			  $narration=$info->narration.':: Cheq # '.$info->cheq_no.'; dt= '.date("d.m.Y",$info->cheq_date).'; Bank # '.$info->bank;

			  

	  ?>

      <tr>

        <td align="left"><?=$info->ledger_name?> : <?=$info->ledger_id?></td>

        <td align="left"><?=$narration?></td>

        <td align="right"><? echo number_format($info->$viewx,2); $tt = $tt + $info->$viewx;?></td>

        </tr>

<?php }?>

      <tr>

        <td colspan="2" align="right">Total Taka: </td>

        <td align="right"><?=number_format($tt,2)?></td>

        </tr>

      

    </table></td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>Amount in Word : 



	 (<? echo convertNumberCustom($tt)?>)

	 </td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td align="center" valign="bottom">................................</td>

        <td align="center" valign="bottom">................................</td>

        <td align="center" valign="bottom">................................</td>

        <td align="center" valign="bottom">................................</td>

        <td align="center" valign="bottom">................................</td>

      </tr>

      <tr>

        <td><div align="center">Receive by </div></td>

        <td><div align="center">Prepared by </div></td>

        <td><div align="center">Checked by </div></td>

        <td><div align="center">Head of Accounts </div></td>

        <td><div align="center">Approved By </div></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

</table>

</body>

</html>

<?

$main_content=ob_get_contents();

ob_end_clean();



echo $main_content;

echo '<br>';echo '<br>';echo '<br>';echo '<br>';

echo $main_content;

?>