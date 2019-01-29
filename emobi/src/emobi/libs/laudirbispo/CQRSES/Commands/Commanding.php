<?php declare(strict_types=1);
namespace libs\laudirbispo\CQRSES\Commands;
/**
 * Copyright (c) Laudir Bispo  (laudirbispo@outlook.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     (c) Laudir Bispo  (laudirbispo@outlook.com)
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

class Commanding implements Commandable 
{
    protected $executedBy;
    
    public function executedBy() : string 
    {
        return $this->executedBy;
    }
}
