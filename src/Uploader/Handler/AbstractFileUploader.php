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

use laudirbispo\FileAndFolder\{Folder, File};

abstract class AbstractFileUploader 
{
	use TraitUploader;
	
	/**
	 * The file
	 *
	 * var (array)
	 */
	protected $file = [];
	
	/**
	 * Prefix name
	 *
	 * var (string)
	 */
	protected $prefix = '';
	
	/**
	 * The file's destination folder
	 *
	 * var (string)
	 */
	protected $save_path = '/docs';
	
	/**
	 * The allowed file types
	 *
	 * var (array)
	 */
	protected $allowed_extensions = [];
	
	/**
	 * MIME TYPES and file extensions list
	 *
	 * var (array)
	 */
	protected $mime_types = [];
	
	/**
	 * The max size allowed em bytes
	 *
	 * var (int)
	 * @defaul  2097152 Bytes (2MB)
	 */
	protected $max_size = 2097152;
	
	/**
	 * Get upload process info
	 *
	 */
	protected $debug = [];
	
	/**
	 * Recent successful uploads
	 */
	protected $uploaded_files = [];
	
	
	public function __construct()
	{
        if (!self::isActiveOnServer()) {
            $this->debug['error'] = "Uploads não são permitidos.";
        }
		$this->mime_types = require_once(dirname(__FILE__).'/../mime_types.php');
	}
	
	/** 
	 * Validate files and upload
	 * 
     * @param mixed $files
	 * @param mixed $rename 
	 */
	abstract public function move($files, bool $rename = false);
	
	/**
	 * Prevent overloading
	 */
	public function __call(string $name , array $arguments)
	{
		return $this;
	}
	
	
	/**
	 * Set max size alloweded
	 *
	 * @param $size (int|string) - The file path
	 * @return $this object
	 */
	public function maxSize($size)
	{
		$this->max_size = $size;
		return $this;
	}
	
	/** 
	 * Set save path
	 *
	 * @param $path (string) - Path to the folder that will save the file
	 * @param $create (bool) - If true create a folder to upload
	 * @return $this object
	 */
	public function savePath(string $path, bool $create = true)
	{
		$this->save_path = Folder::normalize($path);
		if ($create)
		{
			if (Folder::exists($this->save_path) && !Folder::isWritable($this->save_path))
				$this->debug['error'] = "A pasta de destino não tem permissão de escrita.";
			
			if (!Folder::exists($this->save_path))
			{
				if (!Folder::create($this->save_path))
					$this->debug['error'] = "Não foi possível criar o diretório de destino.";
			}
				
		}
		return $this;
	}
	
	/**
	 * Set allowed extensions
	 *
	 * @param $extensions (mixed) array or string
	 * @param $separator (string) - The separator of the string
	 * @return $this object
	 */
	public function allowedExtensions($extensions, string $separator = ',' )
	{
		if (is_array($extensions))
			$this->allowed_extensions = $extensions;
		else
			$this->allowed_extensions = explode($separator, $extensions);
		return $this;
	}
	
	/**
	 * Set prefix to file name
	 */
	public function prefix(string $prefix = '')
	{
		$this->prefix = $prefix;
		return $this;
	}
	
	/**
	 * Get the default value of Post Max Size of the server
	 *
     * @return int|null
     */
    public function getPostMaxSizeBytes()
    {
        $post_max_size = ini_get('post_max_size');
        $bytes       = (int) trim($post_max_size);
        $last        = strtolower($post_max_size[strlen($post_max_size) - 1]);

        switch ($last) 
		{
            case 'g': $bytes *= 1024;
            case 'm': $bytes *= 1024;
            case 'k': $bytes *= 1024;
        }

        if ($bytes === '') $bytes = null;

        return $bytes;
    }
	
	public function isValidFile()
	{
		/**
		 * IMPORTANT
		 * 
		 * This should be the first step of the verification
		 */
		if ($this->file['error'] !== 0) {
			$this->debug['error'][] = array(
                'message' => $this->getUploadError($this->file['error']),
				'file' => $this->file['name']
			);
			return false;
		}
		
		if (!isset($this->file['name']) || empty($this->file['name'])) {
			$this->debug['error'][] = array(
                'message' => "Nenhum arquivo foi selecionado.",
				'file' => ''
			 );
			return false;
		}
		
		if (!$this->checkExtension()) {
			$this->debug['error'][] = array(
                'message' => "Este tipo de arquivo não é permitido.",
				'file' => $this->file['name']
			);
			return false;
		}
		
		if (!$this->checkSize()) {
			$this->debug['error'][] = array(
                'message' => sprintf("Este arquivo ultrapassa o tamanho máximo permitido de %s.", self::convertSizeToHumans($this->max_size)),
				'file' => $this->file['name']
			);
			return false;
		}
		
		if (!$this->checkMime()) {
			$this->debug['error'][] = array(
                'message' => "Este tipo de arquivo não é permitido.",
				'file' => $this->file['name']
			);
			return false;
		}
		
		return true;
	}
	
