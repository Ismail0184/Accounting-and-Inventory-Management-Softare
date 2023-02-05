<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Create LC";
$now=time();
$unique='id';
$unique_details='pi_id';
$unique_field='lc_no';
$table="lc_lc_master";
$table_details = 'lc_lc_details';
$page="LC_create_LC.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];

if($_GET['first_lc']>0)
    unset($_SESSION['lc_id']);
elseif($_REQUEST['unlc_id']>0)
    $lc_id=$_SESSION['lc_id']=$_REQUEST['unlc_id'];
elseif($_GET['lc_id']>0){
    $lc_id=$_SESSION['lc_id']=$_GET['lc_id'];
}
elseif($_POST['lc_id']>0)
    $lc_id=$_SESSION['lc_id']=$_POST['lc_id'];


if(prevent_multi_submit()){
    if(isset($_POST[$unique_field]))

//for insert..................................
    {    $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {   $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            $_POST['prepared_at'] = date('Y-m-d H:s:i');
            $crud->insert();
            $type=1;
            $_SESSION['initiate_create_LC']=$_POST[$unique];
            unset($_POST);
            unset($$unique);
        }


//for modify..................................
        if(isset($_POST['modify']))
        {   $_POST['edit_at']=time();
            $_POST['edit_by']=$_SESSION['userid'];
            $crud->update($unique);
            $type=1;
            //echo $targeturl;
            //unset($_SESSION[under_PI]);
        }}

    if(isset($_POST['add_PI'])) {
        $_POST['edit_at'] = time();
        $_POST['edit_by'] = $_SESSION['userid'];
        $_POST[lc_id]=$_SESSION[initiate_create_LC];
        $_POST[pi_id]=$_POST[under_lc];
        $pi_currency=find_a_field('lc_pi_master','currency','id='.$_POST[under_lc]);
        mysqli_query($conn, "Update lc_lc_master SET pi_id='".$_POST[under_lc]."',currency='".$pi_currency."' where id=".$_SESSION[initiate_create_LC]."");
        unset($_SESSION[under_PI]);
        $_SESSION[under_PI]=$_POST[under_lc];
        $type = 1;
    } // PI Added

    if(isset($_POST['cancel_PI']))
    {   unset($_SESSION[under_PI]);} // cancel PI


    if (isset($_POST['confirmsave'])){
        $rs=mysqli_query($conn,"Select 
d.id,
d.item_id,
d.pi_id,
d.party_id,
SUM(d.qty) as qty,
i.item_name
from 
lc_pi_details d,
item_info i
  where 
 d.item_id=i.item_id and 
 d.pi_id='".$_SESSION[under_PI]."'
  group by d.id
 ");
        while($uncheckrow=mysqli_fetch_array($rs)){
            $js=$js+1;
            $ids=$uncheckrow[id];
            $lcqty=$_POST['lc_qty_'.$ids];
            $lcrate=$_POST['lc_rate_'.$ids];
            $lcamt=$lcqty*$lcrate;
            $party_id=$uncheckrow[party_id];
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['company_id'] = $_SESSION['companyid'];
            if($lcqty>0 & $lcrate>0){
                $_POST[item_id]=$uncheckrow[item_id];
                $_POST[qty]=$lcqty;
                $_POST[rate]=$lcrate;
                $_POST[amount]=$lcamt;
                $_POST[amount_NEG]=$lcamt;
                $_POST[buyer_id]=$uncheckrow[buyer_id];
                $_POST[party_id]=$uncheckrow[party_id];
                $_POST[item_id]=$uncheckrow[item_id];
                $_POST[rate_in_NEG_currency]=$lcrate-($_POST['rate_in_USD_currency'.$ids]*$_POST[con_rate_NEG_LC]);
                $_POST[rate_in_USD_currency]=$_POST['rate_in_USD_currency'.$ids];
                $_POST[amount_NEG]=$_POST['amount_NEG'.$ids];
                $_POST[amount_USD]=$_POST['lc_amount_'.$ids];

                $crud = new crud($table_details);
                $crud->insert();
            }}
        mysqli_query($conn, "Update ".$table." set currency='".$_POST[currency]."', party_id='".$party_id."',status='UNCHECKED' where ".$unique."=".$_SESSION['initiate_create_LC']."");
        unset($_SESSION["under_PI"]);
        unset($_SESSION["initiate_create_LC"]);
        unset($_POST);
    } // confirm saved
} // prevent_multi_submit


//for Delete..................................
if(isset($_POST['delete']))
{   $crud = new crud($table_details);
    $condition =$unique_details."=".$_SESSION['initiate_create_LC'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['initiate_create_LC'];
    $crud->delete($condition);
    unset($_SESSION["under_PI"]);
    unset($_SESSION["initiate_create_LC"]);
}







// data query..................................
if(isset($_SESSION[initiate_create_LC]))
{   $condition=$unique."=".$_SESSION[initiate_create_LC];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}



$PI=find_all_field('lc_pi_master','','id='.$_SESSION[under_PI].'');
$currencys=find_all_field('currency','','id='.$PI->currency.'');
$rs=mysqli_query($conn,"Select 
d.id,
d.item_id,
d.pi_id,
d.party_id,
SUM(d.qty) as qty,
d.rate,
i.item_name,
i.unit_name
from 
lc_pi_details d,
item_info i
  where 
 d.item_id=i.item_id and 
 d.pi_id='".$_SESSION[under_PI]."'
  group by d.id
 ");

?>



<?php require_once 'header_content.php'; ?>
<script src="js/vendor/modernizr-2.8.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<style>
    #customers {}
    #customers td {}
    #customers tr:ntd-child(even)
    {background-color: #f0f0f0;}
    #customers tr:hover {background-color: #f5f5f5;}
    td{}
</style>
<?php require_once 'body_content.php'; ?>



<!-- input section-->
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?=$title;?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <? require_once 'support_html.php';?>

                <?
                if($_SESSION['initiate_create_LC']>0) $lc_idGET =  $_SESSION['initiate_create_LC'];
                else
                {$lc_idGET =  find_a_field('lc_lc_master','max(id)+1','1');
                    if($lc_idGET<1) $lc_idGET = 1;
                }
                ?>

                <table align="center" style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="width:10%;">LC ID</th><th style="width: 1%"> : </th>
                        <td style="width: 30%"><input  name="lc_id2" type="hidden" id="lc_id2" value="<?=$lc_id?>"/>
                            <input type="text" id="<?=$unique?>" style="width:80%;font-size: 11px" readonly    name="<?=$unique?>" value="<?=$lc_idGET;?>" class="form-control col-md-7 col-xs-12" >
                        </td>

                        <th style="width:10%;">LC NO</th><th style="width: 1%"> : </th>
                        <td style="width: 30%">
                            <input type="text" id="lc_no" style="width:80%;font-size: 11px"  required   name="lc_no" value="<?=$lc_no;?>" class="form-control col-md-7 col-xs-12" >
                        </th></tr>

                    <tr>
                        <th>LC Issue Date</th><th style="width: 1%"> : </th>
                        <td style="padding-top: 5px"><input type="hidden"   required="required" name="lc_create_date" max="<?=date('Y-m-d')?>" value="<?=$lc_create_date;?>" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" >
                            <input type="date" required="required" name="lc_issue_date" max="<?=date('Y-m-d')?>" value="<?=$lc_issue_date;?>" class="form-control col-md-7 col-xs-12" style="width:80%; font-size: 11px" >
                        </td>
                        <th>C. Rate (<?=find_a_field('currency','code','id='.$currency);?> to USD)</th><th style="width: 1%"> : </th>
                        <td style="padding-top: 5px"><input type="hidden"    name="party_id"  value="<?=$party_id;?>" class="form-control col-md-7 col-xs-12" style="width:100%; font-size: 11px" >
                            <input type="number" step="any" name="con_rate_NEG_LC"  value="<?=$con_rate_NEG_LC;?>" class="form-control col-md-7 col-xs-12" style="width:80%; font-size: 11px" >
                            <input type="hidden"    name="remarks"  value="<?=$remarks;?>" class="form-control col-md-7 col-xs-12" style="width:80%; font-size: 11px" >
                        </td>
                    </tr>
                    <tr>
                        <?php if($_SESSION[initiate_create_LC]){  ?>
                            <td colspan="6" align="center" style="padding-top: 10px">
                                <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Modify?");' name="modify" id="modify" class="btn btn-primary" style="font-size: 11px">Update LC Information</button>
                            </td>
                        <?php } else {?>
                            <td colspan="6" align="center" style="padding-top: 10px">
                                <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to initiate?");' name="record" id="record"  class="btn btn-primary" style="font-size: 11px">Proceed to the next</button>
                            </td>
                        <?php } ?>
                    </tr></table>
            </form>
        </div>
    </div>
</div>
<?php if($_SESSION[initiate_create_LC]):  ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form action="" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                    <?php require_once 'support_html.php';?>
                    <input type="hidden" name="lc_id" id="lc_id" value="<?=$_SESSION[initiate_create_LC];?>" >
                    <input type="hidden" name="pi_id" id="pi_id" value="<?=$_SESSION[under_PI];?>" >
                    <table align="center" style="width:60%">
                        <tbody>
                        <tr>
                            <td style="text-align: center"><strong>Active PI </strong></td>
                            <td align="left" style="width: 65%">
                                <select class="select2_single form-control" style="width:400px" tabindex="-1" required="required"  id="under_lc" name="under_lc">
                                    <option></option>
                                    <?=foreign_relation('lc_pi_master', 'id', 'CONCAT(id," : ", pi_no)', $_SESSION[under_PI], 'status not in ("COMPLETED")', 'order by id'); ?>
                                </select></td>
                            <?php
                            $PIstatus=find_a_field('lc_pi_master','status','id='.$_SESSION[under_PI].'');
                            if($_SESSION[under_PI]>0){?>
                                <td><button type="submit" name="cancel_PI" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to cancel?");' class="btn btn-danger" style="float: left; font-size: 11px">Cancel this PI</button>
                            <?php } else { ?>
                            <td align="left" style=""><button type="submit" class="btn btn-primary" name="add_PI" id="add_PI" style="font-size: 11px">Add this PI</button></td>

                                <?php } ?>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <input name="count" id="count" type="hidden" value="" />
                </form>
            </div></div></div>
<?php endif; ?>


<?php if($_SESSION[initiate_create_LC]>0):  ?>
    <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
        <input type="hidden" name="lc_id" id="lc_id" value="<?=$_SESSION[initiate_create_LC];?>" >
        <input type="hidden" name="pi_id" id="pi_id" value="<?=$_SESSION[under_PI];?>" >
        <input type="hidden" name="currency" id="currency" value="<?=$currencys->id;?>" >
        <table align="center" id="customers" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <thead>
            <tr style="background-color: bisque">
                <th style="vertical-align: middle">SL</th>
                <th style="vertical-align: middle">Material Description</th>
                <th style="text-align:center; vertical-align: middle">Unit Name</th>
                <th style="text-align:center; width: 10%; vertical-align: middle">PI Qty</th>
                <th style="text-align:center; width: 10%; vertical-align: middle">Used PI Qty</th>
                <th style="text-align:center; width: 10%; vertical-align: middle">Negotiation Rate, <?=$curr=find_a_field('currency','code','id='.$currency);?></th>
                <th style="text-align:center; width: 10%; vertical-align: middle">LC Rate, USD</th>
                <th style="text-align:center; width: 10%; vertical-align: middle">LC Qty</th>
                <th style="text-align:center; width: 10%; vertical-align: middle">Amount, <?=$curr;?></th>
                <th style="text-align:center; width: 10%; vertical-align: middle">Amount, USD</th>

            </tr>
            </thead>
            <tbody>
            <?php
            while($uncheckrow=mysqli_fetch_array($rs)){
                $js=$js+1;
                $ids=$uncheckrow[id];
                $lcqty=$_POST['lc_qty_'.$ids];
                $lcrate=$_POST['lc_rate_'.$ids];
                $lcamt=$lcqty*$lcrate;
                if(isset($_POST['deletedata'.$ids]))
                { mysqli_query($conn, "DELETE FROM ".$table_details." WHERE id='$ids'"); ?>
                    <meta http-equiv="refresh" content="0;<?=$page?>">
                <?php }?>
                <tr>
                    <td style="width:3%; vertical-align:middle"><?php echo $js; ?></td>
                    <td style="text-align:left; vertical-align:middle"><?=$uncheckrow[item_name]?></td>
                    <td style="text-align:center; vertical-align:middle"><?=$uncheckrow[unit_name];?></td>


                    <SCRIPT language=JavaScript>
                        function doAlert<?=$ids;?>(form)
                        {
                            var val=form.lc_qty_<?=$ids;?>.value;
                            var val2=form.usable_pi_qty_<?=$ids;?>.value;
                            if (Number(val)>Number(val2)){
                                alert('Oops!! Exceed PI qty limit!! Thanks');
                                form.lc_qty_<?=$ids;?>.value='';
                            }
                            form.lc_qty_<?=$ids;?>.focus();
                        }</script>

                    <td align="center" style="width:10%; text-align:center"><input type="text" class="form-control col-md-7 col-xs-12" style="font-size: 11px; height: 25px" id="pi_qty_<?=$ids;?>" style="width: 80px; text-align: center" value="<?=$uncheckrow[qty]?>" readonly tabindex="-1" >
                        <input type="hidden" class="form-control col-md-7 col-xs-12" style="font-size: 11px; height: 25px" id="con_rate_NEG_LC" style="width: 80px; text-align: center" value="<?=$con_rate_NEG_LC?>" name="con_rate_NEG_LC" readonly tabindex="-1" >
                    </td>
                    <td align="center" style="text-align:right"><input type="text" class="form-control col-md-7 col-xs-12" style="font-size: 11px; height: 25px" id="used_lc_qty_<?=$ids;?>" style="width: 80px; text-align: center" value="<?=$rcvqty=find_a_field('lc_lc_details','SUM(qty)','item_id='.$uncheckrow[item_id].' and pi_id='.$_SESSION[under_PI].''); ?>" readonly tabindex="-1">
                        <input type="hidden" id="usable_pi_qty_<?=$ids;?>" style="width: 80px; text-align: center" class="form-control col-md-7 col-xs-12" value="<?=$uncheckrow[qty]-$rcvqty; ?>" readonly tabindex="-1">
                    </td>

                    <td align="center" style="text-align:center">
                            <input type="text" name="lc_rate_<?=$ids;?>" tabindex="-1" autocomplete="off" id="lc_rate_<?=$ids;?>" value="<?=$uncheckrow[rate]?>"  style="font-size: 11px; height: 25px" class="form-control col-md-7 col-xs-12" readonly class='lc_rate_<?=$ids;?>' tabindex="-1">
                    </td>
                    <td align="center" style="text-align:center">
                        <input type="number" name="rate_in_USD_currency<?=$ids;?>" tabindex="1" step="any" autocomplete="off" id="rate_in_USD_currency<?=$ids;?>" value="<?=$uncheckrow[rate_in_USD_currency]?>"  style="font-size: 11px; height: 25px; width:100%; text-align:center" class="form-control col-md-7 col-xs-12" class='lc_rate_<?=$ids;?>' tabindex="1" >
                    </td>
                    <td align="center" style="text-align:center">
                        <?php
                        $unrec_qty=$uncheckrow[qty]-$rcvqty;
                        if($unrec_qty>0){$cow++;?>
                            <input type="number" step="any" name="lc_qty_<?=$ids;?>" tabindex="1" autocomplete="off" id="lc_qty_<?=$ids;?>" onkeyup="doAlert<?=$ids;?>(this.form);"  style="font-size: 11px; height: 25px; width: 100; text-align:center"  class='lc_qty' tabindex="1">
                        <?php } else { echo '<font style="font-weight: bold; color:red">Done</font>';} ?>
                    </td>
                    <td align="center" style="text-align:center">
                        <input readonly type='number' id='amount_NEG<?=$ids;?>'  name='amount_NEG<?=$ids;?>' style="font-size: 11px; height: 25px; width:100%; text-align:right"  class='amount_NEG' tabindex="1">
                    </td>
                    <td align="center" style="text-align:center">
                        <?php
                        $unrec_qty=$uncheckrow[qty]-$rcvqty;
                        if($unrec_qty>0){$cow++;?>
                            <input readonly type='number' id='lc_amount_<?=$ids;?>' tabindex="1" name='lc_amount_<?=$ids;?>' style="font-size: 11px; height: 25px; width:100%; text-align:right"  class='sum' tabindex="1">
                        <?php } else { echo '<font style="font-weight: bold; color:red">Done</font>';} ?>
                    </td>
                    <script>
                        $(function(){
                            $('#rate_in_USD_currency<?=$ids;?>, #lc_qty_<?=$ids;?>').keyup(function(){
                                var rate_in_USD_currency<?=$ids;?> = parseFloat($('#rate_in_USD_currency<?=$ids;?>').val()) || 0;
                                var lc_qty_<?=$ids;?> = parseFloat($('#lc_qty_<?=$ids;?>').val()) || 0;
                                $('#lc_amount_<?=$ids;?>').val((rate_in_USD_currency<?=$ids;?> * lc_qty_<?=$ids;?>).toFixed(2));
                            });});
                    </script>
                    <script>
                        $(function(){
                            $('#lc_rate_<?=$ids;?>, #lc_qty_<?=$ids;?>').keyup(function(){
                                var lc_rate_<?=$ids;?> = parseFloat($('#lc_rate_<?=$ids;?>').val()) || 0;
                                var lc_qty_<?=$ids;?> = parseFloat($('#lc_qty_<?=$ids;?>').val()) || 0;
                                var rate_in_USD_currency<?=$ids;?> = parseFloat($('#rate_in_USD_currency<?=$ids;?>').val()) || 0;
                                var con_rate_NEG_LC = parseFloat($('#con_rate_NEG_LC').val()) || 0;
                                $('#amount_NEG<?=$ids;?>').val(((lc_rate_<?=$ids;?>-(rate_in_USD_currency<?=$ids;?>*con_rate_NEG_LC)) * lc_qty_<?=$ids;?>).toFixed(2));
                            }); });
                    </script>

                </tr>
            <?php  } ?>
            </tbody>
            <tr><td colspan="8" style="text-align: right; font-weight: bold; vertical-align: middle; font-size: 11px">Total LC Value = </td>
                <td align="center" style="text-align:center"><input tabindex="1" style="height: 25px;width: 100%; font-weight: bold; font-size: 11px; text-align: right" class="form-control col-md-7 col-xs-12" type='text' id='total_amount_in_NGE' readonly /></td>
                <td align="center" style="text-align:center"><input tabindex="1" style="height: 25px;width: 100%; font-weight: bold; font-size: 11px; text-align: right" class="form-control col-md-7 col-xs-12" type='text' id='total_amount_in_USD' readonly /></td>
            </tr>
        </table>
        <?php
		if($_SESSION[under_PI]>0){
        if($cow<1){
            $vars['status']='COMPLETED';
            $table_master='lc_pi_master';
            $id=$_SESSION["under_PI"];
            db_update($table_master, $id, $vars, 'id');
            ?>
            <button style="float: left;font-size: 12px; margin-left: 1%" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Delete the LC</button>
            <h6 style="text-align: center; color: red; font-weight: bold"><i>THIS PROFORMA INVOICE IS COMPLETED !!</i></h6>
        <?php  } else { ?>
            <button style="float: left;font-size: 12px; margin-left: 1%" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Delete the LC</button>
            <button  type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="confirmsave" class="btn btn-success" style="float: right; font-size: 12px; margin-right: 1%">Confirm and Finish <?=$title;?> </button>
        <?php }} else {?>            <button style="float: left;font-size: 12px; margin-left: 1%" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Delete the LC</button>
<h6 style="text-align: center; color: red; font-weight: bold"><i>Select a PI first !!</i></h6> <?php } ?>
    </form>
<?php endif; ?>
<br><br>



<?=$html->footer_content();?>

<script>
    $('.lc_qty').blur(function () {
        var sum = 0;
        $('.amount_NEG').each(function() {
            sum += Number($(this).val());});
        $('#total_amount_in_NGE').val((sum).toFixed(2));
    });
    $('.lc_qty').blur(function () {
        var sum = 0;
        $('.sum').each(function() {
            sum += Number($(this).val());});
        $('#total_amount_in_USD').val((sum).toFixed(2));
    });
</script>