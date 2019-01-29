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

class JsonEncoder implements EncoderInterface
{
    protected $data;
    
    public function setData($data) 
	{
        foreach ($data as $key => $value) 
		{
            if (is_object($value)) 
			{
                $array = array();
                $reflect = new \ReflectionObject($value);

                foreach ($reflect->getProperties() as $prop) 
				{
                    $prop->setAccessible(true);
                    $array[$prop->getName()] =
                    $prop->getValue($value);
                }
                $data[$key] = $array;
            }
        }
        
        $this->data = $data;
        return $this;
    }
    
    public function encode () 
	{
        return array_map("json_encode", $this->data);
    }
	
	public function decode ()
	{
		if (!$this->isValidJson())
			return false;
		else
			return json_decode($this->data, true);
	}
	
	function isValidJson () 
	{
    	json_decode($this->data);
    	return (json_last_error() === JSON_ERROR_NONE);
	}
	
}
