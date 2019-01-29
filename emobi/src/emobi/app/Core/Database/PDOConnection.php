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
use PDO;

class PDOConnection extends AbstractConnection implements DatabaseConnectable
{
	public function getConnection ()
	{

			
		
		$connection = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->user, $this->password,
array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		if ($this->persistent)
			$connection->setAttribute(PDO::ATTR_PERSISTENT);
        return $connection;
			

	}
	
}