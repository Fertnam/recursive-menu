<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
  * Класс, описывающий подключение к БД (паттерн Singleton)
  *
  * @version 1.0
  */
class Database {
	/**
	  * @access private
	  *
	  * @var Capsule Подключение к БД
	  *
	  * @static
	  */
	private static $_DB;

	/**
	  * Конструктор для создания экземпляра данного класса
	  *
	  * @access private
	  */
	private function __construct() {
		self::$_DB = new Capsule;

		//Параметры подключения к БД
		$dbParams = [
			'driver'   => 'mysql',
			'host' 	   => 'localhost',
			'database' => 'mikolenko',
			'username' => 'root',
			'password' => '',
			'charset'  => 'utf8'
		];

		self::$_DB->addConnection($dbParams);
		self::$_DB->setAsGlobal();
		self::$_DB->bootEloquent();
	}

	/**
	  * Установить соединение с БД
	  *
	  * @access public
	  *
	  * @static
	  */
	public static function setConnection() {
		if (empty(self::$_DB)) {
			new self;
		}
	}
}
