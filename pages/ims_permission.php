 <?php
require_once 'support_file.php';
$title="IMS Permission";

$now=time();
$unique='PBI_ID';
$unique_field='status';
$table="IMS_entry_permission";
$page="ims_permission.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }
    
    
//for modify..................................
if(isset($_POST['modify']))
{
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $crud->update($unique);
    $type=1;
    //echo $targeturl;
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}}}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>



<?php require_once 'header_content.php'; ?>
 <script type="text/javascript">
     function DoNavPOPUP(lk)
     {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 250,top = -1");}
 </script>
<?php require_once 'body_content.php'; ?>



                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
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

                                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>


                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">TSM Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select style="width: 100%" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
                                                <option></option>
                                                <?php
                                                $res=mysql_query("SELECT p.*,des.*

                                    FROM
                                    personnel_basic_info p,
                                    designation des
                                    where
                                    p.PBI_JOB_STATUS in ('In Service') and
                                    p.PBI_DESIGNATION=des.DESG_ID and
                                    p.PBI_DESIGNATION in ('56','57','102')
                                    ");
                                                while($data=mysql_fetch_object($res)){  ?>
                                                    <option  value="<?=$data->PBI_ID; ?>" <?php if($PBI_ID==$data->PBI_ID) echo 'selected' ?>><?=$data->PBI_ID_UNIQUE; ?>#><?=$data->PBI_NAME;?>#> (<?=$data->DESG_DESC;?>)</option>
                                                <?php } ?></select>
                                        </div></div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Status<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select style="width: 100%" class="select2_single form-control" name="status" id="status">
                                                <option value="1">YES</option>
                                                <option value="0">NO</option>
                                                </select>
                                    </div></div>
                                    
                                    <br><br><br>

                                        <br>
                                        <?php if($_GET[$unique]){  ?>                                            
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" class="btn btn-success">Modify</button>
                                            </div></div>
                                            <? if($_SESSION['userid']=="10019"){?>                                            
                                             <div class="form-group" style="margin-left:40%;">
                                             <div class="col-md-6 col-sm-6 col-xs-12">
                                             <input  name="delete" type="submit" class="btn btn-danger" id="delete" value="Delete"/>
                                             </div></div>                                             
                                             <? }?>                                         
                                            <?php } else {?>                                           
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  class="btn btn-success">Add New </button>
                                            </div></div>                                                                                        
                                            <?php } ?> 


                                </form>
                                </div>
                                </div>
                                </div>

                    <?php if(!isset($_GET[$unique])){ ?>
                    <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>List of <?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <table style="width:100%; font-size: 12px" class="table table-striped table-bordered">
                                    <thead><tr>
                                        <th>#</th>
                                        <th>In-charge person</th>
                                        <th style="text-align: center">IMS Entry Permission</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    $ims_date=find_a_field('ims_date','ims_date','month='.$currentmonth.' and year='.$currentyear.'');


                                    $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                                    $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                                    $res=mysql_query("SELECT p.*,des.*                                 
                                    
                                    FROM 
                                    personnel_basic_info p,
                                    designation des
                                    where 
                                    p.PBI_JOB_STATUS in ('In Service') and 
                                    p.PBI_DESIGNATION=des.DESG_ID and 
                                    p.PBI_DESIGNATION in ('56','57','102')
                                    ");
                                    while($data=mysql_fetch_object($res)){
                                        $ims_permission=find_a_field('IMS_entry_permission','status','PBI_ID='.$data->PBI_ID.'');
                                        ?>
                                        <tr  onclick="DoNavPOPUP('<?=$data->$unique;?>', 'TEST!?', 600, 700)">
                                            <td><?=$i=$i+1;?></td>
                                            <td><?=$data->PBI_ID;?> # <?=$data->PBI_ID_UNIQUE;?> # <?=$data->PBI_NAME;?></td>
                                            <td style="text-align: center; <?php if ($ims_permission=='1') {?> background-color: darkturquoise; color: white  <?php } ?>"><?php if ($ims_permission=='1') echo 'YES'; else echo 'NO';?></td>


                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div></div>
                    <!-------------------End of  List View --------------------->
                    <?php } ?>
                    <!---page content----->


                
        
<?php require_once 'footer_content.php' ?>