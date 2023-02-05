<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Pending Stationary Purchased";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');
$_SESSION[postdate]=date('Y-m-d');

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");


$unique='or_no';
$unique_field='or_date';
$table="warehouse_other_receive";
$table_details="warehouse_other_receive_detail";
$current_status=find_a_field("".$table."","status","".$unique."=".$_GET[$unique]."");
$required_status="CHECKED";
$page="accounts_stationary_purchased_verified.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$spdetails=find_all_field(''.$table.'','',''.$unique.'='.$_GET[$unique].'');

if(prevent_multi_submit()){

    if(isset($_POST['Return']))
    {
        $_POST['status']='RETURNED';
        $_POST['return_comments']=$_POST['return_comments'];
        $_POST['return_date']=$todayss;
        $crud->update($unique);
        $type=1;
        //echo $targeturl;
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
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
    if(isset($_POST['Deleted']))
    {   $condition=$unique."=".$$unique;
        $crud->delete($condition);
        $crud = new crud($table_details);
        $condition = $unique . "=" . $$unique;
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
?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 250,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>

<?php if(!isset($_GET[$unique])){ ?>
    <!-------------------list view ------------------------->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>List of <?=$title;?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;font-size:11px">
                    <thead>
                    <tr>
                        <th style="text-align:center">#</th>
                        <th style="text-align:center">PO No</th>
                        <th style="text-align:center">Purchase Date</th>
                        <th style="text-align:center">Remarks</th>
                        <th style="text-align:center">Vendor Name</th>
                        <th style="text-align:center">Chalan No</th>
                        <th style="text-align:center">Requisition From</th>
                        <th style="text-align:center">Purchased By</th>
                        <th style="text-align:center">Recommended By</th>
                        <th style="text-align:center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? 	$res=mysql_query('select r.'.$unique.',r.'.$unique.' as PO_NO,r.'.$unique_field.' as Purchased_Date,r.recommended_date,r.requisition_from,r.vendor_name,r.chalan_no,
				 (SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.PBI_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as Purchased_By,r.or_subject as Remarks,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.recommended_by) as recommended_by,
							 (select PBI_NAME from personnel_basic_info where PBI_ID=r.approved_by) as authorised_person,r.status,r.approved_date
				  from '.$table.' r
				  WHERE   				   	  
				  status="'.$required_status.'" 	  
				   order by r.'.$unique.' DESC');
                    while($req=mysql_fetch_object($res)){

                        ?>
                        <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)">
                            <td><?=$i=$i+1;?></td>
                            <td><?=$req->$unique;?></td>
                            <td><?=$req->Purchased_Date;?></td>
                            <td><?=$req->Remarks;?></td>
                            <td><?=$req->vendor_name;?></td>
                            <td><?=$req->chalan_no;?></td>
                            <td><?=$req->requisition_from;?></td>
                            <td><?=$req->Purchased_By;?></td>
                            <td><?=$req->recommended_by;?></td>
                            <td><?=$req->status;?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div></div>
    <!-------------------End of  List View --------------------->
<?php } ?>
<?php if(isset($_GET[$unique])){ ?>


    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <br />

                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>



                    <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                        <thead>
                        <tr>
                        <tr>
                            <th>#</th>
                            <th>Item ID</th>
                            <th>Item Description</th>
                            <th style="text-align:center">Unit Name</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:center">Rate</th>
                            <th style="text-align:center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php 	$res=mysql_query('Select td.*,i.* from '.$table_details.' td,
                 item_info i
				  where 
				  td.item_id=i.item_id and			  
				  td.'.$unique.'='.$_GET[$unique].'');
                        while($req_data=mysql_fetch_object($res)){ ?>
                            <tr>
                                <td style="text-align: center"><?=$i=$i+1;?></td>
                                <td><?=$req_data->item_id;?></td>
                                <td><?=$req_data->item_name;?></td>
                                <td style="text-align: center"><?=$req_data->pack_unit;?></td>
                                <td style="text-align: center"><?=$req_data->qty;?> </td>
                                <td style="text-align: right"><?=number_format($req_data->rate,2);?></td>
                                <td style="text-align: right"><?=number_format($req_data->amount,2);?></td>
                            </tr>
                            <?php     $total_amount=$total_amount+$req_data->amount; } ?>
                        <tr style="font-weight: bold">
                            <td colspan="6" style="text-align: right">Total Amount = </td><td style="text-align: right"><?=number_format($total_amount,2)?></td>
                        </tr>
                        </tbody>

                    </table>




                    <table class="table table-striped table-bordered" style="width:100%;font-size:11px">
                        <thead>
                        <tr>
                        <tr>
                            <th>#</th>
                            <th>Accounts Description</th>
                            <th>Cost Center</th>
                            <th style="text-align:center">Narration</th>
                            <th style="text-align:center">Debit</th>
                            <th style="text-align:center">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center">1</td>
                                <td><select class="select2_single form-control" style="width:300px" tabindex="-1" required="required"  name="dr_ledger_id">
                                        <option value="4006000800000000">4006000800000000 - <?=find_a_field('accounts_ledger','ledger_name','ledger_id="4006000800000000"');?></option>
                                        <?php
                                        $result=mysql_query("SELECT * from accounts_ledger  where 1 order by ledger_id");
                                        while($row=mysql_fetch_array($result)){  ?>
                                            <option  value="<?php echo $row[ledger_id]; ?>"><?php echo $row[ledger_id]; ?>-<?php echo $row[ledger_name]; ?></option>
                                        <?php } ?>
                                    </select></td>
                                <td><select class="select2_single form-control" style="width:120px" tabindex="-1"   name="dr_cc_code">
                                        <option value="23" selected>Administration</option>
                                        <?php
                                        $result=mysql_query("SELECT * from cost_center  where 1 order by id");
                                        while($row=mysql_fetch_array($result)){  ?>
                                            <option  value="<?php echo $row[id]; ?>"><?php echo $row[id]; ?>-<?php echo $row[center_name]; ?></option>
                                        <?php } ?>
                                    </select></td>
                                <td style="text-align: center"><input type="text" name="dr_narration" id="dr_narration" value="Stationary Purchased, For#<?=$spdetails->requisition_from;?>, Vendor#<?=$spdetails->vendor_name;?>, Challan#<?=$spdetails->chalan_no;?>, ID#<?=$_GET[$unique];?>" class="form-control col-md-7 col-xs-12" style="width:100px; height:35px; font-size: 11px; text-align:center"></td>
                                <td style="text-align: right"><?=number_format($total_amount,2);?></td>
                                <td style="text-align: right">0.00</td>
                            </tr>

                            <tr>
                                <td style="text-align: center">2</td>
                                <td><select class="select2_single form-control" style="width:300px" tabindex="-1" required="required"  name="cr_ledger_id">
                                        <option value="<?=$vendor_id_GET=find_a_field('vendor','ledger_id','vendor_name="Local Purchase"');?>" selected ><?=$vendor_id_GET=find_a_field('vendor','ledger_id','vendor_name="Local Purchase"');?>-<?=find_a_field('vendor','vendor_name','ledger_id='.$vendor_id_GET.'');?></option>
                                        <?php
                                        $result=mysql_query("SELECT * from accounts_ledger  where 1 order by ledger_id");
                                        while($row=mysql_fetch_array($result)){  ?>
                                            <option  value="<?php echo $row[ledger_id]; ?>"><?php echo $row[ledger_id]; ?>-<?php echo $row[ledger_name]; ?></option>
                                        <?php } ?>
                                    </select></td>

                                <td><select class="select2_single form-control" style="width:120px" tabindex="-1"   name="cr_cc_code">
                                        <option value="0" selected></option>
                                        <?php
                                        $result=mysql_query("SELECT * from cost_center  where 1 order by id");
                                        while($row=mysql_fetch_array($result)){  ?>
                                            <option  value="<?php echo $row[id]; ?>"><?php echo $row[id]; ?>-<?php echo $row[center_name]; ?></option>
                                        <?php } ?>
                                    </select></td>
                                <td style="text-align: center"><input type="text" name="cr_narration" id="cr_narration" value="Stationary Purchased, For#<?=$spdetails->requisition_from;?>, Vendor#<?=$spdetails->vendor_name;?>, Challan#<?=$spdetails->chalan_no;?>, ID#<?=$_GET[$unique];?>" class="form-control col-md-7 col-xs-12" style="width:100px; height:35px; font-size: 11px; text-align:center"></td>
                                <td style="text-align: right">0.00</td>
                                <td style="text-align: right"><?=number_format($total_amount,2);?></td>
                            </tr>

                        </tbody>

                    </table>

                    <?php
                    $jv=next_journal_voucher_id();
                    $receipt_no = $_SESSION['debitvoucherNOW'];
                    $dotoday=date('Y-m-d');
                    $transaction_con_date=date('Y-m-d');
                    $tfrom='Local Purchase';


        $date = date('d-m-y');
        $j = 0;
        for ($i = 0; $i < strlen($date); $i++) {
            if (is_numeric($date[$i])) {
                $time[$j] = $time[$j] . $date[$i];
            } else {
                $j++;
            }
        }
        $date = mktime(0, 0, 0, $time[1], $time[0], $time[2]);
        $_SESSION[postdate] = $date;


                    if(isset($_POST[Approved])){

                        add_to_journal_new($transaction_con_date,$proj_id, $jv, $_SESSION[postdate], $_POST[dr_ledger_id], $_POST[dr_narration], $total_amount, 0,$tfrom, $receipt_no,$_GET[$unique],$_POST[dr_cc_code],$jvrow[sub_ledger_id],$_SESSION[usergroup],$jvrow[cheq_no],$jvrow[cheq_date],$create_date,$ip,$now,date('D'),$thisday,$thismonth,$thisyear);
                        add_to_journal_new($transaction_con_date,$proj_id, $jv, $_SESSION[postdate], $_POST[cr_ledger_id], $_POST[cr_narration], 0, $total_amount,$tfrom, $receipt_no,$_GET[$unique],$_POST[cr_cc_code],$jvrow[sub_ledger_id],$_SESSION[usergroup],$jvrow[cheq_no],$jvrow[cheq_date],$create_date,$ip,$now,date('D'),$thisday,$thismonth,$thisyear);
                        mysql_query("Update ".$table." SET status='RECOMMENDED',recommended_date='$todayss' where ".$unique."=".$_GET[$unique]."");
                        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                        echo "<script>window.close(); </script>";  }  ?>

                    <?php if($current_status!=$required_status){ echo '<h5 style="text-align:center; color:red; font-weight:bold"><i>This requisition has been recommended!!</i></h5>';} else { ?>
                        <table style="width:100%;font-size:12px">
                            <tr><td> <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="return_comments"  name="return_comments" class="form-control col-md-7 col-xs-12"  style="width:200px" placeholder="return comments........" >
                                        </div></div></td><td></td></tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="Return" id="Return" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Return?");'>Return the Stationary Purchased</button>
                                        </div></div></td>

                                <td>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" onclick='return window.confirm("Are you confirm to Deleted?");' name="Deleted" id="Deleted" class="btn btn-danger">Cancel & Deleted</button>
                                        </div></div></td>



                                <td><div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" onclick='return window.confirm("Are you confirm to Recommended the Requisition?");' name="Approved" id="Approved" class="btn btn-success">Approved & Create Journal</button>
                                        </div></div></td></tr></table>
                    <?php } ?>




                </form>
            </div>
        </div>
    </div>
<?php } ?>




<?=$html->footer_content();?>