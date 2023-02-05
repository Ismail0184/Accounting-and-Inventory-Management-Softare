<?php
require_once 'support_file.php';
$title="Proforma Invoice";
$now=time();
$table="lc_pi_master";
$unique = 'id';   // Primary Key of this Database table
$table_details = 'lc_pi_details';
$table_fg_deatils = 'lc_pi_fg_details';
$details_unique = 'pi_id';
$page="LC_create_PI.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];


if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {   $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST['status'] = 'MANUAL';
        $_SESSION['initiate_lc_proforma_invoice']=$_POST[$unique];
        $crud->insert();
        $type=1;
        unset($_POST);
        unset($$unique);}


//for modify..................................
    if(isset($_POST['modify']))
    {   $sd=$_POST[pi_issue_date];
        $_POST[pi_issue_date]=date('Y-m-d' , strtotime($sd));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
    }

    if(isset($_POST['add']))
    {   $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST[status] = "MANUAL";
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST[fg_id]=$_POST[fg_id];
        $_POST[fg_line_id]=$_POST[fg_line_id];
        $_POST[pi_id] = $_SESSION['initiate_lc_proforma_invoice'];
        $crud = new crud($table_details);
        $crud->insert();
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
        unset($_SESSION[fgid]);
    }}
if (isset($_POST['confirm'])){
    mysqli_query($conn,"Update ".$table." set status='UNCHECKED' where ".$unique."=".$_SESSION['initiate_lc_proforma_invoice']."");
    mysqli_query($conn, "Update ".$table_details." set status='UNCHECKED' where pi_id=".$_SESSION['initiate_lc_proforma_invoice']."");
    unset($_SESSION['initiate_lc_proforma_invoice']);
    unset($_POST);
    unset($$unique);
}

$results=mysqli_query($conn,"Select d.*,i.* from ".$table_details." d,item_info i where
d.item_id=i.item_id and
d.".$details_unique."='$_SESSION[initiate_lc_proforma_invoice]'");
while($row=mysqli_fetch_array($results)){
    $ids=$row[id];
    if(isset($_POST['deletedata'.$ids]))
    {$del="DELETE FROM ".$table_details." WHERE id='$ids'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);}
    if(isset($_POST['editdata'.$ids]))
    {  mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST[item_id]."', qty='".$_POST[qty]."',rate='".$_POST[rate]."',amount='".$_POST[amount]."' WHERE id=".$ids));
        unset($_POST);
    }
}

if(isset($_POST['cancel']))
{   $crud = new crud($table);
    $condition=$unique."=".$_SESSION['initiate_lc_proforma_invoice'];
    $crud->delete($condition);
    $crud = new crud($table_details);
    $condition = $details_unique . "=" . $_SESSION['initiate_lc_proforma_invoice'];
    $crud->delete_all($condition);
    $crud = new crud($table_fg_deatils);
    $condition = $details_unique . "=" . $_SESSION['initiate_lc_proforma_invoice'];
    $crud->delete_all($condition);
    unset($_SESSION['initiate_lc_proforma_invoice']);

}
if (isset($_GET[id])) {
    $edit_value=find_all_field(''.$table_details.'','','id='.$_GET[id].'');
}
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)','pi_id='.$_SESSION['initiate_lc_proforma_invoice'].'');


