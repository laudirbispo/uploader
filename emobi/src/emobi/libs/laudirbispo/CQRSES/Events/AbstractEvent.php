<?php declare(strict_types=1);
namespace libs\laudirbispo\CQRSES\Events;

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
use laudirbispo\classname\ClassName;
use libs\laudirbispo\CQRSES\Contracts\DomainEvent;

class AbstractEvent implements DomainEvent
{
    /** Who triggered the process **/
    protected $executedBy;
    
    protected $aggregateId;
    
    protected $eventDescription = 'Without description';
    
    /** -------- Implementation of DomainEvent Interface --------*/
    public function getAggregateId() : string 
    {
        return $this->aggregateId;
    }
    
    public function getEventName() : string 
    {
        $class = get_called_class();
        return ClassName::short($class);
    }
    
    public function getEventDescription() : string 
    {
        return $this->eventDescription;
    }
    
    public function occurredOn() : string 
    {
        return (new \DateTimeImmutable())->format('Y-m-d H:i:s');
    }
    
    public function getExecutedBy() : string 
    {
        return $this->executedBy;
    }
}
