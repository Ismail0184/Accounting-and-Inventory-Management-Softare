<?php
require_once 'support_file.php';
$title='Unchecked Stock Transfer List';
$now=time();
$unique='do_no';
$unique_field='name';
$table="ims_transfer_from_super_DB_master";
$table_details="ims_transfer_from_super_DB_details";
$journal_item="ims_journal_item";
$page='sales_ims_stock_transfer_checked.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
    if (isset($_POST['returned'])) {
        $_POST['checked_by']=$_SESSION[userid];
        $_POST['checked_at']=time();
        $_POST['status']="RETURNED";
        $crud->update($unique);
        unset($_POST);
        $type = 1;
        //echo "<script>self.opener.location = 'QC_sales_return_view.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for modify PS information ...........................
    if(isset($_POST['checked']))
    {
        $results="Select srd.*,i.* from ".$table_details." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique."=".$$unique." group by srd.id order by srd.id";
        $query=mysqli_query($conn, $results);
        while($row=mysqli_fetch_array($query)){
            $_POST['ji_date'] = $ji_date;
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[depot_id];
            $_POST['item_in'] = $row[total_unit];
            $_POST['item_price'] = $row[rate];
            $_POST['dealer_code'] = $row[dealer_code];
            $_POST[sub_dealer_code]= $row[sub_dealer_code];
            $_POST['total_amt'] = $row[amount];
            $_POST['tr_from'] = 'Transfer';
            $_POST['tr_no'] = $_GET[$unique];
            $_POST['sr_no'] = $row[id];
            $_POST[ip]=$ip;
            $crud      =new crud($journal_item);
            $crud->insert();
        }
        $up_master="UPDATE ".$table." SET status='CHECKED' where ".$unique."=".$$unique."";
        $update_table_master=mysqli_query($conn, $up_master);
        $up_details="UPDATE ".$table_details." SET status='CHECKED' where ".$unique."=".$unique."";
        $update_table_details=mysqli_query($conn, $up_details);
        $type=1;
        unset($_POST);
        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
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
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=850,height=500,left = 230,top = -1");}
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
                        <tr style="background-color: bisque">
                            <th>SL</th>
                            <th>Code</th>
                            <th>Finish Goods</th>
                            <th style="width:5%; text-align:center">UOM</th>
                            <th style="text-align:center">Transfer Qty in PCS</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php
                        $results="Select srd.*,i.* from ".$table_details." srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique."=".$$unique." order by srd.id";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle; width: 10%"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle;"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td align="center" style=" text-align:center"><?=$row[total_unit];?></td></td>

                            </tr>
                            <?php  $ttotal_unit=$ttotal_unit+$row[total_unit];  } ?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Sales Return</td>
                            <td style="text-align:center"><?=$ttotal_unit;?></td>
                        </tr>
                    </table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED'){  ?>
                        <p>
                            <button style="float: left; margin-left: 1%; font-size: 12px" type="submit" name="deleted" id="deleted" class="btn btn-danger" onclick='return window.confirm("Are you confirm to returned?");'>Deleted the Transfer</button>
                            <button style="float: right; margin-right: 1%; font-size: 12px" type="submit" name="checked" id="checked" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Checked the Transfer</button>
                        </p>
                    <? } else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Stock Transfer has been checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>





<?php if(!isset($_GET[$unique])){
if(isset($_POST[viewreport])){	
$res="Select m.do_no,m.do_no as SR_NO,m.do_date as SR_Date,sd.sub_dealer_name_e as Transfer_From_Super_DB,d.dealer_name_e as Transfer_To_SUB_DB	,m.status,p.PBI_NAME as Entry_By,m.entry_at,
(SELECT COUNT(item_id) from ".$table_details." where ".$unique."=m.".$unique.") as nooffg
from 
".$table." m,
personnel_basic_info p,
dealer_info d,
sub_db_info sd

 where
 m.entry_by=p.PBI_ID and 
 d.dealer_code=m.dealer_code and
 sd.sub_db_code=m.sub_dealer_code and
 m.status in ('UNCHEcKED') and 
 m.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."'
  order by m.".$unique." DESC";	
}?>
                    <!-------------------list view ------------------------->
 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
                                 <table align="center" style="width: 50%; font-size: 11px">
                                    <tr><td>
                                            <input type="date"  style="width:150px; font-size:11px" value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required name="f_date" class="form-control col-md-7 col-xs-12" >
                                        <td style="width:10px; text-align:center"> -</td>
                                        <td><input type="date"  style="width:150px; font-size:11px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                                        <td style="padding:10px"><button type="submit" style="font-size: 12px" name="viewreport"  class="btn btn-primary">GET MIS Report</button></td>
                                        </tr>
                                        </table>

                           
<?=$crud->report_templates_with_data($res,$title);?>   
</form>         
<?php } ?>     

<?php require_once 'footer_content.php' ?>