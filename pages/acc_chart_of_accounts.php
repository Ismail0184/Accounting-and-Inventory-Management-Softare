<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Chart of Accounts";
$page="acc_chart_of_accounts.php";

$sectionid = @$_SESSION['sectionid'];
if($sectionid=='400000'){
    $sec_com_connection=' and 1';
    $sec_com_connection_wa=' and 1';
} else {
    $sec_com_connection=" and j.company_id='".$_SESSION['companyid']."' and j.section_id in ('400000','".$_SESSION['sectionid']."')";
    $sec_com_connection_wa=" and company_id='".$_SESSION['companyid']."' and section_id in ('400000','".$_SESSION['sectionid']."')";
}
?>

<?php require_once 'header_content.php'; ?>
<?php require_once 'body_content.php'; ?>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title;?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <div class="input-group pull-right"></div>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

<?
$separator =@$separator;
$sql="select * from ledger_group where status not in ('SUSPENDED')".$sec_com_connection_wa." order by group_id";
$query=mysqli_query($conn, $sql);
if(mysqli_num_rows($query)>0){
while($grp=mysqli_fetch_object($query)){ $grp_id=(string)($grp->group_id*100000000);?>
    <div id="accordion" <!------------ ledger group----------->
        <div class="card">
            <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree<?=$grp->group_id;?>" aria-expanded="false" aria-controls="collapseThree" style="font-size: 11px; margin-top: -13px;text-align: left">
                        <?=ledger_sepe($grp_id,$separator)?><?=' '.$grp->group_name;?>
                    </button>
                </h5>
            </div>
            <div id="collapseThree<?=$grp->group_id;?>" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
    <?php
    $sql2="select * from accounts_ledger where ledger_id like '%00000000' and ledger_group_id=".$grp->group_id."".$sec_com_connection_wa."";
    $query2=mysqli_query($conn, $sql2);
    if(mysqli_num_rows($query2)>0){
        while($ledger=mysqli_fetch_object($query2)){?>
                    <div id="accordion" style="margin-left: 30px" <!------------------- ledger--------->
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree<?=$ledger->ledger_id;?>" aria-expanded="false" aria-controls="collapseThree" style="font-size: 11px; margin-top: -13px;text-align: left">
                                        <?=ledger_sepe(((string)($ledger->ledger_id)),$separator).' '?><?=$ledger->ledger_name;?>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree<?=$ledger->ledger_id;?>" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">

                                             <?
                                             $sql3="select * from sub_ledger where ledger_id=".$ledger->ledger_id."".$sec_com_connection_wa."";
                                             $query3=mysqli_query($conn, $sql3);
                                             if(mysqli_num_rows($query3)>0){
                                                 while($sub_ledger=mysqli_fetch_object($query3)){
                                                     ?>
                                    <div id="accordion" style="margin-left: 30px" <!------------ sub ledger------->
                                        <div class="card">
                                            <div class="card-header" id="headingThree">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree<?=$sub_ledger->sub_ledger_id;?>" aria-expanded="false" aria-controls="collapseThree" style="font-size: 11px; margin-top: -13px;text-align: left">
                                                        <?=ledger_sepe(((string)($sub_ledger->sub_ledger_id)),$separator).' '?><?=$sub_ledger->sub_ledger;?>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseThree<?=$sub_ledger->sub_ledger_id;?>" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                                <div class="card-body">
                                                     <?
                                                     $sql4="select * from sub_sub_ledger where sub_ledger_id=".$sub_ledger->sub_ledger_id."".$sec_com_connection_wa."";
                                                     $query4=mysqli_query($conn, $sql4);
                                                     if(mysqli_num_rows($query4)>0){?>
                                                                 <? while($sub_sub_ledger=mysqli_fetch_object($query4)){?>
                                                    <div id="accordion" style="margin-left: 30px" <!------------ sub sub ledger------->
                                                        <div class="card">
                                                            <div class="card-header" id="headingThree">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree<?=$ledger->ledger_id;?>" aria-expanded="false" aria-controls="collapseThree" style="font-size: 11px; margin-top: -13px;text-align: left">
                                                                        <?=ledger_sepe(((string)($sub_sub_ledger->sub_sub_ledger_id)),$separator).' '?><?=$sub_sub_ledger->sub_sub_ledger;?>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }} ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                <?php } }?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }} ?>
                </div>
            </div>
        </div>
    </div>
<?php }} ?>
    </div>
    </div>
    </div>
    </div>
 <?=$html->footer_content();?>