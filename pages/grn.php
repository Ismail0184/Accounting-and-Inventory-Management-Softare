<?php
require_once 'support_file.php';
$title='GRN Checked';
$page='purchase_sec_print_view.php';
$unique='jv_no';



?>

<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=900,height=600,left = 250,top = -1");}
</script>
<?php require_once 'body_content.php'; ?>




                    
                            <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >

                                    <table align="center" style="width:70%; font-size: 11px">
                                        <tr>
                                            <td><div align="right"><strong>Date Interval</strong></div></td>
                                            <td style="width: 5%; text-align: center; ">:</td>
                                            <td><input style="width:40%; height: 25px" name="do_date_fr" type="date" max="<?=date('Y-m-d');?>"  value="<?=$_POST[do_date_fr];?>" required autocomplete="off" /> - <input name="do_date_to" type="date" value="<?=$_POST[do_date_to];?>" max="<?=date('Y-m-d');?>"  style="width:40%;height: 25px"  required autocomplete="off" /> - <select name="checked" id="checked" style="width:auto; padding:0px; height:25px; font-size:11px; float:right">
                      <option value=""> Status</option>
                      <option value="PENDING" <?=($_POST['checked']=='PENDING')?'Selected':'';?>>PENDING</option>
                      <option value="YES" <?=($_POST['checked']=='YES')?'Selected':'';?>>YES</option>
                    </select></td>
                    
                    
              <td align="center" rowspan="2"  style="vertical-align: middle;">
              <button type="submit" style="font-size: 11px; height: 30px" name="submitit"  class="btn btn-primary">View GRN List</button></td>
                    </tr>
                    
                    


<tr>
<td><div align="right"><strong>Vendor Name</strong></div></td>
<td style="width: 5%; text-align: center; ">:</td>
<td>
               
                                                <?  $sql = "select v.vendor_id,concat(v.vendor_id,' : ',v.vendor_name) from vendor v where  v.group_for='".$_SESSION['usergroup']."' order by v.vendor_name"; ?>
                                                <select class="select2_single form-control" name="vendor_id" id="vendor_id" style="width: 100%; height: 30px; font-size: 11px;">
                                                    <option></option>
                                                    <?  foreign_relation_sql($sql,$vendor_id);?>
                                                </select></td>
                                                
                                        

                                        
                                            
                                        </tr>
                                    </table>
                                </form>
                                <br>









                    <!----------------------------------- initiate end--------------------------------------------------------------------->









                <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
                    <table align="center"  class="table table-striped table-bordered" style="width:98%; font-size: 11px;">
                        <thead>
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>PO</th>
                            <th>GR</th>
                            <th>Challan</th>
                            <th>Goods / Services Ledger</th>
                            <th>Vendor</th>
                            <th>Depot</th>
                            <th>GRN By </th>
                            <th>Payable Amt</th>
                            <th>Checked?</th>
                        </tr>
                        </thead>

                        <tbody>




                        <?





                        if($_POST['do_date_fr']!='') {
                            $i = 0;
                            if ($_POST['checked'] != '') $checked_con = ' and j.checked="' . $_POST['checked'] . '" ';
                            if ($_SESSION['usergroup'] > 1) $group_s = 'AND j.group_for=' . $_SESSION['usergroup'];
                            if ($_POST['vendor_id'] != '') {
                                $vendor_con = ' and r.vendor_id="' . $_POST['vendor_id'] . '"';
                            }
                            $sql = "SELECT DISTINCT 

				  j.tr_no,
				  1,
				  1,
				  j.jv_date,
				  j.jv_no,
				  l.ledger_name,
				  j.tr_no,
				  u.fname,
				  j.entry_at,
				  j.checked,
				  j.jv_no,
				  j.check_by_production,
				  w.warehouse_name,
				  r.po_no,
				  v.vendor_id,
				  v.vendor_name as Vendorname,
				  j.dr_amt,
				  r.ch_no as challan_no 

				FROM
					
				  secondary_journal j,
				  accounts_ledger l,
				  purchase_receive r,
				  warehouse w,
				  users u,
				  vendor v

				WHERE 
				
				  checked!='NO' and 
				  w.warehouse_id=r.warehouse_id AND
				  j.tr_no = r.pr_no AND
				  j.tr_from = 'Purchase' AND 
				  j.user_id = u.user_id AND
				  j.jv_date between '" . strtotime($_POST['do_date_fr']) . "' AND  '" . strtotime($_POST['do_date_to']) . "' AND 
                  v.vendor_id=r.vendor_id AND
				  j.ledger_id = l.ledger_id " . $group_s . $checked_con . $depot_con . $vendor_con . " group by j.jv_no order by j.tr_no desc";
                            $query = mysqli_query($conn, $sql);
                        } else {
                            $sql = "SELECT DISTINCT 

				  j.tr_no,
				  1,
				  1,
				  j.jv_date,
				  j.jv_no,
				  l.ledger_name,
				  j.tr_no,
				  u.fname,
				  j.entry_at,
				  j.checked,
				  j.jv_no,
				  j.check_by_production,
				  w.warehouse_name,
				  r.po_no,
				  v.vendor_id,
				  v.vendor_name as Vendorname,
				  j.dr_amt,
				  r.ch_no as challan_no

				FROM
					
				  secondary_journal j,
				  accounts_ledger l,
				  purchase_receive r,
				  warehouse w,
				  users u,
				  vendor v

				WHERE 
				
				  checked!='NO' and 
				  w.warehouse_id=r.warehouse_id AND
				  j.tr_no = r.pr_no AND
				  j.tr_from = 'Purchase' AND 
				  j.user_id = u.user_id AND
				  j.checked ='PENDING' AND 
                  v.vendor_id=r.vendor_id AND
				  j.ledger_id = l.ledger_id " . $group_s . $checked_con . $depot_con . $vendor_con . " group by j.jv_no order by j.tr_no desc";
                            $query = mysqli_query($conn, $sql);
                        }
                            while($data=mysqli_fetch_row($query)){?>



                                <tr style="cursor: pointer" onclick="DoNavPOPUP('<?=$data[10] ?>', 'TEST!?', 0, 0)">
                                    <td align="center"><?=++$i;?></td>
                                    <td align="center"><? echo $data[13];?></td>
                                    <td align="center"><? echo $data[6];?></td>
                                    <td align="center" style="width:10%"><? echo $data[17];?></td>
                                    <td align="left"><? echo $data[5];?></td>
                                    <td align="left"><? echo $data[15];?></td>
                                    <td align="left"><? echo $data[12];?></td>
                                    <td align="left"><? echo $data[7];?></td>
                                    <td align="right"><?=number_format($data[16],2);?>
                                        <?php $received = $received + $data[16];?>
                                    </td>
                                    <td align="center" style="vertical-align: middle; background-color: <? if(($data[9]=='YES')){ ?>Green<?  } if(($data[9]=='PENDING')){ ?>Red<? }?>; color: white"><? if(($data[9]=='YES')){ ?>YES<?  } if(($data[9]=='PENDING')){ ?>No<? }?></td>
                                </tr>
                            <? }?>
                        </tbody>

                        <tr>
                            <td colspan="8" align="center">
                                <div align="right"><strong>Total Payable Amount: </strong></div>
                                <div align="left"></div></td>
                            <td align="right"><strong><?=number_format($received,2);?></strong></td>
                            <td align="center">&nbsp;</td>
                        </tr>
                    </table>
                </form>


<?php require_once 'footer_content.php' ?>

