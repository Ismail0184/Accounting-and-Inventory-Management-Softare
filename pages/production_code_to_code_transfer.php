<?php require_once 'support_file.php'; ?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');


$title="Code to Code Transfer";
$dfrom=date('Y-1-1');
$dto=date('Y-m-d');
$now=time();

$table="code_to_code_master";
$unique = 'id';   // Primary Key of this Database table
$table_details = 'code_to_code_transfer';
$details_unique = 'id';
$table_journal_item='journal_item';
$page="production_code_to_code_transfer.php";
$crud      =new crud($table);
$targeturl="<meta http-equiv='refresh' content='0;$page'>";


if(prevent_multi_submit()) {
    if (isset($_POST['initiate'])) {
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST['status'] = 'MANUAL';
        $_SESSION['initiate_production_ctc_transfer'] = $_POST[$unique];
        $crud->insert();
        $type = 1;
        $msg = 'New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }

    if (isset($_POST['add'])) {
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST['status'] = 'MANUAL';
        $_POST['amount'] = $_POST['ctct_qty']*$_POST['cct_rate'];
        $_POST[oi_no] = $_SESSION['initiate_production_ctc_transfer'];
        $crud = new crud($table_details);
        $crud->insert();
        $type = 1;
        $msg = 'New Entry Successfully Inserted.';
        unset($_POST);
        unset($$unique);
    }


//for modify..................................
    if (isset($_POST['modify'])) {
        $sd = $_POST[or_date];
        $_POST[or_date] = date('Y-m-d', strtotime($sd));
        $_POST['edit_at'] = time();
        $_POST['edit_by'] = $_SESSION['userid'];
        $crud->update($unique);
        $type = 1;

    }


}// prevent multi submit


//for Delete..................................
if(isset($_POST['deleted']))
{
    $crud = new crud($table_details);
    $condition =$details_unique."=".$_SESSION['initiate_production_ctc_transfer'];
    $crud->delete_all($condition);
    $crud = new crud($table);
    $condition=$unique."=".$_SESSION['initiate_production_ctc_transfer'];
    $crud->delete($condition);
    unset($_SESSION['initiate_production_ctc_transfer']);
}


if(isset($_POST['confirmsave']))
{
    $up_master=mysqli_query($conn,"UPDATE ".$table." SET status='UNCHECKED' where ".$unique."='$_SESSION[initiate_production_ctc_transfer]'");
    $up_details=mysqli_query($conn,"UPDATE ".$table_details." SET status='UNCHECKED' where ".$unique."='$_SESSION[initiate_production_ctc_transfer]'");
	
	
    $maild = find_a_field('essential_info', 'ESS_CORPORATE_EMAIL', 'PBI_ID=' . $_POST[checked_by]);
		
	 if ($maild != '') {
            $to = $maild;
				$subject = "A CTC Transfer Created!!";
				$message .= '<table border="0" cellpadding="0" cellspacing="0" width="100%"> 
    <tr>
        <td  align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table">                
                <tr>
                    <td align="center" valign="top" style="padding: 40px 0px 40px 0px;"><img alt="Example" src="http://icpbd-erp.com/51816/cmu_mod/icon/title.png" width="100" style="display: block;" border="0" class="responsive-image">
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="padding: 0px 10px 20px 10px;">
					<p style="font-family: sans-serif; font-size: 24px; font-weight: bold; line-height: 28px; margin: 0;">A Code to code transfer has been created!!</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="padding: 0px 10px 10px 10px;">
                        <p style="font-family: sans-serif; font-size: 13px; font-weight: normal; line-height: 24px; margin: 0;">A Code to Code transfer has been created. Your approval is required. CTC No is: <b>'.$_SESSION[initiate_production_ctc_transfer].'</b>.</p>
                    </td>
                </tr> 
            </table>
        </td>
    </tr>
	<tr>
	<td>
	<table align="center" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;font-family: sans-serif; font-size: 11px; padding-top:-200px" width="80%" class="responsive-table">                
                <tr bgcolor="#00a9f7">
                    <th align="center" valign="top">#</th>
					<th align="center" valign="top">Prepared By</th>
                </tr>
                <tr>
                    <th align="center" valign="top">Prepared By</th>
					<td style="padding-left:5px">'.find_a_field('user_activity_management','fname','user_id='.$_SESSION[userid]).'</td>
                </tr>
                <tr>
                    <th align="center" valign="top">Time </th>
					<td style="padding-left:5px">'.$_POST[entry_at].'</td>
                </tr>
				<tr>
                    <th align="center" valign="top">Remarks </th>
					<td style="padding-left:5px">'.$_POST[remarks].'</td>
                </tr> 
            </table></td>
	</tr>
</table>';
$message .= '<p align="center" valign="top" style="padding: 0px 10px 100px 10px;font-style:italic; font-size:10px">This EMAIL is automatically generated by ERP Software.</i>';
$from = 'erp@icpbd.com';
$headers = "";
$headers .= "From: ERP Software<erp@".$_SERVER['SERVER_NAME']."> \r\n";
$headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
$headers .= 'Cc:' . "\r\n";      
mail($to,$subject,$message,$headers); 
        }
	
	
	
	
    unset($_SESSION['initiate_production_ctc_transfer']);
    unset($_POST);
} // if insert posting

//for single FG Delete..................................
 $results="Select srd.*,i.*,
	(select concat(item_id,' : ',item_name) from item_info where item_id=srd.transfer_to_item) as transfer_to_item
	from ".$table_details." srd, item_info i  where
    srd.transfer_from_item=i.item_id and 
    srd.".$unique."='".$_SESSION[initiate_production_ctc_transfer]."' order by srd.id";
$query = mysqli_query($conn, $results);
while ($row = mysqli_fetch_array($query)) {
    $ids = $row[ctct_id];
    if (isset($_POST['deletedata' . $ids])) {
        $del = "DELETE FROM " . $table_details . " WHERE ctct_id='$ids' and " . $unique . "=" . $_SESSION['initiate_production_ctc_transfer'] . "";
        $del_item = mysqli_query($conn, $del);
        unset($_POST);
    }
} // end of single item deleted..

$COUNT_details_data=find_a_field(''.$table_details.'','Count(id)',''.$unique.'='.$_SESSION['initiate_production_ctc_transfer'].'');


// data query..................................
if(isset($_SESSION[initiate_production_ctc_transfer]))
{   $condition=$unique."=".$_SESSION[initiate_production_ctc_transfer];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}

    $results="Select srd.*,i.*,
	(select concat(item_id,' : ',item_name) from item_info where item_id=srd.transfer_to_item) as transfer_to_item
	from ".$table_details." srd, item_info i  where
    srd.transfer_from_item=i.item_id and 
    srd.".$unique."='".$_SESSION[initiate_production_ctc_transfer]."' order by srd.id desc";
    $query=mysqli_query($conn, $results);
}


							  
$sql_checked_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";	
 $sql_authorise_by="SELECT  p.PBI_ID,concat(p.PBI_ID_UNIQUE,' : ',p.PBI_NAME,' : ',d.DEPT_SHORT_NAME) FROM 
							 
							personnel_basic_info p,
							department d
							 where 
							 p.PBI_JOB_STATUS in ('In Service') and 							 
							 p.PBI_DEPARTMENT=d.DEPT_ID					 
							  order by p.PBI_NAME";	
							  
							  
