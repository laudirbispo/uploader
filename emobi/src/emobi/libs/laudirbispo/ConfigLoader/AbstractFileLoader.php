<?php declare(strict_types=1);
namespace libs\laudirbispo\ConfigLoader;

/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com)
 * @version       1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @package       laudirbispo\ConfigLoader
 */

class AbstractFileLoader 
{
	protected function assertValidFile ($path, string $extension)
	{
		if (!file_exists($path))
			throw new ConfigLoaderException(
				sprintf("[%s]: Arquivo %s inexistente.", get_class($this), $path)
			);
		
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		if ($ext !== $extension)
			throw new ConfigLoaderException(
				sprintf("[%s]: Loader inválido para este tipo de arquivo",  get_class($this), $path)
			);
	}
}