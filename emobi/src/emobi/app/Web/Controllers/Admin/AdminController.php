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
use app\Web\Controllers\ApplicationController;
use app\IdentityAccess\Application\Services\Auth\{
    UserSessionService, 
    Exceptions\AuthException,
    Exceptions\UnauthenticatedUser,
    Exceptions\SessionExpired
};
use app\Shared\Helpers\DateTimeHelper;
use app\IdentityAccess\Infrastructure\Repository\Query\PDO\PDOUserQuery;
use app\IdentityAccess\Domain\Services\Authorization\AuthorizationService;
use app\Web\Helpers\AdminMenuHelper;

class AdminController extends ApplicationController
{   

	/**
	 * User info
	 */
	protected $user = [];
    
    /**
	 * Instance of the app\Domain\Services\Authorization\Authorizer;
	 */
    protected $authorizationService;
    
    /**
	 * Instance of the app\Domain\Services\Authentication\UserSessionService;
	 */
    protected $userSessionService;
 
	/* 
	 * Titúlo da página 
	 */
	protected $pageTitle = 'Emobi Multitask Admin';
	
	protected $favicon = '/admin/favicon.png';
	
	/* 
	 * Mostrar ou não o footer;
	 * @param bool
	 */
	protected $showFooter = true;
	
	/**
	 * Load or not navbar_top
	 */
	protected $showNavbarTop = true;
	
	/**
	 * Load or not sidebar menu left
	 */
	protected $showSidebarMenuLeft = true;
	
	/**
	 * Load or not sidebar settings right
	 */
	protected $showSidebarSettingsRight = true;
	
	/**
	 * Load bread breadcrumb
	 */
	protected $showBreadcrumb = true;
	
	/**
	 * Nome do tema atual
	 */
    protected $themeName;
    
    /**
	 * Localização do tema atual
	 */
    protected $themeFolder; 
	
	/**
	 * Layout do tema
	 */
	protected $themeLayout;
	
	/**
	 * Versão do tema
	 */
	protected $themeVersion;
	
	/**
	 * Um link para mais informações sobre o tema
	 */
	protected $themeInfo;
	
	
	public function __construct ()
	{	
		/** Views initialization */
        $this->themeLayout = '/views/layout.tpl';
        $this->themeFolder = '/themes/material';
        $this->themeName = 'Material';
		
		// Constructor ApplicationController
		parent::__construct();

		// Carrega o arquivo base do layout
		$this->template->layout(ROOT_DIR . '/app/Web/Views/admin/layout.tpl', true);
        $this->javaScript->addFile('/themes/account/js/custom.js', 'async');
        $this->requirePlugin('sweetalert');
		
		// Check access
        $this->userSessionService = new UserSessionService($this->session);
		$this->checkAccess();
        
        // Authorization Service Init
		$UserQueryRepository = new PDOUserQuery($this->container->get('pdo'));
        $this->authorizationService = new AuthorizationService(
            $UserQueryRepository, 
            $this->userSessionService->getUserId(),
            $this->userSessionService->getRole()
        );

	}

	/**
	 * Check if have user authenticated
	 * If true, get user information, else, redirect to login page
	 */
	protected function checkAccess ()
	{ 
		try {
			
			$this->userSessionService->checkRole(['support', 'superadmin', 'admin', 'assistant']);
            $this->userSessionService->checkIp();
            $this->userSessionService->checkDomain();
            
        } catch (SessionExpired $e) {
            
            $this->flashMessages->error("O tempo limite de sua sessão acabou.");
            $redirect = $this->url->get('login.path') . '?redirect=' . $this->getCurrentUrl(true);
			$this->redirect($redirect, 302);

		} catch (UnauthenticatedUser $e) {
	       
			// Tenta fazer login com o Tokenizer
			
		} catch (AuthException $e) {
	       
			$this->flashMessages->error($e->getMessage());
			$redirect = $this->url->get('login.path') . '?redirect=' . $this->getCurrentUrl(true);
			$this->redirect($redirect, 302);
			
		}
		
	}
	
	/**
	 * Assign values in the header view
	 */
	protected function initHeader ()
	{
		$this->template->LANG = $this->language;
		$this->template->PAGE_TITLE = $this->pageTitle;
		$this->template->FAVICON = $this->favicon;
	}
	
	/**
	 * Assign values in the content view
	 */
	protected function initContent ()
	{	
		// load navbar top
		if ($this->showNavbarTop)
			$this->loadNavbarTop();
		
		if ($this->showSidebarMenuLeft)
			$this->loadSidebarMenuLeft();
		
		if ($this->showSidebarSettingsRight)
			$this->loadSidebarSettingsRight();
	}
	
