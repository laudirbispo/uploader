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
use app\Utils\Regex;
use app\Shared\Exceptions\DomainDataException;

final class GroupName 
{
	const MIN_LENGTH = 2;
	const MAX_LENGTH = 20;
	const VALIDATE_EXPRESSION = '/^[a-z\d\_\.]{256}$/i';
	
	private $groupName;
	private $regex;
	
	public function __construct ($groupName)
	{
		$this->assertNotEmpty($groupName);
		$this->assertFitsLength($groupName);
		$this->groupName = $groupName;
	}
	
	private function assertNotEmpty ($groupName)
	{
		if (empty($groupName))
			throw new DomainDataException('Talvez você tenha esquecido de preencher o nome do grupo.', DomainDataException::EMPTY_ARGUMENT);
	}
	
	private function assertFitsLength ($groupName)
	{
		$encoding = mb_detect_encoding($groupName);
		$len = mb_strlen($groupName, $encoding);
		if ($len < self::MIN_LENGTH || $len > self::MAX_LENGTH)
			throw new DomainDataException(
				sprintf("O nome do grupo deve conter entre %s e %s caracteres.", self::MIN_LENGTH, self::MAX_LENGTH),
				DomainDataException::LENGTH
			);
	}
	
	public function get() : string
	{
		return (string) $this->groupName;
	}
	
	public function __toString () : string 
	{
		return $this->groupName;
	}
	
}