<?php
require_once 'support_file.php';
$title='LC Received';
$now=time();

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todayss=$dateTime->format("d/m/Y  h:i A");

$unique='lcr_no';
$table="lc_lc_received";
$lc_lc_received_batch_split="lc_lc_received_batch_split";
$page='QC_LC_received.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];
$lcr_master=find_all_field('lc_lc_received','',''.$unique.'='.$_GET[$unique]);
$journal_item='journal_item';
$condition="create_date='".date('Y-m-d')."'";


if(prevent_multi_submit()){

    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        echo "<script>self.opener.location = 'production_transfer2.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }


    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
            $rs="Select * from lc_lc_received_batch_split where lcr_no=".$_GET[lcr_no]."";
            $pdetails=mysqli_query($conn, $rs);
            while($data=mysqli_fetch_object($pdetails)){
            $_POST['ji_date'] = date('Y-m-d');
            $_POST['item_id'] = $data->item_id;
            $_POST['warehouse_id'] = $data->warehouse_id;
            $_POST['item_in'] = $data->qty;
            $_POST['item_price'] = find_a_field('item_costing','fg_cost','status="ON" and item_id='.$row[item_id].'');
            $_POST['total_amt'] = $_POST['item_ex']*$_POST['item_price'];
            $_POST['Remarks'] = $data->Remarks;
            $_POST['batch'] = $data->batch;
            $_POST[expiry_date] = $data->mfg;
            $_POST['tr_from'] = 'Imported';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $data->id;
            $_POST[item_price] = $data->rate;
            $_POST[entry_by]= $_SESSION[userid];
            $_POST[entry_at]= date('Y-m-d H:s:i');
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();
            $update=mysqli_query($conn, "Update lc_lc_received set status='CHECKED' where lcr_no=".$_GET[lcr_no]);
            $update=mysqli_query($conn, "Update lc_lc_received_batch_split set status='PROCESSING' where lcr_no=".$_GET[lcr_no]);

        }
        echo "<script>window.close(); </script>";
    }
    if(isset($_POST['record_batch_split']))
    {

            $_POST['lcr_no'] = $_GET[lcr_no];
            $_POST['lc_id'] = $lcr_master->lc_id;
            $_POST['item_id'] = $_GET[item_id];
            $_POST['warehouse_id'] =$lcr_master->warehouse_id;
            $_POST['rate'] = find_a_field('LC_item_wise_cost_sheet','per_unit_cost','item_id='.$_GET[item_id].' and lcr_no='.$_GET[lcr_no]);
            $_POST[ip]=$ip;
            $_POST[entry_at] = date('Y-m-d H:s:i');
            $crud      =new crud($lc_lc_received_batch_split);
            if($_POST[qty_1]>0) {
                $_POST[qty]=$_POST[qty_1];
                $_POST[batch_no]=$_POST[batch_1];
                $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                $_POST[mfg]=$_POST[exp_date_1];
                $crud->insert();
            }if($_POST[qty_2]>0) {
                $_POST[qty]=$_POST[qty_2];
                $_POST[batch_no]=$_POST[batch_2];
                $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                $_POST[mfg]=$_POST[exp_date_2];
                $crud->insert();
            }if($_POST[qty_3]>0) {
                $_POST[qty]=$_POST[qty_3];
                $_POST[batch_no]=$_POST[batch_3];
                $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                $_POST[mfg]=$_POST[exp_date_3];
                $crud->insert();
            }if($_POST[qty_4]>0) {
                $_POST[qty]=$_POST[qty_4];
                $_POST[batch_no]=$_POST[batch_4];
                $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                $_POST[mfg]=$_POST[exp_date_4];
                $crud->insert();
            }if($_POST[qty_5]>0) {
                $_POST[qty]=$_POST[qty_5];
                $_POST[batch_no]=$_POST[batch_5];
                $_POST[batch]=automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000');
                $_POST[mfg]=$_POST[exp_date_5];
                $crud->insert();
            }
            $update=mysqli_query($conn, "Update lc_lc_received set status='CHECKED' where lcr_no=".$lcr_no." and item_id=".$_GET[item_id]."");
            echo "<script>self.opener.location = '$page?lcr_no=$_GET[lcr_no]'; self.blur(); </script>";
            echo "<script>window.close(); </script>";
    }

