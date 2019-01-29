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
use libs\laudirbispo\CQRSES\Contracts\{DomainEvent, EventListener};
use app\IdentityAccess\Domain\Events\UserWasCreated;
use PHPMailer\PHPMailer\PHPMailer;
use app\Core\RenderView;

final class SendPasswordByEmailWhenAUserWasCreated implements EventListener
{
    private $mailer;
    
    private $password;
    
    public function __construct(PHPMailer $Mailer, $password) 
    {
        $this->mailer = $Mailer;
        $this->password = $password;
    }
    
    public function handler(DomainEvent $Event) 
    {
        $Template = new RenderView;
        $Template->layout(ROOT_DIR.'/themes/mail/account/active_account.tpl', true);
        $Template->USERNAME = $Event->getUsername();
        $Template->EMAIL = $Event->getEmail();
        $Template->PASSWORD = $this->password;
        $Template->LINK_LOGIN = 'http://emobi.com/admin/login';
        //Recipients 
        $this->mailer->addAddress($Event->getEmail()); 
        //Content
        $this->mailer->isHTML(true);                           
        $this->mailer->Subject = 'Informações sobre sua conta';
        $this->mailer->Body = $Template->parse();

        $this->mailer->send();
        return $this->mailer;
    }
    
}
