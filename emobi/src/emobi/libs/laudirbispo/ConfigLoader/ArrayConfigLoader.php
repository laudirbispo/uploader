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

class ArrayConfigLoader implements FileLoadable
{
	private $arr = [];
	
	public function __construct ($arr)
	{
		if (!is_array($arr))
			throw new ConfigLoaderException(
				sprintf("[%s]: Loader inválido para este tipo de dados.",  get_class($this))
			);
		$this->arr = $arr;
	}
	
	public function parse () : array
	{
		return $this->arr;
	}
	
}
