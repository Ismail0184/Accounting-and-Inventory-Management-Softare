<?php
require_once 'support_file.php';
?>



<title></title>


 <?php 
 require_once 'header_content.php'; 
 require_once 'body_content.php';
 ?>



<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><?=$title;?></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content">
<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td><div class="left_report">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  





								  <tr>



									<td><div id="reporting">


<form action="" method="post">
									<table id="grp"  class="tabledesign" width="100%" cellspacing="0" cellpadding="2" border="0">



							  <tr>
                                <th height="20" align="center" style="text-align:center">S/L</th>
								<th height="20" align="center" style="text-align:center">ERP Id</th>
                                <th height="20" align="center" style="text-align:center">FG ID</th>
                                <th height="20" align="center" style="text-align:center">Item Name</th>
                                <th height="20" align="center" style="text-align:center">Update to</th>
                               



								</tr>



<?php
$p="select distinct j.item_price as mid,i.item_name,i.item_id from journal_item j,item_info i where i.item_id=j.item_id order by i.item_id";
  $sql=mysqli_query($conn, $p);
  while($data=mysqli_fetch_object($sql))
  {

      $m_id=$_POST['post_m_id_'.$data->item_id];
  if(isset($_POST[update])){	  
	  mysqli_query($conn, "Update sale_do_chalan SET cogs_price='".$m_id."' where item_id='".$data->item_id."' and cogs_price=0 and item_id not in ('1096000100010312')");
	  mysqli_query($conn, "Update sale_do_details SET cogs_price='".$m_id."' where item_id='".$data->item_id."' and cogs_price=0  and item_id not in ('1096000100010312')");
	  //mysqli_query($conn, "Update item_info SET production_cost='".$m_id."' where item_id='".$data->item_id."'");
  }?>
    <tr>
    <td align="center"><?=$i=$i+1;?></td> 
   <td align="center"><?=$data->MAN_ID;?></td>
   <td align="center"><?=$data->item_id;?></td>
   <td align="center"><?=$data->item_name;?></td>
    <td align="center"><input type="text" name="post_m_id_<?=$data->item_id;?>" id="post_m_id<?=$data->item_id;?>" value="<?=$data->mid;?>" /></td>
    </tr> <?php } ?>


</table> 
<input type="submit" name="update" value="Update" />
</form>

									</div>



		<div id="pageNavPosition"></div>									



		</td>



		</tr>



		</table>



		</div></td>    



  </tr>



</table></div></div></div>



<?php require_once 'footer_content.php' ?>


