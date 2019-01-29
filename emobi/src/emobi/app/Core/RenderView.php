<?php declare(strict_types=1);
namespace app\Core;
/**
 *
 * @author - Laudir Bispo, laudirbispo@outlook.com
 *
 * @license - http://www.gnu.org/licenses/gpl.html GPL License
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * Este mecanismo permite manter o código html separado da lógica 
 * @version - 1.0
 * 
 * NOTA: 
 */

class RenderView 
{

    /**
     * Uma lista de variáveis de documentos existentes.
     * @var	array
     */
    protected $vars = array();

    /**
     * Um hash com vars e valores definidos pelo usuário.
     * @var	array
     */
    protected $values = array();

    /**
     * Um hash de variáveis de propriedades de objetos existentes no documento.
     * @var	array
     */
    private $properties = array();

    /**
     * Um hash das instâncias de objeto definidas pelo usuário.
     * @var	array
     */
    protected $instances = array();

    /**
     * Lista de modificadores usados
     * @var array
     */
    protected $modifiers = array();

    /**
     * Uma lista de todos os blocos reconhecidos automaticamente.
     * @var	array
     */
    private $blocks = array();

    /**
     * A list of all blocks that contains at least a "child" block.
     * @var	array
     */
    private $parents = array();

    /**
     * Lista de blocos analizados
     * @var	array
     */
    private $parsed = array();

    /**
     * Lista de blocos finais
     * @var array
     */
    private $finally = array();

    /**
     * Descreve o método de substituição para blocos.  Veja TRenderView::setFile()
     * method for more details.
     * @var	boolean
     */
    private $accurate;

    /**
     * Expressão regular para encontrar nomes de var e de blocos.
     * Somente caracteres alfanuméricos e o caractere de sublinhado são permitidos.
     *
     * @var		string
     */
    private static $REG_NAME = "([[:alnum:]]|_)+";
	
	public function __construct () {}

    /**
     * Quando o parâmetro $ accurate for true, os blocos serão substituídos perfeitamente
     * (No tempo de análise), por exemplo, removendo todos os caracteres \ t (tab), tornando o
     * minificado.
     *
     * @param     string $filename		
     * @param     booelan $accurate		
     */
    public function layout($filename, bool $accurate = false)
	{
        $this->accurate = $accurate;
        $this->loadfile(".", $filename);
    }

    /**
     * Coloque o conteúdo de $ filename na variável de modelo identificada por $ varname
     *
     * @param     string $varname		
     * @param     string $filename		
     */
    public function addFile($varname, $filename)
	{ 
		if (!$this->exists($varname))
			throw new \Exception("A variável {$varname} não existe no templante.");

        $this->loadfile($varname, $filename);
    }

    /**
     * Não use. Método de estabelecimento de propriedades
     *
     * @param	string	$varname	
     * @param	mixed	$value		
     */
    public function __set($varname, $value)
	{
        if(!$this->exists($varname)) 
			throw new \Exception("Variável $varname não existe.");
        $stringValue = $value;
        if(is_object($value)){
            $this->instances[$varname] = $value;
            if(!isset($this->properties[$varname])) 
				$this->properties[$varname] = array();
            if(method_exists($value, "__toString")) 
				$stringValue = $value->__toString();
            else 
				$stringValue = "Object";
        }
        $this->setValue($varname, $stringValue);
        return $value;
    }

    /**
     * Não use. Método getter de propriedades.
     *
     * @param	string	$varname	nome da var no templante
     */
    public function __get($varname)
	{
        if(isset($this->values["{".$varname."}"])) 
			return $this->values["{".$varname."}"];
        elseif (isset($this->instances[$varname])) 
			return $this->instances[$varname];
		
        throw new \Exception("Variável $varname não existe.");
    }

    /**
     * Checa se a variável existe no templante
     *
     * Esse método retorna verdadeiro se a variável existir no templante. Caso contrário, falso.
     *
     * @param	string	$varname	nome da var no templante
     */
    public function exists($varname)
	{
        return in_array($varname, $this->vars);
    }

