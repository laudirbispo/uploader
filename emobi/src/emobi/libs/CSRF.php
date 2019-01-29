<?php declare(strict_types=1);
namespace libs;
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

use DateTime;
use app\Shared\Adapters\SessionAdapter;

class CSRF 
{
	/**
	 * app\Shared\Adapters\SessionAdapter;
	 */
	private $session;
	
	/**
	 * Token validity period
	 *
	 * @cosnt (int)
	 */
	const EXPIRE = 30;
	
	private $error = null;
	
	
	public function __construct (SessionAdapter $session)
	{
		$this->session = $session;
	}
	
    /**
     * Make a Token
     *
     * @return string
     */
    public function makeToken () : string
    {
		$token = md5(uniqid() . microtime() . rand());
		$date = new DateTime();
		$date->modify('+'.self::EXPIRE.' minutes');
		$expire = $date->getTimestamp();
		
		$csrf = array(
			'token' => $token,
			'IP' => $_SERVER['REMOTE_ADDR'],
			'expire' => $expire
		);
		
		$this->session->set('csrf', $csrf);
		return $token;
    }

    /**
     * Check if the session token is equals the token of the request 
     *
     * @return bool
     */
    public  function checkToken ()
    {
		$csrf = $this->session->get('csrf', false);
		if (!$csrf)
		{
			$this->error = "Token ausente para o pedido atual.";
			return false;
		}
			
		$date = new DateTime();
		$now = $date->getTimestamp();
		
		if ($csrf['IP'] !=  $_SERVER['REMOTE_ADDR'])
		{
			$this->error = "Token inválido! O endereço de origem não é o mesmo que o atual.";
			return false;
		}
		else if ($csrf['expire'] <= $now)
		{
			$this->error = "Token expirado";
			return false;
		}
		else if ($csrf['token'] !== $csrf['token'])
		{
			$this->error = "Requisição inválida!";
			return false;
		}
		else
		{
			return true;
		}
		
    }
	
	public function getError ()
	{
		return $this->error;
	}
	
	/**
	 * Get current token
	 *
	 * @return (mixed) - string token or bool false
	 */
	public function getCurrentToken ()
	{
		return (isset($this->session->get('csrf')['token'])) ? $this->session->get('csrf')['token'] : null;
	}
	
}
