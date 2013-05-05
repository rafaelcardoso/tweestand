<?php namespace Repositories\User;

use \DB, \User, \ConfirmationLink, \Input, \Hash, \Exception;

class UserRepositorie {
	
	public function save() {

		$link = substr(Hash::make(uniqid(rand(), true)), -53);

		DB::transaction(function() use ($link) {

			$id = DB::table('users')->insert_get_id(
				array(
					'role_id'  => 1,
					'enable'   => 2, #2 = flag de espera pelo click no link do email, se não clicar em 24 horas a conta é deletada
					'name'     => Input::get('name'),
					'username' => Input::get('username'),
		    		'email'    => Input::get('email'),
		    		'password' => Hash::make(Input::get('password')),
		    		'token'	   => substr(Hash::make(uniqid(rand(), true)), -53),
		    		'created_at' => date('Y-m-d H:i:s'),
		    		'updated_at' => date('Y-m-d H:i:s')
				)
			);

			$confirmation_link = ConfirmationLink::create (
				array(
					'user_id' => $id,
					'link'    => $link
				)
			);

		});

		$user = User::where('username', '=', Input::get('username'))->first();
		
		return array('user' => $user, 'link' => $link);

	}

	public function confirm($keys) {

		DB::transaction(function() use ($keys) {

			$affected = DB::query('DELETE FROM confirmation_links WHERE user_id = ? AND link = ?',
			array($keys['uid'], $keys['confirm_token']));

			if($affected) {

				DB::query('UPDATE users set enable = 1 WHERE id = ?',
				array($keys['uid']));

			} else {

				throw new Exception('invalid link', 666);

			}

		});

	}
	
}