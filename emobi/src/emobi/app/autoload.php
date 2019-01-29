<?php declare(strict_types=1);

/**
 * Autoload para classes próprias do sistema
 * @author - Laudir Bispo, laudirbispo@outlook.com
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

/**
 * My SPL autoloader.
 * @param string $classname - The name of the class to load
 */
function CustomAutoload ($classname)
{
	$classname = str_replace('\\', '/', $classname);
    //Can't use __DIR__ as it's only in PHP 5.3+
    $filename = BASE_DIR .'/'. $classname . '.php';
    if (is_readable($filename)) 
        require $filename;
}

if (version_compare(PHP_VERSION, '5.1.2', '>=')) 
{
    //SPL autoloading was introduced in PHP 5.1.2
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) 
        spl_autoload_register('CustomAutoload', true, true);
	else
        spl_autoload_register('CustomAutoload');

} 
else 
{
    /**
     * Fall back to traditional autoload for old PHP versions
     * @param string $classname The name of the class to load
     */
    function __autoload($classname)
    {
        CustomAutoload($classname);
    }
}
