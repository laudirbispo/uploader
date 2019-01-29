<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Models;
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

final class Account 
{
    private $userId;
    
    private $username;
    
    private $email;
    
    private $role;
	
	private $password;
    
    private $pin;

	private $securityKey;

	private $status;
    
    private $lastPasswordChange;
	
	private $created;
    
    public function __construct(string $userId) 
    {
        $this->userId = $userId;
    }
 
    public function setUsername(string $username) 
    {
        $this->username = $username;
        return $this;
    }
    
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }
    
    public function setRole(string $role)
    {
        $this->role = $role;
        return $this;
    }
    
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }
    
    public function setPin(?string $pin) 
    {
        $this->pin = $pin;
        return $this;
    }
    
    public function setSecurityKey(string $key)
    {
        $this->securityKey = $key;
        return $this;
    }
    
    public function setStatus(string $status) 
    {
        $this->status = $status;
        return $this;
    }
    
    public function setLastPasswordChange(?string $date) 
    {
        $this->lastPasswordChange = $date;
        return $this;
    }
    
    public function setCreated(string $date) 
    {
        $this->created = $date;
        return $this;
    }
    
    public function getUsername() : string
    {
        return $this->username;
    }
    
    public function getEmail() : string 
    {
        return $this->email;
    }
    
    public function getRole() : string 
    {
        return $this->role;
    }
    
    public function getPassword() : string 
    {
        return $this->password;
    }
    
    public function getPin() : ?string 
    {
        return $this->pin;
    }
    
    public function getSecurityKey() : string 
    {
        return $this->securityKey;
    }
    
    public function getStatus() : string 
    {
        return $this->status;
    }
    
    public function getLastPasswordChange() : ?string 
    {
        return $this->lastPasswordChange;
    }
    
    public function getCreated() : string 
    {
        return $this->created;
    }
    
}
