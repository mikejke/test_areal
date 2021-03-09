<?php
    $page_title = "Создание новости";
    include_once "layout/header.php";

    include_once 'config/Database.php';
    include_once 'model/Post.php';

    $database = new Database();
    $db = $database->getConnection();
?>

<div class="row">
    <div class="col-lg-7 mx-lg-auto">
        <h3><?=$page_title?></h3>

        <?php 
            // POST метод
            if($_POST){
                $post = new Post($db);

                $image = $_FILES["image"]["tmp_name"];
                
                $post->title = $_POST["title"];
                $post->description = $_POST["desc"];
                if($image) {
                    $post->image = base64_encode(file_get_contents($image));
                    $post->imageMime = mime_content_type($image);
                }
                
                if($post->create()) {
                    echo "<div class='alert alert-success'>
                        Новость добавлена
                    </div>";
                } else {
                    echo "<div class='alert alert-danger'>
                        Не удалось добавить новость
                    </div>";
                }
            }
        ?>

        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label" for="title">Заголовок</label>
                <input class="form-control" type="text" name="title" placeholder="name" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Картинка</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304" /> 
                <input class="form-control" type="file" name="image" size="40" accept=".jpg, .jpeg, .png">
            </div>
            <div class="mb-3 d-flex justify-content-center">
                <img src="" alt="превью" width="100%" class="d-none"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="desc">Описание</label>
                <textarea class="form-control" name="desc" rows="3" placeholder="Описание" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
            <a class="btn btn-secondary" href="index.php">Отмена</a>
        </form>
    </div>
</div>

<?php
    include_once "layout/footer.php";
?>

<script type="text/javascript">
    // превью картинки
    document.querySelector('input[type="file"]').onchange = (e) => {
        const image = document.querySelector('img');

        const target = e.target || window.event.srcElement,
        files = target.files;
        
        const fr = new FileReader();
        fr.onload = () => {
            image.src = fr.result;
        }
        fr.readAsDataURL(files[0]);
        image.classList.remove("d-none");
    }
</script>