	/**
	 * Assign values in the footer view
	 */
	protected function initFooter ()
	{

		$this->template->COPY_RIGHT = DateTimeHelper::generateCopyright(2017);
		
		// Aqui fazemos nos referimos realmente ao footer com HTML <footer></footer
		if ($this->showFooter)
			$this->template->block('FOOTER_BLOCK');
		
	}
	
	/**
	 * Load navbar top
	 */
	protected function loadNavbarTop ()
	{
		$this->template->addFile('NAVBAR_MENU_TOP', ROOT_DIR.'/app/Web/Views/admin/blocks/navbar-top.tpl');
        $this->template->URL_ADMIN_LOCKSCREEN = $this->url->get('admin_lock_screen.path');
        $this->template->URL_ADMIN_MY_ACCOUNT = $this->url->get('admin_account_about.path');
        $this->template->URL_ADMIN_LOGOUT = $this->url->get('admin_logout.path');
	}
	
	protected function loadSidebarMenuLeft ()
	{
        $MenuHelper = new AdminMenuHelper(
            $this->authorizationService->getPermissions(),
            $this->userSessionService->getRole(),
            $this->url
        );
        
        $this->template->SIDEBAR_MENU_LEFT = $MenuHelper->get();
		
	}
	
	protected function loadSidebarSettingsRight ()
	{
		$this->template->addFile('SIDEBAR_SETTINGS_RIGHT', ROOT_DIR.'/app/Web/Views/admin/blocks/sidebar-settings-right.tpl');
	}
	
	protected function loadBreadcrumb(array $map)
	{
		$this->template->addFile('BREADCRUMB', ROOT_DIR.'/app/Web/Views/admin/blocks/breadcrumb.tpl');

        $i = count($map)-1;
		foreach ($map as $key => $value) {
           if ($key != $i)
                $this->template->URL = $this->url->get($value.'.path');
            else
                $this->template->URL = '';
            
            $this->template->PAGE_NAME = $this->url->get($value.'.name');
			$this->template->block('BLOCK_LI');
		}

	}
	
	protected function showAccessDeniedAlert()
	{
		$this->showFooter = true;
		$this->showSidebarMenuLeft = true;
		$this->showNavbarTop = true;
		$this->showSidebarSettingsRight = true;
		
		$this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/access-denied.tpl');
		$this->display();
	}
	
	protected function showResourceUnavailable(string $message = '')
	{
		$this->showFooter = true;
		$this->showSidebarMenuLeft = true;
		$this->showNavbarTop = true;
		$this->showSidebarSettingsRight = true;
		
		$this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/resource-unavailable.tpl');
		$this->template->MESSAGE = nl2br($message);
		$this->display();
	}
	
	/**
	 * This function load all variables used on the page
	 */
	private function loadGlobalVars ()
	{
		// Current user logged info
		if ($this->template->exists('CURRENT_USERNAME'))
			$this->template->CURRENT_USERNAME = '@'.$this->userSessionService->getUsername();

		if ($this->template->exists('CURRENT_USER_PROFILE_PICTURE'))
			$this->template->CURRENT_USER_PROFILE_PICTURE = $this->userSessionService->getPicture();

		if ($this->template->exists('CURRENT_USER_EMAIL'))
			$this->template->CURRENT_USER_EMAIL = $this->userSessionService->getEmail();
        
        if ($this->template->exists('CURRENT_PROFILE_PICTURE'))
            $this->template->CURRENT_PROFILE_PICTURE = $this->userSessionService->getPicture();
		
		if ($this->template->exists('URL_ADMIN_ACCOUNT'))
			$this->template->URL_ADMIN_ACCOUNT = $this->url->get('admin_account.path');
		
		if ($this->template->exists('URL_ADMIN_LOGOUT'))
			$this->template->URL_ADMIN_LOGOUT = $this->url->get('admin_logout.path');
			
	}
	
	/**
	 * Show of the view
	 */
	protected function display()
	{
		$this->initHeader();
		$this->initContent();     
		$this->initFooter();
		
		// Load css and javascript for variables of the templantes
        $this->loadCss();
		$this->loadJavaScript();
		// Load global variables
		$this->loadGlobalVars();
		
		// Print Flash Messages
		if ($this->flashMessages->hasMessages()) {
            
			if ($this->template->exists('FLASH_MESSAGES'))
				$this->template->FLASH_MESSAGES = $this->flashMessages->display(false, false);
		}
		
		$this->dispatchTemplate();
	}

}
