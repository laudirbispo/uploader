<?php declare(strict_types=1);
namespace app\Core\Encryption;
/**
 * BlowfishCrypt Class
 * 
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

class Crypt
{
	const SHA512 = 'sha512';
	const SHA256 = 'sha256';
	const MD5    = 'md5';
	const WHIRLPOOL = 'whirlpool';
	
	public static function generateHash (string $algo, string $data, bool $raw)
	{
		return hash($algo, $data, $raw);
	}
	
	public static function compareHash (string $expected, string $string)
	{
		return hash_equals($expected, $string);
	}
}