<?php declare(strict_types=1);
namespace laudirbispo\Uploader;

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

final class UploaderFactory 
{
	public static function create(string $handler)
	{
		switch ($handler) :
	
			case 'images' :
				return new Handler\ImageUpload();
            case 'blob' :
            case 'binary' :
            case 'data' :
            case 'base64' :
				return new Handler\BlobUpload();
			default :
				return new Handler\FileUpload();
		
		endswitch;
		
		
	}
	
}
