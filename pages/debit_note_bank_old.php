<?php
require_once 'support_file.php';
$title='Bank Payment Voucher';
?>



<?php require_once 'header_content.php'; ?>
    <style>
	input[type=text]:focus {
            background-color: lightblue;
        }</style>
    <script type="text/javascript">
        function OpenPopupCenter(pageURL, title, w, h) {
            var left = (screen.width - w) / 2;
            var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
            var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }
    </script>
<?php require_once 'body_content.php'; ?>




                    <div class="col-md-8 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?php echo $title; ?></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <?php

                                $jvbank=next_journal_bank_voucher_id();
                                $d =$_POST[nam_date];
                                $transaction_date=date('Y-m-d' , strtotime($d));
                                $initiate=$_POST[initiate];
                                $enat=date('Y-m-d h:s:i');
                                $cd =$_POST[c_date];
                                $c_date=date('Y-m-d' , strtotime($cd));
                                $invoice=$_POST[invoice];

                                if(isset($initiate)){
                                    add_to_journal_master($invoice,$transaction_date, $_POST[r_from], $_POST[c_no], $c_date, $_POST[bank], MANUAL, $_POST[remarks],Bank_Payment,$_POST[PBI_ID],$ip);
                                    $_SESSION[initiate_bank_debit_note]=$invoice;

                                }



                                if(isset($_POST[updateMAN])){
                                    $insert=mysql_query("UPDATE journal_voucher_master SET  

voucher_date='$transaction_date',

paid_to='$_POST[r_from]',

Cheque_No='$_POST[c_no]',

Cheque_Date='$c_date',

Cheque_of_bank='$_POST[bank]',

remarks='$_POST[remarks]',

PBI_ID='$_POST[PBI_ID]'

 WHERE voucherno='".$_SESSION[initiate_bank_debit_note]."' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]' "); ?>

                                    <meta http-equiv="refresh" content="0;debit_note_bank.php">

                               <?php  }

                                $resultsssss=mysql_query("Select * from journal_voucher_master where voucherno='$_SESSION[initiate_bank_debit_note]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]' ");

                                $inirow=mysql_fetch_array($resultsssss);



                                $dd =$inirow[voucher_date];

                                $date=date('d-m-y' , strtotime($dd));

                                $j=0;

                                for($i=0;$i<strlen($date);$i++)

                                {

                                if(is_numeric($date[$i]))

                                { $time[$j]=$time[$j].$date[$i];

                                } else {

                                 $j++; } }

                                $date=mktime(0,0,0,$time[1],$time[0],$time[2]);







                                ?>









                                <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px">
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td style="width:50%;">
                                                <div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Date<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="nam_date"  required="required" name="nam_date" value="<?php if($_SESSION[initiate_bank_debit_note]){ echo date('m/d/y' , strtotime($inirow[voucher_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width: 130px; font-size: 11px" ><br>
                                                    </div>
                                                </div></td>


                                            <td style="width:50%"><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Transaction No<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="last-name"   required="required" name="invoice" value="<?php if($_SESSION[initiate_bank_debit_note]){ echo $inirow[voucherno];} else { echo
                                                        $_SESSION['bankdebitvoucherNOW']; } ?>" class="form-control col-md-7 col-xs-12"  readonly style="width: 130px; font-size: 11px">
                                                    </div>
                                                </div>
                                            </td></tr>



                                        <tr>
                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Paid to:<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="r_from"  value="<?php if($_SESSION[initiate_bank_debit_note]){ echo $inirow[paid_to];} else { echo ''; } ?>" name="r_from" class="form-control col-md-7 col-xs-12" style="width: 130px; margin-top: 5px; font-size: 11px" >
                                                    </div></div></td>





                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Employee<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="PBI_ID"  value="<?php if($_SESSION[initiate_bank_debit_note]){ echo $inirow[PBI_ID];} else { echo ''; } ?>" name="PBI_ID" class="form-control col-md-7 col-xs-12"  style="width: 130px; margin-top: 5px;font-size: 11px">
                                                    </div></div></td></tr>



                                        <tr> <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Cheque No<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="c_no"  value="<?php if($_SESSION[initiate_bank_debit_note]){ echo $inirow[Cheque_No];} else { echo ''; } ?>" name="c_no"  class="form-control col-md-7 col-xs-12" style="width: 130px; margin-top: 5px; font-size: 11px" ></div></div></td>


                                            <td><div class="form-group">

                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Cheque Date<span class="required">*</span>

                                                    </label>

                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="c_date"   value="<?php if($_SESSION[initiate_bank_debit_note]){ echo date('m/d/y' , strtotime($inirow[Cheque_Date]));} else { echo ''; } ?>" name="c_date"  class="form-control col-md-7 col-xs-12"  style="width: 130px; margin-top: 5px; font-size: 11px">
                                                    </div></div></td></tr>



                                        <tr>

                                            <td><div class="form-group">

                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Of Bank<span class="required">*</span></label>

                                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                                        <input type="text" name="bank" id="bank" value="<?=$inirow[Cheque_of_bank]?>" class="form-control col-md-7 col-xs-12" style="width: 130px; margin-top: 5px; font-size: 11px">

                                                    </div></div> </td>



                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Remarks<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="remarks" id="remarks" value="<?=$inirow[remarks]?>" class="form-control col-md-7 col-xs-12" style="width: 130px; margin-top: 5px; font-size: 11px"></div></div></td>
                                        </tr></table>





                                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_SESSION[initiate_bank_debit_note]){  ?>
                                                <!---a href="Incoming_Material_Received.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
                                                <button type="submit" name="updateMAN" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");'>Update Bank Payment Voucher</button>
                                            <?php   } else {?>
                                                <button type="submit" name="initiate" class="btn btn-primary">Initiate Bank Payment Voucher</button>
                                            <?php } ?>
                                        </div></div>
                                </form></div></div></div>





















                    <div class="col-md-4 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Recent Voucher List</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content" style="overflow:scroll; height:210px; font-size: 11px">
                                <table style="width:100%"  class="table table-striped table-bordered">

                                        <tr>
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Voucher No </th>
                                            <th>Amount</th>

                                        </tr>
                                        <?

                                        $sql2="select a.tr_no, a.jv_no,a.jv_no,a.jvdate,SUM(a.dr_amt) as amount

