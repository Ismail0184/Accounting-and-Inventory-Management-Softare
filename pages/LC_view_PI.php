<?php
require_once 'support_file.php';
$title="View PI";
$now=time();
$table="lc_pi_master";
$unique = 'id';   // Primary Key of this Database table
$table_details = 'lc_pi_details';
$details_unique = 'pi_id';
$page='LC_view_PI.php';
$crud      =new crud($table);
$$unique = $_GET[$unique];

if(prevent_multi_submit()) {

    if (isset($_POST['reprocess'])) {
        $_POST['status'] = 'MANUAL';
        $crud->update($table);
        $_SESSION['initiate_lc_proforma_invoice'] = $_GET[$unique];
        $type = 1;
        echo "<script>self.opener.location = 'LC_create_PI.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

    //for Delete..................................
    if (isset($_POST['delete'])) {
        $crud = new crud($table);
        $condition = $unique . "=" . $$unique;
        $crud->delete($condition);

        $crud = new crud($table_details);
        $condition = $details_unique . "=" . $$unique;
        $crud->delete_all($condition);
        unset($_SESSION['initiate_lc_proforma_invoice']);
        echo "<script>self.opener.location = 'LC_view_PI.php'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }

}
// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}


$rs=mysqli_query($conn, "Select 
d.qty,
d.amount,
d.rate,
i.*,cu.code,m.id
from 
".$table_details." d,
item_info i,
currency cu,
lc_pi_master m
  where 
 m.id=d.pi_id and 
 d.item_id=i.item_id and 
 m.currency=cu.id and 
 d.pi_id='".$_GET['id']."' group by d.id,d.fg_rate order by d.fg_id");
?>


<?php require_once 'header_content.php'; ?>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=830,height=500,left = 250,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>


<?php if(isset($_GET[$unique])){ ?>
    <!-- input section-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post">
                    <?require_once 'support_html.php';?>
                    <table  class="table table-striped table-bordered" style="width:100%; font-size: 11px">
                        <thead>
                        <tr style="background-color: blanchedalmond">
                            <th>SL</th>
                            <th>FG Name</th>
                            <th style="text-align:center">Unit Name</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Total Unit</th>
                            <th style="text-align:center">Total Unit Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($uncheckrow=mysqli_fetch_object($rs)){
                            $js=$js+1; ?>
                            <tr>
                                <td style="width:3%; vertical-align:middle"><?php echo $js; ?></td>
                                <td><?=$uncheckrow->finish_goods_code;?> -  <?=$uncheckrow->item_name;?></td>
                                <td style="text-align:right"><?=$uncheckrow->unit_name?></td>
                                <td align="center" style="width:10%; text-align:right"><?=number_format($uncheckrow->rate,2)?></td>
                                <td align="center" style="width:10%; text-align:center"><?=$uncheckrow->qty;?></td>
                                <td align="center" style="width:15%; text-align:right"><?=number_format($uncheckrow->amount,2);?> , <?=$uncheckrow->code;?></td>
                            </tr>
                            <?php
                            $amounttotal=$amounttotal+$uncheckrow->amount;
                            $qtytotal=$qtytotal+$uncheckrow->qty;
                            $currency=$uncheckrow->code;
                        } ?>
                        <tr style="font-weight: bold"><td colspan="4" style="text-align: right">Total = </td>
                            <td style="text-align: center"><?=$qtytotal;?></td>
                            <td style="text-align: right"><?=number_format($amounttotal,2)?>, <?=$currency;?></td></tr>
                        </tbody></table>
                    <?php
                    $PCOUNT = find_a_field('lc_lc_master', 'COUNT(id)', 'pi_id=' . $_GET[pi_id] . '');
                    if ($PCOUNT > 0) {
                        ?>
                        <p><h5 style="text-align: center; color: black; font-style: italic; color: red "><i><?=$PCOUNT;?>, LC has been created under this Proforma Invoice!!</i></h5></p>
                    <?php } else { ?>
                        <p><button style="float: left;font-size: 12px" type="submit" name="reprocess" id="reprocess" class="btn btn-primary" onclick='return window.confirm("Are you confirm?");'>Re-process the PI</button>
                            <button style="float: right;font-size: 12px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm?");'>Delete the PI</button>
                        </p>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(!isset($_GET[$unique])): ?>
    <form  name="addem" id="addem" class="form-horizontal form-label-left" method="post" >
        <table align="center" style="width: 50%;">
            <tr><td>
                    <input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[f_date])) echo $_POST[f_date]; else echo date('Y-m-01');?>" max="<?=date('Y-m-d');?>" required   name="f_date">
                <td style="width:10px; text-align:center"> -</td>
                <td><input type="date"  style="width:150px; font-size: 11px; height: 25px"  value="<?php if(isset($_POST[t_date])) { echo $_POST[t_date]; } else { echo date('Y-m-d'); }?>" max="<?=date('Y-m-d');?>" required   name="t_date"></td>
                <td style="padding:10px"><button type="submit" name="viewreport"  class="btn btn-primary" style="font-size: 12px">View Available PI</button></td>
            </tr></table>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table style="width:100%; font-size: 11px" class="table table-striped table-bordered">
                        <thead><tr style="background-color: bisque">
                            <th>#</th>
                            <th>ID</th>
                            <th>PI NO</th>
                            <th>PI Issue Date</th>
                            <th>Party Name</th>
                            <th>Entry By</th>
                            <th>Entry At</th>
                            <th>PI Value</th>
                            <th>Status</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        if(isset($_POST[viewreport])){
                            $con.= ' and a.pi_issue_date BETWEEN  "'.$_POST[f_data].'" and "'.$_POST[t_date]. '"';
                            $res=mysqli_query($conn, 'select a.id,a.id as ID,a.pi_no,a.pi_issue_date,a.entry_at,c.buyer_name as Party_Name,u.fname,cu.code,a.status, (select sum(amount) from lc_pi_fg_details where pi_id=a.id ) as amount
							 from 
							 lc_pi_master a,
							 lc_buyer c, 
							 users u,
							 currency cu
                            where 
                            a.party_id = c.party_id and 
                            a.entry_by = u.user_id and 
                            a.currency=cu.id
                             '.$con); } else {
                            $res=mysqli_query($conn, 'select a.id,a.id as ID,a.pi_no,a.pi_issue_date,a.entry_at,c.buyer_name as Party_Name,u.fname,cu.code,a.status, (select sum(amount) from lc_pi_fg_details where pi_id=a.id ) as amount
							 from 
							 lc_pi_master a,
							 lc_buyer c, 
							 users u,
							 currency cu
                            where 
                            a.party_id = c.party_id and 
                            a.entry_by = u.user_id and 
                            a.currency=cu.id');
                        }
                            while($data=mysqli_fetch_object($res)){
                                ?>
                                <tr>
                                    <td style="cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=$i=$i+1;?></td>
                                    <td style="cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=$data->ID;?></td>
                                    <td><a style="color: blue; font-weight: bold; text-decoration: underline" href="LC_proforma_view.php?pi_<?=$unique;?>=<?=$data->id;?>" target="_blank"><?=$data->pi_no;?></a></td>
                                    <td style="cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=$data->pi_issue_date;?></td>
                                    <td style="cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=$data->Party_Name;?></td>
                                    <td style="text-align: left;cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=$data->fname;?></td>
                                    <td style="text-align: left;cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=$data->entry_at;?></td>
                                    <td style="text-align: right;cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=number_format($data->amount,2);?>, <?=$data->code;?></td>
                                    <td style="text-align: center;cursor: pointer" onclick="DoNavPOPUP('<?=$data->id?>', 'TEST!?', 900, 600)"><?=$data->status;?></td>
                                </tr>
                                <?php
                                $totalamt=$totalamt+$data->amount;
                            } ?>
                        </tbody>
                    </table>
                </div></div></div></form>
<?php endif; ?>
<?=$html->footer_content();?>