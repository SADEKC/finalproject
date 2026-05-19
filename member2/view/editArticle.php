<?php 
include "../Model/DatabaseConnection.php";
$db = new DatabaseConnection();
$connection = $db->openConnection();

$id = $_GET["id"];
$result = $db->getArticleById($connection,"articles",$id);
$data = $result->fetch_assoc();
$categoryResult = $db->getAllCategory($connection,"categories");
?>
<html>
<body>
<h2>Edit Article</h2>
<form method="post" action="../Controller/editArticle.php">
<input type="hidden" name="id" value="<?php echo $data['id'];?>"/>
Title
<input type="text" name="title" value="<?php echo $data['title'];?>"/>
<br><br>
Body
<textarea name="body"><?php echo $data['body'];?></textarea>
<br><br>
<select name="category_id">
<?php 
while($row = $categoryResult->fetch_assoc()){

    $category_id = $row["id"];
    $category_name = $row["name"];

    echo "<option value='$category_id'> $category_name</option>";
}
?>
</select>
<br><br>
<input type="submit" name="updateArticle" value="Update"/>
</form>
</body>
</html>