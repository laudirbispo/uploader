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
use app\Shared\Exceptions\{DomainLogicException, InvalidValueObjectException};

class Sessid 
{
	private $value;
	
	public function __construct (string $value)
	{
		$this->assertValidSessid($value);
		$this->value = $value;
	}
	
	private function assertValidSessid ($value)
	{
		if (!preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $value))
			throw new InvalidValueObjectException('Session Id inválido.');
	}
	
	/**
	 * For security reasons this function is disabled.
	 * Use a different library to generate
	 */
	public static function generate ()
	{
		throw new DomainLogicException("Função generate() está indisponível.");
	}
	
	public function get() : string
	{
		return (string) $this->value;
	}
	
	public function __toString ()
	{
		return (string) $this->value;
	}
	
}