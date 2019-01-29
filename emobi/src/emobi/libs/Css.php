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

class Css 
{
    /**
     * The css files
     */
    private $files = [];
    
    /**
     * Styles blocks
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
        
        if ($File->extension() != 'css') {
            $this->errors[] = sprintf('Arquivo %s é inválido.', $src);
            return false;
        }
        $this->files[] = sprintf('<link href="%s" %s>', $src, trim($attributes));
        return true;
        
    }
    
    public function addStyle(string $style, bool $minimize = true) 
    {
        if (empty($style))
            return false;
        
        if ($minimize)
            $style = preg_replace('/\s+/', "", $style);
        
        $this->blocks[] = $style;
        return true;
    }
    
    public function getFiles() : array 
    {
        return $this->files;
    }
    
    public function getBlockStyles() : array 
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
