<?php

$servername = "192.168.11.49";
$productname = "root";
$password = "vins35@123456";
//db connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=surya", $productname,$password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//settting attributes
    //echo "Connected successfully";
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$queryrun = $conn->prepare("SELECT * FROM category_details");
// count of products
//$queryrun = $conn->prepare("SELECT category_details.categoryid, categoryname, count(product_details.categoryid) as total FROM category_details inner join product_details on category_details.categoryid=product_details.categoryid  GROUP BY categoryid ");
$queryrun->execute();
$queryrun->setFetchMode(PDO::FETCH_OBJ);
// foreach($queryrun as $row){
//     //print_r($row);
//      echo $row->total;
// } ****
// $queryrun->setFetchMode(PDO::FETCH_ASSOC);
// foreach($queryrun as $key){
//     print_r($key);
//     foreach($key as $k=>$v){
//         echo "<br>".$v;
//     }
// } ***
//print_r($queryrun);
foreach($queryrun as $row){

}
?>