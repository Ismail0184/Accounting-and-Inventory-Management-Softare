<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Duty Allocation';
$unique='lc_id';
$table_master="lc_lc_master";
$table_details="lc_lc_details";
$table_LC_Duty_cost="LC_duty_cost_breakdown";
$LC_costing_breakdown="LC_costing_breakdown";
$payment_unique='lc_id';
$page="acc_LC_settelement.php";
$crud      =new crud($table_master);
$$unique = $_POST[$unique];
$create_date=date('Y-m-d');

if(prevent_multi_submit()) {
    if (isset($_POST['initiate'])) {
        $_SESSION[ID_while_LC_settelement] = $_POST[$unique];
        unset($_POST);
    }

    if (isset($_POST['cancel_lc'])) {
        unset($_SESSION['ID_while_LC_settelement']);
        unset($_SESSION['next_step_yes']);
        unset($_POST);
        unset($$unique);
    }

    if (isset($_POST['next_step'])) {
        $_SESSION['next_step_yes'] = 1;
        $sql=mysqli_query($conn,"Select d.id as ids,d.*,i.item_id,i.item_name,t.id as hsid,t.* from ".$table_details." d,item_info i,item_tariff_master t where 
            t.id=i.H_S_code and 
            d.item_id=i.item_id and d.".$unique."=".$_SESSION[ID_while_LC_settelement]."");
        while($data=mysqli_fetch_object($sql)) {
            $id = $data->ids;
            $_POST[lc_id]=$_SESSION[ID_while_LC_settelement];
            $_POST[pi_id]=$_POST[pi_id];
            $_POST[item_id]=$data->item_id;
            $_POST[total_unit]=$data->qty;
            $_POST[unit_price]=$data->rate;
            $_POST[AV]=$_POST['av'.$id];
            $_POST[H_S_code]=$data->hsid;
            $_POST[CD]=$_POST['CD'.$id];
            $_POST[RD]=$_POST['RD'.$id];
            $_POST[SD]=$_POST['SD'.$id];
            $_POST[VAT]=$_POST['VAT'.$id];
            $_POST[AIT]=$_POST['AIT'.$id];
            $_POST[ATV]=$_POST['ATV'.$id];
            $_POST[TTI]=$_POST['TTI'.$id];
            $_POST[entry_by]=$_SESSION[userid];
            $_POST[entry_at]=date('Y-m-d H:s:i');
            $_POST[ip]=$ip;
            $_POST[status]='MANUAL';
            $_POST[section_id]=$_SESSION[sectionid];
            $_POST[company_id]=$_SESSION[companyid];
            $crud = new crud($table_LC_Duty_cost);
            $crud->insert();
        }
        unset($_POST);
    }

} // prevent multi submit
$master=find_all_field(''.$table_master.'','','id='.$_SESSION[ID_while_LC_settelement]);
//for Delete..................................
if (isset($_POST['cancel'])) {
    $crud = new crud($table_payment);
    $condition =$payment_unique."=".$_SESSION['ID_while_LC_settelement'];
    $crud->delete_all($condition);
    $crud = new crud($table_journal_master);
    $condition=$unique."=".$_SESSION['ID_while_LC_settelement'];
    $crud->delete($condition);
    unset($_SESSION['ID_while_LC_settelement']);
    unset($_SESSION['next_step_yes']);
    unset($_POST);
    unset($$unique);
}

if (isset($_POST['confirm'])){
    $sql = mysqli_query($conn, "Select d.id as ids,d.*,i.item_id,i.item_name,t.H_S_code as H_S_code,t.*,
    dt.AV as AVs,
    dt.CD as CDs, 
    dt.RD as RDs, 
    dt.SD as SDs, 
    dt.VAT as VATs, 
    dt.AIT as AITs, 
    dt.ATV as ATVs, 
    dt.TTI as TTIs    
    from " . $table_details . " d,item_info i,item_tariff_master t,LC_duty_cost_breakdown dt where 
            t.id=i.H_S_code and 
            d.lc_id=dt.lc_id and
            d.item_id=dt.item_id and  
            d.item_id=i.item_id and d." . $unique . "=" . $_SESSION[ID_while_LC_settelement]." and dt.status='MANUAL' group by d.id");
    while($data=mysqli_fetch_object($sql)) {
        $_POST[lc_id]=$_SESSION[ID_while_LC_settelement];
        $_POST[pi_id]=$data->pi_id;
        $_POST[item_id]=$data->item_id;
        $_POST[total_unit]=$data->qty;
        $_POST[unit_price]=$data->rate;
        $_POST[total_amt]=$_POST[total_unit]*$_POST[unit_price];
        $_POST[AV]=$data->AVs;
        $_POST[H_S_code]=$data->H_S_code;
        $_POST[CD]=$data->CDs;
        $_POST[RD]=$data->RDs;
        $_POST[SD]=$data->SDs;
        if($_POST['dr_ledger_1']=='1005000400000000'){
            $_POST[VAT] = 0;
        } else {
            $_POST[VAT] = $data->VATs;
        }
        if($_POST['dr_ledger_2']=='1005000100000000'){
            $_POST[AIT] = 0;
        } else {
            $_POST[AIT] = $data->AITs;
        }
        if($_POST['dr_ledger_3']=='1005000800000000'){
            $_POST[ATV] = 0;
        } else {
            $_POST[ATV] = $data->ATVs;
        }
        $_POST[TTI]=$data->CDs+$data->RDs+$data->SDs+$_POST[VAT]+$_POST[AIT]+$_POST[ATV];
        $_POST[entry_by]=$_SESSION[userid];
        $_POST[entry_at]=date('Y-m-d H:s:i');
        $_POST[ip]=$ip;
        $_POST[status]='COMPLETED';
        $_POST[section_id]=$_SESSION[sectionid];
        $_POST[company_id]=$_SESSION[companyid];
        $crud = new crud($LC_costing_breakdown);
        $crud->insert();
    }
    $payment_id=automatic_voucher_number_generate('payment','payment_no',1,2);
    $jv=next_journal_voucher_id();
    $transaction_date=date('Y-m-d');
    $narration='Duty Exp. Allocated Against PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
    $narration_others='PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
    if($_POST[total_duty]>0) {
        add_to_payment($payment_id,0, $proj_id, $narration, $_POST[pending_LC], $_POST[total_duty],$aa,Debit,$cur_bal,$aa,$aa,$aa,$aa,$dr_total_amt,$_SESSION[ID_while_LC_settelement],$_POST[duty_ledger],UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[ID_while_LC_settelement]); }

    if($_POST['dr_ledger_1']=='1005000400000000'){
        $narration_1_2='VAT Current Account, PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
        add_to_payment($payment_id,0, $proj_id, $narration_1_2, $_POST['dr_ledger_1'], $_POST[total_VAT],$aa,Debit,$cur_bal,$aa,$aa,$aa,$aa,$dr_total_amt,$aa,0,UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,0);
    } else {
        $narration_1_1='VAT Expenses, PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
        add_to_payment($payment_id,0, $proj_id, $narration_1_1, $_POST[pending_LC], $_POST[total_VAT],$aa,Debit,$cur_bal,$aa,$aa,$aa,$aa,$dr_total_amt,$_SESSION[ID_while_LC_settelement],$_POST['dr_ledger_1'],UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[ID_while_LC_settelement]);
    }

    if($_POST['dr_ledger_2']=='1005000100000000') {
        $narration_2_2='AIT Current Account, PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
        add_to_payment($payment_id,0, $proj_id, $narration_2_2, $_POST['dr_ledger_2'], $_POST[total_AIT],$aa,Debit,$cur_bal,$aa,$aa,$aa,$aa,$dr_total_amt,$aa,0,UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,0);
    } else {
        $narration_2_1='AIT Expenses, PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
        add_to_payment($payment_id,0, $proj_id, $narration_2_1, $_POST[pending_LC], $_POST[total_AIT],$aa,Debit,$cur_bal,$aa,$aa,$aa,$aa,$dr_total_amt,$_SESSION[ID_while_LC_settelement],$_POST['dr_ledger_1'],UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[ID_while_LC_settelement]);
    }

    if($_POST['dr_ledger_3']=='1005000800000000'){
        $narration_3_2='ATV Current Account, PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
        add_to_payment($payment_id,0, $proj_id, $narration_3_2, $_POST['dr_ledger_3'], $_POST[total_ATV],$aa,Debit,$cur_bal,$aa,$aa,$aa,$aa,$dr_total_amt,$aa,0,UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,0);
    } else {
        $narration_3_1='ATV Expenses, PI NO#'.$master->pi_id.', LC NO#'.$_SESSION[ID_while_LC_settelement].', '.'Bill of Entry#'.$_POST[bill_of_entry].', '.$_POST[narration];
        add_to_payment($payment_id,0, $proj_id, $narration_3_1, $_POST[pending_LC], $_POST[total_ATV],$aa,Debit,$cur_bal,$aa,$aa,$aa,$aa,$dr_total_amt,$_SESSION[ID_while_LC_settelement],$_POST['dr_ledger_1'],UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[ID_while_LC_settelement]);
    }

    $total_credit_amount=$_POST[total_duty]+$_POST[total_VAT]+$_POST[total_AIT]+$_POST[total_ATV];
    if($total_credit_amount>0) {
        add_to_payment($payment_id,0, $proj_id, $narration_others, $_POST[cr_ledger_1],$aa,$total_credit_amount,Credit,$cur_bal,0,0,$c_date,0,$dr_total_amt,$_SESSION[ID_while_LC_settelement],$_POST[duty_ledger],UNCHECKED,$ip,$transaction_date,$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
            ,$thisday,$thismonth,$thisyear,$receive_ledger,$_SESSION[ID_while_LC_settelement]);}

    $rs_details="Select 
j.id as jid,
a.ledger_id,
j.paymentdate,
j.payment_no,
j.sub_ledger_id,
(select ledger_name from accounts_ledger where ledger_id=j.sub_ledger_id) as 'LC Expensed Head',
j.narration,
j.dr_amt,
j.cr_amt,
j.manual_payment_no
from 
payment j,
 accounts_ledger a
  where 
 j.ledger_id=a.ledger_id and
 entry_status='UNCHECKED' and 
 j.payment_no='".$payment_id."' group by j.id";
    $rs = mysqli_query($conn, $rs_details);
    while($uncheckrow=mysqli_fetch_array($rs)) {
        $ids = $uncheckrow[jid];
        add_to_journal_new($uncheckrow[paymentdate],$proj_id, $jv, 0, $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt],Payment, $uncheckrow[payment_no],$uncheckrow[jid],$uncheckrow[cc_code],$uncheckrow[sub_ledger_id],$_SESSION[usergroup],$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear);
    }


    $update=mysqli_query($conn, 'update '.$table_LC_Duty_cost.' set status="COMPLETED" where lc_id='.$_SESSION['ID_while_LC_settelement'].'');
    unset($_SESSION['ID_while_LC_settelement']);
    unset($_SESSION['next_step_yes']);
}

if(isset($_POST[clear_data])){
    mysqli_query($conn, 'delete from LC_duty_cost_breakdown where status in ("MANUAL")');
    unset($_SESSION['next_step_yes']);
}

if($_SESSION['next_step_yes']>0) {
    $sql = mysqli_query($conn, "Select d.id as ids,d.*,i.item_id,i.item_name,t.H_S_code as H_S_code,t.*,
    dt.AV as AVs,
    dt.CD as CDs, 
    dt.RD as RDs, 
    dt.SD as SDs, 
    dt.VAT as VATs, 
    dt.AIT as AITs, 
    dt.ATV as ATVs, 
    dt.TTI as TTIs    
    from " . $table_details . " d,item_info i,item_tariff_master t,LC_duty_cost_breakdown dt where 
            t.id=i.H_S_code and 
            d.lc_id=dt.lc_id and
            d.item_id=dt.item_id and  
            d.item_id=i.item_id and d." . $unique . "=" . $_SESSION[ID_while_LC_settelement]." and dt.status='MANUAL' group by d.id");
} else {
    $sql = mysqli_query($conn, "Select d.id as ids,d.*,i.item_id,i.item_name,t.* from " . $table_details . " d,item_info i,item_tariff_master t where 
            t.id=i.H_S_code and 
            d.item_id=i.item_id and d." . $unique . "=" . $_SESSION[ID_while_LC_settelement] ." group by d.id");
}
?>

<?php require_once 'header_content.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <style>
        #customers {}
        #customers td {}
        #customers tr:ntd-child(even)
        {background-color: #white;}
        #customers tr:hover {background-color: #F0F0F0;}
        td{}
    </style>
<?php require_once 'body_content_entry_mod.php'; ?>


    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="x_title">
                        <h2><?php echo $title; ?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <? require_once 'support_html.php';?>
                    <table align="center" style="width:100%">
                        <tr style="display:none">
                            <td style="width:50%;">
                                <div class="form-group" style="width: 100%">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Date<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="date" id="voucher_date"  required="required" name="voucher_date" value="<?=($voucher_date!='')? $voucher_date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" min="<?=date('Y-m-d', strtotime($date .' -'.find_a_field('acc_voucher_config','back_date_limit','1'). 'day'));?>" class="form-control col-md-7 col-xs-12" style="width: 130px; font-size: 11px" ><br>
                                    </div>
                                </div></td>


                            <td style="width:50%"><div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Transaction No<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="voucherno"   required="required" name="voucherno" value="<?=($_SESSION['ID_while_LC_settelement']!='')? $_SESSION['ID_while_LC_settelement'] : automatic_voucher_number_generate($table_payment,$payment_unique,1,2);?>" class="form-control col-md-7 col-xs-12"  readonly style="width: 130px; font-size: 11px">
                                    </div>
                                </div>
                            </td></tr>
                    </table>
                    <table align="center" style="width:60%">
                        <tbody>
                        <tr>
                            <td style="text-align: center">Active LC</td>
                            <td align="left">
                                <select class="select2_single form-control" style="width:400px; font-size: 11px" tabindex="-1" required="required"  name="lc_id" id="lc_id">
                                    <option selected></option>
                                    <? $sql_lc_id="SELECT m.id,concat(m.id,' : ',m.lc_no) FROM  
                            lc_lc_master m
                            WHERE m.id not in (select lc_id from LC_costing_breakdown where lc_id=m.id) and m.status not in ('MANUAL')";
                                    advance_foreign_relation($sql_lc_id,$_SESSION[ID_while_LC_settelement]);?>
                                </select>
                            </td>

                            <td align="center" style="width:10%">
                                <?php if($_SESSION[ID_while_LC_settelement]){  ?>
                                    <button type="submit" name="cancel_lc" class="btn btn-danger" style="font-size: 12px">Cancel this LC</button>
                                <?php   } else {?>
                                    <button type="submit" class="btn btn-primary" name="initiate" id="initiate" style="font-size: 12px">Add LC</button>
                                <?php } ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div></div></div>
        <?php  if($_SESSION[ID_while_LC_settelement]){ ?>
        <table id="customers" align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <input type="hidden" id="pi_id" name="pi_id" value="<?=$master->pi_id;?>">

            <thead>
            <tr style="background-color: bisque">

                <th style="text-align:center">Item Id</th>
                <th style="text-align:center">Item Description</th>
                <th style="text-align:center; width:">H.S Code</th>
                <th style="text-align:center; width:">AV</th>
                <th style="text-align:center; width:">CD</th>
                <th style="text-align:center; width: ">RD</th>
                <th style="text-align:center; width:">SD</th>
                <th style="text-align:center; width:">VAT</th>
                <th style="text-align:center; width:">AIT</th>
                <th style="text-align:center; width:">ATV</th>
                <th style="text-align:center; width:">TTI</th>
            </tr>
            </thead>
            <body>

            <?php while($data=mysqli_fetch_object($sql)){ $id=$data->ids; ?>
                <tr>
                    <td style="text-align:center; vertical-align: middle"><?=$data->item_id?></td>
                    <td style="text-align:left; vertical-align: middle"><?=$data->item_name?></td>
                    <td style="text-align:center; vertical-align: middle"><?=$data->H_S_code;?></td>
                    <td style="text-align:center; vertical-align: middle">
                        <input type="number" step="any" name="av<?=$id;?>" autocomplete="off" id="av<?=$id;?>" value="<?=$data->AVs;?>" style="font-size: 11px; height: 25px; text-align: center" class="form-control col-md-7 col-xs-12" class='av<?=$id;?>'>
                    <td style="text-align:center; vertical-align: middle">
                        <input type="text" name="CD<?=$id;?>" autocomplete="off" id="CD<?=$id;?>" style="font-size: 11px; height: 25px;text-align: center" value="<?=$data->CDs;?>" readonly class="form-control col-md-7 col-xs-12" class='CD<?=$id;?>'>
                    </td>
                    <script>
                        $(function(){
                            $('#av<?=$id;?>').keyup(function(){
                                var av<?=$id;?> = parseFloat($('#av<?=$id;?>').val()) || 0;
                                $('#CD<?=$id;?>').val(((av<?=$id;?> * <?=$data->CD;?>)/100).toFixed(2));
                            });
                        });
                    </script>
                    <td style="text-align:center; vertical-align: middle">
                        <input type="text" name="RD<?=$id;?>" autocomplete="off" id="RD<?=$id;?>" value="<?=$data->RDs;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='RD<?=$id;?>'>
                    </td>
                    <script>
                        $(function(){
                            $('#av<?=$id;?>').keyup(function(){
                                var av<?=$id;?> = parseFloat($('#av<?=$id;?>').val()) || 0;
                                $('#RD<?=$id;?>').val(((av<?=$id;?> * <?=$data->RD;?>)/100).toFixed(2));
                            });
                        });
                    </script>
                    <td style="text-align:center; vertical-align: middle">
                        <input type="text" name="SD<?=$id;?>" autocomplete="off" id="SD<?=$id;?>" value="<?=$data->SDs;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='SD<?=$id;?>'>
                    </td>

                    <script>
                        $(function(){
                            $('#av<?=$id;?>').keyup(function(){
                                var av<?=$id;?> = parseFloat($('#av<?=$id;?>').val()) || 0;
                                var CD<?=$id;?> = parseFloat($('#CD<?=$id;?>').val()) || 0;
                                var RD<?=$id;?> = parseFloat($('#RD<?=$id;?>').val()) || 0;
                                $('#SD<?=$id;?>').val((((av<?=$id;?> +RD<?=$id;?>+CD<?=$id;?>)*<?=$data->SD;?>)/100).toFixed(2));
                            });
                        });
                    </script>
                    <td style="text-align:center; vertical-align: middle">
                        <input type="text" name="VAT<?=$id;?>" autocomplete="off" id="VAT<?=$id;?>" value="<?=$data->VATs;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='VAT<?=$id;?>'>
                    </td>
                    <script>
                        $(function(){
                            $('#av<?=$id;?>').keyup(function(){
                                var av<?=$id;?> = parseFloat($('#av<?=$id;?>').val()) || 0;
                                var CD<?=$id;?> = parseFloat($('#CD<?=$id;?>').val()) || 0;
                                var RD<?=$id;?> = parseFloat($('#RD<?=$id;?>').val()) || 0;
                                var SD<?=$id;?> = parseFloat($('#SD<?=$id;?>').val()) || 0;
                                $('#VAT<?=$id;?>').val((((av<?=$id;?>+CD<?=$id?>+RD<?=$id;?>+SD<?=$id;?>)*<?=$data->VAT;?>)/100).toFixed(2));
                            });
                        });
                    </script>


                    <td style="text-align:center; vertical-align: middle">
                        <input type="text" name="AIT<?=$id;?>" autocomplete="off" id="AIT<?=$id;?>" value="<?=$data->AITs;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='AIT<?=$id;?>'>
                    </td>
                    <script>
                        $(function(){
                            $('#av<?=$id;?>').keyup(function(){
                                var av<?=$id;?> = parseFloat($('#av<?=$id;?>').val()) || 0;
                                $('#AIT<?=$id;?>').val(((av<?=$id;?> * <?=$data->AIT;?>)/100).toFixed(2));
                            });
                        });
                    </script>
                    <td style="text-align:center; vertical-align: middle">
                        <input type="text" name="ATV<?=$id;?>" autocomplete="off" id="ATV<?=$id;?>" value="<?=$data->ATVs;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='ATV<?=$id;?>'>
                    </td>
                    <script>
                        $(function(){
                            $('#av<?=$id;?>').keyup(function(){
                                var av<?=$id;?> = parseFloat($('#av<?=$id;?>').val()) || 0;
                                var CD<?=$id;?> = parseFloat($('#CD<?=$id;?>').val()) || 0;
                                var RD<?=$id;?> = parseFloat($('#RD<?=$id;?>').val()) || 0;
                                var SD<?=$id;?> = parseFloat($('#SD<?=$id;?>').val()) || 0;
                                $('#ATV<?=$id;?>').val((((av<?=$id;?>+CD<?=$id?>+RD<?=$id;?>+SD<?=$id;?>)*<?=$data->ATV;?>)/100).toFixed(2));
                            });
                        });
                    </script>
                    <td style="text-align:center; vertical-align: middle">
                        <input type="text" name="TTI<?=$id;?>" autocomplete="off" id="TTI<?=$id;?>" value="<?=$data->TTIs;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='TTI'>
                    </td>
                    <script>
                        $(function(){
                            $('#av<?=$id;?>').keyup(function(){
                                var av<?=$id;?> = parseFloat($('#av<?=$id;?>').val()) || 0;
                                var CD<?=$id;?> = parseFloat($('#CD<?=$id;?>').val()) || 0;
                                var RD<?=$id;?> = parseFloat($('#RD<?=$id;?>').val()) || 0;
                                var SD<?=$id;?> = parseFloat($('#SD<?=$id;?>').val()) || 0;
                                var VAT<?=$id;?> = parseFloat($('#VAT<?=$id;?>').val()) || 0;
                                var AIT<?=$id;?> = parseFloat($('#AIT<?=$id;?>').val()) || 0;
                                var ATV<?=$id;?> = parseFloat($('#ATV<?=$id;?>').val()) || 0;
                                $('#TTI<?=$id;?>').val((CD<?=$id;?>+RD<?=$id;?>+SD<?=$id;?>+VAT<?=$id;?>+AIT<?=$id;?>+ATV<?=$id;?>).toFixed(2));
                            });
                        });
                    </script>
                </tr>
                <?php $total_amount=$total_amount+$data->TTIs;
                $total_AV_amount=$total_AV_amount+$data->AVs;
                $total_CD_amount=$total_CD_amount+$data->CDs;
                $total_RD_amount=$total_RD_amount+$data->RDs;
                $total_SD_amount=$total_SD_amount+$data->SDs;
                $total_VAT_amount=$total_VAT_amount+$data->VATs;
                $total_AIT_amount=$total_AIT_amount+$data->AITs;
                $total_ATV_amount=$total_ATV_amount+$data->ATVs;

            } ?>
            <tr><th colspan="3" style="vertical-align: middle; text-align: right">Total Duty Amount</th>
                <td><input type="text" name="total_AV"  id="total_AV" value="<?=$total_AV_amount;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='total_AV'></td>
                <td><input type="text" name="total_CD"  id="total_CD" value="<?=$total_CD_amount;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='total_CD'></td>
                <td><input type="text" name="total_RD"  id="total_RD" value="<?=$total_RD_amount;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='total_RD'></td>
                <td><input type="text" name="total_SD"  id="total_SD" value="<?=$total_SD_amount;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='total_SD'></td>
                <td><input type="text" name="total_VAT"  id="total_VAT" value="<?=$total_VAT_amount;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='total_VAT'></td>
                <td><input type="text" name="total_AIT"  id="total_AIT" value="<?=$total_AIT_amount;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='total_AIT'></td>
                <td><input type="text" name="total_ATV"  id="total_ATV" value="<?=$total_ATV_amount;?>" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12" class='total_ATV'></td-->
                <td><input type="text" name="total_TTI"  style="font-size: 11px; height: 25px;text-align: center" value="<?=$total_amount;?>" readonly class="form-control col-md-7 col-xs-12" class='total_TTI'></td>
            </tr>
            </body>
        </table>
        <div class="form-group" style="float: right">
            <div class="col-md-6 col-sm-6 col-xs-12"><?php if($_SESSION[next_step_yes]){?>
                    <button type="submit" class="btn btn-danger" name="clear_data" id="clear_data" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Clear All Data & Reprocess</button>
                <?php } else { ?>
                    <button type="submit" class="btn btn-primary" name="next_step" id="next_step" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Proceed to the next step</button>
                <?php } ?>
            </div></div></form>
<?php } ?>



<?php if($_SESSION[next_step_yes]){?>
    <form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <input type="hidden" name="pending_LC"  id="pending_LC" value="1003001200010000" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12">
        <input type="hidden" name="duty_ledger"  id="duty_ledger" value="1003001200010005" style="font-size: 11px; height: 25px;text-align: center" readonly class="form-control col-md-7 col-xs-12"></td>

        <input type="hidden" id="pi_id" name="pi_id" value="<?=$master->pi_id;?>">
        <input type="hidden" name="total_duty"  id="total_duty" value="<?=$total_CD_amount+$total_RD_amount+$total_SD_amount;?>">
        <input type="hidden" name="total_VAT"  id="total_VAT" value="<?=$total_VAT_amount;?>">
        <input type="hidden" name="total_AIT"  id="total_AIT" value="<?=$total_AIT_amount;?>">
        <input type="hidden" name="total_ATV"  id="total_ATV" value="<?=$total_ATV_amount;?>">
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <thead>
            <tr style="background-color: bisque; font-size: 11px; height: 25px;">
                <th style="text-align:center; vertical-align: middle; width: 5%">Type</th>
                <th style="text-align:center; vertical-align: middle">VAT Ledger</th>
                <th style="text-align:center; vertical-align: middle">AIT Ledger</th>
                <th style="text-align:center; vertical-align: middle">ATV Ledger</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th style="vertical-align: middle; text-align: center">Debit Ledgers</th>
                <td align="left" style="vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px"  name="dr_ledger_1">
                        <?=$ledger1='1005000400000000';?>
                        <?=foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $ledger1, 'ledger_group_id in  ("1005","4015")'); ?>
                    </select>
                </td>
                <td align="left" style="width: 15%; vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="dr_ledger_2">
                        <?=$ledger2='1005000100000000';?>
                        <?=foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $ledger2, 'ledger_group_id in  ("1005")'); ?>
                    </select>
                </td>
                <td align="left" style="width: 15%; vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="dr_ledger_3">
                        <?=$ledger3='1005000800000000';?>
                        <?=foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $ledger3, 'ledger_group_id in  ("1005")'); ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th style="vertical-align: middle; text-align: center">Credit Ledger</th>
                <td align="left" style="width: 15%; vertical-align: middle">
                    <select class="select2_single form-control" style="width:100%; font-size: 11px" required  name="cr_ledger_1">
                        <option></option>
                        <?=foreign_relation('accounts_ledger', 'ledger_id', 'ledger_name', $cr_ledger_1, '1'); ?>
                    </select>
                </td>
                <td align="left" style="width: 15%; vertical-align: middle">
                    <textarea name="narration" style="width:100%; font-size: 11px; height: 36px" required class="form-control col-md-7 col-xs-12" placeholder="Narration"></textarea>
                </td>
                <td align="left" style="width: 15%; vertical-align: middle"><input style="width:100%; font-size: 11px" type="text" placeholder="bill of entry number" required class="form-control col-md-7 col-xs-12" name="bill_of_entry"></td></tr>
            </tbody>
        </table>

        <div class="form-group" style="float: left">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="submit" class="btn btn-danger" name="cancel" id="cancel" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Cancel this proceed</button>
            </div></div>
        <div class="form-group" style="float: right">
            <div class="col-md-6 col-sm-6 col-xs-12"><?php if($_SESSION[next_step_yes]){?>
                    <button type="submit" class="btn btn-primary" name="confirm" id="confirm" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Complete the LC Settelement Process</button>
                <?php } else {} ?>
            </div></div>
    </form>
<?php } mysqli_close($conn); ?>
<?=$html->footer_content();?>