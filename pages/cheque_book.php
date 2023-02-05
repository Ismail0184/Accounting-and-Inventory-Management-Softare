<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Create Cheque Book";

$now=time();
$unique='id';
$unique_field='id';
$table="Cheque_Book";
$page="cheque_book.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

 $fcnumber=$_POST['fcnumber'];
 $zero=0;
 $tpnumber=$_POST['tpnumber'];
 $bank=$_POST['bank'];
 $tpnumber=$fcnumber+$tpnumber-1;
 $idate=date('Y-m-d');


 if(isset($_POST[record])){
     for ( $counter = $fcnumber; $counter <= $tpnumber; $counter += 1) {
         $dupsql=mysqli_query($conn, "select * from Cheque_Book where Bank_Name='$bank' and Cheque_number='$fcnumber'");
         if (mysqli_num_rows($dupsql)==0){
             mysqli_query($conn, "INSERT INTO Cheque_Book (Bank_id,Bank_Name,Cheque_number,entry_by,entry_at,ip,status,create_date,active_inactive,section_id,company_id) VALUES ('$_POST[bank]','','$zero$counter','".$_SESSION[userid]."','".$now=date('Y-m-')."','$ip','UNUSED','$idate','Active','$_SESSION[sectionid]','$_SESSION[companyid]')");} else { echo "Duplicate check number";
             break; ?>
             <meta http-equiv="refresh" content="0;cheque_book.php?bankid=<?=$_GET[bankid]?>">
         <?php }
     }
 } else if ($fcnumberbtn=='delete')
 {
     $delete=mysqli_query($conn, "delete from checquenumber where Bank='$bank'");
     echo "successfully deleted!!!!!!!!!!!";
 }
 $res="select cb.id,a.ledger_name as Bank_name,cb.Cheque_number,cb.cheque_issued_date,cb.maturity_date,cb.settled_date,cb.status from ".$table." cb,accounts_ledger a where a.ledger_id=cb.Bank_id";
?>


 <?php require_once 'header_content.php'; ?>
 <?php if(isset($_GET[$unique])):
     require_once 'body_content_without_menu.php'; else :
     require_once 'body_content.php'; endif;  ?>
 <?php if(isset($_GET[$unique])): ?>
 <div class="col-md-12 col-sm-12 col-xs-12">
     <div class="x_panel">
         <div class="x_title">
             <h2><?=$title;?></h2>
             <ul class="nav navbar-right panel_toolbox">
                 <div class="input-group pull-right"></div>
             </ul>
             <div class="clearfix"></div>
         </div>
         <div class="x_content">
             <?php else: ?>
             <div class="modal fade" id="addModal">
                 <div class="modal-dialog modal-md">
                     <div class="modal-content">
                         <div class="modal-header bg-primary text-white">
                             <h5 class="modal-title">Add New Record
                                 <button class="close" data-dismiss="modal">
                                     <span>&times;</span>
                                 </button>
                             </h5>
                         </div>
                         <div class="modal-body">
                             <?php endif; ?>
                             <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post"  style="font-size: 11px">
                                 <?php require_once 'support_html.php';?>
                                 <div class="form-group" style="width: 100%">
                                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Bank Name<span class="required">*</span></label>
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required" id="bank"  name="bank">
                                             <option></option>
                                             <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',  $_SESSION[selected_bank], 'ledger_group_id in ("1002")'); ?>
                                         </select></div></div>
                                 <div class="form-group" style="width: 100%">
                                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">First Page Number<span class="required">*</span></label>
                                     <div class="col-md-6 col-sm-6 col-xs-12"><input type="text" id="fcnumber" style="width:100%; font-size: 11px"  required   name="fcnumber"   class="form-control col-md-7 col-xs-12">
                                     </div></div>
                                 <div class="form-group" style="width: 100%;">
                                     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Total Page Number</label>
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <input type="text" id="tpnumber" style="width:100%; font-size: 11px"  required   name="tpnumber"  class="form-control col-md-7 col-xs-12" >
                                     </div></div>
                                 <?php if($_GET[$unique]):  ?>

                                     <div class="form-group" style="width: 100%">
                                         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Status</label>
                                         <div class="col-md-6 col-sm-6 col-xs-12">
                                             <select class="select2_single form-control" style="width:100%; font-size:11px" name="status" id="status">
                                                 <option value="1"<?=($status=='1')? ' Selected' : '' ?>>Active</option>
                                                 <option value="0"<?=($status=='0')? ' Selected' : '' ?>>Inactive</option>
                                             </select>
                                         </div></div>

                                     <div class="form-group" style="margin-left:30%">
                                         <div class="col-md-6 col-sm-6 col-xs-12">
                                             <button type="submit" name="cancel" id="cancel" style="font-size:12px" class="btn btn-danger">Cancel</button>
                                             <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Modify</button>
                                         </div></div>
                                 <?php else : ?>
                                     <div class="form-group" style="margin-left:40%">
                                         <div class="col-md-6 col-sm-6 col-xs-12">
                                             <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Create Cheque</button></div></div> <?php endif; ?>

                             </form></div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>
             <?php if(!isset($_GET[$unique])):?>
                 <?=$crud->report_templates_with_status_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
             <?php endif; ?>
             <?=$html->footer_content();mysqli_close($conn);?>
             <?php ob_end_flush();
             ob_flush(); ?>
 