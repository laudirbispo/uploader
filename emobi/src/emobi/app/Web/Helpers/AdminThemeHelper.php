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

final class AdminThemeHelper
{
    
    public static function getLabelForUserRole(string $userRole = 'undefined') : string 
    {
        $wrap = '<span class="label %s">%s</span>';
        $colors = [
            'admin' => 'mdc-bg-purple-A200',
            'assistant' => 'mdc-bg-blue-800',
            'client' => 'mdc-bg-light-green-A200',
            'demo' => 'mdc-text-purple-500',
            'default' => 'mdc-bg-indigo-800'
        ];
        
        if (array_key_exists($userRole, $colors))
            return sprintf($wrap, $colors[$userRole], ucfirst($userRole));
        else
            return sprintf($wrap, $colors['default'], ucfirst($userRole));
    }
    
    public static function getUserRolesGroupScript() : string 
    {
        return "<script>
            $(function() {
            'use strict';
                var input = $('input[name=\"user-role\"]');
                $(input).change(enableGroupsInputs);

                function enableGroupsInputs ()
                {
                    $('input[name=\"user-groups[]\"]').prop(\"disabled\", true);
                    $('input[name=\"user-groups[]\"]').prop(\"checked\", false);
                    var \$this = $(this);
                    if (\$this.prop(\"checked\") === true)
                        \$this.closest('li').find('input[name=\"user-groups[]\"]').prop(\"disabled\", false);
                    else
                        \$this.closest('li').find('input[name=\"user-groups[]\"]').prop(\"disabled\", true);			
                }
            });
            </script>";
    }
    
    
}