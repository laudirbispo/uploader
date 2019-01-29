<?php declare(strict_types=1);
namespace libs\laudirbispo\CQRSES\Contracts;

/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com)
 * @version       1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @package       laudirbispo\CQRSES
 */

interface AggregateIdentifier 
{
    public function id() : string;
	public static function fromString($id) : self;
	public function equals(AggregateIdentifier $OtherId);
	public static function generate() : AggregateIdentifier;
	public function __toString() : string;
}
