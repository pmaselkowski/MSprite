<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/sprite
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/sprite/
 */

namespace Maslosoft\Sprite\Helpers;

use Maslosoft\Sprite\Interfaces\SpritePackageInterface;
use Maslosoft\Sprite\Models\Package;
use Maslosoft\Sprite\Models\SpriteImage;
use Symfony\Component\Finder\Finder;

/**
 * ImageFinder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ImageFinder
{

	/**
	 *
	 * @param SpritePackageInterface[]|Package[] $packages
	 * @return SpriteImage[]
	 */
	public function find($packages)
	{
		// Get icons
		$sprites = [];
		foreach ($packages as $package)
		{
			foreach ($package->getPaths() as $path)
			{
				$finder = new Finder();

				$finder->sortByChangedTime();
				$finder->sortByAccessedTime();
				$finder->name('/\.(png|jpg|gif)$/');
				foreach ($finder->in($path) as $fileInfo)
				{
					$sprite = new SpriteImage($path, $fileInfo);

					/**
					 * NOTE Sprites must be groupped by path, not hash
					 * as order of packages matters here or could
					 * lead to generation of wrong constants.
					 */
					$key = $sprite->getFullPath();
					if (!array_key_exists($key, $sprites))
					{
						// Add new sprite to set if hash does not exists
						$sprites[$key] = $sprite;
					}
					// Sprite with selected hash exists, just add package
					if (!in_array($package, $sprites[$key]->packages))
					{
						$sprites[$key]->packages[] = $package;
					}
				}
			}
		}
		return $sprites;
	}

}
