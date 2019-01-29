<?php declare(strict_types=1);
namespace app\Core\Session;

/**
 * @author - Laudir Bispo, laudirbispo@outlook.com
 * @copyright - 2017/2018
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

class SessionFileHandler extends \SessionHandler
{
    /**
     * Encryption and authentication key
     * @var string
     */
    protected $key;

    public function __construct(string $saveDir)
    {
        if (!file_exists($saveDir))
            mkdir($saveDir, 0744, true);
        
        if (!extension_loaded('openssl')) {
            throw new \RuntimeException(sprintf(
                "Você precisa da extensão OpenSSL para usar %s",
                __CLASS__
            ));
        }
        if (! extension_loaded('mbstring')) {
            throw new \RuntimeException(sprintf(
                "Você precisa da extensão Multibytes para usar %s",
                __CLASS__
            ));
        }
		
    }

    /**
     * Open the session
     *
     * @param string $save_path
     * @param string $session_name
     * @return bool
     */
    public function open ($save_path, $session_name)
    {
        try {
            
            $this->key = $this->getKey('KEY_' . $session_name);
            return parent::open($save_path, $session_name);
            
        } catch (\Exception $e) {
            $e->getMessage('O manipulador de sessão não conseguiu abrir o arquivo. Verifique as permissões do diretório.');
        } 
        
    }

    /**
     * Read from session and decrypt
     *
     * @param string $id
     */
    public function read ($id)
    {
        try {
            
            $data = parent::read($id);
            return empty($data) ? '' : $this->decrypt($data, $this->key);
            
        } catch (\Exception $e) {
            $e->getMessage('Não foi possível ler o arquivo de sessão. Verifique as permissões do diretório ou a existência do mesmo.');
        } 
    }

    /**
     * Encrypt the data and write into the session
     *
     * @param string $id
     * @param string $data
     */
    public function write ($id, $data)
    {
        try {
            
            return parent::write($id, $this->encrypt($data, $this->key));
        
        } catch (\Exception $e) {
            $e->getMessage('Não foi possível escrever no arquivo de sessão. Verifique as permissões do diretório ou a existência do mesmo.');
        }
    }

    /**
     * Encrypt and authenticate
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    protected function encrypt ($data, $key)
    {
        try {
            
            $iv = random_bytes(16); // AES block size in CBC mode
            // Encryption
            $ciphertext = openssl_encrypt(
                $data,
                'AES-256-CBC',
                mb_substr($key, 0, 32, '8bit'),
                OPENSSL_RAW_DATA,
                $iv
            );
            // Authentication
            $hmac = hash_hmac(
                'SHA256',
                $iv . $ciphertext,
                mb_substr($key, 32, null, '8bit'),
                true
            );
            return $hmac . $iv . $ciphertext; 
            
        } catch(\Exception $e) {
            
            $e->getMessage();
        }
        
    }

    /**
     * Authenticate and decrypt
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    protected function decrypt ($data, $key)
    {
        try {
            
            $hmac       = mb_substr($data, 0, 32, '8bit');
            $iv         = mb_substr($data, 32, 16, '8bit');
            $ciphertext = mb_substr($data, 48, null, '8bit');
            // Authentication
            $hmacNew = hash_hmac(
                'SHA256',
                $iv . $ciphertext,
                mb_substr($key, 32, null, '8bit'),
                true
            );
            if (! hash_equals($hmac, $hmacNew)) {
                throw new \RuntimeException('Autenticação de sessão falhou');
            }
            // Decrypt
            return openssl_decrypt(
                $ciphertext,
                'AES-256-CBC',
                mb_substr($key, 0, 32, '8bit'),
                OPENSSL_RAW_DATA,
                $iv
            );
        
        } catch(\Exception $e) {
            
            $e->getMessage();
        }
        
    }

    /**
     * Get the encryption and authentication keys from cookie
     *
     * @param string $name
     * @return string
     */
    protected function getKey ($name)
    {
        try {
            
            if (empty($_COOKIE[$name])) {
                $key         = random_bytes(64); // 32 for encryption and 32 for authentication
                $cookieParam = session_get_cookie_params();
                $encKey      = base64_encode($key);
                setcookie(
                    $name,
                    $encKey,
                    // if session cookie lifetime > 0 then add to current time
                    // otherwise leave it as zero, honoring zero's special meaning
                    // expire at browser close.
                    ($cookieParam['lifetime'] > 0) ? time() + $cookieParam['lifetime'] : 0,
                    $cookieParam['path'],
                    $cookieParam['domain'],
                    $cookieParam['secure'],
                    $cookieParam['httponly']
                );
                $_COOKIE[$name] = $encKey;
            } else {
                $key = base64_decode($_COOKIE[$name]);
            }
            return $key;
            
        } catch(\Exception $e) {
            
            $e->getMessage();
        }
    
    }

}
