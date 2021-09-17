<?php // phpcs:disable Wordpress.NotHyphenatedLowercase
/**
 * Содержит код класса Product
 *
 * @package olegmif/tmural-plugin
 */

namespace Tmural\Models;

/**
 * Класс Product (Товар).
 * Регистрирует тип поста tmural_product, содежрит методы для получения и сохранения данных товара.
 */
class Product {
	/**
	 * Выводит тестовую строку.
	 *
	 * @return void
	 */
	public function print_test() {
		echo 'test product';
	}
}
