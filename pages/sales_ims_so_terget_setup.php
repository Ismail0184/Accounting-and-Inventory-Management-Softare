<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='target_no';
$table="ims_monthly_target_master";
$table_details="ims_monthly_target_details";
$page='sales_ims_so_terget_setup.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

$master=find_all_field(''.$table.'','',''.$unique.'='.$$unique.'');

if(prevent_multi_submit()){

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $results="Select d.id,d.target_proposal,d.unit_price,d.amount,i.item_name,i.unit_name,i.finish_goods_code,i.pack_size
 from ".$table_details." d, item_info i  where
 d.item_id=i.item_id and 
 d.".$unique."=".$$unique." order by i.serial";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)) {
            $i = $i + 1;
            $ids = $row[id];
            $target_revised=$_POST['target_revised_'.$ids]*$row[pack_size];
            $amount=$_POST['amount_'.$ids];
            $rev=mysqli_query($conn, "Update ".$table_details." SET target_revised='".$target_revised."',amount='".$amount."',status='CHECKED' where  id='$ids' and ".$unique."=".$$unique." ");
        }
        $up=mysqli_query($conn, "Update ".$table." set status='CHECKED',checked_by='$_SESSION[userid]',checked_at='$now' where ".$unique."=".$$unique."");
        $type=1;
        unset($_POST);
        echo "<script>window.close(); </script>";
    }

    //for Delete..................................
    if(isset($_POST['add']))
    {
        $add=mysqli_query($conn, "Update ".$table_details." set item_status='1' where item_id=".$_POST[item_id]." and ".$unique."=".$$unique."");
    }

    //for Delete..................................
    if(isset($_POST['add_new']))
    { $add_news="INSERT INTO ".$table_details." (target_no,PBI_ID,item_id,pack_size,TSM_PBI_ID,year,month,item_status) VALUES ('".$$unique."','$master->PBI_ID','$_POST[item_id_new]','','$master->TSM_PBI_ID','$master->year','$master->month','1')";
      $query=mysqli_query($conn, $add_news);
    }

    $results="Select d.item_id,d.id,d.pre_target_amount,d.target_proposal,et.effective_tp,d.unit_price,d.amount,i.item_name,i.unit_name,i.finish_goods_code,i.pack_size,p.PBI_ID_UNIQUE as so_code
 from ".$table_details." d, item_info i,personnel_basic_info p,effective_tp et  where
 d.item_id=i.item_id and 
 d.PBI_ID=p.PBI_ID and
 d.item_status='1' and 
 i.item_id=et.item_id and 
 d.".$unique."=".$$unique." order by i.serial";
    $query=mysqli_query($conn, $results);
    while($row=mysqli_fetch_array($query)){
        $i=$i+1;
        $ids=$row[id];
        $target_revised=$_POST['target_revised_'.$ids]*$row[pack_size];
        $amount=$_POST['amount_'.$ids];

        if(isset($_POST['add_'.$ids])){
            mysqli_query($conn, "Update ".$table_details." SET target_revised='".$target_revised."',amount='".$amount."',status='CHECKED' where  id='$ids' and ".$unique."=".$$unique."");
        }}


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


$current_month=date('m');
$current_year=date('Y');
$IMS_TARGET_ACTIVE_MONTH=find_a_field('ims_date','ims_target_active_month','month ="'.$current_month.'" and  year="'.$current_year.'"');

?>


<?php require_once 'header_content.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=870,height=600,left = 230,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>



