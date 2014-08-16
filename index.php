<?php
namespace Root;



session_start(); 


// Define base URL

define("VERSION", 1); // Version to reset cached files
define('ROOT_DIR', realpath(dirname(__FILE__)));
define('DS', '/'); // dir splitter
define('APP_DIR', ROOT_DIR .'/app/');
require(APP_DIR .'config.php');
global $config;

define('BASE_URL', $config['base_url']);
spl_autoload_extensions('.class.php'); 
spl_autoload_register('Root\loadClasses'); 
function loadClasses($className) { 
    
	if( class_exists( $className, false ))
    return true;
	
	$classparts = explode( '\\', $className );
	$classfile = strtolower(array_pop( $classparts ));
  	$namespace = implode( '\\', $classparts );
	//echo 'Namespace:'.$namespace.'<br>';
	//echo 'classFile:'.$classfile.'<br>';
	
	$dirs_to_autoload = array(str_tolower(str_replace('Root','',$namespace))), 'basic', 'app', 'app/controllers', 'app/models', 'app/views');
   
	foreach ($dirs_to_autoload as $dir){
		//echo ROOT_DIR.DS.$dir.DS.$classfile.'.php<br>';
		if( file_exists(ROOT_DIR.DS.$dir.DS.$classfile.'.class.php') ){ 
			//echo 'dir:'.ROOT_DIR.DS.$dir.DS.'<br>';
			//echo 'classfile:'.$classfile.'<br>';
			//echo '---------<br>';
			set_include_path(ROOT_DIR.DS.$dir.DS); 
			spl_autoload($classfile);
			
			break;
		} 
	};
}

use Root\Basic;
use Root\App\AppController;

require(ROOT_DIR .'/basic/findappparts.php'); // function to split up urls to find app parts/classes



//error_reporting(E_ALL);
//error_reporting( error_reporting() & ~E_NOTICE );
$appParts = findAppParts();

//if ists a ajax url request's, then only return app content
if(isset($_REQUEST['ajax'])){
	echo $appParts->content;
	//print_r($appParts);
	exit;	
}
?>
<!DOCTYPE html>
<head>
  <? echo $appParts->head; ?>
</head>
<body>
<? 
  	$info  = '<strong>MVC info:</strong><br />';
	$info  .= 'AppController extended by: '.$appParts->controllerName.'<br>';
	if(!empty($appParts->actionName)) $info .= 'ActionName: '.$appParts->actionName.'<br>';
	if(!empty($appParts->actionParams)) $info .= 'ActionParams: '.implode(', ', $appParts->actionParams).'<br>';	
	if(!empty($appParts->mvc_error)) $info .= 'Error: '.$appParts->mvc_error;
	echo '<div class="mvc_info">'.$info.'</div>';	
  ?>
  <? echo $appParts->header; ?>
  <? echo $appParts->content; ?>
  <? echo $appParts->footer; ?>
  

  

</body>
</html>
