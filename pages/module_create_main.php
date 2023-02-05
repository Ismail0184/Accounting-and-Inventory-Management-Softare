<?php
require_once 'support_file.php';
$title='Main Module Create';
$now=time();
$unique='id';
$unique_field='zonecode';
$table='zone_main';
$page="module_create_main.php";
$crud      =new crud($table);

$jv_no=mysqli_query($conn, "SELECT MAX(zonecode) AS MAXCODE FROM zone_main");
$jv_noROW=mysqli_fetch_array($jv_no);
$zonecodeN=$jv_noROW[MAXCODE]+1;
$zonecodeNEXT=$zonecodeN;

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))
{
$$unique = $_POST[$unique];
if(isset($_POST['record']))
{
    $crud->insert();
    $type=1;
    $msg='New Entry Successfully Inserted.';
    unset($_POST);
    unset($$unique);

}}}
$resultss=mysqli_query($conn, "Select * from $table order by $unique ");
?>
<?php require_once 'header_content.php'; ?>
    <style>
        input[type=text]{
            font-size: 11px;    }
    </style>
<?php require_once 'body_content.php'; ?>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo $title; ?></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <?php
                                $initiate=$_POST[addpre];
                                $d =$_POST[ps_date];
                                $ps_date=date('Y-m-d' , strtotime($d));
                                $invoice=$_POST[invoice];
                                $billno=$_POST[billno];
                                $enat=date('Y-m-d h:s:i');
                                if(isset($initiate)){
                                    $insert=mysqli_query($conn,"INSERT INTO PARAMETERS (PARAMETERS_CODE,PARAMETERS_Name)  VALUES ('$_POST[PARAMETERS_CODE]','$_POST[PARAMETERS_Name]')");
                                    $_SESSION[initiate_daily_production]=$invoice;
                                    $_SESSION[pr_no] =getSVALUE("production_floor_receive_master", "pr_no", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
                                    ; ?>
                                    <meta http-equiv="refresh" content="0;PARAMETERS.php">
                                <?php } if(isset($_POST[Finish])){ ?>
                                    <meta http-equiv="refresh" content="0;item_specifications.php">
                                <?php } ?>

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                                    <? require_once 'support_html.php';?>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Under Module<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" id="module"  name="module">
                                                    <option></option>
                                                    <?php foreign_relation('module_department', 'id', 'CONCAT(id," : ", module_short_name)', $module, 'status in (\'1\')'); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display: none">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Module Code<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="zonecode" style="width:100%"  required readonly  name="zonecode" value="<?=$zonecodeNEXT?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Module Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="zonename" style="width:100%"  required  name="zonename" value="" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Module Details<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="zonedetails" style="width:100%"   name="zonedetails" value="<?=$data->zonedetails;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Module URL<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="url" style="width:100%"   name="url" value="<?=$data->zonedetails;?>" class="form-control col-md-7 col-xs-12" >
                                        </div>
                                    </div>


                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_GET[mood]){  ?>
                                                <button type="submit" name="updatePS" class="btn btn-success">Update Module Information</button>
                                            <?php   } else {?>
                                                <button type="submit" name="record"  class="btn btn-primary">Create Module</button>
                                            <?php } ?>
                                        </div></div>

                                </form></div></div></div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo 'MODULE LIST' ; ?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                                    <thead>
                                    <tr>
                                        <th style="width: 2%">#</th>
                                        <th>MODULE CODE</th>
                                        <th>MODULE NAME</th>
                                        <th>MODULE DETAILS</th>
                                        <th>POWER BY</th>
                                        <th>CREATE DATE</th>
                                    </tr>
                                    </thead>


                                    <tbody>
                                    <?php
                                    while ($rows=mysqli_fetch_array($resultss)){
                                        $i=$i+1;
                                        $link=$_SERVER['REQUEST_URI'].'?id='.$rows[zonecode]; ?>
                                        <tr style="font-size:11px">
                                            <th style="text-align:center"><?php echo $i; ?></th>
                                            <td><a href="<?php echo $link; ?>" target="_new"><?php echo $rows[zonecode]; ?></a></td>
                                            <td onclick="OpenPopupCenter('module_create_main.php?<?php echo 'id='.$rows[zonecode].'&mood=editmood' ?>', 'TEST!?', 900, 600);"><?php echo $rows[zonename]; ?></td>
                                            <td><a href="<?php echo $link; ?>"><?php echo $rows[zonedetails]; ?></a></td>
                                            <td><?=getSVALUE("user_activity_management", "fname", " where user_id='$rows[power_by]'");?></td>
                                            <td><?php echo $rows[created_at]; ?></td>

                                        </tr>
                                    <?php } ?></tbody></table>

                            </div></div></div>
<?php require_once 'footer_content.php' ?>