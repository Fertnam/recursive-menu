<?php

require_once './core/connect.php';
require_once './core/functions/insert_category_in_table.php';

$categories = json_decode(file_get_contents('./core/categories.json'), true);

try {
	$DbConnect->beginTransaction();

	//Предварительно очищаем таблицу от всех старых категорий
	$DbConnect->query('DELETE FROM categories');

	//Вставляем каждую категорию в таблицу (рекурсивно вместе с её подкатегориями)
	foreach ($categories as $category) {
		insert_category_in_table($category, $DbConnect);
	}

	$DbConnect->commit();
} catch (PDOException $Exception) {
	$DbConnect->rollback();

	echo $Exception->getMessage();
}
