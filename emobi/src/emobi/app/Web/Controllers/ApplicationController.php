<?php declare(strict_types=1);
namespace app\Web\Controllers;
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

use Noodlehaus\Config;
use League\Container\Container;
use JMS\Serializer\SerializerBuilder;
use app\Shared\Infrastructure\Repository\EventStore\PDO\PDOEventStore;
use app\Core\Database\DatabaseFactory;
use app\Core\{RenderView, FlashMessages};
use Symfony\Component\HttpFoundation\{Response, RedirectResponse, JsonResponse};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use app\Services\LogService;
use app\Shared\Adapters\SessionAdapter;
use libs\{JavaScript, Css, CSRF};

abstract class ApplicationController
{
    
    protected $appType = 'catalog_products';
	/**
	 * Instance of the Symfony\Component\HttpFoundation\Request;
	 */
	protected $http;
	
	/**
	 * app\Shared\Adapters\SessionAdapter;
	 */
	protected $session;
	
	/**
	 * Instance of the Symfony\Component\HttpFoundation\Response;
	 */
	protected $response;
	
	/**
	 * Application default cache system
	 * Instance of Symfony\Component\Cache\Adapter\FilesystemAdapter;
	 */
	protected $cache;

    /** 
     * Instance of app\Core\RenderView
     */
    protected $template;
	
	/**
	 * Instance of League\Container\Container
	 */
	protected $container;
	
	/** 
     * Instance of app\Core\FlashMessages
     */
	protected $flashMessages;
	
	/** 
     * Object instance
     */
	protected $config;
    
    /** 
     * Instance of libs\Javascript
     */
    protected $javaScript;
    
    /** 
     * Instance of libs\Css
     */
    protected $css;
	
	/**
	 * Linguagem do sistema
	 * Lista ISO 639-1 codes
	 */
	protected $language = 'pt-br';
	
	/**
	 * List of ISO_639-1 codes
	 *
	 * https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
	 */
	protected $locale = 'pt';
	
	/**
	 * Layout base
	 */
	protected $layout;
	
	/**
	 * Stores information about the loaded plugins
	 *
	 * @var array
	 */
	protected $loadedPlugins = [];
	
	protected $url = [];
	
	
    // Force Extending class to define this method
	//abstract protected function initHeader();
	//abstract protected function initFooter();
	
	
	protected function __construct ()
	{
		
		// Admin Urls
		$this->url = Config::load(BASE_DIR . '/app/config/urls.php');
		// Session handler
		$this->session = new SessionAdapter;
		// Global Request
		$this->http = Request::createFromGlobals();
		// Response
		$this->response = new Response;
		// Default Cache system
		$this->cache = new FilesystemAdapter('default.app.cache', 0, CACHE_DIR);
		// Renderizador do templante
		$this->template = new RenderView; 
		// Container
		$this->container = new Container;
		// Flash notifications
		$this->flashMessages = new FlashMessages;
        
        $this->javaScript = new Javascript;
        $this->css = new Css;
		
		// Carrega as classes principais no container
		$this->buildContainer();
		
	}
	
	/**
	 * Add secondary classes in the container
	 * @return void
	 */
	protected function buildContainer ()
	{
		$this->container->add('tools', '\app\Utils\Tools');
		$this->container->add('csrf', function () {
			return new CSRF($this->session);
		});
		$this->container->add('pdo', function (){
			$pdo = DatabaseFactory::create('pdo');
			$pdo->setDatabase(EM_DB_HOST, EM_DB_USER, EM_DB_PASSWORD, EM_DB_DATABASE);
			return $pdo->getConnection();
		});
        
        $this->container->add('serializer', function(){
           return SerializerBuilder::create()->build(); 
        });

	}
    
    protected function loadCss()
    {
        // Load other styles, if exists
        $cssFiles = $this->css->getFiles(); 
		if (count($cssFiles) > 0) {
			$css = '';
			foreach ($cssFiles as $file) {
				$css .= $file;
			}
           $this->template->CSS_FILES = $css;
		}
        // Load inline css
        $cssBlocks = $this->css->getBlockStyles();
        if (count($cssBlocks) > 0) {
            $style = '';
            foreach ($cssBlocks as $blocks) {
                $style .= $blocks;
            }
        } else {
            $style = '';
        }
        $this->template->CSS_BLOCKS = $style;
    }
    
    protected function loadJavaScript()
    {
		// Load Javascripts
        $jsFiles = $this->javaScript->getFiles();
		if (count($jsFiles) > 0) {
			$js = '';
			foreach ($jsFiles as $file) {
				$js .= $file;
			}	
			$this->template->JAVASCRIPT_FILES = $js;
		}
        //load blocks
        $blocks = $this->javaScript->getBlocks();
        if (count($blocks) > 0) {
            $script = '';
            foreach ($blocks as $block) {
                $script .= $block;
            }
        }else {
            $script = '';
        }
        $this->template->JAVASCRIPT_BLOCKS = $script;
	}
	
