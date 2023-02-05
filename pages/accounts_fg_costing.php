<?php
require_once 'support_file.php';
$title="Material Landed Cost";
$now=time();

$table="item_landad_cost";
$unique = 'id';   // Primary Key of this Database table
$page="accounts_material_landed_cost.php";
$crud      =new crud($table);


if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {        $_SESSION['fg_cost_item_group']=$_POST[item_sg];
    }

//for modify..................................
    if(isset($_POST['modify']))
    {   unset( $_SESSION['fg_cost_item_group']);
        $_SESSION['fg_cost_item_group']=$_POST[item_sg];
    }


//for Delete..................................
    if(isset($_POST['deleted']))
    {

        unset($_SESSION['fg_cost_item_group']);
        unset($_POST);
    }


    if(isset($_POST['add']))
    {
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST[or_date]=$_POST[or_date];
        $_POST['receive_type'] = 'Opening';
        $_POST['warehouse_id'] = $_POST['warehouse_id'];
        $result="SELECT i.*,sg.*,g.*,
 (select landad_cost from item_landad_cost where item_id=i.item_id and status='Active') as landed_cost
 FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        sg.sub_group_id=".$_SESSION['fg_cost_item_group']."
                        order by i.item_name";
        $res_item=mysqli_query($conn, $result);
        while($row=mysqli_fetch_array($res_item)){
            $id=$row[item_id];
            if(($_POST['qty'.$id] && $_POST['rate'.$id])>0){
                $inactive_old=mysqli_query($conn, "Update item_landad_cost set status='off' where item_id='$id'");
                $_POST['lot_number'] = $_POST['lot_number'.$id];
                $_POST['item_id'] = $id;
                $_POST['rate'] = $_POST['rate'.$id];
                $_POST['qty'] = $_POST['qty'.$id];
                $_POST[amount]=$_POST['qty']*$_POST['rate'];
                $_POST[or_no]=$_SESSION['fg_cost_item_group'];
                $crud      =new crud($table);
                $crud->insert();
            }}
        unset($_POST);
    }




    if (isset($_POST['confirmsave'])) {
        mysql_query("Update " . $table . " set status='UNCHECKED' where " . $unique . "=" . $_SESSION['fg_cost_item_group'] . "");

        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['PBI_ID'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $result="SELECT i.*,sg.*,g.*,d.* FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g,
                        warehouse_inventory_opening_detail d
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        d.item_id=i.item_id and 
                        ".$unique."=".$_SESSION[fg_cost_item_group]."
                        order by i.item_name";
        $res_item=mysqli_query($conn, $result);
        while($row=mysqli_fetch_array($res_item)){

            $_POST['ji_date'] = $row[or_date];
            $_POST['item_id'] = $row[item_id];
            $_POST['warehouse_id'] = $row[warehouse_id];
            $_POST['item_in'] = $row[qty];
            $_POST['item_price'] = $row[rate];
            $_POST['total_amt'] = $row[amount];
            $_POST['tr_from'] = 'Opening';
            $_POST['tr_no'] = $_SESSION[fg_cost_item_group];
            $_POST['sr_no'] = $row[id];
			$date=date('Y-m-d');
            $_POST['lot_number'] = automatic_number_generate("","journal_item","lot_number","ji_date='".$date."'")++;;
            $crud      =new crud($table_journal_item);
            $crud->insert();



        }
        unset($_POST);
        unset($_SESSION['item_sg']);
        unset($_SESSION['fg_cost_item_group']);
    }

    $result="SELECT i.*,sg.*,g.*,
 (select landad_cost from item_landad_cost where item_id=i.item_id and status='Active') as landed_cost
 FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        sg.sub_group_id=".$_SESSION['fg_cost_item_group']."
                        order by i.item_name";
    $res_item=mysqli_query($conn, $result);
    while($row=mysqli_fetch_array($res_item)){
        $id=$row[item_id];
        $up_cost=$_POST['update_cost'.$id];
        if(isset($_POST['update'.$id])){
            $up1=mysqli_query($conn, "Update item_landad_cost set landad_cost='".$up_cost."' where item_id=".$id."");
            $up2=mysqli_query($conn, "Update item_info set material_cost='".$up_cost."' where item_id=".$id."");

        }}
}



$result="SELECT i.*,sg.*,g.*,
 (select landad_cost from item_landad_cost where item_id=i.item_id and status='Active') as landed_cost
 FROM
                        item_info i,
                        item_sub_group sg,
                        item_group g
                        where
                        i.sub_group_id=sg.sub_group_id and
                        sg.group_id=g.group_id and 
                        sg.sub_group_id=".$_SESSION['fg_cost_item_group']."
                        order by i.item_name";
$res_item=mysqli_query($conn, $result);



// data query..................................
if(isset($_SESSION[fg_cost_item_group]))
{   $condition=$unique."=".$_SESSION[fg_cost_item_group];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]{
        font-size: 11px;}
</style>
<?php require_once 'body_content.php'; ?>




