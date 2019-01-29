<?php declare(strict_types=1);
namespace app\Shared\Adapters;
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
use Symfony\Component\HttpFoundation\Session\Session;

class SessionAdapter 
{
	private $session;
	
	public function __construct()
	{
		$this->session = new Session();
	}
	
	public function start() 
	{
		$this->session->start();
	}
	
	public function getId() : string 
	{
		return $this->session->getId();
	}
	
	public function setId(string $id)
	{
		return $this->session->setId($id);
	}
	
	public function getName() 
	{
		return $this->session->getName();
	}
	
	public function setName(string $name)
	{
		$this->session->setName($name);
	}
	
	public function isStarted() : bool 
	{
		return $this->session->isStarted();
	}
	
	public function set(string $key, $value)
	{
		return $this->session->set($key, $value);
	}
	
	public function get(string $key, $defaultValue = null)
	{
		return $this->session->get($key, $defaultValue);
	}
	
	public function has(string $key) : bool 
	{
		return $this->session->has($key);
	}
	
	public function all() : array 
	{
		return $this->session->all();
	}
	
	public function isEmpty() : bool 
	{
		return $this->session->isEmpty();
	}
	
	public function remove(string $key)
	{
		return $this->session->remove($key);
	}
	
	public function replace(array $attributes)
	{
		return $this->session->replace($attributes);
	}
	
	public function invalidate(int $lifetime = null)
	{
		return $this->session->invalidate($lifetime);
	}
	
	public function clear()
	{
		return $this->session->clear();
	}
}
