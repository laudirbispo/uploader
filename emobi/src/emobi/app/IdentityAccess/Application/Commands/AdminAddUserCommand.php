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
use libs\laudirbispo\CQRSES\Commands\Commandable;

class AdminAddUserCommand implements Commandable 
{
    /** who triggered the process **/
    private $executedBy;
	private $username;
	private $firstName;
	private $lastName;
	private $email;
    private $hashedPassword;
	private $role;
    private $picture;
    private $gender;
    private $groups = [];
	
	public function __construct(
        string $executedBy,
        $username, 
        $firstName, 
        $lastName, 
        $email,
        $hashedPassword,
        $role,
        $gender,
        array $groups // se deve ter type hinting
    ){
        $this->eventDescription = "Um novo usuário foi cadastrado pelo administrador.";
        $this->executedBy = $executedBy;
		$this->username = $username;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
        $this->hashedPassword = $hashedPassword;
		$this->role = $role;
        $this->gender = $gender;
        $this->groups = $groups;
	}
	
	public function getUsername ()
	{
		return $this->username;
	}
	
	public function getFirstName ()
	{
		return $this->firstName;
	}
	
	public function getLastName ()
	{
		return $this->lastName;
	}
	
	public function getEmail ()
	{
		return $this->email;
	}
    
    public function getHashedPassword() 
    {
        return $this->hashedPassword;
    }
	
	public function getRole ()
	{
		return $this->role;
	}
    
    public function getGender()
    {
        return $this->gender;
    }
    
    public function getGroups()
    {
        return $this->groups;
    }
    
    public function executedBy() : string
    {
        return $this->executedBy;
    }
		
}
