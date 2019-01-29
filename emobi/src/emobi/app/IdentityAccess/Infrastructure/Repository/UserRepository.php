<?php declare(strict_types=1);
namespace app\IdentityAccess\Infrastructure\Repository;
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
use libs\laudirbispo\CQRSES\AggregateRoot;
use libs\laudirbispo\CQRSES\Contracts\{Repository, AggregateIdentifier, Projectable};

class UserRepository implements Repository
{
	private $eventStore;
	private $projection;
	
	public function __construct($EventStore, Projectable $Projection)
	{
		$this->projection = $Projection;
		$this->eventStore = $EventStore;
	}
	
	public function get(AggregateIdentifier $aggregateId)
	{
		return $this->eventStore->getAggregateHistory($aggregateId);
	}
	
	public function save(AggregateRoot $Aggregate)
	{
		$events = $Aggregate->getRecordedEvents();
		$this->projection->project($events);
		$this->eventStore->commit($events);
	}
	
}