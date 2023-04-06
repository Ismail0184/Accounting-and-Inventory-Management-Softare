<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Cycle Counting";
$now=date('Y-m-d H:i:s');
$unique='cc_no';
$unique_field='cc_date';
$table="acc_cycle_counting_master";
$unique_details='cc_no';
$table_details="acc_cycle_counting_detail";
$page="acc_inventory_cycle_counting.php";
$crud      =new crud($table);
$$unique = $_POST[$unique];

$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name) FROM  item_info i,
item_sub_group sg,
item_group g WHERE
i.sub_group_id=sg.sub_group_id and
sg.group_id=g.group_id and i.product_nature in ('Both','Salable')
order by i.finish_goods_code";

if(prevent_multi_submit()){
if(isset($_POST[$unique_field]))

//for insert..................................
{    $$unique = $_POST[$unique];
    if(isset($_POST['record']))
    {   $_POST['status']='MANUAL';
        $_POST['entry_at']=$now;
        $crud->insert();
        $_SESSION['cc_unique']=$_POST[$unique];
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }

if(isset($_POST['modify']))
{
    $_POST['entry_at']=$now;
    $crud->update($unique);
    unset($_POST);
}}

if(isset($_POST['add'])){
        $crud   = new crud($table_details);
        $_POST['entry_by']=$_SESSION['userid'];
        $_POST['entry_at']=date('Y-m-d h:i:s');
        $_POST['status']='MANUAL';
        $_POST['section_id']=$_SESSION['sectionid'];
        $_POST['company_id']=$_SESSION['companyid'];
        $crud->insert();
        unset($_POST);}
} // prevent_multi_submit


//for Delete..................................
if(isset($_POST['cancel']))
{
    $crud   = new crud($table);
    $condition=$unique."=".$_SESSION['cc_unique'];
    $crud->delete($condition);
    $crud   = new crud($table_details);
    $condition=$unique_details."=".$_SESSION['cc_unique'];
    $crud->delete_all($condition);
    unset($_SESSION['cc_unique']);
    unset($_SESSION['pono']);}


//for single FG Delete..................................
$res='select a.id, concat(b.item_id," # ", b.item_name) as item_description,a.batch,a.mfg as expiry_date,b.unit_name as unit,a.qty,a.item_price,a.total_amt,IF(a.cc_type="+", "Stock In","Stock Out") as  "CC Type" from
'.$table_details.' a,item_info b where b.item_id=a.item_id and a.'.$unique.'='.$_SESSION['cc_unique'];
$results=mysqli_query($conn,$res);
while($data=mysqli_fetch_object($results)){
    $id=$data->id;
    if(isset($_POST['deletedata'.$id]))
    {$del="DELETE FROM ".$table_details." WHERE id='".$id."'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);}
	 if(isset($_POST['editdata'.$id]))
    { mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST['item_id']."',batch='".$_POST['batch']."',qty='".$_POST['qty']."',item_price='".$_POST['item_price']."',total_amt='".$_POST['total_amt']."',cc_type='".$_POST['cc_type']."' WHERE id=".$id));
      unset($_POST);
    }}

 if(isset($_POST['confirm']))
 {
     $_POST[$unique]=$_SESSION['cc_unique'];
     $_POST['entry_by']=$_SESSION['userid'];
     $_POST['entry_at']=date('Y-m-d h:i:s');
     $_POST['status']='UNCHECKED';
     $crud   = new crud($table);
     $crud->update($unique);
     $crud   = new crud($table_details);
     $crud->update($unique);
     unset($_POST);
     unset($_SESSION['cc_unique']);
 }

