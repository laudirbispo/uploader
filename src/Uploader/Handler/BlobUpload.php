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
use Exception;

final class BlobUpload extends AbstractFileUploader 
{
	private $saveExt = 'png';
    
	public function __construct (string $ext = 'png') 
	{
        $this->saveExt = $ext;
		// prevent bad startup
		parent::__construct();
	}

	/** 
	 * IMPLEMENTATION OF THE ABSTRACT METHOD
	 * Tries to move the file 
	 *
	 * @param $rename (bool) - If true, rename files
	 */
	public function move($blob, ?string $name = null) : bool
	{
        if (strtolower($ext) !== "png" || "jpg") {
            $this->debug['error'][] = array(
                'message' => "Extensão inválida.",
				'file' => $name
			 );
            return false;
        }

        if (empty($blob)) {
            $this->debug['error'][] = array(
                'message' => "Binário inválido.",
				'file' => $name
			 );
            return false;
        }
        
        if (null === $name) {
            $name = self::generateRandomName().'.'.$this->saveExt;
        } else {
            $name = $name.'.'.$this->saveExt;
        }
        
		$imageDataEncoded = base64_encode(file_get_contents($blob)); 
        $imageData = base64_decode($imageDataEncoded); 
        $source = imagecreatefromstring($imageData); 
        $angle = 0;
        $rotate = imagerotate($source, $angle, 0);
         // if want to rotate the image 
        $imageTmp = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$name;
        $imageSave = imagejpeg($rotate,$imageTmp,100); 
        imagedestroy($source);
        return $imageTmp;
		
	}

	
}
