<?php



require_once 'support_file.php';

$title='Account Ledger';

$proj_id=$_SESSION['proj_id'];

$now=time();

$separator	= $_SESSION['separator'];

if(isset($_REQUEST['ledger_name'])||isset($_REQUEST['ledger_id']))

{

	//common part.............

	

	$ledger_id			= mysql_real_escape_string($_REQUEST['ledger_id']);

	$ledger_name 		= mysql_real_escape_string($_REQUEST['ledger_name']);

	$ledger_name		= str_replace("'","",$ledger_name);

	$ledger_name		= str_replace("&","",$ledger_name);

	$ledger_name		= str_replace('"','',$ledger_name);

	$ledger_group_id	= mysql_real_escape_string($_REQUEST['ledger_group_id']);

	$opening_balance	= mysql_real_escape_string($_REQUEST['balance']);

	$balance_type		= mysql_real_escape_string($_REQUEST['b_type']);

	$depreciation_rate	= mysql_real_escape_string($_REQUEST['d_rate']);

	$credit_limit		= mysql_real_escape_string($_REQUEST['cr_limit']);

	$date				= mysql_real_escape_string($_REQUEST['open_date']);

	$now				= date_value($date);

	$budget_enable		= mysql_real_escape_string($_REQUEST['budget_enable']);

	//end 

	if(isset($_POST['nledger']))

	{

		

	if(!ledger_redundancy($ledger_name))

	{

	$type=0;

	$msg='Given Name('.$ledger_name.') is already exists.';

	}

	else

	{

	$ledger_id=next_ledger_id($ledger_group_id);

		if(ledger_create($ledger_id,$ledger_name,$ledger_group_id,$opening_balance,$balance_type,$depreciation_rate,$credit_limit, $now,$proj_id,$budget_enable))

		{

		$type=1;

		$msg='New Entry Successfully Inserted.';

		}

	}

}





//for Modify..................................



if(isset($_POST['mledger']))

{

if(ledger_redundancy($ledger_name,$ledger_id))

{

$sql="UPDATE `accounts_ledger` SET 

		`ledger_name` 		= '$ledger_name',

		`opening_balance` 	= '$opening_balance',

		`ledger_group_id`	= '$ledger_group_id',

		`balance_type` 		= '$balance_type',

		`depreciation_rate` = '$depreciation_rate',

		`credit_limit` 		= '$credit_limit',

		`budget_enable`		= '$budget_enable',

		`opening_balance_on`= '$now'

	WHERE `ledger_id` 		= '$ledger_id' LIMIT 1";



		if(mysql_query($sql))

		{

		$type=1;

		$msg='Successfully Updated.';

		}

	}

	else

	{

	$type=0;

	$msg='Given Name('.$ledger_name.') is already exists.';

	}

}



//for Delete..................................



//if(isset($_POST['dledger']))

//{

//$ledger_id = $_REQUEST['ledger_id'];

//$sql="delete from `accounts_ledger` where `ledger_id`='$ledger_id' limit 1";

//$query=mysql_query($sql);

		//$type=1;

		//$msg='Successfully Deleted.';

//}





$ddd="select * from accounts_ledger where ledger_id='$ledger_id'";

$dddd=mysql_query($ddd);

if(mysql_num_rows($dddd)>0)

$data = mysql_fetch_row($dddd);

}

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td><div class="left">

							<table width="100%" border="0" cellspacing="0" cellpadding="0">

								  <tr>

								    <td><div class="box">

									<form id="form1" name="form1" method="post" action="">

									<table width="100%" border="0" cellspacing="2" cellpadding="0">

                                      <tr>

                                        <td width="40%" align="right">Ledger Name : </td>

                                        <td width="60%" align="right"><input name="ladger_name" type="text" id="ladger_name" value="<?=$_REQUEST['ladger_name']; ?>" /></td>

                                      </tr>

                                      <tr>

                                        <td align="right">Ledger Group :                                         </td>

                                        <td align="right">

										<select name="ladger_group" id="ladger_group">
										<option></option>

<? 

if($_SESSION['usergroup']>1)

$sql="SELECT group_id ,group_name FROM ledger_group where group_for=".$_SESSION['usergroup']." order by group_id";

else

$sql="SELECT group_id ,group_name FROM ledger_group order by group_id";

$led=mysql_query($sql);

if(mysql_num_rows($led) > 0)

{

while($ledg = mysql_fetch_row($led)){?>

<option value="<?=$ledg[0]?>" <?php if($_REQUEST['ladger_group']==$ledg[0]) echo " Selected "?>><?=$ledg[0].':'.$ledg[1]?></option>

<? }}?>									</select>

		</td>

                                      </tr>

                                      <tr>

                                        <td colspan="2"><input class="btn" name="search" type="submit" id="search" value="Show" /></td>

                                      </tr>

                                    </table>

								    </form></div></td>

						      </tr>

								  <tr>

									<td>&nbsp;</td>

								  </tr>

								  <tr>

									<td>

									<table id="grp" class="tabledesign" cellspacing="0">

							  <tr>

								<th>A/c Code</th>

								<th>Ledger Name</th>

								<th>Ledger Group</th>

							  </tr>

<?php

if($_SESSION['usergroup']>1)

$rrr = "select l.ledger_id, l.ledger_name, g.group_name from accounts_ledger l, ledger_group g WHERE l.ledger_id like '%00000000' and  l.ledger_group_id=g.group_id and l.group_for= ".$_SESSION['usergroup'];

else

