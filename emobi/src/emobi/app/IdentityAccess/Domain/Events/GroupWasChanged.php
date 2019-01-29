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

final class GroupWasChanged extends AbstractEvent
{
	private $name;
	private $description;
    private $status;
	
	public function __construct(string $id, string $name, string $description, string $status)
	{
        $this->eventDescription = "Grupo atualizado.";
		$this->aggregateId = $id;
		$this->name = $name;
		$this->description = $description;
        $this->status = $status;
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
    
    public function __set(string $name, $value) 
    {
        $this->$name = $value;
    }
    
    public function __get(string $name) 
    {
        return $this->$name;
    }
	
}
