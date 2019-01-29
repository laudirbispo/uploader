<?php declare(strict_types=1);
namespace app\Shared\Services;

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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Monolog\{Logger, Formatter, Formatter\HtmlFormatter, Handler\RotatingFileHandler};

class LogService
{
	/** 
	 * Log Levels
	 */
	const DEBUG = 'DEBUG';
	const INFO = 'INFO';
	const NOTICE = 'NOTICE';
	const WARNING = 'WARNING';
	const ERROR = 'ERROR';
	const CRITICAL = 'CRITICAL';
	const ALERT = 'ALERT';
	const EMERGENCY = 'EMERGENCY';
    
    private $configs = [
        'host' => 'email-ssl.com.br',
        'user' => 'noreply@criativanews.com.br',
        'pass' => 'programador.php',
        'smtp' => true,
        'port' => 587,
        'save_dir' => EM_DIR_SAVE_LOGS
    ];
    
    private $sendAddress = ['logs@easymobi.com.br'];
	
	public static function record (string $message, string $level = self::INFO, string $channel = 'Log')
	{
        $log = new self();
        
		switch ($level) :
			case 'DEBUG' :
			case 'INFO' :
			case 'NOTICE' :
			case 'WARNING' :
			case 'ERROR' :
				return $log->htmlLog($message, $level, $channel);
				break;
			case 'CRITICAL' :
			case 'ALERT' :
			case 'EMERGENCY' :
				 $log->mailLog($message, $level, $channel);
				break;
		endswitch;
	}
    
    private function htmlLog(string $message = 'Unidentified error', string $level = 'DEBUG', string $channel = 'Debug')
	{	
        try {
            
            $level = strtoupper($level);
            date_default_timezone_set('America/Sao_Paulo');
            $formatter = new HtmlFormatter($dateFormat = "d/m/Y, H:m:s");
            $save_log = $this->configs['save_dir'] . '/' . $channel . '.html'; 
            $html_log = new RotatingFileHandler($save_log, $level);
            $html_log->setFormatter($formatter);
            // create a log channel
            $logger = new Logger($channel);
            $logger->pushHandler($html_log);
            $logger->$level($message);	
            
        } catch (\Exception $e) {
			return;
		}  
		
	}
    
    private function mailLog(string $message = 'Unidentified error', string $level = 'ALERT', string $channel = 'Debug')
	{
        
		$mail = new PHPMailer(true);                              
		try {
			//Server settings
			//$mail->SMTPDebug = 2;  
			$mail->CharSet = 'utf-8';
			$mail->isSMTP();                                      
			$mail->Host = $this->configs['host'];  
			$mail->SMTPAuth = true;                               
			$mail->Username = $this->configs['user'];                 
			$mail->Password = $this->configs['pass'];                       
			$mail->SMTPSecure = 'tls';                            
			$mail->Port = $this->configs['port'];                                    

			//Recipients
			$mail->setFrom($this->configs['user'], $_SERVER['HTTP_HOST']);
			foreach ($this->sendAddress as $mailTo)
			{
				$mail->addAddress($mailTo); 
			}        
			//Content
			$mail->isHTML(true);                           
			$mail->Subject = strtoupper($channel).' '.$level;
			$mail->Body = $message;

			$mail->send();
			return true;
			
		} catch (\Exception $e) {
			$this->htmlLog('[mail-failed]- '. $message, 'NOTICE', $channel);
		}
        return;
	}
	
}
