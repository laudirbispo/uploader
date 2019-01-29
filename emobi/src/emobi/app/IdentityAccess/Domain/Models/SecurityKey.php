<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Models;

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
use app\Shared\Exceptions\{DomainLogicException, DomainDataException};  

class SecurityKey 
{
	private $key;
	
	public function __construct($key)
	{
		$this->assertValidateKey($key);
		$this->key = $key;
	}
	
	private function assertValidateKey($key)
	{
		$encoding = mb_detect_encoding($key);
		$len = mb_strlen($key, $encoding);
		if ($len !== 128)
			throw new DomainDataException('Chave de segurança inválida.', DomainDataException::LENGTH);
	}
	
	public static function generate() : self
	{
        try {
            
            if (function_exists('openssl_random_pseudo_bytes'))
                $bytes = openssl_random_pseudo_bytes(64);
            else 
                $bytes = random_bytes(64);
            //Convert binary data into hexadecimal representation
            $key = bin2hex($bytes);
            return new self($key);
            
        } catch(\Exception $e) {
            throw new DomainLogicException(
                'Falha ao gerar Chave de Segurança - ' . $e->getMessage(), 
                DomainLogicException::UNEXPECTED_RESULT
            );
        }
        
        
	}
	
	public function get() : string 
	{
		return (string) $this->key;
	}
	
	public function __toString() : string 
	{
		return (string) $this->key;
	}
	
}
