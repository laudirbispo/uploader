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

final class Menu extends AbstractItem implements Element
{
    const WRAP = '<ul%s>%s</ul>';
    const ALLOW_ATTRIBUTES = ['class', 'id', 'style', 'type'];
    
    private $activeClass = 'active';
    
    private $itens = [];
    
    /** 
     * @var string
     */
    private $openContainer;
    
    /** 
     * @var string
     */
    private $closeContainer;
    
    public function __construct($title = '', $attributes = []) 
    {
        $this->setAllowedAttributes(self::ALLOW_ATTRIBUTES);
        $this->setWrap(self::WRAP);
        $this->setAttributes($attributes);
        $this->addContent($title);
    }
    
    public static function make($attributes = [])  : self 
    {
        return new self($attributes);
    }
    
    public function addItem($item = '', bool $active = false) 
    {
        if ($item instanceof Element) {
            $content = $item->get();
        } elseif (is_string($item)) {
           $content = $item; 
        } else {
            throw new HtmlException('Invalid argument $item for addItem().');
        }
        $active = ($active) ? ' class="'.$this->activeClass.'"' : '';
        $this->itens[] = sprintf('<li%s>%s</li>', $active, $content);
        return $this;
    }
    
    public function addSubmenu(Menu $Menu, bool $active = false) 
    {
        $this->addItem($Menu->show());
        return $this;
    }
 
    public static function submenu($title = '', $attributes = [])  : self 
    {
        return new self($title, $attributes);
    }
    
    public function setActiveClass(string $class) 
    {
        $this->activeClass = $class;
        return $this;
    }
    
    public function openContainer($content) : self
    {
        if ($content instanceof Element) {
            $content = $content->get();
        } elseif (!is_string($content)) {
            throw new HtmlException('Invalid argument $content for openContainer().');
        }
        $this->openContainer = $content;
        return $this;
    }
    
    public function closeContainer($content) : self 
    {
        if ($content instanceof Element) {
            $content = $content->get();
        } elseif (!is_string($content)) {
            throw new HtmlException('Invalid argument $content for openContainer().');
        }
        $this->closeContainer = $content;
        return $this;
    }
    
    public function show()  
    {
        $this->addContent($this->openContainer);
        $content = '';
        foreach ($this->itens as $elem) {
            $content .= $elem;
        }
        $this->addContent(sprintf($this->wrap, $this->attributes, $content));
        $this->addContent($this->closeContainer);
        return $this->content;
    }
  
}