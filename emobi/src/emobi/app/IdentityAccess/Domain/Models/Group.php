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
use DateTime, DateTimeImmutable;
use libs\laudirbispo\CQRSES\{AggregateRoot, AggregateHistory};
use libs\laudirbispo\CQRSES\Contracts\{Aggregable, EventIsSourced};
use app\IdentityAccess\Domain\Models\{UserId, GroupId, GroupName, GroupDescription, GroupStatus, GroupPermissions};
use app\IdentityAccess\Domain\Events\{GroupWasCreated, GroupWasChanged, GroupStatusWasChanged, GroupPermissionsWasChanged};

class Group extends AggregateRoot implements Aggregable, EventIsSourced
{
	private $uuid;
	
	private $name;
	
	private $description;
	
	private $status;
    
    private $permissions;
	
	private $created;
	
	private $createdBy;
	
	public function __construct (string $groupId) 
    {
		$this->uuid = $groupId;
	}
	
	/** ------ Implementation of the EventSourced  ------- */
	public static function reconstituteRecordedEvents (AggregateHistory $AggregateHistory)
    {
        $Group = self::createEmptyGroup($AggregateHistory->getAggregateId());
		
        foreach ($AggregateHistory->getEvents() as $Event) {
            $Group->apply($Event);
        }

        return $Group;
    }
	
	private static function createEmptyGroup($groupId)
	{
		return new Group($groupId);
	}
	
	/** ---------------- Apply Functions ------------------- */
	protected function applyGroupWasCreated(GroupWasCreated $Event)
	{
		$this->name = $Event->getName();
		$this->description = $Event->getDescription();
		$this->status = $Event->getStatus();
        $this->permissions = $Event->getPermissions();
		$this->created = $Event->getCreated();
		$this->createdBy = $Event->getCreatedBy();
	}

	protected function applyGroupWasChanged(GroupWasChanged $Event) 
	{
		$this->name = $Event->getName();
		$this->description = $Event->getDescription();
        $this->status = $Event->getStatus();
	}
    
    protected function applyGroupPermissionsWasChanged(GroupPermissionsWasChanged $Event) 
    {
        $this->permissions = $Event->getPermissions();
    }
	
	/** ---------------- Business Logic ------------------- */
	public static function create(
		GroupId $GroupId, 
		GroupName $GroupName, 
		GroupDescription $GroupDescription, 
		GroupStatus $GroupStatus,
		DateTime $Created, 
		UserId $CreatedBy
	) {
		
		$Group = new Group($GroupId->get());
		
		$Group->applyAndRecordThat(
			new GroupWasCreated(
				$GroupId->get(), 
				$GroupName->get(), 
				$GroupDescription->get(), 
				$GroupStatus->get(),
                '',
				$Created->format('Y-m-d H:i:s'), 
				$CreatedBy->get()
			)
		);
		
		return $Group;
	}
	
	public function change(GroupName $GroupName, GroupDescription $GroupDescription, GroupStatus $GroupStatus) 
	{
		$this->applyAndRecordThat(
			new GroupWasChanged(
				$this->uuid, 
				(string) $GroupName, 
				(string) $GroupDescription,
                (string) $GroupStatus
			)
		);
        
	}
    
    public function changePermissions(GroupPermissions $GroupPermissions) 
    {
        $this->applyAndRecordThat(
            new GroupPermissionsWasChanged($this->uuid, (string) $GroupPermissions)
        );
    }
		
}