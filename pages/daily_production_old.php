<?php
 require_once 'support_file.php'; 
 $title='Production Entry';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
 
  <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.productcode.options[form.productcode.options.selectedIndex].value;
	self.location='purchase.php?productcodeget=' + val ;
}


</script>



    <script>
        var x = 0;
        var y = 0;
        var z = 0;
        function calc(obj) {
            var e = obj.id.toString();
            if (e == 'qtys') {
                x = Number(obj.value);
                y = Number(document.getElementById('rate').value);
            } else {
                x = Number(document.getElementById('qtys').value);
                y = Number(obj.value);
            }
            z = x * y;
            document.getElementById('total').value = z;
            document.getElementById('update').innerHTML = z;
        }
		
		
		var submit = document.querySelector("input[type=submit]");
  
/* set onclick on submit input */   
submit.setAttribute("onclick", "return test()");

//submit.addEventListener("click", test);

function test() {

  if (confirm('Are you sure you want to submit this form?')) {         
    return true;         
  } else {
    return false;
  }

}
    </script>
    
    
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="<?php echo $webiste; ?>" class="site_title"><i class="fa fa-paw"></i> <span>ICPBD</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
           <?php include ("pro.php");  ?>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                         <?php include("sidebar_menus.php"); ?>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
            <?php include("menu_footer.php"); ?>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
         <?php include("top.php"); ?>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
           
           

            <div class="row">
              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                     <ul class="nav navbar-right panel_toolbox">
                     <div class="input-group pull-right">
								<a target="_new" class="btn btn-sm btn-default"  href="production_manual_and_return.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">MANUAL & Return Production</span>
								</a>

                               <a target="_new" class="btn btn-sm btn-default"  href="production_report.php">
                               <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Production Report</span>
                               </a>
                                
                                <a target="_blank" class="btn btn-sm btn-default"  href="production_consumption_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Consumption Report</span>
								</a>
                                
                                
								</div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              
                    

<?php 
$initiate=$_POST[initiate];