    /**
     * Carrega o arquivo identificado por $filename
     *
     * O arquivo será carregado e o conteúdo do arquivo será atribuído como o
     * Valor da variável.
     * Além disso, este método invoca o RenderView::identifier () que identifica
     * Todos os blocos e variáveis automaticamente.
     *
     * @param     string $varname		Contém o nome de uma variável a ser carregada
     * @param     string $filename		Nome do arquivo a ser carregado
     *
     * @return    void
     */
    private function loadfile($varname, $filename) 
	{ 
		if (!file_exists($filename))
			throw new \Exception("Templante file $filename não foi encontrado.");
		
        // se o arquivo é PHP
        if($this->isPHP($filename)) {
            ob_start();
            require $filename;
            $str = ob_get_contents();
            ob_end_clean();
            $this->setValue($varname, $str);
        } else {
            // Reading file and hiding comments
            $str = preg_replace("/<!---.*?--->/smi", "", file_get_contents($filename));
            
			if (empty($str))
				throw new \Exception("Arquivo $filename está vazio.");
            
            $this->setValue($varname, $str);
            $blocks = $this->identify($str, $varname);
            $this->createBlocks($blocks);
        }
    }

    /**
     * Check if file is a .php
     */
    private function isPHP ($filename)
	{
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
        return in_array($ext, ['php', 'php3', 'php5', 'php7', 'cgi']);
    }

    /**
     * Identifique todos os blocos e variáveis automaticamente e retorna-os.
     *
     * Todas as variáveis e blocos já foram identificados no momento em que
     * Chamadas do usuário RenderView::setFile(). Esse método chama RenderView::identifierVars()
     * E RenderView::identifierBlocks() métodos para fazer o trabalho.
     *
     * @param     string	$content		
     * @param     string	$varname		
     *
     * @return    array		Uma matriz onde a chave é o nome do bloco e o valor é um
     *                      Array com os nomes dos blocos filhos.
     */
    private function identify(&$content, $varname)
	{
        $blocks = array();
        $queued_blocks = array();
        $this->identifyVars($content);
        $lines = explode("\n", $content);
        // Verificando HTML minificado
        if(1==sizeof($lines)) {
            $content = str_replace('-->', "-->\n", $content);
            $lines = explode("\n", $content);
        }
        foreach (explode("\n", $content) as $line) {
            if (strpos($line, "<!--")!==false) 
				$this->identifyBlocks($line, $varname, $queued_blocks, $blocks);
        }
        return $blocks;
    }

    /**
     * Identifique todos os blocos definidos pelo usuário automaticamente.
     *
     * @param     string $line				Contém uma linha do arquivo de conteúdo
     * @param     string $varname			Contém o identificador da variável do nome do arquivo
     * @param     string $queued_blocks		Contém uma lista dos blocos em fila atuais
     * @param     string $blocks			Contém uma lista de todos os blocos identificados no arquivo atual
     *
     * @return    void
     */
    private function identifyBlocks (&$line, $varname, &$queued_blocks, &$blocks)
	{
        $reg = "/<!--\s*BEGIN\s+(".self::$REG_NAME.")\s*-->/sm";
        preg_match($reg, $line, $m);
        if (1==preg_match($reg, $line, $m)) {
            if (0==sizeof($queued_blocks)) 
				$parent = $varname;
            else 
				$parent = end($queued_blocks);
            if (!isset($blocks[$parent]))
                $blocks[$parent] = array();
            
            $blocks[$parent][] = $m[1];
            $queued_blocks[] = $m[1];
        }
        $reg = "/<!--\s*END\s+(".self::$REG_NAME.")\s*-->/sm";
        if (1==preg_match($reg, $line)) 
			array_pop($queued_blocks);
    }

    /**
     * Identifica todas as vars definidas no documento
     *
     * @param     string $content	conteúdo do arquivo
     */
    private function identifyVars(&$content)
	{
        $r = preg_match_all("/{(".self::$REG_NAME.")((\-\>(".self::$REG_NAME."))*)?((\|.*?)*)?}/", $content, $m);
        if ($r) {
            for($i=0; $i<$r; $i++) {
                // Object var detected
                if($m[3][$i] && (!isset($this->properties[$m[1][$i]]) || !in_array($m[3][$i], $this->properties[$m[1][$i]])))
                    $this->properties[$m[1][$i]][] = $m[3][$i];
                
                // Modifiers detected
                if($m[7][$i] && (!isset($this->modifiers[$m[1][$i]]) || !in_array($m[7][$i], $this->modifiers[$m[1][$i].$m[3][$i]])))
                    $this->modifiers[$m[1][$i].$m[3][$i]][] = $m[1][$i].$m[3][$i].$m[7][$i];
                
                // Common variables
                if(!in_array($m[1][$i], $this->vars))
                    $this->vars[] = $m[1][$i];
                
            }
        }
    }

