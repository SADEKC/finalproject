<?php 
include "../Model/DatabaseConnection.php";
$db = new DatabaseConnection();
$connection = $db->openConnection();
$result = $db->getAllCategory($connection,"categories");
?>
<html>
<body>
<h2>Add Category</h2>
<form method="post" action="../Controller/categoryController.php">
<input type="text" name="category_name" placeholder="Enter Category"/>
<input type="submit" name="addCategory"value="Add"/>
</form>
<br><br>
<table border="1">
<tr>
<th>ID</th>
<th>Name</th>
<th>Delete</th>
</tr>
<?php 
while($row = $result->fetch_assoc()){
    $id = $row["id"];
    $name = $row["name"];
    echo "<tr><td>$id</td> <td>$name</td> <td> <a href='../Controller/categoryController.php?delete=$id'> Delete</a></td></tr>";
}
?>
</table>
</body>
</html>