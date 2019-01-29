<?php declare(strict_types=1);
namespace app\IdentityAccess\Infrastructure\Services;
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
use app\IdentityAccess\Domain\Contracts\HashingPassword;
use app\IdentityAccess\Domain\Models\{Password, HashedPassword};

final class PasswordHashService implements HashingPassword
{
	/**
	 * @var int
	 */
	const COST = 12;
	
	/**
	 * @var const
	 */
	const ALGO = PASSWORD_BCRYPT;
	
	/**
	 * @instance of Password class
	 */
	private $password;
	
	public function __construct(Password $password)
	{
		$this->password = $password;
	}
	
	public function hash(int $cost = 12) : string
	{	
		return password_hash($this->password->get(), PASSWORD_BCRYPT, ['cost' => $cost]);
	}
	
	public function verify(HashedPassword $hash) : bool 
	{
        return password_verify($this->password->get(), $hash->get());
    }

}
