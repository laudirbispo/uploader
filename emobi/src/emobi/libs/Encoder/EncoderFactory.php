<?php declare(strict_types=1);
namespace app\Core\Encoder;

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

class EncoderFactory 
{
	public static function create (string $data_type)
	{
		switch ($data_type) :
		case 'base64' :
			return new Base64Encoder();
			break;
		case 'serializer' :
			return new SerializerEncoder();
			break;
		case 'json' :
			return new JsonEncoder();
			break;
		default :
			return new Base64Encoder();
			break; 
		endswitch;
	}
}
