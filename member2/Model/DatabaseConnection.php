<?php 

class DatabaseConnection{
    function openConnection(){
         $db_host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "project";

        $connection = new mysqli( $db_host,$db_user,$db_password,$db_name);

        if($connection->connect_error){

            die("couldnot connect to the database -" . $connection->connect_error);
        }

        return $connection;
    }

    // category

        function addCategory($connection, $tableName, $name){
        $sql = "INSERT INTO $tableName (name) VALUES('".$name."')";
        $result = $connection->query($sql);
        return $result;
    }

    function getAllCategory($connection, $tableName){
        $sql = "SELECT * FROM $tableName";
        $result = $connection->query($sql);
        return $result;
    }

    function deleteCategory($connection, $tableName, $id){
        $sql = "DELETE FROM $tableName WHERE id='".$id."'";
        $result = $connection->query($sql);
        return $result;
    }

    // article

    function createArticle($connection, $tableName, $author_id, $category_id, $title, $body, $image_path, $status, $publish_at){
        $sql = "INSERT INTO $tableName (author_id, category_id, title, body, featured_image_path, status, publish_at) VALUES('".$author_id."', '".$category_id."', '".$title."', '".$body."', '".$image_path."', '".$status."', '".$publish_at."')";
        $result = $connection->query($sql);
        return $result;
    }

    function getAllArticles($connection, $tableName){
        $sql = "SELECT * FROM $tableName ORDER BY id DESC";
        $result = $connection->query($sql);
        return $result;
    }

    function getArticleById($connection, $tableName, $id){
        $sql = "SELECT * FROM $tableName WHERE id='".$id."'";
        $result = $connection->query($sql);
        return $result;
    }

    function updateArticle($connection, $tableName, $id, $title, $body, $category_id){
        $sql = "UPDATE $tableName SET title='".$title."', body='".$body."', category_id='".$category_id."' WHERE id='".$id."'";
        $result = $connection->query($sql);
        return $result;
    }

    function deleteArticle($connection, $tableName, $id){
        $sql = "DELETE FROM $tableName WHERE id='".$id."'";
        $result = $connection->query($sql);
        return $result;
    }

    function toggleArticle($connection, $tableName, $id, $status){
        $sql = "UPDATE $tableName SET status='".$status."' WHERE id='".$id."'";
        $result = $connection->query($sql);
        return $result;
    }
}