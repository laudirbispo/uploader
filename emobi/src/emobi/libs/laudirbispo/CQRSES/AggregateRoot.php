<?php declare(strict_types=1);
namespace libs\laudirbispo\CQRSES;

/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @package       laudirbispo\CQRSES
 */
use laudirbispo\classname\ClassName;
use libs\laudirbispo\CQRSES\Events\DomainRecordedEvents;
use libs\laudirbispo\CQRSES\Contracts\DomainEvent;
use libs\laudirbispo\CQRSES\Exceptions\DomainLogicException;
	
abstract class AggregateRoot
{
	protected $events = [];
	
	public function getRecordedEvents(string $event = '')
	{
		if (empty($event))
			return new DomainRecordedEvents($this->events);
		
		return (isset($this->events[$event])) 
			? new DomainRecordedEvents($this->events[$event]) 
		    : new DomainRecordedEvents([]);
	}
	
	public function clearRecordedEvents(string $eventName = '') : bool 
	{
		if (empty($eventName)) {
			
			$this->events = [];
			return true;
			
		} else {
			
			if (isset($this->events[$eventName])) {
				unset($this->events[$eventName]);
				return true;
			}
			return false;	
		}
			
	}
	
	protected function recordThat(DomainEvent $Event)
	{
		$this->events[$Event->getEventName()][] = $Event;
	}
	
	protected function apply(DomainEvent $DomainEvent)
    {
        $method = 'apply' . ClassName::short($DomainEvent);
		if (!method_exists($this, $method))
			throw new DomainLogicException(
				sprintf('The method "%s" does not exists in the model "%s"', $method, ClassName::short($this))
			);
        $this->$method($DomainEvent);
    }
	
	protected function applyAndRecordThat(DomainEvent $DomainEvent)
    {
        $this->recordThat($DomainEvent);
        $this->apply($DomainEvent);
    }
	
}