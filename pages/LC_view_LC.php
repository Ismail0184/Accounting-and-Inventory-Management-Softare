<?php
require_once 'support_file.php';
$title="Get IMS Data";
$now=time();
$unique='id';
$unique_field='name';
$table="lc_lc_master";
$table_deatils="lc_lc_details";
$details_unique = 'lc_id';
$page='LC_view_LC.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if (isset($_POST['reprocess'])) {
        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_create_LC'] = $_GET[$unique];
        $_SESSION[under_PI]=getSVALUE("lc_lc_master", "pi_id", " where lc_no=".$_GET[id]."");
        $type = 1;
        echo "<script>self.opener.location = 'LC_create_LC.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }




if (isset($_POST['check'])) {
        $up=mysqli_query($conn, "Update ".$table." set status='CHECKED' where ".$unique."=".$$unique."");
        unset($_POST);
        echo "<script>window.close(); </script>";
    }



//for Delete..................................
    if(isset($_POST['delete']))
    {
    $crud = new crud($table);
    $condition = $unique . "=" . $$unique;
    $crud->delete($condition);

    $crud = new crud($table_deatils);
    $condition = $details_unique . "=" . $$unique;
    $crud->delete_all($condition);

        unset($$unique);
        $type=1;
        $msg='Successfully Deleted.';
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
    
    $PI_currency=find_a_field('lc_pi_master', 'currency', 'id='.$pi_id. '');
    $currency = find_a_field('currency', 'code', 'id='.$PI_currency.'');
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=600,left = 250,top = -15");}
    </script>
<?php require_once 'body_content.php'; ?>


<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$$unique;?>">
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Item ID</th>
                            <th style="vertical-align: middle">Material Description</th>
                            <th style="text-align:center; vertical-align: middle">Unit Name</th>
                            <th style="text-align:center; vertical-align: middle">Qty</th>
                            <th style="text-align:center; vertical-align: middle">Rate NEG, <?=$currency;?></th>
                            <th style="text-align:center; vertical-align: middle">NEG Amount, <?=$currency;?></th>
                            <th style="text-align:center; vertical-align: middle">LC Rate, USD</th>
                            <th style="text-align:center; vertical-align: middle">LC Amount, USD</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                     $rs=mysqli_query($conn,"Select 
d.id,   
d.lc_id,
d.item_id,
d.rate,
d.qty,
d.amount,
d.rate_in_NEG_currency,
d.amount_NEG,
d.rate_in_USD_currency,
d.amount_USD,
i.*
from 
lc_lc_details d,
item_info i
  where 
 d.item_id=i.item_id and 
 d.lc_id='".$_GET['id']."' group by d.item_id
 ");
                        while($data=mysqli_fetch_object($rs)){
                            $ids=$data->id; ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td><?=$data->item_id?></td>
                                <td style="text-align:left"><?=$data->item_name?></td>
                                <td align="center" style="width:10%; text-align:center"><?=$data->unit_name?></td>
                                <td align="center" style="width:10%; text-align:center"><?=$data->qty?></td>
                                <td align="center" style="width:10%; text-align:right"><?=number_format($data->rate,2)?></td>
                                <td align="center" style="width:10%; text-align:right"><?=number_format($data->amount_NEG,2)?></td>
                                <td align="center" style="width:10%; text-align:right"><?=number_format($data->rate_in_USD_currency,2)?></td>
                                <td align="center" style="width:10%; text-align:right"><?=number_format($data->amount_USD,2)?></td>
                            </tr>
                            <?php
                            $amounttotal=$amounttotal+$data->amount_NEG;
                            $amounttotal_USD=$amounttotal_USD+$data->amount_USD;
                            $amountqty=$amountqty+$data->qty;
                        } ?>
                        <tr style="font-weight: bold"><td colspan="4" style="text-align: right">Total = </td>
                            <td style="text-align: center"><?=$amountqty?></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"><?=number_format($amounttotal,2)?></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: right"><?=number_format($amounttotal_USD,2)?></td></tr>
                        </tbody></table>




                    <?php
                    $GET_status=find_a_field($table,'status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='MANUAL' || $GET_status=='UNCHECKED'){  ?>
                    <p>
                        <button style="float: left; font-size: 12px" type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Re-process & Update</button>
                        <button style="float: right;margin-left: 20%; font-size: 12px" type="submit" name="check" id="check" class="btn btn-success" onclick='return window.confirm("Are you confirm?");'>Checked & Forward</button>
                        <button style="float: right;font-size: 12px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Delete the LC</button>
                        <? } else {echo '<h6 style="text-align: center; color: black; font-style: italic;color:red; font-weight:bold">This LC has been confirmed. You do not have permission to update this LC !!</h5>';}?>
                    </p>



                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])): ?>

    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date"  >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 12px;" name="viewreport"  class="btn btn-primary">View Available LC</button></td>


            </tr></table>

<?php		
                        if(isset($_POST[viewreport])){
                            $con.= ' and a.lc_issue_date BETWEEN  "'.$_POST[f_date].'" and "'.$_POST[t_date]. '"';
                            $res='SELECT 
a.id,
a.id as LC_ID, 
a.lc_no as LC_No, 
a.pi_id,
lb.buyer_name as Party_Name, 
a.lc_issue_date as Issue_date,
a.expiry_date ,
a.remarks,
(select SUM(amount) from lc_lc_details where lc_id=a.id) as LC_amount,
cu.code as currency, a.status
FROM lc_lc_master a,  
lc_foreigner_branch c,
lc_buyer lb,
currency cu
WHERE lb.party_id=a.party_id and cu.id=a.currency '.$con. ' order by a.lc_issue_date DESC'; } else {
$res="SELECT 
a.id,
a.id as LC_ID, 
a.lc_no as LC_No, 
a.pi_id,
lb.buyer_name as Party_Name, 
a.lc_issue_date as Issue_date,
a.expiry_date ,
a.remarks,
(select SUM(amount) from lc_lc_details where lc_id=a.id) as LC_amount,
cu.code as currency, a.status
FROM lc_lc_master a,  
lc_foreigner_branch c,
lc_buyer lb,
currency cu
WHERE lb.party_id=a.party_id and cu.id=a.currency  order by a.lc_issue_date DESC";							
						} ?>
<?=$crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>