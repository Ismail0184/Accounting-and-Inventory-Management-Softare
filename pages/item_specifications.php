<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
 $title='Item Specifications';


$item_master = find_all_field('item_info','','item_id='.$_GET['item_id']);


?>
<?php
$initiate=$_POST[initiate];

$d =$_POST[ps_date];
$ps_date=date('Y-m-d' , strtotime($d));
$invoice=$_POST[invoice];
$billno=$_POST[billno];
$enat=date('Y-m-d h:s:i');
if(isset($initiate)){

    $insert=mysql_query("INSERT INTO item_SPECIFICATION (item_id,TEST_PARAMETERS,RESULT,SPECIFICATION,entry_by,entry_at,ip)  VALUES ('$_POST[item_id]','$_POST[TEST_PARAMETERS]','$_POST[RESULT]','$_POST[SPECIFICATION]','$_SESSION[userid]','$enat','$ip')");

    $_SESSION[initiate_daily_production]=$invoice;
    $_SESSION[pr_no] =getSVALUE("production_floor_receive_master", "pr_no", " where custom_pr_no='$_SESSION[initiate_daily_production]'");
    ; ?>
    <meta http-equiv="refresh" content="0;item_specifications.php?item_id=<?php echo $_GET[item_id]; ?>">
<?php }


if(isset($_POST[Finish])){ ?>
    <meta http-equiv="refresh" content="0;item_specifications.php">
<?php } ?>





<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.item_id.options[form.item_id.options.selectedIndex].value;
	self.location='item_specifications.php?item_id=' + val ;
}</script>
<?php require_once 'body_content.php'; ?>
             <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                          <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                              <thead>
                              <tr style="background-color: bisque">
                                  <th style="text-align: center">Item Name</th>
                                  <th style="text-align: center">In Stock</th>
                                  <th style="text-align: center">D Price</th>
                                  <th style="text-align: center">Unit Price</th>
                                  <th style="text-align: center">Invoice Qty</th>
                                  <th style="text-align: center">Unit Amount</th>
                                  <th style="text-align: center">Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td style="vertical-align: middle">
                                      <select class="select2_single form-control" onchange="javascript:reload(this.form)" style="width:400px" tabindex="-1" required="required"  name="item_id" id="item_id" >
                                              <option></option>
                                              <? advance_foreign_relation(find_all_item($product_nature="'Purchasable','Both'"),($_GET[item_id]>0)? $_GET[item_id] : $edit_value->item_id);?>
                                          </select>
                                  </td>
                                  <td><?=$item_master->unit_name;?></td>
                                  <td>
                                  <select class="select2_single form-control"  style="width:400px" tabindex="-1" required="required"  name="TEST_PARAMETERS" id="TEST_PARAMETERS" >
                                      <option></option>
                                      <?=foreign_relation('PARAMETERS', 'id', 'concat(PARAMETERS_CODE," : ", PARAMETERS_Name)',$do_type, '1'); ?>
                                  </select>

                      </div>  
	                </div>     
                    
                 
                      
                      
                      
                      

                      
                      
        <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">RESULT<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            <input type="text" id="RESULT" style="width:400px"   name="RESULT" value="<?php if($_SESSION[initiate_daily_production]){ echo$inirow[remarks]; } else { echo ''; } ?>" class="form-control col-md-7 col-xs-12" >

                      </div>  
	                </div>
                    
                    
                    
                    <div class="form-group">
                   
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">SPECIFICATION<span class="required">*</span></label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
	                	
	            
                <textarea name="SPECIFICATION" id="SPECIFICATION" style="height:100px; width:400px"></textarea>

                      </div>  
	                </div>
        
               
                        
                       
               
               
               <div class="form-group" style="margin-left:40%">
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <?php if($_SESSION[initiate_daily_production]){  ?>
			   
			   <!---a href="daily_production.php" style="font-size:20px; font-weight:bold">Refresh page</a--->
               <button type="submit" name="updatePS" class="btn btn-success">Update PS Documents</button>
			   
			 <?php   } else {?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">SPECIFICATION ADD</button>
               
               
               
               <br><br>
               
               <button type="submit" name="Finish" onclick='return window.confirm("Are you confirm?");' class="btn btn-success">SPECIFICATION ADD FINISHED</button>

               <?php } ?>
               </div></div></form></div></div></div>
            
           
              
   



 <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2><?php echo 'Item Specifications' ; ?></h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                                      <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">

                  <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%;">
                   <thead>
                    <tr>
                     <th style="width: 2%">#</th>
                     <th style="width:4%">ITEM CODE</th>
                     <th style="width:8%">ITEM DESCRIPTION</th>
                     <th style="width:8%">PARAMETERS</th>
                     <th style="width:8%">SPECIFICATION</th>
                     <th style="width:5%; text-align:center">OPTIONS</th>
              
                     </tr>
                     </thead>





                      <tbody>






<?php
if($_GET[item_id]) {
$resultss=mysql_query("Select * from item_SPECIFICATION where item_id='$_GET[item_id]' order by id  ");
} else {
	$resultss=mysql_query("Select * from item_SPECIFICATION where 1 order by item_id,id ");
	}
while ($rows=mysql_fetch_array($resultss)){
	$i=$i+1;
	
	
	$ids=$rows[id];
	$SPECIFICATION=$_POST['SPECIFICATION'.$ids];
				
				if(isset($_POST['editdata'.$ids]))
				{
				mysql_query("UPDATE item_SPECIFICATION SET SPECIFICATION='$SPECIFICATION' WHERE item_id='$_GET[item_id]' and  id='$ids'"); ?>
                <meta http-equiv="refresh" content="0;item_specifications.php?item_id=<?php echo $_GET[item_id]; ?>">
                <?php 
				}
				
				
				if(isset($_POST['deletedata'.$ids]))
				{
				mysql_query("DELETE FROM item_SPECIFICATION WHERE item_id='$_GET[item_id]' and  id='$ids'"); ?>
                <meta http-equiv="refresh" content="0;item_specifications.php?item_id=<?php echo $_GET[item_id]; ?>">
                <?php 
				}	

$link='#';

?>



                      <tr style="font-size:12px">

                        
                        <th style="text-align:center"><?php echo $i; ?></th>
                        <td><a href="<?php echo $link; ?>"><?php echo $rows[item_id]; ?></a></td>
                        <td><?=$nam=getSVALUE("item_info", "item_name", " where item_id='$rows[item_id]'");?></td>
                        <td><a href="<?php echo $link; ?>"><?=$PARAMETERS=getSVALUE("PARAMETERS", "PARAMETERS_Name", " where PARAMETERS_CODE='$rows[TEST_PARAMETERS]'");?></a></td>
                        <td><textarea  name="SPECIFICATION<?php echo $ids; ?>" style="width:100%" id="SPECIFICATION<?php echo $ids; ?>"><?php echo $rows[SPECIFICATION]; ?></textarea></td>
                        
                        <td style="text-align:center">
                        
                         <button type="submit" name="editdata<?php echo $ids; ?>" id="editdata<?php echo $ids; ?>" style="background-color:transparent; border:none;" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Update?");'><img src="update-icon.png" style="width:25px;  height:25px"></button>
                         
                         <button type="submit" name="deletedata<?php echo $ids; ?>" id="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none; margin-left:20px" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:20px;  height:20px"></button>
                          
                          
                          
                          
                          
                         </td>
                        
                        </tr>
<?php } ?></tbody></table></form>

       </div></div></div>









              
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        
        <!-- /footer content -->
      </div>
    </div>



<?=$html->footer_content();mysqli_close($conn);?>
<?php ob_end_flush();
ob_flush(); ?>