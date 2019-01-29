<?php declare(strict_types=1);
namespace app\Shared\Models;
/**
 * @author - Laudir Bispo, laudirbispo@outlook.com
 * @copyright - 2017/2018
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
use app\Shared\Exceptions\{DomainLogicException, EmptyArgumentException, InvalidValueObjectException};

class UserAgent 
{
	private $user_agent;
	
	public function __construct (string $user_agent)
	{
		$this->assertNotEmpty($user_agent);
		$this->user_agent = $user_agent;
	}
	
	protected function assertNotEmpty ($user_agent)
	{
		if (empty($user_agent))
			throw new EmptyArgumentException('User agent é necessário.');
	}
	
	public static function generate ()
	{
		throw new DomainLogicException("Função generate() está indisponível.");
	}
	
	public function get() : string
	{
		return (string) $this->user_agent;
	}
	
	public function __toString ()
	{
		return (string) $this->user_agent;
	}
}