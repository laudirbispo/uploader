<?php declare(strict_types=1);
namespace app\IdentityAccess\Infrastructure\Services;
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
use app\IdentityAccess\Domain\Contracts\UserQueryRepository;
use app\IdentityAccess\Domain\Models\Name;

class GenerateUniqueUsernameService 
{
	/**
	 * @instance of implementing a query repository UserQueryRepository
	 */
	private $userQueryRepository;
	
	public function __construct (UserQueryRepository $UserQueryRepository)
	{
		$this->userQueryRepository = $UserQueryRepository;
	}
	
	/**
	 * Generate username using first and last name
	 *
	 */
	public function execute(Name $name, bool $numbers = false)
	{
		$full_name = $name->fullName();
        $username_parts = array_filter(explode(" ", strtolower($full_name))); 
        $username_parts = array_slice($username_parts, 0, count($username_parts)); 

        $first_name = (!empty($username_parts[0])) ? substr($username_parts[0], 0, 10) : "";
		if (isset($username_parts[2]))
			$last_name = $username_parts[1].$username_parts[2];
		else
			$last_name = $username_parts[1];
	
        $last_name = substr($last_name, 0, 6); 
	
        $numbers = ($numbers) ? rand(1, 99) : "";
        
        $username = $first_name. str_shuffle($last_name) . $numbers; 
        return $username;
	
	}
	
}
