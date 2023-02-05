<?php

session_start();
ob_start();
require "../../support/inc.all.php";
$title='Cash Collection Reports';


do_calander("#f_date");
do_calander("#t_date");
do_calander("#cut_date");
auto_complete_from_db('dealer_info','concat(dealer_code,"-",product_group,"-",dealer_name_e)','dealer_code','canceled="Yes"','dealer_code');
auto_complete_from_db('branch','concat(BRANCH_NAME,"-",branch_rsm_name)','BRANCH_ID','1','region');
auto_complete_from_db('zon','concat(ZONE_NAME)','ZONE_CODE','1','area');
auto_complete_from_db('area','concat(AREA_NAME)','AREA_CODE','1 and Territory_CODE>0','territory');
auto_complete_from_db('town','concat(town_name)','town_code','1','town');
auto_complete_from_db('dealer_info','concat(dealer_name_e)','dealer_code','1','dealer');
auto_complete_from_db('dealer_info','concat(dealer_name_e)','account_code','dealer_category not in ("Rice")','account_code');

?>


<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.reporttypes.options[form.reporttypes.options.selectedIndex].value;
	self.location='cash_collection_detailss.php?reporttypes=' + val ;
}

</script>
<script type="text/javascript">

$(document).ready(function(){

	

	$(function() {

		$("#fdate").datepicker({

			changeMonth: true,

			changeYear: true,

			dateFormat: 'dd-mm-y'

		});

	});

		$(function() {

		$("#tdate").datepicker({

			changeMonth: true,

			changeYear: true,

			dateFormat: 'dd-mm-y'

		});

	});
	
	$(function() {

		$("#comparisonF").datepicker({

			changeMonth: true,

			changeYear: true,

			dateFormat: 'dd-mm-y'

		});

	});
	
	
	$(function() {

		$("#comparisonT").datepicker({

			changeMonth: true,

			changeYear: true,

			dateFormat: 'dd-mm-y'

		});

	});



});

function DoNav(a,b,c)



{



	document.location.href = 'transaction_list.php?fdate='+a+'&tdate='+b+'&ledger_id='+c+'&show=Show';



}

</script>

<form action="cash_collection_report.php" method="post" name="form1" target="_blank" id="form1">

<table width="60%" align="center" cellspacing="0" style="height:20px; background-color:#FCF">
<tr><td style="text-align:center"><select required size="15" style="height:210px; font-size:12px; width:500px"  onchange="javascript:reload(this.form)" name="reporttypes"> 

<option style="height:20px" value="country" <?php if($_GET[reporttypes]=='country') echo 'selected'; ?>>Cash Collection (Country)</option>
<option style="height:20px" value="region" <?php if($_GET[reporttypes]=='region') echo 'selected'; ?>>Cash Collection (Region)</option>
<!--option style="height:20px" value="area" <?php if($_GET[reporttypes]=='area') echo 'selected'; ?>>Cash Collection (Area)</option-->
<option style="height:20px" value="territory" <?php if($_GET[reporttypes]=='territory') echo 'selected'; ?>>Cash Collection (Territory)</option>
<option style="height:20px" value="town" <?php if($_GET[reporttypes]=='town') echo 'selected'; ?>>Cash Collection (Town)</option>
<option style="height:20px" value="dealer" <?php if($_GET[reporttypes]=='dealer') echo 'selected'; ?>>Cash Collection (Dealer)</option>

<option style="height:20px" value="dealerledger" <?php if($_GET[reporttypes]=='dealerledger') echo 'selected'; ?>>Party Ledger</option>

<option style="height:20px" value="allcurrent" <?php if($_GET[reporttypes]=='allcurrent') echo 'selected'; ?>>All Party Current Balance</option>


<option style="height:20px" value="cashandship" <?php if($_GET[reporttypes]=='cashandship') echo 'selected'; ?>>Collection & Shipment</option>

            <option style="height:20px" value="cashandships" <?php if($_GET[reporttypes]=='cashandships') echo 'selected'; ?>>Collection & Shipment (with commission)</option>

<option style="height:20px" value="ytdcashcollection" <?php if($_GET[reporttypes]=='ytdcashcollection') echo 'selected'; ?>>YTD Cash Collection</option>
</select></td></tr>
</table>  

<br /><br />








