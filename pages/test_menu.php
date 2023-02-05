<?php
require_once 'support_file.php';


function generateTreeView($products, $currentParent, $currLevel = 0, $prevLevel = -1) {
    foreach ($products as $productId => $product) {
        if ($currentParent == $product['parent_id']) {                       
            if ($currLevel > $prevLevel){
                echo " 
 
<ol class='tree'> "; 
            }
             
            if ($currLevel == $prevLevel){
                echo " </li>
 
 
 ";
            }
             
            $menuLevel = $product['parent_id'];
            if($product['hasChild'] > 0){
                $menuLevel = $productId;
            }
             
            echo '
 
<li> <label for="level'.$menuLevel.'">'.$product['name'].'</label><input type="checkbox" id="level'.$menuLevel.'"/>';
             
            if ($currLevel > $prevLevel) { 
                $prevLevel = $currLevel; 
            }
             
            $currLevel++; 
             
            generateTreeView ($products, $productId, $currLevel, $prevLevel);
            $currLevel--;
        }
    }
     
    if ($currLevel == $prevLevel) echo " </li>
 
</ol>
 
 
 ";
}
?>

<html>
<body>
<?php 

$res = mysqli_query($conn, "SELECT product.*, (SELECT COUNT(*) FROM `products` WHERE parent = product.id) as hasChild FROM `products` as product");  
$products = array(); 
while($row = mysqli_fetch_assoc($res)){ 
$products[$row['id']] = array("parent_id" => $row['parent'], "name" => $row['name'], "hasChild" => $row['hasChild']); 
}
?>
 
 
<div class="treemenu">
<?php if(count($products) > 0){
    generateTreeView($products, 0);
}
?>
</div>
 
 
</body>
</html>