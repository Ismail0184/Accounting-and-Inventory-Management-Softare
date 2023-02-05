<?php require_once 'support_file.php';?>
<?=(check_permission(basename($_SERVER['SCRIPT_NAME']))>0)? '' : header('Location: dashboard.php');
$title="Chart of Accounts";
$page="acc_chart_of_accounts.php";
?>

<?php require_once 'header_content.php'; ?>
 <link href="http://icpbd-erp.com/51816/acc_mod2/css/sitemapstyler.css" rel="stylesheet" type="text/css" media="screen" />
 <script type="text/javascript" src="http://icpbd-erp.com/51816/acc_mod2/js/sitemapstyler.js"></script>
 <script type="text/javascript">
     function changeBox(cbox) {
         box = eval(cbox);
         box.checked = !box.checked;
     }

     $(function() {
         $("#sitemap").treeview({
             collapsed: true,
             animated: "medium",
             control:"#sidetreecontrol",
             persist: "location"
         });})
 </script>
 <script src="http://icpbd-erp.com/51816/acc_mod2/js/jquery.js" type="text/javascript"></script>
 <script src="http://icpbd-erp.com/51816/acc_mod2/js/jquery.treeview.js" type="text/javascript"></script>

 <style>
     h1{
         font-size:140%;
         margin:0 20px;
         line-height:80px;
     }
     p{
         margin:0 auto;
         width:680px;
         padding:1em 0;
     }
 </style>
<?php require_once 'body_content.php'; ?>

             <form id="form1" name="form1" method="get" action="">
             <div align="right">
                     <div id="sidetreecontrol">
                         <a href="#"><input type="button" name="Button" value="Collapse All" /></a>
                         <a href="?#"><input type="button" name="Button" value="Expand All" /></a>
                     </div>
                 </div>
                 <div id="container" style="width: 95%; font-size: 11px">
                     <div id="content">
                         <ul id="sitemap">

                             <?
                             $sql='select * from ledger_group where status not in ("SUSPENDED") order by group_id';
                             $query=mysqli_query($conn, $sql);
                             if(mysqli_num_rows($query)>0){
                                 while($grp=mysqli_fetch_object($query)){
                                     $grp_id=(string)($grp->group_id*100000000);?>
                                     <li><label for="gid<?=$grp->group_id;?>"><a href="" onClick="changeBox('document.form1.gid<?=$grp->group_id;?>');return false;"><?=ledger_sepe($grp_id,$separator)?><?=' '.$grp->group_name;?></a><input name="gid" id="gid<?=$grp->group_id;?>" type="radio" value="<?=$grp->group_id;?>"  style="visibility:hidden;"/></label>
                                     <?
                                     $sql2='select * from accounts_ledger where ledger_id like "%00000000" and ledger_group_id='.$grp->group_id;
                                     $query2=mysqli_query($conn, $sql2);
                                     if(mysqli_num_rows($query2)>0){
                                         echo '<ul>';
                                         while($ledger=mysqli_fetch_object($query2)){
                                             ?>
                                             <li><label for="lid<?=$ledger->ledger_name;?>"><a href="" onClick="changeBox('document.form1.lid<?=$ledger->ledger_id;?>');return false;"><?=ledger_sepe(((string)($ledger->ledger_id)),$separator).' '?><?=$ledger->ledger_name;?></a><input name="lid" id="lid<?=$ledger->ledger_id;?>" type="radio" value="<?=$ledger->ledger_id;?>"  style="visibility:hidden;"/></label>

                                             <?
                                             $sql3='select * from sub_ledger where ledger_id='.$ledger->ledger_id;
                                             $query3=mysqli_query($conn, $sql3);
                                             if(mysqli_num_rows($query3)>0){
                                                 echo '<ul>';
                                                 while($sub_ledger=mysqli_fetch_object($query3)){
                                                     ?>
                                                     <li><label for="sid<?=$sub_ledger->sub_ledger_id;?>"><a href="" onClick="changeBox('document.form1.sid<?=$sub_ledger->sub_ledger_id;?>');return false;"><?=ledger_sepe(((string)($sub_ledger->sub_ledger_id)),$separator).' '?><?=$sub_ledger->sub_ledger;?></a><input name="sid" id="sid<?=$sub_ledger->sub_ledger_id;?>" type="radio" value="<?=$sub_ledger->sub_ledger_id;?>"  style="visibility:hidden;"/></label>

                                                         <?
                                                         $sql4='select * from sub_sub_ledger where sub_ledger_id='.$sub_ledger->sub_ledger_id;
                                                         $query4=mysqli_query($conn, $sql4);
                                                         if(mysqli_num_rows($query4)>0){?>
                                                             <ul>
                                                                 <? while($sub_sub_ledger=mysql_fetch_object($query4)){?>
                                                                     <li><a><?=$sub_sub_ledger->sub_sub_ledger_id;?>&nbsp;<?=$sub_sub_ledger->sub_sub_ledger;?></a></li>
                                                                 <? }?>
                                                             </ul>
                                                             <? }?> </li>



                                                 <? }?>
                                                 <? echo '</ul>'; }?>
                                             </li>
                                         <? }?>
                                         <? echo '</ul>'; }?>
                                     </li>
                                 <? }}?></ul>
                     </div>
                 </div>
                 </div>
             </form>
 <br><br>

        
 <?=$html->footer_content();?>