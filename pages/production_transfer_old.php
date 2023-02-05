<?php
 require_once 'support_file.php'; 
 $title='Production Transfer (STO)';
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
	var val=form.transfer_from.options[form.transfer_from.options.selectedIndex].value;
	self.location='production_transfer.php?transfer_from=' + val ;
}

function reloaditem(form)
{
	
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='production_transfer.php?transfer_from=' + '<?php echo $_GET[transfer_from]; ?>' + '&item_id=' + val;
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

                         <a target="_new" class="btn btn-sm btn-default"  href="sto_trasnfer_return.php">
                             <i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">STO Return List</span>
                         </a>

								<a target="_new" class="btn btn-sm btn-default"  href="STO_report.php">
									<i class="fa fa-plus-circle"></i> <span class="language" style="color:#000">STO Report</span>
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
	
$insert=mysql_query("INSERT INTO production_issue_master (custom_pi_no,pi_date,receive_date,warehouse_from,warehouse_to,entry_by,entry_at,status,remarks,VATChallanno,ip,ISSUE_TYPE,transporter,track_no,driver_info)  VALUES ('$invoice','$ps_date','$ps_date','$_GET[transfer_from]','$_POST[warehouse_to]','$_SESSION[userid]','$enat','SEND','$_POST[remarkspro]','$_POST[VATChallanno]','$ip','STO','$_POST[transporter]','$_POST[track_no]','$_POST[driver_info]')");	

$_SESSION[initiate_production_transfer]=$invoice;
$_SESSION[pi_no] =getSVALUE("production_issue_master", "pi_no", " where custom_pi_no='$_SESSION[initiate_production_transfer]'");
;

}


if(isset($_POST[updatePS])){	
	
mysql_query("UPDATE production_issue_master SET  pi_date='$ps_date',warehouse_from='$_POST[transfer_from]',warehouse_to='$_POST[warehouse_to]',VATChallanno='$_POST[VATChallanno]',remarks='$_POST[remarkspro]',transporter='$_POST[transporter]',track_no='$_POST[track_no]',driver_info='$_POST[driver_info]' WHERE custom_pi_no='".$_SESSION[initiate_production_transfer]."' ");	


}



$resultsssss=mysql_query("Select * from production_issue_master where custom_pi_no='$_SESSION[initiate_production_transfer]'");
$inirow=mysql_fetch_array($resultsssss);

 
 
 $ofp=getSVALUE("production_floor_receive_detail", "Sum(no_of_pack)", " where custom_pr_no='$_SESSION[initiate_production_transfer]'");
 $ratesss=getSVALUE("production_floor_receive_detail", "Sum(rate) as rate", " where custom_pr_no='$_SESSION[initiate_production_transfer]'");
 $amountsss=getSVALUE("production_floor_receive_detail", "Sum(amount) as amount", " where custom_pr_no='$_SESSION[initiate_production_transfer]'");
 ?>   



                    
                    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">


<table style="width:100%">






<tr>
<td style="width:35%">
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Transfer From<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="first-name" required="required" style="width:200px"   name="transfer_from" class="select2_single form-control" onchange="javascript:reload(this.form)">
                        
						<?php if($_SESSION[initiate_production_transfer]){ ?>
                        <option value="<?php echo $inirow[warehouse_to]; ?>" selected><?=$warename=getSVALUE("warehouse", "warehouse_name", " where warehouse_id='$inirow[warehouse_to]'");?></option>
                        <?php } if  ($_GET[transfer_from]) { ?>
                         <option selected value="<?php echo $_GET[transfer_from]; ?>"><?=$warename=getSVALUE("warehouse", "warehouse_name", " where warehouse_id='$_GET[transfer_from]'");?></option>
                      <?php } ?>
                        <option value="">Choose ......</option>
                        
                        <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL','WH') and warehouse_id!='$_GET[transfer_from]'  order by warehouse_id");
						while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
						?> 
                                         
                 <option value="<?php echo $rowVENDOR[warehouse_id]; ?>"><?php echo $rowVENDOR[warehouse_name]; ?></option>
                      
                    <?php } ?></select></div></div> 
</td>

