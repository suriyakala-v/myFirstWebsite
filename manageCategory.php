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
$queryrun = $conn->prepare("SELECT * FROM category_details");
//$queryrun = $conn->prepare("SELECT category_details.categoryid, categoryname, count(product_details.categoryid) as total FROM category_details inner join product_details on category_details.categoryid=product_details.categoryid  GROUP BY categoryid ");
$queryrun->execute();
$queryrun->setFetchMode(PDO::FETCH_OBJ);

//search button
if(isset($_POST['btn_search'])){
    if(isset($_POST['txt_filter']) ){
        $filter=$_POST['txt_filter'];
        $queryrun = $conn->prepare("SELECT category_details.categoryid, categoryname FROM category_details  where categoryname like '$filter%'");
        $queryrun->execute();
        $queryrun->setFetchMode(PDO::FETCH_OBJ);//..PDO::FETCH_ASSOC OBJ-arryofObj
        $count=$queryrun->rowcount();
    }
}
//add category button
if(isset($_POST['btn_add_category'])){
    header('Location: addCategoryForm.php');
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
    <h1>CATEGORY LIST PAGE</h1>
    <div class="filterSection">
        <form method="post">
            Category Name:<input type="text" name=txt_filter>
            <input type="submit" value="SEARCH" name="btn_search" id="btn_search">
            <input type="submit" value="SHOW ALL" name="btn_all" id="btn_all"><br><br>
            <input type="submit" name='btn_logout' id='btn_logout' value="LOGOUT"><br><br>
            <?php echo  "<a href='addCategoryForm.php?c_id=-1'> <input type='button' name='btn_add_category' id='btn_add_category' value='Add New category'> </a>"?>
            <br><br>
        </form>
    </div>
    <div class="listProducts">
        <table>
            <tr>
                <th>SI NO</th>
                <th>CATEGORY NAME</th>
                <th>NO OF PRODUCTS</th>
                <th>ACTION</th>
            </tr>
            <?php
               if($count==0){
                   echo"<style>table{display:none}</style>";
                   echo "Data not found";
               }else{
                foreach($queryrun as $row){
                $query = $conn->prepare("SELECT count(productid) as total FROM  product_details where categoryid=$row->categoryid and createddate=23");
                $query->execute();
                $query->setFetchMode(PDO::FETCH_OBJ);
                foreach($query as $q){  //2 loops for count no. of products
                    echo "<tr>
                    <td>".$row->categoryid."</td>
                    <td>".$row->categoryname."</td>
                    <td>".$q->total."</td>
                    <td> <a href='addCategoryForm.php?c_id=$row->categoryid'><input type='button' value='EDIT' name='btn_edit' id='btn_edit'></a>
                    </tr>";
                    }
                  }
                }
            ?>
        </table>
    </div>
</body>
</html>