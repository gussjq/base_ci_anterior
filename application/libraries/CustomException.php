<?php 

class CustomException extends Exception {
	protected $message = 'Unknown exception';
	private   $string;
	protected $code    = 0;
	protected $file;
	protected $line;
	private   $trace;  
	private $severity;

	public function __construct($severity, $message = null, $filepath = null, $line = 0, $code = 0)
	{
		if (!$message) {
			throw new $this('Unknown '. get_class($this));
		}
		parent::__construct($message, $code);
		$this->file = $filepath;
		$this->line = $line;
		$this->severity = $severity;
	}

	public function getSeverity(){
		return $this->severity;
	}

	public function __toString()
	{
		$texto = '"' . $this->message . '"';
		if ($this->file){
			$texto .= ' in ' . $this->file . ': ' . $this->line;
		}
		$texto .= '<br/>';
		$texto .= 'Stack trace: <br/>';
		$pila = $this->getTrace();
		foreach ($pila as $elem){
			//$texto .= print_r($elem);
			if (array_key_exists('file', $elem)){
				$texto .= $elem['file'] . ': ' ;
			}
			if (array_key_exists('class', $elem)){
				$texto .= $elem['class'] . '->' ;
			}
			if (array_key_exists('function', $elem)){
				$texto .= $elem['function'] . '()' ;
			}
			if (array_key_exists('line', $elem)){
				$texto .= '-> line: ' . $elem['line'];
			}
			$texto .= '<br/>';
		}
		
		return $texto;
	}

}
?>