    /**
     * Crie todos os blocos identificados dados por RenderView::identifyBlocks().
     *
     * @param     array $blocks		Contém todos os nomes de blocos identificados
     * @return    void
     */
    private function createBlocks(&$blocks) 
	{
        $this->parents = array_merge($this->parents, $blocks);
        foreach($blocks as $parent => $block) {
            foreach($block as $chield) {
                if(in_array($chield, $this->blocks)) 
					throw new \UnexpectedValueException("Bloco duplicado: $chield");
                $this->blocks[] = $chield;
                $this->setBlock($parent, $chield);
            }
        }
    }

    /**
     * Uma variável $parent pode conter um bloco variável definido por:
     * &lt;!-- BEGIN $varname --&gt; content &lt;!-- END $varname --&gt;.
     *
     * Este método remove esse bloco de $parent e o substitui por uma variável
     * Referência chamada $block.
     * Os blocos podem ser aninhados.
     *
     * @param     string $parent	Contém o nome da variável pai
     * @param     string $block		Contém o nome do bloco a ser substituído
     * @return    void
     */
    private function setBlock($parent, $block) 
	{
        $name = $block.'_value';
        $str = $this->getVar($parent);
        if($this->accurate) {
            $str = str_replace("\r\n", "\n", $str);
            $reg = "/\t*<!--\s*BEGIN\s+$block\s+-->\n*(\s*.*?\n?)\t*<!--\s+END\s+$block\s*-->\n*((\s*.*?\n?)\t*<!--\s+FINALLY\s+$block\s*-->\n?)?/sm";
        }
        else $reg = "/<!--\s*BEGIN\s+$block\s+-->\s*(\s*.*?\s*)<!--\s+END\s+$block\s*-->\s*((\s*.*?\s*)<!--\s+FINALLY\s+$block\s*-->)?\s*/sm";
        if(1!==preg_match($reg, $str, $m)) 
			throw new \UnexpectedValueException("Bloco mal formado: $block");
        $this->setValue($name, '');
        $this->setValue($block, $m[1]);
        $this->setValue($parent, preg_replace($reg, "{".$name."}", $str));
        if(isset($m[3])) 
			$this->finally[$block] = $m[3];
    }

    /**
     * Método interno  setValue().
     *
     * A principal diferença entre este e o modelo ::__set() e este método:
     * O método não pode ser chamado pelo usuário, e pode ser chamado usando variáveis ou
     * Blocos como parâmetros.
     *
     * @param     string $varname	   contém o nome da var
     * @param     string $value        contém o novo valor da var
     * @return    void
     */
    protected function setValue($varname, $value) 
	{
        $this->values['{'.$varname.'}'] = $value;
    }

    /**
     * Retorno o valor da var identifilaca por $varname.
     *
     * @param     string	$varname	Ele nomeia a variável para obter o valor 
     * @return    string	            da variável passada como argumento
     */
    private function getVar($varname) 
	{
        return $this->values['{'.$varname.'}'];
    }

    /**
     * Limpa o valor da var
     *
     * Alias para $this->setValue($varname, "");
     *
     * @param     string $varname	var a ser limpa
     * @return    void
     */
    public function clear($varname) 
	{
        $this->setValue($varname, "");
    }

    /**
     * Atribuir manualmente um bloco filho para um bloco pai
     *
     * @param string $parent	blovo pai
     * @param string $block		bloco filho
     */
    public function setParent($parent, $block)
	{
        $this->parents[$parent][] = $block;
    }

    /**
     * Substituir modificadores conteúdo
     *
     * @param	string	$value	texto a ser modificado
     * @param $exp
     * @return unknown_type
     */
    private function substModifiers($value, $exp)
	{
        $statements = explode('|', $exp);
        for($i=1; $i<sizeof($statements); $i++) {
            $temp = explode(":", $statements[$i]);
            $function = $temp[0];
            $parameters = array_diff($temp, array($function));
            $value = call_user_func_array($function, array_merge(array($value), $parameters));
        }
        return $value;
    }

