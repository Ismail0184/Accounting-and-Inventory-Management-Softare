<?php

session_start();

ob_start();

require "../support/inc.all.php";

$title='Ledger Group';

$proj_id=$_SESSION['proj_id'];

//echo $proj_id;

//var_dump($_SESSION);

if(isset($_REQUEST['group_name'])||isset($_REQUEST['group_id']))

{

//common part.............

$group_id		= mysql_real_escape_string($_REQUEST['group_id']);

$group_name		= mysql_real_escape_string(trim($_REQUEST['group_name']));

$group_name		= str_replace("'","",$group_name);

$group_name		= str_replace("&","",$group_name);

$group_name		= str_replace('"','',$group_name);

$group_class	= mysql_real_escape_string($_REQUEST['group_class']);

$group_sub_class= mysql_real_escape_string($_REQUEST['group_sub_class']);

$group_for	= mysql_real_escape_string($_REQUEST['group_for']);

$manual_group_code	= mysql_real_escape_string($_REQUEST['manual_group_code']);

$group_under	= mysql_real_escape_string($_REQUEST['group_under']);

//end 

if(isset($_POST['ngroup']) && !empty($group_name))

{

	if(!group_redundancy($group_name,$manual_group_code))

	{

	$type=0;

	$msg='Given Group Name or Manual Group Code is already exists.';

	}

	else

	{	

			if(!ledger_redundancy($group_name))

				{

					$type=0;

					$msg='Given Name('.$group_name.') is already exists as Ledger.';

				}

			else

			{

					$group_id=next_group_id($group_class);

					$sql="INSERT INTO `ledger_group` (

					`group_id`,

					`group_name` ,

					`group_class` ,

					`group_sub_class` ,

					`group_under` ,

					`group_for` ,

					`proj_id` ,

					`com_id`,

					`manual_group_code`

					)

					VALUES ('$group_id','$group_name', '$group_class', '$group_sub_class', '$group_under ', '$group_for ', '$proj_id','$com_id','$manual_group_code')";

					//echo $sql;

					$query=mysql_query($sql);

//					$ledger_id=group_ledger_id($group_id);

//					ledger_create($ledger_id,$group_name,$group_id,'','Both','','', time(),$proj_id);

					$type=1;

					$msg='New Entry Successfully Inserted.';

						

			}

	}

}





//for Modify..................................



if(isset($_POST['mgroup']))

{



if(group_redundancy($group_name,$manual_group_code,$group_id))

{	

	$sql="UPDATE `ledger_group` SET 

		`group_name` = '$group_name',

		`group_sub_class` = '$group_sub_class',

		`group_for` = '$group_for',

		manual_group_code='$manual_group_code'

		WHERE `group_id` = $group_id LIMIT 1";

	$qry=mysql_query($sql);

		$type=1;

		$msg='Successfully Updated.';



	}

	else

	{

		$type=0;

		$msg='Given Group Name or Manual Group Code is already exists.';

	}

}

//for Delete..................................



if(isset($_POST['dgroup']))

{



		$sql="delete from `ledger_group` where `group_id`='$group_id' limit 1";

		$query=mysql_query($sql);

		$type=1;

		$msg='Successfully Deleted.';

}







		$ddd="select * from ledger_group where group_id='$group_id' and 1";

		$dddd=mysql_query($ddd);

		if(mysql_num_rows($dddd)>0)

		$data = mysql_fetch_row($dddd);

}

$sql='select * from config_group_class limit 1';

$query=mysql_query($sql);

if(mysql_num_rows($query)>0)

{ 

$g_class=mysql_fetch_object($query);

$asset=$g_class->asset_class;

$income=$g_class->income_class;

$expense=$g_class->expanse_class;

$liabilities=$g_class->liabilities_class;

}

?>

<script type="text/javascript">



function checkUserName()

{	

	var e = document.getElementById('group_name');

	if(e.value=='')

	{

		alert("Invalid Group Name!!!");

		e.focus();

		return false;

	}

	else

	{

		$.ajax({

		  url: 'common/check_entry.php',

		  data: "query_item="+$('#group_name').val()+"&pageid=ledger_group",

		  success: function(data) 

		  	{			

			  if(data=='')

			  	return true;

			  else	

			  	{

				alert(data);

				e.value='';

				e.focus();

				return false;

				}

			}

		});

	}

}

function DoNav(theUrl)

{

	document.location.href = 'ledger_group.php?group_id='+theUrl;

}

</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td><div class="left">

							<table width="100%" border="0" cellspacing="0" cellpadding="0">

								  <tr>

								    <td><div class="box"><form id="form1" name="form1" method="post" action=""><table width="100%" border="0" cellspacing="2" cellpadding="0">

                                      <tr>

                                        <td width="40%" align="right">

		    Class :                                       </td>

                                        <td width="60%" align="right">



<select name="group_class" id="group_class">

<option <? if($_REQUEST['group_class']=='%') echo 'Selected ';?>value="%">All</option>

<option <? if(substr($_REQUEST['group_class'],0,1)==substr($asset,0,1)) echo 'Selected ';?>value="<?=$asset?>">Asset</option>

