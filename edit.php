<?php
    $page_title = "Редактирование";
    include_once "layout/header.php";
?>
<div class="row">
    <div class="col-lg-7 mx-lg-auto">
        <h3><?=$page_title?></h3>
        <?php
            $id = isset($_GET['id']) && is_numeric($_GET['id'])
            ? $_GET['id'] 
            : null;
        ?>
        <?php if($id):    
            include_once 'config/Database.php';
            include_once 'model/Post.php';
            
            $database = new Database();
            $db = $database->getConnection();
            
            $post = new Post($db);
            $isFound = $post->read($id);

            if($_POST){
                $image = $_FILES["image"]["tmp_name"];
                
                $post->title = $_POST["title"];
                $post->description = $_POST["desc"];
                if($image) {
                    $post->image = base64_encode(file_get_contents($image));
                    $post->imageMime = mime_content_type($image);
                }

                if($post->update()) {
                    echo "<div class='alert alert-success'>
                        Новость изменена
                    </div>";
                } else {
                    echo "<div class='alert alert-danger'>
                        Не удалось обновить новость
                    </div>";
                }
            }
            $imageSrc = $post->image ? "data:{$post->imageMime}; base64, {$post->image}" : "";
        ?>
            <!-- Не нашел способа лучше  -->
            <?php if($isFound): ?>
                <form action="<?= htmlspecialchars("{$_SERVER["PHP_SELF"]}?id={$id}");?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="title">Заголовок</label>
                        <input class="form-control" type="text" name="title" placeholder="Заголовок" value="<?= $post->title ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Картинка</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="4194304" /> 
                        <input class="form-control" type="file" name="image" size="40" accept=".jpg, .jpeg, .png">
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <img src="<?= $imageSrc ?>" alt="<?=$post->title?>" width="200" class="img-thumbnail d-none"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="desc">Описание</label>
                        <textarea class="form-control" name="desc" rows="3" placeholder="Описание" required><?= $post->description ?></textarea>
                    </div>
                    <p class="text-muted">Новсть добавлена: <?=$post->createdAt?></p>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="button" class="btn btn-danger">Удалить</button>
                    <a class="btn btn-secondary" href="index.php">Отмена</a>
                </form>
            <?php else:?>
                <div class="alert alert-danger">Совпадений не найдено!</div>
            <?php endif; ?>
        <?php else:?>
            <div class="alert alert-danger">Совпадений не найдено!</div>
        <?php endif; ?>
    </div>
</div>
<?php
    include_once "layout/footer.php";
?>

<script type="text/javascript">
    const image = document.querySelector('img');
    const fileInput = document.querySelector('input[type="file"]');
    const deleteButton = document.querySelector('.btn-danger');

    // если есть картинка
    if(image) {
        if(image.src) {
            image.classList.remove("d-none");
        }
    }

    // превью картинки
    if(fileInput) {
        fileInput.onchange = e => {
            const target = e.target || window.event.srcElement,
            files = target.files;
            
            const fr = new FileReader();
            fr.onload = () => {
                image.src = fr.result;
            }
            fr.readAsDataURL(files[0]);
        }
    }
    
    if(deleteButton) {
        document.querySelector('.btn-danger').onclick = e => {
            const deleteRequest = new FormData();
            deleteRequest.append('id', '<?= $id ?>');

            fetch('delete.php', {
                method: 'POST',
                body: deleteRequest
            })
            .then((response) => response.text())
            .then((text) => {
                if(text==="true") {
                    document.location.href = document.referrer;
                } else {
                    console.log("Не удалось удалить новость!")
                }
            })
        }
    }
</script>