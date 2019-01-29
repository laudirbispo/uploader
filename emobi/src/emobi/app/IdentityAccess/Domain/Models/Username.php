<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Models;

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
use app\Shared\Models\Regex;
use app\Shared\Exceptions\DomainDataException;

final class Username 
{
	const MIN_LENGTH = 6;
	const MAX_LENGTH = 20;
	const VALIDATE_EXPRESSION = '/^[a-z\d\_\.]{6,20}$/i';
	
	private $username;
	private $regex;
	
	public function __construct($username)
	{
		$this->regex = new Regex((string) self::VALIDATE_EXPRESSION);
		$this->assertNotEmpty($username);
		$this->assertWithRegex($username);
		$this->username = $username;
	}
	
	private function assertNotEmpty($username)
	{
		if (empty($username))
			throw new DomainDataException('É preciso fornecer um username.', DomainDataException::EMPTY_ARGUMENT);
	}
	
	private function assertWithRegex($username)
	{
		if (!preg_match($this->regex->expression(), $username))
			throw new DomainDataException('Username inválido.', DomainDataException::REGEX_INVALIDATED);
	}
	
	private function asssertFitsLength($username)
	{
		$encoding = mb_detect_encoding($username);
		$len = mb_strlen($username, $encoding);
		if ($len < self::MIN_LENGTH)
			throw new DomainDataException(
				sprintf("Username deve conter no mínimo %s caracteres.", self::MIN_LENGTH),
				DomainDataException::MIN_LENGTH
			);
		if ($len > self::MAX_LENGTH)
			throw new DomainDataException(
				sprintf("Username deve conter no máximo %s caracteres.", self::MAM_LENGTH),
				DomainDataException::MAX_LENGTH
			);
	}
	
	public function get() : string
	{
		return (string) $this->username;
	}
	
	public function __toString() : string
	{
		return $this->username;
	}
	
}