    /**
     * Preencha todas as variáveis contidas na variável chamada $value.
     * $value. A sequência resultante ainda não está "limpa".
     *
     * @param     string 	$value		var value
     * @return    string	content with all variables substituted.
     */
    protected function subst($value) 
	{
        // Substituição das variáveis comuns
        $s = str_replace(array_keys($this->values), $this->values, $value);
        // Variáveis comuns com modificadores
        foreach($this->modifiers as $var => $expressions) {
            if(false!==strpos($s, "{".$var."|")) foreach($expressions as $exp) {
                if(false===strpos($var, "->") && isset($this->values['{'.$var.'}']))
                    $s = str_replace('{'.$exp.'}', $this->substModifiers($this->values['{'.$var.'}'], $exp), $s);
            }
        }
        // Substituição de variáveis de objeto
        foreach($this->instances as $var => $instance) {
            foreach($this->properties[$var] as $properties) {
                if(false!==strpos($s, "{".$var.$properties."}") || false!==strpos($s, "{".$var.$properties."|")) {
                    $pointer = $instance;
                    $property = explode("->", $properties);
                    for($i = 1; $i < sizeof($property); $i++) {
                        if(!is_null($pointer)) {
                            $obj = strtolower(str_replace('_', '', $property[$i]));
                            // Obter accessor
                            if(method_exists($pointer, "get$obj")) 
								$pointer = $pointer->{"get$obj"}();
                            // Magic __get accessor
                            elseif (method_exists($pointer, "__get")) 
								$pointer = $pointer->__get($property[$i]);
                            // Avaliador de propriedades
                            elseif (property_exists($pointer, $obj)) 
								$pointer = $pointer->$obj;
                            else 
							{
                                $className = $property[$i-1] ? $property[$i-1] : get_class($instance);
                                $class = is_null($pointer) ? "NULL" : get_class($pointer);
                                throw new \BadMethodCallException("Nenhum método de acesso na classe ".$class." para ".$className."->".$property[$i]);
                            }
                        } else {
                            $pointer = $instance->get($obj);
                        }
                    }
                    // Verificando se o valor final é um objeto ...
                    if(is_object($pointer)) {
                        $pointer = method_exists($pointer, "__toString") ? $pointer->__toString() : "Object";
                    // ... ou uma matriz
                    } 
					elseif(is_array($pointer)) {
                        $value = "";
                        for($i=0; list($key, $val) = each($pointer); $i++) {
                            $value.= "$key => $val";
                            if($i<sizeof($pointer)-1) $value.= ",";
                        }
                        $pointer = $value;
                    }
                    // Substituindo o valor
                    $s = str_replace("{".$var.$properties."}", $pointer, $s);
                    // Objeto com modificadores
                    if(isset($this->modifiers[$var.$properties])) {
                        foreach($this->modifiers[$var.$properties] as $exp) {
                            $s = str_replace('{'.$exp.'}', $this->substModifiers($pointer, $exp), $s);
                        }
                    }
                }
            }
        }
        return $s;
    }

    /**
     * Show a block.
     *
     * Este método deve ser chamado quando um bloco deve ser mostrado.
     * Caso contrário, o bloco não aparecerá na resultante
     * Conteúdo.
     *
     * @param     string $block     O nome do bloco a ser analisado
     * @param     boolean $append   true Se o conteúdo deve ser anexado
     */
    public function block($block, $append = true) 
	{
        if(!in_array($block, $this->blocks)) throw new \InvalidArgumentException("O bloco $block não existe.");
        // Verificando finalmente blocos dentro deste bloco
        if(isset($this->parents[$block])) foreach($this->parents[$block] as $child) {
            if(isset($this->finally[$child]) && !in_array($child, $this->parsed)) {
                $this->setValue($child.'_value', $this->subst($this->finally[$child]));
                $this->parsed[] = $block;
            }
        }
        if ($append) {
            $this->setValue($block.'_value', $this->getVar($block.'_value') . $this->subst($this->getVar($block)));
        } else {
            $this->setValue($block.'_value', $this->getVar($block.'_value'));
        }
        if(!in_array($block, $this->parsed)) $this->parsed[] = $block;
        // Limpeza de filhos
        if(isset($this->parents[$block])) foreach($this->parents[$block] as $child) $this->clear($child.'_value');
    }

    /**
    * Retorna o conteúdo final
    *
    * @return    string
    */
    public function parse() 
	{
        // Assistência automática para bloqueios 
        foreach(array_reverse($this->parents) as $parent => $children) {
            foreach($children as $block) {
                if(in_array($parent, $this->blocks) && in_array($block, $this->parsed) && !in_array($parent, $this->parsed)){
                    $this->setValue($parent.'_value', $this->subst($this->getVar($parent)));
                    $this->parsed[] = $parent;
                }
            }
        }
        // Parsing blocos finais
        foreach($this->finally as $block => $content) {
            if(!in_array($block, $this->parsed)) {
                $this->setValue($block.'_value', $this->subst($content));
            }
        }
        // Após substituir, remove vars vazio
        return preg_replace("/{(".self::$REG_NAME.")((\-\>(".self::$REG_NAME."))*)?((\|.*?)*)?}/", "", $this->subst($this->getVar(".")));
    }

    /**
     * Imprimi o conteúdo final.
     */
    public function show() 
	{
        echo $this->parse();
    }

}

