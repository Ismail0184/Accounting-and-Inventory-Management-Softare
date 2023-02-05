<?php
require_once 'support_file.php';
$title='Production Report';
$now=time();
$unique='do_no';
$unique_field='name';
$table="sale_return_master";
$table_deatils="sale_return_details";
$journal_item="journal_item";
$journal_accounts="journal";
$page='warehouse_sales_return_view.php';
$ji_date=date('Y-m-d');
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if (isset($_POST['reprocess'])) {
        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['sales_return_id'] = $_GET[$unique];
        $_SESSION['initiate_sr_documents']=getSVALUE("".$table."", "sr_no", " where ".$unique."=".$_GET[$unique]."");
        $type = 1;
        echo "<script>self.opener.location = 'sales_return_all.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }




//for Delete..................................
    if(isset($_POST['deleted']))
    {
        $crud = new crud($table_deatils);
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
                            <th style="text-align:center">Sales Qty</th>
                            <th style="text-align:center">Free Qty</th>
                            <th style="text-align:center">Discount</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Total Qty</th>
                            <th style="text-align:center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>


                        <?php
                        $results="Select srd.*,i.* from sale_return_details srd, item_info i  where
 srd.item_id=i.item_id and 
 srd.".$unique."=".$$unique." order by srd.id";
                        $query=mysqli_query($conn, $results);
                        while($row=mysqli_fetch_array($query)){
                            $i=$i+1;
                            $ids=$row[id];
                            ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                                <td style="vertical-align:middle"><?=$row[finish_goods_code];?></td>
                                <td style="vertical-align:middle; width: 25%"><?=$row[item_name];?></td>
                                <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                                <td align="center" style=" text-align:center"><?=$row[total_unit];?></td>
                                <td align="center" style=" text-align:center"><?=$row[free_qty];?></td>
                                <td align="center" style=" text-align:right"><?=$row[discount];?></td>
                                <td align="center" style=" text-align:right"><?=$row[unit_price]; ?></td>
                                <td align="center" style=" text-align:center"><?=$row[total_qty]; ?></td>
                                <td align="center" style="text-align:right"><?=number_format($row[total_amt],2);?></td>

                            </tr>
                            <?php  $ttotal_unit=$ttotal_unit+$row[total_unit];
                            $tfree_qty=$tfree_qty+$row[free_qty];
                            $ttotal_qty=$ttotal_qty+$row[total_qty];
                            $tdiscount=$tdiscount+$row[discount];
                            $ttotal_amt=$ttotal_amt+$row[total_amt];  } ?>
                        </tbody>
                        <tr style="font-weight: bold">
                            <td colspan="4" style="font-weight:bold; font-size:11px" align="right">Total Sales Return</td>
                            <td style="text-align:center"><?=$ttotal_unit;?></td>
                            <td style="text-align:center"><?=$tfree_qty;?></td>
                            <td style="text-align:right"><?=number_format($tdiscount,2);?></td>
                            <td align="center" ></td>
                            <td align="center" ><?=$ttotal_qty;?></td>
                            <td align="right" ><?=number_format($ttotal_amt,2);?></td>
                        </tr>
                    </table>

                    <?php
                    $GET_status=find_a_field(''.$table.'','status',''.$unique.'='.$_GET[$unique]);
                    if($GET_status=='UNCHECKED' || $GET_status=='MANUAL' || $GET_status=='RETURNED'){ 
					if($entry_by==$_SESSION[userid]){
					 ?>
                        <p>
                            <button style="float: left; font-size:12px; margin-left:1%" type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Re-process & Update</button>
                            <button style="float: right; font-size:12px; margin-right:1%" type="submit" name="deleted" id="deleted" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Deleted</button>
                        </p>
                        <? } else { echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This SR was created by another person. So you are not able to do anything here!!</i></h6>';
                       }} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This Sales Return has been checked !!</i></h6>';}?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(!isset($_GET[$unique])){ ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
       <table align="center" style="width: 50%;">
            <tr><td>
                <input type="date"  style="width:150px; font-size: 11px; height: 25px" max="<?=date('Y-m-d');?>"  value="<?php if($_POST[f_date]) echo $_POST[f_date]; else echo date('Y-m-01');?>" required   name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px;font-size: 11px; height: 25px"  value="<?php if($_POST[t_date]) echo $_POST[t_date]; else echo date('Y-m-d');?>" required  max="<?=date('Y-m-d');?>" name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Sales Return</button></td>
            </tr></table>
 <?php 
if(isset($_POST[viewreport])){
$res="Select p.do_no,p.sr_no as SR_NO,DATE_FORMAT(p.do_date, '%d %M, %Y') as SR_date,w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as dealer_name,p.remarks,(SELECT COUNT(item_id) from ".$table_deatils." where ".$unique."=p.".$unique.") as nooffg,u.fname as entry_by,p.entry_at,p.status

from 
".$table." p,
warehouse w,
user_activity_management u,
dealer_info d
 where
  p.entry_by=u.user_id and 
 w.warehouse_id=p.depot_id and  
 d.dealer_code=p.dealer_code and 
 p.do_date between '".$_POST[f_date]."' and '".$_POST[t_date]."' order by p.".$unique." DESC ";
} else {
$res="Select p.do_no,p.sr_no as SR_NO,DATE_FORMAT(p.do_date, '%d %M, %Y') as SR_date,w.warehouse_name as 'Warehouse / CMU',d.dealer_name_e as dealer_name,p.remarks,(SELECT COUNT(item_id) from ".$table_deatils." where ".$unique."=p.".$unique.") as nooffg,u.fname as entry_by,p.entry_at,p.status
from 
".$table." p,
warehouse w,
user_activity_management u,
dealer_info d

 where
  p.entry_by=u.user_id and 
 w.warehouse_id=p.depot_id and  
 d.dealer_code=p.dealer_code and 
 p.status in ('UNCHECKED') order by p.".$unique." DESC ";	
}
echo $crud->report_templates_with_data($res,$title);?>
</form>
<?php } ?>           
<?=$html->footer_content();?>