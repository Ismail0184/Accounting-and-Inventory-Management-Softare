<?php
require_once 'support_file.php';
$title='Material Issue to CMU';
$now=time();
$unique='pi_no';
$unique_field='pi_date';
$table="production_issue_master";
$table_details="production_issue_detail";
$unique_details='pi_no';
$page="rmpmissuetoCMU.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$create_date=date('Y-m-d');

if(prevent_multi_submit()){
    if(isset($_POST['initiate'])) {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST[ip]=$ip;
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION[initiate_issue_to_CMU]=$_POST[custom_pi_no];
        $_POST[create_date]=$create_date;
        $_POST[status]='ISSUE';
        $_POST[ISSUE_TYPE]='ISSUE';
        $crud->insert();
        $_SESSION['initiate_issue_pi_no']=find_a_field('production_issue_master','pi_no','PI_ID="'.$_POST[PI_ID].'"');
        $type=1;
        unset($_POST);
        unset($$unique);}


//for modify PS information ...........................
    if(isset($_POST['modify']))
    {   $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);}


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['qtys']>0) {
        $_POST[status]="UNCHECKED";
        $_POST[total_unit] = $_POST['qtys'] * find_a_field('item_info','pack_size','item_id='.$_POST[item_id].'');
        $_POST[mfg_year]=date('Y' , strtotime($m));
        $_POST[mfg_month]=date('m' , strtotime($m));
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST['ip'] = $_SESSION['ip'];
        $_POST[total_amt] = $_POST[unit_price] * $_POST[total_unit];
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $crud = new crud($table_details);
        $crud->insert();
    }}




}

