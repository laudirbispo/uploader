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
use app\Shared\Helpers\HtmlHelper;
use JMS\Serializer\SerializerBuilder;
use libs\laudirbispo\CQRSES\ListenerDispatcher;
use app\Shared\Infrastructure\Repository\EventStore\PDO\PDOEventStore;
use app\IdentityAccess\Infrastructure\Repository\{
    UserRepository,
	Projection\PDO\PDOUserProjection,
    Query\PDO\PDOUserQuery
};
use app\IdentityAccess\Application\Commands\{
    CreateGroupCommand, 
    CreateGroupHandler, 
    ChangeGroupCommand, 
    ChangeGroupHandler,
    ChangeGroupStatusCommand,
    ChangeGroupStatusHandler,
    ChangeGroupPermissionsCommand,
    ChangeGroupPermissionsHandler
};
use app\IdentityAccess\Application\Queries\{
    AllGroupsQuery, 
    AllGroupsQueryHandler, 
    GroupQueryByUuid, 
    GroupQueryByUuidHandler
};
use app\IdentityAccess\Specification\{GroupNameIsUnique, UserRoleIsSupport, UserRoleIsSuperadmin, UserRoleIsAdmin};
use app\Shared\Exceptions\DomainDataException;
use app\IdentityAccess\Domain\Services\Authorization\Resources;
use app\Web\Helpers\SwitcheryHelper;
use app\Shared\Exceptions\{StorageException, DuplicateItemInTheStorage};
 
class AdminGroups extends AdminController
{
	public function __construct()
	{
		
		// Prevent
		parent::__construct();
		if (!$this->authorizationService->authorizeRoles(['support', 'superadmin', 'admin'])) {
            $this->showAccessDeniedAlert();
			die(); 
        }
		
	}
	
	public function pageGroups()
	{

		$this->javaScript->addFile('/admin/assets/js/users.js', 'async defer charset= "UTF-8"');
		$this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/groups.tpl');
		$this->loadBreadcrumb(['admin_dashboard', 'admin_users', 'admin_users_groups']);
		
		$this->template->block('BUTTON_ADD_GROUPS');
		
		// Load URL`s
		if ($this->template->exists('URL_ADD_GROUPS'))
			$this->template->URL_ADD_GROUPS = $this->url->get('admin_users_groups_new.path');
		
		try {
	
			$PDO = $this->container->get('pdo');
			$UserQueryRepository = new PDOUserQuery($PDO);
			$AllGroupsQuery = new AllGroupsQuery;
			$AllGroupsQueryHandler = new AllGroupsQueryHandler($UserQueryRepository);
			$groups = $AllGroupsQueryHandler->handler($AllGroupsQuery);
		
			if (count($groups) > 0) {

				foreach ($groups as $Group) {
					$this->template->GROUP_NAME = ucfirst($Group->getName());
					$this->template->GROUP_DESCRIPTION = ucfirst($Group->getDescription());
					$this->template->GROUP_STATUS = ($Group->getStatus() === 'active') ? 
                                                    HtmlHelper::label('success', 'Ativo') : 
                                                    HtmlHelper::label('danger', 'Inativo');
					$this->template->URL_EDIT_GROUP = $this->url->get('admin_users_groups_edit.path').$Group->getId();
					$this->template->URL_EDIT_PERMISSIONS = $this->url->get('admin_users_groups_permissions.path').$Group->getId();
					$this->template->block('COLS_GROUPS');
				}
			}
			
		} catch(\Exception $e) {
			 echo $e->getMessage();
		}
		$this->display();
	}
	
