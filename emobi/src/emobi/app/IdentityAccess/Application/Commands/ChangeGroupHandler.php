<?php declare(strict_types=1);
namespace app\IdentityAccess\Application\Commands;
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
use app\IdentityAccess\Domain\Models\Group;
use libs\laudirbispo\CQRSES\Contracts\Repository;
use app\IdentityAccess\Domain\Models\{GroupId, GroupName, GroupDescription, GroupStatus};

final class ChangeGroupHandler 
{
	private $userRepository;
	
	public function __construct(Repository $UserRepository)
	{
		$this->userRepository = $UserRepository;
	}
	
	public function handler(ChangeGroupCommand $Command)
	{
		$GroupId = GroupId::fromString($Command->getUuid());
		$GroupHistory = $this->userRepository->get($GroupId);
		$GroupModel = Group::reconstituteRecordedEvents($GroupHistory);
		$GroupModel->change(
            new GroupName($Command->getName()), 
            new GroupDescription($Command->getDescription()),
            new GroupStatus($Command->getStatus())
        );
        // Save 
        $this->userRepository->save($GroupModel);
        
		return $GroupModel;
	}
	
}