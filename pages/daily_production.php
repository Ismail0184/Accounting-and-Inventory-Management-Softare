<?php
require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title='Production Entry';
$now=time();
$unique='pr_no';
$unique_PI='pi_no';
$unique_field='name';
$table="production_floor_receive_master";
$table_deatils="production_floor_receive_detail";

$production_table_issue_master="production_floor_issue_master";
$production_table_issue_detail="production_floor_issue_detail";
$journal_item="journal_item";


$page="CMU_FG_Production_Setup.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";
$create_date=date('Y-m-d');



if(prevent_multi_submit()){
    if(isset($_POST['initiate']))
    {   $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $billno=$_POST[billno];
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_SESSION[initiate_daily_production]=$_POST[custom_pr_no];
        $_SESSION['ps_id'] =$_POST[$unique];
        $_POST[warehouse_to]=$_POST[warehouse_from];
        $_SESSION['production_warehouse'] =$_POST[warehouse_to];
        $_POST[create_date]=$create_date;
        $crud->insert();
        $_POST['entry_by'] = $_SESSION['userid'];
        $_POST['entry_at'] = date('Y-m-d H:s:i');
        $_POST['section_id'] = $_SESSION['sectionid'];
        $_POST['company_id'] = $_SESSION['companyid'];
        $d =$_POST[pr_date];
        $_POST[pi_date]=date('Y-m-d' , strtotime($d));
        $_SESSION['pi_id'] =$_POST[pi_no];
        $_POST[production_for_pr_no]=$_POST[custom_pr_no];
        $crud      =new crud($production_table_issue_master);
        $crud->insert();
        $type=1;
        unset($_POST);
        unset($$unique);
    }

//for modify PS information ...........................
    if(isset($_POST['modify']))
    {
        $d =$_POST[pr_date];
        $_POST[pr_date]=date('Y-m-d' , strtotime($d));
        $_POST['edit_at']=time();
        $_POST['edit_by']=$_SESSION['userid'];
        $crud->update($unique);
        $type=1;
        unset($_POST);
    }


//for single FG Add...........................
    if(isset($_POST['add']))
    {  if($_POST['qtys']>0) {
            $packsize=getSVALUE("item_info", "pack_size", " where item_id='$_POST[item_id]'");
            $_POST[total_unit]=($_POST[qtys]*$packsize);
            $m =$_POST[mfg];
            $_POST[status]="UNCHECKED";
            $_POST[mfg_year]=date('Y' , strtotime($m));
            $_POST[mfg_month]=date('m' , strtotime($m));
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['entry_at'] = date('Y-m-d H:s:i');
            $_POST['p_type'] = "Production";
            $crud = new crud($table_deatils);
            $crud->insert();
        }}



//for single FG Delete..................................
    $query="Select * from production_floor_receive_detail where pr_no='$_SESSION[ps_id]'";
    $res=mysqli_query($conn, $query);
    while($row=mysqli_fetch_array($res)){
        $ids=$row[id];
        if(isset($_POST['deletedata'.$ids]))
        {
            $del="DELETE FROM production_floor_receive_detail WHERE id='$ids' and custom_pr_no='$_SESSION[initiate_daily_production]'";
            $del_item=mysqli_query($conn, $del);
            unset($_POST);
        }}

//for Delete..................................
    if(isset($_POST['cancel']))
    {   $crud = new crud($table_deatils);
        $condition =$unique."=".$_SESSION['ps_id'];
        $crud->delete_all($condition);
        $crud = new crud($table);
        $condition=$unique."=".$_SESSION['ps_id'];
        $crud->delete($condition);

        $crud = new crud($production_table_issue_master);
        $condition=$unique_PI."=".$_SESSION['pi_id'];
        $crud->delete($condition);


        unset($_SESSION['ps_id']);
        unset($_SESSION['pi_id']);
        unset($_SESSION['initiate_daily_production']);
        unset($_POST);
    }

    $COUNT_details_data=find_a_field(''.$table_deatils.'','Count(id)',''.$unique.'='.$_SESSION['ps_id'].'');
//for confirm the Production ..................................






if(isset($_POST['confirmsave']))
{
    $results="Select * from production_floor_receive_detail where pr_no='$_SESSION[ps_id]'";
    $query=mysqli_query($conn, $results);
    while($row=mysqli_fetch_array($query)){

        $rowformula="Select * from production_ingredient_detail where item_id='$row[item_id]' and line_id='$row[warehouse_to]' and status>0";
        $res_formula=mysqli_query($conn, $rowformula);
        while($fgrows=mysqli_fetch_array($res_formula)){
            $packsizefind=$totalqty*$fgid[pack_size];

            // material consumption qty calculation
            $issuerow=($row[total_unit]*$fgrows[unit_batch_qty])/$fgrows[unit_batch_size];
            $issuerowSINGLE=($fgrows[row_pack_size]*$fgrows[unit_batch_qty])/$fgrows[unit_batch_size];

$rowSQL = "SELECT distinct(landad_cost) AS LargePrice FROM `item_landad_cost` where status='Active' and item_id='$fgrows[raw_item_id]'";
$resSQL=mysqli_query($conn, $rowSQL);
$rowLargePrice = mysqli_fetch_array( $resSQL );

 $comprice = $rowLargePrice['LargePrice'];
	$conamount=$issuerow*$comprice;
	$conamounttotal=$conamounttotal+$conamount;

	///SINGLE
	$conamountSINGLE=$issuerowSINGLE*$comprice;
	$conamounttotalSINGLE=$conamounttotalSINGLE+$conamountSINGLE;

if($fgrows[type]=='-'){

    $_POST['section_id'] = $_SESSION['sectionid'];
    $_POST['company_id'] = $_SESSION['companyid'];
    $_POST['entry_by'] = $_SESSION['userid'];
    $_POST['entry_at'] = date('Y-m-d H:s:i');
    $_POST[pr_no]      = $_POST[pr_no];
    $_POST[pi_no]      = $_SESSION['pi_id'];
    $_POST[pi_date]      = $_POST[pr_date];

    $_POST['item_id']  = $fgrows[raw_item_id];
    $_POST[total_unit] = $issuerow;
    $_POST[unit_price] = $comprice;
    $_POST[total_amt]  = $conamount;
    $_POST[status]     = 'UNCHECKED';
    $_POST[fg_id]      = $row[item_id];
    $_POST[batch_for]      = $row[batch];
    $_POST[BOM]      = $fgrows[unit_batch_qty];
    $_POST[fg_unit_qty]=  $row[total_unit];
    $crud = new crud($production_table_issue_detail);
    $crud->insert();


    $_POST['section_id'] = $_SESSION['sectionid'];
    $_POST['company_id'] = $_SESSION['companyid'];
    $_POST[ji_date] = $_POST[pr_date];
    $_POST['entry_by'] = $_SESSION['userid'];
    $_POST['entry_at'] = date('Y-m-d H:s:i');
    $_POST['item_id']  = $fgrows[raw_item_id];
    $_POST[sr_no]      = $_SESSION['ps_id'];
    $_POST[item_ex]    = $issuerow;
    $_POST[item_price] = $comprice;
    $_POST[total_amt]  = $conamount;
    $_POST[tr_from]    = 'Consumption';
    $_POST[tr_no]      = $_SESSION['pi_id'];

    $_POST[consumption_for_fg]      = $row[item_id];
    $_POST[batch]      = $row[batch];
    $_POST[custom_no]  = $_SESSION[initiate_daily_production];
    $_POST[warehouse_id] = $_POST[warehouse_from];
    $crud = new crud($journal_item);
    $crud->insert();
}


if($fgrows[type]=='+'){

    $_POST[item_id] = $fgrows[raw_item_id];
    $_POST[lot] = $row[lot];
    $packsize=getSVALUE("item_info", "pack_size", " where item_id='$_POST[item_id]'");
    $_POST[total_unit]=$issuerow;
    $_POST[mfg]=$row[mfg];
    $_POST[mfg_year]=$row[mfg_year];
    $_POST[mfg_month]=$row[mfg_month];
    $_POST['entry_by'] = $_SESSION['userid'];
    $_POST['entry_at'] = date('Y-m-d H:s:i');
    $_POST['p_type'] = "Gain";
    $crud = new crud($table_deatils);
    $crud->insert();

}








$fgrate=$fgrows[unit_batch_qty]*$comprice;
$fgratetotal=number_format(($fgratetotal+$fgrate),2);
/// end of row material consumption
        } // production_ingredient_detail
$pks=getSVALUE("item_info", "pack_size", " where item_id='$row[item_id]'");
//$tmt=($row[total_unit]/$pks)*$fgratetotal;
$tmt=($row[total_unit])*$fgratetotal;

    }  // fg query result

    $up="UPDATE ".$table." SET status='UNCHECKED' where ".$unique."='$_SESSION[ps_id]'";
    $update_table_master=mysqli_query($conn, $up);

    $up2="UPDATE ".$production_table_issue_master." SET status='UNCHECKED' where ".$unique."='$_SESSION[ps_id]'";
    $update_production_floor_issue_master=mysqli_query($conn, $up2);

    $cmuledgerRM=getSVALUE("warehouse", "ledger_id_RM", " where warehouse_id='".$_SESSION[production_warehouse]."'");
    $cmuledgerFG=getSVALUE("warehouse", "ledger_id_FG", " where warehouse_id='".$_SESSION[production_warehouse]."'");
    $narrationFG='Finish Good Production, PS NO# '.$_SESSION[initiate_daily_production].', '.$_SESSION[ps_id].'.';
    $narrationMaterial='Material Consumption, PS NO# '.$_SESSION[initiate_daily_production].', PI#'.$_SESSION[pi_id].'.';
    $productiondate=date('Y-m-d');
    $date=date('d-m-y' , strtotime($productiondate));
    $j=0;
    for($i=0;$i<strlen($date);$i++)
    {
        if(is_numeric($date[$i]))
        { $time[$j]=$time[$j].$date[$i];
        } else {
            $j++; } }
    $date=mktime(0,0,0,$time[1],$time[0],$time[2]);
    $jv=next_journal_voucher_id();
    add_to_journal_new($productiondate,$crowjr[proj_id], $jv, $date, $cmuledgerFG, $narrationFG, $conamounttotal, 0,Production, $_SESSION[ps_id],$_SESSION[pi_id],0,0,$_SESSION[usergroup],$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear);
    add_to_journal_new($productiondate,$crowjr[proj_id], $jv, $date, $cmuledgerRM, $narrationMaterial, 0, $conamounttotal,Consumption, $_SESSION[ps_id],$_SESSION[pi_id],0,0,$_SESSION[usergroup],$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear);

    unset($_SESSION['ps_id']);
    unset($_SESSION['pi_id']);
    unset($_SESSION['initiate_daily_production']);
    unset($_POST);
} // if insert posting





}

