<?php
// Start the session
session_start();
//db info
$servername = "192.168.11.49";
$username = "root";
$password = "vins35@123456";
//db connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=surya", $username,$password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//settting attributes
    //echo "Connected successfully";
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$usr = $_POST['username'];
$pwd = $_POST['password'];
$queryrun = $conn->prepare("SELECT * FROM user_details where username='$usr' and password='$pwd' ");
$queryrun->execute();//returns 1 or 0
$result = $queryrun->setFetchMode(PDO::FETCH_ASSOC);
$result = $queryrun->fetch();
if(!empty($result)){
    echo json_encode("Valid");
    $_SESSION['userName']=$usr;
    $_SESSION['details']=$result;
}
else{
    echo json_encode("INVALID! user and password not match");
}
?>