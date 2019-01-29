<?php declare(strict_types=1);
namespace app\IdentityAccess\Infrastructure\Services;
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
use Symfony\Component\Cache\Simple\FilesystemCache;
use app\Shared\Exceptions\DomainLogicException;

/** 
 * This class uses the symfony cache component to save the tokens
 */
class TokenizerService
{
    
    const DEFAULT_KEY_SIZE = 16;
    const DEFAULT_TOKEN_SIZE = 128;
    const DEFAULT_TOKEN_LIFETIME = 120;
    
    /**
     * Instance that implements AdapterInterface;
     */
    private $cache;
    
    public function __construct() 
    {
        $this->cache = new FilesystemCache();
    }
    
    /**
     * Save Token
     *
     * @param $key (string) - The key that identifies the token
     * @param $token (mixed) - The token or combination of informations
     * @param $lifetime (int) - The lifetime of the token in seconds. Can not be less than 30 seconds.
     */
    public function save(string $key, $token = null, int $lifetime = self::DEFAULT_TOKEN_LIFETIME) : bool 
    {

        if ($lifetime < 30) {
            throw new DomainLogicException(
                get_class($this).': Token lifetime can not be less than 30 seconds.', 
                DomainLogicException::INVALID_ARGUMENT
            );
        }

        return $this->cache->set($key, $token, $lifetime);
    }
    
    public function getToken(string $key)
    {
         if ($this->cache->has($key)) {
             return $this->cache->get($key);
         } else {
             return null;
         }
    }
    
    public static function generateToken(int $size = self::DEFAULT_TOKEN_SIZE) : string 
    {
        if ($size < 64) {
            throw new DomainLogicException(
                get_class($this).': Token length is too short.', 
                DomainLogicException::INVALID_ARGUMENT
            );
        }
        if (function_exists('openssl_random_pseudo_bytes'))
			$bytes = openssl_random_pseudo_bytes(self::DEFAULT_TOKEN_SIZE);
		else 
			$bytes = random_bytes(self::DEFAULT_TOKEN_SIZE);
		//Convert binary data into hexadecimal representation
		$token = bin2hex($bytes);
        return $token;
    }
    
    public static function generateKey(int $size = 16) : string 
    {
        if ($size <= 0) {
            throw new DomainLogicException(
                get_class($this).': Ivalid Key size.', 
                DomainLogicException::INVALID_ARGUMENT
            );
        }
        
        if (function_exists('openssl_random_pseudo_bytes'))
			$key = openssl_random_pseudo_bytes($size);
		else 
			$key = random_bytes($size);
		//Convert binary data into hexadecimal representation
		return bin2hex($key);
    }
    
}
