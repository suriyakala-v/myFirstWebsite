<?php
// Start the session
session_start();
//if not login then redirect to login page
if(empty($_SESSION['userName'])){
    header('Location: adminLoginPage.html');
}
//LOGOUT
if(isset($_POST['btn_logout'])){
    //unset session variable
    unset($_SESSION['userName']);
    header('Location: adminLoginPage.html');
}
//db info
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
$count="";
$queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where createddate=23");
$queryrun->execute();//returns 1 or 0
$queryrun->setFetchMode(PDO::FETCH_OBJ);
//search button
if(isset($_POST['btn_search'])){
    if(isset($_POST['sel_category']) && isset($_POST['txt_filter']) ){
        $filter=$_POST['txt_filter'];
        $user_category=$_POST['sel_category'];
        $queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where category_details.categoryname='$user_category' and productname like '$filter%' and createddate=23");
        $queryrun->execute();
        $queryrun->setFetchMode(PDO::FETCH_OBJ);//..PDO::FETCH_ASSOC OBJ-arryofObj
        $queryrun = $queryrun->fetchall();//fetchall or select *
    }
    elseif(empty($_POST['txt_filter']) && isset($_POST['sel_category'])){
        $user_category=$_POST['sel_category'];
        $queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where categoryname='$user_category' and createddate=23");
        $queryrun->execute();
        $queryrun->setFetchMode(PDO::FETCH_OBJ);
        $count=$queryrun->rowcount();
        echo "c".$count;
    }
    elseif(empty($_POST['sel_category']) && isset($_POST['txt_filter'])){
        $filter=$_POST['txt_filter'];
        $queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where productname like '$filter%' and createddate=23");
        $queryrun->execute();
        $queryrun->setFetchMode(PDO::FETCH_OBJ);
        $count=$queryrun->rowcount();
    }
}

if(isset($_GET['productid'])){
    $id=$_GET['productid'];
    $update_que = "UPDATE product_details SET createddate=null where productid=$id";
    $conn->exec($update_que);
    echo "DELETED";
    header('Location: manageProduct.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage product list page</title>
    <link rel="stylesheet" href="assets/css/manageProduct.css">
</head>
<body>
    <div class="topnav">
        <img src="./assets/img/logo.PNG" alt="logo.PNG" />
        <a href="manageProduct.php">MANAGE PRODUCT</a>
        <a href="manageCategory.php">MANAGE CATEGORY</a>
    </div>
    <h1>PRODUCT LIST PAGE</h1>
    <div class="filterSection">
        <form method="post">
            Product Name:<input type="text" name=txt_filter>
            <select name='sel_category'>
            <option disabled selected value='Choose category'>Choose category</option>
            <?php
            $query = $conn->prepare("SELECT * FROM category_details");
            $query->execute();//returns 1 or 0
            $query->setFetchMode(PDO::FETCH_OBJ);
            foreach($query as $cate){
                echo "
                <option value='$cate->categoryname'>".$cate->categoryname."</option>
                ";
            }
            ?>
            </select>
            <input type="submit" value="SEARCH" name="btn_search" id="btn_search">
            <input type="submit" value="SHOW ALL" name="btn_all" id="btn_all"><br><br>
            <input type="submit" name='btn_logout' id='btn_logout' value="LOGOUT"><br><br>
            <!-- for get value=0 -->
            <?php echo "<a href=addProductForm.php?p_id=-1><input type='button' name='btn_add_product' id='btn_add_product' value='Add New Product'></a>" ?><br><br>
        </form>
    </div>
    <div class="listProducts">
        <table>
            <tr>
                <th>SI NO</th>
                <th>PRODUCT NAME</th>
                <th>PRODUCT CATEGORY</th>
                <th>PRICE</th>
                <th>ACTION</th>
            </tr>
            <?php
            if($count==0){
                echo"<style>table{display:none}</style>";
                echo "Data not found";
           }else{
            foreach($queryrun as $row){
            echo "<tr>
            <td>".$row->productid."</td>
            <td>".$row->productname."</td>
            <td>".$row->categoryname."</td>
            <td>".$row->productprice."</td>
            <td> <a href='addProductForm.php?p_id=$row->productid'><input type='button' value='EDIT' name='btn_edit' id='btn_edit'></a>
            <a href='manageProduct.php?productid=$row->productid'><input type='submit' value='DELETE' name='btn_delete' id='btn_delete'><br></a>
            </tr>";
            }
           }
            ?>
        </table>
    </div>
</body>
</html>