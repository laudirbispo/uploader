<?php declare(strict_types=1);
namespace libs\laudirbispo\CQRSES\Validation;
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

use InvalidArgumentException;

trait AssertionValidationTrait
{
    
    protected function assertArgumentEquals($Object1, $Object2, $message)
    {
        if ($Object1 != $Object2) 
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentFalse($Boolean, $message)
    {
        if ($Boolean) 
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentGreaterThan($string, int $maximum, $message)
    {
        $length = strlen(trim($string));

        if ($length > $maximum)
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentBetween($string, $minimum, $maximum, $message)
    {
        $length = strlen(trim($string));

        if ($length < $minimum || $length > $maximum) 
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentNotEmpty($value, $message)
    {
        if (null === $value || empty($value)) 
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentNotEquals($Object1, $Object2, $message)
    {
        if ($Object1 == $Object2)
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentNotNull($Object, $message)
    {
        if (null === $Object)
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentRange($value, $minimum, $maximum, $message)
    {
        if ($value < $minimum || $value > $maximum)
            throw new InvalidArgumentException($message);
    }

    protected function assertArgumentTrue($boolean, $message)
    {
        if (!$boolean)
            throw new InvalidArgumentException($message);
    }

    protected function assertStateFalse($boolean, $message)
    {
        $this->assertArgumentFalse($boolean, $message);
    }

    protected function assertStateTrue($boolean, $message)
    {
        $this->assertArgumentTrue($boolean, $message);
    }

    protected function assertThatTheArgumentLengthIsLessThan($string, int $minimum, $message)
    {
        $length = count(trim($string));
        if ($length < $minimum)
            throw new InvalidArgumentException($message);
    }
    
    protected function assertThatTheArgumentLengthIsLongerThan($string, int $maximum, $message)
    {
        $length = count(trim($string));
        if ($length > $maximum)
            throw new InvalidArgumentException($message);
    }
    
    protected function assertThatTheArgumentHasTheExactLength($string, int $length, $message) 
    {
        $stringLength = count(trim($string));
        if ($stringLength !== $length)
            throw new InvalidArgumentException($message);
    }

    protected function assertThatTheArgumentIsAnEmailAddress($emailAddress, $message)
    {
        if (false === filter_var($emailAddress, FILTER_VALIDATE_EMAIL))
            throw new InvalidArgumentException($message);
    }
    
}