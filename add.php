<?php
    // include database and enities files
    include_once 'model/Database.php';
    include_once 'model/Post.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // название страницы
    $page_title = "Создание новости";
    include_once "layout/header.php";
?>

<div class="row">
    <div class="col-7 mx-auto">
        <h3>Создание новости</h3>

        <?php 
            // POST метод
            if($_POST){
                $post = new Post($db);

                $image = $_FILES["image"]["tmp_name"];

                $post->title = $_POST["title"];
                $post->description = $_POST["desc"];
                $post->image = base64_encode(file_get_contents($image));
                $post->imageMime = mime_content_type($image);

                if($post->create()) {
                    echo "<div class='alert alert-success'>Новость добавлена.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Не удалось добавить новость.</div>";
                }
            }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label" for="title">Название</label>
                <input class="form-control" type="text" name="title" placeholder="name">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Картинка</label>
                <!-- <input type="hidden" name="MAX_FILE_SIZE" value="1000000"> -->
                <input class="form-control" type="file" name="image" size="40">
            </div>
            <div class="mb-3">
                <label class="form-label" for="desc">Описание</label>
                <textarea class="form-control" name="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
</div>

<?php
    include_once "layout/footer.php";
?>