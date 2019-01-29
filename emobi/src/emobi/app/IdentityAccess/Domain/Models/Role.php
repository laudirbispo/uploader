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
use app\Shared\Exceptions\DomainDataException;

final class Role 
{
	private $role;
	
	private $allowed_roles = ['suporte', 'superadmin', 'admin', 'assistant', 'client', 'student', 'teacher', 'demo'];
	
	public function __construct (string $role)
	{
		$this->assertNotEmpty($role);
		$this->assertValidRole($role);
		$this->role = $role;
	}
	
	private function assertNotEmpty ($role)
	{
		if (empty($role))
			throw new DomainDataException(
                'É preciso fornecer uma função para o usuário.', 
                DomainDataException::EMPTY_ARGUMENT
            );
        
	}
	
	private function assertValidRole ($role) 
	{
		if (!in_array($role, $this->allowed_roles))
			throw new DomainDataException(
                sprintf("%s não é um tipo de usuário válido", $role),
                DomainDataException::INVALID_ARGUMENT
        );
	}
	
	public function get() : string
	{
		return (string) $this->role;
	}
	
	public function __toString () : string
	{
		return (string) $this->role;
	}
	
}
