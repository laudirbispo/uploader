<?php declare(strict_types=1);
namespace app\Shared\Models;
/**
 * @author - Laudir Bispo, laudirbispo@outlook.com
 *
 * AVISO DE LICENÇA
 * 
 * @license - Em hipótese alguma é permitido ao LICENCIADO ou a terceiros, de forma geral:
 * Copiar, ceder, sublicenciar, vender, dar em locação ou em garantia, reproduzir, doar, 
 * alienar de qualquer forma, transferir total ou parcialmente, sob quaisquer modalidades, gratuita ou onerosamente, 
 * provisória ou permanentemente, o SOFTWARE objeto deste EULA, assim como seus módulos, partes,  
 * manuais ou quaisquer informações relativas ao mesmo;
 * Retirar ou alterar, total ou parcialmente, os avisos de reserva de direito existente no SOFTWARE e na documentação;
 * Praticar de engenharia reversa, descompilação ou desmontagem do SOFTWARE.
 * Estando totalmente sujeito a suspensão imediata da utilização do software e cancelamento do período de contratação, 
 * sem quaisquer restituições contratuais por parte da LICENCIANTE.  
 *
 */
use app\Shared\Contracts\Collectable;

abstract class Collection 
{
    private $items = [];
    
    private $listName;
    
    //abstract private function validateItems() : bool;
    
    public function __construct(string $listName = 'undefined') 
    {
        $this->listName = $listName;
    }
    
    public function getListName() : string 
    {
        return $this->listName;
    }
    
    public function addItem(?string $key = null, Collectable $Item) : bool 
    {
        if (null === $key) {
            $this->items[] = $Item;
            return true;
        } else {
            if (isset($this->items[$key])) {
                return false;
            } else {
                $this->items[$key] = $Item;
            }
            return true;
        }
        
    }
    
    public function removeItem(string $key) : void 
    {
       unset($this->items[$key]);
    }
    
    public function getItem(string $key) : ?Collectable 
    {
        return $this->items[$key];
    }
    
    public function getAll() : array 
    {
        return $this->items;
    }
    
    public function hasItem(string $key) : bool 
    {
        return isset($this->items[$key]);
    }
    
    public function keys() : array 
    {
        return array_keys($this->items);
    }
    
    public function countItems(?string $key = null) : int
    {
        if (null === $key) {
            return count($this->items);
        } else {
            return count($this->items[$key]);
        }
    }
    
}