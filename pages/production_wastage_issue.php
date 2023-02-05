<?php
 require_once 'support_file.php'; 
 $title='Production Wastage Issue Entry';
 $dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
				$todaysss=$dateTime->format("d/m/Y  h:i A");
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
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='production_wastage_issue.php?item_id=' + val ;
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
								<a target="_new" class="btn btn-sm btn-default"  href="production_wastage_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">Production Wastage Report</span>
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
	
$insert=mysql_query("INSERT INTO production_wastage_master (ref_no,date,warehouse_from,warehouse_to,ip,entry_by,entry_at,status,remarks)  VALUES ('$invoice','$ps_date','$_POST[warehouse_id]','$_POST[warehouse_id]','$ip','$_SESSION[userid]','$todaysss','MANUAL','$_POST[remarkspro]')");	

$_SESSION[initiate_production_wastage]=$invoice;
$_SESSION[pi_no] =getSVALUE("production_wastage_master", "pi_no", " where ref_no='$_SESSION[initiate_production_wastage]'");
;

}


if(isset($_POST[updatePS])){	
	
mysql_query("UPDATE production_wastage_master SET  date='$ps_date',warehouse_from='$_POST[warehouse_id]',remarks='$_POST[remarkspro]' WHERE ref_no='".$_SESSION[initiate_production_wastage]."' ");	


}



$resultsssss=mysql_query("Select * from production_wastage_master where ref_no='$_SESSION[initiate_production_wastage]'");
$inirow=mysql_fetch_array($resultsssss);

 
 
 $ofp=getSVALUE("production_floor_receive_detail", "Sum(no_of_pack)", " where custom_pr_no='$_SESSION[initiate_production_wastage]'");
 $ratesss=getSVALUE("production_floor_receive_detail", "Sum(rate) as rate", " where custom_pr_no='$_SESSION[initiate_production_wastage]'");
 $amountsss=getSVALUE("production_floor_receive_detail", "Sum(amount) as amount", " where custom_pr_no='$_SESSION[initiate_production_wastage]'");
 ?>   



                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">


<table style="width:100%">
<tr>
<td style="width:50%">

<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ref. NO<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="last-name" style="width:250px" required="required" name="invoice" value="<?=($_SESSION[initiate_production_wastage]!='')? $inirow[ref_no] : automatic_number_generate("","production_wastage_master","ref_no","date='".date('Y-m-d')."' and ref_no like '$sekeyword%'"); ?>" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_production_wastage]){ ?> readonly <?php } ?> >
                          </div>
                      </div> </td>



<td>
                 <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="ps_date" style="width:250px" required="required" name="ps_date" value="<?php if($_SESSION[initiate_production_wastage]){ echo date('m/d/y' , strtotime($inirow[date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div>
                   </td></tr> 
                    
                    
                    
                 
                      
                      
                     <tr><td> 
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">CMU<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						
						
                       
						
						
						
                        <select id="first-name" required="required" style="width:250px"   name="warehouse_id" class="select2_single form-control">
                        <?php if($_SESSION[initiate_production_wastage]){ ?>
                        <option value="<?php echo $inirow[warehouse_from]; ?>" selected><?=$warename=getSVALUE("warehouse", "warehouse_name", " where warehouse_id='$inirow[warehouse_from]'");?></option>
                        <?php } ?>
                        <option value="">Choose ......</option>
                        
                        <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL')  order by warehouse_id");
						while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
						?> 
                                         
                 <option value="<?php echo $rowVENDOR[warehouse_id]; ?>"><?php echo $rowVENDOR[warehouse_name]; ?></option>
                      
                    <?php } ?></select></div></div> </td>

                      
                   <td>
        <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Note<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="remarkspro" style="width:250px"   name="remarkspro" value="<?php if($_SESSION[initiate_production_wastage]){ echo$inirow[remarks]; } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div></td></tr>
        
               
                        
                       
               <tr><td colspan="2">   
               
               <div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_production_wastage]){  ?>
			   
			   <!---a href="production_wastage_issue.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
               <button type="submit" name="updatePS" class="btn btn-success">Update Wastage Issue Info</button>
			   
			 <?php   } else {?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Initiate Production</button>
               <?php } ?>
               </div></div>   
               </tr></table>
               
                          
               
               
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
$batch=$_POST[batch];
$m =$_POST[mfg];
$mfg=date('Y-m-d' , strtotime($m)); 	 
	 
	 
if ($valid){
	if($qtys>0){
		
	$_SESSION[spinvoice]=$invoice;	
		
 
 $productiondetails =mysql_query("INSERT INTO production_westage_detail
		(pi_no,date,ref_no, item_id, warehouse_from, warehouse_to, total_unit, unit_price, total_amt,lot, batch, mfg,ip) VALUES 

('".$_SESSION[pi_no]."','".$inirow['date']."','".$_SESSION[initiate_production_wastage]."','$item_id','$inirow[warehouse_to]','$inirow[warehouse_to]','$totalqtys','','".$total_amt."','$_POST[lot]','$batch','$mfg','$ip')");

  ?>
	
<?php }}} ?>
                   
                      




