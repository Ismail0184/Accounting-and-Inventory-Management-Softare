
<?php

require_once 'support_file.php';
$title='Monthly Attendance Sheet';

$head='<link href="../../css/report_selection.css" type="text/css" rel="stylesheet"/>';

if($_POST['mon']!=''){
    $mon=$_POST['mon'];}
else{
    $mon=date('m');
}

if($_POST['year']!=''){
    $year=$_POST['year'];}
else{
    $year=date('Y');
}

if(isset($_POST['create']))
{	$dept=$_POST['dept'];
    $bonus=$_POST['bonus'];
    $_SESSION[mon]=$_POST['mon'];
    $_SESSION[year]=$_POST['year'];
    $_SESSION[department]=$_POST['dept'];

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $userRow[proj_name]; ?> | <?php echo $title; ?></title>

    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>

    <script type="text/javascript">

        $(document).ready(function(){

            $("#codz").validate();

        });



    </script>
    <script type="text/javascript">

        $(document).ready(function(){



            $(function() {

                $("#date_birth").datepicker({

                    changeMonth: true,

                    changeYear: true,

                    dateFormat: "yy-mm-dd"

                });



            });



        });</script>
    <script>
        function getXMLHTTP() { //fuction to return the xml http object
            var xmlhttp=false;
            try{
                xmlhttp=new XMLHttpRequest();}
            catch(e)	{
                try{
                    xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");}
                catch(e){
                    try{
                        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    catch(e1){
                        xmlhttp=false;}}}
            return xmlhttp;}
        function update_value(id)
        {







            var PBI_ID=id; // Rent

            var fd=(document.getElementById('fd').value)*1; // Other
            var td=(document.getElementById('td_'+id).value)*1; // Other
            var od=(document.getElementById('od_'+id).value)*1; // Rent + Other
            var hd=(document.getElementById('hd_'+id).value)*1; // Paid
            var lt=document.getElementById('lt_'+id).value;
            var ab=document.getElementById('ab_'+id).value;
            var lv=document.getElementById('lv_'+id).value;
            var lwp=document.getElementById('lwp_'+id).value;
            var pre=(document.getElementById('pre_'+id).value)*1; // Due
            var pay=document.getElementById('pay_'+id).value;
            var ot=document.getElementById('ot_'+id).value;
//var deduction=document.getElementById('deduction_'+id).value;
            var benefits=document.getElementById('benefits_'+id).value;
            var designation=document.getElementById('desg_'+id).value;
            var department=document.getElementById('dept_'+id).value;
            var late_deduction_days=document.getElementById('lt_dDays_'+id).value;
            var doj=document.getElementById('doj_'+id).value;
            var mon=document.getElementById('mon').value;
            var year=document.getElementById('year').value;
            var bonus=document.getElementById('bonus').value;

            var strURL="monthly_attendence_ajax.php?PBI_ID="+PBI_ID+"&td="+td+"&fd="+fd+"&od="+od+"&hd="+hd+"&lt="+lt+"&ab="+ab+"&lv="+lv+"&lwp="+lwp+"&pre="+pre+"&pay="+pay+"&ot="+ot+"&mon="+mon+"&year="+year+"&bonus="+bonus+"&benefits="+benefits+"&designation="+designation+"&department="+department+"&late_deduction_days="+late_deduction_days+"&doj="+doj;



            var req = getXMLHTTP();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {
                            document.getElementById('divi_'+id).style.display='inline';
                            document.getElementById('divi_'+id).innerHTML=req.responseText;
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }}}

                req.open("GET", strURL, true);
                req.send(null);	}}
        function cal_all(id)
        {

            var PBI_ID=id; // Rent
            var td=(document.getElementById('td_'+id).value)*1; // Other
            var od=(document.getElementById('od_'+id).value)*1; // Rent + Other
            var hd=(document.getElementById('hd_'+id).value)*1; // Paid
            var lt=(document.getElementById('lt_'+id).value)*1;
            var ab=(document.getElementById('ab_'+id).value)*1;
            var lv=(document.getElementById('lv_'+id).value)*1;
            var lwp=(document.getElementById('lwp_'+id).value)*1;
            var ltd=lt/3;
            var ltdd=Math.floor(ltd);
            var pre=td - (od + hd + ab + lv+lwp);
            var pay=td - ab - ltdd-lwp;
            document.getElementById('pay_'+id).value=pay;
            document.getElementById('pre_'+id).value=pre;
            document.getElementById('lt_dDays_'+id).value=ltdd;
        }

    </script>
    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            width: auto;
            height: 20px;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            font-size: 10px;
            cursor: pointer;
        }

        .button2 {
            background-color: #008CBA;
            color: white;
            font-size: 10px;
            border: 2px solid #008CBA;
        }
        .button2:hover {
            background-color: white;
            color: black;
        }


    </style>

