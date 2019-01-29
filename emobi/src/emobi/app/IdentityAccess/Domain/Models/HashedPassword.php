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

use app\Shared\Exceptions\DomainLogicException;

final class HashedPassword
{
	private $hash;
	
	public function __construct($hash)
	{
		$this->assertFitsLength($hash);
		$this->hash = $hash;
	}
	
	private function assertFitsLength($hash)
	{
		$encoding = mb_detect_encoding($hash);
		$len = mb_strlen($hash, $encoding);
		if ($len !== 60)
			throw new DomainLogicException('Hash de senha inválido.', DomainDataException::LENGTH);
	}
	
	public function get() : string
	{
		return (string) $this->hash;
	}
	
	public function __toString() : string
	{
		return (string) $this->hash;
	}
	
}