	public function pageAddGroups()
	{
		
		$csrf = $this->container->get('csrf');
		if (count($_POST) > 0) {
			
			if (!$csrf->checkToken())
				$this->flashMessages->warning($csrf->getError());
			else

				$this->createGroup();
		}
		
		// Load plugins
		$this->requirePlugin('BootstrapValidator');
		
		$this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/groups-add.tpl');
		$this->loadBreadcrumb(['admin_dashboard', 'admin_users', 'admin_users_groups', 'admin_users_groups_new']);
		$this->template->PAGE = 'Novo Grupo';
		
		$this->template->CSRF_TOKEN = $csrf->makeToken();
		$this->template->FORM_ACTION = $this->url->get('admin_user_groups_add.path');
		$this->template->SUBMIT_ACTION = 'add-group';
		
		$this->template->GROUP_NAME = $this->http->request->get('group-name', ''); 
		$this->template->GROUP_DESCRIPTION = $this->http->request->get('group-description', '');
        $this->template->GROUP_STATUS = ($this->http->request->get('group-status') == 'on') ? 'checked' : '' ;
        

		$this->display();
	}

	
	public function pageEditGroup($groupId)
	{
		
		$csrf = $this->container->get('csrf');
		if (count($_POST) > 0) {
			
			if (!$csrf->checkToken()) {
				$this->flashMessages->warning($csrf->getError());
			} else {
				// Add new var to POST
				$this->http->request->set('group-uuid', $groupId);
				$this->changeGroup();
			}	
		}
		
		try {

			$PDO = $this->container->get('pdo');
			// Check if group already exists
			$UserQueryRepository = new PDOUserQuery($PDO);
			$GroupQueryByUuid = new GroupQueryByUuid($groupId);
			$GroupQueryByUuidHandler = new GroupQueryByUuidHandler($UserQueryRepository);
			$Group = $GroupQueryByUuidHandler->handler($GroupQueryByUuid);

			if ($Group) {
				
				// Load plugins
				$this->requirePlugin('BootstrapValidator');
				$this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/groups-add.tpl');
				$this->loadBreadcrumb(['admin_dashboard', 'admin_users', 'admin_users_groups', 'admin_users_groups_edit']);
				$this->template->PAGE = 'Editar Grupo';

				$this->template->GROUP_NAME = ucfirst($Group->getName());
				$this->template->GROUP_DESCRIPTION = ucfirst($Group->getDescription());
                $this->template->GROUP_STATUS = ($Group->getStatus() === 'active') ? '' : 'checked';
				$this->template->CSRF_TOKEN = $csrf->makeToken();
				$this->template->FORM_ACTION = $this->url->get('admin_user_groups_add.path');
				$this->template->SUBMIT_ACTION = 'add-group';

			} else {
				$this->showResourceUnavailable("Não encotramos o grupo que você está tentando editar.\n Verifique se o endereço que você digitou está correto.");
			}
			
		} catch (DomainDataException $e) {
			
			$this->showResourceUnavailable("Não encotramos o grupo que você está tentando editar.\n Verifique se o endereço que você digitou está correto.");
			
		} catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde....");
		}
		