$rrr = "select l.ledger_id, l.ledger_name, g.group_name from accounts_ledger l, ledger_group g WHERE l.ledger_id like '%00000000' and  l.ledger_group_id=g.group_id ";

	

	if(isset($_REQUEST['search']))

	{

		$ladger_group	= mysql_real_escape_string($_REQUEST['ladger_group']);

		$ladger_name	= mysql_real_escape_string($_REQUEST['ladger_name']);

		
if($ladger_group!='')
		$rrr .= " AND  g.group_id LIKE '%$ladger_group%'";

if($ladger_name!='')
		$rrr .= " AND  l.ledger_name LIKE '%$ladger_name%'";				

	} 

	$rrr .= " order by ledger_name";

	$report=mysql_query($rrr);

	//echo $rrr;

	while($rp=mysql_fetch_row($report)){$i++; if($i%2==0)$cls=' class="alt"'; else $cls='';?>

							    <tr<?=$cls?> onclick="DoNav('<?php echo $rp[0];?>');">

								<td><nobr><?=add_separator($rp[0],$separator);?></nobr></td>

								<td><?=$rp[1];?></td>

								<td><?=$rp[2];?></td>

							  </tr>

	<?php }?>

							</table>									</td>

								  </tr>

								</table>



							</div><div id="pageNavPosition"></div></td>

    <td><div class="right"><form id="form2" name="form2" method="post" action="account_ledger2.php?ledger_id=<?php echo $ledger_id;?>">

							  <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                  <td><div class="box">

                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                      <tr>

                                        <td>Ledger  Name:</td>

                                        <td><input name="ledger_name" type="text" id="ledger_name" value="<?php echo $data[1];?>" class="required" tabindex="001" /></td>

									  </tr>



                                      <tr>

                                        <td>Ledger Group  :</td>

                                        <td>

			<select name="ledger_group_id" id="ledger_group_id">

			<? 

			if($_SESSION['usergroup']>1)

			$sql="SELECT group_id ,group_name FROM ledger_group where group_for=".$_SESSION['usergroup']." order by group_id";

			else

			$sql="SELECT group_id ,group_name FROM ledger_group order by group_id";

			$led=mysql_query($sql);

			if(mysql_num_rows($led) > 0)

			{

			while($ledg = mysql_fetch_row($led)){?>

			<option value="<?=$ledg[0]?>" <?php if($data[2]==$ledg[0]) echo " Selected "?>><?=$ledg[0].':'.$ledg[1]?></option>

			<? }}?>

			</select>

			</td>

									  </tr>

                                      <!--<tr>

                                        <td>Opening Balance   :</td>

                                        <td><input name="balance" type="text" id="balance" value="<?php echo $data[3];?>"/></td>

									  </tr>-->

                                      <tr>

                                        <td>Transaction Type : </td>

                                        <td><select name="b_type" id="b_type">

                                          <option value="Debit"<?php if($data[4]=='Debit') echo " Selected "?>>Debit</option>

                                          <option value="Credit"<?php if($data[4]=='Credit') echo " Selected "?>>Credit</option>

                                          <option value="Both"<?php if($data[4]=='Both') echo " Selected "?>>Both</option>

                                        </select></td>

                                      </tr>

                                      <!--<tr>

                                        <td>Depreciation Rate : </td>

                                        <td><input name="d_rate" type="text" id="d_rate" value="<?php echo $data[5];?>"/></td>

                                      </tr>-->

                                      <!--<tr>

                                        <td>Credit Limit :</td>

                                        <td><input name="cr_limit" type="text" id="cr_limit" value="<?php echo $data[6];?>"/></td>

                                      </tr>-->

                                      <tr>

                                        <td>Budget Enable: </td>

                                        <td>

										<select name="budget_enable" id="budget_enable">

										<option value="NO"<?php if($data[9]=='NO') echo " Selected "?>>NO</option>

                                          <option value="YES"<?php if($data[9]=='YES') echo " Selected "?>>YES</option>

                                          

                                        </select>

										</td>

                                      </tr>

                                      <tr>

                                        <td>Opening Balance on:</td>

                                        <td>          <?php 

		if(isset($data[7]))

		{

		?>

          <input name="open_date" type="text" id="open_date" value="<?php echo date("d-m-Y",$data[7]);?>"/>

          <?php	}else	{

		?>

          <input name="open_date" type="text" id="open_date" value="<?php echo date("d-m-Y",time());?>"/>

          <?php

		}

		?></td>

                                      </tr>

                                    </table>

                                  </div></td>

                                </tr>

                                

                                

                                <tr>

                                  <td>&nbsp;</td>

                                </tr>

                                <tr>

                                  <td>

								  <div class="box1">

								  <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                    <tr>

                                      <td><input name="nledger" type="submit" id="nledger" value="Record" onclick="return checkUserName()" class="btn" /></td>

                                      <td><input name="mledger" type="submit" id="mledger" value="Modify" class="btn" /></td>

                                      <td><input name="Button" type="button" class="btn" value="Clear" onClick="parent.location='account_ledger.php'"/></td>

                                      <!---td><? if($_SESSION['user']['level']==10){?><input class="btn" name="dledger" type="submit" id="dledger" value="Delete"/><? }?></td--->

                                    </tr>

                                  </table>

								  </div>								  </td>

                                </tr>

                              </table>

    </form>

							</div></td>

  </tr>

</table>

<script type="text/javascript"><!--

    var pager = new Pager('grp', 20);

    pager.init();

    pager.showPageNav('pager', 'pageNavPosition');

    pager.showPage(1);

//--></script>

