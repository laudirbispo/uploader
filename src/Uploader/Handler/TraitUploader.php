<?php declare(strict_types=1);
namespace laudirbispo\Uploader\Handler;

/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com)
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 *
 * @package laudirbispo/Uploader - This file is part of the Uploader package.
 */

trait TraitUploader
{
/**
	 * Generates a random name
	 */
	public static function generateRandomName () : string
	{
		return (string) date('Ymdhis').time().mt_rand(111, 999);
	}
	
	/**
	 * Normalize paths
	 */
	public static function normalize (string $path)
	{
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) 
		{
            if ('.' == $part) continue;
            if ('..' == $part) 
                array_pop($absolutes);
            else 
                $absolutes[] = $part;     
        }
		$path = implode(DIRECTORY_SEPARATOR, $absolutes);
		
		if (self::isAbsolute($path))
			return $path;
		else 
        	return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path;	
	}
	
	/**
	 * Slug name
	 */
	public static function slugify ($string) : string
	{
		if (!is_string($string))
			return $string;
		
		$string = preg_replace('/[\t\n]/', ' ', $string);
		$string = preg_replace('/\s{2,}/', ' ', $string);
		$list = array(
			'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-', '.' => '-',
		);

		$string = strtr($string, $list);
		$string = preg_replace('/-{2,}/', '-', $string);
		$string = strtolower($string);
		return $string;
	}
	
	/**
	 * Convert to human readable mode 
	 *
	 * @param string $bytes 
	 * @return string
	 */
	public static function convertSizeToHumans ($bytes) 
	{
		if (!is_int($bytes))
			return 'null';
		
		$bytes = floatval($bytes);
        $arBytes = array(
            0 => array("UNIT" => "TB", "VALUE" => pow(1024, 4)),
            1 => array("UNIT" => "GB", "VALUE" => pow(1024, 3)),
            2 => array("UNIT" => "MB", "VALUE" => pow(1024, 2)),
            3 => array("UNIT" => "KB","VALUE" => 1024),
            4 => array("UNIT" => "B", "VALUE" => 1),
        );
		
		$result = '0 Kb';

		foreach($arBytes as $arItem)
		{
			if($bytes >= $arItem["VALUE"])
			{
				$result = $bytes / $arItem["VALUE"];
				$result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
				break;
			}
		}
		return $result;
		
	}
	
	/**
	 * Verify that the file name is valid
	 *
	 * @return - true if the name is valid
	 */
	public static function isValidName (string $filename) : bool 
	{
		$return = false;
    	if (preg_match("`^[-0-9A-Z_\.]+$`i", $filename) === true)
			$return = true;
		if (mb_strlen($filename, "UTF-8") < 225)
			$return = true;
		
		return $return;
		
	}
		
	
}
