<?php

require_once 'support_file.php';
$title='Account Ledger';
$proj_id=$_SESSION['proj_id'];
$now=time();

$separator	= $_SESSION['separator'];
if(isset($_REQUEST['ledger_name'])||isset($_REQUEST['ledger_id']))

{

	//common part.............
	$ledger_id			= mysqli_real_escape_string($conn, $_REQUEST['ledger_id']);
	$ledger_name 		= mysqli_real_escape_string($conn, $_REQUEST['ledger_name']);
	$ledger_name		= str_replace("'","",$ledger_name);
	$ledger_name		= str_replace("&","",$ledger_name);
	$ledger_name		= str_replace('"','',$ledger_name);
	$ledger_group_id	= mysqli_real_escape_string($conn, $_REQUEST['ledger_group_id']);
	$opening_balance	= mysqli_real_escape_string($conn, $_REQUEST['balance']);
	$balance_type		= mysqli_real_escape_string($conn, $_REQUEST['b_type']);
	$depreciation_rate	= mysqli_real_escape_string($conn, $_REQUEST['d_rate']);
	$credit_limit		= mysqli_real_escape_string($conn, $_REQUEST['cr_limit']);
	$date				= mysqli_real_escape_string($conn, $_REQUEST['open_date']);
	$now				= date_value($date);
	$budget_enable		= mysqli_real_escape_string($conn, $_REQUEST['budget_enable']);

	//end

	if(isset($_POST['nledger']))
	{
	if(!ledger_excess($ledger_name))
	{
	$type=0;
	$msg='Given Name('.$ledger_name.') is already exists.';
	} else {
	$ledger_id=approximate_ledger_id($ledger_group_id);
	 if(ledger_generate($ledger_id,$ledger_name,$ledger_group_id,$opening_balance,$balance_type,$depreciation_rate,$credit_limit, $now,$proj_id,$budget_enable))

		{
		    $type=1;
		    $msg='New Entry Successfully Inserted.';

		}

	}

}





//for Modify..................................



if(isset($_POST['mledger']))

{

if(ledger_excess($ledger_name,$ledger_id))

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

		if(mysqli_query($conn, $sql))

		{
		$type=1;
		$msg='Successfully Updated.';

		} } else {
    $type=0;
	$msg='Given Name('.$ledger_name.') is already exists.';

	}

}



//for Delete..................................



//if(isset($_POST['dledger']))

//{

//$ledger_id = $_REQUEST['ledger_id'];

//$sql="delete from `accounts_ledger` where `ledger_id`='$ledger_id' limit 1";

//$query=mysqli_query($conn, $sql);

		//$type=1;

		//$msg='Successfully Deleted.';

//}





$ddd="select * from accounts_ledger where ledger_id='".$_GET[ledger_id]."'";
$dddd=mysqli_query($conn, $ddd);
if(mysqli_num_rows($dddd)>0)
$data = mysqli_fetch_row($dddd);

}

?>


<?php require_once 'header_content.php'; ?>
        <script type="text/javascript">
            function DoNav(theUrl)
            { document.location.href = '<?=$page?>?ledger_id='+theUrl;  }
        </script>
