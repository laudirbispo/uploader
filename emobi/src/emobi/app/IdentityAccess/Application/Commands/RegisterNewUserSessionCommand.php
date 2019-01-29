<?php declare(strict_types=1);
namespace app\IdentityAccess\Application\Commands;
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

final class RegisterNewUserSessionCommand 
{
    private $userId;
    private $username;
    private $email;
    private $role;
    private $expireIn;
    private $domains = [];
    private $browser;
    private $ip;
    
    public function __construct(
        $userId,
        $username,
        $email,
        $role,
        int $expireIn = 0,
        array $domains = [],
        $browser,
        $ip
    ){
        
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->expireIn = $expireIn;
        $this->domains = $domains;
        $this->browser = $browser;
        $this->ip = $ip;  
    }
    
    public function getUserId() 
    {
        return $this->userId;
    }
    
    public function getUsername() 
    {
        return $this->username;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function getExpireIn()
    {
        return $this->expireIn;
    }
    
    public function getDomains() : array 
    {
        return $this->domains;
    }
    
    public function getBrowser() 
    {
        return $this->browser;
    }
    
    public function getIp()
    {
        return $this->ip;
    }

}
