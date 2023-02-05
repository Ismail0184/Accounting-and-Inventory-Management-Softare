<?php
require_once 'support_file.php';
$title='Depot Expenses Voucher';


$unique='payment_no';
$unique_field='voucher_date';
$table_journal_master="payment";
$table="payment";
$page="acc_expenses_calim_depot_expenses.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$create_date=date('Y-m-d');
$jv=next_journal_voucher_id();

if(prevent_multi_submit()) {

    if (isset($_POST['delete'])) {
        $condition =$unique."=".$$$unique;
        $crud->delete_all($condition);
        unset($_POST);
    }

 if (isset($_POST['confirmsave'])) {
    $rs = "Select 
    j.id as jid,
    j.payment_no,
    j.payment_date,
    j.paymentdate,
    j.narration,
    j.ledger_id,
    j.dr_amt,
    j.cr_amt,
    j.type,
    j.cheq_no,
    j.cheq_date,
    j.bank,
    j.cc_code,
    j.day_name,
    a.*,c.center_name as cname 
    from 
    payment j,
    accounts_ledger a,
    cost_center c
    where 
     j.ledger_id=a.ledger_id and 
     j.cc_code=c.id and
     j.entry_status='UNCHECKED' and 
     j.received_from='Warehouse' and
     j.payment_no='".$$unique."'
     ";
            $re_query = mysqli_query($conn, $rs);
            while ($uncheckrow = mysqli_fetch_array($re_query)) {
                $ids=$uncheckrow[jid];
                $ledger_id=$_POST['ledger_id'.$ids];
                $narration=$_POST['narration'.$ids];
                $cc_code=$_POST['cc_code'.$ids];
                if ($uncheckrow[payment_no]>0) {
                    $update=mysqli_query($conn, "UPDATE payment SET ledger_id='".$ledger_id."', narration='".$narration."', cc_code='".$cc_code."' where id=".$ids);
                add_to_journal_new($uncheckrow[paymentdate], $proj_id, $jv, $uncheckrow[payment_date], $ledger_id, $narration, $uncheckrow[dr_amt], $uncheckrow[cr_amt], Payment, $uncheckrow[payment_no], $uncheckrow[jid], $cc_code, $uncheckrow[sub_ledger_id], $_SESSION[usergroup], $uncheckrow[cheq_no], $uncheckrow[cheq_date], $create_date, $ip, $now, $uncheckrow[day_name], $thisday, $thismonth, $thisyear);
            }}
        
        
        $up_payment="UPDATE ".$table." SET entry_status='CHECKED' where ".$unique."=".$$unique."";
        $up_query=mysqli_query($conn, $up_payment);
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }// if insert confirm
}

$ress="SELECT p.payment_no,p.payment_no,p.ledger_id,p.paymentdate as date,a.ledger_name,p.narration,p.dr_amt,p.cr_amt,p.cc_code,p.id from payment p,accounts_ledger a where 
  p.ledger_id=a.ledger_id and p.payment_no=".$_GET[$unique]." and p.received_from in ('Warehouse')";
?>


<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 230,top = -1");}
</script>
<?php if(isset($_GET[$unique])){ require_once 'body_content_without_menu.php'; } else { require_once 'body_content.php';} ?>

<?php if(isset($_GET[$unique])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%;font-size:11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>#</th>
                            <th style="width: 12%">Accounts Ledger</th>
                            <th style="text-align:center;">Narration</th>
                            <th style="text-align:center;">Cost Center</th>
                            <th style="text-align:center; width: 12%">Debit</th>
                            <th style="text-align:center; width: 12%">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query=mysqli_query($conn, $ress);
                        while($data=mysqli_fetch_object($query)): 
                        $id=$data->id;
                        ?>
                        <tr>
                            <td style="vertical-align: middle"><?=$sl=$sl+1?></td>
                            <td style="vertical-align: middle">
                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="ledger_id<?=$id?>">
                                    <option></option>
                                        <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)', $data->ledger_id, 'status=1'); ?>
                                </select>
                            </td>
                            <td style="vertical-align: middle"><textarea name="narration<?=$id?>" style="font-size:11px" class="form-control col-md-7 col-xs-12"><?=$data->narration?></textarea></td>
                            
                            <td style="vertical-align: middle;">
                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="cc_code<?=$id?>">
                                    <option></option>
                                    <?php foreign_relation('cost_center', 'id', 'CONCAT(id," : ", center_name)', $data->cc_code, 'status=1'); ?>
                                </select>
                            </td>
                             <td style="vertical-align: middle; text-align: right"><?=($data->dr_amt>0)? $data->dr_amt : '-' ?></td>
                            <td style="vertical-align: middle; text-align: right"><?=($data->cr_amt>0)? $data->cr_amt : '-' ?></td>
                        </tr>
                        <?php $total_dr_amt=$total_dr_amt+$data->dr_amt;$total_cr_amt=$total_cr_amt+$data->cr_amt; endwhile; ?>
                        <tr>
                            <th style="vertical-align: middle" colspan="4">Total</th>
                            <th style="vertical-align: middle; text-align: right"><?=number_format($total_dr_amt,2)?></th>
                            <th style="vertical-align: middle; text-align: right"><?=number_format($total_cr_amt,2)?></th>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                    $GET_status=find_a_field(''.$table.'','entry_status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){  ?>
                        <p>
                            <button style="float: left; margin-left:1%;  font-size: 11px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Delete</button>
                            <button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="confirmsave" id="confirmsave" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Check and Confirm</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Voucher has been Checked & Confirmed !!</i></h6>'; ?>
                        <?php  }?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>


<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[f_date]) echo $_POST[f_date]; else echo date('Y-m-01');?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if($_POST[t_date]) echo $_POST[t_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Record</button></td>
            </tr></table>
    </form>
    <?php
    if(isset($_POST[viewreport])){
        $res="SELECT p.payment_no,p.payment_no,p.paymentdate as date,a.ledger_name,p.narration,SUM(p.dr_amt) as amount,w.warehouse_name as warehouse,concat(u.fname,': <br> at: ',p.time) as entry_by,p.entry_status as status from payment p,accounts_ledger a,warehouse w,users u where 
  p.ledger_id=a.ledger_id and p.paymentdate between '".$_POST[f_date]."' and '".$_POST[t_date]."' and p.dr_amt>0 and p.cur_bal=w.warehouse_id and er.received_from in ('Warehouse') and p.entry_by=u.user_id group by p.payment_no";
    } else {
        $res="SELECT p.payment_no,p.payment_no,p.paymentdate as date,a.ledger_name,p.narration,SUM(p.dr_amt) as amount,w.warehouse_name as warehouse,concat(u.fname,': <br> at: ',p.time) as entry_by,p.entry_status as status from payment p,accounts_ledger a,warehouse w,users u where 
  p.ledger_id=a.ledger_id and p.entry_status in ('UNCHECKED') and p.dr_amt>0 and p.cur_bal=w.warehouse_id and p.received_from in ('Warehouse') and p.entry_by=u.user_id group by p.payment_no";}
    echo $crud->report_templates_with_status($res,$title);?>
<?php endif;?>
<?=$html->footer_content();mysqli_close($conn);?>
