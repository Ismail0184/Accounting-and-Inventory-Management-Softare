<?php
require_once 'support_file.php';
$title="Upcoming LC for GRN";
$now=time();
$unique='id';
$unique_field='name';
$table='';
$page="LC_receive.php";

if($_POST['f_date']){
    $f_date =$_POST[f_date];
    $fdate=date('Y-m-d' , strtotime($f_date));}
if($_POST['t_date']){
    $t_date =$_POST[t_date];
    $tdate=date('Y-m-d' , strtotime($t_date));}

$con.= ' and a.lc_issue_date BETWEEN  "'.$_POST[f_date].'" and "'.$_POST[t_date]. '"';
if(isset($_POST['submitit'])){
    $res='SELECT
a.id,
a.id as LC_ID,
a.lc_no as LC_No,
a.pi_id,
lb.buyer_name as Party_Name,
a.lc_issue_date as Issue_date,
a.expiry_date ,
concat( c.bank_name, " ", c.branch_name ) AS Bank_Name,
a.status
FROM lc_lc_master a,
lc_foreigner_branch c,
lc_buyer lb
WHERE lb.party_id=a.party_id and
    a.lc_issue_date between "'.$_POST[f_date].'" and "'.$_POST[t_date].'" order by a.id DESC'; } else {
    $res='SELECT
a.id,
a.id as LC_ID,
a.lc_no as LC_No,
a.pi_id,
lb.buyer_name as Party_Name,
a.lc_issue_date as Issue_date,
a.expiry_date ,
concat( c.bank_name, " ", c.branch_name ) AS Bank_Name,a.status
FROM lc_lc_master a,
lc_foreigner_branch c,
lc_buyer lb
WHERE lb.party_id=a.party_id and
    a.status="CHECKED" order by a.id DESC';
}  ?>

<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=950,height=600,left = 250,top = -15");}
    </script>
<?php require_once 'body_content.php'; ?>









    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" style="font-size:11px">
        <?php require_once 'support_html.php';?>
        <table align="center" style="width: 60%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" required max="<?=date('Y-m-d')?>"  name="f_date" class="form-control col-md-7 col-xs-12" >
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px; font-size:11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" required max="<?=date('Y-m-d')?>"   name="t_date" class="form-control col-md-7 col-xs-12" ></td>
                <td style="width:10px; text-align:center"> -</td>
                <td style="padding:10px"><button type="submit" style="font-size: 11px; height: 30px" name="submitit"  class="btn btn-primary">View LC</button></td>
            </tr></table>
        <?=$crud->report_templates_with_status($res,$title);?>
    </form>


<?=$html->footer_content();?>
