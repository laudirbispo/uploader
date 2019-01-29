<?php

return [
	
	// ************************** Authentication *****************************
	'login' => ['path' => '/login', 'name' => 'Login'],
	'recovery_password' => ['path' => '/accounts/password/recover', 'name' => 'Recuperar senha'],
	'admin_logout' => ['path' => '/admin/logout', 'name' => 'Sair'],
	'admin_lock_screen' => ['path' => '/admin/lockscreen', 'name' => 'Modo de espera'],
	
	// ****************************** Admin **********************************
	'admin_dashboard' => ['path' => '/admin/dashboard', 'name' => 'Dashboard'],
	'admin_users' => ['path' => '/admin/users', 'name' => 'Usuários'],
	'admin_users_new' => ['path' => '/admin/users/new', 'name' => 'Adicionar usuário'],
	'admin_users_edit' => ['path' => '/admin/user/edit', 'name' => 'Editar usuário'],
	'admin_users_groups' => ['path' => '/admin/users/groups', 'name' => 'Grupos de usuários'],
	'admin_users_groups_new' => ['path' => '/admin/users/group/new', 'name' => 'Novo grupo'],
	'admin_users_groups_edit' => ['path' => '/admin/users/group/edit/', 'name' => 'Editar grupo'],
    'admin_users_groups_permissions' => ['path' => '/admin/users/group/permissions/', 'name' => 'Alterar permissões'],
    
    // ****************************** Admin Account **********************************
    'admin_account_about' => ['path' => '/admin/account/about', 'name' => 'Minha conta'],
    'admin_account_password' => ['path' => '/admin/account/password', 'name' => 'Minha conta'],
    
    // ****************************** Admin Settings **********************************
    'admin_settings' => ['path' => '/admin/system/settings', 'name' => 'Configurações do sistema'],
    'admin_trash' => ['path' => '/admin/system/trash', 'name' => 'Lixeira'],
    'admin_logs' => ['path' => '/admin/system/logs', 'name' => 'Logs'],
    'admin_feedback' => ['path' => '/admin/system/feedback', 'name' => 'Feedback'],
    'admin_widgets' => ['path' => '/admin/system/widgets', 'name' => 'Widgets'],

];