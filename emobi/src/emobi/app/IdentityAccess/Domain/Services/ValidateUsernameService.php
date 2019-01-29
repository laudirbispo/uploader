<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Services;

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
use app\IdentityAccess\Domain\Models\Username;
use app\IdentityAccess\Specification\UsernameIsUnique;
use app\Shared\Exceptions\DomainDataException;
use app\IdentityAccess\Domain\Contracts\UserQueryRepository;

final class ValidateUsernameService 
{
    private $userQueryRepository;
    
    private $error;
    
    public function __construct(UserQueryRepository $UserQueryRepository)
    {
        $this->userQueryRepository = $UserQueryRepository;
    }
    
    public function execute($username) : bool 
    {
        try {
            new Username($username);
        } catch (DomainDataException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        
        $Specification = new UsernameIsUnique($this->userQueryRepository);
        if (!$Specification->isSatisfiedBy($username)) {
            $this->error = "Este username já está sendo usado por outro usuário";
            return false;
        } 
        return true;
        
    }
    
    private function verifyThatTheUsernameIsAlreadyRegistered($username) 
    {
         return $this->userQueryRepository->accountExistsWithUsername($username);      
    }
    
    public function getError() : string 
    {
        return $this->error;
    }

}
