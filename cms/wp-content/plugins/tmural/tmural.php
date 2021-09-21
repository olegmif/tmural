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
	$product = new Product();
	$product->register_post_type();
}

add_action( 'init', 'tmural_register_entities' );







