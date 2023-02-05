 <?php
require_once 'support_file.php';
$title="Inventory Return";

$now=time();
$unique='id';
$unique_field='name';
$table="purchase_return_master";
$page="cv_inventory_return.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todaysss=$dateTime->format("d/m/Y  h:i A");

if(prevent_multi_submit()){
   $$unique = $_GET[$unique];


    
//check by qc..................................
if(isset($_POST['modifyqc']))
{
    $_POST['checked_at']=$todaysss;
    $_POST['status']='CHECKED';
    $crud->update($unique);
    $type=1;

    echo "<script>self.opener.location = '$page'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

//check by qc..................................
    if(isset($_POST['modifyqc']))
    {
        $_POST['checked_at']=$todaysss;
        $_POST['status']='CHECKED';
        $crud->update($unique);
        $type=1;

        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }


//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
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
     function OpenPopupCenter(pageURL, title, w, h) {
         var left = (screen.width - w) / 2;
         var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
         var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
     }

 </script>
<?php require_once 'body_content.php'; ?>


 <?php if(isset($_GET[$unique])){ ?>
     <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
         <?require_once 'support_html.php';?>
                    <!-- input section-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right">
                                        <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                                        </a-->
                                    </div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                                    <tr>
                                        <th>Ref. No</th>
                                        <th>PO No</th>
                                        <th>Item Name</th>
                                        <th>Unit Name</th>
                                        <th>Qty</th>
                                    </tr>


                                <? 	$res='select 
m.id,
m.ref_no,
i.item_name,
i.unit_name,
d.qty,
d.po_no

from 

purchase_return_master m,
purchase_return_details d,
warehouse w,
vendor v,
item_info i
where 
m.id=d.m_id and 
i.item_id=d.item_id and  
m.warehouse_id=w.warehouse_id and 
m.vendor_id=v.vendor_id and
m.checked_by_qc='.$_SESSION['PBI_ID'].' and 
m.status in ("UNCHECKED") and 
m.id='.$_GET[$unique].' 
group by d.id';
                                $data2=mysql_query($res);
                                while($data=mysql_fetch_object($data2)){ ?>
                                                <tr>
                                                    <td><?=$data->ref_no;?></td>
                                                    <td><?=$data->po_no;?></td>
                                                    <td><?=$data->item_name;?></td>
                                                    <td><?=$data->unit_name;?></td>
                                                    <td><?=$data->qty;?></td>
                                                </tr>
                                                <?php } ?>
                                </table>


                                    <br><br><br>
                                <table style="width: 100%">
                                <tr>


                                    <td style="width: 30%">

                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="returnqc"   name="returnqc" onclick='return window.confirm("Are you confirm to returned?");' type="submit" class="btn btn-primary" value="RETURN"/>
                                            </div>
                                    </td>

                                <td style="width: 30%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  name="deleteqc" onclick='return window.confirm("Are you confirm to Deleted?");' type="submit" class="btn btn-danger" id="deleteqc" value="DELETED"/>
                                        </div>
                                            </td>

                                <td style="width: 40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" onclick='return window.confirm("Are you confirm to CHECKED & FORWARD?");' name="modifyqc" id="modifyqc" class="btn btn-success">CHECKED & FORWARD</button>
                                        </div>
                                    </td></tr>
                                </table>


                                </div>
                                </div>
                                </div>
     </form>
 <?php } ?>












 <?php if(isset($_GET[id_pro])){ ?>
     <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
         <?require_once 'support_html.php';?>
         <!-- input section-->
         <div class="col-md-12 col-sm-12 col-xs-12">
             <div class="x_panel">
                 <div class="x_title">
                     <h2><?=$title;?></h2>
                     <ul class="nav navbar-right panel_toolbox">
                         <div class="input-group pull-right">
                             <!--a target="_new" class="btn btn-sm btn-default"  href="user_permission2.php">
                                 <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Uer Permission (SUB)</span>
                             </a-->
                         </div>
                     </ul>
                     <div class="clearfix"></div>
                 </div>
                 <div class="x_content">

                     <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                         <tr>
                             <th>Ref. No</th>
                             <th>PO. No</th>
                             <th>Item Name</th>
                             <th>Unit Name</th>
                             <th>Qty</th>
                             <th>Rate</th>
                         </tr>


                         <? 	$res='select 
m.id,
m.ref_no,
i.item_name,
i.unit_name,
d.qty,
d.po_no,
d.id as did

from 

purchase_return_master m,
purchase_return_details d,
warehouse w,
vendor v,
item_info i
where 
m.id=d.m_id and 
i.item_id=d.item_id and  
m.warehouse_id=w.warehouse_id and 
m.vendor_id=v.vendor_id and
m.checked_by_qc='.$_SESSION['PBI_ID'].' and 
m.status in ("CHECKED") and 
m.id='.$_GET[id_pro].' 
group by d.id';
                         $data2=mysql_query($res);
                         while($data=mysql_fetch_object($data2)){
                            $idget=$data->did;
                            $qty=$_POST['qty'.$idget];
                            $rate=$_POST['rate'.$idget];
                            $amount=$qty*$rate;

                            if(isset($_POST[modifypro])){
                                mysql_query("UPDATE purchase_return_details SET rate='".$rate."',amount='$amount' where m_id='".$_GET[id_pro]."' and id='".$idget."'");
                                mysql_query("UPDATE purchase_return_master SET status='ROCOMMENDED',recommended_date='$todaysss' where id=".$_GET[id_pro]." ");

                                echo "<script>self.opener.location = '$page'; self.blur(); </script>";
                                echo "<script>window.close(); </script>";

                            }


                             ?>
                             <tr>
                                 <td><?=$data->ref_no;?></td>
                                 <td><?=$data->po_no;?></td>
                                 <td><?=$data->item_name;?></td>
                                 <td><?=$data->unit_name;?></td>
                                 <td><?=$data->qty;?></td>
                                 <td>
                                 <input type="hidden" name="qty<?=$data->did?>" style="width: 80px" value="<?=$data->qty;?>" id="qty<?=$data->did?>" class="form-control col-md-7 col-xs-12">
                                 <input type="text" name="rate<?=$data->did?>" required style="width: 80px" id="rate<?=$data->did?>" class="form-control col-md-7 col-xs-12"></td>
                             </tr>
                         <?php } ?>
                     </table>


                     <br><br><br>
                     <table style="width: 100%">
                         <tr>


                             <td style="width: 30%">

                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                     <input id="returnqc"   name="returnpro" onclick='return window.confirm("Are you confirm to returned?");' type="submit" class="btn btn-primary" value="RETURN"/>
                                 </div>
                             </td>

                             <td style="width: 30%">
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                     <input  name="deletepro" onclick='return window.confirm("Are you confirm to Deleted?");' type="submit" class="btn btn-danger" id="deleteqc" value="DELETED"/>
                                 </div>
                             </td>

                             <td style="width: 40%">
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                     <button type="submit" onclick='return window.confirm("Are you confirm to CHECKED & FORWARD?");' name="modifypro" id="modifypro" class="btn btn-success">CHECKED & FORWARD</button>
                                 </div>
                             </td></tr>
                     </table>


                 </div>
             </div>
         </div>
     </form>
 <?php } ?>






 <?php
 if(!isset($_GET[$unique]) &&  !isset($_GET[id_pro]) ){
 if($_SESSION["department"]=='Production' || $_SESSION["department"]=='MIS'){
 ?>                   <!-------------------list view ------------------------->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?> (QC)</h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
                                <? 	$res='select 
m.id,
m.ref_no,
m.po_no,
m.return_date as date,
m.remarks,
m.ref_no,w.warehouse_name,v.vendor_name from 

purchase_return_master m,
warehouse w,
vendor v
where 
 
m.warehouse_id=w.warehouse_id and 
m.vendor_id=v.vendor_id and
m.checked_by_qc='.$_SESSION['PBI_ID'].' and 
m.status in ("UNCHECKED") group by m.id';
echo $crud->link_report_popup($res,$link);?>
</div>
</div></div>

<?php } ?>


 <?php
 if($_SESSION["department"]=='Procurement' || $_SESSION["department"]=='Production' || $_SESSION["department"]=='MIS'){
     ?>                   <!-------------------list view ------------------------->
     <div class="col-md-12 col-sm-12 col-xs-12">
     <div class="x_panel">
         <div class="x_title">
             <h2><?=$title;?> (Procurement)</h2>
             <div class="clearfix"></div>
         </div>

         <div class="x_content">


             <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                 <tr>
                     <th>Ref. No</th>
                     <th>Po No</th>
                     <th>Date</th>
                     <th>Remarks</th>
                     <th>Warehouse Name</th>
                     <th>Vendor Name</th>
                     <th>Checked By</th>
                 </tr>


             <?
$res='select 
m.id,
m.ref_no,
m.po_no,
m.return_date as date,
m.remarks,
m.ref_no,
w.warehouse_name,
v.vendor_name,
u.fname as checkedby 
 
 from 

purchase_return_master m,
warehouse w,
vendor v,
user_activity_management u
where 
 
m.warehouse_id=w.warehouse_id and 
m.vendor_id=v.vendor_id and
m.checked_by_qc='.$_SESSION['PBI_ID'].' and 
m.status in ("CHECKED") and 
m.checked_by_qc=u.PBI_ID
 
group by m.id';
                 $data2=mysql_query($res);
                                while($datapro=mysql_fetch_object($data2)){ ?>
                                    <tr style="text-align: left;cursor: pointer" onclick="OpenPopupCenter('<?=$page?>?<?php echo 'id_pro='.$datapro->id.'&view=Show&in=Contra' ?>', 'TEST!?', 700, 400);">
                                    <td><?=$datapro->ref_no;?></td>
                                                    <td><?=$datapro->po_no;?></td>
                                                    <td><?=$datapro->date;?></td>
                                                    <td><?=$datapro->remarks;?></td>
                                                    <td><?=$datapro->warehouse_name;?></td>
                                                    <td><?=$datapro->vendor_name;?></td>
                                                    <td><?=$datapro->checkedby;?></td>
                                                </tr>
                                <?php } ?></table>
         </div></div></div><?php }} ?>
        
<?php require_once 'footer_content.php' ?>