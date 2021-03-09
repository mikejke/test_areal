<?php
    /**
     * delete.php скрипт для удаления Новостей.
     */
    if($_POST){
        include_once "config/Database.php";
        include_once "model/Post.php";

        $database = new Database();
        $db = $database->getConnection();

        $post = new Post($db);
          
        $post->id = $_POST["id"];
   
        if($post->delete()){
            echo "true";
        } else {
            echo "false";
        }
    }
?>