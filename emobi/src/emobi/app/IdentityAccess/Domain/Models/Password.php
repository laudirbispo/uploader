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
use app\IdentityAccess\Exceptions\PasswordException;
use app\Shared\Exceptions\DomainLogicException;

class Password
{
	const MIN_LENGTH = 8;
	const MAX_LENGTH = 32;
	
	private $string;
	
	public function __construct(string $password)
	{
		$this->assertNotEmpty($password);
		$this->assertNotSpace($password);
		$this->assertLength($password);
		$this->string = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
	}

	private function assertNotEmpty(string $password)
	{
		if (empty($password))
			throw new PasswordException('Uma senha é requerida.', PasswordException::EMPTY_ARGUMENT);
	}
	
	private function assertNotSpace(string $password)
	{
		if(preg_match('/\s/', $password))
			throw new PasswordException('Não são permitidos espaços em branco na senha.', PasswordException::INVALID_ARGUMENT);
	}
	
	private function assertLength(string $password)
	{
		$encoding = mb_detect_encoding($password);
		$pass_len = mb_strlen($password, $encoding);
		if ($pass_len < self::MIN_LENGTH)
			throw new PasswordException(
				'Password não pode conter menos de ' . self::MIN_LENGTH . ' caracteres.',
				PasswordException::MAX_LENGTH
			);
		if ($pass_len > self::MAX_LENGTH)
			throw new PasswordException(
				'Password não pode conter mais de ' . self::MAX_LENGTH . ' caracteres.',
				PasswordException::MIN_LENGTH
			);
	}
	
	public static function generate() : self
	{
        try {
            
            $characters = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*()_+";
            $pass = []; 
            $encoding = mb_detect_encoding($characters);
            $length = mb_strlen($characters, $encoding) - 1; 
            for ($i = 0; $i <= 8; $i++) 
            {
                $n = rand(0, $length);
                $pass[] = $characters[$n];
            }
            $password = implode($pass);
            return new self((string) $password);
            
        } catch(\Exception $e) {
            throw new DomainLogicException(
                'Falha ao gerar password - '.$e->getMessage(),
                DomainLogicException::UNEXPECTED_RESULT
            );
        }
		
	}
	
	public function get() : string
	{
		return (string) $this->string;
	}
	
	public function __toString()
	{
		return (string) $this->string;
	}
}