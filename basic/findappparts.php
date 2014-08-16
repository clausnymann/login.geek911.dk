<?php
namespace Root; 

use Root\App\AppController;

function findAppParts() {
	
    // Set our defaults
     $controllerName = 'HomeController';
     $actionName = 'Index';
     $actionParams = array();
	 $error = '';
	 $content = '';
	
	//cleanup request and split into urlParams
	$requestUrl = 'http://'.$_SERVER['HTTP_HOST'].strtok($_SERVER['REQUEST_URI'], '?');
    $uri = str_replace('-','_', substr($requestUrl, strlen(BASE_URL)));
    $urlParams = (!empty($uri) ? explode('/', $uri) : '');

    if(isset($urlParams[0])) $controllerName = ucfirst(array_shift($urlParams)).'Controller';
    if(isset($urlParams[0])) $actionName = strtolower(array_shift($urlParams)).'Action';
    if(isset($urlParams[0])) $actionParams = $urlParams;
    
	$activeController = APP_DIR.'controllers/'.strtolower($controllerName).'.class.php';

    //Include app controller and call a action to get page content
    if(file_exists($activeController)){
		 
  		//include $activeController;
		$class = 'Root\App\Controllers\\'.$controllerName;
    	
		$controller = new $class;
		if(!method_exists($controller, $actionName)){
			$mcv_error = "Action <strong>".$actionName."</strong> not Defined in ".$controllerName;
	    }else {
			if(isset($actionParams))
				$content = $controller->$actionName($actionParams);
			else
				$content = $controller->$actionName();
		}
    }else{
   		$mcv_error = "Module <strong>".$controllerName."</strong> not Found";
	 }
	
	 //call appController on every page
	 $appController = new Appcontroller;
	 return (object) array(
	  'header' => $appController->getHeader(),
	  'head' =>  $appController->getHead(),
	  'content' => $content,
	  'footer' => $appController->getFooter(),
      'controllerName' => $controllerName,
	  'actionName' => $actionName,
	  'actionParams' => $actionParams,
	  'mcv_error' => $mcv_error
     );
}