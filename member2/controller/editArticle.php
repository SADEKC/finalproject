
<?php
include "../Model/DatabaseConnection.php";
session_start();

$db = new DatabaseConnection();
$connection = $db->openConnection();

if(isset($_POST["updateArticle"])){
    $id = $_POST["id"];
    $title = $_POST["title"];
    $body = $_POST["body"];
    $category_id = $_POST["category_id"];

    $hasTitleError = true;
    $hasBodyError = true;

    if(!$title){
        $hasTitleError = true;
        $_SESSION["titleError"] = "Title is required";
    }else{
        $hasTitleError = false;
        unset($_SESSION["titleError"]);
    }

    if(!$body){
        $hasBodyError = true;
        $_SESSION["bodyError"] = "Body is required";
    }else{
        $hasBodyError = false;
        unset($_SESSION["bodyError"]);
    }

    if($hasTitleError || $hasBodyError){
        $_SESSION["title"] = $title;
        $_SESSION["body"] = $body;
        header("Location: ../View/editArticleForm.php?id=" . $id);
        exit();
    }

    $result = $db->updateArticle($connection, "articles", $id, $title, $body, $category_id);

    if($result){
        header("Location: ../View/dashboard.php");
        exit();
    }
}
?>