$item_info=find_all_field('item_info','','item_id='.$_GET[transfer_from_item].'');							  
?>

<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.transfer_from_item.options[form.transfer_from_item.options.selectedIndex].value;
	self.location='<?=$page;?>?transfer_from_item=' + val ;
}
</script><style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php require_once 'body_content_nva_sm.php'; ?>
<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                                        <a target="_new" class="btn btn-sm btn-default"  href="production_code_to_code_transfer_view.php">
                                            <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">View List</span>
                                        </a>
                                </ul>
            <div class="clearfix"></div>
        </div>


        <div class="x_content">
            <form action="" enctype="multipart/form-data" style="font-size: 11px" method="post" name="addem" id="addem" >
                <? //require_once 'support_html.php';?>
                <table style="width:100%"  cellpadding="0" cellspacing="0">
                    <tr>
                    <th style="width:8%">Code</th>
                    <th style="width:1%; text-align:center">:</th>
                        <td style="width:20%;">
                                    <input type="text" id="<?=$unique?>"   required="required" name="<?=$unique?>" value="<?
                                    $idGETs=find_a_field($table,'max('.$unique.')','1');
                                    if($_SESSION['initiate_production_ctc_transfer']>0) {
                                        $idGET=$_SESSION['initiate_production_ctc_transfer'];
                                    } else
                                    {
                                        $idGET=$idGETs+1;
                                        if($idGETs<1) $idGET = 1;
                                    }
                                    echo $idGET; ?>" class="form-control col-md-7 col-xs-12"  readonly style="width:80%"></td>
                                 
                                 
                                    
                      <th style="width:12%">Date</th>  
                      <th style="width:1%; text-align:center">:</th>
                      <td style="width:20%">
                      <input type="date"   required="required" name="ctct_date" max="<?=date('Y-m-d');?>" value="<?=$ctct_date;?>" class="form-control col-md-7 col-xs-12" style="width:80%; font-size: 11px" >
                      </td>
                      
                      
                      
                      <th style="width:10%">Remarks</th>  
                      <th style="width:1%; text-align:center">:</th>
                      <td style="width:20%">
                      <input type="text" name="remarks" id="remarks" value="<?=$remarks;?>"  class="form-control col-md-7 col-xs-12" style="width: 80%;">
                      </td>
                      </tr>
                                 
                             
    
                    
              <tr><td style=" height:5px"></td></tr>                    
              <tr>
              <th>Checked By</th>
              <th style="width:1%; text-align:center">:</th>
              <td>
                      <select class="select2_single form-control" style="width: 80%;" tabindex="-1" required="required" name="checked_by" id="checked_by">
                          <option></option>
                          <? advance_foreign_relation($sql_checked_by,$checked_by);?>
                  </select>
              </td>



              

              <th>Authorised By</th>
              <th style="width:1%; text-align:center">:</th>
              <td><select class="select2_single form-control" style="width: 80%;" tabindex="-1" required="required" name="verify_by" id="verify_by">
                      <option></option>
                      <? advance_foreign_relation($sql_authorise_by,$verify_by);?>
                  </select>
                 </td>
                 
                 
                 <th>Warehouse</th>  
                      <th style="text-align:center">:</th>
                        <td>
                                    <select class="select2_single form-control"  required style="width: 80%;" name="warehouse_id" id="warehouse_id"><option></option>
                                    <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_id);?>                                    </select></td>
          </tr>
                    
                    </table>




                <div class="form-group" style="margin-left:40%; margin-top: 15px">

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if($_SESSION[initiate_production_ctc_transfer]){  ?>
                            <button type="submit" name="modify" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Update?");' style="font-size: 11px">Update <?=$title;?></button>

                        <?php   } else {?>
                            <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 11px">Initiate <?=$title;?></button>
                        <?php } ?>
                    </div></div>
            </form></div></div></div>











