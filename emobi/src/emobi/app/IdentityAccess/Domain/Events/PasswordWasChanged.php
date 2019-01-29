<?php declare(strict_types=1);
namespace app\IdentityAccess\Domain\Events;
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
use libs\laudirbispo\CQRSES\Events\AbstractEvent;

final class PasswordWasChanged extends AbstractEvent 
{
    
    private $hashedPassword;
    
    public function __construct(
        string $executedBy, 
        string $aggregateId,  
        string $hashedPassword
    ){
        $this->eventDescription = sprintf("Senha alterada do usuário [%s]", $aggregateId);
        $this->executedBy = $executedBy;
        $this->aggregateId = $aggregateId; 
        $this->hashedPassword = $hashedPassword;
    }
    
    public function getHashedPassword() : string 
    {
        return $this->hashedPassword;
    }
    
}