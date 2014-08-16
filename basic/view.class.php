<?php
namespace Root\Basic;

class View {
	private $pageVars = array();
	private $appView;

	public function __construct($appView){
		$this->appView = APP_DIR .'views/'. $appView .'.php';
	}

	public function set($var, $val){
		$this->pageVars[$var] = $val;
	}
	
	public function render(){
		extract($this->pageVars);
		ob_start();
		require($this->appView);
		return ob_get_clean();
	} 
}
