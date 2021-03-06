<?php
    // название страницы
    $page_title = "Новости";
    include_once "layout/header.php";

    // пагинация
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    $posts_per_page = 5;                                                // количество постов на странице
    $offset = ($posts_per_page * $page) - $posts_per_page;     // с какой записи начинать Select
    
    // include database and object files
    include_once 'model/Database.php';
    include_once 'model/Post.php';
    
    // instantiate database and objects
    $database = new Database();
    $db = $database->getConnection();
    
    $post = new Post($db);
    
    // query products
    $stmt = $post->readAll($offset, $posts_per_page);
    $count = $stmt->rowCount();

    if($_SERVER['REQUEST_METHOD']=="POST") {
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }
?>

        <h3>Новости</h3>
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="add.php">Добавить новость</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Картинка</th>
                    <th>Дата добавления</th>
                    <th>Дата добавления</th>
                </tr>
            </thead>
            <tbody>
        <?php if($count > 0): ?>    
            <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                extract($row);
                $src = "data:".$image_mime."; base64, ".$image;
            ?>
                <tr>
                    <td><?= $title ?></td>
                    <td><?= $description ?></td>
                    <td><img src="<?= $src ?>" alt="title" width="300" height="150"/></td>
                    <td><?= $created_at ?></td>
                    <td>
                        <!-- read one, edit and delete button will be here -->
                    </td>
                </tr>
            <?php endwhile ?>
        <?php echo "</table>";
        // paging buttons will be here
        ?>
        <?php else: ?>
            <?= "<div class='alert alert-info'>Новостей не найдено</div>" ?>
        <?php endif ?>
            </tbody>

<?php
    include_once "layout/footer.php";
?>