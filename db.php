<?php

require_once './app/core.php';

use Illuminate\Database\Capsule\Manager as DB;
use models\Category;

$categories = json_decode(file_get_contents('./app/categories.json'), true);

try {
	DB::transaction(function() use ($categories) {
		Category::updateTable($categories);
	});

	echo 'Таблица categories успешно перезаполнена';
} catch (PDOException $Exception) {
	echo $Exception->getMessage();
}
