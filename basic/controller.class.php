<?
namespace Root\Basic;

class Controller {
	
	public function loadModel($name){
		require(APP_DIR .'models/'. $name .'.php');
		$model = new $name;
		return $model;
	}
	
	public function loadView($name){
		$view = new View($name);
		return $view;
	}
	
	public function redirect($loc=''){
		global $config;
		header('Location: '. $config['base_url'] . $loc);
	}

	public function checkEmail($email){
		return (preg_match("/[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}/", $email))? true: false;
	}

	public function isMinLength($val, $min){
	  return (strlen($val) >= (int)$min)? true: false;
	}

	public function sendmail($subject, $body, $address, $name){
		global $config;
		require(APP_DIR .'_plugins/PHPMailer_5.2.0/class.phpmailer.php');
		$mail = new PHPMailer();
		$body = eregi_replace("[\]",'',$body);
		
		if($config['mail_isSMTP'] == true)
		$mail->IsSMTP(); // telling the class to use SMTP
		
		$mail->CharSet = $config['mail_charset'];
		$mail->Host = $config['mail_host'];
		
		if(isset($config['mail_SMTPDebug']))
		$mail->SMTPDebug  = $config['mail_SMTPDebug']; 
		 
		$mail->WordWrap = $config['mail_wordwrap'];                   
		$mail->SetFrom($config['mail_from_mail'], $config['mail_from_name']);
		$mail->AddReplyTo($config['mail_from_mail'],  $config['mail_from_name']);
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		
		$mail->AddAddress($address, $name);

		if(!$mail->Send()) {
		  return false;
		} else {
		  return true;
		}

	}
}

?>