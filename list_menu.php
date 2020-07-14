<?php

require_once './core/connect.php';
require_once './core/functions/generate_category_indent.php';

try {
	$categories = $DbConnect->query('SELECT * from categories WHERE parent_category_id IS NULL')->fetchAll();

	$listMenu = '';

	foreach ($categories as $category) {
		$listMenu .= generate_category_indent($category, $DbConnect);
	}

	file_put_contents('./types/type_a.txt', $listMenu);

	echo "<pre>$listMenu</pre>";
} catch (PDOException $Exception) {
	echo $Exception->getMessage();
}
