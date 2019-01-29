<?php declare(strict_types=1);
namespace app\Web\Helpers;
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
use libs\laudirbispo\HTML\Elements\{Link, Icon, Span, Menu};

final class AdminMenuHelper
{
    const MENU_CLASS = 'nav metismenu';
    const MENU_ID = 'side-menu';
    const ACTIVE_CLASS = '';

    private $permissions = [];
    
    private $url;
    
    private $userRole;
    
    private $menuLevelClass = 'nav metismenu';
    
    private $menuMainId = 'side-menu';
    
    private $activeItemClass = 'active';
    
    public function __construct(array $permissions, string $userRole, $url)
    {
        $this->permissions = $permissions;
        $this->userRole = $userRole;
        $this->url = $url;
    }
    
    private function builder() : string 
    {
        $Menu = new Menu('', [
            'class' => self::MENU_CLASS,
            'id' => self::MENU_ID,
        ]);
        
        $Menu->openContainer(
            '<div class="navbar-default sidebar" role="navigation">
             <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close">
                        <i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navegação</span>
                    </h3>
                </div>'
        );
        
        $Menu->addItem(
            Link::make(
                '<i class="mdi mdi-home-outline fa-fw"></i> <span class="hide-menu">Home</span></a>',
                [
                    'href' => $this->url->get('admin_dashboard.path'),
                    'class' => 'waves-effect'
                ]
            )
        );
        
        // Load menus for admins
        if (in_array($this->userRole, ['support', 'superadmin', 'admin'])) {

            $SubmenuUsers = new Menu(
                Link::make(
                    '<i class="mdi mdi-account-multiple" data-icon="v"></i> <span class="hide-menu">Usuários<span class="fa arrow"></span></span>',
                    [
                        'href' => $this->url->get('admin_users.path'), 
                        'class' => 'waves-effect',
                        'role' => 'button'
                    ]
                ),
                ['class' => 'nav nav-second-level'] 
            );
            
            $SubmenuUsers->addItem(
                Link::make(
                    '<i class="mdi mdi-view-list"></i><span class="hide-menu"> Todos</span>',
                    ['href' => $this->url->get('admin_users.path'), 'class' => 'waves-effect']
                )
            );
            
            $SubmenuUsers->addItem(
                Link::make(
                    '<i class="mdi mdi-account-plus"></i><span class="hide-menu"> Adicionar</span>',
                    [
                        'href' => $this->url->get('admin_users_new.path'), 
                        'class' => 'waves-effect'
                    ]
                )
            );
            
            $SubmenuUsers->addItem(
                Link::make(
                    '<i class="mdi mdi-account-multiple"></i><span class="hide-menu"> Grupos</span>',
                    ['href' => $this->url->get('admin_users_groups.path'), 'class' => 'waves-effect']
                )
            );
            
            $Menu->addSubmenu($SubmenuUsers);
                
        }
        
        if ($this->userRole === 'support ' || $this->userRole === 'superadmin') {
            
            $SubmenuSystem = new Menu(
                Link::make(
                    '<i class="mdi mdi-settings"></i> <span class="hide-menu"> <i class="fa arrow"></i> Sistema</span>',
                    [
                        'href' => $this->url->get('admin_settings.path'), 
                        'class' => 'waves-effect',
                        'role' => 'button'
                    ]
                ),
                ['class' => 'nav nav-second-level']
            );
            
            $SubmenuSystem->addItem(
                Link::make(
                    '<i class="mdi mdi-delete"></i> <span class="hide-menu"> Lixeira</span>',
                    ['href' => $this->url->get('admin_trash.path'), 'class' => 'waves-effect']
                )
            );
            
            $SubmenuSystem->addItem(
                Link::make(
                    '<i class="mdi mdi-file-xml"></i> <span class="hide-menu"> Logs</span>',
                    ['href' => $this->url->get('admin_logs.path'), 'class' => 'waves-effect']
                )
            );
            
            $Menu->addSubmenu($SubmenuSystem);
        }

        $Menu->closeContainer('</div></div>');
        return $Menu->show();
        
    }
    
    public function get() : string 
    {
        return $this->builder();
    }
    
    private function checkPermission(string $permission) : bool 
    {
        return in_array($activity, $this->authorizationService->getPermissions());  
    }
}