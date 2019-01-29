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
use libs\laudirbispo\CQRSES\Validation\AssertionValidationTrait;
use app\Shared\Exceptions\DomainDataException;

final class About   
{
    use AssertionValidationTrait;
    
    const MAX_LENGTH = 1024;
    
    private $about;
    
    public function __construct($about) 
    {
        try {
            
            $this->assertThatTheArgumentLengthIsLongerThan(
                $about, 
                self::MAX_LENGTH,
                sprintf("Sua Biografia não pode conter mais de %d caracteres.", self::MAX_LENGTH)
            );
            $this->about = $about;
            
        } catch(\InvalidArgumentException $e) {
            throw new DomainDataException($e->getMessage(), DomainDataException::INVALID_ARGUMENT);
        }
        
    }
    
    public function get() : string
	{
		return (string) $this->about;
	}
	
	public function __toString ()
	{
		return $this->about;
	}
    
        
}