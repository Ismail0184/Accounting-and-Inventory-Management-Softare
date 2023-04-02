<?php
require_once 'support_file.php';
$page = 'sales.php';
$table_master='sales_data_from_prism_software_filterd';
$unique_master='id';
$crud      =new crud($table_master);



if(prevent_multi_submit()){
if(isset($_POST["Import"])){
    echo $filename=$_FILES["file"]["tmp_name"];
    if($_FILES["file"]["size"] > 0)
    {
        $file = fopen($filename, "r");
        while (($eData = fgetcsv($file, 10000, ",")) !== FALSE)
        { if(!empty($eData[0]) && $eData[1]>0) {
                $entry_at = date('Y-m-d H:i:s');
                $sql = "INSERT INTO `sales_data_from_prism_software` 
    (`route`,`section`,`A200010001`,`A200010002`,`A200010003`,`A200010004`,
`A200010005`,`A200010007`,`A200010008`,`A200010009`,`A200010011`,`A200010010`,`A200010012`,`A200010013`,`A200010014`,`A200010015`,
`A200010016`,`A200010017`,`A200010018`,`A200010019`,`A200010020`,`A200010021`,`A200010022`,`A200010023`,`A200010024`,`A200010025`,
`A200010026`,`A200010027`,`A200010028`,`A200010029`,`A200010030`,`A200010031`,`A200010032`,`A200010033`,`A200010034`,`A200010035`,
`A200010036`,`A200010037`,`A200010038`,`A200010039`,`A200010041`,`A200010042`,`A200010043`,`A200010044`,`A200010046`,`A200010045`,
`A200010040`,`A200010047`,`A200010048`,`A200010049`,`A200010050`,`A200010051`,`A200010052`,`A200010053`,`A200010054`,`A200010055`,
`A200010056`,`A200010057`,`A200010058`,`A200010059`,`A200010060`,`A200010061`,`A200010062`,`A200010063`,`A200010065`,`A200010064`,                                              
`A200010066`,`A200010067`,`A200010068`,`A200010069`,`A200010070`,`A200010071`,`A200010072`,`A200010073`,`A200010074`,`A200010075`,
`A200010076`,`A200010077`,`total`,`entry_by`,`entry_at`,`status`,`section_id`,`company_id`) 
	         VALUES('$eData[0]','$eData[1]','$eData[2]','$eData[3]','$eData[4]','$eData[5]',
'$eData[6]','$eData[7]','$eData[8]','$eData[9]','$eData[10]','$eData[11]','$eData[12]','$eData[13]','$eData[14]','$eData[15]',
'$eData[16]','$eData[17]','$eData[18]','$eData[19]','$eData[20]','$eData[21]','$eData[22]','$eData[23]','$eData[24]','$eData[25]',
'$eData[26]','$eData[27]','$eData[28]','$eData[29]','$eData[30]','$eData[31]','$eData[32]','$eData[33]','$eData[34]','$eData[35]',
'$eData[36]','$eData[37]','$eData[38]','$eData[39]','$eData[40]','$eData[41]','$eData[42]','$eData[43]','$eData[44]','$eData[45]',
'$eData[46]','$eData[47]','$eData[48]','$eData[49]','$eData[50]','$eData[51]','$eData[52]','$eData[53]','$eData[54]','$eData[55]',
'$eData[56]','$eData[57]','$eData[58]','$eData[59]','$eData[60]','$eData[61]','$eData[62]','$eData[63]','$eData[64]','$eData[65]',
'$eData[66]','$eData[67]','$eData[68]','$eData[69]','$eData[70]','$eData[71]','$eData[72]','$eData[73]','$eData[74]','$eData[75]',
'$eData[76]','$eData[77]','$eData[78]','".$_SESSION['userid']."','".$entry_at."','MANUAL','".$_SESSION['sectionid']."','".$_SESSION['companyid']."'
)";
            }
            $result = mysqli_query( $conn, $sql);
            if(! $result )
            {
                echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
							window.location = ".$page."
						</script>";
            }}
        fclose($file);
        //throws a message if data successfully imported to mysql database from excel file
        echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = ".$page."
					</script>";
    }

    header("Location: ".$page."");
}}

