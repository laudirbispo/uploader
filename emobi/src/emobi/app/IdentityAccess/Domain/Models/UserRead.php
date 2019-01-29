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
use app\Shared\Contracts\Collectable;

class UserRead implements Collectable 
{
	/**
     * @var string
     */
	private $userId;
    
    /**
     * @object app\IdentityAccess\Domain\Models\Account;
     */
    private $account;
    
    /**
     * @object app\IdentityAccess\Domain\Models\Profile;
     */
    private $profile;
    
    /**
     * @object app\IdentityAccess\Domain\Models\GroupsCollection;
     */
    private $groups;
    
    public function __construct(string $userId) 
    {
        $this->userId = $userId;
        $this->account = new Account($userId);
        $this->profile = new Profile($userId);
    }
    
    public function id() : string 
    {
        return $this->userId;
    }
    
    public function getAccount() : Account
    {
        return $this->account;
    }
    
    public function getProfile() : Profile 
    {
        return $this->profile;
    }
    
    public function getGroups() : GroupsCollection 
    {
        return $this->groups->getAll();
    }
    
    public function setAccount(?Account $Account) : self 
    {
        $this->account = $Account;
        return $this;
    }
    
    public function setProfile(?Profile $Profile) : self 
    {
        $this->profile = $Profile;
        return $this;
    }
    
    public function setGroups(?GroupCollection $Groups) : self 
    {
        $this->groups = $Groups;
        return $this;
    }
    
}
