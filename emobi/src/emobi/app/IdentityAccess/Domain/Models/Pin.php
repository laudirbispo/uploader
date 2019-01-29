<?php declare(strict_types=1);
namespace  app\IdentityAccess\Domain\Models;

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

final class Pin 
{
    const SIZE = 6;
    
    private $pin;
    
    public function __construct($pin)
    {
        $this->assertNotEmpty($pin);
        $this->assertFitsLength($pin);
        $this->assertThatIsNumerical($pin);
        $this->pin = $pin;
    }
    
    private function assertNotEmpty($pin)
    {
        if (empty($pin))
			throw new DomainDataException('É preciso fornecer um PIN.', DomainDataException::EMPTY_ARGUMENT);
    }
    
    private function assertFitsLength($pin)
    {
        $encoding = mb_detect_encoding($pin);
		$len = mb_strlen($pin, $encoding);
        if ($len !== self::SIZE)
            throw new DomainDataException(
				sprintf("O PIN deve possuir %s caracteres.", self::SIZE),
				DomainDataException::LENGTH
			);
    }
    
    private function assertThatIsNumerical($pin)
    {
        if (!is_numeric($pin))
            throw new DomainDataException(
				"O PIN deve conter somente números.",
				DomainDataException::INVALID_ARGUMENT
			);
    }
    
}