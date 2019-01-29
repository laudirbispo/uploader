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
use app\Shared\Exceptions\EmptyArgumentException;

class Name
{
	const NAME_MIN_LENGTH = 3;
	const NAME_MAX_LENGTH = 20;
	const LAST_NAME_MIN_LENGTH = 3;
	const LAST_NAME_MAX_LENGTH = 50;
	
	private $firstName;
	private $lastName;
	
	public function __construct(string $firstName, string $lastName)
	{
		$this->assertNotEmpty($firstName, $lastName);
		$this->assertFitsLength($firstName, $lastName);
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}
	
	public function firstName()
	{
		return $this->firstName;
	}
	
	public function lastName()
	{
		return $this->lastName;
	}
	
	public function fullName()
	{
		return $this->firstName . ' ' . $this->lastName;
	}
	
	private function assertNotEmpty(string $firstName, string $lastName)
	{
		if (empty($firstName))
			throw new EmptyArgumentException('Um usuário deve possuir um nome.');
		if (empty($lastName))
			throw new EmptyArgumentException('Um usuário deve possuir um sobrenome.');
	}
	
	private function assertFitsLength(string $firstName, string $lastName)
	{
		$firstName_len = mb_strlen($firstName);
		$lastName_len = mb_strlen($lastName);
		
		if ($firstName_len < self::NAME_MIN_LENGTH || $firstName_len > self::NAME_MAX_LENGTH)
			throw new \LengthException(
				sprintf("O nome de usuário deve ter entre %s e %s caracteres.", self::NAME_MIN_LENGTH, self::NAME_MAX_LENGTH)
			);

		if ($lastName_len < self::LAST_NAME_MIN_LENGTH || $lastName_len > self::LAST_NAME_MAX_LENGTH)
			throw new \LengthException(
				sprintf("O sobrenome do usuário deve conter entre %s e %s caracteres.", self::NAME_MIN_LENGTH, self::LAST_NAME_MAX_LENGTH)
			);
	}
	public function get() : string
	{
		return (string) $this->firstName . ' ' . $this->lastName;
	}
	
	public function __toString()
	{
		return $this->fullName();
	}
	
}
