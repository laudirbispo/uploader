<?php declare(strict_types=1);
namespace app\Web\Controllers\Admin;

/**
 * @author - Laudir Bispo, laudirbispo@outlook.com
 * @copyright - 2017/2018
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

class AdminDashboard extends AdminController
{
	public function __cosntruct ()
	{
		parent::__construct();
	}
	
	public function index ()
	{
		//$this->show_footer = true;
		//$this->showSidebarMenuLeft = false;
		//$this->showNavbarTop = false;
		//$this->showSidebarSettingsRight = true;
		$this->pageTitle = 'Emobi Multitask - Dashboard';

		$this->template->addFile('CONTENT', ROOT_DIR.'/app/Web/Views/admin/pages/dashboard.tpl');
		$this->loadBreadcrumb(array('admin.home'));

		$this->display();
	}
	
}
