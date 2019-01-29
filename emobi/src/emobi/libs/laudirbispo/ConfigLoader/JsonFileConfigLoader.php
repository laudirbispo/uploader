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

class JsonFileConfigLoader extends AbstractFileLoader implements FileLoadable
{
	private $path;
	
	public function __construct (string $path)
	{
		$this->assertValidFile($path, 'json');
		$this->path = $path;
	}
	
	public function parse () : array 
	{
		$json_file = file_get_contents($this->path);   
		$Configs = json_decode($json_file, true);
		if (is_array($configs))
			return $configs;
		else
			throw new ConfigLoaderException(
				sprintf("[%s]: Estrutura do arquivo %s mal formada.". get_class($this), $this->path)
			);
	}
	
}