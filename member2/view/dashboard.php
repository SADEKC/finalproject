<?php 
include "../Model/DatabaseConnection.php";
$db = new DatabaseConnection();
$connection = $db->openConnection();
$result = $db->getAllArticles($connection,"articles");
?>

<html>
<body>
<h2>Dashboard</h2>
<a href="createArticle.php">Create Article</a>
<br><br>
<a href="categories.php">Manage Categories</a>
<br><br>
<table border="1">
<tr>
<th>ID</th>
<th>Title</th>
<th>Status</th>
<th>Views</th>
<th>Edit</th>
<th>Delete</th>
<th>Toggle</th>
</tr>

<?php 

while($row = $result->fetch_assoc()){

    $id = $row["id"];
    $title = $row["title"];
    $status = $row["status"];
    $views = $row["view_count"];

    echo " <tr>
    <td>$id</td>
    <td>$title</td>
    <td>$status</td>
    <td>$views</td>
    <td> <a href='editArticle.php?id=$id'>Edit</a></td>
    <td><a href='../Controller/deleteArticle.php?id=$id'> Delete</a></td>
    <td><a href='../Controller/toggleArticle.php?id=$id&status=$status'>Toggle</a></td>
    </tr>";
}
?>
</table>
</body>
</html>