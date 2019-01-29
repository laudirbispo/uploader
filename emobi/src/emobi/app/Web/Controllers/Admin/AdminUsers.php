<?php declare(strict_types=1);
namespace app\Web\Controllers\Admin;

/**
 * @author - Laudir Bispo, laudirbispo@outlook.com
 *
 * AVISO DE LICENÇA
 * 
 * @license - Em hipótese alguma é permitido ao LICENCIADO ou a terceiros, de forma geral:
 * Copiar, ceder, sublicenciar, vender, dar em locação ou em garantia, reproduzir, doar, 
 * alienar de qualquer forma, transferir total ou parcialmente, sob quaisquer modalidades, gratuita ou onerosamente, 
 * provisória ou permanentemente, o SOFTWARE objeto deste EULA, assim como seus módulos, partes,  
 * manuais ou quaisquer inf
  ormações relativas ao mesmo;
 * Retirar ou alterar, total ou parcialmente, os avisos de reserva de direito existente no SOFTWARE e na documentação;
 * Praticar de engenharia reversa, descompilação ou desmontagem do SOFTWARE.
 * Estando totalmente sujeito a suspensão imediata da utilização do software e cancelamento do período de contratação, 
 * sem quaisquer restituições contratuais por parte da LICENCIANTE.  
 *
 */
use app\Web\Helpers\AdminThemeHelper;
use JMS\Serializer\SerializerBuilder;
use app\Shared\Exceptions\{DomainDataException, DomainLogicException};
use App\Shared\Infrastructure\Repository\EventStore\PDO\PDOEventStore;
use app\IdentityAccess\Infrastructure\Repository\{
    UserRepository, 
    Query\PDO\PDOUserQuery,
	Projection\PDO\PDOUserProjection
};
use app\IdentityAccess\Application\Queries\{
    AllGroupsQuery, 
    AllGroupsQueryHandler,
    GetCompleteInformationFromAllUsers,
    GetCompleteInformationFromAllUsersHandler
};
use app\IdentityAccess\Application\Commands\{
    AdminAddUserCommand, 
    AdminAddUserHandler
};
use app\IdentityAccess\Domain\Services\{
    ValidateUsernameService, 
    ValidateUserEmailAddressService,
    GenerateRandomPasswordService
};

use app\IdentityAccess\Infrastructure\Services\{
    PasswordHashService,
    SendPasswordByEmailWhenAUserWasCreated,
    UserCacheService
};
use libs\laudirbispo\CQRSES\ListenerDispatcher;
use PHPMailer\PHPMailer\PHPMailer;

class AdminUsers extends AdminController
{
	public function __construct ()
	{
		// Prevent
		parent::__construct();
        
        if (!$this->authorizationService->authorizeRoles(['support', 'superadmin', 'admin'])) {
            $this->showAccessDeniedAlert();
			die(); // Prevention
        }

	}
	
	public function listUsers ()
	{
		try {
            
            $this->pageTitle = 'Emobi Multitask - Usuários';
            $this->javaScript->addFile('/admin/assets/js/users.js', 'async defer');
            $this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/users.tpl');
            $this->loadBreadcrumb(array('admin_dashboard', 'admin_users'));
            $this->template->URL_USERS_NEW = $this->url->get('admin_users_new.path');
            $this->template->URL_USERS_GROUPS = $this->url->get('admin_users_groups.path');
            
            
            $UserCache = new UserCacheService($this->cache);
            $users = $UserCache->getAllUsers();
            
            if (null === $users) {
                $pdo = $this->container->get('pdo');
                $UserQueryRepository = new PDOUserQuery($pdo);
                $Query = new GetCompleteInformationFromAllUsers();
                $QueryHandler = new GetCompleteInformationFromAllUsersHandler($UserQueryRepository);
                $UserCollection = $QueryHandler->execute($Query); 
                $users = $UserCollection->getAll();
                // save new cache
                $UserCache->saveAllUsers($users);
                $UserCache->setTotalUsers(count($users));
            } 
   
            foreach ($users as $key => $User) {
                
                if ($User->getAccount()->getRole() === 'support') continue;
                
                // User ID
                $this->template->USER_ID = $User->id();
                // User status
                $status = $User->getAccount()->getStatus();
                if ($status === 'active')
                    $this->template->block('USER_STATUS_ACTIVE');
                elseif ($status === 'inactive')
                    $this->template->block('USER_STATUS_INACTIVE');
                else
                    $this->template->block('USER_STATUS_UNKNOWN');
                // User full name
                $this->template->USER_NAME = ucfirst($User->getProfile()->getFirstName());
                $this->template->USER_LAST_NAME = ucfirst($User->getProfile()->getLastName());
                if (null == $User->getProfile()->getPicture()) { 
                    $this->template->USER_PROFILE_PICTURE = '/assets/images/default-user.png';
                } else {
                   $this->template->USER_PROFILE_PICTURE = $User->getProfile()->getPicture(); 
                }
                
                $this->template->USER_ROLE_LABEL = AdminThemeHelper::getLabelForUserRole($User->getAccount()->getRole());

                // User E-mail
                $this->template->USER_EMAIL = $User->getAccount()->getEmail();
                $this->template->USER_USERNAME = $User->getAccount()->getUsername();
                
                $this->template->block('COLS_USERS');
                
            }
            
		} catch (DomainDataException $e) {
			$this->flashMessages->info($e->getMessage());
		} catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde!!!");
		}
		
