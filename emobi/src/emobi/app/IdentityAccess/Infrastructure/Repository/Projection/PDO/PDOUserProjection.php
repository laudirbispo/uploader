<?php declare(strict_types=1);
namespace app\IdentityAccess\Infrastructure\Repository\Projection\PDO;
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
use PDO;
use DateTimeImmutable;
use libs\laudirbispo\CQRSES\Infrastructure\BaseProjection;
use app\IdentityAccess\Domain\Contracts\UserProjectionRepository;
use app\IdentityAccess\Domain\Events\{
    GroupWasCreated, 
    GroupWasChanged, 
    GroupStatusWasChanged, 
    GroupPermissionsWasChanged,
    AccountWasCreatedByAdmin,
    ProfileWasCreated,
    ProfileWasChanged,
    UserWasAddedToTheGroup,
    PasswordWasChanged
};
use app\Shared\Exceptions\StorageException;

class PDOUserProjection extends BaseProjection implements UserProjectionRepository
{
	private $pdo;
	
	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}
    
    public function projectGroupWasCreated(GroupWasCreated $Event)
	{
		try {
			
			$stmt = $this->pdo->prepare(
				"INSERT INTO `groups` (`group_id`, `name`, `description`, `status`, `created`, `created_by`) 
				VALUES (:group_id, :name, :description, :status, :created, :created_by)"
			);
			$stmt->execute([
				':group_id' => $Event->getAggregateId(),
				':name' => $Event->getName(),
				':description' => $Event->getDescription(),
				':status' => $Event->getStatus(),
				':created' => $Event->occurredOn(),
				':created_by' => $Event->getCreatedBy()
			]);
			
		} catch (\PDOException $e) {
			throw new StorageException($e->getMessage());
		}
		
	}
    
    public function projectGroupWasChanged(GroupWasChanged $Event)
    {
        try {
            
            $stmt = $this->pdo->prepare(
                'UPDATE `groups` 
                SET `name` = :name, `description` = :description , `status` = :status
                WHERE `group_id` = :group_id'
            );
            $stmt->execute([
                ':name' => $Event->getName(),
                ':description' => $Event->getDescription(),
                ':status' => $Event->getStatus(),
                ':group_id' => $Event->getAggregateId()
            ]);
            
        } catch (\PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
    
    public function projectGroupPermissionsWasChanged(GroupPermissionsWasChanged $Event) : void
    {
        try {
            
            $stmt = $this->pdo->prepare(
                "UPDATE `groups` 
                SET `permissions` = :permissions
                WHERE `group_id` = :group_id"
            );
            
            $stmt->execute([
                ':permissions' => $Event->getPermissions(), 
                ':group_id' => $Event->getAggregateId()
            ]);
            
        } catch (\PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
	
    public function projectAccountWasCreatedByAdmin(AccountWasCreatedByAdmin $Event) : void
    {
        try {
            
            $stmt = $this->pdo->prepare(
                "INSERT INTO `accounts` 
                (`userId`, `username`, `email`, `password`, `role`, `status`, `security_key`, `created`)
                VALUES 
                (:userId, :username, :email, :password, :role, :status, :security_key, :created)"
            );
            $stmt->execute([
                ':userId' => $Event->getAggregateId(),
                ':username' => $Event->getUsername(),
                ':email' => $Event->getEmail(),
                ':password' => $Event->getPassword(),
                ':role' => $Event->getRole(),
                ':status' => $Event->getStatus(),
                ':security_key' => $Event->getSecurityKey(),
                'created' => $Event->getCreated()
            ]);

            
        } catch (\PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
    
    public function projectProfileWasCreated(ProfileWasCreated $Event) : void 
    {
        try {
            
            $stmt = $this->pdo->prepare(
                "INSERT INTO `profiles` 
                (`userId`, `firstName`, `lastName`, `picture`, `about`, `gender`, `dateOfBirth`, `maritalStatus`)
                VALUES 
                (:userId, :firstName, :lastName, :picture, :about, :gender, :dateOfBirth, :maritalStatus)"
            );
            $stmt->execute([
                ':userId' => $Event->getAggregateId(),
                ':firstName' => $Event->getFirstName(),
                ':lastName' => $Event->getLastName(),
                ':picture' => $Event->getPicture(),
                ':about' => $Event->getAbout(),
                ':gender' => $Event->getGender(),
                ':dateOfBirth' => $Event->getDateOfBirth(),
                ':maritalStatus' => $Event->getMaritalStatus()
            ]);

            
        } catch (\PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
    
    public function projectUserWasAddedToTheGroup(UserWasAddedToTheGroup $Event) : void 
    {
        try {
            
            $stmt = $this->pdo->prepare(
                "INSERT INTO `user_groups_relationship`
                (`userId`, `groupId`)
                VALUES 
                (:userId, :groupId)"
            );
            $stmt->execute([
                ':userId' => $Event->getAggregateId(),
                ':groupId' => $Event->getGroupId()
            ]);

            
        } catch (\PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
    
    public function projectProfileWasChanged(ProfileWasChanged $Event) : void 
    {
        try {
            
            $stmt = $this->pdo->prepare("
                UPDATE `profiles` 
                SET `firstName` = :firstName, `lastName` = :lastName, `about` = :about, `gender` = :gender, `dateOfBirth` = :dateOfBirth, `maritalStatus` = :maritalStatus
                WHERE `userId` = :userId
            ");
            $stmt->execute([
                ':firstName' => $Event->getFirstName(),
                ':lastName' => $Event->getLastName(),
                ':about' => $Event->getAbout(),
                ':gender' => $Event->getGender(),
                ':dateOfBirth' => $Event->getDateOfBirth(),
                ':maritalStatus' => $Event->getMaritalStatus(),
                ':userId' => $Event->getAggregateId()
            ]);
            
        } catch (\PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
    
    public function projectPasswordWasChanged(PasswordWasChanged $Event) : void
    {
        try {
            
            $stmt = $this->pdo->prepare(
                "UPDATE `accounts` 
                SET `password` = :password, `lastPasswordChange` = :lastPasswordChange
                WHERE `userId` = :userId"
            );
            $stmt->execute([
                ':password' => $Event->getHashedPassword(),
                ':lastPasswordChange' => $Event->occurredOn(),
                ':userId' => $Event->getAggregateId() 
            ]);
            
        } catch (\PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
	
}
