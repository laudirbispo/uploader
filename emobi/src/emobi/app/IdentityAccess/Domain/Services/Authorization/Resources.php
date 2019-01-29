<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Services\Authorization;
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
    
final class Resources 
{
    private $resources = [];
    
    public function __construct()
    {
        $configurations = (ROOT_DIR . '/app/IdentityAccess/Domain/Services/Authorization/app_resources.php');
        if (!file_exists($configurations))
            throw new \Exception($configurations);
        else
            $this->resources = include_once $configurations;
    }
    
    public function getAll() : array 
    {
        return $this->resources;
    }
    
    public function hasGroup(string $group) : bool 
    {
        return (isset($this->resources[$group]));
    }
    
    public function getGroup(string $group = '') : array 
    {
        if (isset($this->resources[$group]))
            return $this->resources[$group];
        else
            return [];
    }
    
    public function getGroupDescription(string $group) : string 
    {
        if (isset($this->resources[$group]))
            return $this->resources[$group]['description'];
        else
            return '';
    }
    
    public function hasResource(string $resource = '') 
    {
        if (empty($resource))
            return false;

        foreach ($this->resources as $key) {
            return isset($key['resources'][$resource]);  
        }
        return false;
    }
    
    public function getResourceDescription(string $resource) : string 
    {
        if (empty($resource))
            return null;
        
        foreach ($this->resources as $key) {
            if (isset($key['resources'][$resource]))
                return $key['resources'][$resource]['description'];
            else
                '';
        }
    }
    
}
