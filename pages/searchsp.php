<?php
    //database configuration
    $dbHost = 'localhost';
    $dbUsername = 'prottash_tech';
    $dbPassword = 'Allahis1@##';
    $dbName = 'prottash_tech';
    
    //connect with the database
    $db = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
    
    //get search term
    $searchTerm = $_GET['term'];
    
    //get matched data from skills table
    $query = $db->query("SELECT * FROM procurement_supplier WHERE sname LIKE '%".$searchTerm."%' ORDER BY sname ASC");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['sname'];
    }
    
    //return json data
    echo json_encode($data);
?>