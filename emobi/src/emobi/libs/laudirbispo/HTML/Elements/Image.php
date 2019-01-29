<?php declare(strict_types=1);
namespace libs\laudirbispo\HTML\Elements;
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
use libs\laudirbispo\HTML\{AbstractItem, HtmlException};

final class Image extends AbstractItem implements Element
{   
    const WRAP = '<img%s>%s';
    const ALLOW_ATTRIBUTES = ['class', 'id', 'style', 'src', 'alt', 'width', 'height', 'title'];
        
    public function __construct($attributes = []) 
    {
        $this->setAllowedAttributes(self::ALLOW_ATTRIBUTES);
        $this->setWrap(self::WRAP);
        
        $this->setContent('');
        $this->setAttributes($attributes);
    }
    
    public static function make($attributes = []) : self 
    {
        return new self($attributes);
    }
    
}