<?php
if($_GET[transfer_from]){
 ?>
<td style="width:35%">
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Transfer To<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="first-name" required="required" style="width:200px"   name="warehouse_to" class="select2_single form-control">
                        <?php if($_SESSION[initiate_production_transfer]){ ?>
                        <option value="<?php echo $inirow[warehouse_to]; ?>" selected><?=$warename=getSVALUE("warehouse", "warehouse_name", " where warehouse_id='$inirow[warehouse_to]'");?></option>
                        <?php } ?>
                        <option value="">Choose ......</option>
                        
                        <?php $resultVENDOR=mysql_query("Select * from warehouse where use_type in ('PL','WH') and warehouse_id!='$_GET[transfer_from]'  order by warehouse_id");
						while($rowVENDOR=mysql_fetch_array($resultVENDOR)){
						?> 
                                         
                 <option value="<?php echo $rowVENDOR[warehouse_id]; ?>"><?php echo $rowVENDOR[warehouse_name]; ?></option>
                      
                    <?php } ?></select></div></div> 
</td>
<td style="width:30%">
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">VAT Challan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="last-name" style="width:150px"    required="required" name="VATChallanno" value="<?=$inirow[VATChallanno]?>" class="form-control col-md-7 col-xs-12"  Placeholder="Challan & Date" >
                          </div>
                      </div> 
                      </td>
<?php } ?>
</tr>



<?php
if($_GET[transfer_from]){
 ?>

<tr>
<td>
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">STO NO<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="last-name" style="width:200px"   required="required" name="invoice" value="<?php if($_SESSION[initiate_production_transfer]){ echo $inirow[custom_pi_no];} else { echo $_SESSION['STO']; } ?>" class="form-control col-md-7 col-xs-12" <?php if($_SESSION[initiate_production_transfer]){ ?> readonly <?php } ?> >
                          </div>
                      </div> 
                      </td>
                      
                      
<td>
<div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">STO Date<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="ps_date" style="width:200px"  required="required" name="ps_date" value="<?php if($_SESSION[initiate_production_transfer]){ echo date('m/d/y' , strtotime($inirow[pi_date])); } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div> 
                      </td>   
                      
                      <td>
<div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Remarks<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="remarkspro" style="width:150px"  required="required" name="remarkspro" value="<?=$inirow[remarks]?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div> 
                      </td>                     
</tr>









<tr>
<td>
<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Transporter Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="first-name" required="required" style="width:200px"   name="transporter" class="select2_single form-control">
                        
						<?php if($_SESSION[initiate_production_transfer]){ ?>
                        <option value="<?php echo $inirow[transporter]; ?>" selected><?=$transporter=getSVALUE("vendor", "vendor_name", " where vendor_id='$inirow[transporter]'");?></option>
                        <?php } ?>
                        <option value="">Choose ......</option>
                        <option value="0">Others</option>
                        
                        <?php $resultVENDORTR=mysql_query("Select * from vendor where vendor_category in ('30') order by vendor_name");
						while($rowVENDORTR=mysql_fetch_array($resultVENDORTR)){
						?> 
                                         
                 <option value="<?php echo $rowVENDORTR[vendor_id]; ?>"><?php echo $rowVENDORTR[vendor_name]; ?></option>
                      
                    <?php } ?></select></div></div>  
                      </td>
                      
                      
<td>
<div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Track No.<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="ps_date" style="width:200px"  name="track_no" value="<?=$inirow[track_no]?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div> 
                      </td>   
                      
                      <td>
<div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Driver Info<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="remarkspro" style="width:150px"  required="required" name="driver_info" value="<?=$inirow[driver_info]?>" class="form-control col-md-7 col-xs-12" Placeholder="Name & mobile No" >

                      </div>  
	                </div> 
                      </td>                     
</tr>






<tr><td style="height:30px"></td></tr>
<tr>
<td colspan="3">
<div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_production_transfer]){  ?>
			   
			   <!---a href="production_transfer.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
               <button type="submit" name="updatePS" class="btn btn-success">Update PS Documents</button>
			   
			 <?php   } else {?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">Initiate Production Transfer</button>
               <?php } ?>
               </div></div>   
</td>

