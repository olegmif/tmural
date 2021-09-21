<?php // phpcs:disable Wordpress.NotHyphenatedLowercase
/**
 * Содержит код класса Product
 *
 * @package tmural\plugin
 */

namespace Tmural\Models;

use Tmural\Metaboxes\ProductPriceMetabox;

/**
 * Класс Product (Товар).
 * Регистрирует тип поста tmural_product, содежрит методы для получения и сохранения данных товара.
 */
class Product {

	/**
	 * Идентификатор поста
	 *
	 * @var int
	 */
	private int $post_id;

	/**
	 * Наименование товара
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * Данные товара
	 *
	 * @var array
	 */
	private array $product_data = array();

	/**
	 * Конструктор класса.
	 *
	 * @param integer $post_id Идентификатор поста.
	 */
	public function __construct( int $post_id ) {
		$this->post_id     = $post_id;
	}

	/**
	 * Регистрирует тип поста tmural_product
	 *
	 * @return void
	 */
	public static function register_post_type() {
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
	 * Загружает данные товара из базы данных,
	 * если они там есть, или устанавливает значения по умолчанию.
	 *
	 * @return void
	 */
	public function load() {
		global $wpdb;
		$table_name = "{$wpdb->prefix}tmural_products";

		$product_data = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table_name WHERE post_id = %d",
				$this->post_id
			),
			ARRAY_A
		);

		if ( $product_data ) {
			$this->product_data = $product_data;
		} else {
			$this->product_data = array(
				'price' => 0,
			);
		}

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

	/**
	 * Устанавливает цену
	 *
	 * @param float $price Значение цены.
	 * @return bool|WP_Error true в случае успеха или WP_Error в случае ошибки.
	 */
	public function set_price( float $price ) {
		if ( is_float( $price ) ) {
			$this->product_data['price'] = $price;
			return true;
		} else {
			return new WP_Error( 'invalid_value', "$price is not a valid price" );
		}
	}

	/**
	 * Возвращает цену товара.
	 *
	 * @return float Значение цены.
	 */
	public function get_price() {
		return empty( $this->product_data ) ? 0 : $this->product_data['price'];
	}

	/**
	 * Сохраняет поля товара в базе данных.
	 *
	 * @return void
	 */
	public function save(): void {
		global $wpdb;

		$table_name = "{$wpdb->prefix}tmural_products";

		$update = false;

		$exists = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT post_id FROM $table_name WHERE post_id = %d",
				$this->post_id
			),
			ARRAY_A
		);

		if ( $exists ) {
			$update = true;
		}

		if ( $update ) {
			$result = $wpdb->update(
				$table_name,
				array(
					'price' => $this->get_price(),
				),
				array( 'post_id' => $this->post_id ),
				array( '%f' ),
				array( '%d' )
			);
		} else {
			$result = $wpdb->insert(
				$table_name,
				array(
					'post_id' => $this->post_id,
					'price'   => $this->get_price(),
				),
				array( '%f', '%d' )
			);
		}

		if ( ! $result ) {
			$wpdb->print_error();
		}
	}

}


