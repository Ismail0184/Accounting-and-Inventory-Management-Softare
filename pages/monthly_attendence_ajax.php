<?

session_start();

//require "../../config/inc.all.php";
require_once 'support_file.php';


$crud      =new crud('salary_attendence');

$unique = 'id';





//echo 'PBI_ID="'.$_REQUEST['PBI_ID'].'" and mon="'.$_REQUEST['mon'].'" and year="'.$_REQUEST['year'].'" ';

$_POST[$unique] = $$unique = find_a_field('salary_attendence','id','PBI_ID="'.$_REQUEST['PBI_ID'].'" and mon="'.$_REQUEST['mon'].'" and year="'.$_REQUEST['year'].'" ');

$salary = find_all_field('salary_info','','PBI_ID='.$_REQUEST['PBI_ID']);


$firstDate = date('Y-m-d',mktime(0,0,0,$_REQUEST['mon'],1,$_REQUEST['year']));
$lastDate = date('Y-m-d',mktime(0,0,0,$_REQUEST['mon'],$_REQUEST['td'],$_REQUEST['year']));

if($_REQUEST['doj'] > $firstDate){
$interval = date_diff(date_create($data->PBI_DOJ), date_create($lastDate));
$payableDays = ($interval->format("%d"))+1;}
else{
$payableDays = $_REQUEST['td'];}

//$_REQUEST['deduction']=;

//$_REQUEST['benifits']=;
$_REQUEST['PBI_DESIGNATION']=$_REQUEST['designation'];

$_REQUEST['PBI_DEPARTMENT']=$_REQUEST['department'];

$_REQUEST['over_time_hr']=$_REQUEST['ot']; 

$_REQUEST['gross_salary']=$salary->gross_salary;

$_REQUEST['basic_salary']=$salary->basic_salary;

$_REQUEST['house_rent']=$salary->house_rent;

$_REQUEST['medical_allowance']=$salary->medical_allowance;

$_REQUEST['other_allowance']=$salary->others;

$_REQUEST['special_allowance']=$salary->special_allowance;

$_REQUEST['mobile_allowance']=$salary->mobile_allowance;

$_REQUEST['ta_da']=$salary->transport_allowance;

$_REQUEST['food_allowance']=$salary->food_allowance;

$_REQUEST['income_tax']=($salary->income_tax/$_REQUEST['td'])*$payableDays;

$_REQUEST['pf']=$salary->pf;

$_REQUEST['medical_insurance']=$salary->medical_insurance;


$_REQUEST['over_time_amount']=($salary->overtime_applicable*$_REQUEST['ot']);

$_REQUEST['absent_deduction']=(($salary->basic_salary+$salary->consolidated_salary+$salary->special_allowance)/($_REQUEST['td']))*($_REQUEST['td']-$_REQUEST['pay']);



if($_REQUEST['food_allowance']>0&&$_REQUEST['food_allowance']<2000)

{

$_REQUEST['food_allowance'] = (($_REQUEST['food_allowance'])*($_REQUEST['pre']));

}



/*if($_REQUEST['ta_da']>2000)

{

$_REQUEST['ta_da'] = (int)(($_REQUEST['ta_da']/($_REQUEST['td'] - $_REQUEST['fd']))*($_REQUEST['pre']));

}*/





$_REQUEST['advance_install'] = find_a_field('salary_advance','sum(payable_amt)','PBI_ID="'.$_REQUEST['PBI_ID'].'" and current_mon="'.$_REQUEST['mon'].'" and  	current_year="'.$_REQUEST['year'].'" and  	advance_type="Advance Cash" ');

$_REQUEST['other_install'] = find_a_field('salary_advance','sum(payable_amt)','PBI_ID="'.$_REQUEST['PBI_ID'].'" and current_mon="'.$_REQUEST['mon'].'" and  	current_year="'.$_REQUEST['year'].'" and  	advance_type="Other Advance" ');





if($_REQUEST['bonus']=='No')

$_REQUEST['bonus_amount']=0;

else

$_REQUEST['bonus_amount']=($salary->consolidated_salary/2);





$_REQUEST['total_salary']=(($salary->gross_salary/$_REQUEST['td'])*$payableDays)+(($salary->transport_allowance+$salary->food_allowance)*$_REQUEST['pre']);

$_REQUEST['deduction'] = ($salary->gross_salary/$_REQUEST['td'])*($_REQUEST['late_deduction_days']+$_REQUEST['ab']+$_REQUEST['lwp']);

$_REQUEST['total_deduction'] = $_REQUEST['income_tax']+$_REQUEST['advance_install']+$_REQUEST['other_install']+$_REQUEST['deduction']+$_REQUEST['pf']+$_REQUEST['medical_insurance'];

$_REQUEST['total_benefits'] = $_REQUEST['bonus_amount'] + $_REQUEST['over_time_amount'] + $_REQUEST['benefits']+$_REQUEST['ta_da']+$_REQUEST['food_allowance'];

$_REQUEST['total_payable'] = ($_REQUEST['total_salary'] + $_REQUEST['total_benefits'])-$_REQUEST['total_deduction'];

if($$unique>0)

{
$_REQUEST['edit_by']=$_SESSION['userid'];
$_REQUEST['edit_at']=date('Y-m-d h:i:s');
echo 'Updated!';
$crud->update($unique);
} else {
$_REQUEST['entry_by']=$_SESSION['userid'];
$_REQUEST['entry_at']=date('Y-m-d h:i:s');
echo 'Saved!';
$crud->insert();
}

?>