</tr><?php } ?>
</table>



               
               
               
               
                          
               
               
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

	 
	 
	 
if ($valid){
	if($qtys>0){
		
	$_SESSION[spinvoice]=$invoice;	
		
    $fgrate=getSVALUE("production_floor_receive_detail", "unit_price", " where item_id='$item_id' and batch='$_POST[batch]' order by id DESC LIMIT 1");
 if($fgrate<0){
	$fgrate=getSVALUE("production_floor_receive_detail", "unit_price", " where item_id='$item_id'  order by id DESC LIMIT 1");
 }
	 
	 
 
 $total_amt=$totalqtys*$fgrate;
 $productiondetails =mysql_query("INSERT INTO production_issue_detail
		(pi_no, pi_date,custom_pi_no, item_id, warehouse_from, warehouse_to, total_unit, unit_price, total_amt,lot, batch, mfg,status,ip,ISSUE_TYPE) VALUES 

('".$_SESSION[pi_no]."','".$inirow['pi_date']."','".$_SESSION[initiate_production_transfer]."','$_POST[item_id]','$inirow[warehouse_from]','$inirow[warehouse_to]','$totalqtys','$fgrate','".$total_amt."','','$_POST[batch]','$mfg','MANUAL','$ip','STO')");

  ?>
	
<?php }}} ?>
                   
                      




<?php

