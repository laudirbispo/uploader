<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Models;
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
use libs\laudirbispo\CQRSES\{AggregateRoot, AggregateHistory};
use libs\laudirbispo\CQRSES\Contracts\{Aggregable, EventIsSourced};
use app\IdentityAccess\Domain\Models\{
    UserId,
    GroupId,
    Username,
    SecurityKey,
    HashedPassword,
    Role,
    HashedPin,
    GroupCollection
};
use app\Shared\Models\{Gender, EmailAddress, Name, Picture, Maritalstatus};
use app\IdentityAccess\Domain\Events\{
    AccountWasCreatedByAdmin,
    ProfileWasCreated,
    UserWasAddedToTheGroup,
    ProfileWasChanged,
    PasswordWasChanged
};

class User extends AggregateRoot implements Aggregable, EventIsSourced
{
    /**
     * @var 
     */
	private $id;
    
    /**
     * Instance of app\IdentityAccess\Domain\Models\Account;
     */
    private $account;
    
    /**
     * Instance of app\IdentityAccess\Domain\Models\Profile;
     */
    private $profile;
    
	private $groups = [];
	
	public function __construct(UserId $UserId)
	{
		$this->id = $UserId->id();
        $this->account = new Account($UserId->id());
        $this->profile = new Profile($UserId->id());
	}
    
    /** ------ Implementation of the EventSourced  ------- */
	public static function reconstituteRecordedEvents(AggregateHistory $AggregateHistory)
    {
        $User = self::createEmptyUser($AggregateHistory->getAggregateId());

        foreach ($AggregateHistory->getEvents() as $Event) {
            $User->apply($Event);
        }

        return $User;
    }
    
    private static function createEmptyUser($userId)
	{
		return new User(new UserId($userId));
	}
	
	/** --------------- Execute events ------------- */
	public static function adminAddNew(
        string $executedBy,
        UserId $UserId, 
        Username $Username, 
        EmailAddress $Email, 
        HashedPassword $HashedPasssword,
        Role $Role,
        AccountStatus $AccountStatus,
        SecurityKey $SecurityKey,
        DateTime $Created,
        Name $Name,
        Gender $Gender,
        array $groups
    ){
		$User = new User($UserId);
        
        $User->applyAndRecordThat( 
            new AccountWasCreatedByAdmin(
                $executedBy,  
                (string) $UserId,
                (string) $Username,
                (string) $Email,
                (string) $HashedPasssword,
                (string) $Role,
                (string) $AccountStatus,
                (string) $SecurityKey,
                (string) $Created->format('Y-m-d H:i:s')
            )
        );
        
        $User->applyAndRecordThat(
            new ProfileWasCreated(
                $executedBy,
                $UserId->id(),
                $Name->firstName(),
                $Name->lastName(),
                $Gender->get()
            )
        );
        
        if (count($groups) > 0) {
            foreach ($groups as $group) {
                $User->applyAndRecordThat(
                    new UserWasAddedToTheGroup($UserId->id(), $group)
                );
            }
            
        }

        return $User;
	}
    
    
    public function changeProfileInformation(
        string $executedBy, 
        Name $Name, 
        About $About, 
        Gender $Gender,
        ?DateTimeImmutable $DateOfBirth,
        MaritalStatus $MaritalStatus
    ){
        
        $this->applyAndRecordThat(
            new ProfileWasChanged(
                $executedBy,
                $this->id,
                $Name->firstName(),
                $Name->lastName(),
                $Gender->get(),
                $About->get(),
                null,
                $MaritalStatus->get()
            )
        );
        
    }
    
    public function changePassword(string $executedBy, HashedPassword $HashedPassword) 
    {
        $this->applyAndRecordThat(
            new PasswordWasChanged($executedBy, $this->id, $HashedPassword->get())
        );
    }
    
    /** ---------------- Apply functions ------------------- */
    public function applyAccountWasCreatedByAdmin(AccountWasCreatedByAdmin $Event) : void
    {
        $this->account->setUsername($Event->getUsername())
            ->setEmail($Event->getEmail())
            ->setRole($Event->getRole())
            ->setStatus($Event->getStatus())
            ->setSecurityKey($Event->getSecurityKey())
            ->setCreated($Event->getCreated());
    }
    
    public function applyProfileWasCreated(ProfileWasCreated $Event) : void 
    {
        $this->profile->setFirstName($Event->getFirstName())
            ->setLastName($Event->getLastName())
            ->setGender($Event->getGender())
            ->setPicture($Event->getPicture())
            ->setAbout($Event->getAbout())
            ->setDateOfBirth($Event->getDateOfBirth())
            ->setMaritalStatus($Event->getMaritalStatus());
        return;
    }
    
    public function applyUserWasAddedToTheGroup(UserWasAddedToTheGroup $Event) : void 
    {
        if (!in_array($Event->getGroupId(), $this->groups)) {
            $this->groups[] = $Event->getGroupId();
        }
        return;
    }

    public function applyProfileWasChanged(ProfileWasChanged $Event) : void
    {
        $this->profile->setFirstName($Event->getFirstName())
            ->setLastName($Event->getLastName())
            ->setAbout($Event->getAbout())
            ->setGender($Event->getGender())
            ->setDateOfBirth($Event->getDateOfBirth())
            ->setMaritalStatus($Event->getMaritalStatus());
        return;
    }
    
    public function applyPasswordWasChanged(PasswordWasChanged $Event) : void 
    {
        $this->account->setPassword($Event->getHashedPassword())
            ->setLastPasswordChange($Event->occurredOn());
        return;
    }
    
}
