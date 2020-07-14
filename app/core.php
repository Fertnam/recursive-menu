<?php

//Определяем константы с путями приложения
define('ROOT_PATH', "{$_SERVER['DOCUMENT_ROOT']}/");
define('APP_PATH', ROOT_PATH . 'app/');

//Подключение файлов системы
require_once ROOT_PATH . 'vendor/autoload.php';
require_once APP_PATH . 'functions/autoload.php';

//Устанавливаем соединение с БД
Database::setConnection();
