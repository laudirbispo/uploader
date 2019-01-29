<?php declare(strict_types=1);
namespace app\Shared\Models;
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

final class MailConfiguration 
{
    private $host;
    
    private $user;
    
    private $password;
    
    private $port;
    
    private $smtpAuth;
    
    private $smtpSecure;
    
    private $isSmtp;
    
    private $isHtml;
    
    private $charset;
    
    public function __construct(
        string $host,
        string $user,
        string $password,
        int $port,
        bool $smtpAuth,
        string $smtpSecure,
        bool $isSmtp,
        bool $isHtml,
        string $charset
    ){
        
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
        $this->smtpAuth = $smtpAuth;
        $this->smtpSecure = $smtpSecure;
        $this->isSmtp = $isSmtp;
        $this->isHtml = $isHtml;
        $this->charset = $charset;
        
    }
    
    public function getHost() : string 
    {
        return $this->host;
    }
    
    public function getUser() : string 
    {
        return $this->user;
    }
    
    public function getPassword() : string 
    {
        return $this->password;
    }
    
    public function getPort() : int 
    {
        return $this->port;
    }
    
    public function getSmtpAuth() : bool 
    {
        return $this->smtpAuth;
    }
    
    public function getSmtpSecure() : string 
    {
        return $this->smtpSecure;
    }
    
    public function isSmtp() : bool 
    {
        return $this->isSmtp;
    }
    
    public function isHtml() : bool 
    {
        return $this->isHtml;
    }
    
    public function getCharset() : string 
    {
        return $this->charset;
    }
}
