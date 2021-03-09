<?php
/**
 * Post новость (название, дата публикации, картинка, описание)
 */
class Post {
    private $connection;            // бд
    private $tableName = "posts";   // название таблицы

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
     * @return bool true или false в зависимости от результата
     */
    public function create() {
        $query =
        "INSERT INTO {$this->tableName}
        SET title       = :title, 
            created_at  = :created_at, 
            image       = :image, 
            image_mime  = :image_mime, 
            description = :description";

        $stmt = $this->connection->prepare($query);

        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone("Europe/Moscow"));

        $this->createdAt = $datetime->format("Y-m-d H:i:s");

        $this->title        = htmlspecialchars(strip_tags($this->title));
        $this->createdAt    = htmlspecialchars(strip_tags($this->createdAt));
        $this->image        = htmlspecialchars(strip_tags($this->image));
        $this->imageMime    = htmlspecialchars(strip_tags($this->imageMime));
        $this->description  = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":created_at", $this->createdAt);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":image_mime", $this->imageMime);
        $stmt->bindParam(":description", $this->description);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * readAll читает все записи таблицы Posts
     * @param int $offset смещение на
     * @param int $postsPerPage количество элементов в запросе
     * @param string $order_by сортировка по..
     * @param string $order в каком порядке
     */
    public function readAll($offset, $postsPerPage, $order_by = "title", $order="ASC") {
        $query = 
        "SELECT 
            id, 
            title, 
            description, 
            image, 
            image_mime, 
            created_at 
        FROM {$this->tableName} 
        ORDER BY {$order_by} {$order} 
        LIMIT {$offset}, {$postsPerPage}";
      
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * read читает запись по id
     * @param int $id Идентификатор новости в базе данных
     * @return bool true или false в зависимости от результата
     */
    function read($id){
        $query = 
        "SELECT 
            title, 
            description, 
            image, 
            image_mime, 
            created_at 
        FROM {$this->tableName} 
        WHERE id = :id";
      
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // если совпадение в базе данных не найдено возвращаем false
        if(!$row) {
            return false;
        }
        $this->id           = $id;
        $this->title        = $row['title'];
        $this->createdAt    = $row['created_at'];
        $this->image        = $row['image'];
        $this->imageMime    = $row['image_mime'];
        $this->description  = $row['description'];

        return true;
    }

    /**
     * update вносит изменения в таблицу Posts
     * @return bool true или false в зависимости от результата
     */
    function update(){
        $query = 
        "UPDATE {$this->tableName} 
        SET title       = :title,
            image       = :image, 
            image_mime  = :image_mime,
            description = :description 
        WHERE id = :id";
      
        $update = $this->connection->prepare($query);

        $this->title        = htmlspecialchars(strip_tags($this->title));
        $this->image        = htmlspecialchars(strip_tags($this->image));
        $this->imageMime    = htmlspecialchars(strip_tags($this->imageMime));
        $this->description  = htmlspecialchars(strip_tags($this->description));
        $this->id           = htmlspecialchars(strip_tags($this->id));

        $update->bindParam(":title", $this->title);
        $update->bindParam(":image", $this->image);
        $update->bindParam(":image_mime", $this->imageMime);
        $update->bindParam(":description", $this->description);
        $update->bindParam(":id", $this->id);

        if($update->execute()) {
            return true;
        }
        return false;  
    }

    /**
     * delete удаляет запись из таблицы Posts
     * @return bool true или false в зависимости от результата
     */
    function delete(){
        $query = 
            "DELETE 
            FROM {$this->tableName} 
            WHERE id = :id";
        
        $delete = $this->connection->prepare($query);
        $delete->bindParam(":id", $this->id);
    
        if($delete->execute()){
            return true;
        }
        return false;
    }

    /**
     * countRows возвращает количество записей в таблице Posts
     */
    public function countRows() {
        $query = 
        "SELECT * 
        FROM {$this->tableName}";
    
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
  
        return $stmt->rowCount();
    }
}
?>