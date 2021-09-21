<?php
/**
 * Главный файл плагина
 *
 * Plugin Name: Tmural
 * Plugin URI: http://tmural.miftakhov.space
 * Description: This is a specialized plugin for the Tmural project. Not intended for any other use.
 * Author: Oleg Miftakhov
 * Version: 1.0.0
 * Author URI: http://www.miftakhov.space
 *
 * @version 1.0.0
 */

?>
<?php

use Tmural\Models\Product;

require 'vendor/autoload.php';

/**
 * Версия базы данных плагина.
 * Для обновления базы данных нужно изменить на другое значение.
 */
global $tmural_db_version;
$tmural_db_version = '1.0';

/**
 * Создает таблицы в базе данных.
 *
 * @return void
 */
function tmural_create_tables() {
	global $wpdb;
	global $tmural_db_version;

	$product = new Product();
	$product->create_table();

	update_option( 'tmural_db_version', $tmural_db_version );
}

/**
 * Проверяет текущую версию базы данных и при необходимости устанавливает таблицы.
 *
 * @return void
 */
function tmural_update_db_check() {
	global $tmural_db_version;

	if ( get_site_option( 'tmural_db_version' ) !== $tmural_db_version ) {
		tmural_create_tables();
	}
}

add_action( 'plugins_loaded', 'tmural_update_db_check' );

/**
 * Регистрирует блока редактора "Карточка товара".
 *
 * @package tmural\plugin
 * @return void
 */
function tmural_product_card_block_init() {
	register_block_type( __DIR__ . '/blocks/product-card' );
}
add_action( 'init', 'tmural_product_card_block_init' );

/**
 * Регистрирует типы постов и таксономии.
 *
 * @return void
 */
function tmural_register_entities() {
	Product::register_post_type();
}

add_action( 'init', 'tmural_register_entities' );

use \Tmural\Metaboxes\ProductPriceMetabox;

/**
 * Регистрирует метабоксы.
 *
 * @param string   $post_type Тип поста.
 * @param \WP_Post $post Объект поста.
 * @return void
 */
function tmural_add_meta_boxes( string $post_type, \WP_Post $post ): void {
	switch ( $post_type ) {
		case 'tmural_product':
			ProductPriceMetabox::add( $post->ID );
			break;
	}
}

/**
 * Обрабатывает сохранение поста товара
 *
 * @param int      $post_ID Идентификатор поста.
 * @param \WP_Post $post Объект поста.
 * @return void
 */
function tmural_save_product_post( $post_ID, $post ) {
	$product = new Product( $post_ID );
	ProductPriceMetabox::save( $product );
	$product->save( $update );
}

add_action( 'add_meta_boxes', 'tmural_add_meta_boxes', 10, 2 );
add_action( 'save_post_tmural_product', 'tmural_save_product_post', 10, 2 );


