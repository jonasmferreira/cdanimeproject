<?
/**
 * leitura
 * 
 * @package cdanimeproject
 * @author jonas
 * @copyright 2008
 * @version $Id$
 * @access public
 */
class leitura{
	protected $diretorios;
	protected $objeto;
	protected $lista;
	protected $extensao;
	/**
	 * leitura::__construct()
	 * 
	 * @return
	 */
	public function __construct(){
		unset($this->diretorios);
		unset($this->objeto);
	}
	/**
	 * leitura::__call()
	 * 
	 * @param mixed $metodo
	 * @param mixed $parametros
	 * @return
	 */
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

	/**
	 * leitura::diretorios()
	 * 
	 * @param string $diretorio
	 * @param string $ext
	 * @return
	 */
	public function diretorios($diretorio="", $ext="") {
		$ex = array();
		if($diretorio==""){
			$diretorio = $this->diretorios;
		}
		if($ext ==""){
			$ext = $this->extensao;
		}
		array_push($ex,$ext);
		$ext = strtoupper($ext);
		$resultado['d'] = $resultado['a'] = array();
	    if (is_dir($diretorio)) {
	        if ($dir = opendir($diretorio)) {
	            while(false !== ($arq = readdir($dir))) {
	                 if ($arq != '.' && $arq != '..') {
	                    if (is_dir($diretorio . $arq)) {
	                        $resultado['d'][strtolower($arq)] = $this->diretorios($diretorio . $arq . DIRECTORY_SEPARATOR,$ext);
	                    } else {
	                    	//if(strtoupper(pathinfo($arq, 4)) == $ext){
	                    	if (in_array(strtoupper(pathinfo($arq, 4)), $ex)) {
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
	/**
	 * leitura::mostrar()
	 * 
	 * @param mixed $array
	 * @return
	 */
	public function mostrar($array) {
		$mostrar = "";
	    foreach($array['d'] as $chave => $valor) {
	        //echo $chave . "<br />";
	         if (is_array($valor)) {
	              $this->mostrar($valor);
	         }
	    }
	    foreach($array['a'] as $chave => $valor) {
	         $mostrar .=$valor . "<br />";
	    }
	    echo  $mostrar;
	}
	
	/**
	 * leitura::listar_diretorio()
	 * 
	 * @return
	 */
	public function listar_diretorio(){
		$this->mostrar($this->diretorios());
	}
	
	
}

?>