// data query..................................
if(isset($_SESSION[initiate_lc_proforma_invoice]))
{   $condition=$unique."=".$_SESSION[initiate_lc_proforma_invoice];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

    if(isset($_POST["Import"])){
            echo $filename=$_FILES["file"]["tmp_name"];
            if($_FILES["file"]["size"] > 0)
            {
                $file = fopen($filename, "r");
                while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
                {   //It wiil insert a row to our subject table from our csv file`
            if(is_numeric($emapData[0])) {
            $sql = "INSERT INTO ".$table_details." (`fg_line_id`,`pi_issue_date`,`item_id`,`pi_id`,`qty`,`rate`,`amount`,`party_id`,`status`,`section_id`,`company_id`)
            VALUES('1','$pi_issue_date','$emapData[0]','$_SESSION[initiate_lc_proforma_invoice]','$emapData[1]','$emapData[2]','$emapData[3]','$party_id','UNCHECKED','$_SESSION[sectionid]','$_SESSION[companyid]')";
                    }
                    $result = mysqli_query( $conn, $sql);
                    if(! $result )
                    {
                        echo "<script type=\"text/javascript\">
              alert(\"Invalid File:Please Upload CSV File.\");
              window.location = ".$page."
            </script>";
                    }}
                fclose($file);
                echo "<script type=\"text/javascript\">
            alert(\"CSV File has been successfully Imported.\");
            window.location = ".$page."
          </script>";
            }header("Location: ".$page."");}

$rs_details=mysqli_query($conn,"Select m.*,
d.id as did,
SUM(d.fg_qty) as fg_qty,
SUM(d.fg_amount) as fg_amount,
d.fg_rate,
d.fg_id,
i.*,c.*
from
lc_pi_fg_details d,
item_info i,
lc_pi_master m,
currency c
  where
 m.id=d.pi_id and
 m.currency=c.id and
 d.fg_id=i.item_id and
 d.pi_id='".$_SESSION['initiate_lc_proforma_invoice']."' group by d.fg_id,d.fg_rate order by d.fg_id");

$PCOUNT=find_a_field('lc_pi_details','COUNT(id)','pi_id='.$_SESSION[initiate_lc_proforma_invoice].'');

$sql="Select d.id,i.item_id,i.item_name,i.unit_name,d.qty,d.rate,d.amount from ".$table_details." d,item_info i where
d.item_id=i.item_id and
d.pi_id='$_SESSION[initiate_lc_proforma_invoice]'";
$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')')
                            FROM
                            item_info i,
							item_sub_group sg,
							item_group g
							WHERE
							i.sub_group_id=sg.sub_group_id and
							 sg.group_id=g.group_id   order by i.item_name";
?>

<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
    function reload(form){
        var val=form.item_id.options[form.item_id.options.selectedIndex].value;
        self.location='<?=$page;?>?item_code_GET=' + val ;}
</script>
<script src="js/vendor/modernizr-2.8.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content.php'; ?>

<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right">
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form action="" enctype="multipart/form-data" style="font-size: 11px" method="post" name="addem" id="addem" >
                <? require_once 'support_html.php';?>
                <table style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:50%;">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">PI ID :<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="<?=$unique?>"  required="required" name="<?=$unique?>" value="<?
                                    $pids=find_a_field($table,'max('.$unique.')','1');
                                    if($_SESSION['initiate_lc_proforma_invoice']>0) {
                                        $pid = $_SESSION['initiate_lc_proforma_invoice'];
                                    } else {
                                        $pid=$pids+1;
                                        if($pids<1) $pid = 1;
                                    }
                                    echo $pid; ?>" readonly style="width:100%; height: 30px; font-size: 11px" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div></td>


                        <td style="width:50%">
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">PI NO :</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?
                                    $dates=date('Y-m-d');
                                    $pi_ids =  find_a_field('lc_pi_master','COUNT(id)','create_date="'.$dates.'"');
                                    if($_SESSION['initiate_lc_proforma_invoice']>0) {
                                        $pi_id = $pi_id;
                                    } else {
                                        $pi_id=$pi_ids+1;
                                        if($pi_ids<1) $pi_id = 1;
                                    }
                                    $project=find_a_field('project_info','proj_id','1');
                                    $date=date('Y').date('m').date('d');
                                    ?>
                                    <input type="text" id="pi_no" class="form-control col-md-7 col-xs-12"  required="required" name="pi_no" value="<?=($pi_no=='')?''.$project.'/PI/'.$date.'/'.$pi_id:$pi_no;?>"  style="width:100%;height: 30px; font-size: 11px" >      </div>
                            </div>
                        </td></tr>


                    <!tr>
                        <td>
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">PI Issue date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" required="required" name="pi_issue_date" value="<?=$pi_issue_date;?>" class="form-control col-md-7 col-xs-12"  style="width:100%; height: 30px; font-size: 11px" >      </div>
                            </div>
                        </td>

                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Party Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control" style="width:100%; font-size: 11px; height: 30px" tabindex="-1" required="required"  name="party_id" id="party_id">
                                        <option></option>
                                        <?php foreign_relation('lc_buyer', 'party_id', 'concat(party_id," : ", buyer_name)', $party_id, '1','order by buyer_name'); ?>
                                    </select>
                                </div></div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="form-group" style="width: 100%">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Currency<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="select2_single form-control" style="width:100%;height: 30px; font-size: 11px" tabindex="-1"  name="currency" id="currency">
                                        <option></option>
                                        <?php foreign_relation('currency', 'id', 'concat(country, " : ", currency," : ",code, " : ", symbol)', $currency, '1','order by id'); ?>
                                    </select>

                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width:40%">Remarks</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="remarks"  name="remarks" value="<?=$remarks;?>" class="form-control col-md-7 col-xs-12" style="width:100%;height: 30px; font-size: 11px;" >
                                </div></div>
                        </td>
                    </tr>
                </table>
                <div class="form-group" style="margin-left:40%; margin-top: 15px">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($_SESSION[initiate_lc_proforma_invoice]){  ?>
                            <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 12px">Update <?=$title;?></button>
                        <?php   } else {?>
                            <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Initiate <?=$title;?></button>
                        <?php } ?>
                    </div></div>
            </form></div></div></div>
<?php if($_SESSION[initiate_lc_proforma_invoice]):  ?>

    <form action="<?=$page?>" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <? require_once 'support_html.php';?>
        <input type="hidden" id="fg_line_id"  required="required" name="fg_line_id" value="<?
        $pids1=find_a_field($table_fg_deatils,'max('.$unique.')','1');
        $pid1=$pids1+1;
        if($pids1<1) $pid1 = 1;
        echo $pid1; ?>" class="form-control col-md-7 col-xs-12"  readonly style="width:100%; height: 25px; font-size: 11px">
        <input type="hidden" id="buyer_id" name="buyer_id" value="<?=$buyer_id;?>" >
        <input type="hidden" id="party_id" name="party_id" value="<?=$party_id;?>" >
        <input type="hidden" id="pi_issue_date" name="pi_issue_date" value="<?=$pi_issue_date;?>">
        <input type="hidden" id="<?=$unique;?>" name="<?=$unique;?>" value="">
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <tr style="background-color: bisque">
                <th style="text-align: center;">Material / Finished Goods</th>
                <th style="text-align: center">Total Unit</th>
                <th style="text-align: center">Unit Price</th>
                <th style="text-align: center">Total Unit Amount</th>
                <th style="text-align: center">Action</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                  <td colspan="4" style="vertical-align:middle" align="center"><input type="file" name="file" id="file"  /></td>
                  <td style="width:5%;vertical-align:middle" align="center"><button type="submit" class="btn btn-primary" name="Import" id="Import" style="font-size: 11px">Import Data</button></td>
                </tr>
            <tr>
                <td style="vertical-align:middle" align="center">
                    <select class="select2_single form-control" style="width:100%;" tabindex="-1" required="required"  id="item_id" name="item_id">
                        <option></option>
                        <?=advance_foreign_relation($sql_item_id,($_GET[item_id]!='')? $_GET[item_id] : $edit_value->item_id);?>
                    </select></td>

                <td style="width:10%;vertical-align:middle" align="center">
                    <input  type="number" step="any" min="1" style="width:150px; height:35px; font-size: 12px; text-align:center" value="<?=$edit_value->qty?>" name="qty" id="qty" autocomplete="off" class="form-control col-md-7 col-xs-12" class='qty'>
                </td>
                <td style="width:10%;vertical-align:middle" align="center">
                    <input  type="number" step="any" style="width:150px; height:35px; font-size: 12px; text-align:center" value="<?=$edit_value->amount?>" name="rate" id="price" autocomplete="off" class="form-control col-md-7 col-xs-12" class='price'></td>
                <td style="width:10%;vertical-align:middle" align="center"><input style="width:150px; height:35px; font-size: 12px; text-align:center" readonly type='text' id='sum' value="<?=$edit_value->amount?>" name='amount' class="form-control col-md-7 col-xs-12" class='sum' /></td>
                <td style="width:5%;vertical-align:middle" align="center"><?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr>
            </tbody>
        </table>
        <script>
    $(function(){
        $('#price, #qty').keyup(function(){
            var price = parseFloat($('#price').val()) || 0;
            var qty = parseFloat($('#qty').val()) || 0;
            $('#sum').val((price * qty).toFixed(2));
        });
    });
</script>
    </form>



    <?=added_data_delete_edit($sql,$details_unique,$unique_GET,$COUNT_details_data,$page);?>
<?php endif; ?>
<?=$html->footer_content();?>

