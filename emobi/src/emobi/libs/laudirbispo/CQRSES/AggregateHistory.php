<?php declare(strict_types=1);
namespace libs\laudirbispo\CQRSES;
/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use libs\laudirbispo\CQRSES\Events\DomainRecordedEvents;
use libs\laudirbispo\CQRSES\Contracts\AggregateIdentifier;

class AggregateHistory extends DomainRecordedEvents
{
	/**
	 * @var string
	 */
	private $aggregateId;
	
	public function __construct (AggregateIdentifier $AggregateId, array $events)
	{

		foreach ($events as $event){
			
			if ($event->getAggregateId() !==  $AggregateId->get())
				throw new \Exceptions\AggregateIsCorruptedException("Eventos corrompidos. Não é possível recuperar seu estado.");
		}
	
		parent::__construct($events);      // DomainRecordedEvents
		$this->aggregateId = $AggregateId->get();
	}
	
	public function getAggregateId () : string
	{
		return $this->aggregateId;
	}
	
}
