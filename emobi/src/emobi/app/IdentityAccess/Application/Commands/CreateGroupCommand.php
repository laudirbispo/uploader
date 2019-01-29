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

final class CreateGroupCommand 
{
	private $name;
	private $description;
	private $status;
	private $createdBy;
	
	public function __construct($name, $description, $status, $createdBy)
	{
		$this->name = $name;
		$this->description = $description;
		$this->status = $status;
		$this->createdBy = $createdBy;
	}
	
	public function getName () 
	{
		return $this->name;
	}
	
	public function getDescription () 
	{ 
		return $this->description;
	}
	
	public function getStatus () 
	{ 
		return $this->status;
	}
	
	public function getCreatedBy () 
	{ 
		return $this->createdBy; 
	}
	
}