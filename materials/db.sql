CREATE DATABASE mikolenko;
USE mikolenko;

/*
		Таблица категорий
	----------------------------
	id - идентификатор категории
	name - наименование категории
	alias - алиас категории
	parent_category_id - идентификатор родительской категории (если имеется)
	level - уровень вложенности категории
*/
CREATE TABLE categories (
	id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	alias VARCHAR(100) NOT NULL,
	parent_category_id SMALLINT UNSIGNED,
	level SMALLINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE InnoDB;

ALTER TABLE categories ADD FOREIGN KEY(parent_category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE SET NULL;