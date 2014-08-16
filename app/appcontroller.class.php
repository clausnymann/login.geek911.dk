<?php
namespace Root\App;

use Root\Basic\Controller;

class AppController extends Controller {
	protected $jsFiles = array();
	protected $cssFiles = array();
	protected $pageTitle;
	
	public function __construct(){
		//Add default css files
		$this -> cssFiles[] = '_css/default';
		
		//Add default javascript files
		$this -> jsFiles[] = 'auth/_js/auth';
	}
	
	public function addCssFile($cssFile) {
		$this -> cssFiles[] = $jsFile;
	}
	
	public function addJsFile($jsFile) {
		$this -> jsFiles[] = $cssFile;
	}
	
	public function setPageTitle($title) {
		global $pageTitle;
		$pageTitle = $title;
	}
	
	public function getHead(){
		global $pageTitle;
		$head = $this->loadView('head');
		//add jsFiles to head
		$jsfiles = '';
		foreach ($this -> jsFiles as $jsfile){
			$jsfiles .= '<script src="/app/views/'.$jsfile.'.js?v'.VERSION.'" type="text/javascript"></script>'.PHP_EOL;
		}
		$head->set('jsfiles', $jsfiles);
		//add cssFiles to head
		$cssfiles = '';
		foreach ($this -> cssFiles as $cssfile){
			$cssfiles .= '<link rel="stylesheet" href="/app/views/'.$cssfile.'.css?v'.VERSION.'" media="screen, print" />'.PHP_EOL;
		}
		$head->set('cssfiles', $cssfiles);
		//add pageTitle to head
		$head->set('pageTitle', $pageTitle);	
		return $head->render();
	}

	public function getUser(){
		if(isset($_SESSION['auth_access']) &&  $_SESSION['auth_access'] == true){ // user is logged in
			return (object)array(
						"auth_access"=>true,
						"user_id"=>$_SESSION['user_id'],
						"username"=>$_SESSION['username']);
		}else 
			return false;
	}
	
	
	public function getHeader(){
		$header = $this->loadView('header');
		$login_btns = $this->loadView('auth/login_btns_view');
		$user = $this->getUser();
		
		if($user != false){
			$login_btns->set('auth_access', $user->auth_access);
			$login_btns->set('user_id', $user->user_id);
			$login_btns->set('username', $user->username);		
		}
		$header->set('login_btns', $login_btns->render());
		return $header->render();
	}
	
	public function getFooter(){
		$footer = $this->loadView('footer');
		return $footer->render();
	}
}