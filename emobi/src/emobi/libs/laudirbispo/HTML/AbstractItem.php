<?php declare(strict_types=1);
namespace libs\laudirbispo\HTML;
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

use libs\laudirbispo\HTML\{
    Attributes, 
    HtmlException,
    Elements\Element
};

class AbstractItem 
{
    /**
     * @var string
     */
    protected $content;
    
    /**
     * @var string
     */
    protected $attributes;
    
    /**
     * @var string
     */
    protected $wrap = '<div%s>%s</div>';
    
    /**
     * @var array
     */
    private $allowedAttributes = [];
    
    public function get(bool $draw = false) : string 
    {
        if ($draw) {
            return highlight_string(sprintf($this->wrap, $this->attributes, $this->content), true);
        } else {
            return sprintf($this->wrap, $this->attributes, $this->content);
        }
    }
    
    public function setWrap(string $wrap) 
    {
        $this->wrap = $wrap;
        return $this;
    }
    
    protected function setContent($content) 
    {
        if ($content instanceof Element) {
            $this->content = $content->get();
        } elseif (is_string($content)) {
            $this->content = $content;
        } else {
            throw new HtmlException('Invalid argument $content.');
        }
        return $this;
    }
    
    protected function addContent($content) 
    {
        $this->content .= $content;
    }
    
    protected function setAllowedAttributes(array $attributes) 
    {
        $this->allowedAttributes = $attributes;
        return $this;
    }
    
    public function getContent() : string 
    {
        return $this->content;
    }
    
    public function setAttributes($attributes)
    {
       if ($attributes instanceof Attributes) {
            $this->attributes = $attributes->render();
        } elseif (is_array($attributes)) {
            $this->attributes = Attributes::builder($this->filterAttributes($attributes))->render();
        } else {
            throw new HtmlException('Invalid argument $attributes.');
        } 
    }
    
    private function filterAttributes(array $attributes) : array 
    {
        $arr = [];
        foreach ($attributes as $key => $value) {
           if (in_array($key, $this->allowedAttributes))
               $arr[$key] = $value;
        } 
        return $arr;
    }
    
    public function getAttributes() : string 
    {
        return $this->attributes;
    }
    
    public function clean(bool $allowHtml = true) : self
    {
        $text = $this->getContent();
        if (!$allowHtml) {
            $text = strip_tags($text);
        } 
        $this->setContent(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
        return $this;
    }

    public function __toString() : string 
    {
        return $this->get();
    }
    
}