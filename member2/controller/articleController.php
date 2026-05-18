   <?php
include "../Model/DatabaseConnection.php";
session_start();

$db = new DatabaseConnection();
$connection = $db->openConnection();

$author_id = 1;
$title = $_POST["title"];
$body = $_POST["body"];
$category_id = $_POST["category_id"];
$tags = $_POST["tags"];
$status = $_POST["status"];
$publish_at = $_POST["publish_at"];
$uploadFile = $_FILES["fileupload"];

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
    header("Location: ../View/createArticleForm.php");
    exit();
}

$uploadFile = $_FILES["fileupload"];
     $path = "";

    if($uploadFile){
            $uploadDirectory = "../uploads/articles/";
        $path = $uploadDirectory .basename($uploadFile["name"]);
        $response = move_uploaded_file($uploadFile["tmp_name"],$path);
        echo "path : " .$path;
        echo "<br/>Response :" .$response;
    }

$result = $db->createArticle($connection, "articles", $author_id, $category_id, $title, $body, $path, $status, $publish_at);

if($result){
    header("Location: ../View/dashboard.php");
    exit();
}
?>