		$this->display();
	}
    
    public function pageEditPermissions($groupId)
    {
        $csrf = $this->container->get('csrf');
        if (count($_POST) > 0) {
			
			if (!$csrf->checkToken()) {
				$this->flashMessages->warning($csrf->getError());
			} else {
				// Add new var to POST
				$this->http->request->set('group-uuid', $groupId);
				$this->changeGroupPermissions();
			}	
		}
        
        //$this->showSidebarMenuLeft = false;
		//$this->showNavbarTop = false;
		$this->pageTitle = 'Emobi Multitask - Alterar permissões';
        
        // Load plugins
		$this->requirePlugin('Switchery');
        $this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/users/permissions.tpl');
		$this->loadBreadcrumb(['admin_dashboard', 'admin_users', 'admin_users_groups', 'admin_users_groups_permissions']);
        $this->javaScript->addBlock(
            SwitcheryHelper::init('.js-switch', true, ['size' => 'small', 'secondaryColor' => '#F96262'])
        );
        $this->template->CSRF_TOKEN = $csrf->makeToken();
        
        /** -----------*/
        $PDO = $this->container->get('pdo');
        $UserQueryRepository = new PDOUserQuery($PDO);
        $GroupQueryByUuid = new GroupQueryByUuid($groupId);
        $GroupQueryByUuidHandler = new GroupQueryByUuidHandler($UserQueryRepository);
        $Group = $GroupQueryByUuidHandler->handler($GroupQueryByUuid);
        
        if (!$Group) {
            $this->flashMessages->warning('Este Grupo não existe ou não está desponível no momento.');
            $this->display();
        }  elseif ($Group->getPermissions() === null) {
             $permissions = [];
        } else {
            $permissions = json_decode($Group->getPermissions(), true); 
        }
            

        $Resources = new Resources;
        $allResources = $Resources->getAll();

        $this->template->GROUP_NAME = $Group->getName();

        foreach ($allResources as $group) {
            $this->template->RESOURCE_GROUP = $group['description'];
            foreach ($group['resources'] as $resource => $key) {
                $this->template->RESOURCE_DESCRIPTION = $key['description'];
                $this->template->RESOURCE = $resource;
                $this->template->CHECKED = (in_array($resource, $permissions)) ? 'checked' : '';
                $this->template->block('BLOCK_RESOURCE');
            }
        }

        $this->display();
        
    }
    
	public function createGroup()
	{
		try {
			
			$groupName = $this->http->request->get('group-name', '');
			$groupDescription = $this->http->request->get('group-description', '');
            $groupStatus = ($this->http->request->get('group-status') == 'on') ? 'inactive' : 'active';
			$createdBy = $this->userSessionService->getUserId();
			
			$PDO = $this->container->get('pdo');
			// Check if group already exists
			$UserQueryRepository = new PDOUserQuery($PDO);
			$Specification = new GroupNameIsUnique($UserQueryRepository);
			if (!$Specification->isSatisfiedBy($groupName))
				throw new DomainDataException("Este grupo já existe! Que tal escolher outro nome?");
			
			// In this case, the serializer is dispensable
			$Serializer = SerializerBuilder::create()
				->addMetadataDir(ROOT_DIR.'/app/IdentityAccess/Resources/Serializer/Mapping/Events')
				->build();
			
			
			$UserProjection = new PDOUserProjection($PDO);
			$EventStore = new PDOEventStore($PDO, $Serializer);
			$UserRepository = new UserRepository($EventStore, $UserProjection);
			
			$CreateGroupCommand = new CreateGroupCommand($groupName, $groupDescription, $groupStatus, $createdBy);
			$CreateGroupHandler = new CreateGroupHandler($UserRepository);
			$Group = $CreateGroupHandler->handler($CreateGroupCommand);
			
			//$GroupRecordedEvents = $Group->getRecordedEvents();
			
			$this->flashMessages->success('Grupo adicionado');
			$this->redirect($this->url->get('admin_users_groups.path'));
			
		} catch (DomainDataException $e) {
			$this->flashMessages->info($e->getMessage());
		} catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde!!!");
		}
		
		return;
	}
	
	private function changeGroup() 
	{
		try {
			
			$groupId = $this->http->request->get('group-uuid', '');
			$groupName = $this->http->request->get('group-name', '');
            $groupStatus = ($this->http->request->get('group-status', '') == 'on') ? 'inactive' : 'active';
			$groupDescription = $this->http->request->get('group-description', '');
			// In this case, the serializer is dispensable
			$Serializer = SerializerBuilder::create()
				->addMetadataDir(ROOT_DIR.'/app/IdentityAccess/Resources/Serializer/Mapping/Events')
				->build();
			$PDO = $this->container->get('pdo');
            
			$EventStore = new PDOEventStore($PDO, $Serializer);
			$UserProjection = new PDOUserProjection($PDO);
			$UserRepository = new UserRepository($EventStore, $UserProjection);
			$ChangeGroupCommand = new ChangeGroupCommand($groupId, $groupName, $groupDescription, $groupStatus);
			$ChangeGroupHandler = new ChangeGroupHandler($UserRepository);
			$GroupModel = $ChangeGroupHandler->handler($ChangeGroupCommand);
            
			$this->flashMessages->success('Informações atualizadas');
            $this->redirect($this->url->get('admin_users_groups.path'));  
			
		} catch (DomainDataException $e) {
			$this->flashMessages->warning($e->getMessage().'fff');
		} catch (DuplicateItemInTheStorage $e) {
			$this->flashMessages->warning($e->getMessage().'ee');
		} catch (StorageException $e) {
            $e->SaveLog();
			$this->flashMessages->error($e->getMessage().'ttt');
            
		}
		
	}
    
    public function changeGroupPermissions() 
    {
        try {
            
            $groupId = $this->http->request->get('group-uuid', '');
            $permissions = $this->http->request->get('permissions', []);
            $permissions = json_encode(array_keys($permissions));
            
            // In this case, the serializer is dispensable
			$Serializer = SerializerBuilder::create()
				->addMetadataDir(ROOT_DIR.'/app/IdentityAccess/Resources/Serializer/Mapping/Events')
				->build();
            
            $PDO = $this->container->get('pdo');

			$UserProjection = new PDOUserProjection($PDO);
			$EventStore = new PDOEventStore($PDO, $Serializer);
            $Projection = new PDOUserProjection($PDO);
            $Repository = new UserRepository($EventStore, $Projection);
            $Command = new ChangeGroupPermissionsCommand($groupId, $permissions);
            $Handler = new ChangeGroupPermissionsHandler($Repository);
            $Handler->handler($Command);
            
            $this->flashMessages->success('Permissões atualizadas');
            $this->redirect($this->url->get('admin_users_groups.path'));
            
        } catch (DomainDataException $e) {
			$this->flashMessages->info($e->getMessage());
		} catch (StorageException $e) {
			$this->flashMessages->error("Infelizmente nosso servidor se comportou de forma inesperada. Tente novamente mais tarde!!!");
            $e->SaveLog();
		}
        
        return;
    }
    
    public function deleteGroup(string $groupId) : void 
    {
        
    }
    
		
}
