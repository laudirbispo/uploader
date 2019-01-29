<?php declare(strict_types=1);
namespace libs;
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
use laudirbispo\FileAndFolder\File;

class JavaScript 
{
    /**
     * The js files
     */
    private $files = [];
    
    /**
     * Scripts blocks
     */
    private $blocks = [];

    private $errors = [];
    
    
    public function addFile(string $src = '', string $attributes = '')
    {
        $File = new File($src);
        if (!$File->exists()) {
            $this->errors[] = sprintf('Arquivo %s não existe.', $src);
            return false;
        }
        
        if ($File->extension() != 'js') {
            $this->errors[] = sprintf('Arquivo %s é inválido.', $src);
            return false;
        }
        
        $script = '<script src="%s" %s></script>';
        $this->files[] = sprintf($script, $src, $attributes);
        return true;
        
    }
    
    public function addBlock(string $script) 
    {
        if (empty($script))
            return false;
        
        $this->blocks[] = $script;
        return true;
    }
    
    public function addAlert(string $message) 
    {
        $this->addBlock("<script>alert('{$message}');</script>");
    }
    
    public function jsonEncode(array $data = []) 
    {
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $array = array();
                $reflect = new \ReflectionObject($value);

                foreach ($reflect->getProperties() as $prop) {
                    $prop->setAccessible(true);
                    $array[$prop->getName()] =
                    $prop->getValue($value);
                }
                $data[$key] = $array;
            }
        }
        
        return json_encode($data);
    }
    
    public function jsonDecode(string $data) : array 
	{
		if (!self::isValidJson($data))
			throw new \Exception('Invalid Json data');
		else
			return json_decode($data, true);
	}
	
	public static function isValidJson ($data) : bool 
	{
    	json_decode($data);
    	return (json_last_error() === JSON_ERROR_NONE);
	}

    public function getFiles() : array 
    {
        return $this->files;
    }
    
    public function getBlocks() : array 
    {
        return $this->blocks;
    }
    
    public function hasErrors() : bool 
    {
        return (count($this->errors) > 0) ? true : false;
    }
    
    public function getErrors() : array 
    {
        return $this->errors;
    }
    
}