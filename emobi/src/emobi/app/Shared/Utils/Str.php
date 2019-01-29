<?php declare(strict_types=1);
namespace app\Shared\Utils;
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
class Str
{
	public static function len($str, $encoding = null)
    {
		$encoding = (null === $encoding) ? self::detectEncoding($str) : $encoding;
        $str = html_entity_decode($str, ENT_COMPAT, $encoding);
        if (function_exists('mb_strlen')) 
            return mb_strlen($str, $encoding);

        return strlen($str);
    }
	
	public static function toUpper($str) : string
	{	
		if (function_exists('mb_strtoupper')) 
			return mb_strtoupper($str, 'utf-8');
		
		return strtoupper($str);
	}
	
	public static function toLower ($str) : string 
    {      
        if (function_exists('mb_strtolower')) 
            return mb_strtolower($str, 'utf-8');
        
        return strtolower($str);
    }
	
	public static function sub($str, $start, $length = false, $encoding = null)
    {
        if (!is_string($str)) 
            return false;
        $encoding = (null === $encoding) ? self::detectEncoding($str) : $encoding;
        if (function_exists('mb_substr')) 
            return mb_substr($str, (int)$start, ($length === false ? self::strlen($str) : (int)$length), $encoding);
        
        return substr($str, $start, ($length === false ? self::len($str) : (int)$length));
    }
	
	public static function ucfirst($str)
    {
        return self::strtoupper(self::substr($str, 0, 1)).self::substr($str, 1);
    }

    public static function ucwords($str)
    {
        if (function_exists('mb_convert_case')) {
            return mb_convert_case($str, MB_CASE_TITLE);
        }
        return ucwords(self::toLower($str));
    }
	
	public static function truncate($str, int $max_length, string $suffix = '...')
    {
        if (self::len($str) <= $max_length) 
            return $str;  
        $str = utf8_decode($str);
        return (utf8_encode(substr($str, 0, $max_length - self::len($suffix)).$suffix));
    }
	
	public static function detectEncoding($str)
	{
		if (function_exists('mb_detect_encoding')) 
            return mb_detect_encoding($str);
		
		return 'UTF-8';
	}
	
}