$d =$_POST[ps_date];
$ps_date=date('Y-m-d' , strtotime($d)); 
$invoice=$_POST[invoice];
$billno=$_POST[billno];
$enat=date('Y-m-d h:s:i');
if(isset($initiate)){	
	
$insert=mysql_query("INSERT INTO production_floor_receive_master (custom_pr_no,pr_date,warehouse_from,warehouse_to,ip,entry_by,entry_at,status,remarks)  VALUES ('$invoice','$ps_date','$_POST[warehouse_id]','$_POST[warehouse_id]','$ip','$_SESSION[userid]','$enat','MANUAL','$_POST[remarkspro]')");	

$_SESSION[initiate_daily_production]=$invoice;
$_SESSION[pr_no] =getSVALUE("production_floor_receive_master", "pr_no", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
;

}


if(isset($_POST[updatePS])){	
	
mysql_query("UPDATE production_floor_receive_master SET  pr_date='$ps_date',warehouse_to='$_POST[warehouse_id]' WHERE custom_pr_no='".$_SESSION[initiate_daily_production]."' ");	


}



$resultsssss=mysql_query("Select * from production_floor_receive_master where custom_pr_no='$_SESSION[initiate_daily_production]' and custom_pr_no!='0'");
$inirow=mysql_fetch_array($resultsssss);

 
 
 $ofp=getSVALUE("production_floor_receive_detail", "Sum(no_of_pack)", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
 $ratesss=getSVALUE("production_floor_receive_detail", "Sum(rate) as rate", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
 $amountsss=getSVALUE("production_floor_receive_detail", "Sum(amount) as amount", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
 ?>   



                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
<table style="width:100%">






<tr>
<td style="width:50%">



<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">PS NO<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="last-name"   required="required" name="invoice" value="<?php if($_SESSION[initiate_daily_production]){ echo $inirow[custom_pr_no];} else { echo $_SESSION['PS']; } ?>" class="form-control col-md-7 col-xs-12"  readonly >
                          </div>
                      </div> 

</td>
<td>


                 <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Production Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="ps_date"  required="required" name="ps_date" value="<?php if($_SESSION[initiate_daily_production]){ echo date('m/d/y' , strtotime($inirow[pr_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div>
                    
      </td>              
                    
                    
                 
                      
                      
            <tr>
            <td>          
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">CMU / Depot<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
						
                       
						
						
						
                        <select id="first-name" required="required" style=""   name="warehouse_id" class="select2_single form-control">
                        <?php if($_SESSION[initiate_daily_production]){ ?>
                        <option value="<?php echo $inirow[warehouse_to]; ?>" selected><?=$warename=getSVALUE("warehouse", "warehouse_name", " where warehouse_id='$inirow[warehouse_to]'");?></option>
                        <?php } ?>
                        <option value="">Choose ......</option>
                        
                        <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL','WH')  order by warehouse_id");
						while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
						?> 
                                         
                 <option value="<?php echo $rowVENDOR[warehouse_id]; ?>"><?php echo $rowVENDOR[warehouse_name]; ?></option>
                      
                    <?php } ?></select></div></div> 
</td>

<td>
                      
                      
        <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Note<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="remarkspro"   name="remarkspro" value="<?php if($_SESSION[initiate_daily_production]){ echo$inirow[remarks]; } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div>
        
               </td>
                        
                       
       <tr>
       <td colspan="4">        
               
               <div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_daily_production]){  ?>
			   
			   <!---a href="daily_production.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
               <button type="submit" name="updatePS" class="btn btn-success">Update PS Documents</button>
			   
			 <?php   } else {?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Initiate Production Entry</button>
               <?php } ?>
               </div></div>   
               </td></tr></tr>
               
                          
               
               
               </form>
               
               
               
               
               
<!----------------------------------- initiate end--------------------------------------------------------------------->               
               
               
               
               
               
 <?php                    


;
				$item_id=$_POST[item_id];
				$rate=$_POST[rate];
				$qtys=$_POST[qtys];
				$amounts=$rate*$qtys;
				$mfg=$_POST[mfg];
				$no_of_pack=$_POST[no_of_pack];
				$po_no=$_POST[po_no];
				$tdates=date("Y-m-d");
				$idatess=date('Y-m-d'); 
                $day = date('l', strtotime($idatess));
				$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
				$timess=$dateTime->format("d-m-y  h:i A");
				//echo "$timess";


$add=$_POST[add];                    
if (isset($_POST['add'])){	
$valid = true;
$packsize=getSVALUE("item_info", "pack_size", " where item_id='$item_id'");
list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $_POST[mfg]);	
$totalqtys=($_POST[qtys]*$packsize);
$batch=$_POST[batch].$_POST[batchs];
$m =$_POST[mfg];
$mfg=date('Y-m-d' , strtotime($m)); 	 
	 
	 
if ($valid){
	if($qtys>0){
		
	$_SESSION[spinvoice]=$invoice;	
		
 
 $productiondetails =mysql_query("INSERT INTO production_floor_receive_detail
		(pr_no, pr_date,custom_pr_no, item_id, warehouse_from, warehouse_to, total_unit, unit_price, total_amt,lot, batch, mfg, mfg_month, mfg_year,ip) VALUES 

('".$_SESSION[pr_no]."','".$inirow['pr_date']."','".$_SESSION[initiate_daily_production]."','$item_id','$inirow[warehouse_to]','$inirow[warehouse_to]','$totalqtys','','".$total_amt."','$_POST[lot]','$batch','$mfg','$month','$year1','$ip')");

  ?>
	
<?php }}} ?>
                   
                      




<?php

if($_SESSION[initiate_daily_production]){

 ?>


<form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
               

 <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                  

 <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      


                      <tbody>
                       <tr>
                        
                      <td style="width:20%" align="center">
                      
                      <select class="select2_single form-control" style="width:400px" tabindex="-1" required="required"  name="item_id" >
                            <option></option>
                            
                            <?php 
							//$result=mysql_query("SELECT * FROM item_info where sub_group_id in ('200010000','300010000','500010000','800010000') order by item_name");
							
							$result=mysql_query("SELECT f.*,i.* FROM 
							production_line_fg f,
							item_info i
							 where 
							 i.item_id=f.fg_item_id and 
							 i.sub_group_id in ('200010000','300010000','500010000','800010000') and
							 f.line_id='$inirow[warehouse_to]'
							 
							  order by i.item_name");
							while($row=mysql_fetch_array($result)){  ?>
                  <option  value="<?php echo $row[item_id]; ?>"><?php echo $row[finish_goods_code]; ?>-<?php echo $row[item_name]; ?> (<?=$packsizeitem=getSVALUE("item_sub_group", "sub_group_name", " where sub_group_id='$row[sub_group_id]'");?>)</option>
                    <?php } ?>
                          </select></td>
 
 
<td style="width:15%" align="center">
                        <input type="text" id="qtys" style="width:70px; height:37px; font-weight:bold; text-align:center"  required="required"  name="batch" placeholder="Batch" class="form-control col-md-7 col-xs-12" value="<?=$bt=getSVALUE("warehouse", "nick_name", " where warehouse_id='$inirow[warehouse_to]'"). date('y');?>" readonly autocomplete="off" >
                        <?php 
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
                         <input type="text" id="qtys" style="width:60px; height:37px; font-weight:bold; text-align:center"  required="required"  name="batchs" placeholder="Batchs" class="form-control col-md-7 col-xs-12" value="<?php echo $_SESSION['batch']; ?>"  autocomplete="off" >
                        </td>              
                      

   
                     <td align="center" style="width:8%"> 
                     <input type="text" id="no_of_pack" style="width:100px; height:37px; font-weight:bold; text-align:center"  required="required"  name="lot" placeholder="Lot" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                     </td>


<td style="width:5%" align="center">
                        <input type="text" id="mfg" style="width:100px; height:37px;   text-align:center"    name="mfg" placeholder="MFG"  class="form-control col-md-7 col-xs-12"  >
</td>
                    
                     
                     
                     
<td style="width:5%" align="center">
                        <input type="text" id="qtys" style="width:80px; height:37px; font-weight:bold; text-align:center"  required="required"  name="qtys" placeholder="Qty" onkeyup="calc(this)" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        
                     
                      
            <td align="center" style="width:5%">
            <button type="submit" class="btn btn-success" name="add" id="add">Add</button></td></tr>
                        
                        
                     
                      
                      
                      </tbody>
                     </table> 
                 </form>
                 
                 



              












<!-----------------------Data Save Confirm ------------------------------------------------------------------------->  

<?php 
							if($_GET[type]=='delete'){
								if($_GET[productdeletecode]){
								
							$results=mysql_query("Delete from production_floor_receive_detail where id='$_GET[productdeletecode]'"); ?>
							<meta http-equiv="refresh" content="0;daily_production.php">
	
								
							<?php }} ?>
                      
<form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
                     <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
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
				
				
				
				if (isset($_POST['confirmsave'])){
$do =mysql_query("INSERT INTO production_floor_issue_master (pi_date, remarks, warehouse_from, warehouse_to, carried_by, received_by, status, entry_at, entry_by,  production_for_pr_no) VALUES 
('$inirow[pr_date]','','$inirow[warehouse_to]','','','','UNCHECKED','$enat','$_SESSION[userid]','$_SESSION[initiate_daily_production]')");

}
$pinoGET=getSVALUE("production_floor_issue_master", "pi_no", " where production_for_pr_no='$_SESSION[initiate_daily_production]'");				
				
				$results=mysql_query("Select * from production_floor_receive_detail where   custom_pr_no='$_SESSION[initiate_daily_production]'");
				while($row=mysql_fetch_array($results)){ 
	
	    
			
////////////////////////////////////// row material consumption start from here//////////////////////////////////////////////
if (isset($_POST['confirmsave'])){	
$packsizefind=$totalqty*$fgid[pack_size];
       

$rowformula=mysql_query("Select * from production_ingredient_detail where item_id='$row[item_id]' and line_id='$row[warehouse_to]'");
while($fgrows=mysql_fetch_array($rowformula)){
	
	
	

// consumption qty calculation
$issuerow=($row[total_unit]*$fgrows[unit_batch_qty])/$fgrows[unit_batch_size];
$issuerowSINGLE=($fgrows[row_pack_size]*$fgrows[unit_batch_qty])/$fgrows[unit_batch_size];

	
	$pre_stock=$itemin-$itemout;
    $final_stock=$pre_stock-$issuerow;


 $rowSQL = mysql_query( "SELECT distinct(landad_cost) AS LargePrice FROM `item_landad_cost` where status='Active' and item_id='$fgrows[raw_item_id]'");
 $rowLargePrice = mysql_fetch_array( $rowSQL );
 $comprice = $rowLargePrice['LargePrice'];	
	$conamount=$issuerow*$comprice;
	$conamounttotal=$conamounttotal+$conamount;
	
	///SINGLE
	$conamountSINGLE=$issuerowSINGLE*$comprice;
	$conamounttotalSINGLE=$conamounttotalSINGLE+$conamountSINGLE;
	
if($fgrows[type]=='-'){
	
$do =mysql_query("INSERT INTO production_floor_issue_detail (pi_no, pi_date, item_id, warehouse_from, warehouse_to, total_unit, unit_price, total_amt, status, production_for_pr_no, fg_id,batch_for,BOM) VALUES 
('$pinoGET','$inirow[pr_date]','$fgrows[raw_item_id]','$inirow[warehouse_to]','','$issuerow','$comprice ','$conamount','UNCHECKED','$_SESSION[initiate_daily_production]','$row[item_id]','$row[batch]','$fgrows[unit_batch_qty]')");	

$tr_no=getSVALUE("production_floor_issue_detail", "id", " where item_id='$fgrows[raw_item_id]' and pr_no='$_SESSION[pr_no]'");


	
$item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, pre_stock, pre_price, item_in, item_ex, item_price, total_amt, tr_from,tr_no,sr_no,entry_by,entry_at,consumption_for_fg,ip,batch,custom_no) VALUES 
('$inirow[pr_date]','$fgrows[raw_item_id]','$inirow[warehouse_to]','','','','','$issuerow','$comprice','$conamount','Consumption','$tr_no','$_SESSION[pr_no]','$_SESSION[userid]','$enat','$row[item_id]','$ip','$row[batch]','$_SESSION[initiate_daily_production]')");

}


if($fgrows[type]=='+'){
	
$Cogsprice=getSVALUE("item_info", "production_cost", " where item_id='$fgrows[raw_item_id]'");

$do =mysql_query("INSERT INTO production_floor_receive_detail
		(pr_no, pr_date,custom_pr_no, item_id, warehouse_from, warehouse_to, total_unit, unit_price, total_amt,lot, batch, mfg, mfg_month, mfg_year,ip) VALUES 

('".$_SESSION[pr_no]."','".$inirow['pr_date']."','".$_SESSION[initiate_daily_production]."','$fgrows[raw_item_id]','$inirow[warehouse_to]','$inirow[warehouse_to]','$issuerow','','".$Cogsprice."','$_POST[lot]','$batch','$mfg','$month','$year1','$ip')");
	

$tr_no=getSVALUE("production_floor_issue_detail", "id", " where item_id='$fgrows[raw_item_id]' and pr_no='$_SESSION[pr_no]'");


$conamountFG=$Cogsprice*$issuerow;

	
//$item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, pre_stock, pre_price, item_in, item_ex, item_price, total_amt, tr_from,tr_no,sr_no,entry_by,entry_at,consumption_for_fg,ip,batch,custom_no) VALUES 
//('$inirow[pr_date]','$fgrows[raw_item_id]','$inirow[warehouse_to]','','','','$issuerow','','$Cogsprice','$conamountFG','Production','$tr_no','$_SESSION[pr_no]','$_SESSION[userid]','$enat','$row[item_id]','$ip','$row[batch]','$_SESSION[initiate_daily_production]')");

}




$fgrate=$fgrows[unit_batch_qty]*$comprice;
$fgratetotal=number_format(($fgratetotal+$fgrate),2);
}
$pks=getSVALUE("item_info", "pack_size", " where item_id='$row[item_id]'");
//$tmt=($row[total_unit]/$pks)*$fgratetotal;
$tmt=($row[total_unit])*$fgratetotal;
mysql_query("UPDATE production_floor_receive_detail SET unit_price='$fgratetotal', total_amt='$tmt' where custom_pr_no='$_SESSION[initiate_daily_production]' and item_id='$row[item_id]'");
}
/// end of row material consumption				
				
				
				
				
				
				
				$packsizeitem=getSVALUE("item_info", "pack_size", " where item_id='$row[item_id]'");
				
			
				
				$i=$i+1;
				$ids=$row[id];
				$upqty=$_POST['qtyupdate'.$ids]*$packsizeitem;
				$mfgupdate=$_POST['mfgupdate'.$ids];
				$batchupdate=$_POST['batchupdate'.$ids];
				$lotupdate=$_POST['lotupdate'.$ids];
				if(isset($_POST['deletedata'.$ids]))
				{
				mysql_query("DELETE FROM production_floor_receive_detail WHERE id='$ids' and custom_pr_no='$_SESSION[initiate_daily_production]'"); ?>
                <meta http-equiv="refresh" content="0;daily_production.php">
                <?php 
				}		
				
				if(isset($_POST['editdata'.$ids]))
				{
				mysql_query("UPDATE production_floor_receive_detail SET total_unit='$upqty',mfg='$mfgupdate',lot='$lotupdate',batch='$batchupdate' WHERE id='$ids' and custom_pr_no='$_SESSION[initiate_daily_production]'");?>
                <meta http-equiv="refresh" content="0;daily_production.php">
				<?php }?>
				

                      <tr>
                        <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                        <td style="width:8%; vertical-align:middle"><?= $fgcode=getSVALUE("item_info", "finish_goods_code", " where item_id='$row[item_id]'");?></td>
                        
               <td style="vertical-align:middle"><?=$name=getSVALUE("item_info", "item_name", "where item_id='".$row['item_id']."'"); ?></td>
               <td style="vertical-align:middle; text-align:center"><?=$unit=getSVALUE("item_info", "unit_name", "where item_id='".$row['item_id']."'"); ?></td>
                        
                        
                        
                        
                        <td align="center" style="width:6%; text-align:center">
                        <input type="text" id="batchupdate<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  required="required"  name="batchupdate<?php echo $ids; ?>"  value="<?php echo $row[batch]; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        
                        
                        <td align="center" style="width:6%; text-align:center">
                        <input type="text" id="noofpackupdate<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  required="required"  name="lotupdate<?php echo $ids; ?>"  value="<?php echo $row[lot]; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                      
                      
                        
                        
                        <td align="center" style="width:6%; text-align:center">
                        <input type="text" id="mfgupdate<?php echo $ids; ?>" style="width:110px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  required="required"  name="mfgupdate<?php echo $ids; ?>"  value="<?php echo $row[mfg]; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        
                        
                        <td align="center" style="width:6%; text-align:center">
                        <input type="text" id="qtyupdate<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  required="required"  name="qtyupdate<?php echo $ids; ?>"  value="<?=$tproduction=$row[total_unit]/$packsizeitem; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" >
                        
                        
                        </td>
                       
                    
                  
                  
                  
                  
                  <td align="center" style="width:10%;vertical-align:middle">
                  <button type="submit" name="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Update?");'><img src="update-icon.png" style="width:25px;  height:25px"></button>
                  
                  
                   <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:25px;  height:25px"></button>
                    </td>
                    
                    </tr>
                        
                        
                     
                        <?php 
						$profgrate=getSVALUE("item_info", "production_cost", " where item_id='$row[item_id]'");
                         $fgtotamvalue=$row[total_unit]*$profgrate;
						} ?>
                        
                        <?php 
						$tp=$tp+$tproduction;						
                        $totalfgvalue=$totalfgvalue+$fgtotamvalue?>
                      
                      </tbody>
                      
               
                      
                  
 <tr>
                      
                      
                      <td colspan="7" style="font-weight:bold; font-size:14px" align="right">Total Production</td>
                      <td style="text-align:center"><strong><?php echo $tp; ?></strong></td>
                      <td align="center" ></td>
                      
                      </tr>
                      
                      
 
 					

                      
                       
                      
                     
                    
                    
                     
                  
                  
                  
                      
                      
                      
                      <tr>
                      <td colspan="9" style="text-align:center">
                     
                        
                        <?php
			
if (isset($_POST['confirmsave'])){
$valid = true;  
 $datereal=date("Y-m-d");
	 list( $year1, $month, $day) = preg_split("/[\/\.\-]+/", $daterea);	
	 $date=$day.'-'.$month.'-'.$year1;
	 
	 //voucher date decode
	$j=0;
	for($i=0;$i<strlen($date);$i++)
	{
		if(is_numeric($date[$i]))
		{
			$time[$j]=$time[$j].$date[$i];
		}
		else 
		{
			$j++;
		}
	}
	$date=mktime(0,0,0,$time[1],$time[0],$time[2]);
	//////////////////////
	//check date decode
	$j=0;
	for($i=0;$i<strlen($c_date);$i++)
	{
	if(is_numeric($c_date[$i]))
	$ptime[$j]=$ptime[$j].$c_date[$i];
	else $j++;
	}
	$c_date=mktime(0,0,0,$ptime[1],$ptime[0],$ptime[2]);
	//////////////////////////	
	
	$rowSQLJVLearge = mysql_query( "SELECT MAX( jv_no ) AS jv_noLearge FROM `journal`;" );
$rowJVLarge = mysql_fetch_array( $rowSQLJVLearge );
$jv=$rowJVLarge['jv_noLearge']+1;



$tr_no_customProduction=getSVALUE("production_floor_issue_master", "pi_no", " where production_for_pr_no='$_SESSION[initiate_daily_production]'");
$cmuledgerRM=getSVALUE("warehouse", "ledger_id_RM", " where warehouse_id='$inirow[warehouse_to]'");
$cmuledgerFG=getSVALUE("warehouse", "ledger_id", " where warehouse_id='$inirow[warehouse_to]'");
$journal="INSERT INTO `journal` (
									`proj_id` ,
									`jv_no` ,
									`jv_date` ,
									`ledger_id` ,
									`narration` ,
									`dr_amt` ,
									`cr_amt` ,
									`tr_from` ,
									`sub_ledger` ,
									`tr_no`,
									`tr_no_custom`,
									`tr_id`,
									`cc_code` 
									,user_id
									,group_for,jvdate,ip,custom_no
									)
					VALUES ('$crowjr[proj_id]', '$jv', '$date', '".$cmuledgerFG."', 'Finish Good Production, PSNO#$_SESSION[initiate_daily_production]', '".$conamounttotal."','', 'ProductionReceived','$crowjr[sub_ledger]', '$_SESSION[pr_no]','$tr_no_customProduction','', '$cc_code','".$_SESSION['userid']."','".$_SESSION['user']['group']."','$tdates','$ip','$_SESSION[initiate_daily_production]')";					
				$query_journal = mysql_query($journal);	
				
				

$journal="INSERT INTO `journal` (
									`proj_id` ,
									`jv_no` ,
									`jv_date` ,
									`ledger_id` ,
									`narration` ,
									`dr_amt` ,
									`cr_amt` ,
									`tr_from` ,
									`sub_ledger` ,
									`tr_no`,
									`tr_no_custom`,
									`tr_id`,
									`cc_code` 
									,user_id
									,group_for,jvdate,ip,custom_no
									)
					VALUES ('$crowjr[proj_id]', '$jv', '$date', '$cmuledgerRM', 'RM Issue to Production, PSNO#$_SESSION[initiate_daily_production]', '','".$conamounttotal."', 'ProductionISSUE','$crowjr[sub_ledger]', '$_SESSION[pr_no]','$tr_no_customProduction','','$cc_code','".$_SESSION['userid']."','".$_SESSION['user']['group']."','$tdates','$ip','$_SESSION[initiate_daily_production]')";					
				$query_journal = mysql_query($journal);	



mysql_query("UPDATE production_floor_receive_master SET status='UNCHECKED' WHERE custom_pr_no='$_SESSION[initiate_daily_production]'");

mysql_query("UPDATE production_floor_receive_detail SET status='UNCHECKED' WHERE custom_pr_no='$_SESSION[initiate_daily_production]'");
unset($_SESSION['initiate_daily_production']); 
unset($_SESSION[pr_no]); 

  ?>
	
<meta http-equiv="refresh" content="0;daily_production.php">
<?php } ?>			 
                        
                        
                       <?php 
					   
					   $cancel=$_POST[cancel];
					   
					   if(isset($cancel)){
						   
						   
						   
						   
						   mysql_query("Delete from production_floor_issue_master where production_for_pr_no='$_SESSION[initiate_daily_production]'");
						   
						   mysql_query("Delete from production_floor_issue_detail where production_for_pr_no='$_SESSION[initiate_daily_production]'");
						   
						   
					  $delete=mysql_query("Delete from production_floor_receive_master where custom_pr_no='$_SESSION[initiate_daily_production]'");
  
  
$deletes=mysql_query("Delete From production_floor_receive_detail where custom_pr_no='$_SESSION[initiate_daily_production]'");
					   
					   unset($_SESSION["initiate_daily_production"]);
					   
					   
					   ?>
                       <meta http-equiv="refresh" content="0;daily_production.php">

                       <?php } ?>
                          
                          <button type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete MAN?");' class="btn btn-primary">Delete Producction </button>
                          <button type="submit" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Finish Production </button>
                          
                          
                       
                      </td></tr> 
                    </table>  
                   
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

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../vendors/starrr/dist/starrr.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- bootstrap-daterangepicker -->
    <script>
      $(document).ready(function() {
        $('#ps_date').daterangepicker({
			
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
    <!-- /bootstrap-daterangepicker -->

   

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "select your choice",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
          maximumSelectionLength: 4,
          placeholder: "With Max Selection limit 4",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
    
    
    
    

   
    

   

   

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
