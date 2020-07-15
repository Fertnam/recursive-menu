<?php

namespace models;

use Illuminate\Database\Eloquent\Model;

/**
  * Класс модели для работы с категориями
  *
  * @version 1.0
  */
class Category extends Model
{
	/**
	  * @access public
	  *
	  * @var string Шаблон строки для элемента меню с отступами для type_a.txt
	  */
	const TYPE_A = ':name :path';

	/**
	  * @access public
	  *
	  * @var string Шаблон строки для элемента меню с отступами для type_b.txt
	  */
	const TYPE_B = ':name';

	/**
	  * @access public
	  *
	  * @var bool Отключаем работу со столбцами created_at и updated_at
	  */
	public $timestamps = false;

	/**
	  * @access protected
	  *
	  * @var array Список недоступных для массового присваивания полей (все доступны)
	  */
	protected $guarded = [];

	/* -------- ЗАПОЛНЕНИЕ ТАБЛИЦЫ -------- */
	/**
	  * Обновить таблицу категорий (вставить новые категории на замену старым)
	  *
	  * @param array $categories Массив с данными всех категорий
	  *
	  * @throws PDOException
	  */
	public static function updateTable(array $categories)
	{
		self::select()->delete(); //Удаляем все строки из таблицы

		foreach ($categories as $category) {
			self::createRecursively($category);
		}
	}

	/**
	  * Записать категорию в таблицу (рекурсивно вместе с подкатегориями)
	  *
	  * @param array $category Массив с данными одной категории
	  * @param int $parentId Идентификатор родительской категории (если есть)
	  * @param int $level Уровень вложенности
	  *
	  * @throws PDOException
	  */
	public static function createRecursively(array $category, int $parentId = null, int $level = 0)
	{
		parent::create([
			'id' 				 => $category['id'],
			'name' 				 => $category['name'],
			'alias' 			 => $category['alias'],
			'parent_category_id' => $parentId,
			'level' 			 => $level
		]);

		if (!empty($category['childrens'])) {
			foreach ($category['childrens'] as $child) {
				self::createRecursively($child, $category['id'], $level + 1);
			}
		}
	}

	/* -------- РАБОТА СО СПИСКОМ МЕНЮ -------- */
	/**
	  * Получить список меню категорий с отступами
	  *
	  * @static
	  *
	  * @param string $type Тип шаблона строки для элементов меню
	  * @param int $levelMax Максимально выводимый уровень вложенности
	  *
	  * @throws PDOException
	  *
	  * @return string Список меню категорий с отступами
	  */
	public static function getListMenu(string $type = self::TYPE_A, int $levelMax = null) : string {
		//Получим главные категории (которые не имеют родителя)
		$headCategories = self::whereNull('parent_category_id')->get();

		$result = '';

		foreach ($headCategories as $headCategory) {
			$result .= $headCategory->getMenuItemRecursively($type, $levelMax);
		}

		return $result;
	}

	/**
	  * Получить элемент меню с отступами (рекурсивно вместе с подкатегориями)
	  *
	  * @param string $type Тип шаблона строки для элемента меню
	  * @param int $levelMax Максимально выводимый уровень вложенности (если null, тогда выводятся абсолютно все вложенности)
	  *
	  * @return string Элемент меню с отступами
	  */
	public function getMenuItemRecursively(string $type = self::TYPE_A, int $levelMax = null) : string {
		$result = '';

		if (is_null($levelMax) || ($this->level <= $levelMax)) {
			$pattern = $type;

			//Подставляем данные категории в заготовленный шаблон строки
			foreach ($this->toArray() as $key => $value) {
				$pattern = str_replace(":$key", $value, $pattern);
			}

			$result .= str_repeat("\t", $this->level) . $pattern . PHP_EOL;

			if (!empty($this->childrens)) {
				foreach ($this->childrens as $child) {
					$result .= $child->getMenuItemRecursively($type, $levelMax);
				}
			}
		}

		return $result;
	}

	/* -------- СВЯЗИ МЕЖДУ МОДЕЛЯМИ -------- */
	/**
	  * Установить связь 1:n с моделью Category (получить дочерние категории)
	  *
	  * @access public
	  */
	public function childrens()
	{
		return $this->hasMany(self::class, 'parent_category_id');
	}

	/**
	  * Установить связь 1:1 с моделью Category (получить родительскую категорию)
	  *
	  * @access public
	  */
	public function parent()
	{
		return $this->hasOne(self::class, 'id', 'parent_category_id');
	}
}
