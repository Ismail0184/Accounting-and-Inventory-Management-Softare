<?php

require_once 'support_file.php';

$title='Ledger Group';
$proj_id=$_SESSION['proj_id'];

$unique='group_id';
$unique_field='group_name';
$table='ledger_group';
$page="account_ledger_group.php";

$crud      =new crud($table);
$$unique = $_GET[$unique];


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
    $group_for	= $_SESSION['usergroup'];
    $manual_group_code	= mysql_real_escape_string($_REQUEST['manual_group_code']);
    $group_under	= mysql_real_escape_string($_REQUEST['group_under']);
//end

    if(isset($_POST['ngroup']) && !empty($group_name))
    {
        if(!group_excess($group_name,$manual_group_code))
        {
            $type=0;
            $msg='Given Group Name or Manual Group Code is already exists.';
        } else {

            if(!ledger_excess($group_name))

            {	$type=0;
                $msg='Given Name('.$group_name.') is already exists as Ledger.';
            } else {

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
//					ledger_generate($ledger_id,$group_name,$group_id,'','Both','','', time(),$proj_id);
                $type=1;
                $msg='New Entry Successfully Inserted.';



            }}}





//for Modify..................................



    if(isset($_POST['mgroup']))

    {



        if(group_excess($group_name,$manual_group_code,$group_id))
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


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript"> function DoNav(lk){document.location.href = '<?=$page?>?<?=$unique?>='+lk;}
        function popUp(URL)
        {
            day = new Date();
            id = day.getTime();
            eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=800,left = 383,top = -16');"); }

    </script>
<?php require_once 'body_content.php'; ?>


                    <!-------------------list view ------------------------->
                    <div class="col-md-6 col-sm-12 col-xs-12" style="margin: 0px">
                        <div class="x_panel" >
                            <div class="x_title">
                                <h2>Ledger Group List</h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <? 	$res='select lg.'.$unique.',lg.'.$unique.' as Code,lg.'.$unique_field.',sc.sub_class_name as sub_class,ac.class_name as class from '.$table.' lg,
                                acc_sub_class sc,
                                acc_class ac
                                where
                                lg.group_sub_class=sc.id and 
                                sc.class_id=ac.id
                                 
                                 order by lg.'.$unique;
                                echo $crud->link_report($res,$link);?>
                                <?=paging(10);?>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->






                    <!-------------------list view ------------------------->
                    <div class="col-md-6 col-sm-12 col-xs-12" style="margin: 0px">
                        <div class="x_panel" >
                            <div class="x_title">
                                <h2>Add Ledger Group</h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <form class="form-horizontal form-label-left" id="form2" name="form2" method="post" action="account_ledger_group.php?group_id=<?php echo $group_id;?>" style="font-size: 11px">

                                    <? require_once 'support_html.php';?>






                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Group Name :<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="group_name" style="width:100%" name="group_name" value="<?php echo $data[1];?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>





                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Sub Class<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" name="group_sub_class" id="group_sub_class">
                                                    <option></option>
                                                    <?	$sql="select * from acc_sub_class order by sub_class_name";
                                                    $query=mysql_query($sql);
                                                    while($datas=mysql_fetch_object($query))
                                                    { ?>
                                                        <option <? if($datas->id==$data[10]) echo 'Selected ';?> value="<?=$datas->id?>"><?=$datas->sub_class_name?></option>
                                                    <? } ?>
                                                </select></select></div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Class<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <select class="select2_single form-control" required name="group_class" id="group_class">
                                                <option value=""></option>
                                                <option <? if(substr($data[2],0,1)==substr($asset,0,1)) echo 'Selected ';?>value="<?=$asset?>">Asset</option>
                                                <option <? if(substr($data[2],0,1)==substr($income,0,1)) echo 'Selected ';?>value="<?=$income?>">Income</option>
                                                <option <? if(substr($data[2],0,1)==substr($expense,0,1)) echo 'Selected ';?>value="<?=$expense?>">Expense</option>
                                                <option <? if(substr($data[2],0,1)==substr($liabilities,0,1)) echo 'Selected ';?>value="<?=$liabilities?>">Liabilities</option>
                                            </select></div>
                                    </div>




                                            <?php if($_GET[group_id]){  ?>
                                            <div class="form-group" style="float: right">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input  name="mgroup" type="submit" class="btn btn-success" onclick='return window.confirm("Are you confirm to Update?");' id="mgroup" value="Modify Ledger Group"/>
                                                    </div>
                                                </div>


                                                <? if($_SESSION['userid']==440){?>
                                                    <div class="form-group" style="float: left">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" ></label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <input  name="dgroup" type="submit" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Deleted?");' id="dgroup" Value="Delete"  />
                                                        </div></div>

                                                <? }?>

                                            <?php } else {  ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%"></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <button type="submit" name="ngroup" id="ngroup"  class="btn btn-primary">Add Sub Class</button>
                                                    </div>
                                                </div>
                                            <?php } ?>



                                                </form>
                            </div></div></div>





            </div>
        </div>
    </div>
    <!---page content----->







<?php require_once 'footer_content.php' ?>