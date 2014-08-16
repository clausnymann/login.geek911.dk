<?php
namespace Root\App\Controllers;

use Root\Basic;
use Root\App\AppController;

class HomeController extends AppController{
	
	public function __construct(){
		$this->setPageTitle('Hjemmside med login');
	}
	
	public function initAction(){
		$home = $this->loadView('home/home');
		return $home->render();
	}
	
}