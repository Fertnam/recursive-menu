<?php

require_once './app/core.php';

use models\Category;

try {
	$typeAListMenu = Category::getListMenu();
	$typeBListMenu = Category::getListMenu(Category::TYPE_B, 1);
} catch (PDOException $Exception) {
	echo $Exception->getMessage();
}

//Заполняем текстовые файлы
file_put_contents(TYPE_PATH . 'type_a.txt', $typeAListMenu);
file_put_contents(TYPE_PATH . 'type_b.txt', $typeBListMenu);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Рекурсивное меню - Вывод</title>
</head>
<body>
	<?php if (!empty($typeAListMenu)): ?>
		<div>
			<p>TYPE A</p>
			<pre><?= $typeAListMenu ?></pre>
			<a href="/types/type_a.txt" download>Скачать файл type_a.txt</a>
		</div>
	<?php endif; ?>

	<?php if (!empty($typeBListMenu)): ?>
		<div>
			<p>TYPE B</p>
			<pre><?= $typeBListMenu ?></pre>
			<a href="/types/type_b.txt" download>Скачать файл type_b.txt</a>
		</div>
	<?php endif; ?>
	<hr>
	<p>
		<a href="/">Вернуться на главную</a>
	</p>
</body>
</html>