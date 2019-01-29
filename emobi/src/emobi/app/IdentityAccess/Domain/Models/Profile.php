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

final class Profile 
{
    private $userId;
    
    private $firstName;
    
    private $lastName;
    
    private $picture;
    
	private $gender;
    
    private $dateOfBirth;
    
    private $maritalStatus;
    
    private $about;
    
    public function __construct(string $userId) 
    {
        $this->userId = $userId;
    }
    
    public function setFirstName(string $firstName) 
    {
        $this->firstName = $firstName;
        return $this;
    }
    
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
    
    public function setPicture(?string $picture) 
    {
        $this->picture = $picture;
        return $this;
    }
    
    public function setGender(string $gender) 
    {
        $this->gender = $gender;
        return $this;
    }
    
    public function setDateOfBirth(?string $date) 
    {
        $this->dateOfBirth = $date;
        return $this;
    }
    
    public function setMaritalStatus(?string $state) 
    {
        $this->maritalStatus = $state;
        return $this;
    }
    
    public function setAbout(?string $about) 
    {
        $this->about = $about;
        return $this;
    }
    
    public function getFirstName() : string 
    {
        return $this->firstName;
    }
    
    public function getLastName() : string 
    {
        return $this->lastName;
    }
    
    public function getFullname() : string 
    {
        return $this->firstName . ' ' . $this->lastName;
    }
    
    public function getPicture() : ?string 
    {
        return $this->picture;
    }
    
    public function getGender() : ?string 
    {
        return $this->gender;
    }
    
    public function getDateOfBirth() : ?string 
    {
        return $this->dateOfBirth;
        
    }
    
    public function getMaritalStatus() : ?string 
    {
        return $this->maritalStatus;
    }
    
    public function getAbout() : ?string 
    {
        return $this->about;
    }
    
}