<?php
 require_once 'support_file.php'; 
 $title='Incoming Material Received';

$now=time();
$unique='id';
$unique_PI='MAN_ID';
$unique_field='name';
$table="MAN_master";
$table_details="MAN_details";
$unique_details='m_id';
$page="Incoming_Material_Received.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');

if(prevent_multi_submit()){

    if(isset($_POST['initiate']))
    {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $d =$_POST[man_date];
        $_POST[man_date]=date('Y-m-d' , strtotime($d));
        $e =$_POST[delivary_challan_Date];
        $_POST[delivary_challan_Date]=date('Y-m-d' , strtotime($e));
        $f =$_POST[VAT_challan_Date];
        $_POST[VAT_challan_Date]=date('Y-m-d' , strtotime($f));
        $_POST[ip]=$ip;
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION['initiate_man_documents']=$_POST[MAN_ID];
        $_SESSION['m_id'] =$_POST[$unique];
        $_POST[create_date]=$create_date;
        $_POST[status]='MANUAL';
        $crud->insert();

        if($_FILES['dChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['dChallanPDF']['tmp_name'];
            $folder = "dc_documents/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'dc'.".pdf");}

        if($_FILES['VatChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['VatChallanPDF']['tmp_name'];
            $folder = "vc_documents/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'vc'.".pdf");}
        $type=1;
        unset($_POST);
        unset($$unique);
    }

//for modify PS information ...........................
    if(isset($_POST['modify']))
    {
        $d =$_POST[man_date];
        $_POST[man_date]=date('Y-m-d' , strtotime($d));
        $e =$_POST[delivary_challan_Date];
        $_POST[delivary_challan_Date]=date('Y-m-d' , strtotime($e));
        $f =$_POST[VAT_challan_Date];
        $_POST[VAT_challan_Date]=date('Y-m-d' , strtotime($f));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);

        if($_FILES['dChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['dChallanPDF']['tmp_name'];
            $folder = "../../po_documents/dChallanPDF/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'dc'.".pdf");}

        if($_FILES['VatChallanPDF']['tmp_name']!=''){
            $file_temp = $_FILES['VatChallanPDF']['tmp_name'];
            $folder = "../../po_documents/VatChallanPDF/";
            move_uploaded_file($file_temp, $folder.$_SESSION['m_id'].'_'.'vc'.".pdf");}
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['qty']>0) {
        $mfgsss =$_POST[mfg];
        $_POST[status]="UNCHECKED";
        $_POST[mfg]=date('Y-m-d' , strtotime($mfgsss));
        $_POST[mfg_year]=date('Y' , strtotime($m));
        $_POST[mfg_month]=date('m' , strtotime($m));
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $crud = new crud($table_details);
        $crud->insert();
    }}




}

//for single FG Delete..................................
$results=mysqli_query($conn,"Select m.*,i.* from ".$table_details." m,item_info i where   
m.item_id=i.item_id and 
m.m_id='$_SESSION[m_id]'");
while($row=mysqli_fetch_array($results)){
    $ids=$row[id];
    if(isset($_POST['deletedata'.$ids]))
    {
        $del="DELETE FROM ".$table_details." WHERE id='$ids'";
        $del_item=mysqli_query($conn, $del);
        unset($_POST);
    }}
    
if(isset($_POST['confirmsave']))
{
    $up="UPDATE ".$table." SET status='UNCHECKED' where ".$unique."='$_SESSION[m_id]'";
    $update_table_master=mysqli_query($conn, $up);
    $up2="UPDATE ".$table_details." SET status='UNCHECKED' where ".$unique_details."='$_SESSION[m_id]'";
    $update_table_details=mysqli_query($conn, $up2);
    unset($_SESSION['m_id']);
    unset($_SESSION['initiate_man_documents']);
    unset($_POST);
    header("'.$page.'");

} // if insert posting

//for Delete..................................
if(isset($_POST['cancel']))
{   $crud = new crud($table_details);
    $condition =$unique_details."=".$_SESSION['m_id'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['m_id'];
    $crud->delete($condition);

    $dc_delete = 'dc_documents/'."'.$unique.'".'_'.'dc'.'.pdf';
    unlink($dc_delete);

    $vc_delete = 'vc_documents/'."'.$unique.'".'_'.'vc'.'.pdf';
    unlink($vc_delete);
    unset($_SESSION['m_id']);
    unset($_SESSION['initiate_man_documents']);
    unset($_POST);
}
$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)','m_id='.$_SESSION['m_id'].'');
// data query..................................
if(isset($_SESSION['m_id']))
{   $condition=$unique."=".$_SESSION['m_id'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}

$results=mysqli_query($conn,"Select m.*,i.* from ".$table_details." m,item_info i where   
m.item_id=i.item_id and 
m.m_id='$_SESSION[m_id]'");
?>

<?php require_once 'header_content.php'; ?>
 
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='Incoming_Material_Received.php?Searchitem_id=' + val ;
}
</script>
<style>
    input[type=text]{
        font-size: 11px;
        height: 30px;
        width: 100%;
    }
    input[type=file]{
        font-size: 11px;
        height: 30px;
        width: 45%;
        float: right;
    }
</style>
<?php require_once 'body_content.php'; ?>

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                     <ul class="nav navbar-right panel_toolbox">



                         <a class="btn btn-sm btn-default"  href="MAN_report.php" target="_blank">
                             <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000; font-size: 11px">MAN View</span>
                         </a>

                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" style="font-size: 11px">

<table align="center" style="width:100%">

<tr>

             <td style="width:50%">

                 <div class="form-group">
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">MAN Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	            <input type="text" id="man_date"  required="required" name="man_date" value="<?php if($_SESSION[initiate_daily_production]){ echo date('m/d/Y' , strtotime($man_date)); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >
                 </div></div>
             </td>
    <?
    $ps_ids=find_a_field(''.$table.'','max('.$unique.')','1');
    if($_SESSION['m_id']>0) {
        $ps_idGET = $_SESSION['m_id'];
    } else {
        $ps_idGET=$ps_ids+1;
        if($ps_ids<1) $ps_idGET = 1;
    }
    ?>
<td style="width:50%"><div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name" style="width: 30%">MAN NO<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$ps_idGET;?>">
                            <input type="text" id="MAN_ID"   required="required" name="MAN_ID" value="<?=($_SESSION[initiate_man_documents]!='')? $MAN_ID : automatic_number_generate("MAN","MAN_master","MAN_ID","create_date='".date('Y-m-d')."' and MAN_ID like 'MAN_ID%'"); ?>" class="form-control col-md-7 col-xs-12"  readonly >
                          </div>
                      </div>
</td>
</tr>


<tr>
<td><div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Delivary Challan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="delivary_challan" style="width: 50%" value="<?=$delivary_challan;?>" name="delivary_challan" placeholder="don't use ( /,&,',*)" class="form-control col-md-7 col-xs-12" required >

                        
                        <?php if($_SESSION[initiate_man_documents]){ ?>
                        <a href="dc_documents/<?=$_SESSION[m_id].'_'.'dc'.'.pdf';?>" target="_new" style="text-decoration:underline; color:blue">Challan Document View</a>
                        
                        <?php } else { ?>
                        <input type="file" placeholder="Delivery Challan" id="nam_date"   name="dChallanPDF"  class="form-control col-md-7 col-xs-12" ><?php } ?>
                        
                        </div></div></td>
<td><div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">VAT Challan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="VAT_challan" style="width: 50%" placeholder="don't use ( /,&,',*)"  value="<?=$VAT_challan;?>" name="VAT_challan" class="form-control col-md-7 col-xs-12"  >

                        <?php if($_SESSION[initiate_man_documents]){ ?>
                        <a href="vc_documents/<?=$_SESSION[m_id].'_'.'vc'.'.pdf';?>" target="_new" style="text-decoration:underline; color:blue">VAT Challan View</a>
                        
                        <?php } else { ?>
                        <input type="file" id="nam_date"    name="VatChallanPDF"  class="form-control col-md-7 col-xs-12" >
	            <?php } ?>
                        </div></div></td></tr>




<tr>
<td><div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Delivary Challan Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <input type="text" id="delivary_challan_Date"  value="<?php if($_SESSION[initiate_man_documents]){ echo date('m/d/Y' , strtotime($delivary_challan_Date));} else { echo ''; } ?>" name="delivary_challan_Date"  class="form-control col-md-7 col-xs-12" ></div></div></td>


<td><div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">VAT Challan Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="VAT_challan_Date"   value="<?php if($_SESSION[initiate_man_documents]){ echo date('m/d/Y' , strtotime($VAT_challan_Date));} else { echo ''; } ?>" name="VAT_challan_Date"  class="form-control col-md-7 col-xs-12"  >
                      </div></div></td></tr>




<tr>
<td><div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Remarks<span class="required">*</span></label>
<div class="col-md-6 col-sm-6 col-xs-12">
<input type="text" name="remarks" id="remarks" value="<?=$remarks?>" class="form-control col-md-7 col-xs-12">
</div></div> </td>
<td><div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">CMU / Depot<span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" style="width:100%; font-size: 11px; height: 30px" tabindex="-1" required="required" id="warehouse_id" name="warehouse_id">
                <option></option>
                <?php foreign_relation('warehouse', 'warehouse_id', 'CONCAT(warehouse_id," : ", warehouse_name)', $warehouse_id, 'use_type in (\'PL\',\'WH\')','order by warehouse_id'); ?>
            </select></div></div> </td>
</tr>


<tr>
<td>
 <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Vendor<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px; height: 30px" tabindex="-1" required="required"  name="vendor_code" id="vendor_code">
                                <option></option>
                                <?php foreign_relation('vendor', 'vendor_id', 'CONCAT(vendor_id," : ", vendor_name)', $vendor_code, '1','order by vendor_name'); ?>
                            </select></div></div>
</td>
<td>
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Address<span class="required">*</span></label>
<div class="col-md-6 col-sm-6 col-xs-12">
<textarea id="first-name"  style="width:100%; font-size: 11px"   name="vendoraddress" class="form-control col-md-7 col-xs-12" readonly><?=getSVALUE("vendor", "address", "where vendor_id='".$vendor_code."'");?></textarea></div></div>
</td></tr>
</table>


                    
                    <div class="form-group" style="margin-left:40%">
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_man_documents]){  ?>
               <button type="submit" name="modify" id="modify" class="btn btn-primary" style="font-size: 12px">Update MAN Documents</button>
			 <?php   } else {?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Initiate MAN Documents</button>
               <?php } ?>
               </div></div>   </form></div></div></div>
               
               
 <?php if($_SESSION[initiate_man_documents]){?>
<form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">

    <input type="hidden" name="m_id" id="m_id" value="<?=$_SESSION[m_id];?>">
    <input type="hidden" name="MAN_ID" id="MAN_ID" value="<?=$_SESSION[initiate_man_documents];?>">
    <input type="hidden" name="man_date" id="man_date" value="<?=$man_date;?>">
    <input type="hidden" name="vendor_code" id="vendor_code" value="<?=$vendor_code;?>">
    <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse_id;?>">
    <input type="hidden" name="delivary_challan" id="delivary_challan" value="<?=$delivary_challan;?>">
    <input type="hidden" name="delivary_challan_Date" id="delivary_challan_Date" value="<?=$delivary_challan_Date;?>">
    <input type="hidden" name="VAT_challan" id="VAT_challan" value="<?=$VAT_challan;?>">
    <input type="hidden" name="VAT_challan_Date" id="VAT_challan_Date" value="<?=$VAT_challan_Date;?>">
 <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
        <thead>
        <tr style="background-color: bisque">
            <th style="text-align: center">Material Details</th>
            <th style="text-align: center">PO No</th>
            <th style="text-align: center">Qty</th>
            <th style="text-align: center">MFG</th>
            <th style="text-align: center">No of pack</th>
            <th style="text-align: center"></th>
        </tr>
        </thead>



        <tbody>
                       <tr>
                      <td style="width:20%" align="center">
                          <select class="select2_single form-control" style="width: 100%" tabindex="-1" required="required" name="item_id" id="item_id" onchange="javascript:reload(this.form)">
                              <option></option>
                              <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g WHERE  i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id and 
							 g.group_id						 
							  order by i.item_name";
                              advance_foreign_relation($sql_item_id,$_GET[Searchitem_id]);?>
                          </select></td>
 
 
                        <td style="width:5%" align="center">
                         <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="po_no" id="po_no">
                         <option></option>
                      <?php foreign_relation('purchase_invoice', 'distinct po_no', 'po_no', $po_no, 'item_id='.$_GET[Searchitem_id].'','order by po_no'); ?>
                         </select>
                        </td>
                      

<td style="width:5%" align="center">
                        <input type="text" id="qty" style="width:99%; height:37px; font-weight:bold; text-align:center"  required="required"  name="qty" placeholder="Qty" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        

<td style="width:5%" align="center">
                        <input type="text" id="mfg" style="width:99%; height:37px;  font-weight:bold; text-align:center"    name="mfg" placeholder="MFG" class="form-control col-md-7 col-xs-12"  >
</td>
                    
                     
                        
                     <td align="center" style="width:8%"> 
                     <input type="text" id="no_of_pack" style="width:99%; height:37px; font-weight:bold; text-align:center"  required="required"  name="no_of_pack" placeholder="No of Pack" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                     </td>
                      
            <td align="center" style="width:5%">
            <button type="submit" class="btn btn-primary" style="font-size: 12px" name="add" id="add">Add</button></td></tr> </tbody>
                     </table> 
                 </form>

<form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
    <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">

                      <thead>
                        <tr style="background-color: bisque">
                        <th>SL</th>
                        <th style="width:5%; text-align:center">PO</th>
                          <th>Code</th>
                          <th>Product</th>   
                          <th style="width:5%; text-align:center">UOM</th>
                          <th style="width:10%; text-align:center">Qty</th>
                          <th style="width:10%; text-align:center">MFG</th>
                          <th style="width:10%; text-align:center">No of Pack</th>
                          <th style="width:15%; text-align:center">Options</th>
                        </tr>
                      </thead>
                      <tbody>

						
						 
				<?php
				while($row=mysqli_fetch_array($results)){
				$i=$i+1;
				$ids=$row[id];
				?>
                      <tr>
                        <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                        <td style="vertical-align:middle"><?=$row[po_no] ?></td>
                        <td style="width:8%; vertical-align:middle"><?=$row[finish_goods_code];?></td>
                         <td style="vertical-align:middle"><?=$row[item_name]; ?></td>
                        <td style="vertical-align:middle"><?=$row[unit_name]; ?></td>
                        <td align="center" style="width:6%; text-align:center"><?=$row[qty];?></td>
                        <td align="center" style="width:6%; text-align:center"><?=$row[mfg];?></td>
                        <td align="center" style="width:6%; text-align:center"><?=$row[no_of_pack];?></td>

                    
                  <td align="center" style="width:10%;vertical-align:middle">
                   <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete MAN?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                    </td></tr>

                        <?php 
						$qtytotal=$qtytotal+$row[qty];
						$noptotal=$noptotal+$row[no_of_pack];
				}
						 ?>

                        
                      
                      </tbody>

                      
                  
 <tr>

                      
                      <td colspan="5" style="font-weight:bold; font-size:14px" align="right">Total</td>           
                      <td align="center" ><strong><?php echo $qtytotal; ?></strong></td>
                      <td></td>
                      <td align="center" ><strong><?php echo $noptotal; ?></strong></td>
                      <td></td>
                      </tr>
    </table>


                        


    <button type="submit" style="float: left; margin-left: 1%" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to the Production Deleted?");' class="btn btn-danger">Delete MAN </button>
    <?php if($COUNT_details_data>0) { ?>
        <button type="submit" style="float: right; margin-right: 1%" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" id="confirmsave" class="btn btn-success">Confirm and Finish MAN </button>
    <?php } else { echo '';} ?>
                   
</form>
                  </div>

                </div>

              </div>
            
<?php } ?>               
              
   













              
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        
        <!-- /footer content -->
      </div>
    </div>

<?php require_once 'footer_content.php' ?>

   <script>
      $(document).ready(function() {
        $('#man_date').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4",
		  }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    
    
    <script>
      $(document).ready(function() {
        $('#delivary_challan_Date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    
    
    <script>
      $(document).ready(function() {
        $('#VAT_challan_Date').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
    
    
    <script>
      $(document).ready(function() {
        $('#mfg').daterangepicker({
			
          singleDatePicker: true,
          calender_style: "picker_4",
		  
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>

    <!-- Starrr -->
    <script>
      $(document).ready(function() {
        $(".stars").starrr();

        $('.stars-existing').starrr({
          rating: 4
        });

        $('.stars').on('starrr:change', function (e, value) {
          $('.stars-count').html(value);
        });

        $('.stars-existing').on('starrr:change', function (e, value) {
          $('.stars-count-existing').html(value);
        });
      });
	  
	  
	  
	  $('#rate').keyup(function(){
        var qtys;
        var rate;
        qtys = parseFloat($('#qtys').val());
        rate = parseFloat($('#rate').val());
		
        var amounta = qtys * rate;
        $('#amounta').val(amounta.toFixed(2));


    });
    </script>
    <!-- /Starrr -->
  </body>
</html>
