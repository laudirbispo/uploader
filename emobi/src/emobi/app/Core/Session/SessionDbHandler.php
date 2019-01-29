<?php declare(strict_types=1);
namespace app\Core\Session;

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
 * NOTA - Desabilitar "session_write_close()" na função Session::set() em caso de problemas ou caso não esteje usando
 * um manipulador customisado de sessão.
 *
 */

class SessionDbHandler 
{
	private $table_name = 'session_handler';
	
	private $con;
	
	private $expire = 3600;
	
	public function __construct () 
	{
		$this->con = \app\Core\Database\DatabaseFactory::create('mysqli');
		$this->createTable();
        ini_set('session.save_handler', 'user');
		$this->start();
 
        register_shutdown_function(array($this, 'sessionClose'));
    }
	
	/**
     * Actually start the session
     */
    private function start () 
	{
        @session_set_save_handler(
            array($this, 'sessionOpen'),
            array($this, 'sessionClose'),
            array($this, 'sessionRead'),
            array($this, 'sessionWrite'),
            array($this, 'sessionDestroy'),
            array($this, 'sessionGC')
        );
        @session_start();
		try {
			if(session_id() == '') 
            throw new \Exceptions('Falha ao iniciar a sessão.');
		}catch(\Exceptions $e){
			$e->getMessage();
		}
        
    }
	
	/**
     * Session open handler
     * Do not call this method directly.
     * @param string @savePath session save path
     * @param @sessionName session name
     * @return boolean Whether session is opened successfully
     */
    public function sessionOpen ($savePath, $sessionName) 
	{
        return true;
    }
	
	/**
     * Session read handler
     * Do not call this method directly.
     * @param string $id session ID
     * @return string the session data
     */
    public function sessionRead ($id) 
	{
        $expire = time();
        $sql = $this->con->prepare("SELECT `data` FROM `{$this->table_name}` WHERE (`id`='{$id}' AND `expire`>='{$expire}')");
		$sql->execute();
		$rows = $sql->get_result();
		$sql->free_result();
		$sql->close();
		
		$data = array();
		while ($reg = $rows->fetch_assoc()) { $data[] = $reg; }
		//return $data;
        return (count($data) > 0 ? base64_decode($data[0]['data']) : null);
    }
	
	 /**
     * Session write hanlder
     * Do not call this method directly.
     * @param string $id Session ID
     * @param string $data session data
     * @return boolean Whether session write is successful
     */
    public function sessionWrite ($id, $data) 
	{
        $data = base64_encode($data);
        $expire = (int) time() + (int) $this->expire;
        $this->con->query("INSERT INTO `{$this->table_name}` (`id`, `data`, `expire`) VALUES ('{$id}','{$data}',{$expire}) ON DUPLICATE KEY UPDATE `data`='{$data}',`expire`={$expire} ");
    }
	
	/**
     * Ends the current session and store session data
     * Do not call this method directly.
     */
    public function sessionClose ()
	{
        $this->deleteExpired();
        if(session_id() !== '') {
            @session_write_close();
        }
    }
	
	/**
     * Session GC (garbage collector) handler
     * Do not call this method directly.
     * @param integer $maxLifetime The number of seconds after which data will be seen as 'garbage' and cleaned up.
     * @return boolean whether session is GCed successfully.
     */
    public function sessionGC ($id) 
	{
        $this->deleteExpired();
        return true;
    }
    
	
	/**
     * Deletes expired session
     */
    private function deleteExpired () 
	{
        $expire = (int) time();
        $this->con->query("DELETE FROM `{$this->table_name}` WHERE (`expire` < {$expire}) ");
    }	
	
	/**
     * Session destroy handler
     * Do not call this method directly.
     * param string $id session ID
     * @return boolean whether session is destroyed successfully
     */
    public function sessionDestroy ($id) 
	{
        $this->con->query("DELETE FROM `{$this->table_name}` WHERE (`id`='{$id}')");
        return ($this->con->affected_rows > 0) ? true : false;
    }
	 /**
     * Create the session table
     */
    private function createTable () 
	{
        $this->con->query("
            CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
                `id` char(32) COLLATE utf8_bin NOT NULL,
                `expire` int(11) DEFAULT NULL,
                `data` longblob,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
        ");
    }
	
}