//for single FG Delete..................................
$results=mysqli_query($conn,"Select m.*,i.* from ".$table_details." m,item_info i where   
m.item_id=i.item_id and 
m.pi_no='$_SESSION[initiate_issue_pi_no]'");
while($row=mysqli_fetch_array($results)){
    $ids=$row[id];
    if(isset($_POST['deletedata'.$ids]))
    {$del="DELETE FROM ".$table_details." WHERE id='$ids'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);}
	 if(isset($_POST['editdata'.$ids])){
         $_POST[qty]=$_POST[qtys]*find_a_field('item_info','pack_size','item_id='.$_POST[item_id].'');
	     mysqli_query($conn, ("UPDATE ".$table_details." SET item_id='".$_POST[item_id]."',total_unit='".$_POST[qty]."',mfg='".$_POST[mfg]."',lot='".$_POST[lot]."',batch='".$_POST[batch]."' WHERE id=".$ids));
        unset($_POST);
    }
	}

if(isset($_POST['confirm']))
{   $up="UPDATE ".$table." SET verifi_status='UNCHECKED' where ".$unique."='$_SESSION[initiate_issue_pi_no]'";
    $update_table_master=mysqli_query($conn, $up);
    $up2="UPDATE ".$table_details." SET verifi_status='UNCHECKED' where ".$unique_details."='$_SESSION[initiate_issue_pi_no]'";
    $update_table_details=mysqli_query($conn, $up2);
    unset($_SESSION['initiate_issue_pi_no']);
    unset($_SESSION['initiate_issue_to_CMU']);
    unset($_POST);
    header("'.$page.'");
} // if confirm the posting



//for Delete..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table_details);
    $condition =$unique_details."=".$_SESSION['initiate_issue_pi_no'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['initiate_issue_pi_no'];
    $crud->delete($condition);
    unset($_SESSION['initiate_issue_pi_no']);
    unset($_SESSION['initiate_issue_to_CMU']);
    unset($_POST);
}
if (isset($_GET[id])) {
    $edit_value=find_all_field(''.$table_details.'','','id='.$_GET[id].'');
}
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)','pi_no='.$_SESSION['initiate_issue_pi_no'].'');
// data query..................................
if(isset($_SESSION[initiate_issue_pi_no]))
{   $condition=$unique."=".$_SESSION['initiate_issue_pi_no'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$results=mysqli_query($conn,"Select m.*,i.* from ".$table_details." m,item_info i where   
m.item_id=i.item_id and 
m.pi_no='$_SESSION[initiate_issue_pi_no]'");

$sql="Select d.id,i.item_id,i.item_name,i.unit_name,d.lot,d.batch,d.mfg,d.total_unit from ".$table_details." d,item_info i where   
d.item_id=i.item_id and 
d.pi_no='$_SESSION[initiate_issue_pi_no]'";


$sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id						 
							  order by i.item_name";
$item_master=find_all_field('item_info','','item_id='.$_GET[item_id]);
$item_landad_cost=find_a_field('item_landad_cost','landad_cost','status="Active" and item_id='.$_GET[item_id]);
?>
<?php require_once 'header_content.php'; ?>
<script language=JavaScript>
    function reload(form)
    {
        var val=form.item_id.options[form.item_id.options.selectedIndex].value;
        self.location='<?=$page?>?item_id=' + val ;
    }
</script>
<?php require_once 'body_content.php'; ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a target="_new" class="btn btn-sm btn-default"  href="material_issue_to_CMU_view.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Issued View</span>
								</a></div>
                    </ul><div class="clearfix"></div>
                  </div>
                  <div class="x_content">

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
<table style="width:100%">
    <tr>
        <td>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">ISSUE NO<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="hidden" name="PI_ID" id="PI_ID" value="<?=($_SESSION[initiate_issue_to_CMU]!='')? $PI_ID : automatic_number_generate("".$_SESSION[userid],"production_issue_master","custom_pi_no","create_date='".date('Y-m-d')."' and entry_by=".$_SESSION[userid].""); ?>" >
                    <input type="text" style="width: 30%; font-size: 11px" name="<?=$unique?>" id="<?=$unique?>" value="<?=$_SESSION[initiate_issue_pi_no]?>" class="form-control col-md-7 col-xs-12"  readonly >
                    <input type="text" id="custom_pi_no" required="required" style="font-size: 11px; width: 70%; text-align: left" name="custom_pi_no" value="<?=($_SESSION[initiate_issue_to_CMU]!='')? $_SESSION[initiate_issue_to_CMU] : automatic_number_generate("ISSUE".$_SESSION[userid],"production_issue_master","custom_pi_no","create_date='".date('Y-m-d')."' and entry_by=".$_SESSION[userid].""); ?>" class="form-control col-md-7 col-xs-12"  readonly >
                </div>
            </div>
        </td>

        <td>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="date" id="pi_date" style="width:100%; font-size: 11px"  required="required" name="pi_date" value="<?=$pi_date;?>" class="form-control col-md-7 col-xs-12" >

                </div>
            </div>
        </td>

        <td>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Remarks</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="remarks" style="width:100%; font-size: 11px" name="remarks" value="<?=$remarks?>" class="form-control col-md-7 col-xs-12" >
                </div>
            </div>
        </td>
    </tr>

    <tr>
<td style="width:35%">
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Issue From<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px; height: 30px" tabindex="-1" required="required" id="warehouse_from" name="warehouse_from">
                                <option></option>
                                <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
                                advance_foreign_relation($sql_plant,$warehouse_from);?>
                            </select>
                        </select></div></div>
</td>

<td style="width:35%">
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Issue To<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px; height: 30px" tabindex="-1" required="required" id="warehouse_to" name="warehouse_to">
                                <option></option>
    <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
    advance_foreign_relation($sql_plant,$warehouse_to);?></td>
<td style="width:30%">
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Challan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="last-name" style="width:100%; font-size: 11px"    required="required" name="VATChallanno" value="<?=$VATChallanno;?>" class="form-control col-md-7 col-xs-12"  >
                          </div>
                      </div> 
                      </td>
</tr>

    <tr>
        <td>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Transporter<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control" style="width:100%; font-size: 11px; height: 30px" required="required"  name="transporter" id="transporter">
                        <option></option>
                        <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $transporter, 'vendor_category in (\'30\') order by vendor_name'); ?>
                    </select>
                </div></div>
        </td>


        <td>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Track No.<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="ps_date" style="width:100%; font-size: 11px"  name="track_no" value="<?=$track_no?>" class="form-control col-md-7 col-xs-12" >
                </div>
            </div>
        </td>

        <td>
            <div class="form-group">

                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Driver Info<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="remarkspro" style="width:100%; font-size: 11px"  required="required" name="driver_info" value="<?=$driver_info;?>" class="form-control col-md-7 col-xs-12" Placeholder="Name & mobile No" >
                </div>
            </div>
        </td>
    </tr>


</table>
    <div class="form-group" style="margin-left:40%">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php if($_SESSION[initiate_issue_to_CMU]):  ?>
                <button type="submit" name="modify" id="modify" class="btn btn-primary" style="font-size: 11px">Update Data</button>
            <?php else: ?>
                <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 11px">Initiate Issue</button>
            <?php endif; ?>
        </div></div>
</form></div></div></div>
                      

<?php if($_SESSION[initiate_issue_to_CMU]):?>
<form action="<?=$page;?>" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
    <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$$unique;?>" >
    <input type="hidden" name="custom_pi_no" id="custom_pi_no" value="<?=$_SESSION[initiate_issue_to_CMU];?>" >
    <input type="hidden" name="pi_date" id="pi_date" value="<?=$pi_date;?>" >
    <input type="hidden" name="PI_ID" id="PI_ID" value="<?=$PI_ID;?>">
    <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
    <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
    <input type="hidden" name="ISSUE_TYPE" id="ISSUE_TYPE" value="ISSUE">
    <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
        <thead>
        <tr style="background-color: bisque">
            <th style="text-align: center">Material Details</th>
            <th style="text-align: center">Unit</th>
            <th style="text-align: center">Present Stock</th>
            <th style="text-align: center">Lot <br> Number</th>
            <th style="text-align: center">Batch <br> Number</th>
            <th style="text-align: center; width: 10%">MFG</th>
            <th style="text-align: center">Total Unit</th>
            <th style="text-align: center">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td style="vertical-align: middle">
                          <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id" <?php if($_GET[id]>0) : echo ''; else : ?>  onchange="javascript:reload(this.form)" <?php endif; ?> >
                              <option></option>
                              <?=advance_foreign_relation($sql_item_id,($_GET[item_id]!='')? $_GET[item_id] : $edit_value->item_id);?>
                          </select></td>
            <td style="width:10%; vertical-align: middle" align="center">
                <input type="text" id="units" style="width:99%; height:37px; font-size: 11px; text-align:center"  value="<?=$item_master->unit_name;?>"  name="units"  class="form-control col-md-7 col-xs-12" autocomplete="off" readonly ></td>
                <input type="hidden" id="unit_price" style="width:99%; height:37px; font-size: 11px; text-align:center"  value="<?=$item_landad_cost;?>"  name="unit_price"  class="form-control col-md-7 col-xs-12" autocomplete="off" readonly ></td>
            <td style="width:10%; vertical-align: middle" align="center">
                <input type="text" id="stock" style="width:99%; height:37px;font-size: 11px;text-align:center"   name="stock"  onkeyup="calc(this)" value="<?=$stockbalance=find_a_field("journal_item", "SUM(item_in-item_ex)","item_id='$_GET[item_id]' and warehouse_id='$warehouse_from'")/$item_master->pack_size;?>" class="form-control col-md-7 col-xs-12" autocomplete="off" readonly ></td>
            <td style="width:10%; vertical-align: middle" align="center">
                <input type="text" id="lot" style="width:99%; height:37px; font-size: 11px; text-align:center"  value="<?=$edit_value->lot;?>"  name="lot"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
            <td style="width:10%; vertical-align: middle" align="center">
                <input type="text" id="batch" style="width:99%; height:37px; font-size: 11px; text-align:center"  value="<?=$edit_value->batch;?>"  name="batch"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
            <td style="width:10%; vertical-align: middle" align="center">
                <input type="date" id="mfg" style="width:99%; height:37px; font-size: 11px; text-align:center"  value="<?=$edit_value->mfg;?>"  name="mfg"  class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>



<td style="width:10%; vertical-align: middle" align="center">
    <input type="text" id="qtys" style="width:99%; height:37px; font-size:11px; text-align:center"  required="required"  name="qtys" class="form-control col-md-7 col-xs-12" value="<?=$edit_value->total_unit/find_a_field('item_info','pack_size','item_id='.$edit_value->item_id.'');?>" autocomplete="off" ></td>

<td align="center" style="width:5%;vertical-align:middle">
                <?php if (isset($_GET[id])) : ?><button type="submit" class="btn btn-primary" name="editdata<?=$_GET[id];?>" id="editdata<?=$_GET[id];?>" style="font-size: 11px">Update</button><br><a href="<?=$page;?>" style="font-size: 11px"  onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Voucher?");' class="btn btn-danger">Cancel</a>
                <?php else: ?><button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 11px">Add</button> <?php endif; ?></td></tr> </tbody>

 </tbody></table></form>
    
<?=added_data_delete_edit($sql,$unique,$unique_GET,$COUNT_details_data,$page);?>
<?php endif; ?>
<?=$html->footer_content();?>
