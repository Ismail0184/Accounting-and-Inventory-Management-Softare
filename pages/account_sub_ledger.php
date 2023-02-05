<?php

require_once 'support_file.php';
$title='Sub Ledger';
$proj_id=$_SESSION['proj_id'];

$now=time();

$separator	= $_SESSION['separator'];

if(isset($_REQUEST['name'])||isset($_REQUEST['id']))

{

    //common part.............



    $id=$_REQUEST['id'];
    //echo $ledger_id;
    $name		= mysql_real_escape_string($_REQUEST['name']);
    $name		= str_replace("'","",$name);
    $name		= str_replace("&","",$name);
    $name		= str_replace('"','',$name);
    $under		= mysql_real_escape_string($_REQUEST['under']);
    $balance	= mysql_real_escape_string($_REQUEST['balance']);
    //end

    if(isset($_POST['nledger']))

    {

        $check="select sub_ledger_id from sub_ledger where sub_ledger='$name'";
        if(mysql_num_rows(mysql_query($check))>0)

        {   $aaa=mysql_num_rows(mysql_query($check));
            $ledger_id=$aaa[0];
            $type=0;
            $msg='Given Name('.$name.') is already exists.';

        } else {
            $sql_check="select ledger_group_id, balance_type, budget_enable from accounts_ledger where ledger_id='".$under."' limit 1";
            $sql_query=mysql_query($sql_check);
            if(mysql_num_rows($sql_query)>0){
                $ledger_data=mysql_fetch_row($sql_query);
                if(!ledger_excess($name))
                {

                    $type=0;
                    $msg='Given Name('.$name.') is already exists as Ledger.';

                }  else  {

                    $sub_ledger_id=number_format(next_sub_ledger_id($under), 0, '.', '');
                    sub_ledger_generate($sub_ledger_id,$name, $under, $balance, $now, $proj_id);
                    ledger_generate($sub_ledger_id,$name,$ledger_data[0],'',$ledger_data[1],'','', time(),$proj_id,$ledger_data[2]);
                    $type=1;
                    $msg='New Entry Successfully Inserted.';
                } }  else  {

                $type=0;
                $msg='Invalid Accounts Ledger!!!';
            }}}



//for Modify..................................



    if(isset($_POST['mledger']))

    {
        $search_sql="select 1 from sub_ledger where `sub_ledger`!='$id' and `sub_ledger` = '$name' limit 1";
        if(mysql_num_rows(mysql_query($search_sql))==0)
        {

            $sql_check="select ledger_id from accounts_ledger where ledger_id=".$under;
            $sql_query=mysql_query($sql_check);
            if(mysql_num_rows($sql_query)==1){
                $id=$_REQUEST['id'];
                $sql2="UPDATE `accounts_ledger` SET 
		`ledger_name` 		= '$name'
			WHERE `ledger_id` 		='$id' LIMIT 1";
                $sql="UPDATE `sub_ledger` SET
		`sub_ledger` = '$name'
		WHERE `sub_ledger_id` =$id LIMIT 1";
                $query=mysql_query($sql);
                $query=mysql_query($sql2);
                $type=1;
                $msg='Successfully Updated.';
            }  else  {
                $type=0;
                $msg='Invalid Accounts Ledger!!!';
            }
            //echo $sql;
        }  else {
            $type=0;
            $msg='Given Name('.$name.') is already exists.';

        }}



    //if(isset($_POST['dledger']))
//{
//$id=$_REQUEST['id'];
//$sql="delete from `sub_ledger` where `sub_ledger_id`='$id' limit 1";
//$query=mysql_query($sql);
//$sql="delete from `accounts_ledger` where `ledger_id`='$id' limit 1";
//$query=mysql_query($sql);
    //$type=1;
    //$msg='Successfully Deleted.';
//}
    $ddd="select * from sub_ledger where sub_ledger_id='$id'";
    $data=mysql_fetch_row(mysql_query($ddd));

}

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>

        <!-- Select2 -->
        <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
        <!-- jQuery custom content scroller -->
        <link href="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
        <!-- Bootstrap -->
        <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
        <!-- bootstrap-daterangepicker -->
        <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
        <!-- Custom Theme Style -->
        <link href="../build/css/custom.min.css" rel="stylesheet">

        <script type="text/javascript">
            function DoNav(theUrl)
            { document.location.href = '<?=$page?>?id='+theUrl;  }

        </script>
    </head>