<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_content">
            <form action="" enctype="multipart/form-data" style="font-size: 11px" method="post" name="addem" id="addem" >
                <? //require_once 'support_html.php';?>
                <table align="center" style="width:70%"  cellpadding="0" cellspacing="0">
                    <tr>
                        <td>Sub Group</td>
                        <td>
                            <select class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="item_sg" id="item_sg">
                                <option></option>
                                <? $sql = "SELECT sg.sub_group_id,concat(sg.sub_group_id,' : ',sg.sub_group_name,' : ',g.group_name) FROM                        
                        item_sub_group sg,
                        item_group g
                        where
                        sg.group_id=g.group_id  and g.group_id  in ('500000000')
                        order by sg.sub_group_id";
                                advance_foreign_relation($sql,$_SESSION['fg_cost_item_group']);?>
                            </select></td>
                        <td align="center"><?php if($_SESSION[fg_cost_item_group]){  ?>
                                <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 12px">Update Material</button>
                            <?php   } else {?>
                                <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Search Material</button>
                            <?php } ?></td>
                    </tr>
                    <tr><td style="height:5px"></td></tr>
                </table>
            </form></div></div></div>



<?php if($_SESSION[fg_cost_item_group]){  ?>
    <form action="" enctype="multipart/form-data" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <? require_once 'support_html.php';?>
                    <input type="hidden" id="vendor_id" name="vendor_id" value="<?=$vendor_id;?>" >
                    <input type="hidden" id="vendor_name" name="vendor_name" value="<?=$vendor_name;?>" >
                    <input type="hidden" id="warehouse_id" name="warehouse_id" value="<?=$warehouse_id;?>" >
                    <input type="hidden" id="or_date" name="or_date" value="<?=$or_date;?>">
                    <input type="hidden" id="<?=$unique;?>" name="<?=$unique;?>" value="<?=$_SESSION[fg_cost_item_group];?>">


                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">

                        <tr style="background-color: bisque">

                            <td>Sl</td>

                            <td>FG Code</td>





                            <td>FG Description</td>

                            <td>RM Cost</td>

                            <td>PM Cost</td>

                            <td>Total Mat. Cost</td>

                            <td>Conversion Cost</td>

                            <td>Total Cost</td>





                        </tr>









                        <?php

                        $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));

                        $todaysss=$dateTime->format("d M Y,  h:i A");



                        $result=mysql_query("Select 

i.item_id,

i.item_name as fgname,

i.finish_goods_code as fgcode,

i.unit_name as unit, 

i.conversion_cost as conversionCOST,

i.pack_size as pksize,

s.sub_group_id, 

s.group_id, g.group_id 



from

item_info i,

item_sub_group s,

item_group g where 



i.sub_group_id=s.sub_group_id and 

s.group_id=g.group_id and 



s.sub_group_id in ('200010000') group by i.item_id order by i.serial



");

                        while($row=mysql_fetch_array($result)){
                            $fgcost=$_POST['cost_'.$row[item_id]];
                            if(isset($_POST[update])){
                                mysql_query("UPDATE item_costing SET status='OFF' where item_id='$row[item_id]'");
                                mysql_query("INSERT INTO item_costing (item_id,fg_cost,status,entry_by,entry_at) VALUES ('$row[item_id]','$fgcost','ON','".$_SESSION[user][id]."','$todaysss')")	;
                            }   ?>



                            <tr>
                                <td><?=$i=$i+1;?></td>
                                <td><?=$row[fgcode]?></td>
                                <td><?=$row[fgname]?></td>
                                <td><?php
                                    $formulaRMCOST=mysqli_query($conn, "Select 
distinct f.raw_item_id,
 f.unit_batch_qty,
f.unit_batch_size,
i.item_id,
i.item_name as RMname,
i.unit_name as unit,
s.sub_group_id, 
s.group_id,
g.group_id,
f.item_id,L.*,
SUM(distinct f.unit_batch_qty*L.landad_cost/f.unit_batch_size) as RMCostVALUE

from
item_info i,
item_sub_group s,
item_group g ,
production_ingredient_detail f,
item_landad_cost L

where 
f.raw_item_id=i.item_id and
f.raw_item_id=L.item_id and
i.sub_group_id=s.sub_group_id and 
s.group_id=g.group_id and 
g.group_id in ('200000000','300000000','1096000100000000') and 
f.item_id='".$row[item_id]."' and
L.status in ('Active')
group by f.raw_item_id ");

                                    while($RMCOST=mysqli_fetch_array($formulaRMCOST)){
                                        $totalRMCOST=$totalRMCOST+$RMCOST[RMCostVALUE];  }?>
                                    <?=number_format($totalRMCOST,2)?></td>

                                <td>

                                    <?php
                                    $formulaPMCOST=mysqli_query($conn, "Select 
distinct f.raw_item_id,
 f.unit_batch_qty,
f.unit_batch_size,
i.item_id,
i.item_name as PMname,
i.unit_name as unit,
s.sub_group_id, 
s.group_id,
g.group_id,
f.item_id,LC.*,
SUM(distinct f.unit_batch_qty*LC.landad_cost/f.unit_batch_size) as PMCostVALUE
from
item_info i,
item_sub_group s,
item_group g ,
production_ingredient_detail f,
item_landad_cost LC
where 
f.raw_item_id=i.item_id and 
f.raw_item_id=LC.item_id and
i.sub_group_id=s.sub_group_id and 
s.group_id=g.group_id and 
g.group_id in ('400000000') and
f.item_id='".$row[item_id]."' and 
LC.status in ('Active') group by f.raw_item_id");
                                    while($PMCOST=mysqli_fetch_array($formulaPMCOST)){
                                        $totalPMCost=$totalPMCost+$PMCOST[PMCostVALUE]; 	} ?>
                                    <?=number_format($totalPMCost,2)?>
                                </td>
                                <td><?php $totalMATCOST=$totalRMCOST+$totalPMCost; print number_format($totalMATCOST,2);?></td>
                                <td><?php   $actualCONCOST=$row[conversionCOST]/$row[pksize]; print number_format($actualCONCOST,2);?></td>
                                <td style="text-align:center"><strong><?php $TotalCOST=$actualCONCOST+$totalMATCOST; echo number_format($TotalCOST,2); ?> <input style="width:100px" type="text" name="cost_<?=$row[item_id]?>" value="<?=number_format($TotalCOST,2)?>"  /></strong></td>
                                </td>
                            </tr>









                            <?php
                            $totalRMCOST=0;
                            $totalPMCost=0;

                        } ?>
                    </table>

                    <!--table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: bisque">
                            <th style="vertical-align: middle">SL</th>
                            <th style="vertical-align: middle">Code</th>
                            <th style="vertical-align: middle">Item Description</th>
                            <th style="text-align:center;vertical-align: middle">RM Cost</th>
                            <th style="text-align:center;vertical-align: middle">PM Cost</th>
                            <th style="text-align:center;vertical-align: middle">Total Mat. Cost</th>
                            <th style="text-align:center;vertical-align: middle">Conversion Cost</th>
                            <th style="text-align:center;vertical-align: middle">Standard Cost<br>(Active)</th>
                            <th style="text-align:center;vertical-align: middle">Last Update</th>
                            <th style="text-align:center;vertical-align: middle">Standard Cost <br>(Suggested)</th>
                            <th style="text-align:center;vertical-align: middle">Update?</th>

                        </tr>
                        <tbody>
                        <?php
                        while($row=mysqli_fetch_array($res_item)){
                            $mysqli=mysqli_query($conn, "select * from item_costing where item_id=".$row[item_id]." and status='ON'");
                            $fg_cost=mysqli_fetch_array($mysqli);
                            $id=$row[item_id];



                            $update_cost=$grn_row[total_cost_against_GRN]/$grn_row[qty];
                            $up_cost=$_POST['update_cost'.$id];


                            ?>
                            <tr>
                                <td style="width: 1%; vertical-align: middle"><?=$i=$i+1;?></td>
                                <td style="vertical-align: middle"><?php echo $row[item_id]; ?></td>
                                <td style="vertical-align: middle" align="left" ><?php echo $row[item_name]; ?></td>
                                <td style="vertical-align: middle" align="left" ><?php echo $row[sub_group_name]; ?></td>
                                <td style="vertical-align: middle" align="left" ><?php echo $row[unit_name]; ?></td>
                                <td style="vertical-align: middle" align="center" style="vertical-align: middle">
                                    <?php if($fg_cost[fg_cost]>0){ echo $fg_cost[fg_cost];} else { ?>
                                        <input align="center"  type="text" style="width:80px; height:20px; font-size: 11px; text-align:center" name="qty<?=$id;?>" id="qty<?=$id;?>" autocomplete="off" class='qty' class='qty<?=$id;?>'>
                                    <?php } ?>
                                </td>
                                <td style="vertical-align: middle" align="center" ><?=$fg_cost[entry_at];?></td>
                                <td style="vertical-align: middle" align="center">
                                    <input align="center" type="text" style="width:80px; height:20px; font-size: 11px; text-align:center" value="<?=number_format($update_cost,2);?>" name="update_cost<?=$id;?>" id="update_cost<?=$id;?>" autocomplete="off">
                                </td>
                                <td style="vertical-align: middle" align="left" ><button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="update<?=$id;?>" id="update<?=$id;?>" class="btn btn-primary" style="float: right; font-size: 11px">Update</button></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table-->
                    <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="add" id="add" class="btn btn-primary" style="float: right; font-size: 12px">Add Standard Cost</button>
                </div></div></div>

        <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Purchase?");' name="deleted" id="deleted" class="btn btn-danger" style="float: left; margin-left: 1%; font-size: 12px">Cancel  </button>
        <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm?");' name="confirmsave" id="confirmsave" class="btn btn-success" style="float: right; margin-right: 1%;font-size: 12px">Confirm Standard Cost</button>
    </form><br>
<?php } ?>
<?php require_once 'footer_content.php' ?>
