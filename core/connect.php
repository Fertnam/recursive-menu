<?php

//Инициализируем параметры подключения
define('DB_DSN', 'mysql:host=localhost; dbname=mikolenko');
define('DB_LOGIN', 'root');
define('DB_PASSWORD', '');
define('DB_OPTIONS', [
	PDO::ATTR_ERRMODE 			 => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

//Подключаемся к БД
$DbConnect = new PDO(DB_DSN, DB_LOGIN, DB_PASSWORD, DB_OPTIONS);
