<?php 

include "../Model/DatabaseConnection.php";

$db = new DatabaseConnection();

$connection = $db->openConnection();

$result = $db->getAllCategory(
    $connection,
    "categories"
);

?>

<html>
<body>

<h2>Create Article</h2>

<form
method="post"
action="../Controller/articleController.php"
enctype="multipart/form-data"
>

Title

<input
type="text"
name="title"
/>

<br><br>

Body

<textarea name="body"></textarea>

<br><br>

Image

<input
type="file"
name="image"
/>

<br><br>

Category

<select name="category_id">

<?php 

while($row = $result->fetch_assoc()){

    $id = $row["id"];
    $name = $row["name"];

    echo "

    <option value='$id'>

    $name

    </option>
    ";
}

?>

</select>

<br><br>

Tags

<input
type="text"
name="tags"
placeholder="php, mysql"
/>

<br><br>

Draft
<input
type="radio"
name="status"
value="draft"
checked
/>

Published
<input
type="radio"
name="status"
value="published"
/>

<br><br>

Publish Time

<input
type="datetime-local"
name="publish_at"
/>

<br><br>

<input
type="submit"
name="createArticle"
value="Create"
/>

</form>

</body>
</html>