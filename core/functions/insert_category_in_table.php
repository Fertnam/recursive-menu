<?php

/**
  * Вставить категорию в таблицу (рекурсивно вместе с подкатегориями)
  *
  * @param array $data Данные категории
  * @param PDO $DbConnect Подключение к БД
  * @param int $parentCategoryId Идентификатор родительской категории (если есть)
  * @param int $level Уровень вложенности
  *
  * @throws PDOException
  */
function insert_category_in_table(array $data, PDO $DbConnect, int $parentCategoryId = null, int $level = 0)
{
	$WritingQuery = $DbConnect->prepare('INSERT INTO categories(id, name, alias, parent_category_id, level) VALUES(:id, :name, :alias, :parent_category_id, :level)');

	$WritingQuery->bindParam(':id', $data['id'], PDO::PARAM_INT);
	$WritingQuery->bindParam(':name', $data['name']);
	$WritingQuery->bindParam(':alias', $data['alias']);
	$WritingQuery->bindParam(':parent_category_id', $parentCategoryId);
	$WritingQuery->bindParam(':level', $level, PDO::PARAM_INT);

	$WritingQuery->execute();

	if (!empty($data['childrens'])) {
		foreach ($data['childrens'] as $childrenCategory) {
			insert_category_in_table($childrenCategory, $DbConnect, $data['id'], $level + 1);
		}
	}
}
