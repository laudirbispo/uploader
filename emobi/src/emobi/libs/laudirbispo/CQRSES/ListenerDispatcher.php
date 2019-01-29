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
 */
use laudirbispo\classname\ClassName;
use libs\laudirbispo\CQRSES\Contracts\{DomainEvent, EventListener};

class ListenerDispatcher 
{
	/**
	 * Listeners registered
	 * @var array
	 */
	private $listeners = [];
	
	/**
	 * clean up listeners after dispatch
	 * @var bool
	 */
	private $cleanAfterDispatch;
	
	/**
	 * Silent errors
	 * @var array
	 */
	public $errors = [];
	
	public function __construct(bool $cleanAfterDispatch = true) 
	{
		$this->cleanAfterDispatch = $cleanAfterDispatch;
	}
	
	public function addListener(string $eventName, EventListener $Listener)
	{
		// We check if the listener is not signed for this event
		if ($this->thereIsAListenerForTheEvent($eventName)) {
			
			foreach ($this->listeners[$eventName] as $inscribed) {
				// Prevents duplicate item addition
				if (ClassName::full($inscribed) === ClassName::full($Listener))
					return false;
				else
					$this->listeners[$eventName][] = $Listener;
			}
			
		} else {
			$this->listeners[$eventName][] = $Listener;
		}
		return true;
	}
	
	/**
	 * @return void
	 */
	public function dispatch($events) : void
	{
		if (is_array($events)) {
			foreach ($events as $event) {
				$this->dispatch($event);
			}
		} else if ($events instanceof DomainEvent) {	
			$this->doDispatch($events);	
		} else {
			$this->errors[] = sprintf("%s não é um evento de domínio válido.", gettype($events));
		}
	}
	
	/**
	 * @return void
	 */
	private function doDispatch(DomainEvent $event) : void
	{
		$eventName = $event->getEventName();
		if ($this->thereIsAListenerForTheEvent($eventName)) {
			foreach ($this->listeners[$eventName] as $Listener) {
                if (method_exists($Listener, 'handler')) {
                    $Listener->handler($event);
                } elseif (method_exists($Listener, 'execute')) {
                   $Listener->execute($event); 
                } else {
                    $this->errors[] = "Não existe um método de execução no ouvinte cadastrado.";
                    continue;
                }
			}
			// remove listener
			if ($this->cleanAfterDispatch)
				unset($this->listeners[$eventName]);
		}
	}
	
	public function getListeners(string $eventName = '') : array
	{
		if (empty($eventName))
			return $this->listeners;
		else
			return $this->listeners[$eventName];
	}
	
	public function thereIsAListenerForTheEvent(string $eventName) : bool 
	{
		return isset($this->listeners[$eventName]);
	}
	
	/**
	 * @return void
	 */
	public function cleanListeners(string $eventName = '') : void
	{
		if (empty($eventName))
			$this->listeners = [];
		else
			if ($this->thereIsAListenerForTheEvent($eventName))
				unset($this->listeners[$eventName]);
	}
	
	public function getErrors () : array 
	{
		return $this->errors;
	}
	
}
