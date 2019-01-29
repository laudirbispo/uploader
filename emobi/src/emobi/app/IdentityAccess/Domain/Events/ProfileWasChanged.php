<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Events;
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
use libs\laudirbispo\CQRSES\Events\AbstractEvent;

final class ProfileWasChanged extends AbstractEvent
{
    private $firstName;
    private $lastName;
    private $gender;
    private $about;
    private $dateOfBirth;
    private $maritalStatus;
    
    public function __construct(
        string $executedBy, 
        string $aggregateId, 
        string $firstName, 
        string $lastName, 
        string $gender, 
        ?string $about = null,
        ?string $dateOfBirth = null,
        ?string $maritalStatus = null
    ){
        $this->executedBy = $executedBy;
        $this->aggregateId = $aggregateId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->about = $about;
        $this->dateOfBirth = $dateOfBirth;
        $this->maritalStatus = $maritalStatus;
    }
    
    public function getFirstName() : string 
    {
        return $this->firstName;
    }
    
    public function getLastName() : string 
    {
        return $this->lastName;
    }
    
    public function getGender() : string 
    {
        return $this->gender;
    }
    
    public function getAbout() : ?string
    {
        return $this->about;
    }
    
    public function getDateOfBirth() : ?string 
    {
        return $this->dateOfBirth;
    }
    
    public function getMaritalStatus() : ?string
    {
        return $this->maritalStatus;
    }
          
}
