<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Sprite\Helpers;

use Maslosoft\Sprite\Models\Configuration;

/**
 * FolderChecker
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FolderChecker
{

	public static function check(Configuration $config)
	{
		// Prepare folder
		if (empty($config->runtimePath))
		{
			throw new UnexpectedValueException(sprintf('Property `runtimePath` of `%s` must be set and point to writeable directory', $config->runtimePath));
		}
		if (!is_writable($config->runtimePath))
		{
			throw new RuntimeException(sprintf('Runtime path `%s` is not writeable', $config->runtimePath));
		}
		if (empty($config->generatedPath))
		{
			throw new UnexpectedValueException(sprintf('Property `generatedPath` of `%s` must be set and point to writeable directory', $config->generatedPath));
		}
		if (!is_writable($config->generatedPath))
		{
			throw new RuntimeException(sprintf('Generated path `%s` is not writeable', $config->generatedPath));
		}
		// Check if base name contains path
		$basename = $config->basename;
		if (strpos($basename, '/') !== false || strpos($basename, '\\') !== false)
		{
			$pathname = sprintf('%s/%s', $config->generatedPath, dirname($basename));
			if (!is_writable($pathname))
			{
				throw new RuntimeException(sprintf('Base name contains path `%s`, which is not writeable', $pathname));
			}
		}
//		if (!file_exists($config->getAssetFolder()))
//		{
//			if (!empty($config->spriteDir))
//			{
//				mkdir($config->getAssetFolder());
//				if (!is_writable($config->getAssetFolder()))
//				{
//					throw new RuntimeException(sprintf('Runtime path `%s` is not writeable', $config->getAssetFolder()));
//				}
//			}
//		}
	}

}