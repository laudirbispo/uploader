<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Events;
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
use libs\laudirbispo\CQRSES\Events\AbstractEvent;

final class AccountWasCreatedByAdmin extends AbstractEvent
{
	private $username;
	private $email;
    private $password;
	private $role;
    private $status;
    private $securityKey;
    private $created;
	
	public function __construct (
        string $executedBy,
        string $userId, 
        string $username, 
        string $email, 
        string $password,
        string $role,
        string $status,
        string $securityKey,
        string $created
    ){
        $this->eventDescription = sprintf("Usuário %s cadastrado.", $username);
        $this->executedBy = $executedBy;
		$this->aggregateId = $userId;
		$this->username = $username;
		$this->email = $email;
        $this->password = $password;
		$this->role = $role;
        $this->status = $status;
        $this->securityKey = $securityKey;
        $this->created = $created;
	}
	
	public function getUsername () : string
	{
		return $this->username;
	}
	
	public function getEmail () : string 
	{
		return $this->email;
	}
    
    public function getPassword() : string 
    {
        return $this->password;
    }
	
	public function getRole () : string
	{
		return $this->role;
	}
    
    public function getStatus() : string 
    {
        return $this->status;
    }
    
    public function getSecurityKey() : string 
    {
        return $this->securityKey;
    }
    
    public function getCreated() : string 
    {
        return $this->created;
    }
	
}
