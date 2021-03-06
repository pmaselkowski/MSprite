<?php

namespace Helpers;

use Maslosoft\Sprite\Helpers\ConstantsFactory;
use Maslosoft\Sprite\Helpers\ImageFinder;
use Maslosoft\Sprite\Icon\I;
use Maslosoft\Sprite\Models\ConstClass;
use Maslosoft\Sprite\Models\Package;
use ReflectionClass;
use UnitTester;
use const ASSETS_DIR;

class ConstantsFactoryTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillCreateConstClassFile()
	{
		$path = ASSETS_DIR . '/helpers/image-finder';

		$classPath = dirname((new ReflectionClass(I::class))->getFileName());
		$classFile = sprintf('%s/I2.php', $classPath);

		codecept_debug($classFile);

		@unlink($classFile);

		$package = new Package();
		$package->paths = [$path];
		$package->constantsClassPath = $classPath;
		$package->constantsClass = 'Maslosoft\Sprite\Icon\I2';
		$packages = [$package];
		$sprites = (new ImageFinder)->find($packages);

		// Should be 5 images
		$this->assertSame(5, count($sprites));

		$consts = ConstantsFactory::create($sprites);

//		codecept_debug($consts);

		$this->assertFileExists($classFile);
		$this->assertSame(1, count($consts), 'That one constants definition class was created');
	}

	public function testIfWillCreateConstClassInstance()
	{
		$path = ASSETS_DIR . '/helpers/image-finder';

		$package = new Package();
		$package->paths = [$path];
		$package->constantsClass = I::class;
		$packages = [$package];
		$sprites = (new ImageFinder)->find($packages);

		// Should be 5 images
		$this->assertSame(5, count($sprites));

		$consts = ConstantsFactory::create($sprites);

//		codecept_debug($consts);

		$this->assertSame(1, count($consts), 'That one constants definition class was created');
	}

	public function testIfWillCreateConstClassInstanceWithTwoPackages()
	{
		$path = ASSETS_DIR . '/helpers/image-finder';

		$package = new Package();
		$package->paths = [$path];

		$path2 = ASSETS_DIR . '/helpers/image-finder2';

		$package2 = new Package();
		$package2->paths = [$path2];
		$package2->constantsClass = I::class;

		$packages = [$package, $package2];
		$sprites = (new ImageFinder)->find($packages);

		// Should be 9 images
		$this->assertSame(9, count($sprites));

		$consts = ConstantsFactory::create($sprites);


		$this->assertSame(1, count($consts), 'That one constants definition class was created');

		$const = array_pop($consts);
		/* @var $const ConstClass */
		$this->assertInstanceOf(ConstClass::class, $const);

		$this->assertSame(4, count($const->constants), "That has constants for 4 icons, only from `$path2`");
	}

}