</head>
<?php require_once 'body_content.php'; ?>

<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <div class="input-group pull-right">
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>


        <div class="x_content">
                    <form action=""  method="post">
<table align="center" style="width: 100%"><tr>

                        <td><div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Year<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="year" style="width:200px; height: 30px" id="year" required="required">
                                        <? for($i=(date('Y')-2); $i<=(date('Y')+4); $i+=1){?>
                                            <option <?=($year==$i)?'selected':''?>><?=$i?></option>
                                        <? }?>

                                    </select></div></div></td>


                        <td><div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Month<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="mon" style="width:200px; height: 30px" id="mon" required="required">
                                        <option value="1" <?=($mon=='1')?'selected':''?>>Jan</option>
                                        <option value="2" <?=($mon=='2')?'selected':''?>>Feb</option>
                                        <option value="3" <?=($mon=='3')?'selected':''?>>Mar</option>
                                        <option value="4" <?=($mon=='4')?'selected':''?>>Apr</option>
                                        <option value="5" <?=($mon=='5')?'selected':''?>>May</option>
                                        <option value="6" <?=($mon=='6')?'selected':''?>>Jun</option>
                                        <option value="7" <?=($mon=='7')?'selected':''?>>Jul</option>
                                        <option value="8" <?=($mon=='8')?'selected':''?>>Aug</option>
                                        <option value="9" <?=($mon=='9')?'selected':''?>>Sep</option>
                                        <option value="10" <?=($mon=='10')?'selected':''?>>Oct</option>
                                        <option value="11" <?=($mon=='11')?'selected':''?>>Nov</option>
                                        <option value="12" <?=($mon=='12')?'selected':''?>>Dec</option>
                                    </select></div></div></td>


                        <td><div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" style="width: 30%">Department<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="dept" style="width:200px; height: 30px" id="dept" required="required">
                                    <?=foreign_relation('department','DEPT_ID','DEPT_DESC',$_SESSION[department]);?>
                                </select></div></div></td>

                             </tr></table>




                                                                    <div class="form-group" style="margin-left:40%; margin-top: 15px">
                                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                <button type="submit" name="create" id="create" onclick='return window.confirm("Are you confirm?");' class="btn btn-primary">View Attendance Sheet</button>
                                                                        </div></div></form></div></div></div>