<?php require_once 'body_content.php'; ?>


        <!-------------------list view ------------------------->
        <div class="col-md-7 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>List of <?=$title;?></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>


                        <tr>
                            <th>A/c Code</th>
                            <th>Ledger Name</th>
                            <th>Ledger Group</th>
                        </tr></thead>

                        <tbody>

                        <?php

                        if($_SESSION['usergroup']>1)
                            $rrr = "select l.ledger_id, l.ledger_name, g.group_name from accounts_ledger l, ledger_group g WHERE l.ledger_id like '%00000000' and  l.ledger_group_id=g.group_id and l.group_for= ".$_SESSION['usergroup'];
                        else
                            $rrr = "select l.ledger_id, l.ledger_name, g.group_name from accounts_ledger l, ledger_group g WHERE l.ledger_id like '%00000000' and  l.ledger_group_id=g.group_id ";


                        if(isset($_REQUEST['search']))
                        {   $ladger_group	= mysqli_real_escape_string($_REQUEST['ladger_group']);
                            $ladger_name	= mysqli_real_escape_string($_REQUEST['ladger_name']);
                            if($ladger_group!='')
                                $rrr .= " AND  g.group_id LIKE '%$ladger_group%'";
                            if($ladger_name!='')
                                $rrr .= " AND  l.ledger_name LIKE '%$ladger_name%'";

                        }

                        $rrr .= " order by ledger_name";
                        $report=mysqli_query($conn, $rrr);
                        //echo $rrr;
                        while($rp=mysqli_fetch_row($report)){$i++; if($i%2==0)$cls=' class="alt"'; else $cls='';?>
                        <tr<?=$cls?> onclick="DoNav('<?php echo $rp[0];?>');">
                            <td><nobr><?=add_separator($rp[0],$separator);?></nobr></td>
                            <td><?=$rp[1];?></td>
                            <td><?=$rp[2];?></td>
                            </tr><?php }?>
                        </tbody>
                    </table>
                </div>

            </div></div>
        <!-------------------End of  List View --------------------->






        <!-- input section-->
        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add New Ledger</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <div class="input-group pull-right">
                            <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                                <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                            </a-->
                        </div>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />

                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post"  style="font-size: 11px">
                        <?require_once 'support_html.php';?>


                        <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Ledger  Name<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="ledger_name"  required="required" name="ledger_name" value="<?php echo $data[1];?>" class="form-control col-md-7 col-xs-12" style="width: 100%" >
                            </div></div>




                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Ledger Group<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control" name="ledger_group_id" id="ledger_group_id">
                                        <?
                                        if($_SESSION['usergroup']>1)
                                            $sql="SELECT group_id ,group_name FROM ledger_group where group_for=".$_SESSION['usergroup']." order by group_id";
                                        else
                                            $sql="SELECT group_id ,group_name FROM ledger_group order by group_id";
                                        $led=mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($led) > 0)
                                        {
                                            while($ledg = mysqli_fetch_row($led)){?>
                                                <option value="<?=$ledg[0]?>" <?php if($data[2]==$ledg[0]) echo " Selected "?>><?=$ledg[0].':'.$ledg[1]?></option>
                                            <? }}?>
                                    </select></div></div>



                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Transaction Type<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" name="b_type" id="b_type">
                                    <option value="Debit"<?php if($data[4]=='Debit') echo " Selected "?>>Debit</option>
                                    <option value="Credit"<?php if($data[4]=='Credit') echo " Selected "?>>Credit</option>
                                    <option value="Both"<?php if($data[4]=='Both') echo " Selected "?>>Both</option>
                                </select>
                            </div></div>


                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Opening Balance on<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if(isset($data[7])) { ?>
                                    <input name="open_date" type="text" id="open_date" value="<?php echo date("d-m-Y",$data[7]);?>" class="form-control col-md-7 col-xs-12" style="width: 130px" />
                                <?php } else	{ ?>
                                    <input name="open_date" type="text" id="open_date" value="<?php echo date("d-m-Y",time());?>" class="form-control col-md-7 col-xs-12" style="width: 130px" />
                                    <?php } ?>
                            </div></div>


                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Opening Balance<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="balance"  name="balance" value="<?php echo $data[3];?>" class="form-control col-md-7 col-xs-12" style="width: 130px" >
                            </div></div>



                            <div class="form-group" style="width: 100%;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Depreciation Rate<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="d_rate"   name="d_rate" value="<?php echo $data[5];?>" class="form-control col-md-7 col-xs-12" style="width: 130px" >
                            </div></div>


                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Credit Limit<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="cr_limit"   name="cr_limit" value="<?php echo $data[6];?>" class="form-control col-md-7 col-xs-12" style="width: 130px" >
                            </div></div>


                            <div class="form-group" style="width: 100%">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Budget Enable<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" name="budget_enable" id="budget_enable">
                                    <option value="NO"<?php if($data[9]=='NO') echo " Selected "?>>NO</option>
                                    <option value="YES"<?php if($data[9]=='YES') echo " Selected "?>>YES</option>
                                </select>
                            </div></div>





                                                <?php if($_GET[ledger_id]){ ?>
                                                <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%"></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12"><button type="submit" name="mledger" id="mledger" class="btn btn-success">Modify</button>
                                                        </div></div>
                                                <?php } else { ?>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%"></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <button type="submit" name="nledger" id="nledger" onclick="return checkUserName()" class="btn btn-primary">Record</button>
                                                        </div></div>
                                                <?php } ?>
                                                <? if($_SESSION['userid']==440){?>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%"></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12"><button type="submit" name="dledger" id="dledger" class="btn btn-danger">Delete</button>
                                                        </div></div>
                                                <? }?>


                    </form>
                </div></div></div>
        <!-- input section-->
    </div>
    </div>
    </div>
    <!---page content----->






    <!-- jQuery custom content scroller -->

<?php require_once 'footer_content.php' ?>