<?php
 require_once 'support_file.php'; 
 $title='Re-processing Transfer';

$now=time();
$unique='pi_no';
$table="re_processing_master";
$table_details="re_processing_detail";

$page="re_processing_transfer.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');
$item_info=find_all_field('item_info','','item_id='.$_GET[item_id].'');


function rpsto_no_create($conn,$create_date)
{
    list( $year1, $month, $day) = preg_split("/[\/\.-]+/", $create_date);
    $tdatevalye=substr($year1,2,3).$month.$day;
    $sekeyword='RPSTO';
    $query = "Select distinct custom_pi_no from  re_processing_master where create_date='".$_SESSION[create_date]."' and custom_pi_no like '$sekeyword%'  ORDER BY custom_pi_no DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result)
    {   if (mysqli_num_rows($result) == 0){
            $vnos="RPSTO".$tdatevalye."001";
            $custom_no= $vnos;
        } else {
            while($row = mysqli_fetch_array($result)) {
                $sl= substr($row['custom_pi_no'],-3);
                $sl=$sl+1;
                if (strlen($sl)==1) {
                    $sl="00".$sl;
                } else if (strlen($sl)==2){
                    $sl="0".$sl;
                }
                $custom_no= $sekeyword.$tdatevalye.$sl;
            }}
        mysqli_free_result($result);
    }
    return $custom_no;
}





if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST[ISSUE_TYPE]='STO';
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION[initiate_reprocessing_transfer]=$_POST[custom_pi_no];
        $_SESSION['pi_no'] =$_POST[$unique];
        $$unique=$_POST[$unique];
        $_SESSION['production_warehouse'] =$_POST[warehouse_to];
        $_POST[create_date]=$create_date;
        $_POST[ip]=$ip;
        $crud->insert();
        $type=1;
        unset($_POST);
        unset($$unique);
    }

//for modify PS information ...........................
    if(isset($_POST['modify']))
    {   $d =$_POST[pi_date];
        $_POST[pi_date]=date('Y-m-d' , strtotime($d));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['total_unit']>0) {
        $_POST[status]="MANUAL";
        $_POST[ISSUE_TYPE]="STO";
        $_POST[ip]=$ip;
        $_POST[total_unit]=$_POST[total_unit];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $crud = new crud($table_details);
        $crud->insert();
    }}



} /// prevent multi submit


if(isset($_POST['confirmsave']))
{   $up="UPDATE ".$table." SET status='UNCHECKED' where ".$unique."='$_SESSION[pi_no]'";
    $update_table_master=mysqli_query($conn, $up);
    $up2="UPDATE ".$table_details." SET status='UNCHECKED' where ".$unique."='$_SESSION[pi_no]'";
    $update_production_floor_issue_master=mysqli_query($conn, $up2);
    unset($_SESSION['pi_no']);
    unset($_SESSION['initiate_reprocessing_transfer']);
    unset($_POST);
}


//for single FG Delete..................................
$query="Select * from ".$table_details." where ".$unique."='$_SESSION[pi_no]'";
$res=mysqli_query($conn, $query);
while($row=mysqli_fetch_array($res)){
    $ids=$row[id];
    if(isset($_POST['deletedata'.$ids]))
    {
        $del="DELETE FROM ".$table_details." WHERE id='$ids' and ".$unique."='$_SESSION[pi_no]'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);
    }}

//for Delete..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table_details);
    $condition =$unique."=".$_SESSION['pi_no'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['pi_no'];
    $crud->delete($condition);
    unset($_SESSION['pi_no']);
    unset($_SESSION['initiate_reprocessing_transfer']);
    unset($_POST);
}

$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$_SESSION['pi_no'].'');


