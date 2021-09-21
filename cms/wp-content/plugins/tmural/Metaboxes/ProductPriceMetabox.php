<?php
/**
 * Содержит класс метабокса ProductPriceMetabox, предназначенного
 * для отображения в интерфейсе редактирования постов типа Product.
 *
 * @package tmural\plugin
 */

namespace Tmural\Metaboxes;

use Tmural\Models\Product;

/**
 * Класс метабокса, предназначен для отображения в интерфейсе
 * редактирования поста. Позволяет пользователю изменить цену товара.
 */
class ProductPriceMetabox {

	/**
	 * Добавляет метабокс.
	 *
	 * @return void
	 */
	public static function add(): void {
		add_meta_box(
			'tmural_product_price',
			'Цена',
			array( self::class, 'html' ),
			'tmural_product'
		);
	}

	/**
	 * Выводит HTML метабокса
	 *
	 * @param \WP_Post $product Объект поста.
	 * @return void
	 */
	public static function html( \WP_Post $product ): void {
		wp_nonce_field( 'product-edit', '_product-price' );
		?>
		<label for="product-price">Цена:</label>
		<input id="product-price" type="" name="product-price" />
		<?php
	}

	/**
	 * Сохранение данных метабокса
	 *
	 * @param \Tmural\Models\Product $product Идентификатор поста товара.
	 * @return void
	 */
	public static function save( Product $product ): void {

		$nonce = isset( $_POST['_product-price'] ) ? sanitize_text_field( wp_unslash( $_POST['_product-price'] ) ) : '';

		if ( wp_verify_nonce( $nonce, 'product-edit' ) ) {
			$price = isset( $_POST['product-price'] ) ? sanitize_text_field( wp_unslash( $_POST['product-price'] ) ) : 0;

			if ( is_numeric( $price ) ) {
				$product->set_price( (float) $price );
			}
		}
	}
}