<? if($_SESSION[department]>0){?>
    <form action="" enctype="multipart/form-data" method="post" name="addem" id="addem" >
    <div class="col-md-12 col-xs-12">
                                                                <div class="x_panel">
                                                                    <div class="x_title">
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                    <div class="x_content">


                                                                                        <table width="100%" class="table table-striped table-bordered"><thead><tr class="oe_list_header_columns" style="font-size:10px;padding:3px;">

                                                                                                <th>Code</th>
                                                                                                <th>Full Name</th>
                                                                                                <th>Desg</th>
                                                                                                <th>Dept</th>
                                                                                                <th style="text-align: center">TD</th>
                                                                                                <th style="text-align: center">OD</th>
                                                                                                <th style="text-align: center">HD</th>
                                                                                                <th style="text-align: center">LT</th>
                                                                                                <th style="text-align: center">AB</th>
                                                                                                <th style="text-align: center">LV</th>
                                                                                                <th style="text-align: center">LWP</th>
                                                                                                <th style="text-align: center">Pre</th>
                                                                                                <th style="text-align: center">Pay</th>
                                                                                                <th style="text-align: center">OT</th>
                                                                                                <th style="text-align: center">Benefits</th>
                                                                                                <th style="text-align: center">&nbsp;</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            <?

$startTime = $days1=mktime(0,0,0,($mon-1),26,$year);
$endTime = $days2=mktime(0,0,0,$mon,25,$year);
$days_in_month = date('t',$endTime);
$startTime1 = $days1=mktime(0,0,0,($mon),01,$year);
$endTime1 = $days2=mktime(0,0,0,$mon,$days_in_month,$year);
$startday = date('Y-m-d',$startTime);
$endday = date('Y-m-d',$endTime);
$start_date = $year.'-'.($mon-1).'-26';
$end_date = $year.'-'.$mon.'-25';
$days_mon=ceil(($endTime - $startTime)/(3600*24))+1;
for ($i = $startTime1; $i <= $endTime1; $i = $i + 86400) {
$day   = date('l',$i);
${'day'.date('N',$i)}++;
//if(isset($$day))
//$$day .= ',"'.date('Y-m-d', $i).'"';
//else
//$$day .= '"'.date('Y-m-d', $i).'"';
}
$r_count=${'day5'};
?>
<input name="fd" type="hidden" id="fd" value="<?=$r_count;?>" />

<?

		$holy_day=find_a_field('salary_holy_day','count(holy_day)','holy_day between "'.$year.'-'.$mon.'-'.'01'.'" and "'.$year.'-'.$mon.'-'.$days_mon.'"');
		if($_POST['PBI_BRANCH']!='')	$con .= " and p.PBI_BRANCH = '".$_POST['PBI_BRANCH']."'";
		if($_POST['PBI_ZONE']!='')		$con .= " and p.PBI_ZONE = '".$_POST['PBI_ZONE']."'";
		if($_POST['PBI_GROUP']!='')		$con .= " and p.PBI_GROUP = '".$_POST['PBI_GROUP']."'";
		if($_POST['PBI_DOMAIN']!='')	$con .= " and p.PBI_DOMAIN = '".$_POST['PBI_DOMAIN']."'";
		if($_POST['JOB_LOCATION']!='') $con .= " and JOB_LOCATION = '".$_POST['JOB_LOCATION']."'";
		$sql = "select p.*,s.*,
		(select DEPT_DESC from department where DEPT_ID=p.PBI_DEPARTMENT) as DEPARTMENT, 
		(select DESG_DESC from designation where DESG_ID=p.PBI_DESIGNATION) as DESIGNATION 		
		from personnel_basic_info p, salary_info s 		
		where p.PBI_ID=s.PBI_ID and p.PBI_JOB_STATUS='In Service' and p.PBI_DEPARTMENT='".$_SESSION[department]."' and p.PBI_ORG='".$_SESSION['usergroup']."'  order by p.PBI_ID";
		$query = mysql_query($sql);
		while($info=mysql_fetch_object($query))

        {
            $td=$_POST['td_'.$info->PBI_ID];
            $od=$_POST['od_'.$info->PBI_ID];
            $hd=$_POST['hd_'.$info->PBI_ID];
            $lt=$_POST['lt_'.$info->PBI_ID];
            $ab=$_POST['ab_'.$info->PBI_ID];

            $lv=$_POST['lv_'.$info->PBI_ID];
            $pre=$_POST['pre_'.$info->PBI_ID];
            $pay=$_POST['pay_'.$info->PBI_ID];
            $acutal_salary=$_POST['acutal_salary_'.$info->PBI_ID];
            $income_tax=($info->income_tax/$td)*$pay;
            $advance_install = find_a_field('salary_advance','sum(payable_amt)','PBI_ID="'.$info->PBI_ID.'" and current_mon="'.$_SESSION[mon].'" and  	current_year="'.$_SESSION[year].'" and  	advance_type="Advance Cash" ');
            $other_install = find_a_field('salary_advance','sum(payable_amt)','PBI_ID="'.$info->PBI_ID.'" and current_mon="'.$_SESSION[mon].'" and  	current_year="'.$_SESSION[year].'" and  	advance_type="Other Advance" ');
            $advance_loan=$advance_install+$other_install;

            $lwp=$_POST['lwp_'.$info->PBI_ID];
            $ot=$_POST['ot_'.$info->PBI_ID];
            $ot=$_POST['ot_'.$info->PBI_ID];
            $benefits=$_POST['benefits_'.$info->PBI_ID];
            $pf=$info->pf;
            $medical_insurance=$info->medical_insurance;

            /// late attendance
            $late_deduction_days=$lt/3;
            $deduction = ($info->basic_salary/$td)*($late_deduction_days);

            // absent deduction
            $absent_deduction=(($info->basic_salary)/($td))*($ab+$lwp);

            $total_deduction = $income_tax+$advance_install+$other_install+$deduction+$pf+$medical_insurance+$absent_deduction;

            $total_benefits = $bonus_amount + $over_time_amount + $benefits+$ta_da+$food_allowance;
            $total_salary=($info->gross_salary+$info->transport_allowance+$info->food_allowance);
            $total_payable= ($total_salary + $total_benefits)-$total_deduction;




            if(isset($_POST['save_'.$info->PBI_ID])) {
                mysql_query("INSERT INTO salary_attendence (mon,year,PBI_DEPARTMENT,PBI_ID,gross_salary,ta_da,td,od,hd,lv,pre,pay,acutal_salary,advance_loan,income_tax,ab,lt,lwp,ot,benefits,late_deduction_days,deduction,absent_deduction,total_deduction,total_payable,entry_by,entry_at,section_id,company_id) 
VALUES ('$_SESSION[mon]','$_SESSION[year]','$_SESSION[department]','$info->PBI_ID]','$info->gross_salary','$info->transport_allowance','$td','$od','$hd','$lv','$pre','$pay','$acutal_salary','$advance_loan','$income_tax','$ab','$lt','$lwp','$ot','$benefits','$late_deduction_days','$deduction','$absent_deduction','$total_deduction','$total_payable','$_SESSION[userid]','".date('Y-m-d h:i:s')."','$_SESSION[sectionid]','$_SESSION[companyid]')");
            }

            if(isset($_POST['confirmsave'])) {
                mysql_query("INSERT INTO salary_attendence (mon,year,PBI_DEPARTMENT,PBI_ID,gross_salary,ta_da,td,od,hd,lv,pre,pay,acutal_salary,advance_loan,income_tax,ab,lt,lwp,ot,benefits,late_deduction_days,deduction,absent_deduction,total_deduction,total_payable,entry_by,entry_at,section_id,company_id) 
VALUES ('$_SESSION[mon]','$_SESSION[year]','$_SESSION[department]','$info->PBI_ID]','$info->gross_salary','$info->transport_allowance','$td','$od','$hd','$lv','$pre','$pay','$acutal_salary','$advance_loan','$income_tax','$ab','$lt','$lwp','$ot','$benefits','$late_deduction_days','$deduction','$absent_deduction','$total_deduction','$total_payable','$_SESSION[userid]','".date('Y-m-d h:i:s')."','$_SESSION[sectionid]','$_SESSION[companyid]')");
            }


		if($info->PBI_DEPARTMENT=='S&M')
		$r_count = 0;
		$data = find_all_field('salary_attendence','','PBI_ID="'.$info->PBI_ID.'" and mon="'.$mon.'" and year="'.$year.'" ');
		if($data->td>0)
		{
			$status='Edit';
		}
		else
		{
			if($info->special_attendence==0)
			$att = find_all_field('hrm_attendence_final','','PBI_ID="'.$info->PBI_ID.'" and mon="'.$mon.'" and year="'.$year.'" ');
			else
			{
			$att->lt = 0;
			$att->ab = 0;
			$att->lv = 0;
			$att->ot = 0;
			$att->pay = $days_mon;
			$att->pre = $days_mon - ($holy_day + $r_count);
			}
			$status='Save';
			$pay = $days_mon;
			$pre = $days_mon - ($holy_day + $r_count);
		}
		$sdte=$year.'-'.$mon.'-'."01";
		$edte=$year.'-'.$mon.'-'."31";
		$leavetakenstartdate = find_a_field('hrm_leave_info','SUM(total_days)','half_or_full="Full" and PBI_ID="'.$info->PBI_ID.'" and s_date between "'.$sdte.'" and "'.$edte.'" and e_date between "'.$sdte.'" and "'.$edte.'"');

		//$leavetakene_datetdate = find_a_field('hrm_leave_info','SUM(total_days)','half_or_full="Full" and PBI_ID="'.$info->PBI_ID.'" and e_date between "'.$sdte.'" and "'.$edte.'" ');

		$leavetaken=$leavetakenstartdate+$leavetakene_datetdate ;
		?>

<tr style="font-size:10px; padding:3px; "><td><?=$info->PBI_ID_UNIQUE?>
<input name="id_<?=$info->PBI_ID;?>" type="hidden" id="id_<?=$info->PBI_ID?>"  value="" />
<input name="dept_<?=$info->PBI_ID;?>" type="hidden" id="dept_<?=$info->PBI_ID?>"  value="<?=$info->PBI_DEPARTMENT;?>" />
<input name="desg_<?=$info->PBI_ID;?>" type="hidden" id="desg_<?=$info->PBI_ID?>"  value="<?=$info->PBI_DESIGNATION;?>" />
<input name="doj_<?=$info->PBI_ID;?>" type="hidden" id="doj_<?=$info->PBI_ID?>"  value="<?=$info->PBI_DOJ;?>" />
<input name="lt_dDays_<?=$info->PBI_ID;?>" type="hidden" id="lt_dDays_<?=$info->PBI_ID?>"  value="" />
<input type="hidden" name="PBI_ID" id="PBI_ID" value="<?=$info->PBI_ID;?>" />
</td><td><?=$info->PBI_NAME;?></td><td><?=$info->DESIGNATION;?></td><td><?=$info->DEPARTMENT;?></td>
<td align="center"><input name="td_<?=$info->PBI_ID;?>" type="text" id="td_<?=$info->PBI_ID?>" style="font-size:10px; width:20px; min-width:20px; background-color: beige; border: solid 1px; text-align: center" value="<?=$days_in_month?>" size="2" maxlength="2" readonly="readonly" /></td>
<td align="center"><input name="od_<?=$info->PBI_ID;?>" type="text" id="od_<?=$info->PBI_ID?>" style="font-size:10px; width:20px; min-width:20px;background-color: beige; border: solid 1px; text-align: center" size="2" maxlength="2" readonly="readonly" value="<?=($att->od>0)?$att->od:$r_count;?>" /></td>
<td align="center"><input name="hd_<?=$info->PBI_ID;?>" type="text" id="hd_<?=$info->PBI_ID?>" style="font-size:10px; width:20px; min-width:20px;background-color: beige; border: solid 1px; text-align: center" size="2" maxlength="2" readonly="readonly" value="<?=$holy_day?>" /></td>
<td align="center"><input name="lt_<?=$info->PBI_ID;?>" type="text" id="lt_<?=$info->PBI_ID?>" style="font-size:10px; width:20px; min-width:20px;" value="<?=($data->lt=='')?$att->lt:$data->lt;?>" size="2" maxlength="2" onchange="cal_all(<?=$info->PBI_ID?>)" /></td>
<td align="center"><input name="ab_<?=$info->PBI_ID;?>" type="text" id="ab_<?=$info->PBI_ID?>" style="font-size:10px; width:20px; min-width:20px;" value="<?=($data->ab=='')?$att->ab:$data->ab;?>" size="2" maxlength="2"  onchange="cal_all(<?=$info->PBI_ID?>)"/></td>
<td align="center"><input name="lv_<?=$info->PBI_ID;?>" type="text" id="lv_<?=$info->PBI_ID?>" style="font-size:10px; width:20px; min-width:20px; text-align:center"
value="
<?php if($leavetaken>0){ echo $leavetaken;?>
<?php } else { ?>
<?=($data->lv=='')?$att->lv:$data->lv;?>
<?php } ?>"
 size="2" maxlength="2"  onchange="cal_all(<?=$info->PBI_ID?>)"/></td>

<td align="center"><input name="lwp_<?=$info->PBI_ID?>" type="text" id="lwp_<?=$info->PBI_ID?>" style="font-size:10px; width:20px; min-width:20px;" value="<?=($data->lwp=='')?$att->lwp:$data->lwp;?>" size="2" maxlength="2"  onchange="cal_all(<?=$info->PBI_ID?>)"/></td>
<td align="center"><input name="pre_<?=$info->PBI_ID?>" type="text" id="pre_<?=$info->PBI_ID?>" style="font-size:10px; width:25px; min-width:20px;background-color: beige; border: solid 1px; text-align: center" onchange="cal_all(<?=$info->PBI_ID?>)" value="<?=($data->pre=='')?$att->pre:$data->pre;?>" size="2" maxlength="2" readonly="readonly" /></td>
<td align="center"><input name="pay_<?=$info->PBI_ID?>" type="text" id="pay_<?=$info->PBI_ID?>" style="font-size:10px; width:25px; min-width:20px;background-color: beige; border: solid 1px; text-align: center" value="<?=($data->pay=='')?$att->pay:$data->pay;?>" size="2" maxlength="2" readonly="readonly" /></td>
<td align="center"><input name="ot_<?=$info->PBI_ID?>" type="text" id="ot_<?=$info->PBI_ID?>" style="font-size:10px; width:25px; min-width:20px;" value="<?=$data->ot?>" size="2" maxlength="2" /></td>
<td align="center"><input name="benefits_<?=$info->PBI_ID?>" type="text" id="benefits_<?=$info->PBI_ID?>" style=" width:50px; min-width:20px;" value="<?=$data->benefits?>" size="8" maxlength="8" /></td>
          <td align="center"><span id="divi_<?=$info->PBI_ID?>">
            <?
			  if($status=='Edit')
			  {
			  if($_SESSION['userlevel']==5)
			  {?><input type="button" name="Button" value="<?=$status?>"  onclick="cal_all(<?=$info->PBI_ID?>), update_value(<?=$info->PBI_ID?>)" style="font-size:10px;"/><?
			  }  else echo 'Saved';  }
			  else {  ?>
                  <button type="submit"  style="height:30px; width: auto; margin: 0px;display: table-cell;vertical-align: middle;padding-top:-15px;" name="save_<?=$info->PBI_ID;?>" id="save_<?=$info->PBI_ID;?>"><?=$status?></button>
              <? } ?>

          </span>&nbsp;</td>
          </tr>
        <? } ?>
        </tbody> </table>
        </div></div></div>
    <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Purchase?");' name="deleted" id="deleted" class="btn btn-danger" style="float: left; margin-left: 10px">Cancel</button>
    <button type="submit" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete the Purchase?");' name="confirmsave" id="confirmsave" class="btn btn-success" style="float: right; margin-right: 10px">Save the Attendance Sheet </button>
    </form>
<? }?>
<?php require_once 'footer_content.php'; ?>