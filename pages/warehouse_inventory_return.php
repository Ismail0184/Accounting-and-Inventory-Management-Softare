<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Inventory Return";
$now=time();
$unique='id';
$unique_field='ref_no';
$table="purchase_return_master";
$unique_details='m_id';
$table_details="purchase_return_details";
$page="warehouse_inventory_return.php";
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
    {   $d =$_POST[return_date];
        $_POST[return_date]=date('Y-m-d' , strtotime($d));
        $_POST[status]='MANUAL';
        $_POST[vendor_ledger]=find_a_field('vendor','ledger_id','vendor_id="'.$_POST[vendor_id].'"');
        $_POST[warehouse_ledger]=find_a_field('warehouse','ledger_id_RM','warehouse_id="'.$_POST[warehouse_id].'"');
        $crud->insert();
        $_SESSION['wir_unique']=$_POST[$unique];
        $type=1;
        $msg='New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }



//for modify..................................
if(isset($_POST['modify']))
{
    $d =$_POST[return_date];
    $_POST[return_date]=date('Y-m-d' , strtotime($d));
    $_POST['edit_at']=time();
    $_POST['edit_by']=$_SESSION['userid'];
    $_SESSION['pono']=$_POST[po_no];
    $_POST[vendor_ledger]=find_a_field('vendor','ledger_id','vendor_id="'.$_POST[vendor_id].'"');
    $_POST[warehouse_ledger]=find_a_field('warehouse','ledger_id_RM','warehouse_id="'.$_POST[warehouse_id].'"');
    $crud->update($unique);
    $type=1;
}}

if(isset($_POST['add']))
{
        $crud   = new crud($table_details);
        $_POST['entry_by']=$_SESSION['userid'];
        $_POST['entry_at']=date('Y-m-d h:s:i');
        $_POST['m_id']=$_SESSION['wir_unique'];
        $_POST['cogs_price']=$_POST[batch_rate];

        $_POST['status']='MANUAL';
        $_POST[section_id]=$_SESSION[sectionid];
        $_POST[company_id]=$_SESSION[companyid];
        $crud->insert();
    }

} // prevent_multi_submit


//for Delete..................................
if(isset($_POST['cancel']))
{
    $crud   = new crud($table);
    $condition=$unique."=".$_SESSION['wir_unique'];
    $crud->delete($condition);
    $crud   = new crud($table_details);
    $condition=$unique_details."=".$_SESSION['wir_unique'];
    $crud->delete_all($condition);
    unset($_SESSION['wir_unique']);
    unset($_SESSION['pono']);}


//for single FG Delete..................................
$res='select a.id, concat(b.item_id," # ", b.item_name) as item_description,a.batch,b.unit_name as unit ,a.qty ,a.rate as unit_price,a.amount from purchase_return_details a,item_info b where b.item_id=a.item_id and a.m_id='.$_SESSION[wir_unique];
$results=mysqli_query($conn,$res);
while($data=mysqli_fetch_object($results)){
    $id=$data->id;
    if(isset($_POST['deletedata'.$id]))
    {$del="DELETE FROM ".$table_details." WHERE id='".$id."'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);}
	 if(isset($_POST['editdata'.$id]))
    { mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST[item_id]."',batch='".$_POST[batch]."',qty='".$_POST[qty]."',rate='".$_POST[rate]."',amount='".$_POST[amount]."' WHERE id=".$id));
      unset($_POST);
    }}



 if(isset($_POST['confirm']))
 {
     $_POST[$unique]=$_SESSION['wir_unique'];
     $_POST['entry_by']=$_SESSION['userid'];
     $_POST['entry_at']=date('Y-m-d h:s:i');
     $_POST['status']='UNCHECKED';
     $crud   = new crud($table);
     $crud->update($unique);
     $crud   = new crud($table_details);
     $crud->update($unique);
     unset($_SESSION['wir_unique']);
 }

