<?php
require_once 'support_file.php';
$title="Effective TP";

$now=time();
$unique='item_id';
$unique_field='effective_tp';
$table="effective_tp";
$table_item_info="item_info";
$page="effective_tp.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){
       $$unique = $_POST[$unique];
        if(isset($_POST['record']))
        {
            $crud->insert();
            $type=1;
            $msg='New Entry Successfully Inserted.';
            unset($_POST);
            unset($$unique);
        }
		
		
	//for serial modify..................................
        if(isset($_POST['update_serial']))
        {           
		   $res=mysqli_query($conn,"SELECT i.*,sg.sub_group_name,g.group_name FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id	in ('500000000') and 
							 i.status in ('Active')	and
							 i.exim_status not in ('Export') and
							 i.brand_category not in ('Rice') and 
							 i.item_id not in ('1096000100010312','1096000100010313','700020001')
							  order by i.serial");					
                    while($item=mysqli_fetch_array($res)){						
						$id=$item[item_id];						
						$_POST[serial]=$_POST['serial_'.$id];
						mysqli_query($conn, "UPDATE item_info SET serial=".$_POST[serial]." where item_id=".$id." ");
					}
					unset($_POST);					       
        }	
		
		
		
		//for TP modify..................................
        if(isset($_POST['tp_update']))
        {           
		   $res=mysqli_query($conn,"SELECT i.*,sg.sub_group_name,g.group_name FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id	in ('500000000') and 
							 i.status in ('Active')	and
							 i.exim_status not in ('Export') and
							 i.brand_category not in ('Rice') and 
							 i.item_id not in ('1096000100010312','1096000100010313','700020001')
							  order by i.serial");					
                    while($item=mysqli_fetch_array($res)){						
						$id=$item[item_id];						
						$effectivetp_up=$_POST['effectivetp'.$id];
						mysqli_query($conn, "UPDATE effective_tp SET effective_tp=".$effectivetp_up." where item_id=".$id."");
					}
					unset($_POST);					       
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
<?php require_once 'body_content.php'; ?>

    <!-------------------list view ------------------------->

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Effective TP</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                    <thead>


                    <tr>
                        <th>#</th>
                        <th>Serial</th>
                        <th>CODE</th>
                        <th>Item Description</th>
                        <th>Unit</th>                        
                        <th>Regular TP</th>
                        <th width="13%">Activated TP</th>
                        <th width="">Suggested TP</th>
                        
                        
                         <th>Regular DP</th>
                        <th width="13%">Activated DP</th>
                        <th width="">Suggested DP</th>
                        
                    </tr></thead>

                    <tbody>
                    <?php

$end_date='2019-09-30';
                    //$res=mysqli_query($conn,"select * from item_info where sub_group_id in ('200010000','2400010000','500020000') and exim_status not in ('Export') and brand_category not in ('Rice') and status in ('Active') order by serial");
					
					
					$res=mysqli_query($conn,"SELECT i.*,sg.sub_group_name,g.group_name,
					
					(select effective_tp from effective_tp where item_id=i.item_id) as effective_tp
					 FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id	in ('500000000') and 
							 i.status in ('Active')	and
							 i.exim_status not in ('Export') and
							 i.brand_category not in ('Rice') and 
							 i.item_id not in ('1096000100010312','1096000100010313','700020001')
							  order by i.serial");
					
                    while($item=mysqli_fetch_array($res)){
                        $id=$item[item_id];
                        $effectivetpGET=$_POST['effectivetp'.$id];
                        $buyqty=find_all_field('sale_gift_offer','','item_id="'.$id.'" and status in ("Active") and dealer_type in ("Distributor")');

                       if($buyqty->item_id==$buyqty->gift_id) {
                            $effectivetpcalculation = ($item[t_price] * $buyqty->item_qty)/($buyqty->item_qty+$buyqty->gift_qty);
                        } else {
                            $effectivetpcalculation = $item[t_price];

                        }
                        if($effectivetpcalculation>0) {
                            $effectivetpcalculation = $effectivetpcalculation;
                        } else {
                            $effectivetpcalculation = $item[t_price];
                        }
                        if($buyqty->gift_type=='Cash'){
                            $cashdiscount=($buyqty->gift_qty/$buyqty->item_qty);
                            $actialeffectivetp=$effectivetpcalculation-$cashdiscount;
                        } else {

                            $actialeffectivetp=$effectivetpcalculation;
                        }



                        if(isset($_POST[record])){
                            if($effectivetpGET>0){

                                mysql_query("INSERT INTO `effective_tp` (item_id,effective_tp,start_date,end_date,region,territory,town_code,customer_type,section_id,company_id) VALUE 
('$id','$effectivetpGET','$start_date','$end_date','','','','','$_SESSION[sectionid]','$_SESSION[companyid]')");
                            }

                        }
                        ?>
                    <tr><td><?=$i=$i+1;?></td>
                        <td><input type="text" autocomplete="off" name="serial_<?=$id?>" id="serial_<?=$id?>" value="<?=$item[serial]?>" style="text-align: center; width:50px" ></td>
                        <td><?=$item[finish_goods_code]?></td>
                        <td><?=$item[item_name]?></td>
                        <td><?=$item[unit_name]?></td>
                        <td><?=$item[t_price]?></td>
                        <td><?=$item[effective_tp]?></td>
                        <td><input type="text" name="effectivetp<?=$id?>" id="effectivetp<?=$id?>" value="<?=number_format($actialeffectivetp,2)?>" style="text-align: right; width:100px" > </td>
                        
                        
                        <td><?=$item[d_price]?></td>
                        <td><?=$item[effective_dp]?></td>
                        <td><input type="text" name="effectivedp<?=$id?>" id="effectivedp<?=$id?>" value="<?=number_format($actialeffectivedp,2)?>" style="text-align: right; width:100px" > </td>


                    </tr>
                    <?php } ?>
                    </tbody></table>
           <button type="submit" onclick='return window.confirm("Are you confirm to Update Serial?");' name="update_serial" style="float:left" id="update_serial"  class="btn btn-primary">Update Serial</button>
           <!--button type="submit" onclick='return window.confirm("Are you confirm to TP?");' name="record" id="record" style="float:right" class="btn btn-success">Update Effective TP </button-->
           
           <button type="submit" onclick='return window.confirm("Are you confirm to TP?");' name="tp_update" id="tp_update" style="float:right" class="btn btn-success">Update Effective TP </button>
                        
                </form>
            </div>

        </div></div>
   



<?php require_once 'footer_content.php' ?>