<option <? if(substr($_REQUEST['group_class'],0,1)==substr($income,0,1)) echo 'Selected ';?>value="<?=$income?>">Income</option>

<option <? if(substr($_REQUEST['group_class'],0,1)==substr($expense,0,1)) echo 'Selected ';?>value="<?=$expense?>">Expense</option>

<option <? if(substr($_REQUEST['group_class'],0,1)==substr($liabilities,0,1)) echo 'Selected ';?>value="<?=$liabilities?>">Liabilities</option>

</select>



										</td>

                                      </tr>

                                      <tr>

                                        <td align="right">Group name :                                         </td>

                                        <td align="right"><input name="ladger_group" type="text" id="ladger_group" value="<?php echo $_REQUEST['ladger_group']; ?>" size="20" /></td>

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

								<th>ID</th>

								<th>Group Name</th>

								<th>Class</th>

							  </tr>

<?php

$rrr = "select group_id, group_name, group_class from ledger_group where 1";

	if($_SESSION['user']['group']>1)

$rrr = "select group_id, group_name, group_class from ledger_group where group_for= ".$_SESSION['user']['group'];





	if(isset($_REQUEST['search']))

	{

		$ladger_group	= mysql_real_escape_string($_REQUEST['ladger_group']);

		$group_class	= mysql_real_escape_string($_REQUEST['group_class']);

	

		$rrr .= " and group_name LIKE '%$ladger_group%'";

		$rrr .= " AND group_class LIKE '%$group_class%'";	

	} 

	$rrr .= " order by group_id";

	$report = mysql_query($rrr);

	$i=0;

	

	while($rp=mysql_fetch_row($report)){$i++; if($i%2==0)$cls=' class="alt"'; else $cls='';?>

							   <tr<?=$cls?> onclick="DoNav('<?php echo $rp[0];?>');">

								<td><?=$rp[0];?></td>

								<td><?=$rp[1];?></td>

								<td><?=group_class($rp[2])?></td>

							  </tr>

	<?php }?>

							</table>

									<div id="pageNavPosition"></div>									</td>

								  </tr>

								</table>



							</div></td>

    <td><div class="right"><form id="form2" name="form2" method="post" action="ledger_group.php?group_id=<?php echo $group_id;?>">

							  <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                  <td><div class="box">

                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                      <tr>

                                        <td>Group  Name :</td>

                                        <td><input name="group_name" type="text" id="group_name" value="<?php echo $data[1];?>" class="required" /></td>

									  </tr>

                                      <tr>

                                        <td>Class :</td>

                                        <td><select name="group_class" id="group_class">

<option <? if(substr($data[2],0,1)==substr($asset,0,1)) echo 'Selected ';?>value="<?=$asset?>">Asset</option>

<option <? if(substr($data[2],0,1)==substr($income,0,1)) echo 'Selected ';?>value="<?=$income?>">Income</option>

<option <? if(substr($data[2],0,1)==substr($expense,0,1)) echo 'Selected ';?>value="<?=$expense?>">Expense</option>

<option <? if(substr($data[2],0,1)==substr($liabilities,0,1)) echo 'Selected ';?>value="<?=$liabilities?>">Liabilities</option>

</select></td>

                                      </tr>

                                      <tr>

                                        <td>Sub Class  :</td>

                                        <td>

                                        <select name="group_sub_class" id="group_sub_class">

                                        <option></option>

                                        <?	$sql="select * from acc_sub_class order by sub_class_name";

											$query=mysql_query($sql);

											while($datas=mysql_fetch_object($query))

										{

										?>

 <option <? if($datas->id==$data[10]) echo 'Selected ';?> value="<?=$datas->id?>"><?=$datas->sub_class_name?></option>

                                        <? } ?>

                                        </select></td>

									  </tr>

                                      <tr>

                                        <td>Concern Group  :</td>

                                        <td>

                                        <select name="group_for" id="group_for">

                                        <?	$sql="select * from user_group order by id";

											$query=mysql_query($sql);

											while($datas=mysql_fetch_object($query))

										{

										?>

 <option <? if($datas->id==$data[9]) echo 'Selected ';?> value="<?=$datas->id?>"><?=$datas->group_name?></option>

                                        <? } ?>

                                        </select></td>

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

              <td><input name="ngroup" type="submit" id="ngroup" value="Record" onclick="return checkUserName()" class="btn" /></td>

              <td><input name="mgroup" type="submit" id="mgroup" value="Modify" class="btn" /></td>

              <td><input name="Button" type="button" class="btn" value="Clear" onClick="parent.location='ledger_group.php'"/></td>

              <td><? if($_SESSION['user']['level']==10){?><input class="btn" name="dgroup" type="submit" id="dgroup" value="Delete"/><? }?></td>

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

    var pager = new Pager('grp', 12);

    pager.init();

    pager.showPageNav('pager', 'pageNavPosition');

    pager.showPage(1);

//--></script>

<script type="text/javascript">

	document.onkeypress=function(e){

	var e=window.event || e

	var keyunicode=e.charCode || e.keyCode

	if (keyunicode==13)

	{

		return false;

	}

}

</script>

<?

$main_content=ob_get_contents();

ob_end_clean();

include ("../template/main_layout.php");

?>