//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_details);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($_POST);
        unset($$unique);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

if(isset($_POST[viewreport])){
    $resultss="Select m.lcr_no,m.lcr_no as 'recv. id',llm.lc_no,m.pi_id,m.lc_id,m.rcv_Date as received_date,lb.buyer_name as Party_Name,w.warehouse_name as Warehouse,u.fname as received_by,m.status from 
".$table." m,lc_buyer lb,lc_lc_master llm,warehouse w,user_activity_management u
 where lb.party_id=m.vendor_id and m.rcv_Date between '".$_POST[f_date]."' and '".$_POST[t_date]."' and llm.id=m.lc_id and m.warehouse_id=w.warehouse_id and u.user_id=m.entry_by group by m.lcr_no  order by m.".$unique." DESC ";
} else {
    $resultss="Select m.lcr_no,m.lcr_no as 'recv. id',llm.lc_no,m.pi_id,m.lc_id,m.rcv_Date as received_date,lb.buyer_name as Party_Name,w.warehouse_name as Warehouse,u.fname as received_by,m.status from 
".$table." m,lc_buyer lb,lc_lc_master llm,warehouse w,user_activity_management u
 where lb.party_id=m.vendor_id and m.status in ('UNCHECKED') and llm.id=m.lc_id and m.warehouse_id=w.warehouse_id and u.user_id=m.entry_by group by m.lcr_no  order by m.".$unique." DESC ";

}
?>
<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function OpenPopupCenter(pageURL, title, w, h) {
            var left = (screen.width - w) / 2;
            var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
            var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=500,left = 250,top = -1");}
    </script>
<?php  if(isset($_GET[$unique])){
 require_once 'body_content_without_menu.php'; } else {
 require_once 'body_content.php'; } ?>
<?php if(isset($_GET[$unique]) && !isset($_GET[item_id])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th>SL</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th style="text-align: center">Unit</th>
                            <th style="text-align: center">Landed Cost</th>
                            <th style="text-align:center">Received Qty</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $rs="Select d.*,d.status as item_status,i.item_id,i.item_name,i.unit_name,i.finish_goods_code,c.per_unit_cost
from 
".$table." d,
item_info i,
LC_item_wise_cost_sheet c
 where
 i.item_id=d.item_id  and 
 d.".$unique."=".$$unique." and 
 d.".$unique."=c.lcr_no and 
 c.item_id=d.item_id 
 order by d.id";
                        $pdetails=mysqli_query($conn, $rs);
                        while($data=mysqli_fetch_object($pdetails)){?>
                            <tr style="cursor:pointer" onclick='OpenPopupCenter("<?=$page?>?lcr_no=<?=$_GET[lcr_no]?>&item_id=<?=$data->item_id;?>", "TEST!?", 850, 600)'>
                                <td style="width:3%; vertical-align:middle"><?=$js=$js+1;?></td>
                                <td><?=$data->finish_goods_code;?></td>
                                <td style="text-align:left"><?=$data->item_name;?></td>
                                <td style="text-align:center"><?=$data->unit_name;?></td>
                                <td style="text-align:center"><?=$data->per_unit_cost;?></td>
                                <td style="width:15%; text-align:center"><?=$data->qty;?></td>
                                <td style="width:15%; text-align:center"><?=$data->item_status;?></td>
                            </tr><?php } ?>
                        </tbody></table>

                    <?php if($uncheck_status=find_a_field(''.$table.'','COUNT(id)','status in ("UNCHECKED") and '.$unique.'='.$$unique)>0){  ?>
                        <p>
                            <input type="hidden" value="<?=$totalamount;?>" name="total_amount">
                            <button style="float: left; font-size: 12px" type="submit" name="returned" id="returned" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Returned</button>
                            <input type="text" id="returned_remarks" style="width: 200px; font-size: 11px"   name="returned_remarks" placeholder="Why Returned?? Plz explain here." class="form-control col-md-7 col-xs-12" >
                            <button style="float: right;font-size: 12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked & Forward</button>
                        </p>
                    <? } else {
                        echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This LC received has been checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<?php if(isset($_GET[item_id])){ ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th style="width: 1%">SL</th>
                            <th>Item Name</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:center">Batch</th>
                            <th style="text-align:center">Exp. Date</th>
                        </tr>
                        </thead>
                        <?php
                        //$item_status=find_a_field('lc_lc_received_batch_split','COUNT(id)','item_id='.$_GET[item_id].' and lcr_no='.$lcr_no);
                        $item_status=find_a_field('lc_lc_received','count(id)','item_id='.$_GET[item_id].' and status in ("CHECKED") and lcr_no='.$lcr_no);
                        $item_name=find_a_field('item_info','item_name','item_id='.$_GET[item_id]);
                        $rs="Select * from lc_lc_received_batch_split where lcr_no=".$_GET[lcr_no]." and item_id=".$_GET[item_id]."";
                        $pdetails=mysqli_query($conn, $rs);
                        while($data=mysqli_fetch_object($pdetails)){ ?>
                            <tr>
                                <td style="vertical-align: middle"><?=$i=$i+1;?></td>
                                <td style="text-align:left; vertical-align: middle"><?=$item_name?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><?=$data->qty?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><?=$data->batch?></td>
                                <td style="text-align:center; width: 15%; vertical-align: middle"><?=$data->mfg?></td>
                            </tr>
                        <?php } if($item_status>0){ echo ''; } else { ?>
                        <tr>
                            <td style="vertical-align: middle">1</td>
                            <td style="text-align:left; vertical-align: middle"><?=$item_name;?></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_1" id="qty_1"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_1" id="batch_1"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_1" id="exp_date_1"></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">2</td>
                            <td style="text-align:left; vertical-align: middle"><?=$item_name;?></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_2" id="qty_2"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_2" id="batch_2"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_2" id="exp_date_2"></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">3</td>
                            <td style="text-align:left; vertical-align: middle"><?=$item_name;?></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_3" id="qty_3"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_3" id="batch_3"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_3" id="exp_date_3"></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">4</td>
                            <td style="text-align:left; vertical-align: middle"><?=$item_name;?></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_4" id="qty_4"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_4" id="batch_4"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_4" id="exp_date_4"></td>
                        </tr><tr>
                            <td style="vertical-align: middle">5</td>
                            <td style="text-align:left; vertical-align: middle"><?=$item_name;?></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="number" step="any" name="qty_5" id="qty_5"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="text" name="batch_5" id="batch_5"></td>
                            <td style="text-align:center; width: 15%; vertical-align: middle"><input type="date" name="exp_date_5" id="exp_date_5"></td>
                        </tr>
        <?php } ?>
                        <tbody></tbody></table>
                    <?php if($item_status>0){?>
                    <h6 style="text-align: center; color: red; font-weight: bold"><i>THIS ITEM HAS BEEN CHECKED !!</i></h6>
                        <?php } else { ?>
                    <p><button style="float: right;font-size: 12px" type="submit" name="record_batch_split" id="record_batch_split" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Record</button></p>
                        <?php } ?>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<?php if(!isset($_GET[$unique])){ ?>
    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?=($_POST[f_date]!='')? $_POST[f_date] : date('Y-m-01') ?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?=($_POST[t_date]!='')? $_POST[t_date] : date('Y-m-d') ?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View LC Received</button></td>
            </tr></table>
        <?=$crud->report_templates_with_status($resultss);?>
    </form>
<?php } ?>
<?=$html->footer_content();mysqli_close($conn);?>