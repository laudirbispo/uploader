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
use DateTime;
use app\IdentityAccess\Domain\Models\User;
use app\IdentityAccess\Domain\Models\{
    UserId, 
    GroupId, 
    Username, 
    SecurityKey, 
    Role, 
    AccountStatus, 
    HashedPassword
};
use app\Shared\Models\{Gender, EmailAddress, Name, Picture};
use libs\laudirbispo\CQRSES\Contracts\Repository;
	
final class AdminAddUserHandler 
{
	private $userRepository;
	
	public function __construct(Repository $userRepository)
	{
		$this->userRepository = $userRepository;
	}
	
	public function execute(AdminAddUserCommand $Command)
	{
		$User = User::adminAddNew(
            $Command->executedBy(),
			UserId::generate(),
			new Username($Command->getUsername()),
            new EmailAddress($Command->getEmail()),
            new HashedPassword($Command->getHashedPassword()),
			new Role($Command->getRole()),
            new AccountStatus('active'),
            SecurityKey::generate(),
            new DateTime('now'),
            new Name($Command->getFirstName(), $Command->getLastName()),
            new Gender($Command->getGender()),
            $Command->getGroups()
		);
		$this->userRepository->save($User);
		return $User;
	}
}
