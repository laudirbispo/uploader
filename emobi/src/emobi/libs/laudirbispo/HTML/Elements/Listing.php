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

class Listing extends AbstractItem implements Element
{   
    const ALLOW_ATTRIBUTES = ['class', 'id', 'style', 'type'];
    
    /**
     * @var string
     */
    private $listType;
    
    /**
     * @var array 
     */
    private $itens = '';
    
    /**
     * @var string
     */
    private $activeClass = 'active';
    
    public function __construct(string $listType = 'ul', $attributes = []) 
    {
        $this->setAllowedAttributes(self::ALLOW_ATTRIBUTES);
        if ($listType === 'ol') {
            $this->setWrap('<ol%s>%s</ol>');
        } elseif ($listType === 'ul') {
            $this->setWrap('<ul%s>%s</ul>');
        } else {
            throw new HtmlException('Invalid argument $listType.');
        }
        $this->setAttributes($attributes);
    }
    
    public static function make(string $listType = 'ul', $attributes = []) : self 
    {
        return new self($listType, $attributes);
    }
    
    
    public function addItem($content, bool $active = false) 
    {
        if (!($content instanceof Element) && !(is_string($content))) {
            throw new HtmlException('Invalid argument $content for addItem.');  
        }
        $active = ($active) ? ' class="'.$this->activeClass.'"' : '';
        $this->content .= sprintf('<li%s>%s</li>', $active, $content);
        return $this;
    }
    
    public function addSeparator(string $class = 'separator') 
    {
        $this->content .= sprintf('<li class="%s"></li>', $class);
        return $this;
    }
    
}