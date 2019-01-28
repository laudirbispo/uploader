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

final class FileUpload extends AbstractFileUploader 
{
	
	public function __construct () 
	{
		// prevent bad startup
		parent::__construct();
	}

	/** 
	 * IMPLEMENTATION OF THE ABSTRACT METHOD
	 * Tries to move the file 
	 *
	 * @param $rename (bool) - If true, rename files
	 */
	public function move($files, bool $rename = false)
	{
		// Reset list of recently uploaded files
		$this->uploaded_files = [];
		
        if (is_array($files)) {
            // Reorganize array to facilitate the process
            $files = $this->rearrange($files);

            foreach ($files as $file) {
                $this->file = $file;
                $this->file['extension'] = pathinfo($file['name'], PATHINFO_EXTENSION);
                // Use this method to validate the normal properties of the file
                if (!$this->isValidFile())
                    continue;
                //  Triy uploading the file
                $this->moveUpload($rename);
                continue;
            }
        } else {
            // Use this method to validate the normal properties of the file
            if (!$this->isValidFile())
                continue;
            //  Triy uploading the file
            $this->moveUpload($rename);
            continue;
        }
		return;
		
	}
	
}
