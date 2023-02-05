<?php

$api_key = 'p56e0z6ijre9r0ypb7w7ls0zrf6py7';
$barcode = $_GET[barcode];
$url = 'https://api.barcodelookup.com/v2/products?barcode='.$barcode.'&formatted=y&key=' . $api_key;

$ch = curl_init(); // Use only one cURL connection for multiple queries

$data = get_data($url, $ch);

$response = array();
$response = json_decode($data);

echo '<strong>Barcode Number:</strong> ' . $response->products[0]->barcode_number . '<br><br>';

echo '<strong>Product Name:</strong> ' . $response->products[0]->product_name . '<br><br>';

echo '<strong>Entire Response:</strong><pre>';
print_r($response);
echo '</pre>';

function get_data($url, $ch) {
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

?>