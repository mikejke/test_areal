<?php
/**
 * Post новость (название, дата публикации, картинка, описание)
 */
class Post {
    private $connection;          // бд
    private $tableName = "posts";    // название таблицы

    // свойства
    public $id;             // идентификатор
    public $title;          // название
    public $image;          // картинка
    public $imageMime;      // формат картинки
    public $description;    // описание
    public $createdAt;      // дата добавления

    /**
     * Конструктор, принимает базу данных
     */
    public function __construct($db){
        $this->connection = $db;
    }
    
    /**
     * create создает запись в базу данных 
     */
    public function create() {
        $query =
        "INSERT INTO ".$this->tableName." SET title=:title, created_at=:created_at, image=:image, image_mime=:image_mime, description=:description";

        $stmt = $this->connection->prepare($query);

        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone("Europe/Moscow")); // Часовой пояс

        $this->createdAt = $datetime->format("Y-m-d H:i:s");

        // Значения
        $this->name         = htmlspecialchars(strip_tags($this->title));
        $this->createdAt    = htmlspecialchars(strip_tags($this->createdAt));
        $this->image        = htmlspecialchars(strip_tags($this->image));
        $this->imageMime   = htmlspecialchars(strip_tags($this->imageMime));
        $this->description  = htmlspecialchars(strip_tags($this->description));

        // Привязка к запросу
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":created_at", $this->createdAt);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":image_mime", $this->imageMime);
        $stmt->bindParam(":description", $this->description);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * readAll читает все записи таблицы Posts
     * @param int $offset смещение на
     * @param int $posts_per_page количество элементов в запросе
     * @param string $order_by сортировка по..
     * @param string $order в каком порядке
     */
    public function readAll($offset, $posts_per_page, $order_by = "title", $order="ASC"){
        $query 
            = "SELECT id, title, description, image, image_mime, created_at 
            FROM ".$this->tableName." ORDER BY {$order_by} {$order} LIMIT {$offset}, {$posts_per_page}";
      
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>