// data query..................................
if(isset($_SESSION['ps_id']))
{   $condition=$unique."=".$_SESSION['ps_id'];
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
  <SCRIPT language=JavaScript>
function reload(form)
{   var val=form.productcode.options[form.productcode.options.selectedIndex].value;
	self.location='purchase.php?productcodeget=' + val ;}
</script>
<style>
    input[type=text]{
        font-size: 11px;
    }
</style>
<?php require_once 'body_content.php'; ?>


<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size: 11px">
<table style="width:100%">
<tr>
<th style="width:5%">PS No</th>
<th style="width:1%; text-align:center">:</th>
<td>
                            <?
                            $ps_ids=find_a_field(''.$table.'','max('.$unique.')','1');
                            if($_SESSION['ps_id']>0) {
                                $ps_idGET = $_SESSION['ps_id'];
                            } else {
                                $ps_idGET=$ps_ids+1;
                                if($ps_ids<1) $ps_idGET = 1;
                            }

                            $pi_nos=find_a_field(''.$production_table_issue_master.'','max(pi_no)','1');
                            if($_SESSION['pi_no']>0) {
                                $pi_noGET = $_SESSION['pi_no'];
                            } else {
                                $pi_noGET=$ps_ids+1;
                                if($pi_nos<1) $pi_noGET = 1;
                            }
                            ?>
                            <input type="text" class="form-control col-md-7 col-xs-12"  readonly style="width:50px; font-size:11px" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$ps_idGET;?>">
                            <input type="hidden" name="pi_no" id="pi_no" value="<?=$pi_noGET;?>">
                <input type="text" id="custom_pr_no"   required="required" name="custom_pr_no" style="width:125px" value="<?=($_SESSION['initiate_daily_production']!='')? $_SESSION['initiate_daily_production'] : ps_no(); ?>" class="form-control col-md-7 col-xs-12" >
                          </td> 

<th style="width:">Date</th>
<th style="width:1%; text-align:center">:</th>
<td>              
	               <input type="date" id="pr_date" style="font-size:11px; width:90%" MAX="<?=date('Y-m-d');?>" required="required" name="pr_date" value="<?=$pr_date;?>" class="form-control col-md-7 col-xs-12" ></td>
                    
                    
<th style="width:">Warehouse</th>
<th style="width:1%; text-align:center">:</th> 
            <td>
                        <select  class="form-control" style="width: 200px;font-size:11px" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                        <option selected></option>
                        <?=advance_foreign_relation(check_plant_permission($_SESSION[userid]),$warehouse_from);?></select>
             </td>

<th style="width:">Remarks</th>
<th style="width:1%; text-align:center">:</th>
<td>
	            <input type="text" id="remarks" style="width:90%" name="remarks" value="<?=$remarks;?>" class="form-control col-md-7 col-xs-12" >
</td></tr>
</table>
                        
                       
      <p align="center" style=" margin-top:18px">
               <?php if($_SESSION[initiate_daily_production]){  ?>
               <button type="submit" name="modify" id="" style="font-size:11px" onclick='return window.confirm("Are you confirm to Updated?");' class="btn btn-primary">Modify PS Info</button>
			 <?php   } else {?>
               <button type="submit" name="initiate" style="font-size:11px" onclick='return window.confirm("Are you confirm to Initiated?");' class="btn btn-primary">Initiate PS Entry</button>
               <?php } ?></p>
               
                          
               
               
               </form></div></div></div>




<?php if($_SESSION[initiate_daily_production]){ ?>





<form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
    <? require_once 'support_html.php';?>
    <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$$unique;?>" >
    <input type="hidden" name="pi_no" id="pi_no" value="<?=$_SESSION[pi_no];?>" >
    <input type="hidden" name="custom_pr_no" id="custom_pr_no" value="<?=$custom_pr_no;?>" >
    <input type="hidden" name="pr_date" id="pr_date" value="<?=$pr_date;?>">
    <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
    <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">

    <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
<thead>
<tr style="background-color: bisque">
<th style="text-align: center">Production FG</th>
<th style="text-align: center">Batch No</th>
<th style="text-align: center">Lot No</th>
<th style="text-align: center">MFG</th>
<th style="text-align: center">Production Qty</th>
<th style="text-align: center"></th>
</tr>
</thead>



                      <tbody>
                       <tr>
                        
                      <td style="width:25%">
                      <select class="select2_single form-control" name="item_id" id="item_id" style="width:100%;font-size: 11px">
                              <option></option>
                                      <?php
                                      $resitem="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name),pif.* FROM  item_info i , production_ingredient_detail pif WHERE i.item_id=pif.item_id and pif.line_id=".$warehouse_from." group by i.item_id order by i.item_id ";
									  advance_foreign_relation($resitem,$item_id); ?>
                          </select>
                       </td>
 
 
<td style="width:8%" align="center">
                      <?php
$bt=getSVALUE("warehouse", "nick_name", " where warehouse_id='$warehouse_from'"). date('y');
$key=$bt;
// *********************************************************  Create bathch no
function batchcreate(){

$sql="Select distinct batch from production_floor_receive_detail where warehouse_from='$inirow[warehouse_to]' and   batch like '$key%'  ORDER BY batch DESC LIMIT 1";
$result=mysql_query($sql);
		if (mysql_num_rows($result) == 0){
            $vnos="001";
           $_SESSION['batch']= $vnos; //echo $_SESSION['vno']  .  $idates;
            } else {
              while($row = mysql_fetch_array($result)) { 
                 $sl= substr($row['batch'],-3);
                   $sl=$sl+1;
				   if (strlen($sl)==1) {
					   $sl="00".$sl;
					   } else if (strlen($sl)==2){
						   $sl="0".$sl;
						    }
           $_SESSION['batch']=$sl;
           }}}batchcreate(); ?>
                         <input type="text" id="batch" style="width:100%; height:37px; font-weight:bold; text-align:center"  required="required"  name="batch" placeholder="Batchs" class="form-control col-md-7 col-xs-12" value="<?=$key;?><?php echo $_SESSION['batch']; ?>"  autocomplete="off" >
                        </td>              

                     <td align="center" style="width:8%"> 
                     <input type="text" id="no_of_pack" style="width:100%; height:37px; font-weight:bold; text-align:center" readonly value="<?=automatic_number_generate("","journal_item","lot_number","ji_date='".date('Y-m-d')."'");?>" name="lot" placeholder="Lot" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                     </td>
<td style="width:8%" align="center">
                        <input align="center" type="date" id="mfg"  style="width:100%; height:37px; font-size:11px;   text-align:center"    name="mfg" placeholder="MFG"  class="form-control col-md-7 col-xs-12"  >
</td>

<td style="width:5%" align="center">
                        <input type="text" id="qtys" style="width:100%; height:37px; font-weight:bold; text-align:center"  required="required"  name="qtys"  onkeyup="calc(this)" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
<td align="center" style="width:5%">
            <button type="submit" style="font-size:12px" class="btn btn-primary" name="add" id="add">Add</button></td></tr>
                      </tbody>
                     </table> 
</form>








<form id="ismail" name="ismail"  method="post" style="font-size: 11px"  class="form-horizontal form-label-left">
    <? require_once 'support_html.php';?>
    <input type="hidden" name="<?=$unique;?>" id="<?=$unique;?>" value="<?=$$unique;?>" >
    <input type="hidden" name="custom_pr_no" id="custom_pr_no" value="<?=$custom_pr_no;?>" >
    <input type="hidden" name="pr_date" id="pr_date" value="<?=$pr_date;?>">
    <input type="hidden" name="warehouse_from" id="warehouse_from" value="<?=$warehouse_from;?>">
    <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$warehouse_to;?>">
    <input type="hidden" name="lot" id="lot" value="<?=$lot;?>">
                     <table align="center" class="table table-striped table-bordered" style="width:98%">
                      <thead>
                        <tr style="background-color: bisque">
                        <th>SL</th>
                          <th>Code</th>
                          <th>Product</th>   
                          <th style="width:5%; text-align:center">UOM</th>
                         <th style="width:10%; text-align:center">Batch</th>
                         <th style="width:10%; text-align:center">Lot</th>
                         <th style="width:10%; text-align:center">MFG</th>
                         <th style="width:10%; text-align:center">Qty</th>
                         <th style="width:15%; text-align:center">Options</th>
                        </tr>
                      </thead>
                      <tbody>
						
						 
				<?php

				$results="Select pfrd.*,i.* from production_floor_receive_detail pfrd, item_info i  where
 pfrd.item_id=i.item_id and 
 pfrd.pr_no='$_SESSION[ps_id]'";
				$query=mysqli_query($conn, $results);
				while($row=mysqli_fetch_array($query)){
				$i=$i+1;
				$ids=$row[id];

				?>

                      <tr>
                        <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                        <td style="width:8%; vertical-align:middle"><?=$row[finish_goods_code];?></td>
                        <td style="vertical-align:middle"><?=$row[item_name];?></td>
                        <td style="vertical-align:middle; text-align:center"><?=$row[unit_name];?></td>
                        <td align="center" style="width:6%; text-align:center"><?=$row[batch];?></td>
                        <td align="center" style="width:6%; text-align:center"><?=$row[lot];?></td>
                        <td align="center" style="width:6%; text-align:center"><?=$row[mfg]; ?></td>
                        <td align="center" style="width:6%; text-align:center"><?=$tproduction=$row[total_unit]/$row[pack_size]; ?></td>
                        <td align="center" style="width:10%;vertical-align:middle">
                  <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                    </td>
                    
                    </tr>
                    <?php  $tp=$tp+$tproduction; } ?>
                      </tbody>
                      
               
                      
                  
 <tr>
                      
                      
                      <td colspan="7" style="font-weight:bold; font-size:11px" align="right">Total Production</td>
                      <td style="text-align:center"><strong><?php echo $tp; ?></strong></td>
                      <td align="center" ></td>
                      </tr>
                     </table>

    <button type="submit" style="float: left; font-size:12px; margin-left:1%" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you confirm to the Production Deleted?");' class="btn btn-danger">Delete Producction </button>
   <?php if($COUNT_details_data>0) { ?>
    <button type="submit" style="float: right; font-size:12px; margin-right:1%" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Finish Production </button>
<?php } else { echo '';} ?>

</form>  
<?php } ?>
<?=$html->footer_content();?>