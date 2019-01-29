<?php declare(strict_types=1);
namespace app\Shared\Exceptions;
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
use app\Shared\Services\LogService;
use app\Shared\Contracts\CustomizableException;

class StorageException extends \Exception implements CustomizableException
{
	const DEFAULT_CODE = 0;
	const UNDEFINED_CODE = 1;
	const DUPLICATE_ITEM = 800;
	const ITEM_NOT_FOUND = 801;
    const LOGIC_ERROR = 802; 
    
	// Redefine the exception so message isn't optional
    public function __construct($message = null, int $code = self::DEFAULT_CODE, \Exception $previous = null) 
	{   
        parent::__construct($message, $code, $previous);
    }
	
	public function saveLog(string $level = 'ALERT', string $channel = 'STORAGE') 
	{
		LogService::record($this->getMoreInfo(), $level, $channel);
	}
	
	public function getMoreInfo() : string 
	{
		$format = " [Mensagem]: %s \n [Linha]: %s \n [Código]: %d";
		return sprintf($format, $this->getMessage(), $this->getLine(), $this->getCode());
	}
	
	public function __toString () : string
	{
		return $this->message;
	}
	
}