// data query..................................
if(isset($_SESSION['pi_no']))
{   $condition=$unique."=".$_SESSION['pi_no'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$result_details=mysqli_query($conn, "Select t.*,i.* 
from 
".$table_details." t,item_info i 
where   
t.".$unique."=".$_SESSION[pi_no]." and t.item_id=i.item_id");

?>
<?php require_once 'header_content.php'; ?>
    <SCRIPT language=JavaScript>
        function reloaditem(form)
        {   var val=form.item_id.options[form.item_id.options.selectedIndex].value;
            self.location='<?=$page;?>?transfer_from=' + '<?php echo $_GET[transfer_from]; ?>' + '&item_id=' + val;
        }
    </script>
<style>
    input[type=text]{
        font-size: 11px;
    }
    input[type=date]{
        font-size: 11px;
    }
</style>
<?php require_once 'body_content.php'; ?>

              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2><?php echo $title; ?></h2>
                          <a target="_new" style="float: right" class="btn btn-sm btn-default"  href="warehouse_re_processing_view.php">
                              <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">Transfer View</span>
                          </a>
                          <div class="clearfix"></div>
                      </div>

                  <div class="x_content">


                      <form method="post" name="addem" id="addem" class="form-horizontal form-label-left"  style="font-size: 11px">
                          <table style="width:100%">
                              <tr>
                                  <th style="padding: 2px">RP No</th><th style="width: 1%;padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%">
                                      <input style="width:80%; height: 30px"  name="custom_pi_no" type="text" id="custom_pi_no" value="<?php if($_SESSION[initiate_reprocessing_transfer]){ echo $custom_pi_no;} else { echo $custom_nos=rpsto_no_create($conn,$_SESSION[create_date]); } ?>"  readonly/>
                                      <?
                                      $pi_nos=find_a_field(''.$table.'','max('.$unique.')','1');
                                      if($_SESSION['pi_no']>0) {
                                          $pi_noGET = $_SESSION['pi_no'];
                                      } else {
                                          $pi_noGET=$pi_nos+1;
                                          if($pi_nos<1) $pi_noGET = 1;
                                      }
                                      ?>
                                      <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$pi_noGET;?>">                                  </td>

                                  <th style="padding: 2px">RP Date</th><th style="width: 1%; padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%">
                                      <input style="width:80%; font-size: 11px; height: 30px" required  name="pi_date" type="date" max="<?=date('Y-m-d')?>" id="pi_date" value="<?=$pi_date;?>">
                                  </td>

                                  <th style="padding: 2px">Remarks</th><th style="width: 1%; padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%">
                                      <input style="width:80%; font-size: 11px; height: 30px"  name="remarks" type="text" id="remarks" value="<?=$remarks;?>">
                                  </td>
                              </tr>

                              <tr>
                                  <th style="padding: 2px">Transfer From</th><th style="width: 1%; padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%"><select class="form-control" style="width:80%; font-size: 11px; height: 30px"  tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                                          <option></option>
                                          <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_from, 'use_type in (\'PL\',\'WH\')'); ?>
                                      </select></td>

                                  <th style="padding: 2px">Transfer To</th><th style="width: 1%; padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%">
                                      <select class="form-control" style="width:80%; font-size: 11px; height: 30px"  tabindex="-1" required="required"  name="warehouse_to" id="warehouse_to">
                                          <option></option>
                                          <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_to, 'use_type in (\'PL\',\'WH\')'); ?>
                                      </select>
                                  </td>

                  <th style="padding: 2px">VAT Challan</th><th style="width: 1%; padding: 2px">:</th>
                  <td style="padding: 2px; width: 25%">
                      <input type="text" id="VATChallanno" style="width:80%; height: 30px"  name="VATChallanno" value="<?=$VATChallanno;?>" class="form-control col-md-7 col-xs-12"  Placeholder="Challan & Date" >
                  </td>
                  </tr>



                              <tr>
                                  <th style="padding: 2px">Transporter</th><th style="width: 1%; padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%"><select class="form-control" style="width:80%; font-size: 11px; height: 30px"  tabindex="-1" required="required"  name="transporter" id="transporter">
                                          <option>Other</option>
                                          <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $transporter, 'vendor_category in (\'30\') order by vendor_name'); ?>
                                      </select></td>

                                  <th style="padding: 2px">Track No.</th><th style="width: 1%; padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%">
                                      <input type="text" id="track_no" style="width:80%; height: 30px"  name="track_no" value="<?=$track_no;?>" class="form-control col-md-7 col-xs-12">

                                  </td>

                                  <th style="padding: 2px">Driver Info</th><th style="width: 1%; padding: 2px">:</th>
                                  <td style="padding: 2px; width: 25%">
                                      <input type="text" id="driver_info" style="width:80%; height: 30px"  name="driver_info" value="<?=$driver_info;?>" class="form-control col-md-7 col-xs-12">
                                  </td>
                              </tr>




                              <tr>
                                  <td align="center" style="padding-top: 10px" colspan="9">
                                              <?php if($_SESSION[initiate_reprocessing_transfer]){  ?>
                                                  <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px">Update Transfer Entry</button>
                                              <?php   } else {?>
                                                  <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Initiate Transfer Entry</button>
                                              <?php } ?>
                                  </td>

                              </tr>
                          </table></form></div></div></div>
               


