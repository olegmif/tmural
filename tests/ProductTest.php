<?php // phpcs:disable Wordpress.NotHyphenatedLowercase
/**
 * Тесты класса Proroduct.
 *
 * @package tmural/tests
 */

declare(strict_types=1);

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Tmural\Models\Product;

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
}
