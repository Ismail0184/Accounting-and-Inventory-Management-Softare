<?php
ob_start();
require_once 'support_file.php';
$title='Batch Management';
$unique='id';
$unique_field='batch';
$table='lc_lc_received_batch_split';
$table_logs='QC_batch_modified_logs';
$lc_lc_received_batch_split="lc_lc_received_batch_split";
$journal_item='journal_item';
$page="QC_batch_management_edit.php";
$main_page = "QC_batch_management.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];



if(isset($_POST['cancelwarehouse'])){       
    unset($_SESSION['batch_warehouse_id']); 
    unset($_POST);
}
if(prevent_multi_submit()) {

    if(isset($_POST['record']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['create_date'] = date('Y-m-d');
        $_POST['warehouse_id'] = $_SESSION['batch_warehouse_id'];
        $_POST['mfg'] = $_POST['expiry_date'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d h:i:s');
        $_POST['source'] = 'MC';
        $crud->insert();


        $_POST['ji_date'] = date('Y-m-d');
        $_POST['item_id'] = $_POST[item_id];
        $_POST['warehouse_id'] = $_POST[warehouse_id];
        $_POST['item_in'] = $_POST[qty];
        $_POST['item_price'] = $_POST[rate];
        $_POST['total_amt'] = $_POST['qty']*$_POST['rate'];
        $_POST['batch'] = $_POST[batch];
        $_POST[expiry_date] = $_POST['mfg'];
        $_POST['tr_from'] = 'MC';
        $_POST[entry_by]= $_SESSION[userid];
        $_POST[entry_at]= date('Y-m-d H:s:i');
        $_POST[ip]=$ip;
        $crud      =new crud($journal_item);
        $crud->insert();

        unset($_POST);
        unset($$unique);}



//for Modify..................................
    if(isset($_POST['modify']))
    {       
            $data=find_all_field('lc_lc_received_batch_split','','id="'.$_POST[$unique].'"');
            if($data->id>0){
            $_POST[m_rate] = $_POST[rate];
            $_POST[m_expiry_date] = $_POST[expiry_date];
            $_POST[m_status] = $_POST[status];
            $_POST[batch] = $data->batch;
            $_POST[rate] = $data->rate;
            $_POST[expiry_date] = $data->mfg;
            $_POST[status] = $data->status;
            $_POST['entry_by']=$_SESSION['userid'];
            $crud      =new crud($table_logs);
            $crud->insert();
        }
        $_POST[mfg] = $_POST[expiry_date];
        $crud      =new crud($table);
        $crud->update($unique);
        $type = 1;
        unset($_POST);
		//echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";        
    }}



//for Delete..................................
if(isset($_POST['delete']))
{   $condition=$unique."=".$$unique;
    $crud->delete($condition);
    unset($$unique);
    $type=1;
    $msg='Successfully Deleted.';
}




if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data)){ $$key=$value;}}


$query='select b.id,b.batch,b.batch_no,i.item_id,i.item_name,i.unit_name,b.rate,DATE_FORMAT(b.mfg,"%M %d %Y") as expiry_date,w.warehouse_name,b.source,b.status,w.warehouse_id from lc_lc_received_batch_split b, item_info i,warehouse w 
where b.item_id=i.item_id and b.warehouse_id=w.warehouse_id and b.status="PROCESSING" and b.warehouse_id="'.$_POST['warehouse_id'].'" order by b.id desc';
$result = mysqli_query($conn, $query);
while($data=mysqli_fetch_object($result))
{ $i=$i+1;
    $present_stock = find_a_field('journal_item','SUM(item_in-item_ex)','item_id="'.$data->item_id.'" and warehouse_id="'.$data->warehouse_id.'" and batch="'.$data->batch.'"');
    if($present_stock>0){
        //echo $i.' - '.$data->batch.' - '.$data->item_id.' - active<br>';
    } else {
       $update=mysqli_query($conn, "UPDATE lc_lc_received_batch_split set status='COMPLETED' where status='PROCESSING' and item_id='".$data->item_id."' and warehouse_id='".$data->warehouse_id."' and batch='".$data->batch."' and id='".$data->id."'");
    }
}

if (isset($_POST[viewreport])) {
unset($_SESSION[batch_warehouse_id]);$_SESSION[batch_warehouse_id] = $_POST['warehouse_id'];
}
$res='select b.id,b.batch,b.batch_no,i.item_id,i.item_name,i.unit_name,b.rate,DATE_FORMAT(b.mfg,"%M %d %Y") as expiry_date,w.warehouse_name,b.source,
(SELECT SUM(item_in-item_ex) from journal_item where item_id=i.item_id and warehouse_id=w.warehouse_id and batch=b.batch) as present_stock,b.completed_at,b.status
from lc_lc_received_batch_split b, item_info i,warehouse w 
where b.item_id=i.item_id and b.warehouse_id=w.warehouse_id and b.warehouse_id="'.$_SESSION['batch_warehouse_id'].'" order by b.id desc';
						

