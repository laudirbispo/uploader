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

final class BlobUpload extends AbstractFileUploader 
{
	private $saveExt = 'png';
    
    private $newFile;
    
	public function __construct () 
	{
		// prevent bad startup
		parent::__construct();
	}
    
    public function saveAs(string $ext = 'png') 
    {
        $this->saveExt = $ext;
        return $this;
    }

	/** 
	 * IMPLEMENTATION OF THE ABSTRACT METHOD
	 * Tries to move the file 
	 *
	 * @param $rename (bool) - If true, rename files
	 */
	public function move($blob, bool $rename = true) : bool
	{
        if (strtolower($this->saveExt) !== "png" && "jpg") {
            $this->debug['error'][] = array(
                'message' => "Extensão inválida.",
				'file' => $name
			 );
            return false;
        }

        if (empty($blob)) {
            $this->debug['error'][] = array(
                'message' => "Binário inválido.",
				'file' => ''
			 );
            return false;
        }
        // Force random name
        $name = self::generateRandomName().'.'.$this->saveExt;
        
		$imageDataEncoded = base64_encode(file_get_contents($blob)); 
        $imageData = base64_decode($imageDataEncoded); 
        $source = imagecreatefromstring($imageData); 
        $angle = 0;
        $rotate = imagerotate($source, $angle, 0);
         // if want to rotate the image 
        $newImage = $this->save_path .'/'. $name;
        $this->newFile = $newImage;
        $file = $newImage;
        if (file_exists($file)) {
            $this->debug['error'][] = array(
                'message' => "Já existe um arquivo com o este nome",
				'file' => $this->newFile
			 );
            return false;
        }
        $imageSave = imagejpeg($rotate, $file, 100); 
        imagedestroy($source);
        return true;
		
	}
    
    public function rename(string $name) : bool
    {
        if (empty($this->newFile) || null === $this->newFile) {
            $this->debug['error'][] = "Não há arquivos para renomear";
            return false;
        }
        $ext = pathinfo($this->newFile, PATHINFO_EXTENSION);
        $newName = $this->save_path .'/'. $name . '.' . $ext;
        if (file_exists($newName)) {
            $this->debug['error'][] = array(
                'message' => "Já existe um arquivo com o nome: {$name}",
				'file' => $this->newFile
			 );
            return false;
        } else {
            rename($this->newFile, $newName);
            $this->newFile = $newName;
            return true;
        }
    }
    
    /**
     * Delete failed upload
     */
    public function rollback() : bool 
    {
        return unlink($this->newFile);
    }
    
    public function getNewFile() : ?string 
    {
        return $this->newFile;
    }

	
}
