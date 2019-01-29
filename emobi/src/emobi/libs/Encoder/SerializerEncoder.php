<?php declare(strict_types=1);
namespace app\Core\Encoder;

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

class SerializerEncoder implements EncoderInterface
{
    protected $data = null;
	
	public $error;
    
    public function setData ($data) 
	{
        $this->data = $data;
        return $this;
    }

    public function encode () 
	{
		if (null === $this->data)
		{
			$this->error = "Não há nada para codificar.";
			return false;
		}
		
        return serialize($this->data);
    }
	
	public function decode ()
	{
		if (null === $this->data or !is_string($this->data))
		{
			$this->error = "Não há nada para decodificar.";
			return false;
		}
		
		return unserialize($this->data);
	}
	
}
