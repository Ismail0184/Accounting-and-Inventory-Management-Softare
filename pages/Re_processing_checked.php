<?php

require_once 'support_file.php';
$title='Re-processing Checked';
$now=time();
$unique='pi_no';
$table="re_processing_master";
$page="Re_processing_checked.php";
$crud      =new crud($table);
$$unique = $_GET[$unique];
$rp_master=find_all_field('re_processing_master','','pi_no='.$_GET[$unique].'');
$table_QC_fg_screp='QC_fg_screp';
$table_QC_FG_forwarded='QC_FG_forwarded';
$table_QC_Inv_gain_due_to_scrap_Master='QC_Inv_gain_due_to_scrap_Master';
$table_QC_Inv_gain_due_to_scrap_details='QC_Inv_gain_due_to_scrap_details';
$table_production_floor_receive_master='production_floor_receive_master';
$table_production_floor_receive_detail='production_floor_receive_detail';
$PS=automatic_number_generate("PS","production_floor_receive_master","custom_pr_no","create_date='".date('Y-m-d')."' and custom_pr_no like '$sekeyword%'");


$createdate=date('Y-m-d');

if(prevent_multi_submit()){
$resultss=mysqli_query($conn, "Select p.id,p.*,i.* from re_processing_detail p, item_info i where p.item_id=i.item_id and p.ISSUE_TYPE='STO' and p.".$unique."=".$_GET[$unique]." order by p.".$unique." DESC ");
while ($rows=mysqli_fetch_array($resultss)){
	
    $id=$rows[id];	 	 
	$_POST[screp_qty]=$rows[total_unit];
	$_POST[send_qty]=$rows[total_unit];
	$_POST['entry_by'] = $_SESSION['userid'];
    $_POST['entry_at'] = date('Y-m-d H:s:i');
	$_POST['create_date']=date('Y-m-d');
	$_POST[screp_no]=automatic_number_generate("",QC_fg_screp,screp_no,"create_date='$idatess'");
	$_POST[FG_store_no]=automatic_number_generate("",QC_fg_screp,screp_no,"create_date='$idatess'");
		
	
	
/// insert into  fg_screp
	if(isset($_POST['ford_status'.$id])){		
		if($_POST['scrap_'.$id]>0){		
		$_POST[item_id]= $rows[item_id];
		$_POST[batch_for]=$rows[batch];
		$_POST[screp_qty]=$_POST['scrap_'.$id];				
		$crud      =new crud($table_QC_fg_screp);
        $crud->insert();
	    screp_nocreate();
		}
		
/// insert into  FG_forwarded		
		if($_POST['fg_'.$id]>0){			
		$_POST[item_id]= $rows[item_id];
		$_POST[batch_for]=$_POST['batchFG'.$id];	
		$_POST[FG_qty]=$_POST['fg_'.$id];		
		$crud      =new crud($table_QC_FG_forwarded);
        $crud->insert();
		}}
		 if(isset($_POST['deletedata_'.$rows[item_id]])){
			$del=mysqli_query($conn, "Delete from ".$table_QC_fg_screp." where ".$unique."=".$$unique." and item_id=".$rows[item_id]."");
			$del2=mysqli_query($conn, "Delete from ".$table_QC_FG_forwarded." where ".$unique."=".$$unique." and item_id=".$rows[item_id]."");
		 }
		 }
		
		
       if(isset($_POST[initiate])){	
       $_POST[warehouse_id] = $_POST[warehouse_id];
       $_POST[status] = 'UNCHECKED';
	   $_POST[sto_no] = $_GET[custom_pi_no];   
	   $_POST[date] = date('Y-m-d');  
       $crud      =new crud($table_QC_Inv_gain_due_to_scrap_Master);
       $crud->insert();	   
	   
	   $_POST[warehouse_from] = $_POST[warehouse_id];
	   $_POST[warehouse_to] = $_POST[warehouse_id];
	   $_POST[custom_pr_no] = $PS;
       $_POST[status] = 'UNCHECKED'; 
	   $_POST[remarks] = 'Re-processing Production, ID#'.$_GET[custom_pi_no]; 
	   $_POST[p_type] = 'Re-processing'; 
	   $_POST[production_for]='Local';	   
       //$crud      =new crud($table_production_floor_receive_master);
      // $crud->insert();
	   
	   $_SESSION['psGET']=$PS;
       $_SESSION['QC_Inv_gain_due_to_scrap_details']='1';	   
       }

if(isset($_POST[gainscrapAdd])){
			
	$psGET=$_SESSION['psGET'];
	$_POST[pi_no]=$_GET[pi_no];
	$_POST[sto_no]=$_GET[custom_pi_no];
	$_POST[warehouse_id]=$_POST[warehouse_id];
	$_POST[item_id]=$_POST[SGitem_id];
	$_POST[batch_for]=$_POST[Sgbatch];
	$_POST[qty]=$_POST[Sgqty];
	$_POST[rate]=getSVALUE('item_landad_cost','landad_cost','where status="Active" and item_id='.$_POST[SGitem_id]);
	$_POST[amout]=$_POST[qty]*$_POST[rate];	
	$_POST[entry_by]=$_SESSION[userid];
	$_POST[entry_at]=date('Y-m-d H:s:i');
	$_POST[ip]=$ip;		
	$crud      =new crud($table_QC_Inv_gain_due_to_scrap_details);
    $crud->insert();	
	
	
	
	
	
	$_POST[custom_pr_no]=$_SESSION['psGET'];
	$_POST[pr_date]=date('Y-m-d');
	$_POST[warehouse_from]=$_GET[warehouse_id];
	$_POST[warehouse_to]=$_GET[warehouse_id];
	$_POST[total_unit]=$_POST[Sgqty];
	$_POST[unit_price]=$_POST[rate];
	$_POST[total_amt]=$_POST[total_unit]*$_POST[unit_price];
	$_POST[status]='UNCHECKED';
	$_POST[ip]=$ip;

    $crud      =new crud($table_production_floor_receive_detail);
    //$crud->insert();
	
	
	
	
	

	}
	
	if(isset($_POST[gainscrapSKIP])){
mysql_query("DELETE FROM QC_Inv_gain_due_to_scrap_details WHERE sto_no='$_GET[custom_pi_no]'");
mysql_query("DELETE FROM QC_Inv_gain_due_to_scrap_Master WHERE sto_no='$_GET[custom_pi_no]'");
$_SESSION['QC_Inv_consumption_due_to_FG_reprocess']='1';
mysql_query("INSERT INTO production_floor_issue_master VALUES ('','$createdate','Re-processing Production, ID#$_GET[custom_pi_no]','$_GET[warehouse_id]','$_GET[warehouse_id]','','','UNCHECKED','$enat','$userid','Local','$_SESSION[psGET]')");
unset($_SESSION["QC_Inv_gain_due_to_scrap_details"]);
}	




	
	} // prevent multi submit
	
	//for Delete..................................
    if(isset($_POST['gainscrapCANCEL']))
    {   $crud = new crud($table_QC_Inv_gain_due_to_scrap_details);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);
        $crud = new crud($table_QC_Inv_gain_due_to_scrap_Master);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);
        unset($_SESSION['QC_Inv_gain_due_to_scrap_details']);
        unset($_SESSION['psGET']);
        unset($_POST);
    }


