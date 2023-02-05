<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Asset Purchase Report';
$now=time();
$unique='id';
$unique_field='id';
$table="code_to_code_master";
$table_details="code_to_code_transfer";
$journal_item="journal_item";
$journal_accounts="journal";
$page='production_code_to_code_transfer_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['reprocess'])) {
        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_production_ctc_transfer'] = $_GET[$unique];
        $type = 1;
        echo "<script>self.opener.location = 'production_code_to_code_transfer.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $up_master="UPDATE ".$table." SET status='CHECKED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_deatils." SET status='CHECKED' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}



 $results="Select srd.*,i.*,
	(select concat(item_id,' : ',item_name) from item_info where item_id=srd.transfer_to_item) as transfer_to_item
	from ".$table_details." srd, item_info i  where
    srd.transfer_from_item=i.item_id and 
    srd.".$unique."='".$$unique."' order by srd.id desc";
    $query=mysqli_query($conn, $results);
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
    </script>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content_nva_sm.php'; } ?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                  <table align="center" class="table table-striped table-bordered" style="width:100%; font-size:11px">
                <thead>
                <tr style="background-color: bisque">
                    <th>SL</th>
                    <th>Transfer From Code</th>
                    <th style="width:5%; text-align:center">UOM</th>
                    <th style="text-align:center;  width:10%">Transfer Qty</th>
                    <th style="text-align:center">Transfer to Code</th>
                    <th style="text-align:center; width:10%">Transfer to Qty</th>
                    <th style="text-align:center; width:10%">Unit Price</th>
                    <th style="text-align:center; width:10%">Amount</th>
                </tr>
                </thead>
                <tbody>


                <?php  while($row=mysqli_fetch_array($query)){  ?>
                    <tr>
                        <td style="width:3%; vertical-align:middle"><?=$i=$i+1;?></td>
                        <td style="vertical-align:middle; width: 25%"><?=$row[finish_goods_code];?> : <?=$row[item_name];?></td>
                        <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                        <td align="center" style=" text-align:center;vertical-align:middle;"><?=$row[t_qty];?></td>
                        <td align="center" style=" text-align:left;vertical-align:middle;"><?=$row[transfer_to_item];?></td>
                        <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[ctct_qty];?></td>
                        <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[cct_rate]; ?></td>
                        <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[amount]; ?></td>
                    </tr>
                    <?php  } ?>
                </tbody>                
            </table>





                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='RETURNED'){  ?>
                        <p><button style="float: left; font-size:12px" type="submit" name="reprocess" id="reprocess" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Re-process the CTC Transfer</button></p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This CTC has been checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View CTC Transfer Available</button></td>
            </tr></table>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="text-align:center">#</th>
                            <th style="text-align:center">CTC No</th>
                            <th style="text-align:center">CTC Date</th>
                            <th style="text-align:center">Remarks</th>
                            <th style="text-align:center">Wareehouse Name</th>
                            <th style="text-align:center">Entry By</th>
                            <th style="text-align:center">Entry At</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                        $from_date=date('Y-m-d' , strtotime($_POST[f_date]));
                        $to_date=date('Y-m-d' , strtotime($_POST[t_date]));
                        if(isset($_POST[viewreport])){
                            $res='select r.*,w.*,(SELECT concat(p2.PBI_NAME," # ","(",de.DESG_SHORT_NAME,")") FROM 
							 
							personnel_basic_info p2,
							department d,
							designation de 
							 where 
							 p2.ERP_ID=r.entry_by and
							 p2.PBI_DESIGNATION=de.DESG_ID and  							 
							 p2.PBI_DEPARTMENT=d.DEPT_ID) as entry_by
				  from '.$table.' r,
				  warehouse w
				  WHERE 
				  r.warehouse_id=w.warehouse_id and 
				  r.ctct_date between "'.$from_date.'" and "'.$to_date.'"  	  
				   order by r.'.$unique.' DESC';
                            $pquery=mysqli_query($conn, $res);
                            while($req=mysqli_fetch_object($pquery)){ ?>
                                <tr style="cursor: pointer">
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$i=$i+1;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->$unique;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->ctct_date;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->remarks;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->warehouse_name;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->entry_by;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->entry_at;?></td>
                                    <td onclick="DoNavPOPUP('<?=$req->$unique;?>', 'TEST!?', 600, 700)"><?=$req->status;?></td>
                                </tr>
                            <?php }} ?></tbody></table>

                </div></div></div></form>
<?php } ?>

<?=$html->footer_content();mysqli_close($conn);?>