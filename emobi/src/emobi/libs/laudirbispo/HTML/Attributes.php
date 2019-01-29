<?php declare(strict_types=1);
namespace libs\laudirbispo\HTML;
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
 * @package       laudirbispo\HTML
 */

class Attributes 
{
    private $attributes = [];
    
    public function __construct(array $attributes = []) 
    {
        $this->attributes = $attributes;
    }
    
    public static function builder(array $attributes) : self 
    {
        return new self($attributes);
    }
    
    public function setMultiple(array $attributes) 
    {
        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }
    }
    
    public function set(string $attribute, $value) 
    {
        $this->attributes[$attribute] = $value;
    }
    
    public function add(string $attribute, $value) : bool 
    {
         return $this->set($attribute, $value);
    }
    
    public function remove(string $attribute) 
    {
        unset($this->attributes[$attribute]);  
    }
    
    public function has(string $attribute) : bool 
    {
        return array_key_exists($attribute, $this->attributes);
    }
    
    public function get(string $attribute, $defaultValue = null) 
    {
        if ($this->has($attribute)) {
            return $this->attributes[$attribute];
        } else {
            return $defaultValue;
        }
    }
    
    public function getAll() : array 
    {
        return $this->attributes;
    }
    
    public function render() : string 
    {
        $str = '';
		foreach ($this->attributes as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $data => $val) {
                    $str .= ' '.$data.'="'.$val.'"';
                }
            } else {
                $str .= ' '.$key.'="'.$value.'"';
            }
		}
		return $str; 
    }
    
    public function __toString() : string 
    {
        return $this->get();
    }
}
