<?php declare(strict_types=1);
namespace app\Web\Controllers;
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
use libs\AgentDetect;
use app\Shared\Adapters\SessionAdapter;
use app\Shared\Helpers\{HtmlHelper, DateTimeHelper};
use app\Core\Database\DatabaseFactory;
use app\Shared\Exceptions\DomainDataException;
use app\IdentityAccess\Infrastructure\Repository\Query\PDO\PDOUserQuery;
use app\Shared\Services\LogService;
use app\IdentityAccess\Application\Services\Auth\UserSessionService;
use app\IdentityAccess\Infrastructure\Exceptions\UserNotFound;
use app\IdentityAccess\Application\Services\Auth\{
    AuthenticationService, 
    Exceptions\AuthException,
    Exceptions\AccountIsNotActive, 
    Exceptions\IncorrectPassword,
    Exceptions\UserDoesNotExist
};
use app\IdentityAccess\Application\Commands\{
    AuthenticateUserCommand, 
    AuthenticateUserHandler,
    RegisterNewUserSessionCommand,
    RegisterNewUserSessionHandler
};
use app\IdentityAccess\Application\Queries\{
    ProfileQueryById,
    ProfileQueryByIdHandler
};
    
class AccountController extends ApplicationController
{	
    /**
     * Default session lifetime in minutes.
     * 10080 minutes = 7 days
     */
    const SESSION_EXPIRE_IN = 10080;
    
    private $userSessionService;
	
	private $userRedirect = [
        'support' => '/admin/home', 
        'superadmin' => '/admin/home', 
        'admin' => '/admin/home', 
        'assistant' => '/admin/home', 
        'client' => '/home', 
        'guest' => '/home'
    ];
	
	private $roleExpiration = [
        'suporte' => 0, 
        'superadmin' => 0, 
        'admin' => 0, 
        'assistant' => 0, 
        'client' => 0, 
        'guest' => 0
    ];
	
	private $allowedDomains = ['192.168.0.100', 'emobi.com'];
	
	public function __construct ()
	{
		// Prevent
		parent::__construct();
        $this->userSessionService = new UserSessionService($this->session);
  
		// Carrega o arquivo base do layout
		$this->template->layout(ROOT_DIR . '/themes/account/views/layout.tpl', true);
	}
	
	/**
	 * Tenta autenticar o usuário
	 *
	 * ===================== MELHORAR ESTA FUNCTION =====================
	 */
	public function authenticate() 
	{	
       
		try {
            
            $loginAttempts =  $this->session->get('login_attempt');
            $this->session->set('login_attempt', $loginAttempts+1);
			
			$username = $this->http->request->get('username', '');
			$password = $this->http->request->get('password', '');
			$redirectTo = $this->http->request->get('redirect', null);
			$remember = false;
			
			$PDO = DatabaseFactory::create('pdo');
			$PDO->setDatabase(EM_DB_HOST, EM_DB_USER, EM_DB_PASSWORD, EM_DB_DATABASE);
			$conn = $PDO->getConnection();

			$UserQueryRepository = new PDOUserQuery($conn);
            
            $LoginCommand = new AuthenticateUserCommand($username, $password);
            $AuthService = new AuthenticationService($UserQueryRepository);
            $LoginHandler = new AuthenticateUserHandler($AuthService);
            $AuthInfo = $LoginHandler->handler($LoginCommand);
            $AgentDetect = new AgentDetect();
            
            $RegisterNewUserSessionCommand = 
                new RegisterNewUserSessionCommand(
                    $AuthInfo['user_id'],
                    $AuthInfo['username'],
                    $AuthInfo['email'],
                    $AuthInfo['role'],
                    self::SESSION_EXPIRE_IN,
                    $this->allowedDomains,
                    $AgentDetect->getBrowser(),
                    $_SERVER['REMOTE_ADDR']
                );
            
            //$UserSessionService = new UserSessionService($this->session);
            $RegisterNewUserSessionHandler = new RegisterNewUserSessionHandler($this->userSessionService);
            $RegisterNewUserSessionHandler->execute($RegisterNewUserSessionCommand);
            
            // User Profile information
            $ProfileQueryById = new ProfileQueryById($AuthInfo['user_id']);
            $ProfileQueryByIdHandler = new ProfileQueryByIdHandler($UserQueryRepository);
            $Profile = $ProfileQueryByIdHandler->execute($ProfileQueryById);
            
            if (null != $Profile) {
                $this->userSessionService->setProfileInfo(
                    $Profile->getFirstName(),
                    $Profile->getLastName(),
                    $Profile->getPicture()
                );
            } else {
                $this->flashMessages->warning("Algumas informações sobre você não foram carregadas. O indicado é fazer o login novamente.");
            }
            
			if (null === $redirectTo || empty($redirectTo))
				$redirectTo = $this->userRedirect[$UserSessionService->getRole()];
			else
				$redirectTo = urldecode($redirectTo);
                
            $this->session->set('login_attempt', 0);
			
			$this->redirect($redirectTo);
			
			
		} catch (AccountIsNotActive $e) {
			
			$resendEmail = HtmlHelper::anchor('/accounts/active', [], 'envie');
			$error = "Esta conta não está ativada. Verifique sua caixa de entrada ou {$resendEmail} um novo e-mail de ativação!";	
			
		} catch (IncorrectPassword $e) { 
            
            $loginAttempts = $this->session->get('login_attempt');
            
            if ($loginAttempts >= 3) {
                $recoverPassword = HtmlHelper::anchor($this->url->get('recovery_password.path'), [], 'recupera-la');
			     $error = "Esqueceu sua senha? Talvez possamos ajudar a {$recoverPassword}";	
            } else {
                $error = $e->getMessage();
            }
			
		} catch (DomainDataException $e) {
			
			$error = $e->getMessage();
			
		} catch (AuthException $e) {
			
			$error = $e->getMessage();
			
		} catch (\Exception $e) {
			
			LogService::record($e->getMessage(), LogService::ERROR);
			$error = "Infelizmente o sistema comportou-se de maneira inesperada.
				Tente mais tarde pra verificar se o problema foi corrigido.";
			
		}
		
		if (!empty($error))
			$this->flashMessages->error($error);
		
		return;

	}
	
	public function pageLogin($redirect = null)
	{

        $this->userSessionService->finalize();
		
		if (count($_POST) > 0)
			$this->authenticate();

		// Load templante
		$this->template->addFile('CONTENT', ROOT_DIR . '/themes/account/views/login.tpl');
		
		// If not authenticate
		$this->template->USERNAME = $this->http->request->get('username', ''); 
		$this->template->PASSWORD = $this->http->request->get('password', '');
		$this->template->REMEMBER = ($this->http->request->get('remember') == 'on') ? 'checked' : '';
		
		$redirectTo = $this->http->query->get('redirect', '');
		if ($this->template->exists('REDIRECT'))
			$this->template->REDIRECT = $redirectTo;
		$this->page_title = 'Account Login';
		// Js plugins
		$this->requirePlugin('BootstrapValidator');
		$this->dispatchTemplate();
		
	}
	
	public function logout ()
	{
        $this->userSessionService->finalize();
        $this->redirect('home');
	}
	
		
}