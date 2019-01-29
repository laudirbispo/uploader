<?php declare(strict_types=1);
namespace app\Shared\Infrastructure\Repository\EventStore\PDO;

/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use PDO;
use DateTimeImmutable;
use laudirbispo\classname\ClassName;
use libs\laudirbispo\CQRSES\{AggregateHistory, Events\DomainRecordedEvents};
use libs\laudirbispo\CQRSES\Contracts\{AggregateIdentifier, EventStore};
use app\Shared\Exceptions\StorageException;

final class PDOEventStore implements EventStore
{
	/**
     * @instance Mysqli
     */
	private $pdo;
	
	/**
     * @instance Serializer
     */
	private $serializer;
	
	public function __construct (PDO $pdo, $serializer)
	{
		$this->pdo = $pdo;
		$this->serializer = $serializer;
	}
	
	public function commit(DomainRecordedEvents $events)
	{
		try {
			
			$stmt = $this->pdo->prepare(
				'INSERT INTO `events` (aggregate_id, `mapper`, `event`, `description`, `created`, `data`, `executedBy`)
				 VALUES (:aggregate_id, :mapper, :event, :description, :created, :data, :executedBy)'
			);

			foreach ($events->getEvents() as $events) 
			{
				foreach ($events as $Event)
				{
                    if (method_exists($Event, 'getExecutedBy')) {
                        $executedBy = $Event->getExecutedBy();
                    } else {
                        $executedBy = null;
                    }
					$stmt->execute([
						':aggregate_id' => (string) $Event->getAggregateId(),
						':mapper'       => get_class($Event),
						':event'        => $Event->getEventName(),
						':description'  => $Event->getEventDescription(),
						':created'      => $Event->occurredOn(),
						':data'         => $this->serializer->serialize($Event, 'json'),
                        ':executedBy'   => $executedBy
					]);
				}

			}
		} catch (\PDOException $e) {
			throw new StorageException($e->getMessage());
		}
		
	}
	
	public function getAggregateHistory (AggregateIdentifier $aggregateId) : AggregateHistory
	{
        
		try {
			$stmt = $this->pdo->prepare(
				"SELECT * FROM `events` WHERE `aggregate_id` = :aggregate_id"
			);
			$stmt->execute([':aggregate_id' => (string) $aggregateId]);
			$events = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
			{
				$events[] = $this->serializer->deserialize(
					$row['data'],
					$row['mapper'],
					'json'
				);
			}
            
			$stmt->closeCursor();
			return new AggregateHistory($aggregateId, $events);
			
		} catch (\PDOException $e) {
			throw new StorageException($e->getMessage());
		}
		
	}
	
}
