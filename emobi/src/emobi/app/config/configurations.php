<?php declare(strict_types=1);

/* Configurações de data e hora  */
date_default_timezone_set('America/Sao_Paulo');

$httpProtocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
define('DS', '/');
//define('DS', DIRECTORY_SEPARATOR);
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT']);
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
define('VIEWS_DIR', BASE_DIR . '/themes');
define('ADMIN_DIR', BASE_DIR . '/admin');
define('DIR_USERS', BASE_DIR . '/docs/users'); 
// Domain principal
define('EM_DOMAIN', $httpProtocol.'://localhost');

// System identification
define('SYSTEM_NAME', 'CMS Easy Mobi');
// Use UUID4 to generate the system ID, remove the hyphens
define('SYSTEM_ID', '1a5442f5fc6d4bbea3be148a014acb5c');
define('SYSTEM_VERSION', '1.0.1');

/** =========== DATABASES ============== */
// Projection Database
define('EM_DB_HOST', 'localhost');  
define('EM_DB_USER', 'root');                     
define('EM_DB_PASSWORD', 'timao000');                
define('EM_DB_DATABASE', 'emobi');     
define('EM_DB_PERSISTENT', 'Y');
define('EM_TABLES_PREFIX', 'em_');
// EventStore Database. ES = Event Source
define('ES_HOST', 'localhost');  
define('ES_USER', 'root');                     
define('ES_PASSWORD', 'timao000');                
define('ES_DATABASE', 'emobi');

/** ======================= Admin  ============================ */
// Theme
define('EM_ADMIN_THEME_FOLDER', '/themes/admin/material');
define('EM_ADMIN_THEME_LAYOUT', '/views/layout.tpl');
define('EM_ADMIN_THEME_NAME', 'Material');
define('EM_ADMIN_THEME_VERSION', '1.0.1');
define('EM_ADMIN_THEME_AUTHOR', '');
define('EM_ADMIN_THEME_HELPER', 'https://easymobi.com.br/emobi/ajuda');
//Paths .....

/** ======================= System configurations  ============================ */\
// Gerenciamento de logs do sistema
define('EM_DIR_SAVE_LOGS', BASE_DIR . '/../.logs');
// Cache dir 
define('CACHE_DIR', BASE_DIR . '/../.cache');
// Session dave patch
define('EM_SESSION_SAVE_PATH', BASE_DIR . '/../.sessions');
define('EM_SESSION_NAME', 'lbfcms'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
