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
	 * Признак изменения данных.
	 * true, если данные изменялись или false, если данные не изменялись.
	 * Если содержит false, то вызов save() с любым значением параметра
	 * не приведет к сохранению данных в базе.
	 *
	 * @var bool
	 */
	private bool $is_changed;

	/**
	 * Наименование товара
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * Цена товара
	 *
	 * @var string
	 */
	private float $price;

	/**
	 * Конструктор класса.
	 *
	 * @param integer $post_id Идентификатор поста.
	 */
	public function __construct( int $post_id ) {
		$this->is_changed = false;
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
	 * Устанавливает наименование товара.
	 *
	 * @param string $name Строка с наименованием товара.
	 * @return void
	 */
	public function set_name( string $name ) {
		$this->name = $name;

		$this->is_changed = true;
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
			$this->price      = $price;
			$this->is_changed = true;
			return true;
		} else {
			return new WP_Error( 'invalid_value', "$price is not a valid price" );
		}
	}

	/**
	 * Сохраняет поля товара в базе данных.
	 *
	 * @return void
	 */
	public function save(): void {

		if ( ! $this->is_changed ) {
			return;
		}

		global $wpdb;

		$table_name = $wpdb->prefix . 'tmural_products';

		$update = false;

		$product_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE post_id = $this->post_id" );

		if ( $product_data ) {
			$update = true;
		}

		if ( $update ) {
			$result = $wpdb->update(
				$table_name,
				array(
					'price' => $this->price,
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
					'price'   => $this->price,
				),
				array( '%f', '%d' )
			);
		}

		if ( ! $result ) {
			$wpdb->print_error();
		}
	}

}