from  secondary_journal_bank a where a.tr_from='Payment' and a.user_id='$_SESSION[userid]' and a.section_id='$_SESSION[sectionid]' and a.company_id='$_SESSION[companyid]'  group by a.tr_no  order by a.tr_no desc limit 10";



                                        $data2=mysql_query($sql2);
                                        if(mysql_num_rows($data2)>0){
                                            while($dataa=mysql_fetch_row($data2)){ ?>
                                                <tr class="alt">
                                                    <td><?=$is=$is+1;?></td>
                                                    <td style="text-align: left;cursor: pointer" onclick="OpenPopupCenter('voucher_view_popup_bank.php?<?php echo 'v_type=payment&vdate='.$dataa[3].'&v_no='.$dataa[2].'&view=Show&in=payment' ?>', 'TEST!?', 900, 600);"><?=$dataa[3]?></td>
                                                    <td><a href="voucher_print_payment_secondary.php?v_type=payment&vo_no=<?php echo $dataa[2];?>" target="_blank"><?=$dataa[0]?></a></td>
                                                    <td style="text-align: right"><?=number_format($dataa[4],2)?></td>
                                                </tr> <? }}?>
                                    </table>
                                </div></div></div>



                                <!----------------------------------- initiate end--------------------------------------------------------------------->



                                <?php
                                $c_dd =$inirow[Cheque_Date];
                                $c_date=date('d-m-y' , strtotime($c_dd));
                                $j=0;
                                for($i=0;$i<strlen($c_date);$i++)
                                {
                                    if(is_numeric($c_date[$i]))
                                    {$ptime[$j]=$ptime[$j].$c_date[$i]; } else {
                                        $j++;}}
                                $c_date=mktime(0,0,0,$ptime[1],$ptime[0],$ptime[2]);

                                $tdates=date("Y-m-d");
                                $day = date('l', strtotime($idatess));
                                $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
                                $timess=$dateTime->format("d-m-y  h:i A");
                                //echo "$timess";
                                $add=$_POST[add];
                                if (isset($_POST['add'])){

                                    $valid = true;



                                    if($_POST[dr_amt]>0) {

                                        $type='Debit';

                                    }

                                    elseif ($_POST[cr_amt]>0) {

                                        $type='Credit';

                                    }









                                    if ($valid){

                                        if($_POST[dr_amt]>0 || $_POST[cr_amt]>0){

                                            $recept="INSERT INTO `secondary_payment` (

							payment_no ,

							payment_date ,

							proj_id ,

							narration ,

							ledger_id ,

							dr_amt ,

							cr_amt ,

							type ,

							cur_bal ,

							received_from,

							cheq_no,

							cheq_date,

							bank,

							manual_payment_no,

							cc_code,

							sub_ledger_id,

							entry_status,

							`ip`,

							paymentdate,section_id,company_id,entry_by,po_no,do_no,create_date ) 

							 

							VALUES 

							

							('$_SESSION[initiate_bank_debit_note]',

							'$date',

							'icpbd', 

							'$_POST[narration]', 

							'$_POST[ledger_id]', 

							'$_POST[dr_amt]',

							'$_POST[cr_amt]', 

							'$type', 

							'$cur_bal',

							'$inirow[paid_to]',

							'$inirow[Cheque_No]',

							'$c_date',

							'$inirow[Cheque_of_bank]',

							'$manual_payment_no'

							,'$_POST[cc_code]',

							'$_POST[subledger_id]',

							'UNCHECKED',

							'$ip',

							'$inirow[voucher_date]',

							'$_SESSION[sectionid]',

							'$_SESSION[companyid]',

							'$_SESSION[userid]','','','$tdates')";

                                            $query_receipt = mysql_query($recept);





                                            ?>



                                        <?php }}} ?>













                                <?php
                                if($_SESSION[initiate_bank_debit_note]){
                                ?>

                                                <form action="" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
                                                    <input type="hidden" name="payment_no" id="payment_no" value="<?=$_SESSION[initiate_debit_note];?>">
                                                    <input type="hidden" name="receipt_date" id="receipt_date" value="<?=$voucher_date;?>">
                                                    <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
                                                    <input type="hidden" name="paid_to" id="paid_to" value="<?=$paid_to;?>">
                                                    <input type="hidden" name="Cheque_No" id="Cheque_No" value="<?=$Cheque_No;?>">
                                                    <input type="hidden" name="Cheque_Date" id="Cheque_Date" value="<?=$Cheque_Date;?>">
                                                    <input type="hidden" name="Cheque_of_bank" id="Cheque_of_bank" value="<?=$Cheque_of_bank;?>">


                                                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                                                        <tbody>
                                                        <tr style="background-color: bisque">
                                                            <th style="text-align: center">Accounts Ledger</th>
                                                            <th style="text-align: center">Cost Center</th>
                                                            <th style="text-align: center">Narration</th>
                                                            <th style="text-align: center">Attachment</th>
                                                            <th style="width:5%; text-align:center">Amount</th>
                                                            <th style="text-align:center">#</th>
                                                        </tr>
                                                        <tbody>
                                                        <tr>
                                                            <td style="width: 25%; vertical-align: middle" align="center">
                                                                <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_id">
                                                                    <option></option>
                                                                    <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id,"-", ledger_name)', $ledger_id, ' ledger_group_id not in  ("1006")'); ?>
                                                                </select></td>
                                                            <td align="center" style="width: 10%;vertical-align: middle">
                                                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="cc_code" id="cc_code">
                                                                    <option></option>
                                                                    <?php foreign_relation('cost_center', 'id', 'CONCAT(id,"-", center_name)', $cc_code, 'status in ("1")'); ?>
                                                                </select></td>
                                                            <td style="width:15%;vertical-align: middle" align="center">
                                                                <input type="text" id="narration" style="width:100%; height:37px; font-size: 11px; text-align:center"  name="narration" value="<?=$_POST[narration];?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                                                            <td style="width:10%;vertical-align: middle" align="center">
                                                                <input type="file" id="attachment" style="width:100%; height:37px; font-size: 11px; text-align:center"    name="attachment" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                                                            <td align="center" style="width:10%">
                                                                <input type="text" id="dr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center"    name="dr_amt" placeholder="Debit" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                                                                <input type="text" id="cr_amt" style="width:98%; height:25px; font-size: 11px; text-align:center; margin-top: 5px"    name="cr_amt" placeholder="Credit" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                                                            <td align="center" style="width:5%; vertical-align: middle "><button type="submit" class="btn btn-primary" name="add" id="add">Add</button></td></tr>
                                                        </tbody>
                                                    </table>
                                                    <input name="count" id="count" type="hidden" value="" />
                                                </form>
                                                </form>



































                                <!-----------------------Data Save Confirm ------------------------------------------------------------------------->



                                <?php

                                if($_GET[type]=='delete'){

                                    if($_GET[productdeletecode]){



                                        $results=mysql_query("Delete from secondary_payment where id='$_GET[productdeletecode]'"); ?>

                                        <meta http-equiv="refresh" content="0;debit_note_bank.php">





                                    <?php }} ?>





                                <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left" style="font-size: 11px">

                                    <table  class="table table-striped table-bordered" style="width:100%">

                                        <thead>

                                        <tr>

                                            <th>SL</th>

                                            <th>Account Head</th>

                                            <th style="text-align:center">CC Center</th>

                                            <th style="text-align:center">Narration</th>

                                            <th style="width:10%; text-align:center">Debit</th>

                                            <th style="width:10%; text-align:center">Credit</th>

                                            <th style="width:15%; text-align:center">Action</th>



                                        </tr>

                                        </thead>





                                        <tbody>







                                        <?php



                                        $usergroup=$_SESSION['user']['group'];

                                        $rs=mysql_query("Select 

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

a.*,c.center_name as cname from 



secondary_payment j,

 accounts_ledger a,cost_center c

  where 

 j.ledger_id=a.ledger_id and 

 j.cc_code=c.id and

 entry_status='UNCHECKED' and 

 j.payment_no='".$_SESSION['initiate_bank_debit_note']."'

 ");

                                        while($uncheckrow=mysql_fetch_array($rs)){







                                            if (isset($_POST['confirmsave'])){

                                            $journal="INSERT INTO `secondary_journal_bank` (

							`proj_id` ,

							`jvdate`,

							`jv_no` ,

							`jv_date` ,

							`ledger_id` ,

							`narration` ,

							`dr_amt` ,

							`cr_amt` ,

							`tr_from` ,

							`tr_no`,

							`tr_id`,

							`cc_code` 

							,user_id

							,group_for,

`cheq_no`,`cheq_date`,

							relavent_cash_head,

							sub_ledger_id,`ip`,sub_ledger,section_id,company_id,create_date

							)

			VALUES ('icpbd','$uncheckrow[paymentdate]', '$jvbank', '$date', '$uncheckrow[ledger_id]', '$uncheckrow[narration]', '$uncheckrow[dr_amt]','$uncheckrow[cr_amt]', 'Payment', '$uncheckrow[payment_no]', '$uncheckrow[jid]', '$uncheckrow[cc_code]','$_SESSION[userid]','$_SESSION[usergroup]','$uncheckrow[cheq_no]','$uncheckrow[cheq_date]','$uncheckrow[ledger_id]','$uncheckrow[sub_ledger_id]','$ip','$uncheckrow[sub_ledger_id]'

			,'$_SESSION[sectionid]','$_SESSION[companyid]','$tdates')";

                                            $query_journal = mysql_query($journal);



                                            mysql_query("UPDATE secondary_payment SET entry_status='' WHERE payment_no='".$_SESSION['initiate_bank_debit_note']."'");

                                            //mysql_query("UPDATE journal SET cc_code='0' WHERE ledger_id between '1001000100000000' and '3002000600000000'");



                                                unset($_SESSION['initiate_bank_debit_note']);

                                            ?>



                                            <meta http-equiv="refresh" content="0;debit_note_bank.php">

                                        <?php }

                                            $js=$js+1;
                                            $ids=$uncheckrow[jid];
                                            $updr_amt=$_POST['updr_amt'.$ids];
                                            $upcr_amt=$_POST['upcr_amt'.$ids];
                                            $upnarration=$_POST['upnarration'.$ids];

                                            if(isset($_POST['deletedata'.$ids]))

                                            {   mysql_query("DELETE FROM secondary_payment WHERE id='$ids'"); ?>
                                                <meta http-equiv="refresh" content="0;debit_note_bank.php">
                                                <?php  }

                                            if(isset($_POST['editdata'.$ids]))
                                            {
                                                mysql_query("UPDATE secondary_payment SET dr_amt='".$updr_amt."',cr_amt='".$upcr_amt."',narration='".$upnarration."' WHERE id='$ids'");?>
                                                <meta http-equiv="refresh" content="0;debit_note_bank.php">
                                            <?php }?>

                                           <tr>
                                                <td style="width:3%; vertical-align:middle"><?php echo $js; ?></td>
                                                <td style="vertical-align:middle"><?=$uncheckrow[ledger_name] ;?></td>
                                                <td style="vertical-align:middle"><?=$uncheckrow[cname] ;?></td>
                                                <td style="vertical-align:middle; width: 20%">
                                                    <input type="text" id="upnarration<?php echo $ids; ?>" style="width:98%; height:37px; font-weight:bold; text-align:center; font-size: 11px"    name="upnarration<?php echo $ids; ?>"  value="<?=$uncheckrow[narration] ;?>" class="form-control col-md-7 col-xs-12" autocomplete="off"  >
                                                    </td>
                                                <td align="center" style="width:6%; text-align:center">
                                                    <input type="text" id="updr_amt<?php echo $ids; ?>" style="width:110px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none; font-size: 11px"    name="updr_amt<?php echo $ids; ?>"  value="<?=$uncheckrow[dr_amt] ;?>" class="form-control col-md-7 col-xs-12" autocomplete="off" <? if($uncheckrow[dr_amt]==0) echo  'readonly';?> ></td>
                                                <td align="center" style="width:6%; text-align:center">
                                                    <input type="text" id="upcr_amt<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none; font-size: 11px"    name="upcr_amt<?php echo $ids; ?>"  value="<?=$uncheckrow[cr_amt] ;?>" class="form-control col-md-7 col-xs-12" autocomplete="off" <? if($uncheckrow[cr_amt]==0) echo  'readonly';?> ></td>
                                                <td align="center" style="width:10%;vertical-align:middle">
                                                    <button type="submit" name="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Edit Date?");'><img src="update-icon.png" style="width:20px;  height:20px"></button>
                                                    <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete Journal Voucher?");'><img src="delete.png" style="width:20px;  height:20px"></button>
                                                </td>
                                            </tr>


                                            <?php
                                            $totaldr=$totaldr+$uncheckrow[dr_amt];
                                            $totalcr=$totalcr+$uncheckrow[cr_amt];
                                            } ?>




                                        </tbody>
                                        <tr>
                                            <td colspan="4" style="font-weight:bold;" align="right">Total</td>
                                            <td align="center" ><strong><?php echo number_format($totaldr,2); ?></strong></td>
                                            <td align="center" ><strong><?php echo number_format($totalcr,2); ?></strong></td>
                                            <td></td>
                                        </tr>
                                    </table>

                                    <?php
                                    $cancel=$_POST[cancel];
                                    if(isset($cancel)){
                                        $deletes=mysql_query("Delete From journal_voucher_master where voucherno='$_SESSION[initiate_bank_debit_note]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                                        $deletes=mysql_query("Delete From secondary_payment where payment_no='$_SESSION[initiate_bank_debit_note]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                                        unset($_SESSION["initiate_bank_debit_note"]); ?>
                                        <meta http-equiv="refresh" content="0;debit_note_bank.php">
                                    <?php } ?>
                                    <button style="float: left" type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Delete the Voucher </button>
                                    <?php if($totaldr==$totalcr){ ?>
                                        <button style="float: right" type="submit" name="confirmsave" class="btn btn-success">Confirm and Finish Voucher </button>
                                    <?php } else { ?>
                                        <font  style="font-size: 15px; color: red; font-weight: bold; float: right">Invalid Debit Bank Voucher!!</font>
                                    <?php } ?>
                                </form></div></div></div>
        <?php } ?>





<?php require_once 'footer_content.php' ?>
    <!-- Select2 -->

    <script>

        $(document).ready(function() {

            $(".select2_single").select2({

                placeholder: "Select a Choose",

                allowClear: true

            });

            $(".select2_group").select2({});

            $(".select2_multiple").select2({

                maximumSelectionLength: 4,

                placeholder: "With Max Selection limit 4",

                allowClear: true

            });

        });

    </script>

    <!-- /Select2 -->











    <script>

        $(document).ready(function() {

            $('#nam_date').daterangepicker({



                singleDatePicker: true,

                calender_style: "picker_4",



            }, function(start, end, label) {

                console.log(start.toISOString(), end.toISOString(), label);

            });

        });

    </script>





    <script>

        $(document).ready(function() {

            $('#c_date').daterangepicker({



                singleDatePicker: true,

                calender_style: "picker_4",



            }, function(start, end, label) {

                console.log(start.toISOString(), end.toISOString(), label);

            });

        });

    </script>


