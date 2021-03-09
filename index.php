<?php
    $page_title = "Новости";
    include_once "layout/header.php";

    $page = isset($_GET['page']) && is_numeric($_GET['page'])
        ? $_GET['page'] 
        : 1;

    $postsPerPage = 5;                                     // количество постов на странице
    $offset = ($postsPerPage * $page) - $postsPerPage;     // смещение запроса

    include_once 'config/Database.php';
    include_once 'model/Post.php';

    $database = new Database();
    $db = $database->getConnection();
    
    $post = new Post($db);

    $stmt = $post->readAll($offset, $postsPerPage);
    $count = $stmt->rowCount();
?>

        <h3>Новости</h3>
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="add.php">Добавить новость</a>
        </div>
        <?php if($count > 0): ?>   
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Картинка</th>
                    <th scope="col">Дата добавления</th>
                    <th scope="col">Дата добавления</th>
                </tr>
            </thead>
            <tbody> 
                <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    extract($row);
                    // base64 image
                    if($image){
                        $src = "data:".$image_mime."; base64, ".$image;
                    }
                ?>
                    <tr>
                        <td><?= $title ?></td>
                        <td><?= $description ?></td>
                        <td>
                            <?= $image ? "<img src='{$src}' alt='{$title}' width='200' height='200' class='img-thumbnail'/>" : "" ?>
                        </td>
                        <td><?= $created_at ?></td>
                        <td>
                            <a href="edit.php?id=<?=$id?>" class="btn btn-info">Изменить</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
        <nav>
            <?php
            $postCount = $post->countRows();   
            include_once "service/Pagination.php"
            ?>
        </nav>
        <?php else: ?>
                <div class='alert alert-info'>Новостей не найдено</div>
        <?php endif ?>
                
<?php
    include_once "layout/footer.php";
?>