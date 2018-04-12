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

final class ImageUpload extends AbstractFileUploader 
{
	/**
	 * List of mime types valids for images
	 */
	private $images_mimes = array('image/cgm', 'image/fits', 'image/g3fax', 'image/gif', 'image/ief', 'image/jp2', 'image/jpeg', 'image/jpm', 'image/jpx', 'image/naplps', 'image/png', 'image/prs.btif', 'image/prs.pti', 'image/t38', 'image/tiff', 'image/tiff-fx', 'image/vnd.adobe.photoshop', 'image/vnd.cns.inf2', 'image/vnd.djvu', 'image/vnd.dwg', 'image/vnd.dxf', 'image/vnd.fastbidsheet', 'image/vnd.fpx', 'image/vnd.fst', 'image/vnd.fujixerox.edmics-mmr', 'image/vnd.fujixerox.edmics-rlc', 'image/vnd.globalgraphics.pgb', 'image/vnd.microsoft.icon', 'image/vnd.mix', 'image/vnd.ms-modi', 'image/vnd.net-fpx', 'image/vnd.sealed.png', 'image/vnd.sealedmedia.softseal.gif', 'image/vnd.sealedmedia.softseal.jpg', 'image/vnd.svf', 'image/vnd.wap.wbmp', 'image/vnd.xiff');
	
	/**
	 * The maximum width of the image
	 *
	 * @var mixed null|array
	 */
	private $exact_dimension = null;
	/**
	 * The maximum width of the image
	 *
	 * @var int
	 */
	private $max_width = 5000;
	
	/**
	 * The minimum width of the image
	 *
	 * @var int
	 */
	private $min_width = 1;
	
	/**
	 * The maximum height of the image
	 *
	 * @var int
	 */
	private $max_height = 5000;
	
	/**
	 * The minimum height of the image
	 *
	 * @var int
	 */
	private $min_height = 1;
	
	public function __construct () 
	{
		// prevent bad startup
		parent::__construct();
	}
	
	/**
	 * Set exact dimension of the image
	 *
	 * @param int $width - the width 
	 * @param int $width - the height
	 */
	public function exactDimension (int $width, int $height)
	{
		if (is_int($width) && is_int($height))
		{
			$this->exact_dimension['width'] = $width;
			$this->exact_dimension['height'] = $height;
		}
		return $this;
	}
	
	/**
	 * Set maximum dimension of the image
	 *
	 * @param int $width - the maximum width allowed on the image
	 * @param int $width - the maximum height allowed on the image
	 */
	public function maxDimension (int $width, int $height)
	{
		if (is_int($width) && is_int($height))
		{
			$this->max_width = $width;
			$this->max_height = $height;
		}
		
		return $this;

	}
	
	/**
	 * Set minimum dimension of the image
	 *
	 * @param int $width - the maximum width allowed on the image
	 * @param int $width - the maximum height allowed on the image
	 */
	public function minDimension (int $width, int $height)
	{
		if (is_int($width) && is_int($height))
		{
			$this->min_width = $width;
			$this->min_height = $height;
		}
		
		return $this;

	}
	
	/** 
	 * IMPLEMENTATION OF THE ABSTRACT METHOD
	 * Tries to move the file 
	 *
	 * @param $rename (bool) - If true, rename files
	 */
	public function move (array $files, bool $rename = false)
	{
		// Reset list of recently uploaded files
		$this->uploaded_files = [];
		
		// Reorganize array to facilitate the process
		$files = $this->rearrange($files);
		
		foreach ($files as $file)
		{
			$this->file = $file;
			$this->file['extension'] = pathinfo($file['name'], PATHINFO_EXTENSION);
			
			// Use this method to validate the normal properties of the file
			if (!$this->isValidFile())
				continue;
			
			// This method validate the specific image files
			if (!$this->isValidImage())
				continue;

			//  Triy uploading the file
			$this->moveUpload($rename);

			continue;
		}
		
		return;
		
	}
	
	
	/**
	 * Make sure the image does not exceed the allowed dimensions
	 */
	private function isValidImage () : bool
	{
		/**
		 * Make sure the file is an image
		 */
		if (!in_array($this->file['type'], $this->images_mimes))
		{
			$this->debug['error'][] = array('message' => "Não é uma imagem válida.",
										    	'file' => $this->file['name']
										   	   );
			return false;
		}
		
		// Make sure that the image has the exact dimension required
		list($width, $height) = @getimagesize($this->file['tmp_name']);
		
		if (null !== $this->exact_dimension)
		{
			if ($width !== $this->exact_dimension['width'] || $height !== $this->exact_dimension['height'])
			{
				$this->debug['error'][] = array('message' => "Esta imagem deve ser exatamente {$this->exact_dimension['width']}x{$this->exact_dimension['height']}px.",
										    	'file' => $this->file['name']
										   	   );
				return false;
			}
			
		}

		// Make sure that the image does not exceeded the maximum width and height allowed
		if ($width > $this->max_width || $height > $this->max_height)
		{
			$this->debug['error'][] = array('message' => "Esta imagem ultrapassa as dimensões permitidas. Sua largura máxima deve ser {$this->max_width}px e sua altura {$this->max_height}px.",
										    'file' => $this->file['name']
										   );
			return false;
		}
		
		// Make sure that the image reaches the minimum width and height requireds
		if ($width < $this->min_width || $height < $this->min_width)
		{
			$this->debug['error'][] = array('message' => "Esta imagem é menor que as dimensões requeridas. Sua largura mínima deve ser {$this->min_width}px e sua altura {$this->min_height}px.",
										    'file' => $this->file['name']
										   );
			return false;
		}
		
		// Successfully
		return true;
		
	}
	
}
