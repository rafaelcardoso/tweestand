<?php #custom authentication driver

class AcessAuth extends Laravel\Auth\Drivers\Driver { 

    private $response_data = array();
    
    public function attempt($args = array()) {

    try {

        $validation = new Services\Client\ClientInputValidation($args);
        $validation->validate_secure_request();
        $user_auth = new Services\User\UserAuth();
        $user_auth->auth_keys($args);

    } catch(ValidateException $e) {
        
        $this->response_data = array(
            'status' => 'error',
            'messages' => array(
                'error' => array(
                    array(
                        'default' => 'invalid credentials.'
                    )
                )
            )
        );

        #para ver o erro detalhado: $e->get()
        

    } catch (AuthException $e) {
        
        $this->response_data = array(
            'status' => 'error',
            'messages' => array(
                'error' => array(
                    array(
                        'default' => $e->get() #'invalid credentials'
                    )
                )
            )
        );

    } catch (Exception $e) {

        $this->response_data = array(
            'status' => 'error',
            'messages' => array(
                'error' => array(
                    array(
                        'default' => 'could not complete the operation, wait a few minutes and try again.'
                    )
                )
            )
        );

    }

    return $this->response_data;
    
}
    
    public function retrieve($id) {

        return DB::query('SELECT token FROM users WHERE id = ? LIMIT 1', $id);

    } 

}