<?php
include "../Model/DatabaseConnection.php";
session_start();
$db = new DatabaseConnection();
$connection = $db->openConnection();
// add catagory
if(isset($_POST["addCategory"])){
    $name = $_POST["category_name"];
    $hasCategoryError = true;

    if(!$name){
        $hasCategoryError = true;
        $_SESSION["categoryError"] = "Category name is required";
    }else{
        $hasCategoryError = false;
        unset($_SESSION["categoryError"]);
    }

    if($hasCategoryError){
        $_SESSION["category_name"] = $name;
        header("Location: ../View/categories.php");
        exit();
    }

    $result = $db->addCategory($connection, "categories", $name);

    if($result){
        header("Location: ../View/categories.php");
        exit();
    }
}

// delete catagory
if(isset($_GET["delete"])){
    $id = $_GET["delete"];

    $result = $db->deleteCategory($connection, "categories", $id);

    if($result){
        header("Location: ../View/categories.php");
        exit();
    }
}
?>
