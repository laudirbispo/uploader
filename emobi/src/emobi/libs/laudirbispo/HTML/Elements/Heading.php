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

final class Heading extends AbstractItem implements Element
{
    const H1 = '<h1%s>%s</h1>';
    const H2 = '<h2%s>%s</h2>';
    const H3 = '<h3%s>%s</h3>';
    const H4 = '<h4%s>%s</h4>';
    const H5 = '<h5%s>%s</h5>';
    const H6 = '<h6%s>%s</h6>';
    const ALLOW_ATTRIBUTES = ['class', 'id', 'style'];
    
    public function __construct($content, $attributes = [], string $size = self::H4) 
    {
        $this->setAllowedAttributes(self::ALLOW_ATTRIBUTES);
        $this->setWrap($size);
        $this->setContent($content);
        $this->setAttributes($attributes);
    }
    
    public static function h1($content, $attributes = []) : string
    {
        return (string) new self($content, $attributes, self::H1);
    }
    
    public static function h2($content, $attributes = []) : string
    {
        return (string) new self($content, $attributes, self::H2);
    }
    
    public static function h3($content, $attributes = []) : string
    {
        return (string) new self($content, $attributes, self::H3);
    }
    
    public static function h4($content, $attributes = []) : string
    {
        return (string) new self($content, $attributes, self::H4);
    }
    
    public static function h5($content, $attributes = []) : string
    {
        return (string) new self($content, $attributes, self::H5);
    }
    
    public static function h6($content, $attributes = []) : string
    {
        return (string) new self($content, $attributes, self::H6);
    }
   
}
