<?php namespace Services\User;

use \Bundle, \Swift_Message, \Swift_SmtpTransport, \Swift_Mailer, \Swift_SwiftException, \Services\Client\AuthApp;


class ConfirmationEmailSender {
	
	public function send($user) {

		Bundle::start('swiftmailer');

		$auth_app = new AuthApp(null, null);
		$tokens = $auth_app->generate_tokens();

		$link = 'http://127.0.0.1/webservice/public/user/confirm?confirm_token='.$user['link'].'&uid='.$user['user']->id.'&atoken='.$tokens['atoken'].'&avalue='.$tokens['avalue'];

		$text = 'hello, this is a registration confirmation email.<br/>
				To confirm your account in tweestand, please click this link <a href="'.$link.'">confirm</a>.<br/><br/>
				If you do not know what it is about this email, please disregard it.';

		$body = $text;

		$message = Swift_Message::newInstance()
		->setSubject('Tweestand - registration confirmation')
		->setFrom(array('no-reply@tweestand.com' => 'No Reply'))
		->setTo(array($user['user']->email => $user['user']->name))
		->addPart('Confirmation link ', 'text/plain')
		->setBody($body, 'text/html');
		
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')->setUsername('')->setPassword('');
		$mailer = Swift_Mailer::newInstance($transport);
		
		if(!$mailer->send($message)) {

			throw new Swift_SwiftException('an error occurred while sending the email.');
			
		}

	}

	public function resend($email){

		$user = \DB::query('SELECT cl.link, u.id, u.email, u.name FROM users u, confirmation_links cl WHERE cl.user_id = u.id AND u.enable = 2 AND u.email = ? LIMIT 1', array($email));
 		$user = array('link' => $user[0]->link, 'user' => $user[0]);
 		$this->send($user);

	}

}

