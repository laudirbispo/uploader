<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Contracts;
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
use app\IdentityAccess\Domain\Models\{
    Profile,
    UserCollection
};

interface UserQueryRepository
{
	public function getAccountByEmail(string $email);
	public function getAccountByUsername(string $username);
	public function accountExistsWithEmail(string $email);
	public function accountExistsWithUsername(string $username);
    public function getAllGroups() : array;
	public function groupExists(string $name) : bool;
	public function getGroupByUuid(string $uuid);
    public function getPermissionsByUserId(string $uuid) : array;
    public function getRoleById(string $id) : string;
    public function getCompleteInformationFromAllUsers() : UserCollection;
    public function getProfileById(string $userId) : ?Profile;
}
