<?php

    use \Firebase\JWT\JWT;
    require_once('JWT.php');
    require_once('login.php');
    require_once 'vendor/autoload.php';
    
class userAuth {
    private $password;
    private $username;
    private $key = "secretSignKey";


    // Checks if the user exists in the database
    private function validUser($usernameInput, $passwordInput) {
        $connection = new PDO("mysql:host=localhost;dbname=macroeat",'justine','admin');

        $sql = $connection->query("SELECT username, password FROM login WHERE username = '$usernameInput' && `password`= '$passwordInput';");
        while ($result = $sql->fetch()){
            $data = array('username' => $result['username'],
                          'password' => $result['password']);
        }

        $this->username = $usernameInput;
        $this->password = $passwordInput;

        if($data['username'] == $this->username && $data['password'] == $this->password){
            return true;
        }
        else{
            return false;
        }
    }

    // Generates and signs a JWT for User
    private function genJWT() {
        $payload = array(
            "username" => $this->username,
            "exp" => time() + (60 * 60)
        );
        return JWT::encode($payload, $this->key);
    }


    // Validates a given JWT from the user email
    private function validJWT($token) {
        $res = array(false, '');
        try {
            $decoded = JWT::decode($token, $this->key, array('HS256'));
        } 
        catch (Exception $e) {
            return $res;
        }

        $res['0'] = true;
        $res['1'] = (array) $decoded;
        return $res;
    }


     // sends signed token in email to user if the user exists
    public function mailUser($username, $password) {
        // check if the user exists
        if ($this->validUser($username, $password)) {
                $token = $this->genJWT();
                return $token;
        } 
        else{
            return 'Wrong Email/Password';
        }
    }

 
    public function validMail($token) {
        // checks if an email is valid
        $tokenVal = $this->validJWT($token);
    
        if ($tokenVal['0']) {
            return true;
        } 
        else{
            return false;
        }
    }

}

?>