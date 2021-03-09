<?php
    /**
     * Pagination.php служит компонентом кнопок пагинации
     */
    $pageUrl = strtok($_SERVER["REQUEST_URI"], "?");
    echo "<ul class='pagination'>" ?>
    
    <?php if($page > 1): ?>
        <li class='page-item'>
            <a class='page-link' href='<?=$pageUrl?>' title='На первую страницу.'>
            Начало
            </a>
        </li>
    <?php endif ?>

    <?php

    $totalPages = ceil($postCount / $postsPerPage);

    $btnRange = 2;
    $firstPage = $page - $btnRange;
    $lastPage = ($page + 1) + $btnRange;
    
    for ($i = $firstPage; $i < $lastPage; $i++) {
        if (($i > 0) && ($i <= $totalPages)) {
            if ($i == $page) {
                echo "<li class='page-item active'>
                    <a class='page-link'>
                        $i
                    </a>
                </li>";
            } else {
                echo "<li class='page-item'>
                    <a class='page-link' href='{$pageUrl}?page=$i'>
                    $i
                    </a>
                </li>";
            }
        }
    }
    ?>
    
    <?php if($page < $totalPages): ?>
        <li class='page-item'>
            <a class='page-link' href='<?=$pageUrl?>?page=<?=$totalPages?>' title='Последняя страница <?=$totalPages?>.'>
            Последняя
            </a>
        </li>
    <?php endif ?>
    
<?= "</ul>" ?>