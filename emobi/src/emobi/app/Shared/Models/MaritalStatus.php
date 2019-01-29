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
use libs\laudirbispo\CQRSES\Validation\AssertionValidationTrait;
use app\Shared\Exceptions\DomainDataException; 

final class MaritalStatus 
{
    use AssertionValidationTrait;
    
    private $status;
    
    private $validStatus = [
        'não declarado', 
        'solteiro', 
        'casado', 
        'separado judicialmente', 
        'viúvo', 
        'divorciado'
    ];

    public function __construct($status)
    {
        $this->customValidation($status);
        $this->status = $status;
        //Continuar
    }

    private function customValidation($status) 
    {
        if (!in_array($status, $this->validStatus)) 
            throw new DomainDataException('Estado civil inválido', DomainDataException::INVALID_ARGUMENT);
    }
	
	public function get() : string
	{
		return (string) $this->status;
	}
	
	public function __toString()
    {
        return (string) $this->status;
    }
	
}