	protected function requirePlugin (string $pluginName)
	{
		
		$plugins = include(BASE_DIR . '/app/config/plugins.php');
		
		// Check if the plugin has been loaded  
		if (!isset($plugins[$pluginName])){
			LogService::record(
				sprintf("O plugin -%s- não foi encontrado na lista de plugins.", $pluginName), LogService::NOTICE
			);
			return false;
		}

		// Load CSS Files
		if (isset($plugins[$pluginName]['files']['css'])) {
            
			foreach ($plugins[$pluginName]['files']['css'] as $href => $cssAttributes) {
                
				$this->css->addFile($href, $cssAttributes);
			}
		}
		// Load Javascript Files
		if (isset($plugins[$pluginName]['files']['js'])) {
            
			foreach ($plugins[$pluginName]['files']['js'] as $src => $jsAttributes){
				$this->javaScript->addFile($src, $jsAttributes);
			}
		}
		
		$this->loadedPlugins[] = $pluginName;
		return true;
	}
	
	/**
	 * Check if a plugin has been loaded
	 */
	protected function pluginIsLoaded (string $plugin) : bool
	{
		return in_array($plugin, $this->loadedPlugins);
	}
	
	/**
	 * Return the locale
	 *
	 */
	protected function getLocale ()
	{
		 //return $this->http->request->getLocale();
	}
	
	protected function getLanguage ()
	{
		return 'pt-br';
	}
	
	protected function getCurrentUrl (bool $encode = false) : string 
	{
		$protocol = (isset($_SERVER['HTTPS']) ? "https" : "http");
		$url = $protocol .'://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
		if ($encode)
			return urlencode($url);
		else
			return $url;
	}
		
	
	protected function dispatchTemplate ()
	{
		// Print Flash Messages
		if ($this->flashMessages->hasMessages()) {
			if ($this->template->exists('FLASH_MESSAGES'))
				$this->template->FLASH_MESSAGES = $this->flashMessages->display(false, false);
		}
		$this->response($this->template->parse(), $this->response::HTTP_OK,  'text/html');
		die();
	}
    
    protected function minimizeHtmlOutput(string $html) : string 
    {
        $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
        $replace = array('>', '<', '\\1');
        if (preg_match("/\<html/i",$html) == 1 && preg_match("/\<\/html\>/i",$html) == 1) {
            $html = preg_replace($search, $replace, $html);
        }
        return $html;
    }
	
	/**
	 * Verifica se a requisição é ajax
	 * 
	 * @return bool
	 */
	protected function isAjaxRequest () : bool
	{
		$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : 'none';
		return (strtolower($isAjax) === 'xmlhttprequest');
	}
	
	/**
	 * HTTP HTML Response
	 *
	 * @param $content (mixed) - The content of application
	 * @param $contentType (string) - The MIME Type 
	 * @param $statusCode (int) - The HTTP Status Code
     * @param $compress (bool) - Compress the Html output
	 */
	protected function response(
        $content, 
        int $statusCode = 200, 
        string $contentType = 'text/html', 
        bool $compress = true
    ){
		if ($this->isAjaxRequest()) 
            $this->ajaxResponse($content, $statusCode);

		if (empty($content) || null === $content) {
            $statusCode = 204;
        } else {
           $content = $this->minimizeHtmlOutput($content);
        }

		$this->response->setContent($content);
		$this->response->setStatusCode($statusCode);
		$this->response->setCharset('UTF-8');
		$this->response->headers->set('Content-Type', $contentType);
		$this->response->send();
		die();
	}
	
	/**
	 * HTTP Response
	 *
	 * @param $content (mixed) - The content of application
	 * @param $content_type (string) - The MIME Type 
	 */
	protected function ajaxResponse($content, int $statusCode = 200) 
	{
		$serializer = $this->container->get('serializer');
		$this->response->setContent($serializer->serialize($content, 'json'));
		$this->response->setStatusCode($statusCode);
		$this->response->setCharset('UTF-8');
		$this->response->headers->set('Content-Type', 'application/json');
		$this->response->send();
		die();
	}
	
	/**
	 * Redirect
	 *
	 * @param $redirect (string) - The url of the destiny
	 */
	protected function redirect(?string $redirect = null, int $statusCode = 302, bool $force = false)
	{ 
		// Does not redirect if the source address is equal to the target address
		if ($_SERVER['REQUEST_URI'] === $redirect && false === $force)
			return false;
        if (null === $redirect) {
            $redirect = $_SERVER['REQUEST_URI'];
        }
        
		$response = new RedirectResponse($redirect, $statusCode);
		$response->send();
		die();
	}

}
