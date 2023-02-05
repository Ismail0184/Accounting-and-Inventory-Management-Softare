<?php 

$post_data = array();

$post_data['store_id'] = "erosnowcombdlive";
$post_data['store_passwd'] = "5F6F22A6C74DE78492";
$post_data['action'] = "transaction";

$post_data['start_date'] = "2020-10-31 00:00:00";
$post_data['end_date'] = "2020-10-31 23:59:59";

$direct_api_url = "https://securepay.sslcommerz.com/validator/api/v4/";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url );
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1 );
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

$content = curl_exec($handle );

echo $content; exit;
?>