$condition="create_date='".date('Y-m-d')."'";
?>
<?php require_once 'header_content.php'; ?>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php if(isset($_GET[$unique])):
 require_once 'body_content_without_menu.php'; else :
 require_once 'body_content.php'; endif;  ?>








<?php if(isset($_GET[$unique])): ?>
<div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="input-group pull-right"></div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                            <?php else: ?>

<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Record
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
          </h5>
        </div>
        <div class="modal-body">
        <?php endif; ?>
                                <form action="<?=$main_page;?>" enctype="multipart/form-data" method="post" name="addem" id="addem" class="form-horizontal form-label-left" style="font-size: 11px" >
                                    <input type="hidden" name="<?=$unique?>"  value="<?=$_GET['id']?>">
                                    <input type="hidden" name="uid"  value="">

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Batch<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" name="batch"  value="<?=($_GET[$unique]>0) ? $batch : automatic_number_generate(20,$lc_lc_received_batch_split,'batch',$condition,'000'); ?>" <?=($_GET[$unique]>0) ? 'readonly' : '' ;?> style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                                        </div></div>

                                        <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Batch No<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="text" name="batch_no" value="<?=$batch_no?>" <?=($_GET[$unique]>0) ? 'readonly' : '' ;?> style="width:100%; font-size: 12px" class="form-control col-md-7 col-xs-12" required />
                                        </div></div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Item Name<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                        <select style="width: 100%" class="select2_single form-control" name="item_id" id="item_id">
                                        <option></option>
                                        <?=advance_foreign_relation(find_all_item($product_nature="'Salable','Both'"),$item_id);?>
                                        </select>    
                                    </div>
                                    </div>
                                    
                    

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Rate:<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input <?=($_GET[$unique]>0) ? 'readonly' : '' ;?> type="text" id="rate" style="width:100%; font-size: 11px; left:left" name="rate" value="<?=$rate;?>" class="form-control col-md-7 col-xs-12" placeholder="DP" title="Dealer Price">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Qty:<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input <?=($_GET[$unique]>0) ? 'readonly' : '' ;?> type="text" id="qty" style="width:100%; font-size: 11px; left:left" name="qty" value="<?=$qty;?>" class="form-control col-md-7 col-xs-12" title="Dealer Price">
                                        </div>
                                    </div>

                                   <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Expiry Date:</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <input type="date" style="width:100%; font-size: 11px; left:left" name="expiry_date" <?=($_GET[$unique]>0) ? 'readonly' : '' ;?> value="<?=$mfg;?>" class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">Batch Current Status<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="width: 60%">
                                            <select style="width: 100%" class="select2_single form-control" name="status" id="status">
                                              <option value=""></option>
                                              <option value="UNCHECKED" <?=($status=='UNCHECKED')? 'selected':'';?>>UNCHECKED</option>
                                              <option value="CHECKED" <?=($status=='CHECKED')? 'selected' : '';?>>CHECKED</option>
                                              <option value="PROCESSING" <?=($status=='PROCESSING')? 'selected' : '';?>>PROCESSING</option>
                                              <option value="COMPLETED" <?=($status=='COMPLETED')? 'selected' : '';?>>COMPLETED</option>
                                              <option value="HOLDED" <?=($status=='HOLDED')? 'selected' : '';?>>HOLDED</option>
                                              <option value="SUSPENDED" <?=($status=='SUSPENDED')? 'selected' : '';?>>SUSPENDED</option>
                                              
                                            </select></div>
                                    </div>


                                    <hr/>
                                    <?php if($_GET[$unique]):  ?>
                                        <?php $status=find_a_field('lc_lc_received_batch_split','status','id='.$_GET['id']);?>
                                        <?php if($status=='COMPLETED'):  ?>
                                            <h6 style="color:red; text-align:center"><i>This Batch has been COMPLETED</i></h6>
                                            <?php else : ?>
                                        
                                    <div class="form-group" style="margin-left:40%">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button type="submit" name="modify" id="modify" class="btn btn-primary">Modify Batch</button>
                                    </div></div>
                                    <?php endif; ?>
                                    <?php else : ?>
                                    <div class="form-group" style="margin-left:40%">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record"  style="font-size:12px" class="btn btn-primary">Add New Batch</button></div></div> <?php endif; ?>


                        </form>
                    </div></div></div><?php if(!isset($_GET[$unique])): ?></div><?php endif; ?>

    
    
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 30%;">
            <tr>
                <td><select  class="form-control" style="width: 200px;font-size:11px; height: 30px" required="required"  name="warehouse_id">
                        <option selected></option>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$_SESSION['batch_warehouse_id']);?>
                    </select></td>
                <td style="padding: 10px; width:50%">
                <button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">Select and Proceed to Next</button>
                <?php if($_SESSION[batch_warehouse_id]>0){?>
                <button type="submit" style="font-size: 11px; height: 30px" name="cancelwarehouse"  class="btn btn-danger">Cancel</button>
                <?php }?>
                </td>
            </tr></table>
    </form>

<?php if($_SESSION['batch_warehouse_id']>0): ?>
<?=$crud->report_templates_with_add_new($res,$title,12,$action=$_SESSION["userlevel"],$create=1);?>
<?php endif; ?>
<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>
