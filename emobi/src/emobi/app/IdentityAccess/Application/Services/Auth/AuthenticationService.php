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
use app\IdentityAccess\Domain\Contracts\UserQueryRepository;
use app\IdentityAccess\Infrastructure\Services\PasswordHashService;
use app\IdentityAccess\Domain\Models\{UserRead, Username, Password, HashedPassword};
use app\Shared\Models\EmailAddress;
use app\IdentityAccess\Application\Commands\LoginCommand;
use app\Shared\Exceptions\DomainDataException;

class AuthenticationService
{	
	/**
	 * @instance of implementing a query repository UserQueryRepository
	 */
	private $UserQueryRepository;
	
	
	public function __construct (UserQueryRepository $UserQueryRepository)
	{
		$this->UserQueryRepository = $UserQueryRepository;
	}
	
	/**
	 * Authenticate user
	 *
	 * @param string $login - Username or email address
	 * @param string $password - not hashed
	 */
	public function authenticate($username, $password)
	{
		$User = $this->findAccount($username);
		// Check account status
		$this->checkAccountStatus($User);
		// Check Password
		$this->checkPassword($User->getAccount()->getPassword(), $password);
		
		$authInfo = array(
			'time' => time(),
			'user_id' => $User->id(),
            'username' => $User->getAccount()->getUsername(),
			'email' => $User->getAccount()->getEmail(),
			'role' => $User->getAccount()->getRole()
		);
		
		return $authInfo;
			
	}

	private function findAccount($login)
	{
		try {
			
			if (filter_var($login, FILTER_VALIDATE_EMAIL)){
				$Login = new EmailAddress($login);
				$User = $this->UserQueryRepository->getAccountByEmail($Login->get()); 
			} else {
				$Login = new Username($login);
				$User = $this->UserQueryRepository->getAccountByUsername($Login->get()); 
			}
			
		} catch (DomainDataException $e) {
			throw new Exceptions\UserDoesNotExist('Credenciais inválidas');
		}
		
		if (!$User)
			throw new Exceptions\UserDoesNotExist('Credenciais inválidas');
		else 
			return $User;
	}
	
	private function checkAccountStatus(UserRead $User) 
	{
		// If true throw exception
		$isSuspended = new Specification\AccountIsSuspended();
		if ($isSuspended->isSatisfiedBy($User->getAccount()->getStatus()))
			throw new Exceptions\AccountIsNotActive("Esta conta está suspensa.");
		
		// If true throw exception
		$isBlocked = new Specification\AccountIsBlocked();
		if ($isBlocked->isSatisfiedBy($User->getAccount()->getStatus()))
			throw new Exceptions\AccountIsNotActive("Esta conta está bloqueada temporariamente.");
		
		// If true throw exception
		$isInactive = new Specification\AccountIsInactive();
		if ($isInactive->isSatisfiedBy($User->getAccount()->getStatus()))
			throw new Exceptions\AccountIsNotActive("Esta conta não está ativada, verifique sua caixa de e-mails.");
		
		// If false throw exception
		$isActive = new Specification\AccountIsActive();
		if (!$isActive->isSatisfiedBy($User->getAccount()->getStatus()))
			throw new Exceptions\AccountIsNotActive();
	}
	
	private function checkPassword($hash, $password)
	{
		try {
			$PasswordHashService = new PasswordHashService(
				new Password($password)
			);
			if (!$PasswordHashService->verify(new HashedPassword($hash)))
				throw new Exceptions\IncorrectPassword();
			
		} catch (DomainDataException $e) {
			
			throw new Exceptions\AuthException('Credenciais inválidas');
		}
		
	}	
	
}
