<?php
require_once 'support_file.php';
$title='Receipt Voucher';


//Image Attachment Function
function image_upload_on_id2($path,$file,$id)
{   $root=$path.'/'.$id.'.jpg';
    move_uploaded_file($file['tmp_name'],$root);
    return $root;
}
function image_upload_on_id($path,$file,$id='')
{    if($file['name']!=''){
        $path_file = $path.basename($file['name']);
        $imageFileType = pathinfo($path_file,PATHINFO_EXTENSION);
        $root=$path.'/'.$id.'.'.$imageFileType;
        if($imageFileType != "jpg" && $imageFileType != "pdf" )
        {}
        else
        move_uploaded_file($file['tmp_name'],$root);
        return $root;
    }}
//Image Attachment Function

?>

<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]:focus {
        background-color: lightblue;
    }
</style>
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
                                $jv=next_journal_voucher_id();

                                $d =$_POST[nam_date];
                                $transaction_date=date('Y-m-d' , strtotime($d));
                                $initiate=$_POST[initiate];
                                $enat=date('Y-m-d h:s:i');
                                $cd =$_POST[c_date];
                                $c_date=date('Y-m-d' , strtotime($cd));
                                $invoice=$_POST[invoice];


                                /////////////// if issent initiate
                                if(isset($initiate)){
                                    add_to_journal_master($invoice,$transaction_date, $_POST[r_from], $_POST[c_no], $c_date, $_POST[bank], MANUAL, $_POST[remarks],Receipt,$_POST[PBI_ID],$ip);
                                    $_SESSION[initiate_credit_note]=$invoice;} // }



                                ////////////////// if isset update journal master
                                if(isset($_POST[updateMAN])){
                                    journal_master_update($transaction_date,$_POST[r_from], $_POST[c_no], $c_date,$_POST[bank],$_POST[remarks],$_POST[PBI_ID],$_SESSION[initiate_credit_note]);
                                    ?>
                                    <meta http-equiv="refresh" content="0;credit_note.php">
                                <?php  }




                                $resultsssss=mysql_query("Select * from journal_voucher_master where voucherno='$_SESSION[initiate_credit_note]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]' ");
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




                                <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px" >
                                    <table align="center" style="width:100%">
                                        <tr>
                                            <td style="width:50%;">
                                                <div class="form-group" style="width: 100%">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Date<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="nam_date"  required="required" name="nam_date" value="<?php if($_SESSION[initiate_credit_note]){ echo date('m/d/y' , strtotime($inirow[voucher_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" style="width: 130px; font-size: 11px;" ><br>

                                                    </div>
                                                </div></td>


                                            <td style="width:50%"><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 40%">Transaction No<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="last-name"   required="required" name="invoice" value="<?php if($_SESSION[initiate_credit_note]){ echo $inirow[voucherno];} else { echo
                                                        $_SESSION['creditvoucherNOW']; } ?>" class="form-control col-md-7 col-xs-12"  readonly style="width: 130px; font-size: 11px;">
                                                    </div>
                                                </div>
                                            </td></tr>




                                        <tr>

                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Received From:<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="r_from"  value="<?php if($_SESSION[initiate_credit_note]){ echo $inirow[paid_to];} else { echo ''; } ?>" name="r_from" class="form-control col-md-7 col-xs-12" required style="width: 130px; margin-top: 5px; font-size: 11px;" >
                                                    </div></div></td>


                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Employee<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="PBI_ID"  value="<?php if($_SESSION[initiate_credit_note]){ echo $inirow[PBI_ID];} else { echo ''; } ?>" name="PBI_ID" class="form-control col-md-7 col-xs-12"  style="width: 130px; margin-top: 5px; font-size: 11px;">
                                                    </div></div></td></tr>

                                        <tr> <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Cheque No<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="c_no"  value="<?php if($_SESSION[initiate_credit_note]){ echo $inirow[Cheque_No];} else { echo ''; } ?>" name="c_no"  class="form-control col-md-7 col-xs-12" style="width: 130px; margin-top: 5px; font-size: 11px;" ></div></div></td>


                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Cheque Date<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="c_date"   value="<?php if($_SESSION[initiate_credit_note]){ echo date('m/d/y' , strtotime($inirow[Cheque_Date]));} else { echo ''; } ?>" name="c_date"  class="form-control col-md-7 col-xs-12"  style="width: 130px; margin-top: 5px; font-size: 11px;">
                                                    </div></div></td></tr>

                                        <tr>
                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Of Bank<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="bank" id="bank" value="<?=$inirow[Cheque_of_bank]?>" class="form-control col-md-7 col-xs-12" style="width: 130px; margin-top: 5px; font-size: 11px;">
                                                    </div></div> </td>

                                            <td><div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 40%">Remarks<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="remarks" id="remarks" value="<?=$inirow[remarks]?>" class="form-control col-md-7 col-xs-12" style="width: 130px; margin-top: 5px; font-size: 11px;"></div></div></td>
                                        </tr></table>


                                <div class="form-group" style="margin-left:40%; margin-top: 15px">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($_SESSION[initiate_credit_note]){  ?>
                                                <!---a href="Incoming_Material_Received.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
                                                <button type="submit" name="updateMAN" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");'>Update Receipt Voucher</button>

                                            <?php   } else {?>
                                                <button type="submit" name="initiate" class="btn btn-primary">Initiate Receipt Voucher</button>
                                            <?php } ?>
                                        </div></div>
                                </form></div></div></div>










                    <div class="col-md-4 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Recent Voucher List</h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content" style="overflow:scroll; height:210px;">
                                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                                        <tr>
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Voucher No </th>
                                            <th>Amount</th>
                                        </tr>

                                        <?
                                        $sql2="select a.tr_no, a.jv_no,a.jv_no,a.jvdate,SUM(a.dr_amt) as amount
