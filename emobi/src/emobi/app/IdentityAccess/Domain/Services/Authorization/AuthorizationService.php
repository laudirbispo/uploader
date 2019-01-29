<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Services\Authorization;
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

class AuthorizationService
{
	private $userQueryRepository;
    
    private $userId;
    
    private $userRole;
    
    private $permissions = [];
    
	public function __construct (UserQueryRepository $UserQueryRepository, string $userId, string $userRole)
	{
		$this->userQueryRepository = $UserQueryRepository;
        $this->userId = $userId;
        $this->userRole = $userRole;
        $this->loadPermissions();
	}
    
    private function loadPermissions()
    {
        $permsGroups = $this->userQueryRepository->getPermissionsByUserId($this->userId);
        foreach ($permsGroups as $key) {
            $perms = json_decode($key);
            foreach ($perms as $task) {
                $this->permissions[] = $task;
            }
        }
    }
    
    public function getUserRole() : string 
    {
        return (string) $this->userRole;
    }
    
    public function authorizeRoles(array $roles = []) : bool 
    {
        return (in_array($this->userRole, $roles));
    }
    
    public function authorizeActivity(string $activity)
    {
        return in_array($activity, $this->permissions);
    }
    
    public function getPermissions() : array 
    {
        return $this->permissions;
    }
	
}
