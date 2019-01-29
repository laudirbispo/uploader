<?php declare(strict_types=1);
namespace app\Shared\Models;
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
use app\Shared\Exceptions\DomainDataException; 

class Token128 
{
	private $token;
	
	public function __construct (string $token)
	{
		$this->assertValidToken($token);
		$this->token = $token;
	}
	
	private function assertValidToken ($token)
	{
		if (!preg_match('/^(0[xX])?[a-fA-F0-9]{128}+$/', $token))
			throw new DomainDataException('Token inválido.');
	}
	
	public static function generate () : self 
	{
		if (function_exists('openssl_random_pseudo_bytes'))
			$bytes = openssl_random_pseudo_bytes(64);
		else 
			$bytes = random_bytes(64);
		//Convert binary data into hexadecimal representation
		$token = bin2hex($bytes);
		return new self($token);
	}
	
	public function get() : string 
	{
		return (string) $this->token;
	}
	
	public function __toString () : string 
	{
		return (string) $this->token;
	}
}
