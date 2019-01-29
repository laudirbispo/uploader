<?php declare(strict_types=1);
namespace app\Utils;

/**
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

class Tools
{
	
	
	/**
     * @return random string encrypted with md5
     */
    public static function generateRandomMd5 () : string
    {
        return md5( uniqid(microtime(), true ) );
    }
    
    /**
     * @return random string encrypted with sha256
     */
    public static function generateRandomSha256 () : string
    {
        return hash('sha256',( uniqid(microtime(), true )));
    }
    
    /**
     * @return random string encrypted with sha512
     */
    public static function generateRandomSha512 () : string
    {
        return hash('sha512', ( uniqid(microtime(), true )));
    }
    
    /**
     * Function inverteData
     * Inverte o formato da data, dd/mm/yyyy -> yyyy-mm-dd e vice-versa
     * 
     * @param $data string
	 * @param $time bool - manter o time caso a data seja timestamp
     * @return mixed
     */
    public static function inverteData (string $stamp, bool $time = false) : string
    {
        $data = substr($stamp, 0, 10);
		$time =  ($time) ? substr($stamp, -8, 8) : '';
        if(count(explode("/",$data)) > 1)
            return implode("-",array_reverse(explode("/",$data))) . ' às ' . $time;
        else if(count(explode("-",$data)) > 1)
            return implode("/",array_reverse(explode("-",$data))). ' às ' . $time;
        else
            throw new \Exceptions\InvalidArgumentException('Formato de data inválido.');
    }
    
    /**
     * Retorna somente o time de uma string date_time;
     * @param string
     * @return string 
     */
    public static function returnTime (string $date_time) : string
    {
        return substr($date_time, -8);
    }
    
    /**
     * Converte valor monetário em decimal
     *
     * @param string $value - 
     */
    public static function moedaDecimal (string $value) : float
    {
        $source = array('.', ',');
        $replace = array('', '.');
        return str_replace($source, $replace, $value); 
    }
	
	/**
     * Converte valor decimal em monetário 
     *
     * @param string $value - 
     */
    public function decimalMoeda (float $valor) : string
    { 
        return number_format($valor, 2, ',', '.');
    }
	
	/**
	 * Gera ID único sem criptografia
	 * @example -> generateUniqueIdAlpha(8) - gera uma string com 8 caracteres;
	 *
	 * @param $lenght - tamanho do ID gerado
	 * @return string
	 */
	public static function generateUniqueIdAlpha (int $length = 16) : string
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
		$len = strlen($chars);
		$string = '';
		for ($i = 0; $i < $length; $i++)
		{
		   $string .= $chars[mt_rand(0,$len - 1)];
		}
		return $string;
	}
    
	/**
     * Retorna um texto descritiDto com o tempo decorrido a partir de uma date_time 
     *
     * @param string $time -
	 * @return string
     */
	public function elapsedTime (string $time) : string
	{
		$now = strtotime(date('m/d/Y H:i:s'));
		$time = strtotime($time);
		$diff = $now - $time;

		$seconds = $diff;
		$minutes = round($diff / 60);
		$hours = round($diff / 3600);
		$days = round($diff / 86400);
		$weeks = round($diff / 604800);
		$months = round($diff / 2419200);
		$years = round($diff / 29030400);

		if ($seconds <= 60) 
			return"agora há pouco";
		else if ($minutes <= 59) 
			return ($minutes == 1) ? '1 min atrás' : $minutes.' min atrás';
		else if ($hours <= 24) 
			return ($hours == 1) ? '1 horas atrás' : $hours.' horas atrás';
		else if ($days <= 7) 
			return ($days == 1) ? '1 dia atras' : $days.' dias atrás';
		else if ($weeks <= 4) 
			return ($weeks == 1) ? '1 semana atrás' : $weeks.' semanas atrás';
		else if ($months <= 12) 
			return ($months == 1) ? '1 mês atrás' : $months.' meses atrás';
		else 
			return ($years == 1) ? 'um ano atrás' : $years.' anos atrás';
	}
	
	/**
	 * Verifica se a data é válida
	 * @param $date = a data a ser verificada
	 * @param $format = formato da data a ser analizada
	 * @return true se a data dor valida e false se a data não exisitir
	 * 
	 * @examples:
	 *
	 * var_dump(isValidDate('2012-02-28 12:12:12')); # true
	 * var_dump(isValidDate('2012-02-28 12:12:12')); # true
	 * var_dump(isValidDate('2012-02-30 12:12:12')); # false
	 * var_dump(isValidDate('2012-02-28', 'Y-m-d')); # true
	 * var_dump(isValidDate('28/02/2012', 'd/m/Y')); # true
	 * var_dump(isValidDate('30/02/2012', 'd/m/Y')); # false
	 * var_dump(isValidDate('14:50', 'H:i')); # true
	 * var_dump(isValidDate('14:77', 'H:i')); # false
	 * var_dump(isValidDate(14, 'H')); # true
	 * var_dump(isValidDate('14', 'H')); # true
	 *
	 * @return bool
	 */
	public static function isValidDate (string $date, string $format = 'Y-m-d H:i:s') : string
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	/** 
	 * Remove pontos, vírgula e outros caracteres 
	 * 
	 * @param $string - string
	 * @return int
	 */
	public function cleanCpfCnpj(string $string) : int
	{
		$string = trim($string);
		$string = str_replace(".", "", $string);
		$string = str_replace(",", "", $string);
		$string = str_replace("-", "", $string);
		$string = str_replace("/", "", $string);
		return $string;
	}
	
	
	
	
	public static function hiddenString($str, $start = 1, $end = 1)
	{
		$len = strlen($str);
		return substr($str, 0, $start) . str_repeat('*', $len - ($start + $end)) . substr($str, $len - $end, $end);
	}
	
}
