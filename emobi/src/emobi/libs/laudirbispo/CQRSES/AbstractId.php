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
use Ramsey\Uuid\Uuid;
use libs\laudirbispo\CQRSES\Contracts\AggregateIdentifier;

abstract class AbstractId implements AggregateIdentifier
{
    protected $id;
	
    protected function assertValidId($id)
	{
		return (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $id) === 1)
            ? true : false;	
	}
    
    public function id() : string
    {
        return $this->id;
    }
	
	public static function fromString($id) : AggregateIdentifier
    {
        $class = get_called_class();
        return new $class($id);
    }
	
	public function equals(AggregateIdentifier $IdToCompare) : bool
	{
		$equalObjects = false;

        if (null !== $IdToCompare && get_class($this) === get_class($IdToCompare)) {
            $equalObjects = $this->id() === $IdToCompare->id();
        }

        return $equalObjects;
	}

	public static function generate() : AggregateIdentifier
	{
		$class = get_called_class();
        return new $class(Uuid::uuid4()->toString());
	}
	
	public function get() : string
	{
		return (string) $this->id;
	}
	
	public function __toString() : string 
	{
		return (string) $this->id;
	}
}