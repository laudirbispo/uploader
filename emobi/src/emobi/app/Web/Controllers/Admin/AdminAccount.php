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
 * manuais ou quaisquer informações relativas ao mesmo;
 * Retirar ou alterar, total ou parcialmente, os avisos de reserva de direito existente no SOFTWARE e na documentação;
 * Praticar de engenharia reversa, descompilação ou desmontagem do SOFTWARE.
 * Estando totalmente sujeito a suspensão imediata da utilização do software e cancelamento do período de contratação, 
 * sem quaisquer restituições contratuais por parte da LICENCIANTE.  
 *
 */
use JMS\Serializer\SerializerBuilder;
use app\Shared\Exceptions\{DomainDataException, DomainLogicException};
use libs\laudirbispo\CQRSES\ListenerDispatcher;
use app\Shared\Infrastructure\Repository\EventStore\PDO\PDOEventStore;
use app\Shared\Models\MailConfiguration;
use app\IdentityAccess\Infrastructure\Repository\{
    UserRepository, 
    Query\PDO\PDOUserQuery,
	Projection\PDO\PDOUserProjection
};
use app\IdentityAccess\Application\Queries\{
    ProfileQueryById,
    ProfileQueryByIdHandler
};
use app\IdentityAccess\Application\Commands\{
    ChangeProfileInfoCommand,
    ChangeProfileInfoHandler
};
use app\IdentityAccess\Application\Services\ChangePasswordService;

class AdminAccount extends AdminController
{
    
	public function __construct()
	{
		// Prevent
		parent::__construct(); 
	}
    
    /* ======================== Pages templates ================== */
    
	private function pageContent()
	{	
		// Load plugins
		$this->requirePlugin('Cropit');
		$this->requirePlugin('blockUI');
		
		// Add custom files
		$this->css->addFile('/admin/assets/css/profile-card.css', 'rel="stylesheet"');
		$this->javaScript->addFile('/admin/assets/js/update-profile-picture.js', 'async defer charset="UTF-8"');
		
		// Load view
		$this->template->addFile('CONTENT', ROOT_DIR.'/app/web/views/admin/pages/users/account.tpl');
        $this->template->URL_ACCOUNT_PASSWORD = $this->url->get('admin_account_password.path');
        $this->template->URL_ACCOUNT_ABOUT = $this->url->get('admin_account_about.path');
        
	}

	public function pageAboutMe()
	{
        
        $CSRF = $this->container->get('csrf');
        if (count($_POST) > 0) {
            if (!$CSRF->checkToken()) {
                $this->flashMessages->warning($CSRF->getError());
            } else {
                $this->updateMyProfileInformation();
            }
        }
        
        $this->pageContent();
        $this->page_title = 'Emobi Multitask - Meu Perfil';
        
        $this->requirePlugin('BootstrapValidator');
		$this->template->addFile('TAB_CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/tabs/about.tpl');
		$this->loadBreadcrumb(['admin_dashboard', 'admin_account_about']);
        
        $this->template->CSRF_TOKEN = $CSRF->makeToken();

        try {
            
            $PDO = $this->container->get('pdo');
            $UserQueryRepository = new PDOUserQuery($PDO);
            $ProfileQuery = new ProfileQueryById($this->userSessionService->getUSerId());
            $ProfileQueryHandler = new ProfileQueryByIdHandler($UserQueryRepository);
            $Profile = $ProfileQueryHandler->execute($ProfileQuery);
            
            if (null !== $Profile) {
                $this->template->USER_FIRST_NAME = $Profile->getFirstName();
                $this->template->USER_LAST_NAME = $Profile->getLastName();
                $this->template->USER_ABOUT = $Profile->getAbout();
                if ($Profile->getGender() == 'male') {
                    $this->template->GENDER_MALE = "checked";
                } elseif ($Profile->getGender() == 'female') {
                    $this->template->GENDER_FEMALE = "checked";
                } else {
                    $this->template->GENDER_OTHER = "checked";
                }
            }
            
        } catch (DomainDataException $e) {
			$this->flashMessages->info($e->getMessage());
		} catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde!!!");
		}

