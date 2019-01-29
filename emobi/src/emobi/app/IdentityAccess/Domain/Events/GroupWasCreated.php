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

final class GroupWasCreated extends AbstractEvent
{
	private $name;
	private $description;
	private $status;
    private $permissions;
	private $created;
	private $createdBy;
	
	public function __construct(
        string $id, 
        string $name, 
        string $description, 
        string $status,
        string $permissions,
        string $created, 
        string $createdBy
    ){
        $this->eventDescription = "Um novo grupo de usuários foi criado.";
		$this->aggregateId = $id;
		$this->name = $name;
		$this->description = $description;
		$this->status = $status;
        $this->permissions = $permissions;
		$this->created = $created;
		$this->createdBy = $createdBy;
	}
	
	/** -------------- Getters ---------------*/
	public function getName() : string 
	{ 
		return $this->name; 
	}
	
	public function getDescription() : string 
	{ 
		return $this->description; 
	}
	
	public function getStatus() : string 
	{ 
		return $this->status; 
	}
    
    public function getPermissions() : string 
    {
        return $this->permissions;
    }
	
	public function getCreated() : string 
	{ 
		return $this->created; 
	}
	
	public function getCreatedBy() : string 
	{ 
		return $this->createdBy; 
	}
	
}
