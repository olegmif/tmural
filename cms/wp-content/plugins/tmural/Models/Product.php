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
	 * Регистрирует тип поста tmural_product
	 *
	 * @return void
	 */
	public function register_post_type() {
		$args = array(
			'label'  => 'Товары',
			'public' => true,
		);

		register_post_type( 'tmural_product', $args );
	}

	/**
	 * Создает в базе данных таблицу tmural_products.
	 *
	 * @return void
	 */
	public function create_table() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'tmural_products';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			post_id bigint(20) NOT NULL,
			price decimal(10,2) NOT NULL DEFAULT '0',
			PRIMARY KEY  (post_id),
			KEY price (price)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

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


