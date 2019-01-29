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

class Base64Encoder implements EncoderInterface
{
	
	protected $data = null;
	
	public function setData ($data)
	{
		if (!is_string($data))
			throw new \InvalidArgumentException('Base64Encoder necessita de uma string como parâmetro.');
		$this->data = $data;
		return $this;
	}
	
	public function encode ()
	{
		if (null === $this->data)
			return null;
		else
			return base64_encode($this->data);
	}
	
	public function decode ()
	{
		if (null === $this->data)
			return null;
		else
			return base64_decode($this->data);
	}
	
	/**
	 * Decode string with strict and recode it with base64_encode, 
	 * you can compare the result with the original data to determine 
	 * if it is a valid base64 encoded value:
	 */
	public function isValid () : bool
	{
		if (base64_encode(base64_decode($this->data, true)) === $this->data)
			return true;
		else
			return false;
	}
	
}