		$this->display();
	}
	
	public function pageNewUser()
	{
        $csrf = $this->container->get('csrf');
		if (count($_POST) > 0) {
			
			if (!$csrf->checkToken())
				$this->flashMessages->notice('Pedido inválido');
			else
				$this->addNewUser();
		}
        
		$this->requirePlugin('BootstrapValidator');
		$this->javaScript->addFile('/admin/assets/js/users.js', 'async defer');
        $this->javaScript->addBlock(AdminThemeHelper::getUserRolesGroupScript());
		$this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/new-user.tpl');
		$this->loadBreadcrumb(array('admin_dashboard', 'admin_users', 'admin_users_new'));
		
		if ($this->flashMessages->hasMessages())
			$this->template->FLASH_MESSAGES = $this->flashMessages->display(false, false);
		
		// Make a CSRF_TOKEN
		$this->template->CSRF_TOKEN = $csrf->makeToken();
        $this->template->URL_USERS_GROUPS_NEW = $this->url->get('admin_users_groups_new.path');
        
        try {
            
            $pdo = $this->container->get('pdo');
            $UserQueryRepository = new PDOUserQuery($pdo);
            $AllGroupsQuery = new AllGroupsQuery;
            $AllGroupsQueryHandler = new AllGroupsQueryHandler($UserQueryRepository);
            $groups = $AllGroupsQueryHandler->handler($AllGroupsQuery);
            
            foreach ($groups as $Group) {
                $this->template->GROUP_NAME = $Group->getName();
                $this->template->GROUP_UUID = $Group->getId();
                $this->template->block('GROUPS_LIST');
            }
            
        } catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde....");
		}

		$this->display();
	}
    
    
    public function addNewUser()
    {
        try {
            
            $firstName = $this->http->request->get('first-name', '');
            $lastName = $this->http->request->get('last-name', '');
            $username = $this->http->request->get('username', '');
            $email = $this->http->request->get('email', '');
            $Password = GenerateRandomPasswordService::execute();
            $PasswordHashService = new PasswordHashService($Password);
            $hash = $PasswordHashService->hash();
            $gender = $this->http->request->get('gender', '');
            $role = $this->http->request->get('role');
            if ($role === 'assistant') {
                $groups = $this->http->request->get('groups', []);
            } else {
                $groups = [];
            }
            
            $UserCache = new UserCacheService($this->cache);

            $AdminAddUserCommand = new AdminAddUserCommand(
                $this->userSessionService->getUserId(),
                $username, 
                $firstName,
                $lastName,
                $email,
                $hash,
                $role,
                $gender,
                $groups
            );
            // In this case, the serializer is dispensable
			$Serializer = SerializerBuilder::create()
				->addMetadataDir(ROOT_DIR.'/app/IdentityAccess/Resources/Serializer/Mapping/Events')
				->build();
            
            $PDO = $this->container->get('pdo');
            $UserProjection = new PDOUserProjection($PDO);
            $EventStore = new PDOEventStore($PDO, $Serializer);
            $UserRepository = new UserRepository($EventStore, $UserProjection);
            
            // Listeners
            $ListenerDispatcher = new ListenerDispatcher(true);
            
            // Mail configurations
            $Mailer = new PHPMailer();
            //$Mailer->SMTPDebug = 3;                                 // Enable verbose debug output
            $Mailer->isSMTP();    
            $Mailer->CharSet = 'utf-8';// Set mailer to use SMTP
            $Mailer->Host = 'email-ssl.com.br';  // Specify main and backup SMTP servers
            $Mailer->SMTPAuth = true;                               // Enable SMTP authentication
            $Mailer->Username = 'noreply@criativanews.com.br';                 // SMTP username
            $Mailer->Password = 'programador.php';                           // SMTP password
            $Mailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $Mailer->Port = 587;                                    // TCP port to connect to
            $Mailer->setFrom('noreply@criativanews.com.br'); 
            
            $SendPasswordByEmail = new SendPasswordByEmailWhenAUserWasCreated($Mailer, $Password->get());
            $ListenerDispatcher->addListener('AccountWasCreatedByAdmin', $SendPasswordByEmail);
                
            $AdminAddUserHandler = new AdminAddUserHandler($UserRepository);
            $RecordedEvents = $AdminAddUserHandler->execute($AdminAddUserCommand);
            $events = $RecordedEvents->getRecordedEvents();
           
            $ListenerDispatcher->dispatch($events->getEvents());
            $UserCache->increaseUsersCount(1);
            
            
            $this->flashMessages->success("Usuário cadastrado. Um e-mail contendo as informações de login, foi enviado para o endereço fornecido.");
            
            return true;
            
        } catch (DomainDataException $e) {
			$this->flashMessages->info($e->getMessage());
		} catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde!!!");
            $e->SaveLog();
		}
        return false;

    }
    
    /** 
     * Ajax Request
     */
	public function validateUsername()
    {
        $response = [];
        
        if (!$this->isAjaxRequest()) 
            return;

        try {
            
            $username = $this->http->request->get('username', '');
            
            $pdo = $this->container->get('pdo');
            $UserQueryRepository = new PDOUserQuery($pdo);
            
            $ValidateUsernameService = new ValidateUsernameService($UserQueryRepository);
            if (!$ValidateUsernameService->execute($username)) {
                $response = [
                    'status' => 'OK',
                    'code' => 200,
                    'payload' => ['isValid' => false],
                    'message' => $ValidateUsernameService->getError()
                ];
                
            } else {
                $response = [
                    'status' => 'OK',
                    'code' => 200,
                    'payload' => ['isValid' => true],
                    'message' => 'Username disponível.'
                ];
            }
            
            
        } catch(\Exception $e) {
            $response = [
                'status' => 'error',
                'code' => 500,
                'payload' => ['isValid' => false],
                'message' => 'Serviço indisponível no momento.'
            ];
        }
        
        $this->ajaxResponse($response, $response['code']);
        
    }
    
    public function validateUserEmailAddress()
    {
       $response = [];
        
        if (!$this->isAjaxRequest()) 
            return;

        try {
            
            $emailAddress = $this->http->request->get('email', '');
            
            $pdo = $this->container->get('pdo');
            $UserQueryRepository = new PDOUserQuery($pdo);
            
            $ValidateUserEmailAddressService = new ValidateUserEmailAddressService($UserQueryRepository);
            if (!$ValidateUserEmailAddressService->execute($emailAddress)) {
                $response = [
                    'status' => 'OK',
                    'code' => 200,
                    'payload' => ['isValid' => false],
                    'message' => $ValidateUserEmailAddressService->getError()
                ];
                
            } else {
                $response = [
                    'status' => 'OK',
                    'code' => 200,
                    'payload' => ['isValid' => true],
                    'message' => 'Endereço disponível.'
                ];
            }
            
            
        } catch(\Exception $e) {
            $response = [
                'status' => 'error',
                'code' => 500,
                'payload' => ['isValid' => false],
                'message' => 'Serviço indisponível no momento.'
            ];
        }
        
        $this->ajaxResponse($response, $response['code']);
        
    }
	
}
