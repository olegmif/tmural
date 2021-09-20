<?php // phpcs:disable Wordpress.NotHyphenatedLowercase
/**
 * Тесты класса Proroduct.
 *
 * @package tmural/tests
 */

declare(strict_types=1);

namespace Tmural\Models;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Кейс тестов класса Product.
 *
 * @author olegmif
 * @test
 */
final class ProductTest extends TestCase {

	/**
	 * Вызов Product->get_name() после вызова Product->ser_name( $name )
	 * возвращает значение, переданное в Product->ser_name( $name ).
	 *
	 * @return void
	 */
	public function test_name_setter_getter(): void {

		$product = new Product();

		$title = 'test_name';

		$product->set_name( $title );

		$this->assertEquals( $title, $product->get_name() );
	}

	use \phpmock\phpunit\PHPMock;

	/**
	 * Вызов Product->register_post_type() вызывает функцию
	 * register_post_type с "tmural_product" в первом аргументе
	 * и непустым массивом во втором.
	 *
	 * @return void
	 */
	public function test_register_post_type(): void {
		$product = new Product();

		$register_post_type = $this->getFunctionMock( 'Tmural\Models', 'register_post_type' );
		$register_post_type->expects( $this->once() )->willReturnCallback(
			function ( $post_type, $args ) {
				$this->assertEquals( 'tmural_product', $post_type );
				$this->assertNotEmpty( $args );
			}
		);
		$product->register_post_type();
	}
}
