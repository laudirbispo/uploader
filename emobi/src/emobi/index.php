<?php declare(strict_types=1);
// Diz para o PHP que estamos usando strings UTF-8 até o final do script
mb_internal_encoding('UTF-8');
// Diz para o PHP que nós vamos enviar uma saída UTF-8 para o navegador
mb_http_output('UTF-8');

require_once(__DIR__ . '/app/config/configurations.php');
require_once(__DIR__ . '/app/autoload.php');
require_once(__DIR__ . '/vendor/autoload.php');

use app\Core\Session\SessionFileHandler;
use libs\laudirbispo\Route\{EasyRouter,Exceptions\RouteNotFound};
use app\Services\LogService;

// Setting session cookie access
session_set_cookie_params(0, "/", $_SERVER['HTTP_HOST'], false);
/* Tempo de vida das sessões */
ini_set('session.gc_maxlifetime', '3600'); 
/* Cookies somente acessiveis por HTTP */
ini_set('session.cookie_httponly', '1'); 
//Alteramos o manipulador de sessões, tipo files 
ini_set('session.save_handler', 'files');
$sessionHandler = new SessionFileHandler(EM_SESSION_SAVE_PATH);
// Iformamos ao PHP a nova classe manipuladora
session_set_save_handler($sessionHandler, true);
// Define o novo local em que os arquivos de sessão serão salvos
ini_set('session.save_path', EM_SESSION_SAVE_PATH);
// Nome aleatório para a sessão
session_name(md5(EM_SESSION_NAME));
// Inicia as sessão
session_start();

try {
	
    //die(ROOT_DIR);
	$router = new EasyRouter();

	$router->add(['/login', '/login?(.*)'], 'app\Web\Controllers\AccountController::pageLogin');
    
	$router->execute($_SERVER['REQUEST_URI']);
	
} catch(RouteNotFound $e) {

	(new app\Web\Controllers\Admin\AdminErrors())->pageNotFound();
	
} catch (\Exception $e) {
	
	LogService::record($e->getMessage(), LogService::ERROR);
	(new app\Web\Controllers\Admin\AdminErrors())->serviceUnavailable();
	
}
