<?php declare(strict_types=1);
namespace app\Shared\Services;
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

final class Md5HashService
{
    /**
     * @var string
     */
    private $value;
    
    /**
     * @var bool
     */
    private $raw;
    
    public function __construct(string $value, bool $raw = false)
    {
        $this->value = $value;
        $this->raw = $raw;
    }
    
    /**
     * Usage the MD5 HASH
     */
    public function hash() : string
    {
        return md5($this->value, $this->raw);
    }
    
    public function verify(string $hash) : bool 
    {
        return ($hash === md5($this->value, $this->raw));
    }
    
    public static function isMd5($hash) : bool 
    {
        return strlen($hash) == 32 && ctype_xdigit($hash);
    }
    
}
