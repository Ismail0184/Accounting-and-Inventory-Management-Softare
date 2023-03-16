<?php
require_once 'support_file.php';
$title='Bill Wise GRN Summery';
$now=time();
$unique='id';
$table="grn_report_view";
$page='grn_bill_wise_GRN_setup.php';
$view_page='grn_list_view.php';

$crud      =new crud($table);
$$unique = $_GET[$unique];
$targeturl="<meta http-equiv='refresh' content='0;$page'>";

if(prevent_multi_submit()){

    if(isset($_POST['add_grn']))
    {
        $grnnos= $_POST['grn_no'];
        foreach ($grnnos as $i) {
            $grnnos = $i;
            $_POST[grn_no] = $grnnos;
            $_POST['status'] = 1;
            $_POST['section_id'] = $_SESSION['sectionid'];
            $_POST['companyid'] = $_SESSION['companyid'];
            $_POST['entry_by'] = $_SESSION['userid'];
            $_POST['powerdate'] = date('Y-m-d');
            $_POST['sl'] =$_POST['sl']+1;
            $_POST[ip] = $ip;
            $crud->insert();
            $type = 1;
            $msg = 'New Entry Successfully Inserted.';
        }
        unset($_POST);
        unset($$unique);
    }




//for Delete..................................
    if(isset($_POST['deleted']))
    {

        $crud = new crud($table_deatils);
        $condition =$unique."=".$$unique;
        $crud->delete_all($condition);

        $crud = new crud($table);
        $condition=$unique."=".$$unique;
        $crud->delete($condition);

        echo "<script>self.opener.location = '$page'; self.blur(); </script>";
        echo "<script>window.close(); </script>";
    }}

//for single FG Delete..................................
$result=mysqli_query($conn, "Select g.*,g.id as grid,SUM(p.amount) as grn_amount,p.rcv_Date,p.pr_no,p.po_no,i.* from 
				grn_report_view g ,
				purchase_receive p,
				item_info i
				
				where 
				g.grn_no=p.pr_no and  				
				p.item_id=i.item_id group by p.pr_no
				order by g.sl,g.grn_no");
while($row=mysqli_fetch_object($result)){
    $ids=$row->grid;
    if(isset($_POST['deletedata'.$ids]))
    {
        $del=mysqli_query($conn,"DELETE FROM ".$table." WHERE id='$ids'");
        unset($_POST);
    }}

// data query..................................
if(isset($$unique))
{   $condition=$unique."=".$$unique;
    $data=db_fetch_object($table,$condition);
    while (list($key, $value)=each($data))
    { $$key=$value;}}
?>

<?php require_once 'header_content.php'; ?>
    <SCRIPT language=JavaScript>
        function reload(form)
        {
            var val=form.user_id.options[form.user_id.options.selectedIndex].value;
            self.location='user_permission.php?user_id=' + val ;
        }
    </script>
    <script type="text/javascript">
        function DoNavPOPUP(lk)
        {myWindow = window.open("<?=$view_page?>?<?=$unique?>="+lk, "myWindow", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,directories=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=500,left = 250,top = -1");}
    </script>
<?php require_once 'body_content.php'; ?>





    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?=$title?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">GRN List<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select multiple class="select2_single form-control" style="width: 100%;" tabindex="-1" required="required" name="grn_no[]" id="grn_no">
                                    <option></option>
                                    <? $sql_user_id="SELECT  pr.pr_no,concat('GRN: ',pr.pr_no,' : ','PO:',pr.po_no) FROM 						 
							purchase_receive pr
							 where 
							 1 		group by 	pr.pr_no 
							  order by pr.pr_no";
                                    advance_foreign_relation($sql_user_id,$grnno);?>
                                </select>
                            </div>
                        </div>





                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Serial<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" name="sl" id="sl" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>







                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php if($_GET[type]){ ?>
                                <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</button>
                                    <button type="submit" name="edit" class="btn btn-success" style="font-size: 12px">Edit</button>
                                    <?php } else { ?>
                                        <a class="btn btn-danger" href="<?=$page;?>" style="font-size: 12px">Cancel</a>
                                        <button type="submit" name="add_grn" id="add_grn" class="btn btn-primary" style="font-size: 12px">Add GRN</button>
                                    <?php } ?>
                            </div></div>

                    </form>
                </div></div></div></div>











    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><? //$title?></h2>
                <div class="clearfix"></div>
            </div>


            <div class="x_content">
                <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left" style="font-size: 11px">
                <table id="datatable-buttons" class="table table-striped table-bordered" style="width: 100%; font-size: 11px">
                    <thead>
                    <tr>
                        <th style="width: 1%">SL</th>
                        <th style="">GRN NO</th>
                        <th style="">PO NO</th>
                        <th style="">GRN Date</th>
                        <th style="">GRN Amount</th>
                        <th style="">GRN By</th>
                        <th style="" align="center">Option</th>
                    </tr>
                    </thead>


                    <tbody>

                    <?php
                    $result=mysqli_query($conn, "Select g.*,g.id as grid,SUM(p.amount) as grn_amount,p.rcv_Date,p.pr_no,p.po_no,i.*,u.fname from 
				grn_report_view g ,
				purchase_receive p,
				item_info i,
				users u
				
				where 
				g.grn_no=p.pr_no and  				
				p.item_id=i.item_id and 
				 g.entry_by=u.user_id
				 group by p.pr_no
				order by g.sl,g.grn_no");
                    while($row=mysqli_fetch_object($result)){
                        $ids=$row->grid;
                        $j=$j+1; ?>
                        <tr >
                            <td onclick="DoNavPOPUP('<?=$row->grid;?>', 'TEST!?', 600, 700)"><?php echo $j; ?></td>
                            <td onclick="DoNavPOPUP('<?=$row->grid;?>', 'TEST!?', 600, 700)"><?=$row->pr_no; ?></td>
                            <td onclick="DoNavPOPUP('<?=$row->grid;?>', 'TEST!?', 600, 700)"><?=$row->po_no; ?></td>
                            <td onclick="DoNavPOPUP('<?=$row->grid;?>', 'TEST!?', 600, 700)"><?=$row->rcv_Date; ?></td>
                            <td onclick="DoNavPOPUP('<?=$row->grid;?>', 'TEST!?', 600, 700)" style="text-align: right"><?=number_format($row->grn_amount,2); ?></td>
                            <td onclick="DoNavPOPUP('<?=$row->grid;?>', 'TEST!?', 600, 700)" style="text-align: right"><?=$row->fname; ?></td>
                            <td style="text-align: center"><button type="submit" name="deletedata<?php echo $ids; ?>" style="background-color:transparent; border:none" onclick='return window.confirm("Mr. <?php echo $_SESSION["username"]; ?>, Are you sure you want to Delete?");'><img src="delete.png" style="width:15px;  height:15px"></button>
                            </td>
                        </tr>
                    <?php } ?></tbody></table></form>
            </div></div></div>
<?php require_once 'footer_content.php' ?>