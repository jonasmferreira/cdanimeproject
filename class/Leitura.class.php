<?
include('class/getid3/getid3.php');

// Needed for windows only
define('GETID3_HELPERAPPSDIR', 'C:/helperapps/');

// Initialize getID3 engine

/**
 * Leitura
 * 
 * @package cdanimeproject
 * @author jonas
 * @copyright 2008
 * @version $Id$
 * @access public
 */
class LeituraDiretorios{
	
	protected $objeto;
	protected $lista;
	public function __construct(){
		unset($this->diretorios);
		unset($this->objeto);
	}

	public function __call ($metodo, $parametros) {
		// se for set*, "seta" um valor para a propriedade
		if (substr($metodo, 0, 3) == 'set') {
			$var = substr(strtolower(preg_replace('/([a-z])([A-Z])/', "$1_$2", $metodo)), 4);
			$this->$var = $parametros[0];
		}
		// se for get*, retorna o valor da propriedade
		elseif (substr($metodo, 0, 3) == 'get') {
			$var = substr(strtolower(preg_replace('/([a-z])([A-Z])/', "$1_$2", $metodo)), 4);
			return $this->$var;
		}
	}


	public function diretorios($diretorio,$ext) {
		$ext = strtoupper($ext);
		$resultado['d'] = $resultado['a'] = array();
	    if (is_dir($diretorio)) {
	        if ($dir = opendir($diretorio)) {
	            while(false !== ($arq = readdir($dir))) {
	                 if ($arq != '.' && $arq != '..') {
	                    if (is_dir($diretorio . $arq)) {
	                        $resultado['d'][strtolower($arq)] = $this->diretorios($diretorio . $arq . DIRECTORY_SEPARATOR,$ext);
	                    } else {
	                    	if(strtoupper(pathinfo($arq, 4)) == $ext){
	                        	$resultado['a'][strtolower($arq)] = $diretorio.$arq;
	                    	}
	                    }
	                }
	            }
	        }
	    }
	    ksort($resultado['d']);
	    ksort($resultado['a']);
	    return $resultado;
	}
	public function mostra($array) {
		if(is_array($array)){
		    foreach($array['d'] as $chave => $valor) {
		         if (is_array($valor)) {
		              $this->mostra($valor);
		         }
		    }
		    foreach($array['a'] as $chave => $valor) {
		         echo $valor . "<br />";
		    }
		}
		
	}
	public function armazenarArray($array){
		if(is_array($array)){
		    foreach($array['d'] as $chave => $valor) {
		         if (is_array($valor)) {
		              $this->armazenarArray($valor);
		         }
		    }
		    foreach($array['a'] as $chave => $valor) {
		         $this->lista[] =  $valor;
		    }
		}
	}
	public function analizar(){
		$arq = new getID3();
		$arq->setOption(array('encoding' => 'UTF-8'));
		$table = "<table border='1'>\n";
		$table .="<tr>\n";
		$table .="<td>Files</td>\n";
		$table .="<td>Audio</td>\n";
		$table .="<td>Codec de Audio</td>\n";
		$table .="<td>Video</td>\n";
		$table .="<td>Codec de Video</td>\n";
		$table .="<td>Tamanho(HH:MM:SS)</td>";
		$table .="</tr>\n";
		foreach($this->lista as $value){
			$teste = $arq->analyze($value);
			$table .="<tr>\n";
			$table .="<td>".$value."</td>\n";
			$table .="<td>".$teste["audio"]["dataformat"]."</td>\n";
			$table .="<td>".$teste["audio"]["codec"]."</td>\n";
			$table .="<td>".$teste["video"]["dataformat"]."</td>\n";
			$table .="<td>".$teste["video"]["codec"]."</td>\n";
			$table .="<td>".$teste["playtime_string"]."</td>";
			$table .="</tr>\n";
		}
		$table .="</tr>\n";
		$table .="</table>";
		echo $table;
	}
	
}

?>