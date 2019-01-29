<?php declare(strict_types=1);
namespace app\Web\Helpers;
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

final class SwitcheryHelper 
{
    const INIT_VAR = 'switchery';
    
    /**
     * Switchery Api init
     *
     * @var string $selector = The HTML element id
     * @var array $options = The api settings
     * @var bool $multiples = Pressing one or more buttons
     */
    public static function init(string $selector = '.js-switch', bool $multiples = false, array $options = []) : string 
    {
        $defaultOptions = [
            'color'             => '#64bd63',
            'secondaryColor'    => '#dfdfdf',
            'jackColor'         => '#fff',
            'jackSecondaryColor' => 'null',
            'className'         => 'switchery',
            'disabled'         =>  'false',
            'disabledOpacity'   => '0.5',
            'speed'             => '0.1s',
            'size'             => 'default'
        ];
        
        $options = array_replace($defaultOptions, $options);
        
        $settings = "{
                color             : '%s',
                secondaryColor    : '%s',
                jackColor         : '%s',
                jackSecondaryColor:  %s,
                className         : '%s',
                disabled          :  %s,
                disabledOpacity   : '%s',
                speed             : '%s',
                size              : '%s'
            }";
        
        $settings = sprintf(
            $settings, 
            $options['color'], 
            $options['secondaryColor'], 
            $options['jackColor'], 
            $options['jackSecondaryColor'], 
            $options['className'], 
            $options['disabled'], 
            $options['disabledOpacity'], 
            $options['speed'], 
            $options['size']
        );
        
        if ($multiples) {
            
            $script = "<script>var elems = Array.prototype.slice.call(document.querySelectorAll('%s'));
                        elems.forEach(function(html) {
                          var %s = new Switchery(html, %s);
                       });</script>";
           
        } else {
            
            $script = "<script>var elem = document.querySelector('%s');
                     var %s = new Switchery(elem, %s);</script>"; 
        }
        
        return sprintf($script, $selector, self::INIT_VAR, $settings);
        
    }
    
    public static function changeState(string $selector, string $state = 'disabled') : string 
    {
        $script = "<script>var elem = document.querySelector('%s');
                     var %s = new Switchery(elem, {disabled: %s, disabledOpacity: 0.75});</script>"; 
        
        $state = ($state === 'disabled') ? 'true' : 'false';
        
        return sprintf($script, $selector, self::INIT_VAR, $state);
    }
    
}