from  journal a where a.tr_from='Receipt' and a.user_id='$_SESSION[userid]' and a.section_id='$_SESSION[sectionid]' and a.company_id='$_SESSION[companyid]'  group by a.tr_no  order by a.tr_no desc limit 10";
                                        $data2=mysql_query($sql2);
                                        if(mysql_num_rows($data2)>0){
                                            while($dataa=mysql_fetch_row($data2)){ ?>
                                                <tr class="alt">
                                                    <td><?=$is=$is+1;?></td>
                                                    <td style="text-align: left; cursor: pointer" onclick="OpenPopupCenter('voucher_view_popup_ismail.php?<?php echo 'v_type=receipt&vdate='.$dataa[3].'&v_no='.$dataa[2].'&view=Show&in=receipt' ?>', 'TEST!?', 900, 600);"><?=$dataa[3]?></td>
                                                    <td><a href="voucher_print1.php?v_type=Receipt&vo_no=<?php echo $dataa[2];?>"  target="_blank"><?=$dataa[0]?></a></td>
                                                    <td style="text-align: right"><?=number_format($dataa[4],2)?></td>
                                                </tr> <? }} else {?>
                                        <tr><td colspan="4" style="text-align: center">No data available in table</td></tr>
                                        <?php } ?>
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

                        if($_FILES["attachment"]["tmp_name"]!=''){
                            $path='../page/receipt_attch/'.$_SESSION[initiate_credit_note].'.jpg';
                            move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
                        }


                        if($_POST[dr_amt]>0) {
                            $type='Debit';
                        }
                        elseif ($_POST[cr_amt]>0) {
                            $type='Credit';
                        }


                        if(prevent_multi_submit()){
                            if($_POST[dr_amt]>0 || $_POST[cr_amt]>0){

                                add_to_receipt($_SESSION[initiate_credit_note],$date, $proj_id, $_POST[narration], $_POST[ledger_id], $_POST[dr_amt],
                                    $_POST[cr_amt], $type,$cur_bal,$inirow[paid_to],$inirow[Cheque_No],$c_date,$inirow[Cheque_of_bank],$manual_payment_no,$_POST[cc_code],$_POST[subledger_id],UNCHECKED,$ip,$inirow[voucher_date],$_SESSION[sectionid],$_SESSION[companyid],$_SESSION[userid],$create_date,$now,$day
                                    ,$thisday,$thismonth,$thisyear,$receive_ledger);  ?> <?php }}} ?>






                    <?php if($_SESSION[initiate_credit_note]){  ?>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <form action="" enctype="multipart/form-data" name="addem" id="addem" style="font-size: 11px" class="form-horizontal form-label-left" method="post">
                                    <table style="width:100%">
                                        <tbody>
                                        <tr>
                                            <td align="left" style="width: 22%;">
                                                <select class="select2_single form-control" style="width:98%" tabindex="-1" required="required"  name="ledger_id">
                                                    <option></option>
                                                    <?php
                                                    $result=mysql_query("SELECT * from accounts_ledger  where 1 order by ledger_id");
                                                    while($row=mysql_fetch_array($result)){  ?>
                                                        <option  value="<?php echo $row[ledger_id]; ?>"><?php echo $row[ledger_id]; ?>-<?php echo $row[ledger_name]; ?></option>
                                                    <?php } ?>
                                                </select></td>

                                            <!--td align="left" style="width: 20%">
                                                <select class="select2_single form-control" style="width:210px" tabindex="-1"   name="subledger_id">
                                                    <option></option>
                                                    <?php
                                                    $result=mysql_query("SELECT * from sub_ledger  where 1 order by sub_ledger_id");
                                                    while($row=mysql_fetch_array($result)){  ?>
                                                        <option  value="<?php echo $row[sub_ledger_id]; ?>"><?php echo $row[sub_ledger_id]; ?>-<?php echo $row[sub_ledger]; ?></option>
                                                    <?php } ?>
                                                </select></td-->


                                            <td align="left" style="width: 15%;">
                                                <select class="select2_single form-control" style="width:98%" tabindex="-1"   name="cc_code">
                                                    <option></option>
                                                    <?php
                                                    $result=mysql_query("SELECT * from cost_center  where 1 order by id");
                                                    while($row=mysql_fetch_array($result)){  ?>
                                                        <option  value="<?php echo $row[id]; ?>"><?php echo $row[id]; ?>-<?php echo $row[center_name]; ?></option>
                                                    <?php } ?>
                                                </select></td>


                                            <td style="width:15%;" align="center">
                                                <input type="text" id="narration" style="width:200px; height:37px; font-size: 11px; text-align:center"    name="narration" placeholder="Narration"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>


                                            <td style="width:10%;" align="center">
                                                <input type="file" id="attachment" style="width:150px; height:37px; font-size: 11px; text-align:center"    name="attachment" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>


                                            <td align="center" style="width:8%">
                                                <input type="text" id="dr_amt" style="width:100px; height:30px; font-size: 11px; text-align:center"    name="dr_amt" placeholder="Dr Amount" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                                                <input type="text" id="cr_amt" style="width:100px; height:30px; font-size: 11px; text-align:center; margin-top: 10px"    name="cr_amt" placeholder="Cr Amount" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                                            <td align="center" style="width:5%; "><button type="submit" class="btn btn-primary" name="add" id="add">Add</button></td></tr>





                                        </tbody>
                                    </table>
                                    <input name="count" id="count" type="hidden" value="" />
                                </form>
                            </div></div></div></div>


                <!-----------------------Data Save Confirm ------------------------------------------------------------------------->

                <?php
                if($_GET[type]=='delete'){
                    if($_GET[productdeletecode]){
                        $results=mysql_query("Delete from receipt where id='$_GET[productdeletecode]'"); ?>
                        <meta http-equiv="refresh" content="0;credit_note.php">
                    <?php }} ?>


                <form id="ismail" name="ismail"  method="post" style="font-size: 11px"  class="form-horizontal form-label-left">
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
                        $usergroup=$_SESSION['usergroup'];
                        $rs=mysql_query("Select 
j.id as jid,
j.receipt_no,
j.receipt_date,
j.receiptdate,
j.narration,
j.ledger_id,
j.dr_amt,
j.cr_amt,
j.type,
j.cheq_no,
j.cheq_date,
j.bank,
j.cc_code,
j.sub_ledger_id,
a.*,c.center_name as cname 

from 
receipt j,
accounts_ledger a,
cost_center c
  where 
 j.ledger_id=a.ledger_id and 
 j.cc_code=c.id and
 entry_status='UNCHECKED' and 
 j.receipt_no='".$_SESSION['initiate_credit_note']."'
 ");
                        while($uncheckrow=mysql_fetch_array($rs)){


                            //if(prevent_multi_submit()){
                            if (isset($_POST['confirmsave'])){
                                add_to_journal_new($uncheckrow[receiptdate],$proj_id, $jv, $date, $uncheckrow[ledger_id], $uncheckrow[narration], $uncheckrow[dr_amt], $uncheckrow[cr_amt],Receipt, $uncheckrow[receipt_no],$uncheckrow[jid],$uncheckrow[cc_code],$uncheckrow[sub_ledger_id],$_SESSION[usergroup],$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear);
                                mysql_query("UPDATE receipt SET entry_status='' WHERE receipt_no='".$_SESSION['initiate_credit_note']."'");
                                unset($_SESSION['initiate_credit_note']);
                                ?> <meta http-equiv="refresh" content="0;credit_note.php"> <?php }





                            $js=$js+1;
                            $ids=$uncheckrow[jid];
                            $ledger_code_update=$_POST['ledger_code_update'.$ids];
                            $cc_code_update=$_POST['cc_code_update'.$ids];
                            $updr_amt=$_POST['updr_amt'.$ids];
                            $upcr_amt=$_POST['upcr_amt'.$ids];
                            $upnarration=$_POST['upnarration'.$ids];


                            if(isset($_POST['deletedata'.$ids]))
                            {
                                mysql_query("DELETE FROM receipt WHERE id='$ids'"); ?>
                                <meta http-equiv="refresh" content="0;credit_note.php">
                                <?php
                            }

                            if(isset($_POST['editdata'.$ids]))
                            {
                                update_receipt_add_data($ledger_code_update,$cc_code_update,$updr_amt,$upcr_amt,$upnarration,$ids); ?>
                                <meta http-equiv="refresh" content="0;credit_note.php">
                            <?php }?>


                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $js; ?></td>
                                <td style="vertical-align:middle">

                                    <select class="select2_single form-control" style="width:300px" tabindex="-1" required="required" id="ledger_code_update<?php echo $ids; ?>"   name="ledger_code_update<?php echo $ids; ?>">
                                        <option value="<?=$uncheckrow[ledger_id] ;?>" selected><?=$uncheckrow[ledger_id] ;?>-<?=$uncheckrow[ledger_name] ;?></option>
                                        <?php
                                        $result=mysql_query("SELECT * from accounts_ledger  where 1 order by ledger_id");
                                        while($row=mysql_fetch_array($result)){  ?>
                                            <option  value="<?php echo $row[ledger_id]; ?>"><?php echo $row[ledger_id]; ?>-<?php echo $row[ledger_name]; ?></option>
                                        <?php } ?>
                                    </select> </td>


                                <td style="vertical-align:middle" width="80px">
                                    <select class="select2_single form-control" style="width:120px" tabindex="-1"  id="cc_code_update<?php echo $ids; ?>"  name="cc_code_update<?php echo $ids; ?>" ">
                                    <option value="<?=$uncheckrow[cc_code] ;?>" selected ><?=$uncheckrow[cc_code] ;?>-<?=$uncheckrow[cname] ;?></option>
                                    <?php
                                    $result=mysql_query("SELECT * from cost_center  where 1 order by id");
                                    while($row=mysql_fetch_array($result)){  ?>
                                        <option  value="<?php echo $row[id]; ?>"><?php echo $row[id]; ?>-<?php echo $row[center_name]; ?></option>
                                    <?php } ?>
                                    </select>


                                    <!--input type="text" id="cname<?php echo $ids; ?>" style="width:50px; height:37px; font-weight:bold; text-align:center;"    name="cname<?php echo $ids; ?>"  value="<?=$uncheckrow[cname] ;?>" class="form-control col-md-7 col-xs-12" autocomplete="off" --->
                                </td>
                                <td style="vertical-align:middle; width: 20%">
                                    <input type="text" id="upnarration<?php echo $ids; ?>" style="width:98%; height:37px; font-weight:bold; text-align:center; font-size: 11px"    name="upnarration<?php echo $ids; ?>"  value="<?=$uncheckrow[narration] ;?>" class="form-control col-md-7 col-xs-12" autocomplete="off"  >
                                </td>

                                <td align="center" style="width:6%; text-align:center">
                                    <input type="text" id="updr_amt<?php echo $ids; ?>" style="width:110px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none; font-size: 11px"    name="updr_amt<?php echo $ids; ?>"  value="<?=$uncheckrow[dr_amt] ;?>" class="form-control col-md-7 col-xs-12" autocomplete="off" <? if($uncheckrow[dr_amt]==0) echo  'readonly';?> ></td>


                                <td align="center" style="width:6%; text-align:center">
                                    <input type="text" id="upcr_amt<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none; font-size: 11px"    name="upcr_amt<?php echo $ids; ?>"  value="<?=$uncheckrow[cr_amt] ;?>" class="form-control col-md-7 col-xs-12" autocomplete="off" <? if($uncheckrow[cr_amt]==0) echo  'readonly';?> ></td>



                                <td align="center" style="width:10%;vertical-align:middle">
                                    <button type="submit" name="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Edit Date?");'><img src="update.jpg" style="width:20px;  height:20px"></button>
                                    <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete Credit Voucher?");'><img src="delete.png" style="width:20px;  height:18px"></button>
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
                        </tr></table>

                    <?php

                    $cancel=$_POST[cancel];
                    if(isset($cancel)){
                        $deletes=mysql_query("Delete From journal_voucher_master where voucherno='$_SESSION[initiate_credit_note]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                        $deletes=mysql_query("Delete From receipt where receipt_no='$_SESSION[initiate_credit_note]' and section_id='$_SESSION[sectionid]' and company_id='$_SESSION[companyid]'");
                        unset($_SESSION["initiate_credit_note"]); ?>
                        <meta http-equiv="refresh" content="0;credit_note.php">
                    <?php } ?>
                    <button style="float: left" type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Delete the Voucher </button>
                    <?php if($totaldr==$totalcr){ ?>
                        <button style="float: right" type="submit" name="confirmsave" class="btn btn-success">Confirm and Finish Voucher </button>
                    <?php } else { ?>
                        <font  style="font-size: 15px; color: red; font-weight: bold; float: right">Invalid Credit Voucher!!</font>
                    <?php } ?>

                </form></div></div></div>
    <?php } ?>

<?php require_once 'footer_content.php' ?>
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