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

class ConfigLoader 
{
	private $configs = [];
	
	public $error = null;
	
	public function __construct (FileLoadable $configs)
	{
		$configs = $configs->parse();
		foreach ($configs as $name => $value) 
		{
            $this->set($name, $value);
        }
	}
	
	public static function load ($configs)
	{
		if (is_array($configs))
			return new self(new ArrayConfigLoader($configs));

		$ext = strtolower(pathinfo($configs, PATHINFO_EXTENSION));
		
		switch ($ext) :
		
			case 'json' :
				return new self(new JsonFileConfigLoader($configs));
			case 'ini' :
				return new self(new IniFileConfigLoader($configs));
			case 'php' :
				return new self(new PhpFileConfigLoader($configs));
			default:
				throw new ConfigLoaderException("Tipo de configuração não suportado.");
		
		endswitch;
	}
	
	public function has (string $key) : bool 
	{
		return isset($this->configs[$key]);
	}
	
	public function set (string $key, $value)
	{
		if (is_array($value)) 
            $value = new self(new ArrayConfigLoader($value));
		
		$this->configs[$key] = $value;
	}
	
	public function get (string $key)
	{
		if (isset($this->configs[$key]))
			return $this->configs[$key];
		else
			return null;
	}
	
	public function getAll () 
	{
		return $this->configs;
	}
	
	public function clear ()
	{
		$this->configs = [];
	}
	
	public function __set ($name, $value) 
	{
        $this->set($name, $value);
    }

    public function __get ($name) 
	{
        return $this->get($name);
    }
	
}
