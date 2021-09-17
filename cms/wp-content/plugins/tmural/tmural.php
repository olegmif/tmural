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

require 'vendor/autoload.php';

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

use Tmural\Models\Product;

$product = new Product();
$product->print_test();

/**
 * Undocumented function
 *
 * @package tmural\plugin
 * @return void
 */
function tmural_test() {

}