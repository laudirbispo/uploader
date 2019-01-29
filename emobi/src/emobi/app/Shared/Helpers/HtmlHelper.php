<?php declare(strict_types=1);
namespace app\Shared\Helpers;
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

class HtmlHelper
{
	public static function anchor (string $url = '', array $attributes = [], string $label = 'clique aqui')
	{
		ksort($attributes);
		$anchor = '<a href="'.$url.'" ';
		foreach ($attributes as $key => $value)
		{
			$anchor .= $key.'="'.$value.'" ';
		}
		$anchor .= '>'.$label.' </a>';
		return $anchor;
	}
	
	public static function generateSelectList (array $options = [], array $attributes = [], string $selected)
	{
		ksort($attributes);
		ksort($options);
		$list = '<ul ';
		foreach ($attributes as $key => $value)
		{
			$list .= $key.'="'.$value.'" ';
		}
		$list .= '>';
		foreach ($options as $value => $name) 
		{
			$optionSelected = ($selected == $value) ? 'selected' : '';
			$list .= '<option value="'.$value.'" '.$optionSelected.' >'.$name.'</option>';
		}
		$list .= '</ul>';
		return $list;
	}
	
	public static function generateOptionList (array $options = [], string $selected)
	{
		ksort($options);
		$list = '<ul>';
		foreach ($options as $value => $name) 
		{
			$optionSelected = ($selected == $value) ? 'selected' : '';
			$list .= '<option value="'.$value.'" '.$optionSelected.' >'.$name.'</option>';
		}
		$list .= '</ul>';
		return $list;
	}
    
    public static function label(string $type = 'notice', string $title) : string 
    {
        return sprintf('<span class="label label-%s">%s</span>', $type, $title);
    }
	
}
