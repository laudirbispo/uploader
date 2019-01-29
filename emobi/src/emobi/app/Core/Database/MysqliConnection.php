<?php declare(strict_types=1);
namespace app\Core\Database;

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


class MysqliConnection extends AbstractConnection implements DatabaseConnectable
{
	
	public $error;		

    
	// Obtenha conexão com mysqli
	public function getConnection() 
    {	
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		
		if ($this->persistent)
			$host = "p:".$this->host;
		else
			$host = $this->host;
		
		$conn = new \mysqli($host, $this->user, $this->password, $this->database);
		mysqli_set_charset($conn, $this->charset);
		
		if (mysqli_connect_errno()) 
		{
			$this->error = ("Falha de conexão: " . mysqli_connect_error() . ": Error número[".mysqli_connect_errno()."]");
			return false;
		}
		
		return $conn;
	}
	
}
