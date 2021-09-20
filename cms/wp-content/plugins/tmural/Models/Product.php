<?php // phpcs:disable Wordpress.NotHyphenatedLowercase
/**
 * Содержит код класса Product
 *
 * @package tmural\plugin
 */

namespace Tmural\Models;

/**
 * Класс Product (Товар).
 * Регистрирует тип поста tmural_product, содежрит методы для получения и сохранения данных товара.
 */
class Product {
	/**
	 * Наименование товара
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * Устанавливает наименование товара.
	 *
	 * @param string $name Строка с наименованием товара.
	 * @return void
	 */
	public function set_name( string $name ) {
		$this->name = $name;
	}

	/**
	 * Возвращает наименование товара.
	 *
	 * @return string Строка с наименованием товара.
	 */
	public function get_name(): string {
		return $this->name;
	}

}


