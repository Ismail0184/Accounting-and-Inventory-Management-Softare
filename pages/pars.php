<?php
echo GetMAC();

function GetMAC(){
    ob_start();
    system('getmac');
    $Content = ob_get_contents();
    ob_clean();
    return substr($Content, strpos($Content,'\\')-20, 17);
}

?>


<html>
<head>
</head>
<body>
    <form class="public" action="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" method="POST" enctype="multipart/form-data">
    <p><label for="file">File Name:</label> <input type="file" name="file" id="file" /></p>
    <br/>
    <p><input type="submit" value="Parsing" name="sendData"/></p>
    </form>

</body>
</html>

<?php
//test if the file is selected
if(!isset($_POST['sendData'])) exit;
//get file name
$filename = $_FILES["file"]["name"];
//be sure this file is html file
$ext = explode('.', $filename);
if($ext[1] != 'php'){
    echo '<br>bad file type, it must be php file';
    exit;
}
//upload the file to temp area
move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
//read the file
$file = @fopen($filename, 'r');
if($file === false){
    echo 'Error when reading the file';
    exit;
}
//reading line by line
$output ='';
while (($line = fgets($file)) !== FALSE) {
    $output .= str_replace('<img', '<svg', $line);
}
fclose($file);
//write the new file
$result = file_put_contents($filename, $output);
echo $result;
if(!$result){
    echo 'faild';
}else{
    echo 'success <br/>';
    $load_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $load_link = str_replace('pars.php', '', $load_link);
    $load_link .= $filename;
    echo '<a href='.$load_link.'>See the results</a><br>';
}


?>