<?php

if($_SESSION[initiate_production_wastage]){

 ?>


<form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
               

 <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                  

 <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      


                      <tbody>
                       <tr>
                        
                      <td style="width:20%" align="center">
                      
                      <select class="select2_single form-control" style="width:400px" tabindex="-1" required="required" onchange="javascript:reload(this.form)" name="item_id" >
                            <option></option>
                            
                            <?php if($_GET[item_id]){ ?>
							<option value="<?=$_GET[item_id]?>" selected><?=$packsizeitem=getSVALUE("item_info", "item_name", " where item_id='".$_GET[item_id]."'");?></option>
							<?php } ?>
                            
                            <?php 
							$result=mysql_query("SELECT p.*,i.* FROM production_line_fg p, item_info i where
							p.fg_item_id=i.item_id and
							 p.line_id='$inirow[warehouse_to]' order by fg_item_id");
							while($row=mysql_fetch_array($result)){  ?>
                  <option  value="<?php echo $row[fg_item_id]; ?>"><?php echo $row[finish_goods_code]; ?>-<?php echo $row[item_name]; ?> (<?=$packsizeitem=getSVALUE("item_sub_group", "sub_group_name", " where sub_group_id='$row[sub_group_id]'");?>)</option>
                    <?php } ?>
                          </select></td>
 
 
<td style="width:15%" align="center">

<select class="select2_single form-control" style="width:150px" tabindex="-1" required="required"  name="batch" >
                            <option>select a batch</option>
                            
                            <?php 
							
							if($_POST[batch]){ ?>
							 <option  selected value="<?=$_POST[batch];?>"><?=$_POST[batch];?></option>	
							<?php } 
							$batchresult=mysql_query("SELECT distinct batch FROM production_floor_receive_detail where warehouse_to='".$inirow[warehouse_to]."' and item_id='".$_GET[item_id]."' order by batch DESC");
							while($batrow=mysql_fetch_array($batchresult)){  ?>
                  <option  value="<?php echo $batrow[batch]; ?>"><?php echo $batrow[batch]; ?></option>
                    <?php } ?>
                          </select>

                        
                       
                        
                        </td> 
                      
            <td align="center" style="width:5%">
            <button type="submit" class="btn btn-success" name="add" id="add">Search Production</button></td></tr>
                        
                        
                     
                      
                      
                      </tbody>
                     </table> 
                 </form>
                 
                 



              












<!-----------------------Data Save Confirm ------------------------------------------------------------------------->  

<?php 
							if($_GET[type]=='delete'){
								if($_GET[productdeletecode]){
								
							$results=mysql_query("Delete from production_westage_detail where id='$_GET[productdeletecode]'"); ?>
							<meta http-equiv="refresh" content="0;production_wastage_issue.php">
	
								
							<?php }} ?>
                      
<form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
                     <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                        <th>SL</th>
                          <th>Code</th>
                          <th>Product</th>   
                          <th style="width:5%; text-align:center">UOM</th>
                          
                         <th style="width:10%; text-align:center">STD. Qty</th>
                         <th style="width:10%; text-align:center">Wastage<br>Qty</th>
                         <th style="width:10%; text-align:center">Wastage<br> %</th>
                         <th style="width:15%; text-align:center">Remarks</th>

                        </tr>
                      </thead>



                      <tbody>
                       			
						
						 
				<?php 
				
		if(isset($_POST[add])){
			    $_SESSION[post_item_id]=$_POST[item_id];
				$_SESSION[post_batch]=$_POST[batch];
		}
				$results=mysql_query("Select distinct raw_item_id from production_ingredient_detail where  item_id='$_SESSION[post_item_id]'");
				while($row=mysql_fetch_array($results)){ 
				$ids=$row[raw_item_id];
				
				$wastageqty=$_POST['wastageqty'.$ids];
				$remarks=$_POST['remarks'.$ids];
				$rate=$_POST['rate'.$ids];
				$amount=$wastageqty*$rate;
				
		if (isset($_POST['confirmsave'])){
			if($wastageqty>0){
		$insert=mysql_query("INSERT INTO production_westage_detail (pi_no,ref_no,date,item_id,warehouse_from,warehouse_to,total_unit,unit_price,total_amt,batch,status,fg_for,remarks) VALUES 
('$_SESSION[pi_no]','$_SESSION[initiate_production_wastage]','$inirow[date]','$row[raw_item_id]','$inirow[warehouse_to]','$inirow[warehouse_to]','$wastageqty','$rate','$amount','$_SESSION[post_batch]','UNCHECKED','$_SESSION[post_item_id]','$remarks')")	;
		
		
		
			
		}}
				
				
				
	    ?>
	
				
				
				
				
			

                      <tr>
                        <td style="width:3%; vertical-align:middle"><?php echo $i=$i+1; ?></td>
                        <td style="width:8%; vertical-align:middle"><?= $fgcode=getSVALUE("item_info", "finish_goods_code", " where item_id='$row[raw_item_id]'");?></td>
                        
               <td style="vertical-align:middle"><?=$name=getSVALUE("item_info", "item_name", "where item_id='".$row[raw_item_id]."'"); ?></td>
               <td style="vertical-align:middle; text-align:center"><?=$unit=getSVALUE("item_info", "unit_name", "where item_id='".$row[raw_item_id]."'"); ?></td>
                        
                        
                        
                        
                        <td align="center" style="width:6%; text-align:center; display:none">
                        <input type="text" id="batchupdate<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  required="required"  name="batchupdate<?php echo $ids; ?>"  value="<?php echo $_SESSION[post_batch]; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" readonly ></td>
                        
                        
                       
                   
                        
                        
                        
                        <td align="center" style="width:10%; text-align:center">
                        <input type="text" id="qtyupdate<?php echo $ids; ?>" style="width:100px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  required="required"  name="qtyupdate<?php echo $ids; ?>"  value="<?=$qtyupdate=getSVALUE("production_floor_issue_detail", "SUM(total_unit)", "where item_id='".$row[raw_item_id]."' and fg_id='$_SESSION[post_item_id]' and   batch_for='$_SESSION[post_batch]'"); ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" readonly >
                        
                        
                        </td>
                       
                    
 <script>
function doMath<?=$ids ?>() {
    
   
    var wastageqty<?php echo $ids; ?> = parseInt(document.getElementById('wastageqty<?php echo $ids; ?>').value);
    
    var Wastagep<?php echo $ids; ?> = (wastageqty<?php echo $ids; ?> / <?=$qtyupdate=getSVALUE("production_floor_issue_detail", "SUM(total_unit)", "where item_id='".$row[raw_item_id]."' and fg_id='$_SESSION[post_item_id]' and   batch_for='$_SESSION[post_batch]'"); ?> * 100).toFixed(2);
   

    
    document.getElementById('Wastagep<?=$ids ?>').value = Wastagep<?php echo $ids; ?>;
    
}
</script>                      
                  
                  
                  
                  <td align="center" style="width:10%;vertical-align:middle">
                  <input type="text" id="wastageqty<?php echo $ids; ?>" style="width:100px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  onBlur="doMath<?=$ids ?>();"  name="wastageqty<?php echo $ids; ?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" >
                    </td>
                    
                     
                    
                    
                    <td  align="center" style="width:10%;vertical-align:middle; display:none">
                  <input type="text" id="rate<?php echo $ids; ?>" style="width:130px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  value="<?=$name=getSVALUE("item_landad_cost", "landad_cost", "where item_id='".$row[raw_item_id]."'"); ?>"   name="rate<?php echo $ids; ?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" readonly >
                    </td>
                    
                   
                    
                    
                    <td  align="center" style="width:10%;vertical-align:middle;">
                  <input type="text" id="Wastagep<?php echo $ids; ?>" style="width:80px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"     name="Wastagep<?php echo $ids; ?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" readonly  >
                    
                    

<!--div>Total Parts: <input type="text" id="parts_input" value="1" readonly="true" /></div>
<div>Labor: <input type="text" id="labor_input" onBlur="doMath();" /></div>
<div>Misc: <input type="text" id="misc_input" onBlur="doMath();" /></div>
<div>Sub Total: <input type="text" id="subtotal_input" readonly="true" /></div>
<div>Tax: <input type="text" id="tax_input" readonly="true" /></div>
<div>Total: <input type="text" id="total_input" readonly="true" /></div---->
                    
                    
                    </td>
                    
                    <td  align="center" style="width:10%;vertical-align:middle;">
                  <input type="text" id="remarks<?php echo $ids; ?>" style="width:130px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"     name="remarks<?php echo $ids; ?>"  class="form-control col-md-7 col-xs-12" autocomplete="off" >
                    </td>
                    
                    </tr> 
					<?php }?>
                    </tbody>
                      
               
                      
                  
 
                      
                      
 
 					

                      
                       
                      
                     
                    
                    
                     
                  
                  
                  
                      
                      
                      
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




$cmuledgerRM=getSVALUE("warehouse", "ledger_id_RM", " where warehouse_id='$inirow[warehouse_to]'");
$cmuledgerFG=getSVALUE("warehouse", "ledger_id", " where warehouse_id='$inirow[warehouse_to]'");
$journal="INSERT INTO `` (
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
									,group_for
									)
					VALUES ('$crowjr[proj_id]', '$jv', '$date', '".$cmuledgerFG."', 'Finish Good Production, PSNO#$_SESSION[initiate_production_wastage]', '".$conamounttotal."','', 'ProductionReceived','$crowjr[sub_ledger]', '$_SESSION[pr_no]','$_SESSION[initiate_production_wastage]','', '$cc_code','".$_SESSION['user']['id']."','".$_SESSION['user']['group']."')";					
				$query_journal = mysql_query($journal);	
				
				

$journal="INSERT INTO `` (
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
									,group_for
									)
					VALUES ('$crowjr[proj_id]', '$jv', '$date', '$cmuledgerRM', 'RM Issue to Production, PSNO#$_SESSION[initiate_production_wastage]', '','".$conamounttotal."', 'ProductionISSUE','$crowjr[sub_ledger]', '$_SESSION[pr_no]','$_SESSION[initiate_production_wastage]','','$cc_code','".$_SESSION['user']['id']."','".$_SESSION['user']['group']."')";					
				$query_journal = mysql_query($journal);	


mysql_query("UPDATE production_westage_detail SET status='UNCHECKED' WHERE ref_no='$_SESSION[initiate_production_wastage]'");
mysql_query("UPDATE production_wastage_master SET status='UNCHECKED' WHERE ref_no='$_SESSION[initiate_production_wastage]'");


unset($_SESSION['initiate_production_wastage']); 
unset($_SESSION[pi_no]); 
unset($_SESSION[post_item_id]); 
		unset($_SESSION[post_batch]); 
  ?>
	
<meta http-equiv="refresh" content="0;production_wastage_issue.php">
<?php } ?>			 
                        
                        
                       <?php 
					   
					   if(isset($_POST[cancel])){
				mysql_query("Delete from production_westage_detail where ref_no='".$_SESSION["initiate_production_wastage"]."'");
				mysql_query("Delete from production_wastage_master where ref_no='".$_SESSION["initiate_production_wastage"]."'");
						   unset($_SESSION["initiate_production_wastage"]);
						   unset($_SESSION[post_item_id]);
						   unset($_SESSION[post_batch]);
						   
						   ?>
                       <meta http-equiv="refresh" content="0;production_wastage_issue.php">
					   <?php } ?>
                          
                          <button type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Cancel?");' class="btn btn-primary">Cancel Producction Wastage Issue </button>
                          <button type="submit" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Producction Wastage Issue </button>
                          
                          
                       
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
