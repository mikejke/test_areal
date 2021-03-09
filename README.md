## База данных mysql
Запрос для создания таблицы:
```
CREATE TABLE `posts` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `created_at` datetime NOT NULL,
 `image` longblob NOT NULL,
 `image_mime` varchar(255) NOT NULL,
 `description` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4
```