		$this->display();
	}
    
    public function pagePassword()
    {
        $CSRF = $this->container->get('csrf');
        if (count($_POST) > 0) {
            if (!$CSRF->checkToken()) {
                $this->flashMessages->warning($CSRF->getError());
            } else {
                $this->changePassword();
            }
        }
        
        $this->pageContent();
        $this->page_title = 'Emobi Multitask - Segurança e Login';
        
        $this->requirePlugin('BootstrapValidator');
        $this->requirePlugin('pwdHandler');
		$this->template->addFile('TAB_CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/tabs/password.tpl');
		$this->loadBreadcrumb(['admin_dashboard', 'admin_account_password']);
        
        $this->template->CSRF_TOKEN = $CSRF->makeToken();
        
        $this->display();
    }
	
	/* ======================== TABS CONTENT ================== */
    
    private function updateMyProfileInformation() 
    {
        
        try {
            
            $firstName = $this->http->request->get('user-first-name', null);
            $lastName = $this->http->request->get('user-last-name', null);
            $about = $this->http->request->get('user-about', null);
            $gender = $this->http->request->get('user-gender', null);
            
            // In this case, the serializer is dispensable
			$Serializer = SerializerBuilder::create()
				->addMetadataDir(ROOT_DIR.'/app/IdentityAccess/Resources/Serializer/Mapping/Events')
				->build();
            
            $PDO = $this->container->get('pdo');
            $UserProjection = new PDOUserProjection($PDO);
            $EventStore = new PDOEventStore($PDO, $Serializer);
            $UserRepository = new UserRepository($EventStore, $UserProjection);
            
            $ChangeProfileCommand = new ChangeProfileInfoCommand(
                $this->userSessionService->getUserId(),
                $this->userSessionService->getUserId(),
                $firstName,
                $lastName,
                $about,
                $gender,
                null,
                'não declarado'
            );
            
            $ChangeProfileHandler = new ChangeProfileInfoHandler($UserRepository);
            $Model = $ChangeProfileHandler->execute($ChangeProfileCommand);
            $this->flashMessages->success('Informações atualizadas');
            return;
            
        } catch (DomainDataException $e) {
			$this->flashMessages->info($e->getMessage());
		} catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde!!!");
		}
        
    }
    
    public function changePassword() 
    {
        try {
            
            $oldPassword = $this->http->request->get('password-current', null);
            $newPassword = $this->http->request->get('password-new', null);
            
            // In this case, the serializer is dispensable
			$Serializer = SerializerBuilder::create()
				->addMetadataDir(ROOT_DIR.'/app/IdentityAccess/Resources/Serializer/Mapping/Events')
				->build();
            
            $pdo = $this->container->get('pdo');
            $UserQueryRepository = new PDOUserQuery($pdo);
            $UserProjectionRepository = new PDOUserProjection($pdo);
            $EventStore = new PDOEventStore($pdo, $Serializer);
            $UserRepository = new UserRepository($EventStore, $UserProjectionRepository);
            
            $MailConfiguration = new MailConfiguration(
                'email-ssl.com.br',
                'noreply@criativanews.com.br',
                'programador.php',
                587,
                true,
                'tls',
                true,
                true,
                'utf-8'
            );
            
            $ChangePasswordService = new ChangePasswordService(
                $this->userSessionService->getUserId(),
                $UserRepository,
                $UserQueryRepository,
                $MailConfiguration
            );
            $ChangePasswordService->execute($this->userSessionService->getUserId(), $oldPassword, $newPassword);
 
            $this->flashMessages->success('Senha alterada');
            
        } catch (DomainDataException $e) {
			$this->flashMessages->warning
                 ($e->getMessage());
		} catch (StorageException $e) {
			$this->flashMessages->error($e->getMessage);
		}
    }
    
    public function changeProfilePicture() : void 
    {
        
    }
	
}
