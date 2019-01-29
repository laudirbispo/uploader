<?php declare(strict_types=1);
namespace app\Shared\Helpers;
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
use DateTime, DateInterval;

class DateTimeHelper
{
	 /**
     * Reverses the date format, dd/mm/yyyy -> yyyy-mm-dd and vice versa
     * 
     * @param $stamp string - 
	 * @param $time bool -
     * @return string
     */
	public static function reverse(string $stamp, bool $time = false) : string
    {
        $date = substr($stamp, 0, 10);
		$time =  ($time) ? substr($stamp, -8, 8) : '';
        if (count(explode("/",$date)) > 1)
            return implode("-", array_reverse(explode("/", $date))) . ' às ' . $time;
        else if (count(explode("-", $date)) > 1)
            return implode("/", array_reverse(explode("-", $date))). ' às ' . $time;
        else
            throw new \InvalidArgumentException('Formato de data inválido.');
    }
	 
	public static function elapsedTime(string $time) : string
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
			return "agora há pouco";
		else if ($minutes <= 59) 
			return ($minutes == 1) ? '1 min atrás' : $minutes.' min atrás';
		else if ($hours <= 24) 
			return ($hours == 1) ? '1 horas atrás' : $hours.' horas atrás';
		else if ($days <= 7) 
			return ($days == 1) ? '1 dia atrás' : $days.' dias atrás';
		else if ($weeks <= 4) 
			return ($weeks == 1) ? '1 semana atrás' : $weeks.' semanas atrás';
		else if ($months <= 12) 
			return ($months == 1) ? '1 mês atrás' : $months.' meses atrás';
		else 
			return ($years == 1) ? 'um ano atrás' : $years.' anos atrás';
	}
	 
	public static function timeLeft(string $future, string $initial = 'now', string $returnType = 'datetime') : string 
	{
		$initial = new DateTime($initial);
		$future = new DateTime($future);
		$diff = $initial->diff($future);
		$years = $diff->format('%y%');
		$months = $diff->format('%m%');
		$days =  $diff->format('%d%');
		$weeks = floor($days / 7);
		$hours = $diff->format('%h%');
		$minutes = $diff->format('%i%');
		$seconds = $diff->format('%s%');

		if ($initial > $future)
			return 'A data já foi alcançada.';
		/*
		$timeLeft = '';
		if ($years >= 1)
			$years = ($years == 1) ? $years . ' ano' :  $years . ' anos'; 			
		else if ($months >= 1)
			$months = ($months == 1) ? $months . ' mês' :  $months . ' meses';
		else if ($weeks >= 1)
			$weeks = ($weeks == 1) ? $weeks . ' semana' :  $weeks . ' semanas'; 
		else if ($days >= 1)
			$days = ($days == 1) ? $days . ' dia' :  $days . ' dias'; 
		else if ($hours >= 1)
			$hours = ($hours == 1) ? $hours . ' hora' :  $hours . ' horas'; 
		else if ($minutes >= 1)
			$minutes = ($minutes == 1) ? $minutes . ' minuto' :  $minutes . ' minutos'; 
		else
			$seconds =  $seconds . ' segundos'; 
		*/
		if ($returnType === 'date')
			return $diff->format('%Y anos %m meses e %d dias ');
		else if ($returnType === 'time')
			return $diff->format('%H horas %i minutos e %s segundos');
		else
			return $diff->format('%Y anos %m meses %d dias %H horas %i minutos e %s segundos');
	}
	 
	public static function writeTheDateInFull(string $date = null) : string
	{
		$date = ($date === null) ? date('Y-m-d') : $date ;
		$day_week = ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sabado'];
		$day_week_number = date('w', strtotime($date));

		$month = [
            'Jan' => 'Janeiro', 
            'Feb' => 'Fevereiro', 
            'Mar' => 'Marco', 
            'Apr' => 'Abril', 
            'May' => 'Maio', 
            'Jun' => 'Junho', 
            'Jul' => 'Julho', 
            'Aug' => 'Agosto', 
            'Sep' => 'Setembro', 
            'Oct' => 'Outubro',
            'Nov' => 'Novembro',
            'Dec' => 'Dezembro'
         ];
		$month_number = date('M', strtotime($date));
		$day_month = date('d', strtotime($date));

		$year = date('Y', strtotime($date));

		return $day_week[$day_week_number].', '.$day_month.' de '.$month[$month_number].' de '. $year;    
	}
	 
	 /** 
	 * Generate the copyright
	 * 
	 * @param $startYear = inicio da contagem, se omitido o retorno será somente o ano atual
	 * @return String = 'Ano inicial - ano atual
	 */
	public static function generateCopyright(int $startYear = null) : string
	{
		$thisYear = date('Y');  
		$year = (!is_numeric($startYear)) ? $thisYear : intval($startYear);
		return ($year == $thisYear || $year > $thisYear) ? "&copy; $thisYear" : "&copy; $year&ndash;$thisYear";  
    }
    
    public static function formatDateDiff(DateInterval $Interval) : ?string
    {
        $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;};  
        $format = array();
        if ($Interval->y !== 0) {
            $format[] = "%y ".$doPlural($Interval->y, "ano");
        }
        if ($Interval->m !== 0) {
            $format[] = "%m ".$doPlural($Interval->m, "mese");
        }
        if ($Interval->d !== 0) {
            $format[] = "%d ".$doPlural($Interval->d, "dia");
        }
        if ($Interval->h !== 0) {
            $format[] = "%h ".$doPlural($Interval->h, "hora");
        }
        if ($Interval->i !== 0) {
            $format[] = "%i ".$doPlural($Interval->i, "minuto");
        }
        if ($Interval->s !== 0) {
            if(!count($format)) {
                return "a menos de um minuto atras";
            } else {
                $format[] = "%s ".$doPlural($Interval->s, "segundo");
            }
        }

        // We use the two biggest parts
        if(count($format) > 1) {
            $format = array_shift($format)." e ".array_shift($format);
        } else {
            $format = array_pop($format);
        }

        // Prepend 'since ' or whatever you like
        return $Interval->format($format);
    }
	 
 }
