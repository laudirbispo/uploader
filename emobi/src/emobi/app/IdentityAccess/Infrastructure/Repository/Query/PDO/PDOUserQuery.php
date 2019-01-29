<?php declare(strict_types=1);
namespace app\IdentityAccess\Infrastructure\Repository\Query\PDO;
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
use app\IdentityAccess\Domain\Contracts\UserQueryRepository;
use app\Shared\Exceptions\{StorageException, DuplicateItemInTheStorage};
use app\IdentityAccess\Infrastructure\Exceptions\UserNotFound;
use app\IdentityAccess\Domain\Models\{
    UserRead, 
    GroupRead, 
    Profile, 
    Account, 
    UserCollection,
    GroupCollection
};

final class PDOUserQuery implements UserQueryRepository
{
	private $pdo;
	
	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}
	
	public function accountExistsWithEmail(string $email) : bool
	{
		$stmt = $this->pdo->prepare("SELECT count(*) FROM `accounts` WHERE `email` = :email");
		$stmt->execute([':email' => $email]);
		$count = $stmt->fetchColumn();
		return ((int) $count >= 1) ? true : false ;
	}
	
	public function accountExistsWithUsername(string $username) : bool
	{
		$stmt = $this->pdo->prepare("SELECT count(*) FROM `accounts` WHERE `username` = :username");
		$count = $stmt->execute([':username' => $username]);
		$count = $stmt->fetchColumn();
		return ((int) $count >= 1) ? true : false ;
	}
	
	public function getCompleteInformationFromAllUsers() : UserCollection
	{
		try {
            
            $UserCollection = new UserCollection('Lista de usuários completa');
            
            $stmt = $this->pdo->query("SELECT * FROM `accounts`");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($data as $row) {
                $User = new UserRead($row['userId']);
                $User->setAccount($this->getAccountById($row['userId']));
                $User->setProfile($this->getProfileById($row['userId']));
                $User->setGroups($this->getUserGroups($row['userId']));
                
                $UserCollection->addItem($row['userId'], $User);
            }

            return $UserCollection;
			
		} catch (PDOException $e) {
			throw new StorageException($e->getMessage());
		}

	}
    
    public function getAccountById(string $id) : ?Account
	{
		try {
			
			$stmt = $this->pdo->prepare("SELECT * FROM `accounts` WHERE `userId` = :userId LIMIT 1");
			$stmt->execute([':userId' => $id]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row) return null;
            
            $Account = new Account($row['userId']);
            $Account->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setUsername($row['username'])
                ->setRole($row['role'])
                ->setStatus($row['status'])
                ->setPin($row['pin'])
                ->setSecurityKey($row['security_key'])
                ->setLastPasswordChange($row['lastPasswordChange'])
                ->setCreated($row['created']);
            return $Account;
			
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
		
	}
    
    public function getProfileById(string $userId) : ?Profile 
    {
        try {
            
            $stmt = $this->pdo->prepare("SELECT * FROM `profiles` WHERE `userId` = :userId LIMIT 1");
            $stmt->execute([':userId' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $Profile = new Profile($userId);
            $Profile->setFirstName($row['firstName'])
                ->setLastName($row['lastName'])
                ->setPicture($row['picture'])
                ->setAbout($row['about'])
                ->setGender($row['gender'])
                ->setDateOfBirth($row['dateOfBirth'])
                ->setMaritalStatus($row['maritalStatus']);
            return $Profile;
            
        } catch(PDOException $e) {
            throw new StorageException($e->getMessage());
        }
    }
    
    
	
	public function getAccountByUsername(string $username)
	{
		try {
			
			$stmt = $this->pdo->prepare("SELECT * FROM `accounts` WHERE `username` = :username LIMIT 1");
			$stmt->execute([':username' => $username]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row) return false;
            
            $User = new UserRead($row['userId']);
            $User->getAccount()->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setUsername($row['username'])
                ->setRole($row['role'])
                ->setStatus($row['status'])
                ->setPin($row['pin'])
                ->setSecurityKey($row['security_key'])
                ->setLastPasswordChange($row['lastPasswordChange'])
                ->setCreated($row['created']);
            return $User;
			
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
		
	}
	
	public function getAccountByEmail(string $email) : UserRead
	{
		try {
			
			$stmt = $this->pdo->prepare("SELECT * FROM `accounts` WHERE `email` = :email");
			$stmt->execute([':email' => $email]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
            
			if (!$row) return false;
            
			$User = new UserRead($row['userId']);
            $User->getAccount()->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setUsername($row['username'])
                ->setRole($row['role'])
                ->setStatus($row['status'])
                ->setPin($row['pin'])
                ->setSecurityKey($row['secure_key'])
                ->setLastPasswordChange($row['lastPasswordChange'])
                ->setCreated($row['created']);
            return $User;
			
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
		
	}
    
    public function getAllGroups() : array 
	{
		try {
			
			$query = "SELECT * FROM `groups` ORDER BY `name` ASC";
			$data = $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
			$groups = [];
			
			foreach ($data as $row) {
				$Group = new GroupRead($row['group_id']);
                $Group->setName($row['name']) 
				    ->setDescription($row['description']) 
					->setStatus($row['status']) 
                    ->setPermissions($row['permissions']) 
					->setCreated($row['created']) 
					->setCreatedBy($row['created_by']);
                        
                $groups[] = $Group;
			}
			return $groups;
			
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
		
	}
    
    public function getUserGroups(string $userId)
	{
		try {
			
			$stmt = $this->pdo->prepare(
				"SELECT * FROM `groups` g
                RIGHT JOIN `user_groups_relationship` ug ON (g.`group_id` = ug.`groupId`)
                WHERE ug.`userId` = :userId"
			);
			$stmt->execute([':userId' => $userId]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $groups = [];
            
            $GroupCollection = new GroupCollection($userId);
			
			foreach ($data as $row) {
				$Group = new GroupRead($row['group_id']);
                $Group->setName($row['name']) 
				    ->setDescription($row['description']) 
					->setStatus($row['status']) 
                    ->setPermissions($row['permissions']) 
					->setCreated($row['created']) 
					->setCreatedBy($row['created_by']);
                        
                $GroupCollection->addItem($row['group_id'], $Group);
			}
			return $GroupCollection;
			
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
		
	}
    
    public function getGroupByUuid(string $uuid)
	{
		try {
			
			$stmt = $this->pdo->prepare("SELECT * FROM `groups` WHERE `group_id` = :group_id ORDER BY `name` ASC LIMIT 1");
			$stmt->execute([':group_id' => $uuid]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if (!$row || $stmt->rowCount() <= 0)
				return false;

			$Group = new GroupRead($row['group_id']);
            $Group->setName($row['name']) 
                ->setDescription($row['description']) 
                ->setStatus($row['status']) 
                ->setPermissions($row['permissions']) 
                ->setCreated($row['created']) 
                ->setCreatedBy($row['created_by']);
            return $Group;
			
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
		
	}
    
    public function groupExists(string $name) : bool 
	{
		try {
			
			$stmt = $this->pdo->prepare("SELECT count(*) FROM `groups` WHERE `name` = :name");
			$stmt->execute([':name' => $name]);
			$count = $stmt->fetchColumn();
			return ((int) $count >= 1) ? true : false ;
			
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
	}
	
	public function getPermissionsByUserId(string $userId) : array  
	{
		try {
			
			$stmt = $this->pdo->prepare(
				"SELECT g.`permissions` FROM `groups` g
                RIGHT JOIN `user_groups_relationship` ug ON (g.`group_id` = ug.`groupId`)
                WHERE ug.`userId` = :user_id"
			);
            $stmt->execute([':user_id' => $userId]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $perms = [];
            foreach ($data as $row) {
                $perms[] = $row['permissions'];
            }
			return $perms;
            
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
		
	}
    
    public function getRoleById(string $id) : string 
    {
        try {
            
			$stmt = $this->pdo->prepare("SELECT `role` FROM `accounts` WHERE `userId` = :userId");
            $stmt->execute([':userId' => $id]);
            return $stmt->fetchColumn();
            
		} catch (PDOException $e){
			throw new StorageException($e->getMessage());
		}
    }
	
}