<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <? require_once 'support_html.php';?>
                    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque; vertical-align: middle">
                            <th>SL</th>
                            <th>SO Code</th>
                            <th>Finish Goods</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center">Achievement</th>
                            <th style="text-align:center">Proposed Target</th>
                            <th style="text-align:center">Checked Target</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Amount</th>
                            <th>Add</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $results="Select d.item_id,d.id,d.pre_target_amount,i.serial,d.target_proposal,et.effective_tp,d.target_revised,d.unit_price,d.amount,i.item_name,i.unit_name,i.finish_goods_code,i.pack_size,p.PBI_ID_UNIQUE as so_code
 from ".$table_details." d, item_info i,personnel_basic_info p,effective_tp et  where
 d.item_id=i.item_id and 
 d.PBI_ID=p.PBI_ID and

 i.item_id=et.item_id and 
 d.".$unique."=".$$unique." order by i.serial";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[so_code];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td style="vertical-align:middle; text-align:right"><?=$row[pre_target_amount];?></td>
                                <td align="center" style=" text-align:center; vertical-align: middle"><?php if($row[target_proposal]>0) echo $row[target_proposal]/$row[pack_size]; else echo '';?></td>
                                <input  type="hidden" style="height: 25px; vertical-align: middle" value="<?=$row[pack_size]?>" name="pack_size_<?=$ids;?>" id="pack_size_<?=$ids;?>" class='pack_size_<?=$ids;?>'>
                                <td align="center" style=" text-align:center;vertical-align: middle"><input type="text" style="width: 100%; text-align: center" name="target_revised_<?=$ids;?>" id="target_revised_<?=$ids;?>" value="<?php if($row[target_proposal]>0) echo $row[target_proposal]/$row[pack_size]; else echo '';?>" class="target_revised_<?=$ids;?>"></td>
                                <td align="center" style=" text-align:right;vertical-align: middle"><input type="text" style="width: 100%; text-align: right" name="unit_price<?=$ids;?>" id="unit_price<?=$ids;?>" value="<?=$row[effective_tp];?>" readonly class="unit_price<?=$ids;?>"></td>
                                <td align="center" style=" text-align:right;vertical-align: middle"><input type="text" style="width: 100%; text-align: right" name="amount_<?=$ids;?>" id="amount_<?=$ids;?>" readonly value="<?php if($row[amount]>0) echo $row[amount]; else echo '';?>" class="sum"></td>
                            <td style="vertical-align: middle"><button type="submit" class="btn btn-primary" name="add_<?=$ids;?>" id="add_<?=$ids;?>" style="font-size: 12px">Add</button></td>
                            </tr>


                            <script>
                                $(function(){
                                    $('#unit_price<?=$ids;?>, #target_revised_<?=$ids;?>').keyup(function(){
                                        var unit_price<?=$ids;?> = parseFloat($('#unit_price<?=$ids;?>').val()) || 0;
                                        var target_revised_<?=$ids;?> = parseFloat($('#target_revised_<?=$ids;?>').val()) || 0;
                                        var pack_size_<?=$ids;?> = parseFloat($('#pack_size_<?=$ids;?>').val()) || 0;
                                        $('#amount_<?=$ids;?>').val(((target_revised_<?=$ids;?> * pack_size_<?=$ids;?>)*unit_price<?=$ids;?>).toFixed(2));
                                    });
                                });
                            </script>
                            <?php $ttotalamount=$ttotalamount+$row[amount];
                            $tpre_target=$tpre_target+$row[pre_target_amount];
                        } ?>
                        </tbody>
                        <script>
                            // we used jQuery 'keyup' to trigger the computation as the user type
                            $('.sum').blur(function () {
                                // initialize the sum (total price) to zero
                                var sum = 0;
                                // we use jQuery each() to loop through all the textbox with 'price' class
                                // and compute the sum for each loop
                                $('.sum').each(function() {
                                    sum += Number($(this).val());
                                });
                                // set the computed value to 'totalPrice' textbox
                                $('#totalPrice').val((sum).toFixed(2));
                            });
                        </script>

                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Target in Amount</td>
                            <td style="text-align:right"><?=number_format($tpre_target,2);?></td>
                            <td style="text-align:center"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td align="right" ><input style="height: 25px; width: 80px;font-size: 11px; text-align: right" type='text' id='totalPrice' value="<?=number_format($ttotalamount,2);?>" disabled /></td>
                        </tr>


                    </table>
                    <?php mysqli_close($conn); ?>

                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED' || $GET_status=='MANUAL'){  ?>
                        <br><br>
                        <p>
                            <button style="float: left; margin-left: 1%" type="submit" name="deleted" id="deleted" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Deleted?");'>Deleted the Target</button>
                            <button style="float: right;" type="submit" name="checked" id="checked" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Checked the Target </button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This SO Target has been Checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>
<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="text-align: center">IMS Month : <?=date("F", mktime(0, 0, 0, $IMS_TARGET_ACTIVE_MONTH, 10));?>, <?=date('Y')?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?
                    $res="Select m.target_no,m.target_no as Target_NO,p.PBI_ID_UNIQUE as SO_CODE,p.PBI_NAME as 'SO / Comission DB',(select PBI_NAME from personnel_basic_info where PBI_ID=m.TSM_PBI_ID) as In_charge_Person,p.so_type,m.month,m.year,FORMAT(SUM(d.pre_target_amount),2) as Achievement_in_Amount,FORMAT(SUM(amount),2) as Target_in_values,m.status
from 
".$table." m,
".$table_details." d,
personnel_basic_info p
 where
  m.PBI_ID=p.PBI_ID and 
  m.target_no=d.target_no and  m.month=".$IMS_TARGET_ACTIVE_MONTH." and m.year=".$current_year."  group by m.target_no
    order by m.TSM_PBI_ID,p.PBI_ID";
                    echo $crud->link_report_voucher($res,$link);
                   
				   
				    $ress="Select m.target_no,m.target_no as Target_NO,p.PBI_ID_UNIQUE as SO_CODE,p.PBI_NAME as 'SO / Comission DB',(select PBI_NAME from personnel_basic_info where PBI_ID=m.TSM_PBI_ID) as In_charge_Person,p.so_type,m.month,m.year,m.status
from 
".$table." m,
personnel_basic_info p
 where
  m.PBI_ID=p.PBI_ID and    m.status in ('MANUAL','UNCHECKED')  group by m.target_no
    order by m.TSM_PBI_ID,p.PBI_ID";
                    echo $crud->link_report_voucher($ress,$link);?>
                    <?php mysqli_close($conn); ?>
                </div></div></div></form>
<?php } ?>
<?php require_once 'footer_content.php' ?>