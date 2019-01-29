<?php declare(strict_types=1);
namespace app\IdentityAccess\Application\Services;
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
use app\IdentityAccess\Domain\Contracts\UserQueryRepository;
use app\IdentityAccess\Infrastructure\{
    Repository\UserRepository,
    Services\PasswordHashService
};
use PHPMailer\PHPMailer\PHPMailer;
use app\Shared\Models\MailConfiguration;
use app\IdentityAccess\Domain\Models\{UserId, Password, HashedPassword};
use app\IdentityAccess\Domain\Models\User;
use app\Shared\Exceptions\{DomainDataException, StorageException};

class ChangePasswordService 
{
    private $executedBy;
    
    private $userRepository;
    
    private $userQueryRepository;
    
    private $passwordHashService;
    
    private $mailerConfiguration;
    
    private $mailer;
    
    private $error;
    
    public function __construct(
        string $executedBy,
        UserRepository $UserRepository,
        UserQueryRepository $UserQueryRepository,
        MailConfiguration $MailConfiguration
    ){
        $this->executedBy = $executedBy;
        $this->userRepository = $UserRepository;
        $this->userQueryRepository = $UserQueryRepository;
        $this->mailerConfiguration = $MailConfiguration;
        $this->mailer = new PHPMailer();
    }
    
    public function execute($userId, $oldPass, $newPass) 
    {
            
        $UserId = new UserId($userId);
        $Account = $this->userQueryRepository->getAccountById($UserId->get());
        if (null === $Account) {
            throw new DomainDataException('Usuário não encontrado', StorageException::ITEM_NOT_FOUND);
        }

        /**
         * NOTE: 
         * You may want to implement account status checking.
         */ 

        $OldPassword = new Password($oldPass);
        $CurrentPassword = new HashedPassword($Account->getPassword());

        $PasswordHashService = new PasswordHashService($OldPassword);
        if (!$PasswordHashService->verify($CurrentPassword)) {
            throw new DomainDataException("Senha incorreta");
        }

        $NewPasswordHash = new PasswordHashService(new Password($newPass));
        $NewPasswordHash = new HashedPassword($NewPasswordHash->hash());

        $aggregateHistory = $this->userRepository->get($UserId);
        $UserModel = User::reconstituteRecordedEvents($aggregateHistory);
        $UserModel->changePassword(
            $this->executedBy,
            $NewPasswordHash
        );
        $this->userRepository->save($UserModel);
        return true;
        
    }
    
    public function sendNotificationByEmail(string $email) : void
    {
        $Mailer = new PHPMailer();
        $Mailer->host($this->mailerConfiguration->getHost());
    }
    
    
    public function getError() : ?string 
    {
        return $this->error;
    }
    
}
