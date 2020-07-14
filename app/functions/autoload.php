<?php
/**
  * Зарегистрировать функцию для автоматической загрузки классов
  *
  * @param mixed $className Имя класса
  */
spl_autoload_register(function($className) {
	$path = APP_PATH . str_replace('\\', '/', $className) . '.php';

	if (is_file($path)) {
		require_once $path;	
	}
});
