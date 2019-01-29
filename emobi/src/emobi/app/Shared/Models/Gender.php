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
use app\Shared\Exceptions\DomainDataException;

final class Gender 
{
    private $gender;
    
    private $validGenders = ['male', 'female', 'other'];
    
    public function __construct($gender)
    {
        $this->assertValidateGender($gender);
        $this->gender = $gender;
    }
    
    private function assertValidateGender($gender) 
    {
        if (!in_array($gender, $this->validGenders))
            throw new DomainDataException(
                'É preciso fornecer um gênero válido para o usuário.', 
                DomainDataException::INVALID_ARGUMENT
            );
    }
    
    public function get() : string
	{
		return $this->gender;
	}
	
	public function __toString()
	{
		return $this->gender;
	}
    
}
