<?php
include "../Model/DatabaseConnection.php";
session_start();
$db = new DatabaseConnection();
$connection = $db->openConnection();
$id = $_GET["id"];
$hasIdError = true;

if(!$id){
    $hasIdError = true;
    $_SESSION["articleDeleteError"] = "Article ID is required to delete";
}else{
    $hasIdError = false;
    unset($_SESSION["articleDeleteError"]);
}

if($hasIdError){
    header("Location: ../View/dashboard.php");
    exit();
}

$result = $db->deleteArticle($connection, "articles", $id);

if($result){
    header("Location: ../View/dashboard.php");
    exit();
}
?>