?>



<?php require_once 'header_content.php'; ?>
<script type="text/javascript">
    function DoNavPOPUP(lk)
    {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
</script>

<SCRIPT language=JavaScript>
    function reloaditem(form)
    {   var val=form.FGitem_id.options[form.FGitem_id.options.selectedIndex].value;
        self.location='<?=$page;?>?pi_no=' + '<?php echo $_GET[pi_no]; ?>&custom_pi_no=' + '<?php echo $_GET[custom_pi_no]; ?>&warehouse_id=' + '<?php echo $_GET[warehouse_id]; ?>' + '&FGitem_id=' + val;
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php if(isset($_GET[$unique])){ 
 require_once 'body_content_without_menu.php'; } else {  
 require_once 'body_content.php'; } ?>




                                
                                



<?php if($_GET[$unique]) { ?>   
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
               <h2><?=$title;?></h2>
                <div class="clearfix"></div>
                </div> 
                <div class="x_content">
 <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
           <input type="hidden"  name="pi_no" id="pi_no" value="<?=$$unique;?>" />
           <input type="hidden"  name="STO_No_custom" id="STO_No_custom" value="<?=$_GET[custom_pi_no];;?>" />
           <input type="hidden"  name="warehouse_from" id="warehouse_from" value="<?=$rp_master->warehouse_from;?>" />
           <input type="hidden"  name="warehouse_id" id="warehouse_id" value="<?=$rp_master->warehouse_to;?>" />
           <input type="hidden"  name="pr_date" id="pr_date" value="<?=date('Y-m-d');?>" />
           <input type="hidden"  name="create_date" id="create_date" value="<?=date('Y-m-d');?>" />
           <input type="hidden"  name="section_id" id="section_id" value="<?=$_SESSION[sectionid];?>" />
           <input type="hidden"  name="company_id" id="company_id" value="<?=$_SESSION[companyid];?>" />
                   <table class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                   <thead>
                   <tr style="background-color: bisque">
                     <th style="width: 2%">#</th>
                     <th>FG Code</th>
                     <th>FG Description</th>
                     <th style=" text-align:center">Qty</th>
                     <th style="text-align:center">Scrap (Pcs)</th>
                     <th style="text-align:center">FG (Pcs)</th>
                     <th style="text-align:center">Batch</th>
                     <th style="text-align:center">Option</th>
                     </tr>
                     </thead>

                      <tbody>


<?php

$enat=date('Y-m-d h:s:i');
$createdate=date('Y-m-d');
$userid=$_SESSION['userid'];











$resultss=mysqli_query($conn, "Select p.id,p.*,i.* from re_processing_detail p, item_info i where p.item_id=i.item_id and p.ISSUE_TYPE='STO' and p.".$unique."=".$_GET[$unique]." order by p.pi_no DESC ");
while ($rows=mysqli_fetch_array($resultss)){
     $id=$rows[id];
	
$scrpreceived=getSVALUE("QC_fg_screp", "SUM(screp_qty)", "where ".$unique."='".$_GET[$unique]."' and item_id='".$rows['item_id']."'");
$fgreceived=getSVALUE("QC_FG_forwarded", "SUM(FG_qty)", "where ".$unique."='".$_GET[$unique]."' and item_id='".$rows['item_id']."'");
$totalreceived=$scrpreceived+$fgreceived;	
$pkisze=getSVALUE("item_info", "pack_size", "where item_id='".$rows['item_id']."'");
	

	$scrap=$_POST['scrap'.$id];
	$fg=$_POST['fg'.$id];
	$batchFG=$_POST['batchFG'.$id];	
	$scrapno=automatic_number_generate("",QC_fg_screp,screp_no,"create_date='$idatess'");
	$scrapqty=$rows[total_unit];
	$scraps=$scrap;
	$FGQty=$fg;
	
	
	
	
	
	
$psiz=getSVALUE("item_info", "pack_size", "where item_id='".$rows['item_id']."'");









if(isset($_POST[gainscrapSKIP])){	
$psGET=$_SESSION['psGET'];


mysql_query("INSERT INTO production_floor_receive_detail VALUES ('','','$psGET','$createdate','$rows[item_id]','$_GET[warehouse_id]','$_GET[warehouse_id]','$fgreceived','','','UNCHECKED','','$rows[batch]','','','','$ip')");
}


?>








                      <tr style="font-size:11px">
                        <th style="text-align:center; vertical-align:middle" valign="middle"><?=$i=$i+1;; ?></th>
                          <td style="vertical-align:middle"><?=$rows[finish_goods_code];?></td>
                        <td style="vertical-align:middle" valign="middle"><?=$rows[item_name];?></td>
                        <td style="text-align:right; vertical-align:middle" valign="middle"><?=$rows[total_unit];?></td>
                          <td style="text-align:center; vertical-align:middle">
                        <input type="hidden" name="total_unit_<?=$id?>" id="total_unit_<?=$id?>"  value="<?=$rows[total_unit];?>" class="total_unit<?=$id?>" >
                        <input type="text"  value="<?=$scrpreceived;?>"   style="font-size:11px;float:left; width:48%"  readonly class="form-control col-md-7 col-xs-12" >
            
            
            <?php if($scrapqty==$totalreceived || $scrapqty<$totalreceived){ ?>
            <input type="text"  value="Done"  placeholder="Scrapdone" style="font-size:11px;text-align:center; width:48%;float:right;  font-weight:bold; color:green"  readonly class="form-control col-md-7 col-xs-12" >
			<?php } else {?>
            <input type="text" id="scrap_<?=$id?>"  name="scrap_<?=$id?>"  placeholder="Add Scrap" style="font-size:11px;text-align:center; float:right;width:48%; height:35px"  class="scrap_<?=$id?>" onkeyup="doAlert_scrap_<?=$id?>(this.form);"><?php } ?></td>
            
            
           
           <td style="text-align:center; vertical-align:middle">  
            <input type="text"  value="<?=$fgreceived;?>"  placeholder="Add Scrap" style="font-size:11px;float:left;text-align:center; width:48%"  readonly class="form-control col-md-7 col-xs-12" >
            <?php if($scrapqty==$totalreceived || $scrapqty<$totalreceived){ ?>  
            <input type="text"  value="Done"  placeholder="Scrapdone" style="font-size:11px;text-align:center;float:right; width:48%; font-weight:bold; color:green"  readonly class="form-control col-md-7 col-xs-12" >
            <?php } else {?>
            <input type="text" id="fg_<?=$id?>"   name="fg_<?=$id?>"  placeholder="Add FG" style="font-size:11px; text-align:center;width:48%;float:right; height:35px"  class="fg_<?=$id?>" onkeyup="doAlert_fg_<?=$id?>(this.form);">
            <?php } ?> 
            </td>
            
            <script>
        $(function(){
            $('#fg_<?=$id?>').keyup(function(){
                var total_unit_<?=$id?> = parseFloat($('#total_unit_<?=$id?>').val()) || 0;
                var fg_<?=$id?> = parseFloat($('#fg_<?=$id?>').val()) || 0;
                $('#scrap_<?=$id?>').val((total_unit_<?=$id?> - fg_<?=$id?>).toFixed(2));
            });
        });
    </script>
    <SCRIPT language=JavaScript>
            function doAlert_scrap_<?=$id?>(form)
            {
                var val=form.scrap_<?=$id?>.value;
                var val2=form.total_unit_<?=$id?>.value;
                if (Number(val)>Number(val2)){
                    alert('Oops !! Exceeded the receive limit !! Thanks');
                    form.scrap_<?=$id?>.value='';
                }
                form.scrap_<?=$id?>.focus();
            }</script>
            
            <SCRIPT language=JavaScript>
            function doAlert_fg_<?=$id?>(form)
            {
                var val=form.fg_<?=$id?>.value;
                var val2=form.total_unit_<?=$id?>.value;
                if (Number(val)>Number(val2)){
                    alert('Oops !! Exceeded the receive limit !! Thanks');
                    form.fg_<?=$id?>.value='';
                }
                form.fg_<?=$id?>.focus();
            }</script>
           
           <td style="text-align:center; vertical-align:middle">                	
	       <input type="text" id="batchFG<?=$id?>"   name="batchFG<?=$id?>"  placeholder="Batch" style="font-size:11px; float:left; text-align:center;width:98%" class="form-control col-md-7 col-xs-12" ></td>        
           
           
           <td style="text-align:center; vertical-align:middle">
           <?php if($scrapqty==$totalreceived || $scrapqty<$totalreceived){?>
		   <button type="submit" name="deletedata_<?=$rows[item_id];?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button>
		   <?php } else {?>
           <button type="submit" id="ford_status<?=$id;?>" name="ford_status<?=$id;?>" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px" class="btn btn-primary">Add</button>
           <?php } ?>
           
           </td>
           </tr>
           <?php } ?>
                      </tbody></table>

           <?php if($_SESSION['QC_Inv_gain_due_to_scrap_details']!=='1' && $_SESSION['QC_Inv_consumption_due_to_FG_reprocess']!=='1'){  ?>
               <button type="submit" name="initiate" onclick='return window.confirm("Are you confirm?");' style="font-size: 12px; margin-left: 40%" class="btn btn-primary">Go Ahead for Further Process</button>
            <?php } ?>
           </form></div></div></div>
<?php } ?>                             
                                
                                
                                
                                

                                
                                
     
        
        
<?php if($_SESSION['QC_Inv_gain_due_to_scrap_details']){?>     
               <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Inventory Gain Due to Scrap</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                  <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                   <table class="table table-striped table-bordered" style="width:100%; font-size: 11px">                   
                   <tr>
                   <td style="width: 50%">
                       <select class="select2_single form-control" style="width: 100%" tabindex="-1"  name="SGitem_id" id="SGitem_id" >
                           <option></option>
                           <?
                           $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g,
							production_ingredient_detail pid,
							re_processing_detail rpd
							WHERE   
							i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id	and
							 i.item_id=pid.raw_item_id and 
							 rpd.item_id=pid.item_id
							 group by pid.raw_item_id  
							  order by i.item_name";
                           advance_foreign_relation($sql_item_id,$SGitem_id);?>
                       </select>
                   </td>
                   <td style="vertical-align:middle">                	
	       <input type="text" id="Sgbatch"   name="Sgbatch"  placeholder="Batch / Lot" style="font-size:11px; text-align:center;width:100px" class="form-control col-md-7 col-xs-12" ></td>
                   <td style="vertical-align:middle">                	
	       <input type="text" id="Sgqty"   name="Sgqty"  placeholder="Qty" style="font-size:11px; text-align:center;width:100px" class="form-control col-md-7 col-xs-12" ></td>
                   <td valign="middle" style="vertical-align:middle"><button type="submit" id="gainscrapAdd" name="gainscrapAdd" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary" style="font-size: 12px">Add</button></td>
                   </tr></table>
                   
                   
                   
                   <table class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                   <tbody>
                       <?php
                       $query=mysqli_query($conn, "SELECT * FROM QC_Inv_gain_due_to_scrap_details WHERE sto_no='$_GET[custom_pi_no]'");
                       while($SPROW=mysql_fetch_array($query)){
                           if(isset($_POST['deletedata'.$SPROW[id]]))
                           {
                               mysql_query("DELETE FROM QC_Inv_gain_due_to_scrap_details WHERE id='$SPROW[id]'"); ?>
                               <meta http-equiv="refresh" content="0;Re_processing_checked.php?warehouse_id=<?=$_GET[warehouse_id]?>&custom_pi_no=<?=$_GET[custom_pi_no]?>">
                               <?php
                           }
                           ?>
                           <tr style="font-size:12px">
                               <td style="vertical-align:middle"><?=$itemname=getSVALUE("item_info", "item_name", " where item_id='$SPROW[item_id]'");?></td>
                               <td style="vertical-align:middle; text-align:center" valign="middle"><?=$itemname=getSVALUE("item_info", "unit_name", " where item_id='$SPROW[item_id]'");?></td>
                               <td style="vertical-align:middle; text-align:center" valign="middle"><?=$SPROW[batch_for]?></td>
                               <td style="text-align:right;vertical-align:middle; text-align:center" valign="middle"><?=$SPROW[qty]?></td>
                               <td style="text-align:center; vertical-align:middle"><button type="submit" name="deletedata<?php echo $SPROW[id]; ?>" id="deletedata<?php echo $SPROW[id]; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:25px;  height:25px"></button></td>
                           </tr>
                       <?php } ?>
                       </tbody></table>



               <div class="form-group">
               <button type="submit" name="gainscrapCANCEL" onclick='return window.confirm("Are you confirm?");' class="btn btn-danger" style="font-size: 12px">Cancel</button>
               <button type="submit" name="gainscrapSKIP" onclick='return window.confirm("Are you confirm?");' class="btn btn-success"style="font-size: 12px; float: right" >GO Ahead for Further Process</button>
               </div></form>
                  </div></div></div>


 <?php } elseif($_SESSION['QC_Inv_consumption_due_to_FG_reprocess']){?>   


                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Inventory Consumption Due to FG Re-process</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                  <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                  
                  
    <?php 
    if(isset($_POST[CONgainscrapAdd])){
	$_SESSION['pi_no']=getSVALUE("production_floor_issue_master", "pi_no", "where production_for_pr_no='".$_SESSION[psGET]."'");
	$pino=$_SESSION['pi_no'];
	mysql_query("INSERT INTO production_floor_issue_detail VALUES ('','$pino','$createdate','$_POST[CONitem_id]','$_GET[warehouse_id]','$_GET[warehouse_id]','','$_POST[CONSgqty]','','','UNCHECKED','$_SESSION[psGET]','$_POST[FGitem_id]','')"); } ?>

                   <table align="center" style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                       <thead>
                       <tr style="background-color: bisque">
                           <th style="text-align: center">Finish Goods</th>
                           <th style="text-align: center">Raw Material</th>
                           <th style="text-align: center">Lot No</th>
                           <th style="text-align: center">Consumption Qty</th>
                           <th style="text-align: center"></th>
                       </tr>
                       </thead>


                   <tr>
                   <td style="width: 40%">
                       <select class="select2_single form-control" style="width: 100%" tabindex="-1" name="FGitem_id" id="FGitem_id" onchange="javascript:reloaditem(this.form)">
                           <option></option>
                           <?
                           $sql_item_id="SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g, 
							re_processing_detail d
							WHERE  d.item_id=i.item_id and i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id	and 
                             d.pi_no=".$_GET[pi_no]."
							  order by i.item_name";
                           advance_foreign_relation($sql_item_id,$_GET[FGitem_id]);?>
                       </select>
                   </td>
                   
                   
                   
                    <td style="width: 30%">
                 <select class="select2_single form-control" style="width:300px" tabindex="-1"   name="CONitem_id" >
                 <option></option>
                            <?php
                            $sql_item_id = "SELECT i.item_id,concat(i.item_id,' : ',i.finish_goods_code,' : ',i.item_name,' (',sg.sub_group_name,')') FROM  item_info i,
							item_sub_group sg,
							item_group g
							WHERE
							i.sub_group_id=sg.sub_group_id and 
							 sg.group_id=g.group_id	and 
                             i.sub_group_id not in ('200010000')
							  order by i.item_name";
                            advance_foreign_relation($sql_item_id, $CONitem_id); ?>
                          </select>
                   </td>
                 
                 
                   <td><div class="col-md-6 col-sm-6 col-xs-12">	                	
	       <input type="text" id="Sgbatch"   name="Sgbatch"  placeholder="Batch / Lot" style="font-size:11px; text-align:center;width:100px" class="form-control col-md-7 col-xs-12" ></div></td>
                   <td><div class="col-md-6 col-sm-6 col-xs-12">	                	
	       <input type="text" id="CONSgqty"   name="CONSgqty"  placeholder="Qty" style="font-size:11px; text-align:center;width:100px" class="form-control col-md-7 col-xs-12" ></div></td>
                   <td><button type="submit" id="CONgainscrapAdd" name="CONgainscrapAdd"  class="btn btn-primary" style="font-size: 12px">Add</button></td>
                   </tr> </thead></table></form></div></div></div>






    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
    <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
        <thead>
        <tr style="background-color: bisque">
            <th style="text-align: center">SL</th>
            <th style="text-align: center">Finish Goods</th>
            <th style="text-align: center">Raw Material</th>
            <th style="text-align: center">Lot No</th>
            <th style="text-align: center">Consumption Qty</th>
            <th style="text-align: center"></th>
        </tr>
        </thead>


                      <tbody>
                      <?php 
				$query=mysqli_query($conn,"SELECT pid.*,i.*,ri.item_name as material_name FROM 
                production_floor_issue_detail pid,item_info i,item_info ri WHERE 
                pid.fg_id=i.item_id and 
                pid.item_id=ri.item_id and 
                pid.production_for_pr_no='$_SESSION[psGET]' order by pid.fg_id,pid.item_id ");
			    while($CONROW=mysqli_fetch_array($query)){
					
					
				if(isset($_POST[ALLCONFIRM])){	
	           $item_journal =mysqli_query($conn, "INSERT INTO journal_item (ji_date, item_id, warehouse_id, relevant_warehouse, pre_stock, pre_price, item_in, item_ex, item_price, total_amt, tr_from,tr_no,sr_no,entry_by,entry_at,consumption_for_fg,ip,batch,custom_no) VALUES 
               ('$createdate','$CONROW[item_id]','$CONROW[swarehouse_to]','','','','','$CONROW[total_unit]','$comprice','$conamount','Consumption','$tr_no','$_SESSION[pr_no]','$_SESSION[userid]','$enat','$CONROW[fg_id]','$ip','$row[batch_for]','$_SESSION[psGET]')"); ?>
		<meta http-equiv="refresh" content="0;production_checked.php">
	<?php }	
					
					
					
					
				if(isset($_POST['CONdeletedata'.$CONROW[id]]))
				{
				mysql_query("DELETE FROM production_floor_issue_detail WHERE id='$CONROW[id]'"); ?>
<meta http-equiv="refresh" content="0;Re_processing_checked.php?warehouse_id=<?=$_GET[warehouse_id]?>&custom_pi_no=<?=$_GET[custom_pi_no]?>">
                <?php  }  ?>
                
                
                
                
                      <tr>
                      <th style="text-align: center"><?=$i=$i+1;?></th>
                      <td style="vertical-align:middle"><?=$CONROW[item_name];?></td>
                      <td style="vertical-align:middle"><?=$CONROW[material_name];?></td>
                      <td style="vertical-align:middle; text-align:center" valign="middle"><?=$CONROW[batch_for]?></td>
                      <td style="text-align:right;vertical-align:middle; text-align:center" valign="middle"><?=$CONROW[total_unit]?></td>
                      <td style="text-align:center; vertical-align:middle"><button type="submit" name="CONdeletedata<?php echo $CONROW[id]; ?>" id="CONdeletedata<?php echo $CONROW[id]; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button></td>
                      </tr>
                      <?php }
					  
					  if(isset($_POST[ALLCONFIRM])){
						  
						  mysql_query("Update re_processing_master SET status='CHECKED' where custom_pi_no='$_GET[custom_pi_no]'");
						  unset($_SESSION["psGET"]);
                          unset($_SESSION["QC_Inv_consumption_due_to_FG_reprocess"]);
						  
					  }?>


                      </tbody></table>

               <div class="form-group">
               <button type="submit" name="consumptionCANCEL" onclick='return window.confirm("Are you confirm?");' class="btn btn-danger" style="font-size: 12px; float: left; margin-left: 1%">Cancel and Back</button>
               <button type="submit" name="ALLCONFIRM" onclick='return window.confirm("Are you confirm?");' class="btn btn-success" style="font-size: 12px; float: right; margin-right: 1%">Confirm All Process</button>
               </div>
</form>

                  


<?php } ?>


<?php
if(!isset($_GET[$unique])){	
if(isset($_POST[viewreport])){	
if(isset($_POST[warehouse_from])){
	$warehouse_conn=" and m.warehouse_to=".$_POST[warehouse_from]."";
} else {
	$warehouse_conn="";
}
$warehouse_CONN=
$res="Select m.pi_no,m.pi_no,m.custom_pi_no,m.pi_date as date,w.warehouse_name as warehouse_from, (select warehouse_name from warehouse where warehouse_id=m.warehouse_to) as warehouse_to,m.remarks,u.fname as entry_by from re_processing_master m,warehouse w, user_activity_management u where m.entry_by=u.user_id  and m.warehouse_from=w.warehouse_id and  m.ISSUE_TYPE='STO' and m.verifi_status='UNCHECKED' ".$warehouse_conn." order by m.pi_no DESC";	}?>

<form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date" style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date" style="width:150px;font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d')?>" required   name="t_date"></td>
                <td style="width:10px; text-align:center"> -</td>                                       
    <td style="width:30%">
    <select  class="form-control" style="width: 200px; font-size:11px; height:25px" tabindex="-1" required="required"  name="warehouse_from" id="warehouse_from">
                        <option selected></option>
                        <? $sql_plant="SELECT w.warehouse_id,concat(w.warehouse_id,' : ',w.warehouse_name),upp.* FROM  
                            user_plant_permission upp,
							warehouse w  WHERE  upp.warehouse_id=w.warehouse_id and 
							 upp.user_id=".$_SESSION[userid]." and upp.status>0					 
							  order by w.warehouse_id";
                        advance_foreign_relation($sql_plant,$_POST[warehouse_from]);?>
                    </select>
    </td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="viewreport"  class="btn btn-primary">View Available RP</button></td>
            </tr></table>
<?=$crud->report_templates_with_data($res,$title);?>
</form>
<?php } ?>   


<?php require_once 'footer_content.php' ?>