<body class="nav-md">
<div class="container body">
    <div class="main_container">
    <div class="col-md-3 left_col menu_fixed">
        <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
                <a href="<?php echo $webiste; ?>" class="site_title"><i class="fa fa-paw"></i> <span>ICPBD</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <?php include ("pro.php");  ?> <br />
            <!-- /menu profile quick info -->


            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                <?php include("sidebar_menus.php"); ?>
            </div>
            <!-- /sidebar menu -->



            <!-- /menu footer buttons -->
            <?php include("menu_footer.php"); ?>
            <!-- /menu footer buttons -->

        </div>
    </div>


    <!-- top navigation -->
    <div class="top_nav">
        <?php include("top.php"); ?>
    </div>

    <!-- /top navigation -->



    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="row">




                <!-------------------list view ------------------------->
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>List of <?=$title;?></h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 12px">
                                <thead>
                                <tr>
								<th>Sub Ledger Id</th>
								<th>Sub Ledger</th>
								<th>A/C Ledger</th>
							    </tr>
                                </thead>

                                <tbody>

<?php



		if($_SESSION['usergroup']>1)
$rrr="select a.sub_ledger, b.ledger_name, c.group_name, a.sub_ledger_id FROM sub_ledger a,accounts_ledger b,ledger_group c where a.ledger_id=b.ledger_id and b.ledger_group_id=c.group_id and c.group_for=".$_SESSION['usergroup'];
else
$rrr="select a.sub_ledger, b.ledger_name, c.group_name, a.sub_ledger_id FROM sub_ledger a,accounts_ledger b,ledger_group c where a.ledger_id=b.ledger_id and b.ledger_group_id=c.group_id";

	if(isset($_REQUEST['search']))
	{
		$ladger_group	= mysql_real_escape_string($_REQUEST['ladger_group']);
		$ladger_name	= mysql_real_escape_string($_REQUEST['ladger_name']) ;

if($ladger_name!='')
		$rrr .= " AND b.ledger_name LIKE '%$ladger_name%'";
		//$rrr .= " AND c.group_name LIKE '%$ladger_group%'";

if($_REQUEST['sub_ladger']!='')
{
if(is_numeric($_REQUEST['sub_ladger']))
$rrr.=' and a.sub_ledger_id='.$_REQUEST['sub_ladger'];
else
$rrr.=' and a.sub_ledger like "%'.$_REQUEST['sub_ladger'].'%"';
}}	$rrr .= "  order by sub_ledger_id";
//echo $rrr;
	$report=mysql_query($rrr);
	while($rp=mysql_fetch_row($report))
	{$i++; if($i%2==0)$cls=' class="alt"'; else $cls='';?>
							   <tr<?=$cls?> onclick="DoNav('<?php echo $rp[3];?>');">
				 				<td><nobr><?=add_separator($rp[3],$separator);?></nobr></td>
								<td><?=$rp[0];?></td>
								<td><?=$rp[1];?></td>
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
                            <h2>Add New Sub Ledger</h2>
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
                            <form id="form2" name="form2" class="form-horizontal form-label-left" method="post" action="account_sub_ledger.php?id=<?php echo $id;?>">
                                <?require_once 'support_html.php';?>


                                <div class="form-group" style="width: 100%">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Sub Ledger:<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="name"  required="required" name="name" value="<?php echo $data[1];?>" class="form-control col-md-7 col-xs-12" style="width: 100%; font-size: 12px" >
                                 </div></div>


                                <div class="form-group" style="width: 100%">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Under Ledger<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" name="under" id="under" style="width: 100%; font-size: 12px">
                                            <option value=""></option>
                                            <?
                                            $sql="select l.ledger_id, l.ledger_name, g.group_name from accounts_ledger l, ledger_group g WHERE l.ledger_id like '%00000000' and  l.ledger_group_id=g.group_id and l.group_for= ".$_SESSION['usergroup'];

                                            $led=mysql_query($sql);
                                            if(mysql_num_rows($led) > 0)
                                            {
                                                while($ledg = mysql_fetch_row($led)){?>
                                                    <option value="<?=$ledg[0]?>" <?php if($data[2]==$ledg[0]) echo " Selected "?>><?=$ledg[0].':'.$ledg[1]?></option>
                                                <? }}?>
                                        </select></div></div>


                                <tr align="center">
                                    <?php if($_GET[id]){ ?>
                                        <td><button type="submit" name="mledger" id="mledger" class="btn btn-success">Modify</button></td>
                                        <? if($_SESSION['userid']==1001900){?>
                                            <td><button type="submit" name="dledger" id="dledger" class="btn btn-delete">Delete</button></td>
                                        <? }?>
                                    <?php } else { ?>
                                        <td align="center"><button type="submit" name="nledger" id="nledger" onclick="return checkUserName()" class="btn btn-success">Record</button></td>
                                    <?php } ?>

                                </tr>
                                </table>
                            </form>
                        </div></div></div>
                <!-- input section-->
            </div>
        </div>
    </div>
    <!---page content----->




 <!-- jQuery custom content scroller -->
<?php require_once 'footer_content.php' ?>