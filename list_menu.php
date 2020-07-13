<?php

require_once './core/connect.php';

function rec(array $data, array &$categories, array &$usedCategories) : string
{
	if (!in_array($data['id'], $usedCategories)) {
		$usedCategories[] = $data['id'];

		$children = array_filter($categories, function(array $arrElem) use ($data) {
			return ($arrElem['parent_category_id'] === $data['id']) && (!empty($arrElem['parent_category_id']));
		});

		$margin = str_repeat("\t", $data['level']);
		$res = "$margin{$data['name']} /{$data['alias']}\n";

		if (!empty($children)) {
			foreach ($children as $child) {
				$res .= rec($child, $categories, $usedCategories);
			}
		}
	} else {
		$res = '';
	}

	return $res;
}

try {
	$categories = $DbConnect->query('SELECT * from categories')->fetchAll();

	$usedCategories = [];

	$res = '';

	foreach ($categories as $category) {
		$res .= rec($category, $categories, $usedCategories);
	}

	echo "<pre>$res</pre>";
} catch (PDOException $Exception) {
	echo $Exception->getMessage();
}
