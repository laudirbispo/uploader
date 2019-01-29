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

class GroupRead implements Collectable
{
	/**
     * @var string
     */
	private $id;
	
	/**
     * @var string
     */
	private $name;
	
	/**
     * @var mixed - string or null
     */
	private $description;
	
	/**
     * @var string
     */
	private $status;
    
    /**
     * @var json string
     */
	private $permissions;
	
	/**
     * @var string
     */
	private $created;
	
	/**
     * @var string
     */
	private $createdBy;
	
	public function __construct(string $id)
    {
		$this->id = $id;
	}
	
	public function getId() : string 
	{
		return $this->id;
	}
	
	public function getName() : string 
	{
		return $this->name;
	}
	
	public function getDescription() : string 
	{
		return (null === $this->description) ? '' : $this->description;	
	}
	
	public function getStatus() : string
	{
		return $this->status;
	}
    
    public function getPermissions() : ?string
	{
		return $this->permissions ;	
	}
	
	public function getCreated() : string 
	{
		return $this->created;
	}
	
	public function getCreatedBy() : string 
	{
		return $this->createdBy;
	}
    
    public function setName(string $name) : self 
    {
        $this->name = $name;
        return $this;
    }
    
    public function setDescription(?string $description = null) : self
    {
        if (null === $description) {
            $this->description = '';
        } else {
            $this->description = $description;
        }
        return $this;
    }
	
    public function setStatus(string $status) : self 
    {
        $this->status = $status;
        return $this;
    }
    
    public function setPermissions(?string $permissions = '') : self 
    {
        $this->permissions = $permissions;
        return $this;
    }
    
    public function setCreated(string $date) : self 
    {
        $this->created = $date;
        return $this;
    }
    
    public function setCreatedBy(string $userId) : self 
    {
        $this->createdBy = $userId;
        return $this;
    }
    
}