// data query..................................
if(isset($_SESSION['wir_unique']))
{   $condition=$unique."=".$_SESSION['wir_unique'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


if (isset($_GET[id])) {
$edit_value=find_all_field(''.$table_details.'','','id='.$_GET[id].'');}
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique_details.'='.$_SESSION[wir_unique].'');
$batch_stock_get=find_a_field('journal_item','SUM(item_in-item_ex)','item_id='.$_GET[item_id].' and batch='.$_GET[batch].' and warehouse_id='.$warehouse_id.'');
$batch_data_get=find_all_field('lc_lc_received_batch_split','','status="PROCESSING" and item_id='.$_GET[item_id].' and batch='.$_GET[batch].' and warehouse_id='.$warehouse_id.'');
?>


<?php require_once 'header_content.php'; ?>
 <SCRIPT language=JavaScript>
 function reload(form)
 {var val=form.item_id.options[form.item_id.options.selectedIndex].value;
 self.location='<?=$page?>?item_id=' + val ;}

 function reload2(form)
 {var val=form.batch.options[form.batch.options.selectedIndex].value;
 self.location='<?=$page?>?item_id=<?=$_GET[item_id]?>&batch=' + val ;}
 </script>
 <script src="js/vendor/modernizr-2.8.3.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
 <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content_nva_sm.php'; ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2><?php echo $title; ?></h2>
                              <a  style="float: right" target="_blank" class="btn btn-sm btn-default"  href="warehouse_inventory_return_report.php">
                                  <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">View Report</span></a>
                              <div class="clearfix"></div>
                          </div>
                            <div class="x_content">
                                <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                                    <? require_once 'support_html.php';?>
                                    <table style="width: 100%; font-size:11px">
                                      <tr>
                                          <th style="width:15%;">ID<span class="required">*</span></th><th style="width: 2%;">:</th>
                                          <td style="width:28%"><input type="text" id="<?=$unique?>" style="width:90%;font-size:11px"  required   name="<?=$unique?>" value="<? if($$unique>0) echo $$unique; else echo (find_a_field($table,'max('.$unique.')','1')+1);?>" readonly class="form-control col-md-7 col-xs-12" ></td>
                                          <th style="width:15%;">Ref. No<span class="required">*</span></th><th style="width: 2%">:</th>
                                          <td style="width:28%"><input type="text" id="<?=$unique_field?>" style="width:90%;font-size:11px"  required   name="<?=$unique_field?>" value="<?=$$unique_field?>"  class="form-control col-md-7 col-xs-12" ></td>
                                          </tr>
                                          <tr>
                                            <th>Date<span class="required">*</span></th><th>:</th>
                                            <td><input type="date" name="return_date" id="return_date"  value="<?=($return_date!='')? $return_date : date('Y-m-d') ?>" max="<?=date('Y-m-d');?>" class="form-control col-md-7 col-xs-12" required style="width: 90%; margin-top: 5px;font-size:11px"></td>
                                            <th>Remrks</th><th>:</th>
                                            <td><input type="text" name="remarks" id="remarks"  value="<?=$remarks?>" class="form-control col-md-7 col-xs-12" style="width: 90%; margin-top: 5px;font-size:11px"></td>
                                        </tr>
                                        <tr><td style="height:5px"></td></tr>
                                         <tr>
                                          <th>Vendor<span class="required">*</span></th><th style="width: 2%;">:</th>
                                          <td><select style="width: 90%;font-size:11px" class="select2_single form-control"  required name="vendor_id" id="vendor_id">
                                              <option></option>
                                              <?=foreign_relation('vendor','vendor_id','concat(vendor_id," : ",vendor_name)',$vendor_id);?>
                                          </select></td>
                                          <th>Warehouse<span class="required">*</span></th><th style="width: 2%">:</th>
                                          <td><select style="width: 90%;font-size:11px" class="select2_single form-control"  required name="warehouse_id" id="warehouse_id">
                                              <option></option>
                                              <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_id);?>
                                              </select></td>
                                        </tr>
                                    </table>

                                            <br>
                                            <?php if($_SESSION['wir_unique']){  ?>
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



<?php if($_SESSION[wir_unique]>0):?>
             <form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
             <?php require_once 'support_html.php';?>
             <input type="hidden" name="<?=$unique_field?>" id="<?=$unique_field?>" value="<?=$$unique_field?>">
             <input type="hidden" name="po_no" id="po_no" value="<?=$po_no?>">
             <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse_id?>">
             <input type="hidden" name="return_date" id="return_date" value="<?=$return_date?>">
             <input type="hidden" name="vendor_id" id="vendor_id" value="<?=$vendor_id?>">
             <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
             <thead>
             <tr style="background-color: bisque">
             <th style="text-align: center">Item Name</th>
             <th style="text-align: center">Batch</th>
             <th style="text-align: center">Batch Stock</th>
             <th style="text-align: center">Batch Rate</th>
             <th style="text-align: center">Unit Price</th>
             <th style="text-align: center">Qty</th>
             <th style="text-align: center">Amount</th>
             <th style="text-align: center">Action</th>
             </tr>
             </thead>
                   <tbody>
                   <tr>
                     <td style="vertical-align:middle"><input  name="<?=$unique?>" type="hidden" id="<?=$unique?>" value="<?=$_SESSION[initiate_po_no];?>"/>
                     <input  name="po_id" type="hidden" id="po_id" value="<?=$_SESSION[initiate_po_id];?>"/>
                       <input  name="warehouse_id" type="hidden" id="warehouse_id" value="<?=$warehouse_id?>"/>
                       <input  name="po_date" type="hidden" id="po_date" value="<?=$po_date?>"/>
                       <input  name="vendor_id" type="hidden" id="vendor_id" value="<?=$vendor_id?>"/>
                         <input  name="pono" type="hidden" id="pono" value="<?=$_SESSION[initiate_pono];?>"/>
                         <select class="select2_single form-control" style="width: 100%" tabindex="-1" onchange="javascript:reload(this.form)" required="required" name="item_id" id="item_id">
                             <option></option>
                             <?=advance_foreign_relation($sql_item_id, ($_GET[item_id]>0)? $_GET[item_id] : $edit_value->item_id)?>
                         </select>
                         </td>
                         <td style="vertical-align:middle;width: 10%">
                           <select class="select2_single form-control" style="width: 100%" tabindex="-1" onchange="javascript:reload2(this.form)" required="required" name="batch" id="batch">
                               <option></option>
                               <?=foreign_relation('lc_lc_received_batch_split', 'batch', 'CONCAT(batch," : ", batch_no)', $_GET[batch], 'warehouse_id='.$warehouse_id.' and item_id='.$_GET[item_id]); ?>
                           </select>
                         </td>
                       <td style="vertical-align:middle;width: 10%">
                       <input type="number" name="batch_stock"   id="batch_stock" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center;" value="<?=$batch_stock_get?>"  readonly  />
                       </td>

                       <td style="vertical-align:middle;width: 10%">
                       <input type="number" name="batch_rate"   id="batch_rate" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center;" value="<?=$batch_data_get->rate?>" readonly />
                       <input type="date" name="expiry_date" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center; display:none" value="<?=$batch_data_get->mfg?>" readonly />   
                       </td>

                       <td style="vertical-align:middle;width: 10%">
                       <input type="number" name="rate"   id="rate" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center;" value="<?=$edit_value->rate?>"  required="required" step="any" min="0.01" class="rate" />
                       </td>

                       <td style="vertical-align:middle;width: 10%">
                       <input type="number" name="qty"  onkeyup="doAlert(this.form);" id="qty" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px;font-size: 11px; text-align:center;" value="<?=$edit_value->qty?>" step="any" min="1" class="qty" />
                       </td>
                       
                       <td align="center" style="vertical-align:middle;width: 12%">
                       <input type="number" name="amount" readonly id="amount" class="form-control col-md-7 col-xs-12" style="width:96%; margin-left:2%; height:38px; font-size: 11px;text-align:center;" value="<?=$edit_value->amount?>" step="any" min="1" />
                       </td>
                       <td align="center" style="vertical-align:middle;width:7%">
                       <?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                                 <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td>
                   </tr>
                 </table></form>
             <?=added_data_delete_edit($res,$unique,$unique_GET,$COUNT_details_data,$page);?>
             <?php endif;?>
             <?=$html->footer_content();mysqli_close($conn);?>
             <script>
                 $(function(){
                     $('#rate,#qty').keyup(function(){
                         var rate = parseFloat($('#rate').val()) || 0;
                         var qty = parseFloat($('#qty').val()) || 0;
                         $('#amount').val((qty * rate).toFixed(2));
                     });
                 });
             </script>
             <SCRIPT language=JavaScript>
                 function doAlert(form)
                 {
                     var val=form.qty.value;
                     var val2=form.batch_stock.value;
                     if (Number(val)>Number(val2)){
                         alert('oops!! exceed stock limit!! Thanks');
                         form.qty.value='';
                     }
                     form.qty.focus();
                 }</script>
