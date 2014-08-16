<?php
namespace Root\App\Controllers;
use Root\Basic\View;
use Root\App\AppController;
 
class AuthController extends AppController{
	/*
	public function __construct(){
		$this->addJsFile('auth_extras');//
	}
	*/
	public function try_loginAction($ajax=false){
		if($ajax == true)
		header('Content-Type: application/json');
		
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		//validate
		if (!$this->checkPassword($password) or !$this->checkEmail($email))
		$form_report =  array('msg'=>'Login ikke korrekt.');
		
		if(isset($form_report)){
			if($ajax == true){
				echo json_encode($form_report);
			}else
				return $form_report;
		}else{
			$model = $this->loadModel('AuthModel');
			if($ajax==true){
				if($model->login($email, $password) === true){	
					echo json_encode(array('msg'=>'success'));
				}else
					echo json_encode(array('msg'=>'Login ikke korrekt.'));
			}else {
				if($model->login($email, $password) === true){	
					$this->redirect();//go to index.php
					exit;
				}else
					return array('msg'=>'Login ikke korrekt.');
			}
		}
	}
	
	public function loginAction(){
		$this->setPageTitle('Login');
		$view = $this->loadView('auth/login_view');
		if(isset($_POST['login'])){ // form submitted$form_report = $this->tryLogin();
			$form_report = $this->try_loginAction();
			$view->set('info', $form_report['msg']);
			foreach($_POST as $k=>$v) // insert old values in form in view
					$view->set($k, $v);
		}
		return $view->render();
		
    }
	
    public function logoutAction(){
		if($_SESSION['auth_access']==true){
			session_destroy();
			$this->redirect('auth/logout');
		}
		$this->setPageTitle('Logout');
		 $view = $this->loadView('auth/logout_view');
		return $view->render();
		
    }
	
	public function createPassword($length) {
		$chars = "1234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$i = 0;
		$password = "";
		while ($i <= $length) {
			$password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
		return $password;
	}
	
	private function checkPassword($pwd) {
		$re = '/
				# Match password with 5-20 chars with letters and digits
				^                # Anchor to start of string.
				(?=.*?[A-Za-z])  # Assert there is at least one letter, AND
				(?=.*?[0-9])     # Assert there is at least one digit, AND
				(?=.{5,20}\z)    # Assert the length is from 5 to 20 chars.
				/x';
		return (preg_match($re, $pwd))? true: false;
	}
	
	
	public function send_passwordAction($ajax=false){
		
		if($ajax == true)
		header('Content-Type: application/json');
		
		$email = trim($_POST['email']);
		$model = $this->loadModel('AuthModel');
		$account = $model->getAccount($email);
		if($account != false){
			$tmp_password = $this->createPassword(5);
			
			if($model->setTmpPassword($account['id'], $tmp_password) == true){
				$mail = $this->loadView('auth/mail_tmp_password');
				$mail->set('tmp_password', $tmp_password);
				$mail->set('login_link', BASE_URL .'auth/login');
				if($this->sendmail('Midlertidigt password fra login', $mail->render(), $account['email'], $account['name'])!=false){
					$msg = 'Der er sendt et password til din email.';
					if($ajax)
						echo json_encode(array('msg'=>$msg));
					else 
						return $msg;
				}
			}
		}else {
			$msg = 'Desværre! E-mailen kunne ikke findes.';
			if($ajax)
				echo json_encode(array('focus'=>'email','msg'=>$msg));
			else 
				return $msg;
			
		}
	}
	
	public function forget_passwordAction(){
		$this->setPageTitle('Glemt password');
        $view = $this->loadView('auth/forget_password_view');
		if(isset($_POST['send_password'])){
			$info = $this->send_passwordAction();
			$view->set('info',$info);
		}
		return $view->render();
    }
	
	public function activate_accountAction($actionParams){
		$this->setPageTitle('Aktiver konto');
        $activation_key = $actionParams[0];
		$model = $this->loadModel('AuthModel');
		$view = $this->loadView('auth/activate_account_view');
		if($model->activate_account($activation_key)==true){
			$view->set('info', 'Din konto er nu aktiveret og du kan logge ind <a href="/auth/login" class="loginBtn">her</a>');
		}else{
			$view->set('info', 'Kontoen eksistere ikke.');
		}
		return $view->render();
	}
	
	public function registerAction(){
		global $config;
		
		
		$this->setPageTitle('Opret konto');
		$view = $this->loadView('auth/register_view');
		$view->set('focus', 'name'); // default focus on input field "name" if no errors
		
		if(isset($_POST['register'])){ // form submitted
			$model = $this->loadModel('AuthModel');
			//validate password
			if (!$this->checkPassword($_POST['password']))
			$form_report =  array('msg'=>'Passwordet skal indeholde min. 5 anslag indeholdende både tal og bogstaver.', 'focus'=>'password');
			
			//validate email
			if (!$this->checkEmail($_POST['email']))
			$form_report = array('msg'=>'E-mailen er ikke korrekt.', 'focus'=>'email');
		
			//validate required fields
			$to_check_for_content = array('password'=>'en adgangskode', 'email'=>'din e-mail', 'name'=>'dit navn');
			foreach($to_check_for_content as $k=>$i){
				if (!$this->isMinLength($_POST[$k], 1))
					$form_report = array('msg'=>'Udfyld venligst '. $i, 'focus'=>$k);
			}
			
			if(isset($form_report)){
				foreach($_POST as $k=>$v) // insert old values in form in view
					$view->set($k, $v);
				$view->set('info', $form_report['msg']);
			}else{
				if(!$_SESSION['submitted']){ // check for dobbelt submit
					$activation_key = $model->register($_POST);
					$_SESSION['submitted'] = 1;
					if($activation_key != false){
						$mail = $this->loadView('auth/mail_activate_account');
						$mail->set('activation_link', $config['base_url'].'auth/activate_account/'.$activation_key);
						if($this->sendmail('Aktiveringsmail fra login', $mail->render(), $_POST['email'], $_POST['name'])!=false)//$subject, $body, $address, $name
							$view->set('info', 'Tak! Der er sendt et aktiveringslink til din e-mail.');
						else 
							$view->set('info', 'Fejl! Der er desværre ikke lykkedes at sende aktiveringsmailen.');
					}else {
						$view->set('info', 'Fejl! Der er desværre problemer med at oprette din profil.');
					}
				}else {
					$view->set('info', 'Fejl! Du kan ikke gensende form data.');
					unset($_SESSION['submitted']);
				}
			}
			
			if(isset($form_report['focus']))
			$view->set('focus', $form_report['focus']);
		}
		return $view->render();
    }

}
?>