if($_SESSION[initiate_production_transfer]){

 ?>


<form action="" name="addem" id="addem" class="form-horizontal form-label-left" method="post">
               

 <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                   
                  

 <table style="width:100%" id="datatable-buttons" class="table table-striped table-bordered">
                      


                      <tbody>
                       <tr>
                        
                      <td style="width:20%" align="center">
                      
                      <select class="select2_single form-control" style="width:400px" tabindex="-1" required="required"  name="item_id" id="item_id" onchange="javascript:reloaditem(this.form)" >
                            <option></option>
                            
                            <?php if($_GET[item_id]){ ?>
                            <option value="<?php echo $_GET[item_id]; ?>" selected><?=$packsizeitem=getSVALUE("item_info", "item_name", " where item_id='$_GET[item_id]'");?></option><?php } ?>
                            <?php 
							$result=mysql_query("SELECT i.* FROM 
							item_info i
							where 
							1				 
							order by i.item_name");
							while($row=mysql_fetch_array($result)){  ?>
                  <option  value="<?php echo $row[item_id]; ?>"><?=$row[item_id]?> - <?php echo $row[finish_goods_code]; ?>-<?php echo $row[item_name]; ?> (<?=$packsizeitem=getSVALUE("item_sub_group", "sub_group_name", " where sub_group_id='$row[sub_group_id]'");?>)</option>
                    <?php } ?>
                          </select></td>
 
 <td style="width:5%" align="center"> 
 <input type="text" id="unit" style="width:80px; height:37px; font-weight:bold; text-align:center"   name="unit" placeholder="unit"  class="form-control col-md-7 col-xs-12" value="<?=$unitname=getSVALUE("item_info", "unit_name", " where item_id='$_GET[item_id]'")?>" >
 </td>

 
<td style="width:5%" align="center">
                        <select class="select2_single form-control" style="width:300px" tabindex="-1" required="required"  name="batch" >
                            <option>Select a Batch</option>
                            
                            <?php 
							$tda=date('Y-m-d');
							$result=mysql_query("SELECT distinct batch FROM production_floor_receive_detail where item_id='$_GET[item_id]'  order by batch DESC");
							while($rowlot=mysql_fetch_array($result)){ 
							$pks=getSVALUE("item_info", "pack_size", " where item_id='$_GET[item_id]'");
							///$lotstock=getSVALUE("production_floor_receive_detail", "SUM(total_unit)", " where pr_date between '2018-06-01' and '$tda' batch='$rowlot[batch]' and item_id='$_GET[item_id]'");
							
							$lotstock=getSVALUE("production_floor_receive_detail", "SUM(total_unit)", " where  batch='$rowlot[batch]' and item_id='$_GET[item_id]'");
							$lotex=getSVALUE("production_issue_detail", "SUM(total_unit)", " where batch='$rowlot[batch]' and item_id='$_GET[item_id]'");
							
							 ?>
                  <option  value="<?php echo $rowlot[batch]; ?>">Batch-<?php echo $rowlot[batch]; ?> (Stock - <?=($lotstock-$lotex)/$pks; ?>)</option>
                    <?php } ?>
                          </select></td>              
                      

   

                    
                     
                     
                     
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
								
							$results=mysql_query("Delete from production_issue_detail where id='$_GET[productdeletecode]'"); ?>
							<meta http-equiv="refresh" content="0;production_transfer.php">
	
								
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
                         <th style="width:10%; text-align:center">Qty</th>
                         <th style="width:15%; text-align:center">Options</th>

                        </tr>
                      </thead>


                      <tbody>
                       			
						
						 
				<?php 
				
				
				

				
				
			$results=mysql_query("Select * from production_issue_detail where   custom_pi_no='$_SESSION[initiate_production_transfer]'");
		    while($row=mysql_fetch_array($results)){ 
	
	        $unitprice=$row[unit_price];
			$packsizeitem=getSVALUE("item_info", "pack_size", " where item_id='$row[item_id]'");
			
				
				$i=$i+1;
				$ids=$row[id];
				$upqty=$_POST['qtyupdate'.$ids]*$packsizeitem;
				$total_amt=$upqty*$unitprice;
				$batchupdate=$_POST['batchupdate'.$ids];
				if(isset($_POST['deletedata'.$ids]))
				{
				mysql_query("DELETE FROM production_issue_detail WHERE id='$ids' and custom_pi_no='$_SESSION[initiate_production_transfer]'"); ?>
                <meta http-equiv="refresh" content="0;production_transfer.php?transfer_from=<?=$_GET[transfer_from]?>">
                <?php 
				}		
				
				if(isset($_POST['editdata'.$ids]))
				{
				mysql_query("UPDATE production_issue_detail SET total_unit='$upqty',batch='$batchupdate',total_amt='$total_amt' WHERE id='$ids' and custom_pi_no='$_SESSION[initiate_production_transfer]'");?>
                <meta http-equiv="refresh" content="0;production_transfer.php?transfer_from=<?=$_GET[transfer_from]?>">
				<?php }?>
				

                      <tr>
                        <td style="width:3%; vertical-align:middle"><?php echo $i; ?></td>
                        <td style="width:8%; vertical-align:middle"><?= $fgcode=getSVALUE("item_info", "finish_goods_code", " where item_id='$row[item_id]'");?></td>
                        
               <td style="vertical-align:middle"><?=$name=getSVALUE("item_info", "item_name", "where item_id='".$row['item_id']."'"); ?></td>
               <td style="vertical-align:middle; text-align:center"><?=$unit=getSVALUE("item_info", "unit_name", "where item_id='".$row['item_id']."'"); ?></td>
                        
                        
                        
                        
                        <td align="center" style="width:6%; text-align:center">
                        <input type="text" id="noofpackupdate<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  name="batchupdate<?php echo $ids; ?>"  value="<?php echo $row[batch]; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                        
                        
                      
                        
                        <td align="center" style="width:6%; text-align:center">
                        <input type="text" id="qtyupdate<?php echo $ids; ?>" style="width:90px; margin-left:1%; height:37px; font-weight:bold; text-align:center; float:none"  required="required"  name="qtyupdate<?php echo $ids; ?>"  value="<?=$tproduction=$row[total_unit]/$packsizeitem; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off" ></td>
                       
                    
                  <td align="center" style="width:10%;vertical-align:middle">
                  <button type="submit" name="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Update?");'><img src="update-icon.png" style="width:25px;  height:25px"></button>
                  
                  
                   <button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:25px;  height:25px"></button>
                    </td>
                    
                    </tr>
                        
                        
                     
                        <?php
						
						if (isset($_POST['confirmsave'])){
							
							
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
							
							
							
$transferfromLedger=$name=getSVALUE("warehouse", "ledger_id", "where warehouse_id='".$row[warehouse_from]."'");							
							
							
							
						$item_journal =mysql_query("INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, item_ex, item_price, tr_from,tr_no,sr_no,entry_by,entry_at,custom_no,batch,ip) VALUES 
('$row[pi_date]','$row[item_id]','$row[warehouse_from]','$row[warehouse_to]','$row[total_unit]','$row[unit_price]','ProductionTransfer','$row[id]','$_SESSION[pi_no]','".$_SESSION[userid]."','$enat','$_SESSION[initiate_production_transfer]','$row[batch]','$ip')");

$TRItemValue=$row[total_unit]*$row[unit_price];
$TRItemValueTotal=$TRItemValueTotal+$TRItemValue;

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
					VALUES ('', '$jv', '$date', '".$transferfromLedger."', 'FG Transfer, STONO#$_SESSION[initiate_production_transfer]', '".$TRItemValueTotal."','', 'ProductionTransfer','', '$_SESSION[pr_no]','$tr_no_customProduction','', '$cc_code','".$_SESSION['userid']."','".$_SESSION['user']['group']."','$tdates','$ip','$_SESSION[initiate_production_transfer]')";					
				$query_journal = mysql_query($journal);	



// fg  transfer to code start from here
$item_journal =mysql_query("INSERT INTO cycle_journal_item (ji_date, item_id, warehouse_id, relevant_warehouse,item_in, item_ex, item_price, final_stock, tr_from,tr_no,sr_no,entry_by,entry_at,tr_no_custom,batch) VALUES 
('$row[pi_date]','$row[item_id]','$row[warehouse_from]','$row[warehouse_to]','$row[total_unit]','','$row[unit_price]','$final_stock','ProductionTransfer','$row[id]','".$_SESSION[pi_no]."','".$_SESSION[userid]."','$enat','$_SESSION[initiate_production_transfer]','$row[batch]')");


$transitLedger='1007003000050000';
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
					VALUES ('', '$jv', '$date', '$transitLedger', 'FG Transfer, STONO#$_SESSION[initiate_production_transfer]', '','".$TRItemValueTotal."', 'ProductionTransfer','', '$_SESSION[pr_no]','$tr_no_customProduction','','$cc_code','".$_SESSION['userid']."','".$_SESSION['user']['group']."','$tdates','$ip','$_SESSION[initiate_production_transfer]')";					
				$query_journal = mysql_query($journal);							
						}
						 
						$tp=$tp+$tproduction;
						} ?>
                        
                        
                      
                      </tbody>
                      
               
                      
                  
 <tr>
                      
                      
                      <td colspan="5" style="font-weight:bold; font-size:14px" align="right">Total STO QTY</td>           
                     
                      <td style="text-align:center"><strong><?php echo $tp; ?></strong></td>
                     
                      <td align="center" ></td>
                      
                      </tr>
                      
                      
 
 					

                      
                       
                      
                     
                    
                    
                     
                  
                  
                  
                      
                      
                      
                      <tr>
                      <td colspan="9" style="text-align:center">
                     
                        
                        <?php
			
if (isset($_POST['confirmsave'])){
$valid = true;  
mysql_query("UPDATE production_issue_master SET verifi_status='UNCHECKED' WHERE custom_pi_no='$_SESSION[initiate_production_transfer]'");
mysql_query("UPDATE production_issue_detail SET verifi_status='UNCHECKED' WHERE custom_pi_no='$_SESSION[initiate_production_transfer]'");
unset($_SESSION['initiate_production_transfer']); 
unset($_SESSION["pi_no"]);

  ?>
	
<meta http-equiv="refresh" content="0;production_transfer.php">
<?php } ?>			 
                        
                        
                       <?php 
					   
					   $cancel=$_POST[cancel];
					   
					   if(isset($cancel)){
					  $delete=mysql_query("Delete from production_issue_master where custom_pi_no='$_SESSION[initiate_production_transfer]'");
  
  
$deletes=mysql_query("Delete From production_issue_detail where custom_pi_no='$_SESSION[initiate_production_transfer]'");
					   
					   unset($_SESSION["initiate_production_transfer"]);
					   unset($_SESSION["pi_no"]);
					   
					   
					   ?>
                       <meta http-equiv="refresh" content="0;production_transfer.php">

                       <?php } ?>
                          
                          <button type="submit" name="cancel" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete MAN?");' class="btn btn-primary">Delete STO </button>
                          <button type="submit" onclick='return window.confirm("Are you want to Finished?");' name="confirmsave" class="btn btn-success">Confirm and Finish STO </button>
                          
                          
                       
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