if(isset($_POST['get_data']))
{
   unset($_SESSION['SL_route']);
   unset($_SESSION['To_route']);

    $_SESSION['SL_route'] = $_POST['SL_route'];
    $_SESSION['To_route'] = $_POST['To_route'];
}
?>

<?php require_once 'header_content.php'; ?>
    <style>
        input[type=text]{
            font-size: 11px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<?php require_once 'body_content.php'; ?>

    <form action="" name="addem" id="addem" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post">
            <input  name="section_id" type="hidden" id="section_id" value="<?=$_SESSION['sectionid']?>">
            <input style="width:155px;"  name="company_id" type="hidden" id="company_id" value="<?=$_SESSION['companyid']?>"/>
            <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
                <thead>
                <tr style="background-color: bisque">
                    <th style="text-align: center">Attachment ( .csv file)</th>
                    <th style="text-align: center"></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td align="center">
                        <input style="font-size:11px" type="file" id="file" name="file" value="<?=$file;?>" class="form-control col-md-7 col-xs-12" >
                    </td>
                    <td align="center" style="width:5%; vertical-align:middle">
                        <button type="submit" name="Import" onclick='return window.confirm("Are you confirm to Upload?");' class="btn btn-primary" style="font-size: 11px">Upload the File</button>
                    </td>
                </tr>
                </tbody>
            </table>
    </form>


    <form id="ismail" name="ismail"  method="post"  class="form-horizontal form-label-left">
        <table align="center" style="width:98%; font-size: 11px" class="table table-striped table-bordered">
            <thead>
            <?php
            $i = 0;
            $sql = mysqli_query($conn, "SELECT distinct route,section from sales_data_from_prism_software where status='manual' and section_id='".$_SESSION['sectionid']."' and company_id='".$_SESSION['companyid']."'");
            while($data=mysqli_fetch_object($sql)){ ?>
                <tr style="background-color: #00ADEE"><td colspan="3"><?=$data->route;?> : <?=$data->section;?></td></tr>
                <?php $sql2 = mysqli_query($conn, "SELECT * from item_info where 1 order by serial");
                while($data2=mysqli_fetch_object($sql2)){
                    $item_id = $data2->item_id;
                    $a='A';
                    $Get_qty=find_a_field('sales_data_from_prism_software',''.$a.$item_id.'',''.$item_id.'='.$data2->item_id.' and route='.$data->route.' and section in ("'.$data->section.'")');
                    $POST_route = $_POST['route'.$item_id];
                    $POST_section = $_POST['section'.$item_id];
                    $POST_item_id = $_POST['item_id_'.$item_id];
                    $POST_qty = $_POST['qty_'.$a.$item_id];
                    $_POST['item_id'] = $data2->item_id;
                    $_POST['route'] = $data->route;
                    $_POST['section'] = $data->section;
                    $_POST['qty'] = $Get_qty;
                    $_POST['entry_by'] = $_SESSION['userid'];
                    $_POST['status'] = 'UNCHECKED';
                    $_POST['section_id'] = $_SESSION['sectionid'];
                    $_POST['company_id'] = $_SESSION['companyid'];
                    if(isset($_POST['insert_into_database'])) {
                        $crud->insert();
                        unset($_POST);
                    }
                    ?>
                    <tr>
                        <td><?=$i=$i+1;?>
                            <input type="hidden" name="route<?=$data2->item_id;?>" value="<?=$data->route;?>">
                            <input type="hidden" name="section<?=$data2->item_id;?>" value="<?=$data->section;?>">
                            <input type="hidden" name="item_id_<?=$data2->item_id;?>" value="<?=$data2->item_id;?>"></td>
                        <td><?=$data2->item_id;?> : <?=$data2->item_name;?></td>
                        <td><input type="text" name="qty_<?=$a.$item_id?>" value="<?=$Get_qty?>"></td>
                    </tr>
                <?php }}?>
                </thead>
            </table>
            <input type="submit" name="insert_into_database" value="Confirm Data">
        </form>
            </div>
            </div>
            </div>
<?=$html->footer_content();?>