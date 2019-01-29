<?php declare(strict_types=1);
namespace app\IdentityAccess\Application\Services\Auth;
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
use app\Shared\Adapters\SessionAdapter;

class UserSessionService
{
	/**
	 * Instance of the app\Shared\Adapters\SessionAdapter;
	 */
	protected $session;
	
	/**
	 * @param $Session - Instance of the Symfony Session
	 * @param $expireOn (int) - Session duration in minutes
	 */
	public function __construct(SessionAdapter $Session)
	{
		$this->session = $Session;
	}
	
	/**
	 * Authenticate user
	 * @return void
	 */
	public function init(
        string $UserId, 
        string $username, 
        string $userEmail, 
        string $userRole, 
        int $expireIn = 0,
        array $domains = [],
        string $ip,
        string $browser
    ){
        
        $this->session->set('auth_user_id', $UserId);
        $this->session->set('auth_username', $username);
        $this->session->set('auth_email', $userEmail);
        $this->session->set('auth_role', $userRole);
        $this->session->set('auth_domains', $domains);
        $this->session->set('auth_ip', $ip);
        $this->session->set('auth_browser', $browser);
       
		$Date = new DateTime();
        $now = $Date->format('Y-m-d H:i:s');
        
        $this->session->set('auth_started', $now);
        $this->session->set('auth_started_timestamp', $Date->getTimestamp());
		
		if ($expireIn > 0) {
            $Date->modify('+'.$expireIn.' minutes');
            $this->session->set('auth_expiration', $Date->getTimestamp());   
		} else {
            $this->session->set('auth_expiration', false);
        }
        
        $this->session->set('user_authenticated', 'Y');

	}
    
    public function setProfileInfo(string $firstName, string $lastName, string $picture) 
    {
        $userInfo = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'picture' => $picture
        ];
        $this->session->set('auth_user_info', $userInfo);
    }
    
    /**
	 * Check if the seesion has expired
	 *
	 * @return void
	 * @throw Exceptions\SessionExpired
	 */
	private function checkExpiration() : void 
	{
		if (!$this->session->has('auth_expiration')) {
			$this->finalize();
			throw new Exceptions\AuthException('Sessão inválida.');
		}
		
		$expiration = $this->session->get('auth_expiration', false);
		// Check Expiration
		if (!$expiration) {
			$now = new DateTime('now');
            $now = $now->getTimestamp();
			$expire = new DateTime();
            $expire->setTimestamp($expiration);
			
			if ($now > $expire)
				throw new Exceptions\SessionExpired();
		}
		
	}
	
	/**
	 * Check if the current domain is allowed in the session
	 *
	 * @return void
	 * @throw Exceptions\AuthException
	 */
	public function checkDomain(?string $domain = null) : void 
	{
		$allowedDomains = $this->session->get('auth_domains');
		// Check domains is valid
        if (null === $domain) {
            $domain = $_SERVER['HTTP_HOST'];
        }
		
		if (!in_array($domain, $allowedDomains))
			throw new Exceptions\AuthException(
				'As suas credenciais não permitem acesso autenticado a este domínio.'
			);
	}
    
    /** 
	 * Returns remaining session time
	 *
	 * @return mixed - (string) remaining time and (null) if expiration has not been defined
	 */
	public function getRemainingSessionTime() : ?DateInterval
	{
        $expiration = $this->session->get('auth_expiration', false);
		if (!$expiration)
			return null;
		
		$Now = new DateTime('now');
		$Expire = new DateTime();
        $Expire->setTimestamp($expiration);
		return $Now->diff($Expire);
	}
    
    /** 
	 * Increase session lifetime
	 *
     * @param int - time in minutes
	 * @return mixed - (string) remaining time and (null) if expiration has not been defined
	 */
    public function increaseExpirationTime(int $time) : bool
	{
		$expiration = $this->session->get('auth_expiration', false);
		if (!$expiration)
			return false;
		$expire = new DateTime();
        $expire->setTimestamp($expiration);
		$expire->modify('+'.$time.' minutes');
		$newExpireTime = $expire->getTimestamp();
		$this->session->set('auth_expiration', $newExpireTime);
        return true;
	}
    
    /**
	 * Check if for changes in IP address
	 *
	 * @return void
	 * @throw Exceptions\AuthException
	 */
	public function checkIp(?string $ip = null) 
	{	
		if (null === $ip) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
		if ($ip != $this->session->get('auth_ip', null)) {
			$this->finalize();
			throw new Exceptions\AuthException("Notamos uma mudança em sua rede. Para sua proteção está sessão foi encerrada.");
		}
			
	}
    
    /**
	 * Check if the user role is allowed
	 *
	 * @return void
	 * @throw Exceptions\UnauthorizedUser
	 */
	public function checkRole(array $allowedRoles = []) : void 
	{
		
		if (!in_array($this->getRole(), $allowedRoles))
			throw new Exceptions\UnauthorizedArea('Suas credenciais não permitem acesso a esta área do site.');
	}

	/**
	 * Verifies that the user is authenticated 
	 */
	public function hasUserAuthenticated() : bool 
	{
		return ($this->session->get('user_authenticated', null) === 'Y') ? true : false;
	}
    
    public function getUsername() : ?string 
    {
        return $this->session->get('auth_username', null);
    }
    
    public function getUserId() : ?string 
    {
        return $this->session->get('auth_user_id', null);
    }
    
    public function getRole() : ?string 
    {
        return $this->session->get('auth_role', null);
    }
    
    public function getEmail() : ?string 
    {
        return $this->session->get('auth_email', null);
    }
    
    public function getFirstName() : ?string 
    {
        return $this->session->get('auth_first_name', null)['first_name'];
    }
    
    public function getLastName() : ?string 
    {
        return $this->session->get('auth_last_name', null)['last_name'];
    }
    
    public function getPicture() : ?string 
    {
        return $this->session->get('auth_user_info', null)['picture'];
    }
    
    public function isSuperadmin () : bool 
	{
		return ($this->session->get('auth_role', null) === 'superadmin') ? true : false;
	}
	
	public function isSupport () : bool 
	{
		return ($this->session->get('auth_role', null) === 'support') ? true : false;
	}  
	
	public function isAdmin () : bool 
	{
		return ($this->session->get('auth_role', null) === 'admin') ? true : false;
	}
    
    public function isAssistant () : bool 
	{
		return ($this->session->get('auth_role', null) === 'assistant') ? true : false;
	}
    
    public function isDemo () : bool 
	{
		return ($this->session->get('auth_role', null) === 'demo') ? true : false;
	}
    
    public function clearLastSession() 
    {
        $this->session->clear();
    }
    
    public function finalize()
    {
       $this->session->clear(); 
    }

}