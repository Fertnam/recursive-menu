<?php

/**
  * Сгенерировать строку с отступами для вывода категории (рекурсивно вместе с подкатегориями)
  *
  * @param array $data Данные категории
  * @param PDO $DbConnect Подключение к БД
  *
  * @throws PDOException
  *
  * @return string Строка с необходимыми отступами
  */
function generate_category_indent(array $data, PDO $DbConnect) : string
{
	$WritingQuery = $DbConnect->prepare('SELECT * from categories WHERE parent_category_id = :parent_category_id');

	$WritingQuery->bindParam(':parent_category_id', $data['id'], PDO::PARAM_INT);

	$WritingQuery->execute();

	$childrenCategories = $WritingQuery->fetchAll();

	$result = str_repeat("\t", $data['level']) . "{$data['name']} /{$data['alias']}\n";

	if (!empty($childrenCategories)) {
		foreach ($childrenCategories as $childrenCategory) {
			$result .= generate_category_indent($childrenCategory, $DbConnect);
		}
	}

	return $result;
}