// data query..................................
if(isset($_SESSION['cc_unique']))
{   $condition=$unique."=".$_SESSION['cc_unique'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


if(isset($_POST["Import"])){
            echo $filename=$_FILES["file"]["tmp_name"];
            if($_FILES["file"]["size"] > 0)
            {
                $file = fopen($filename, "r");
                while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
                {   //It wiil insert a row to our subject table from our csv file`
            if(is_numeric($emapData[0])) {
            $sql = "INSERT INTO ".$table_details." (`cc_no`,`cc_date`,`item_id`,`warehouse_id`,`cc_type`,`qty`,`item_price`,`total_amt`,`status`,`batch`,`mfg`,`section_id`,`company_id`)
            VALUES('".$_SESSION['cc_unique']."','".$_POST['cc_date']."','$emapData[0]','".$_POST['warehouse_id']."','$emapData[6]','$emapData[1]','$emapData[2]','$emapData[3]','UNCHECKED','$emapData[4]','$emapData[5]','".$_SESSION['sectionid']."','".$_SESSION['companyid']."')";
                    }
                    $result = mysqli_query( $conn, $sql);
                    if(! $result )
                    {
                        echo "<script type=\"text/javascript\">
              alert(\"Invalid File:Please Upload CSV File.\");
              window.location = ".$page."
            </script>";
                    }}
                fclose($file);
                echo "<script type=\"text/javascript\">
            alert(\"CSV File has been successfully Imported.\");
            window.location = ".$page."
          </script>";
            }header("Location: ".$page."");}    


if (isset($_GET['id'])) {
$edit_value=find_all_field(''.$table_details.'','','id='.$_GET['id'].'');}
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique_details.'='.$_SESSION['cc_unique'].'');
$batch_stock_get=find_a_field('journal_item','SUM(item_in-item_ex)','item_id='.$_GET['item_id'].' and batch='.$_GET['batch'].' and warehouse_id='.$warehouse_id.'');
$batch_get=find_all_field('lc_lc_received_batch_split','','item_id="'.$_GET['item_id'].'" and batch="'.$_GET['batch'].'" and warehouse_id="'.$warehouse_id.'"');
?>


<?php require_once 'header_content.php'; ?>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
 <script type="text/javascript">
 function reload(form){
var val=form.item_id.options[form.item_id.options.selectedIndex].value;
self.location='<?=$page;?>?item_id=' + val ;}
function reload_batch(form){
var val=form.batch.options[form.batch.options.selectedIndex].value;
self.location='<?=$page;?>?item_id=<?=$_GET['item_id']?>&batch=' + val ;}
</script>

<?php require_once 'body_content.php'; ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2><?php echo $title; ?></h2>
                              <a  style="float: right" target="_blank" class="btn btn-sm btn-default"  href="acc_inventory_cycle_counting_view.php">
                                  <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">View Report</span></a>
                              <div class="clearfix"></div>
                          </div>
                            <div class="x_content">
                                <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    <table style="width: 100%; font-size:11px">
                                      <tr>
                                          <th style="width:15%;">CC No<span class="required">*</span></th><th style="width: 2%;">:</th>
                                          <td style="width:28%"><input type="text" id="<?=$unique?>" style="width:90%;font-size:11px"  required   name="<?=$unique?>" value="<? if($$unique>0) echo $$unique; else echo (find_a_field($table,'max('.$unique.')','1')+1);?>" readonly class="form-control col-md-7 col-xs-12" ></td>
                                          <th style="width:15%;">Date<span class="required">*</span></th><th style="width: 2%">:</th>
                                          <td style="width:28%"><input type="date" name="cc_date" id="cc_date"  value="<?=($cc_date!='')? $cc_date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12" required style="width: 90%; margin-top: 5px;font-size:11px"></td>
                                          </tr>
                                        <tr><td style="height:5px"></td></tr>
                                         <tr>
                                          <th>Warehouse<span class="required">*</span></th><th style="width: 2%;">:</th>
                                          <td><select style="width: 90%;font-size:11px" class="select2_single form-control"  required name="warehouse_id" id="warehouse_id">
                                              <option></option>
                                              <?=advance_foreign_relation(check_plant_permission($_SESSION['userid']),$warehouse_id);?>
                                              </select></td>
                                          <th>Remarks</th><th style="width: 2%">:</th>
                                          <td><input type="text" name="remarks" id="remarks"  value="<?=$remarks?>" class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px;font-size:11px"></td>
                                        </tr>
                                    </table>

                                            <br>
                                            <?php if($_SESSION['cc_unique']){  ?>
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="modify" id="modify" style="font-size:12px" class="btn btn-primary">Update Info</button>
                                            </div></div>
                                            <?php } else {?>
                                            <div class="form-group" style="margin-left:40%">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" name="record" id="record" style="font-size:12px" class="btn btn-primary">Initiate Proceeding</button>
                                            </div></div>
                                            <?php } ?>
                                          </form>
                                </div>
                                </div>
                                </div>



<?php if($_SESSION['cc_unique']>0):?>
             <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
             <thead>
             <tr style="background-color: bisque">
             <th style="text-align: center; vertical-align:middle">Item Name</th>
             <th style="text-align: center; vertical-align:middle">Batch</th>
             <th style="text-align: center; vertical-align:middle">Expiry<br>Date</th>
             <th style="text-align: center; vertical-align:middle">Unit Price</th>
             <th style="text-align: center; vertical-align:middle">Qty</th>
             <th style="text-align: center; vertical-align:middle">Amount</th>
             <th style="text-align: center; vertical-align:middle">CC Type</th>
             <th style="text-align: center; vertical-align:middle">Action</th>
             </tr>
             </thead>
             <tbody>

             <form enctype="multipart/form-data" action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
             <input type="hidden" name="<?=$unique?>" id="<?=$unique?>" value="<?=$_SESSION['cc_unique']?>">
             <input type="hidden" name="<?=$unique_field?>" id="<?=$unique_field?>" value="<?=$$unique_field?>">
             <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse_id?>">
             <input type="hidden" name="cc_date"   value="<?=$cc_date;?>">
             <tr>
             <td colspan="7" style="vertical-align:middle" align="center"><input type="file" name="file" id="file"  /></td>
             <td style="width:5%;vertical-align:middle" align="center"><button type="submit" class="btn btn-primary" name="Import" id="Import" style="font-size: 11px">Import Data</button></td>
             </tr>
             </form>
            


            <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
             <input type="hidden" name="<?=$unique?>" id="<?=$unique?>" value="<?=$_SESSION['cc_unique']?>">
             <input type="hidden" name="<?=$unique_field?>" id="<?=$unique_field?>" value="<?=$$unique_field?>">
             <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse_id?>">
             <input type="hidden" name="cc_date"   value="<?=$cc_date;?>">
                   <tr>
                     <td style="vertical-align:middle">
                       <select class="select2_single form-control" style="width: 99%" tabindex="-1" onchange="javascript:reload(this.form)"  required="required" name="item_id" id="item_id">
                       <option></option>
                       <?=advance_foreign_relation($sql_item_id, ($_GET['item_id']>0)? $_GET['item_id'] : $edit_value->item_id)?>
                       </select></td>
                       <td style="vertical-align:middle;width: 10%">
                       <select class="select2_single form-control" style="width: 99%" tabindex="-1" onchange="javascript:reload_batch(this.form)"  required="required" name="batch" id="batch">
                       <option></option>
                       <?=foreign_relation('lc_lc_received_batch_split', 'batch', 'CONCAT(batch," : ", batch_no)', $_GET['batch'], 'warehouse_id = "'.$warehouse_id.'" and status="PROCESSING" and item_id='.$_GET['item_id']);?>
                       </select>
                       </td>
                       <td style="vertical-align:middle;width: 10%">
                           <input type="date" name="mfg" id="mfg" required class="form-control col-md-7 col-xs-12" readonly style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center;" value="<?=($_GET[batch]>0)? $batch_get->mfg : $edit_value->mfg ?>" />
                       </td>
                       <td style="vertical-align:middle;width: 10%">
                           <input type="number" name="item_price" id="item_price" required="required" readonly class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center;" value="<?=($_GET['batch']>0)? $batch_get->rate : $edit_value->item_price ?>"  required="required" step="any" min="0.01" class="item_price" />
                       </td>
                       <td style="vertical-align:middle;width: 10%">
                           <input type="number" name="qty" id="qty" required="required" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center;" value="<?=$edit_value->qty?>" step="any" min="1" class="qty" />
                       </td>
                       <td align="center" style="vertical-align:middle;width: 12%">
                           <input type="number" name="total_amt" readonly id="total_amt" required="required" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px; font-size: 11px;text-align:center;" value="<?=$edit_value->total_amt?>" step="any" min="1" />
                       </td>
                       <td style="vertical-align:middle;width: 10%">
                           <select class="form-control" style="width: 99%; font-size:11px" tabindex="-1"  required="required" name="cc_type" id="cc_type">
                               <option></option>
                               <option value="+" <?php if($edit_value->cc_type=='+') echo 'selected' ?>>Stock In</option>
                               <option value="-" <?php if($edit_value->cc_type=='-') echo 'selected' ?>>Stock Out</option>
                           </select></td>
                       <td align="center" style="vertical-align:middle;width:7%">
                       <?php if (isset($_GET['id'])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET['id'];?>" id="editdata<?=$_GET['id'];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                                 <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>


                       <script>
                           $(function(){
                               $('#item_price,#qty').keyup(function(){
                                   var item_price = parseFloat($('#item_price').val()) || 0;
                                   var qty = parseFloat($('#qty').val()) || 0;
                                   $('#total_amt').val((qty * item_price));
                               });
                           });
                       </script>
            </form></tr>
                 </table>
             <?=added_data_delete_edit($res,$unique,$unique_GET,$COUNT_details_data,$page);?>
             <?php endif;?>
             <?=$html->footer_content();mysqli_close($conn);?>