<?php if($_SESSION[initiate_production_ctc_transfer]){  ?>

    <form  name="addem" id="addem" action="<?=$page;?>" class="form-horizontal form-label-left" method="post">
        <? require_once 'support_html.php';?>
        <input type="hidden" id="warehouse_from" name="warehouse_from" value="<?=$warehouse_id;?>" >
        <input type="hidden" id="warehouse_to" name="warehouse_to" value="<?=$warehouse_id;?>" >
        <input type="hidden" id="ctct_date" name="ctct_date" value="<?=$ctct_date;?>" >
        <input type="hidden" id="<?=$unique;?>" name="<?=$unique;?>" value="<?=$_SESSION[initiate_production_ctc_transfer];?>">
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <tr style="background-color: bisque">
                <th style="text-align: center; vertical-align:middle">Transfer From Code</th>
                <th style="text-align: center; vertical-align:middle">Current Stock</th>
                <th style="text-align: center; vertical-align:middle">Transfer Qty</th>
                <th style="text-align: center; vertical-align:middle">Transfer to Code</th>
                <th style="text-align: center; vertical-align:middle">Transfer to Qty</th>
                <th style="text-align: center; vertical-align:middle">Unit Price</th>
                <th style="text-align: center; vertical-align:middle"></th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td style="width:25%; background-color:#CCC" align="center">
                    <select class="select2_single form-control" required name="transfer_from_item" id="transfer_from_item" style="width:100%;font-size: 11px" onchange="javascript:reload(this.form)">       
                    <option></option>
                        <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name) FROM  item_info i,
							item_sub_group sg,
							item_group g
							
							WHERE  
							i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id   order by i.item_name";
                        advance_foreign_relation($sql_item_id,$_GET[transfer_from_item]);?>
                    </select>
                </td>
                <td style="width:13%; background-color:#CCC" align="center">
                    <input type="text" id="current_stock" style="width:60%; height:37px; font-weight:bold; text-align:center" value="<?=find_a_field('journal_item','SUM(item_in-item_ex)','item_id='.$_GET[transfer_from_item].'');?>"   name="total_unit" readonly class="form-control col-md-7 col-xs-12" autocomplete="off" >
                     
                     <input type="text" id="unit_name" style="width:40%; height:37px; font-weight:bold; text-align:center" value="<?=$item_info->unit_name;?>" name="unit_name" readonly class="form-control col-md-7 col-xs-12" autocomplete="off" >
                </td>            

                <td align="center" style="width:10%; background-color:#CCC">
                    <input type="text" id="t_qty" onkeyup="doAlert(this.form);" style="width:100%; height:37px; font-weight:bold; text-align:center"  name="t_qty" required  class="form-control col-md-7 col-xs-12"  autocomplete="off" >
                </td>
                                 
                <SCRIPT language=JavaScript>
            function doAlert1(form)
            {
                var val=form.t_qty.value;
                var val2=form.current_stock.value;
                if (Number(val)>Number(val2)){
                    alert('oops!! Exceed Current Stock!! Thanks');
                    form.t_qty.value='';
                }
                form.t_qty.focus();
            }</script>

                <td style="width:25%" align="center">
                   <select class="select2_single form-control" required name="transfer_to_item" id="transfer_to_item" style="width:100%;font-size: 11px">       
                    <option></option>
                        <? $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name) FROM  item_info i,
							item_sub_group sg,
							item_group g
							
							WHERE  
							i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id  order by i.item_name";
                        advance_foreign_relation($sql_item_id,$transfer_to_item);?>
                    </select>
                </td>

                <td style="width:12%" align="center">
                    <input align="center" type="text" id="ctct_qty" style="width:100%; height:37px;text-align:center"  required   name="ctct_qty" class="form-control col-md-7 col-xs-12" >
                </td>

                <td style="width:10%" align="center">
                    <input type="text" id="cct_rate" style="width:100%; height:37px; font-weight:bold; text-align:center" required  name="cct_rate" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>


                <td align="center" style="width:5%">
                    <button type="submit" class="btn btn-primary" name="add" id="add" style="font-size: 12px">Add</button></td></tr>
            </tbody>
        </table>
    </form>




    <!-----------------------Data Save Confirm ------------------------------------------------------------------------->


    <form id="ismail" name="ismail"  method="post" action="<?=$page;?>" style="font-size: 11px"  class="form-horizontal form-label-left">
        <? require_once 'support_html.php';?>
        <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$$unique;?>" >
        <input type="hidden" name="entry_at" id="entry_at" value="<?=$entry_at;?>" >
        <input type="hidden" name="pr_date" id="pr_date" value="<?=$pr_date;?>">
        <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
        <input type="hidden" name="checked_by" id="checked_by" value="<?=$checked_by;?>">
        <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
        <input type="hidden" name="remarks" id="remarks" value="<?=$remarks;?>">
        <?php if($COUNT_details_data>0) { ?>
            <table align="center" class="table table-striped table-bordered" style="width:98%">
                <thead>
                <tr style="background-color: bisque">
                    <th>SL</th>
                    <th>Transfer From Code</th>
                    <th style="width:5%; text-align:center">UOM</th>
                    <th style="text-align:center;  width:10%">Transfer Qty</th>
                    <th style="text-align:center">Transfer to Code</th>
                    <th style="text-align:center; width:10%">Transfer to Qty</th>
                    <th style="text-align:center; width:10%">Unit Price</th>
                    <th style="text-align:center; width:10%">Amount</th>
                    <th style="text-align:center; width:5%">#</th>
                </tr>
                </thead>
                <tbody>


                <?php

                while($row=mysqli_fetch_array($query)){
                    $i=$i+1;
                    $ids=$row[ctct_id];
                    ?>
                    <tr>
                        <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                        <td style="vertical-align:middle; width: 25%"><?=$row[finish_goods_code];?> : <?=$row[item_name];?></td>
                        <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                        <td align="center" style=" text-align:center;vertical-align:middle;"><?=$row[t_qty];?></td>
                        <td align="center" style=" text-align:left;vertical-align:middle;"><?=$row[transfer_to_item];?></td>
                        <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[ctct_qty];?></td>
                        <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[cct_rate]; ?></td>
                        <td align="center" style=" text-align:right;vertical-align:middle;"><?=$row[amount]; ?></td>
                        <td align="center" style="vertical-align:middle">
                            <button type="submit" name="deletedata<?=$ids;?>" id="deletedata<?=$ids;?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                        </td>
                    </tr>
                    <?php  $ttotal_unit=$ttotal_unit+$row[total_unit];
                    $tfree_qty=$tfree_qty+$row[free_qty];
                    $ttotal_qty=$ttotal_qty+$row[total_qty];
                    $tdiscount=$tdiscount+$row[discount];
                    $ttotal_amt=$ttotal_amt+$row[total_amt];  } ?>
                </tbody>                
            </table>
        <?php } ?>

        <button type="submit" style="float: left; margin-left: 1%; font-size: 12px" name="deleted" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to the Purchase Deleted?");' class="btn btn-danger">Delete <?=$title;?></button>
        <?php if($COUNT_details_data>0) { ?>
            <button type="submit" style="float: right; margin-right: 1%; font-size: 12px" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Finish <?=$title;?></button>
        <?php } else { echo '';} ?>


    </form>
    <br>
<?php } ?>
<?=$html->footer_content();mysqli_close($conn);?>