<?php 
/////////////////////////////////////cash book----------------------------------------------------------					  
					  if(($_GET['reporttypes'])=='country'): ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      <tr>
                                        <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date From : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>


<?php elseif ($_GET['reporttypes']=='region'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      
      
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Region Name : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="region" type="text" id="region"   value="" autocomplete="off" /></td></tr>
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date From : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>


<?php elseif ($_GET['reporttypes']=='area'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      
      
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Area Name : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="area" type="text" id="area"   value="" autocomplete="off" /></td></tr>
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date From : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>



<?php elseif ($_GET['reporttypes']=='territory'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      
      
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Territory Name : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="territory" type="text" id="territory"   value="" autocomplete="off" /></td></tr>
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date From : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>
                                      
                                      
                                      
<?php elseif ($_GET['reporttypes']=='town'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      
      
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Town Name : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="town" type="text" id="town"  value="" autocomplete="off" /></td></tr>
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"  value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date From : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"  value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>
                                      
                                      
                                      
  <?php elseif ($_GET['reporttypes']=='dealer'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      
      
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Dealer Name : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="dealer" type="text" id="dealer"   value="" autocomplete="off" /></td></tr>
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date From : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>                                    


<?php elseif ($_GET['reporttypes']=='dealerledger'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      
      
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Dealer Name : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="account_code" type="text" id="account_code"   value="" autocomplete="off" /></td></tr>
                            <tr>
                            <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date From : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>
                                      
                                      
                                      
 <?php elseif ($_GET['reporttypes']=='allcurrent'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>
      
      
      
                           
                            
                   
                    <tr><td style="text-align:right; border:none"> As At : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>                                       


<?php elseif ($_GET['reporttypes']=='cashandship'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>     <tr>
                            <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                        <td style="text-align:right; border:none" width="60%">
                   <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>
                   
                    <tr><td style="text-align:right; border:none"> Date To : </td>
                   <td style="text-align:left; border:none">
                   <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                   <tr><td style="height:5px"></td></tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>

                      <?php elseif ($_GET['reporttypes']=='cashandships'):
/////////////////////////////////////cash Journal----------------------------------------------------------
                          ?>

                          <table width="60%" align="center" cellspacing="0" style="height:20px;">

                              <tbody>     <tr>
                                  <td style="text-align:right; border:none" width="40%"> Date From : </td>
                                  <td style="text-align:right; border:none" width="60%">
                                      <input style="width:150px; height:20px" required name="fdate" type="text" id="fdate"   value="<?php echo $_REQUEST['fdate'];?>" autocomplete="off" /></td></tr>

                              <tr><td style="text-align:right; border:none"> Date To : </td>
                                  <td style="text-align:left; border:none">
                                      <input style="width:150px; height:20px" required name="tdate" type="text" id="tdate"   value="<?php echo $_REQUEST['tdate'];?>" autocomplete="off" /></td></tr>
                              <tr><td style="height:5px"></td></tr>
                              <tr>
                                  <td></td> <td style="text-align:center; border:none"> <input style="width:150px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                                  </td>
                              </tr></tbody></table>
                                      
                                      
                                      
                                      <?php elseif ($_GET['reporttypes']=='ytdcashcollection'):
/////////////////////////////////////cash Journal----------------------------------------------------------
					   ?>

<table width="60%" align="center" cellspacing="0" style="height:20px;">

      <tbody>     <tr><td>Select Year</td>
                    <td><?php
//get the current year
$Startyear=date('Y');
$endYear=$Startyear-10;

// set start and end year range i.e the start year
$yearArray = range($Startyear,$endYear);
?>
<!-- here you displaying the dropdown list -->
<select name="year" id="year" style="width:202px;height:30px">
    <option value="">Select Year</option>
    <?php
    foreach ($yearArray as $year) {
        // this allows you to select a particular year
        $selected = ($year == $Startyear) ? 'selected' : '';
        echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
    }
    ?>
</select></td>

</tr>
                   <tr>
                  <td></td> <td style="text-align:center; border:none"> <input style="width:202px; height:30px; vertical-align:middle" class="btn" name="show" type="submit" id="show"  value="View Report" />
                   </td>
                                      </tr></tbody></table>                                       

<?php endif; ?>




</form>
<?



$main_content=ob_get_contents();



ob_end_clean();



include ("../../template/main_layout.php");



?>