	/**
	 * Check file size
	 */
	protected function checkSize() : bool
	{
		return ($this->file['size'] > $this->max_size) ? false : true;
	}
	
	/**
	 * Check if extension is allowed
	 */ 
	protected function checkExtension() : bool
	{
		$ext = strtolower($this->file['extension']);		
		return (in_array($ext, array_map('strtolower', $this->allowed_extensions)));
	}
	
	/**
	 * Verify that the mime type is valid according to the extension
	 *
	 * @return bool 
	 */
	protected function checkMime() : bool
	{
		$ext = strtolower($this->file['extension']);
		if (empty($ext) || empty($this->file['type']))
			return false;
		
		if (isset($this->mime_types[$ext])) {
			if (is_array($this->mime_types[$ext]))
				return (in_array($this->file['type'], $this->mime_types[$ext]));
			else
				return ($this->mime_types[$ext] === $this->file['type']);	
		}
		return false;
	}
	
	/**
	 * Move uploaded file
	 */
	protected function moveUpload(bool $rename)
	{
		// Rename the file
		if (true === $rename)
			$name = $this->prefix . self::generateRandomName() . '.' . $this->file['extension'];
		else
			$name = $this->prefix . $this->file['name'];

		$destination = $this->save_path . DIRECTORY_SEPARATOR . $name;
		
		if (move_uploaded_file($this->file['tmp_name'], $destination)) {
			$this->debug['success'][] = array(
                'message' => "Upload bem sussuedido!",
				'file' => $this->file['name']
			 );

			$this->uploaded_files[] = array(
                'old_file' => $this->file['name'],
				'new_file' => $name,
				'path' => $destination
			);
			return true;
		} else {
			$this->debug['error'][] = array(
                'message' => "Falha no upload.",
				'file' => $this->file['name']
			);
			return false;
		}
		
	}	
	
	/**
	 * THIS IS A FINAL METHOD
	 * The cleanest way to rearrange the $_FILES
	 */
	final protected function rearrange(array $array_files)
	{
		$new_array = [];
		foreach($array_files as $key => $all) {
			foreach($all as $i => $val) {
				$new_array[$i][$key] = $val;    
			}    
		}
		return $new_array;
	}
	
	/**
	 * Get upload error code 
     *
     * @return string
     */
    public function getUploadError(int $error_code) : string
    {
        $error = 0;
        switch ($error_code) {
            case 1:
                $error = sprintf("O arquivo enviado excede o limite permitido de %s.", ini_get('upload_max_filesize'));
                break;
            case 2:
                $error = sprintf("O total de arquivo(s) excede o limite permitido de %s.", ini_get('post_max_size'));
                break;
            case 3:
                $error = "O upload do arquivo foi feito parcialmente.";
                break;
            case 4:
                $error = "Nenhum arquivo foi enviado.";
                break;
            case 6:
                $error = "Pasta temporária ausênte.";
                break;
            case 7:
                $error = "Falha ao gravar o arquivo em disco.";
                break;
            case 8:
                $error = "Upload interrompido pelo servidor.";
                break;
            default:
                break;
        }
        return $error;
		
    }
	
	/**
	 * Returns all errors during the process
	 *
	 * @return mixed array|null
	 */
	public function getErrors() 
	{
		if (isset($this->debug['error']))
			return $this->debug['error'];
		else 
			return null;
	}
	
	/**
	 * Returns all uploaded files successfully
	 *
	 * @return mixed array|null
	 */
	public function getUploadedFiles()
	{
		if (count($this->uploaded_files) > 0)
			return $this->uploaded_files;
		else 
			return null;
	}
	
	//============================================================================
	// Static functions                                                          =
	//============================================================================
	
	/**
	 * Check if the upload has been active in the server
	 */
	public static function isActiveOnServer() : bool
	{
		return (ini_get('file_uploads') === '1') ? true : false;
	}

}