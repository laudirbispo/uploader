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
use libs\laudirbispo\CQRSES\Commands\Commanding;

final class ChangeProfileInfoCommand extends Commanding
{
    private $userId;
    
    private $firstName;
    
    private $lastName;
    
    private $about;
    
    private $gender;
    
    private $dateOfBirth;
    
    private $maritalStatus;
    
    public function __construct(
        $executedBy, 
        $userId, 
        $firstName, 
        $lastName, 
        $about, 
        $gender, 
        $dateOfBirth, 
        $maritalStatus
    ){
        $this->executedBy = $executedBy;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->about = $about;
        $this->gender = $gender;
        $this->dateOfBirth = $dateOfBirth;
        $this->maritalStatus = $maritalStatus;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function getFirstName() 
    {
        return $this->firstName;
    }
    
    public function getLastName() 
    {
        return $this->lastName;
    }
    
    public function getAbout() 
    {
        return $this->about;
    }
    
    public function getGender() 
    {
        return $this->gender;
    }
    
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }
    
}
