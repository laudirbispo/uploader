<?php declare(strict_types=1);
namespace libs\laudirbispo\CQRSES\Infrastructure;
/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com)
 * @version         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use laudirbispo\classname\ClassName;
use libs\laudirbispo\CQRSES\Events\DomainRecordedEvents;
use libs\laudirbispo\CQRSES\Contracts\Projectable;

abstract class BaseProjection implements Projectable
{
	public function project(DomainRecordedEvents $eventStream)
    {
        foreach ($eventStream->getEvents() as $events) {
			
			foreach ($events as $event) {
				$projectMetohd = 'project' . ClassName::short($event);
            	$this->$projectMetohd($event);
			}
            
        }
    }

}
