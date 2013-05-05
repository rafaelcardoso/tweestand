<?php namespace Services\User;

use \Hash, \AuthException, \Session;

class UserAuth {
	
	public function auth($username, $password) {

        $user = \DB::query('SELECT id, password, token, name, username, email FROM users WHERE BINARY username = ? AND enable = 1 LIMIT 1', $username);

        if ((empty($user)) || (!Hash::check($password, $user[0]->password))) {
            
            throw new \AuthException('username or password invalid');

        } else {

            $twitter_accounts = \DB::query('UPDATE users SET updated_at = "'.date('Y-m-d H:i:s').'" WHERE id = '.$user[0]->id);            

            $twitter_accounts = \DB::query('SELECT id, identification, enable, oauth_token, oauth_token_secret FROM twitter_accounts WHERE user_id = ?', $user[0]->id);
            
            foreach ($twitter_accounts as $account) {
                $account->followers = array();
                if($account->enable == 1){
                    $account->followers = \DB::query('SELECT id FROM current_followers WHERE twitter_account_id = ?', $account->id);
                }
            }

            return array(
                'name' => $user[0]->name,
                'username' => $user[0]->username,
                'email' => $user[0]->email,
                'keys' => array(
                    'uid' => $user[0]->id,
                    'utoken' => $user[0]->token
                ),
                'twitter_accounts' => $twitter_accounts
            );

        }

	}

    public function auth_keys($args = array()) {

        $user = \User::find($args['uid']);

        if ((is_null($user)) || ($user->token !== $args['utoken'])) {

            throw new AuthException('invalid credentials');

        } else {
            
            Session::put('user', $user);

        }
    }

}