<?php if($_SESSION[initiate_reprocessing_transfer]){ ?>
 <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
                <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$_SESSION[pi_no];?>" >
                <input type="hidden" name="custom_pi_no" id="custom_pi_no" value="<?=$custom_pi_no;?>" >
                <input type="hidden" name="pi_date" id="pi_date" value="<?=$pi_date;?>">
                <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
                <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
                <input type="hidden" name="section_id" id="section_id" value="<?=$_SESSION[sectionid];?>">
                <input type="hidden" name="company_id" id="company_id" value="<?=$_SESSION[companyid];?>">

                <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                    <thead>
                    <tr style="background-color: bisque">
                        <th style="text-align: center">Item Desctiption</th>
                        <th style="text-align: center">Unit</th>
                        <th style="text-align: center">Pack Size</th>
                        <th style="text-align: center">Batch No</th>
                        <th style="text-align: center">Stock in Pcs</th>
                        <th style="text-align: center">Qty in Pcs</th>
                        <th style="text-align: center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td align="center">
                            <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id" onchange="javascript:reloaditem(this.form)">
                                <option></option>
                                <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id in ('200000000','300000000','400000000','500000000') 							 
							  order by i.item_name";
                                advance_foreign_relation($sql_item_id,$_GET[item_id]);?>
                            </select>
                        </td>

                        <td style="width:10%" align="center">
                            <input type="text" id="unit" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="unit" readonly  class="form-control col-md-7 col-xs-12" value="<?=$item_info->unit_name;?>" >
                        </td>
                        <td style="width:10%" align="center">
                            <input type="text" id="pack_size" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="pack_size" readonly  class="form-control col-md-7 col-xs-12" value="<?=$item_info->pack_size;?>" >
                        </td>


                        <td style="width:15%" align="center">
                            <select class="form-control" style="width: 100%; font-size: 11px" tabindex="-1" required="required" name="batch" id="batch">
                                <option>0</option>
                                <? $sql_batch="SELECT distinct batch,batch FROM production_floor_receive_detail where item_id='$_GET[item_id]'  order by batch DESC";
                                advance_foreign_relation($sql_batch,$batch);?>
                            </select>
                            </td>

                        <td style="width:10%" align="center">
                            <input type="text" id="stock_balance" style="width:100%; height:37px; font-weight:bold; text-align:center"   name="stock_balance" readonly  class="form-control col-md-7 col-xs-12" value="<?=number_format($stock_balance,2);?>" >
                        </td>

                        <td style="width:15%" align="center">
                            <input type="text" id="total_unit" onkeyup="doAlert(this.form);" name="total_unit" style="width:100%; height:37px;text-align:center"  required="required"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        <td align="center" style="width:5%">
                            <button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 12px">Add</button></td></tr>
                    </tbody>
                </table>
            </form>




    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->
    <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left" style="font-size: 11px">
        <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
            <thead>
            <tr style="background-color: bisque">
                <th>SL</th>
                <th>Code / Barcode</th>
                <th>Item Desctiption</th>
                <th style="text-align:center">UOM</th>
                <th style="text-align:center">Pack Size</th>
                <th style="text-align:center">Batch</th>
                <th style=" text-align:center">Qty in Pcs</th>
                <th style="text-align:center">#</th>
            </tr>
            </thead>

            <tbody>
            <?php  while($row=mysqli_fetch_array($result_details)){ $ids=$row[id]; ?>
                <tr>
                    <td style="width:1%; vertical-align:middle"><?=$i=$i+1;?></td>
                    <td style="width:10%; vertical-align:middle"><?=$row[finish_goods_code];?></td>
                    <td style="vertical-align:middle"><?=$row[item_name];?></td>
                    <td style="width:10%; vertical-align:middle; text-align: center"><?=$row[unit_name];?></td>
                    <td style="width:10%; vertical-align:middle; text-align: center"><?=$row[pack_size];?></td>
                    <td align="center" style="width:10%; text-align:center"><?php echo $row[batch]; ?></td>
                    <td align="center" style="width:10%; text-align:center"><?=number_format($row[total_unit]);?></td>
                    <td align="center" style="width:5%;vertical-align:middle">
                        <!--a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row["id"]; ?>" data-toggle="modal"><i class="fa fa-times" data-toggle="tooltip"
                                                                                                                                 title="Delete"></i></a-->
                        <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                    </td>
                </tr>
            <?php }  ?>
            </tbody>
            <tr>
                <td colspan="6" style="font-weight:bold; font-size:11px" align="right">Total STO QTY = </td>
                <td style="text-align:center"><strong><?php echo $tp; ?></strong></td>
                <td align="center" ></td>
            </tr>
        </table>

        <button type="submit" style="float: left; margin-left: 1%; font-size: 12px" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to the Production Deleted?");' class="btn btn-danger">Delete STO</button>
        <?php if($COUNT_details_data>0) { ?>
            <button type="submit" style="float: right; margin-right: 1%; font-size: 12px" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Finish Transfer Entry </button>
        <?php } else { echo '';} ?>
    </form><br>


<?php } ?>

<?php require_once 'footer_content.php' ?>