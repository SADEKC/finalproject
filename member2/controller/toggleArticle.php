
<?php
include "../Model/DatabaseConnection.php";
session_start();

$db = new DatabaseConnection();
$connection = $db->openConnection();

$id = $_GET["id"];
$status = $_GET["status"];

$hasIdError = true;
$hasStatusError = true;

if(!$id){
    $hasIdError = true;
    $_SESSION["toggleError"] = "Article ID is required to toggle status";
}else{
    $hasIdError = false;
}

if(!$status){
    $hasStatusError = true;
    $_SESSION["toggleError"] = "Current status is required to toggle status";
}else{
    $hasStatusError = false;
}

if($hasIdError || $hasStatusError){
    header("Location: ../View/dashboard.php");
    exit();
}

unset($_SESSION["toggleError"]);

$newStatus = "";
if($status == "draft"){
    $newStatus = "published";
}else{
    $newStatus = "draft";
}

$result = $db->toggleArticle($connection, "articles", $id, $newStatus);

if($result){
    header("Location: ../View/dashboard.php");
    exit();
}
?>
