<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close">
				<i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navegação</span>
			</h3>
        </div>
        <ul class="nav metismenu" id="side-menu">
			
			<li> <a href="/admin/home" class="waves-effect"><i class="mdi mdi-home-outline"></i> <span class="hide-menu">Home</span></a> </li>
            
            <!-- BEGIN MENU_USERS -->
            <li><a href="{URL_ADMIN_USERS}" role="button" class="waves-effect"> 
				<i class="mdi mdi-account-multiple" data-icon="v"></i> <span class="hide-menu">Usuários<span class="fa arrow"></span></span>
				</a>
                <ul class="nav nav-second-level">
					<!-- BEGIN SUBMENU_VIEW_USERS -->
                    <li>
						<a href="{URL_ADMIN_USERS}">
							<i class="mdi mdi-account-multiple-outline fa-fw"></i><span class="hide-menu">Todos</span>
						</a> 
					</li>
					<!-- END SUBMENU_VIEW_USERS -->
					<!-- BEGIN SUBMENU_ADD_USERS -->
                    <li>
						<a href="{URL_ADMIN_USERS_ADD}">
							<i class="mdi mdi-plus fa-fw"></i><span class="hide-menu">Adicionar</span>
						</a> 
					</li>
					<!-- END SUBMENU_ADD_USERS -->
					<!-- BEGIN SUBMENU_VIEW_GROUPS -->
                    <li>
						<a href="{URL_ADMIN_USERS_GROUPS}">
							<i class="mdi mdi-account-box-outline fa-fw"></i><span class="hide-menu">Grupos de usuários</span>
						</a> 
					</li>
					<!-- END SUBMENU_VIEW_GROUPS -->
                </ul>
            </li>
            <!-- END MENU_USERS --> 
            
            <!-- BEGIN MENU_SYSTEM_CONFIGS -->
            <li>
				<a href="#" class="waves-effect">
					<i class="mdi mdi-settings" data-icon="v"></i> <span class="hide-menu"> Sistema <span class="fa arrow"></span></span>
				</a>
                <ul class="nav nav-second-level">
                    <li><a href="#"><span class="hide-menu">Todos</span></a> </li>
                    <li><a href="#"><span class="hide-menu">Adicionar</span></a> </li>
                    <li><a href="#"><span class="hide-menu">Grupos e Permissões</span></a> </li>
                </ul>
            </li>
            <!-- END MENU_SYSTEM_CONFIGS -->
            
        </ul>
    </div>
</div>
